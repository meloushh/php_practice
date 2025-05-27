<?php

namespace Framework;

class Request {
    public string $uri;
    public string $method;
    public array $get;
    public array $post;
    public string $body;
    public array $headers;
    public string $protocol = 'http';
}

?>