@extends('admin.layouts.template')
@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<div class="container-fluid" style="background-color: white; margin-top:10px">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border: none; border-radius: 10px; background: #193561;">
                <div class="card-body py-3">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-person-plus me-2"></i>Nouvel Agent
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire Step by Step -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm" style="border: none; border-radius: 15px;">
                <div class="card-header py-3" style="background: #193561; border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0 text-white text-center">
                        <i class="bi bi-person-shield me-2"></i>Création d'un Agent - Formulaire en Étapes
                    </h5>
                </div>
                
                <div class="card-body p-4" style="background-color: #e9eaef">
                    <!-- Progress Bar -->
                    <div class="progress mb-4" style="height: 8px; border-radius: 10px;">
                        <div class="progress-bar" role="progressbar" style="background: #193561; width: 33%;" 
                             aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    
                    <!-- Steps Indicators -->
                    <div class="row text-center mb-4">
                        <div class="col-4">
                            <div class="step-indicator active" data-step="1">
                                <div class="step-circle">1</div>
                                <small class="step-label">Informations Personnelles</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="step-indicator" data-step="2">
                                <div class="step-circle">2</div>
                                <small class="step-label">Coordonnées</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="step-indicator" data-step="3">
                                <div class="step-circle">3</div>
                                <small class="step-label">Photo & Validation</small>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('agent.store') }}" method="POST" enctype="multipart/form-data" id="agentForm">
                        @csrf
                        
                        <!-- Step 1: Informations Personnelles -->
                        <div class="step-content" id="step1">
                            <h5 class="mb-4" style="color: #193561; border-bottom: 2px solid #193561; padding-bottom: 10px;">
                                <i class="bi bi-person-vcard me-2"></i>Informations Personnelles
                            </h5>
                            
                            <div class="row">
                                <!-- Nom -->
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-bold" style="color: #193561;">
                                        <i class="bi bi-person me-1"></i>Nom *
                                    </label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ old('name') }}" required 
                                           style="border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                                    @error('name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Prénom -->
                                <div class="col-md-6 mb-3">
                                    <label for="prenom" class="form-label fw-bold" style="color: #193561;">
                                        <i class="bi bi-person me-1"></i>Prénom *
                                    </label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" 
                                           value="{{ old('prenom') }}" required 
                                           style="border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                                    @error('prenom')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-next" 
                                            style="background: #193561; color: white; border: none; border-radius: 8px; padding: 10px 30px; font-weight: bold;">
                                        Suivant <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Coordonnées -->
                        <div class="step-content d-none" id="step2">
                            <h5 class="mb-4" style="color: #193561; border-bottom: 2px solid #193561; padding-bottom: 10px;">
                                <i class="bi bi-geo-alt me-2"></i>Coordonnées
                            </h5>
                            
                            <div class="row">
                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label fw-bold" style="color: #193561;">
                                        <i class="bi bi-envelope me-1"></i>Email *
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="{{ old('email') }}" required 
                                           style="border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                                    @error('email')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Contact -->
                                <div class="col-md-6 mb-3">
                                    <label for="contact" class="form-label fw-bold" style="color: #193561;">
                                        <i class="bi bi-phone me-1"></i>Contact *
                                    </label>
                                    <input type="text" class="form-control" id="contact" name="contact" 
                                           value="{{ old('contact') }}" required 
                                           style="border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                                    @error('contact')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <!-- Commune -->
                                <div class="col-md-6 mb-3">
                                    <label for="commune" class="form-label fw-bold" style="color: #193561;">
                                        <i class="bi bi-geo me-1"></i>Commune *
                                    </label>
                                    <select class="form-control" id="commune" name="commune" required 
                                            style="border: 2px solid #193561; border-radius: 8px">
                                        <option value="">Sélectionner une commune</option>
                                        <option value="Abobo" {{ old('commune') == 'Abobo' ? 'selected' : '' }}>Abobo</option>
                                        <option value="Adjamé" {{ old('commune') == 'Adjamé' ? 'selected' : '' }}>Adjamé</option>
                                        <option value="Attécoubé" {{ old('commune') == 'Attécoubé' ? 'selected' : '' }}>Attécoubé</option>
                                        <option value="Cocody" {{ old('commune') == 'Cocody' ? 'selected' : '' }}>Cocody</option>
                                        <option value="Koumassi" {{ old('commune') == 'Koumassi' ? 'selected' : '' }}>Koumassi</option>
                                        <option value="Marcory" {{ old('commune') == 'Marcory' ? 'selected' : '' }}>Marcory</option>
                                        <option value="Plateau" {{ old('commune') == 'Plateau' ? 'selected' : '' }}>Plateau</option>
                                        <option value="Port-Bouët" {{ old('commune') == 'Port-Bouët' ? 'selected' : '' }}>Port-Bouët</option>
                                        <option value="Treichville" {{ old('commune') == 'Treichville' ? 'selected' : '' }}>Treichville</option>
                                        <option value="Yopougon" {{ old('commune') == 'Yopougon' ? 'selected' : '' }}>Yopougon</option>
                                    </select>
                                    @error('commune')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Cas d'urgence -->
                                <div class="col-md-6 mb-3">
                                    <label for="cas_urgence" class="form-label fw-bold" style="color: #193561;">
                                        <i class="bi bi-telephone me-1"></i>Contact d'urgence
                                    </label>
                                    <input type="text" class="form-control" id="cas_urgence" name="cas_urgence" 
                                           value="{{ old('cas_urgence') }}" 
                                           style="border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                                    @error('cas_urgence')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-6">
                                    <button type="button" class="btn btn-prev" 
                                            style="border: 2px solid #193561; color: #193561; border-radius: 8px; padding: 10px 30px; font-weight: bold;">
                                        <i class="bi bi-arrow-left me-2"></i>Précédent
                                    </button>
                                </div>
                                <div class="col-6 text-end">
                                    <button type="button" class="btn btn-next" 
                                            style="background: #193561; color: white; border: none; border-radius: 8px; padding: 10px 30px; font-weight: bold;">
                                        Suivant <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Photo & Validation -->
                        <div class="step-content d-none" id="step3">
                            <h5 class="mb-4" style="color: #193561; border-bottom: 2px solid #193561; padding-bottom: 10px;">
                                <i class="bi bi-camera me-2"></i>Photo de Profil & Validation
                            </h5>
                            
                            <!-- Photo de profil -->
                            <div class="mb-4">
                                <label for="profile_picture" class="form-label fw-bold" style="color: #193561;">
                                    <i class="bi bi-camera me-1"></i>Photo de profil
                                </label>
                                <input type="file" class="form-control" id="profile_picture" name="profile_picture" 
                                       accept="image/jpeg,image/png,image/jpg,image/gif" 
                                       style="border: 2px solid #193561; border-radius: 8px;">
                                <div class="form-text" style="color: #193561;">
                                    Formats acceptés : JPEG, PNG, JPG, GIF (max 2MB)
                                </div>
                                @error('profile_picture')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Aperçu de l'image -->
                            <div class="mb-4 text-center">
                                <div id="imagePreview" class="d-none">
                                    <img id="preview" src="#" alt="Aperçu de l'image" 
                                         style="max-width: 200px; max-height: 200px; border: 2px solid #193561; border-radius: 10px; margin-bottom: 15px;">
                                    <br>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImage()">
                                        <i class="bi bi-trash me-1"></i>Supprimer
                                    </button>
                                </div>
                            </div>

                            <!-- Récapitulatif -->
                            <div class="card mb-4" style="border: 2px solid #193561; border-radius: 10px;">
                                <div class="card-header" style="background: #193561; color: white;">
                                    <h6 class="mb-0"><i class="bi bi-list-check me-2"></i>Récapitulatif</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Nom:</strong> <span id="recap_name"></span></p>
                                            <p><strong>Prénom:</strong> <span id="recap_prenom"></span></p>
                                            <p><strong>Email:</strong> <span id="recap_email"></span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Contact:</strong> <span id="recap_contact"></span></p>
                                            <p><strong>Commune:</strong> <span id="recap_commune"></span></p>
                                            <p><strong>Contact urgence:</strong> <span id="recap_cas_urgence"></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-6">
                                    <button type="button" class="btn btn-prev" 
                                            style="border: 2px solid #193561; color: #193561; border-radius: 8px; padding: 10px 30px; font-weight: bold;">
                                        <i class="bi bi-arrow-left me-2"></i>Précédent
                                    </button>
                                </div>
                                <div class="col-6 text-end">
                                    <button type="submit" class="btn btn-success" 
                                            style="background: #28a745; border: none; border-radius: 8px; padding: 10px 30px; font-weight: bold;">
                                        <i class="bi bi-check-lg me-2"></i>Créer l'Agent
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentStep = 1;
const totalSteps = 3;

