@extends('agent.layouts.template')
@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<div class="container-fluid" style="margin-top:10px">
    <!-- Header avec Bienvenue -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border: none; border-radius: 20px; background: linear-gradient(135deg, #193561 0%, #2c4b8a 100%);">
                <div class="card-body py-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="avatar-xl bg-white rounded-circle d-flex align-items-center justify-content-center me-4 shadow">
                                    <i class="bi bi-person-check text-primary" style="font-size: 2.5rem;"></i>
                                </div>
                                <div>
                                    <h2 class="mb-2 text-white">Bonjour, <strong>{{ $admin->name ?? 'Agent' }}</strong> !</h2>
                                    <p class="mb-0 text-white opacity-75 fs-5">
                                        <i class="bi bi-calendar-check me-2"></i>
                                        Bienvenue sur votre tableau de bord
                                    </p>
                                    <small class="text-white opacity-50">
                                        <i class="bi bi-clock me-1"></i>
                                        <span id="current-time">{{ now()->format('H:i') }}</span> • 
                                        <span id="current-date">{{ now()->format('d F Y') }}</span>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex flex-column align-items-end gap-3">
                                <!-- Section Pointage -->
                                <div class="pointage-section">
                                    <div class="pointage-info mb-2">
                                        <small class="text-white opacity-75" id="pointage-status">
                                            Chargement...
                                        </small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button id="btn-arrivee" class="btn btn-success btn-sm">
                                            <i class="bi bi-box-arrow-in-right me-1"></i>
                                            Arrivée
                                        </button>
                                        <button id="btn-depart" class="btn btn-danger btn-sm">
                                            <i class="bi bi-box-arrow-right me-1"></i>
                                            Départ
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes d'Action Principales -->
    {{-- <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <div class="card action-card entry-card" data-action="entry" 
                 style="border: none; border-radius: 20px; cursor: pointer; transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <div class="icon-circle-lg" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                <i class="bi bi-box-arrow-in-right"></i>
                            </div>
                        </div>
                        <div class="col-9">
                            <h4 class="card-title mb-2" style="color: #28a745;">Enregistrer une Entrée</h4>
                            <p class="card-text text-muted mb-3">
                                Scanner le QR code d'un visiteur pour enregistrer son entrée
                            </p>
                            <div class="action-btn">
                                <button class="btn btn-lg" 
                                        style="background: #28a745; color: white; border: none; border-radius: 12px; padding: 12px 30px;">
                                    Commencer <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card action-card exit-card" data-action="exit" 
                 style="border: none; border-radius: 20px; cursor: pointer; transition: all 0.3s ease;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-3">
                            <div class="icon-circle-lg" style="background: linear-gradient(135deg, #dc3545 0%, #e35d6a 100%);">
                                <i class="bi bi-box-arrow-right"></i>
                            </div>
                        </div>
                        <div class="col-9">
                            <h4 class="card-title mb-2" style="color: #dc3545;">Enregistrer une Sortie</h4>
                            <p class="card-text text-muted mb-3">
                                Scanner le QR code pour enregistrer la sortie d'un visiteur
                            </p>
                            <div class="action-btn">
                                <button class="btn btn-lg" 
                                        style="background: #dc3545; color: white; border: none; border-radius: 12px; padding: 12px 30px;">
                                    Commencer <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

   <!-- Statistiques en Temps Réel -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-light py-3">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>Statistiques du Jour
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card-primary">
                            <div class="stat-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number" id="total-entrees">{{ $stats['total_entrees'] }}</div>
                                <div class="stat-label">Entrées Aujourd'hui</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card-warning">
                            <div class="stat-icon">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number" id="visiteurs-presence">{{ $stats['visiteurs_presence'] }}</div>
                                <div class="stat-label">Visiteurs Présents</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card-success">
                            <div class="stat-icon">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <div class="stat-content">
                                <div class="stat-number" id="total-sorties">{{ $stats['total_sorties'] }}</div>
                                <div class="stat-label">Sorties Aujourd'hui</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="stat-card-info">
                            <div class="stat-icon">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div class="stat-content">
                                @if($stats['a_des_codes_en_attente'])
                                    <div class="stat-number" id="temps-restant">
                                        {{ $stats['temps_plus_court'] }}
                                    </div>
                                    <div class="stat-label">Prochaine vérification</div>
                                    <!-- NOUVEAU : Affichage du compteur -->
                                    <div class="stat-timer mt-2">
                                        <small class="text-white opacity-75">
                                            <i class="bi bi-qr-code me-1"></i>
                                            {{ $stats['scans_2h'] }}/{{ $stats['total_codes_acces'] }} scans
                                        </small>
                                    </div>
                                @else
                                    <div class="stat-number" id="temps-restant">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <div class="stat-label">Tous disponibles</div>
                                    <!-- NOUVEAU : Affichage du compteur quand tout est disponible -->
                                    <div class="stat-timer mt-2">
                                        <small class="text-white opacity-75">
                                            <i class="bi bi-qr-code me-1"></i>
                                            {{ $stats['scans_2h'] }}/{{ $stats['total_codes_acces'] }} scans (2h)
                                        </small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Dernières Visites & Accès Rapides -->
    <div class="row">
        <!-- Dernières Visites -->
        <div class="col-lg-12 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history me-2"></i>Dernières Visites
                    </h5>
                    <a href="{{ route('visite.history') }}" class="btn btn-sm btn-outline-primary">
                        Voir tout <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">Visiteur</th>
                                    <th class="text-center">Heure Entrée</th>
                                    <th class="text-center">Heure Sortie</th>
                                    <th class="text-center">Statut</th>
                                </tr>
                            </thead>
                            <tbody id="recent-visits">
                                <!-- Les données seront chargées via AJAX -->
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Chargement...</span>
                                        </div>
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

<script>
$(document).ready(function() {
    // Mise à jour de l'heure en temps réel
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('fr-FR', { 
            hour: '2-digit', 
            minute: '2-digit'
        });
        const dateString = now.toLocaleDateString('fr-FR', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        $('#current-time').text(timeString);
        $('#current-date').text(dateString.charAt(0).toUpperCase() + dateString.slice(1));
    }

    // Vérifier si l'arrivée a été pointée aujourd'hui
    function checkArrivalStatus() {
        $.ajax({
            url: '{{ route("agent.statut-pointage") }}',
            type: 'GET',
            success: function(response) {
                // Si l'agent n'a pas pointé son arrivée aujourd'hui
                if (!response.a_pointé_arrivee) {
                    showArrivalAlert();
                }
                // Mettre à jour l'interface du pointage
                mettreAJourInterfacePointage(response);
            },
            error: function() {
                console.error('Erreur lors de la vérification du statut de pointage');
                // En cas d'erreur, on suppose que l'arrivée n'est pas pointée
                showArrivalAlert();
            }
        });
    }

    // Afficher l'alerte d'arrivée
    function showArrivalAlert() {
        Swal.fire({
            title: 'Pointage d\'arrivée requis',
            text: 'Vous devez pointer votre arrivée avant de mener les actions.',
            icon: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Pointer mon arrivée',
            allowOutsideClick: false,
            allowEscapeKey: false,
            backdrop: true,
            background: '#ffffff',
            color: '#333',
            position: 'center',
            customClass: {
                popup: 'arrival-alert-popup'
            },
            didOpen: () => {
                // Désactiver l'interface derrière le popup
                $('.container-fluid').addClass('blurred-interface');
            }
        }).then((result) => {
            if (result.isConfirmed) {
                pointerArrivee();
            }
        });
    }

    // Pointer l'arrivée
    function pointerArrivee() {
        $.ajax({
            url: '{{ route("agent.pointer-arrivee") }}',
            type: 'POST',
            data: { 
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Arrivée pointée !',
                        text: response.message,
                        icon: 'success',
                        confirmButtonColor: '#28a745',
                        confirmButtonText: 'Commencer',
                        background: '#ffffff',
                        color: '#333',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(() => {
                        // Réactiver l'interface et recharger
                        $('.container-fluid').removeClass('blurred-interface');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Attention',
                        text: response.message,
                        icon: 'warning',
                        confirmButtonColor: '#ffc107',
                        confirmButtonText: 'OK',
                        background: '#ffffff',
                        color: '#333'
                    }).then(() => {
                        // Réactiver l'interface même en cas d'erreur
                        $('.container-fluid').removeClass('blurred-interface');
                        location.reload();
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Erreur',
                    text: 'Erreur lors du pointage de l\'arrivée: ' + (xhr.responseJSON?.message || error),
                    icon: 'error',
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Réessayer',
                    background: '#ffffff',
                    color: '#333'
                }).then((result) => {
                    if (result.isConfirmed) {
                        pointerArrivee(); // Réessayer
                    } else {
                        $('.container-fluid').removeClass('blurred-interface');
                        location.reload();
                    }
                });
            }
        });
    }

    // Mettre à jour l'interface du pointage
    function mettreAJourInterfacePointage(statutPointage = null) {
        const statusElement = $('#pointage-status');
        const btnArrivee = $('#btn-arrivee');
        const btnDepart = $('#btn-depart');

        // Si on ne reçoit pas de statut en paramètre, on le récupère via AJAX
        if (!statutPointage) {
            $.ajax({
                url: '{{ route("agent.statut-pointage") }}',
                type: 'GET',
                success: function(response) {
                    mettreAJourInterfacePointage(response);
                },
                error: function() {
                    statusElement.html('<span class="text-warning">Erreur de chargement</span>');
                }
            });
            return;
        }

        if (statutPointage.a_pointé_arrivee && !statutPointage.a_pointé_depart) {
            statusElement.html(`<i class="bi bi-check-circle me-1"></i>Arrivée: ${statutPointage.heure_arrivee}`);
            btnArrivee.prop('disabled', true).addClass('btn-secondary');
            btnDepart.prop('disabled', false).removeClass('btn-secondary');
        } else if (statutPointage.a_pointé_depart) {
            statusElement.html(`<i class="bi bi-clock-history me-1"></i>Terminé: ${statutPointage.heure_arrivee} - ${statutPointage.heure_depart}`);
            btnArrivee.prop('disabled', true).addClass('btn-secondary');
            btnDepart.prop('disabled', true).addClass('btn-secondary');
        } else {
            statusElement.html('<i class="bi bi-exclamation-circle me-1"></i>En attente d\'arrivée');
            btnArrivee.prop('disabled', false).removeClass('btn-secondary');
            btnDepart.prop('disabled', true).addClass('btn-secondary');
        }
    }

    // Effets hover sur les cartes d'action
    $('.action-card').hover(
        function() {
            $(this).css({
                'transform': 'translateY(-8px)',
                'box-shadow': '0 15px 35px rgba(0, 0, 0, 0.15)'
            });
        },
        function() {
            $(this).css({
                'transform': 'translateY(0)',
                'box-shadow': '0 5px 15px rgba(0, 0, 0, 0.08)'
            });
        }
    );

    // Gestion du clic sur les cartes d'action
    $('.action-card').click(function() {
        const action = $(this).data('action');
        
        // Effet de clic
        $(this).css('transform', 'scale(0.98)');
        setTimeout(() => {
            $(this).css('transform', 'scale(1)');
        }, 150);

        // Redirection
        if (action === 'entry') {
            window.location.href = '{{ route("visite.create") }}';
        } else if (action === 'exit') {
            window.location.href = '{{ route("visite.sortie") }}';
        }
    });

    // Chargement des statistiques
    function loadStatistics() {
        // Les statistiques sont déjà chargées dans la page via PHP
        // On peut juste animer les nombres
        animateNumber($('#total-entrees'), {{ $stats['total_entrees'] }});
        animateNumber($('#visiteurs-presence'), {{ $stats['visiteurs_presence'] }});
        animateNumber($('#total-sorties'), {{ $stats['total_sorties'] }});
        
        // Charger les dernières visites via AJAX
        loadRecentVisits();

        // Démarrer le décompte du temps
        startCountdown();
    }

    // Décompte en temps réel pour le temps le plus court
    function startCountdown() {
        function updateCountdown() {
            @if($stats['a_des_codes_en_attente'])
                // Récupérer le temps le plus court depuis les données PHP
                const codesEnAttente = @json($stats['liste_codes_attente']);
                
                if (codesEnAttente.length > 0) {
                    // Trouver le temps le plus court
                    let plusCourtTemps = null;
                    let plusCourtElement = null;

                    codesEnAttente.forEach(code => {
                        const endTime = new Date(code.prochain_scan);
                        const now = new Date();
                        const diff = endTime - now;

                        if (diff > 0 && (plusCourtTemps === null || diff < plusCourtTemps)) {
                            plusCourtTemps = diff;
                            plusCourtElement = code;
                        }
                    });

                    if (plusCourtTemps !== null && plusCourtElement !== null) {
                        const hours = Math.floor(plusCourtTemps / (1000 * 60 * 60));
                        const minutes = Math.floor((plusCourtTemps % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((plusCourtTemps % (1000 * 60)) / 1000);
                        
                        const tempsFormat = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                        
                        $('#temps-restant').text(tempsFormat);
                        
                        // Changer la couleur selon le temps restant
                        if (hours === 0 && minutes < 5) {
                            $('#temps-restant').addClass('text-warning').removeClass('text-white');
                        } else if (hours === 0 && minutes < 1) {
                            $('#temps-restant').addClass('text-danger').removeClass('text-white text-warning');
                        } else {
                            $('#temps-restant').addClass('text-white').removeClass('text-warning text-danger');
                        }
                    } else {
                        // Tous les temps sont écoulés, recharger la page
                        location.reload();
                    }
                }
            @endif
        }

        // Mettre à jour immédiatement et toutes les secondes
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    // Animation des nombres
    function animateNumber(element, target) {
        const duration = 1500;
        const step = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                element.text(target);
                clearInterval(timer);
            } else {
                element.text(Math.floor(current));
            }
        }, 16);
    }

    // Charger les dernières visites via AJAX
    function loadRecentVisits() {
        $.ajax({
            url: '{{ route("visite.recent") }}',
            type: 'GET',
            success: function(response) {
                $('#recent-visits').html(response);
                // Réinitialiser les tooltips
                $('[data-bs-toggle="tooltip"]').tooltip();
            },
            error: function() {
                $('#recent-visits').html(`
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Impossible de charger les données
                        </td>
                    </tr>
                `);
            }
        });
    }

    // Gestion du pointage via les boutons
    let statutPointage = {
        a_pointé_arrivee: false,
        a_pointé_depart: false,
        heure_arrivee: null,
        heure_depart: null
    };

    // Charger le statut du pointage
    function chargerStatutPointage() {
        $.ajax({
            url: '{{ route("agent.statut-pointage") }}',
            type: 'GET',
            success: function(response) {
                statutPointage = response;
                mettreAJourInterfacePointage(response);
            },
            error: function() {
                $('#pointage-status').html('<span class="text-warning">Erreur de chargement</span>');
            }
        });
    }

    // Pointer l'arrivée depuis le bouton
    function pointerArriveeFromButton(button, originalHtml) {
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Pointage...');

        $.ajax({
            url: '{{ route("agent.pointer-arrivee") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    showSweetAlert('success', response.message, function() {
                        location.reload();
                    });
                } else {
                    showSweetAlert('warning', response.message);
                    button.prop('disabled', false).html(originalHtml);
                }
            },
            error: function() {
                showSweetAlert('error', 'Erreur lors du pointage');
                button.prop('disabled', false).html(originalHtml);
            }
        });
    }

    // Pointer le départ depuis le bouton
    function pointerDepartFromButton(button, originalHtml) {
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Pointage...');

        $.ajax({
            url: '{{ route("agent.pointer-depart") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    showSweetAlert('success', response.message, function() {
                        location.reload();
                    });
                } else {
                    showSweetAlert('warning', response.message);
                    button.prop('disabled', false).html(originalHtml);
                }
            },
            error: function() {
                showSweetAlert('error', 'Erreur lors du pointage');
                button.prop('disabled', false).html(originalHtml);
            }
        });
    }

    // Fonction pour afficher les alertes SweetAlert2
    function showSweetAlert(icon, message, callback = null) {
        const title = icon === 'success' ? 'Succès' : 
                     icon === 'warning' ? 'Attention' : 
                     'Erreur';

        const background = icon === 'success' ? '#28a745' :
                          icon === 'warning' ? '#ffc107' :
                          '#dc3545';

        Swal.fire({
            icon: icon,
            title: title,
            text: message,
            background: '#ffffff',
            color: '#333',
            iconColor: background,
            confirmButtonColor: background,
            confirmButtonText: 'OK',
            timer: icon === 'success' ? null : 5000,
            timerProgressBar: icon !== 'success',
            toast: false,
            position: 'center',
            showClass: {
                popup: 'animate__animated animate__fadeIn'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOut'
            }
        }).then((result) => {
            if (result.isConfirmed && callback) {
                callback();
            }
        });
    }

    // Événements pour les boutons de pointage
    $(document).on('click', '#btn-arrivee', function() {
        if ($(this).prop('disabled')) return;

        const button = $(this);
        const originalHtml = button.html();

        Swal.fire({
            title: 'Pointer votre arrivée ?',
            text: 'Êtes-vous sûr de vouloir pointer votre arrivée ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, pointer arrivée',
            cancelButtonText: 'Annuler',
            background: '#ffffff',
            color: '#333',
            reverseButtons: true,
            position: 'center'
        }).then((result) => {
            if (result.isConfirmed) {
                pointerArriveeFromButton(button, originalHtml);
            }
        });
    });

    $(document).on('click', '#btn-depart', function() {
        if ($(this).prop('disabled')) return;

        const button = $(this);
        const originalHtml = button.html();

        Swal.fire({
            title: 'Pointer votre départ ?',
            text: 'Êtes-vous sûr de vouloir pointer votre départ ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, pointer départ',
            cancelButtonText: 'Annuler',
            background: '#ffffff',
            color: '#333',
            reverseButtons: true,
            position: 'center'
        }).then((result) => {
            if (result.isConfirmed) {
                pointerDepartFromButton(button, originalHtml);
            }
        });
    });

    // Actualiser les statistiques
    $('#refresh-stats').click(function() {
        $(this).find('i').addClass('spin');
        setTimeout(() => {
            loadStatistics();
            $(this).find('i').removeClass('spin');
        }, 500);
    });

    // Initialisation
    updateTime();
    setInterval(updateTime, 1000);
    
    // Vérifier le statut d'arrivée au chargement de la page
    setTimeout(() => {
        checkArrivalStatus();
    }, 1000);

    // Charger les données initiales
    loadStatistics();
    chargerStatutPointage();
    
    // Auto-refresh toutes les 30 secondes
    setInterval(loadStatistics, 30000);
    
    // Actualiser le statut toutes les minutes
    setInterval(chargerStatutPointage, 60000);
});

