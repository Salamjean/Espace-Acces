@extends('demandeur.layouts.template')
@section('content')
<div class="demande-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <!-- En-tête -->
                <div class="text-center mb-5">
                    <h1 class="main-title">Nouvelle Demande d'Accès</h1>
                    <p class="subtitle">Remplissez le formulaire en quelques étapes simples</p>
                </div>

                <!-- Étapes -->
                <div class="steps-container mb-5">
                    <div class="steps">
                        <div class="step active" data-step="0">
                            <div class="step-number">1</div>
                            <div class="step-label">Informations Personnelles</div>
                        </div>
                        <div class="step" data-step="1">
                            <div class="step-number">2</div>
                            <div class="step-label">Informations Supplémentaires</div>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-number">3</div>
                            <div class="step-label">Documents & Validation</div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire -->
                <form id="demandeForm" action="{{ route('demandes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Étape 0: Informations Personnelles -->
                    <div class="form-step active" id="step0">
                        <div class="form-card">
                            <div class="card-header">
                                <i class="bi bi-person-circle"></i>
                                <h3 class="text-white">Informations Personnelles</h3>
                            </div>
                            <div class="card-body">
                                <!-- Sélecteur du type de personnes -->
                                <div class="form-group">
                                    <label for="type_personnes" class="form-label">Type de Personnes <span class="required">*</span></label>
                                    <select class="form-control" id="type_personnes" name="type_demande" required>
                                        <option value="">Sélectionnez le type de personnes</option>
                                        <option value="moi">Pour moi-même uniquement</option>
                                        <option value="moi_autres">Pour moi et d'autres personnes</option>
                                        <option value="autres">Pour d'autres personnes uniquement</option>
                                    </select>
                                    <small class="form-text text-muted" id="typePersonnesHelp">
                                        Choisissez pour qui vous faites cette demande.
                                    </small>
                                </div>

                                <!-- Sélecteur du nombre de personnes -->
                                <div class="form-group" id="nbre_perso_container" style="display: none;">
                                    <label for="nbre_perso" class="form-label">Nombre de Personnes <span class="required">*</span></label>
                                    <select class="form-control" id="nbre_perso" name="nbre_perso" required>
                                        <!-- Les options seront générées dynamiquement -->
                                    </select>
                                    <small class="form-text text-muted" id="nbrePersoHelp">
                                        Sélectionnez le nombre total de personnes.
                                    </small>
                                </div>
                                
                                <!-- Navigation par onglets -->
                                <div class="personnes-tabs-container" id="personnes-tabs-container" style="display: none;">
                                    <ul class="nav nav-tabs" id="personnesTabs" role="tablist">
                                        <!-- Les onglets seront générés dynamiquement ici -->
                                    </ul>
                                    
                                    <div class="tab-content" id="personnesTabContent">
                                        <!-- Le contenu des onglets sera généré dynamiquement ici -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="button" class="btn btn-next" data-next="1" id="btnStep0Next" disabled>
                                Suivant
                            </button>
                        </div>
                    </div>

                    <!-- Étape 1: Informations Supplémentaires -->
                    <div class="form-step" id="step1">
                        <div class="form-card">
                            <div class="card-header">
                                <i class="bi bi-info-circle"></i>
                                <h3 class="text-white">Informations Supplémentaires</h3>
                            </div>
                            <div class="card-body">
                                <!-- Navigation par onglets pour les informations supplémentaires -->
                                <div class="personnes-supp-tabs-container" id="personnes-supp-tabs-container">
                                    <ul class="nav nav-tabs" id="personnesSuppTabs" role="tablist">
                                        <!-- Les onglets seront générés dynamiquement ici -->
                                    </ul>
                                    
                                    <div class="tab-content" id="personnesSuppTabContent">
                                        <!-- Le contenu des onglets sera généré dynamiquement ici -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-prev" data-prev="0">Précédent</button>
                            <button type="button" class="btn btn-next" data-next="2">Suivant</button>
                        </div>
                    </div>

                    <!-- Étape 2: Documents & Validation -->
                    <div class="form-step" id="step2">
                        <div class="form-card">
                            <div class="card-header">
                                <i class="bi bi-file-earmark-text"></i>
                                <h3 class="text-white">Documents & Validation</h3>
                            </div>
                            <div class="card-body">
                                <!-- NOUVEAU CHAMP TICKET -->
                                <div class="form-group">
                                    <label for="numero_ticket" class="form-label">Numéro de Ticket <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="numero_ticket" name="numero_ticket"  
                                        placeholder="Entrez le numéro de ticket" maxlength="50">
                                    <small class="form-text text-muted">
                                        Ce numéro de ticket sera commun à toutes les personnes de cette demande.
                                    </small>
                                </div>

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
                                            Je certifie que les informations fournies sont exactes et complètes pour toutes les personnes concernées
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-prev" data-prev="1">Précédent</button>
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
    max-width: 1200px;
    margin: 0 auto;
}

/* Styles pour les onglets */
.personnes-tabs-container, .personnes-supp-tabs-container {
    margin-top: 2rem;
}

