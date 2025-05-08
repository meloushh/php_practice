<?php

require_once BASEDIR.'/framework/Route.php';
require_once 'MainController.php';
require_once 'DocumentController.php';

$routes = [
    new Route(['GET'], '/', [MainController::class, 'PageHome']),
    new Route(['POST'], '/login', [MainController::class, 'Login']),
    new Route(['GET'], '/register', [MainController::class, 'PageRegister']),
    new Route(['POST'], '/register', [MainController::class, 'Register']),

    new Route(['GET'], '/documents', [DocumentController::class, 'PageAllDocs']),
    new Route(['POST'], '/documents', [DocumentController::class, 'Create']),
    new Route(['GET'], '/documents/{id:\d+}', [DocumentController::class, 'PageOneDoc']),
    new Route(['POST'], '/documents/{id:\d+}', [DocumentController::class, 'Update']),
    new Route(['POST'], '/documents/{id:\d+}/del', [DocumentController::class, 'Delete'])
];

?>