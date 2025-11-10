<?php

namespace App\Http\Controllers\Api\Personel;

use App\Http\Controllers\Controller;
use App\Models\PersonneDemande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfilPersonelController extends Controller
{
    /**
     * Récupérer les informations du profil du personnel connecté
     */
    public function getProfil()
    {
        $personnel = Auth::guard('sanctum')->user();
        
        if (!$personnel) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié.'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => $this->formatPersonnelData($personnel)
        ]);
    }

    /**
     * Mettre à jour les informations du profil
     */
    public function updateProfil(Request $request)
    {
        $personnel = Auth::guard('sanctum')->user();
        
        if (!$personnel) {
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
                Rule::unique('personne_demandes')->ignore($personnel->id)
            ],
            'contact' => 'sometimes|string|max:20',
            'adresse' => 'sometimes|string|max:255',
            'fonction' => 'sometimes|string|max:255',
            'structure' => 'sometimes|string|max:255',
            'numero_piece' => 'sometimes|string|max:50',
            'type_piece' => 'sometimes|string|max:50',
            'motif_acces_permanent' => 'sometimes|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        // Champs autorisés pour la mise à jour
        $allowedFields = [
            'name', 'prenom', 'email', 'contact', 'adresse', 
            'fonction', 'structure', 'numero_piece', 'type_piece',
            'motif_acces_permanent'
        ];

        $updateData = [];
        foreach ($allowedFields as $field) {
            if ($request->has($field)) {
                $updateData[$field] = $request->$field;
            }
        }

        // Mettre à jour le profil
        $personnel->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour avec succès',
            'data' => $this->formatPersonnelData($personnel->fresh())
        ]);
    }

    /**
     * Mettre à jour la photo de profil
     */
    public function updatePhotoProfil(Request $request)
    {
        $personnel = Auth::guard('sanctum')->user();
        
        if (!$personnel) {
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
        if ($personnel->profile_picture && Storage::disk('public')->exists($personnel->profile_picture)) {
            Storage::disk('public')->delete($personnel->profile_picture);
        }

        // Sauvegarder la nouvelle photo
        $path = $request->file('profile_picture')->store('personnel/profile-pictures', 'public');
        
        $personnel->update([
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
        $personnel = Auth::guard('sanctum')->user();
        
        if (!$personnel) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié.'
            ], 401);
        }

        if (!$personnel->profile_picture) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune photo de profil à supprimer'
            ], 400);
        }

        // Supprimer le fichier physique
        if (Storage::disk('public')->exists($personnel->profile_picture)) {
            Storage::disk('public')->delete($personnel->profile_picture);
        }

        // Mettre à jour la base de données
        $personnel->update([
            'profile_picture' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Photo de profil supprimée avec succès'
        ]);
    }

    /**
     * Mettre à jour les photos de pièce d'identité
     */
    public function updatePieceIdentite(Request $request)
    {
        $personnel = Auth::guard('sanctum')->user();
        
        if (!$personnel) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'photo_recto' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'photo_verso' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'numero_piece' => 'sometimes|string|max:50',
            'type_piece' => 'sometimes|string|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = [];

        // Gérer la photo recto
        if ($request->hasFile('photo_recto')) {
            // Supprimer l'ancienne photo recto si elle existe
            if ($personnel->photo_recto && Storage::disk('public')->exists($personnel->photo_recto)) {
                Storage::disk('public')->delete($personnel->photo_recto);
            }

            $rectoPath = $request->file('photo_recto')->store('personnel/pieces-identite', 'public');
            $updateData['photo_recto'] = $rectoPath;
        }

        // Gérer la photo verso
        if ($request->hasFile('photo_verso')) {
            // Supprimer l'ancienne photo verso si elle existe
            if ($personnel->photo_verso && Storage::disk('public')->exists($personnel->photo_verso)) {
                Storage::disk('public')->delete($personnel->photo_verso);
            }

            $versoPath = $request->file('photo_verso')->store('personnel/pieces-identite', 'public');
            $updateData['photo_verso'] = $versoPath;
        }

        // Mettre à jour les informations de la pièce
        if ($request->has('numero_piece')) {
            $updateData['numero_piece'] = $request->numero_piece;
        }

        if ($request->has('type_piece')) {
            $updateData['type_piece'] = $request->type_piece;
        }

        $personnel->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Informations de pièce d\'identité mises à jour avec succès',
            'data' => [
                'numero_piece' => $personnel->numero_piece,
                'type_piece' => $personnel->type_piece,
                'photo_recto_url' => $personnel->photo_recto ? '/storage/' . $personnel->photo_recto : null,
                'photo_verso_url' => $personnel->photo_verso ? '/storage/' . $personnel->photo_verso : null,
            ]
        ]);
    }

    /**
     * Changer le mot de passe
     */
    public function changePassword(Request $request)
    {
        $personnel = Auth::guard('sanctum')->user();
        
        if (!$personnel) {
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
        if (!Hash::check($request->current_password, $personnel->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Le mot de passe actuel est incorrect'
            ], 400);
        }

        // Mettre à jour le mot de passe
        $personnel->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe modifié avec succès'
        ]);
    }

    /**
     * Formater les données du personnel pour la réponse
     */
    private function formatPersonnelData($personnel)
    {
        return [
            'id' => $personnel->id,
            'name' => $personnel->name,
            'prenom' => $personnel->prenom,
            'email' => $personnel->email,
            'contact' => $personnel->contact,
            'adresse' => $personnel->adresse,
            'fonction' => $personnel->fonction,
            'structure' => $personnel->structure,
            'est_permanent' => $personnel->est_permanent,
            'statut' => $personnel->statut,
            'profile_picture_url' => $personnel->profile_picture ? '/storage/' . $personnel->profile_picture : null,
            'numero_piece' => $personnel->numero_piece,
            'type_piece' => $personnel->type_piece,
            'photo_recto_url' => $personnel->photo_recto ? '/storage/' . $personnel->photo_recto : null,
            'photo_verso_url' => $personnel->photo_verso ? '/storage/' . $personnel->photo_verso : null,
            'motif_acces_permanent' => $personnel->motif_acces_permanent,
            'date_debut_permanent' => $personnel->date_debut_permanent,
            'date_fin_permanent' => $personnel->date_fin_permanent,
            'duree_restante' => $personnel->duree_restante,
            'qr_code_url' => $personnel->path_qr_code ? '/storage/' . $personnel->path_qr_code : null,
            'code_acces' => $personnel->code_acces,
            'created_at' => $personnel->created_at,
            'updated_at' => $personnel->updated_at
        ];
    }
}