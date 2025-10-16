<div class="demande-details">
    <div class="row">
        <div class="col-md-6">
            <h6 class="section-title">Informations du Demandeur</h6>
            <div class="info-group">
                <label>Nom complet:</label>
                <span>{{ $demande->prenom_demandeur }} {{ $demande->name_demandeur }}</span>
            </div>
            <div class="info-group">
                <label>Email:</label>
                <span>{{ $demande->email_demandeur }}</span>
            </div>
            <div class="info-group">
                <label>Contact:</label>
                <span>{{ $demande->contact_demandeur }}</span>
            </div>
            <div class="info-group">
                <label>Fonction:</label>
                <span>{{ $demande->fonction_demandeur ?? 'Non spécifié' }}</span>
            </div>
        </div>
        
        <div class="col-md-6">
            <h6 class="section-title">Détails de la Visite</h6>
            <div class="info-group">
                <label>Date de début:</label>
                <span>{{ \Carbon\Carbon::parse($demande->date_visite)->format('d/m/Y') }}</span>
            </div>
            <div class="info-group">
                <label>Date de fin:</label>
                <span>{{ \Carbon\Carbon::parse($demande->date_fin_visite)->format('d/m/Y') }}</span>
            </div>
            <div class="info-group">
                <label>Heure de début:</label>
                <span>{{ $demande->heure_visite }}</span>
            </div>
            <div class="info-group">
                <label>Heure de fin:</label>
                <span>{{ $demande->heure_fin_visite ?? 'Non spécifiée' }}</span>
            </div>
            <div class="info-group">
                <label>Nombre de personnes:</label>
                <span>{{ $demande->nbre_perso }}</span>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="info-group">
                <label>Motif de la visite:</label>
                <span>{{ $demande->motif_visite }}</span>
            </div>
            <div class="info-group">
                <label>Description détaillée:</label>
                <span>{{ $demande->description_detaille ?? 'Non fournie' }}</span>
            </div>
        </div>
    </div>

    @if($demande->marque_voiture || $demande->modele_voiture || $demande->immatriculation_voiture)
    <div class="row mt-3">
        <div class="col-12">
            <h6 class="section-title">Informations du Véhicule</h6>
            <div class="row">
                <div class="col-md-4">
                    <div class="info-group">
                        <label>Marque:</label>
                        <span>{{ $demande->marque_voiture ?? 'Non spécifiée' }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-group">
                        <label>Modèle:</label>
                        <span>{{ $demande->modele_voiture ?? 'Non spécifié' }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-group">
                        <label>Immatriculation:</label>
                        <span>{{ $demande->immatriculation_voiture ?? 'Non spécifiée' }}</span>
                    </div>
                </div>
            </div>
            @if($demande->type_intervention)
            <div class="info-group">
                <label>Type d'intervention:</label>
                <span>{{ $demande->type_intervention }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    <div class="row mt-3">
        <div class="col-md-6">
            <h6 class="section-title">Informations de la Demande</h6>
            <div class="info-group">
                <label>Numéro de demande:</label>
                <span class="badge badge-primary">{{ $demande->numero_demande }}</span>
            </div>
            <div class="info-group">
                <label>Statut:</label>
                @php
                    $statutClasses = [
                        'en_attente' => 'warning',
                        'approuve' => 'success',
                        'rejete' => 'danger',
                        'annule' => 'secondary',
                        'termine' => 'info'
                    ];
                @endphp
                <span class="badge badge-{{ $statutClasses[$demande->statut] ?? 'secondary' }}">
                    {{ ucfirst(str_replace('_', ' ', $demande->statut)) }}
                </span>
            </div>
            <div class="info-group">
                <label>Date de soumission:</label>
                <span>{{ $demande->created_at->format('d/m/Y H:i') }}</span>
            </div>
        </div>
        
        <div class="col-md-6">
            <h6 class="section-title">Informations de Traitement</h6>
            <div class="info-group">
                <label>Agent traitant:</label>
                <span>{{ $demande->agent ? $demande->agent->name : 'Non assigné' }}</span>
            </div>
            @if($demande->motif_rejet)
            <div class="info-group">
                <label>Motif de rejet:</label>
                <span class="text-danger">{{ $demande->motif_rejet }}</span>
            </div>
            @endif
            @if($demande->code_acces)
            <div class="info-group">
                <label>Code d'accès:</label>
                <span class="badge badge-success">{{ $demande->code_acces }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.section-title {
    color: #193561;
    font-weight: 600;
    border-bottom: 2px solid #193561;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}

.info-group {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.75rem;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.info-group label {
    font-weight: 600;
    color: #495057;
    min-width: 150px;
    margin-bottom: 0;
}

.info-group span {
    color: #193561;
    text-align: right;
    flex: 1;
}

.badge {
    font-size: 0.8rem;
}
</style>