<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../Model/databaseReservation.php';

class ReservationsController {
    public function afficherMesReservations() {
        // vérifier cookie
        if (empty($_COOKIE['auth_token'])) {
            header('Location: index.php?route=connexion');
            exit();
        }

        try {
            $decoded = \Firebase\JWT\JWT::decode($_COOKIE['auth_token'], new \Firebase\JWT\Key('my_token_secret_key', 'HS256'));
            $userId = $decoded->userId ?? null;
            if (empty($userId)) {
                header('Location: index.php?route=connexion');
                exit();
            }
        } catch (Exception $e) {
            header('Location: index.php?route=connexion');
            exit();
        }

        $db = new databaseReservation();
        $reservations = $db->getReservationsByUser($userId);
        require_once __DIR__ . '/../View/Reservations.php';
    }

    public function afficherFormulaireReservation() {
        // Exige login, reçoit spectacleId en GET
        if (empty($_COOKIE['auth_token'])) {
            header('Location: index.php?route=connexion'); exit();
        }
        $spectacleId = $_GET['spectacleId'] ?? null;
        if (empty($spectacleId)) {
            header('Location: index.php?route=accueil'); exit();
        }

        // charger spectacle pour affichage
        require_once __DIR__ . '/../Model/databaseSpectacle.php';
        $db = new databaseSpectacle();
        $spectacles = $db->getAllSpectacles();
        $spectacle = null;
        foreach ($spectacles as $s) {
            if (isset($s['id']) && (string)$s['id'] === (string)$spectacleId) {
                $spectacle = $s; break;
            }
        }
        if (!$spectacle) { header('Location: index.php?route=accueil'); exit(); }

        $selectedSpectacle = $spectacle;
        require_once __DIR__ . '/../View/ReserveForm.php';
    }

    public function creerReservation() {
        // POST handler
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php?route=accueil'); exit(); }
        if (empty($_COOKIE['auth_token'])) { header('Location: index.php?route=connexion'); exit(); }

        try {
            $decoded = \Firebase\JWT\JWT::decode($_COOKIE['auth_token'], new \Firebase\JWT\Key('my_token_secret_key','HS256'));
            $userId = $decoded->userId ?? null;
            if (empty($userId)) { header('Location: index.php?route=connexion'); exit(); }
        } catch (Exception $e) { header('Location: index.php?route=connexion'); exit(); }

        $spectacleId = $_POST['spectacleId'] ?? null;
        $seats = $_POST['seats'] ?? 1;
        if (empty($spectacleId)) { header('Location: index.php?route=accueil'); exit(); }

        // récupérer titre du spectacle
        require_once __DIR__ . '/../Model/databaseSpectacle.php';
        $dbSpec = new databaseSpectacle();
        $specs = $dbSpec->getAllSpectacles();
        $title = '';
        foreach ($specs as $s) { if (isset($s['id']) && (string)$s['id'] === (string)$spectacleId) { $title = $s['titre'] ?? ''; break; } }

        $db = new databaseReservation();
        $db->addReservation($userId, $spectacleId, $title, $seats, 'confirmed');
        header('Location: index.php?route=mes_reservations');
        exit();
    }
}
