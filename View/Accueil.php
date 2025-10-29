<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Réservation</title>
</head>
<body>
  <header>
    <nav>
      <a href="/">Accueil</a>
    <?php
    // Afficher le lien d'ajout de spectacle uniquement pour l'admin
    if (!empty($isadmin)) {
      echo '<a href="addSpectacle">Ajouter Spectacle</a>';
    }
    // Afficher le lien de déconnexion si l'utilisateur est connecté
    if (!empty($isLoggedIn)) {
      echo '<a href="logout.php">Déconnexion</a>';
    } else {
      echo '<a href="sign_in.php">Connexion</a>';
    }
    ?>
    <?= htmlspecialchars($name ?? '', ENT_QUOTES, 'UTF-8') ?>
    </nav>
  </header>
  <main>
    <h1>Bienvenue sur la page d'accueil</h1>
    <p>Voici les spectacles disponibles</p>
    <?php if (!empty($spectacles) && is_array($spectacles)): ?>
      <?php foreach ($spectacles as $spectacle): ?>
        <div>
          <h2><?= htmlspecialchars($spectacle['titre'] ?? '', ENT_QUOTES, 'UTF-8') ?></h2>
          <p>Date: <?= htmlspecialchars($spectacle['date'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
          <p>Lieu: <?= htmlspecialchars($spectacle['lieu'] ?? ($spectacle['location'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>
          <p>Artiste: <?= htmlspecialchars($spectacle['artiste'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
          <p>Prix: <?= htmlspecialchars($spectacle['prix'] ?? '', ENT_QUOTES, 'UTF-8') ?> €</p>
          <?php
            $rawUrl = $spectacle['url'] ?? '';
            $imgUrl = '';
            if (!empty($rawUrl)) {
                // si l'URL contient un paramètre 'mediaurl' (bing search), l'utiliser
                $parts = parse_url($rawUrl);
                if (!empty($parts['query'])) {
                    parse_str($parts['query'], $qs);
                    if (!empty($qs['mediaurl'])) {
                        $imgUrl = urldecode($qs['mediaurl']);
                    }
                }
                // si pas de mediaurl, vérifier si l'URL elle-même ressemble à une image
                if (empty($imgUrl) && preg_match('/\.(jpe?g|png|gif|webp)(?:[?#].*)?$/i', $rawUrl)) {
                    $imgUrl = $rawUrl;
                }
            }
            $safeImg = htmlspecialchars($imgUrl ?: $rawUrl ?: '#', ENT_QUOTES, 'UTF-8');
          ?>
          <?php if (!empty($imgUrl)): ?>
            <div class="thumbnail">
              <img src="<?= $safeImg ?>" alt="<?= htmlspecialchars($spectacle['titre'] ?? 'image', ENT_QUOTES, 'UTF-8') ?>" style="max-width:200px; height:auto; display:block; margin-bottom:6px;" />
              <a href="<?= $safeImg ?>" target="_blank" rel="noopener">Voir en grand</a>
            </div>
          <?php elseif (!empty($rawUrl)): ?>
            <a href="<?= htmlspecialchars($rawUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">Plus d'infos / source</a>
          <?php else: ?>
            <p>Aucune image disponible.</p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Aucun spectacle trouvé.</p>
    <?php endif; ?>
  </main>
  <footer></footer>
</body>
</html>