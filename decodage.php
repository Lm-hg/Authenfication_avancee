<?php
require 'vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class decodage{

  function decodeToken(){
    //récupérer le token depuis les cookies
    $secretKey = "my_token_secret_key";
    $jwt = $_COOKIE['auth_token'];
    if(!isset($jwt)){
      http_response_code(401);
      echo "Jeton d'authentification manquant.";
      exit();
    }
    try {
      // Décodage et validation du jeton
      $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
      if ($decoded) {
        
        if ($decoded->exp < time()) {
          throw new Exception("Le jeton a expiré.");
        }
        if ($decoded->name !== 'admin') {
          throw new Exception("Accès refusé. Rôle insuffisant.");
        }
        echo "Utilisateur authentifié : " . $decoded->name;
      }else {
          echo "Jeton invalide.";
      }
      
    } catch (Exception $e) {
    http_response_code(401);
    echo "Erreur d'authentification : " . $e->getMessage();
    exit();
    }
  }
}





