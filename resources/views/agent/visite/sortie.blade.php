@extends('agent.layouts.template')
@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

<div class="container-fluid"  style="background-color: white">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border: none; border-radius: 10px; background: #193561;  margin-top:10px;">
                <div class="card-body py-3" style="display:flex; justify-content:space-between">
                    <h4 class="mb-0 text-white" >
                        <i class="bi bi-box-arrow-right me-2"></i>Enregistrer une Sortie
                    </h4>
                    <h4 class="mb-0 text-white">
                        <a href="{{route('visite.access')}}"><button  style="padding:12px 10px; border-radius:5px">Retour</button></a>
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Processus de Sortie -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm" style="border: none; border-radius: 15px;background-color: #e9eaef">
                <div class="card-header py-3" style="background: #193561; border-radius: 15px 15px 0 0;">
                    <h5 class="mb-0 text-white text-center">
                        <i class="bi bi-person-check me-2"></i>Enregistrement de Sortie
                    </h5>
                </div>
                
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Étape 1: Scan QR Code -->
                    <div id="step1">
                        <div class="text-center mb-4">
                            <h5 style="color: #193561;">
                                <i class="bi bi-qr-code me-2"></i>Scanner le QR Code de Sortie
                            </h5>
                            <p class="text-muted">Positionnez le QR code du visiteur devant votre caméra pour enregistrer sa sortie</p>
                        </div>

                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="scanner-container">
                                    <div id="qr-reader" style="width: 100%; min-height: 300px;"></div>
                                    <div id="qr-reader-results" class="mt-3"></div>
                                </div>
                                
                                <div class="text-center mt-4">
                                    <button type="button" id="startScanner" class="btn btn-danger me-2" 
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
                                                    <button type="button" id="validateManualCode" class="btn btn w-100" style="border-color: #193561">
                                                        Valider
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Étape 2: Récapitulatif -->
                    <div id="step2" class="d-none">
                        <h5 class="mb-4" style="color: #193561; border-bottom: 2px solid #193561; padding-bottom: 10px;">
                            <i class="bi bi-person-vcard me-2"></i>Récapitulatif de la Visite
                        </h5>
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            Vérifiez les informations avant d'enregistrer la sortie
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold" style="color: #193561;">Nom</label>
                                <input type="text" class="form-control" id="display_name" readonly 
                                       style="background: #f8f9fa; border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold" style="color: #193561;">Prénom</label>
                                <input type="text" class="form-control" id="display_prenom" readonly 
                                       style="background: #f8f9fa; border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold" style="color: #193561;">Heure d'entrée</label>
                                <input type="text" class="form-control" id="display_heure_entree" readonly 
                                       style="background: #f8f9fa; border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold" style="color: #193561;">Durée de visite</label>
                                <input type="text" class="form-control" id="display_duree" readonly 
                                       style="background: #f8f9fa; border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold" style="color: #193561;">Fonction</label>
                                <input type="text" class="form-control" id="display_fonction" readonly 
                                       style="background: #f8f9fa; border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold" style="color: #193561;">Structure</label>
                                <input type="text" class="form-control" id="display_structure" readonly 
                                       style="background: #f8f9fa; border: 2px solid #193561; border-radius: 8px; padding: 12px;">
                            </div>
                        </div>

                        <form action="{{ route('visite.store-sortie') }}" method="POST" id="sortieForm">
                            @csrf
                            <input type="hidden" name="visite_id" id="visite_id">
                            <input type="hidden" name="personne_demande_id" id="personne_demande_id">

                            <!-- Ajoutez ce div pour le debug -->
                            <div id="form-debug" class="alert alert-info d-none">
                                <strong>Debug:</strong>
                                <div>Visite ID: <span id="debug-visite-id"></span></div>
                                <div>Personne ID: <span id="debug-personne-id"></span></div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-6">
                                    <button type="button" id="btn-precedent" class="btn btn-outline-secondary" 
                                            style="border-radius: 8px; padding: 10px 30px;">
                                        <i class="bi bi-arrow-left me-2"></i>Précédent
                                    </button>
                                </div>
                                <div class="col-6 text-end">
                                    <button type="submit" class="btn btn-danger" id="btn-valider-sortie"
                                            style="background: #193561; border: none; border-radius: 8px; padding: 10px 30px;">
                                        <i class="bi bi-check-lg me-2"></i>Valider la Sortie
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let html5QrcodeScanner = null;
let isScanning = false;
let scannedData = null;

// Scanner QR Code
$('#startScanner').click(function() {
    startScanner();
});

$('#stopScanner').click(function() {
    stopScanner();
});

