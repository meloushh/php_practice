<?php

define('BASEDIR', __DIR__);

require_once 'framework/App.php';
require_once 'jinsei/routes.php';
require_once 'jinsei/migrations/migrations.php';
require_once 'jinsei/config.php';

$app = new App(
    routes: $routes,
    migrations: $migrations,
    db_path: $db_path,
    name: 'Jinsei',
    encryption_key_path: $encryption_key_path,
);

$app->Run();


?>