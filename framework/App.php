<?php

require_once BASEDIR.'/vendor/autoload.php';
require_once 'functions.php';
require_once 'Route.php';
require_once 'Request.php';
require_once 'Response.php';
require_once 'Migrator.php';
require_once 'DB.php';

class App {
    public static App $inst;
    public Request $request;
    public DB $db;
    public string $base_url;



    function __construct(
        public array $routes,
        public array $migrations,
        public string $db_path,
        public string $name,
        public string $encryption_key_path = ''
    ) {
        App::$inst = $this;
        $this->db = new DB($this->db_path);
    }

    function Run() {
        if (PHP_SAPI === 'cli') {
            $this->HandleCLI();
        } else {
            $this->HandleRequest();
        }
    }

    function HandleRequest() {
        $this->base_url = $_SERVER['SERVER_NAME'];

        $this->request = new Request();
        $this->request->method = $_SERVER['REQUEST_METHOD'];
        $this->request->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->request->headers = getallheaders();
        $this->request->get_params = $_GET;
        $this->request->post_params = $_POST;
        $this->request->protocol = str_contains($_SERVER['SERVER_PROTOCOL'], 'HTTP/') ? 'http' : 'https';
        $this->request->body = file_get_contents('php://input');

        // routing
        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
            foreach ($this->routes as $route) {
                $r->addRoute($route->methods, $route->uri, $route->handler);
            }
        });

        $route_info = $dispatcher->dispatch($this->request->method, $this->request->uri);
        switch ($route_info[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                DumpDie(404);
                break;

            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                DumpDie(405);
                break;

            case \FastRoute\Dispatcher::FOUND:
                $controller = new $route_info[1][0];
                call_user_func_array([$controller, $route_info[1][1]], $route_info[2]);
                break;
        }
    }

    function HandleCLI() {
        $commands = ['migrate_up', 'migrate_down', 'db_drop', 'encryption_make_key'];

        if (isset($_SERVER['argv'][1]) === false) {
            echo "COMMANDS: \n\r\n\r";
            $i = 0;
            foreach ($commands as $command) {
                echo $command."\n\r";
                $i++;
            }
            return;
        }

        $cmd = $_SERVER['argv'][1];
        switch ($cmd) {
            case  $commands[0]: {
                $migrator = new Migrator($this->migrations);
                $migrator->up();
                break;
            }
            
            case $commands[1]:
                $migrator = new Migrator($this->migrations);
                $migrator->down();
                break;

            case $commands[2]:
                $this->db->sqlite->close();
                unlink($this->db_path);
                echo 'Deleted database '.$this->db_path;
                break;

            case $commands[3]:
                $secret_key = sodium_crypto_secretbox_keygen();
                echo sodium_bin2hex($secret_key);
                break;

            default:
                echo "Command '{$cmd}' doesn't exist";
                break;
        }
    }

    function SetCookie(string $name, string $val, int $expires = 0) {
        if (setcookie($name, $val, $expires, '/', '.'.$this->base_url) === false) {
            throw new Exception("Failed to set cookie '{$name}'");
        }
    }

    function DeleteCookieIfExists(string $name) {
        if (isset($_COOKIE[$name])) {
            unset($_COOKIE[$name]);
            $this->SetCookie($name, '', time() - 10000);
        }
    }

    function Encrypt($value) {
        $path = $this->encryption_key_path;
        $file = fopen($path, 'r');
        if ($file === false) {
            throw new Exception("Failed to open file '{$this->encryption_key_path}'");
        }
        $key = fread($file, filesize($path));
        fclose($file);

        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = sodium_crypto_secretbox($value, $nonce, sodium_hex2bin($key));
        $result = sodium_bin2base64($nonce.$cipher, SODIUM_BASE64_VARIANT_ORIGINAL);
        if (is_string($value)) {
            sodium_memzero($value);
        }
        sodium_memzero($nonce);
        sodium_memzero($key);

        return $result;
    }
}

?>