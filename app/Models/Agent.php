<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Agent extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'prenom',
        'email',
        'contact',
        'password',
        'profile_picture',
        'cas_urgence',
        'commune',
        'archived_at'
    ];

    public function pointages()
    {
        return $this->hasMany(AgentPointage::class);
    }

    public function pointageAujourdhui()
    {
        return $this->hasOne(AgentPointage::class)
                    ->whereDate('date_pointage', today());
    }

    public function scans()
    {
        return $this->hasMany(AgentScan::class);
    }

    public function scansAujourdhui()
    {
        return $this->hasMany(AgentScan::class)
                    ->whereDate('heure_scan', today());
    }

    // Vérifier si un code spécifique peut être scanné
    public function peutScannerCode($codeUnique)
    {
        $dernierScanCeCode = AgentScan::where('agent_id', $this->id)
            ->where('code_unique_scanne', $codeUnique)
            ->where('est_valide', true)
            ->where('heure_scan', '>=', now()->subHours(2))
            ->latest('heure_scan')
            ->first();

        return is_null($dernierScanCeCode);
    }

    // Obtenir le temps d'attente pour un code spécifique
    public function tempsAttenteCode($codeUnique)
    {
        $dernierScan = AgentScan::where('agent_id', $this->id)
            ->where('code_unique_scanne', $codeUnique)
            ->where('est_valide', true)
            ->where('heure_scan', '>=', now()->subHours(2))
            ->latest('heure_scan')
            ->first();

        if ($dernierScan) {
            $prochainScanPossible = $dernierScan->heure_scan->addHours(2);
            return now()->diff($prochainScanPossible);
        }

        return null;
    }

    // Méthode pour obtenir l'historique récent (optionnel)
    public function scansRecents($limit = 5)
    {
        return $this->scans()
            ->with('codeAcces')
            ->where('heure_scan', '>=', now()->subHours(24))
            ->latest('heure_scan')
            ->limit($limit)
            ->get();
    }
}
