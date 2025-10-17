<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use App\Models\PersonneDemande;
use App\Models\ResetCodePasswordPersonnel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PersonalAuthenticate extends Controller
{
    public function defineAccess($email){
        $checkSousadminExiste = PersonneDemande::where('type_visiteur', 'permanent')->where('email', $email)->first();
        if($checkSousadminExiste){
            return view('permanent-personnel.auth.defineAcces', compact('email'));
        }else{
            return redirect()->route('personal.login');
        };
    }

     public function submitDefineAccess(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
                'code' => 'required|exists:reset_code_password_personnels,code',
                'password' => 'required|min:8|same:confirme_password',
                'confirme_password' => 'required|min:8|same:password',
                ], [
                'code.exists' => 'Le code de réinitialisation est invalide.',
                'password.min'=> 'Le mot de passe doit avoir au moins 8 caractères.',
                'confirme_password.min'=> 'Le mot de passe doit avoir au moins 8 caractères.',
                'password.same' => 'Les mots de passe doivent être identiques.',
                'confirme_password.same' => 'Les mots de passe doivent être identiques.',
        ]);

        try {
            $personal = PersonneDemande::where('type_visiteur', 'permanent')->where('email',$request->email)->first();

            if ($personal) {
                // Mise à jour du mot de passe
                $personal->password = Hash::make($request->password);

                // Traitement de l'image de profil
                if ($request->hasFile('profile_picture')) {
                    // Supprimer l'ancienne photo si elle existe
                    if ($personal->profile_picture) {
                        Storage::delete('public/' . $personal->profile_picture); // Assurez-vous du 'public/' ici
                    }

                    // Stocker la nouvelle image
                    $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
                    $personal->profile_picture = $imagePath;
                }

                $personal->update();

                if ($personal) {
                    $existingcodeagent = ResetCodePasswordPersonnel::where('email', $personal->email)->count();

                    if ($existingcodeagent > 1) {
                        ResetCodePasswordPersonnel::where('email', $personal->email)->delete();
                    }
                }

                return redirect()->route('personal.login')->with('success', 'Compte mis à jour avec succès');
            } else {
                return redirect()->route('personal.login')->with('error', 'Email inconnu');
            }
        } catch (\Exception $e) {
            Log::error('Error updating agent profile: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue : ' . $e->getMessage())->withInput();
        }
    }

    public function login(){
        if (auth('personal')->check()) {
            return redirect()->route('personal.dashboard');
        }
        return view('permanent-personnel.auth.login');
    }

    public function handleLogin(Request $request)
    {
        // Validation des champs du formulaire
        $request->validate([
            'email' => 'required|exists:personne_demandes,email',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'Le mail est obligatoire.',
            'email.exists' => 'Cette adresse mail n\'existe pas.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit avoir au moins 8 caractères.',
        ]);

        try {
            // Récupérer personal par son email
            $personal = PersonneDemande::where('email', $request->email)->first();

            // Vérifier si l'personal est archivé
            if ($personal && $personal->archived_at !== null) {
                return redirect()->back()->with('error', 'Votre compte a été supprimé. Vous ne pouvez pas vous connecter.');
            }

            if (auth('personal')->attempt($request->only('email', 'password'))) {
                return redirect()->route('personal.dashboard')->with('success', 'Bienvenue sur la page des demandes en attente');
            } else {
                return redirect()->back()->with('error', 'Votre mot de passe est incorrect.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la connexion.');
        }
    }
}
