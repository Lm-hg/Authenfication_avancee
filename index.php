<?php
session_start();
// Route detection robust : GET param ?route=..., sinon PATH_INFO, sinon REQUEST_URI
// This works when your server doesn't populate PATH_INFO (common on some setups).
$route = '/accueil';
if (!empty($_GET['route'])) {
    $route = '/' . ltrim($_GET['route'], '/');
} elseif (!empty($_SERVER['PATH_INFO'])) {
    $route = $_SERVER['PATH_INFO'];
} else {
    // fallback to REQUEST_URI and strip base folder
    $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $basePath = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
    if ($basePath !== '' && $basePath !== '/') {
        if (strpos($requestUri, $basePath) === 0) {
            $route = substr($requestUri, strlen($basePath));
        } else {
            $route = $requestUri;
        }
    } else {
        $route = $requestUri;
    }
    // ensure route is not empty
    if (empty($route)) {
        $route = '/accueil';
    }
}


switch ($route) {
        case '/':
        case '/accueil':
            require_once './controller/AccueilController.php';
            $controller = new AccueilController();
            $controller->afficherAccueil();
            break;

        case '/connexion':
            require_once './controller/Connexion.php';
            $controller = new Connexion();
            $controller->SignIn();
            break;

        case '/sign_in':
            require_once './controller/Connexion.php';
            $controller = new Connexion();
            $controller->isUserLoggedIn();
            break;

        case '/logout':
            require_once './controller/Connexion.php';
            $controller = new Connexion();
            $controller->logout();
            break;

        case '/add_spectacle':
            require_once './controller/Spectacles.php';
            $controller = new SpectaclesController();
            $controller->pageAdmin();
            break;
        case '/addSpectacle':
            require_once './controller/Spectacles.php';
            $controller = new SpectaclesController();
            $controller->addSpectacle();
            break;

        case '/mes_reservations':
            require_once './controller/ReservationsController.php';
            $controller = new ReservationsController();
            $controller->afficherMesReservations();
            break;
        case '/reserver':
            require_once './controller/ReservationsController.php';
            $controller = new ReservationsController();
            // show form
            $controller->afficherFormulaireReservation();
            break;
        case '/creer_reservation':
            require_once './controller/ReservationsController.php';
            $controller = new ReservationsController();
            $controller->creerReservation();
            break;
        case '/2fa_setup':
            require_once './controller/TwoFAController.php';
            $c2 = new TwoFAController();
            $c2->setup();
            break;
        case '/2fa_verify':
            require_once './controller/TwoFAController.php';
            $c2 = new TwoFAController();
            $c2->verify();
            break;
        default:
            require_once './controller/Error404Controller.php';
            $controller = new Error404Controller();
            $controller->afficherErreur404();
            break;
                
 
        
    }