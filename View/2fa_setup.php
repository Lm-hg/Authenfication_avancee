<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Configurer 2FA</title>
  <style>
    .qr { max-width:220px; height:auto; border:1px solid #ddd; padding:6px; }
    .secret { font-family: monospace; background:#f8f8f8; padding:6px; display:inline-block; border-radius:4px; }
    .note { color:#555; font-size:0.95rem; }
  </style>
</head>
<link rel="stylesheet" href="/assets/style.css">
<body>
  <h1>Activer l'authentification à deux facteurs</h1>
  <?php if (!empty($error)): ?><p style="color:red"><?= htmlspecialchars($error) ?></p><?php endif; ?>
  <p class="note">Scannez ce QR code avec votre application TOTP (Google Authenticator, Authy, etc.). Si vous ne pouvez pas scanner le QR, copiez le secret manuellement.</p>

  <div>
    <img class="qr" src="<?= htmlspecialchars($qr, ENT_QUOTES, 'UTF-8') ?>" alt="QR Code">
  </div>

  <p>Secret (copiez et conservez en lieu sûr) :</p>
  <div>
    <span class="secret" id="secretTxt"><?= htmlspecialchars($secret, ENT_QUOTES, 'UTF-8') ?></span>
    <button type="button" onclick="navigator.clipboard && navigator.clipboard.writeText('<?= htmlspecialchars($secret, ENT_QUOTES, 'UTF-8') ?>') ? alert('Copié') : alert('Copie impossible')">Copier</button>
  </div>

  <form method="post" style="margin-top:12px;">
    <input type="hidden" name="secret" value="<?= htmlspecialchars($secret, ENT_QUOTES, 'UTF-8') ?>">
    <label for="code">Code TOTP :</label>
    <input id="code" name="code" required>
    <button type="submit">Activer</button>
  </form>

  <p><a href="index.php?route=accueil">Retour</a></p>
</body>
</html>
