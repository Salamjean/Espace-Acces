@extends('admin.layouts.template')
@section('content')
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('index/index.css')}}">
<!-- Bootstrap JS (optionnel, pour certaines fonctionnalités) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<div class="container-fluid">
    <!-- Header avec navigation -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-title">
                <h1 class="page-title">
                    <i class="fas fa-users icon-primary"></i>
                    Gestion des Demandeurs
                </h1>
                <p class="page-subtitle">Liste de tous les demandeurs enregistrés dans le système</p>
            </div>
            <a href="{{ route('demandeur.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i>
                Nouveau Demandeur
            </a>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon total">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $demandeurs->total() }}</div>
                    <div class="stat-label">Total Demandeurs</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon active">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $demandeurs->where('archived_at', null)->count() }}</div>
                    <div class="stat-label">Demandeurs Actifs</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon archived">
                    <i class="fas fa-archive"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $demandeurs->where('archived_at', '!=', null)->count() }}</div>
                    <div class="stat-label">Demandeurs Archivés</div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon recent">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $demandeurs->where('created_at', '>=', now()->subMonth())->count() }}</div>
                    <div class="stat-label">Ajoutés ce mois</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et recherche -->
    <div class="card filters-card">
        <div class="card-body">
            <form method="GET" action="{{ route('demandeur.index') }}" id="filterForm">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" name="search" value="{{ request('search') }}" class="search-input" placeholder="Rechercher un demandeur...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Tous les statuts</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archivés</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                            <i class="fas fa-redo"></i> Réinitialiser
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des demandeurs -->
    <div class="card main-card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="fas fa-list"></i>
                Liste des Demandeurs
            </h5>
            <div class="card-actions">
                <div class="table-info" id="tableInfo">
                    Affichage de {{ $demandeurs->count() }} sur {{ $demandeurs->total() }} demandeurs
                </div>
                <a href="{{ route('demandeur.index') }}" class="btn btn-sm btn-outline-secondary refresh-btn">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="photo-column" style="color: white; text-align:center">Photo</th>
                            <th style="color: white; text-align:center">Nom & Prénom</th>
                            <th style="color: white; text-align:center">Email</th>
                            <th style="color: white; text-align:center">Contact</th>
                            <th style="color: white; text-align:center">Fonction</th>
                            <th style="color: white; text-align:center">Structure</th>
                            <th style="color: white; text-align:center">Adresse</th>
                            <th style="color: white; text-align:center">Statut</th>
                            <th style="color: white; text-align:center">Date Création</th>
                            <th class="actions-column" style="color: white; text-align:center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($demandeurs as $demandeur)
                        <tr class="{{ $demandeur->archived_at ? 'table-archived' : '' }}">
                            <td class="photo-column">
                                @if($demandeur->profile_picture)
                                    <img src="{{ asset('storage/' . $demandeur->profile_picture) }}" 
                                         class="user-photo" alt="Photo {{ $demandeur->name }}">
                                @else
                                    <div class="photo-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="user-info">
                                    <div class="user-name" style="text-align: center">
                                        {{ $demandeur->name }} {{ $demandeur->prenom }}
                                    </div>
                                </div>
                            </td>
                            <td style="display:flex; justify-content:center">
                                <div class="email-cell">
                                    <i class="fas fa-envelope text-muted mr-1"></i>
                                    <a href="mailto:{{ $demandeur->email }}" class="email-link">{{ $demandeur->email }}</a>
                                </div>
                            </td>
                            <td>
                                <div class="contact-cell" style="display:flex; justify-content:center">
                                    <i class="fas fa-phone text-muted mr-1"></i>
                                    <a href="tel:{{ $demandeur->contact }}" class="contact-link">{{ $demandeur->contact }}</a>
                                </div>
                            </td>
                            <td style="text-align: center">
                                <span class="badge badge-info">{{ $demandeur->Fonction }}</span>
                            </td>
                            <td style="text-align: center">
                                <span class="badge badge-secondary">{{ $demandeur->structure }}</span>
                            </td>
                            <td>
                                <div class="address-cell" style="display:flex; justify-content:center">
                                    @if($demandeur->adresse)
                                        <i class="fas fa-map-marker-alt text-muted mr-1"></i>
                                        <span class="address-text" title="{{ $demandeur->adresse }}">
                                            {{ Str::limit($demandeur->adresse, 25) }}
                                        </span>
                                    @else
                                        <span class="text-muted">Non renseignée</span>
                                    @endif
                                </div>
                            </td>
                            <td style="display:flex; justify-content:center">
                                <span class="status-badge {{ $demandeur->archived_at ? 'status-archived' : 'status-active' }}">
                                    <i class="fas {{ $demandeur->archived_at ? 'fa-archive' : 'fa-check-circle' }} mr-1"></i>
                                    {{ $demandeur->archived_at ? 'Archivé' : 'Actif' }}
                                </span>
                            </td>
                            <td>
                                <div class="date-cell">
                                    <div class="date-main" style="text-align: center">{{ $demandeur->created_at->format('d/m/Y') }}</div>
                                    <div class="date-time" style="text-align: center">{{ $demandeur->created_at->format('H:i') }}</div>
                                </div>
                            </td>
                            <td class="actions-column">
                                <div class="btn-group action-buttons">
                                    <button type="button" 
                                            class="btn btn-info btn-sm action-btn" 
                                            title="Voir détails"
                                            onclick="showDetails({{ $demandeur->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-warning btn-sm action-btn" 
                                            title="Modifier"
                                            onclick="window.location.href='{{ route('demandeur.edit', $demandeur->id) }}'">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn {{ $demandeur->archived_at ? 'btn-success' : 'btn-secondary' }} btn-sm action-btn" 
                                            onclick="toggleArchive({{ $demandeur->id }}, '{{ $demandeur->name }} {{ $demandeur->prenom }}', {{ $demandeur->archived_at ? 'true' : 'false' }})"
                                            title="{{ $demandeur->archived_at ? 'Désarchiver' : 'Archiver' }}">
                                        <i class="fas {{ $demandeur->archived_at ? 'fa-box-open' : 'fa-archive' }}"></i>
                                    </button>
                                    {{-- <button type="button" class="btn btn-danger btn-sm action-btn" 
                                            onclick="confirmDelete({{ $demandeur->id }}, '{{ $demandeur->name }} {{ $demandeur->prenom }}')"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button> --}}
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-users fa-3x"></i>
                                    </div>
                                    <h3>Aucun demandeur trouvé</h3>
                                    <p class="text-muted">
                                        @if(request()->has('search') || request()->has('status'))
                                            Aucun demandeur ne correspond à vos critères de recherche.
                                        @else
                                            Commencez par ajouter votre premier demandeur.
                                        @endif
                                    </p>
                                    <a href="{{ route('demandeur.create') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus"></i>
                                        Ajouter un Demandeur
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($demandeurs->hasPages())
            <div class="pagination-container mt-4">
                <nav>
                    {{ $demandeurs->links('pagination.custom') }}
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Fonction pour réinitialiser les filtres
function resetFilters() {
    window.location.href = "{{ route('demandeur.index') }}";
}

