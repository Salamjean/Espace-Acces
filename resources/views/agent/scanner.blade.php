@extends('agent.layouts.template')
@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

<div class="container-fluid" style="margin-top: 20px; background-color: white">
    <div class="row justify-content-center" >
        <div class="col-12 col-lg-8 col-xl-6" >
            <div class="card shadow-sm border-0 modern-card" style="background-color: #e9eaef">
                <div class="card-header py-3 modern-header">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-white rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="bi bi-qr-code-scan text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h3 class="card-title mb-0 text-white">Scanner un Code QR</h3>
                            <p class="mb-0 text-white opacity-75">Scannez un code pour enregistrer votre présence</p>
                        </div>
                        <div class="status-indicator">
                            <div class="indicator-dot ready"></div>
                            <span class="indicator-text">Prêt</span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <!-- Scanner compact -->
                    <div class="scan-section text-center mb-4">
                        <div class="scanner-container">
                            <div id="qr-reader" class="compact-scanner"></div>
                            <div class="scanner-overlay">
                                <div class="scanner-frame">
                                    <div class="corner top-left"></div>
                                    <div class="corner top-right"></div>
                                    <div class="corner bottom-left"></div>
                                    <div class="corner bottom-right"></div>
                                </div>
                            </div>
                            <div id="qr-reader-results" class="mt-3"></div>
                        </div>
                        
                        <div class="scanner-controls mt-3">
                            <button type="button" id="startScanner" class="btn btn-primary btn-action">
                                <i class="bi bi-camera me-2"></i>Démarrer le scanner
                            </button>
                            <button type="button" id="stopScanner" class="btn btn-secondary btn-action d-none">
                                <i class="bi bi-stop-circle me-2"></i>Arrêter
                            </button>
                        </div>
                        
                        <div class="scan-info mt-3">
                            <div class="info-card">
                                <i class="bi bi-info-circle me-2"></i>
                                <span>Scannez un code QR valide pour enregistrer votre présence</span>
                            </div>
                            <div class="scan-rules mt-2">
                                <small class="text-muted">
                                    <i class="bi bi-clock-history me-1"></i>
                                    Limite : 1 scan toutes les 2 heures par code
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Dernier scan -->
                    <div id="lastScan" class="last-scan-card" style="display: none;">
                        <h6 class="section-title mb-3">
                            <i class="bi bi-clock-history me-2"></i>Dernier Scan
                        </h6>
                        <div class="last-scan-info">
                            <div class="scan-details">
                                <strong id="lastScanPorte"></strong>
                                <span id="lastScanType" class="badge ms-2"></span>
                            </div>
                            <div class="scan-time text-muted" id="lastScanTime"></div>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="quick-actions mt-4">
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="{{route('agent.scanner.view')}}">
                                    <button id="refreshStatus" class="btn btn-outline-secondary w-100 btn-sm">
                                        <i class="bi bi-arrow-clockwise me-1"></i>Actualiser
                                    </button>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('agent.scan.historique') }}" class="btn btn-primary w-100 btn-sm" style="background: #193561; border: none;">
                                    <i class="bi bi-list-ul me-1"></i>Historique
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de résultat -->
<div class="modal fade" id="resultModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultModalTitle">Résultat du Scan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="resultContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" style="background: #193561; border: none;">Fermer</button>
            </div>
        </div>
    </div>
</div>

<style>
.modern-card {
    border-radius: 16px;
    overflow: hidden;
    border: none;
}

.modern-header {
    background: linear-gradient(135deg, #193561 0%, #2c4b8a 100%);
    border: none;
    padding: 1.5rem;
}

.status-indicator {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.2);
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    backdrop-filter: blur(10px);
}

.indicator-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 6px;
}

.indicator-dot.ready {
    background: #10b981;
    box-shadow: 0 0 8px #10b981;
}

.indicator-dot.waiting {
    background: #f59e0b;
    box-shadow: 0 0 8px #f59e0b;
}

.indicator-text {
    color: white;
    font-size: 0.8rem;
    font-weight: 500;
}

.scanner-container {
    position: relative;
    border-radius: 12px;
    background: #f8f9fa;
    overflow: hidden;
    min-height: 300px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    border: 1px solid #e9ecef;
}

