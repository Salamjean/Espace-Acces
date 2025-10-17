@extends('agent.layouts.template')
@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

<div class="container-fluid" style="background-color: white">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border: none; border-radius: 10px; background: #193561; margin-top:10px;">
                <div class="card-body py-3" style="display:flex; justify-content:space-between">
                    <h4 class="mb-0 text-white">
                        <i class="bi bi-qr-code-scan me-2"></i>Scanner un Visiteur
                    </h4>
                    <h4 class="mb-0 text-white">
                        <a href="{{route('visite.access')}}"><button  style="padding:12px 10px; border-radius:5px">Retour</button></a>
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Processus Step by Step -->
    <div class="row justify-content-center" >
        <div class="col-lg-10" >
            <div class="card shadow-sm" style="border: none; border-radius: 15px;background-color: #e9eaef" >
                <div class="card-header py-3" style="background: #193561; border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0 text-white text-center">
                        <i class="bi bi-person-check me-2"></i>Enregistrement du Visiteur
                    </h5>
                </div>
                
                <div class="card-body p-4">
                    <!-- Progress Bar -->
                    <div class="progress mb-4" style="height: 8px; border-radius: 10px;">
                        <div class="progress-bar" role="progressbar" style="background: #193561; width: 25%;" 
                             aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    
                    <!-- Steps Indicators -->
                    <div class="row text-center mb-4">
                        <div class="col-3">
                            <div class="step-indicator active" data-step="1">
                                <div class="step-circle">1</div>
                                <small class="step-label">Scan QR Code</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="step-indicator" data-step="2">
                                <div class="step-circle">2</div>
                                <small class="step-label">Informations</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="step-indicator" data-step="3">
                                <div class="step-circle">3</div>
                                <small class="step-label">Pièce Identité</small>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="step-indicator" data-step="4">
                                <div class="step-circle">4</div>
                                <small class="step-label">Validation</small>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('visite.store') }}" method="POST" enctype="multipart/form-data" id="visiteForm">
                        @csrf
                        <input type="hidden" name="personne_demande_id" id="personne_demande_id">
                        
                        <!-- Step 1: Scan QR Code -->
                        <div class="step-content active" id="step1">
                            <div class="text-center mb-4">
                                <h5 style="color: #193561;">
                                    <i class="bi bi-qr-code me-2"></i>Scanner le QR Code du Visiteur
                                </h5>
                                <p class="text-muted">Positionnez le QR code du visiteur devant votre caméra</p>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="scanner-container">
                                        <div id="qr-reader" style="width: 10%; min-height: 50px;"></div>
                                        <div id="qr-reader-results" class="mt-3"></div>
                                    </div>
                                    
                                    <div class="text-center mt-4">
                                        <button type="button" id="startScanner" class="btn btn-primary me-2" 
                                                style="background: #193561; border: none; border-radius: 8px; padding: 10px 20px;">
                                            <i class="bi bi-camera me-1"></i>Démarrer le Scanner
                                        </button>
                                        <button type="button" id="stopScanner" class="btn btn-secondary d-none" 
                                                style="border-radius: 8px; padding: 10px 20px;">
                                            <i class="bi bi-stop-circle me-1"></i>Arrêter le Scanner
                                        </button>
                                    </div>

                                    <!-- Alternative manuelle -->
                                    <div class="mt-4">
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0"><i class="bi bi-keyboard me-2"></i>Entrée manuelle</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" id="manualCodeInput" 
                                                               placeholder="Entrez le code d'accès (ex: ABC123XY)">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button type="button" id="validateManualCode" class="btn btn-outline-primary w-100">
                                                            Valider
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-next d-none" 
                                            style="background: #193561; color: white; border: none; border-radius: 8px; padding: 10px 30px;">
                                        Suivant <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Informations du Visiteur -->
                        <div class="step-content d-none" id="step2">
                            <h5 class="mb-4" style="color: #193561; border-bottom: 2px solid #193561; padding-bottom: 10px;">
                                <i class="bi bi-person-vcard me-2"></i>Informations du Visiteur
                            </h5>
                            
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Informations récupérées automatiquement depuis la base de données
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold" style="color: #193561;">
                                        <i class="bi bi-person me-1"></i>Nom
                                    </label>
                                    <input type="text" class="form-control" id="display_name" readonly 
                                           style="background: #f8f9fa; border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold" style="color: #193561;">
                                        <i class="bi bi-person me-1"></i>Prénom
                                    </label>
                                    <input type="text" class="form-control" id="display_prenom" readonly 
                                           style="background: #f8f9fa; border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold" style="color: #193561;">
                                        <i class="bi bi-envelope me-1"></i>Email
                                    </label>
                                    <input type="email" class="form-control" id="display_email" readonly 
                                           style="background: #f8f9fa; border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold" style="color: #193561;">
                                        <i class="bi bi-phone me-1"></i>Contact
                                    </label>
                                    <input type="text" class="form-control" id="display_contact" readonly 
                                           style="background: #f8f9fa; border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold" style="color: #193561;">
                                        <i class="bi bi-geo-alt me-1"></i>Adresse
                                    </label>
                                    <input type="text" class="form-control" id="display_adresse" readonly 
                                           style="background: #f8f9fa; border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold" style="color: #193561;">
                                        <i class="bi bi-briefcase me-1"></i>Fonction
                                    </label>
                                    <input type="text" class="form-control" id="display_fonction" readonly 
                                           style="background: #f8f9fa; border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold" style="color: #193561;">
                                        <i class="bi bi-building me-1"></i>Structure
                                    </label>
                                    <input type="text" class="form-control" id="display_structure" readonly 
                                           style="background: #f8f9fa; border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold" style="color: #193561;">
                                        <i class="bi bi-calendar me-1"></i>Date de visite
                                    </label>
                                    <input type="text" class="form-control" id="display_date_visite" readonly 
                                           style="background: #f8f9fa; border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold" style="color: #193561;">
                                        <i class="bi bi-chat-dots me-1"></i>Motif de la visite
                                    </label>
                                    <textarea class="form-control" id="display_motif_visite" readonly rows="3"
                                              style="background: #f8f9fa; border: 2px solid #193561; border-radius: 8px; padding: 12px;"></textarea>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-6">
                                    <button type="button" class="btn btn-prev" 
                                            style="border: 2px solid #193561; color: #193561; border-radius: 8px; padding: 10px 30px;">
                                        <i class="bi bi-arrow-left me-2"></i>Précédent
                                    </button>
                                </div>
                                <div class="col-6 text-end">
                                    <button type="button" class="btn btn-next" 
                                            style="background: #193561; color: white; border: none; border-radius: 8px; padding: 10px 30px;">
                                        Suivant <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Informations Pièce d'Identité -->
                        <div class="step-content d-none" id="step3">
                            <h5 class="mb-4" style="color: #193561; border-bottom: 2px solid #193561; padding-bottom: 10px;">
                                <i class="bi bi-card-image me-2"></i>Informations Pièce d'Identité et Carte
                            </h5>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold" style="color: #193561;">
                                        <i class="bi bi-card-checklist me-1"></i>Type de Pièce *
                                    </label>
                                    <select class="form-control" id="type_piece" name="type_piece" required
                                            style="border: 2px solid #193561; border-radius: 8px;">
                                        <option value="">Sélectionner le type</option>
                                        <option value="CNI">Carte Nationale d'Identité</option>
                                        <option value="PASSEPORT">Passeport</option>
                                        <option value="PERMIS">Permis de Conduire</option>
                                        <option value="CARTE_SEJOUR">Carte de Séjour</option>
                                    </select>
                                    @error('type_piece')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="numero_piece" class="form-label fw-bold" style="color: #193561;">
                                        <i class="bi bi-credit-card me-1"></i>N° Pièce d'Identité *
                                    </label>
                                    <input type="text" class="form-control" id="numero_piece" name="numero_piece" 
                                        required style="border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                                    @error('numero_piece')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold" style="color: #193561;">
                                        <i class="bi bi-credit-card me-1"></i>Numéro de Carte de visite *
                                    </label>
                                    <input type="text" class="form-control" id="numero_carte" name="numero_carte" 
                                        required style="border: 2px solid #193561; border-radius: 8px; padding: 12px;"
                                        placeholder="Ex: CARTE001">
                                    @error('numero_carte')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-6">
                                    <button type="button" class="btn btn-prev" 
                                            style="border: 2px solid #193561; color: #193561; border-radius: 8px; padding: 10px 30px;">
                                        <i class="bi bi-arrow-left me-2"></i>Précédent
                                    </button>
                                </div>
                                <div class="col-6 text-end">
                                    <button type="button" class="btn btn-next" 
                                            style="background: #193561; color: white; border: none; border-radius: 8px; padding: 10px 30px;">
                                        Suivant <i class="bi bi-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Validation -->
