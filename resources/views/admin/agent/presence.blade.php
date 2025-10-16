@extends('admin.layouts.template')

@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0" style="color: #193561">Surveillance Agents - Scan & Pointage</h1>
        <div class="d-flex">
            <span style="color:#193561" id="last-update"></span>
        </div>
    </div>

    <!-- Statistiques en temps réel -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 stats-label">
                                Scans Aujourd'hui</div>
                            <div class="h5 mb-0 font-weight-bold stats-value" id="scans-today">0</div>
                            <div class="mt-2 text-xs">
                                <i class="fas fa-arrow-up text-success mr-1"></i>
                                <span class="text-success" id="scans-trend">En direct</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-qrcode stats-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 stats-label">
                                Pointages Aujourd'hui</div>
                            <div class="h5 mb-0 font-weight-bold stats-value" id="pointages-today">0</div>
                            <div class="mt-2 text-xs">
                                <i class="fas fa-users text-info mr-1"></i>
                                <span class="text-info" id="pointages-info">En temps réel</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-clock stats-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 stats-label">
                                Agents Présents</div>
                            <div class="h5 mb-0 font-weight-bold stats-value" id="agents-presents">0</div>
                            <div class="mt-2 text-xs">
                                <i class="fas fa-circle text-success mr-1"></i>
                                <span class="text-success" id="presence-status">Actifs</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users stats-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-uppercase mb-1 stats-label">
                                Taux de Validation</div>
                            <div class="h5 mb-0 font-weight-bold stats-value" id="scans-valides">0%</div>
                            <div class="mt-2 text-xs">
                                <i class="fas fa-check-circle text-success mr-1"></i>
                                <span class="text-success" id="validation-rate">Scans valides</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle stats-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row">
        <div class="col-xl-6 col-lg-6">
            <div class="card modern-card shadow mb-4">
                <div class="card-header modern-card-header">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-chart-line mr-2"></i>Activité des Scans - 7 jours</h6>
                    <div class="chart-legend">
                        <span class="legend-item"><i class="fas fa-circle text-primary mr-1"></i>Scans</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="scansChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card modern-card shadow mb-4">
                <div class="card-header modern-card-header">
                    <h6 class="m-0 font-weight-bold"><i class="fas fa-chart-bar mr-2"></i>Pointages - 7 jours</h6>
                    <div class="chart-legend">
                        <span class="legend-item"><i class="fas fa-circle text-success mr-1"></i>Pointages</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="pointagesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation par onglets -->
    <div class="row">
        <div class="col-12">
            <div class="card modern-card shadow mb-4">
                <div class="card-header modern-card-header">
                    <ul class="nav nav-tabs modern-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="scans-tab" data-toggle="tab" href="#scans" role="tab">
                                <i class="fas fa-qrcode mr-2"></i>Historique des Scans
                                <span class="badge badge-pill badge-light ml-2" id="scans-count">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pointages-tab" data-toggle="tab" href="#pointages" role="tab">
                                <i class="fas fa-user-clock mr-2"></i>Pointages
                                <span class="badge badge-pill badge-light ml-2" id="pointages-count">0</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="agents-tab" data-toggle="tab" href="#agents" role="tab">
                                <i class="fas fa-users mr-2"></i>Statistiques par Agent
                                <span class="badge badge-pill badge-light ml-2" id="agents-count">0</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        
                        <!-- Onglet Scans -->
                        <div class="tab-pane fade show active" id="scans" role="tabpanel">
                            <div class="filters-section mb-4">
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <label class="filter-label">Agent</label>
                                        <select class="form-control modern-select" id="filter-agent-scan">
                                            <option value="">Tous les agents</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label class="filter-label">Type</label>
                                        <select class="form-control modern-select" id="filter-type-scan">
                                            <option value="">Tous types</option>
                                            <option value="entree">Entrée</option>
                                            <option value="sortie">Sortie</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label class="filter-label">Statut</label>
                                        <select class="form-control modern-select" id="filter-validite-scan">
                                            <option value="">Tous statuts</option>
                                            <option value="1">Valide</option>
                                            <option value="0">Invalide</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label class="filter-label">Date début</label>
                                        <input type="date" class="form-control modern-input" id="filter-date-debut-scan">
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label class="filter-label">Date fin</label>
                                        <input type="date" class="form-control modern-input" id="filter-date-fin-scan">
                                    </div>
                                    <div class="col-md-1 mb-2 d-flex align-items-end">
                                        <button class="btn btn-filter" onclick="loadScansData()">
                                            <i class="fas fa-filter"></i>
                                        </button>
                                        <button class="btn btn-reset ml-1" onclick="resetScanFilters()">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-container">
                                <table class="table modern-table" id="scansTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-white">Agent</th>
                                            <th class="text-center text-white">Code Scanné</th>
                                            <th class="text-center text-white">Porte</th>
                                            <th class="text-center text-white">Type</th>
                                            <th class="text-center text-white">Heure</th>
                                            <th class="text-center text-white">Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody id="scansTableBody">
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="fas fa-spinner fa-spin mr-2"></i>Chargement des données...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="scansPagination" class="modern-pagination"></div>
                        </div>

                        <!-- Onglet Pointages -->
                        <div class="tab-pane fade" id="pointages" role="tabpanel">
                            <div class="filters-section mb-4">
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <label class="filter-label">Agent</label>
                                        <select class="form-control modern-select" id="filter-agent-pointage">
                                            <option value="">Tous les agents</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label class="filter-label">Statut</label>
                                        <select class="form-control modern-select" id="filter-statut-pointage">
                                            <option value="">Tous statuts</option>
                                            <option value="present">Présent</option>
                                            <option value="en_retard">En retard</option>
                                            <option value="absent">Absent</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label class="filter-label">Date début</label>
                                        <input type="date" class="form-control modern-input" id="filter-date-debut-pointage">
                                    </div>
                                    <div class="col-md-2 mb-2">
                                        <label class="filter-label">Date fin</label>
                                        <input type="date" class="form-control modern-input" id="filter-date-fin-pointage">
                                    </div>
                                    <div class="col-md-1 mb-2 d-flex align-items-end">
                                        <button class="btn btn-filter" onclick="loadPointagesData()">
                                            <i class="fas fa-filter"></i>
                                        </button>
                                        <button class="btn btn-reset ml-1" onclick="resetPointageFilters()">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-container">
                                <table class="table modern-table" id="pointagesTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-white">Agent</th>
                                            <th class="text-center text-white">Date</th>
                                            <th class="text-center text-white">Heure Arrivée</th>
                                            <th class="text-center text-white">Heure Départ</th>
                                            <th class="text-center text-white">Statut</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pointagesTableBody">
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">
                                                <i class="fas fa-spinner fa-spin mr-2"></i>Chargement des données...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div id="pointagesPagination" class="modern-pagination"></div>
                        </div>

                        <!-- Onglet Agents -->
                        <div class="tab-pane fade" id="agents" role="tabpanel">
                            <div class="table-container">
                                <table class="table modern-table" id="agentsTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-white">Agent</th>
                                            <th class="text-center text-white">Total Scans</th>
                                            <th class="text-center text-white">Scans Valides</th>
                                            <th class="text-center text-white">Scans Invalides</th>
                                            <th class="text-center text-white">Total Pointages</th>
                                            <th class="text-center text-white">Présents</th>
                                            <th class="text-center text-white">Retards</th>
                                        </tr>
                                    </thead>
                                    <tbody id="agentsTableBody">
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fas fa-spinner fa-spin mr-2"></i>Chargement des données...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales
let scansChart, pointagesChart;
let currentScansPage = 1;
let currentPointagesPage = 1;

