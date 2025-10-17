<?php

namespace App\Http\Controllers\Personal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;

class CarteAccesController extends Controller
{
    /**
     * Afficher la carte d'accès du personnel connecté
     */
    public function showMyCard()
    {
        // Récupérer le personnel connecté
        $personnel =  Auth::guard('personal')->user();
        
        // Vérifier que l'utilisateur est bien un personnel permanent
        if (!$personnel->est_permanent) {
            abort(403, 'Accès non autorisé.');
        }
        
        return view('permanent-personnel.my-access-card', compact('personnel'));
    }

    /**
     * Télécharger la carte d'accès du personnel connecté
     */
    public function downloadMyCard()
    {
        $personnel =  Auth::guard('personal')->user();
        
        if (!$personnel->est_permanent) {
            abort(403, 'Accès non autorisé.');
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
        
        return $pdf->download('ma-carte-acces-' . $personnel->name . '-' . $personnel->prenom . '.pdf');
    }
}
