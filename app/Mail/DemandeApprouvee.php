<?php

namespace App\Mail;

use App\Models\Demande;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class DemandeApprouvee extends Mailable
{
    use Queueable, SerializesModels;

    public $demande;
    public $codesAcces;
    public $qrCodesPaths;
    public $dateVisiteFormatted;
    public $expirationFormatted;

    /**
     * Create a new message instance.
     */
    public function __construct(Demande $demande, array $codesAcces, array $qrCodesPaths)
    {
        $this->demande = $demande;
        $this->codesAcces = $codesAcces;
        $this->qrCodesPaths = $qrCodesPaths;
        $this->dateVisiteFormatted = $demande->date_visite;
        $this->expirationFormatted = $demande->expiration_code_acces->format('d/m/Y \à H:i');
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // CORRECTION : Spécifier le bon chemin de la vue
        $email = $this->subject('✅ Votre accès KKS Technologies a été approuvé - ' . $this->demande->numero_demande)
            ->markdown('emails.demande-approuvee'); // Assurez-vous que ce chemin est correct

        // Attacher les QR codes
        foreach ($this->qrCodesPaths as $index => $qrCodePath) {
            if ($qrCodePath && Storage::disk('public')->exists($qrCodePath)) {
                $personneNumber = $index + 1;
                $email->attach(Storage::disk('public')->path($qrCodePath), [
                    'as' => 'QR_Code_' . $this->demande->numero_demande . '_Personne_' . $personneNumber . '.png',
                    'mime' => 'image/png',
                ]);
            }
        }

        return $email;
    }
}