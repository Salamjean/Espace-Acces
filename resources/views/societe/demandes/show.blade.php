@extends('societe.layouts.template')
@section('content')
<div class="confirmation-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="confirmation-card text-center">
                    <div class="success-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    
                    <h1 class="main-title">Demande Soumise avec Succès !</h1>
                    
                    <div class="confirmation-details">
                        <div class="detail-item">
                            <strong>Numéro de demande :</strong>
                            <span class="badge badge-numero">{{ $demande->numero_demande }}</span>
                        </div>
                        
                        <div class="detail-item">
                            <strong>Statut :</strong>
                            <span class="badge badge-en-attente">En attente de validation</span>
                        </div>
                        
                        <div class="detail-item">
                            <strong>Date de soumission :</strong>
                            <span>{{ $demande->created_at->format('d/m/Y à H:i') }}</span>
                        </div>
                    </div>

                    <div class="demande-summary">
                        <h3>Récapitulatif de votre demande</h3>
                        <div class="summary-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Demandeur :</strong> {{ $demande->prenom_demandeur }} {{ $demande->name_demandeur }}</p>
                                    <p><strong>Contact :</strong> {{ $demande->contact_demandeur }}</p>
                                    <p><strong>Email :</strong> {{ $demande->email_demandeur }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Date de visite :</strong> {{ \Carbon\Carbon::parse($demande->date_visite)->format('d/m/Y') }}</p>
                                    <p><strong>Heure :</strong> {{ $demande->heure_visite }}</p>
                                    <p><strong>Nombre de personnes :</strong> {{ $demande->nbre_perso }}</p>
                                </div>
                            </div>
                            <p><strong>Motif :</strong> {{ $demande->motif_visite }}</p>
                            @if($demande->description_detaille)
                                <p><strong>Description :</strong> {{ $demande->description_detaille }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('demandes.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i>
                            Nouvelle Demande
                        </a>
                        <a href="{{ route('society.dashboard') }}" class="btn btn-outline-primary">
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

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 2rem;
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
}

.btn-primary {
    background: #193561;
    color: white;
    border: none;
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
}
</style>
@endsection