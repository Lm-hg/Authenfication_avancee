<?php
require_once  './vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class ConnexionUser {

    // Vérifie les identifiants et retourne l'objet utilisateur (array) s'ils sont valides,
    // sans poser de cookie. Le controller décidera d'émettre le JWT après 2FA éventuelle.
    public function verifyCredentials($name, $password) {
        $path = __DIR__ . '/../BD/users.json';
        $usersData = file_exists($path) ? file_get_contents($path) : '[]';
        $users = json_decode($usersData, true) ?? [];
        if (isset($users['users']) && is_array($users['users'])) {
            $users = $users['users'];
        }

        foreach ($users as $user) {
            if (!isset($user['name'], $user['password'])) continue;
            if ($user['name'] === $name && $user['password'] === $password) {
                return $user;
            }
        }
        return false;
    }

    // Générer un JWT pour un utilisateur donné (array) et renvoyer le token
    public function generateJwtForUser(array $user, $ttl = 3600) {
        $secretKey = "my_token_secret_key";
        $issuedAt = time();
        $expirationTime = $issuedAt + $ttl;
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'userId' => $user['id'] ?? null,
            'name' => $user['name'] ?? null,
            'role' => $user['role'] ?? null
        ];
        return JWT::encode($payload, $secretKey, 'HS256');
    }

    // Utility: récupère un utilisateur par id
    public function getUserById($id) {
        $path = __DIR__ . '/../BD/users.json';
        $usersData = file_exists($path) ? file_get_contents($path) : '[]';
        $users = json_decode($usersData, true) ?? [];
        if (isset($users['users']) && is_array($users['users'])) $list = $users['users']; else $list = $users;
        foreach ($list as $u) { if (isset($u['id']) && (string)$u['id'] === (string)$id) return $u; }
        return null;
    }

    // Utility: mettre à jour un utilisateur complet (écrase l'entrée correspondante)
    public function updateUser(array $updatedUser) {
        $path = __DIR__ . '/../BD/users.json';
        $usersData = file_exists($path) ? file_get_contents($path) : '[]';
        $users = json_decode($usersData, true) ?? [];
        $isWrapped = false;
        if (isset($users['users']) && is_array($users['users'])) { $list = $users['users']; $isWrapped = true; } else { $list = $users; }
        $found = false;
        foreach ($list as $i => $u) {
            if (isset($u['id']) && isset($updatedUser['id']) && (string)$u['id'] === (string)$updatedUser['id']) {
                $list[$i] = $updatedUser; $found = true; break;
            }
        }
        if (!$found) $list[] = $updatedUser;
        $out = $isWrapped ? ['users' => $list] : $list;
        file_put_contents($path, json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return true;
    }

    // Récupère le nom d'utilisateur depuis le JWT présent en cookie
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