<?php
// Script de test automatique 2FA (CLI).
// 1) Choisit user1
// 2) Génère un secret, sauvegarde l'utilisateur avec twofa_enabled=true
// 3) Calcule le TOTP et vérifie
// 4) Génère le JWT si OK

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Model/TwoFA.php';
require_once __DIR__ . '/../Model/databaseUser.php';

function out($s) { echo $s . PHP_EOL; }

$userModel = new ConnexionUser();

// Récupérer user1
$usersPath = __DIR__ . '/../BD/users.json';
$all = json_decode(file_get_contents($usersPath), true);
$list = $all['users'] ?? $all;
$target = null;
foreach ($list as $u) { if (($u['name'] ?? '') === 'user1') { $target = $u; break; } }
if (!$target) { out('Utilisateur user1 introuvable'); exit(1); }

out('Utilisateur trouvé: ' . ($target['name'] ?? '')); 

// Générer secret
$secret = TwoFA::generateSecret();
out('Secret généré: ' . $secret);

// Sauvegarder sur le disque (activate 2FA)
$target['twofa_enabled'] = true;
$target['twofa_secret'] = $secret;
$userModel->updateUser($target);
out('Utilisateur mis à jour avec twofa_enabled=true');

// Calculer token TOTP
$code = TwoFA::getTotpToken($secret);
out('Code TOTP calculé: ' . $code);

// Vérifier
$ok = TwoFA::verifyCode($secret, $code);
out('Vérification TwoFA: ' . ($ok ? 'OK' : 'ECHEC'));

if (!$ok) { exit(2); }

// Générer JWT
$jwt = $userModel->generateJwtForUser($target, 3600);
out('JWT généré: ' . $jwt);

// Décode pour affichage
try {
    $decoded = \Firebase\JWT\JWT::decode($jwt, new \Firebase\JWT\Key('my_token_secret_key','HS256'));
    out('Payload JWT: ' . json_encode($decoded));
} catch (Exception $e) {
    out('Erreur décodage JWT: ' . $e->getMessage());
}

out('Test 2FA terminé.');