<div class="step-content d-none" id="step4">
    <h5 class="mb-4" style="color: #193561; border-bottom: 2px solid #193561; padding-bottom: 10px;">
        <i class="bi bi-check-circle me-2"></i>Validation Finale
    </h5>

    <div class="alert alert-success">
        <i class="bi bi-check-lg me-2"></i>
        Toutes les informations sont complètes. Vérifiez les détails ci-dessous avant validation.
    </div>

    <!-- SYSTÈME "ADD TO" pour les personnes permanentes -->
    <div class="card mb-4" style="border: 2px solid #193561; border-radius: 10px;">
        <div class="card-header" style="background: #193561; color: white;">
            <h6 class="mb-0"><i class="bi bi-person-plus me-2"></i>Technicien Associés</h6>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label fw-bold" style="color: #193561;">
                    <i class="bi bi-person-check me-1"></i>Ajouter des Techniciens
                </label>
                
                <!-- Sélecteur et bouton d'ajout -->
                <div class="row mb-3">
                    <div class="col-md-8">
                        <select class="form-control" id="select_permanente" 
                                style="border: 2px solid #193561; border-radius: 8px;">
                            <option value="">-- Sélectionnez un Technicien --</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="button" id="btn_add_permanente" class="btn btn-primary w-100"
                                style="background: #193561; border: none; border-radius: 8px;">
                            <i class="bi bi-plus-circle me-1"></i>Ajouter
                        </button>
                    </div>
                </div>

                <!-- Liste des personnes ajoutées -->
                <div class="mt-3">
                    <label class="form-label fw-bold" style="color: #193561;">
                        <i class="bi bi-list-check me-1"></i>Techniciens sélectionnées
                    </label>
                    <div id="liste_personnes_ajoutees" class="border rounded p-3" 
                         style="min-height: 60px; background: #f8f9fa; border-color: #193561 !important;">
                        <p class="text-muted mb-0" id="message_vide">Aucune personne ajoutée</p>
                    </div>
                </div>

                <!-- Champ hidden pour stocker les IDs -->
                <input type="hidden" name="personnes_permanentes_associees" id="personnes_permanentes_associees" value="[]">
                
                <small class="form-text text-muted">
                    <i class="bi bi-info-circle me-1"></i>Sélectionnez et ajoutez les personnes permanentes qui accompagnent cette visite
                </small>
                @error('personnes_permanentes_associees')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="card mb-4" style="border: 2px solid #193561; border-radius: 10px;">
        <div class="card-header" style="background: #193561; color: white;">
            <h6 class="mb-0"><i class="bi bi-person me-2"></i>Récapitulatif du Visiteur</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nom:</strong> <span id="recap_nom"></span></p>
                    <p><strong>Prénom:</strong> <span id="recap_prenom"></span></p>
                    <p><strong>Email:</strong> <span id="recap_email"></span></p>
                    <p><strong>Contact:</strong> <span id="recap_contact"></span></p>
                    <p><strong>Structure:</strong> <span id="recap_structure"></span></p>
                    <p><strong>Type visiteur:</strong> <span id="recap_type_visiteur"></span></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Adresse:</strong> <span id="recap_adresse"></span></p>
                    <p><strong>Fonction:</strong> <span id="recap_fonction"></span></p>
                    <p><strong>Date visite:</strong> <span id="recap_date_visite"></span></p>
                    <p><strong>Motif visite:</strong> <span id="recap_motif"></span></p>
                    <p><strong>N° Pièce:</strong> <span id="recap_numero_piece"></span></p>
                    <p><strong>Type Pièce:</strong> <span id="recap_type_piece"></span></p>
                    <p><strong>Numéro de Carte:</strong> <span id="recap_numero_carte"></span></p>
                    <p><strong>Personnes permanentes:</strong> <span id="recap_personnes_permanentes">Aucune</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-6">
            <button type="button" class="btn btn-prev" 
                    style="border: 2px solid #193561; color: #193561; border-radius: 8px; padding: 10px 30px;">
                <i class="bi bi-arrow-left me-2"></i>Précédent
            </button>
        </div>
        <div class="col-6 text-end">
            <button type="submit" class="btn btn-success" id="submitBtn"
                    style="background: #28a745; border: none; border-radius: 8px; padding: 10px 30px;">
                <i class="bi bi-check-lg me-2"></i>Valider l'Entrée
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
const totalSteps = 4;
let html5QrcodeScanner = null;
let isScanning = false;
let scannedData = null;

