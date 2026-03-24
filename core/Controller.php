<?php
class Controller {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->generateCsrfToken();
    }

    public function model($model) {
        if (file_exists('app/models/' . $model . '.php')) {
            require_once 'app/models/' . $model . '.php';
            return new $model();
        }
        return false;
    }

    public function view($view, $data = []) {
        if (file_exists('app/views/' . $view . '.php')) {
            $render = Closure::bind(function() use ($view, $data) {
                require 'app/views/' . $view . '.php';
            }, $this, get_class($this));
            $render();
        } else {
            $this->logError("View {$view} não encontrada.");
            if (file_exists(__DIR__ . '/../public/error_500.php')) {
                include __DIR__ . '/../public/error_500.php';
            } else {
                echo "Erro técnico: Interface não encontrada.";
            }
            exit;
        }
    }

    // --- SEGURANÇA ---

    // XSS Helper: Escapa HTML para saída segura
    protected function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    // CSRF: Geração de Token
    protected function generateCsrfToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    // CSRF: Verificação de Token
    protected function verifyCsrfToken() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                $this->logError("Tentativa de ataque CSRF ou sessão expirada no IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Desconhecido'));
                $_SESSION['flash_error'] = "A sua sessão de segurança expirou. Por favor, tente novamente.";
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/green/'));
                exit;
            }
        }
    }

    protected function logError($message) {
        $logFile = dirname(__DIR__) . '/app/logs/error.log';
        $formattedMessage = "[" . date('Y-m-d H:i:s') . "] PROD ERROR: " . $message . PHP_EOL;
        error_log($formattedMessage, 3, $logFile);
    }

    // IDOR: Verifica se o recurso pertence ao utilizador logado
    protected function checkOwnership($resource_owner_id) {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != $resource_owner_id) {
            if ($_SESSION['user_role'] !== 'admin') { // Admin pode ver tudo
                $_SESSION['flash_error'] = "Erro: Não tem permissão para aceder a este recurso.";
                header('Location: /green/auth');
                exit;
            }
        }
    }
    // AUDITORIA: Regista ações no sistema
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
            // Falha silenciosa para não quebrar o fluxo principal
            error_log("Erro ao registar log: " . $e->getMessage());
        }
    }
}
