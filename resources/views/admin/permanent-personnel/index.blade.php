@extends('admin.layouts.template')
@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<div class="container-fluid" style="margin-top:10px">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border: none; border-radius: 15px; background: linear-gradient(135deg, #193561 0%, #2c4b8a 100%);">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1 text-white">
                                <i class="bi bi-people-fill me-2"></i>Personnel Permanent
                            </h4>
                            <p class="mb-0 text-white opacity-75">Gestion des accès permanents (3 mois)</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('admin.permanent-personnel.create') }}" class="btn btn-light">
                                <i class="bi bi-person-plus me-2"></i>Nouveau Personnel
                            </a>
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
                            <div class="stat-label">Total Personnel</div>
                        </div>
                        <div class="col-4 text-end">
                            <i class="bi bi-people-fill text-primary" style="font-size: 2rem;"></i>
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
                            <div class="stat-number text-success">{{ $stats['actif'] }}</div>
                            <div class="stat-label">Accès Actifs</div>
                        </div>
                        <div class="col-4 text-end">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 2rem;"></i>
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
                            <div class="stat-number text-warning">{{ $stats['expire'] }}</div>
                            <div class="stat-label">Accès Expirés</div>
                        </div>
                        <div class="col-4 text-end">
                            <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 2rem;"></i>
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
                            <div class="stat-number text-info">{{ $stats['en_attente'] }}</div>
                            <div class="stat-label">En Attente</div>
                        </div>
                        <div class="col-4 text-end">
                            <i class="bi bi-clock-history text-info" style="font-size: 2rem;"></i>
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
                    <form action="{{ route('admin.permanent-personnel.index') }}" method="GET" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Recherche</label>
                                <input type="text" class="form-control" name="search" value="{{ $search }}" 
                                       placeholder="Nom, prénom, email, code..." 
                                       style="border-radius: 8px; border: 2px solid #e9ecef;">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Statut</label>
                                <select class="form-control" name="statut" style="border-radius: 8px; border: 2px solid #e9ecef;">
                                    <option value="tous" {{ $statut == 'tous' ? 'selected' : '' }}>Tous les statuts</option>
                                    <option value="actif" {{ $statut == 'actif' ? 'selected' : '' }}>Actif</option>
                                    <option value="expire" {{ $statut == 'expire' ? 'selected' : '' }}>Expiré</option>
                                    <option value="en_attente" {{ $statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="rejete" {{ $statut == 'rejete' ? 'selected' : '' }}>Rejeté</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2" 
                                        style="border-radius: 8px; border: none; padding: 10px 20px;">
                                    <i class="bi bi-search me-1"></i>Filtrer
                                </button>
                                <a href="{{ route('admin.permanent-personnel.index') }}" class="btn btn-outline-secondary"
                                   style="border-radius: 8px; padding: 10px 20px;">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-list-ul me-2"></i>Liste du Personnel Permanent</h6>
                    <div class="text-muted small">
                        {{ $personnels->total() }} résultat(s) trouvé(s)
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($personnels->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th class="border-0">Personnel</th>
                                        <th class="border-0">Contact</th>
                                        <th class="border-0">Fonction</th>
                                        <th class="border-0">Code Accès</th>
                                        <th class="border-0">Validité</th>
                                        <th class="border-0">Statut</th>
                                        <th class="border-0 text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($personnels as $personnel)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="bi bi-person text-muted"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $personnel->prenom }} {{ $personnel->name }}</div>
                                                    <small class="text-muted">{{ $personnel->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>{{ $personnel->contact }}</div>
                                            <small class="text-muted">{{ $personnel->structure }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $personnel->fonction }}</div>
                                            <small class="text-muted">{{ $personnel->numero_piece }}</small>
                                        </td>
                                        <td>
                                            <code class="bg-light p-1 rounded">{{ $personnel->code_acces }}</code>
                                        </td>
                                        <td>
                                            <div class="fw-bold {{ $personnel->date_fin_permanent < now() ? 'text-danger' : 'text-success' }}">
                                                {{ $personnel->date_fin_permanent }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $personnel->duree_restante }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($personnel->statut == 'approuve' && $personnel->date_fin_permanent >= now())
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>Actif
                                                </span>
                                            @elseif($personnel->statut == 'approuve' && $personnel->date_fin_permanent < now())
                                                <span class="badge bg-warning">
                                                    <i class="bi bi-clock me-1"></i>Expiré
                                                </span>
                                            @elseif($personnel->statut == 'en_attente')
                                                <span class="badge bg-info">
                                                    <i class="bi bi-hourglass me-1"></i>En attente
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-x-circle me-1"></i>Rejeté
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                                <a href="{{ route('admin.permanent-personnel.show', $personnel->id) }}" 
                                                   class="btn btn-sm btn-outline-primary"
                                                   data-bs-toggle="tooltip" title="Voir détails">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.permanent-personnel.card', $personnel->id) }}" 
                                                   class="btn btn-sm btn-outline-info"
                                                   target="_blank"
                                                   data-bs-toggle="tooltip" title="Générer carte">
                                                    <i class="bi bi-card-image"></i>
                                                </a>
                                                @if($personnel->statut == 'approuve' && $personnel->date_fin_permanent >= now())
                                                    <form action="{{ route('admin.permanent-personnel.desactivate', $personnel->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-warning" 
                                                                data-bs-toggle="tooltip" title="Désactiver">
                                                            <i class="bi bi-pause-circle"></i>
                                                        </button>
                                                    </form>
                                                @elseif($personnel->statut == 'rejete')
                                                    <form action="{{ route('admin.permanent-personnel.activate', $personnel->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-success" 
                                                                data-bs-toggle="tooltip" title="Activer">
                                                            <i class="bi bi-play-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($personnel->date_fin_permanent < now())
                                                    <form action="{{ route('admin.permanent-personnel.renew', $personnel->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-outline-primary" 
                                                                data-bs-toggle="tooltip" title="Renouveler">
                                                            <i class="bi bi-arrow-repeat"></i>
                                                        </button>
                                                    </form>
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
                                    Affichage de {{ $personnels->firstItem() }} à {{ $personnels->lastItem() }} sur {{ $personnels->total() }} résultats
                                </div>
                                <div>
                                    {{ $personnels->links('pagination.custom') }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-people display-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">Aucun personnel permanent trouvé</h5>
                            <p class="text-muted">Aucun personnel ne correspond à vos critères de recherche.</p>
                            <a href="{{ route('admin.permanent-personnel.create') }}" class="btn btn-primary me-2">
                                <i class="bi bi-person-plus me-1"></i>Ajouter un personnel
                            </a>
                            <a href="{{ route('admin.permanent-personnel.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Réinitialiser
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
    
    // Tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Messages de succès
    @if(session('success'))
        alert('{{ session('success') }}');
    @endif
    
    @if(session('code_acces'))
        alert('Personnel créé avec succès! Code d\\'accès': {{ session('code_acces') }}');
    @endif
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

/* Pagination personnalisée */
.pagination {
    display: flex;
    padding-left: 0;
    list-style: none;
    border-radius: 8px;
}

.page-link {
    position: relative;
    display: block;
    padding: 0.5rem 0.75rem;
    margin-left: -1px;
    line-height: 1.25;
    color: #193561;
    background-color: white;
    border: 1px solid #dee2e6;
    text-decoration: none;
    transition: all 0.3s ease;
}

.page-link:hover {
    z-index: 2;
    color: white;
    background-color: #193561;
    border-color: #193561;
}

.page-item.active .page-link {
    z-index: 3;
    color: white;
    background-color: #193561;
    border-color: #193561;
}

.page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: white;
    border-color: #dee2e6;
}

.page-link:focus {
    z-index: 3;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(25, 53, 97, 0.25);
}

/* Style spécifique pour la pagination Laravel */
.pagination .page-item:first-child .page-link {
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
}

.pagination .page-item:last-child .page-link {
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
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