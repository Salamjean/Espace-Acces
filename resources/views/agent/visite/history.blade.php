@extends('agent.layouts.template')
@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border: none; border-radius: 15px; background: linear-gradient(135deg, #193561 0%, #2c4b8a 100%);margin-top:10px">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-12" style="display: flex;justify-content:space-between">
                            <h4 class="mb-1 text-white">
                                <i class="bi bi-clock-history me-2"></i>Historique des Visites
                            </h4>
                            <p class="mb-0 text-white opacity-75">Consultation des entrées et sorties des visiteurs</p>
                    
                            <div class="badge bg-light text-dark p-2">
                                <i class="bi bi-calendar me-1"></i>
                                {{ now()->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="stat-number text-primary">{{ $stats['total'] }}</div>
                            <div class="stat-label">Total Visites</div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="stat-icon">
                                <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="stat-number text-warning">{{ $stats['en_cours'] }}</div>
                            <div class="stat-label">En Cours</div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="stat-icon">
                                <i class="bi bi-person-check text-warning" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="stat-number text-success">{{ $stats['termine'] }}</div>
                            <div class="stat-label">Terminées</div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="stat-icon">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <div class="stat-number text-info">{{ $stats['aujourdhui'] }}</div>
                            <div class="stat-label">Aujourd'hui</div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="stat-icon">
                                <i class="bi bi-calendar-day text-info" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-funnel me-2"></i>Filtres</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('visite.history') }}" method="GET" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Date</label>
                                <input type="date" class="form-control" name="date" value="{{ $date }}" 
                                       style="border-radius: 8px; border: 2px solid #e9ecef;">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Statut</label>
                                <select class="form-control" name="statut" style="border-radius: 8px; border: 2px solid #e9ecef;">
                                    <option value="tous" {{ $statut == 'tous' ? 'selected' : '' }}>Tous les statuts</option>
                                    <option value="en_cours" {{ $statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="termine" {{ $statut == 'termine' ? 'selected' : '' }}>Terminé</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Recherche</label>
                                <input type="text" class="form-control" name="search" value="{{ $search }}" 
                                       placeholder="Nom, prénom, contact, n° pièce..." 
                                       style="border-radius: 8px; border: 2px solid #e9ecef;">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100" 
                                        style="border-radius: 8px; border: none; padding: 10px;">
                                    <i class="bi bi-search me-1"></i>Filtrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des visites -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-list-ul me-2"></i>Liste des Visites</h6>
                    <div class="text-muted small">
                        {{ $visites->total() }} résultat(s) trouvé(s)
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($visites->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th class="border-0 text-center">Visiteur</th>
                                        <th class="border-0 text-center">Contact</th>
                                        <th class="border-0 text-center">Pièce Identité</th>
                                        <th class="border-0 text-center">Heure Entrée</th>
                                        <th class="border-0 text-center">Heure Sortie</th>
                                        <th class="border-0 text-center">Durée</th>
                                        <th class="border-0 text-center">Statut</th>
                                        <th class="border-0 text-center">Agent</th>
                                        <th class="border-0 text-center">Actions</th>
                                    </tr>
                                </thead>
                                 <tbody>
                                    @foreach($visites as $visite)
                                    <tr>
                                        <td class="text-center">
                                            <div class="d-flex align-items-center" style="display:flex; justify-content:center">
                                                <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="bi bi-person text-muted"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $visite->personneDemande->prenom }} {{ $visite->personneDemande->name }}</div>
                                                    <small class="text-muted">{{ $visite->personneDemande->fonction }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="text-align:center">
                                            <div>{{ $visite->personneDemande->contact }}</div>
                                            <small class="text-muted">{{ $visite->personneDemande->email }}</small>
                                        </td>
                                        <td style="text-align:center">
                                            <div class="fw-bold">{{ $visite->type_piece }}</div>
                                            <small class="text-muted">{{ $visite->numero_piece }}</small>
                                        </td>
                                        <td style="text-align:center">
                                            <div class="fw-bold text-success">{{ $visite->date_entree->format('H:i') }}</div>
                                            <small class="text-muted">{{ $visite->date_entree->format('d/m/Y') }}</small>
                                        </td>
                                        <td style="text-align:center">
                                            @if($visite->date_sortie)
                                                <div class="fw-bold text-danger">{{ $visite->date_sortie->format('H:i') }}</div>
                                                <small class="text-muted">{{ $visite->date_sortie->format('d/m/Y') }}</small>
                                            @else
                                                <span class="badge bg-warning">En attente</span>
                                            @endif
                                        </td>
                                        <td  style="text-align:center">
                                            @if($visite->date_sortie)
                                                @php
                                                    $duree = $visite->date_entree->diffInMinutes($visite->date_sortie);
                                                    if($duree < 60) {
                                                        $duree_affichage = $duree . ' min';
                                                    } else {
                                                        $heures = floor($duree / 60);
                                                        $minutes = $duree % 60;
                                                        $duree_affichage = $heures . 'h' . ($minutes > 0 ? $minutes . 'min' : '');
                                                    }
                                                @endphp
                                                <span class="badge bg-info">{{ $duree_affichage }}</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td  style="text-align:center">
                                            @if($visite->statut == 'en_cours')
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-clock me-1"></i>En cours
                                                </span>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Terminé
                                                </span>
                                            @endif
                                        </td>
                                        <td  style="text-align:center">
                                            <small class="text-muted">{{ $visite->agent->name ?? 'N /' }} {{ $visite->agent->prenom ?? 'A' }}</small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="tooltip" 
                                                        title="Voir détails">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                @if($visite->statut == 'en_cours')
                                                    <a href="{{ route('visite.sortie') }}?code={{ $visite->personneDemande->code_acces }}" 
                                                       class="btn btn-sm btn-outline-danger"
                                                       data-bs-toggle="tooltip"
                                                       title="Enregistrer sortie">
                                                        <i class="bi bi-box-arrow-right"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Affichage de {{ $visites->firstItem() }} à {{ $visites->lastItem() }} sur {{ $visites->total() }} résultats
                                </div>
                                <div>
                                    {{ $visites->links() }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">Aucune visite trouvée</h5>
                            <p class="text-muted">Aucune visite ne correspond à vos critères de recherche.</p>
                            <a href="{{ route('visite.history') }}" class="btn btn-primary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Réinitialiser les filtres
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-submit du formulaire quand les filtres changent
    $('select[name="statut"]').change(function() {
        $('#filterForm').submit();
    });
    
    $('input[name="date"]').change(function() {
        $('#filterForm').submit();
    });
    
    // Tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Reset filters
    $('#resetFilters').click(function() {
        $('input[name="search"]').val('');
        $('select[name="statut"]').val('tous');
        $('input[name="date"]').val('');
        $('#filterForm').submit();
    });
});
</script>

<style>
.stat-card {
    border-radius: 12px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    line-height: 1;
}

.stat-label {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 500;
}

.avatar-sm {
    width: 40px;
    height: 40px;
}

.table th {
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    color: #6c757d;
    border-bottom: 2px solid #e9ecef;
}

.table td {
    vertical-align: middle;
    border-bottom: 1px solid #f8f9fa;
    padding: 1rem 0.75rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

.btn-group .btn {
    border-radius: 6px;
    margin: 0 2px;
}

.card {
    border-radius: 12px;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

.form-control, .form-select {
    border-radius: 8px;
}

/* Responsive */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.85rem;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
}
</style>
@endsection