// Chargement initial
document.addEventListener('DOMContentLoaded', function() {
    updateLastUpdateTime();
    loadDashboardStats();
    loadAgentsData();
    loadScansData();
    
    // Écouter les changements d'onglets
    $('#myTab a').on('shown.bs.tab', function (e) {
        const target = $(e.target).attr("href");
        if (target === '#pointages' && !window.pointagesDataLoaded) {
            loadPointagesData();
            window.pointagesDataLoaded = true;
        }
    });
    
    // Recharger les stats toutes les 30 secondes
    setInterval(loadDashboardStats, 30000);
    setInterval(updateLastUpdateTime, 60000);
});

// Mettre à jour l'heure de dernière mise à jour
function updateLastUpdateTime() {
    const now = new Date();
    document.getElementById('last-update').textContent = 
        `Dernière mise à jour: ${now.toLocaleTimeString('fr-FR')}`;
}

// Actualiser toutes les données
function refreshAllData() {
    const refreshBtn = document.querySelector('.btn-refresh');
    const originalHtml = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    Promise.all([
        loadDashboardStats(),
        loadAgentsData(),
        loadScansData(),
        loadPointagesData()
    ]).finally(() => {
        setTimeout(() => {
            refreshBtn.innerHTML = originalHtml;
        }, 1000);
    });
}

