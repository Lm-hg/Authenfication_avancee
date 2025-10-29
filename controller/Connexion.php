<?php
require_once './Model/databaseUser.php';
require_once './Model/databaseSpectacle.php';
require_once './Model/addSpectacle.php';

class Connexion {

  public function SignIn() {
    require_once './View/Connexion.php';
  }
  public function isUserLoggedIn() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $password = $_POST['password'];
        $userExist = new ConnexionUser();
        $spectacleDB = new databaseSpectacle(); 
        $identity = new gestionSpectacle();
    if ($userExist->verifyCredentials($name, $password)) {
      // Après avoir posé le cookie, rediriger vers l'accueil pour que le navigateur
      header('Location: index.php?route=accueil');
      exit();
    } else {
      $error = "Nom d'utilisateur ou mot de passe incorrect.";
      require_once './View/Connexion.php';
    }
    }
  }

  public function logout() {
    setcookie('auth_token', '', time() - 3600, "/");
    header('Location: index.php?route=accueil');
    exit();
  }
  
}