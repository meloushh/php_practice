<?php

require_once 'constants.php';

class Response {
    public string $body;
    public $headers;
    public $status = 200;

    function Send() {
        http_response_code($this->status);
        foreach ($this->headers as $key => $val) {
            header("{$key}: {$val}");
        }
        echo $this->body;
    }
}

class HtmlResponse extends Response {
    function __construct(string $body) {
        $this->headers[CON_TYPE] = CON_TYPE_HTML;
        $this->body = $body;
    }
}

?>