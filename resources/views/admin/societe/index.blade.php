@extends('admin.layouts.template')
@section('content')
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

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
                    <i class="fas fa-building icon-primary"></i>
                    Gestion des Sociétés
                </h1>
                <p class="page-subtitle">Liste de toutes les sociétés enregistrées dans le système</p>
            </div>
            <a href="{{ route('society.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i>
                Nouvelle Société
            </a>
        </div>
    </div>

    <!-- Cartes de statistiques -->
    <!-- Cartes de statistiques -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon total">
                <i class="fas fa-building"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $societes->total() }}</div>
                <div class="stat-label">Total Sociétés</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon active">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $societes->where('archived_at', null)->count() }}</div>
                <div class="stat-label">Sociétés Actives</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon archived">
                <i class="fas fa-archive"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $societes->where('archived_at', '!=', null)->count() }}</div>
                <div class="stat-label">Sociétés Archivées</div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon recent">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $societes->where('created_at', '>=', now()->subMonth())->count() }}</div>
                <div class="stat-label">Ajoutées ce mois</div>
            </div>
        </div>
    </div>
</div>

    <!-- Filtres et recherche -->
    <div class="card filters-card">
        <div class="card-body">
            <form method="GET" action="{{ route('society.index') }}" id="filterForm">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" name="search" value="{{ request('search') }}" class="search-input" placeholder="Rechercher une société...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="filter-select" onchange="document.getElementById('filterForm').submit()">
                            <option value="">Tous les statuts</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actives</option>
                            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archivées</option>
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

    <!-- Tableau des sociétés -->
    <div class="card main-card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="fas fa-list"></i>
                Liste des Sociétés
            </h5>
            <div class="card-actions">
                <div class="table-info" id="tableInfo">
                    Affichage de {{ $societes->count() }} sur {{ $societes->total() }} sociétés
                </div>
                <a href="{{ route('society.index') }}" class="btn btn-sm btn-outline-secondary refresh-btn">
                    <i class="fas fa-sync-alt"></i>
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="logo-column" style="color: white; text-align:center">Logo</th>
                            <th style="color: white; text-align:center">Nom</th>
                            <th style="color: white ; text-align:center">Email</th>
                            <th style="color: white ; text-align:center">Contact</th>
                            <th style="color: white ; text-align:center">Adresse</th>
                            <th style="color: white ; text-align:center">Statut</th>
                            <th style="color: white ; text-align:center">Date Création</th>
                            <th class="actions-column" style="color: white ; text-align:center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($societes as $societe)
                        <tr class="{{ $societe->archived_at ? 'table-archived' : '' }}">
                            <td class="logo-column">
                                @if($societe->profile_picture)
                                    <img src="{{ asset('storage/' . $societe->profile_picture) }}" 
                                         class="company-logo" alt="Logo {{ $societe->name }}">
                                @else
                                    <div class="logo-placeholder">
                                        <i class="fas fa-building"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="company-info">
                                    <div class="company-name" style="text-align: center">{{ $societe->name }}</div>
                                </div>
                            </td>
                            <td style="display:flex; justify-content:center">
                                <div class="email-cell" >
                                    <i class="fas fa-envelope text-muted mr-1"></i>
                                    <a href="mailto:{{ $societe->email }}" class="email-link" >{{ $societe->email }}</a>
                                </div>
                            </td>
                            <td >
                                <div class="contact-cell" style="display:flex; justify-content:center">
                                    <i class="fas fa-phone text-muted mr-1"></i>
                                    <a href="tel:{{ $societe->contact }}"  class="contact-link">{{ $societe->contact }}</a>
                                </div>
                            </td>
                            <td>
                                <div class="address-cell" style="display:flex; justify-content:center">
                                    @if($societe->adresse)
                                        <i class="fas fa-map-marker-alt text-muted mr-1"></i>
                                        <span class="address-text" title="{{ $societe->adresse }}">
                                            {{ Str::limit($societe->adresse, 30) }}
                                        </span>
                                    @else
                                        <span class="text-muted">Non renseignée</span>
                                    @endif
                                </div>
                            </td>
                            <td style="display:flex; justify-content:center">
                                <span class="status-badge {{ $societe->archived_at ? 'status-archived' : 'status-active' }}">
                                    <i class="fas {{ $societe->archived_at ? 'fa-archive' : 'fa-check-circle' }} mr-1"></i>
                                    {{ $societe->archived_at ? 'Archivée' : 'Active' }}
                                </span>
                            </td>
                            <td>
                                <div class="date-cell" >
                                    <div class="date-main" style="text-align: center">{{ $societe->created_at->format('d/m/Y') }}</div>
                                    <div class="date-time" style="text-align: center">{{ $societe->created_at->format('H:i') }}</div>
                                </div>
                            </td>
                            <td class="actions-column">
                                <div class="btn-group action-buttons">
                                    <button type="button" class="btn btn-info btn-sm action-btn" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm action-btn" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn {{ $societe->archived_at ? 'btn-success' : 'btn-secondary' }} btn-sm action-btn" 
                                            onclick="toggleArchive({{ $societe->id }}, '{{ $societe->name }}', {{ $societe->archived_at ? 'true' : 'false' }})"
                                            title="{{ $societe->archived_at ? 'Désarchiver' : 'Archiver' }}">
                                        <i class="fas {{ $societe->archived_at ? 'fa-box-open' : 'fa-archive' }}"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm action-btn" 
                                            onclick="confirmDelete({{ $societe->id }}, '{{ $societe->name }}')"
                                            title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-building fa-3x"></i>
                                    </div>
                                    <h3>Aucune société trouvée</h3>
                                    <p class="text-muted">
                                        @if(request()->has('search') || request()->has('status'))
                                            Aucune société ne correspond à vos critères de recherche.
                                        @else
                                            Commencez par ajouter votre première société.
                                        @endif
                                    </p>
                                    <a href="{{ route('society.create') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus"></i>
                                        Ajouter une Société
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($societes->hasPages())
            <div class="pagination-container mt-4">
                <nav>
                    {{ $societes->links() }}
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
    window.location.href = "{{ route('society.index') }}";
}

