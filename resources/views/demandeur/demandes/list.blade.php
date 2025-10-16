@extends('demandeur.layouts.template')
@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<!-- Token CSRF pour les requêtes AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="demandes-demandeur-container">
    <div class="container-fluid">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="main-title">Mes Demandes</h1>
                <p class="subtitle">Consultez l'état de vos demandes d'accès</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" id="filterToggle">
                    <i class="bi bi-funnel"></i> Filtres
                </button>
                <a href="{{ route('demandes.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Nouvelle Demande
                </a>
            </div>
        </div>

        <!-- Filtres -->
        <div class="filter-card mb-4" id="filterSection" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="filterStatut" class="form-label">Statut</label>
                            <select class="form-control" id="filterStatut">
                                <option value="">Tous les statuts</option>
                                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                <option value="approuve" {{ request('statut') == 'approuve' ? 'selected' : '' }}>Approuvé</option>
                                <option value="rejete" {{ request('statut') == 'rejete' ? 'selected' : '' }}>Rejeté</option>
                                <option value="annule" {{ request('statut') == 'annule' ? 'selected' : '' }}>Annulé</option>
                                <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="filterTypeDemande" class="form-label">Type de demande</label>
                            <select class="form-control" id="filterTypeDemande">
                                <option value="">Tous les types</option>
                                <option value="individuelle" {{ request('type_demande') == 'individuelle' ? 'selected' : '' }}>Individuelle</option>
                                <option value="groupe" {{ request('type_demande') == 'groupe' ? 'selected' : '' }}>En groupe</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="w-100">
                                <a href="{{ route('demandes.list') }}" class="btn btn-secondary w-100 mb-2">Réinitialiser</a>
                                <button class="btn btn-primary w-100" id="applyFilters">Appliquer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques personnelles -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon total">
                        <i class="bi bi-list-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $totalDemandes }}</h3>
                        <p>Total Demandes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon pending">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $demandesEnAttente }}</h3>
                        <p>En attente</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon approved">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $demandesApprouvees }}</h3>
                        <p>Approuvées</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon rejected">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $demandesRejetees + $demandesAnnulees }}</h3>
                        <p>Rejetées et Annulées</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des demandes -->
        <div class="card">
            <div class="card-body">
                @if($demandes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" id="demandesTable">
                        <thead>
                            <tr>
                                <th style="text-align: center; color:white">Numéro Demande</th>
                                <th style="text-align: center; color:white">Type</th>
                                <th style="text-align: center; color:white">Personnes</th>
                                <th style="text-align: center; color:white">Date Visite</th>
                                <th style="text-align: center; color:white">Heure</th>
                                <th style="text-align: center; color:white">Motif</th>
                                <th style="text-align: center; color:white">Statut</th>
                                <th style="text-align: center; color:white">Date Soumission</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($demandes as $demande)
                            <tr>
                                <td style="text-align: center">
                                    <strong>{{ $demande->numero_demande }}</strong>
                                    @if($demande->nbre_perso > 1)
                                    <br>
                                    <small class="text-muted">Groupe: {{ Str::limit($demande->groupe_id, 8) }}</small>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    @if($demande->nbre_perso == 1)
                                        <span class="badge bg-success">Individuelle</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Groupe ({{ $demande->nbre_perso }})</span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            @if($demande->profile_picture)
                                                <img src="{{ asset('storage/' . $demande->profile_picture) }}" 
                                                     class="avatar-img rounded-circle me-2" 
                                                     alt="{{ $demande->prenom }} {{ $demande->name }}"
                                                     style="width: 35px; height: 35px; object-fit: cover;">
                                            @else
                                                <div class="avatar-circle me-2">
                                                    {{ strtoupper(substr($demande->prenom, 0, 1)) }}{{ strtoupper(substr($demande->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div class="text-start">
                                                <strong>{{ $demande->prenom }} {{ $demande->name }}</strong><br>
                                                <small class="text-muted">{{ $demande->email }}</small>
                                            </div>
                                        </div>
                                </td>
                                <td style="text-align: center">
                                    <strong>{{ \Carbon\Carbon::parse($demande->date_visite)->format('d/m/Y') }}</strong>
                                    @if($demande->date_fin_visite && $demande->date_fin_visite != $demande->date_visite)
                                    <br>
                                    <small>au {{ \Carbon\Carbon::parse($demande->date_fin_visite)->format('d/m/Y') }}</small>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    {{ $demande->heure_visite }}
                                    @if($demande->heure_fin_visite)
                                    <br>
                                    <small>à {{ $demande->heure_fin_visite }}</small>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <span class="motif-text" title="{{ $demande->motif_visite }}">
                                        {{ Str::limit($demande->motif_visite, 30) }}
                                    </span>
                                </td>
                                <td style="text-align: center; color:white">
                                    @php
                                        $statutClasses = [
                                            'en_attente' => 'bg-warning',
                                            'approuve' => 'bg-success',
                                            'rejete' => 'bg-danger',
                                            'annule' => 'bg-secondary',
                                            'termine' => 'bg-info'
                                        ];
                                    @endphp
                                    <span class="badge {{ $statutClasses[$demande->statut] ?? 'bg-secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $demande->statut)) }}
                                    </span>
                                </td>
                                <td style="text-align: center">
                                    <small>{{ $demande->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination personnalisée -->
                @if($demandes->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Affichage de <strong>{{ $demandes->firstItem() }}</strong> à <strong>{{ $demandes->lastItem() }}</strong> sur <strong>{{ $demandes->total() }}</strong> demandes
                    </div>
                    
                    <nav aria-label="Pagination">
                        <ul class="pagination custom-pagination">
                            <!-- Premier page -->
                            <li class="page-item {{ $demandes->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $demandes->url(1) }}" aria-label="Premier">
                                    <i class="bi bi-chevron-double-left"></i>
                                </a>
                            </li>

                            <!-- Page précédente -->
                            <li class="page-item {{ $demandes->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $demandes->previousPageUrl() }}" aria-label="Précédent">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>

                            <!-- Pages numérotées -->
                            @php
                                $current = $demandes->currentPage();
                                $last = $demandes->lastPage();
                                $start = max($current - 2, 1);
                                $end = min($current + 2, $last);
                                
                                if($start > 1) {
                                    $start = max($current - 1, 1);
                                    $end = min($current + 1, $last);
                                }
                                
                                if($end == $last && $last > 3) {
                                    $start = max($last - 2, 1);
                                }
                            @endphp

                            <!-- Points de suspension début -->
                            @if($start > 1)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif

                            <!-- Pages -->
                            @for ($i = $start; $i <= $end; $i++)
                                <li class="page-item {{ $i == $demandes->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $demandes->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            <!-- Points de suspension fin -->
                            @if($end < $last)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif

                            <!-- Page suivante -->
                            <li class="page-item {{ !$demandes->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $demandes->nextPageUrl() }}" aria-label="Suivant">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>

                            <!-- Dernière page -->
                            <li class="page-item {{ !$demandes->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $demandes->url($last) }}" aria-label="Dernier">
                                    <i class="bi bi-chevron-double-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <!-- Sélecteur d'éléments par page -->
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-2">Afficher :</span>
                        <select class="form-select form-select-sm items-per-page-select" style="width: auto;">
                            <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                </div>
                @endif
                @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h3 class="text-muted mt-3">Aucune demande trouvée</h3>
                    <p class="text-muted">Vous n'avez pas encore soumis de demande d'accès.</p>
                    <a href="{{ route('demandes.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle"></i> Créer votre première demande
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal pour voir les détails -->
<div class="modal fade" id="viewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails de la Demande</h5>
                <button type="button" class="btn-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Contenu chargé via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<style>
.demandes-demandeur-container {
    background: #f8f9fa;
    min-height: calc(100vh - 76px);
    padding: 20px 0;
}

.main-title {
    color: #193561;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.subtitle {
    color: #6c757d;
    margin-bottom: 0;
}

/* Cartes de statistiques */
.stat-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    height: 100%;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-icon.total { background: #193561; }
.stat-icon.pending { background: #ffc107; }
.stat-icon.approved { background: #198754; }
.stat-icon.rejected { background: #dc3545; }

.stat-info h3 {
    margin: 0;
    font-weight: 700;
    color: #193561;
}

.stat-info p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
}

/* Tableau */
.table th {
    background: #193561;
    color: white;
    border: none;
    font-weight: 600;
    padding: 1rem 0.75rem;
}

.table td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.avatar-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: #193561;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.8rem;
}

.motif-text {
    font-weight: 500;
    color: #193561;
}

/* Badges */
.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

/* Pagination personnalisée */
.custom-pagination {
    margin-bottom: 0;
}

.custom-pagination .page-item.active .page-link {
    background-color: #193561;
    border-color: #193561;
    color: white;
}

.custom-pagination .page-link {
    color: #193561;
    border: 1px solid #dee2e6;
    padding: 0.5rem 0.75rem;
    margin: 0 2px;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.custom-pagination .page-link:hover {
    background-color: #e9ecef;
    border-color: #193561;
    color: #193561;
}

.custom-pagination .page-item.disabled .page-link {
    color: #6c757d;
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

/* Sélecteur d'éléments par page */
.items-per-page-select {
    width: 80px !important;
}

/* Filtres */
.filter-card .card {
    border: 2px solid #e9ecef;
    border-radius: 10px;
}

.filter-card .card-body {
    padding: 1.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .demandes-demandeur-container {
        padding: 10px 0;
    }
    
    .stat-card {
        flex-direction: column;
        text-align: center;
        padding: 1rem;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .d-flex.justify-content-between.align-items-center.mt-4 {
        flex-direction: column;
        gap: 1rem;
        align-items: center !important;
    }
    
    .custom-pagination {
        order: -1;
    }
    
    .items-per-page-select {
        margin-top: 1rem;
    }
}

@media (max-width: 576px) {
    .d-flex.justify-content-between.align-items-center.mb-4 {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start !important;
    }
    
    .main-title {
        font-size: 1.5rem;
    }
    
    .custom-pagination .page-link {
        padding: 0.375rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }
}
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Toggle des filtres
    $('#filterToggle').click(function() {
        $('#filterSection').toggle();
    });

    // Appliquer les filtres
    $('#applyFilters').click(function() {
        applyFilters();
    });

    // Changer le nombre d'éléments par page
    $('.items-per-page-select').change(function() {
        const perPage = $(this).val();
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', '1'); // Retour à la première page
        window.location.href = url.toString();
    });

    function applyFilters() {
        const statut = $('#filterStatut').val();
        const typeDemande = $('#filterTypeDemande').val();

        const url = new URL(window.location.href);
        if (statut) url.searchParams.set('statut', statut);
        if (typeDemande) url.searchParams.set('type_demande', typeDemande);
        
        // Réinitialiser à la page 1 lors de l'application des filtres
        url.searchParams.set('page', '1');

        window.location.href = url.toString();
    }

    // SweetAlert notifications pour les messages de session
    @if (Session::has('success'))
        Swal.fire({
            icon: 'success',
            title: 'Succès',
            text: '{{ Session::get('success') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#198754',
        });
    @endif

    @if (Session::has('error'))
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: '{{ Session::get('error') }}',
            confirmButtonText: 'OK',
            confirmButtonColor: '#dc3545',
        });
    @endif
});
</script>
@endsection