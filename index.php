<?php

define('BASEDIR', __DIR__);
require_once 'framework/App.php';
require_once 'jinsei/routes.php';
require_once 'jinsei/migrations/migrations.php';

$app = new App(
    routes: $routes,
    migrations: [
        CreateUsers::class,
        CreateDocuments::class
    ],
    db_path: BASEDIR . '/jinsei/database.sqlite',
    name: 'Jinsei'
);

$app->Run();


?>