// Fonctions globales supplémentaires
function confirmerAction(action, callback) {
    const config = {
        'delete': {
            title: 'Confirmer la suppression',
            text: 'Êtes-vous sûr de vouloir supprimer cet élément ?',
            icon: 'warning',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Oui, supprimer'
        },
        'logout': {
            title: 'Déconnexion',
            text: 'Êtes-vous sûr de vouloir vous déconnecter ?',
            icon: 'question',
            confirmButtonColor: '#6c757d',
            confirmButtonText: 'Oui, se déconnecter'
        },
        'default': {
            title: 'Confirmer l\'action',
            text: 'Êtes-vous sûr de vouloir effectuer cette action ?',
            icon: 'question',
            confirmButtonColor: '#193561',
            confirmButtonText: 'Oui, confirmer'
        }
    };

    const settings = config[action] || config['default'];

    Swal.fire({
        ...settings,
        showCancelButton: true,
        cancelButtonText: 'Annuler',
        background: '#ffffff',
        color: '#333',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed && callback) {
            callback();
        }
    });
}

// Gestion des erreurs AJAX globales
$(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
    if (jqXHR.status === 419) { // Token CSRF expiré
        Swal.fire({
            title: 'Session expirée',
            text: 'Votre session a expiré. Veuillez vous reconnecter.',
            icon: 'warning',
            confirmButtonColor: '#193561',
            confirmButtonText: 'Se reconnecter',
            allowOutsideClick: false
        }).then(() => {
            window.location.reload();
        });
    } else if (jqXHR.status === 403) { // Accès refusé
        Swal.fire({
            title: 'Accès refusé',
            text: 'Vous n\'avez pas les permissions nécessaires pour cette action.',
            icon: 'error',
            confirmButtonColor: '#dc3545'
        });
    } else if (jqXHR.status === 500) { // Erreur serveur
        Swal.fire({
            title: 'Erreur serveur',
            text: 'Une erreur interne est survenue. Veuillez réessayer.',
            icon: 'error',
            confirmButtonColor: '#dc3545'
        });
    }
});
</script>

