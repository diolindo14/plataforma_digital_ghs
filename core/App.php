<?php
class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Verifica inatividade (30 minutos)
        if (isset($_SESSION['user_id']) && isset($_SESSION['last_activity'])) {
            if (time() - $_SESSION['last_activity'] > 1800) {
                session_unset();
                session_destroy();
                session_start();
                $_SESSION['flash_error'] = "Sessão expirada por inatividade. Faça login novamente.";
                header("Location: /green/auth");
                exit;
            }
            $_SESSION['last_activity'] = time(); // Update activity stamp
        }
        
        // Verifica se existe o controlador na pasta
        if (!empty($url) && file_exists('app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

        require_once 'app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Verifica o método na classe
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Pega os parâmetros ou passa array vazio
        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if (isset($_GET['url']) && !empty($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
