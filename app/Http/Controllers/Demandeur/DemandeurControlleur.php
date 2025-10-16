<?php

namespace App\Http\Controllers\Demandeur;

use App\Http\Controllers\Controller;
use App\Models\Demandeur;
use App\Models\ResetCodePassword;
use App\Models\User;
use App\Notifications\SendEmailToDemandeurAfterRegistrationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class DemandeurControlleur extends Controller
{

    public function index()
    {
        $query = Demandeur::query();
        
        // Recherche
        if (request()->has('search') && request('search') != '') {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('prenom', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('contact', 'like', "%{$search}%")
                ->orWhere('adresse', 'like', "%{$search}%")
                ->orWhere('fonction', 'like', "%{$search}%")
                ->orWhere('structure', 'like', "%{$search}%");
            });
        }
        
        // Filtre par statut
        if (request()->has('status') && request('status') != '') {
            if (request('status') == 'active') {
                $query->whereNull('archived_at');
            } elseif (request('status') == 'archived') {
                $query->whereNotNull('archived_at');
            }
        }
        
        // Tri par date de création (plus récent en premier)
        $query->orderBy('created_at', 'desc');
        
        $demandeurs = $query->paginate(10);
        
        return view('admin.demandeur.index', compact('demandeurs'));
    }
    public function create(){
        return view('admin.demandeur.create');
    }

   public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'contact' => 'required|string|max:20',
            'adresse' => 'required|string|max:255',
            'fonction' => 'required|string|max:255',
            'structure' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        try {
            Log::info('Début de création du demandeur', ['email' => $request->email]);
            
            $demandeur = new Demandeur();
            $demandeur->name = $request->name;
            $demandeur->prenom = $request->prenom;
            $demandeur->email = $request->email;
            $demandeur->contact = $request->contact;
            $demandeur->structure = $request->structure;
            $demandeur->adresse = $request->adresse;
            $demandeur->fonction = $request->fonction;
            $demandeur->password = Hash::make('default');
            
            if ($request->hasFile('profile_picture')) {
                $demandeur->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
            }
            
            $demandeur->save();
            Log::info('Demandeur créé avec succès', ['id' => $demandeur->id, 'email' => $demandeur->email]);

            // Envoi de l'e-mail de vérification
            Log::info('Début envoi email');
            ResetCodePassword::where('email', $demandeur->email)->delete();
            $code1 = rand(1000, 4000);
            $code = $code1 . '' . $demandeur->id;

            ResetCodePassword::create([
                'code' => $code,
                'email' => $demandeur->email,
            ]);
            Log::info('Code de reset créé', ['code' => $code]);

            // Notification
            Notification::route('mail', $demandeur->email)
                ->notify(new SendEmailToDemandeurAfterRegistrationNotification($code, $demandeur->email));
            Log::info('Notification envoyée');

            return redirect()->route('demandeur.index')->with('success', 'Demandeur a bien été enregistré avec succès.');
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du demandeur', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $demandeur = Demandeur::findOrFail($id);
        return response()->json($demandeur);
    }

    public function edit(Demandeur $demandeur)
    {
        return view('admin.demandeur.edit', compact('demandeur'));
    }

    public function update(Request $request, Demandeur $demandeur)
    {
        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:demandeurs,email,' . $demandeur->id,
            'contact' => 'required|string|max:20',
            'structure' => 'required|string|max:255',
            'adresse' => 'required|string|max:500',
            'fonction' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Gestion de la photo de profil
            if ($request->hasFile('profile_picture')) {
                // Supprimer l'ancienne photo si elle existe
                if ($demandeur->profile_picture && Storage::exists($demandeur->profile_picture)) {
                    Storage::delete($demandeur->profile_picture);
                }
                
                // Stocker la nouvelle photo
                $validated['profile_picture'] = $request->file('profile_picture')->store('demandeurs/profile_pictures', 'public');
            } else {
                // Garder l'ancienne photo si aucune nouvelle n'est uploadée
                $validated['profile_picture'] = $demandeur->profile_picture;
            }

            // Mise à jour du demandeur
            $demandeur->update($validated);

            return redirect()->route('demandeur.index')
                ->with('success', 'Demandeur modifié avec succès!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la modification: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function toggleArchive(Demandeur $demandeur)
    {
        try {
            if ($demandeur->archived_at) {
                // Désarchiver
                $demandeur->update(['archived_at' => null]);
                $message = 'Demandeur désarchivé avec succès!';
            } else {
                // Archiver
                $demandeur->update(['archived_at' => now()]);
                $message = 'Demandeur archivé avec succès!';
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }
}
