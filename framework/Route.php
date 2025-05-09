<?php

namespace Framework;

class Route {
    public array $methods;
    public string $uri;
    public array $handler;
    
    function __construct(array $methods, string $uri, array $handler) {
        $this->methods = $methods;
        $this->uri = $uri;
        $this->handler = $handler;
    }
}