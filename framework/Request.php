<?php

enum Protocol {
    case HTTP;
    case HTTPS;
}

class Request {
    public string $uri;
    public string $method;
    public array $getParams;
    public array $postParams;
    public string $body;
    public array $headers;
    public string $protocol = 'http';
}

?>