<style>
.avatar-xl {
    width: 80px;
    height: 80px;
}

.icon-circle-lg {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.8rem;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.action-card {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.action-card:hover {
    border-color: rgba(25, 53, 97, 0.1);
}

.arrival-alert-popup {
    border-radius: 20px;
    box-shadow: 0 10px 50px rgba(0, 0, 0, 0.3);
    border: 2px solid #28a745;
}

.blurred-interface {
    filter: blur(3px);
    pointer-events: none;
    user-select: none;
}

/* Animation pour les boutons de pointage */
.btn-success, .btn-danger {
    transition: all 0.3s ease;
}

.btn-success:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
}

.btn-danger:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
}

/* Style pour les états désactivés */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

/* Animation spin pour les indicateurs de chargement */
.spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Responsive pour les alertes */
@media (max-width: 768px) {
    .arrival-alert-popup {
        margin: 20px;
        width: auto !important;
    }
    
    .blurred-interface {
        filter: blur(2px);
    }
}

/* Cartes de statistiques */
.stat-card-primary, .stat-card-warning, .stat-card-success, .stat-card-info {
    padding: 1.5rem;
    border-radius: 15px;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
}
.stat-timer {
    border-top: 1px solid rgba(255,255,255,0.3);
    padding-top: 8px;
    margin-top: 8px;
}

