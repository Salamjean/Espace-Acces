@extends('admin.layouts.template')
@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<div class="container-fluid" style="margin-top:10px">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border: none; border-radius: 15px; background: linear-gradient(135deg, #193561 0%, #2c4b8a 100%);">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1 text-white">
                                <i class="bi bi-person-plus me-2"></i>Nouveau Personnel Permanent
                            </h4>
                            <p class="mb-0 text-white opacity-75">Créer un accès permanent de 3 mois</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('admin.permanent-personnel.index') }}" class="btn btn-light">
                                <i class="bi bi-arrow-left me-2"></i>Retour à la liste
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0"><i class="bi bi-person-badge me-2"></i>Informations du Personnel</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.permanent-personnel.store') }}" method="POST" enctype="multipart/form-data" id="personnelForm">
                        @csrf
                        <div class="row" style="display: flex;justify-content:center">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-bold text-center" style="text-align: center">Photo de Profil</label>
                            <div class="upload-section">
                                <div class="upload-area" id="profileUploadArea">
                                    <i class="bi bi-person-circle upload-icon"></i>
                                    <p class="mb-1">Photo de profil</p>
                                    <small class="text-muted">Cliquez ou glissez-déposez (optionnel)</small>
                                    <input type="file" class="file-input" name="profile_picture" accept="image/*">
                                </div>
                                <div class="image-preview d-none" id="profilePreview">
                                    <img id="profilePreviewImg" src="#" alt="Photo de profil">
                                    <button type="button" class="btn-remove" onclick="removeImage('profile')">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </div>
                            </div>
                            @error('profile_picture')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Format recommandé : JPEG, PNG, JPG - Max 2MB</small>
                        </div>
                    </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nom *</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" required
                                       style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px;">
                                @error('name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Prénom *</label>
                                <input type="text" class="form-control" name="prenom" value="{{ old('prenom') }}" required
                                       style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px;">
                                @error('prenom')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Email *</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" required
                                       style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px;">
                                @error('email')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Contact *</label>
                                <input type="text" class="form-control" name="contact" value="{{ old('contact') }}" required
                                       style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px;">
                                @error('contact')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Fonction *</label>
                                <input type="text" class="form-control" name="fonction" value="{{ old('fonction') }}" required
                                       style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px;">
                                @error('fonction')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Structure *</label>
                                <input type="text" class="form-control" name="structure" value="{{ old('structure') }}" required
                                       style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px;">
                                @error('structure')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Adresse *</label>
                                <textarea class="form-control" name="adresse" rows="2" required
                                          style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px;">{{ old('adresse') }}</textarea>
                                @error('adresse')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3"><i class="bi bi-card-checklist me-2"></i>Pièce d'Identité</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Type de pièce *</label>
                                <select class="form-control" name="type_piece" required
                                        style="border-radius: 8px; border: 2px solid #e9ecef;">
                                    <option value="">Sélectionner...</option>
                                    <option value="CNI" {{ old('type_piece') == 'CNI' ? 'selected' : '' }}>Carte Nationale d'Identité</option>
                                    <option value="PASSEPORT" {{ old('type_piece') == 'PASSEPORT' ? 'selected' : '' }}>Passeport</option>
                                    <option value="PERMIS" {{ old('type_piece') == 'PERMIS' ? 'selected' : '' }}>Permis de Conduire</option>
                                    <option value="CARTE_SEJOUR" {{ old('type_piece') == 'CARTE_SEJOUR' ? 'selected' : '' }}>Carte de Séjour</option>
                                </select>
                                @error('type_piece')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Numéro de pièce *</label>
                                <input type="text" class="form-control" name="numero_piece" value="{{ old('numero_piece') }}" required
                                       style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px;">
                                @error('numero_piece')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Photo Recto *</label>
                                <div class="upload-section">
                                    <div class="upload-area" id="rectoUploadArea">
                                        <i class="bi bi-cloud-arrow-up upload-icon"></i>
                                        <p class="mb-1">Recto de la pièce</p>
                                        <small class="text-muted">Cliquez ou glissez-déposez</small>
                                        <input type="file" class="file-input" name="photo_recto" accept="image/*" required>
                                    </div>
                                    <div class="image-preview d-none" id="rectoPreview">
                                        <img id="rectoPreviewImg" src="#" alt="Recto">
                                        <button type="button" class="btn-remove" onclick="removeImage('recto')">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('photo_recto')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold">Photo Verso *</label>
                                <div class="upload-section">
                                    <div class="upload-area" id="versoUploadArea">
                                        <i class="bi bi-cloud-arrow-up upload-icon"></i>
                                        <p class="mb-1">Verso de la pièce</p>
                                        <small class="text-muted">Cliquez ou glissez-déposez</small>
                                        <input type="file" class="file-input" name="photo_verso" accept="image/*" required>
                                    </div>
                                    <div class="image-preview d-none" id="versoPreview">
                                        <img id="versoPreviewImg" src="#" alt="Verso">
                                        <button type="button" class="btn-remove" onclick="removeImage('verso')">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </div>
                                </div>
                                @error('photo_verso')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-12 mb-4">
                                <label class="form-label fw-bold">Motif de l'accès permanent *</label>
                                <textarea class="form-control" name="motif_acces_permanent" rows="3" required
                                          style="border-radius: 8px; border: 2px solid #e9ecef; padding: 12px;"
                                          placeholder="Décrivez le motif de l'accès permanent...">{{ old('motif_acces_permanent') }}</textarea>
                                @error('motif_acces_permanent')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Information :</strong> Un code d'accès unique et un QR Code seront générés automatiquement. 
                            L'accès sera valable pour une durée de 3 mois à partir de la date de création.
                        </div>

                        <div class="row mt-4">
                            <div class="col-6">
                                <a href="{{ route('admin.permanent-personnel.index') }}" class="btn btn-outline-secondary"
                                   style="border-radius: 8px; padding: 12px 30px;">
                                    <i class="bi bi-arrow-left me-2"></i>Annuler
                                </a>
                            </div>
                            <div class="col-6 text-end">
                                <button type="submit" class="btn btn-primary"
                                        style="background: #193561; border: none; border-radius: 8px; padding: 12px 30px;">
                                    <i class="bi bi-check-lg me-2"></i>Créer le Personnel
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function setupImageUpload(uploadAreaId, previewId, previewImgId, fileInputName) {
    const uploadArea = document.getElementById(uploadAreaId);
    const preview = document.getElementById(previewId);
    const previewImg = document.getElementById(previewImgId);
    const fileInput = document.querySelector(`input[name="${fileInputName}"]`);

    if (!uploadArea || !preview || !previewImg || !fileInput) return;

    // Click sur la zone d'upload
    uploadArea.addEventListener('click', () => fileInput.click());

    // Changement de fichier
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Vérifier la taille du fichier (max 2MB)
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

    // Drag and drop
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
        document.querySelector('input[name="photo_recto"]').value = '';
        document.getElementById('rectoPreview').classList.add('d-none');
        document.getElementById('rectoUploadArea').style.display = 'flex';
    } else {
        document.querySelector('input[name="photo_verso"]').value = '';
        document.getElementById('versoPreview').classList.add('d-none');
        document.getElementById('versoUploadArea').style.display = 'flex';
    }
}