.nav-tabs {
    border-bottom: 2px solid #193561;
    margin-bottom: 1.5rem;
}

.nav-tabs .nav-link {
    border: 2px solid transparent;
    border-bottom: none;
    border-radius: 10px 10px 0 0;
    padding: 12px 20px;
    color: #6c757d;
    font-weight: 600;
    transition: all 0.3s ease;
    margin-right: 5px;
}

.nav-tabs .nav-link:hover {
    border-color: #e9ecef #e9ecef #193561;
    color: #193561;
}

.nav-tabs .nav-link.active {
    color: #193561;
    background-color: white;
    border-color: #193561 #193561 white;
    border-width: 3px;
}

.tab-content {
    padding: 0;
}

.tab-pane {
    animation: fadeIn 0.3s ease;
}

/* Section véhicules cachée par défaut */
.vehicule-section {
    display: none;
    margin-top: 1.5rem;
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #193561;
}

.vehicule-section.visible {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Badge pour le demandeur principal */
.demandeur-principal-badge {
    background: #193561;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 1rem;
}

/* Styles responsifs pour les onglets */
@media (max-width: 768px) {
    .nav-tabs .nav-link {
        padding: 10px 15px;
        font-size: 0.9rem;
    }
    
    .nav-tabs {
        flex-wrap: wrap;
    }
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

/* Options de type de demande */
.type-demande-options {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.option-card {
    background: white;
    border: 3px solid #e9ecef;
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.option-card:hover {
    border-color: #193561;
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(25, 53, 97, 0.1);
}

.option-card.selected {
    border-color: #193561;
    background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(25, 53, 97, 0.15);
}

.option-card.selected::before {
    content: '✓';
    position: absolute;
    top: 15px;
    right: 15px;
    background: #193561;
    color: white;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

.option-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #193561, #2c5282);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
}

.option-card h4 {
    color: #193561;
    font-weight: 700;
    margin-bottom: 1rem;
    font-size: 1.3rem;
}

.option-card p {
    color: #6c757d;
    margin-bottom: 1.5rem;
    line-height: 1.5;
}

.option-info {
    background: #e7f3ff;
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid #193561;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #193561;
}

.option-info i {
    flex-shrink: 0;
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

/* Notes d'information */
.form-info-note {
    background: #e7f3ff;
    padding: 1rem 1.5rem;
    border-radius: 10px;
    border-left: 4px solid #193561;
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.form-info-note i {
    color: #193561;
    font-size: 1.5rem;
    flex-shrink: 0;
}

.form-info-note p {
    margin: 0;
    color: #193561;
    font-weight: 500;
}

/* Cartes de personnes */
.person-form-card {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    transition: all 0.3s ease;
}

.person-form-card:hover {
    border-color: #193561;
    box-shadow: 0 5px 15px rgba(25, 53, 97, 0.1);
}

.person-header {
    border-bottom: 2px solid #dee2e6;
    padding-bottom: 1rem;
    margin-bottom: 1.5rem;
}

.person-header h4 {
    color: #193561;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.person-header h4::before {
    content: '👤';
    font-size: 1.2rem;
}

.demandeur-principal-badge {
    background: #193561;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 1rem;
}

/* Section dates et heures dans les cartes personnes */
.dates-heures-section {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 2px dashed #dee2e6;
}

.dates-heures-title {
    color: #193561;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
}

.dates-heures-title::before {
    content: '📅';
    font-size: 1.1rem;
}

/* Section véhicules communes */
.vehicules-communes-section {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid #e9ecef;
}

.section-title {
    color: #193561;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.2rem;
}

/* Formulaire */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: #193561;
    margin-bottom: 0.5rem;
    display: block;
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
    width: 100%;
}

.form-control:focus {
    border-color: #193561;
    box-shadow: 0 0 0 0.2rem rgba(25, 53, 97, 0.25);
}

.form-control.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.form-text {
    font-size: 0.875rem;
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
    border: 1px solid #e9ecef;
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
    margin-top: 2rem;
}

.form-check-input:checked {
    background-color: #193561;
    border-color: #193561;
}

.form-check-label {
    color: #193561;
    font-weight: 500;
    margin-left: 0.5rem;
}

/* Boutons */
.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 2rem;
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
    cursor: pointer;
    font-size: 1rem;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

.btn-prev {
    background: #6c757d;
    color: white;
}

.btn-prev:hover:not(:disabled) {
    background: #5a6268;
    color: white;
    transform: translateX(-2px);
}

.btn-next, .btn-submit {
    background: #193561;
    color: white;
}

.btn-next:hover:not(:disabled), .btn-submit:hover:not(:disabled) {
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
@media (max-width: 1200px) {
    .type-demande-options {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 992px) {
    .type-demande-options {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .steps {
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .step {
        flex: 0 0 calc(33.333% - 1rem);
    }
}

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
        flex: 1;
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
    
    .form-info-note {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .person-form-card {
        padding: 1.5rem;
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
    
    .col-md-6, .col-md-4 {
        padding: 0 0 1rem 0;
    }
    
    .option-card {
        padding: 1.5rem;
    }
    
    .option-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const steps = document.querySelectorAll('.step');
    const formSteps = document.querySelectorAll('.form-step');
    const typePersonnesSelect = document.getElementById('type_personnes');
    const nbrePersoContainer = document.getElementById('nbre_perso_container');
    const nbrePersoSelect = document.getElementById('nbre_perso');
    const personnesTabsContainer = document.getElementById('personnes-tabs-container');
    const personnesSuppTabsContainer = document.getElementById('personnes-supp-tabs-container');
    const btnStep0Next = document.getElementById('btnStep0Next');

    // Initialisation
    updateSteps(0);

    // Gestion du type de personnes
    typePersonnesSelect.addEventListener('change', function() {
        const selectedType = this.value;
        nbrePersoContainer.style.display = selectedType ? 'block' : 'none';
        personnesTabsContainer.style.display = 'none';
        btnStep0Next.disabled = true;

        // Générer les options du nombre de personnes selon le type
        nbrePersoSelect.innerHTML = '<option value="">Sélectionnez...</option>';
        
        if (selectedType === 'moi') {
            nbrePersoSelect.innerHTML = '<option value="1">1 personne</option>';
            nbrePersoSelect.value = '1';
            // Activer directement le bouton suivant pour "moi"
            btnStep0Next.disabled = false;
            generatePersonnesForms();
        } else if (selectedType === 'moi_autres') {
            for (let i = 2; i <= 10; i++) {
                nbrePersoSelect.innerHTML += `<option value="${i}">${i} personnes</option>`;
            }
        } else if (selectedType === 'autres') {
            for (let i = 1; i <= 10; i++) {
                nbrePersoSelect.innerHTML += `<option value="${i}">${i} personnes</option>`;
            }
        }
    });

    // Gestion du nombre de personnes
    nbrePersoSelect.addEventListener('change', function() {
        if (this.value) {
            generatePersonnesForms();
            btnStep0Next.disabled = false;
        } else {
            personnesTabsContainer.style.display = 'none';
            btnStep0Next.disabled = true;
        }
    });

    // Génération des formulaires avec onglets
    function generatePersonnesForms() {
        const nbrePerso = parseInt(nbrePersoSelect.value);
        const typePersonnes = typePersonnesSelect.value;
        
        if (!nbrePerso || !typePersonnes) return;

        // Afficher le conteneur des onglets
        personnesTabsContainer.style.display = 'block';

        // Générer les onglets
        const tabsList = document.getElementById('personnesTabs');
        const tabsContent = document.getElementById('personnesTabContent');
        
        tabsList.innerHTML = '';
        tabsContent.innerHTML = '';

        for (let i = 0; i < nbrePerso; i++) {
            const isFirstPerson = i === 0;
            const isMoiAutresType = typePersonnes === 'moi_autres';
            const isMoiType = typePersonnes === 'moi';
            const isDemandeurPrincipal = (isMoiType && isFirstPerson) || (isMoiAutresType && isFirstPerson);
            const personneLabel = isDemandeurPrincipal ? 'Vous' : `Personne ${i + 1}`;

            // Créer l'onglet
            const tabHTML = `
                <li class="nav-item" role="presentation">
                    <button class="nav-link ${i === 0 ? 'active' : ''}" 
                            id="tab-${i}" 
                            data-bs-toggle="tab" 
                            data-bs-target="#personne-${i}" 
                            type="button" 
                            role="tab">
                        ${personneLabel}
                        ${isDemandeurPrincipal ? '<span class="demandeur-principal-badge">Principal</span>' : ''}
                    </button>
                </li>
            `;
            tabsList.insertAdjacentHTML('beforeend', tabHTML);

            // Créer le contenu de l'onglet avec 3 colonnes
            const tabContentHTML = `
                <div class="tab-pane fade ${i === 0 ? 'show active' : ''}" 
                     id="personne-${i}" 
                     role="tabpanel">
                    <div class="person-form-card">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personnes_${i}_name" class="form-label">Nom <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="personnes_${i}_name" name="personnes[${i}][name]" required
                                           ${isDemandeurPrincipal && isMoiType ? 'readonly' : ''}>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personnes_${i}_prenom" class="form-label">Prénom <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="personnes_${i}_prenom" name="personnes[${i}][prenom]" required
                                           ${isDemandeurPrincipal && isMoiType ? 'readonly' : ''}>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personnes_${i}_contact" class="form-label">Contact <span class="required">*</span></label>
                                    <input type="tel" class="form-control" id="personnes_${i}_contact" name="personnes[${i}][contact]" required
                                           ${isDemandeurPrincipal && isMoiType ? 'readonly' : ''}>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personnes_${i}_email" class="form-label">Email <span class="required">*</span></label>
                                    <input type="email" class="form-control" id="personnes_${i}_email" name="personnes[${i}][email]" required
                                           ${isDemandeurPrincipal && isMoiType ? 'readonly' : ''}>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personnes_${i}_adresse" class="form-label">Adresse <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="personnes_${i}_adresse" name="personnes[${i}][adresse]" required
                                           ${isDemandeurPrincipal && isMoiType ? 'readonly' : ''}>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="personnes_${i}_fonction" class="form-label">Fonction <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="personnes_${i}_fonction" name="personnes[${i}][fonction]" required
                                           ${isDemandeurPrincipal && isMoiType ? 'readonly' : ''}>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="personnes_${i}_structure" class="form-label">Structure <span class="required">*</span></label>
                                    <input type="text" class="form-control" id="personnes_${i}_structure" name="personnes[${i}][structure]" required
                                           ${isDemandeurPrincipal && isMoiType ? 'readonly' : ''}>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="personnes_${i}_profile_picture" class="form-label">Photo de profil</label>
                                    <input type="file" class="form-control" id="personnes_${i}_profile_picture" name="personnes[${i}][profile_picture]" accept="image/*">
                                    ${isDemandeurPrincipal && isMoiType ? '<small class="form-text text-muted">Votre photo de profil sera utilisée automatiquement</small>' : ''}
                                </div>
                            </div>
                        </div>

                        <!-- Bouton pour passer à la personne suivante (sauf pour la dernière) -->
                        ${i < nbrePerso - 1 ? `
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-next-person" data-next-person="${i + 1}">
                                Passer à la personne suivante <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                        ` : ''}
                    </div>
                </div>
            `;
            tabsContent.insertAdjacentHTML('beforeend', tabContentHTML);

            // Pré-remplir si c'est le demandeur principal
            if (isDemandeurPrincipal) {
                prefillUserData(i);
            }

            // Ajouter les écouteurs d'événements pour la validation en temps réel
            addRealTimeValidation(i);
        }

        // Activer les boutons "Passer à la personne suivante"
        document.querySelectorAll('.btn-next-person').forEach(button => {
            button.addEventListener('click', function() {
                const nextPersonIndex = this.getAttribute('data-next-person');
                
                // Valider les champs obligatoires de la personne actuelle
                const currentPersonIndex = nextPersonIndex - 1;
                if (validatePersonneForm(currentPersonIndex)) {
                    // Activer l'onglet suivant
                    const nextTab = document.getElementById(`tab-${nextPersonIndex}`);
                    if (nextTab) {
                        nextTab.click();
                    }
                }
            });
        });

        // Générer aussi les onglets pour les informations supplémentaires
        generateSuppTabs();
    }

    // Ajouter la validation en temps réel pour une personne
    function addRealTimeValidation(personIndex) {
        const fields = [
            `personnes_${personIndex}_name`,
            `personnes_${personIndex}_prenom`,
            `personnes_${personIndex}_contact`,
            `personnes_${personIndex}_email`,
            `personnes_${personIndex}_adresse`,
            `personnes_${personIndex}_fonction`,
            `personnes_${personIndex}_structure`
        ];

        fields.forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                input.addEventListener('blur', function() {
                    validateField(this);
                });
                input.addEventListener('input', function() {
                    if (this.classList.contains('is-invalid')) {
                        validateField(this);
                    }
                });
            }
        });
    }

    // Ajouter la validation en temps réel pour les informations supplémentaires
    function addRealTimeSuppValidation(personIndex) {
        const fields = [
            `personnes_${personIndex}_date_visite`,
            `personnes_${personIndex}_date_fin_visite`,
            `personnes_${personIndex}_heure_visite`,
            `personnes_${personIndex}_motif_visite`
        ];

        fields.forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                input.addEventListener('blur', function() {
                    validateSuppField(this, personIndex);
                });
                input.addEventListener('input', function() {
                    if (this.classList.contains('is-invalid')) {
                        validateSuppField(this, personIndex);
                    }
                });
            }
        });

        // Validation spéciale pour les dates
        const dateDebut = document.getElementById(`personnes_${personIndex}_date_visite`);
        const dateFin = document.getElementById(`personnes_${personIndex}_date_fin_visite`);
        const heureDebut = document.getElementById(`personnes_${personIndex}_heure_visite`);

        if (dateDebut && dateFin) {
            dateDebut.addEventListener('change', function() {
                validateDates(personIndex);
            });
            dateFin.addEventListener('change', function() {
                validateDates(personIndex);
            });
        }

        if (heureDebut) {
            heureDebut.addEventListener('change', function() {
                validateDateTime(personIndex);
            });
        }
    }

    // Validation d'un champ individuel
    function validateField(field) {
        if (!field.value.trim() && !field.disabled && !field.readOnly) {
            field.classList.add('is-invalid');
            return false;
        } else {
            field.classList.remove('is-invalid');
            return true;
        }
    }

    // Validation d'un champ des informations supplémentaires
    function validateSuppField(field, personIndex) {
        if (!field.value.trim() && !field.disabled && !field.readOnly) {
            field.classList.add('is-invalid');
            return false;
        } else {
            field.classList.remove('is-invalid');
            return true;
        }
    }

    // Validation des dates
    function validateDates(personIndex) {
        const dateDebut = document.getElementById(`personnes_${personIndex}_date_visite`);
        const dateFin = document.getElementById(`personnes_${personIndex}_date_fin_visite`);
        
        if (dateDebut && dateFin && dateDebut.value && dateFin.value) {
            if (new Date(dateFin.value) < new Date(dateDebut.value)) {
                dateFin.classList.add('is-invalid');
                return false;
            } else {
                dateFin.classList.remove('is-invalid');
                return true;
            }
        }
        return true;
    }

    // Validation de la date et heure
    function validateDateTime(personIndex) {
        const dateDebut = document.getElementById(`personnes_${personIndex}_date_visite`);
        const heureDebut = document.getElementById(`personnes_${personIndex}_heure_visite`);
        
        if (dateDebut && heureDebut && dateDebut.value && heureDebut.value) {
            const dateTimeVisite = new Date(dateDebut.value + 'T' + heureDebut.value);
            if (dateTimeVisite < new Date()) {
                dateDebut.classList.add('is-invalid');
                heureDebut.classList.add('is-invalid');
                return false;
            } else {
                dateDebut.classList.remove('is-invalid');
                heureDebut.classList.remove('is-invalid');
                return true;
            }
        }
        return true;
    }

  

    // Validation d'une personne spécifique
    function validatePersonneForm(personIndex) {
        let isValid = true;
        let emptyFields = [];
        
        const requiredFields = [
            { id: `personnes_${personIndex}_name`, label: 'Nom' },
            { id: `personnes_${personIndex}_prenom`, label: 'Prénom' },
            { id: `personnes_${personIndex}_contact`, label: 'Contact' },
            { id: `personnes_${personIndex}_email`, label: 'Email' },
            { id: `personnes_${personIndex}_adresse`, label: 'Adresse' },
            { id: `personnes_${personIndex}_fonction`, label: 'Fonction' },
            { id: `personnes_${personIndex}_structure`, label: 'Structure' }
        ];

        for (const field of requiredFields) {
            const input = document.getElementById(field.id);
            if (input && !input.value.trim() && !input.disabled && !input.readOnly) {
                input.classList.add('is-invalid');
                isValid = false;
                emptyFields.push(field.label);
            }
        }

        if (!isValid) {
            const personneLabel = personIndex === 0 ? 'Vous' : `Personne ${personIndex + 1}`;
            alert(`Veuillez remplir tous les champs obligatoires pour ${personneLabel} : ${emptyFields.join(', ')}`);
        }

        return isValid;
    }

    // Validation de toutes les personnes pour l'étape 0
    function validateAllPersonnesStep0() {
        const nbrePerso = parseInt(nbrePersoSelect.value);
        let allValid = true;
        let personsWithErrors = [];

        // Valider chaque personne
        for (let i = 0; i < nbrePerso; i++) {
            if (!validatePersonneFormSilent(i)) {
                allValid = false;
                personsWithErrors.push(i === 0 ? 'Vous' : `Personne ${i + 1}`);
            }
        }

        // Si des erreurs, activer l'onglet de la première personne avec erreur
        if (!allValid) {
            for (let i = 0; i < nbrePerso; i++) {
                if (!validatePersonneFormSilent(i)) {
                    const tab = document.getElementById(`tab-${i}`);
                    if (tab) {
                        tab.click();
                    }
                    break;
                }
            }
            alert(`Veuillez remplir tous les champs obligatoires pour les personnes suivantes : ${personsWithErrors.join(', ')}`);
        }

        return allValid;
    }

    // Validation silencieuse d'une personne (sans afficher d'alerte)
    function validatePersonneFormSilent(personIndex) {
        let isValid = true;
        
        const requiredFields = [
            `personnes_${personIndex}_name`,
            `personnes_${personIndex}_prenom`,
            `personnes_${personIndex}_contact`,
            `personnes_${personIndex}_email`,
            `personnes_${personIndex}_adresse`,
            `personnes_${personIndex}_fonction`,
            `personnes_${personIndex}_structure`
        ];

        for (const field of requiredFields) {
            const input = document.getElementById(field);
            if (input && !input.value.trim() && !input.disabled && !input.readOnly) {
                input.classList.add('is-invalid');
                isValid = false;
            }
        }

        return isValid;
    }

    // Générer les onglets pour les informations supplémentaires
    function generateSuppTabs() {
        const nbrePerso = parseInt(nbrePersoSelect.value);
        const typePersonnes = typePersonnesSelect.value;
        
        if (!nbrePerso || !typePersonnes) return;

        const tabsList = document.getElementById('personnesSuppTabs');
        const tabsContent = document.getElementById('personnesSuppTabContent');
        
        tabsList.innerHTML = '';
        tabsContent.innerHTML = '';

        for (let i = 0; i < nbrePerso; i++) {
            const isFirstPerson = i === 0;
            const isMoiAutresType = typePersonnes === 'moi_autres';
            const isMoiType = typePersonnes === 'moi';
            const isDemandeurPrincipal = (isMoiType && isFirstPerson) || (isMoiAutresType && isFirstPerson);
            const personneLabel = isDemandeurPrincipal ? 'Vous' : `Personne ${i + 1}`;

            // Créer l'onglet
            const tabHTML = `
                <li class="nav-item" role="presentation">
                    <button class="nav-link ${i === 0 ? 'active' : ''}" 
                            id="tab-supp-${i}" 
                            data-bs-toggle="tab" 
                            data-bs-target="#personne-supp-${i}" 
                            type="button" 
                            role="tab">
                        ${personneLabel}
                        ${isDemandeurPrincipal ? '<span class="demandeur-principal-badge">Principal</span>' : ''}
                    </button>
                </li>
            `;
            tabsList.insertAdjacentHTML('beforeend', tabHTML);

            // Créer le contenu de l'onglet (informations supplémentaires) avec 3 colonnes pour le véhicule
            const tabContentHTML = `
                <div class="tab-pane fade ${i === 0 ? 'show active' : ''}" 
                     id="personne-supp-${i}" 
                     role="tabpanel">
                    <div class="person-supp-form-card">
                        <!-- Dates et heures -->
                        <div class="dates-heures-section">
                            <h5 class="dates-heures-title">Dates et Horaires de Visite</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="personnes_${i}_date_visite" class="form-label">Date de Début <span class="required">*</span></label>
                                        <input type="date" class="form-control" id="personnes_${i}_date_visite" name="personnes[${i}][date_visite]" min="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="personnes_${i}_date_fin_visite" class="form-label">Date de Fin <span class="required">*</span></label>
                                        <input type="date" class="form-control" id="personnes_${i}_date_fin_visite" name="personnes[${i}][date_fin_visite]" min="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="personnes_${i}_heure_visite" class="form-label">Heure de Début <span class="required">*</span></label>
                                        <input type="time" class="form-control" id="personnes_${i}_heure_visite" name="personnes[${i}][heure_visite]" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="personnes_${i}_heure_fin_visite" class="form-label">Heure de Fin Estimée</label>
                                        <input type="time" class="form-control" id="personnes_${i}_heure_fin_visite" name="personnes[${i}][heure_fin_visite]">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Véhicule (caché par défaut) -->
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input vehicule-toggle" type="checkbox" id="vehicule_toggle_${i}" data-person-index="${i}">
                                <label class="form-check-label" for="vehicule_toggle_${i}">
                                    Cette personne viendra avec un véhicule
                                </label>
                            </div>
                        </div>

                        <div class="vehicule-section" id="vehicule_section_${i}">
                            <h5 class="section-title">
                                <i class="bi bi-car-front"></i>
                                Informations du Véhicule
                            </h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="personnes_${i}_marque_voiture" class="form-label">Marque du Véhicule</label>
                                        <input type="text" class="form-control" id="personnes_${i}_marque_voiture" name="personnes[${i}][marque_voiture]" placeholder="Ex: Toyota, Renault...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="personnes_${i}_modele_voiture" class="form-label">Modèle du Véhicule</label>
                                        <input type="text" class="form-control" id="personnes_${i}_modele_voiture" name="personnes[${i}][modele_voiture]" placeholder="Ex: Corolla, Clio...">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="personnes_${i}_immatriculation_voiture" class="form-label">Immatriculation</label>
                                        <input type="text" class="form-control" id="personnes_${i}_immatriculation_voiture" name="personnes[${i}][immatriculation_voiture]" placeholder="Ex: AB-123-CD">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="personnes_${i}_type_intervention" class="form-label">Type d'Intervention</label>
                                <select class="form-control" id="personnes_${i}_type_intervention" name="personnes[${i}][type_intervention]">
                                    <option value="">Sélectionnez...</option>
                                    <option value="urgence">Urgence</option>
                                    <option value="unitaire">Unitaire</option>
                                    <option value="recurrente">Récurrente</option>
                                    <option value="maintenance">Maintenance</option>
                                    <option value="livraison">Livraison</option>
                                </select>
                            </div>
                        </div>

                        <!-- Motif de visite pour chaque personne -->
                        <div class="form-group">
                            <label for="personnes_${i}_motif_visite" class="form-label">Motif de la Visite <span class="required">*</span></label>
                            <input type="text" class="form-control" id="personnes_${i}_motif_visite" name="personnes[${i}][motif_visite]" required placeholder="Entrez le motif de la visite">
                        </div>

                        <div class="form-group">
                            <label for="personnes_${i}_description_detaille" class="form-label">Description Détaillée</label>
                            <textarea class="form-control" id="personnes_${i}_description_detaille" name="personnes[${i}][description_detaille]" rows="4" placeholder="Décrivez en détail l'objet de la visite..."></textarea>
                        </div>

                        <!-- Bouton pour passer à la personne suivante (sauf pour la dernière) -->
                        ${i < nbrePerso - 1 ? `
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-next-person-supp" data-next-person-supp="${i + 1}">
                                Passer à la personne suivante <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                        ` : ''}
                    </div>
                </div>
            `;
            tabsContent.insertAdjacentHTML('beforeend', tabContentHTML);

            // Définir les valeurs par défaut pour les dates
            const today = new Date().toISOString().split('T')[0];
            const dateVisiteInput = document.getElementById(`personnes_${i}_date_visite`);
            const dateFinVisiteInput = document.getElementById(`personnes_${i}_date_fin_visite`);
            const heureVisiteInput = document.getElementById(`personnes_${i}_heure_visite`);
            const heureFinVisiteInput = document.getElementById(`personnes_${i}_heure_fin_visite`);

            if (dateVisiteInput) dateVisiteInput.value = today;
            if (dateFinVisiteInput) dateFinVisiteInput.value = today;
            if (heureVisiteInput) heureVisiteInput.value = '09:00';
            if (heureFinVisiteInput) heureFinVisiteInput.value = '17:00';

            // Ajouter la validation en temps réel pour les informations supplémentaires
            addRealTimeSuppValidation(i);
        }

        // Activer les toggle de véhicule
        document.querySelectorAll('.vehicule-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const personIndex = this.getAttribute('data-person-index');
                const vehiculeSection = document.getElementById(`vehicule_section_${personIndex}`);
                if (this.checked) {
                    vehiculeSection.classList.add('visible');
                } else {
                    vehiculeSection.classList.remove('visible');
                }
            });
        });

        // Activer les boutons "Passer à la personne suivante" pour les infos supplémentaires
        document.querySelectorAll('.btn-next-person-supp').forEach(button => {
            button.addEventListener('click', function() {
                const nextPersonIndex = this.getAttribute('data-next-person-supp');
                
                // Valider les champs obligatoires de la personne actuelle
                const currentPersonIndex = nextPersonIndex - 1;
                if (validatePersonneSuppForm(currentPersonIndex)) {
                    // Activer l'onglet suivant
                    const nextTab = document.getElementById(`tab-supp-${nextPersonIndex}`);
                    if (nextTab) {
                        nextTab.click();
                    }
                }
            });
        });
    }

    // Validation des informations supplémentaires d'une personne
    function validatePersonneSuppForm(personIndex) {
        let isValid = true;
        let emptyFields = [];
        
        const requiredFields = [
            { id: `personnes_${personIndex}_date_visite`, label: 'Date de début' },
            { id: `personnes_${personIndex}_date_fin_visite`, label: 'Date de fin' },
            { id: `personnes_${personIndex}_heure_visite`, label: 'Heure de début' },
            { id: `personnes_${personIndex}_motif_visite`, label: 'Motif de visite' }
        ];

        for (const field of requiredFields) {
            const input = document.getElementById(field.id);
            if (input && !input.value.trim() && !input.disabled && !input.readOnly) {
                input.classList.add('is-invalid');
                isValid = false;
                emptyFields.push(field.label);
            }
        }

        // Validation des dates
        if (!validateDates(personIndex)) {
            isValid = false;
        }

        // Validation de la date et heure
        if (!validateDateTime(personIndex)) {
            isValid = false;
        }

        if (!isValid) {
            const personneLabel = personIndex === 0 ? 'Vous' : `Personne ${personIndex + 1}`;
            if (emptyFields.length > 0) {
                alert(`Veuillez remplir les champs obligatoires pour ${personneLabel} : ${emptyFields.join(', ')}`);
            }
        }

        return isValid;
    }

    // Validation de toutes les personnes pour l'étape 1
    function validateAllPersonnesStep1() {
        const nbrePerso = parseInt(nbrePersoSelect.value);
        let allValid = true;
        let personsWithErrors = [];

        // Valider chaque personne
        for (let i = 0; i < nbrePerso; i++) {
            if (!validatePersonneSuppFormSilent(i)) {
                allValid = false;
                personsWithErrors.push(i === 0 ? 'Vous' : `Personne ${i + 1}`);
            }
        }

        // Si des erreurs, activer l'onglet de la première personne avec erreur
        if (!allValid) {
            for (let i = 0; i < nbrePerso; i++) {
                if (!validatePersonneSuppFormSilent(i)) {
                    const tab = document.getElementById(`tab-supp-${i}`);
                    if (tab) {
                        tab.click();
                    }
                    break;
                }
            }
            alert(`Veuillez remplir tous les champs obligatoires pour les personnes suivantes : ${personsWithErrors.join(', ')}`);
        }

        return allValid;
    }

    // Validation silencieuse des informations supplémentaires d'une personne
    function validatePersonneSuppFormSilent(personIndex) {
        let isValid = true;
        
        const requiredFields = [
            `personnes_${personIndex}_date_visite`,
            `personnes_${personIndex}_date_fin_visite`,
            `personnes_${personIndex}_heure_visite`,
            `personnes_${personIndex}_motif_visite`
        ];

        for (const field of requiredFields) {
            const input = document.getElementById(field);
            if (input && !input.value.trim() && !input.disabled && !input.readOnly) {
                input.classList.add('is-invalid');
                isValid = false;
            }
        }

        // Validation des dates
        if (!validateDates(personIndex)) {
            isValid = false;
        }

        // Validation de la date et heure
        if (!validateDateTime(personIndex)) {
            isValid = false;
        }

        return isValid;
    }

    // Pré-remplir avec les données de l'utilisateur connecté
    function prefillUserData(personIndex = 0) {
        const userData = {
            name: '{{ Auth::guard("demandeur")->user()->name ?? "" }}',
            prenom: '{{ Auth::guard("demandeur")->user()->prenom ?? "" }}',
            contact: '{{ Auth::guard("demandeur")->user()->contact ?? "" }}',
            email: '{{ Auth::guard("demandeur")->user()->email ?? "" }}',
            adresse: '{{ Auth::guard("demandeur")->user()->adresse ?? "" }}',
            fonction: '{{ Auth::guard("demandeur")->user()->fonction ?? "" }}',
            structure: '{{ Auth::guard("demandeur")->user()->structure ?? "" }}'
        };

        const nameInput = document.getElementById(`personnes_${personIndex}_name`);
        if (nameInput && userData.name) nameInput.value = userData.name;

        const prenomInput = document.getElementById(`personnes_${personIndex}_prenom`);
        if (prenomInput && userData.prenom) prenomInput.value = userData.prenom;

        const contactInput = document.getElementById(`personnes_${personIndex}_contact`);
        if (contactInput && userData.contact) contactInput.value = userData.contact;

        const emailInput = document.getElementById(`personnes_${personIndex}_email`);
        if (emailInput && userData.email) emailInput.value = userData.email;

        const adresseInput = document.getElementById(`personnes_${personIndex}_adresse`);
        if (adresseInput && userData.adresse) adresseInput.value = userData.adresse;

        const fonctionInput = document.getElementById(`personnes_${personIndex}_fonction`);
        if (fonctionInput && userData.fonction) fonctionInput.value = userData.fonction;

        const structureInput = document.getElementById(`personnes_${personIndex}_structure`);
        if (structureInput && userData.structure) structureInput.value = userData.structure;
    }

    // Gestion de la navigation entre les étapes
    document.querySelectorAll('.btn-next').forEach(button => {
        button.addEventListener('click', function() {
            const currentStep = this.closest('.form-step');
            const nextStepId = this.getAttribute('data-next');

            // Validation renforcée pour le passage à l'étape 2
            if (nextStepId === '1' && currentStep.id === 'step0') {
                if (!validateAllPersonnesStep0()) {
                    return; // Empêcher le passage si validation échoue
                }
            }

            // Validation renforcée pour le passage à l'étape 3
            if (nextStepId === '2' && currentStep.id === 'step1') {
                if (!validateAllPersonnesStep1()) {
                    return; // Empêcher le passage si validation échoue
                }
            }

            if (validateStep(currentStep.id)) {
                currentStep.classList.remove('active');
                document.getElementById(`step${nextStepId}`).classList.add('active');
                updateSteps(nextStepId);
            }
        });
    });

    document.querySelectorAll('.btn-prev').forEach(button => {
        button.addEventListener('click', function() {
            const currentStep = this.closest('.form-step');
            const prevStepId = this.getAttribute('data-prev');

            currentStep.classList.remove('active');
            document.getElementById(`step${prevStepId}`).classList.add('active');
            updateSteps(prevStepId);
        });
    });

    function updateSteps(activeStep) {
        steps.forEach(step => {
            const stepNumber = parseInt(step.getAttribute('data-step'));
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
    let isValid = true;

    step.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

    switch(stepId) {
        case 'step0':
            if (!typePersonnesSelect.value) {
                alert('Veuillez sélectionner le type de personnes.');
                isValid = false;
            } else if (!nbrePersoSelect.value) {
                alert('Veuillez sélectionner le nombre de personnes.');
                isValid = false;
            }
            break;

        case 'step1':
            // La validation est déjà faite dans validateAllPersonnesStep1()
            break;

        case 'step2':
            // Validation du ticket
            if (!validateTicket()) {
                isValid = false;
                alert('Veuillez renseigner le numéro de ticket.');
            }

            const nbrePersoStep2 = parseInt(nbrePersoSelect.value);

            // Valider toutes les personnes pour les informations supplémentaires
            for (let i = 0; i < nbrePersoStep2; i++) {
                if (!validatePersonneSuppFormSilent(i)) {
                    isValid = false;
                }
            }

            // Validation de la confirmation
            const confirmationCheckbox = document.getElementById('confirmation');
            if (!confirmationCheckbox || !confirmationCheckbox.checked) {
                isValid = false;
                alert('Veuillez confirmer que les informations fournies sont exactes.');
            }
            break;
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

    // Validation du formulaire avant soumission
    document.getElementById('demandeForm').addEventListener('submit', function(e) {
        if (!validateStep('step2')) {
            e.preventDefault();
            return;
        }

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Soumission en cours...';
    });
});
</script>
@endsection