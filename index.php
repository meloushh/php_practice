<?php

define('BASEDIR', __DIR__);
define('FE_DIR', __DIR__.'/jinsei/frontend');

require_once BASEDIR.'/vendor/autoload.php';

// Do this now cause there might be an error while setting up App construction params
Framework\SetupErrorHandling();

require_once BASEDIR.'/jinsei/routes.php';
require_once BASEDIR.'/jinsei/migrations/migrations.php';
use Framework\App;

$app = new App(
    routes: $routes,
    migrations: $migrations,
    db_path: $db_path,
    name: 'Jinsei',
    encryption_key_path: $encryption_key_path,
);

$app->Run();


?>