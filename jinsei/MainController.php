<?php

require_once 'models.php';

class MainController {
    function PageHome() {
        return new HtmlResponse(__DIR__.'/frontend/homepage.php', [])->Send();
    }

    function Login() {
        $req = App::$si->request;

        $user = User::GetOne('WHERE email = ?', [$req->post_params['email']]);
        
        if ($user === null
            || password_verify($req->post_params['password'], $user->password) === false
        ) {
            return new RedirectResponse('/', 'Invalid credentials')->Send();
        }

        $result = App::$si->Encrypt($user->id);
        App::$si->SetCookie('_a', $result, 0);
        return new RedirectResponse('/documents')->Send();
    }

    function PageRegister() {
        return new HtmlResponse(__DIR__.'/frontend/register.php', [])->Send();
    }

    function Register() {
        $req = App::$si->request;

        if ($req->post_params['password'] !== $req->post_params['repeat_password']) {
            return new RedirectResponse('/register', 'Passwords don\'t match')->Send();
        }

        $data = $req->post_params;
        unset($data['repeat_password']);
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        User::Create($data);
        return new RedirectResponse('/', 'Registration successful')->Send();
    }
}

?>