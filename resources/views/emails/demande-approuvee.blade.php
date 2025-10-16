<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Votre Badge d'Accès Personnel - KKS Technologies</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <!-- En-tête avec logo -->
        <div style="text-align: center; margin-bottom: 30px;">
            <img src="{{ $logoUrl }}" alt="KKS Technologies" style="max-width: 200px;">
            <h1 style="color: #193561; margin-top: 20px;">🎫 Votre Accès Personnel Approuvé</h1>
        </div>

        <!-- Message personnel -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
            <p>Bonjour <strong>{{ $personne->prenom }} {{ $personne->name }}</strong>,</p>
            <p>Votre accès personnel pour la demande <strong>{{ $personne->numero_demande }}</strong> a été <strong style="color: #28a745;">approuvé</strong>.</p>
            <p>Retrouvez ci-dessous toutes les informations de votre visite et votre QR code personnel.</p>
        </div>

        <!-- Informations personnelles -->
        <div style="background: #fff; border: 2px solid #193561; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
            <h2 style="color: #193561; margin-top: 0;">👤 Vos Informations</h2>
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 8px 0; width: 40%;"><strong>Nom complet :</strong></td>
                    <td style="padding: 8px 0;">{{ $personne->prenom }} {{ $personne->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Email :</strong></td>
                    <td style="padding: 8px 0;">{{ $personne->email }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Contact :</strong></td>
                    <td style="padding: 8px 0;">{{ $personne->contact }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Fonction :</strong></td>
                    <td style="padding: 8px 0;">{{ $personne->fonction }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Structure :</strong></td>
                    <td style="padding: 8px 0;">{{ $personne->structure }}</td>
                </tr>
            </table>
        </div>

        <!-- Détails de la visite -->
        <div style="background: #fff; border: 2px solid #193561; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
            <h2 style="color: #193561; margin-top: 0;">📋 Détails de la Visite</h2>
            <table style="width: 100%;">
                <tr>
                    <td style="padding: 8px 0; width: 40%;"><strong>📍 Date de début :</strong></td>
                    <td style="padding: 8px 0;">{{ $dateVisiteFormatted }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>📍 Date de fin :</strong></td>
                    <td style="padding: 8px 0;">{{ $dateFinFormatted }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>🕐 Heure de début :</strong></td>
                    <td style="padding: 8px 0;">{{ $personne->heure_visite }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>🕐 Heure de fin :</strong></td>
                    <td style="padding: 8px 0;">{{ $personne->heure_fin_visite ?? 'Non spécifiée' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>🎯 Motif :</strong></td>
                    <td style="padding: 8px 0;">{{ $personne->motif_visite }}</td>
                </tr>
                @if($personne->description_detaille)
                <tr>
                    <td style="padding: 8px 0;"><strong>📝 Description :</strong></td>
                    <td style="padding: 8px 0;">{{ $personne->description_detaille }}</td>
                </tr>
                @endif
            </table>
        </div>

        <!-- Code d'accès -->
        <div style="background: #fff; border: 2px solid #28a745; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
            <h2 style="color: #28a745; margin-top: 0;">🔑 Votre Code d'Accès</h2>
            <div style="text-align: center; padding: 20px; background: #f8f9fa; border-radius: 5px;">
                <div style="font-size: 28px; font-weight: bold; color: #193561; letter-spacing: 3px; margin: 10px 0;">
                    {{ $codeAcces }}
                </div>
                <p style="margin-top: 10px; color: #666;">
                    Code personnel - À présenter avec le QR code
                </p>
            </div>
        </div>

        <!-- Instructions QR code -->
        <div style="background: #e7f3ff; border: 2px solid #0dcaf0; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
            <h2 style="color: #0c5460; margin-top: 0;">📱 Votre QR Code Personnel</h2>
            <p><strong>Votre QR code est joint à cet email en pièce jointe.</strong></p>
            <p>Ce QR code contient toutes vos informations de visite et est unique.</p>
            <ul style="padding-left: 20px;">
                <li><strong>Nom :</strong> {{ $personne->prenom }} {{ $personne->name }}</li>
                <li><strong>Demande :</strong> {{ $personne->numero_demande }}</li>
                <li><strong>Dates :</strong> {{ $dateVisiteFormatted }} au {{ $dateFinFormatted }}</li>
                <li><strong>Validité :</strong> Jusqu'au {{ $expirationFormatted }}</li>
            </ul>
        </div>

        <!-- Instructions -->
        <div style="background: #fff3cd; border: 2px solid #ffc107; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
            <h2 style="color: #856404; margin-top: 0;">🚨 Instructions Importantes</h2>
            <ol style="padding-left: 20px;">
                <li><strong>🆔 Pièce d'identité :</strong> Présentez une pièce d'identité valide</li>
                <li><strong>📱 QR Code :</strong> Présentez le QR code joint (fichier attaché)</li>
                <li><strong>🔢 Code :</strong> Mémorisez votre code d'accès personnel</li>
                <li><strong>⏰ Ponctualité :</strong> Présentez-vous 15 minutes avant</li>
                <li><strong>🔒 Confidentialité :</strong> Ne partagez pas votre code/QR code</li>
            </ol>
        </div>

        <!-- Pied de page -->
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd;">
            <p style="color: #666; font-size: 14px;">
                <strong>KKS-Technologies</strong><br>
                [Adresse complète de vos locaux]<br>
                [Téléphone de contact] | [Email de contact]
            </p>
            <p style="color: #999; font-size: 12px;">
                Cet email a été généré automatiquement. Merci de ne pas y répondre.
            </p>
        </div>
    </div>
</body>
</html>