// Navigation entre les étapes
$('.btn-next').click(function() {
    if (validateStep(currentStep)) {
        if (currentStep < totalSteps) {
            $('#step' + currentStep).removeClass('active').addClass('d-none');
            currentStep++;
            $('#step' + currentStep).removeClass('d-none').addClass('active');
            updateProgressBar();
            updateStepIndicators();
            
            if (currentStep === 4) {
                updateRecap();
            }
        }
    }
});

$('.btn-prev').click(function() {
    if (currentStep > 1) {
        $('#step' + currentStep).removeClass('active').addClass('d-none');
        currentStep--;
        $('#step' + currentStep).removeClass('d-none').addClass('active');
        updateProgressBar();
        updateStepIndicators();
    }
});

// Modifier la validation du step 4
function validateStep(step) {
    let isValid = true;
    
    if (step === 1) {
        if (!scannedData) {
            alert('Veuillez scanner un QR code valide avant de continuer.');
            isValid = false;
        }
    } else if (step === 3) {
        const numeroCarte = $('#numero_carte').val();
        const numeroPiece = $('#numero_piece').val();
        const typePiece = $('#type_piece').val();
        
        if (scannedData && scannedData.type_visiteur === 'permanent') {
            if (!numeroCarte) {
                showError($('#numero_carte'), 'Le numéro de carte est obligatoire');
                isValid = false;
            }
        } else {
            if (!numeroCarte) {
                showError($('#numero_carte'), 'Le numéro de carte est obligatoire');
                isValid = false;
            }
            if (!numeroPiece) {
                showError($('#numero_piece'), 'Le numéro de pièce est obligatoire');
                isValid = false;
            }
            if (!typePiece) {
                showError($('#type_piece'), 'Le type de pièce est obligatoire');
                isValid = false;
            }
        }
    } else if (step === 4) {
        // Validation pour le step 4 (personnes permanentes)
        if (scannedData && scannedData.type_visiteur === 'permanent') {
            if (personnesPermanentesListe.length === 0) {
                alert('Veuillez ajouter au moins une personne permanente.');
                isValid = false;
            }
        }
    }
    
    return isValid;
}

