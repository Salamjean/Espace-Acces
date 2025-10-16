@extends('admin.layouts.template')
@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border: none; border-radius: 15px; background: linear-gradient(135deg, #193561 0%, #2c4b8a 100%);">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1 text-white">
                                <i class="bi bi-person-badge me-2"></i>Détails du Personnel
                            </h4>
                            <p class="mb-0 text-white opacity-75">{{ $personnel->prenom }} {{ $personnel->name }}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('admin.permanent-personnel.index') }}" class="btn btn-light me-2">
                                <i class="bi bi-arrow-left me-2"></i>Retour
                            </a>
                            <a href="{{ route('admin.permanent-personnel.card', $personnel->id) }}" 
                               class="btn btn-warning" target="_blank">
                                <i class="bi bi-card-image me-2"></i>Carte d'accès
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informations personnelles -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Informations Personnelles</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-4 fw-bold text-muted">Nom complet:</div>
                        <div class="col-8">{{ $personnel->prenom }} {{ $personnel->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold text-muted">Email:</div>
                        <div class="col-8">{{ $personnel->email }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold text-muted">Contact:</div>
                        <div class="col-8">{{ $personnel->contact }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold text-muted">Fonction:</div>
                        <div class="col-8">{{ $personnel->fonction }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold text-muted">Structure:</div>
                        <div class="col-8">{{ $personnel->structure }}</div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold text-muted">Adresse:</div>
                        <div class="col-8">{{ $personnel->adresse }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations d'accès -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0"><i class="bi bi-key-fill me-2"></i>Informations d'Accès</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-4 fw-bold text-muted">Code d'accès:</div>
                        <div class="col-8">
                            <code class="bg-light p-2 rounded d-inline-block">{{ $personnel->code_acces }}</code>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold text-muted">Type de pièce:</div>
                        <div class="col-8">{{ $personnel->type_piece }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold text-muted">N° pièce:</div>
                        <div class="col-8">{{ $personnel->numero_piece }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold text-muted">Date début:</div>
                        <div class="col-8">{{ $personnel->date_debut_permanent}}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-4 fw-bold text-muted">Date fin:</div>
                        <div class="col-8">
                            <span class="{{ $personnel->date_fin_permanent < now() ? 'text-danger' : 'text-success' }}">
                                {{ $personnel->date_fin_permanent}}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 fw-bold text-muted">Statut:</div>
                        <div class="col-8">
                            @if($personnel->statut == 'approuve' && $personnel->date_fin_permanent >= now())
                                <span class="badge bg-success">Actif</span>
                            @elseif($personnel->statut == 'approuve' && $personnel->date_fin_permanent < now())
                                <span class="badge bg-warning">Expiré</span>
                            @else
                                <span class="badge bg-danger">Inactif</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Motif et Actions -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0"><i class="bi bi-chat-text me-2"></i>Motif de l'Accès</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $personnel->motif_acces_permanent }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0"><i class="bi bi-gear me-2"></i>Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('admin.permanent-personnel.card', $personnel->id) }}" 
                               class="btn btn-outline-primary w-100" target="_blank">
                                <i class="bi bi-card-image me-2"></i>Voir Carte
                            </a>
                        </div>
                        {{-- <div class="col-md-3">
                            <a href="{{ route('admin.permanent-personnel.download-card', $personnel->id) }}" 
                               class="btn btn-outline-info w-100">
                                <i class="bi bi-download me-2"></i>Télécharger PDF
                            </a>
                        </div> --}}
                        @if($personnel->statut == 'approuve' && $personnel->date_fin_permanent >= now())
                            <div class="col-md-3">
                                <form action="{{ route('admin.permanent-personnel.desactivate', $personnel->id) }}" method="POST" class="d-inline w-100">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-warning w-100">
                                        <i class="bi bi-pause-circle me-2"></i>Désactiver
                                    </button>
                                </form>
                            </div>
                        @elseif($personnel->statut == 'rejete')
                            <div class="col-md-3">
                                <form action="{{ route('admin.permanent-personnel.activate', $personnel->id) }}" method="POST" class="d-inline w-100">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success w-100">
                                        <i class="bi bi-play-circle me-2"></i>Activer
                                    </button>
                                </form>
                            </div>
                        @endif
                        @if($personnel->date_fin_permanent < now())
                            <div class="col-md-3">
                                <form action="{{ route('admin.permanent-personnel.renew', $personnel->id) }}" method="POST" class="d-inline w-100">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-arrow-repeat me-2"></i>Renouveler
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 12px;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

.badge {
    font-size: 0.8rem;
    padding: 0.5em 0.8em;
}

.btn {
    border-radius: 8px;
    padding: 10px 15px;
}
</style>
@endsection