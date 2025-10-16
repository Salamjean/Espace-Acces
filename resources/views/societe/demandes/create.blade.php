@extends('societe.layouts.template')
@section('content')
<div class="demande-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- En-tête -->
                <div class="text-center mb-5">
                    <h1 class="main-title">Nouvelle Demande d'Accès</h1>
                    <p class="subtitle">Remplissez le formulaire en quelques étapes simples</p>
                </div>

                <!-- Étapes -->
                <div class="steps-container mb-5">
                    <div class="steps">
                        <div class="step active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Informations Personnelles</div>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">Détails de la Visite</div>
                        </div>
                        <div class="step" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Informations Véhicule</div>
                        </div>
                        <div class="step" data-step="4">
                            <div class="step-number">4</div>
                            <div class="step-label">Documents & Validation</div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire -->
                <form id="demandeForm" action="{{ route('demandes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Étape 1: Informations Personnelles -->
                    <div class="form-step active" id="step1">
                        <div class="form-card">
                            <div class="card-header">
                                <i class="bi bi-person-circle"></i>
                                <h3 class="text-white">Informations Personnelles</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name_demandeur" class="form-label">Nom <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="name_demandeur" name="name_demandeur" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="prenom_demandeur" class="form-label">Prénom <span class="required">*</span></label>
                                            <input type="text" class="form-control" id="prenom_demandeur" name="prenom_demandeur" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contact_demandeur" class="form-label">Contact <span class="required">*</span></label>
                                            <input type="tel" class="form-control" id="contact_demandeur" name="contact_demandeur" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email_demandeur" class="form-label">Email <span class="required">*</span></label>
                                            <input type="email" class="form-control" id="email_demandeur" name="email_demandeur" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="fonction_demandeur" class="form-label">Fonction</label>
                                            <input type="text" class="form-control" id="fonction_demandeur" name="fonction_demandeur">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nbre_perso" class="form-label">Nombre de Personnes <span class="required">*</span></label>
                                            <input type="number" class="form-control" id="nbre_perso" name="nbre_perso" min="1" max="50" required placeholder="Entrez le nombre de personnes">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-next" data-next="2">Suivant</button>
                        </div>
                    </div>

                    <!-- Étape 2: Détails de la Visite -->
                    <div class="form-step" id="step2">
                        <div class="form-card">
                            <div class="card-header">
                                <i class="bi bi-calendar-event"></i>
                                <h3 class="text-white">Détails de la Visite</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_visite" class="form-label">Date de Début <span class="required">*</span></label>
                                            <input type="date" class="form-control" id="date_visite" name="date_visite" min="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_fin_visite" class="form-label">Date de Fin <span class="required">*</span></label>
                                            <input type="date" class="form-control" id="date_fin_visite" name="date_fin_visite" min="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="heure_visite" class="form-label">Heure de Début <span class="required">*</span></label>
                                            <input type="time" class="form-control" id="heure_visite" name="heure_visite" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="heure_fin_visite" class="form-label">Heure de Fin Estimée</label>
                                            <input type="time" class="form-control" id="heure_fin_visite" name="heure_fin_visite">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="motif_visite" class="form-label">Motif de la Visite <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="motif_visite" name="motif_visite" required placeholder="Entrez le motif de votre visite">
                                </div>

                                <div class="form-group">
                                    <label for="description_detaille" class="form-label">Description Détaillée</label>
                                    <textarea class="form-control" id="description_detaille" name="description_detaille" rows="4" placeholder="Décrivez en détail l'objet de votre visite..."></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-prev" data-prev="1">Précédent</button>
                            <button type="button" class="btn btn-next" data-next="3">Suivant</button>
                        </div>
                    </div>

                    <!-- Étape 3: Informations Véhicule -->
                    <div class="form-step" id="step3">
                        <div class="form-card">
                            <div class="card-header">
                                <i class="bi bi-car-front"></i>
                                <h3 class="text-white">Informations du Véhicule</h3>
                            </div>
                            <div class="card-body">
                                <div class="optional-notice">
                                    <i class="bi bi-info-circle"></i>
                                    <p>Cette section est optionnelle. Remplissez-la uniquement si votre visite concerne un véhicule.</p>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="marque_voiture" class="form-label">Marque du Véhicule</label>
                                            <input type="text" class="form-control" id="marque_voiture" name="marque_voiture" placeholder="Ex: Toyota, Renault...">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="modele_voiture" class="form-label">Modèle du Véhicule</label>
                                            <input type="text" class="form-control" id="modele_voiture" name="modele_voiture" placeholder="Ex: Corolla, Clio...">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="immatriculation_voiture" class="form-label">Immatriculation</label>
                                            <input type="text" class="form-control" id="immatriculation_voiture" name="immatriculation_voiture" placeholder="Ex: AB-123-CD">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type_intervention" class="form-label">Type d'Intervention</label>
                                            <select class="form-control" id="type_intervention" name="type_intervention">
                                                <option value="">Sélectionnez...</option>
                                                <option value="urgence">Urgence</option>
                                                <option value="unitaire">Unitaire</option>
                                                <option value="recurrente">Récurrente</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="skip-section">
                                    <button type="button" class="btn btn-skip" id="skipVehicleBtn">
                                        <i class="bi bi-arrow-right"></i>
                                        Passer cette étape
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-prev" data-prev="2">Précédent</button>
                            <button type="button" class="btn btn-next" data-next="4">Suivant</button>
                        </div>
                    </div>

                    <!-- Étape 4: Documents & Validation -->
                    <div class="form-step" id="step4">
                        <div class="form-card">
                            <div class="card-header">
                                <i class="bi bi-file-earmark-text"></i>
                                <h3 class="text-white">Documents & Validation</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="documents_joints" class="form-label">Documents à Joindre</label>
                                    <div class="file-upload-area">
                                        <input type="file" class="form-control" id="documents_joints" name="documents_joints[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        <div class="file-upload-info">
                                            <i class="bi bi-cloud-upload"></i>
                                            <p>Glissez-déposez vos fichiers ou cliquez pour parcourir</p>
                                            <small>Formats acceptés: PDF, DOC, DOCX, JPG, PNG (Max: 5MB par fichier)</small>
                                        </div>
                                    </div>
                                    <div id="file-list" class="file-list mt-3"></div>
                                </div>

                                <div class="confirmation-section">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="confirmation" required>
                                        <label class="form-check-label" for="confirmation">
                                            Je certifie que les informations fournies sont exactes et complètes
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-prev" data-prev="3">Précédent</button>
                            <button type="submit" class="btn btn-submit" id="submitBtn">
                                <i class="bi bi-send"></i>
                                Soumettre la Demande
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.demande-container {
    min-height: 80vh;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 40px 0;
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

/* Étapes */
.steps-container {
    position: relative;
}

.steps {
    display: flex;
    justify-content: space-between;
    position: relative;
    max-width: 1000px;
    margin: 0 auto;
}

.steps:before {
    content: '';
    position: absolute;
    top: 30px;
    left: 0;
    right: 0;
    height: 3px;
    background: #dee2e6;
    z-index: 1;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    flex: 1;
}

.step-number {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #dee2e6;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 10px;
    border: 4px solid white;
    transition: all 0.3s ease;
}

.step-label {
    color: #6c757d;
    font-weight: 500;
    text-align: center;
    font-size: 0.8rem;
}

.step.active .step-number {
    background: #193561;
    color: white;
    transform: scale(1.1);
}

.step.active .step-label {
    color: #193561;
    font-weight: 600;
}

/* Cartes de formulaire */
.form-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(25, 53, 97, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
    border: none;
}

.card-header {
    background: #193561;
    color: white;
    padding: 1.5rem 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.card-header i {
    font-size: 1.5rem;
}

.card-header h3 {
    margin: 0;
    font-weight: 600;
    font-size: 1.3rem;
}

.card-body {
    padding: 2rem;
}

/* Section optionnelle */
.optional-notice {
    background: #e7f3ff;
    padding: 1rem 1.5rem;
    border-radius: 10px;
    border-left: 4px solid #193561;
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.optional-notice i {
    color: #193561;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.optional-notice p {
    margin: 0;
    color: #193561;
    font-weight: 500;
}

/* Bouton passer */
.skip-section {
    text-align: center;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px dashed #dee2e6;
}

.btn-skip {
    background: transparent;
    color: #6c757d;
    border: 2px solid #dee2e6;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-skip:hover {
    background: #f8f9fa;
    color: #193561;
    border-color: #193561;
    transform: translateY(-2px);
}

/* Formulaire */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: #193561;
    margin-bottom: 0.5rem;
}

.required {
    color: #dc3545;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 12px 15px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #193561;
    box-shadow: 0 0 0 0.2rem rgba(25, 53, 97, 0.25);
}

/* Upload de fichiers */
.file-upload-area {
    position: relative;
    border: 2px dashed #dee2e6;
    border-radius: 10px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.file-upload-area:hover {
    border-color: #193561;
    background: #f8f9fa;
}

.file-upload-area input[type="file"] {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.file-upload-info i {
    font-size: 3rem;
    color: #193561;
    margin-bottom: 1rem;
}

.file-upload-info p {
    color: #193561;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.file-upload-info small {
    color: #6c757d;
}

.file-list {
    display: none;
}

.file-item {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.file-name {
    flex: 1;
    color: #193561;
    font-weight: 500;
}

.file-size {
    color: #6c757d;
    font-size: 0.9rem;
}

/* Confirmation */
.confirmation-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    border-left: 4px solid #193561;
}

.form-check-input:checked {
    background-color: #193561;
    border-color: #193561;
}

/* Boutons */
.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn {
    padding: 12px 30px;
    border-radius: 10px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-prev {
    background: #6c757d;
    color: white;
}

.btn-prev:hover {
    background: #5a6268;
    color: white;
    transform: translateX(-2px);
}

.btn-next, .btn-submit {
    background: #193561;
    color: white;
}

.btn-next:hover, .btn-submit:hover {
    background: #152a4d;
    color: white;
    transform: translateX(2px);
}

.btn-submit:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
}

/* Étapes du formulaire */
.form-step {
    display: none;
}

.form-step.active {
    display: block;
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Responsive */
@media (max-width: 768px) {
    .demande-container {
        padding: 20px 0;
    }
    
    .main-title {
        font-size: 2rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .steps {
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .steps:before {
        display: none;
    }
    
    .step {
        flex-direction: row;
        text-align: left;
        gap: 1rem;
    }
    
    .step-number {
        margin-bottom: 0;
        width: 40px;
        height: 40px;
        font-size: 0.9rem;
    }
    
    .step-label {
        font-size: 0.8rem;
    }
    
    .form-actions {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .optional-notice {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
}

@media (max-width: 576px) {
    .card-header {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
        padding: 1rem;
    }
    
    .row {
        margin: 0;
    }
    
    .col-md-6 {
        padding: 0 0 1rem 0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Navigation entre les étapes
    const steps = document.querySelectorAll('.step');
    const formSteps = document.querySelectorAll('.form-step');
    
    // Boutons suivant
    document.querySelectorAll('.btn-next').forEach(button => {
        button.addEventListener('click', function() {
            const currentStep = this.closest('.form-step');
            const nextStepId = this.getAttribute('data-next');
            
            // Validation de l'étape actuelle
            if (validateStep(currentStep.id)) {
                currentStep.classList.remove('active');
                document.getElementById(`step${nextStepId}`).classList.add('active');
                updateSteps(nextStepId);
            }
        });
    });
    
    // Boutons précédent
    document.querySelectorAll('.btn-prev').forEach(button => {
        button.addEventListener('click', function() {
            const currentStep = this.closest('.form-step');
            const prevStepId = this.getAttribute('data-prev');
            
            currentStep.classList.remove('active');
            document.getElementById(`step${prevStepId}`).classList.add('active');
            updateSteps(prevStepId);
        });
    });
    
    // Bouton passer l'étape véhicule
    document.getElementById('skipVehicleBtn').addEventListener('click', function() {
        const currentStep = this.closest('.form-step');
        currentStep.classList.remove('active');
        document.getElementById('step4').classList.add('active');
        updateSteps(4);
    });
    
    // Mise à jour des étapes
    function updateSteps(activeStep) {
        steps.forEach(step => {
            const stepNumber = step.getAttribute('data-step');
            if (stepNumber <= activeStep) {
                step.classList.add('active');
            } else {
                step.classList.remove('active');
            }
        });
    }
    
    // Validation des étapes
    function validateStep(stepId) {
        const step = document.getElementById(stepId);
        const inputs = step.querySelectorAll('input[required], select[required], textarea[required]');
        let isValid = true;
        
        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        // Validation spécifique pour les dates (étape 2)
        if (stepId === 'step2') {
            const dateDebut = document.getElementById('date_visite');
            const dateFin = document.getElementById('date_fin_visite');
            
            if (dateDebut.value && dateFin.value) {
                if (new Date(dateFin.value) < new Date(dateDebut.value)) {
                    dateFin.classList.add('is-invalid');
                    isValid = false;
                    alert('La date de fin ne peut pas être antérieure à la date de début.');
                } else {
                    dateFin.classList.remove('is-invalid');
                }
            }
        }
        
        return isValid;
    }
    
    // Gestion de l'upload de fichiers
    const fileInput = document.getElementById('documents_joints');
    const fileList = document.getElementById('file-list');
    
    fileInput.addEventListener('change', function() {
        fileList.innerHTML = '';
        
        if (this.files.length > 0) {
            fileList.style.display = 'block';
            
            Array.from(this.files).forEach(file => {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                fileItem.innerHTML = `
                    <div class="file-name">${file.name}</div>
                    <div class="file-size">${formatFileSize(file.size)}</div>
                `;
                fileList.appendChild(fileItem);
            });
        } else {
            fileList.style.display = 'none';
        }
    });
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Validation en temps réel pour le nombre de personnes
    const nbrePersoInput = document.getElementById('nbre_perso');
    nbrePersoInput.addEventListener('input', function() {
        if (this.value < 1) {
            this.value = 1;
        } else if (this.value > 50) {
            this.value = 50;
        }
    });
    
    // Validation du formulaire avant soumission
    document.getElementById('demandeForm').addEventListener('submit', function(e) {
        if (!document.getElementById('confirmation').checked) {
            e.preventDefault();
            alert('Veuillez confirmer que les informations fournies sont exactes.');
        }
    });
});
</script>
@endsection