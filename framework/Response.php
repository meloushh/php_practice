<?php

namespace Framework;

abstract class Response {
    public string $body = '';
    public array $headers = [];
    public $status = 200;

    function Send() {
        http_response_code($this->status);
        foreach ($this->headers as $key => $val) {
            header("{$key}: {$val}");
        }
    }
}

?>