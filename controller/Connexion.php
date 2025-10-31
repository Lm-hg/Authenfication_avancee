<?php
require_once './Model/databaseUser.php';
require_once './Model/databaseSpectacle.php';
require_once './Model/addSpectacle.php';

class Connexion {

  public function SignIn() {
    require_once './View/Connexion.php';
  }
  public function isUserLoggedIn() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $password = $_POST['password'];
        $userExist = new ConnexionUser();
        $spectacleDB = new databaseSpectacle(); 
        $identity = new gestionSpectacle();

        $user = $userExist->verifyCredentials($name, $password);
        if ($user !== false) {
      // si l'utilisateur a la 2FA activée, stocker en session et rediriger vers la vérification
      if (!empty($user['twofa_enabled']) && !empty($user['twofa_secret'])) {
        $_SESSION['twofa_user'] = $user;
        header('Location: index.php?route=2fa_verify');
        exit();
      }

      // si l'utilisateur n'a pas encore activé la 2FA, démarrer le flux de configuration
      if (empty($user['twofa_enabled'])) {
        // garder l'utilisateur en session pour la configuration 2FA
        $_SESSION['twofa_setup_user'] = $user;
        header('Location: index.php?route=2fa_setup');
        exit();
      }

      // sinon émettre le JWT et rediriger (fallback)
      $jwt = $userExist->generateJwtForUser($user, 3600);
      setcookie('auth_token', $jwt, time() + 3600, "/");
      header('Location: index.php?route=accueil');
      exit();
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect.";
            require_once './View/Connexion.php';
        }
    }
  }

  public function logout() {
    setcookie('auth_token', '', time() - 3600, "/");
    header('Location: index.php?route=accueil');
    exit();
  }
  
}