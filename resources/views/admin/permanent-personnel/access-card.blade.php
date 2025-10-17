<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Carte d'Accès - {{ $personnel->prenom }} {{ $personnel->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="{{asset('assets/assets/img/kks.jpg')}}" />
    <style>
        @media print {
            body { margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .card-container { box-shadow: none !important; margin: 0 !important; }
            .page-break { page-break-before: always; }
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            min-height: 100vh;
        }
        
        .card-container {
            width: 105mm;
            height: 148mm;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            overflow: hidden;
            position: relative;
            margin: 0 auto;
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .card-front {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            height: 100%;
            padding: 25px;
            position: relative;
            overflow: hidden;
        }
        
        .card-front::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .card-back {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #2c3e50;
            height: 100%;
            padding: 25px;
            position: relative;
            overflow: hidden;
        }
        
        .card-back::before {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -30%;
            width: 80%;
            height: 80%;
            background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
            border-radius: 50%;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255,255,255,0.3);
            position: relative;
            z-index: 2;
        }
        
        .header h1 {
            font-size: 18px;
            margin: 0;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .header .subtitle {
            font-size: 11px;
            opacity: 0.9;
            margin-top: 8px;
            font-weight: 300;
            letter-spacing: 1px;
        }
        
         .photo-section {
            text-align: center;
            margin-bottom: 5px;
            margin-top: 5px;
            position: relative;
            z-index: 2;
        }
        
        .profile-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto;
            border: 4px solid rgba(255,255,255,0.4);
            overflow: hidden;
            background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.1) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            position: relative;
        }
        
        .profile-photo::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(135deg, #fff, #a8c0ff);
            border-radius: 50%;
            z-index: -1;
        }
        
        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        
        .profile-photo .placeholder {
            font-size: 32px;
            color: rgba(255,255,255,0.8);
        }
        
        .info-section {
            margin-bottom: 25px;
            position: relative;
            z-index: 2;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 12px;
            padding: 6px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }
        
        .info-row:hover {
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 6px 10px;
        }
        
        .info-label {
            font-weight: 700;
            font-size: 17px;
            opacity: 0.9;
            flex: 1;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        
        .info-value {
            text-align: right;
            flex: 2;
            font-size: 17px;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        }
        
        .qr-section {
            text-align: center;
            margin-top: 20px;
            padding: 20px;
            background: rgba(255,255,255,0.15);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 2;
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .qr-code {
            width: 110px;
            height: 110px;
            background: white;
            margin: 0 auto;
            padding: 8px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            border: 2px solid rgba(255,255,255,0.5);
        }
        
        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 6px;
        }
        
        .validity {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
            opacity: 0.9;
            font-weight: 600;
            position: relative;
            z-index: 2;
            background: rgba(255,255,255,0.1);
            padding: 8px;
            border-radius: 10px;
            backdrop-filter: blur(5px);
        }
        
        .barcode {
            text-align: center;
            margin-top: 15px;
            font-family: 'Libre Barcode 128', cursive;
            font-size: 24px;
            letter-spacing: 3px;
            color: #2c3e50;
            font-weight: bold;
            position: relative;
            z-index: 2;
        }
        
        .watermark {
            position: absolute;
            bottom: 15px;
            right: 140px;
            font-size: 9px;
            opacity: 0.7;
            z-index: 2;
            font-weight: 300;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }
        
        .logo-icon {
            font-size: 32px;
            color: #1e3c72;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }
        
        .back-header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(44, 62, 80, 0.3);
            position: relative;
            z-index: 2;
        }
        
        .back-header h2 {
            font-size: 18px;
            margin: 0;
            color: #2c3e50;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .instructions {
            font-size: 10px;
            text-align: center;
            margin-top: 15px;
            color: #34495e;
            line-height: 1.5;
            font-weight: 500;
            position: relative;
            z-index: 2;
            background: rgba(255,255,255,0.6);
            padding: 12px;
            border-radius: 12px;
            backdrop-filter: blur(5px);
        }
        
        .contact-info {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #7f8c8d;
            padding: 0 20px;
            z-index: 2;
            font-weight: 500;
        }
        
        .signature-section {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            position: relative;
            z-index: 2;
        }
        
        .signature-line {
            width: 85%;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.8), transparent);
            margin: 8px auto;
            border-radius: 2px;
        }
        
        .photo-id {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 9px;
            opacity: 0.8;
            background: rgba(0,0,0,0.2);
            padding: 4px 8px;
            border-radius: 12px;
            backdrop-filter: blur(5px);
            z-index: 2;
            font-weight: 600;
        }
        
        .badge {
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 2;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        
        .debug-info {
            background: #ffebee;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            font-size: 12px;
            display: none;
        }
        
        .debug-info.show {
            display: block;
        }
        
        .card-container {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.4);
        }
    </style>
