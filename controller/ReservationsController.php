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
}
