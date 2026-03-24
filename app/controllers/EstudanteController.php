<?php
class EstudanteController extends Controller {
    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'aluno' && $_SESSION['user_role'] !== 'estudante')) {
            header('Location: /green/auth');
            exit;
        }
    }

    public function index() {
        $estudanteModel = $this->model('Estudante');
        $academicoModel = $this->model('Academico');
        $financeiroModel = $this->model('Financeiro');

        $estudanteData = $estudanteModel->findByUserId($_SESSION['user_id']);
        
        if (!$estudanteData) {
            $_SESSION['flash_error'] = "Perfil de estudante não encontrado para este utilizador. Por favor, contacte a administração.";
            header('Location: /green/auth');
            exit;
        }

        // Buscar matrícula ativa para pegar a turma e dados extras
        $stmt = Database::getInstance()->prepare("
            SELECT m.turma_id, t.codigo as turma_codigo, m.ano_curso_id
            FROM matriculas m 
            LEFT JOIN turmas t ON m.turma_id = t.id 
            WHERE m.estudante_id = :id AND m.status = 'Aprovada' 
            ORDER BY m.id DESC LIMIT 1
        ");
        $stmt->bindValue(':id', $estudanteData['id']);
        $stmt->execute();
        $matricula = $stmt->fetch();
        $turma_id = $matricula['turma_id'] ?? null;
        $estudanteData['turma_codigo'] = $matricula['turma_codigo'] ?? null;
        $estudanteData['ano_curso_id'] = $matricula['ano_curso_id'] ?? 1;

        $notas = $academicoModel->getGradesByStudent($estudanteData['id']);
        $pagamentos = $financeiroModel->getPaymentsByStudent($estudanteData['id']);
        
        // Calculate Metrics
        $media_geral = 0;
        $desempenho_ac = 0;
        if (count($notas) > 0) {
            $soma = 0;
            $soma_ac = 0;
            foreach ($notas as $n) {
                $soma += ($n['nota_final'] ?? 0);
                $soma_ac += ($n['total_ac'] ?? 0);
            }
            $media_geral = number_format($soma / count($notas), 1);
            $desempenho_ac = round(($soma_ac / (count($notas) * 20)) * 100);
        }

        $stmtFaltas = Database::getInstance()->prepare("SELECT COUNT(*) FROM frequencias WHERE estudante_id = :eid AND status = 'F'");
        $stmtFaltas->execute([':eid' => $estudanteData['id']]);
        $faltas_count = $stmtFaltas->fetchColumn();

        $pendencias = 0;
        foreach ($pagamentos as $p) {
            if ($p['status'] === 'Pendente' || $p['status'] === 'Atrasado') $pendencias++;
        }

        $comunicadoModel = $this->model('Comunicado');
        $comunicados = $comunicadoModel->getComunicadosParaUtilizador($_SESSION['user_id'], 'aluno', $turma_id);
        $unread_count = $comunicadoModel->getNotificacoesNaoLidas($_SESSION['user_id'], 'aluno', $turma_id);

        $matriculaModel = $this->model('Matricula');
        $can_renew = $matriculaModel->isEligibleForRenewal($estudanteData['id']);
        $current_year = $matriculaModel->getCurrentYearInfo($estudanteData['id']);
        $next_year = null;
        if ($current_year && $current_year['ordem'] < 5) {
            $stmtNext = Database::getInstance()->prepare("SELECT * FROM anos WHERE ordem = :ord ORDER BY id LIMIT 1");
            $stmtNext->execute([':ord' => $current_year['ordem'] + 1]);
            $next_year = $stmtNext->fetch();
        }

        $gridData = [];
        if ($turma_id) {
            $horarioModel = $this->model('Horario');
            $gridData = $horarioModel->buildWeeklyGrid($turma_id);
        }

        $data = [
            'estudante' => $estudanteData,
            'notas' => $notas,
            'horario' => $turma_id ? $academicoModel->getScheduleByTurma($turma_id) : [],
            'pagamentos' => $pagamentos,
            'turma_id' => $turma_id,
            'media_geral' => $media_geral,
            'desempenho_ac' => $desempenho_ac,
            'faltas_count' => $faltas_count,
            'pendencias_count' => $pendencias,
            'can_renew' => $can_renew,
            'detailed_status' => $matriculaModel->getDetailedAcademicStatus($estudanteData['id']),
            'next_year' => $next_year,
            'gridData' => $gridData,
            'materiais' => $this->model('Material')->getByTurma($turma_id),
            'comunicados' => $comunicados,
            'unread_count' => $unread_count,
            'proximas_aulas' => ($turma_id ? count($academicoModel->getScheduleByTurma($turma_id)) : 0),
            'historico_global' => $academicoModel->getGlobalHistory($estudanteData['id']),
            'tempos_aula' => [
                '1º' => ['07:20', '08:50'],
                '2º' => ['08:55', '10:25'],
                '3º' => ['10:45', '12:15'],
                '4º' => ['12:20', '13:50']
            ],
            'dias_semana' => ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta']
        ];

        $this->view('estudante/dashboard', $data);
    }

    public function getCalendarEvents() {
        header('Content-Type: application/json');
        
        $estudanteModel = $this->model('Estudante');
        $estudanteData = $estudanteModel->findByUserId($_SESSION['user_id']);
        if (!$estudanteData) { echo json_encode([]); exit; }

        $stmt = Database::getInstance()->prepare("SELECT turma_id FROM matriculas WHERE estudante_id = :id AND status = 'Aprovada' ORDER BY id DESC LIMIT 1");
        $stmt->bindValue(':id', $estudanteData['id']);
        $stmt->execute();
        $matricula = $stmt->fetch();
        $turma_id = $matricula['turma_id'] ?? null;

        $events = [];

        // Mocking class schedule based on day of week since actual dates might not exist
        if ($turma_id) {
            $academicoModel = $this->model('Academico');
            $horario = $academicoModel->getScheduleByTurma($turma_id);

            // Create recurring events spanning the current month
            $daysMap = ['Segunda' => 1, 'Terça' => 2, 'Quarta' => 3, 'Quinta' => 4, 'Sexta' => 5, 'Sábado' => 6];
            foreach ($horario as $h) {
                if (isset($daysMap[$h['dia_semana']])) {
                    $events[] = [
                        'title' => $h['disciplina_nome'] . "\nSala " . $h['sala'],
                        'daysOfWeek' => [ $daysMap[$h['dia_semana']] ],
                        'startTime' => $h['hora_inicio'],
                        'endTime' => $h['hora_fim'],
                        'backgroundColor' => '#3b82f6', // Azul (Aulas)
                        'borderColor' => '#2563eb'
                    ];
                }
            }
        }

        // Fetch real events from database
        $eventoModel = $this->model('Evento');
        // Students see: Global events, events for their Year, and events for their Class
        $dbEvents = $eventoModel->getForStudent($estudanteData['id']);
        
        foreach ($dbEvents as $de) {
            $events[] = [
                'title' => $de['titulo'],
                'start' => str_replace(' ', 'T', $de['data_evento']),
                'backgroundColor' => $de['cor'],
                'borderColor' => $de['cor'],
                'description' => $de['descricao']
            ];
        }

        echo json_encode($events);
        exit;
    }

    public function marcarLido() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comunicado_id'])) {
            $this->verifyCsrfToken();
            $comModel = $this->model('Comunicado');
            $success = $comModel->marcarComoLido($_POST['comunicado_id'], $_SESSION['user_id']);
            if ($success) $this->logActivity('Estudante Marcar Comunicado Lido', ['id' => $_POST['comunicado_id']]);
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
        exit;
    }

    public function changePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken();
            $newPw = $_POST['new_password'] ?? '';
            $confirmPw = $_POST['confirm_password'] ?? '';

            if (strlen($newPw) < 6) {
                $_SESSION['flash_error'] = "A nova password deve ter pelo menos 6 caracteres.";
                header('Location: /green/estudante');
                exit;
            }

            if ($newPw !== $confirmPw) {
                $_SESSION['flash_error'] = "As passwords não coincidem.";
                header('Location: /green/estudante');
                exit;
            }

            $userModel = $this->model('User');
            if ($userModel->updatePassword($_SESSION['user_id'], $newPw)) {
                $_SESSION['must_change_password'] = false;
                $this->logActivity('Estudante Alterar Password');
                $_SESSION['flash_success'] = "Password alterada com sucesso!";
            } else {
                $_SESSION['flash_error'] = "Erro ao alterar a password.";
            }
            header('Location: /green/estudante');
            exit;
        }
    }

    public function registarFeedbackNota() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken();
            $estudanteModel = $this->model('Estudante');
            $estudanteData = $estudanteModel->findByUserId($_SESSION['user_id']);
            
            if ($estudanteData) {
                $notaModel = $this->model('Nota');
                $res = $notaModel->registrarFeedback(
                    $estudanteData['id'],
                    $_POST['turma_id'],
                    $_POST['disciplina_id'],
                    $_POST['status'],
                    $_POST['comentario'] ?? null
                );
                if ($res) $this->logActivity('Estudante Feedback de Nota', ['status' => $_POST['status']]);
                header('Content-Type: application/json');
                echo json_encode(['success' => $res]);
                exit;
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
        exit;
    }

    public function registarPagamento() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /green/estudante');
            exit;
        }
        $this->verifyCsrfToken();

        $estudanteModel = $this->model('Estudante');
        $estudanteData = $estudanteModel->findByUserId($_SESSION['user_id']);
        if (!$estudanteData) {
            header('Location: /green/estudante');
            exit;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/comprovativos/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $caminho = null;
        if (isset($_FILES['comprovativo']) && $_FILES['comprovativo']['error'] === 0) {
            // --- Validação Segura de Upload ---
            $maxSize = 5 * 1024 * 1024; // 5 MB
            $allowedMimes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
            $allowedExts  = ['pdf', 'jpg', 'jpeg', 'png'];

            // 1. Verificar tamanho
            if ($_FILES['comprovativo']['size'] > $maxSize) {
                header('Location: /green/estudante?tab=financeiro&error=size');
                exit;
            }

            // 2. Verificar extensão (lista branca)
            $origExt = strtolower(pathinfo($_FILES['comprovativo']['name'], PATHINFO_EXTENSION));
            if (!in_array($origExt, $allowedExts, true)) {
                header('Location: /green/estudante?tab=financeiro&error=ext');
                exit;
            }

            // 3. Verificar tipo MIME real (via finfo – não confia no navegador)
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $realMime = $finfo->file($_FILES['comprovativo']['tmp_name']);
            if (!in_array($realMime, $allowedMimes, true)) {
                header('Location: /green/estudante?tab=financeiro&error=mime');
                exit;
            }

            // 4. Gerar nome seguro aleatório (sem extensão do utilizador)
            $safeFilename = 'PAG_' . $estudanteData['id'] . '_' . bin2hex(random_bytes(8)) . '.' . $origExt;

            if (!move_uploaded_file($_FILES['comprovativo']['tmp_name'], $uploadDir . $safeFilename)) {
                header('Location: /green/estudante?tab=financeiro&error=upload');
                exit;
            }

            $caminho = 'public/uploads/comprovativos/' . $safeFilename;
        }

        $db = \Database::getInstance();
        $stmt = $db->prepare("
            INSERT INTO pagamentos (estudante_id, descricao, valor, comprovativo_arquivo, observacoes, status, data_criacao, data_vencimento)
            VALUES (:eid, :desc, :val, :comp, :obs, 'Pendente', NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY))
            ON DUPLICATE KEY UPDATE status = 'Pendente', comprovativo_arquivo = :comp2
        ");
        try {
            $stmt->execute([
                ':eid'  => $estudanteData['id'],
                ':desc' => $_POST['referencia'] ?? '',
                ':val'  => $_POST['valor'] ?? 0,
                ':comp' => $caminho,
                ':obs'  => $_POST['observacoes'] ?? null,
                ':comp2' => $caminho
            ]);
            header('Location: /green/estudante?tab=financeiro&success=1');
        } catch (\Exception $e) {
            header('Location: /green/estudante?tab=financeiro&error=1');
        }
        exit;
    }

    public function downloadRecibo($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT p.*, u.nome_completo as estudante_nome, e.bi, u.id as utilizador_id
            FROM pagamentos p
            JOIN estudantes e ON p.estudante_id = e.id
            JOIN utilizadores u ON e.utilizador_id = u.id
            WHERE p.id = :id AND p.status = 'Pago'
        ");
        $stmt->execute([':id' => $id]);
        $p = $stmt->fetch();

        if (!$p) {
            $_SESSION['flash_error'] = "O recibo solicitado não foi encontrado ou ainda não foi validado pela secretaria.";
            header('Location: /green/estudante');
            exit;
        }

        // Mitigação de IDOR: Verificar se o recibo pertence ao utilizador logado
        $this->checkOwnership($p['utilizador_id']);

        $data = ['pagamento' => $p];
        $this->view('estudante/recibo_print', $data);
        exit;
    }

    public function renewEnrollment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /green/estudante');
            exit;
        }
        $this->verifyCsrfToken();

        $estudanteModel = $this->model('Estudante');
        $estudanteData = $estudanteModel->findByUserId($_SESSION['user_id']);
        if (!$estudanteData) {
            header('Location: /green/estudante');
            exit;
        }

        $matriculaModel = $this->model('Matricula');
        $detailedStatus = $matriculaModel->getDetailedAcademicStatus($estudanteData['id']);
        $isRepetition = isset($_POST['is_repetition']) && $_POST['is_repetition'] == '1';

        if ($isRepetition) {
            if ($detailedStatus['status'] !== 'Reprovado') {
                $_SESSION['flash_error'] = "Não tem permissão para renovação por repetição.";
                header('Location: /green/estudante');
                exit;
            }
            $current = $matriculaModel->getCurrentYearInfo($estudanteData['id']);
            $targetYearId = $current['ano_curso_id'];
            $tipo = 'Renovação (Repetição)';
            $motivo = $_POST['observacoes'] ?? 'Repetição de ano';
        } else {
            if (!$detailedStatus['can_transit']) {
                $_SESSION['flash_error'] = "Não é elegível para renovação por trânsito neste momento.";
                header('Location: /green/estudante');
                exit;
            }
            $current = $matriculaModel->getCurrentYearInfo($estudanteData['id']);
            $stmtNext = Database::getInstance()->prepare("SELECT id FROM anos WHERE ordem = :ord ORDER BY id LIMIT 1");
            $stmtNext->execute([':ord' => ($current['ordem'] ?? 0) + 1]);
            $targetYearId = $stmtNext->fetchColumn();
            $tipo = 'Renovação';
            $motivo = 'Renovação automática por trânsito de ano.';
        }

        if (!$targetYearId) {
            $_SESSION['flash_error'] = "Nível de destino não encontrado.";
            header('Location: /green/estudante');
            exit;
        }

        // Criar Matrícula
        $matricula_id = $matriculaModel->createEnrollment([
            'user_id' => $estudanteData['id'],
            'ano_id' => $targetYearId,
            'turno' => $_POST['turno'] ?? 'Manhã',
            'tipo' => $tipo,
            'motivo' => $motivo
        ]);

        if ($matricula_id) {
            // Upload do Comprovativo
            $upload_dir = 'public/uploads/matriculas/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
            
            if (isset($_FILES['comprovativo']) && $_FILES['comprovativo']['error'] == 0) {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $realMime = $finfo->file($_FILES['comprovativo']['tmp_name']);
                $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png'];
                
                if (in_array($realMime, $allowedMimes)) {
                    $ext = pathinfo($_FILES['comprovativo']['name'], PATHINFO_EXTENSION);
                    $new_name = $matricula_id . '_renovacao_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    
                    if (move_uploaded_file($_FILES['comprovativo']['tmp_name'], $upload_dir . $new_name)) {
                        $matriculaModel->saveDocument($matricula_id, 'Comprovativo_Pagamento', $new_name, $upload_dir . $new_name);
                    }
                }
            }
            $this->logActivity('Estudante Solicitar Renovação', ['matricula_id' => $matricula_id]);
            $_SESSION['flash_success'] = "Solicitação de renovação enviada com sucesso! Aguarde a validação da secretaria.";
        } else {
            $_SESSION['flash_error'] = "Erro ao processar renovação.";
        }

        header('Location: /green/estudante');
        exit;
    }
}