</head>
<body>
    <!-- Debug info (à masquer en production) -->
    <div class="debug-info no-print" id="debugInfo">
        <strong>Debug Information:</strong><br>
        Profile Picture: {{ $personnel->profile_picture }}<br>
        QR Code Path: {{ $personnel->path_qr_code }}<br>
        Storage URL: {{ Storage::url('test') }}
    </div>

    <div class="no-print text-center mb-4">
        <button onclick="window.print()" class="btn btn-primary me-2">
            <i class="bi bi-printer me-2"></i>Imprimer
        </button>
        <a href="{{ route('admin.permanent-personnel.download-card', $personnel->id) }}" class="btn btn-success">
            <i class="bi bi-download me-2"></i>Télécharger PDF
        </a>
        <a href="{{ route('admin.permanent-personnel.index') }}" class="btn btn-secondary ms-2">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>

    <!-- Recto de la carte -->
    <div class="card-container">
        <div class="card-front">
            <div class="photo-id">
                ID: {{ $personnel->code_acces }}
            </div>
            
            <div class="badge {{ $personnel->statut == 'approuve' ? 'bg-success' : 'bg-danger' }}">
                {{ $personnel->statut == 'approuve' ? 'ACTIF' : 'INACTIF' }}
            </div>

            <div class="header">
                <h1>CARTE D'ACCÈS</h1>
                <div class="subtitle">PERSONNEL PERMANENT AUTORISÉ</div>
            </div>
            
            <div class="photo-section">
                <div class="profile-photo">
                    @if($personnel->profile_picture)
                        <img src="{{ asset('storage/' . $personnel->profile_picture) }}" 
                             alt="Photo de profil de {{ $personnel->prenom }} {{ $personnel->name }}"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="placeholder" style="display: none;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    @else
                        <div class="placeholder">
                            <i class="bi bi-person-fill"></i>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="info-section">
                <div class="info-row">
                    <span class="info-label">NOM:</span>
                    <span class="info-value">{{ strtoupper($personnel->name) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">PRÉNOM:</span>
                    <span class="info-value">{{ ucfirst($personnel->prenom) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">FONCTION:</span>
                    <span class="info-value">{{ $personnel->fonction }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">STRUCTURE:</span>
                    <span class="info-value">{{ $personnel->structure }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">CONTACT:</span>
                    <span class="info-value">{{ $personnel->contact }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">N° PIÈCE:</span>
                    <span class="info-value">{{ $personnel->numero_piece }}</span>
                </div>
            </div>
            
            <div class="validity text-center">
                <strong>VALIDE DU:</strong> {{ $personnel->date_debut_permanent }} 
                <strong>AU:</strong> {{ $personnel->date_fin_permanent }}
            </div>
            
            <div class="signature-section">
                <div class="signature-line"></div>
                <div>Signature du titulaire</div>
            </div>
            
            <div class="watermark text-center">
                Généré le: {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>
    </div>

    <div class="text-center my-4 no-print">
        <i class="bi bi-arrow-down text-white" style="font-size: 2rem; text-shadow: 0 2px 4px rgba(0,0,0,0.3);"></i>
    </div>

    <!-- Verso de la carte -->
    <div class="card-container">
        <div class="card-back">
            <div class="logo">
                <i class="bi bi-shield-check logo-icon"></i>
            </div>
            
            <div class="back-header">
                <h2>CODE D'ACCÈS QR</h2>
            </div>
            
            <div class="qr-section">
                <div class="qr-code">
                    @if($personnel->path_qr_code)
                        <img src="{{ asset('storage/' . $personnel->path_qr_code) }}" 
                             alt="QR Code d'accès {{ $personnel->code_acces }}"
                             onerror="this.style.display='none';">
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 9px; color: #666; background: #f8f9fa; {{ $personnel->path_qr_code ? 'display: none;' : '' }}; border-radius: 6px; font-weight: 600;">
                            QR CODE: {{ $personnel->code_acces }}
                        </div>
                    @else
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 9px; color: #666; background: #f8f9fa; border-radius: 6px; font-weight: 600;">
                            QR CODE: {{ $personnel->code_acces }}
                        </div>
                    @endif
                </div>
                <div style="margin-top: 12px; font-size: 11px; color: #2c3e50; font-weight: 800; letter-spacing: 1px;">
                    {{ $personnel->code_acces }}
                </div>
            </div>
            
            <div class="barcode">
                {{ $personnel->code_acces }}
            </div>
            
            <div class="instructions">
                <strong>INSTRUCTIONS D'UTILISATION:</strong><br>
                • Présentez cette carte à l'entrée<br>
                • Scanner le QR code pour enregistrement<br>
                • Conservez cette carte en lieu sûr<br>
                • Signalez immédiatement toute perte
            </div>
            
            <div class="contact-info">
                <strong>EN CAS DE PERTE:</strong><br>
                Contactez l'administration • +225 07 11 11 79 79<br>
                Email: info@example.com
            </div>
        </div>
    </div>

    <script>
        function toggleDebug() {
            document.getElementById('debugInfo').classList.toggle('show');
        }

        // Auto-impression si paramètre print
        if (window.location.search.includes('print=true')) {
            window.print();
        }
        
        // Vérification du chargement des images
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier la photo de profil
            const profileImg = document.querySelector('.profile-photo img');
            if (profileImg) {
                profileImg.onerror = function() {
                    this.style.display = 'none';
                    const placeholder = this.nextElementSibling;
                    if (placeholder) {
                        placeholder.style.display = 'flex';
                    }
                };
            }

            // Vérifier le QR code
            const qrImg = document.querySelector('.qr-code img');
            if (qrImg) {
                qrImg.onerror = function() {
                    this.style.display = 'none';
                    const fallback = this.nextElementSibling;
                    if (fallback) {
                        fallback.style.display = 'flex';
                    }
                };
            }
        });

        // Log de debug
        console.log('Profile Picture Path:', '{{ $personnel->profile_picture }}');
        console.log('QR Code Path:', '{{ $personnel->path_qr_code }}');
        console.log('Storage URL Test:', '{{ asset("storage/test") }}');
    </script>
</body>
</html>