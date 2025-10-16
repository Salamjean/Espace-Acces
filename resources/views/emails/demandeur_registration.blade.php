<!DOCTYPE html>
<html>
<head>
    <title>Espace - Accèss - Confirmation d'enregistrement</title>
</head>
<body>
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <img src="{{ $logoUrl }}" alt="Logo Espace - Accèss" width="150">
            </td>
        </tr>
        <tr>
            <td>
                <h1>KKS-Techologies a enregistré vous à enregistrer</h1>
                <p>Vous pouvez effectuer des demandes d'accès au DATA-CENTER.</p>
                <p>Cliquez sur le bouton ci-dessous pour valider votre compte.</p>
                <p>Saisissez le code <strong>{{ $code }}</strong> dans le formulaire qui apparaîtra.</p>
                <p><a href="{{ url('/validate-claimant-account/' . $email) }}" style="background-color:#193561; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; cursor: pointer;">Valider mon compte</a></p>
                <p>Merci d'utiliser notre application Espace - Accèss.</p>
            </td>
        </tr>
    </table>
</body>
</html>