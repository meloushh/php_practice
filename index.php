<?php

define('BASEDIR', __DIR__);
require_once 'framework/App.php';
require_once 'life_app/routes.php';
require_once 'life_app/migrations/migrations.php';

$app = new App(
    $routes,
    [
        MigrationOne::class
    ],
    BASEDIR . '/life_app/database.sqlite'
);

if (PHP_SAPI == 'cli') {
    $app->HandleCLI();
} else {
    $app->HandleRequest();
}


?>