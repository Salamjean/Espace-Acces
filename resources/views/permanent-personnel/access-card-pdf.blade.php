<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Carte d'Accès - {{ $personnel->prenom }} {{ $personnel->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 0; padding: 20px; }
        .card { border: 2px solid #198754; border-radius: 15px; padding: 20px; max-width: 600px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #198754 0%, #20c997 100%); color: white; padding: 20px; border-radius: 10px 10px 0 0; margin: -20px -20px 20px -20px; text-align: center; }
        .content { display: flex; justify-content: space-between; align-items: flex-start; }
        .info { flex: 2; }
        .qrcode { flex: 1; text-align: center; }
        .photo { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid #dee2e6; }
        .footer { text-align: center; margin-top: 20px; padding-top: 10px; border-top: 1px solid #dee2e6; font-size: 12px; color: #6c757d; }
        .field { margin-bottom: 8px; }
        .label { font-size: 12px; color: #6c757d; }
        .value { font-size: 14px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h2 style="margin: 0;">CARTE D'ACCÈS PERSONNEL</h2>
            <p style="margin: 5px 0 0 0; opacity: 0.9;">Personnel Permanent</p>
        </div>
        
        <div class="content">
            <div class="info">
                <div style="display: flex; align-items: center; margin-bottom: 15px;">
                    @if($profilePictureBase64)
                        <img src="{{ $profilePictureBase64 }}" class="photo" style="margin-right: 15px;">
                    @endif
                    <div>
                        <h3 style="margin: 0 0 5px 0;">{{ $personnel->prenom }} {{ $personnel->name }}</h3>
                        <p style="margin: 0; color: #6c757d;">{{ $personnel->fonction }} - {{ $personnel->structure }}</p>
                    </div>
                </div>
                
                <div class="field">
                    <div class="label">Email</div>
                    <div class="value">{{ $personnel->email }}</div>
                </div>
                
                <div class="field">
                    <div class="label">Contact</div>
                    <div class="value">{{ $personnel->contact }}</div>
                </div>
                
                <div class="field">
                    <div class="label">Pièce d'identité</div>
                    <div class="value">{{ $personnel->type_piece }}: {{ $personnel->numero_piece }}</div>
                </div>
                
                <div class="field">
                    <div class="label">Code d'accès</div>
                    <div class="value" style="color: #198754;">{{ $personnel->code_acces }}</div>
                </div>
                
                <div style="display: flex; justify-content: space-between; margin-top: 15px;">
                    <div class="field">
                        <div class="label">Date de début</div>
                        <div class="value">{{ $personnel->date_debut_permanent }}</div>
                    </div>
                    <div class="field">
                        <div class="label">Date de fin</div>
                        <div class="value" style="color: #dc3545;">{{ $personnel->date_fin_permanent }}</div>
                    </div>
                </div>
            </div>
            
            <div class="qrcode">
                @if($qrCodeBase64)
                    <img src="{{ $qrCodeBase64 }}" style="width: 120px; height: 120px; border: 1px solid #dee2e6;">
                @endif
                <p style="font-size: 10px; margin: 5px 0 0 0; color: #6c757d;">Scanner pour vérification</p>
            </div>
        </div>
        
        <div class="footer">
            <p style="margin: 0;">Carte d'accès personnelle - À présenter à l'entrée</p>
        </div>
    </div>
</body>
</html>