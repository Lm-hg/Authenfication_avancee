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
}
