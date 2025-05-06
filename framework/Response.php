<?php

require_once 'constants.php';
require_once 'App.php';
require_once 'HtmlEngine.php';

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

class HtmlResponse extends Response {
    function __construct(
        public string $path,
        public array $data
    ) {}

    function Send() {
        HtmlEngine::RenderPage($this->path, $this->data);
    }
}

class RedirectResponse extends Response {
    function __construct(
        public string $uri,
        public string $message = ''
    ) {
        if (strlen($this->message) > 0) {
            SetCookie('message', $this->message, 0);
        }

        $this->headers[HEADER_LOCATION] = $uri;
    }
}

?>