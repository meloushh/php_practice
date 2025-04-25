<?php

require_once 'framework/App.php';
require_once 'MainController.php';

$routes = [
    new Route(['GET'], '/', [MainController::class, 'Homepage']),
    new Route(['POST'], '/documents', [MainController::class, 'CreateDocument']),
    new Route(['GET'], '/documents/{id:\d+}', [MainController::class, 'Document'])
];

?>