@extends('admin.layouts.template')
@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<!-- Token CSRF pour les requêtes AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="demandes-admin-container">
    <div class="container-fluid">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="main-title">Gestion des Personnes</h1>
                <p class="subtitle">Consultez et gérez toutes les personnes des demandes d'accès</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" id="filterToggle">
                    <i class="bi bi-funnel"></i> Filtres
                </button>
                <button class="btn btn-primary" id="exportBtn">
                    <i class="bi bi-download"></i> Exporter
                </button>
            </div>
        </div>

        <!-- Filtres -->
        <div class="filter-card mb-4" id="filterSection" style="display: none;">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
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
                        <div class="col-md-3">
                            <label for="filterStructure" class="form-label">Structure</label>
                            <select class="form-control" id="filterStructure">
                                <option value="">Toutes les structures</option>
                                @foreach($structures as $structure)
                                    <option value="{{ $structure }}" {{ request('structure') == $structure ? 'selected' : '' }}>
                                        {{ $structure }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filterGroupe" class="form-label">Groupe ID</label>
                            <select class="form-control" id="filterGroupe">
                                <option value="">Tous les groupes</option>
                                @foreach($groupes as $groupe)
                                    <option value="{{ $groupe }}" {{ request('groupe_id') == $groupe ? 'selected' : '' }}>
                                        {{ $groupe }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filterTypeDemande" class="form-label">Type de demande</label>
                            <select class="form-control" id="filterTypeDemande">
                                <option value="">Tous les types</option>
                                <option value="individuelle" {{ request('type_demande') == 'individuelle' ? 'selected' : '' }}>Individuelle</option>
                                <option value="groupe" {{ request('type_demande') == 'groupe' ? 'selected' : '' }}>En groupe</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label for="filterDateDebut" class="form-label">Date de début</label>
                            <input type="date" class="form-control" id="filterDateDebut" value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="filterDateFin" class="form-label">Date de fin</label>
                            <input type="date" class="form-control" id="filterDateFin" value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="filterSearch" class="form-label">Recherche</label>
                            <input type="text" class="form-control" id="filterSearch" placeholder="Nom, prénom, email, contact, structure..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="w-100">
                                <a href="{{ route('admin.demandes.index') }}" class="btn btn-secondary w-100 mb-2">Réinitialiser</a>
                                <button class="btn btn-primary w-100" id="applyFilters">Appliquer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="stat-card">
                    <div class="stat-icon total">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $totalPersonnes }}</h3>
                        <p>Total Personnes</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <div class="stat-icon pending">
                        <i class="bi bi-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $personnesEnAttente }}</h3>
                        <p>En attente</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <div class="stat-icon approved">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $personnesApprouvees }}</h3>
                        <p>Approuvées</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <div class="stat-icon rejected">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $personnesRejetees + $personnesAnnulees }}</h3>
                        <p>Rejetées et Annulées</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <div class="stat-icon individual">
                        <i class="bi bi-person"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $demandesIndividuelles }}</h3>
                        <p>Individuelles</p>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card">
                    <div class="stat-icon group">
                        <i class="bi bi-people" ></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $demandesGroupe }}</h3>
                        <p>En groupe</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau des personnes -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="demandesTable">
                        <thead>
                            <tr>
                                <th style="text-align: center; color:white">Numéro Demande</th>
                                <th style="text-align: center; color:white">Personne</th>
                                <th style="text-align: center; color:white">Contact</th>
                                <th style="text-align: center; color:white">Structure</th>
                                <th style="text-align: center; color:white">Fonction</th>
                                <th style="text-align: center; color:white">Type</th>
                                <th style="text-align: center; color:white">Date Visite</th>
                                <th style="text-align: center; color:white">Heure</th>
                                <th style="text-align: center; color:white">Motif</th>
                                <th style="text-align: center; color:white">Statut</th>
                                <th style="text-align: center; color:white">Numéro de ticket</th>
                                <th style="text-align: center; color:white">Date Soumission</th>
                                <th style="text-align: center; color:white">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($personnesDemandes as $personne)
                            <tr>
                                <td style="text-align: center">
                                    <strong>{{ $personne->numero_demande }}</strong>
                                    @if($personne->nbre_perso > 1)
                                    <br>
                                    <small class="text-muted">Groupe: {{ Str::limit($personne->groupe_id, 8) }}</small>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <div class="d-flex align-items-center">
                                        @if($personne->profile_picture)
                                            <img src="{{ asset('storage/' . $personne->profile_picture) }}" 
                                                 class="avatar-img rounded-circle me-2" 
                                                 alt="{{ $personne->prenom }} {{ $personne->name }}"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="avatar-circle me-2">
                                                {{ strtoupper(substr($personne->prenom, 0, 1)) }}{{ strtoupper(substr($personne->name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="text-start">
                                            <strong>{{ $personne->prenom }} {{ $personne->name }}</strong>
                                            @if($personne->est_demandeur_principal)
                                            <br>
                                            <span class="badge bg-primary text-white">Principal</span>
                                            @endif
                                            <br>
                                            <small class="text-muted">{{ $personne->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: center">{{ $personne->contact }}</td>
                                <td style="text-align: center">
                                    <span class="badge bg-info text-white" title="{{ $personne->structure }}">
                                        {{ Str::limit($personne->structure, 20) }}
                                    </span>
                                </td>
                                <td style="text-align: center">
                                    <small>{{ $personne->fonction }}</small>
                                </td>
                                <td style="text-align: center; color:white">
                                    @if($personne->nbre_perso == 1)
                                        <span class="badge bg-success">Individuelle</span>
                                    @else
                                        <span class="badge bg-warning text-white">Groupe ({{ $personne->nbre_perso }})</span>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <strong>{{ \Carbon\Carbon::parse($personne->date_visite)->format('d/m/Y') }}</strong>
                                    @if($personne->date_fin_visite && $personne->date_fin_visite != $personne->date_visite)
                                    <br>
                                    <small>au {{ \Carbon\Carbon::parse($personne->date_fin_visite)->format('d/m/Y') }}</small>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    {{ $personne->heure_visite }}
                                    @if($personne->heure_fin_visite)
                                    <br>
                                    <small>à {{ $personne->heure_fin_visite }}</small>
                                    @endif
                                </td>
                                <td style="text-align: center">
                                    <span class="motif-text" title="{{ $personne->motif_visite }}">
                                        {{ Str::limit($personne->motif_visite, 25) }}
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
                                    <span class="badge {{ $statutClasses[$personne->statut] ?? 'bg-secondary' }}">
                                        {{ ucfirst(str_replace('_', ' ', $personne->statut)) }}
                                    </span>
                                </td>
                                <td style="text-align: center">
                                    <small>{{ $personne->numero_ticket ?? 'Pas de ticket' }}</small>
                                </td>
                                <td style="text-align: center">
                                    <small>{{ $personne->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td style="text-align: center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.request.show', $personne->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Voir les détails">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        
                                        @if($personne->statut == 'en_attente')
                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                title="Approuver"
                                                onclick="showApproveConfirmation({{ $personne->id }})">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                title="Rejeter"
                                                onclick="showRejectPrompt({{ $personne->id }})">
                                            <i class="bi bi-x"></i>
                                        </button>
                                        @endif
                                        
                                        @if(in_array($personne->statut, ['approuve', 'en_attente']))
                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                title="Annuler"
                                                onclick="showCancelConfirmation({{ $personne->id }})">
                                            <i class="bi bi-slash-circle"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($personnesDemandes->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Affichage de <strong>{{ $personnesDemandes->firstItem() }}</strong> à <strong>{{ $personnesDemandes->lastItem() }}</strong> sur <strong>{{ $personnesDemandes->total() }}</strong> personnes
                    </div>
                    
                    <nav aria-label="Pagination">
                        <ul class="pagination custom-pagination">
                            <!-- Premier page -->
                            <li class="page-item {{ $personnesDemandes->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $personnesDemandes->url(1) }}" aria-label="Premier">
                                    <i class="bi bi-chevron-double-left"></i>
                                </a>
                            </li>

                            <!-- Page précédente -->
                            <li class="page-item {{ $personnesDemandes->onFirstPage() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $personnesDemandes->previousPageUrl() }}" aria-label="Précédent">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>

                            <!-- Pages numérotées -->
                            @php
                                $current = $personnesDemandes->currentPage();
                                $last = $personnesDemandes->lastPage();
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
                                <li class="page-item {{ $i == $personnesDemandes->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $personnesDemandes->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            <!-- Points de suspension fin -->
                            @if($end < $last)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif

                            <!-- Page suivante -->
                            <li class="page-item {{ !$personnesDemandes->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $personnesDemandes->nextPageUrl() }}" aria-label="Suivant">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>

                            <!-- Dernière page -->
                            <li class="page-item {{ !$personnesDemandes->hasMorePages() ? 'disabled' : '' }}">
                                <a class="page-link" href="{{ $personnesDemandes->url($last) }}" aria-label="Dernier">
                                    <i class="bi bi-chevron-double-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>

                    <!-- Sélecteur d'éléments par page -->
                    <div class="d-flex align-items-center">
                        <span class="text-muted me-2">Afficher :</span>
                        <select class="form-select form-select-sm items-per-page-select" style="width: auto;">
                            <option value="10" {{ request('per_page', 20) == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                            <option value="50" {{ request('per_page', 20) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page', 20) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
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
.demandes-admin-container {
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

/* Responsive pour la pagination */
@media (max-width: 768px) {
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
    
    .custom-pagination .page-link {
        padding: 0.375rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .pagination {
        flex-wrap: wrap;
        justify-content: center;
    }
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
.stat-icon.completed { background: #0dcaf0; }
.stat-icon.individual { background: #0dcaf0; }
.stat-icon.cancelled { background: #6c757d; }
.stat-icon.group { background: #6c757d; }

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
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #193561;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
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

/* Boutons */
.btn-group .btn {
    border-radius: 5px;
    margin: 0 2px;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
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
    .demandes-admin-container {
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
    
    .btn-group {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .btn-group .btn {
        margin: 0;
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
}
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Changer le nombre d'éléments par page
$('.items-per-page-select').change(function() {
    const perPage = $(this).val();
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', perPage);
    url.searchParams.set('page', '1'); // Retour à la première page
    window.location.href = url.toString();
});

// Modifiez la fonction applyFilters pour inclure la réinitialisation de la page
function applyFilters() {
    const statut = $('#filterStatut').val();
    const structure = $('#filterStructure').val();
    const groupe = $('#filterGroupe').val();
    const typeDemande = $('#filterTypeDemande').val();
    const dateDebut = $('#filterDateDebut').val();
    const dateFin = $('#filterDateFin').val();
    const search = $('#filterSearch').val();

    const url = new URL(window.location.href);
    if (statut) url.searchParams.set('statut', statut);
    if (structure) url.searchParams.set('structure', structure);
    if (groupe) url.searchParams.set('groupe_id', groupe);
    if (typeDemande) url.searchParams.set('type_demande', typeDemande);
    if (dateDebut) url.searchParams.set('date_debut', dateDebut);
    if (dateFin) url.searchParams.set('date_fin', dateFin);
    if (search) url.searchParams.set('search', search);
    
    // Réinitialiser à la page 1 lors de l'application des filtres
    url.searchParams.set('page', '1');

    window.location.href = url.toString();
}
// Fonctions globales SweetAlert2
function showApproveConfirmation(demandeId) {
    Swal.fire({
        title: 'Confirmer l\'approbation',
        text: 'Êtes-vous sûr de vouloir approuver cette demande ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Oui, approuver',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            approveDemande(demandeId);
        }
    });
}

function showRejectPrompt(demandeId) {
    Swal.fire({
        title: 'Motif du rejet',
        input: 'textarea',
        inputLabel: 'Veuillez saisir le motif du rejet',
        inputPlaceholder: 'Saisissez le motif ici...',
        inputAttributes: {
            'aria-label': 'Saisissez le motif du rejet'
        },
        showCancelButton: true,
        confirmButtonText: 'Rejeter',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        inputValidator: (value) => {
            if (!value) {
                return 'Le motif du rejet est obligatoire !';
            }
            if (value.length > 1000) {
                return 'Le motif ne peut pas dépasser 1000 caractères !';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            rejectDemande(demandeId, result.value);
        }
    });
}

function showCancelConfirmation(demandeId) {
    Swal.fire({
        title: 'Confirmer l\'annulation',
        text: 'Êtes-vous sûr de vouloir annuler cette demande ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Oui, annuler',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            cancelDemande(demandeId);
        }
    });
}

// Fonctions AJAX
function approveDemande(demandeId) {
    const url = `{{ url('admin/request') }}/${demandeId}/approve`;
    
    Swal.fire({
        title: 'Traitement en cours...',
        text: 'Approximation de la demande',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
        success: function(data) {
            Swal.close();
            if (data.success) {
                Swal.fire({
                    title: 'Succès !',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#198754'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Erreur !',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.close();
            Swal.fire({
                title: 'Erreur !',
                text: 'Une erreur est survenue lors de l\'approbation de la demande.',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        }
    });
}

function rejectDemande(demandeId, motif) {
    const url = `{{ url('admin/request') }}/${demandeId}/reject`;
    
    Swal.fire({
        title: 'Traitement en cours...',
        text: 'Rejet de la demande',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            motif_rejet: motif
        },
        dataType: 'json',
        success: function(data) {
            Swal.close();
            if (data.success) {
                Swal.fire({
                    title: 'Succès !',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#198754'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Erreur !',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.close();
            Swal.fire({
                title: 'Erreur !',
                text: 'Une erreur est survenue lors du rejet de la demande.',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        }
    });
}

function cancelDemande(demandeId) {
    const url = `{{ url('admin/request') }}/${demandeId}/cancel`;
    
    Swal.fire({
        title: 'Traitement en cours...',
        text: 'Annulation de la demande',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
        success: function(data) {
            Swal.close();
            if (data.success) {
                Swal.fire({
                    title: 'Succès !',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#198754'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Erreur !',
                    text: data.message,
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.close();
            Swal.fire({
                title: 'Erreur !',
                text: 'Une erreur est survenue lors de l\'annulation de la demande.',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        }
    });
}

$(document).ready(function() {
    // Toggle des filtres
    $('#filterToggle').click(function() {
        $('#filterSection').toggle();
    });

    // Appliquer les filtres
    $('#applyFilters').click(function() {
        applyFilters();
    });

    function applyFilters() {
        const statut = $('#filterStatut').val();
        const dateDebut = $('#filterDateDebut').val();
        const dateFin = $('#filterDateFin').val();
        const search = $('#filterSearch').val();

        const url = new URL(window.location.href);
        if (statut) url.searchParams.set('statut', statut);
        if (dateDebut) url.searchParams.set('date_debut', dateDebut);
        if (dateFin) url.searchParams.set('date_fin', dateFin);
        if (search) url.searchParams.set('search', search);

        window.location.href = url.toString();
    }

    // Exporter les données
    $('#exportBtn').click(function() {
        Swal.fire({
            title: 'Exportation',
            text: 'Fonction d\'export à implémenter',
            icon: 'info',
            confirmButtonText: 'OK',
            confirmButtonColor: '#193561'
        });
    });

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