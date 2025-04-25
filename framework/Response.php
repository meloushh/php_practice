<?php

require_once 'constants.php';
require_once 'App.php';

class Response {
    public string $body;
    public $headers;
    public $status = 200;

    function Send() {
        http_response_code($this->status);
        foreach ($this->headers as $key => $val) {
            header("{$key}: {$val}");
        }
    }
}

class HtmlResponse extends Response {
    function __construct(public string $path, public array $data) {
        $this->path = $path;
        $this->data = $data;
    }

    function Send()
    {
        $data = $this->data;
        require($this->path);
    }
}

class RedirectResponse extends Response {
    function __construct(public string $uri) {
        $this->headers[HEADER_LOCATION] = $uri;
    }
}

?>