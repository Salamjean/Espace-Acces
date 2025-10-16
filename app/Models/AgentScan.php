<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentScan extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'code_acces_id',
        'type_scan',
        'nom_porte',
        'code_unique_scanne',
        'heure_scan',
        'est_valide',
        'raison_invalidite'
    ];

    protected $casts = [
        'heure_scan' => 'datetime',
        'est_valide' => 'boolean'
    ];

    // Relation avec l'agent
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    // Relation avec le code d'accès
    public function codeAcces()
    {
        return $this->belongsTo(CodeAcces::class);
    }

    // Vérifier si l'agent peut scanner (toutes les 2 heures)
    public static function peutScanner($agentId)
    {
        $dernierScanValide = self::where('agent_id', $agentId)
            ->where('est_valide', true)
            ->where('heure_scan', '>=', now()->subHours(2))
            ->latest('heure_scan')
            ->first();

        return is_null($dernierScanValide);
    }

    // Obtenir le temps d'attente restant
    public static function tempsAttenteRestant($agentId)
    {
        $dernierScan = self::where('agent_id', $agentId)
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
}