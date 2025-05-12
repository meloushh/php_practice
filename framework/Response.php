<?php

namespace Framework;

abstract class Response {
    public string $body = '';
    public array $headers = [];
    public $status = 200;

    function Send() {
        foreach ($this->headers as $key => $val) {
            header("{$key}: {$val}");
        }
        die();
    }
}

?>