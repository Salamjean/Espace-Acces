@extends('admin.layouts.template')
@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<div class="container-fluid" style="margin-top: 20px;">
    <div class="row justify-content-center" style="background-color: white">
        <div class="col-12 col-lg-8" >
            <!-- En-tête de la carte -->
            <div class="card shadow-sm border-0" style="border-radius: 20px; overflow: hidden;" >
                <div class="card-header py-4" style="background: linear-gradient(135deg, #193561 0%, #2c4b8a 100%); border: none;">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-white rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="bi bi-qr-code-scan text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h3 class="card-title mb-0 text-white">Générer un Code QR d'Accès</h3>
                            <p class="mb-0 text-white opacity-75">Créez des codes QR pour gérer les accès</p>
                        </div>
                    </div>
                </div>
                
                <!-- Corps de la carte -->
                <div class="card-body p-5" style="background-color: #e9eaef">
                    <form action="{{ route('admin.code.store') }}" method="POST" id="qrForm">
                        @csrf
                        
                        <!-- Section Informations de Base -->
                        <div class="section-card mb-4">
                            <div class="section-header mb-4">
                                <h5 class="section-title">
                                    <i class="bi bi-info-circle me-2"></i>Informations de Base
                                </h5>
                                <div class="section-divider"></div>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="nom_porte" class="form-label">
                                            <i class="bi bi-door-closed me-2"></i>Nom de la Porte *
                                        </label>
                                        <input type="text" class="form-control-modern @error('nom_porte') is-invalid @enderror" 
                                               id="nom_porte" name="nom_porte" value="{{ old('nom_porte') }}" 
                                               placeholder="Ex: Porte Principale, Entrée Sud..." required>
                                        @error('nom_porte')
                                            <div class="invalid-feedback-modern">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="type" class="form-label">
                                            <i class="bi bi-arrow-left-right me-2"></i>Type d'Accès *
                                        </label>
                                        <select class="form-control-modern @error('type') is-invalid @enderror" 
                                                id="type" name="type" required>
                                            <option value="">Sélectionnez le type</option>
                                            <option value="entree" {{ old('type') == 'entree' ? 'selected' : '' }}>
                                                🟢 Entrée
                                            </option>
                                            <option value="sortie" {{ old('type') == 'sortie' ? 'selected' : '' }}>
                                                🔴 Sortie
                                            </option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback-modern">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Paramètres Avancés -->
                        <div class="section-card mb-4">
                            <div class="section-header mb-4">
                                <h5 class="section-title">
                                    <i class="bi bi-gear me-2"></i>Paramètres Avancés
                                </h5>
                                <div class="section-divider"></div>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="duree_validite" class="form-label">
                                            <i class="bi bi-clock me-2"></i>Durée de Validité (heures)
                                        </label>
                                        <input type="number" class="form-control-modern @error('duree_validite') is-invalid @enderror" 
                                               id="duree_validite" name="duree_validite" 
                                               value="{{ old('duree_validite') }}" min="1" 
                                               placeholder="Ex: 24 pour 24 heures">
                                        @error('duree_validite')
                                            <div class="invalid-feedback-modern">{{ $message }}</div>
                                        @enderror
                                        <div class="form-hint">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Laisser vide pour une validité illimitée
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="info-card">
                                        <div class="info-icon">
                                            <i class="bi bi-shield-check"></i>
                                        </div>
                                        <div class="info-content">
                                            <h6>Sécurité du Code</h6>
                                            <p class="mb-0">Code unique généré automatiquement avec cryptage sécurisé</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'Action -->
                        <div class="action-buttons pt-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary-modern w-100" id="generateBtn">
                                        <i class="bi bi-qr-code me-2"></i>
                                        <span class="btn-text">Générer le Code QR</span>
                                        <span class="btn-loading d-none">
                                            <span class="spinner-border spinner-border-sm me-2"></span>
                                            Génération...
                                        </span>
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('admin.code.index') }}" class="btn btn-secondary-modern w-100">
                                        <i class="bi bi-list-ul me-2"></i>Voir tous les codes
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles Généraux */
body {
    background-color: #f8fafc;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Carte Principale */
.card {
    box-shadow: 0 10px 40px rgba(25, 53, 97, 0.1);
    border: none;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 60px rgba(25, 53, 97, 0.15);
}

/* Icône Cercle */
.icon-circle {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 5px 15px rgba(25, 53, 97, 0.2);
}

/* Sections */
.section-card {
    background: #ffffff;
    padding: 2rem;
    border-radius: 15px;
    border: 1px solid #eef2f7;
    box-shadow: 0 4px 12px rgba(25, 53, 97, 0.05);
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.section-title {
    color: #193561;
    font-weight: 600;
    font-size: 1.1rem;
    margin: 0;
}

.section-divider {
    flex: 1;
    height: 2px;
    background: linear-gradient(90deg, #193561 0%, transparent 100%);
    margin-left: 1rem;
    opacity: 0.3;
}

/* Formulaires Modernes */
.form-group-modern {
    margin-bottom: 1.5rem;
}

.form-label {
    color: #193561;
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control-modern {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #eef2f7;
    border-radius: 12px;
    background: #ffffff;
    color: #193561;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(25, 53, 97, 0.05);
}

.form-control-modern:focus {
    border-color: #193561;
    box-shadow: 0 4px 12px rgba(25, 53, 97, 0.15);
    outline: none;
}

.form-control-modern::placeholder {
    color: #a0a4a8;
}

/* Feedback de Validation */
.invalid-feedback-modern {
    color: #e74c3c;
    font-size: 0.85rem;
    margin-top: 0.25rem;
    display: block;
}

.is-invalid {
    border-color: #e74c3c !important;
}

/* Indications */
.form-hint {
    color: #6b7280;
    font-size: 0.8rem;
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
}

/* Carte d'Information */
.info-card {
    background: linear-gradient(135deg, #f8fafc 0%, #eef2f7 100%);
    padding: 1.5rem;
    border-radius: 12px;
    border-left: 4px solid #193561;
    display: flex;
    align-items: center;
    gap: 1rem;
    height: 100%;
}

.info-icon {
    width: 40px;
    height: 40px;
    background: #193561;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.info-content h6 {
    color: #193561;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.info-content p {
    color: #6b7280;
    font-size: 0.85rem;
    margin: 0;
}

/* Boutons Modernes */
.action-buttons {
    border-top: 1px solid #eef2f7;
    padding-top: 2rem;
}

.btn-primary-modern {
    background: linear-gradient(135deg, #193561 0%, #2c4b8a 100%);
    border: none;
    color: white;
    padding: 14px 24px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(25, 53, 97, 0.3);
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(25, 53, 97, 0.4);
    color: white;
}

.btn-secondary-modern {
    background: #ffffff;
    border: 2px solid #193561;
    color: #193561;
    padding: 14px 24px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.btn-secondary-modern:hover {
    background: #193561;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(25, 53, 97, 0.2);
}

/* Animation du Bouton */
.btn-loading .spinner-border {
    width: 1rem;
    height: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        margin-top: 10px;
    }
    
    .card-body {
        padding: 2rem 1.5rem !important;
    }
    
    .section-card {
        padding: 1.5rem;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .section-divider {
        width: 100%;
        margin: 0.5rem 0 0 0;
    }
    
    .info-card {
        flex-direction: column;
        text-align: center;
    }
}

/* Effets de Focus Améliorés */
.form-control-modern:focus {
    transform: translateY(-1px);
}

/* Animation de Transition */
.section-card, .form-control-modern, .btn {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* État Disabled */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('qrForm');
    const generateBtn = document.getElementById('generateBtn');
    const btnText = generateBtn.querySelector('.btn-text');
    const btnLoading = generateBtn.querySelector('.btn-loading');

    form.addEventListener('submit', function() {
        // Afficher l'état de chargement
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
        generateBtn.disabled = true;
    });

    // Animation d'entrée des éléments
    const elements = document.querySelectorAll('.section-card, .action-buttons');
    elements.forEach((element, index) => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            element.style.transition = 'all 0.6s ease';
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>

@endsection