<?php
class ProfessorController extends Controller {
    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'professor') {
            header('Location: ' . URL_ROOT . '/auth');
            exit;
        }
    }

    public function index() {
        $profModel = $this->model('Professor');
        $profData = $profModel->findByUserId($_SESSION['user_id']);
        
        if (!$profData) {
            $_SESSION['flash_error'] = "Perfil de professor não encontrado para este utilizador.";
            header('Location: ' . URL_ROOT . '/auth');
            exit;
        }

        $classes = $profModel->getAssignedClasses($profData['id']);
        
        $selected_turma = $_GET['turma_id'] ?? (!empty($classes) ? $classes[0]['turma_id'] : null);
        $selected_disciplina = $_GET['disciplina_id'] ?? (!empty($classes) ? $classes[0]['disciplina_id'] : null);
        
        $horarioModel = $this->model('Horario');
        $comunicadoModel = $this->model('Comunicado');
        $notaModel = $this->model('Nota');
        $frequenciaModel = $this->model('Frequencia');

        $todos_eventos = $this->model('Evento')->getForProfessor($profData['id']);
        $agendamentos_proprios = [];
        $eventos_globais_raw = [];
        
        foreach($todos_eventos as $ev) {
            if ($ev['criado_por'] == $_SESSION['user_id']) {
                $agendamentos_proprios[] = $ev;
            } else {
                $eventos_globais_raw[] = $ev;
            }
        }

        $eventos_globais = [];
        foreach($eventos_globais_raw as $ev) {
            $key = $ev['titulo'] . '_' . $ev['tipo'];
            if (!isset($eventos_globais[$key])) {
                $ev['data_fim'] = $ev['data_evento'];
                $eventos_globais[$key] = $ev;
            } else {
                if (strtotime($ev['data_evento']) > strtotime($eventos_globais[$key]['data_fim'])) {
                    $eventos_globais[$key]['data_fim'] = $ev['data_evento'];
                }
                if (strtotime($ev['data_evento']) < strtotime($eventos_globais[$key]['data_evento'])) {
                    $eventos_globais[$key]['data_evento'] = $ev['data_evento'];
                }
            }
        }

        $data = [
            'professor' => $profData,
            'classes' => $classes,
            'students' => $selected_turma ? $profModel->getStudentsByTurma($selected_turma) : [],
            'horario' => $horarioModel->getHorarioByProfessor($profData['id']),
            'comunicados' => $comunicadoModel->getComunicadosParaUtilizador($_SESSION['user_id'], 'professor'),
            'notas' => ($selected_turma && $selected_disciplina) ? $notaModel->getNotasByTurma($selected_turma, $selected_disciplina) : [],
            'meus_sumarios' => $frequenciaModel->getSummariesByProfessor($profData['id']),
            'meus_materiais' => $this->model('Material')->getByProfessor($profData['id']),
            'meus_eventos' => $todos_eventos,
            'agendamentos_proprios' => $agendamentos_proprios,
            'eventos_globais' => array_values($eventos_globais),
            'reclamacoes' => $notaModel->getFeedbacksParaProfessor($profData['id']),
            'minha_assiduidade' => $frequenciaModel->getDetailedAttendanceForProfessor($profData['id']),
            'selected_turma' => $selected_turma,
            'selected_disciplina' => $selected_disciplina,
        ];
        
        // Filtrar horário de hoje
        $diasSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
        $hojeStr = $diasSemana[date('w')];
        
        $data['horario_hoje'] = array_filter($data['horario'], function($h) use ($hojeStr) {
            return $h['dia_semana'] == $hojeStr;
        });
        $data['hoje'] = $hojeStr;
        $data['tempos_aula'] = [
            '1º' => ['07:20', '08:50'],
            '2º' => ['08:55', '10:25'],
            '3º' => ['10:45', '12:15'],
            '4º' => ['12:20', '13:50'],
            'T1' => ['13:00', '14:30'],
            'T2' => ['14:35', '16:05'],
            'T3' => ['16:10', '17:40'],
            'T4' => ['17:45', '19:15'],
            'N1' => ['17:45', '19:15'],
            'N2' => ['19:20', '20:50'],
            'N3' => ['21:00', '22:30'],
            'N4' => ['22:35', '24:00']
        ];
        $data['gridData'] = $horarioModel->buildWeeklyGridForProfessor($data['professor']['id']);
        $data['dias_semana'] = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];

        // --- 🏆 MÉRITO ACADÉMICO ---
        $acadRank = $this->model('Academico');
        $data['ranking_escola'] = $acadRank->getRankingEscola(3);
        $data['ranking_nivel']  = $acadRank->getRankingByNivel();

        $this->view('professor/dashboard', $data);
    }

    public function saveNota() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $notaModel = $this->model('Nota');
            $res = $notaModel->saveNotasRow($_POST);
            if ($res) {
                $this->logActivity('Lançar Nota', ['turma_id' => $_POST['turma_id'] ?? 'N/A']);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao guardar notas na base de dados.']);
            }
            exit;
        }
    }

    public function saveComunicado() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $model = $this->model('Comunicado');
            $res = $model->create($_POST);
            if ($res) $this->logActivity('Professor Enviar Comunicado', ['titulo' => $_POST['titulo'] ?? 'N/A']);
            echo json_encode(['success' => $res]);
            exit;
        }
    }

    public function deleteComunicado() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
            $this->verifyCsrfToken();
            $model = $this->model('Comunicado');
            $res = $model->delete($_POST['id'], $_SESSION['user_id']);
            if ($res) $this->logActivity('Professor Remover Comunicado', ['id' => $_POST['id']]);
            echo json_encode(['success' => $res]);
            exit;
        }
        echo json_encode(['success' => false]);
        exit;
    }


    public function marcarLido() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comunicado_id'])) {
            $comModel = $this->model('Comunicado');
            $success = $comModel->marcarComoLido($_POST['comunicado_id'], $_SESSION['user_id']);
            echo json_encode(['success' => $success]);
            exit;
        }
        echo json_encode(['success' => false]);
        exit;
    }

    public function saveSummary() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $model = $this->model('Frequencia');
            $res = $model->saveSummary($_POST);
            if ($res) {
                $this->logActivity('Lançar Sumário', ['turma_id' => $_POST['turma_id'] ?? 'N/A']);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao salvar o sumário institucional.']);
            }
            exit;
        }
    }

    public function uploadMaterial() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['ficheiro'])) {
            echo json_encode(['success' => false, 'message' => 'Pedido inválido.']);
            exit;
        }
        $this->verifyCsrfToken();

        $profModel = $this->model('Professor');
        $profData  = $profModel->findByUserId($_SESSION['user_id']);

        $targetDir = 'public/uploads/materiais/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

        // --- Validação Segura de Upload ---
        $maxSize      = 20 * 1024 * 1024; // 20 MB
        $allowedMimes = [
            'application/pdf',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/zip', 'application/x-zip-compressed',
            'image/jpeg', 'image/png'
        ];
        $allowedExts  = ['pdf', 'ppt', 'pptx', 'doc', 'docx', 'zip', 'jpg', 'jpeg', 'png'];

        if ($_FILES['ficheiro']['size'] > $maxSize) {
            echo json_encode(['success' => false, 'message' => 'Ficheiro demasiado grande (máx. 20MB).']);
            exit;
        }

        $origExt = strtolower(pathinfo($_FILES['ficheiro']['name'], PATHINFO_EXTENSION));
        if (!in_array($origExt, $allowedExts, true)) {
            echo json_encode(['success' => false, 'message' => 'Tipo de ficheiro não permitido.']);
            exit;
        }

        $realMime = 'application/octet-stream';
        if (class_exists('finfo')) {
            $finfo    = new finfo(FILEINFO_MIME_TYPE);
            $realMime = $finfo->file($_FILES['ficheiro']['tmp_name']);
        } else {
            $realMime = $_FILES['ficheiro']['type'] ?? 'application/octet-stream';
        }

        if (!in_array($realMime, $allowedMimes, true)) {
            echo json_encode(['success' => false, 'message' => 'Tipo MIME inválido.']);
            exit;
        }

        $safeFilename = 'MAT_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $origExt;

        if (!move_uploaded_file($_FILES['ficheiro']['tmp_name'], $targetDir . $safeFilename)) {
            echo json_encode(['success' => false, 'message' => 'Erro ao guardar ficheiro.']);
            exit;
        }

        $materialModel = $this->model('Material');
        $this->logActivity('Upload de Material', ['titulo' => $_POST['titulo'] ?? 'N/A']);
        $res = $materialModel->create([
            'turma_id'       => $_POST['turma_id'],
            'disciplina_id'  => $_POST['disciplina_id'],
            'professor_id'   => $profData['id'],
            'titulo'         => $_POST['titulo'],
            'nome_ficheiro'  => $_FILES['ficheiro']['name'],
            'caminho_ficheiro' => $targetDir . $safeFilename,
            'tipo_ficheiro'  => $origExt
        ]);

        echo json_encode(['success' => $res]);
        exit;
    }

    public function saveRespostaReclamacao() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: application/json');
            $this->verifyCsrfToken();
            $estudante_id = $_POST['estudante_id'] ?? null;
            $turma_id = $_POST['turma_id'] ?? null;
            $disciplina_id = $_POST['disciplina_id'] ?? null;
            $resposta = $_POST['resposta_professor'] ?? null;
            
            if ($estudante_id && $turma_id && $disciplina_id && $resposta) {
                $notaModel = $this->model('Nota');
                $res = $notaModel->responderReclamacao($estudante_id, $turma_id, $disciplina_id, $resposta);
                
                if ($res) {
                    $this->logActivity('Resposta à Reclamação', ['estudante_id' => $estudante_id]);
                    
                    // Notificação automática ao aluno via MensagemModel
                    $estudanteModel = $this->model('Estudante');
                    $est = $estudanteModel->findById($estudante_id);
                    
                    if ($est && !empty($est['utilizador_id'])) {
                        $msgModel = $this->model('Mensagem');
                        $conteudo = "O Professor respondeu à sua reclamação de nota: \"" . $resposta . "\"";
                        $msgModel->send($_SESSION['user_id'], $est['utilizador_id'], "Resposta a Reclamação de Nota", $conteudo);
                    }
                    
                    echo json_encode(['success' => true]);
                    exit;
                }
            }
            echo json_encode(['success' => false]);
            exit;
        }
    }

    public function removerComunicado() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken();
            $comunicadoId = $_POST['comunicado_id'] ?? null;
            if ($comunicadoId) {
                $compModel = $this->model('Comunicado');
                $res = $compModel->excluirParaUtilizador($_SESSION['user_id'], $comunicadoId);
                header('Content-Type: application/json');
                echo json_encode(['success' => $res]);
                exit;
            }
        }
    }

    public function saveEvento() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $eventoModel = $this->model('Evento');
            if ($eventoModel->create($_POST)) {
                $this->logActivity('Professor Agendar Evento', ['titulo' => $_POST['titulo'] ?? 'N/A']);
                $_SESSION['flash_success'] = "Evento agendado com sucesso!";
            } else {
                $_SESSION['flash_error'] = "Erro ao agendar evento.";
            }
            header('Location: ' . URL_ROOT . '/professor/dashboard');
        }
    }

    public function deleteEvento($id) {
        $eventoModel = $this->model('Evento');
        if ($eventoModel->delete($id)) {
            $this->logActivity('Professor Remover Evento', ['id' => $id]);
            $_SESSION['flash_success'] = "Agendamento removido!";
        } else {
            $_SESSION['flash_error'] = "Erro ao remover agendamento.";
        }
        header('Location: ' . URL_ROOT . '/professor/dashboard');
    }

    public function getCalendarEvents() {
        $events = $this->model('Evento')->getAll();
        $formatted = [];
        foreach($events as $e) {
            // Professores veem eventos gerais e pedagógicos
            $formatted[] = [
                'id'    => $e['id'],
                'title' => $e['titulo'],
                'start' => $e['data_evento'] . 'T08:00:00',
                'end'   => $e['data_evento'] . 'T18:00:00',
                'color' => $e['cor'] ?? '#6366f1',
                'description' => $e['descricao']
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($formatted);
        exit;
    }
}
