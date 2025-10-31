<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Réservation</title>
  <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
  <header class="wrap">
    <div class="brand">Réservations</div>
    <nav>
      <a href="index.php?route=accueil">Accueil</a>
      <?php
      // Afficher le lien d'ajout de spectacle uniquement pour l'admin
      if (!empty($isadmin)) {
        echo '<a href="index.php?route=add_spectacle">Ajouter Spectacle</a>';
        echo '<a href="index.php?route=mes_reservations">Mes réservations</a>';
      }
      // Afficher le lien de déconnexion si l'utilisateur est connecté
      if (!empty($isLoggedIn)) {
        echo '<a href="index.php?route=logout">Déconnexion</a>';
        echo '<a href="index.php?route=mes_reservations">Mes réservations</a>';
      } else {
        echo '<a href="index.php?route=connexion">Connexion</a>';
      }
      ?>
    </nav>
  </header>
  <main class="wrap">
    <section class="hero card">
      <div>
        <div class="title">Bienvenue, <?= htmlspecialchars($name ?? 'visiteur', ENT_QUOTES, 'UTF-8') ?></div>
        <div class="subtitle">Découvrez les spectacles à venir — réservez vos places en quelques clics.</div>
      </div>
      <div class="cta">
        <a class="btn" href="index.php?route=connexion">Se connecter</a>
      </div>
    </section>

    <h2 style="margin:8px 0">Spectacles disponibles</h2>

    <?php if (!empty($spectacles) && is_array($spectacles)): ?>
      <div class="grid">
        <?php foreach ($spectacles as $spectacle): ?>
          <?php
            $rawUrl = $spectacle['url'] ?? '';
            $imgUrl = '';
            if (!empty($rawUrl)) {
                $parts = parse_url($rawUrl);
                if (!empty($parts['query'])) {
                    parse_str($parts['query'], $qs);
                    if (!empty($qs['mediaurl'])) {
                        $imgUrl = urldecode($qs['mediaurl']);
                    }
                }
                if (empty($imgUrl) && preg_match('/\.(jpe?g|png|gif|webp)(?:[?#].*)?$/i', $rawUrl)) {
                    $imgUrl = $rawUrl;
                }
            }
            $safeImg = htmlspecialchars($imgUrl ?: $rawUrl ?: '#', ENT_QUOTES, 'UTF-8');
          ?>
          <article class="card spectacle fade-in">
            <div class="thumbnail-wrap">
              <?php if (!empty($imgUrl)): ?>
                <img src="<?= $safeImg ?>" alt="<?= htmlspecialchars($spectacle['titre'] ?? 'image', ENT_QUOTES, 'UTF-8') ?>">
              <?php else: ?>
                <svg width="100" height="60" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="placeholder">
                  <rect width="100%" height="100%" fill="#f4f4f4"/>
                </svg>
              <?php endif; ?>
            </div>
            <div style="padding:10px">
              <h3 style="margin:0 0 6px 0"><?= htmlspecialchars($spectacle['titre'] ?? '', ENT_QUOTES, 'UTF-8') ?></h3>
              <div class="meta">
                <div class="note"><?= htmlspecialchars($spectacle['date'] ?? '', ENT_QUOTES, 'UTF-8') ?> • <?= htmlspecialchars($spectacle['lieu'] ?? ($spectacle['location'] ?? ''), ENT_QUOTES, 'UTF-8') ?></div>
                <div class="price"><?= htmlspecialchars($spectacle['prix'] ?? '', ENT_QUOTES, 'UTF-8') ?> €</div>
              </div>
              <p class="note" style="margin:8px 0 12px 0">Artiste: <?= htmlspecialchars($spectacle['artiste'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
              <div class="flex">
                <a class="btn" href="index.php?route=reserver&spectacleId=<?= htmlspecialchars($spectacle['id'] ?? '', ENT_QUOTES, 'UTF-8') ?>">Réserver</a>
                <a class="btn secondary" href="<?= htmlspecialchars($rawUrl ?: '#', ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">Source</a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="card">Aucun spectacle trouvé.</div>
    <?php endif; ?>

  </main>
  <footer class="wrap"><div class="note">&copy; <?= date('Y') ?> Réservations - Exemple éducatif</div></footer>
</body>
<script src="/assets/app.js" defer></script>
</html>