// Navigation entre les étapes
$('.btn-next').click(function() {
    if (validateStep(currentStep)) {
        if (currentStep < totalSteps) {
            $('#step' + currentStep).addClass('d-none');
            currentStep++;
            $('#step' + currentStep).removeClass('d-none');
            updateProgressBar();
            updateStepIndicators();
            updateRecap();
        }
    }
});

$('.btn-prev').click(function() {
    if (currentStep > 1) {
        $('#step' + currentStep).addClass('d-none');
        currentStep--;
        $('#step' + currentStep).removeClass('d-none');
        updateProgressBar();
        updateStepIndicators();
    }
});

// Validation des étapes
function validateStep(step) {
    let isValid = true;
    
    if (step === 1) {
        const name = $('#name').val();
        const prenom = $('#prenom').val();
        
        if (!name) {
            showError($('#name'), 'Le nom est obligatoire');
            isValid = false;
        }
        if (!prenom) {
            showError($('#prenom'), 'Le prénom est obligatoire');
            isValid = false;
        }
    } else if (step === 2) {
        const email = $('#email').val();
        const contact = $('#contact').val();
        const commune = $('#commune').val();
        
        if (!email) {
            showError($('#email'), 'L\'email est obligatoire');
            isValid = false;
        }
        if (!contact) {
            showError($('#contact'), 'Le contact est obligatoire');
            isValid = false;
        }
        if (!commune) {
            showError($('#commune'), 'La commune est obligatoire');
            isValid = false;
        }
    }
    
    return isValid;
}

