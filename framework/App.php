<?php

namespace Framework;

require_once 'functions.php';
use FastRoute;
use Throwable;
use Exception;
use Framework\HtmlResponse;

class App {
    public static App $si;
    public static string $error_page = '';
    public static bool $debug = false;
    public static string $name = '';

    public static function SetupErrorHandling(string $error_page) {
        App::$error_page = $error_page;
        error_reporting(0);
        set_error_handler('Framework\App::ErrorHandler');
        set_exception_handler('Framework\App::ExceptionHandler');
    }

    public static function ExceptionHandler(Throwable $e): void {
        if (App::$debug) {
            DumpDie($e);
        } else {
            new HtmlResponse(App::$error_page, [])->Send();
        }
    }

    public static function ErrorHandler(int $errno, string $errstr, string $errfile = '', 
        int $errline = 0, array $errcontext = []): bool 
    {
        if (App::$debug) {
            DumpDie($errno, $errstr, $errfile, $errline, $errcontext);
        } else {
            new HtmlResponse(App::$error_page, [])->Send();
        }
    }

    public static function EnableDebugging() {
        App::$debug = true;
        error_reporting(E_ALL);
    }
    


    public Request $request;
    public DB $db;
    public string $base_url;

    function __construct(
        protected array $routes,
        protected array $migrations = [],
        protected string $db_path = '',
        protected string $encryption_key_path = '',
        ?Request $request = null,
    ) {
        App::$si = $this;

        $this->db = new DB($this->db_path);
        if ($request !== null) {
            $this->request = $request;
        }
    }

    function Run() {
        if (PHP_SAPI === 'cli') {
            $this->RunCLI();
        } else {
            $this->RunWeb();
        }
    }

    function RunWeb() {
        $this->base_url = $_SERVER['SERVER_NAME'];

        if (isset($this->request) === false) {
            $this->request = new Request();
            $this->request->method = $_SERVER['REQUEST_METHOD'];
            $this->request->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $this->request->headers = getallheaders();
            $this->request->get_params = $_GET;
            $this->request->post_params = $_POST;
            $this->request->protocol = str_contains($_SERVER['SERVER_PROTOCOL'], 'HTTP/') ? 'http' : 'https';
            $this->request->body = file_get_contents('php://input');
        }

        // routing
        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
            foreach ($this->routes as $route) {
                $r->addRoute($route->methods, $route->uri, $route->handler);
            }
        });

        $route_info = $dispatcher->dispatch($this->request->method, $this->request->uri);
        switch ($route_info[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                new HtmlResponse(App::$error_page, [
                    'message' => '404: Route or resource doesn\'t exist'
                ])->Send();
                break;

            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                new HtmlResponse(App::$error_page, [
                    'message' => '405: HTTP method not allowed'
                ])->Send();
                break;

            case \FastRoute\Dispatcher::FOUND:
                $controller = new $route_info[1][0];
                call_user_func_array([$controller, $route_info[1][1]], $route_info[2]);
                break;

            default:
                new HtmlResponse(App::$error_page, [])->Send();
                break;
        }
    }

    function RunCLI() {
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
        if (setcookie($name, $val, $expires) === false) {
            throw new Exception("Failed to set cookie '{$name}'");
        }
    }

    function DeleteCookie(string $name) {
        $this->SetCookie($name, '', time() - 10000);
    }

    function Encrypt(string $value) {
        $key = file_get_contents($this->encryption_key_path);
        if ($key === false) {
            throw new Exception("Failed to open file '{$this->encryption_key_path}'");
        }

        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
        $cipher = sodium_crypto_secretbox($value, $nonce, sodium_hex2bin($key));
        $result = sodium_bin2base64($nonce.$cipher, SODIUM_BASE64_VARIANT_ORIGINAL);

        sodium_memzero($value);
        sodium_memzero($key);
        sodium_memzero($nonce);
        sodium_memzero($cipher);

        return $result;
    }

    function Decrypt(string $value) {
        $key = file_get_contents($this->encryption_key_path);
        if ($key === false) {
            throw new Exception("Failed to open file '{$this->encryption_key_path}'");
        }

        $cipher = sodium_base642bin($value, SODIUM_BASE64_VARIANT_ORIGINAL);
        $nonce = mb_substr($cipher, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');
        $text = mb_substr($cipher, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

        $plaintext = sodium_crypto_secretbox_open($text, $nonce, sodium_hex2bin($key));
        if ($plaintext === false) {
            throw new Exception("Failed to decrypt");
        }

        sodium_memzero($value);
        sodium_memzero($key);
        sodium_memzero($nonce);
        sodium_memzero($cipher);

        return $plaintext;
    }

    function GetAuthUserId() {
        if (isset($_COOKIE['_a']) === false) {
            return 0;
        }
        $cookie = $_COOKIE['_a'];
        $val = $this->Decrypt($cookie);

        return (int)$val;
    }

    function Url($uri) {
        $return = App::$si->request->protocol . '://' . App::$si->base_url;
        if ($uri[0] !== '/') {
            $return .= '/';
        }
        $return .= $uri;
        return $return;
    }
}

?>