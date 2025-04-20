<?php

require_once 'framework/functions.php';
require_once 'framework/Response.php';

class MainController {
    public function homepage() {
        $page = file_get_contents('index.html');
        $response = new HtmlResponse($page);
        $response->Send();
    }
}

?>