// Initialisation des événements
$(document).ready(function() {
    // Événement pour le bouton d'ajout
    $('#btn_add_permanente').click(ajouterPersonnePermanente);
    
    // Événement Enter sur le select
    $('#select_permanente').keypress(function(e) {
        if (e.which === 13) {
            ajouterPersonnePermanente();
        }
    });
});

function showError(element, message) {
    element.css('border-color', '#dc3545');
    alert(message);
}

// Scanner QR Code
$('#startScanner').click(function() {
    startScanner();
});

$('#stopScanner').click(function() {
    stopScanner();
});

function startScanner() {
    const isSecure = window.location.protocol === 'https:' || 
                    window.location.hostname === 'localhost' || 
                    window.location.hostname === '127.0.0.1';
    
    if (!isSecure) {
        $('#qr-reader-results').html(`
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                La caméra nécessite une connexion HTTPS ou localhost. Utilisez l'entrée manuelle.
            </div>
        `);
        return;
    }

    if (isScanning) {
        return;
    }

    stopScanner();

    try {
        if (typeof Html5Qrcode === 'undefined') {
            throw new Error('Bibliothèque Html5Qrcode non chargée');
        }

        console.log("Tentative de démarrage du scanner...");

        const html5Qrcode = new Html5Qrcode("qr-reader");
        
        const config = {
            fps: 10,
            qrbox: 250,
            aspectRatio: 1.0
        };

        html5Qrcode.start(
            { facingMode: "environment" },
            config,
            onScanSuccess,
            onScanFailure
        ).then(() => {
            console.log("Scanner démarré avec succès!");
            isScanning = true;
            html5QrcodeScanner = html5Qrcode;
            $('#startScanner').addClass('d-none');
            $('#stopScanner').removeClass('d-none');
            $('#qr-reader-results').html(`
                <div class="alert alert-success">
                    <i class="bi bi-camera-video me-2"></i>
                    Scanner activé! Approchez le QR code de la caméra.
                </div>
            `);
        }).catch((err) => {
            console.error("Erreur démarrage scanner:", err);
            handleCameraError(err);
        });

    } catch (error) {
        console.error("Erreur création scanner:", error);
        handleCameraError(error);
    }
}

function handleCameraError(error) {
    let errorMessage = "Erreur d'accès à la caméra";
    
    if (error.name === 'NotAllowedError') {
        errorMessage = "Permission caméra refusée. Veuillez autoriser l'accès à la caméra dans les paramètres de votre navigateur.";
    } else if (error.name === 'NotFoundError') {
        errorMessage = "Aucune caméra trouvée sur cet appareil.";
    } else if (error.name === 'NotSupportedError') {
        errorMessage = "Fonctionnalité non supportée par votre navigateur.";
    } else if (error.name === 'NotReadableError') {
        errorMessage = "La caméra est déjà utilisée par une autre application.";
    } else if (error.message && error.message.includes('Permission denied')) {
        errorMessage = "Permission caméra refusée. Cliquez sur l'icône de cadenas dans la barre d'adresse pour autoriser la caméra.";
    } else {
        errorMessage = "Erreur: " + (error.message || error.toString());
    }
    
    $('#qr-reader-results').html(`
        <div class="alert alert-danger">
            <i class="bi bi-camera-video-off me-2"></i>
            ${errorMessage}
        </div>
    `);
    
    $('#startScanner').removeClass('d-none');
    $('#stopScanner').addClass('d-none');
    isScanning = false;
    html5QrcodeScanner = null;
}

