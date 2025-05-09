<?php

namespace Framework;

class HtmlResponse extends Response {
    function __construct(
        public string $path,
        public array $data
    ) {}

    function Send() {
        HtmlEngine::Render($this->path, $this->data);
    }
}