.stat-card-primary { background: linear-gradient(135deg, #193561 0%, #2c4b8a 100%); color: white; }
.stat-card-warning { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: white; }
.stat-card-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; }
.stat-card-info { background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%); color: white; }

.stat-card-primary:hover, .stat-card-warning:hover, .stat-card-success:hover, .stat-card-info:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.stat-icon {
    font-size: 2.5rem;
    opacity: 0.9;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 2.2rem;
    font-weight: bold;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
    font-weight: 500;
}

/* Table */
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

/* Boutons d'accès rapides */
.btn-outline-primary, .btn-outline-success, .btn-outline-danger, .btn-outline-info {
    border-radius: 12px;
    transition: all 0.3s ease;
    border-width: 2px;
}

.btn-outline-primary:hover { background: #193561; color: white; }
.btn-outline-success:hover { background: #28a745; color: white; }
.btn-outline-danger:hover { background: #dc3545; color: white; }
.btn-outline-info:hover { background: #17a2b8; color: white; }

/* Animation spin */
.spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
    .avatar-xl {
        width: 60px;
        height: 60px;
    }
    
    .icon-circle-lg {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }
    
    .stat-number {
        font-size: 1.8rem;
    }
    
    .stat-icon {
        font-size: 2rem;
    }
    
    .card-body.p-4 {
        padding: 1.5rem !important;
    }
}



/* Badges */
.badge {
    border-radius: 10px;
    font-weight: 500;
}

.pointage-section {
    background: rgba(255, 255, 255, 0.1);
    padding: 15px;
    border-radius: 15px;
    backdrop-filter: blur(10px);
}

.pointage-info {
    min-height: 20px;
}

.btn-sm {
    padding: 8px 16px;
    font-size: 0.875rem;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.btn-sm:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

/* Animation pour les boutons désactivés */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Alertes */
.alert {
    border: none;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
}

/* Responsive */
@media (max-width: 768px) {
    .pointage-section {
        text-align: center;
    }
    
    .d-flex.gap-2 {
        justify-content: center;
    }
}
</style>
<script>
$(document).ready(function() {
    // Mise à jour de l'heure en temps réel
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('fr-FR', { 
            hour: '2-digit', 
            minute: '2-digit'
        });
        const dateString = now.toLocaleDateString('fr-FR', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        $('#current-time').text(timeString);
        $('#current-date').text(dateString.charAt(0).toUpperCase() + dateString.slice(1));
    }
    
    setInterval(updateTime, 1000);
    updateTime();

    // Effets hover sur les cartes d'action
    $('.action-card').hover(
        function() {
            $(this).css({
                'transform': 'translateY(-8px)',
                'box-shadow': '0 15px 35px rgba(0, 0, 0, 0.15)'
            });
        },
        function() {
            $(this).css({
                'transform': 'translateY(0)',
                'box-shadow': '0 5px 15px rgba(0, 0, 0, 0.08)'
            });
        }
    );

    

    // Gestion du clic sur les cartes d'action
    $('.action-card').click(function() {
        const action = $(this).data('action');
        
        // Effet de clic
        $(this).css('transform', 'scale(0.98)');
        setTimeout(() => {
            $(this).css('transform', 'scale(1)');
        }, 150);

        // Redirection
        if (action === 'entry') {
            window.location.href = '{{ route("visite.create") }}';
        } else if (action === 'exit') {
            window.location.href = '{{ route("visite.sortie") }}';
        }
    });

    // Chargement des statistiques
    function loadStatistics() {
        // Les statistiques sont déjà chargées dans la page via PHP
        // On peut juste animer les nombres
        animateNumber($('#total-entrees'), {{ $stats['total_entrees'] }});
        animateNumber($('#visiteurs-presence'), {{ $stats['visiteurs_presence'] }});
        animateNumber($('#total-sorties'), {{ $stats['total_sorties'] }});
        animateNumber($('#codes-attente .stat-number'), {{ $stats['codes_en_attente'] }});
        
        // Charger les dernières visites via AJAX
        loadRecentVisits();
    }

    // Charger les dernières visites via AJAX
    function loadRecentVisits() {
        $.ajax({
            url: '{{ route("visite.recent") }}',
            type: 'GET',
            success: function(response) {
                $('#recent-visits').html(response);
                // Réinitialiser les tooltips
                $('[data-bs-toggle="tooltip"]').tooltip();
            },
            error: function() {
                $('#recent-visits').html(`
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Impossible de charger les données
                        </td>
                    </tr>
                `);
            }
        });
    }

    // Animation des nombres
    function animateNumber(element, target) {
        const duration = 1500;
        const step = target / (duration / 16);
        let current = 0;
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                element.text(target);
                clearInterval(timer);
            } else {
                element.text(Math.floor(current));
            }
        }, 16);
    }

    // Gestion du pointage
    let statutPointage = {
        a_pointé_arrivee: false,
        a_pointé_depart: false,
        heure_arrivee: null,
        heure_depart: null
    };

    // Charger le statut du pointage
    function chargerStatutPointage() {
        $.ajax({
            url: '{{ route("agent.statut-pointage") }}',
            type: 'GET',
            success: function(response) {
                statutPointage = response;
                mettreAJourInterfacePointage();
            },
            error: function() {
                $('#pointage-status').html('<span class="text-warning">Erreur de chargement</span>');
            }
        });
    }

    // Mettre à jour l'interface en fonction du statut
    function mettreAJourInterfacePointage() {
        const statusElement = $('#pointage-status');
        const btnArrivee = $('#btn-arrivee');
        const btnDepart = $('#btn-depart');

        if (statutPointage.a_pointé_arrivee && !statutPointage.a_pointé_depart) {
            statusElement.html(`<i class="bi bi-check-circle me-1"></i>Arrivée: ${statutPointage.heure_arrivee}`);
            btnArrivee.prop('disabled', true).addClass('btn-secondary');
            btnDepart.prop('disabled', false).removeClass('btn-secondary');
        } else if (statutPointage.a_pointé_depart) {
            statusElement.html(`<i class="bi bi-clock-history me-1"></i>Terminé: ${statutPointage.heure_arrivee} - ${statutPointage.heure_depart}`);
            btnArrivee.prop('disabled', true).addClass('btn-secondary');
            btnDepart.prop('disabled', true).addClass('btn-secondary');
        } else {
            statusElement.html('<i class="bi bi-exclamation-circle me-1"></i>En attente d\'arrivée');
            btnArrivee.prop('disabled', false).removeClass('btn-secondary');
            btnDepart.prop('disabled', true).addClass('btn-secondary');
        }
    }

 // Pointer l'arrivée avec confirmation
$('#btn-arrivee').click(function() {
    if ($(this).prop('disabled')) return;

    const button = $(this);
    const originalHtml = button.html();

    Swal.fire({
        title: 'Pointer votre arrivée ?',
        text: 'Êtes-vous sûr de vouloir pointer votre arrivée ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Oui, pointer arrivée',
        cancelButtonText: 'Annuler',
        background: '#ffffff',
        color: '#333',
        reverseButtons: true,
        position: 'center'
    }).then((result) => {
        if (result.isConfirmed) {
            button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Pointage...');

            $.ajax({
                url: '{{ route("agent.pointer-arrivee") }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        showSweetAlert('success', response.message, function() {
                            // Actualiser la page après clic sur OK
                            location.reload();
                        });
                        // Mettre à jour l'interface immédiatement
                        chargerStatutPointage();
                    } else {
                        showSweetAlert('warning', response.message);
                        button.prop('disabled', false).html(originalHtml);
                    }
                },
                error: function() {
                    showSweetAlert('error', 'Erreur lors du pointage');
                    button.prop('disabled', false).html(originalHtml);
                }
            });
        }
    });
});

