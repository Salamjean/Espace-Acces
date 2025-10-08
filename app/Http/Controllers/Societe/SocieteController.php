<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocieteRequest;
use App\Models\ResetCodePasswordSociete;
use App\Models\Societe;
use App\Notifications\SendEmailToSocieteAfterRegistrationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class SocieteController extends Controller
{
    public function index()
    {
        $query = Societe::query();
        
        // Recherche
        if (request()->has('search') && request('search') != '') {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('contact', 'like', "%{$search}%")
                ->orWhere('adresse', 'like', "%{$search}%");
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
        
        $societes = $query->paginate(10);
        
        return view('admin.societe.index', compact('societes'));
    }

    public function create(){
        return view('admin.societe.create');
    }

     public function store(SocieteRequest $request){

       try {
           $societe = new Societe();
           $societe->name = $request->name;
           $societe->email = $request->email;
           $societe->contact = $request->contact;
           $societe->adresse = $request->adresse;
           $societe->password = Hash::make('default');
           
           if ($request->hasFile('profile_picture')) {
               $societe->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
           }
           
           $societe->save();
   
           // Envoi de l'e-mail de vérification
           ResetCodePasswordSociete::where('email', $societe->email)->delete();
           $code1 = rand(1000, 4000);
           $code = $code1.''.$societe->id;
   
           ResetCodePasswordSociete::create([
               'code' => $code,
               'email' => $societe->email,
           ]);
   
           Notification::route('mail', $societe->email)
               ->notify(new SendEmailToSocieteAfterRegistrationNotification($code, $societe->email));
   
           return redirect()->route('society.index')->with('success', 'Société bien été enregistré avec succès.');
       } catch (\Exception $e) {
           return redirect()->back()->withErrors(['error' => 'Une erreur est survenue : ' . $e->getMessage()]);
       }
    }

    public function toggleArchive(Societe $societe)
    {
        try {
            if ($societe->archived_at) {
                // Désarchiver
                $societe->archived_at = null;
                $message = 'Société désarchivée avec succès';
            } else {
                // Archiver
                $societe->archived_at = now();
                $message = 'Société archivée avec succès';
            }
            
            $societe->save();
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue : ' . $e->getMessage()
            ], 500);
        }
    }
}
