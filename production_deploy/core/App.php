<?php
/**
 * App - Motor de Roteamento e Inicialização (Bootstrap).
 * 
 * Esta classe é o ponto de entrada da lógica MVC. Ela analisa a URL amigável, 
 * gere a persistência de sessões por inatividade e despacha a execução 
 * para o controlador e método correspondentes.
 */
class App {
    /** @var string Controlador padrão caso nenhum seja especificado na URL */
    protected $controller = 'HomeController';
    
    /** @var string Método padrão do controlador */
    protected $method = 'index';
    
    /** @var array Parâmetros adicionais da URL */
    protected $params = [];

    /**
     * Ciclo de Vida do Roteamento:
     * 1. Sanitização da URL.
     * 2. Gestão de Segurança de Sessão (Time-to-Live).
     * 3. Mapeamento Dinâmico de Controladores (Autoloading Lógico).
     * 4. Invocação via Reflexão com 'call_user_func_array'.
     */
    public function __construct() {
        $url = $this->parseUrl();

        // Camada de Segurança: Expiração Automática de Sessão (Pilar 3)
        if (isset($_SESSION['user_id']) && isset($_SESSION['last_activity'])) {
            if (time() - $_SESSION['last_activity'] > SESSION_LIFETIME) {
                session_unset();
                session_destroy();
                session_start();
                $_SESSION['flash_error'] = "Sessão expirada por inatividade. Faça login novamente.";
                header("Location: " . URL_ROOT . "/auth");
                exit;
            }
            $_SESSION['last_activity'] = time(); // Renovação do carimbo de atividade
        }
        
        // Mapeamento de Controlador: Verifica se o ficheiro físico existe
        if (!empty($url) && file_exists('app/controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

        // Instanciação Dinâmica
        require_once 'app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Mapeamento de Método
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Extração de Argumentos (Params) e Despacho (Dispatch)
        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Processamento de URL Amigável.
     * Transforma 'dominio.com/controller/method/param' em um array indexado.
     * 
     * @return array
     */
    public function parseUrl() {
        if (isset($_GET['url']) && !empty($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