// Pointer le départ avec confirmation
$('#btn-depart').click(function() {
    if ($(this).prop('disabled')) return;

    const button = $(this);
    const originalHtml = button.html();

    Swal.fire({
        title: 'Pointer votre départ ?',
        text: 'Êtes-vous sûr de vouloir pointer votre départ ?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Oui, pointer départ',
        cancelButtonText: 'Annuler',
        background: '#ffffff',
        color: '#333',
        reverseButtons: true,
        position: 'center'
    }).then((result) => {
        if (result.isConfirmed) {
            button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Pointage...');

            $.ajax({
                url: '{{ route("agent.pointer-depart") }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        showSweetAlert('success', response.message, function() {
                            // Actualiser la page après clic sur OK
                            location.reload();
                        });
                        // Mettre à jour l'interface immédiatement
                        chargerStatutPointage();
                    } else {
                        showSweetAlert('warning', response.message);
                        button.prop('disabled', false).html(originalHtml);
                    }
                },
                error: function() {
                    showSweetAlert('error', 'Erreur lors du pointage');
                    button.prop('disabled', false).html(originalHtml);
                }
            });
        }
    });
});

 // Fonction pour afficher les alertes SweetAlert2 avec actualisation
function showSweetAlert(icon, message, callback = null) {
    const title = icon === 'success' ? 'Succès' : 
                 icon === 'warning' ? 'Attention' : 
                 'Erreur';

    const background = icon === 'success' ? '#28a745' :
                      icon === 'warning' ? '#ffc107' :
                      '#dc3545';

    Swal.fire({
        icon: icon,
        title: title,
        text: message,
        background: '#ffffff',
        color: '#333',
        iconColor: background,
        confirmButtonColor: background,
        confirmButtonText: 'OK',
        timer: icon === 'success' ? null : 5000, // Pas de timer pour le succès
        timerProgressBar: icon !== 'success', // Pas de barre de progression pour le succès
        toast: false,
        position: 'center',
        showClass: {
            popup: 'animate__animated animate__fadeIn'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOut'
        }
    }).then((result) => {
        if (result.isConfirmed && callback) {
            callback();
        }
    });
}

    // Fonction pour confirmer le pointage (optionnel)
    function confirmPointage(action) {
        const title = action === 'arrivee' ? 'Pointer votre arrivée ?' : 'Pointer votre départ ?';
        const text = action === 'arrivee' 
            ? 'Êtes-vous sûr de vouloir pointer votre arrivée ?' 
            : 'Êtes-vous sûr de vouloir pointer votre départ ?';
        const confirmButtonText = action === 'arrivee' ? 'Oui, pointer arrivée' : 'Oui, pointer départ';

        return Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: action === 'arrivee' ? '#28a745' : '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Annuler',
            background: '#1a1a1a',
            color: '#fff',
            reverseButtons: true
        });
    }

    // Version alternative avec confirmation (décommentez pour l'utiliser)
    /*
    $('#btn-arrivee').click(function() {
        if ($(this).prop('disabled')) return;

        confirmPointage('arrivee').then((result) => {
            if (result.isConfirmed) {
                const button = $(this);
                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Pointage...');

                $.ajax({
                    url: '{{ route("agent.pointer-arrivee") }}',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            showSweetAlert('success', response.message);
                            chargerStatutPointage();
                        } else {
                            showSweetAlert('warning', response.message);
                            button.prop('disabled', false).html('<i class="bi bi-box-arrow-in-right me-1"></i>Arrivée');
                        }
                    },
                    error: function() {
                        showSweetAlert('error', 'Erreur lors du pointage');
                        button.prop('disabled', false).html('<i class="bi bi-box-arrow-in-right me-1"></i>Arrivée');
                    }
                });
            }
        });
    });

    $('#btn-depart').click(function() {
        if ($(this).prop('disabled')) return;

        confirmPointage('depart').then((result) => {
            if (result.isConfirmed) {
                const button = $(this);
                button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Pointage...');

                $.ajax({
                    url: '{{ route("agent.pointer-depart") }}',
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            showSweetAlert('success', response.message);
                            chargerStatutPointage();
                        } else {
                            showSweetAlert('warning', response.message);
                            button.prop('disabled', false).html('<i class="bi bi-box-arrow-right me-1"></i>Départ');
                        }
                    },
                    error: function() {
                        showSweetAlert('error', 'Erreur lors du pointage');
                        button.prop('disabled', false).html('<i class="bi bi-box-arrow-right me-1"></i>Départ');
                    }
                });
            }
        });
    });
    */

    // Actualiser les statistiques
    $('#refresh-stats').click(function() {
        $(this).find('i').addClass('spin');
        setTimeout(() => {
            loadStatistics();
            $(this).find('i').removeClass('spin');
        }, 500);
    });

    // Initialisation
    loadStatistics();
    chargerStatutPointage();
    
    // Auto-refresh toutes les 30 secondes
    setInterval(loadStatistics, 30000);
    
    // Actualiser le statut toutes les minutes
    setInterval(chargerStatutPointage, 60000);
});
</script>

