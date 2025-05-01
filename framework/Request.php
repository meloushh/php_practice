<?php

enum Protocol {
    case HTTP;
    case HTTPS;
}

class Request {
    public string $uri;
    public string $method;
    public array $get_params;
    public array $post_params;
    public string $body;
    public array $headers;
    public string $protocol = 'http';
}

?>