// Initialisation
$(document).ready(function() {
    setupImageUpload('rectoUploadArea', 'rectoPreview', 'rectoPreviewImg', 'photo_recto');
    setupImageUpload('versoUploadArea', 'versoPreview', 'versoPreviewImg', 'photo_verso');
});

// Initialisation
$(document).ready(function() {
    setupImageUpload('rectoUploadArea', 'rectoPreview', 'rectoPreviewImg', 'photo_recto');
    setupImageUpload('versoUploadArea', 'versoPreview', 'versoPreviewImg', 'photo_verso');
    setupImageUpload('profileUploadArea', 'profilePreview', 'profilePreviewImg', 'profile_picture'); // Ajouté
});

// Mettez à jour la fonction removeImage
function removeImage(type) {
    if (type === 'recto') {
        document.querySelector('input[name="photo_recto"]').value = '';
        document.getElementById('rectoPreview').classList.add('d-none');
        document.getElementById('rectoUploadArea').style.display = 'flex';
    } else if (type === 'verso') {
        document.querySelector('input[name="photo_verso"]').value = '';
        document.getElementById('versoPreview').classList.add('d-none');
        document.getElementById('versoUploadArea').style.display = 'flex';
    } else if (type === 'profile') {
        document.querySelector('input[name="profile_picture"]').value = '';
        document.getElementById('profilePreview').classList.add('d-none');
        document.getElementById('profileUploadArea').style.display = 'flex';
    }
}
</script>

<style>
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

.form-control:focus, .form-select:focus {
    border-color: #193561 !important;
    box-shadow: 0 0 0 0.2rem rgba(25, 53, 97, 0.25) !important;
}

.card {
    border-radius: 12px;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

#profileUploadArea {
    border-color: #28a745;
}

#profileUploadArea:hover, #profileUploadArea.highlight {
    border-color: #218838;
    background: rgba(40, 167, 69, 0.05);
}
</style>
@endsection