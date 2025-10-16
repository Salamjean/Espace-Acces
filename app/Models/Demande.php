<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Demande extends Model
{
    protected $fillable = [
        'name_demandeur',
        'prenom_demandeur',
        'contact_demandeur',
        'email_demandeur',
        'fonction_demandeur',
        'nbre_perso',
        'date_visite',
        'date_fin_visite',
        'heure_visite',
        'heure_fin_visite',
        'motif_visite',
        'description_detaille',
        'statut',
        'numero_demande',
        'agent_id',
        'motif_rejet',
        'path_qr_code',
        'code_acces',
        'expiration_code_acces',
        'is_read',
        'user_agent',
        'documents_joints',
        'demandeur_id',
        'type_intervention',
        'marque_voiture',
        'modele_voiture',
        'immatriculation_voiture',
    ];

    public function demandeur()
    {
        return $this->belongsTo(Demandeur::class, 'demandeur_id'); 
    }

    // Dans app/Models/Demande.php
    public function personnes()
    {
        return $this->hasMany(PersonneDemande::class);
    }
}
