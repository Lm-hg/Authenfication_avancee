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
        // Accepter plusieurs valeurs possibles pour le rôle admin (admin ou administrator)
        $role = $decoded->role ?? '';
        if ($role !== 'admin' && $role !== 'administrator') {
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
  $spectaclesData = file_exists($path) ? file_get_contents($path) : '';
  $decoded = json_decode($spectaclesData, true);

  if (is_array($decoded) && isset($decoded['spectacles']) && is_array($decoded['spectacles'])) {
    $list = $decoded['spectacles'];
    $is_wrapped = true;
  } elseif (is_array($decoded)) {
    $list = $decoded;
    $is_wrapped = false;
  } else {
    $list = [];
    $is_wrapped = true;
  }

  $maxId = 0;
  foreach ($list as $item) {
    if (isset($item['id']) && is_numeric($item['id'])) {
      $maxId = max($maxId, (int)$item['id']);
    }
  }

  $newSpectacle = [
    'id' => $maxId + 1,
    'titre' => $title,
    'date' => $date,
    'lieu' => $lieu,
    'artiste' => $artiste,
    'url' => $url,
    'prix' => is_numeric($prix) ? (float)$prix : $prix
  ];

  $list[] = $newSpectacle;

  if ($is_wrapped) {
    $out = ['spectacles' => $list];
  } else {
    $out = $list;
  }

  file_put_contents($path, json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
  }

}