.compact-scanner {
    width: 100%;
    height: 100%;
    position: relative;
    z-index: 1;
}

.scanner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
    z-index: 2;
}

.scanner-frame {
    width: 220px;
    height: 220px;
    position: relative;
    border: 2px solid rgba(25, 53, 97, 0.3);
    border-radius: 12px;
}

.corner {
    position: absolute;
    width: 20px;
    height: 20px;
    border: 2px solid #193561;
}

.corner.top-left {
    top: -2px;
    left: -2px;
    border-right: none;
    border-bottom: none;
    border-top-left-radius: 8px;
}

.corner.top-right {
    top: -2px;
    right: -2px;
    border-left: none;
    border-bottom: none;
    border-top-right-radius: 8px;
}

.corner.bottom-left {
    bottom: -2px;
    left: -2px;
    border-right: none;
    border-top: none;
    border-bottom-left-radius: 8px;
}

.corner.bottom-right {
    bottom: -2px;
    right: -2px;
    border-left: none;
    border-top: none;
    border-bottom-right-radius: 8px;
}

.scanner-controls {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.btn-action {
    border-radius: 8px;
    padding: 8px 16px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.info-card {
    display: inline-flex;
    align-items: center;
    background: #e8f4fd;
    color: #0a58ca;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 0.9rem;
}

.last-scan-card {
    padding: 1rem;
    background: #f8fafc;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.last-scan-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.scan-details {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-title {
    color: #193561;
    font-weight: 600;
}

.badge-entree {
    background: #10b981;
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
}

.badge-sortie {
    background: #ef4444;
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
}

.icon-circle {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 5px 15px rgba(25, 53, 97, 0.2);
}

.quick-actions .btn {
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 0.85rem;
}

/* Animation pour le scan en cours */
@keyframes scanning {
    0% {
        transform: translateY(-100%);
    }
    100% {
        transform: translateY(100%);
    }
}

.scanning-line {
    position: absolute;
    top: 0;
    left: 10%;
    width: 80%;
    height: 2px;
    background: linear-gradient(90deg, transparent, #193561, transparent);
    animation: scanning 2s linear infinite;
    z-index: 3;
}

/* Styles pour le scanner HTML5 */
#html5-qrcode-anchor-scan-type-change {
    display: none !important;
}

#html5-qrcode-button-camera-stop, 
#html5-qrcode-button-camera-start {
    margin: 5px !important;
    padding: 8px 16px !important;
    border-radius: 8px !important;
}

/* Responsive */
@media (max-width: 768px) {
    .scanner-container {
        min-height: 250px;
    }
    
    .scanner-frame {
        width: 180px;
        height: 180px;
    }
    
    .last-scan-info {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
    
    .modern-header {
        padding: 1.25rem;
    }
    
    .status-indicator {
        padding: 0.3rem 0.6rem;
    }
}
</style>

<script>
let html5QrcodeScanner = null;
let isScanning = false;
let scanningLine = null;

document.addEventListener('DOMContentLoaded', function() {
    // Charger le statut initial
    loadScanStatus();

    // Gestionnaire pour le bouton d'actualisation
    document.getElementById('refreshStatus').addEventListener('click', loadScanStatus);

    // Initialiser les boutons du scanner
    document.getElementById('startScanner').addEventListener('click', startScanner);
    document.getElementById('stopScanner').addEventListener('click', stopScanner);

    // Vérifier si la bibliothèque est chargée
    if (typeof Html5Qrcode === 'undefined') {
        console.error("Html5Qrcode n'est pas chargé!");
        document.getElementById('qr-reader-results').innerHTML = `
            <div class="alert alert-warning p-2">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Fonction scanner non disponible. Rechargez la page.
            </div>
        `;
        document.getElementById('startScanner').disabled = true;
    } else {
        console.log("Bibliothèque Html5Qrcode disponible");
    }
});

function startScanner() {
    const isSecure = window.location.protocol === 'https:' || 
                    window.location.hostname === 'localhost' || 
                    window.location.hostname === '127.0.0.1';
    
    if (!isSecure) {
        document.getElementById('qr-reader-results').innerHTML = `
            <div class="alert alert-warning p-2">
                <i class="bi bi-exclamation-triangle me-2"></i>
                La caméra nécessite une connexion HTTPS ou localhost.
            </div>
        `;
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

        // Vider le conteneur du scanner
        document.getElementById('qr-reader').innerHTML = '';
        
        const html5Qrcode = new Html5Qrcode("qr-reader");
        
        const config = {
            fps: 10,
            qrbox: { width: 200, height: 200 },
            aspectRatio: 1.0,
            supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_QR_CODE]
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
            document.getElementById('startScanner').classList.add('d-none');
            document.getElementById('stopScanner').classList.remove('d-none');
            
            // Ajouter l'animation de ligne de scan
            addScanningLine();
            
            document.getElementById('qr-reader-results').innerHTML = `
                <div class="alert alert-success p-2">
                    <i class="bi bi-camera-video me-2"></i>
                    Scanner activé! Approchez le QR code de la caméra.
                </div>
            `;
            
            // Mettre à jour l'indicateur de statut
            updateStatusIndicator('scanning');
        }).catch((err) => {
            console.error("Erreur démarrage scanner:", err);
            handleCameraError(err);
        });

    } catch (error) {
        console.error("Erreur création scanner:", error);
        handleCameraError(error);
    }
}

