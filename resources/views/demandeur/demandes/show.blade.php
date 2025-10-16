@extends('demandeur.layouts.template')
@section('content')
<div class="confirmation-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="confirmation-card text-center">
                    <div class="success-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    
                    <h1 class="main-title">Demande Soumise avec Succès !</h1>
                    
                    <div class="confirmation-details">
                        <div class="detail-item">
                            <strong>Numéro de demande :</strong>
                            <span class="badge badge-numero">{{ $personne->numero_demande }}</span>
                        </div>
                        
                        <div class="detail-item">
                            <strong>Statut :</strong>
                            <span class="badge badge-en-attente">En attente de validation</span>
                        </div>
                        
                        <div class="detail-item">
                            <strong>Date de soumission :</strong>
                            <span>{{ $personne->created_at->format('d/m/Y à H:i') }}</span>
                        </div>

                        <div class="detail-item">
                            <strong>Type de demande :</strong>
                            <span>{{ $personne->nbre_perso == 1 ? 'Pour moi-même' : 'Pour plusieurs personnes' }}</span>
                        </div>

                        <div class="detail-item">
                            <strong>Nombre total de personnes :</strong>
                            <span>{{ $personne->nbre_perso }}</span>
                        </div>
                    </div>

                    <!-- Récapitulatif du demandeur principal -->
                    <div class="demande-summary">
                        <h3>Récapitulatif de votre demande</h3>
                        <div class="summary-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Demandeur principal :</strong> {{ $personne->prenom }} {{ $personne->name }}</p>
                                    <p><strong>Contact :</strong> {{ $personne->contact }}</p>
                                    <p><strong>Email :</strong> {{ $personne->email }}</p>
                                    <p><strong>Fonction :</strong> {{ $personne->fonction }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Structure :</strong> {{ $personne->structure }}</p>
                                    <p><strong>Date de début :</strong> {{ \Carbon\Carbon::parse($personne->date_visite)->format('d/m/Y') }}</p>
                                    <p><strong>Date de fin :</strong> {{ \Carbon\Carbon::parse($personne->date_fin_visite)->format('d/m/Y') }}</p>
                                    <p><strong>Heure de début :</strong> {{ $personne->heure_visite }}</p>
                                </div>
                            </div>
                            <p><strong>Motif :</strong> {{ $personne->motif_visite }}</p>
                            @if($personne->description_detaille)
                                <p><strong>Description détaillée :</strong> {{ $personne->description_detaille }}</p>
                            @endif
                            
                            <!-- Informations véhicule -->
                            @if($personne->marque_voiture || $personne->modele_voiture || $personne->immatriculation_voiture)
                            <div class="vehicule-info mt-3">
                                <h6>Informations du véhicule :</h6>
                                <div class="row">
                                    @if($personne->marque_voiture)
                                    <div class="col-md-4">
                                        <strong>Marque :</strong> {{ $personne->marque_voiture }}
                                    </div>
                                    @endif
                                    @if($personne->modele_voiture)
                                    <div class="col-md-4">
                                        <strong>Modèle :</strong> {{ $personne->modele_voiture }}
                                    </div>
                                    @endif
                                    @if($personne->immatriculation_voiture)
                                    <div class="col-md-4">
                                        <strong>Immatriculation :</strong> {{ $personne->immatriculation_voiture }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Liste des autres personnes si demande groupée -->
                    @if($personnesGroupe->count() > 1)
                    <div class="autres-personnes">
                        <h4>Autres personnes incluses dans la demande</h4>
                        <div class="personnes-list">
                            @foreach($personnesGroupe as $autrePersonne)
                                @if(!$autrePersonne->est_demandeur_principal)
                                <div class="personne-item">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Nom :</strong> {{ $autrePersonne->prenom }} {{ $autrePersonne->name }}
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Contact :</strong> {{ $autrePersonne->contact }}
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Email :</strong> {{ $autrePersonne->email }}
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <strong>Dates :</strong> 
                                            {{ \Carbon\Carbon::parse($autrePersonne->date_visite)->format('d/m/Y') }} - 
                                            {{ \Carbon\Carbon::parse($autrePersonne->date_fin_visite)->format('d/m/Y') }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Heure :</strong> {{ $autrePersonne->heure_visite }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="action-buttons">
                        <a href="{{ route('demandes.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i>
                            Nouvelle Demande
                        </a>
                        <a href="{{ route('demandeur.dashboard') }}" class="btn btn-outline-primary">
                            <i class="bi bi-house"></i>
                            Retour à l'accueil
                        </a>
                    </div>

                    <div class="info-note">
                        <i class="bi bi-info-circle"></i>
                        <p>Vous recevrez une notification par email dès que votre demande sera traitée. Vous pouvez suivre l'état de votre demande dans votre espace personnel.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.confirmation-container {
    min-height: 80vh;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 60px 0;

}

.confirmation-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(25, 53, 97, 0.1);
    padding: 3rem;
}

.success-icon {
    color: #28a745;
    font-size: 4rem;
    margin-bottom: 2rem;
}

.main-title {
    color: #193561;
    font-weight: 700;
    margin-bottom: 2rem;
}

.confirmation-details {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 2rem;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.detail-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.badge-numero {
    background: #193561;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 1.1rem;
}

.badge-en-attente {
    background: #ffc107;
    color: #212529;
    padding: 0.5rem 1rem;
    border-radius: 20px;
}

.demande-summary {
    text-align: left;
    margin-bottom: 2rem;
}

.demande-summary h3 {
    color: #193561;
    margin-bottom: 1rem;
    text-align: center;
}

.summary-content {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    border-left: 4px solid #193561;
}

.vehicule-info {
    background: #e7f3ff;
    padding: 1rem;
    border-radius: 8px;
    border-left: 3px solid #193561;
}

.vehicule-info h6 {
    color: #193561;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.autres-personnes {
    text-align: left;
    margin-bottom: 2rem;
}

.autres-personnes h4 {
    color: #193561;
    margin-bottom: 1rem;
    text-align: center;
}

.personnes-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.personne-item {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
    transition: all 0.3s ease;
}

.personne-item:hover {
    border-color: #193561;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 30px;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: #193561;
    color: white;
}

.btn-primary:hover {
    background: #152a4d;
    color: white;
    transform: translateY(-2px);
}

.btn-outline-primary {
    background: white;
    color: #193561;
    border: 2px solid #193561;
}

.btn-outline-primary:hover {
    background: #193561;
    color: white;
    transform: translateY(-2px);
}

.btn-outline-secondary {
    background: white;
    color: #6c757d;
    border: 2px solid #6c757d;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
    transform: translateY(-2px);
}

.info-note {
    background: #e7f3ff;
    padding: 1rem;
    border-radius: 10px;
    border-left: 4px solid #193561;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.info-note i {
    color: #193561;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.info-note p {
    margin: 0;
    color: #193561;
}

@media (max-width: 768px) {
    .confirmation-container {
        padding: 30px 0;
    }
    
    .confirmation-card {
        padding: 2rem 1.5rem;
    }
    
    .detail-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .info-note {
        flex-direction: column;
        text-align: center;
    }
    
    .personne-item .row {
        margin: 0;
    }
    
    .personne-item .col-md-4,
    .personne-item .col-md-6 {
        margin-bottom: 0.5rem;
    }
}

@media (max-width: 576px) {
    .confirmation-card {
        padding: 1.5rem 1rem;
    }
    
    .demande-summary .row,
    .vehicule-info .row {
        margin: 0;
    }
    
    .demande-summary .col-md-6,
    .vehicule-info .col-md-4 {
        margin-bottom: 1rem;
    }
}
</style>
@endsection