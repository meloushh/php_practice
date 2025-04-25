<?php

require_once 'App.php';

function DumpDie($var) {
    Dump($var);
    die();
}

function Dump($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

function Url($uri) {
    return App::$inst->request->protocol . '://' . App::$inst->baseUrl . '/' . $uri;
}

?>