// Fonction pour archiver/désarchiver un demandeur
function toggleArchive(id, name, isArchived) {
    const action = isArchived ? 'désarchiver' : 'archiver';
    const actionPast = isArchived ? 'désarchivé' : 'archivé';
    const icon = isArchived ? 'fa-box-open' : 'fa-archive';
    
    Swal.fire({
        title: `${isArchived ? 'Désarchiver' : 'Archiver'} le demandeur`,
        html: `
            <div class="confirmation-content">
                <div class="confirmation-icon">
                    <i class="fas ${icon} fa-3x"></i>
                </div>
                <div class="confirmation-text">
                    <p>Êtes-vous sûr de vouloir <strong>${action}</strong> le demandeur :</p>
                    <h4>"${name}"</h4>
                    <div class="confirmation-info">
                        <i class="fas fa-info-circle"></i>
                        ${isArchived ? 
                            'Le demandeur redeviendra actif et pourra à nouveau utiliser le système.' : 
                            'Le demandeur sera archivé et ne pourra plus accéder au système.'
                        }
                    </div>
                </div>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#1c3966',
        cancelButtonColor: '#6c757d',
        confirmButtonText: `Oui, ${action}`,
        cancelButtonText: 'Annuler',
        reverseButtons: true,
        customClass: {
            popup: 'custom-swal',
            htmlContainer: 'swal-html-container'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Afficher le loader
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })

            Toast.fire({
                icon: 'info',
                title: `${action} en cours...`
            })

            // Envoyer la requête AJAX
            fetch(`/admin/claimant/${id}/toggle-archive`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    _method: 'PATCH'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès!',
                        html: `
                            <div class="success-content">
                                <i class="fas fa-check-circle success-icon"></i>
                                <h4>Demandeur ${actionPast}</h4>
                                <p>"${name}" a été ${actionPast} avec succès.</p>
                            </div>
                        `,
                        confirmButtonColor: '#1c3966',
                        confirmButtonText: 'Fermer',
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    html: `
                        <div class="error-content">
                            <i class="fas fa-exclamation-triangle error-icon"></i>
                            <h4>Erreur</h4>
                            <p>${error.message || `Une erreur est survenue lors de l'${action} du demandeur.`}</p>
                        </div>
                    `,
                    confirmButtonColor: '#1c3966'
                });
            });
        }
    });
}

// Fonction pour supprimer un demandeur
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Supprimer le demandeur',
        html: `
            <div class="confirmation-content">
                <div class="confirmation-icon text-danger">
                    <i class="fas fa-exclamation-triangle fa-3x"></i>
                </div>
                <div class="confirmation-text">
                    <p>Êtes-vous sûr de vouloir <strong>supprimer définitivement</strong> le demandeur :</p>
                    <h4>"${name}"</h4>
                    <div class="confirmation-warning">
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>Attention :</strong> Cette action est irréversible. Toutes les données associées à ce demandeur seront perdues.
                    </div>
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
        reverseButtons: true,
        customClass: {
            popup: 'custom-swal danger-swal'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Envoyer la requête de suppression
            fetch(`/admin/claimant/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Supprimé!',
                        html: `
                            <div class="success-content">
                                <i class="fas fa-check-circle success-icon"></i>
                                <h4>Demandeur supprimé</h4>
                                <p>"${name}" a été supprimé avec succès.</p>
                            </div>
                        `,
                        confirmButtonColor: '#1c3966',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: error.message || 'Une erreur est survenue lors de la suppression.',
                    confirmButtonColor: '#1c3966'
                });
            });
        }
    });
}

// Recherche en temps réel avec debounce
let searchTimeout;
document.querySelector('input[name="search"]').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('filterForm').submit();
    }, 500);
});

// Animation au survol des cartes de statistiques
document.querySelectorAll('.stat-card').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transform = 'translateY(-5px)';
    });
    
    card.addEventListener('mouseleave', function() {
        this.style.transform = 'translateY(0)';
    });
});

function showDetails(id) {
    fetch(`/admin/claimant/${id}`)
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                title: `Détails de ${data.name} ${data.prenom}`,
                html: `
                    <p><strong>Nom :</strong> ${data.name} ${data.prenom}</p>
                    <p><strong>Email :</strong> <a href="mailto:${data.email}">${data.email}</a></p>
                    <p><strong>Contact :</strong> <a href="tel:${data.contact}">${data.contact}</a></p>
                    <p><strong>Adresse :</strong> ${data.adresse}</p>
                    <p><strong>Fonction :</strong> ${data.fonction}</p>
                    <p><strong>Structure :</strong> ${data.structure}</p>
                    <p><strong>Date de création :</strong> ${new Date(data.created_at).toLocaleDateString()}</p>
                `,
                icon: 'info',
                confirmButtonText: 'Fermer'
            });
        })
        .catch(error => {
            console.error('Erreur:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Une erreur est survenue lors de la récupération des détails.',
            });
        });
}
</script>

@endsection