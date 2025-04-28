<?php

define('BASEDIR', __DIR__);
require_once 'framework/App.php';
require_once 'jinsei/routes.php';
require_once 'jinsei/migrations/migrations.php';

$app = new App(
    $routes,
    [
        MigrationOne::class
    ],
    BASEDIR . '/jinsei/database.sqlite',
    'Jinsei'
);

if (PHP_SAPI == 'cli') {
    $app->HandleCLI();
} else {
    $app->HandleRequest();
}


?>