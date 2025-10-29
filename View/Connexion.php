<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion</title>
</head>
<body>
  <form action="index.php?route=sign_in" method="post">
    <span style="color: brown;"><?= htmlspecialchars($error ?? '', ENT_QUOTES, 'UTF-8') ?></span>
    <label for="name">Nom d'utilisateur:</label>
    <input type="text" id="name" name="name" required><br><br>
    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" required><br><br>
    <input type="submit" value="Se connecter">
  </form>
</body>
</html>