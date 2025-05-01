<?php

require_once 'models.php';

class MainController {
    function Homepage() {
        $response = new HtmlResponse(__DIR__.'/frontend/homepage.php', []);
        $response->Send();
    }

    function Login() {
        $req = App::$inst->request;
        $user = User::GetOne('WHERE email = ?', [$req->post_params['email']]);
    }

    function PageRegister() {
        $response = new HtmlResponse(__DIR__.'/frontend/register.php', []);
        $response->Send();
    }

    function Register() {
        //
    }
}

?>