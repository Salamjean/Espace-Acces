<?php

namespace App\Http\Controllers\Api\Agent;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfilAgentController extends Controller
{
    /**
     * Récupérer les informations du profil de l'agent connecté
     */
    public function getProfil()
    {
        $agent = Auth::guard('sanctum')->user();
        
        if (!$agent) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié.'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $agent->id,
                'name' => $agent->name,
                'prenom' => $agent->prenom,
                'email' => $agent->email,
                'contact' => $agent->contact,
                'cas_urgence' => $agent->cas_urgence,
                'commune' => $agent->commune,
                'profile_picture_url' => $agent->profile_picture ? '/storage/' . $agent->profile_picture : null,
                'created_at' => $agent->created_at,
                'updated_at' => $agent->updated_at
            ]
        ]);
    }

    /**
     * Mettre à jour les informations du profil
     */
    public function updateProfil(Request $request)
    {
        $agent = Auth::guard('sanctum')->user();
        
        if (!$agent) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('agents')->ignore($agent->id)
            ],
            'contact' => 'sometimes|string|max:20',
            'cas_urgence' => 'sometimes|string|max:255',
            'commune' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        // Mettre à jour uniquement les champs fournis
        $agent->update($request->only([
            'name', 'prenom', 'email', 'contact', 'cas_urgence', 'commune'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour avec succès',
            'data' => [
                'id' => $agent->id,
                'name' => $agent->name,
                'prenom' => $agent->prenom,
                'email' => $agent->email,
                'contact' => $agent->contact,
                'cas_urgence' => $agent->cas_urgence,
                'commune' => $agent->commune,
                'profile_picture_url' => $agent->profile_picture ? '/storage/' . $agent->profile_picture : null,
            ]
        ]);
    }

    /**
     * Mettre à jour la photo de profil
     */
    public function updatePhotoProfil(Request $request)
    {
        $agent = Auth::guard('sanctum')->user();
        
        if (!$agent) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        // Supprimer l'ancienne photo si elle existe
        if ($agent->profile_picture && Storage::disk('public')->exists($agent->profile_picture)) {
            Storage::disk('public')->delete($agent->profile_picture);
        }

        // Sauvegarder la nouvelle photo
        $path = $request->file('profile_picture')->store('agents/profile-pictures', 'public');
        
        $agent->update([
            'profile_picture' => $path
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Photo de profil mise à jour avec succès',
            'data' => [
                'profile_picture_url' => '/storage/' . $path
            ]
        ]);
    }

    /**
     * Supprimer la photo de profil
     */
    public function deletePhotoProfil()
    {
        $agent = Auth::guard('sanctum')->user();
        
        if (!$agent) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié.'
            ], 401);
        }

        if (!$agent->profile_picture) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune photo de profil à supprimer'
            ], 400);
        }

        // Supprimer le fichier physique
        if (Storage::disk('public')->exists($agent->profile_picture)) {
            Storage::disk('public')->delete($agent->profile_picture);
        }

        // Mettre à jour la base de données
        $agent->update([
            'profile_picture' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Photo de profil supprimée avec succès'
        ]);
    }

    /**
     * Changer le mot de passe
     */
    public function changePassword(Request $request)
    {
        $agent = Auth::guard('sanctum')->user();
        
        if (!$agent) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $agent->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Le mot de passe actuel est incorrect'
            ], 400);
        }

        // Mettre à jour le mot de passe
        $agent->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe modifié avec succès'
        ]);
    }
}