function stopScanner() {
    if (html5QrcodeScanner && typeof html5QrcodeScanner.stop === 'function') {
        html5QrcodeScanner.stop().then(() => {
            console.log("Scanner arrêté avec succès");
            cleanupScanner();
        }).catch((err) => {
            console.error("Erreur arrêt scanner:", err);
            cleanupScanner();
        });
    } else {
        cleanupScanner();
    }
}

function cleanupScanner() {
    html5QrcodeScanner = null;
    isScanning = false;
    $('#startScanner').removeClass('d-none');
    $('#stopScanner').addClass('d-none');
    $('#qr-reader-results').html(`
        <div class="alert alert-secondary">
            <i class="bi bi-pause-circle me-2"></i>
            Scanner arrêté.
        </div>
    `);
}

function onScanSuccess(decodedText, decodedResult) {
    console.log("QR Code scanné:", decodedText);
    
    stopScanner();
    
    try {
        const codeAcces = decodedText.trim();
        
        if (codeAcces && codeAcces.length >= 6) {
            $('#qr-reader-results').html(`
                <div class="alert alert-info">
                    <i class="bi bi-hourglass-split me-2"></i>
                    Code détecté: ${codeAcces.substring(0, 10)}... Validation en cours...
                </div>
            `);
            validateCodeAccess(codeAcces);
        } else {
            throw new Error('Code d\'accès trop court');
        }
    } catch (error) {
        console.error("Erreur traitement QR:", error);
        $('#qr-reader-results').html(`
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                QR Code invalide: ${error.message}
            </div>
        `);
        setTimeout(() => {
            if (!isScanning) {
                startScanner();
            }
        }, 2000);
    }
}

function onScanFailure(error) {
    // Erreurs normales de scan
}

// Entrée manuelle
$('#validateManualCode').click(function() {
    const manualCode = $('#manualCodeInput').val().trim();
    if (manualCode) {
        validateCodeAccess(manualCode);
    } else {
        alert('Veuillez entrer un code d\'accès');
    }
});

$('#manualCodeInput').keypress(function(e) {
    if (e.which === 13) {
        $('#validateManualCode').click();
    }
});

// Fonction pour valider le code d'accès (QR code ou manuel)
function validateCodeAccess(codeAcces) {
    $('#qr-reader-results').html(`
        <div class="alert alert-info">
            <i class="bi bi-hourglass-split me-2"></i>
            Validation du code en cours...
        </div>
    `);

    $.ajax({
        url: '{{ route("visite.check-code") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            code_acces: codeAcces
        },
        success: function(response) {
            console.log("Réponse serveur:", response);
            
            if (response.valid) {
                scannedData = response.personne;
                displayVisitorInfo(response.personne);
                $('#personne_demande_id').val(response.personne.id);
                
                let nextStep = 2;
                if (response.personne.type_visiteur === 'permanent') {
                    nextStep = 4;
                    
                    // PRÉ-REMPLIR TOUS LES CHAMPS POUR LES PERMANENTS
                    $('#numero_carte').val(codeAcces);
                    
                    // PRÉ-REMPLIR LES CHAMPS DE PIÈCE D'IDENTITÉ
                    if (response.personne.numero_piece) {
                        $('#numero_piece').val(response.personne.numero_piece);
                    }
                    if (response.personne.type_piece) {
                        $('#type_piece').val(response.personne.type_piece);
                    }
                    
                    // Charger la liste de TOUTES les personnes permanentes
                    loadPersonnesPermanentes();
                    
                } else {
                    // Pour les visiteurs ponctuels, passer par l'étape 2 et 3 normalement
                    nextStep = 2;
                }
                
                // Navigation vers l'étape appropriée
                $('#step1').removeClass('active').addClass('d-none');
                currentStep = nextStep;
                $('#step' + nextStep).removeClass('d-none').addClass('active');
                updateProgressBar();
                updateStepIndicators();
                
                // Si on va directement à l'étape 4 (permanents), mettre à jour le récapitulatif
                if (nextStep === 4) {
                    updateRecap();
                }
                
                $('#qr-reader-results').html(`
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        Code validé avec succès! Redirection vers les informations...
                    </div>
                `);
                
                // Afficher le bouton "Suivant" pour l'étape 1
                if (nextStep === 2) {
                    $('.btn-next').removeClass('d-none');
                }
                
            } else {
                $('#qr-reader-results').html(`
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        ${response.message || 'Code d\'accès invalide!'}
                    </div>
                `);
                
                // Réactiver le scanner après un délai
                setTimeout(() => {
                    if (!isScanning) {
                        startScanner();
                    }
                }, 3000);
            }
        },
        error: function(xhr, status, error) {
            console.error("Erreur AJAX:", xhr.responseText);
            let errorMessage = 'Erreur lors de la validation';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.status === 0) {
                errorMessage = 'Problème de connexion au serveur';
            } else if (xhr.status === 500) {
                errorMessage = 'Erreur interne du serveur';
            } else if (xhr.status === 422) {
                errorMessage = 'Données invalides envoyées au serveur';
            } else if (xhr.status === 404) {
                errorMessage = 'Service non trouvé';
            }
            
            $('#qr-reader-results').html(`
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle me-2"></i>
                    ${errorMessage}
                </div>
            `);
            
            // Réactiver le scanner après un délai
            setTimeout(() => {
                if (!isScanning) {
                    startScanner();
                }
            }, 3000);
        }
    });
}
// Variables globales pour gérer les personnes permanentes
let personnesPermanentesListe = [];
let toutesPersonnesPermanentes = [];