function showError(element, message) {
    element.css('border-color', '#dc3545');
    // Vous pouvez ajouter un toast ou une alerte ici
    alert(message);
}

function removeError(element) {
    element.css('border-color', '#193561');
}

// Mise à jour de la barre de progression
function updateProgressBar() {
    const progress = (currentStep / totalSteps) * 100;
    $('.progress-bar').css('width', progress + '%');
}

// Mise à jour des indicateurs d'étape
function updateStepIndicators() {
    $('.step-indicator').removeClass('active completed');
    
    $('.step-indicator').each(function() {
        const step = parseInt($(this).data('step'));
        if (step < currentStep) {
            $(this).addClass('completed');
        } else if (step === currentStep) {
            $(this).addClass('active');
        }
    });
}

// Mise à jour du récapitulatif
function updateRecap() {
    $('#recap_name').text($('#name').val());
    $('#recap_prenom').text($('#prenom').val());
    $('#recap_email').text($('#email').val());
    $('#recap_contact').text($('#contact').val());
    $('#recap_commune').text($('#commune').val());
    $('#recap_cas_urgence').text($('#cas_urgence').val() || 'Non renseigné');
}

// Aperçu de l'image
document.getElementById('profile_picture').addEventListener('change', function(e) {
    const preview = document.getElementById('preview');
    const imagePreview = document.getElementById('imagePreview');
    const file = e.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            imagePreview.classList.remove('d-none');
        }
        
        reader.readAsDataURL(file);
    } else {
        imagePreview.classList.add('d-none');
    }
});

function removeImage() {
    document.getElementById('profile_picture').value = '';
    document.getElementById('imagePreview').classList.add('d-none');
}

// Initialisation
$(document).ready(function() {
    updateStepIndicators();
    
    // Retirer les erreurs quand l'utilisateur commence à taper
    $('input, select').on('input change', function() {
        removeError($(this));
    });
});
</script>

<style>
.step-indicator {
    cursor: pointer;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: white;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 5px;
    font-weight: bold;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.step-indicator.active .step-circle {
    background: #193561;
    color: white;
    border-color: #193561;
}

.step-indicator.completed .step-circle {
    background: #28a745;
    color: white;
    border-color: #28a745;
}

.step-label {
    color: #6c757d;
    font-weight: 500;
}

.step-indicator.active .step-label {
    color: #193561;
    font-weight: bold;
}

.step-indicator.completed .step-label {
    color: #28a745;
}

.form-control:focus {
    border-color: #193561 !important;
    box-shadow: 0 0 0 0.2rem rgba(25, 53, 97, 0.25) !important;
}

.btn-next:hover, .btn-success:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.btn-prev:hover {
    background: #193561 !important;
    color: white !important;
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.card {
    box-shadow: 0 4px 6px rgba(25, 53, 97, 0.1);
}
</style>
@endsection