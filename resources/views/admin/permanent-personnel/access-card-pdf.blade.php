<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Carte d'Accès - {{ $personnel->prenom }} {{ $personnel->name }}</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }
        
        body {
            font-family: 'DejaVu Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: #f8f9fa;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .a4-container {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            background: white;
        }
        
        .cards-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30mm;
            min-height: 250mm;
        }
        
        .card-container {
            width: 105mm;
            height: 148mm;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow: hidden;
            position: relative;
        }
        
        .card-front {
            background: linear-gradient(135deg, #193561 0%, #2c4b8a 100%);
            color: white;
            height: 100%;
            padding: 20px;
            position: relative;
        }
        
        .card-back {
            background: #f8f9fa;
            color: #333;
            height: 100%;
            padding: 20px;
            position: relative;
            border: 1px solid #e0e0e0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255,255,255,0.3);
        }
        
        .header h1 {
            font-size: 16px;
            margin: 0;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .header .subtitle {
            font-size: 10px;
            opacity: 0.8;
            margin-top: 5px;
        }
        
        .photo-section {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .profile-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto;
            border: 3px solid rgba(255,255,255,0.3);
            overflow: hidden;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .profile-photo .placeholder {
            font-size: 24px;
            color: rgba(255,255,255,0.7);
        }
        
        .info-section {
            margin-bottom: 20px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 11px;
            padding: 3px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .info-label {
            font-weight: bold;
            opacity: 0.8;
            flex: 1;
        }
        
        .info-value {
            text-align: right;
            flex: 2;
        }
        
        .qr-section {
            text-align: center;
            margin-top: 15px;
            padding: 15px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
        }
        
        .qr-code {
            width: 100px;
            height: 100px;
            background: white;
            margin: 0 auto;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .validity {
            text-align: center;
            margin-top: 10px;
            font-size: 9px;
            opacity: 0.7;
        }
        
        .barcode {
            text-align: center;
            margin-top: 10px;
            font-family: monospace;
            font-size: 18px;
            letter-spacing: 2px;
            color: #193561;
            font-weight: bold;
        }
        
        .watermark {
            position: absolute;
            bottom: 10px;
            right: 15px;
            font-size: 8px;
            opacity: 0.5;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 15px;
        }
        
        .logo-icon {
            font-size: 24px;
            color: #193561;
        }
        
        .back-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #193561;
        }
        
        .back-header h2 {
            font-size: 14px;
            margin: 0;
            color: #193561;
            font-weight: bold;
        }
        
        .instructions {
            font-size: 9px;
            text-align: center;
            margin-top: 10px;
            color: #666;
            line-height: 1.4;
        }
        
        .contact-info {
            position: absolute;
            bottom: 15px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #666;
            padding: 0 10px;
        }
        
        .signature-section {
            margin-top: 15px;
            text-align: center;
            font-size: 9px;
        }
        
        .signature-line {
            width: 80%;
            height: 1px;
            background: rgba(255,255,255,0.5);
            margin: 5px auto;
        }
        
        .photo-id {
            position: absolute;
            top: 15px;
            left: 15px;
            font-size: 8px;
            opacity: 0.7;
        }
        
        .footer-info {
            text-align: center;
            margin-top: 20mm;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .qr-fallback {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #666;
            text-align: center;
            background: #f8f9fa;
        }
        
        .card-label {
            text-align: center;
            margin-bottom: 10px;
            font-size: 12px;
            font-weight: bold;
            color: #193561;
        }
    </style>
</head>
<body>
    <div class="a4-container">
        <div class="cards-container">
            <!-- Recto de la carte -->
            <div class="card-side">
                <div class="card-label">RECTO</div>
                <div class="card-container">
                    <div class="card-front">
                        <div class="photo-id">
                            ID: {{ $personnel->code_acces }}
                        </div>
                        
                        <div class="header">
                            <h1>CARTE D'ACCÈS PERMANENT</h1>
                            <div class="subtitle">PERSONNEL AUTORISÉ</div>
                        </div>
                        
                        <div class="photo-section">
                            <div class="profile-photo">
                                @if(isset($profilePictureBase64) && $profilePictureBase64)
                                    <img src="{{ $profilePictureBase64 }}" alt="Photo de profil">
                                @else
                                    <div class="placeholder">
                                        ●
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
                        
                        <div class="validity">
                            <strong>VALIDE DU:</strong> {{ \Carbon\Carbon::parse($personnel->date_debut_permanent)->format('d/m/Y') }} 
                            <strong>AU:</strong> {{ \Carbon\Carbon::parse($personnel->date_fin_permanent)->format('d/m/Y') }}
                        </div>
                        
                        <div class="signature-section">
                            <div class="signature-line"></div>
                            <div>Signature du titulaire</div>
                        </div>
                        
                        <div class="watermark">
                            Généré le: {{ now()->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verso de la carte -->
            <div class="card-side">
                <div class="card-label">VERSO</div>
                <div class="card-container">
                    <div class="card-back">
                        <div class="logo">
                            ●
                        </div>
                        
                        <div class="back-header">
                            <h2>CODE D'ACCÈS QR</h2>
                        </div>
                        
                        <div class="qr-section">
                            <div class="qr-code">
                                @if(isset($qrCodeBase64) && $qrCodeBase64)
                                    <img src="{{ $qrCodeBase64 }}" alt="QR Code">
                                @else
                                    <div class="qr-fallback">
                                        QR CODE<br>{{ $personnel->code_acces }}
                                    </div>
                                @endif
                            </div>
                            <div style="margin-top: 10px; font-size: 10px; color: #193561; font-weight: bold;">
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
                            Email: {{ $personnel->email }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-info">
            <strong>Carte d'accès permanente - {{ $personnel->prenom }} {{ $personnel->name }}</strong><br>
            Document généré le {{ now()->format('d/m/Y à H:i') }} | Valide jusqu'au {{ \Carbon\Carbon::parse($personnel->date_fin_permanent)->format('d/m/Y') }}
        </div>
    </div>
</body>
</html>