<?php
class MatriculaController extends Controller {
    public function index() {
        $this->view('home/matricula');
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            
            $userModel = $this->model('User');
            $estudanteModel = $this->model('Estudante');
            $matriculaModel = $this->model('Matricula');

            // 1. Criar Utilizador (Pendente)
            $bi = filter_input(INPUT_POST, 'bi', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);

            if (!$email || !$bi || !$nome) {
                $_SESSION['flash_error'] = "Dados de contacto ou identificação inválidos.";
                header('Location: ' . URL_ROOT . '/matricula');
                exit;
            }

            $senha_provisoria = 'ghs' . substr($bi, -4);
            // Matrícula online: conta fica ATIVA imediatamente.
            // O aluno pode fazer login e acompanhar o estado da sua matrícula.
            // Só a MATRÍCULA (documentos) ficará 'Pendente' para validação pela Secretaria.
            $user_id = $userModel->insertUser($nome, $email, $senha_provisoria, 'aluno', 'ativo');

            if (!$user_id) {
                $_SESSION['flash_error'] = "Erro ao criar utilizador. Verifique se o email já existe.";
                header('Location: ' . URL_ROOT . '/matricula');
                exit;
            }

            // 2. Criar Estudante
            $estudante_id = $estudanteModel->createEstudante([
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
                'escola' => $_POST['escola'],
                'ano_conclusao' => $_POST['ano_conclusao'],
                'media' => $_POST['media']
            ]);

            // Map Especialização
            $esp_map = ['Hardware & Robótica' => 1, 'Programação' => 2, 'Banco de Dados' => 3, 'Redes de Computadores' => 4, 'Engenharia Médica' => 5];
            $esp_id = isset($_POST['especializacao']) ? ($esp_map[$_POST['especializacao']] ?? null) : null;

            // 3. Criar Matrícula
            $matricula_id = $matriculaModel->createEnrollment([
                'user_id' => $estudante_id,
                'ano_id' => 1,
                'turno' => $_POST['turno'],
                'tipo' => $_POST['tipo_candidatura'],
                'especializacao_id' => $esp_id,
                'motivo' => $_POST['motivacao']
            ]);

            // 4. Upload Seguro de Ficheiros (Pilar 3: FileHelper)
            $files = ['doc_bi' => 'BI', 'doc_foto' => 'Fotografia', 'doc_cert' => 'Certificado', 'doc_comprovativo' => 'Comprovativo_Pagamento'];
            
            foreach ($files as $field => $tipo) {
                if (isset($_FILES[$field]) && $_FILES[$field]['error'] == 0) {
                    $upload = FileHelper::upload($_FILES[$field], 'matriculas', 5); // 5MB limit
                    if ($upload['success']) {
                        $matriculaModel->saveDocument($matricula_id, $tipo, basename($upload['path']), $upload['path']);
                    } else {
                        // Log de falha de segurança ou formato inválido
                        error_log("Falha no upload de $tipo para matrícula $matricula_id: " . $upload['message']);
                    }
                }
            }

            // Sucesso total: armazenar dados na sessão flash para exibir na página de sucesso
            $_SESSION['matricula_senha_provisoria'] = $senha_provisoria;
            $_SESSION['matricula_email'] = $email;
            header('Location: ' . URL_ROOT . '/matricula/sucesso');
            exit;
        }
    }

    public function sucesso() {
        $this->view('home/sucesso');
    }
}
