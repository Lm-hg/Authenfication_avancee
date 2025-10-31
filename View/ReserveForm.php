<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Réserver - <?= htmlspecialchars($selectedSpectacle['titre'] ?? '', ENT_QUOTES, 'UTF-8') ?></title>
</head>
<body>
  <h1>Réserver : <?= htmlspecialchars($selectedSpectacle['titre'] ?? '', ENT_QUOTES, 'UTF-8') ?></h1>
  <p>Artiste: <?= htmlspecialchars($selectedSpectacle['artiste'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
  <p>Date: <?= htmlspecialchars($selectedSpectacle['date'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
<link rel="stylesheet" href="/assets/style.css">

  <form action="index.php?route=creer_reservation" method="post">
    <input type="hidden" name="spectacleId" value="<?= htmlspecialchars($selectedSpectacle['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    <label for="seats">Nombre de places</label>
    <input id="seats" name="seats" type="number" min="1" value="1" required>
    <div style="margin-top:10px">
      <button type="submit">Confirmer la réservation</button>
      <a href="index.php?route=accueil" style="margin-left:8px">Annuler</a>
    </div>
  </form>
</body>
</html>
