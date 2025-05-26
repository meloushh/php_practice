<?php

define('BASE_DIR', __DIR__);
define('APP_DIR', __DIR__.'/jinsei');

require_once BASE_DIR.'/vendor/autoload.php';
use Framework\App;

// Do this now cause there might be an error while setting up App construction params
App::SetupErrorHandling(APP_DIR.'/fe/error.php');
App::$name = 'Jinsei';

require_once BASE_DIR.'/jinsei/config.php';
if ($debug) {
    App::EnableDebugging();
}

require_once BASE_DIR.'/jinsei/routes.php';

$app = new App(
    routes: $routes,
    migrations: $migrations,
    migrations_path: APP_DIR.'/migrations/migrations.php',
    db_path: $db_path,
    encryption_key_path: $encryption_key_path,
);

$app->Run();

?>