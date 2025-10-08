@extends('home.layouts.template')
@section('content')
<div class="form-container">
    <div class="form-header">
        <div class="header-content">
            <h1 class="form-title">Demande d'Accès</h1>
            <p class="form-subtitle">Remplissez le formulaire ci-dessous pour soumettre votre demande</p>
        </div>
        <div class="progress-indicator">
            <div class="progress-step active">
                <div class="step-number">1</div>
                <span>Informations</span>
            </div>
            <div class="progress-step">
                <div class="step-number">2</div>
                <span>Validation</span>
            </div>
            <div class="progress-step">
                <div class="step-number">3</div>
                <span>Confirmation</span>
            </div>
        </div>
    </div>

    <form class="modern-form" id="demandeForm">
        <!-- Informations Société -->
        <fieldset class="form-section">
            <legend class="section-legend">
                <i class="icon-building"></i>
                Informations de la Société
            </legend>
            <div class="form-grid">
                <div class="input-group">
                    <label for="societe" class="input-label">
                        Nom de la Société
                        <span class="required">*</span>
                    </label>
                    <input type="text" id="societe" name="societe" class="modern-input" required>
                    <div class="input-icon">
                        <i class="icon-company"></i>
                    </div>
                </div>

                <div class="input-group">
                    <label for="contact_societe" class="input-label">
                        Contact Société
                        <span class="required">*</span>
                    </label>
                    <input type="text" id="contact_societe" name="contact_societe" class="modern-input" required>
                    <div class="input-icon">
                        <i class="icon-phone"></i>
                    </div>
                </div>

                <div class="input-group">
                    <label for="email_societe" class="input-label">
                        Email Société
                        <span class="required">*</span>
                    </label>
                    <input type="email" id="email_societe" name="email_societe" class="modern-input" required>
                    <div class="input-icon">
                        <i class="icon-email"></i>
                    </div>
                </div>

                <div class="input-group">
                    <label for="adresse_societe" class="input-label">Adresse Société</label>
                    <input type="text" id="adresse_societe" name="adresse_societe" class="modern-input">
                    <div class="input-icon">
                        <i class="icon-location"></i>
                    </div>
                </div>
            </div>
        </fieldset>

        <!-- Informations Demandeur -->
        <fieldset class="form-section">
            <legend class="section-legend">
                <i class="icon-user"></i>
                Informations du Demandeur
            </legend>
            <div class="form-grid">
                <div class="input-group">
                    <label for="name_demandeur" class="input-label">
                        Nom
                        <span class="required">*</span>
                    </label>
                    <input type="text" id="name_demandeur" name="name_demandeur" class="modern-input" required>
                </div>

                <div class="input-group">
                    <label for="prenom_demandeur" class="input-label">
                        Prénom
                        <span class="required">*</span>
                    </label>
                    <input type="text" id="prenom_demandeur" name="prenom_demandeur" class="modern-input" required>
                </div>

                <div class="input-group">
                    <label for="contact_demandeur" class="input-label">
                        Contact
                        <span class="required">*</span>
                    </label>
                    <input type="text" id="contact_demandeur" name="contact_demandeur" class="modern-input" required>
                    <div class="input-icon">
                        <i class="icon-phone"></i>
                    </div>
                </div>

                <div class="input-group">
                    <label for="email_demandeur" class="input-label">
                        Email
                        <span class="required">*</span>
                    </label>
                    <input type="email" id="email_demandeur" name="email_demandeur" class="modern-input" required>
                    <div class="input-icon">
                        <i class="icon-email"></i>
                    </div>
                </div>

                <div class="input-group">
                    <label for="fonction_demandeur" class="input-label">Fonction</label>
                    <input type="text" id="fonction_demandeur" name="fonction_demandeur" class="modern-input">
                    <div class="input-icon">
                        <i class="icon-briefcase"></i>
                    </div>
                </div>
            </div>
        </fieldset>

        <!-- Informations Visite -->
        <fieldset class="form-section">
            <legend class="section-legend">
                <i class="icon-calendar"></i>
                Informations de la Visite
            </legend>
            <div class="form-grid">
                <div class="input-group">
                    <label for="nbre_perso" class="input-label">
                        Nombre de Personnes
                        <span class="required">*</span>
                    </label>
                    <div class="number-input">
                        <button type="button" class="number-btn" onclick="decrementPersons()">-</button>
                        <input type="number" id="nbre_perso" name="nbre_perso" class="modern-input" min="1" value="1" required>
                        <button type="button" class="number-btn" onclick="incrementPersons()">+</button>
                    </div>
                </div>

                <div class="input-group">
                    <label for="date_visite" class="input-label">
                        Date de Visite
                        <span class="required">*</span>
                    </label>
                    <input type="date" id="date_visite" name="date_visite" class="modern-input" required>
                    <div class="input-icon">
                        <i class="icon-calendar"></i>
                    </div>
                </div>

                <div class="input-group">
                    <label for="heure_visite" class="input-label">
                        Heure de Début
                        <span class="required">*</span>
                    </label>
                    <input type="time" id="heure_visite" name="heure_visite" class="modern-input" required>
                </div>

                <div class="input-group">
                    <label for="heure_fin_visite" class="input-label">Heure de Fin</label>
                    <input type="time" id="heure_fin_visite" name="heure_fin_visite" class="modern-input">
                </div>
            </div>
        </fieldset>

        <!-- Motif Visite -->
        <fieldset class="form-section">
            <legend class="section-legend">
                <i class="icon-file"></i>
                Détails de la Visite
            </legend>
            <div class="form-grid">
                <div class="input-group full-width">
                    <label for="motif_visite" class="input-label">
                        Motif de la Visite
                        <span class="required">*</span>
                    </label>
                    <select id="motif_visite" name="motif_visite" class="modern-select" required>
                        <option value="">Sélectionnez un motif</option>
                        <option value="reunion">Réunion d'affaires</option>
                        <option value="entretien">Entretien professionnel</option>
                        <option value="formation">Session de formation</option>
                        <option value="maintenance">Maintenance technique</option>
                        <option value="audit">Audit ou contrôle</option>
                        <option value="autre">Autre</option>
                    </select>
                    <div class="input-icon">
                        <i class="icon-chevron"></i>
                    </div>
                </div>

                <div class="input-group full-width">
                    <label for="description_detaille" class="input-label">Description Détaillée</label>
                    <textarea id="description_detaille" name="description_detaille" class="modern-textarea" rows="4" placeholder="Décrivez en détail le but de votre visite..."></textarea>
                </div>
            </div>
        </fieldset>

        <!-- Documents -->
        <fieldset class="form-section">
            <legend class="section-legend">
                <i class="icon-attachment"></i>
                Documents Joints
            </legend>
            <div class="upload-area" id="uploadArea">
                <div class="upload-content">
                    <i class="icon-upload"></i>
                    <h3>Glissez-déposez vos fichiers ici</h3>
                    <p>ou <span class="browse-link">parcourez</span> vos fichiers</p>
                    <input type="file" id="documents_joints" name="documents_joints[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" class="file-input">
                </div>
                <div class="upload-preview" id="uploadPreview"></div>
            </div>
        </fieldset>

        <!-- Actions -->
        <div class="form-actions">
            <button type="button" class="btn-secondary" onclick="resetForm()">
                <i class="icon-reset"></i>
                Réinitialiser
            </button>
            <button type="submit" class="btn-primary">
                <i class="icon-send"></i>
                Soumettre la Demande
            </button>
        </div>
    </form>
