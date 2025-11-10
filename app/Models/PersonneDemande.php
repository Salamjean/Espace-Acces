<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
class PersonneDemande extends Authenticatable
{
    
    use HasFactory, Notifiable, HasApiTokens;
    protected $table = 'personne_demandes';

    protected $fillable = [
        'name',
        'prenom',
        'contact',
        'email',
        'adresse',
        'fonction',
        'structure',
        'profile_picture',
        'date_visite',
        'date_fin_visite',
        'heure_visite',
        'heure_fin_visite',
        'motif_visite',
        'description_detaille',
        'nbre_perso',
        'marque_voiture',
        'modele_voiture',
        'immatriculation_voiture',
        'type_intervention',
        'statut',
        'numero_demande',
        'demandeur_id',
        'agent_id',
        'motif_rejet',
        'path_qr_code',
        'code_acces',
        'expiration_code_acces',
        'is_read',
        'user_agent',
        'documents_joints',
        'groupe_id',
        'est_demandeur_principal',
        'type_visiteur',
        'est_permanent',
        'photo_recto',
        'photo_verso',
        'numero_piece',
        'type_piece',
        'motif_acces_permanent',
        'date_debut_permanent',
        'date_fin_permanent',
        'numero_ticket',
    ];

    protected $casts = [
        'date_visite' => 'date',
        'date_fin_visite' => 'date',
        'documents_joints' => 'array',
        'expiration_code_acces' => 'datetime',
        'est_demandeur_principal' => 'boolean',
        'is_read' => 'boolean',
    ];

    /**
     * Relation avec le demandeur
     */
    public function demandeur()
    {
        return $this->belongsTo(Demandeur::class);
    }

     /**
     * Relation avec les visites
     */
    public function visites()
    {
        return $this->hasMany(Visite::class, 'personne_demande_id');
    }
    /**
     * Vérifier si c'est un personnel permanent
     */
    public function isPermanent()
    {
        return $this->est_permanent && $this->date_fin_permanent >= now();
    }

    /**
     * Vérifier si l'accès permanent a expiré
     */
    public function isPermanentExpired()
    {
        return $this->est_permanent && $this->date_fin_permanent < now();
    }

    /**
     * Générer un code d'accès pour personnel permanent
     */
    public static function generatePermanentCode()
    {
        do {
            $code = 'PERM-' . strtoupper(substr(md5(uniqid()), 0, 8));
        } while (self::where('code_acces', $code)->exists());

        return $code;
    }

    /**
     * Scope pour les personnels permanents
     */
    public function scopePermanent($query)
    {
        return $query->where('est_permanent', true);
    }

    /**
     * Scope pour les personnels permanents actifs
     */
    public function scopePermanentActif($query)
    {
        return $query->where('est_permanent', true)
                    ->where('date_fin_permanent', '>=', now())
                    ->where('statut', 'approuve');
    }

    /**
     * Formater la durée restante
     */
    public function getDureeRestanteAttribute()
    {
        if (!$this->est_permanent) return null;
        
        $now = now();
        $fin = Carbon::parse($this->date_fin_permanent);
        
        if ($fin->lessThan($now)) {
            return 'Expiré';
        }
        
        return $now->diffForHumans($fin, true);
    }

    /**
     * Relation avec l'agent
     */
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Relation avec les autres personnes du même groupe
     */
    public function personnesGroupe()
    {
        return $this->hasMany(PersonneDemande::class, 'groupe_id', 'groupe_id');
    }

    /**
     * Accessor pour l'URL complète de la photo de profil
     */
    public function getProfilePictureUrlAttribute()
    {
        return $this->profile_picture ? asset('storage/' . $this->profile_picture) : null;
    }

    /**
     * Scope pour les demandeurs principaux
     */
    public function scopeDemandeurPrincipal($query)
    {
        return $query->where('est_demandeur_principal', true);
    }

    /**
     * Scope pour un groupe spécifique
     */
    public function scopeDuGroupe($query, $groupeId)
    {
        return $query->where('groupe_id', $groupeId);
    }
}