@extends('admin.layouts.template')
@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<div class="container-fluid" style="margin-top: 20px; background-color:white">
    <div class="row">
        <div class="col-12">
            <!-- En-tête de la page -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="page-title">
                            <i class="bi bi-qr-code me-3"></i>Liste des Codes QR
                        </h1>
                        <p class="page-subtitle">Gérez tous vos codes d'accès générés</p>
                    </div>
                    <a href="{{ route('admin.code.create') }}" class="btn btn-primary-modern">
                        <i class="bi bi-plus-circle me-2"></i>Nouveau Code QR
                    </a>
                </div>
            </div>

            <!-- Cartes de Statistiques -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stat-card total-codes">
                        <div class="stat-icon">
                            <i class="bi bi-qr-code"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">{{ $codes->count() }}</div>
                            <div class="stat-label">Total des Codes</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stat-card active-codes">
                        <div class="stat-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">{{ $codes->where('est_actif', true)->where(function($q) { return $q->whereNull('expire_at')->orWhere('expire_at', '>', now()); })->count() }}</div>
                            <div class="stat-label">Codes Actifs</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stat-card entry-codes">
                        <div class="stat-icon">
                            <i class="bi bi-box-arrow-in-right"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">{{ $codes->where('type', 'entree')->count() }}</div>
                            <div class="stat-label">Codes Entrée</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stat-card exit-codes">
                        <div class="stat-icon">
                            <i class="bi bi-box-arrow-right"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">{{ $codes->where('type', 'sortie')->count() }}</div>
                            <div class="stat-label">Codes Sortie</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte Principale -->
            <div class="card shadow-sm border-0" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #193561 0%, #2c4b8a 100%); border: none;">
                    <div class="d-flex justify-content-between align-items-center" >
                        <h5 class="card-title mb-0 text-white">
                            <i class="bi bi-list-ul me-2"></i>Tous les Codes d'Accès
                        </h5>
                        <div class="header-actions" >
                            <button class="btn btn-light btn-sm me-2" onclick="window.print()">
                                <i class="bi bi-printer me-1"></i>Imprimer
                            </button>
                            <div class="btn-group" >
                                <button type="button" class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="bi bi-filter me-1"></i>Filtrer
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item filter-option" href="#" data-filter="all">Tous</a></li>
                                    <li><a class="dropdown-item filter-option" href="#" data-filter="active">Actifs</a></li>
                                    <li><a class="dropdown-item filter-option" href="#" data-filter="expired">Expirés</a></li>
                                    <li><a class="dropdown-item filter-option" href="#" data-filter="entree">Entrées</a></li>
                                    <li><a class="dropdown-item filter-option" href="#" data-filter="sortie">Sorties</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4" style="background-color: #e9eaef">
                    @if($codes->isEmpty())
                        <div class="empty-state text-center py-5">
                            <div class="empty-icon mb-4">
                                <i class="bi bi-qr-code" style="font-size: 4rem; color: #cbd5e0;"></i>
                            </div>
                            <h4 class="empty-title">Aucun code généré</h4>
                            <p class="empty-text text-muted mb-4">
                                Commencez par créer votre premier code QR d'accès
                            </p>
                            <a href="{{ route('admin.code.create') }}" class="btn btn-primary-modern">
                                <i class="bi bi-plus-circle me-2"></i>Créer un Code QR
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-modern" id="codesTable">
                                <thead>
                                    <tr>
                                        <th class="text-center">QR Code</th>
                                        <th class="text-center">Porte</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Code Unique</th>
                                        <th class="text-center">Statut</th>
                                        <th class="text-center">Expiration</th>
                                        <th class="text-center">Créé le</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($codes as $code)
                                    <tr class="code-row" data-type="{{ $code->type }}" data-status="{{ $code->estValide() ? 'active' : 'expired' }}">
                                        <td class="text-center">
                                            <div class="qr-preview">
                                                @if($code->qr_code_path)
                                                    <img src="{{ Storage::url($code->qr_code_path) }}" 
                                                         alt="QR Code" 
                                                         class="qr-thumbnail"
                                                         data-bs-toggle="tooltip" 
                                                         data-bs-title="Cliquer pour agrandir"
                                                         onclick="showQrModal('{{ Storage::url($code->qr_code_path) }}', '{{ $code->nom_porte }}')">
                                                @else
                                                    <div class="qr-placeholder">
                                                        <i class="bi bi-qr-code"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="porte-info">
                                                <strong>{{ $code->nom_porte }}</strong>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            @if($code->type == 'entree')
                                                <span class="badge badge-entree">
                                                    <i class="bi bi-box-arrow-in-right me-1"></i>Entrée
                                                </span>
                                            @else
                                                <span class="badge badge-sortie">
                                                    <i class="bi bi-box-arrow-right me-1"></i>Sortie
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <code class="code-unique" data-bs-toggle="tooltip" data-bs-title="Cliquer pour copier" onclick="copyToClipboard('{{ $code->code_unique }}')">
                                                {{ $code->code_unique }}
                                            </code>
                                        </td>
                                        <td class="text-center">
                                            @if($code->estValide())
                                                <span class="badge badge-success">
                                                    <i class="bi bi-check-circle me-1"></i>Actif
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    <i class="bi bi-x-circle me-1"></i>Inactif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($code->expire_at)
                                                <div class="expiration-info">
                                                    <div>{{ $code->expire_at->format('d/m/Y H:i') }}</div>
                                                    <small class="text-muted">
                                                        @if($code->expire_at->isFuture())
                                                            Expire dans {{ $code->expire_at->diffForHumans() }}
                                                        @else
                                                            Expiré {{ $code->expire_at->diffForHumans() }}
                                                        @endif
                                                    </small>
                                                </div>
                                            @else
                                                <span class="text-success">
                                                    <i class="bi bi-infinity me-1"></i>Illimité
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="created-info">
                                                <div>{{ $code->created_at->format('d/m/Y') }}</div>
                                                <small class="text-muted">{{ $code->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.code.show', $code->id) }}" 
                                                   class="btn btn-action btn-view" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-title="Voir détails">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.code.download', $code->id) }}" 
                                                   class="btn btn-action btn-download"
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-title="Télécharger QR">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                <button class="btn btn-action btn-share"
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-title="Partager"
                                                        onclick="shareCode('{{ $code->code_unique }}', '{{ $code->nom_porte }}', '{{ $code->type }}')">
                                                    <i class="bi bi-share"></i>
                                                </button>
                                                <button class="btn btn-action btn-delete"
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-title="Supprimer"
                                                        onclick="confirmDelete({{ $code->id }}, '{{ $code->nom_porte }}', '{{ $code->code_unique }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($codes->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="pagination-info">
                                Affichage de {{ $codes->firstItem() }} à {{ $codes->lastItem() }} sur {{ $codes->total() }} codes
                            </div>
                            <nav>
                                {{ $codes->links() }}
                            </nav>
                        </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher le QR code en grand -->
<div class="modal fade" id="qrModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrModalTitle">QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalQrImage" src="" alt="QR Code" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary-modern" onclick="downloadModalQr()">Télécharger</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles pour la page de liste */
.page-header {
    padding: 2rem 0;
}

.page-title {
    color: #193561;
    font-weight: 700;
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.btn-delete {
    background: #ef4444;
    color: white;
}

.btn-delete:hover {
    background: #dc2626;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.page-subtitle {
    color: #6b7280;
    font-size: 1.1rem;
    margin: 0;
}

/* Cartes de statistiques */
.stat-card {
    padding: 1.5rem;
    border-radius: 15px;
    display: flex;
    align-items: center;
    gap: 1rem;
    color: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card.total-codes { background: linear-gradient(135deg, #193561 0%, #2c4b8a 100%); }
.stat-card.active-codes { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
.stat-card.entry-codes { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
.stat-card.exit-codes { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }

.stat-icon {
    width: 60px;
    height: 60px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
}

.stat-content {
    flex: 1;
}

.stat-number {
    font-size: 2.2rem;
    font-weight: bold;
    line-height: 1;
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
    font-weight: 500;
}

/* Table moderne */
.table-modern {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.table-modern th {
    background: #f8fafc;
    color: #193561;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 1rem 0.75rem;
    border-bottom: 2px solid #e2e8f0;
}

.table-modern td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    background: white;
}

.code-row:hover td {
    background: #f8fafc;
    transform: scale(1.02);
    transition: all 0.2s ease;
}

/* QR Code miniature */
.qr-preview {
    display: inline-block;
}

.qr-thumbnail {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    cursor: pointer;
    transition: transform 0.3s ease;
    border: 2px solid #e2e8f0;
}

.qr-thumbnail:hover {
    transform: scale(1.1);
    border-color: #193561;
}

.qr-placeholder {
    width: 60px;
    height: 60px;
    background: #f8fafc;
    border: 2px dashed #cbd5e0;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 1.5rem;
}

/* Badges */
.badge-entree {
    background: #10b981;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.badge-sortie {
    background: #ef4444;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.badge-success {
    background: #10b981;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

.badge-danger {
    background: #ef4444;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
}

/* Code unique */
.code-unique {
    background: #193561;
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-family: 'Courier New', monospace;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.code-unique:hover {
    background: #2c4b8a;
    transform: scale(1.05);
}

/* Boutons d'action */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.btn-action {
    width: 35px;
    height: 35px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    transition: all 0.3s ease;
}

.btn-view {
    background: #3b82f6;
    color: white;
}

.btn-download {
    background: #10b981;
    color: white;
}

.btn-share {
    background: #8b5cf6;
    color: white;
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* État vide */
.empty-state {
    padding: 4rem 2rem;
}

.empty-icon {
    color: #cbd5e0;
}

.empty-title {
    color: #193561;
    font-weight: 600;
    margin-bottom: 1rem;
}

.empty-text {
    font-size: 1.1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .stat-card {
        padding: 1rem;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }
    
    .stat-number {
        font-size: 1.8rem;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .btn-action {
        width: 30px;
        height: 30px;
        font-size: 0.8rem;
    }
}

/* Animation des lignes */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.code-row {
    animation: fadeIn 0.5s ease;
}

/* Filtres */
.filter-option.active {
    background: #193561;
    color: white;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips
    const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltips.forEach(tooltip => {
        new bootstrap.Tooltip(tooltip);
    });

    // Filtrage des codes
    const filterOptions = document.querySelectorAll('.filter-option');
    const codeRows = document.querySelectorAll('.code-row');

    filterOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Mettre à jour l'option active
            filterOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            
            codeRows.forEach(row => {
                if (filter === 'all') {
                    row.style.display = '';
                } else if (filter === 'active') {
                    row.style.display = row.dataset.status === 'active' ? '' : 'none';
                } else if (filter === 'expired') {
                    row.style.display = row.dataset.status === 'expired' ? '' : 'none';
                } else if (filter === 'entree') {
                    row.style.display = row.dataset.type === 'entree' ? '' : 'none';
                } else if (filter === 'sortie') {
                    row.style.display = row.dataset.type === 'sortie' ? '' : 'none';
                }
            });
        });
    });
});

// Fonction pour afficher le QR code en modal
function showQrModal(imageSrc, porteName) {
    document.getElementById('modalQrImage').src = imageSrc;
    document.getElementById('qrModalTitle').textContent = `QR Code - ${porteName}`;
    const modal = new bootstrap.Modal(document.getElementById('qrModal'));
    modal.show();
}

// Fonction pour copier le code dans le presse-papiers
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        // Afficher une notification
        const toast = document.createElement('div');
        toast.className = 'alert alert-success position-fixed top-0 end-0 m-3';
        toast.style.zIndex = '1060';
        toast.innerHTML = `<i class="bi bi-check-circle me-2"></i>Code copié: ${text}`;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    });
}

// Fonction pour partager un code
function shareCode(code, porte, type) {
    const text = `Code d'accès ${type === 'entree' ? 'Entrée' : 'Sortie'} - ${porte}\nCode: ${code}`;
    
    if (navigator.share) {
        navigator.share({
            title: `Code d'Accès ${porte}`,
            text: text
        });
    } else {
        copyToClipboard(text);
    }
}

// Fonction pour télécharger le QR code du modal
function downloadModalQr() {
    const qrImage = document.getElementById('modalQrImage');
    const link = document.createElement('a');
    link.href = qrImage.src;
    link.download = `qr_code_${document.getElementById('qrModalTitle').textContent.replace('QR Code - ', '')}.png`;
    link.click();
}

// Fonction pour confirmer la suppression
function confirmDelete(codeId, porteName, codeUnique) {
    Swal.fire({
        title: 'Supprimer le Code QR',
        html: `
            <div class="confirmation-content">
                <div class="confirmation-icon text-danger">
                    <i class="bi bi-exclamation-triangle fa-3x"></i>
                </div>
                <div class="confirmation-text">
                    <p>Êtes-vous sûr de vouloir <strong>supprimer définitivement</strong> le code :</p>
                    <h4>"${porteName}"</h4>
                    <p><strong>Code:</strong> ${codeUnique}</p>
                    <div class="confirmation-warning">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        <strong>Attention :</strong> Cette action est irréversible. Le QR code sera supprimé du système.
                    </div>
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler',
        reverseButtons: true,
        customClass: {
            popup: 'custom-swal danger-swal'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Envoyer la requête de suppression
            deleteCode(codeId);
        }
    });
}

// Fonction pour supprimer le code
function deleteCode(codeId) {
    // Afficher un loader
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });

    Toast.fire({
        icon: 'info',
        title: 'Suppression en cours...'
    });

    // Envoyer la requête AJAX
    fetch(`/admin/code/code-acces/${codeId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erreur réseau');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Supprimé!',
                html: `
                    <div class="success-content">
                        <i class="bi bi-check-circle success-icon"></i>
                        <h4>Code supprimé</h4>
                        <p>Le code a été supprimé avec succès.</p>
                    </div>
                `,
                confirmButtonColor: '#10b981',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Recharger la page ou supprimer la ligne
                window.location.reload();
            });
        } else {
            throw new Error(data.message || 'Une erreur est survenue');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: error.message || 'Une erreur est survenue lors de la suppression.',
            confirmButtonColor: '#ef4444'
        });
    });
}
</script>

@endsection