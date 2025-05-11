<?php

require_once APP_DIR.'/controllers/MainController.php';
require_once APP_DIR.'/controllers/DocumentController.php';
use Framework\Route;

$routes = [
    new Route(['GET'], '/', [MainController::class, 'PageHome']),
    new Route(['POST'], '/login', [MainController::class, 'Login']),
    new Route(['GET'], '/register', [MainController::class, 'PageRegister']),
    new Route(['POST'], '/register', [MainController::class, 'Register']),
    new ROute(['GET'], '/logout', [MainController::class, 'Logout']),

    new Route(['GET'], '/documents', [DocumentController::class, 'PageAllDocs']),
    new Route(['POST'], '/documents', [DocumentController::class, 'Create']),
    new Route(['GET'], '/documents/{id:\d+}', [DocumentController::class, 'PageOneDoc']),
    new Route(['POST'], '/documents/{id:\d+}', [DocumentController::class, 'Update']),
    new Route(['POST'], '/documents/{id:\d+}/del', [DocumentController::class, 'Delete'])
];

?>