// Fonction pour charger TOUTES les personnes permanentes
function loadPersonnesPermanentes() {
    console.log('Chargement de TOUTES les personnes permanentes...');
    
    $('#select_permanente').html('<option value="">Chargement en cours...</option>');
    $('#btn_add_permanente').prop('disabled', true);

    $.ajax({
        url: '{{ route("visite.get-permanents") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
            // Pas de groupe_id - on veut toutes les permanentes
        },
        success: function(response) {
            console.log('Réponse serveur personnes permanentes:', response);
            
            const select = $('#select_permanente');
            select.empty();
            
            if (response.success && response.personnes && response.personnes.length > 0) {
                select.append('<option value="">-- Sélectionnez un technicien --</option>');
                
                // Trier les personnes par nom pour une meilleure lisibilité
                response.personnes.sort((a, b) => {
                    const nomA = (a.prenom + ' ' + a.name).toLowerCase();
                    const nomB = (b.prenom + ' ' + b.name).toLowerCase();
                    return nomA.localeCompare(nomB);
                });
                
                response.personnes.forEach(function(personne) {
                    let optionText = `${personne.prenom} ${personne.name} - ${personne.fonction}`;
                    if (personne.contact) {
                        optionText += ` (${personne.contact})`;
                    }
                    
                    select.append(new Option(optionText, personne.id));
                });
                
                $('#btn_add_permanente').prop('disabled', false);
                console.log('Personnes permanentes chargées avec succès:', response.personnes.length);
                
                // Debug supplémentaire
                if (response.debug) {
                    console.log('Debug serveur:', response.debug);
                }
            } else {
                let message = 'Aucun technicien disponible';
                if (response.message) {
                    message = response.message;
                }
                select.append(`<option value="">${message}</option>`);
                $('#btn_add_permanente').prop('disabled', true);
                console.log('Aucune personne permanente trouvée:', response.message || 'Raison inconnue');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erreur chargement personnes permanentes:', error);
            console.error('Détails erreur:', xhr.responseText);
            console.error('Status:', xhr.status);
            
            let errorMessage = 'Erreur de chargement';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            $('#select_permanente').html(`<option value="">${errorMessage}</option>`);
            $('#btn_add_permanente').prop('disabled', true);
            
            // Afficher une alerte pour informer l'utilisateur
            alert('Erreur lors du chargement des personnes permanentes: ' + errorMessage);
        }
    });
}
// Fonction pour ajouter une personne à la liste
function ajouterPersonnePermanente() {
    const select = document.getElementById('select_permanente');
    const personneId = select.value;
    const personneText = select.options[select.selectedIndex].text;
    
    if (!personneId) {
        alert('Veuillez sélectionner une personne permanente.');
        return;
    }
    
    // Vérifier si la personne est déjà ajoutée
    if (personnesPermanentesListe.some(p => p.id === personneId)) {
        alert('Cette personne est déjà dans la liste.');
        return;
    }
    
    // Ajouter à la liste
    const personne = {
        id: personneId,
        text: personneText
    };
    
    personnesPermanentesListe.push(personne);
    updateListePersonnes();
    updateChampHidden();
    
    // Réinitialiser le select
    select.value = '';
    
    // Mettre à jour le récapitulatif si on est à l'étape 4
    if (currentStep === 4) {
        updateRecap();
    }
}

