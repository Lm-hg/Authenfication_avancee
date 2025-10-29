<?php
class databaseSpectacle {
    public function getAllSpectacles() {
        // Lire le fichier JSON sans l'inclure (ne PAS require/include un fichier JSON)
    $path = __DIR__ . '/../BD/spectacle.json';
        if (!file_exists($path)) {
            return [];
        }
        $spectaclesData = file_get_contents($path);
        $spectacles = json_decode($spectaclesData, true);
        if (!is_array($spectacles)) {
            return [];
        }
        // Supporter deux formats : soit le JSON est un tableau direct, soit il contient une clé "spectacles"
        if (isset($spectacles['spectacles']) && is_array($spectacles['spectacles'])) {
            return $spectacles['spectacles'];
        }
        return $spectacles;
    }

}