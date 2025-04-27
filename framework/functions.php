<?php

require_once 'App.php';

function DumpDie(... $vars) {
    call_user_func('var_dump', $vars);
    die();
}

function Dump(... $vars) {
    call_user_func('var_dump', $vars);
}

function Url($uri) {
    return App::$inst->request->protocol . '://' . App::$inst->baseUrl . '/' . $uri;
}

function GetStringBetween(string $str, string $start, string $end, int $offset = 0) {
    $startI = strpos($str, $start, $offset) + strlen($start);
    if ($startI === false)
        throw new Exception("Failed to find start index");

    $endI = strpos($str, $end, $startI);
    if ($endI === false)
        throw new Exception('Failed to find end index');

    return substr($str, $startI, $endI - $startI);
}

function strpos_exception(string $haystack, string $needle, int $offset = 0) {
    $r = strpos($haystack, $needle, $offset);
    if ($r === false) throw new Exception('strpos() exception');
    return $r;
}

?>