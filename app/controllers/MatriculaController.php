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
                header('Location: /green/matricula');
                exit;
            }

            $senha_provisoria = 'ghs' . substr($bi, -4);
            $user_id = $userModel->insertUser($nome, $email, $senha_provisoria, 'aluno', 'pendente');

            if (!$user_id) {
                $_SESSION['flash_error'] = "Erro ao criar utilizador. Verifique se o email já existe.";
                header('Location: /green/matricula');
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

            // 4. Upload Seguro de Ficheiros
            $upload_dir = 'public/uploads/matriculas/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            
            $files = ['doc_bi' => 'BI', 'doc_foto' => 'Fotografia', 'doc_cert' => 'Certificado', 'doc_comprovativo' => 'Comprovativo_Pagamento'];
            $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png'];
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            
            foreach ($files as $field => $tipo) {
                if (isset($_FILES[$field]) && $_FILES[$field]['error'] == 0) {
                    // Validação MIME Real
                    $realMime = $finfo->file($_FILES[$field]['tmp_name']);
                    if (!in_array($realMime, $allowedMimes)) continue;

                    $ext = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
                    $new_name = $matricula_id . '_' . $field . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    
                    if (move_uploaded_file($_FILES[$field]['tmp_name'], $upload_dir . $new_name)) {
                        $matriculaModel->saveDocument($matricula_id, $tipo, $new_name, $upload_dir . $new_name);
                    }
                }
            }

            header('Location: /green/matricula/sucesso');
            exit;
        }
    }

    public function sucesso() {
        $this->view('home/sucesso');
    }
}
