<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use App\Models\ResetCodePasswordSociete;
use App\Models\Societe;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SocieteAuthenticate extends Controller
{
    public function defineAccess($email){
        $checkSousadminExiste = Societe::where('email', $email)->first();

        if($checkSousadminExiste){
            return view('societe.auth.defineAcces', compact('email'));
        }else{
            return redirect()->route('society.login');
        };
    }

     public function submitDefineAccess(Request $request){

        // Validation des données
        $request->validate([
                'code'=>'required|exists:reset_code_password_societes,code',
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
            $societe = Societe::where('email', $request->email)->first();

            if ($societe) {
                // Mise à jour du mot de passe
                $societe->password = Hash::make($request->password);
                $societe->update();

                if($societe){
                $existingcodehop =  ResetCodePasswordSociete::where('email', $societe->email)->count();

                if($existingcodehop > 1){
                    ResetCodePasswordSociete::where('email', $societe->email)->delete();
                }
                }

                return redirect()->route('society.login')->with('success', 'Vos accès on été definir avec succès');
            } else {
                return redirect()->route('society.login')->with('error', 'Email inconnu');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue : ' . $e->getMessage());
        }
    }

    public function login(){
        if (auth('societe')->check()) {
            return redirect()->route('society.dashboard');
        }
        return view('societe.auth.login');
    }

     public function handleLogin(Request $request)
    {
        // Validation des champs du formulaire
        $request->validate([
            'email' => 'required|exists:societes,email',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'Le mail est obligatoire.',
            'email.exists' => 'Cette adresse mail n\'existe pas.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit avoir au moins 8 caractères.',
        ]);

        try {
            // Récupérer la societe par son email
            $societe = Societe::where('email', $request->email)->first();

            // Vérifier si la societe est archivée
            if ($societe && $societe->archived_at !== null) {
                return redirect()->back()->with('error', 'Le compte société a été supprimé. Vous ne pouvez pas vous connectez.');
            }

            // Tenter la connexion
            if (auth('societe')->attempt($request->only('email', 'password'))) {
                return redirect()->route('society.dashboard')->with('success', 'Bienvenue sur votre page.');
            } else {
                return redirect()->back()->with('error', 'Votre mot de passe est incorrect.');
            }
        } catch (Exception $e) {
            // Gérer les erreurs
            return redirect()->back()->with('error', 'Une erreur s\'est produite lors de la connexion.');
        }
    }
}
