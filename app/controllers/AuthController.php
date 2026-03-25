<?php
class AuthController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if (isset($_SESSION['user_id'])) {
            $this->redirectBasedOnRole($_SESSION['user_role']);
        }
        $this->view('auth/login');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);

            if (!$user) {
                $_SESSION['flash_error'] = "Credenciais inválidas.";
                header('Location: /green/auth');
                exit;
            }

            // Brute force check
            if ($userModel->isAccountLocked($user)) {
                $bloqueio = strtotime($user['bloqueado_ate']);
                $restantes = ceil(($bloqueio - time()) / 60);
                $_SESSION['flash_error'] = "Conta bloqueada por excesso de tentativas. Tente novamente em $restantes minutos.";
                header('Location: /green/auth');
                exit;
            }

            if (password_verify($password, $user['senha'])) {
                if ($user['status'] !== 'ativo') {
                    // Check if pending email
                    if ($user['status'] === 'pendente') {
                        $_SESSION['flash_error'] = "A sua conta aguarda aprovação administrativa.";
                    } else {
                        $_SESSION['flash_error'] = "Conta inativa ou bloqueada.";
                    }
                    header('Location: /green/auth');
                    exit;
                }

                $userModel->resetLoginAttempts($user['id']);

                // ----------------------------------------------------
                // Bypass de 2FA para testes locais habilitado
                // ----------------------------------------------------
                /* 
                $codigo_2fa = sprintf("%06d", mt_rand(1, 999999));
                $userModel->set2FACode($user['id'], $codigo_2fa);
                // mail($user['email'], "Seu código de acesso", "Código: " . $codigo_2fa);
                $_SESSION['pending_2fa_user_id'] = $user['id'];
                $_SESSION['active_view'] = 'view-2fa';
                header('Location: /green/auth');
                exit;
                */

                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nome_completo'];
                $_SESSION['user_role'] = $user['tipo'];
                $_SESSION['last_activity'] = time();
                $_SESSION['must_change_password'] = ($user['requires_pw_change'] == 1);

                $userModel->updateLastAccess($user['id']);
                $this->redirectBasedOnRole($user['tipo']);
            } else {
                $userModel->incrementLoginAttempts($user['id']);
                $_SESSION['flash_error'] = "Credenciais inválidas.";
                header('Location: /green/auth');
                exit;
            }
        } else {
            header('Location: /green/auth');
        }
    }

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
                $this->redirectBasedOnRole($user['tipo']);
            } else {
                $_SESSION['flash_error'] = "Código de verificação incorreto ou expirado.";
                $_SESSION['active_view'] = 'view-2fa';
                header('Location: /green/auth');
                exit;
            }
        }
        header('Location: /green/auth');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            
            $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $senha = $_POST['password'];
            $tipo = 'estudante'; // Apenas estudantes podem ser criados via formulário público


            if (!$email) {
                $_SESSION['flash_error'] = "E-mail inválido.";
                $_SESSION['active_view'] = 'view-register';
                header('Location: /green/auth');
                exit;
            }

            if (strlen($senha) < 6) {
                $_SESSION['flash_error'] = "A senha deve ter pelo menos 6 caracteres.";
                $_SESSION['active_view'] = 'view-register';
                header('Location: /green/auth');
                exit;
            }

            $userModel = $this->model('User');
            $userId = $userModel->insertUser($nome, $email, $senha, $tipo);

            if ($userId) {
                if ($tipo === 'estudante') {
                    $estudanteModel = $this->model('Estudante');
                    $estudanteModel->createEstudante([
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
                } else if ($tipo === 'professor') {
                    $profModel = $this->model('Professor');
                    $profModel->createProfessor([
                        'user_id' => $userId,
                        'bi' => 'PRF-' . strtoupper(substr(uniqid(), -8)),
                        'telefone' => '000000000',
                        'especialidade' => 'A definir',
                        'grau_academico' => 'Licenciatura'
                    ]);
                }

                $_SESSION['flash_success'] = "Conta criada com sucesso! Faça login.";
                $_SESSION['active_view'] = 'view-login';
            } else {
                $_SESSION['flash_error'] = "O email fornecido já se encontra registado.";
                $_SESSION['active_view'] = 'view-register';
            }
            header('Location: /green/auth');
            exit;
        }
    }

    public function forgot() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $userModel = $this->model('User');
            $user = $userModel->findByEmail($email);

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $userModel->setRecoveryToken($email, $token);
                // Em ambiente real: mail($email, "Recuperação", "Link: /auth/reset?token=" . $token);
            }
            $_SESSION['flash_success'] = "Se o email estiver registado, receberá um link de recuperação.";
            $_SESSION['active_view'] = 'view-login';
            header('Location: /green/auth');
            exit;
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /green/auth');
    }

    private function redirectBasedOnRole($role) {
        switch ($role) {
            case 'admin': header('Location: /green/admin'); break;
            case 'estudante':
            case 'aluno': header('Location: /green/estudante'); break;
            case 'professor': header('Location: /green/professor'); break;
            case 'secretaria': header('Location: /green/secretaria'); break;
            default: 
                // Evitar loops infinitos se a sessão estiver corrompida (role em branco antigo)
                session_destroy();
                header('Location: /green/auth'); 
                break;
        }
        exit;
    }
}
