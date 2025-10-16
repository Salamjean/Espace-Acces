@extends('admin.layouts.template')
@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<div class="container-fluid" style="margin-top: 20px; background-color:white">
    <div class="row justify-content-center">
        <div class="col-8 col-lg-8">
            <div class="card shadow-sm border-0" style="border-radius: 20px; overflow: hidden;">
                <div class="card-header py-4" style="background: linear-gradient(135deg, #193561 0%, #2c4b8a 100%); border: none;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                <i class="bi bi-qr-code-scan text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h3 class="card-title mb-0 text-white">Code QR Généré</h3>
                                <p class="mb-0 text-white opacity-75">Votre code d'accès est prêt</p>
                            </div>
                        </div>
                        <div class="badge bg-success px-3 py-2">
                            <i class="bi bi-check-circle me-1"></i>Actif
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-5"  style="background-color: #e9eaef">
                    @if(session('success'))
                        <div class="alert alert-success-modern alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger-modern alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    <div class="row">
                        <!-- Colonne QR Code -->
                        <div class="col-md-6">
                            <div class="qr-code-container text-center">
                                <div class="qr-code-wrapper mb-4">
                                    @if($codeAcces->qr_code_path)
                                        <img src="{{ Storage::url($codeAcces->qr_code_path) }}" 
                                             alt="QR Code {{ $codeAcces->nom_porte }}" 
                                             class="qr-code-image shadow">
                                    @else
                                        <div class="qr-code-placeholder">
                                            <i class="bi bi-qr-code" style="font-size: 4rem; color: #ccc;"></i>
                                            <p class="text-muted mt-2">QR Code non disponible</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="qr-actions d-flex gap-2 justify-content-center">
                                    @if($codeAcces->qr_code_path)
                                        <a href="{{ route('admin.code.download', $codeAcces->id) }}" 
                                           class="btn btn-info-modern btn-sm">
                                            <i class="bi bi-download me-2"></i>Télécharger
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Colonne Informations -->
                        <div class="col-md-6">
                            <div class="info-section">
                                <h5 class="section-title mb-4">
                                    <i class="bi bi-info-circle me-2"></i>Détails du Code
                                </h5>
                                
                                <div class="info-grid">
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-door-closed me-2"></i>Porte
                                        </div>
                                        <div class="info-value">{{ $codeAcces->nom_porte }}</div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-arrow-left-right me-2"></i>Type
                                        </div>
                                        <div class="info-value">
                                            @if($codeAcces->type == 'entree')
                                                <span class="badge badge-entree">🟢 Entrée</span>
                                            @else
                                                <span class="badge badge-sortie">🔴 Sortie</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-key me-2"></i>Code Unique
                                        </div>
                                        <div class="info-value">
                                            <code class="code-unique">{{ $codeAcces->code_unique }}</code>
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-clock me-2"></i>Statut
                                        </div>
                                        <div class="info-value">
                                            @if($codeAcces->estValide())
                                                <span class="badge badge-success">Actif</span>
                                            @else
                                                <span class="badge badge-danger">Inactif</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-calendar me-2"></i>Expiration
                                        </div>
                                        <div class="info-value">
                                            @if($codeAcces->expire_at)
                                                {{ $codeAcces->expire_at->format('d/m/Y H:i') }}
                                            @else
                                                <span class="text-muted">Illimité</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="info-item">
                                        <div class="info-label">
                                            <i class="bi bi-calendar-plus me-2"></i>Créé le
                                        </div>
                                        <div class="info-value">
                                            {{ $codeAcces->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="action-section mt-5 pt-4 border-top">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <a href="{{ route('admin.code.create') }}" class="btn btn-success-modern w-100">
                                    <i class="bi bi-plus-circle me-2"></i>Créer un autre code
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('admin.code.index') }}" class="btn btn-danger-modern w-100">
                                    <i class="bi bi-list-ul me-2"></i>Voir tous les codes
                                </a>
                            </div>
                            <div class="col-md-4">
                                <button onclick="shareCode()" class="btn btn-info-modern w-100">
                                    <i class="bi bi-share me-2"></i>Partager
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles spécifiques pour la page show */
.qr-code-container {
    padding: 2rem;
    background: linear-gradient(135deg, #f8fafc 0%, #eef2f7 100%);
    border-radius: 20px;
    border: 2px dashed #e2e8f0;
}

.qr-code-wrapper {
    padding: 1rem;
    background: white;
    border-radius: 15px;
    display: inline-block;
}

.qr-code-image {
    max-width: 300px;
    height: auto;
    border-radius: 10px;
    transition: transform 0.3s ease;
}

.qr-code-image:hover {
    transform: scale(1.05);
}

.qr-code-placeholder {
    padding: 3rem;
    background: white;
    border-radius: 15px;
    border: 2px dashed #e2e8f0;
}

.info-section {
    padding: 1rem;
}

.info-grid {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #f8fafc;
    border-radius: 10px;
    border-left: 4px solid #193561;
}

.info-label {
    font-weight: 600;
    color: #193561;
    display: flex;
    align-items: center;
}

.info-value {
    font-weight: 500;
    color: #374151;
}

.code-unique {
    background: #193561;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
}

.badge-entree {
    background: #10b981;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 500;
}

.badge-sortie {
    background: #ef4444;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 500;
}

.alert-success-modern {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    border: none;
    border-radius: 12px;
    border-left: 4px solid #047857;
}

.alert-danger-modern {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    border: none;
    border-radius: 12px;
    border-left: 4px solid #b91c1c;
}

.btn-success-modern {
    background: linear-gradient(135deg, #198754 0%, #198754 100%);
    border: none;
    color: white;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
}
.btn-danger-modern {
    background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
    border: none;
    color: white;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
}
.btn-info-modern {
    background: linear-gradient(135deg, #223f74 0%, #223f74 100%);
    border: none;
    color: white;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
}

.btn-info-modern:hover {
    background: linear-gradient(135deg, #0891b2 0%, #0e7490 100%);
    color: white;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .qr-code-image {
        max-width: 250px;
    }
}
</style>

<script>
function shareCode() {
    const code = '{{ $codeAcces->code_unique }}';
    const porte = '{{ $codeAcces->nom_porte }}';
    const type = '{{ $codeAcces->type == 'entree' ? 'Entrée' : 'Sortie' }}';
    
    const text = `Code d'accès ${type} - ${porte}\nCode: ${code}\nGénéré le: {{ $codeAcces->created_at->format('d/m/Y H:i') }}`;
    
    if (navigator.share) {
        navigator.share({
            title: `Code d'Accès ${porte}`,
            text: text,
            url: window.location.href
        });
    } else {
        // Fallback pour les navigateurs qui ne supportent pas l'API Share
        navigator.clipboard.writeText(text).then(() => {
            alert('Code copié dans le presse-papiers!');
        });
    }
}
</script>

@endsection