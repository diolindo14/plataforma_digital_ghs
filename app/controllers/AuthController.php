<?php
/**
 * AuthController - Sentinela de Acesso à Plataforma.
 * 
 * Responsável por gerir o ciclo de vida da autenticação, desde o login inicial 
 * até à recuperação de conta e logout. Implementa múltiplas camadas de 
 * segurança para proteger as contas dos utilizadores.
 */
class AuthController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * Ponto de entrada do portal de autenticação.
     */
    public function index() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole($_SESSION['user_role']);
        }
        $this->view('auth/login');
    }

    /**
     * Processamento de Credenciais e Gestão de Sessão.
     * 
     * Documentação Funcional:
     * 1. Sanitização de entradas.
     * 2. Verificação de bloqueio por força bruta (Brute Force Protection).
     * 3. Validação de Hash de Senha (Argon2/Bcrypt).
     * 4. Regeneração de ID de sessão (Prevenção de Session Fixation).
     * 5. Carregamento de permissões (RBAC).
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);

            if (!$user) {
                $_SESSION['flash_error'] = "Credenciais inválidas.";
                header('Location: ' . URL_ROOT . '/auth');
                exit;
            }

            // Segurança: Proteção contra adivinhação de senhas.
            if ($userModel->isAccountLocked($user)) {
                $bloqueio = strtotime($user['bloqueado_ate']);
                $restantes = ceil(($bloqueio - time()) / 60);
                $_SESSION['flash_error'] = "Conta bloqueada por excesso de tentativas. Tente novamente em $restantes minutos.";
                header('Location: ' . URL_ROOT . '/auth');
                exit;
            }

            if (password_verify($password, $user['senha'])) {
                if ($user['status'] !== 'ativo') {
                    if ($user['status'] === 'pendente') {
                        $_SESSION['flash_error'] = "A sua conta aguarda aprovação administrativa.";
                    } else {
                        $_SESSION['flash_error'] = "Conta inativa ou bloqueada.";
                    }
                    header('Location: ' . URL_ROOT . '/auth');
                    exit;
                }

                $userModel->resetLoginAttempts($user['id']);

                // Prevenção de Session Fixation: Gera novo ID após escalação de privilégios.
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nome_completo'];
                $_SESSION['user_role'] = $user['tipo'];
                $_SESSION['last_activity'] = time();
                $_SESSION['must_change_password'] = ($user['requires_pw_change'] == 1);

                $userModel->updateLastAccess($user['id']);
                $userModel->registrarAcesso($user['id']);
                $this->redirectBasedOnRole($user['tipo']);
            } else {
                $userModel->incrementLoginAttempts($user['id']);
                $_SESSION['flash_error'] = "Credenciais inválidas.";
                header('Location: ' . URL_ROOT . '/auth');
                exit;
            }
        } else {
            header('Location: ' . URL_ROOT . '/auth');
        }
    }

    /**
     * Verificação de Segundo Fator de Autenticação (2FA).
     */
    public function verify2fa() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['pending_2fa_user_id'])) {
            $this->verifyCsrfToken();
            $codigo = $_POST['code'] ?? '';
            $userId = $_SESSION['pending_2fa_user_id'];
            
            $userModel = $this->model('User');
            
            if ($userModel->verify2FACode($userId, $codigo)) {
                $user = $userModel->findById($userId);
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nome_completo'];
                $_SESSION['user_role'] = $user['tipo'];
                $_SESSION['last_activity'] = time();
                unset($_SESSION['pending_2fa_user_id']);
                unset($_SESSION['active_view']);
                $userModel->updateLastAccess($user['id']);
                $userModel->registrarAcesso($user['id']); // Monitorização Invisível
                $this->redirectBasedOnRole($user['tipo']);
            } else {
                $_SESSION['flash_error'] = "Código de verificação incorreto ou expirado.";
                $_SESSION['active_view'] = 'view-2fa';
                header('Location: ' . URL_ROOT . '/auth');
                exit;
            }
        }
        header('Location: ' . URL_ROOT . '/auth');
    }

    /**
     * Registo Público de Alunos.
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $senha = $_POST['password'];
            $tipo = 'estudante'; 

            if (!$email) {
                $_SESSION['flash_error'] = "E-mail inválido.";
                $_SESSION['active_view'] = 'view-register';
                header('Location: ' . URL_ROOT . '/auth');
                exit;
            }

            if (strlen($senha) < 6) {
                $_SESSION['flash_error'] = "A senha deve ter pelo menos 6 caracteres.";
                $_SESSION['active_view'] = 'view-register';
                header('Location: ' . URL_ROOT . '/auth');
                exit;
            }

            $userModel = $this->model('User');
            $userId = $userModel->insertUser($nome, $email, $senha, $tipo);

            if ($userId) {
                $this->model('Estudante')->createEstudante([
                    'user_id' => $userId,
                    'bi' => 'REG-' . strtoupper(substr(uniqid(), -8)),
                    'data_nascimento' => date('Y-m-d', strtotime('-18 years')),
                    'nacionalidade' => 'Guineense',
                    'sexo' => 'Masculino',
                    'estado_civil' => 'Solteiro',
                    'telefone' => '000000000',
                    'morada' => 'A definir',
                    'encarregado_nome' => 'A definir',
                    'encarregado_telefone' => '000000000',
                    'escola' => 'A definir',
                    'ano_conclusao' => date('Y') - 1,
                    'media' => 10.0
                ]);

                $_SESSION['flash_success'] = "Conta criada com sucesso! Aguarde aprovação de um Administrador para iniciar sessão.";
                $_SESSION['active_view'] = 'view-login';
            } else {
                $_SESSION['flash_error'] = "O email fornecido já se encontra registado.";
                $_SESSION['active_view'] = 'view-register';
            }
            header('Location: ' . URL_ROOT . '/auth');
            exit;
        }
    }

    /**
     * Recuperação de Password.
     */
    public function forgot() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $userModel->setRecoveryToken($email, $token);
            }
            $_SESSION['flash_success'] = "Se o email estiver registado, receberá um link de recuperação.";
            $_SESSION['active_view'] = 'view-login';
            header('Location: ' . URL_ROOT . '/auth');
            exit;
        }
    }

    /**
     * Encerramento de Sessão.
     */
    public function logout() {
        session_destroy();
        header('Location: ' . URL_ROOT . '/auth');
    }

    /**
     * Despacho Centralizado de Níveis de Acesso.
     */
    private function redirectBasedOnRole($role) {
        switch ($role) {
            case 'admin': header('Location: ' . URL_ROOT . '/admin'); break;
            case 'estudante':
            case 'aluno': header('Location: ' . URL_ROOT . '/estudante'); break;
            case 'professor': header('Location: ' . URL_ROOT . '/professor'); break;
            case 'secretaria': header('Location: ' . URL_ROOT . '/secretaria'); break;
            default: 
                session_destroy();
                header('Location: ' . URL_ROOT . '/auth'); 
                break;
        }
        exit;
    }
}