// Réinitialiser les filtres
function resetScanFilters() {
    document.getElementById('filter-agent-scan').value = '';
    document.getElementById('filter-type-scan').value = '';
    document.getElementById('filter-validite-scan').value = '';
    document.getElementById('filter-date-debut-scan').value = '';
    document.getElementById('filter-date-fin-scan').value = '';
    loadScansData();
}

function resetPointageFilters() {
    document.getElementById('filter-agent-pointage').value = '';
    document.getElementById('filter-statut-pointage').value = '';
    document.getElementById('filter-date-debut-pointage').value = '';
    document.getElementById('filter-date-fin-pointage').value = '';
    loadPointagesData();
}

// Chargement des statistiques du dashboard
async function loadDashboardStats() {
    try {
        const response = await fetch('{{ route("admin.agent.dashboard-stats") }}');
        if (!response.ok) throw new Error('Erreur réseau');
        
        const data = await response.json();
        
        // Mettre à jour les cartes
        document.getElementById('scans-today').textContent = data.scans_aujourdhui || 0;
        document.getElementById('pointages-today').textContent = data.pointages_aujourdhui || 0;
        document.getElementById('agents-presents').textContent = data.agents_presents || 0;
        
        // Calculer le taux de validation
        const tauxValidation = data.scans_aujourdhui > 0 ? 
            Math.round((data.scans_valides_aujourdhui / data.scans_aujourdhui) * 100) : 0;
        document.getElementById('scans-valides').textContent = tauxValidation + '%';
        
        // Mettre à jour les graphiques
        if (data.scans_7jours) {
            updateScansChart(data.scans_7jours);
        }
        if (data.pointages_7jours) {
            updatePointagesChart(data.pointages_7jours);
        }
        
    } catch (error) {
        console.error('Erreur lors du chargement des stats:', error);
        showError('scans-today', 'Erreur');
        showError('pointages-today', 'Erreur');
        showError('agents-presents', 'Erreur');
        showError('scans-valides', 'Erreur');
    }
}

function showError(elementId, message) {
    const element = document.getElementById(elementId);
    if (element) {
        element.textContent = message;
        element.style.color = '#e74a3b';
    }
}

