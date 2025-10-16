<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visite extends Model
{
    use HasFactory;

    protected $fillable = [
        'personne_demande_id',
        'agent_id',
        'numero_carte',
        'numero_piece',
        'type_piece',
        'photo_recto',
        'photo_verso',
        'date_entree',
        'date_sortie',
        'statut',
        'observations',
        'personnes_permanentes_associees',
    ];

    protected $casts = [
        'date_entree' => 'datetime',
        'date_sortie' => 'datetime',
    ];

    /**
     * Relation avec la personne (visiteur)
     */
    public function personneDemande(): BelongsTo
    {
        return $this->belongsTo(PersonneDemande::class);
    }

    /**
     * Relation avec l'agent qui a scanné
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    /**
     * Scope pour les visites en cours
     */
    public function scopeEnCours($query)
    {
        return $query->where('statut', 'en_cours');
    }

    /**
     * Scope pour les visites terminées
     */
    public function scopeTerminees($query)
    {
        return $query->where('statut', 'termine');
    }

    /**
     * Marquer la visite comme terminée
     */
    public function terminer()
    {
        $this->update([
            'statut' => 'termine',
            'date_sortie' => now()
        ]);
    }

    /**
     * Vérifier si la visite est en cours
     */
    public function estEnCours(): bool
    {
        return $this->statut === 'en_cours';
    }

    /**
     * Accessor pour le nom complet du visiteur
     */
    public function getNomCompletVisiteurAttribute(): string
    {
        return $this->personneDemande->prenom . ' ' . $this->personneDemande->name;
    }

    /**
     * Accessor pour l'email du visiteur
     */
    public function getEmailVisiteurAttribute(): string
    {
        return $this->personneDemande->email;
    }

    /**
     * Accessor pour le contact du visiteur
     */
    public function getContactVisiteurAttribute(): string
    {
        return $this->personneDemande->contact;
    }

    /**
     * Accessor pour la durée de la visite
     */
    public function getDureeVisiteAttribute(): ?string
    {
        if ($this->date_sortie) {
            return $this->date_entree->diffForHumans($this->date_sortie, true);
        }
        
        return $this->date_entree->diffForHumans(now(), true);
    }
}