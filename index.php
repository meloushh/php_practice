<?php

define('BASE_DIR', __DIR__);
define('APP_DIR', __DIR__.'/jinsei');

require_once BASE_DIR.'/vendor/autoload.php';
use Framework\App;

// Do this now cause there might be an error while setting up App construction params
App::SetupErrorHandling();

require_once BASE_DIR.'/jinsei/routes.php';
require_once BASE_DIR.'/jinsei/config.php';

$app = new App(
    name: 'Jinsei',
    routes: $routes,
    migrations: $migrations,
    db_path: $db_path,
    encryption_key_path: $encryption_key_path,
);

$app->Run();


?>