<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthentificationAgentController extends Controller
{
    /**
     * Gère la connexion de l'agent (handleLogin)
     */
    public function login(Request $request)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:agents,email',
            'password' => 'required|min:8',
        ], [
            'email.required' => 'Le mail est obligatoire.',
            'email.email' => 'Le format du mail est incorrect.',
            'email.exists' => 'Cette adresse mail n\'existe pas.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit avoir au moins 8 caractères.',
        ]);

        // 2. Réponse en cas d'échec de validation
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        try {
            // 3. Récupérer l'agent
            $agent = Agent::where('email', $request->email)->first();

            // 4. Vérifier l'agent et le mot de passe
            if (!$agent || !Hash::check($request->password, $agent->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email ou mot de passe incorrect.'
                ], 401); // 401 Unauthorized
            }

            // 5. Vérifier si le compte est archivé (logique de votre ancien contrôleur)
            if ($agent->archived_at !== null) {
                return response()->json([
                    'status' => false,
                    'message' => 'Votre compte a été supprimé. Vous ne pouvez pas vous connecter.'
                ], 403); // 403 Forbidden
            }

            // 6. Générer le token
            $token = $agent->createToken('api-token-agent')->plainTextToken;

            // 7. Réponse de succès
            return response()->json([
                'status' => true,
                'message' => 'Connexion réussie',
                'user' => $agent,
                'token' => $token,
                'token_type' => 'Bearer',
            ], 200); // 200 OK

        } catch (\Exception $e) {
            // 8. Gérer les erreurs serveur
            return response()->json([
                'status' => false,
                'message' => 'Une erreur s\'est produite lors de la connexion.',
                'error' => $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Gère la déconnexion de l'agent
     */
    public function logout(Request $request)
    {
        // Révoque le token utilisé pour faire la requête
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Déconnexion réussie'
        ], 200);
    }
}