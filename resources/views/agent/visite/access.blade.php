@extends('agent.layouts.template')
@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<div class="container-fluid" style="background-color: white">
    <!-- Header avec heure bien designée -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card" style="border: none; border-radius: 20px; background: linear-gradient(135deg, #193561 0%, #2c4b8a 100%);margin-top:10px">
                <div class="card-body py-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-2 text-white">
                                <i class="bi bi-door-open me-3"></i>Gestion des Accès Visiteurs
                            </h4>
                            <p class="mb-0 text-white opacity-75 fs-5">Sélectionnez le type d'opération à effectuer</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="time-display">
                                <div class="time-icon mb-2">
                                    <i class="bi bi-clock-fill text-white" style="font-size: 2rem;"></i>
                                </div>
                                <div id="current-time" class="time-text text-white" style="font-size: 3rem; font-weight: bold; line-height: 1;">
                                    {{ now()->format('H:i') }}
                                </div>
                                <div id="current-date" class="date-text text-white opacity-75" style="font-size: 1.2rem;">
                                    {{ now()->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cartes de Sélection SUR LA MÊME LIGNE -->
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row justify-content-center">
                <!-- Carte Entrée -->
                <div class="col-md-5 mb-4" >
                    <div class="card selection-card entry-card" data-action="entry" 
                         style="border: none; border-radius: 20px; cursor: pointer; transition: all 0.3s ease; height: 100%;background-color:#e9eaef">
                        <div class="card-body text-center p-5">
                            <div class="icon-container mb-4">
                                <div class="icon-circle" style="background: linear-gradient(135deg, #193561 0%, #193561 100%);">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                </div>
                            </div>
                            <h3 class="card-title mb-3" style="color: #193561;">Enregistrer une Entrée</h3>
                            <p class="card-text text-muted mb-4">
                                Scanner le QR code d'un visiteur pour enregistrer son entrée dans l'établissement
                            </p>
                            <div class="features-list text-center mb-4">
                                <div class="feature-item mb-2 d-flex justify-content-center">
                                    <i class="bi bi-check-circle-fill me-2" style="color: #193561"></i>&nbsp;
                                    <span>Scan du QR code</span>
                                </div>
                                <div class="feature-item mb-2 d-flex justify-content-center">
                                    <i class="bi bi-check-circle-fill me-2" style="color: #193561"></i>&nbsp;
                                    <span>Vérification des informations</span>
                                </div>
                                <div class="feature-item mb-2 d-flex justify-content-center">
                                    <i class="bi bi-check-circle-fill me-2" style="color: #193561"></i>&nbsp;
                                    <span>Capture de la pièce d'identité</span>
                                </div>
                            </div>
                            <div class="action-btn mt-4">
                                <button class="btn btn-lg" 
                                        style="background: #193561; color: white; border: none; border-radius: 12px; padding: 12px 40px;">
                                    Commencer <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Espace entre les cartes -->
                <div class="col-md-1"></div>

                <!-- Carte Sortie -->
                <div class="col-md-5 mb-4">
                    <div class="card selection-card exit-card" data-action="exit" 
                         style="border: none; border-radius: 20px; cursor: pointer; transition: all 0.3s ease; height: 100%;background-color:#e9eaef">
                        <div class="card-body text-center p-5">
                            <div class="icon-container mb-4">
                                <div class="icon-circle" style="background: linear-gradient(135deg, #193561 0%, #193561 100%);">
                                    <i class="bi bi-box-arrow-right"></i>
                                </div>
                            </div>
                            <h3 class="card-title mb-3" style="color: #193561;">Enregistrer une Sortie</h3>
                            <p class="card-text text-muted mb-4">
                                Scanner le QR code d'un visiteur pour enregistrer sa sortie de l'établissement
                            </p>
                            <div class="features-list text-center mb-4">
                                <div class="feature-item mb-2 d-flex justify-content-center">
                                    <i class="bi bi-check-circle-fill me-2" style="color: #193561"></i>&nbsp;
                                    <span>Scan du QR code de sortie</span>
                                </div>
                                <div class="feature-item mb-2 d-flex justify-content-center">
                                    <i class="bi bi-check-circle-fill me-2" style="color: #193561"></i>&nbsp;
                                    <span>Validation de la durée de visite</span>
                                </div>
                                <div class="feature-item mb-2 d-flex justify-content-center">
                                    <i class="bi bi-check-circle-fill me-2" style="color: #193561"></i>&nbsp;
                                    <span>Enregistrement de l'heure de sortie</span>
                                </div>
                            </div>
                            <div class="action-btn mt-4">
                                <button class="btn btn-lg" 
                                        style="background: #193561; color: white; border: none; border-radius: 12px; padding: 12px 40px;">
                                    Commencer <i class="bi bi-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
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
    
    setInterval(updateTime, 1000);
    updateTime();

    // Effets hover sur les cartes
    $('.selection-card').hover(
        function() {
            $(this).css({
                'transform': 'translateY(-10px)',
                'box-shadow': '0 20px 40px rgba(25, 53, 97, 0.3)'
            });
        },
        function() {
            $(this).css({
                'transform': 'translateY(0)',
                'box-shadow': '0 5px 15px rgba(0, 0, 0, 0.1)'
            });
        }
    );

    // Gestion du clic sur les cartes
    $('.selection-card').click(function() {
        const action = $(this).data('action');
        
        // Ajout d'un effet de clic
        $(this).css('transform', 'scale(0.95)');
        setTimeout(() => {
            $(this).css('transform', 'scale(1)');
        }, 150);

        // Redirection selon l'action
        if (action === 'entry') {
            window.location.href = '{{ route("visite.create") }}';
        } else if (action === 'exit') {
            window.location.href = '{{ route("visite.sortie") }}';
        }
    });
});
</script>

<style>
.selection-card {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border: 3px solid transparent;
}

.selection-card:hover {
    border-color: rgba(25, 53, 97, 0.1);
}

.icon-container {
    display: flex;
    justify-content: center;
}

.icon-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2.5rem;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.features-list {
    max-width: 300px;
    margin: 0 auto;
}

.feature-item {
    display: flex;
    align-items: center;
    padding: 8px 0;
}

/* Design de l'heure */
.time-display {
    text-align: center;
}

.time-text {
    font-family: 'Arial', sans-serif;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    letter-spacing: 2px;
}

.date-text {
    font-family: 'Arial', sans-serif;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
}

.time-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 0.7; }
    50% { opacity: 1; }
    100% { opacity: 0.7; }
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.selection-card {
    animation: fadeInUp 0.6s ease-out;
}

.entry-card {
    animation-delay: 0.1s;
}

.exit-card {
    animation-delay: 0.2s;
}

/* Responsive */
@media (max-width: 768px) {
    .card-body.p-5 {
        padding: 2rem !important;
    }
    
    .icon-circle {
        width: 80px;
        height: 80px;
        font-size: 2rem;
    }
    
    .btn-lg {
        padding: 10px 30px !important;
        font-size: 1rem;
    }
    
    .time-text {
        font-size: 2rem !important;
    }
    
    .date-text {
        font-size: 1rem !important;
    }
    
    .col-md-5 {
        margin-bottom: 2rem !important;
    }
    
    .col-md-1 {
        display: none;
    }
}

/* Effet de brillance sur les boutons */
.action-btn .btn {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.action-btn .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.action-btn .btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
}

.action-btn .btn:hover::before {
    left: 100%;
}

/* Hauteur égale pour les cartes */
.card {
    display: flex;
    flex-direction: column;
}

.card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.action-btn {
    margin-top: auto;
}
</style>
@endsection