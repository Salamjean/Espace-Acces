@extends('home.layouts.template')
@section('content')
<div class="access-request-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <!-- En-tête -->
                <div class="text-center mb-5">
                    <h1 class="main-title">Demande d'Accès</h1>
                    <p class="subtitle">Gérez vos accès en toute simplicité</p>
                </div>

                <!-- Carte principale -->
                <div class="access-card">
                    <div class="card-header">
                        <i class="bi bi-shield-check"></i>
                        <h2 style="color: white">Comment obtenir l'accès ?</h2>
                    </div>
                    
                    <div class="card-body">
                        <!-- Section pour les nouveaux utilisateurs -->
                        <div class="info-section new-user-section">
                            <div class="section-icon">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="section-content">
                                <h3>Nouvelle Société ?</h3>
                                <p>Votre société n'est pas encore inscrite sur notre plateforme ? Contactez KKS-Technologies pour procéder à l'inscription de votre société.</p>
                                
                                <div class="contact-info">
                                    <div class="contact-item">
                                        <i class="bi bi-telephone"></i>
                                        <span>+225 07 11 11 79 79</span>
                                    </div>
                                    <div class="contact-item">
                                        <i class="bi bi-envelope"></i>
                                        <span>contact@kks-technologies.com</span>
                                    </div>
                                    <div class="contact-item">
                                        <i class="bi bi-clock"></i>
                                        <span>Lundi - Vendredi, 8H00 à 17H30</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Séparateur -->
                        <div class="separator">
                            <span>OU</span>
                        </div>

                        <!-- Section pour les utilisateurs existants -->
                        <div class="info-section existing-user-section">
                            <div class="section-icon">
                                <i class="bi bi-house-check"></i>
                            </div>
                            <div class="section-content">
                                <h3>Déjà inscrit ?</h3>
                                <p>Si votre société est déjà inscrite chez KKS-Technologies, connectez-vous à votre compte pour faire une demande d'accès.</p>
                                
                                <div class="action-buttons">
                                    <a href="{{ route('demandeur.login') }}" class="btn btn-primary login-btn">
                                        <i class="bi bi-box-arrow-in-right"></i>
                                        Se Connecter
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations supplémentaires -->
                <div class="additional-info">
                    <div class="info-box">
                        <i class="bi bi-info-circle"></i>
                        <p>Une fois votre société inscrite, vous pourrez faire des demandes d'accès directement depuis votre espace personnel.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.access-request-container {
    min-height: 80vh;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 60px 0;
}

.main-title {
    color: #193561;
    font-weight: 700;
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.subtitle {
    color: #6c757d;
    font-size: 1.2rem;
}

.access-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(25, 53, 97, 0.1);
    overflow: hidden;
    margin-bottom: 2rem;
    border: none;
}

.card-header {
    background: #193561;
    color: white;
    padding: 2rem;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.card-header i {
    font-size: 2rem;
}

.card-header h2 {
    margin: 0;
    font-weight: 600;
}

.card-body {
    padding: 3rem;
}

.info-section {
    display: flex;
    align-items: flex-start;
    gap: 2rem;
    margin-bottom: 2rem;
}

.section-icon {
    background: #193561;
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.section-icon i {
    font-size: 1.5rem;
}

.section-content {
    flex: 1;
}

.section-content h3 {
    color: #193561;
    font-weight: 600;
    margin-bottom: 1rem;
}

.section-content p {
    color: #6c757d;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.contact-info {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    border-left: 4px solid #193561;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.8rem;
    color: #495057;
}

.contact-item:last-child {
    margin-bottom: 0;
}

.contact-item i {
    color: #193561;
    width: 20px;
}

.separator {
    text-align: center;
    margin: 2.5rem 0;
    position: relative;
}

.separator:before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #dee2e6;
    z-index: 1;
}

.separator span {
    background: white;
    padding: 0 2rem;
    color: #193561;
    font-weight: 600;
    position: relative;
    z-index: 2;
}

.action-buttons {
    text-align: center;
}

.login-btn {
    background: #193561;
    border: none;
    padding: 12px 30px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.login-btn:hover {
    background: #152a4d;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(25, 53, 97, 0.3);
    color: white;
    text-decoration: none;
}

.additional-info {
    text-align: center;
}

.info-box {
    background: white;
    padding: 1.5rem;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(25, 53, 97, 0.1);
    display: inline-flex;
    align-items: center;
    gap: 1rem;
    max-width: 500px;
}

.info-box i {
    color: #193561;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.info-box p {
    margin: 0;
    color: #6c757d;
    text-align: left;
}

/* Responsive */
@media (max-width: 768px) {
    .access-request-container {
        padding: 30px 0;
    }
    
    .main-title {
        font-size: 2rem;
    }
    
    .card-body {
        padding: 2rem 1.5rem;
    }
    
    .info-section {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .section-icon {
        align-self: center;
    }
    
    .contact-info {
        text-align: left;
    }
    
    .info-box {
        flex-direction: column;
        text-align: center;
    }
    
    .info-box p {
        text-align: center;
    }
}

@media (max-width: 576px) {
    .card-header {
        flex-direction: column;
        gap: 0.5rem;
        padding: 1.5rem;
    }
    
    .login-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection