<?php

namespace Framework;

require_once 'constants.php';

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