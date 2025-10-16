<?php

namespace App\Http\Controllers\Demandeur;

use App\Http\Controllers\Controller;
use App\Models\Demandeur;
use App\Models\ResetCodePassword;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DemandeurAuthenticate extends Controller
{
   public function defineAccess($email){
        $checkSousadminExiste = Demandeur::where('email', $email)->first();

        if($checkSousadminExiste){
            return view('demandeur.auth.defineAcces', compact('email'));
        }else{
            return redirect()->route('demandeur.login');
        };
    }

      public function submitDefineAccess(Request $request){

        // Validation des données
        $request->validate([
                'code'=>'required|exists:reset_code_passwords,code',
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
            $demandeur = Demandeur::where('email', $request->email)->first();

            if ($demandeur) {
                // Mise à jour du mot de passe
                $demandeur->password = Hash::make($request->password);
                $demandeur->update();

                if($demandeur){
                $existingcodehop =  ResetCodePassword::where('email', $demandeur->email)->count();

                if($existingcodehop > 1){
                    ResetCodePassword::where('email', $demandeur->email)->delete();
                }
                }

                return redirect()->route('demandeur.login')->with('success', 'Vos accès on été definir avec succès');
            } else {
                return redirect()->route('demandeur.login')->with('error', 'Email inconnu');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    public function login(){
        if (auth('demandeur')->check()) {
            return redirect()->route('demandeur.dashboard');
        }
        return view('demandeur.auth.login');
    }

     public function handleLogin(Request $request)
    {
        // Validation des champs du formulaire
        $request->validate([
            'email' => 'required|exists:demandeurs,email',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'Le mail est obligatoire.',
            'email.exists' => 'Cette adresse mail n\'existe pas.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit avoir au moins 8 caractères.',
        ]);

        try {
            // Récupérer la demandeur par son email
            $demandeur = Demandeur::where('email', $request->email)->first();

            // Vérifier si la demandeur est archivée
            if ($demandeur && $demandeur->archived_at !== null) {
                return redirect()->back()->with('error', 'Le compte société a été supprimé. Vous ne pouvez pas vous connectez.');
            }

            // Tenter la connexion
            if (auth('demandeur')->attempt($request->only('email', 'password'))) {
                return redirect()->route('demandeur.dashboard')->with('success', 'Bienvenue sur votre page.');
            } else {
                return redirect()->back()->with('error', 'Votre mot de passe est incorrect.');
            }
        } catch (Exception $e) {
            // Gérer les erreurs
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la connexion.');
        }
    }
}
