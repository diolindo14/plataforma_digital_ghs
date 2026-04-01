<?php
/**
 * MatriculaController - Fluxo de Inscrição Online e Presencial (Refatorado)
 */
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

    /**
     * Submissão Única de Matrícula (Workflow Atómico)
     */
    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URL_ROOT . '/matricula');
            exit;
        }

        try {
            $this->verifyCsrfToken();
            
            $userModel = $this->model('User');
            $estudanteModel = $this->model('Estudante');
            $matriculaModel = $this->model('Matricula');

            // 1. Captura de Dados Básicos
            $nome  = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $bi    = filter_input(INPUT_POST, 'bi', FILTER_SANITIZE_SPECIAL_CHARS);
            
            if (!$nome || !$email || !$bi) {
                $_SESSION['flash_error'] = "Por favor, preencha corretamente o Nome, Email e BI.";
                header('Location: ' . URL_ROOT . '/matricula');
                exit;
            }

            // 2. Orquestração de Utilizador (Conta de Acesso)
            $user_id = null;
            if (isset($_SESSION['user_id']) && ($_POST['tipo_candidatura'] ?? '') === 'Estudante Interno') {
                $user_id = $_SESSION['user_id'];
            } else {
                // Candidato Novo: Verifica se já existe conta
                $existingUser = $userModel->findByEmail($email);
                if ($existingUser) {
                    $user_id = $existingUser['id'];
                } else {
                    $senha_provisoria = 'ghs' . substr($bi, -4);
                    $user_id = $userModel->insertUser($nome, $email, $senha_provisoria, 'aluno', 'ativo');
                }
            }

            if (!$user_id) throw new Exception("Falha ao criar/identificar conta de utilizador.");

            // 3. Orquestração de Perfil (Dados Biográficos)
            $existingEstudante = $estudanteModel->findByUserId($user_id);
            $profileData = [
                'user_id' => $user_id,
                'bi' => $bi,
                'data_nascimento' => $_POST['data_nascimento'] ?? null,
                'nacionalidade' => $_POST['nacionalidade'] ?? 'Guineense',
                'sexo' => $_POST['sexo'] ?? 'M',
                'estado_civil' => $_POST['estado_civil'] ?? 'Solteiro',
                'telefone' => $_POST['telefone'] ?? '',
                'morada' => $_POST['morada'] ?? '',
                'encarregado_nome' => $_POST['encarregado_nome'] ?? '',
                'encarregado_telefone' => $_POST['encarregado_telefone'] ?? '',
                'escola' => $_POST['escola'] ?? 'Externa',
                'ano_conclusao' => $_POST['ano_conclusao'] ?? date('Y'),
                'media' => $_POST['media'] ?? 0
            ];

            if ($existingEstudante) {
                $estudante_id = $existingEstudante['id'];
                $estudanteModel->updateEstudante($estudante_id, $profileData);
            } else {
                $estudante_id = $estudanteModel->createEstudante($profileData);
            }

            if (!$estudante_id) throw new Exception("Erro ao processar o perfil biográfico do aluno.");

            // 4. Verificação de Duplicidade de Matrícula Pendente
            $db = Database::getInstance();
            $stmtCheck = $db->prepare("SELECT status FROM matriculas WHERE estudante_id = :eid AND ano_letivo = :ano AND status != 'Rejeitada' LIMIT 1");
            $stmtCheck->execute([':eid' => $estudante_id, ':ano' => date('Y')]);
            if ($stmtCheck->fetch()) {
                $_SESSION['flash_error'] = "Já possui uma matrícula (Pendente ou Aprovada) para este ano letivo.";
                header('Location: ' . URL_ROOT . '/matricula');
                exit;
            }

            // 5. Criação da Matrícula (Pilar 1: Registro Académico)
            $esp_map = ['Hardware & Robótica' => 1, 'Programação' => 2, 'Banco de Dados' => 3, 'Redes de Computadores' => 4, 'Engenharia Médica' => 5];
            $esp_id = isset($_POST['especializacao']) ? ($esp_map[$_POST['especializacao']] ?? null) : null;

            $matricula_id = $matriculaModel->createEnrollment([
                'user_id' => $estudante_id, // Passamos o ID do estudante correto para o modelo
                'ano_id' => $_POST['ano_id'] ?? 1,
                'turno' => $_POST['turno'] ?? 'Manhã',
                'tipo' => $_POST['tipo_candidatura'] ?? 'Novo Ingresso',
                'especializacao_id' => $esp_id,
                'motivo' => $_POST['motivacao'] ?? 'Inscrição via Portal Online'
            ]);

            if (!$matricula_id) throw new Exception("Erro ao registar intenção de matrícula.");

            // 6. Upload de Documentos (Pilar 3: Integridade)
            $files_map = ['doc_bi' => 'BI', 'doc_foto' => 'Fotografia', 'doc_cert' => 'Certificado', 'doc_comprovativo' => 'Comprovativo_Pagamento'];
            foreach ($files_map as $field => $doc_type) {
                if (isset($_FILES[$field]) && $_FILES[$field]['error'] === 0) {
                    $dest = 'public/uploads/matriculas';
                    $upload = FileHelper::upload($_FILES[$field], $dest, ALLOWED_EXTENSIONS);
                    if ($upload['success']) {
                        $matriculaModel->saveDocument($matricula_id, $doc_type, $upload['fileName'], $dest . '/' . $upload['fileName']);
                    }
                }
            }

            // 7. Notificação e Redirecionamento
            $_SESSION['flash_success'] = "Solicitação enviada com sucesso! Aguarde a aprovação.";
            header('Location: ' . URL_ROOT . '/matricula/sucesso');
            exit;

        } catch (Exception $e) {
            error_log("FALHA MATRÍCULA ONLINE: " . $e->getMessage());
            $_SESSION['flash_error'] = "Ocorreu um erro ao processar: " . $e->getMessage();
            $this->view('home/erro');
            exit;
        }
    }

    public function sucesso() {
        $this->view('home/sucesso');
    }
}
