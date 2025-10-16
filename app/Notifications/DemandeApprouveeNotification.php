<?php

namespace App\Notifications;

use App\Models\PersonneDemande;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DemandeApprouveeNotification extends Notification 
{

    public $personne;
    public $codeAcces;
    public $qrCodePath;
    public $logoUrl;

    public function __construct(PersonneDemande $personne, string $codeAcces, string $qrCodePath)
    {
        $this->personne = $personne;
        $this->codeAcces = $codeAcces;
        $this->qrCodePath = $qrCodePath;
        $this->logoUrl = asset('assets/assets/img/kks.jpg');
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        Log::info('📧 Construction email pour: ' . $this->personne->email);
        
        $mailMessage = (new MailMessage)
            ->subject('Espace - Accès : Votre badge d\'accès personnel')
            ->from('contact@edemarchee-ci.com', 'Espace - Accès')
            ->view('emails.demande-approuvee', [
                'personne' => $this->personne,
                'codeAcces' => $this->codeAcces,
                'qrCodePath' => $this->qrCodePath,
                'logoUrl' => $this->logoUrl,
                'dateVisiteFormatted' => \Carbon\Carbon::parse($this->personne->date_visite)->format('d/m/Y'),
                'dateFinFormatted' => \Carbon\Carbon::parse($this->personne->date_fin_visite)->format('d/m/Y'),
                'expirationFormatted' => \Carbon\Carbon::parse($this->personne->expiration_code_acces)->format('d/m/Y à H:i'),
            ]);

        // Attacher le QR code
        if ($this->qrCodePath && Storage::disk('public')->exists($this->qrCodePath)) {
            $fileName = 'QR_Code_' . $this->personne->numero_demande . '_' . $this->personne->prenom . '_' . $this->personne->name . '.png';
            
            Log::info("📎 Attachement QR code: $fileName");
            $mailMessage->attach(
                Storage::disk('public')->path($this->qrCodePath),
                ['as' => $fileName, 'mime' => 'image/png']
            );
        }

        Log::info('✅ Email construit pour: ' . $this->personne->email);
        return $mailMessage;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'personne_id' => $this->personne->id,
            'numero_demande' => $this->personne->numero_demande,
        ];
    }
}