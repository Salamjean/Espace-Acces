@extends('admin.layouts.template')
@section('content')

<div class="container-fluid">
    <!-- Header avec navigation -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-title">
                <h1 class="page-title">
                    <i class="fas fa-user icon-primary"></i>
                    Enregistrer un Nouveau Demandeur
                </h1>
                <p class="page-subtitle">Remplissez les informations ci-dessous pour créer un nouveau compte demandeur</p>
            </div>
            <a href="{{ route('demandeur.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Retour à la liste
            </a>
        </div>
    </div>

    <!-- Formulaire principal -->
    <div class="form-container">
        <div class="form-card">
            <!-- En-tête du formulaire -->
            <div class="form-header">
                <div class="form-header-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="form-header-text">
                    <h2>Informations du Demandeur</h2>
                    <p>Tous les champs marqués d'un * sont obligatoires</p>
                </div>
            </div>

            <!-- Formulaire -->
            <form id="demandeurForm" action="{{ route('demandeur.store') }}" method="POST" enctype="multipart/form-data" class="modern-form">
                @csrf
                
                <div class="form-grid">
                    <!-- Colonne gauche -->
                    <div class="form-column">
                        <!-- Nom -->
                        <div class="input-group modern-input">
                            <label for="name" class="input-label">
                                <i class="fas fa-user input-icon"></i>
                                Nom <span class="required">*</span>
                            </label>
                            <input type="text" class="form-input @error('name') error @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="Ex: KOUADIO" required>
                            @error('name')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Prénom -->
                        <div class="input-group modern-input">
                            <label for="prenom" class="input-label">
                                <i class="fas fa-user-tag input-icon"></i>
                                Prénom <span class="required">*</span>
                            </label>
                            <input type="text" class="form-input @error('prenom') error @enderror" 
                                   id="prenom" name="prenom" value="{{ old('prenom') }}" 
                                   placeholder="Ex: Jean" required>
                            @error('prenom')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="input-group modern-input">
                            <label for="email" class="input-label">
                                <i class="fas fa-envelope input-icon"></i>
                                Email <span class="required">*</span>
                            </label>
                            <input type="email" class="form-input @error('email') error @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" 
                                   placeholder="jean.kouadio@example.com" required>
                            @error('email')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact -->
                        <div class="input-group modern-input">
                            <label for="contact" class="input-label">
                                <i class="fas fa-phone input-icon"></i>
                                Contact <span class="required">*</span>
                            </label>
                            <input type="text" class="form-input @error('contact') error @enderror" 
                                   id="contact" name="contact" value="{{ old('contact') }}" 
                                   placeholder="+225 07 98 27 89 81" required>
                            @error('contact')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="input-group modern-input">
                            <label for="structure" class="input-label">
                                <i class="fas fa-home input-icon"></i>
                                Structure <span class="required">*</span>
                            </label>
                            <input type="text" class="form-input @error('structure') error @enderror" 
                                   id="structure" name="structure" value="{{ old('structure') }}" 
                                   placeholder="Entrez le nom de la structure du demandeur" required>
                            @error('structure')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Colonne droite -->
                    <div class="form-column">
                        <!-- Adresse -->
                        <div class="input-group modern-input">
                            <label for="adresse" class="input-label">
                                <i class="fas fa-map-marker-alt input-icon"></i>
                                Adresse <span class="required">*</span>
                            </label>
                            <input type="text" class="form-input @error('adresse') error @enderror" 
                                   id="adresse" name="adresse" value="{{ old('adresse') }}" 
                                   placeholder="123 rue galarie des parcs, 75008 Plateau" required>
                            @error('adresse')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fonction -->
                        <div class="input-group modern-input">
                            <label for="fonction" class="input-label">
                                <i class="fas fa-briefcase input-icon"></i>
                                Fonction <span class="required">*</span>
                            </label>
                            <input type="text" class="form-input @error('fonction') error @enderror" 
                                   id="fonction" name="fonction" value="{{ old('fonction') }}" 
                                   placeholder="Ex: Commercial, Développeur, etc." required>
                            @error('fonction')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Upload de la photo de profil -->
                        <div class="input-group modern-input">
                            <label class="input-label">
                                <i class="fas fa-image input-icon"></i>
                                Photo de profil
                            </label>
                            <div class="file-upload-container">
                                <input type="file" class="file-input @error('profile_picture') error @enderror" 
                                       id="profile_picture" name="profile_picture" accept="image/*">
                                <label for="profile_picture" class="file-upload-label">
                                    <div class="file-upload-content">
                                        <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                        <div class="upload-text">
                                            <span class="file-main-text">Cliquez pour uploader</span>
                                            <span class="file-sub-text">ou glissez-déposez</span>
                                        </div>
                                    </div>
                                    <span class="file-info" id="fileInfo">PNG, JPG, GIF jusqu'à 2MB</span>
                                </label>
                            </div>
                            @error('profile_picture')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Prévisualisation -->
                        <div class="image-preview-container" id="imagePreviewContainer">
                            <div class="preview-header">
                                <span>Aperçu de la photo</span>
                                <button type="button" class="preview-remove" onclick="removeImage()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="preview-content">
                                <img id="previewImg" class="preview-image">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Information sur le mot de passe -->
                <div class="info-card">
                    <div class="info-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="info-content">
                        <h4>Concernant le mot de passe</h4>
                        <p>Un mail sera envoyé au demandeur pour qu'il puisse définir ses accès et faire ses demandes.</p>
                    </div>
                </div>

                <!-- Actions du formulaire -->
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                        <i class="fas fa-eraser"></i>
                        Tout effacer
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i>
                        Créer le Demandeur
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Vérifier que le DOM est chargé
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de l'upload de fichier
    const profilePictureInput = document.getElementById('profile_picture');
    if (profilePictureInput) {
        profilePictureInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const fileInfo = document.getElementById('fileInfo');
            
            if (file) {
                fileInfo.textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
                
                // Prévisualisation
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreviewContainer').style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Drag and drop
    const fileUpload = document.querySelector('.file-upload-container');
    if (fileUpload) {
        fileUpload.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUpload.classList.add('dragover');
        });

        fileUpload.addEventListener('dragleave', () => {
            fileUpload.classList.remove('dragover');
        });

        fileUpload.addEventListener('drop', (e) => {
            e.preventDefault();
            fileUpload.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('profile_picture').files = files;
                document.getElementById('profile_picture').dispatchEvent(new Event('change'));
            }
        });
    }

    // Soumission du formulaire - VERSION CORRIGÉE
    const demandeurForm = document.getElementById('demandeurForm');
    if (demandeurForm) {
        demandeurForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            
            // Animation de chargement
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Création en cours...';
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json, text/html'
                }
            })
            .then(response => {
                const contentType = response.headers.get('content-type');
                
                if (contentType && contentType.includes('application/json')) {
                    return response.json().then(data => ({ type: 'json', data }));
                } else {
                    return response.text().then(html => ({ type: 'html', html }));
                }
            })
            .then(result => {
                if (result.type === 'json') {
                    if (result.data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès!',
                            html: `
                                <div class="success-content">
                                    <i class="fas fa-check-circle success-icon"></i>
                                    <h3>Demandeur créé avec succès!</h3>
                                    <p>Un email de confirmation a été envoyé à <strong>${document.getElementById('email').value}</strong></p>
                                </div>
                            `,
                            confirmButtonColor: '#1c3966',
                            confirmButtonText: 'Voir la liste des demandeurs'
                        }).then(() => {
                            window.location.href = result.data.redirect || "{{ route('demandeur.index') }}";
                        });
                    } else {
                        throw new Error(result.data.message || 'Une erreur est survenue');
                    }
                } else {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès!',
                        text: 'Demandeur créé avec succès!',
                        confirmButtonColor: '#1c3966',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = "{{ route('demandeur.index') }}";
                    });
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: error.message || 'Une erreur est survenue lors de la création',
                    confirmButtonColor: '#1c3966'
                });
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }

    // Validation en temps réel
    document.querySelectorAll('.form-input').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.hasAttribute('required') && !this.value.trim()) {
                this.classList.add('error');
            } else {
                this.classList.remove('error');
            }
        });
        
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('error');
            }
        });
    });
});

