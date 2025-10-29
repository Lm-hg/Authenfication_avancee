<?php
require_once  './vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class gestionSpectacle {
  //fonction pour verifier que c'est bien l'adim
  public function verifyIdentity() {
    $secretKey = "my_token_secret_key";
    if(!isset($_COOKIE['auth_token'])){
      http_response_code(401);
      echo "Jeton d'authentification manquant.";
      exit();
    }else{
      try {
        $decoded = JWT::decode($_COOKIE['auth_token'], new Key($secretKey, 'HS256'));
        if ($decoded->role !== 'admin') {
          throw new Exception("Accès refusé. Rôle insuffisant.");

        }
        return true;
      } catch (Exception $e) {
        http_response_code(401);
        echo "Erreur d'authentification : " . $e->getMessage();
        exit();
      }
    }
  }
  
  //ajouter un spectacle
    
  public function addSpectacle($title, $date, $lieu, $artiste, $url, $prix) {
    $path = __DIR__ . '/../BD/spectacle.json';
    $spectaclesData = file_exists($path) ? file_get_contents($path) : '[]';
    $spectacles = json_decode($spectaclesData, true) ?? [];
        
    $newSpectacle = [
      'id' => count($spectacles) + 1,
      'titre' => $title,
      'date' => $date,
      'lieu' => $lieu,
      'artiste' => $artiste,
      'url' => $url,
      'prix' => $prix
    ];
        
    $spectacles[] = $newSpectacle;
    file_put_contents($path, json_encode($spectacles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
  }

}