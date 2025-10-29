<?php

class Error404Controller {
    public function afficherErreur404() {
        http_response_code(404);
        include __DIR__ . '/../View/error404.php';
    }
}