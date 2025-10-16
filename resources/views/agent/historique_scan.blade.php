@extends('agent.layouts.template')
@section('content')

<div class="container-fluid py-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="background-color: #193561;">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="h4 mb-1">Historique des Scans</h2>
                            <p class="mb-0 opacity-75">Consultez l'historique de tous vos scans</p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-light text-dark fs-6">Agent Connecté</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Total Scans</h6>
                            <h3 class="fw-bold text-primary">{{ $scans->total() }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-qrcode fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Entrées</h6>
                            <h3 class="fw-bold text-success">{{ $scans->where('type_scan', 'entree')->count() }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-sign-in-alt fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Sorties</h6>
                            <h3 class="fw-bold text-warning">{{ $scans->where('type_scan', 'sortie')->count() }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-sign-out-alt fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-2">Valides</h6>
                            <h3 class="fw-bold text-info">{{ $scans->where('est_valide', true)->count() }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-check-circle fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('agent.scan.historique') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Type de scan</label>
                                <select class="form-select" name="type_scan">
                                    <option value="">Tous les types</option>
                                    <option value="entree" {{ request('type_scan') == 'entree' ? 'selected' : '' }}>Entrée</option>
                                    <option value="sortie" {{ request('type_scan') == 'sortie' ? 'selected' : '' }}>Sortie</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Statut</label>
                                <select class="form-select" name="est_valide">
                                    <option value="">Tous les statuts</option>
                                    <option value="1" {{ request('est_valide') == '1' ? 'selected' : '' }}>Valide</option>
                                    <option value="0" {{ request('est_valide') == '0' ? 'selected' : '' }}>Invalide</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-filter"></i> Filtrer
                                </button>
                                <a href="{{ route('agent.scan.historique') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des scans -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">Derniers scans</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background-color: #193561; color: white;">
                                <tr>
                                    <th class="text-center">Porte</th>
                                    <th class="text-center">Type</th>
                                    <th class="text-center">Date/Heure</th>
                                    <th class="text-center">Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($scans as $scan)
                                <tr>
                                    <td class="text-center"><strong>{{ $scan->nom_porte }}</strong></td>
                                    <td class="text-center">
                                        @if($scan->type_scan === 'entree')
                                            <span class="badge bg-success">Entrée</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Sortie</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $scan->heure_scan->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        @if($scan->est_valide)
                                            <span class="badge bg-success">Valide</span>
                                        @else
                                            <span class="badge bg-danger">Invalide</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#scanDetailModal{{ $scan->id }}">
                                            <i class="fas fa-eye"></i> Détails
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>Aucun scan trouvé</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($scans->hasPages())
                    <div class="d-flex justify-content-between align-items-center p-3 border-top">
                        <div class="text-muted">
                            Affichage de {{ $scans->firstItem() }} à {{ $scans->lastItem() }} sur {{ $scans->total() }} résultats
                        </div>
                        <nav aria-label="Page navigation">
                            {{ $scans->links() }}
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals en dehors de la boucle et du conteneur principal -->
@foreach($scans as $scan)
<div class="modal fade" id="scanDetailModal{{ $scan->id }}" tabindex="-1" aria-labelledby="scanDetailModalLabel{{ $scan->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #193561; color: white;">
                <h5 class="modal-title" id="scanDetailModalLabel{{ $scan->id }}">Détails du Scan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informations du scan</h6>
                        <ul class="list-unstyled">
                            <li><strong>ID:</strong> {{ $scan->id }}</li>
                            <li><strong>Type:</strong> 
                                @if($scan->type_scan === 'entree')
                                    <span class="badge bg-success">Entrée</span>
                                @else
                                    <span class="badge bg-warning text-dark">Sortie</span>
                                @endif
                            </li>
                            <li><strong>Porte:</strong> {{ $scan->nom_porte }}</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Détails techniques</h6>
                        <ul class="list-unstyled">
                            <li><strong>Heure:</strong> {{ $scan->heure_scan->format('d/m/Y H:i:s') }}</li>
                            <li><strong>Statut:</strong> 
                                @if($scan->est_valide)
                                    <span class="badge bg-success">Valide</span>
                                @else
                                    <span class="badge bg-danger">Invalide</span>
                                @endif
                            </li>
                            <li><strong>Agent:</strong> Vous</li>
                            @if(!$scan->est_valide && $scan->raison_invalidite)
                                <li><strong>Raison:</strong> <span class="text-danger">{{ $scan->raison_invalidite }}</span></li>
                            @endif
                        </ul>
                    </div>
                </div>
                
                @if($scan->codeAcces)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Informations du code d'accès</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <p class="mb-1"><strong>Type:</strong> {{ $scan->codeAcces->type }}</p>
                                <p class="mb-0"><strong>Statut:</strong> 
                                    @if($scan->codeAcces->est_actif)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger">Inactif</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<style>
.card {
    border-radius: 12px;
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.badge {
    font-size: 0.75em;
}

.table th {
    border-bottom: 2px solid #193561;
    font-weight: 600;
}

.btn-primary {
    background-color: #193561;
    border-color: #193561;
}

.btn-primary:hover {
    background-color: #152a4d;
    border-color: #152a4d;
}

.pagination .page-link {
    color: #193561;
}

.pagination .page-item.active .page-link {
    background-color: #193561;
    border-color: #193561;
}

.modal-backdrop {
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}
</style>

<!-- Scripts chargés à la fin -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<script>
// Script pour gérer l'ouverture des modals
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser tous les modals Bootstrap
    var modals = document.querySelectorAll('.modal');
    modals.forEach(function(modal) {
        new bootstrap.Modal(modal);
    });
    
    // Debug: Vérifier si Bootstrap est chargé
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap non chargé');
    } else {
        console.log('Bootstrap chargé avec succès');
    }
});
</script>

@endsection