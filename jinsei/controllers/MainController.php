<?php

require_once APP_DIR.'/models.php';
use Framework\App;
use Framework\HtmlResponse;
use Framework\RedirectResponse;

class MainController {
    function __construct() {
        $app = App::$si;

        if ($app->GetAuthUserId() !== 0) {
            if ($app->request->uri === '/logout') {
                return;
            }

            $response = new RedirectResponse('/documents');
            $response->Send();
            return;
        }
    }

    function PageHome() {
        $response = new HtmlResponse(APP_DIR.'/fe/homepage.php', []);
        $response->Send();
    }

    function Login() {
        $req = App::$si->request;

        $user = User::GetOne('WHERE email = ?', [$req->post_params['email']]);
        
        if ($user === null
            || password_verify($req->post_params['password'], $user->password) === false
        ) {
            $response = new RedirectResponse('/', 'Invalid credentials');
            $response->Send();
            return;
        }

        $result = App::$si->Encrypt($user->id);
        App::$si->SetCookie('_a', $result, 0);
        $response = new RedirectResponse('/documents');
        $response->Send();
    }

    function PageRegister() {
        $response = new HtmlResponse(APP_DIR.'/fe/register.php', []);
        $response->Send();
    }

    function Register() {
        $req = App::$si->request;

        if ($req->post_params['password'] !== $req->post_params['repeat_password']) {
            $response = new RedirectResponse('/register', 'Passwords don\'t match');
            $response->Send();
            return;
        }

        $data = $req->post_params;
        unset($data['repeat_password']);
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        User::Create($data);
        $response = new RedirectResponse('/', 'Registration successful');
        $response->Send();
    }

    function Logout() {
        App::$si->DeleteCookie('_a');
        $response = new RedirectResponse('/');
        $response->Send();
    }
}

?>