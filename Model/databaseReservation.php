<?php
class databaseReservation {
    public function getReservationsByUser($userId) {
        $path = __DIR__ . '/../BD/reservations.json';
        if (!file_exists($path)) {
            return [];
        }
        $data = json_decode(file_get_contents($path), true);
        $list = [];
        if (is_array($data)) {
            if (isset($data['reservations']) && is_array($data['reservations'])) {
                $list = $data['reservations'];
            } else {
                $list = $data;
            }
        }
        $filtered = [];
        foreach ($list as $r) {
            if (!isset($r['userId'])) continue;
            if ((string)$r['userId'] === (string)$userId) {
                $filtered[] = $r;
            }
        }
        return $filtered;
    }

    public function addReservation($userId, $spectacleId, $spectacleTitre, $seats = 1, $status = 'pending') {
        $path = __DIR__ . '/../BD/reservations.json';
        $data = file_exists($path) ? json_decode(file_get_contents($path), true) : ['reservations' => []];
        $list = [];
        if (is_array($data)) {
            if (isset($data['reservations']) && is_array($data['reservations'])) {
                $list = $data['reservations'];
            } else {
                $list = $data;
            }
        }

        $max = 0;
        foreach ($list as $r) {
            if (isset($r['id']) && is_numeric($r['id'])) $max = max($max, (int)$r['id']);
        }

        $new = [
            'id' => $max + 1,
            'userId' => $userId,
            'spectacleId' => $spectacleId,
            'spectacleTitre' => $spectacleTitre,
            'seats' => (int)$seats,
            'reservationDate' => gmdate('c'),
            'status' => $status
        ];

        $list[] = $new;
        $out = ['reservations' => $list];
        file_put_contents($path, json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return $new;
    }
}
