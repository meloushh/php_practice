<?php

function DumpDie($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    die();
}

?>