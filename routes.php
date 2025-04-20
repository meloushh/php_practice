<?php

require_once 'framework/App.php';
require_once 'MainController.php';

$routes = [
    new Route(['GET'], '/', [MainController::class, 'homepage'])
];

?>