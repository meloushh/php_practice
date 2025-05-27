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

        $user = User::GetOne('WHERE email = ?', [$req->post['email']]);
        
        if ($user === null
            || password_verify($req->post['password'], $user->password) === false
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
        $input = App::$si->request->post;

        if ($input['password'] !== $input['repeat_password']) {
            $response = new RedirectResponse('/register', 'Passwords don\'t match');
            $response->Send();
            return;
        }

        if (User::GetOne('WHERE email = ?', [$input['email']])) {
            $response = new RedirectResponse('/register', 'Email is taken');
            $response->Send();
            return;
        }

        unset($input['repeat_password']);

        $input['password'] = password_hash($input['password'], PASSWORD_DEFAULT);
        
        User::Create($input);

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