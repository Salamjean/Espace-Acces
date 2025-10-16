@extends('agent.layouts.template')

@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<div class="container-fluid" style="background-color: white; margin-top:10px">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="background-color: #e9eaef">
                <div class="card-header bg-success text-white text-center py-4">
                    <i class="bi bi-check-circle-fill display-1"></i>
                    <h3 class="mt-3 mb-0">Visiteur Enregistré avec Succès!</h3>
                </div>
                
                <div class="card-body text-center p-5">
                    @if(session('success'))
                        <div class="alert alert-success mb-4">
                            <i class="bi bi-check-lg me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="success-details mb-4">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3" style="color: #193561">
                                            <i class="bi bi-person-check me-2"></i>
                                            Détails de l'enregistrement
                                        </h5>
                                        
                                        @if(session('visiteur'))
                                            <p class="mb-2"><strong>Visiteur:</strong> {{ session('visiteur') }}</p>
                                        @endif
                                        
                                        @if(session('numero_piece'))
                                            <p class="mb-2"><strong>N° Pièce:</strong> {{ session('numero_piece') }}</p>
                                        @endif
                                        
                                        <p class="mb-0"><strong>Heure d'entrée:</strong> {{ now()->format('H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons mt-4">
                        <a href="{{ route('visite.access') }}" class="btn btn-primary me-3">
                            <i class="bi bi-plus-circle me-2"></i>Nouvelle Entrée
                        </a>
                        <a href="{{ route('agent.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-house me-2"></i>Retour au Dashboard
                        </a>
                    </div>

                    <div class="mt-5">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Le visiteur a été enregistré avec succès. N'oubliez pas de scanner son QR code lors de sa sortie.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.bg-success {
    background: linear-gradient(135deg, #193561 0%, #193561 100%) !important;
}

.display-1 {
    font-size: 4rem;
}

.success-details .card {
    border-left: 4px solid #193561;
}

.action-buttons .btn {
    border-radius: 10px;
    padding: 10px 25px;
    font-weight: 500;
}
</style>
@endsection