// Fonction pour démarrer le scanner
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

    if (isScanning) return;
    stopScanner();

    try {
        if (typeof Html5Qrcode === 'undefined') {
            throw new Error('Bibliothèque Html5Qrcode non chargée');
        }

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
            handleCameraError(err);
        });

    } catch (error) {
        handleCameraError(error);
    }
}

function stopScanner() {
    if (html5QrcodeScanner && typeof html5QrcodeScanner.stop === 'function') {
        html5QrcodeScanner.stop().then(() => {
            cleanupScanner();
        }).catch((err) => {
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
            validateSortieCode(codeAcces);
        } else {
            throw new Error('Code d\'accès trop court');
        }
    } catch (error) {
        $('#qr-reader-results').html(`
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                QR Code invalide: ${error.message}
            </div>
        `);
        setTimeout(() => { if (!isScanning) startScanner(); }, 2000);
    }
}

function onScanFailure(error) {
    // Erreurs normales de scan
}

function handleCameraError(error) {
    let errorMessage = "Erreur d'accès à la caméra";
    if (error.name === 'NotAllowedError') {
        errorMessage = "Permission caméra refusée. Veuillez autoriser l'accès à la caméra.";
    } else if (error.name === 'NotFoundError') {
        errorMessage = "Aucune caméra trouvée sur cet appareil.";
    }
    
    $('#qr-reader-results').html(`
        <div class="alert alert-danger">
            <i class="bi bi-camera-video-off me-2"></i>
            ${errorMessage}
        </div>
    `);
    cleanupScanner();
}

// Entrée manuelle
$('#validateManualCode').click(function() {
    const manualCode = $('#manualCodeInput').val().trim();
    if (manualCode) {
        validateSortieCode(manualCode);
    } else {
        alert('Veuillez entrer un code d\'accès');
    }
});

$('#manualCodeInput').keypress(function(e) {
    if (e.which === 13) {
        $('#validateManualCode').click();
    }
});

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

// Valider le code de sortie
function validateSortieCode(codeAcces) {
    $('#qr-reader-results').html(`
        <div class="alert alert-info">
            <i class="bi bi-hourglass-split me-2"></i>
            Validation du code en cours...
        </div>
    `);

    $.ajax({
        url: '{{ route("visite.check-sortie-code") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            code_acces: codeAcces
        },
        success: function(response) {
            if (response.valid) {
                scannedData = response.personne;
                displayVisitorInfo(response.personne);
                $('#visite_id').val(response.personne.visite_id);
                $('#personne_demande_id').val(response.personne.id);
                
                // Passer à l'étape 2
                $('#step1').addClass('d-none');
                $('#step2').removeClass('d-none');
                
                $('#qr-reader-results').html(`
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        Code validé avec succès!
                    </div>
                `);
            } else {
                $('#qr-reader-results').html(`
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        ${response.message || 'Code d\'accès invalide!'}
                    </div>
                `);
                setTimeout(() => { startScanner(); }, 3000);
            }
        },
        error: function(xhr) {
            let errorMessage = 'Erreur lors de la validation';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            $('#qr-reader-results').html(`
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle me-2"></i>
                    ${errorMessage}
                </div>
            `);
            setTimeout(() => { startScanner(); }, 3000);
        }
    });
}

function displayVisitorInfo(data) {
    $('#display_name').val(data.name || 'Non renseigné');
    $('#display_prenom').val(data.prenom || 'Non renseigné');
    $('#display_heure_entree').val(data.heure_entree || 'Non renseigné');
    $('#display_duree').val(
        data.duree_visite !== undefined && data.duree_visite !== null
            ? parseFloat(data.duree_visite).toFixed(2) + ' min'
            : 'Non renseigné'
    );
    $('#display_fonction').val(data.fonction || 'Non renseigné');
    $('#display_structure').val(data.structure || 'Non renseigné');
}


// Bouton précédent
$('#btn-precedent').click(function() {
    $('#step2').addClass('d-none');
    $('#step1').removeClass('d-none');
    startScanner();
});

// Initialisation
$(document).ready(function() {
    if (typeof Html5Qrcode === 'undefined') {
        $('#qr-reader-results').html(`
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Fonction scanner non disponible. Utilisez l'entrée manuelle.
            </div>
        `);
        $('#startScanner').prop('disabled', true).addClass('btn-secondary');
    }
});
</script>

<style>
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

.form-control:focus {
    border-color: #193561 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.btn:hover {
    transform: translateY(-1px);
    transition: all 0.3s ease;
}

@media (max-width: 768px) {
    .scanner-container {
        min-height: 300px;
        padding: 15px;
    }
}
</style>
@endsection