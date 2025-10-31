<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Vérification 2FA</title>
  <style>
    .note { color:#555; font-size:0.95rem; }
  </style>
</head>
<link rel="stylesheet" href="/assets/style.css">
<body>
  <h1>Vérification en deux étapes</h1>
  <?php if (!empty($error)): ?><p style="color:red"><?= htmlspecialchars($error) ?></p><?php endif; ?>
  <p class="note">Entrez le code (6 chiffres) fourni par votre application d'authentification (Google Authenticator, Authy, etc.).</p>
  <?php if (!empty($_SESSION['twofa_user'])): ?>
    <p>Utilisateur: <strong><?= htmlspecialchars($_SESSION['twofa_user']['name'] ?? '') ?></strong></p>
  <?php endif; ?>
  <form method="post">
    <label for="code">Code :</label>
    <input id="code" name="code" required>
    <button type="submit">Vérifier</button>
  </form>
  <p><a href="index.php?route=connexion">Retour à la connexion</a></p>
</body>
</html>
