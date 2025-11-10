<?php

namespace App\Http\Controllers\Api\Personel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;

class CarteAccesController extends Controller
{
    /**
     * Afficher les données de la carte d'accès du personnel connecté (API)
     */
    public function showMyCard()
    {
        // Récupérer le personnel connecté - CORRECTION DU GUARD
        $personnel = Auth::guard('sanctum')->user();
        
        // Vérifier si l'utilisateur est authentifié
        if (!$personnel) {
            return response()->json([
                'error' => 'Non authentifié.'
            ], 401);
        }
        
        // Vérifier que l'utilisateur est bien un personnel permanent
        if (!$personnel->est_permanent) {
            return response()->json([
                'error' => 'Accès non autorisé. Réservé au personnel permanent.'
            ], 403);
        }
        
        // Construire les URLs complètes pour les images
        $profilePictureUrl = null;
        $qrCodeUrl = null;
        
        if ($personnel->profile_picture && Storage::disk('public')->exists($personnel->profile_picture)) {
            $profilePictureUrl = Storage::disk('public')->url($personnel->profile_picture);
        }
        
        if ($personnel->path_qr_code && Storage::disk('public')->exists($personnel->path_qr_code)) {
            $qrCodeUrl = Storage::disk('public')->url($personnel->path_qr_code);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $personnel->id,
                'nom' => $personnel->name,
                'prenom' => $personnel->prenom,
                'email' => $personnel->email,
                'est_permanent' => $personnel->est_permanent,
                'profile_picture_url' => $profilePictureUrl,
                'qr_code_url' => $qrCodeUrl,
                'created_at' => $personnel->created_at,
                'updated_at' => $personnel->updated_at
            ]
        ]);
    }

    /**
     * Télécharger la carte d'accès du personnel connecté (API)
     */
    public function downloadMyCard()
    {
        // Récupérer le personnel connecté - CORRECTION DU GUARD
        $personnel = Auth::guard('sanctum')->user();
        
        // Vérifier si l'utilisateur est authentifié
        if (!$personnel) {
            return response()->json([
                'error' => 'Non authentifié.'
            ], 401);
        }
        
        if (!$personnel->est_permanent) {
            return response()->json([
                'error' => 'Accès non autorisé. Réservé au personnel permanent.'
            ], 403);
        }
        
        // Convertir les images en base64 pour le PDF
        $profilePictureBase64 = null;
        $qrCodeBase64 = null;
        
        if ($personnel->profile_picture && Storage::disk('public')->exists($personnel->profile_picture)) {
            $profilePicturePath = Storage::disk('public')->path($personnel->profile_picture);
            $profilePictureData = base64_encode(file_get_contents($profilePicturePath));
            $profilePictureBase64 = 'data:image/jpeg;base64,' . $profilePictureData;
        }
        
        if ($personnel->path_qr_code && Storage::disk('public')->exists($personnel->path_qr_code)) {
            $qrCodePath = Storage::disk('public')->path($personnel->path_qr_code);
            $qrCodeData = base64_encode(file_get_contents($qrCodePath));
            $qrCodeBase64 = 'data:image/png;base64,' . $qrCodeData;
        }
        
        $pdf = PDF::loadView('permanent-personnel.access-card-pdf', [
            'personnel' => $personnel,
            'profilePictureBase64' => $profilePictureBase64,
            'qrCodeBase64' => $qrCodeBase64
        ]);
        
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('enable-local-file-access', true);
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->setOption('dpi', 150);
        
        $filename = 'ma-carte-acces-' . $personnel->name . '-' . $personnel->prenom . '.pdf';
        
        return $pdf->download($filename);
    }
}