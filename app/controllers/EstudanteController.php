<?php
class EstudanteController extends Controller {
    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'aluno' && $_SESSION['user_role'] !== 'estudante')) {
            header('Location: ' . URL_ROOT . '/auth');
            exit;
        }
    }

    public function index() {
        $estudanteModel = $this->model('Estudante');
        $academicoModel = $this->model('Academico');
        $financeiroModel = $this->model('Financeiro');

        $estudanteData = $estudanteModel->findByUserId($_SESSION['user_id']);
        
        if (!$estudanteData) {
            $_SESSION['flash_error'] = "Perfil de estudante não encontrado.";
            header('Location: ' . URL_ROOT . '/auth');
            exit;
        }

        // --- Lógica de Verificação de 48h para Matrícula ---
        $db = Database::getInstance();
        $userStmt = $db->prepare("SELECT data_aprovacao FROM utilizadores WHERE id = :id");
        $userStmt->execute([':id' => $_SESSION['user_id']]);
        $userData = $userStmt->fetch();

        if ($userData && !empty($userData['data_aprovacao'])) {
            // Verificar se já tem matrícula (mesmo que pendente)
            $checkMat = $db->prepare("SELECT id FROM matriculas WHERE estudante_id = :eid LIMIT 1");
            $checkMat->execute([':eid' => $estudanteData['id']]);
            $hasMatricula = $checkMat->fetch();

            if (!$hasMatricula) {
                $aprovacao = strtotime($userData['data_aprovacao']);
                $agora = time();
                $diff = $agora - $aprovacao;
                $limite = 48 * 3600; // 48 horas em segundos

                if ($diff > $limite) {
                    // Excluir conta por inatividade de matrícula
                    $db->prepare("DELETE FROM utilizadores WHERE id = :id")->execute([':id' => $_SESSION['user_id']]);
                    session_destroy();
                    session_start();
                    $_SESSION['flash_error'] = "O seu prazo de 48h para realizar a matrícula expirou. A sua conta foi removida automaticamente conforme a política institucional.";
                    header('Location: ' . URL_ROOT . '/auth');
                    exit;
                } else {
                    $horasRestantes = round(($limite - $diff) / 3600);
                    $data['alerta_matricula'] = "Atenção: Você tem apenas $horasRestantes horas para realizar a sua matrícula, caso contrário a sua conta será removida.";
                }
            }
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

        $smart_delinquency = $financeiroModel->getStudentDelinquencyStatus($estudanteData['id']);

        $pendencias = 0;
        foreach ($pagamentos as $p) {
            if ($p['status'] === 'Pendente' || $p['status'] === 'Atrasado') $pendencias++;
        }

        $comunicadoModel = $this->model('Comunicado');
        $frequenciaModel = $this->model('Frequencia');
        $comunicados = $comunicadoModel->getComunicadosParaUtilizador($_SESSION['user_id'], 'aluno', $turma_id);
        $unread_count = $comunicadoModel->getNotificacoesNaoLidas($_SESSION['user_id'], 'aluno', $turma_id);
        $sumarios = $frequenciaModel->getSummariesByStudent($estudanteData['id']);

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

        $data['estudante'] = $estudanteData;
        $data['notas'] = $notas;
        $data['horario'] = $turma_id ? $academicoModel->getScheduleByTurma($turma_id) : [];
        $data['pagamentos'] = $pagamentos;
        $data['turma_id'] = $turma_id;
        $data['media_geral'] = $media_geral;
        $data['desempenho_ac'] = $desempenho_ac;
        $data['faltas_count'] = $faltas_count;
        $data['pendencias_count'] = $pendencias;
        $data['can_renew'] = $can_renew;
        $data['detailed_status'] = $matriculaModel->getDetailedAcademicStatus($estudanteData['id']);
        $data['next_year'] = $next_year;
        $data['gridData'] = $gridData;
        $data['materiais'] = $this->model('Material')->getByTurma($turma_id);
        $data['comunicados'] = $comunicados;
        $data['unread_count'] = $unread_count;
        $data['sumarios'] = $sumarios;
        $data['certificados_merito'] = $academicoModel->getCertificadoDoAluno($estudanteData['id']);
        $data['proximas_aulas'] = (function($h_list) {
            $hoje = ['Monday'=>'Segunda', 'Tuesday'=>'Terça', 'Wednesday'=>'Quarta', 'Thursday'=>'Quinta', 'Friday'=>'Sexta','Saturday'=>'Sábado','Sunday'=>'Domingo'][date('l')];
            $agora = date('H:i');
            $count = 0;
            foreach ($h_list as $h) {
                if ($h['dia_semana'] == $hoje && substr($h['hora_inicio'], 0, 5) >= $agora) $count++;
            }
            return $count;
        })($data['horario'] ?? []);
        $data['smart_delinquency'] = $smart_delinquency;
        $data['historico_global'] = $academicoModel->getGlobalHistory($estudanteData['id']);
        $data['tempos_aula'] = [
            '1º' => ['07:20', '08:50'],
            '2º' => ['08:55', '10:25'],
            '3º' => ['10:45', '12:15'],
            '4º' => ['12:20', '13:50']
        ];
        $data['dias_semana'] = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta'];

        // --- 🏆 MÉRITO ACADÉMICO: Ranking e Alerta Personalizado ---
        $acadRank = $academicoModel;
        $data['ranking_escola']  = $acadRank->getRankingEscola(3);
        $data['ranking_nivel']   = $acadRank->getRankingByNivel();
        
        // Buscar apenas certificados oficiais emitidos para o estudante na DB
        $data['certificados_emitidos'] = $acadRank->getCertificadoDoAluno($estudanteData['id']);
        
        // Posição exata universal (Para todos os alunos)
        $data['meu_ranking']     = $acadRank->getDetailedStudentRank($estudanteData['id']);

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

    public function concordarNota() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken();
            $db = Database::getInstance();
            $stmt = $db->prepare("UPDATE concordancia_notas SET status = 'Resolvido', data_resposta = NOW() WHERE estudante_id = (SELECT id FROM estudantes WHERE utilizador_id = :uid) AND turma_id = :tid AND disciplina_id = :did");
            $res = $stmt->execute([
                ':uid' => $_SESSION['user_id'],
                ':tid' => $_POST['turma_id'],
                ':did' => $_POST['disciplina_id']
            ]);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => $res]);
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
                header('Location: ' . URL_ROOT . '/estudante');
                exit;
            }

            if ($newPw !== $confirmPw) {
                $_SESSION['flash_error'] = "As passwords não coincidem.";
                header('Location: ' . URL_ROOT . '/estudante');
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
            header('Location: ' . URL_ROOT . '/estudante');
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
                $result = $notaModel->registrarFeedback(
                    $estudanteData['id'],
                    $_POST['turma_id'],
                    $_POST['disciplina_id'],
                    $_POST['status'],
                    $_POST['comentario'] ?? null
                );
                
                if ($result['success'] && $result['bloqueado']) {
                    try {
                        $this->logActivity('Estudante Feedback de Nota - BLOQUEADO', ['status' => $_POST['status']]);
                        
                        $msgModel = $this->model('Mensagem');
                        $alerta = "ALERTA CRÍTICO: Registada 2ª reclamação para a mesma nota. Por favor, compareça na Administração ou Secretaria para resolver o problema.";
                        
                        // 1. Notificar Aluno
                        $msgModel->send(0, $_SESSION['user_id'], "Aviso de Conflito Crítico", $alerta);
                        
                        // 2. Notificar Professor
                        $db = Database::getInstance();
                        $stmtP = $db->prepare("SELECT p.utilizador_id FROM professor_disciplina pd JOIN professores p ON pd.professor_id = p.id WHERE pd.turma_id = :tid AND pd.disciplina_id = :did LIMIT 1");
                        $stmtP->execute([':tid' => $_POST['turma_id'], ':did' => $_POST['disciplina_id']]);
                        $prof = $stmtP->fetch();
                        if ($prof && !empty($prof['utilizador_id'])) {
                            $msgModel->send(0, $prof['utilizador_id'], "Alerta de Conflito de Nota", $alerta . " (Aluno: ".$estudanteData['nome_completo'].")");
                        }
                        
                        // 3. Notificar Administração/Secretaria via grupo
                        $msgModel->notifyGroup('admin', "Bloqueio Anti-Fraude Ativado: Aluno ".$estudanteData['nome_completo']." reclamou 2 vezes da mesma nota.");
                    } catch (Exception $e) {
                        // Log o erro mas não trava a resposta JSON
                        error_log("Erro nas notificações de bloqueio: " . $e->getMessage());
                    }
                } elseif ($result['success']) {
                    $this->logActivity('Estudante Feedback de Nota', ['status' => $_POST['status']]);
                }
                
                header('Content-Type: application/json');
                echo json_encode($result);
                exit;
            }
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => false]);
        exit;
    }

    public function registarPagamento() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URL_ROOT . '/estudante');
            exit;
        }
        $this->verifyCsrfToken();

        $estudanteModel = $this->model('Estudante');
        $estudanteData = $estudanteModel->findByUserId($_SESSION['user_id']);
        if (!$estudanteData) {
            header('Location: ' . URL_ROOT . '/estudante');
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
                header('Location: ' . URL_ROOT . '/estudante?tab=financeiro&error=size');
                exit;
            }

            // 2. Verificar extensão (lista branca)
            $origExt = strtolower(pathinfo($_FILES['comprovativo']['name'], PATHINFO_EXTENSION));
            if (!in_array($origExt, $allowedExts, true)) {
                header('Location: ' . URL_ROOT . '/estudante?tab=financeiro&error=ext');
                exit;
            }

            // 3. Verificar tipo MIME real (via finfo ou fallback)
            $realMime = 'application/octet-stream';
            if (class_exists('finfo')) {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $realMime = $finfo->file($_FILES['comprovativo']['tmp_name']);
            } else {
                // Fallback básico para quando fileinfo está desativado no PHP
                $realMime = $_FILES['comprovativo']['type'] ?? 'application/octet-stream';
            }

            if (!in_array($realMime, $allowedMimes, true)) {
                header('Location: ' . URL_ROOT . '/estudante?tab=financeiro&error=mime');
                exit;
            }

            // 4. Gerar nome seguro aleatório (sem extensão do utilizador)
            $safeFilename = 'PAG_' . $estudanteData['id'] . '_' . bin2hex(random_bytes(8)) . '.' . $origExt;

            if (!move_uploaded_file($_FILES['comprovativo']['tmp_name'], $uploadDir . $safeFilename)) {
                header('Location: ' . URL_ROOT . '/estudante?tab=financeiro&error=upload');
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
            header('Location: ' . URL_ROOT . '/estudante?tab=financeiro&success=1');
        } catch (\Exception $e) {
            error_log("Erro no pagamento (registarPagamento): " . $e->getMessage());
            header('Location: ' . URL_ROOT . '/estudante?tab=financeiro&error=1');
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
            header('Location: ' . URL_ROOT . '/estudante');
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
            header('Location: ' . URL_ROOT . '/estudante');
            exit;
        }
        $this->verifyCsrfToken();

        $estudanteModel = $this->model('Estudante');
        $estudanteData = $estudanteModel->findByUserId($_SESSION['user_id']);
        if (!$estudanteData) {
            header('Location: ' . URL_ROOT . '/estudante');
            exit;
        }

        $matriculaModel = $this->model('Matricula');
        $detailedStatus = $matriculaModel->getDetailedAcademicStatus($estudanteData['id']);
        $isRepetition = isset($_POST['is_repetition']) && $_POST['is_repetition'] == '1';

        if ($isRepetition) {
            if ($detailedStatus['status'] !== 'Reprovado') {
                $_SESSION['flash_error'] = "Não tem permissão para renovação por repetição.";
                header('Location: ' . URL_ROOT . '/estudante');
                exit;
            }
            $current = $matriculaModel->getCurrentYearInfo($estudanteData['id']);
            $targetYearId = $current['ano_curso_id'];
            $tipo = 'Renovação (Repetição)';
            $motivo = $_POST['observacoes'] ?? 'Repetição de ano';
        } else {
            if (!$detailedStatus['can_transit']) {
                $_SESSION['flash_error'] = "Não é elegível para renovação por trânsito neste momento.";
                header('Location: ' . URL_ROOT . '/estudante');
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
            header('Location: ' . URL_ROOT . '/estudante');
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
                $realMime = 'application/octet-stream';
                if (class_exists('finfo')) {
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $realMime = $finfo->file($_FILES['comprovativo']['tmp_name']);
                } else {
                    $realMime = $_FILES['comprovativo']['type'] ?? 'application/octet-stream';
                }
                
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

        header('Location: ' . URL_ROOT . '/estudante');
        exit;
    }

    public function certificado($index = null) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL_ROOT . '/auth');
            exit;
        }

        $academicoModel = $this->model('Academico');
        $estudanteModel = $this->model('Estudante');

        $estudanteData = $estudanteModel->findByUserId($_SESSION['user_id']);
        if (!$estudanteData) {
            header('Location: ' . URL_ROOT . '/estudante');
            exit;
        }

        $estudanteId = $estudanteData['id'];

        // Buscar apenas certificados OFICIALMENTE EMITIDOS na tabela (view-only)
        $certificados = $academicoModel->getCertificadoDoAluno($estudanteId);

        if (empty($certificados)) {
            $_SESSION['flash_info'] = "Ainda não existe nenhum Certificado de Mérito emitido para a sua conta. Os certificados são atribuídos pela Direção no final de cada semestre.";
            header('Location: ' . URL_ROOT . '/estudante');
            exit;
        }

        // Selecionar pelo índice se houver múltiplos certificados
        $idx  = (int)($index ?? $_GET['id'] ?? 0);
        $cert = $certificados[$idx] ?? $certificados[0];

        $semestreLabel = $cert['semestre'] === '1' ? '1º Semestre' : '2º Semestre';
        $posicaoLabel  = $cert['posicao'] === '1'
            ? '🥇 1º Lugar — Melhor Aluno do ' . $semestreLabel
            : '🥈 2º Lugar — Segundo Melhor Aluno do ' . $semestreLabel;

        $renderData = [
            'nome'        => (string)($estudanteData['nome_completo'] ?? 'Estudante'),
            'data_emissao'=> date('d/m/Y', strtotime($cert['data_emissao'])),
            'assinatura'  => 'Samba Djob',
            'assinatura_diretor' => $cert['assinatura_diretor'] ?? null,
            'assinatura_secretaria' => $cert['assinatura_secretaria'] ?? null,
            'winner_type' => $posicaoLabel,
            'media'       => (float)$cert['media'],
            'nivel_nome'  => (string)($cert['nivel_nome'] ?? 'GHS CAMPUS'),
            'periodo'     => $semestreLabel . ' — ' . $cert['ano_letivo'],
            'total_certs' => count($certificados),
            'cert_id'     => (int)$cert['id'],
        ];

        $this->view('estudante/certificado', $renderData);
    }
}

