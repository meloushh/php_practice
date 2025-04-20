<?php

require_once 'framework/App.php';
require_once 'routes.php';

$app = new App($routes);
$app->HandleRequest();

?>