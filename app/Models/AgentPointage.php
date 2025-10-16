<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentPointage extends Model
{
      use HasFactory;

    protected $fillable = [
        'agent_id',
        'date_pointage',
        'heure_arrivee',
        'heure_depart',
        'statut',
        'observations'
    ];

    protected $casts = [
        'date_pointage' => 'date',
        'heure_arrivee' => 'datetime',
        'heure_depart' => 'datetime',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