// Supprimer l'image
function removeImage() {
    document.getElementById('profile_picture').value = '';
    document.getElementById('fileInfo').textContent = 'PNG, JPG, GIF jusqu\'à 2MB';
    document.getElementById('imagePreviewContainer').style.display = 'none';
}

// Réinitialiser le formulaire
function resetForm() {
    Swal.fire({
        title: 'Réinitialiser le formulaire?',
        text: 'Toutes les données saisies seront perdues.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#1c3966',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Oui, réinitialiser',
        cancelButtonText: 'Annuler',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('demandeurForm').reset();
            removeImage();
            Swal.fire({
                icon: 'success',
                title: 'Formulaire réinitialisé',
                text: 'Tous les champs ont été vidés.',
                confirmButtonColor: '#1c3966',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}
</script>

<style>
/* Variables CSS */
:root {
    --primary-color: #1c3966;
    --primary-dark: #15294d;
    --primary-light: #2c5282;
    --secondary-color: #6c757d;
    --success-color: #28a745;
    --danger-color: #dc3545;
    --warning-color: #ffc107;
    --light-bg: #f8f9fa;
    --border-color: #e9ecef;
    --text-color: #333;
    --text-muted: #6c757d;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Reset et base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
    min-height: 100vh;
    color: var(--text-color);
}

/* En-tête de page */
.page-header {
    background: white;
    padding: 2rem 0;
    margin-bottom: 2rem;
    box-shadow: var(--shadow);
}

.header-content {
    width: 70%;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.icon-primary {
    color: var(--primary-color);
}

.page-subtitle {
    color: var(--text-muted);
    font-size: 1.1rem;
}

.back-btn {
    background: white;
    color: var(--primary-color);
    border: 2px solid var(--primary-color);
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

/* Conteneur principal */
.form-container {
    width: 70%;
    margin: 0 auto;
    padding: 0 2rem 3rem;
}

/* Carte du formulaire */
.form-card {
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
}

/* En-tête du formulaire */
.form-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
    color: white;
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.form-header-icon {
    background: rgba(255, 255, 255, 0.2);
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.form-header-text h2 {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.form-header-text p {
    opacity: 0.9;
    font-size: 0.9rem;
}

/* Grille du formulaire */
.modern-form {
    padding: 2rem;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .form-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
}

/* Groupes d'entrée */
.input-group {
    margin-bottom: 1.5rem;
}

.input-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.input-icon {
    color: var(--primary-color);
    width: 16px;
}

.required {
    color: var(--danger-color);
}

.form-input {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.form-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(28, 57, 102, 0.1);
    transform: translateY(-1px);
}

.form-input.error {
    border-color: var(--danger-color);
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
}

.error-message {
    color: var(--danger-color);
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

/* Upload de fichier */
.file-upload-container {
    position: relative;
}

.file-input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.file-upload-label {
    display: block;
    border: 2px dashed var(--border-color);
    border-radius: 12px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: var(--light-bg);
}

.file-upload-label:hover {
    border-color: var(--primary-color);
    background: rgba(28, 57, 102, 0.02);
}

.file-upload-container.dragover .file-upload-label {
    border-color: var(--primary-color);
    background: rgba(28, 57, 102, 0.05);
    transform: scale(1.02);
}

.file-upload-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.upload-icon {
    font-size: 2rem;
    color: var(--primary-color);
}

.file-main-text {
    font-weight: 600;
    color: var(--primary-color);
}

.file-sub-text {
    font-size: 0.9rem;
    color: var(--text-muted);
}

.file-info {
    font-size: 0.8rem;
    color: var(--text-muted);
}

/* Prévisualisation d'image */
.image-preview-container {
    display: none;
    background: var(--light-bg);
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid var(--border-color);
}

.preview-header {
    background: white;
    padding: 0.75rem 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--border-color);
    font-weight: 600;
    color: var(--primary-color);
}

.preview-remove {
    background: var(--danger-color);
    color: white;
    border: none;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.preview-remove:hover {
    background: #c82333;
    transform: scale(1.1);
}

.preview-content {
    padding: 1.5rem;
    display: flex;
    justify-content: center;
    align-items: center;
}

.preview-image {
    max-width: 200px;
    max-height: 150px;
    border-radius: 8px;
    box-shadow: var(--shadow);
}

/* Carte d'information */
.info-card {
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    border-left: 4px solid var(--primary-color);
    border-radius: 8px;
    padding: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin: 2rem 0;
}

.info-icon {
    color: var(--primary-color);
    font-size: 1.25rem;
    margin-top: 0.125rem;
}

.info-content h4 {
    color: var(--primary-color);
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.info-content p {
    color: var(--text-color);
    line-height: 1.5;
}

/* Actions du formulaire */
.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
}

@media (max-width: 576px) {
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
        justify-content: center;
    }
}

.btn {
    padding: 0.875rem 2rem;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

.btn-secondary {
    background: white;
    color: var(--secondary-color);
    border: 2px solid var(--border-color);
}

.btn-secondary:hover:not(:disabled) {
    background: var(--light-bg);
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

/* Style pour SweetAlert2 */
.success-content {
    text-align: center;
    padding: 1rem;
}

.success-icon {
    font-size: 3rem;
    color: var(--success-color);
    margin-bottom: 1rem;
}

.success-content h3 {
    color: var(--primary-color);
    margin-bottom: 1rem;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-card {
    animation: fadeIn 0.6s ease-out;
}

/* États de chargement */
.loading {
    position: relative;
    overflow: hidden;
}

.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { left: -100%; }
    100% { left: 100%; }
}
</style>

@endsection