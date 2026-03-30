<?php
class MatriculaController extends Controller {
    public function index() {
        $data = [];
        if (isset($_SESSION['user_id']) && in_array($_SESSION['user_role'], ['aluno', 'estudante'])) {
            $estudanteModel = $this->model('Estudante');
            $data['student_profile'] = $estudanteModel->findByUserId($_SESSION['user_id']);
            $data['is_internal'] = true;
        }
        $this->view('home/matricula', $data);
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $this->verifyCsrfToken();
                
                $userModel = $this->model('User');
                $estudanteModel = $this->model('Estudante');
                $matriculaModel = $this->model('Matricula');

                // 1. Identificar ou Criar Utilizador
                $bi = filter_input(INPUT_POST, 'bi', FILTER_SANITIZE_SPECIAL_CHARS);
                $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
                $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);

                if (!$email || !$bi || !$nome) {
                    $_SESSION['flash_error'] = "Dados de contacto ou identificação inválidos.";
                    header('Location: ' . URL_ROOT . '/matricula');
                    exit;
                }

                $senha_provisoria = 'ghs' . substr($bi, -4);
                $is_new_user = false;

                // Se já estiver logado como estudante, usamos o ID da sessão
                if (isset($_SESSION['user_id']) && ($_POST['tipo_candidatura'] ?? '') == 'Estudante Interno') {
                    $user_id = $_SESSION['user_id'];
                } else {
                    // Novo utilizador
                    $user_id = $userModel->insertUser($nome, $email, $senha_provisoria, 'aluno', 'ativo');
                    $is_new_user = true;
                    
                    if (!$user_id) {
                        $_SESSION['flash_error'] = "Erro ao criar utilizador. Verifique se o email já existe ou peça recuperação de senha.";
                        header('Location: ' . URL_ROOT . '/matricula');
                        exit;
                    }
                }

                // 2. Criar ou Atualizar Perfil Estudante
                $existingEstudante = $estudanteModel->findByUserId($user_id);
                $estudante_id = null;

                $profileData = [
                    'user_id' => $user_id,
                    'bi' => $bi,
                    'data_nascimento' => $_POST['data_nascimento'],
                    'nacionalidade' => $_POST['nacionalidade'],
                    'sexo' => $_POST['sexo'],
                    'estado_civil' => $_POST['estado_civil'] ?? 'Solteiro',
                    'telefone' => $_POST['telefone'],
                    'morada' => $_POST['morada'],
                    'encarregado_nome' => $_POST['encarregado_nome'],
                    'encarregado_telefone' => $_POST['encarregado_telefone'],
                    'escola' => $_POST['escola'] ?? 'GHS (Interno)',
                    'ano_conclusao' => $_POST['ano_conclusao'] ?? date('Y'),
                    'media' => $_POST['media'] ?? 0
                ];

                if ($existingEstudante) {
                    $estudante_id = $existingEstudante['id'];
                    $estudanteModel->updateEstudante($estudante_id, $profileData);
                } else {
                    $estudante_id = $estudanteModel->createEstudante($profileData);
                }

                if (!$estudante_id) {
                    throw new Exception("Erro ao processar perfil do estudante.");
                }

                // Map Especialização
                $esp_map = ['Hardware & Robótica' => 1, 'Programação' => 2, 'Banco de Dados' => 3, 'Redes de Computadores' => 4, 'Engenharia Médica' => 5];
                $esp_id = isset($_POST['especializacao']) ? ($esp_map[$_POST['especializacao']] ?? null) : null;

                // 3. Criar Matrícula
                $matricula_id = $matriculaModel->createEnrollment([
                    'user_id' => $estudante_id,
                    'ano_id' => 1, // Assume 1º ano para nova inscrição via portal público
                    'turno' => $_POST['turno'],
                    'tipo' => $_POST['tipo_candidatura'],
                    'especializacao_id' => $esp_id,
                    'motivo' => $_POST['motivacao'] ?? 'Inscrição via Portal'
                ]);

                // 4. Upload Seguro de Ficheiros (Pilar 3: FileHelper)
                $files = ['doc_bi' => 'BI', 'doc_foto' => 'Fotografia', 'doc_cert' => 'Certificado', 'doc_comprovativo' => 'Comprovativo_Pagamento'];
                
                foreach ($files as $field => $tipo) {
                    if (isset($_FILES[$field]) && $_FILES[$field]['error'] == 0) {
                        $dest = 'public/uploads/matriculas';
                        $upload = FileHelper::upload($_FILES[$field], $dest, ALLOWED_EXTENSIONS);
                        
                        if ($upload['success']) {
                            $matriculaModel->saveDocument($matricula_id, $tipo, $upload['fileName'], $dest . '/' . $upload['fileName']);
                        } else {
                            // Log de falha de segurança ou formato inválido
                            error_log("Falha no upload de $tipo para matrícula $matricula_id: " . $upload['message']);
                        }
                    }
                }

                // Sucesso total: armazenar dados na sessão flash para exibir na página de sucesso
                if (!isset($is_new_user) || !$is_new_user) {
                    $_SESSION['is_internal_enrollment'] = true;
                }
                
                header('Location: ' . URL_ROOT . '/matricula/sucesso');
                exit;

            } catch (Exception $e) {
                error_log("Erro Crítico na Submissão de Matrícula: " . $e->getMessage());
                $this->view('home/erro');
                exit;
            }
        }
    }

    public function sucesso() {
        $this->view('home/sucesso');
    }
}