function addScanningLine() {
    if (scanningLine) {
        scanningLine.remove();
    }
    
    scanningLine = document.createElement('div');
    scanningLine.className = 'scanning-line';
    document.querySelector('.scanner-container').appendChild(scanningLine);
}

function removeScanningLine() {
    if (scanningLine) {
        scanningLine.remove();
        scanningLine = null;
    }
}

function updateStatusIndicator(status) {
    const indicatorDot = document.querySelector('.indicator-dot');
    const indicatorText = document.querySelector('.indicator-text');
    
    if (status === 'ready') {
        indicatorDot.className = 'indicator-dot ready';
        indicatorText.textContent = 'Prêt';
    } else if (status === 'scanning') {
        indicatorDot.className = 'indicator-dot waiting';
        indicatorText.textContent = 'Scan en cours';
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
    
    document.getElementById('qr-reader-results').innerHTML = `
        <div class="alert alert-danger p-2">
            <i class="bi bi-camera-video-off me-2"></i>
            ${errorMessage}
        </div>
    `;
    
    document.getElementById('startScanner').classList.remove('d-none');
    document.getElementById('stopScanner').classList.add('d-none');
    isScanning = false;
    html5QrcodeScanner = null;
    removeScanningLine();
    updateStatusIndicator('ready');
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
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear();
    }
    html5QrcodeScanner = null;
    isScanning = false;
    document.getElementById('startScanner').classList.remove('d-none');
    document.getElementById('stopScanner').classList.add('d-none');
    removeScanningLine();
    updateStatusIndicator('ready');
    
    // Vider le conteneur du scanner
    document.getElementById('qr-reader').innerHTML = '';
    
    document.getElementById('qr-reader-results').innerHTML = `
        <div class="alert alert-secondary p-2">
            <i class="bi bi-pause-circle me-2"></i>
            Scanner arrêté.
        </div>
    `;
}

function onScanSuccess(decodedText, decodedResult) {
    console.log("QR Code scanné:", decodedText);
    
    // Arrêter temporairement le scanner pour traiter le résultat
    if (html5QrcodeScanner && isScanning) {
        html5QrcodeScanner.pause();
    }
    
    try {
        const qrData = decodedText.trim();
        
        if (qrData) {
            document.getElementById('qr-reader-results').innerHTML = `
                <div class="alert alert-info p-2">
                    <i class="bi bi-hourglass-split me-2"></i>
                    Code détecté! Validation en cours...
                </div>
            `;
            
            // Envoyer les données au serveur
            processScan(qrData);
        } else {
            throw new Error('QR Code vide');
        }
    } catch (error) {
        console.error("Erreur traitement QR:", error);
        document.getElementById('qr-reader-results').innerHTML = `
            <div class="alert alert-danger p-2">
                <i class="bi bi-exclamation-triangle me-2"></i>
                QR Code invalide: ${error.message}
            </div>
        `;
        // Redémarrer le scanner après l'erreur
        setTimeout(() => {
            if (html5QrcodeScanner && !isScanning) {
                html5QrcodeScanner.resume();
            }
        }, 2000);
    }
}

