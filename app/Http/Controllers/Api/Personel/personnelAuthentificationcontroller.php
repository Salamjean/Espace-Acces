<?php

namespace App\Http\Controllers\Api\Personel;

use App\Http\Controllers\Controller;
use App\Models\PersonneDemande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PersonnelAuthentificationController extends Controller
{
    /**
     * Gère la connexion du personnel (handleLogin)
     */
    public function login(Request $request)
    {
        // 1. Validation des données de la requête
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:personne_demandes,email',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'Le mail est obligatoire.',
            'email.email' => 'Le format du mail est incorrect.',
            'email.exists' => 'Cette adresse mail n\'existe pas.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit avoir au moins 8 caractères.',
        ]);

        // 2. Si la validation échoue, retourner les erreurs en JSON
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        try {
            // 3. Récupérer le personnel (uniquement les permanents)
            // J'ajoute le filtre 'type_visiteur' = 'permanent' basé sur votre ancien contrôleur
            $personal = PersonneDemande::where('email', $request->email)
                                       ->where('type_visiteur', 'permanent')
                                       ->first();

            // 4. Vérifier si l'utilisateur existe ET si le mot de passe est correct
            if (!$personal || !Hash::check($request->password, $personal->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email ou mot de passe incorrect.'
                ], 401); // 401 Unauthorized
            }

            // 5. Vérifier si le compte est archivé (logique de votre ancien contrôleur)
            if ($personal->archived_at !== null) {
                return response()->json([
                    'status' => false,
                    'message' => 'Votre compte a été supprimé. Vous ne pouvez pas vous connecter.'
                ], 403); // 403 Forbidden
            }

            // 6. Générer le token d'API
            $token = $personal->createToken('api-token-personnel')->plainTextToken;

            // 7. Retourner la réponse de succès avec le token et l'utilisateur
            return response()->json([
                'status' => true,
                'message' => 'Connexion réussie',
                'user' => $personal,
                'token' => $token,
                'token_type' => 'Bearer',
            ], 200); // 200 OK

        } catch (\Exception $e) {
            // Gérer les erreurs inattendues
            return response()->json([
                'status' => false,
                'message' => 'Une erreur s\'est produite lors de la connexion.',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Gère la déconnexion du personnel
     */
    public function logout(Request $request)
    {
        // Révoque le token actuel utilisé pour la requête
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Déconnexion réussie'
        ], 200);
    }
}