// Fonction pour supprimer une personne de la liste
function supprimerPersonnePermanente(personneId) {
    personnesPermanentesListe = personnesPermanentesListe.filter(p => p.id !== personneId);
    updateListePersonnes();
    updateChampHidden();
    
    // Mettre à jour le récapitulatif si on est à l'étape 4
    if (currentStep === 4) {
        updateRecap();
    }
}
// Mettre à jour l'affichage de la liste
function updateListePersonnes() {
    const container = $('#liste_personnes_ajoutees');
    const messageVide = $('#message_vide');
    
    if (personnesPermanentesListe.length === 0) {
        messageVide.show();
        container.html('<p class="text-muted mb-0" id="message_vide">Aucune personne ajoutée</p>');
        return;
    }
    
    messageVide.hide();
    
    let html = '';
    personnesPermanentesListe.forEach(function(personne) {
        html += `
            <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded" 
                 style="background: white; border-color: #193561 !important;">
                <span>${personne.text}</span>
                <button type="button" class="btn btn-sm btn-danger" 
                        onclick="supprimerPersonnePermanente('${personne.id}')"
                        style="border-radius: 5px; padding: 2px 8px;">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
    });
    
    container.html(html);
}


// Mettre à jour le champ hidden avec les IDs
function updateChampHidden() {
    const ids = personnesPermanentesListe.map(p => p.id);
    $('#personnes_permanentes_associees').val(JSON.stringify(ids));
}
// Fonction pour afficher les informations du visiteur
function displayVisitorInfo(data) {
    $('#display_name').val(data.name || 'Non renseigné');
    $('#display_prenom').val(data.prenom || 'Non renseigné');
    $('#display_email').val(data.email || 'Non renseigné');
    $('#display_contact').val(data.contact || 'Non renseigné');
    $('#display_adresse').val(data.adresse || 'Non renseigné');
    $('#display_fonction').val(data.fonction || 'Non renseigné');
    $('#display_structure').val(data.structure || 'Non renseigné');
    $('#display_motif_visite').val(data.motif_visite || 'Non renseigné');
    
    if (data.date_visite) {
        const dateVisite = new Date(data.date_visite);
        $('#display_date_visite').val(dateVisite.toLocaleDateString('fr-FR'));
    } else {
        $('#display_date_visite').val('Non renseigné');
    }
}

$(document).ready(function() {
    // Mettre le focus sur le champ d'entrée manuelle
    $('#manualCodeInput').focus();

    // Sélectionner le texte quand le champ est focalisé
    $('#manualCodeInput').on('focus', function() {
        $(this).select();
    });

    // Remettre le focus sur le champ si l'utilisateur clique ailleurs
    $('#manualCodeInput').on('blur', function() {
        $(this).focus();
    });
});

// Fonction pour mettre à jour le récapitulatif
function updateRecap() {
    if (scannedData) {
        $('#recap_nom').text(scannedData.name || 'Non renseigné');
        $('#recap_prenom').text(scannedData.prenom || 'Non renseigné');
        $('#recap_email').text(scannedData.email || 'Non renseigné');
        $('#recap_contact').text(scannedData.contact || 'Non renseigné');
        $('#recap_adresse').text(scannedData.adresse || 'Non renseigné');
        $('#recap_fonction').text(scannedData.fonction || 'Non renseigné');
        $('#recap_structure').text(scannedData.structure || 'Non renseigné');
        $('#recap_motif').text(scannedData.motif_visite || 'Non renseigné');
        $('#recap_numero_carte').text($('#numero_carte').val());
        
        if (scannedData.date_visite) {
            const dateVisite = new Date(scannedData.date_visite);
            $('#recap_date_visite').text(dateVisite.toLocaleDateString('fr-FR'));
        } else {
            $('#recap_date_visite').text('Non renseigné');
        }
        
        // Afficher les informations de pièce selon le type de visiteur
        if (scannedData.type_visiteur === 'permanent') {
            $('#recap_numero_piece').text(scannedData.numero_piece || 'Non renseigné');
            $('#recap_type_piece').text(getTypePieceLabel(scannedData.type_piece) || 'Non renseigné');
            
            // Afficher les personnes permanentes dans le récapitulatif
            if (personnesPermanentesListe.length > 0) {
                const noms = personnesPermanentesListe.map(p => p.text.split(' - ')[0]).join(', ');
                $('#recap_personnes_permanentes').text(noms);
            } else {
                $('#recap_personnes_permanentes').text('Aucune');
            }
        } else {
            $('#recap_numero_piece').text($('#numero_piece').val());
            $('#recap_type_piece').text($('#type_piece option:selected').text());
            $('#recap_personnes_permanentes').text('Non applicable');
        }
        
        $('#recap_type_visiteur').text(
            scannedData.type_visiteur === 'permanent' ? 'Visiteur Permanent' : 'Visiteur Ponctuel'
        );
    }
}

// Fonction utilitaire pour les labels des types de pièce
function getTypePieceLabel(type) {
    const types = {
        'CNI': 'Carte Nationale d\'Identité',
        'PASSEPORT': 'Passeport',
        'PERMIS': 'Permis de Conduire',
        'CARTE_SEJOUR': 'Carte de Séjour'
    };
    return types[type] || type;
}

// Gestion de la soumission du formulaire
$('#visiteForm').on('submit', function(e) {
    console.log("Soumission du formulaire...");
    console.log("Type visiteur:", scannedData ? scannedData.type_visiteur : 'non défini');
    
    // Pour les visiteurs permanents, désactiver la validation HTML5
    if (scannedData && scannedData.type_visiteur === 'permanent') {
        $('#numero_piece').prop('required', false);
        $('#type_piece').prop('required', false);
        $('#photo_recto').prop('required', false);
        $('#photo_verso').prop('required', false);
        
        // Vider les fichiers pour éviter les erreurs
        $('#photo_recto').val('');
        $('#photo_verso').val('');
    }
    
    // Afficher un indicateur de chargement
    $('#submitBtn').prop('disabled', true).html('<i class="bi bi-hourglass-split me-2"></i>Enregistrement...');
    
    return true;
});

// Gestion des uploads d'images
function setupImageUpload(uploadAreaId, previewId, previewImgId, fileInputId) {
    const uploadArea = document.getElementById(uploadAreaId);
    const preview = document.getElementById(previewId);
    const previewImg = document.getElementById(previewImgId);
    const fileInput = document.getElementById(fileInputId);

    if (!uploadArea || !preview || !previewImg || !fileInput) return;

    uploadArea.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                alert('Le fichier est trop volumineux. Taille maximum: 2MB');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                uploadArea.style.display = 'none';
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    });

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => uploadArea.classList.add('highlight'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => uploadArea.classList.remove('highlight'), false);
    });

    uploadArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length > 0) {
            fileInput.files = files;
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
    }
}

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

function removeImage(type) {
    if (type === 'recto') {
        $('#photo_recto').val('');
        $('#rectoPreview').addClass('d-none');
        $('#rectoUploadArea').css('display', 'flex');
    } else {
        $('#photo_verso').val('');
        $('#versoPreview').addClass('d-none');
        $('#versoUploadArea').css('display', 'flex');
    }
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

// Initialisation
$(document).ready(function() {
    if (typeof Html5Qrcode === 'undefined') {
        console.error("Html5Qrcode n'est pas chargé!");
        $('#qr-reader-results').html(`
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Fonction scanner non disponible. Utilisez l'entrée manuelle.
            </div>
        `);
        $('#startScanner').prop('disabled', true).addClass('btn-secondary');
    } else {
        console.log("Bibliothèque Html5Qrcode disponible");
    }

    updateStepIndicators();
    
    setupImageUpload('rectoUploadArea', 'rectoPreview', 'rectoPreviewImg', 'photo_recto');
    setupImageUpload('versoUploadArea', 'versoPreview', 'versoPreviewImg', 'photo_verso');
    
    console.log("Scanner QR Code initialisé");
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
    background: #e9ecef;
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

.scanner-container {
    border: 2px dashed #193561;
    border-radius: 10px;
    padding: 20px;
    background: #f8f9fa;
    min-height: 400px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.upload-section {
    position: relative;
}

.upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 150px;
}

.upload-area:hover, .upload-area.highlight {
    border-color: #193561;
    background: rgba(25, 53, 97, 0.05);
}

.upload-icon {
    font-size: 2rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.file-input {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    opacity: 0;
    cursor: pointer;
}

.image-preview {
    position: relative;
    border: 2px solid #193561;
    border-radius: 8px;
    overflow: hidden;
    max-width: 100%;
}

.image-preview img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.btn-remove {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #dc3545;
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-remove:hover {
    background: #c82333;
    transform: scale(1.1);
}

.form-control:focus {
    border-color: #193561 !important;
    box-shadow: 0 0 0 0.2rem rgba(25, 53, 97, 0.25) !important;
}

.btn:hover {
    transform: translateY(-1px);
    transition: all 0.3s ease;
}

.card {
    box-shadow: 0 4px 6px rgba(25, 53, 97, 0.1);
    display:flex;
    justify-content: space-between;
}

/* Style pour le scanner */
#qr-reader {
    width: 100% !important;
}

#qr-reader__dashboard {
    margin-top: 10px;
}

#qr-reader__camera_selection {
    margin-bottom: 10px;
}

/* Responsive */
@media (max-width: 768px) {
    .scanner-container {
        min-height: 300px;
        padding: 15px;
    }
    
    .upload-area {
        padding: 1.5rem;
        min-height: 120px;
    }
    
    .step-label {
        font-size: 0.8rem;
    }
}
</style>
@endsection