<script>
$(document).ready(function() {
    let html5QrcodeScanner = null;
    let isScanning = false;

    // Gestion du toggle du scanner
    $('#toggleScanner').click(function() {
        $('#scannerSection').toggleClass('d-none');
        if (!$('#scannerSection').hasClass('d-none')) {
            $('#scanStatus').html(`
                <div class="alert alert-info">
                    <i class="bi bi-camera me-2"></i>
                    Prêt à scanner. Cliquez sur "Démarrer" pour activer la caméra.
                </div>
            `);
        }
    });

    $('#closeScanner').click(function() {
        stopScanner();
        $('#scannerSection').addClass('d-none');
        $('#scanStatus').html(`
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Cliquez sur "Ouvrir le Scanner" pour scanner un code QR
            </div>
        `);
    });

    // Fonctions du scanner (similaires à celles de la vue scanner)
    $('#startScanner').click(startScanner);
    $('#stopScanner').click(stopScanner);

    function startScanner() {
        // Votre code existant pour démarrer le scanner
        // ... (reprennez le code de votre vue scanner)
    }

    function stopScanner() {
        // Votre code existant pour arrêter le scanner
        // ... (reprennez le code de votre vue scanner)
    }

    function processScan(qrData) {
        // Votre code existant pour traiter le scan
        // ... (reprennez le code de votre vue scanner)
    }

    // Décompte en temps réel pour les codes en attente
    function updateCountdownTimers() {
        $('.countdown-timer').each(function() {
            const endTime = new Date($(this).data('end-time'));
            const now = new Date();
            const diff = endTime - now;

            if (diff > 0) {
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                
                $(this).text(
                    `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`
                );
            } else {
                $(this).text('00:00:00').addClass('text-success');
                // Recharger la page quand un timer expire
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        });

        // Mettre à jour le timer principal
        const mainTimer = $('#countdown-timer');
        if (mainTimer.length) {
            const nextExpiry = $('.countdown-timer').first().data('end-time');
            if (nextExpiry) {
                const endTime = new Date(nextExpiry);
                const now = new Date();
                const diff = endTime - now;

                if (diff > 0) {
                    const hours = Math.floor(diff / (1000 * 60 * 60));
                    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    mainTimer.text(`Prochain dans: ${hours}h ${minutes}m`);
                } else {
                    mainTimer.text('Actualisation...');
                }
            }
        }
    }

    // Démarrer les timers
    setInterval(updateCountdownTimers, 1000);
    updateCountdownTimers();

    // Actualiser les statistiques toutes les 30 secondes
    setInterval(() => {
        $.ajax({
            url: '{{ route("agent.dashboard.stats") }}', // Vous devrez créer cette route
            type: 'GET',
            success: function(response) {
                // Mettre à jour les compteurs
                $('#total-entrees').text(response.total_entrees);
                $('#visiteurs-presence').text(response.visiteurs_presence);
                $('#total-sorties').text(response.total_sorties);
                $('#codes-attente .stat-number').text(response.codes_en_attente);
            }
        });
    }, 30000);
});
</script>

<style>
.scanner-container {
    border: 2px dashed #193561;
    border-radius: 10px;
    padding: 20px;
    background: #f8f9fa;
}

.countdown-timer {
    font-family: 'Courier New', monospace;
    font-weight: bold;
    font-size: 0.9rem;
}

.stat-timer {
    border-top: 1px solid rgba(255,255,255,0.3);
    padding-top: 8px;
}

/* Animation pour les timers */
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

.countdown-timer:not(.text-success) {
    animation: pulse 2s infinite;
}
</style>
@endsection