function onScanFailure(error) {
    // Erreurs normales de scan, on les ignore silencieusement
    // Ces erreurs se produisent fréquemment quand aucun code n'est détecté
}

function processScan(qrData) {
    console.log("Données QR reçues:", qrData);
    
    // Afficher le loading dans la zone de résultats
    document.getElementById('qr-reader-results').innerHTML = `
        <div class="alert alert-info p-2">
            <i class="bi bi-hourglass-split me-2"></i>
            <strong>Traitement en cours...</strong><br>
            <small>Validation du code QR</small>
        </div>
    `;

    fetch('{{ route("agent.scan") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            qr_data: qrData
        })
    })
    .then(response => {
        // Vérifier le statut HTTP d'abord
        if (response.status === 429) {
            // Erreur de limite atteinte - on veut récupérer le message du serveur
            return response.json().then(data => {
                throw new Error('LIMIT_REACHED:' + JSON.stringify(data));
            });
        }
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log("Réponse serveur:", data);
        
        if (data.success) {
            // Message de succès dans la zone de résultats
            document.getElementById('qr-reader-results').innerHTML = `
                <div class="alert alert-success p-2">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong>Scan réussi!</strong><br>
                    <small>${data.message}</small>
                </div>
            `;
            
            showResultModal('success', data.message, data.data);
            // Recharger le statut
            loadScanStatus();
        } else {
            // Message d'erreur dans la zone de résultats
            document.getElementById('qr-reader-results').innerHTML = `
                <div class="alert alert-danger p-2">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Scan échoué</strong><br>
                    <small>${data.message}</small>
                </div>
            `;
            
            showResultModal('error', data.message, data);
        }
        
        // Redémarrer le scanner après 3 secondes
        setTimeout(() => {
            if (html5QrcodeScanner) {
                // Recréer complètement le scanner pour éviter les problèmes
                stopScanner();
                setTimeout(() => {
                    startScanner();
                }, 500);
            }
        }, 3000);
    })
    .catch(error => {
        console.error('Erreur:', error);
        
        // Gérer spécifiquement l'erreur 429
        if (error.message.startsWith('LIMIT_REACHED:')) {
            try {
                const errorData = JSON.parse(error.message.replace('LIMIT_REACHED:', ''));
                handleLimitReachedError(errorData);
            } catch (e) {
                handleGenericError(error);
            }
        } else {
            handleGenericError(error);
        }
        
        // Redémarrer le scanner après l'erreur
        setTimeout(() => {
            if (html5QrcodeScanner) {
                stopScanner();
                setTimeout(() => {
                    startScanner();
                }, 500);
            }
        }, 3000);
    });
}

// Fonction pour gérer les limites atteintes
function handleLimitReachedError(errorData) {
    const message = errorData.message || 'Limite de scans atteinte';
    const tempsAttente = errorData.temps_attente || '2 heures';
    const prochainScan = errorData.prochain_scan || 'dans 2 heures';
    const codeScanne = errorData.code_scanne || 'ce code';
    
    // Message dans la zone de résultats
    document.getElementById('qr-reader-results').innerHTML = `
        <div class="alert alert-warning p-2">
            <i class="bi bi-clock-history me-2"></i>
            <strong>Limite atteinte</strong><br>
            <small>${message}</small>
        </div>
    `;
    
    // Popup détaillée
    Swal.fire({
        icon: 'warning',
        title: 'Trop de scans!',
        html: `
            <div class="text-center">
                <div class="mb-3">
                    <i class="bi bi-clock-history text-warning" style="font-size: 3rem;"></i>
                </div>
                <h5 class="text-warning">${message}</h5>
                <div class="alert alert-warning mt-3 text-start">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-clock me-2"></i>
                        <strong>Temps d'attente:</strong> ${tempsAttente}
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-calendar me-2"></i>
                        <strong>Prochain scan:</strong> ${prochainScan}
                    </div>
                </div>
                <p class="text-muted mt-3">
                    <small>
                        <i class="bi bi-info-circle me-1"></i>
                        Vous pouvez scanner d'autres codes immédiatement, mais ce code spécifique nécessite 2 heures d'attente.
                    </small>
                </p>
            </div>
        `,
        confirmButtonText: 'Compris',
        confirmButtonColor: '#f59e0b',
        background: '#ffffff'
    });
}