</div>

<style>
/* Variables CSS */
:root {
    --primary-color: #193561;
    --primary-light: #2a4a7a;
    --primary-dark: #14294a;
    --white: #ffffff;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --success: #10b981;
    --warning: #f59e0b;
    --error: #ef4444;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Reset et base */
.form-container {
    width: 80%;
    margin: 0 auto;
    padding: 2rem;
    background: var(--white);
    min-height: 100vh;
}

/* En-tête */
.form-header {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
    color: var(--white);
    border-radius: 16px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: var(--shadow-lg);
}

.header-content {
    text-align: center;
    margin-bottom: 2rem;
}

.form-title {
    color: white;
    font-size: 2.25rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.form-subtitle {
    font-size: 1.125rem;
    opacity: 0.9;
}

/* Indicateur de progression */
.progress-indicator {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 2rem;
}

.progress-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    opacity: 0.6;
    transition: all 0.3s ease;
}

.progress-step.active {
    opacity: 1;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--white);
    color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    box-shadow: var(--shadow);
}

.progress-step.active .step-number {
    background: var(--white);
    color: var(--primary-color);
}

/* Sections du formulaire */
.form-section {
    border: none;
    background: var(--white);
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--shadow);
    border-left: 4px solid var(--primary-color);
}

.section-legend {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--primary-color);
    padding: 0 1rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Grille du formulaire */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.input-group {
    position: relative;
}

.input-group.full-width {
    grid-column: 1 / -1;
}

.input-label {
    display: block;
    font-weight: 500;
    color: var(--gray-700);
    margin-bottom: 0.5rem;
}

