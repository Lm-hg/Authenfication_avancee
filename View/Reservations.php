<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Mes réservations</title>
</head>
<body>
  <h1>Mes réservations</h1>
  <?php if (empty($reservations)): ?>
    <p>Vous n'avez aucune réservation.</p>
  <?php else: ?>
    <?php foreach ($reservations as $r): ?>
      <div>
        <strong><?= htmlspecialchars($r['spectacleTitre'] ?? ('Spectacle #' . ($r['spectacleId'] ?? '')), ENT_QUOTES, 'UTF-8') ?></strong>
        <p>Places: <?= htmlspecialchars($r['seats'] ?? 1, ENT_QUOTES, 'UTF-8') ?></p>
        <p>Date réservation: <?= htmlspecialchars($r['reservationDate'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
        <p>Statut: <?= htmlspecialchars($r['status'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
  <p><a href="index.php?route=accueil">Retour à l'accueil</a></p>
</body>
</html>
