<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CodeAcces extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_porte',
        'type',
        'code_unique',
        'qr_code_path',
        'est_actif',
        'expire_at'
    ];

    protected $casts = [
        'est_actif' => 'boolean',
        'expire_at' => 'datetime'
    ];

    // Générer un code unique
    public static function genererCodeUnique()
    {
        do {
            $code = 'CA_' . strtoupper(substr(md5(uniqid()), 0, 8));
        } while (self::where('code_unique', $code)->exists());

        return $code;
    }

    // Vérifier si le code est valide
    public function estValide()
    {
        return $this->est_actif && (!$this->expire_at || $this->expire_at->isFuture());
    }

    // Getter pour l'URL du QR code
    public function getQrCodeUrlAttribute()
    {
        return $this->qr_code_path ? Storage::url($this->qr_code_path) : null;
    }

    // Supprimer le fichier QR code lors de la suppression
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($codeAcces) {
            if ($codeAcces->qr_code_path && Storage::exists($codeAcces->qr_code_path)) {
                Storage::delete($codeAcces->qr_code_path);
            }
        });
    }
}