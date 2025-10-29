<?php
require_once  './vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class ConnexionUser {

    public function verifyCredentials($name, $password) {
        $path = __DIR__ . '/../BD/users.json';
        $usersData = file_exists($path) ? file_get_contents($path) : '[]';
        $users = json_decode($usersData, true) ?? [];
        if (isset($users['users']) && is_array($users['users'])) {
            $users = $users['users'];
        }
        $secretKey = "my_token_secret_key";
        $issuedAt   = time();
        $expirationTime = $issuedAt + 60; 
      
        foreach ($users as $user) {
            if (!isset($user['name'], $user['password'])) {
                    continue;
            }
            if ($user['name'] === $name && $user['password'] === $password) {
                $payload = [
                'iat' => $issuedAt,
                'exp' => $expirationTime,
                'userId' => $user['id'], 
                'name' => $name,
                'role' => $user['role'] 
                ];
                $jwt = JWT::encode($payload, $secretKey, 'HS256');
                setcookie('auth_token', $jwt, $expirationTime, "/");
                return true;
            }
        }
        return false;
    }
    //envoyer le nom
    public function getUserName() {
        if (isset($_COOKIE['auth_token'])) {
            $secretKey = "my_token_secret_key";
            $jwt = $_COOKIE['auth_token'];
            try {
                $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
                return $decoded->name;
            } catch (Exception $e) {
                return null;
            }
          }
          return null;
    }
}