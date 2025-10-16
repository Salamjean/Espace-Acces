@extends('admin.layouts.template')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid py-4">
    <!-- En-tête de la page -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="header-content">
                    <h1 class="page-title">
                        <i class="fas fa-users me-3"></i>
                        Gestion des Agents
                    </h1>
                    <p class="page-subtitle">Gérez efficacement votre équipe d'agents</p>
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <a href="{{ route('agent.create') }}" class="btn btn-primary-custom btn-lg text-white">
                    <i class="fas fa-plus-circle me-2"></i>
                    Nouvel Agent
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="stats-card">
                <div class="stats-icon total-agents">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $agents->total() }}</h3>
                    <p>Agents Total</p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="stats-card">
                <div class="stats-icon active-agents">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $activeAgentsCount ?? 0 }}</h3>
                    <p>Agents Actifs</p>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="stats-card">
                <div class="stats-icon archived-agents">
                    <i class="fas fa-archive"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $archivedAgentsCount ?? 0 }}</h3>
                    <p>Agents Archivés</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Carte principale -->
    <div class="main-card">
        <div class="card-header-custom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="card-title">
                        <i class="fas fa-list me-2"></i>
                        Liste des Agents
                    </h2>
                </div>
            </div>
        </div>

        <div class="card-body-custom">
            <!-- Barre de recherche et filtres -->
            <div class="filters-section">
                <div class="row g-3">
                    <div class="col-lg-4 col-md-6">
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="form-control search-input" id="searchInput" 
                                   placeholder="Rechercher un agent par nom, email...">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des agents -->
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-modern" id="agentsTable">
                        <thead>
                            <tr>
                                <th class="agent-column text-center">Agent</th>
                                <th class="contact-column  text-center">Contact</th>
                                <th class="commune-column  text-center">Commune</th>
                                <th class="urgence-column  text-center">Cas d'urgence</th>
                                <th class="status-column  text-center">Statut</th>
                                <th class="actions-column  text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($agents as $agent)
                            <tr class="agent-row" data-commune="{{ $agent->commune }}" 
                                data-status="{{ $agent->archived_at ? 'archived' : 'active' }}"
                                data-created="{{ $agent->created_at->timestamp }}"
                                data-name="{{ $agent->prenom }} {{ $agent->name }}">
                                
                                <td class="agent-column">
                                    <div class="agent-info">
                                        <div class="agent-avatar">
                                            @if($agent->profile_picture)
                                                <img src="{{ asset('storage/' . $agent->profile_picture) }}" 
                                                     alt="{{ $agent->name }}" 
                                                     class="avatar-img">
                                            @else
                                                <div class="avatar-placeholder">
                                                    {{ substr($agent->name, 0, 1) }}{{ substr($agent->prenom, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="agent-details">
                                            <h6 class="agent-name text-center">{{ $agent->prenom }} {{ $agent->name }}</h6>
                                            <p class="agent-email">{{ $agent->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="contact-column text-center">
                                    <div class="contact-info">
                                        <i class="fas fa-phone me-2 text-muted"></i>
                                        {{ $agent->contact }}
                                    </div>
                                </td>
                                <td class="commune-column text-center">
                                    <span class="commune-badge">{{ $agent->commune }}</span>
                                </td>
                                <td class="urgence-column text-center">
                                    @if($agent->cas_urgence)
                                        <span class="urgence-badge">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            {{ $agent->cas_urgence }}
                                        </span>
                                    @else
                                        <span class="no-urgence">Aucun</span>
                                    @endif
                                </td>
                                <td class="status-column text-center">
                                    @if($agent->archived_at)
                                        <span class="status-badge archived">
                                            <i class="fas fa-archive me-1"></i>
                                            Archivé
                                        </span>
                                    @else
                                        <span class="status-badge active">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Actif
                                        </span>
                                    @endif
                                </td>
                                <td class="actions-column text-center">
                                    <div class="actions-group">
                                        <button type="button" class="btn btn-action btn-view" data-bs-toggle="tooltip" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <form action="{{ route('agent.edit', $agent->id) }}" method="GET" style="display:inline;">
                                            <button type="submit" class="btn btn-action btn-edit" data-bs-toggle="tooltip" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </form>
                                        @if($agent->archived_at)
                                            <form action="{{ route('agent.restore', $agent->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-action btn-restore" data-bs-toggle="tooltip" title="Restaurer">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('agent.archive', $agent->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-action btn-archive" data-bs-toggle="tooltip" title="Archiver">
                                                    <i class="fas fa-archive"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-users fa-4x mb-3"></i>
                                        <h4>Aucun agent trouvé</h4>
                                        <p class="text-muted mb-4">Commencez par ajouter votre premier agent à l'équipe</p>
                                        <a href="{{ route('agent.create') }}" class="btn btn-primary-custom btn-lg">
                                            <i class="fas fa-plus-circle me-2"></i>
                                            Ajouter le premier agent
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            @if($agents->hasPages())
            <div class="card-footer-custom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="pagination-info">
                            Affichage de <strong>{{ $agents->firstItem() }}</strong> à 
                            <strong>{{ $agents->lastItem() }}</strong> sur 
                            <strong>{{ $agents->total() }}</strong> agents
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div>
                            {{ $agents->links('pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Actions groupées -->
    <div class="bulk-actions" id="bulkActions">
        <div class="bulk-actions-content">
            <div class="selected-count">
                <i class="fas fa-check-circle me-2"></i>
                <span id="selectedCount">0</span> agent(s) sélectionné(s)
            </div>
            <div class="bulk-buttons">
                <button type="button" class="btn btn-bulk btn-email">
                    <i class="fas fa-envelope me-2"></i>
                    Email groupé
                </button>
                <button type="button" class="btn btn-bulk btn-archive">
                    <i class="fas fa-archive me-2"></i>
                    Archiver
                </button>
                <button type="button" class="btn btn-bulk btn-delete">
                    <i class="fas fa-trash me-2"></i>
                    Supprimer
                </button>
                <button type="button" class="btn btn-bulk-cancel" id="cancelBulk">
                    <i class="fas fa-times me-2"></i>
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Variables CSS */
:root {
    --primary-color: #193561;
    --primary-light: #2a4a7a;
    --primary-dark: #12274a;
    --primary-gradient: linear-gradient(135deg, #193561 0%, #2a4a7a 100%);
    --success-color: #10b981;
    --warning-color: #f59e0b;
    --danger-color: #ef4444;
    --secondary-color: #6b7280;
    --light-bg: #f8fafc;
    --border-color: #e2e8f0;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Styles généraux */
body {
    font-family: 'Inter', sans-serif;
    background-color: #f1f5f9;
}

.container-fluid {
    width: 100%;
}

/* En-tête de page */
.page-header {
    background: var(--primary-gradient);
    border-radius: 16px;
    padding: 2rem;
    color: white;
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
}

/* Cartes de statistiques */
.stats-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    box-shadow: var(--shadow);
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
}

.stats-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1.5rem;
}

.stats-icon.total-agents {
    background: linear-gradient(135deg, #193561, #2a4a7a);
    color: white;
}

.stats-icon.active-agents {
    background: linear-gradient(135deg, #10b981, #34d399);
    color: white;
}

.stats-icon.archived-agents {
    background: linear-gradient(135deg, #6b7280, #9ca3af);
    color: white;
}

.stats-icon.urgent-cases {
    background: linear-gradient(135deg, #f59e0b, #fbbf24);
    color: white;
}

.stats-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: var(--primary-color);
}

.stats-content p {
    color: var(--secondary-color);
    margin-bottom: 0;
    font-weight: 500;
}

/* Carte principale */
.main-card {
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow);
    overflow: hidden;
    margin-bottom: 2rem;
}

.card-header-custom {
    background: var(--primary-gradient);
    color: white;
    padding: 1.5rem 2rem;
    border-bottom: none;
}

.card-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0;
}

.card-body-custom {
    padding: 0;
}

/* Section des filtres */
.filters-section {
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--border-color);
    background: var(--light-bg);
}

.search-box {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--secondary-color);
    z-index: 10;
}

.search-input {
    padding-left: 3rem;
    border-radius: 10px;
    border: 1px solid var(--border-color);
    height: 50px;
    font-size: 0.95rem;
}

.filter-group {
    margin-bottom: 0;
}

.filter-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
    display: block;
}

.filter-select {
    border-radius: 10px;
    border: 1px solid var(--border-color);
    height: 50px;
    font-size: 0.95rem;
}

/* Tableau */
.table-container {
    overflow: hidden;
}

.table-modern {
    margin-bottom: 0;
}

.table-modern thead th {
    background: var(--light-bg);
    border-bottom: 2px solid var(--border-color);
    padding: 1rem 1.5rem;
    font-weight: 600;
    color: var(--primary-color);
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 0.5px;
}

.table-modern tbody td {
    padding: 1.25rem 1.5rem;
    vertical-align: middle;
    border-bottom: 1px solid var(--border-color);
}

.agent-row:hover {
    background-color: rgba(25, 53, 97, 0.02);
    transform: scale(1);
}

/* Colonnes spécifiques */
.checkbox-column {
    width: 50px;
}

.agent-column {
   display: flex;
   justify-content: center;
}

.contact-column, .commune-column, .urgence-column, .status-column {
    min-width: 150px;
}

.actions-column {
    width: 150px;
}

/* Informations agent */
.agent-info {
    display: flex;
    align-items: center;
}

.agent-avatar {
    margin-right: 1rem;
}

.avatar-img, .avatar-placeholder {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    object-fit: cover;
}

.avatar-placeholder {
    background: var(--primary-gradient);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.1rem;
}

.agent-name {
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 0.25rem;
}

.agent-email {
    color: var(--secondary-color);
    font-size: 0.875rem;
    margin-bottom: 0;
}

/* Badges */
.commune-badge {
    background: #e0f2fe;
    color: #0369a1;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.urgence-badge {
    background: #fef3c7;
    color: #92400e;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.no-urgence {
    color: var(--secondary-color);
    font-style: italic;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
}

.status-badge.active {
    background: #d1fae5;
    color: #065f46;
}

.status-badge.archived {
    background: #f3f4f6;
    color: #374151;
}

/* Actions */
.actions-group {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

.btn-action {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border-color);
    background: white;
    transition: all 0.2s ease;
}

.btn-action:hover {
    transform: translateY(-1px);
}

.btn-view:hover {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.btn-edit:hover {
    background: #10b981;
    color: white;
    border-color: #10b981;
}

.btn-archive:hover {
    background: #f59e0b;
    color: white;
    border-color: #f59e0b;
}

.btn-restore:hover {
    background: #10b981;
    color: white;
    border-color: #10b981;
}

.btn-delete:hover {
    background: #ef4444;
    color: white;
    border-color: #ef4444;
}

/* Boutons principaux */
.btn-primary-custom {
    background: var(--primary-gradient);
    border: none;
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
    background: var(--primary-light);
}

/* Actions groupées */
.bulk-actions {
    position: fixed;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%) translateY(100px);
    background: white;
    border-radius: 12px;
    box-shadow: var(--shadow-lg);
    padding: 1rem 1.5rem;
    min-width: 500px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 1000;
    border: 1px solid var(--border-color);
}

.bulk-actions.show {
    transform: translateX(-50%) translateY(0);
    opacity: 1;
    visibility: visible;
}

.bulk-actions-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.selected-count {
    font-weight: 600;
    color: var(--primary-color);
}

.bulk-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-bulk {
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.875rem;
    border: 1px solid var(--border-color);
    background: white;
    transition: all 0.2s ease;
}

.btn-bulk:hover {
    transform: translateY(-1px);
}

.btn-bulk.btn-email:hover {
    background: #3b82f6;
    color: white;
    border-color: #3b82f6;
}

.btn-bulk.btn-archive:hover {
    background: #f59e0b;
    color: white;
    border-color: #f59e0b;
}

.btn-bulk.btn-delete:hover {
    background: #ef4444;
    color: white;
    border-color: #ef4444;
}

.btn-bulk-cancel {
    background: #6b7280;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.875rem;
}

/* État vide */
.empty-state {
    padding: 3rem 1rem;
    text-align: center;
    color: var(--secondary-color);
}

.empty-state i {
    opacity: 0.5;
}

.empty-state h4 {
    color: var(--primary-color);
    margin-bottom: 1rem;
}

/* Pied de carte */
.card-footer-custom {
    background: var(--light-bg);
    padding: 1.5rem 2rem;
    border-top: 1px solid var(--border-color);
}

.pagination-info {
    color: var(--secondary-color);
    font-weight: 500;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        padding: 1.5rem;
    }
    
    .page-title {
        font-size: 1.5rem;
    }
    
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .filters-section {
        padding: 1rem;
    }
    
    .table-modern thead th,
    .table-modern tbody td {
        padding: 0.75rem 1rem;
    }
    
    .bulk-actions {
        min-width: 90%;
        left: 5%;
        transform: translateX(0) translateY(100px);
    }
    
    .bulk-actions.show {
        transform: translateX(0) translateY(0);
    }
    
    .bulk-actions-content {
        flex-direction: column;
        gap: 1rem;
    }
    
    .bulk-buttons {
        flex-wrap: wrap;
        justify-content: center;
    }
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.agent-row {
    animation: fadeIn 0.3s ease;
}

/* Focus states */
.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(25, 53, 97, 0.1);
    outline: none;
}

.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner/désélectionner tous les agents
    const selectAll = document.getElementById('selectAll');
    const agentCheckboxes = document.querySelectorAll('.agent-checkbox');
    const bulkActions = document.getElementById('bulkActions');
    const selectedCount = document.getElementById('selectedCount');
    const cancelBulk = document.getElementById('cancelBulk');
    
    selectAll.addEventListener('change', function() {
        agentCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
        updateBulkActions();
    });
    
    agentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
    
    cancelBulk.addEventListener('click', function() {
        agentCheckboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        selectAll.checked = false;
        updateBulkActions();
    });
    
    function updateBulkActions() {
        const checkedCount = document.querySelectorAll('.agent-checkbox:checked').length;
        
        if (checkedCount > 0) {
            bulkActions.classList.add('show');
            selectedCount.textContent = checkedCount;
        } else {
            bulkActions.classList.remove('show');
        }
        
        // Mettre à jour la case "Sélectionner tout"
        selectAll.checked = checkedCount === agentCheckboxes.length;
        selectAll.indeterminate = checkedCount > 0 && checkedCount < agentCheckboxes.length;
    }
    
    // Filtrage en temps réel
    const searchInput = document.getElementById('searchInput');
    const communeFilter = document.getElementById('communeFilter');
    const statusFilter = document.getElementById('statusFilter');
    const sortFilter = document.getElementById('sortFilter');
    
    function filterAgents() {
        const searchTerm = searchInput.value.toLowerCase();
        const communeValue = communeFilter.value;
        const statusValue = statusFilter.value;
        const sortValue = sortFilter.value;
        
        const rows = Array.from(document.querySelectorAll('.agent-row'));
        
        // Filtrer
        rows.forEach(row => {
            const name = row.querySelector('.agent-name').textContent.toLowerCase();
            const email = row.querySelector('.agent-email').textContent.toLowerCase();
            const commune = row.getAttribute('data-commune');
            const status = row.getAttribute('data-status');
            
            const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
            const matchesCommune = !communeValue || commune === communeValue;
            const matchesStatus = !statusValue || status === statusValue;
            
            row.style.display = matchesSearch && matchesCommune && matchesStatus ? '' : 'none';
        });
        
        // Trier
        const visibleRows = rows.filter(row => row.style.display !== 'none');
        
        visibleRows.sort((a, b) => {
            switch(sortValue) {
                case 'newest':
                    return parseInt(b.getAttribute('data-created')) - parseInt(a.getAttribute('data-created'));
                case 'oldest':
                    return parseInt(a.getAttribute('data-created')) - parseInt(b.getAttribute('data-created'));
                case 'name':
                    return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                default:
                    return 0;
            }
        });
        
        // Réorganiser dans le DOM
        const tbody = document.querySelector('#agentsTable tbody');
        visibleRows.forEach(row => tbody.appendChild(row));
    }
    
    searchInput.addEventListener('input', filterAgents);
    communeFilter.addEventListener('change', filterAgents);
    statusFilter.addEventListener('change', filterAgents);
    sortFilter.addEventListener('change', filterAgents);
    
    // Bouton d'actualisation
    document.getElementById('refreshBtn').addEventListener('click', function() {
        this.classList.add('rotating');
        setTimeout(() => {
            location.reload();
        }, 500);
    });
    
    // Animation de rotation
    const style = document.createElement('style');
    style.textContent = `
        .rotating {
            animation: rotate 0.5s linear;
        }
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
});

// SweetAlert notifications
            @if (Session::has('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: '{{ Session::get('success') }}',
                    confirmButtonText: 'OK',
                    background: 'white',
                });
            @endif

            @if (Session::has('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: '{{ Session::get('error') }}',
                    confirmButtonText: 'OK',
                    background: 'white',
                    
                });
            @endif

            @if (Session::has('no_account'))
                Swal.fire({
                    icon: 'info',
                    title: 'Information',
                    text: '{{ Session::get('no_account') }}',
                    confirmButtonText: 'Créer un compte',
                    background: 'white',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('register') }}';
                    }
                });
            @endif

            const viewButtons = document.querySelectorAll('.btn-view');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('.agent-row');
            const name = row.getAttribute('data-name');
            const commune = row.getAttribute('data-commune');
            const email = row.querySelector('.agent-email').textContent;
            const contact = row.querySelector('.contact-info').textContent;
            const urgence = row.querySelector('.urgence-column .urgence-badge') 
                ? row.querySelector('.urgence-column .urgence-badge').textContent : "Aucun";

            Swal.fire({
                title: `Détails de l'agent: ${name}`,
                html: `
                    <p><strong>Email:</strong> ${email}</p>
                    <p><strong>Contact:</strong> ${contact}</p>
                    <p><strong>Commune:</strong> ${commune}</p>
                    <p><strong>Cas d'urgence:</strong> ${urgence}</p>
                `,
                icon: 'info',
                confirmButtonText: 'Fermer',
                background: 'white',
            });
        });
    });
   // Action "Modifier" 
    const editButtons = document.querySelectorAll('.btn-edit');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const agentId = this.getAttribute('data-id');
            // Utilisez la route nommée pour l'édition
            window.location.href = `/agent/${agentId}/edit`;
        });
    });

    // Action "Archiver"
    const archiveButtons = document.querySelectorAll('.btn-archive');
    archiveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const agentId = this.getAttribute('data-id');
            // Soumettez une requête pour archiver l'agent
            // Exemple : utilisez AJAX pour appeler une méthode de votre contrôleur
            // Ou redirigez vers une route d'archivage
            window.location.href = `{{ url('admin/agent/${agentId}/archive') }}`; // Remplacez par votre route d'archivage
        });
    });

    // Action "Restaurer"
    const restoreButtons = document.querySelectorAll('.btn-restore');
    restoreButtons.forEach(button => {
        button.addEventListener('click', function() {
            const agentId = this.getAttribute('data-id');
            // Soumettez une requête pour restaurer l'agent
            window.location.href = `{{ url('admin/agent/${agentId}/restore') }}`; // Remplacez par votre route de restauration
        });
    });

    // Action "Supprimer"
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const agentId = this.getAttribute('data-id');
            // Redirigez vers une méthode de suppression
            window.location.href = `{{ url('admin/agent/${agentId}/delete') }}`; // Remplacez par votre route de suppression
        });
    });
</script>
@endsection