<?php

function DumpDie(... $vars) {
    call_user_func('var_dump', $vars);
    die();
}

function Dump(... $vars) {
    call_user_func('var_dump', $vars);
}

function GetStringBetween(string $str, string $start, string $end, int $offset = 0) {
    $start_i = strpos($str, $start, $offset) + strlen($start);
    if ($start_i === false)
        throw new Exception("Failed to find start index");

    $end_i = strpos($str, $end, $start_i);
    if ($end_i === false)
        throw new Exception('Failed to find end index');

    return substr($str, $start_i, $end_i - $start_i);
}

function strpos_exception(string $haystack, string $needle, int $offset = 0) {
    $r = strpos($haystack, $needle, $offset);
    if ($r === false) throw new Exception('strpos() exception');
    return $r;
}

?>