// Chargement des données des agents
async function loadAgentsData() {
    try {
        const response = await fetch('{{ route("admin.agent.stats") }}');
        if (!response.ok) throw new Error('Erreur réseau');
        
        const data = await response.json();
        
        // Mettre à jour le compteur
        document.getElementById('agents-count').textContent = data.agents?.length || 0;
        
        // Remplir les select filters
        const agentSelectScan = document.getElementById('filter-agent-scan');
        const agentSelectPointage = document.getElementById('filter-agent-pointage');
        
        if (agentSelectScan && agentSelectPointage) {
            agentSelectScan.innerHTML = '<option value="">Tous les agents</option>';
            agentSelectPointage.innerHTML = '<option value="">Tous les agents</option>';
            
            if (data.agents && data.agents.length > 0) {
                data.agents.forEach(agent => {
                    const option = `<option value="${agent.id}">${agent.name || agent.nom} ${agent.prenom}</option>`;
                    agentSelectScan.innerHTML += option;
                    agentSelectPointage.innerHTML += option;
                });
            }
        }
        
        // Remplir le tableau des agents
        const tbody = document.getElementById('agentsTableBody');
        if (tbody) {
            tbody.innerHTML = '';
            
            if (data.agents && data.agents.length > 0) {
                data.agents.forEach(agent => {
                    const row = `
                        <tr>
                            <td class="text-center">${agent.name || agent.nom} ${agent.prenom}</td>
                            <td class="text-center">${agent.total_scans || 0}</td>
                            <td class="text-center"><span class="badge badge-success">${agent.scans_valides || 0}</span></td>
                            <td class="text-center"><span class="badge badge-danger">${agent.scans_invalides || 0}</span></td>
                            <td class="text-center">${agent.total_pointages || 0}</td>
                            <td class="text-center"><span class="badge badge-success">${agent.pointages_presents || 0}</span></td>
                            <td class="text-center"><span class="badge badge-warning">${agent.pointages_retards || 0}</span></td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fas fa-search mr-2"></i>Aucun agent trouvé
                        </td>
                    </tr>
                `;
            }
        }
        
    } catch (error) {
        console.error('Erreur lors du chargement des agents:', error);
        const tbody = document.getElementById('agentsTableBody');
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-danger py-4">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Erreur de chargement
                    </td>
                </tr>
            `;
        }
    }
}

// Chargement des scans
async function loadScansData(page = 1) {
    try {
        currentScansPage = page;
        
        const params = new URLSearchParams({
            page: page,
            agent_id: document.getElementById('filter-agent-scan')?.value || '',
            type_scan: document.getElementById('filter-type-scan')?.value || '',
            est_valide: document.getElementById('filter-validite-scan')?.value || '',
            date_debut: document.getElementById('filter-date-debut-scan')?.value || '',
            date_fin: document.getElementById('filter-date-fin-scan')?.value || ''
        });
        
        const response = await fetch(`{{ route("admin.agent.scans") }}?${params}`);
        if (!response.ok) throw new Error('Erreur réseau');
        
        const data = await response.json();
        
        // Mettre à jour le compteur
        document.getElementById('scans-count').textContent = data.scans?.total || 0;
        
        // Remplir le tableau
        const tbody = document.getElementById('scansTableBody');
        if (tbody) {
            tbody.innerHTML = '';
            
            if (data.scans && data.scans.data && data.scans.data.length > 0) {
                data.scans.data.forEach(scan => {
                    const row = `
                        <tr>
                            <td class="text-center">${scan.agent ? (scan.agent.name || scan.agent.nom) + ' ' + scan.agent.prenom : 'N/A'}</td>
                            <td class="text-center"><code class="code-scan">${scan.code_unique_scanne}</code></td>
                            <td class="text-center">${scan.nom_porte}</td>
                            <td class="text-center">
                                <span class="badge ${scan.type_scan === 'entree' ? 'badge-entree' : 'badge-sortie'}">
                                    ${scan.type_scan === 'entree' ? 'Entrée' : 'Sortie'}
                                </span>
                            </td>
                            <td class="text-center">${new Date(scan.heure_scan).toLocaleString('fr-FR')}</td>
                            <td class="text-center">
                                <span class="badge ${scan.est_valide ? 'badge-success' : 'badge-danger'}">
                                    ${scan.est_valide ? 'Valide' : 'Invalide'}
                                </span>
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-search mr-2"></i>Aucun scan trouvé
                        </td>
                    </tr>
                `;
            }
        }
        
        // Pagination
        updatePagination('scansPagination', data.scans, 'loadScansData');
        
    } catch (error) {
        console.error('Erreur lors du chargement des scans:', error);
        const tbody = document.getElementById('scansTableBody');
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-danger py-4">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Erreur de chargement
                    </td>
                </tr>
            `;
        }
    }
}

// Chargement des pointages
async function loadPointagesData(page = 1) {
    try {
        currentPointagesPage = page;
        
        const params = new URLSearchParams({
            page: page,
            agent_id: document.getElementById('filter-agent-pointage')?.value || '',
            statut: document.getElementById('filter-statut-pointage')?.value || '',
            date_debut: document.getElementById('filter-date-debut-pointage')?.value || '',
            date_fin: document.getElementById('filter-date-fin-pointage')?.value || ''
        });
        
        const response = await fetch(`{{ route("admin.agent.pointages") }}?${params}`);
        if (!response.ok) throw new Error('Erreur réseau');
        
        const data = await response.json();
        
        // Mettre à jour le compteur
        document.getElementById('pointages-count').textContent = data.pointages?.total || 0;
        
        // Remplir le tableau
        const tbody = document.getElementById('pointagesTableBody');
        if (tbody) {
            tbody.innerHTML = '';
            
            if (data.pointages && data.pointages.data && data.pointages.data.length > 0) {
                data.pointages.data.forEach(pointage => {
                    const row = `
                        <tr>
                            <td class="text-center">${pointage.agent.name || pointage.agent.nom} ${pointage.agent.prenom}</td>
                            <td class="text-center">${new Date(pointage.date_pointage).toLocaleDateString('fr-FR')}</td>
                            <td class="text-center">${pointage.heure_arrivee ? new Date(pointage.heure_arrivee).toLocaleTimeString('fr-FR') : '-'}</td>
                            <td class="text-center">${pointage.heure_depart ? new Date(pointage.heure_depart).toLocaleTimeString('fr-FR') : '-'}</td>
                            <td class="text-center">
                                <span class="badge ${getStatutBadge(pointage.statut)}">
                                    ${getStatutText(pointage.statut)}
                                </span>
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="fas fa-search mr-2"></i>Aucun pointage trouvé
                        </td>
                    </tr>
                `;
            }
        }
        
        // Pagination
        updatePagination('pointagesPagination', data.pointages, 'loadPointagesData');
        
    } catch (error) {
        console.error('Erreur lors du chargement des pointages:', error);
        const tbody = document.getElementById('pointagesTableBody');
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-danger py-4">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Erreur de chargement
                    </td>
                </tr>
            `;
        }
    }
}

// Fonctions utilitaires
function updatePagination(elementId, paginator, loadFunction) {
    const paginationDiv = document.getElementById(elementId);
    if (!paginationDiv) return;
    
    let paginationHtml = '';
    
    if (paginator.links) {
        paginator.links.forEach(link => {
            if (link.url) {
                const page = new URL(link.url).searchParams.get('page') || 1;
                const activeClass = link.active ? ' active' : '';
                const disabledClass = !link.url ? ' disabled' : '';
                
                paginationHtml += `
                    <li class="page-item${activeClass}${disabledClass}">
                        <a class="page-link" href="#" onclick="${loadFunction}(${page}); return false;">
                            ${link.label.replace('&laquo;', '«').replace('&raquo;', '»')}
                        </a>
                    </li>
                `;
            }
        });
    }
    
    paginationDiv.innerHTML = `
        <nav>
            <ul class="pagination justify-content-center">${paginationHtml}</ul>
        </nav>
    `;
}

function getStatutBadge(statut) {
    switch(statut) {
        case 'present': return 'badge-success';
        case 'en_retard': return 'badge-warning';
        case 'absent': return 'badge-danger';
        default: return 'badge-secondary';
    }
}

function getStatutText(statut) {
    switch(statut) {
        case 'present': return 'Présent';
        case 'en_retard': return 'En retard';
        case 'absent': return 'Absent';
        default: return statut;
    }
}

// Graphiques
function updateScansChart(data) {
    const ctx = document.getElementById('scansChart');
    if (!ctx) return;
    
    if (scansChart) {
        scansChart.destroy();
    }
    
    scansChart = new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels: data.labels || [],
            datasets: [{
                label: 'Nombre de scans',
                data: data.data || [],
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

function updatePointagesChart(data) {
    const ctx = document.getElementById('pointagesChart');
    if (!ctx) return;
    
    if (pointagesChart) {
        pointagesChart.destroy();
    }
    
    pointagesChart = new Chart(ctx.getContext('2d'), {
        type: 'bar',
        data: {
            labels: data.labels || [],
            datasets: [{
                label: 'Nombre de pointages',
                data: data.data || [],
                backgroundColor: '#1cc88a',
                borderColor: '#1cc88a',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}
</script>

<style>
:root {
    --primary-color: #193561;
    --primary-light: #2a4a7a;
    --primary-dark: #142a4a;
    --accent-color: #4e73df;
    --success-color: #1cc88a;
    --warning-color: #f6c23e;
    --danger-color: #e74a3b;
    --text-light: #ffffff;
    --text-muted: #6e707e;
    --bg-light: #f8f9fc;
    --border-color: #e3e6f0;
}

body {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    min-height: 100vh;
}

.container-fluid {
    background: transparent;
    padding: 20px;
}

/* Cartes de statistiques */
.stats-card {
    background: var(--text-light);
    border: none;
    border-radius: 15px;
    transition: all 0.3s ease;
    border-left: 4px solid var(--primary-color);
    height: 100%;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(25, 53, 97, 0.15) !important;
}

.stats-label {
    color: var(--text-muted);
    font-size: 0.8rem;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.stats-value {
    color: var(--primary-color);
    font-size: 1.8rem;
    margin: 0.5rem 0;
}

.stats-icon {
    font-size: 2.5rem;
    color: var(--primary-color);
    opacity: 0.7;
}

/* Cartes modernes */
.modern-card {
    background: var(--text-light);
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}

.modern-card-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    color: var(--text-light);
    border: none;
    padding: 1.5rem;
    display: flex;
    justify-content: between;
    align-items: center;
}

.modern-card-header h6 {
    margin: 0;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
}

.chart-legend {
    display: flex;
    gap: 1rem;
    font-size: 0.8rem;
}

.legend-item {
    display: flex;
    align-items: center;
}

/* Onglets modernes */
.modern-tabs {
    border: none;
    gap: 0.5rem;
}

.modern-tabs .nav-link {
    background: transparent;
    border: none;
    color: var(--text-light);
    padding: 1rem 1.5rem;
    border-radius: 10px 10px 0 0;
    transition: all 0.3s ease;
    opacity: 0.8;
    display: flex;
    align-items: center;
    font-weight: 500;
}

.modern-tabs .nav-link:hover {
    opacity: 1;
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}

.modern-tabs .nav-link.active {
    background: var(--text-light);
    color: var(--primary-color);
    opacity: 1;
    font-weight: 600;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
}

/* Filtres */
.filters-section {
    background: var(--bg-light);
    padding: 1.5rem;
    border-radius: 10px;
    border: 1px solid var(--border-color);
}

.filter-label {
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
    display: block;
}

.modern-select, .modern-input {
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.modern-select:focus, .modern-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(25, 53, 97, 0.1);
    outline: none;
}

/* Boutons */
.btn-refresh {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: var(--text-light);
    border-radius: 8px;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn-refresh:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(180deg);
}

.btn-filter {
    background: var(--primary-color);
    color: var(--text-light);
    border: none;
    border-radius: 8px;
    padding: 0.5rem;
    width: 40px;
    height: 40px;
    transition: all 0.3s ease;
}

.btn-filter:hover {
    background: var(--primary-dark);
    transform: scale(1.05);
}

.btn-reset {
    background: var(--text-muted);
    color: var(--text-light);
    border: none;
    border-radius: 8px;
    padding: 0.5rem;
    width: 40px;
    height: 40px;
    transition: all 0.3s ease;
}

.btn-reset:hover {
    background: #5a5c69;
    transform: scale(1.05);
}

/* Tableaux modernes */
.table-container {
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid var(--border-color);
    background: var(--text-light);
}

.modern-table {
    margin: 0;
    background: var(--text-light);
    width: 100%;
}

.modern-table thead th {
    background: var(--primary-color);
    color: var(--text-light);
    border: none;
    padding: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
    text-align: center;
}

.modern-table tbody td {
    padding: 1rem;
    border-color: var(--border-color);
    vertical-align: middle;
    text-align: center;
}

.modern-table tbody tr {
    transition: all 0.3s ease;
}

.modern-table tbody tr:hover {
    background: var(--bg-light);
    transform: translateX(5px);
}

/* Badges */
.badge {
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    min-width: 70px;
}

.badge-entree {
    background: var(--success-color);
    color: var(--text-light);
}

.badge-sortie {
    background: var(--warning-color);
    color: var(--text-light);
}

.badge-success {
    background: var(--success-color);
    color: var(--text-light);
}

.badge-warning {
    background: var(--warning-color);
    color: var(--text-light);
}

.badge-danger {
    background: var(--danger-color);
    color: var(--text-light);
}

/* Code scan */
.code-scan {
    background: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 0.8rem;
    color: var(--primary-color);
}

/* Pagination */
.modern-pagination {
    margin-top: 1.5rem;
    padding: 1rem 0;
}

.modern-pagination .pagination {
    justify-content: center;
    margin: 0;
}

.modern-pagination .page-item .page-link {
    border: 1px solid var(--border-color);
    color: var(--primary-color);
    margin: 0 0.25rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
}

.modern-pagination .page-item.active .page-link {
    background: var(--primary-color);
    border-color: var(--primary-color);
    color: var(--text-light);
}

.modern-pagination .page-item .page-link:hover {
    background: var(--bg-light);
    border-color: var(--primary-color);
    transform: translateY(-1px);
}

/* Graphiques */
.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
    padding: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding: 10px;
    }
    
    .stats-value {
        font-size: 1.5rem;
    }
    
    .stats-icon {
        font-size: 2rem;
    }
    
    .modern-tabs .nav-link {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
    }
    
    .filters-section .row > div {
        margin-bottom: 1rem;
    }
    
    .modern-table thead th {
        padding: 0.75rem 0.5rem;
        font-size: 0.7rem;
    }
    
    .modern-table tbody td {
        padding: 0.75rem 0.5rem;
        font-size: 0.8rem;
    }
}

/* Animations */
@keyframes fadeIn {
    from { 
        opacity: 0; 
        transform: translateY(20px); 
    }
    to { 
        opacity: 1; 
        transform: translateY(0); 
    }
}

.stats-card, .modern-card {
    animation: fadeIn 0.6s ease-out;
}

/* Text colors */
.text-white {
    color: var(--text-light) !important;
}

.text-muted {
    color: var(--text-muted) !important;
}

.text-success {
    color: var(--success-color) !important;
}

.text-danger {
    color: var(--danger-color) !important;
}

/* Loading states */
.fa-spinner {
    color: var(--primary-color);
}

/* Badge pills in tabs */
.badge-pill {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}
</style>
@endsection