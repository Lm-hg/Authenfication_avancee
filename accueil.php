<?php
require 'decodage.php';

//verifier si le jeton JWT est présent dans l'en-tête Authorization
$decoder = new decodage();
$decoder->decodeToken();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>

</body>
</html>