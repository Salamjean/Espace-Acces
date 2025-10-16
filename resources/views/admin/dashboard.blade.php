@extends('admin.layouts.template')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary: #193561;
        --secondary: #2c4b8a;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #3b82f6;
        --light: #f8fafc;
        --dark: #1e293b;
    }

    .dashboard-container {
        min-height: 100vh;
        padding: 20px;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 25px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
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
        background: linear-gradient(90deg, var(--primary), var(--secondary));
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 15px;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--dark);
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-trend {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 12px;
        margin-top: 8px;
        display: inline-block;
    }

    .trend-up { background: rgba(16, 185, 129, 0.1); color: var(--success); }
    .trend-down { background: rgba(239, 68, 68, 0.1); color: var(--danger); }

    .chart-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 25px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .action-btn {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: none;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        color: var(--dark);
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        color: var(--primary);
    }

    .action-icon {
        font-size: 2rem;
        margin-bottom: 10px;
        color: var(--primary);
    }

    .recent-activity {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 25px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .activity-item {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 18px;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 2px;
    }

    .activity-time {
        font-size: 0.8rem;
        color: #64748b;
    }

    .badge-stat {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .welcome-card {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 25px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        position: relative;
        overflow: hidden;
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .welcome-title {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 10px;
    }

    .welcome-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 20px;
    }

    .progress-ring {
        width: 80px;
        height: 80px;
    }

    .progress-bg {
        fill: none;
        stroke: #e2e8f0;
        stroke-width: 8;
    }

    .progress-fill {
        fill: none;
        stroke-width: 8;
        stroke-linecap: round;
        transform: rotate(-90deg);
        transform-origin: 50% 50%;
        transition: stroke-dasharray 0.3s ease;
    }

    @media (max-width: 768px) {
        .stat-number {
            font-size: 2rem;
        }
        
        .quick-actions {
            grid-template-columns: 1fr;
        }
        
        .welcome-title {
            font-size: 1.5rem;
        }
    }
</style>

<div class="dashboard-container">
    <div class="container-fluid">
        <!-- Carte de bienvenue -->
        <div class="welcome-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="welcome-title">Tableau de Bord</h1>
                    <p class="welcome-subtitle">Bienvenue dans votre espace d'administration</p>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="badge-stat">
                            <i class="bi bi-people me-1"></i> {{ $totalPersonnes ?? 0 }} Personnes
                        </span>
                        <span class="badge-stat">
                            <i class="bi bi-clock me-1"></i> {{ now()->format('d/m/Y') }}
                        </span>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <i class="bi bi-speedometer2" style="font-size: 4rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="quick-actions">
            <a href="{{ route('admin.demandes.index') }}" class="action-btn">
                <i class="bi bi-list-check action-icon"></i>
                <div>Gérer les Demandes</div>
            </a>
            <a href="{{ route('admin.permanent-personnel.index') }}" class="action-btn">
                <i class="bi bi-person-badge action-icon"></i>
                <div>Personnel Permanent</div>
            </a>
            <a href="{{ route('admin.visite.history') }}" class="action-btn">
                <i class="bi bi-clock-history action-icon"></i>
                <div>Historique Visites</div>
            </a>
            <a href="{{ route('admin.demandes.index') }}?statut=en_attente" class="action-btn">
                <i class="bi bi-exclamation-circle action-icon"></i>
                <div>Demandes En Attente</div>
            </a>
        </div>

        <div class="row">
            <!-- Statistiques des demandes -->
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-card">
                            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="stat-number">{{ $totalPersonnes ?? 0 }}</div>
                            <div class="stat-label">Total Personnes</div>
                            <span class="stat-trend trend-up">
                                <i class="bi bi-arrow-up"></i> Total
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-card">
                            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div class="stat-number">{{ $personnesEnAttente ?? 0 }}</div>
                            <div class="stat-label">En Attente</div>
                            <span class="stat-trend trend-down">
                                <i class="bi bi-clock"></i> En cours
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-card">
                            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                                <i class="bi bi-check-circle"></i>
                            </div>
                            <div class="stat-number">{{ $personnesApprouvees ?? 0 }}</div>
                            <div class="stat-label">Approuvées</div>
                            <span class="stat-trend trend-up">
                                <i class="bi bi-arrow-up"></i> Validées
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-card">
                            <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">
                                <i class="bi bi-x-circle"></i>
                            </div>
                            <div class="stat-number">{{ $personnesRejetees ?? 0 }}</div>
                            <div class="stat-label">Rejetées</div>
                            <span class="stat-trend trend-down">
                                <i class="bi bi-arrow-down"></i> Refusées
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Graphiques et statistiques détaillées -->
                <div class="chart-container">
                    <h3 class="section-title">
                        <i class="bi bi-bar-chart"></i> Statistiques des Demandes
                    </h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Demandes Individuelles</span>
                                <strong>{{ $demandesIndividuelles ?? 0 }}</strong>
                            </div>
                            <div class="progress mb-4" style="height: 10px;">
                                <div class="progress-bar bg-success" style="width: {{ $totalPersonnes > 0 ? ($demandesIndividuelles / $totalPersonnes) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>Demandes de Groupe</span>
                                <strong>{{ $demandesGroupe ?? 0 }}</strong>
                            </div>
                            <div class="progress mb-4" style="height: 10px;">
                                <div class="progress-bar bg-info" style="width: {{ $totalPersonnes > 0 ? ($demandesGroupe / $totalPersonnes) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activité récente et stats secondaires -->
            <div class="col-md-4">
                <div class="recent-activity">
                    <h3 class="section-title">
                        <i class="bi bi-activity"></i> Activité Récente
                    </h3>
                    
                    <div class="activity-item">
                        <div class="activity-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Demandes approuvées aujourd'hui</div>
                            <div class="activity-time">{{ $personnesApprouvees ?? 0 }} validations</div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning);">
                            <i class="bi bi-clock"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">En attente de traitement</div>
                            <div class="activity-time">{{ $personnesEnAttente ?? 0 }} demandes</div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--info);">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Personnel permanent</div>
                            <div class="activity-time">Gestion des accès</div>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Performance système</div>
                            <div class="activity-time">Tout fonctionne normalement</div>
                        </div>
                    </div>
                </div>

                <!-- Stats secondaires -->
                <div class="stat-card">
                    <h3 class="section-title">
                        <i class="bi bi-pie-chart"></i> Répartition
                    </h3>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Terminées</span>
                        <strong>{{ $personnesTerminees ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Annulées</span>
                        <strong>{{ $personnesAnnulees ?? 0 }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Taux de réussite</span>
                        <strong>
                            {{ $totalPersonnes > 0 ? round(($personnesApprouvees / $totalPersonnes) * 100, 1) : 0 }}%
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Animation des compteurs
    document.addEventListener('DOMContentLoaded', function() {
        const statNumbers = document.querySelectorAll('.stat-number');
        
        statNumbers.forEach(stat => {
            const target = parseInt(stat.textContent);
            let current = 0;
            const increment = target / 50;
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    stat.textContent = target;
                    clearInterval(timer);
                } else {
                    stat.textContent = Math.floor(current);
                }
            }, 30);
        });
    });

    // Mise à jour en temps réel (simulation)
    setInterval(() => {
        // Ici vous pourriez faire un appel AJAX pour les données en temps réel
        console.log('Mise à jour des données...');
    }, 30000); // Toutes les 30 secondes
</script>
@endsection