<?php

namespace Framework;

require_once 'constants.php';

class RedirectResponse extends Response {
    function __construct(
        public string $uri,
        public string $notification = ''
    ) {
        if (strlen($this->notification) > 0) {
            SetCookie('notification', $this->notification, 0);
        }

        $this->headers[HEADER_LOCATION] = $uri;
    }
}