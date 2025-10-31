<?php
require_once __DIR__ . '/../Model/TwoFA.php';
require_once __DIR__ . '/../Model/databaseUser.php';

class TwoFAController {
    public function setup() {
        $userModel = new ConnexionUser();

        // Deux modes :
        // - configuration depuis une session après authentification (session['twofa_setup_user'])
        // - configuration depuis un utilisateur déjà connecté via cookie JWT
        $user = null;
        $sessionSetup = false;

        if (!empty($_SESSION['twofa_setup_user'])) {
            $user = $_SESSION['twofa_setup_user'];
            $sessionSetup = true;
        } else if (!empty($_COOKIE['auth_token'])) {
            try {
                $decoded = \Firebase\JWT\JWT::decode($_COOKIE['auth_token'], new \Firebase\JWT\Key('my_token_secret_key','HS256'));
                $userId = $decoded->userId ?? null;
                if (!empty($userId)) {
                    $user = $userModel->getUserById($userId);
                }
            } catch (Exception $e) {
                // ignore and fallthrough
            }
        }

        if (!$user) {
            header('Location: index.php?route=connexion'); exit();
        }

        // POST: confirmer le code fourni par l'utilisateur
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $secret = $_POST['secret'] ?? '';
            $code = $_POST['code'] ?? '';
            if (!empty($secret) && !empty($code) && \TwoFA::verifyCode($secret, $code)) {
                // sauvegarder le secret pour l'utilisateur
                $user['twofa_enabled'] = true;
                $user['twofa_secret'] = $secret;
                $userModel->updateUser($user);

                // Si l'utilisateur venait du flux de setup (session), émettre le JWT maintenant
                if ($sessionSetup) {
                    $jwt = $userModel->generateJwtForUser($user, 3600);
                    setcookie('auth_token', $jwt, time() + 3600, '/');
                    unset($_SESSION['twofa_setup_user']);
                    header('Location: index.php?route=accueil'); exit();
                }

                // sinon rediriger vers l'accueil (utilisateur déjà connecté)
                header('Location: index.php?route=accueil'); exit();
            } else {
                $error = 'Code invalide.';
                $qr = TwoFA::getQrCodeUrl('MyApp', $user['name'], $secret ?? '');
                require __DIR__ . '/../View/2fa_setup.php';
                return;
            }
        }

        // GET: générer secret temporaire et afficher QR
        $secret = \TwoFA::generateSecret();
        $qr = TwoFA::getQrCodeUrl('MyApp', $user['name'], $secret);
        require __DIR__ . '/../View/2fa_setup.php';
    }

    public function verify() {
        // Vérifier que l'utilisateur a lancé une session 2FA (depuis login)
        if (empty($_SESSION['twofa_user'])) {
            header('Location: index.php?route=connexion'); exit();
        }
        $pending = $_SESSION['twofa_user'];
        // POST: vérifier le code
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $code = $_POST['code'] ?? '';
            $secret = $pending['twofa_secret'] ?? '';
            if (!empty($secret) && !empty($code) && TwoFA::verifyCode($secret, $code)) {
                // Générer JWT et poser cookie
                $userModel = new ConnexionUser();
                $jwt = $userModel->generateJwtForUser($pending, 3600);
                setcookie('auth_token', $jwt, time() + 3600, '/');
                unset($_SESSION['twofa_user']);
                header('Location: index.php?route=accueil'); exit();
            } else {
                $error = 'Code invalide.';
                require __DIR__ . '/../View/2fa_verify.php';
                return;
            }
        }

        // GET: afficher formulaire de saisie
        require __DIR__ . '/../View/2fa_verify.php';
    }
}
