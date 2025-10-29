<?php
require_once  './Model/databaseSpectacle.php';
require_once  './Model/addSpectacle.php';
require_once  './Model/databaseUser.php';
require_once  './vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AccueilController {
  public function afficherAccueil() {
    // Par défaut : non-admin
    $isadmin = false;

    // Récupérer le nom si disponible
    $nameUser = new ConnexionUser();
    $name = $nameUser->getUserName();

    // Si un token est présent, essayer de le décoder localement pour déterminer le rôle
    if (!empty($_COOKIE['auth_token'])) {
      try {
        $secretKey = "my_token_secret_key";
        $decoded = JWT::decode($_COOKIE['auth_token'], new Key($secretKey, 'HS256'));
        $role = $decoded->role ?? '';
        if ($role === 'admin' || $role === 'administrator') {
          $isadmin = true;
        }
      } catch (Exception $e) {
        // Ne pas exit() ici ; 
        $isadmin = false;
      }
    }

    $databaseSpectacle = new databaseSpectacle();
    $spectacles = $databaseSpectacle->getAllSpectacles();
    // Variable indiquant si un utilisateur est connecté
    $isLoggedIn = !empty($name);
    require_once './View/Accueil.php';
  }
}
