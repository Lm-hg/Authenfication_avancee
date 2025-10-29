<?php
require 'index.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $_POST['name'];
    $password = $_POST['password'];

    // Simuler une validation des informations d'identification
    if($name === 'admin' && $password === 'password123'){
        $jwtCreator = new create_Jwt();
        $token = $jwtCreator->createToken($name);
        //stockeer le jeton dans un cookie
        setcookie("auth_token", $token, time() + 60, "/");
        header("Location: accueil.php");
    } else {
        echo "Nom d'utilisateur ou mot de passe incorrect.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <form  method="post">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <input type="submit" value="Sign In">
  </form>
</body>
</html>