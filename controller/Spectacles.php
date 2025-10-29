<?php
require_once './Model/addSpectacle.php';


class SpectaclesController {

    public function pageAdmin() {
        require_once './View/AddSpectacle.php';
    }
    public function addSpectacle() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['titre'] ?? '';
            $date = $_POST['date'] ?? '';
            $location = $_POST['lieu'] ?? '';
            $price = $_POST['prix'] ?? '';
            $url = $_POST['url'] ?? '';
            $artiste = $_POST['artiste'] ?? '';

  
            $spectacleDB = new gestionSpectacle();
            $spectacleDB->addSpectacle($title, $date, $location, $artiste, $url, $price);

            header('Location: index.php?route=accueil');
            exit();
        } else {
            require_once './View/AddSpectacle.php';
        }
    }
}