// Fonction pour archiver/désarchiver une société
function toggleArchive(id, name, isArchived) {
    const action = isArchived ? 'désarchiver' : 'archiver';
    const actionPast = isArchived ? 'désarchivée' : 'archivée';
    const icon = isArchived ? 'fa-box-open' : 'fa-archive';
    
    Swal.fire({
        title: `${isArchived ? 'Désarchiver' : 'Archiver'} la société`,
        html: `
            <div class="confirmation-content">
                <div class="confirmation-icon">
                    <i class="fas ${icon} fa-3x"></i>
                </div>
                <div class="confirmation-text">
                    <p>Êtes-vous sûr de vouloir <strong>${action}</strong> la société :</p>
                    <h4>"${name}"</h4>
                    <div class="confirmation-info">
                        <i class="fas fa-info-circle"></i>
                        ${isArchived ? 
                            'La société redeviendra active et pourra à nouveau utiliser le système.' : 
                            'La société sera archivée et ne pourra plus accéder au système.'
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
            fetch(`/admin/society/${id}/toggle-archive`, {
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
                                <h4>Société ${actionPast}</h4>
                                <p>"${name}" a été ${actionPast} avec succès.</p>
                            </div>
                        `,
                        confirmButtonColor: '#1c3966',
                        confirmButtonText: 'Fermer',
                        timer: 3000,
                        timerProgressBar: true
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
                            <p>${error.message || `Une erreur est survenue lors de l'${action} de la société.`}</p>
                        </div>
                    `,
                    confirmButtonColor: '#1c3966'
                });
            });
        }
    });
}

// Fonction pour supprimer une société
function confirmDelete(id, name) {
    Swal.fire({
        title: 'Supprimer la société',
        html: `
            <div class="confirmation-content">
                <div class="confirmation-icon text-danger">
                    <i class="fas fa-exclamation-triangle fa-3x"></i>
                </div>
                <div class="confirmation-text">
                    <p>Êtes-vous sûr de vouloir <strong>supprimer définitivement</strong> la société :</p>
                    <h4>"${name}"</h4>
                    <div class="confirmation-warning">
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>Attention :</strong> Cette action est irréversible. Toutes les données associées à cette société seront perdues.
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
            fetch(`/admin/societe/${id}`, {
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
                                <h4>Société supprimée</h4>
                                <p>"${name}" a été supprimée avec succès.</p>
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
</script>

<style>
/* Variables CSS étendues */
:root {
    --primary-color: #1c3966;
    --primary-dark: #15294d;
    --primary-light: #2c5282;
    --primary-gradient: linear-gradient(135deg, #1c3966 0%, #2c5282 100%);
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --info-color: #3b82f6;
    --secondary-color: #6b7280;
    --light-bg: #f8fafc;
    --border-color: #e2e8f0;
    --text-color: #1f2937;
    --text-muted: #6b7280;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --border-radius: 12px;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Reset et base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    min-height: 100vh;
    color: var(--text-color);
    line-height: 1.6;
}

/* Header amélioré */
.page-header {
    background: white;
    padding: 2.5rem 0;
    margin-bottom: 2rem;
    box-shadow: var(--shadow);
    border-bottom: 3px solid var(--primary-color);
}

.header-content {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.page-title {
    font-size: 2.25rem;
    font-weight: 800;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.page-subtitle {
    color: var(--text-muted);
    font-size: 1.125rem;
    font-weight: 400;
}

.btn-primary {
    background: var(--primary-gradient);
    border: none;
    padding: 0.875rem 2rem;
    font-weight: 600;
    border-radius: 10px;
    transition: var(--transition);
    box-shadow: var(--shadow);
    position: relative;
    overflow: hidden;
}

.btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn-primary:hover::before {
    left: 100%;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

/* Cartes de statistiques améliorées */
.stat-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 2rem;
    box-shadow: var(--shadow);
    display: flex;
    align-items: center;
    gap: 1.5rem;
    transition: var(--transition);
    border: 1px solid var(--border-color);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--primary-color);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}

.stat-card.total::before { background: var(--primary-color); }
.stat-card.active::before { background: var(--success-color); }
.stat-card.archived::before { background: var(--warning-color); }
.stat-card.recent::before { background: var(--info-color); }

.stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: white;
    flex-shrink: 0;
    box-shadow: var(--shadow);
}

.stat-icon.total { background: var(--primary-gradient); }
.stat-icon.active { background: linear-gradient(135deg, var(--success-color), #059669); }
.stat-icon.archived { background: linear-gradient(135deg, var(--warning-color), #d97706); }
.stat-icon.recent { background: linear-gradient(135deg, var(--info-color), #2563eb); }

.stat-number {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-color);
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    color: var(--text-muted);
    font-size: 0.9rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Filtres améliorés */
.filters-card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    margin-bottom: 2rem;
    background: white;
    border: 1px solid var(--border-color);
}

.search-box {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    z-index: 2;
}

.search-input {
    width: 100%;
    padding: 1rem 1rem 1rem 3.5rem;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    font-size: 1rem;
    transition: var(--transition);
    background: var(--light-bg);
    font-weight: 500;
}

.search-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(28, 57, 102, 0.1);
    background: white;
}

.filter-select {
    width: 100%;
    padding: 1rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: 10px;
    font-size: 1rem;
    background: var(--light-bg);
    cursor: pointer;
    font-weight: 500;
    transition: var(--transition);
}

.filter-select:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(28, 57, 102, 0.1);
    background: white;
}

/* Tableau amélioré */
.main-card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-xl);
    background: white;
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-bottom: 2px solid var(--border-color);
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title {
    color: var(--primary-color);
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.25rem;
}

.table-info {
    color: var(--text-muted);
    font-size: 0.875rem;
    font-weight: 500;
}

.refresh-btn {
    border-radius: 8px;
    transition: var(--transition);
}

.refresh-btn:hover {
    transform: rotate(180deg);
}

.table {
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.table th {
    background: var(--primary-gradient);
    color: red;
    border: none;
    padding: 1.25rem 1rem;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    position: sticky;
    top: 0;
}

.table th:first-child {
    border-top-left-radius: 0;
}

.table th:last-child {
    border-top-right-radius: 0;
}

.table td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
    border-color: var(--border-color);
    transition: var(--transition);
}

.table tbody tr {
    transition: var(--transition);
}

.table tbody tr:hover {
    background: #f8fafc;
    transform: scale(1.01);
}

/* Colonnes spécifiques */
.logo-column {
    width: 70px;
}

.company-logo {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    object-fit: cover;
    border: 2px solid var(--border-color);
    transition: var(--transition);
}

.company-logo:hover {
    transform: scale(1.1);
    border-color: var(--primary-color);
}

.logo-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    background: var(--light-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
    border: 2px dashed var(--border-color);
    transition: var(--transition);
}

.logo-placeholder:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
}

.company-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.company-name {
    font-weight: 600;
    color: var(--text-color);
    font-size: 1rem;
}

.company-id {
    font-size: 0.75rem;
    color: var(--text-muted);
    font-family: 'Monaco', 'Consolas', monospace;
}

.email-cell, .contact-cell, .address-cell {
    display: flex;
    align-items: center;
    color: var(--text-color);
}

.email-link, .contact-link {
    color: var(--text-color);
    text-decoration: none;
    transition: var(--transition);
}

.email-link:hover, .contact-link:hover {
    color: var(--primary-color);
}

.address-text {
    cursor: help;
}

.date-cell {
    display: flex;
    flex-direction: column;
    gap: 0.125rem;
}

.date-main {
    font-weight: 600;
    color: var(--text-color);
}

.date-time {
    font-size: 0.75rem;
    color: var(--text-muted);
}

/* Badges de statut améliorés */
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: var(--transition);
}

.status-active {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.status-archived {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    border: 1px solid #fde68a;
}

/* Actions */
.actions-column {
    width: 220px;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.action-btn {
    padding: 0.5rem;
    border-radius: 8px;
    border: none;
    transition: var(--transition);
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

/* Lignes archivées */
.table-archived {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    opacity: 0.8;
}

.table-archived:hover {
    background: linear-gradient(135deg, #e9ecef, #dee2e6);
    opacity: 1;
}

/* État vide amélioré */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-icon {
    color: var(--text-muted);
    margin-bottom: 2rem;
    opacity: 0.5;
}

.empty-state h3 {
    color: var(--text-color);
    margin-bottom: 1rem;
    font-weight: 600;
}

/* Pagination améliorée */
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.pagination {
    margin-bottom: 0;
    display: flex;
    gap: 0.5rem;
}

.pagination .page-link {
    color: var(--primary-color);
    border: 1px solid var(--border-color);
    padding: 0.75rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    transition: var(--transition);
}

.pagination .page-item.active .page-link {
    background: var(--primary-gradient);
    border-color: var(--primary-color);
    color: white;
}

.pagination .page-link:hover {
    color: var(--primary-dark);
    background: var(--light-bg);
    border-color: var(--primary-color);
}

/* Styles pour SweetAlert2 personnalisés */
.custom-swal {
    border-radius: 20px;
    border: 2px solid var(--border-color);
}

.danger-swal {
    border-color: var(--danger-color);
}

.confirmation-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    text-align: left;
}

.confirmation-icon {
    flex-shrink: 0;
    color: var(--primary-color);
}

.confirmation-text h4 {
    color: var(--text-color);
    margin: 0.5rem 0;
    font-weight: 600;
}

.confirmation-info, .confirmation-warning {
    padding: 0.75rem;
    border-radius: 8px;
    margin-top: 1rem;
    font-size: 0.875rem;
}

.confirmation-info {
    background: #dbeafe;
    color: #1e40af;
    border: 1px solid #93c5fd;
}

.confirmation-warning {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fca5a5;
}

.success-content, .error-content {
    text-align: center;
    padding: 1rem;
}

.success-icon {
    color: var(--success-color);
    font-size: 3rem;
    margin-bottom: 1rem;
}

.error-icon {
    color: var(--danger-color);
    font-size: 3rem;
    margin-bottom: 1rem;
}

/* Responsive design amélioré */
@media (max-width: 1200px) {
    .header-content {
        padding: 0 1.5rem;
    }
    
    .page-title {
        font-size: 2rem;
    }
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .page-title {
        font-size: 1.75rem;
    }
    
    .stat-card {
        margin-bottom: 1rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .actions-column {
        width: 120px;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .confirmation-content {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
}

@media (max-width: 576px) {
    .page-header {
        padding: 2rem 0;
    }
    
    .header-content {
        padding: 0 1rem;
    }
    
    .filters-card .row > div {
        margin-bottom: 1rem;
    }
    
    .card-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .table-info {
        order: -1;
    }
}
</style>
@endsection