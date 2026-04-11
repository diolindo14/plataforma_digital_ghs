<?php
/**
 * Controlador Base (Base Controller)
 * 
 * @package Core
 * @author Senior Software Engineer / Mentor
 * 
 * Documentação Funcional:
 * Esta classe serve como fundação para todos os controladores do sistema (Admin, Secretaria, Estudante, etc.).
 * Ela centraliza funções vitais como carregamento de modelos, renderização de vistas e protocolos de segurança.
 */
class Controller {
    /**
     * Construtor do Controlador Base.
     * Garante que a sessão esteja ativa e o token CSRF gerado.
     */
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->generateCsrfToken();
    }

    /**
     * Fabrica de Modelos (Dependency Injection Manual)
     * 
     * @param string $model Nome do ficheiro do modelo em app/models/
     * @return object|false Retorna a instância do modelo ou false se não existir.
     */
    public function model($model) {
        if (file_exists('app/models/' . $model . '.php')) {
            require_once 'app/models/' . $model . '.php';
            return new $model();
        }
        // // Identificação de Pontos Cegos: Retornar 'false' pode causar "fatal errors" 
        // // se o controlador tentar chamar um método no retorno sem validar.
        // // Sugestão: Lançar uma Exception ou usar NullObject Pattern.
        return false;
    }

    /**
     * Motor de Renderização de Vistas
     * 
     * @param string $view Caminho relativo em app/views/
     * @param array $data Dados a serem passados para a interface
     */
    public function view($view, $data = []) {
        if (file_exists('app/views/' . $view . '.php')) {
            // // Refatoração / Clean Code: O uso de Closure::bind serve para isolar o escopo da vista, 
            // // permitindo que a variável $this dentro do ficheiro .php da vista se refira ao controlador
            // // que a chamou (Permitindo acesso a métodos protegidos como $this->e()).
            $render = Closure::bind(function() use ($view, $data) {
                require 'app/views/' . $view . '.php';
            }, $this, get_class($this));
            $render();
        } else {
            // // Gargalo de Performance: Verificações de file_exists constantes podem ser otimizadas 
            // // com cache opcache em produção.
            $this->logError("View {$view} não encontrada.");
            if (file_exists(__DIR__ . '/../public/error_500.php')) {
                include __DIR__ . '/../public/error_500.php';
            } else {
                echo "Erro técnico: Interface não encontrada.";
            }
            exit;
        }
    }

    // --- SEGURANÇA (ESTADO DA ARTE) ---

    /**
     * XSS Helper: Escapa HTML para saída segura.
     * Use sempre que exibir dados vindos da base de dados ou input de utilizador.
     */
    protected function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * CSRF: Geração de Token Único de Sessão.
     * Previne ataques Cross-Site Request Forgery.
     */
    protected function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    /**
     * CSRF: Validação de Token.
     * Deve ser chamado no início de qualquer método de POST nos controladores filhos.
     */
    protected function verifyCsrfToken() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $this->logError("Tentativa de ataque CSRF ou sessão expirada no IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Desconhecido'));
                $_SESSION['flash_error'] = "A sua sessão de segurança expirou. Por favor, tente novamente.";
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? URL_ROOT));
                exit;
            }
        }
    }

    /**
     * Sistema de Log de Erros.
     */
    protected function logError($message) {
        $logFile = dirname(__DIR__) . '/app/logs/error.log';
        $formattedMessage = "[" . date('Y-m-d H:i:s') . "] PROD ERROR: " . $message . PHP_EOL;
        error_log($formattedMessage, 3, $logFile);
    }

    /**
     * IDOR (Insecure Direct Object Reference) Protection.
     * Verifica se o utilizador logado tem direito a aceder ao recurso solicitado.
     */
    protected function checkOwnership($resource_owner_id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $resource_owner_id) {
            if ($_SESSION['user_role'] !== 'admin') { 
                // // Lógica de Negócio: O Admin tem permissão bypass (Super User).
                $_SESSION['flash_error'] = "Erro: Não tem permissão para aceder a este recurso.";
                header('Location: ' . URL_ROOT . '/auth');
                exit;
            }
        }
    }

    /**
     * Middleware de Autorização: Verifica permissões de acesso baseado em roles.
     * @param string $role Papel exigido (admin, professor, estudante, secretaria).
     */
    protected function checkRole($role) {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $role) {
            if ($_SESSION['user_role'] !== 'admin') { 
                // // Lógica de Negócio: O Admin tem permissão bypass (Super User).
                $_SESSION['flash_error'] = "Erro: Não tem permissão para aceder a este recurso.";
                header('Location: ' . URL_ROOT . '/auth');
                exit;
            }
        }
    }

    /**
     * AUDITORIA: Regista todas as ações críticas no sistema.
     * Essencial para conformidade e rastreabilidade académica/financeira.
     */
    protected function logActivity($acao, $detalhes = null) {
        if (!isset($_SESSION['user_id'])) return;
        
        try {
            $db = Database::getInstance();
            $stmt = $db->prepare("INSERT INTO logs_atividades (utilizador_id, acao, detalhes, ip_address) VALUES (:uid, :acao, :det, :ip)");
            $stmt->execute([
                ':uid' => $_SESSION['user_id'],
                ':acao' => $acao,
                ':det' => is_array($detalhes) ? json_encode($detalhes) : $detalhes,
                ':ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'
            ]);
        } catch (\Exception $e) {
            // // Sugestão: A falha no log não deve interromper a vida do utilizador, 
            // // mas deve ser reportada silenciosamente ao SysAdmin.
            error_log("Erro ao registar log: " . $e->getMessage());
        }
    }
}