.required {
    color: var(--error);
}

/* Champs de formulaire */
.modern-input, .modern-select, .modern-textarea {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid var(--gray-200);
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: var(--white);
}

.modern-input:focus, .modern-select:focus, .modern-textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(25, 53, 97, 0.1);
}

.modern-input:hover, .modern-select:hover {
    border-color: var(--gray-300);
}

/* Input nombre */
.number-input {
    display: flex;
    align-items: center;
}

.number-btn {
    padding: 0.75rem 1rem;
    background: var(--gray-100);
    border: 2px solid var(--gray-200);
    color: var(--gray-600);
    cursor: pointer;
    transition: all 0.3s ease;
}

.number-btn:hover {
    background: var(--gray-200);
}

.number-btn:first-child {
    border-radius: 8px 0 0 8px;
    border-right: none;
}

.number-btn:last-child {
    border-radius: 0 8px 8px 0;
    border-left: none;
}

.number-input .modern-input {
    border-radius: 0;
    border-left: none;
    border-right: none;
    text-align: center;
}

/* Zone de téléchargement */
.upload-area {
    border: 2px dashed var(--gray-300);
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    background: var(--gray-50);
}

.upload-area:hover {
    border-color: var(--primary-color);
    background: var(--gray-100);
}

.upload-area.dragover {
    border-color: var(--primary-color);
    background: rgba(25, 53, 97, 0.05);
}

.upload-content h3 {
    color: var(--gray-700);
    margin-bottom: 0.5rem;
}

.upload-content p {
    color: var(--gray-500);
}

.browse-link {
    color: var(--primary-color);
    text-decoration: underline;
    cursor: pointer;
}

.file-input {
    display: none;
}

.upload-preview {
    margin-top: 1rem;
    display: none;
}

/* Boutons */
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

.btn-primary, .btn-secondary {
    padding: 0.875rem 2rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: var(--primary-color);
    color: var(--white);
}

.btn-primary:hover {
    background: var(--primary-light);
    transform: translateY(-2px);
    box-shadow: var(--shadow-lg);
}

.btn-secondary {
    background: var(--gray-100);
    color: var(--gray-700);
    border: 2px solid var(--gray-200);
}

.btn-secondary:hover {
    background: var(--gray-200);
}

/* Icônes (simplifiées) */
.icon-building::before { content: "🏢"; }
.icon-user::before { content: "👤"; }
.icon-calendar::before { content: "📅"; }
.icon-file::before { content: "📄"; }
.icon-attachment::before { content: "📎"; }
.icon-company::before { content: "💼"; }
.icon-phone::before { content: "📞"; }
.icon-email::before { content: "✉️"; }
.icon-location::before { content: "📍"; }
.icon-briefcase::before { content: "💼"; }
.icon-chevron::before { content: "⌄"; }
.icon-upload::before { content: "📤"; }
.icon-reset::before { content: "🔄"; }
.icon-send::before { content: "🚀"; }

.input-icon {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
}

/* Responsive */
@media (max-width: 768px) {
    .form-container {
        padding: 1rem;
    }
    
    .form-header {
        padding: 1.5rem;
    }
    
    .progress-indicator {
        flex-direction: column;
        gap: 1rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn-primary, .btn-secondary {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
// Gestion du nombre de personnes
function incrementPersons() {
    const input = document.getElementById('nbre_perso');
    input.value = parseInt(input.value) + 1;
}

function decrementPersons() {
    const input = document.getElementById('nbre_perso');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

// Gestion de la date minimum
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date_visite');
    const today = new Date().toISOString().split('T')[0];
    dateInput.min = today;
    
    // Gestion du drag and drop
    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('documents_joints');
    
    uploadArea.addEventListener('click', () => fileInput.click());
    
    uploadArea.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });
    
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('dragover');
    });
    
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        fileInput.files = e.dataTransfer.files;
        updateFilePreview();
    });
    
    fileInput.addEventListener('change', updateFilePreview);
});

function updateFilePreview() {
    const files = document.getElementById('documents_joints').files;
    const preview = document.getElementById('uploadPreview');
    
    if (files.length > 0) {
        preview.style.display = 'block';
        preview.innerHTML = `<p>${files.length} fichier(s) sélectionné(s)</p>`;
    } else {
        preview.style.display = 'none';
    }
}

function resetForm() {
    document.getElementById('demandeForm').reset();
    document.getElementById('uploadPreview').style.display = 'none';
}

// Soumission du formulaire
document.getElementById('demandeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Ici vous ajouterez la logique de soumission
    alert('Formulaire soumis avec succès!');
});
</script>
@endsection