// Fonction pour les autres erreurs
function handleGenericError(error) {
    let errorMessage = 'Erreur de connexion';
    
    if (error.message.includes('429')) {
        errorMessage = 'Limite de scans atteinte pour ce code. Veuillez attendre 2 heures.';
    } else if (error.message.includes('404')) {
        errorMessage = 'Code non trouvé. Vérifiez le code QR.';
    } else if (error.message.includes('403')) {
        errorMessage = 'Accès non autorisé. Veuillez vous reconnecter.';
    } else if (error.message.includes('500')) {
        errorMessage = 'Erreur interne du serveur. Veuillez réessayer.';
    } else {
        errorMessage = error.message || 'Erreur inconnue';
    }
    
    // Message dans la zone de résultats
    document.getElementById('qr-reader-results').innerHTML = `
        <div class="alert alert-danger p-2">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong>Erreur</strong><br>
            <small>${errorMessage}</small>
        </div>
    `;
    
    // Popup d'erreur
    Swal.fire({
        icon: 'error',
        title: 'Erreur',
        text: errorMessage,
        confirmButtonText: 'Fermer',
        confirmButtonColor: '#dc3545',
        background: '#ffffff'
    });
}

function loadScanStatus() {
    fetch('{{ route("agent.scan.statut") }}', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateScanStatus(data.data);
        }
    })
    .catch(error => {
        console.error('Erreur lors du chargement du statut:', error);
    });
}

function updateScanStatus(statusData) {
    // Mettre à jour le dernier scan
    if (statusData.dernier_scan) {
        document.getElementById('lastScan').style.display = 'block';
        document.getElementById('lastScanPorte').textContent = statusData.dernier_scan.porte;
        document.getElementById('lastScanTime').textContent = `à ${statusData.dernier_scan.heure}`;
        
        const badge = document.getElementById('lastScanType');
        badge.className = statusData.dernier_scan.type === 'entree' ? 'badge badge-entree' : 'badge badge-sortie';
        badge.textContent = statusData.dernier_scan.type === 'entree' ? 'Entrée' : 'Sortie';
    } else {
        document.getElementById('lastScan').style.display = 'none';
    }
}

function showResultModal(type, message, data = null) {
    if (type === 'success') {
        Swal.fire({
            icon: 'success',
            title: 'Scan Réussi!',
            html: `
                <div class="text-center">
                    <p class="mb-3">${message}</p>
                    ${data ? `
                    <div class="scan-details mt-3 p-3 bg-light rounded text-start">
                        <p class="mb-2"><strong>📍 Porte:</strong> ${data.porte}</p>
                    </div>
                    ` : ''}
                </div>
            `,
            confirmButtonText: 'Fermer',
            confirmButtonColor: '#193561',
            background: '#ffffff',
            customClass: {
                popup: 'sweet-alert-popup'
            }
        });
    } else {
        let htmlContent = `
            <div class="text-center">
                <p class="mb-3">${message}</p>
        `;

        if (data && data.temps_attente) {
            htmlContent += `
                <div class="alert alert-warning mt-3">
                    <i class="bi bi-clock me-2"></i>
                    <strong>Temps d'attente:</strong> ${data.temps_attente}
                </div>
            `;
        }

        if (data && data.prochain_scan) {
            htmlContent += `
                <div class="alert alert-info mt-2">
                    <i class="bi bi-calendar me-2"></i>
                    <strong>Prochain scan possible:</strong> ${data.prochain_scan}
                </div>
            `;
        }

        if (data && data.code_scanne) {
            htmlContent += `
                <div class="alert alert-secondary mt-2">
                    <i class="bi bi-qr-code me-2"></i>
                    <strong>Code scanné:</strong> ${data.code_scanne}
                </div>
            `;
        }

        htmlContent += `</div>`;

        Swal.fire({
            icon: 'error',
            title: 'Scan Échoué',
            html: htmlContent,
            confirmButtonText: 'Compris',
            confirmButtonColor: '#dc3545',
            background: '#ffffff',
            customClass: {
                popup: 'sweet-alert-popup'
            }
        });
    }
}
</script>

@endsection