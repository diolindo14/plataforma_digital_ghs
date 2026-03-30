<?php
/**
 * SecretariaController - Gestor Operacional da Plataforma.
 * 
 * Responsável pela recepção de novas matrículas, triagem de documentos 
 * e pré-validação de pagamentos. Atua como o primeiro nível de governação 
 * antes da supervisão do Administrador.
 */
class SecretariaController extends Controller {
    
    /**
     * Construtor com restrição de acesso por Role-Based Access Control (RBAC).
     */
    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'secretaria') {
            header('Location: ' . URL_ROOT . '/auth');
            exit;
        }
    }

    /**
 * Controlador do Portal da Secretaria.
     * Consolida dados de matrículas pendentes, pagamentos por validar e alertas de sistema.
     */
    public function index() {
        $matriculaModel = $this->model('Matricula');
        $pagamentoModel = $this->model('Pagamento');
        $comunicadoModel = $this->model('Comunicado');
        $dashboardModel = $this->model('DashboardModel');

        $data = [
            'nome' => $_SESSION['user_name'] ?? 'Secretaria',
            'stats' => $dashboardModel->getSecretariaStats(),
            'matriculas_pendentes' => $matriculaModel->getPendingEnrollments(),
            'pagamentos_pendentes' => $pagamentoModel->getAll(), 
            'comunicados' => $comunicadoModel->getAll(),
            'tipos_pagamento' => $pagamentoModel->getTiposPagamento(),
            'estudantes' => $this->model('Estudante')->getAllStudents()
        ];

        // Lógica de Negócio: Filtra apenas pagamentos que possuem comprovativo enviado
        $data['pagamentos_validar'] = array_filter($data['pagamentos_pendentes'], function($p) {
            return $p['status'] === 'Pendente' && !empty($p['comprovativo_arquivo']);
        });

        // Recupera notificações bidirecionais (GHS Message System)
        $data['mensagens_painel'] = $this->model('Mensagem')->getUnreadMessages($_SESSION['user_id']);
        $data['mensagens_historico'] = $this->model('Mensagem')->getReceivedMessages($_SESSION['user_id']);

        // --- 🏆 MÉRITO ACADÉMICO ---
        $acadRank = $this->model('Academico');
        $data['ranking_escola'] = $acadRank->getRankingEscola(3);
        $data['ranking_nivel']  = $acadRank->getRankingByNivel();

        $this->view('secretaria/index', $data);
    }

    /**
     * Fluxo de Aprovação de Matrícula (Nível Secretaria).
     * 
     * Documentação Funcional:
     * 1. Altera o status da matrícula.
     * 2. Ativa o utilizador no sistema core.
     * 3. Executa alocação automática em turmas disponíveis.
     * 4. Notifica o Administrador sobre a entrada de um novo aluno.
     */
    public function approveMatricula($id) {
        $this->verifyCsrfToken(); 
        $matriculaModel = $this->model('Matricula'); 
        $db = Database::getInstance(); 
        
        $stmt = $db->prepare("SELECT m.*, u.nome_completo, u.email, u.id as user_id FROM matriculas m JOIN estudantes e ON m.estudante_id = e.id JOIN utilizadores u ON e.utilizador_id = u.id WHERE m.id = :id");
        $stmt->execute([':id' => $id]);
        $m = $stmt->fetch(); 

        if ($matriculaModel->updateStatus($id, 'Aprovada', $_SESSION['user_id'])) {
            $this->logActivity('Aprovar Matrícula Secretaria', ['matricula_id' => $id]); 
            
            // Ativação Core
            $db->prepare("UPDATE utilizadores SET status = 'ativo' WHERE id = :uid")->execute([':uid' => $m['user_id']]);

            // Alocação Inteligente (Turno/Ano)
            $stmtT = $db->prepare("SELECT id FROM turmas WHERE ano_id = :ano AND turno = :turno AND vagas > (SELECT COUNT(*) FROM matriculas WHERE turma_id = turmas.id) LIMIT 1");
            $stmtT->execute([':ano' => $m['ano_curso_id'], ':turno' => $m['turno']]);
            $t = $stmtT->fetch(); 
            
            if ($t) {
                $matriculaModel->assignToTurma($id, $t['id']); 
                $_SESSION['flash_success'] = "Matrícula aprovada! Aluno ativado e alocado à turma automaticamente.";
            } else {
                $_SESSION['flash_success'] = "Matrícula aprovada e aluno ativado! (Alocação manual necessária).";
            }

            // Integração: Informe ao Admin
            $notif = "A Secretaria (" . ($_SESSION['user_name'] ?? 'Membro') . ") aprovou a matrícula de " . ($m['nome_completo'] ?? 'N/A') . ".";
            $this->model('Mensagem')->notifyGroup('admin', $notif, $_SESSION['user_id']); 
            
        } else {
            $_SESSION['flash_error'] = "Erro ao aprovar matrícula.";
        }
        header('Location: ' . URL_ROOT . '/secretaria'); 
    }

    /**
     * Rejeição de Matrícula.
     * Permite à secretaria impedir o avanço de inscrições irregulares.
     */
    public function rejectMatricula($id) {
        $this->verifyCsrfToken(); 
        $db = Database::getInstance(); 
        $stmt = $db->prepare("SELECT u.nome_completo FROM matriculas m JOIN estudantes e ON m.estudante_id = e.id JOIN utilizadores u ON e.utilizador_id = u.id WHERE m.id = :id");
        $stmt->execute([':id' => $id]);
        $u = $stmt->fetch();

        $model = $this->model('Matricula'); 
        $motivo = $_POST['motivo'] ?? 'Documentação incompleta'; 
        
        if ($model->updateStatus($id, 'Rejeitada', $_SESSION['user_id'], $motivo)) {
            $this->logActivity('Rejeitar Matrícula Secretaria', ['matricula_id' => $id, 'motivo' => $motivo]);
            
            $notif = "A Secretaria REJEITOU a matrícula de " . ($u['nome_completo'] ?? 'N/A') . ". Motivo: $motivo.";
            $this->model('Mensagem')->notifyGroup('admin', $notif, $_SESSION['user_id']);
            
            $_SESSION['flash_success'] = "Matrícula rejeitada.";
        } else {
            $_SESSION['flash_error'] = "Erro ao rejeitar matrícula.";
        }
        header('Location: ' . URL_ROOT . '/secretaria'); 
    }

    /**
     * Validação de Fluxo de Caixa (Pagamentos).
     */
    public function validatePayment($id) {
        $this->verifyCsrfToken(); 
        $db = Database::getInstance(); 
        $stmt = $db->prepare("SELECT p.*, u.nome_completo FROM pagamentos p JOIN estudantes e ON p.estudante_id = e.id JOIN utilizadores u ON e.utilizador_id = u.id WHERE p.id = :id");
        $stmt->execute([':id' => $id]);
        $p = $stmt->fetch();

        if ($this->model('Pagamento')->aprovarPagamento($id, $_SESSION['user_id'])) {
            $this->logActivity('Validar Pagamento Secretaria', ['pagamento_id' => $id]);
            
            $notif = "Pagamento validado pela Secretaria: " . ($p['nome_completo'] ?? 'N/A') . " (ID: $id).";
            $this->model('Mensagem')->notifyGroup('admin', $notif, $_SESSION['user_id']);
            
            $_SESSION['flash_success'] = "Pagamento validado com sucesso.";
        } else {
            $_SESSION['flash_error'] = "Erro ao validar pagamento.";
        }
        header('Location: ' . URL_ROOT . '/secretaria'); 
    }

    /**
     * Gestão de Notificações.
     * 
     * // Sugestão: Poderia ser implementado via AJAX para evitar o reload da página.
     */
    public function clearNotifications() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $this->model('Mensagem')->markAllAsRead($_SESSION['user_id']);
            $_SESSION['flash_success'] = "Painel de alertas limpo!";
        }
        header('Location: ' . URL_ROOT . '/secretaria');
        exit;
    }

    /**
     * Lista certificados emitidos via AJAX para o painel secretaria.
     */
    public function getCertificadosEmitidos() {
        header('Content-Type: application/json');
        
        $db = Database::getInstance();
        $sql = "
            SELECT cm.*, u.nome_completo AS estudante_nome, ua.nome_completo AS emitido_por_nome
            FROM certificados_merito cm
            JOIN estudantes e ON e.id = cm.estudante_id
            JOIN utilizadores u ON u.id = e.utilizador_id
            LEFT JOIN utilizadores ua ON ua.id = cm.emitido_por
            ORDER BY cm.ano_letivo DESC, cm.semestre DESC, cm.posicao ASC
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $certs = $stmt->fetchAll();
        
        echo json_encode($certs ?: []);
        exit;
    }

    /**
     * Salva a assinatura digital da Secretaria no certificado.
     */
    public function assinarCertificado() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;
        
        $this->verifyCsrfToken();
        
        $id = $_POST['id'] ?? null;
        $assinatura = $_POST['assinatura'] ?? null;
        
        if (!$id || !$assinatura) {
            echo json_encode(['success' => false, 'message' => 'Dados incompletos.']);
            exit;
        }

        $academicoModel = $this->model('Academico');
        // Assina como secretaria
        $res = $academicoModel->assinarCertificado($id, $assinatura, 'secretaria');
        
        if ($res) $this->logActivity('Assinar Certificado (Secretaria)', ['id' => $id]);
        
        echo json_encode(['success' => $res]);
        exit;
    }
    /**
     * Criação de Matrícula Manual via Secretaria.
     */
    public function createMatriculaManual() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;
        $this->verifyCsrfToken();

        $userModel = $this->model('User');
        $estudanteModel = $this->model('Estudante');
        $matriculaModel = $this->model('Matricula');

        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $bi = $_POST['bi'];
        $senha_provisoria = 'ghs' . substr($bi, -4);

        // 1. Criar Utilizador Ativo
        $user_id = $userModel->insertUser($nome, $email, $senha_provisoria, 'aluno', 'ativo');

        if (!$user_id) {
            $_SESSION['flash_error'] = "Erro: Email já cadastrado.";
            header('Location: ' . URL_ROOT . '/secretaria');
            exit;
        }

        // 2. Criar Perfil Estudante
        $estudante_id = $estudanteModel->createEstudante([
            'user_id' => $user_id,
            'bi' => $bi,
            'telefone' => $_POST['telefone'] ?? '',
            'data_nascimento' => $_POST['data_nascimento'] ?? null,
            'nacionalidade' => 'Guineense',
            'sexo' => 'M',
            'morada' => 'Bissau',
            'escola' => 'GHS',
            'ano_conclusao' => date('Y'),
            'media' => 0
        ]);

        // 3. Criar Matrícula Aprovada
        $matricula_id = $matriculaModel->createEnrollment([
            'user_id' => $estudante_id,
            'ano_id' => $_POST['ano_id'],
            'turno' => $_POST['turno'],
            'tipo' => $_POST['tipo'] ?? 'Novo Ingresso'
        ]);

        $matriculaModel->updateStatus($matricula_id, 'Aprovada', $_SESSION['user_id']);

        // 4. Upload Comprovativo (se houver)
        if (isset($_FILES['comprovativo']) && $_FILES['comprovativo']['error'] == 0) {
            $dest = 'public/uploads/matriculas';
            $upload = FileHelper::upload($_FILES['comprovativo'], $dest, ALLOWED_EXTENSIONS);
            if ($upload['success']) {
                $matriculaModel->saveDocument($matricula_id, 'Comprovativo_Pagamento', basename($upload['fileName']), $dest . '/' . $upload['fileName']);
            }
        }

        $_SESSION['flash_success'] = "Matrícula manual para $nome criada e aprovada com sucesso!";
        header('Location: ' . URL_ROOT . '/secretaria');
        exit;
    }
}
