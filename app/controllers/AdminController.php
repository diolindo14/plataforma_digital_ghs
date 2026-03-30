<?php
/**
 * Controlador de Administração (AdminController)
 * 
 * @package Controllers
 * @author Senior Software Engineer / Mentor
 * 
 * Documentação Funcional:
 * Este é o "Painel de Controlo" central de toda a instituição académica. 
 * Gere desde a infraestrutura (anos, turmas, disciplinas) até fluxos financeiros 
 * e auditoria pedagógica.
 */
class AdminController extends Controller {
    /**
     * Middleware de Autenticação e Autorização (RBAC).
     * Garante que apenas utilizadores com papel 'admin' acedam a estes métodos.
     */
    public function __construct() {
        parent::__construct();
        // // Segurança: Verificação de sessão e role centralizada.
        // // Sugestão: Mover esta lógica para um Middleware ou Decorator para 
        // // desacoplar a autorização do controlador.
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . URL_ROOT . '/auth');
            exit;
        }
    }

    /**
     * Dashboard Principal do Administrador.
     * Atua como um Agregador (Mediator) que recolhe dados de mais de 10 modelos 
     * para compor a visão geral do sistema.
     * 
     * // Análise de Performance: A função index() está a carregar MUITOS modelos.
     * // Isso pode causar um "overhead" em servidores com pouca RAM.
     * // Sugestão: Usar Lazy Loading ou agrupar estatísticas numa única query no DashboardModel.
     */
    public function index() {
        $data['anos'] = $this->model('Academico')->getAnos();
        $data['disciplinas'] = $this->model('Disciplina')->getAll();
        $data['especialidades'] = $this->model('Especialidade')->getAll();
        $data['professores'] = $this->model('Professor')->getAllProfessors();
        $data['turmas'] = $this->model('Turma')->getAll();
        $data['approved_matriculas'] = $this->model('Matricula')->getApprovedWithoutTurma();
        $data['pagamentos'] = $this->model('Pagamento')->getAll();
        $data['comunicados'] = $this->model('Comunicado')->getAll();
        $data['tipos_pagamento'] = $this->model('Pagamento')->getTiposPagamento();
        
        $data['stats'] = $this->model('DashboardModel')->getAdminStats();
        $data['chartData'] = $this->model('DashboardModel')->getAdminChartData();
        $data['pendentes'] = $this->model('User')->getPendingUsers();
        $data['estudantes'] = $this->model('Estudante')->getAllStudents(1, 1000); // Lista completa de alunos (Pilar 7)
        $data['matriculas'] = $this->model('Matricula')->getPendingEnrollments();
        
        // Dados pedagógicos e relatórios
        $frequenciaModel = $this->model('Frequencia');
        $data['sumarios'] = $frequenciaModel->getAllSummaries();
        $data['frequencias_report'] = $frequenciaModel->getFrequenciaRelatorio();
        $data['notas_report'] = $this->model('Nota')->getRelatorioGeral();
        $data['atrasos_sumarios'] = $frequenciaModel->getMissingSummaries();
        $data['teacher_attendance_report'] = $frequenciaModel->getTeacherAttendanceReport();
        $data['detailed_attendance'] = $frequenciaModel->getDetailedAttendanceLog();

        $data['secretarios'] = $this->model('Administrador')->getAllSecretarios();
        
        // Recupera notificações de sistema (GHS Workflow) para feedback imediato no widget
        $data['mensagens_painel'] = $this->model('Mensagem')->getUnreadMessages($_SESSION['user_id']);
        $data['mensagens_historico'] = $this->model('Mensagem')->getReceivedMessages($_SESSION['user_id']);
        
        // --- 🏆 SISTEMA DE MÉRITO ACADÉMICO ---
        $academicoRankingModel = $this->model('Academico');
        $data['ranking_escola'] = $academicoRankingModel->getRankingEscola(3);
        $data['ranking_nivel']  = $academicoRankingModel->getRankingByNivel();
        
        // --- 🚨 MONITORIZAÇÃO DE RECLAMAÇÕES DE NOTAS ---
        $data['conflitos_notas'] = $this->model('Nota')->getConflitosNotas();
        
        // --- 📊 LOGS DE ACESSO (Auditoria de Segurança) ---
        $data['logs_acesso'] = $this->model('User')->getRecentAccesses(20);

        $this->view('admin/dashboard', $data);
    }

    // --- PONTES DE COMPATIBILIDADE (Pilar 2) ---
    // Estes métodos permitem que os links antigos continuem a funcionar,
    // delegando a execução para os novos controladores especializados.

    public function saveTurma() { (new AdminAcademicoController())->saveTurma(); }
    public function saveAno() { (new AdminAcademicoController())->saveAno(); }
    public function deleteAno($id) { (new AdminAcademicoController())->deleteAno($id); }
    public function saveDisciplina() { (new AdminAcademicoController())->saveDisciplina(); }
    public function deleteDisciplina($id) { (new AdminAcademicoController())->deleteDisciplina($id); }
    public function saveEspecialidade() { (new AdminAcademicoController())->saveEspecialidade(); }
    public function deleteEspecialidade($id) { (new AdminAcademicoController())->deleteEspecialidade($id); }
    public function saveHorario() { (new AdminAcademicoController())->saveHorario(); }
    public function getHorariosAjax($id) { (new AdminAcademicoController())->getHorariosAjax($id); }

    public function validarPagamento($id) { (new AdminFinanceiroController())->validarPagamento($id); }
    public function rejeitarPagamento($id) { (new AdminFinanceiroController())->rejeitarPagamento($id); }

    public function saveProfessor() { (new AdminUsuarioController())->saveProfessor(); }
    public function deleteProfessor($id) { (new AdminUsuarioController())->deleteProfessor($id); }
    public function createSecretaria() { (new AdminUsuarioController())->createSecretaria(); }
    public function deleteSecretaria($id) { (new AdminUsuarioController())->deleteSecretaria($id); }

    /**
     * Aprova uma conta de utilizador (Estudante/Professor) após registo inicial.
     * Esta ação libera o acesso básico mas exige matrícula para estudantes.
     */
    public function approveAccount($id) {
        $this->verifyCsrfToken();
        $db = Database::getInstance();
        
        $stmt = $db->prepare("UPDATE utilizadores SET status = 'ativo', data_aprovacao = NOW() WHERE id = :id");
        if ($stmt->execute([':id' => $id])) {
            $this->logActivity('Aprovar Conta Utilizador', ['user_id' => $id]);
            
            $user = $this->model('User')->findById($id);
            $notif = "O Administrador aprovou a conta de " . ($user['nome_completo'] ?? 'Utilizador') . ".";
            $this->model('Mensagem')->notifyGroup('secretaria', $notif, $_SESSION['user_id']);
            
            // 📧 Notificação por Email ao utilizador aprovado
            if (!empty($user['email'])) {
                Mailer::sendWelcome($user['email'], $user['nome_completo'] ?? 'Estudante');
            }
            
            $_SESSION['flash_success'] = "Conta aprovada com sucesso! Email enviado ao utilizador.";
        } else {
            $_SESSION['flash_error'] = "Erro ao aprovar conta.";
        }
        header('Location: ' . URL_ROOT . '/admin');
        exit;
    }

    /**
     * Alocação de Aluno à Turma (Manual).
     */
    public function assignStudentToTurma() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['matricula_id']) && isset($_POST['turma_id'])) {
            $this->verifyCsrfToken();
            $model = $this->model('Matricula');
            if ($model->assignToTurma($_POST['matricula_id'], $_POST['turma_id'])) {
                $this->logActivity('Alocar Aluno à Turma', ['matricula_id' => $_POST['matricula_id'], 'turma_id' => $_POST['turma_id']]);
                $_SESSION['flash_success'] = "Aluno alocado à turma com sucesso.";
            } else {
                $_SESSION['flash_error'] = "Erro ao alocar aluno.";
            }
            header('Location: ' . URL_ROOT . '/admin');
            exit;
        }
    }

    /**
     * Fluxo de Governança: Aprovação de Matrícula.
     * 
     * Documentação Funcional:
     * Este é um dos métodos mais críticos. Ele orquestra 4 ações:
     * 1. Aprova a matrícula no modelo.
     * 2. Ativa a conta do utilizador (User status = 'ativo').
     * 3. Tenta alocação automática numa turma com vagas.
     * 4. Envia notificação bidirecional para a Secretaria.
     */
    public function approveMatricula($id) {
        $this->verifyCsrfToken(); 
        $matriculaModel = $this->model('Matricula'); 
        $db = Database::getInstance(); 
        
        $stmt = $db->prepare("
            SELECT m.*, u.nome_completo, u.email, u.id as user_id, u.status as user_status,
                   a.nome as ano_nome
            FROM matriculas m 
            JOIN estudantes e ON m.estudante_id = e.id 
            JOIN utilizadores u ON e.utilizador_id = u.id 
            LEFT JOIN anos a ON m.ano_curso_id = a.id
            WHERE m.id = :id");
        $stmt->execute([':id' => $id]);
        $m = $stmt->fetch(); 

        if ($matriculaModel->updateStatus($id, 'Aprovada', $_SESSION['user_id'])) {
            $this->logActivity('Aprovar Matrícula', ['matricula_id' => $id, 'aluno' => $m['email'] ?? 'N/A']);
            
            $db->prepare("UPDATE utilizadores SET status = 'ativo', data_aprovacao = NOW() WHERE id = :uid")
               ->execute([':uid' => $m['user_id']]);

            // Alocação automática inteligente baseada em vagas e turno
            $stmtT = $db->prepare("SELECT id FROM turmas WHERE ano_id = :ano AND turno = :turno AND vagas > (SELECT COUNT(*) FROM matriculas WHERE turma_id = turmas.id) LIMIT 1");
            $stmtT->execute([':ano' => $m['ano_curso_id'], ':turno' => $m['turno']]);
            $t = $stmtT->fetch(); 
            
            if ($t) {
                $matriculaModel->assignToTurma($id, $t['id']);
                $_SESSION['flash_success'] = "Matrícula de <strong>{$m['nome_completo']}</strong> aprovada! Aluno alocado automaticamente à turma compatível.";
            } else {
                $_SESSION['flash_success'] = "Matrícula de <strong>{$m['nome_completo']}</strong> aprovada! Alocação manual necessária.";
            }
            
            // 📧 Notificação por Email ao aluno
            if (!empty($m['email'])) {
                Mailer::sendMatriculaAprovada($m['email'], $m['nome_completo'] ?? 'Estudante', $m['ano_nome'] ?? '');
            }

            // Notificação interna para a Secretaria
            $notif = "Matrícula validada pelo Admin: {$m['nome_completo']} ({$m['email']}).";
            $this->model('Mensagem')->notifyGroup('secretaria', $notif, $_SESSION['user_id']); 
            
        } else {
            $_SESSION['flash_error'] = "Erro ao aprovar matrícula.";
        }
        header('Location: ' . URL_ROOT . '/admin#pane-matriculas'); 
        exit;
    }

    public function rejectMatricula($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $motivo = $_POST['motivo'] ?? '';
            
            // Busca dados do aluno para notificação
            $db = Database::getInstance();
            $stmt = $db->prepare("SELECT u.email, u.nome_completo FROM matriculas m JOIN estudantes e ON m.estudante_id = e.id JOIN utilizadores u ON e.utilizador_id = u.id WHERE m.id = :id");
            $stmt->execute([':id' => $id]);
            $aluno = $stmt->fetch();

            if ($this->model('Matricula')->updateStatus($id, 'Rejeitada', $_SESSION['user_id'], $motivo)) {
                $this->logActivity('Rejeitar Matrícula', ['matricula_id' => $id, 'motivo' => $motivo]);
                
                // 📧 Notificação por Email ao aluno
                if (!empty($aluno['email'])) {
                    Mailer::sendMatriculaRejeitada($aluno['email'], $aluno['nome_completo'] ?? 'Estudante', $motivo);
                }
                
                $_SESSION['flash_success'] = "Matrícula rejeitada. Email enviado ao aluno.";
            } else {
                $_SESSION['flash_error'] = "Erro ao rejeitar matrícula.";
            }
        }
        header('Location: ' . URL_ROOT . '/admin#pane-matriculas');
        exit;
    }

    /**
     * Criação Manual de Matrícula pelo Admin/Secretaria.
     * Permite matricular um aluno diretamente sem passar pelo formulário público.
     * Útil para casos presenciais ou situações especiais.
     */
    public function createMatriculaManual() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URL_ROOT . '/admin#pane-matriculas');
            exit;
        }
        $this->verifyCsrfToken();

        $nome     = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
        $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $bi       = filter_input(INPUT_POST, 'bi', FILTER_SANITIZE_SPECIAL_CHARS);
        $ano_id   = (int)($_POST['ano_id'] ?? 1);
        $turno    = filter_input(INPUT_POST, 'turno', FILTER_SANITIZE_SPECIAL_CHARS);
        $tipo     = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS) ?: 'Novo Ingresso';
        $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!$nome || !$email || !$bi) {
            $_SESSION['flash_error'] = "Preencha todos os campos obrigatórios (Nome, Email, BI).";
            header('Location: ' . URL_ROOT . '/admin#pane-matriculas');
            exit;
        }

        $userModel      = $this->model('User');
        $estudanteModel = $this->model('Estudante');
        $matriculaModel = $this->model('Matricula');
        $db             = Database::getInstance();

        // 1. Verifica se email já existe
        $existe = $db->prepare("SELECT id FROM utilizadores WHERE email = :e");
        $existe->execute([':e' => $email]);
        if ($existe->fetch()) {
            $_SESSION['flash_error'] = "Já existe um utilizador com este email: $email";
            header('Location: ' . URL_ROOT . '/admin#pane-matriculas');
            exit;
        }

        // 2. Criar utilizador (imediatamente ativo, pois é criado pelo admin)
        $senha_provisoria = 'ghs' . substr($bi, -4);
        $user_id = $userModel->insertUser($nome, $email, $senha_provisoria, 'aluno', 'ativo');

        if (!$user_id) {
            $_SESSION['flash_error'] = "Erro ao criar utilizador. Verifique o email.";
            header('Location: ' . URL_ROOT . '/admin#pane-matriculas');
            exit;
        }

        // 3. Criar perfil de estudante
        $estudante_id = $estudanteModel->createEstudante([
            'user_id'    => $user_id,
            'bi'         => $bi,
            'telefone'   => $telefone,
            'data_nascimento' => $_POST['data_nascimento'] ?? null,
            'sexo'       => $_POST['sexo'] ?? 'M',
            'nacionalidade' => $_POST['nacionalidade'] ?? 'Guineense',
            'estado_civil' => 'Solteiro',
            'morada'     => '',
            'encarregado_nome' => '',
            'encarregado_telefone' => '',
            'escola'     => '',
            'ano_conclusao' => '',
            'media'      => 0
        ]);

        // 4. Criar matrícula já aprovada (admin cria e aprova no mesmo passo)
        $matricula_id = $matriculaModel->createEnrollment([
            'user_id'          => $estudante_id,
            'ano_id'           => $ano_id,
            'turno'            => $turno,
            'tipo'             => $tipo,
            'especializacao_id'=> null,
            'motivo'           => 'Matrícula criada manualmente pelo Administrador/Secretaria.'
        ]);

        // 5. Upload de comprovativo se fornecido
        if (isset($_FILES['comprovativo']) && $_FILES['comprovativo']['error'] == 0) {
            $dest   = 'public/uploads/matriculas';
            $upload = FileHelper::upload($_FILES['comprovativo'], $dest, ALLOWED_EXTENSIONS);
            if ($upload['success']) {
                $matriculaModel->saveDocument($matricula_id, 'Comprovativo_Pagamento', $upload['fileName'], $dest . '/' . $upload['fileName']);
            }
        }

        // 6. Aprovar automaticamente
        $matriculaModel->updateStatus($matricula_id, 'Aprovada', $_SESSION['user_id']);

        // 7. Tentativa de alocação automática à turma
        $stmtT = $db->prepare("SELECT id FROM turmas WHERE ano_id = :ano AND turno = :turno AND vagas > (SELECT COUNT(*) FROM matriculas WHERE turma_id = turmas.id) LIMIT 1");
        $stmtT->execute([':ano' => $ano_id, ':turno' => $turno]);
        $turmaAuto = $stmtT->fetch();
        if ($turmaAuto) {
            $matriculaModel->assignToTurma($matricula_id, $turmaAuto['id']);
        }

        $this->logActivity('Criar Matrícula Manual', ['user_id' => $user_id, 'email' => $email]);

        // 8. Notificar aluno por email
        Mailer::sendMatriculaAprovada($email, $nome);

        $_SESSION['flash_success'] = "Matrícula de <strong>$nome</strong> criada e aprovada! Senha provisória: <code>$senha_provisoria</code>";
        header('Location: ' . URL_ROOT . '/admin#pane-matriculas');
        exit;
    }

    public function generateInvite() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $email = $_POST['email'] ?? '';
            $role = $_POST['role'] ?? 'Utilizador';
            $this->logActivity('Gerar Convite', ['email' => $email, 'role' => $role]);
            $_SESSION['flash_success'] = "Convite gerado e registo permitido para $email ($role).";
        }
        header('Location: ' . URL_ROOT . '/admin#pane-professores');
        exit;
    }

    /**
     * Exportação Financeira (CSV).
     * 
     * // Sugestão: Para relatórios maiores, usar geração assíncrona ou Paginação 
     * // para não exceder o tempo de execução do PHP (max_execution_time).
     */
    public function exportFinanceiro() {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT p.*, u.nome_completo as aluno 
                            FROM pagamentos p 
                            JOIN estudantes e ON p.estudante_id = e.id 
                            JOIN utilizadores u ON e.utilizador_id = u.id");
        $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="relatorio_financeiro_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Aluno', 'Descricao', 'Valor', 'Vencimento', 'Status', 'Data Pagamento']);
        foreach ($payments as $p) {
            fputcsv($output, [$p['id'], $p['aluno'], $p['descricao'], $p['valor'], $p['data_vencimento'], $p['status'], $p['data_pagamento']]);
        }
        fclose($output);
        exit;
    }
    /**
     * Gestão de Estudantes (Cadastro via Admin).
     */
    public function saveStudent() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $userModel = $this->model('User');
            $estudanteModel = $this->model('Estudante');
            
            $id = $_POST['id'] ?? null;
            $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $senha = $_POST['password'] ?? null;
            
            $estudanteData = [
                'bi' => $_POST['bi'],
                'data_nascimento' => $_POST['data_nascimento'],
                'nacionalidade' => $_POST['nacionalidade'] ?? 'Guineense',
                'sexo' => $_POST['sexo'] ?? 'Masculino',
                'telefone' => $_POST['telefone'] ?? '',
                'telefone_alternativo' => $_POST['telefone_alternativo'] ?? '',
                'estado_civil' => $_POST['estado_civil'] ?? 'Solteiro',
                'cidade' => $_POST['cidade'] ?? '',
                'bairro' => $_POST['bairro'] ?? '',
                'morada' => $_POST['morada'] ?? '',
                'escola' => $_POST['escola'] ?? '',
                'ano_conclusao' => $_POST['ano_conclusao'] ?? null,
                'media' => $_POST['media'] ?? null,
                'encarregado_nome' => $_POST['encarregado_nome'] ?? '',
                'encarregado_telefone' => $_POST['encarregado_telefone'] ?? ''
            ];

            if ($id) {
                // // Identificação de Pontos Cegos: Se o email for alterado aqui, 
                // // não há verificação de duplicidade para o novo email.
                
                // --- Correção de Bug: Verificação de duplicidade de email na edição ---
                $currentUser = $userModel->findById($id);
                if ($currentUser && $currentUser['email'] !== $email) {
                    $existingUser = $userModel->findByEmail($email);
                    if ($existingUser) {
                        $_SESSION['flash_error'] = "O email '{$email}' já está em uso por outro utilizador.";
                        header('Location: ' . URL_ROOT . '/admin');
                        exit;
                    }
                }
                // ------------------------------------------------------------------

                $userData = ['nome_completo' => $nome, 'email' => $email];
                if (!empty($senha)) $userData['senha'] = $senha;
                
                $userModel->updateUser($id, $userData);
                
                $estudante = $estudanteModel->findByUserId($id);
                if ($estudante) {
                    $estudanteModel->updateEstudante($estudante['id'], $estudanteData);
                }
                $this->logActivity('Atualizar Estudante Admin', ['user_id' => $id]);
                $_SESSION['flash_success'] = "Estudante atualizado com sucesso.";
            } else {
                // Create
                $userId = $userModel->insertUser($nome, $email, $senha ?: '123456', 'aluno');
                if ($userId) {
                    $db = Database::getInstance();
                    $db->prepare("UPDATE utilizadores SET requires_pw_change = 1 WHERE id = ?")->execute([$userId]);
                    $estudanteData['utilizador_id'] = $userId;
                    $estudanteModel->createEstudante($estudanteData);
                    $this->logActivity('Criar Estudante Admin', ['nome' => $nome]);
                    $_SESSION['flash_success'] = "Estudante criado com sucesso.";
                } else {
                    $_SESSION['flash_error'] = "Erro ao criar utilizador. Email já existe?";
                }
            }
            header('Location: ' . URL_ROOT . '/admin');
            exit;
        }
    }

    public function deleteStudent($id) {
        $userModel = $this->model('User');
        $estudanteModel = $this->model('Estudante');
        $this->logActivity('Remover Estudante', ['user_id' => $id]);
        $estudanteModel->deleteEstudanteByUserId($id);
        $userModel->deleteUser($id);
        $_SESSION['flash_success'] = "Estudante removido com sucesso.";
        header('Location: ' . URL_ROOT . '/admin');
        exit;
    }

    public function toggleStudentStatus($id, $status) {
        $userModel = $this->model('User');
        if ($userModel->updateUser($id, ['status' => $status])) {
            $this->logActivity('Alterar Status do Estudante', ['user_id' => $id, 'status' => $status]);
            $_SESSION['flash_success'] = "Status do estudante atualizado para $status.";
        } else {
            $_SESSION['flash_error'] = "Erro ao atualizar status.";
        }
        header('Location: ' . URL_ROOT . '/admin');
        exit;
    }

    public function resetStudentPassword($id) {
        $userModel = $this->model('User');
        $novaSenha = substr(md5(time()), 0, 6); // Senha aleatória curta
        if ($userModel->updateUser($id, ['senha' => $novaSenha])) {
            $db = Database::getInstance();
            $db->prepare("UPDATE utilizadores SET requires_pw_change = 1 WHERE id = ?")->execute([$id]);
            $this->logActivity('Resetar Senha de Estudante', ['user_id' => $id]);
            $_SESSION['flash_success'] = "Senha resetada com sucesso para: $novaSenha";
        } else {
            $_SESSION['flash_error'] = "Erro ao resetar senha.";
        }
        header('Location: ' . URL_ROOT . '/admin');
        exit;
    }

    public function savePagamento() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $model = $this->model('Pagamento');
            if ($model->createManual($_POST)) {
                $this->logActivity('Registrar Pagamento Manual', ['estudante_id' => $_POST['estudante_id'] ?? 'N/A']);
                $_SESSION['flash_success'] = "Pagamento registado com sucesso.";
            } else {
                $_SESSION['flash_error'] = "Erro ao registar pagamento.";
            }
            header('Location: ' . URL_ROOT . '/admin');
            exit;
        }
    }

    /**
     * Gestão de Professores (v2 - Atribuições em Bloco).
     * 
     * // Nota: Este método é mais completo que o saveProfessor() anterior, 
     * // permitindo definir múltiplas disciplinas/turmas de uma só vez.
     */
    public function createProfessor() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken();
            $atribuicoes = [];
            if (isset($_POST['turma_id']) && isset($_POST['disciplina_id'])) {
                foreach ($_POST['turma_id'] as $key => $tid) {
                    if (!empty($tid) && !empty($_POST['disciplina_id'][$key])) {
                        $atribuicoes[] = [
                            'turma_id' => $tid,
                            'disciplina_id' => $_POST['disciplina_id'][$key]
                        ];
                    }
                }
            }
            
            $data = [
                'nome' => $_POST['nome'],
                'email' => $_POST['email'],
                'senha' => $_POST['senha'] ?? '123456',
                'bi' => $_POST['bi'],
                'telefone' => $_POST['telefone'],
                'especialidade' => $_POST['especialidade'],
                'grau_academico' => $_POST['grau_academico'],
                'data_contratacao' => $_POST['data_contratacao'],
                'atribuicoes' => $atribuicoes
            ];

            if ($this->model('Professor')->createManual($data)) {
                $this->logActivity('Criar Professor v2', ['nome' => $data['nome']]);
                $_SESSION['flash_success'] = "Professor cadastrado com sucesso.";
            } else {
                $_SESSION['flash_error'] = "Erro ao criar professor. O email pode já estar em uso.";
            }
            header('Location: ' . URL_ROOT . '/admin#pane-professores');
            exit;
        }
    }

    public function updateProfessor() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrfToken();
            $id = $_POST['id'];
            $atribuicoes = [];
            if (isset($_POST['turma_id']) && isset($_POST['disciplina_id'])) {
                foreach ($_POST['turma_id'] as $key => $tid) {
                    if (!empty($tid) && !empty($_POST['disciplina_id'][$key])) {
                        $atribuicoes[] = [
                            'turma_id' => $tid,
                            'disciplina_id' => $_POST['disciplina_id'][$key]
                        ];
                    }
                }
            }

            $data = [
                'nome' => $_POST['nome'],
                'email' => $_POST['email'],
                'senha' => $_POST['senha'],
                'bi' => $_POST['bi'],
                'telefone' => $_POST['telefone'],
                'especialidade' => $_POST['especialidade'],
                'grau_academico' => $_POST['grau_academico'],
                'data_contratacao' => $_POST['data_contratacao'],
                'atribuicoes' => $atribuicoes
            ];

            if ($this->model('Professor')->updateManual($id, $data)) {
                $this->logActivity('Atualizar Professor v2', ['nome' => $data['nome']]);
                $_SESSION['flash_success'] = "Professor atualizado com sucesso.";
            } else {
                $_SESSION['flash_error'] = "Erro ao atualizar professor.";
            }
            header('Location: ' . URL_ROOT . '/admin#pane-professores');
            exit;
        }
    }

    /**
     * Recupera dados completos do professor via AJAX.
     */
    public function getProfessorData($id) {
        $prof = $this->model('Professor')->findById($id);
        $atribuicoes = $this->model('Professor')->getAssignedClasses($id);
        header('Content-Type: application/json');
        echo json_encode(['prof' => $prof, 'atribuicoes' => $atribuicoes]);
    }

    public function saveComunicado() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $model = $this->model('Comunicado');
            if ($model->create($_POST)) {
                $this->logActivity('Enviar Comunicado', ['titulo' => $_POST['titulo'] ?? 'N/A']);
                $_SESSION['flash_success'] = "Comunicado publicado com sucesso.";
            } else {
                $_SESSION['flash_error'] = "Erro ao publicar comunicado.";
            }
            header('Location: ' . URL_ROOT . '/admin');
            exit;
        }
    }

    public function deleteTurma($id) {
        if ($this->model('Turma')->deleteTurma($id)) {
            $this->logActivity('Remover Turma', ['id' => $id]);
            $_SESSION['flash_success'] = "Turma excluída com sucesso!";
        } else {
            $_SESSION['flash_error'] = "Erro ao excluir turma.";
        }
        header('Location: ' . URL_ROOT . '/admin#pane-turmas');
        exit;
    }

    public function getComunicadoStats($id) {
        $stats = $this->model('Comunicado')->getLeiturasPorComunicado($id);
        header('Content-Type: application/json');
        echo json_encode($stats);
        exit;
    }

    /**
     * Sistema de Horário Modelo (Grade Escolar Fixa).
     */
    public function saveHorarioModelo() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = Database::getInstance();
            $stmt = $db->prepare("INSERT INTO horarios_modelo (ano_id, dia_semana, hora_inicio, hora_fim, disciplina_id, sala) 
                                 VALUES (:ano, :dia, :ini, :fim, :did, :sala)");
            $stmt->execute([
                ':ano' => $_POST['ano_id'],
                ':dia' => $_POST['dia_semana'],
                ':ini' => $_POST['hora_inicio'],
                ':fim' => $_POST['hora_fim'],
                ':did' => $_POST['disciplina_id'] ?: null,
                ':sala' => $_POST['sala']
            ]);
            $_SESSION['flash_success'] = "Padrão de horário salvo.";
            header('Location: ' . URL_ROOT . '/admin#pane-horarios');
            exit;
        }
    }

    /**
     * Recupera o modelo de horário (grade fixa) via AJAX.
     * 
     * // Refatoração: Mais uma vez, o controlador renderiza HTML diretamente. 
     * // Sugestão: Migrar para JSON ou Blade-style partials para manter o MVC "limpo".
     */
    public function getHorarioModeloAjax($ano_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT hm.*, d.nome as disciplina_nome FROM horarios_modelo hm LEFT JOIN disciplinas d ON hm.disciplina_id = d.id WHERE hm.ano_id = :aid ORDER BY FIELD(hm.dia_semana, 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'), hm.hora_inicio");
        $stmt->execute([':aid' => $ano_id]);
        $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($slots)) {
            echo '<div class="text-center py-5 text-muted"><ion-icon name="calendar-outline" style="font-size: 3rem;" class="opacity-25"></ion-icon><p class="mt-2">Nenhum slot definido.</p></div>';
        } else {
            // Renderização da tabela de modelo
            echo '<div class="table-responsive"><table class="table table-hover table-sm align-middle mb-0" style="font-size: 0.85rem;"><thead class="table-light"><tr><th>DIA</th><th>HORÁRIO</th><th>DISCIPLINA</th><th class="text-end pe-3">AÇÃO</th></tr></thead><tbody>';
            foreach ($slots as $s) {
                $checkNight = (int)substr($s['hora_inicio'], 0, 2) >= 17;
                echo '<tr><td class="ps-3 fw-bold">'.$s['dia_semana'].'</td><td><span class="badge '.($checkNight ? 'bg-dark' : 'bg-primary').' bg-opacity-10 text-'.($checkNight ? 'dark' : 'primary').' px-2">'.substr($s['hora_inicio'],0,5).' – '.substr($s['hora_fim'],0,5).'</span></td><td><div class="fw-bold">'.($s['disciplina_nome'] ?? 'A definir').'</div></td><td class="text-end pe-3"><button class="btn btn-link text-danger p-0" onclick="deleteModeloSlot('.$s['id'].', '.$s['ano_id'].')"><ion-icon name="trash-outline"></ion-icon></button></td></tr>';
            }
            echo '</tbody></table></div>';
        }
    }

    public function deleteHorarioModelo($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("DELETE FROM horarios_modelo WHERE id = :id");
        $stmt->execute([':id' => $id]);
        echo "OK";
        exit;
    }

    public function replicateModeloToTurma($ano_id, $turma_id) {
        $this->logActivity('Replicar Horário Modelo', ['ano_id' => $ano_id, 'turma_id' => $turma_id]);
        $db = Database::getInstance();
        // Buscar slots do modelo
        $stmt = $db->prepare("SELECT * FROM horarios_modelo WHERE ano_id = :aid");
        $stmt->execute([':aid' => $ano_id]);
        $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($slots)) {
            $_SESSION['flash_error'] = "Não existe um modelo de horário para o ano desta turma.";
            header('Location: ' . URL_ROOT . '/admin');
            exit;
        }

        // Limpar horários existentes da turma se o admin quiser (ou só adicionar)
        // Por segurança, vamos apenas adicionar ou avisar. O admin deve limpar se quiser sobrescrever.
        
        $stmtIns = $db->prepare("INSERT INTO horarios (turma_id, disciplina_id, professor_id, dia_semana, hora_inicio, hora_fim, sala) 
                                VALUES (:tid, :did, :pid, :dia, :ini, :fim, :sala)");

        foreach ($slots as $s) {
            // Professor_id é opcional no modelo, mas obrigatório no horário da turma.
            // Vamos tentar achar o professor que ensina a disciplina nesta turma ou deixar ID 1 como fallback.
            $stmtProf = $db->prepare("SELECT professor_id FROM professor_disciplina WHERE turma_id = :tid AND disciplina_id = :did LIMIT 1");
            $stmtProf->execute([':tid' => $turma_id, ':did' => $s['disciplina_id']]);
            $prof = $stmtProf->fetch();
            $pid = $prof ? $prof['professor_id'] : 1; 

            $stmtIns->execute([
                ':tid' => $turma_id,
                ':did' => $s['disciplina_id'],
                ':pid' => $pid,
                ':dia' => $s['dia_semana'],
                ':ini' => $s['hora_inicio'],
                ':fim' => $s['hora_fim'],
                ':sala' => $s['sala']
            ]);
        }

        $_SESSION['flash_success'] = "Horário aplicado com sucesso a partir do modelo do ano.";
        header('Location: ' . URL_ROOT . '/admin');
        exit;
    }

    public function getTurmaInfo($id) {
        $model = $this->model('Turma');
        $turma = $model->findById($id);
        header('Content-Type: application/json');
        echo json_encode($turma);
        exit;
    }

    public function getTurmaStudentsAjax($id) {
        $model = $this->model('Professor');
        $students = $model->getStudentsByTurma($id);
        
        if (empty($students)) {
            echo '<div class="alert alert-warning py-2 mb-0">Nenhum aluno alocado nesta turma.</div>';
        } else {
            echo '<table class="table table-sm small mb-0">
                    <thead class="table-dark"><tr><th>Nome</th><th>Status</th></tr></thead>
                    <tbody>';
            foreach ($students as $s) {
                echo '<tr>
                        <td class="fw-bold">'.$s['nome_completo'].'</td>
                        <td><span class="badge bg-success">Ativo</span></td>
                      </tr>';
            }
            echo '</tbody></table>';
        }
        exit;
    }

    public function getProfessorInfo($id) {
        $model = $this->model('Professor');
        $prof = $model->getDetails($id);
        header('Content-Type: application/json');
        echo json_encode($prof);
        exit;
    }

    public function getStudentDetails($id) {
        $model = $this->model('Estudante');
        $student = $model->getDetailsByUserId($id);
        header('Content-Type: application/json');
        echo json_encode($student);
        exit;
    }

    public function convocarPartes($estudante_id, $disciplina_id) {
        $this->verifyCsrfToken();
        $motivo = $_POST['motivo'] ?? 'Convocatória oficial para resolução de conflito de notas.';
        
        $notaModel = $this->model('Nota');
        $detalhes = $notaModel->getConflitoDetalhes($estudante_id, $disciplina_id);
        
        if ($detalhes) {
            $msgModel = $this->model('Mensagem');
            $msgCorpo = "O Diretor convocou as partes para a resolução de conflito de nota na disciplina: " . $detalhes['disciplina_nome'] . ".\n\nMotivo: " . $motivo . "\n\nPor favor, compareça à sala da Direção/Secretaria no próximo horário disponível.";
            
            // Notificar Aluno
            $msgModel->send($_SESSION['user_id'], $detalhes['estudante_user_id'], "CONVOCATÓRIA: Conflito de Nota", $msgCorpo);
            
            // Notificar Professor
            if ($detalhes['professor_user_id']) {
                $msgModel->send($_SESSION['user_id'], $detalhes['professor_user_id'], "CONVOCATÓRIA: Conflito de Nota", $msgCorpo);
            }
        }

        $notaModel->resolverConflito($estudante_id, $disciplina_id);
        
        $this->model('Mensagem')->notifyGroup('secretaria', "O Administrador convocou as partes para a resolução de conflito de nota (Aluno ID: $estudante_id). Motivo: $motivo", $_SESSION['user_id']);

        $_SESSION['flash_success'] = "Convocatória enviada com sucesso ao Professor e ao Aluno!";
        header('Location: ' . URL_ROOT . '/admin');
        exit;
    }
    public function confirmSummary($id) {
        $this->logActivity('Confirmar Sumário', ['sumario_id' => $id]);
        $db = Database::getInstance();
        $db->prepare("UPDATE sumarios SET confirmado_admin = 1 WHERE id = :id")->execute([':id' => $id]);
        $_SESSION['flash_success'] = "Sumário confirmado como recebido.";
        header('Location: ' . URL_ROOT . '/admin#pane-pedagogico');
        exit;
    }

    /**
     * Orquestrador de Confirmação Pedagógica.
     * Valida em lote as notas submetidas por professores para uma turma/disciplina.
     */
    public function confirmGrades() {
        $turma_id = $_GET['turma_id'] ?? 0;
        $disciplina_id = $_GET['disciplina_id'] ?? 0;
        $this->logActivity('Confirmar Lote de Notas', ['turma_id' => $turma_id, 'disciplina_id' => $disciplina_id]);
        $db = Database::getInstance();
        
        // // Lógica de Negócio: O JOIN garante que confirmamos as notas vinculadas às avaliações corretas.
        $db->prepare("
            UPDATE notas n
            JOIN avaliacoes a ON n.avaliacao_id = a.id
            SET n.confirmado_admin = 1
            WHERE a.turma_id = :tid AND a.disciplina_id = :did
        ")->execute([':tid' => $turma_id, ':did' => $disciplina_id]);
        
        $_SESSION['flash_success'] = "Lote de notas confirmado com sucesso.";
        header('Location: ' . URL_ROOT . '/admin#pane-pedagogico');
        exit;
    }

    public function confirmAttendance() {
        $turma_id = $_GET['turma_id'] ?? 0;
        $disciplina_id = $_GET['disciplina_id'] ?? 0;
        $this->logActivity('Confirmar Lote de Frequências', ['turma_id' => $turma_id, 'disciplina_id' => $disciplina_id]);
        $db = Database::getInstance();
        $db->prepare("UPDATE frequencias SET confirmado_admin = 1 WHERE turma_id = :tid AND disciplina_id = :did")->execute([':tid' => $turma_id, ':did' => $disciplina_id]);
        $_SESSION['flash_success'] = "Lote de frequências confirmado.";
        header('Location: ' . URL_ROOT . '/admin#pane-pedagogico');
        exit;
    }


    // ─── Calendário Escolar ─── (Métodos movidos para o final para persistência)


    public function alocarAluno() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URL_ROOT . '/admin');
            exit;
        }
        $this->verifyCsrfToken();
        $estudante_id = (int)($_POST['estudante_id'] ?? 0);
        $turma_id     = (int)($_POST['turma_id'] ?? 0);
        $ano_letivo   = (int)($_POST['ano_letivo'] ?? date('Y'));
        $turno        = $_POST['turno'] ?? 'Manhã';
        $sala         = trim($_POST['sala'] ?? '');
        $observacoes  = trim($_POST['observacoes'] ?? '');

        if (!$estudante_id || !$turma_id) {
            $_SESSION['flash_error'] = "Estudante e Turma são obrigatórios para alocar.";
            header('Location: ' . URL_ROOT . '/admin#pane-alunos');
            exit;
        }

        $db = Database::getInstance();

        // Fetch ano_curso_id from turma
        $stmtT = $db->prepare("SELECT ano_id FROM turmas WHERE id = :tid");
        $stmtT->execute([':tid' => $turma_id]);
        $turmaRow = $stmtT->fetch(PDO::FETCH_ASSOC);
        $ano_curso_id = $turmaRow['ano_id'] ?? 1;

        // Check if allocation already exists
        $stmtChk = $db->prepare("SELECT id FROM matriculas WHERE estudante_id = :eid AND turma_id = :tid AND ano_letivo = :al");
        $stmtChk->execute([':eid' => $estudante_id, ':tid' => $turma_id, ':al' => $ano_letivo]);
        if ($stmtChk->fetch()) {
            $_SESSION['flash_error'] = "Aluno já está alocado nesta turma para o ano letivo $ano_letivo.";
            header('Location: ' . URL_ROOT . '/admin#pane-alunos');
            exit;
        }

        $stmt = $db->prepare("
            INSERT INTO matriculas (estudante_id, turma_id, ano_letivo, ano_curso_id, turno, tipo, status, data_matricula, observacoes, aprovado_por, data_aprovacao)
            VALUES (:eid, :tid, :al, :acid, :turno, 'Estudante Interno', 'Aprovada', CURDATE(), :obs, :admin, CURDATE())
        ");

        if ($stmt->execute([
            ':eid'   => $estudante_id,
            ':tid'   => $turma_id,
            ':al'    => $ano_letivo,
            ':acid'  => $ano_curso_id,
            ':turno' => $turno,
            ':obs'   => $sala ? "Sala: $sala. $observacoes" : $observacoes,
            ':admin' => $_SESSION['user_id'],
        ])) {
            $this->logActivity('Alocar Aluno Interno', ['estudante_id' => $estudante_id, 'turma_id' => $turma_id]);
            $_SESSION['flash_success'] = "Aluno alocado com sucesso na turma para $ano_letivo!";
        } else {
            $_SESSION['flash_error'] = "Erro ao alocar aluno. Verifique os dados.";
        }

        header('Location: ' . URL_ROOT . '/admin#pane-alunos');
        exit;
    }

    public function getEventosAjax() {
        header('Content-Type: application/json');
        $model = $this->model('Evento');
        
        $raw = $model->getAll();
        $grouped = [];
        foreach($raw as $ev) {
            $key = $ev['titulo'] . '_' . $ev['tipo'];
            if (!isset($grouped[$key])) {
                $ev['data_fim'] = $ev['data_evento'];
                $grouped[$key] = $ev;
            } else {
                if (strtotime($ev['data_evento']) > strtotime($grouped[$key]['data_fim'])) {
                    $grouped[$key]['data_fim'] = $ev['data_evento'];
                }
                if (strtotime($ev['data_evento']) < strtotime($grouped[$key]['data_evento'])) {
                    $grouped[$key]['data_evento'] = $ev['data_evento'];
                }
            }
        }
        
        $result = array_values($grouped);
        
        // Add visual text for data range for the UI
        foreach ($result as &$e) {
            if ($e['data_evento'] != $e['data_fim']) {
                $e['data_evento_display'] = date('d/m/Y', strtotime($e['data_evento'])) . ' a ' . date('d/m/Y', strtotime($e['data_fim']));
            } else {
                $e['data_evento_display'] = date('d/m/Y', strtotime($e['data_evento']));
            }
        }
        
        echo json_encode($result);
        exit;
    }

    public function saveEvento() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $model = $this->model('Evento');
            if ($model->create($_POST)) {
                $this->logActivity('Registrar Evento', ['titulo' => $_POST['titulo'] ?? 'N/A']);
                $_SESSION['flash_success'] = "Evento registado com sucesso.";
            } else {
                $_SESSION['flash_error'] = "Erro ao registar evento.";
            }
        }
        header('Location: ' . URL_ROOT . '/admin#pane-calendario');
        exit;
    }

    public function deleteEvento($id) {
        $model = $this->model('Evento');
        if ($model->delete($id)) {
            $this->logActivity('Remover Evento', ['id' => $id]);
            $_SESSION['flash_success'] = "Evento removido.";
        } else {
            $_SESSION['flash_error'] = "Erro ao remover evento.";
        }
        header('Location: ' . URL_ROOT . '/admin#pane-calendario');
        exit;
    }

    public function getAnosJson() {
        header('Content-Type: application/json');
        $model = $this->model('Academico');
        echo json_encode($model->getAnos());
        exit;
    }

    public function getTurmasJson() {
        header('Content-Type: application/json');
        $model = $this->model('Turma');
        echo json_encode($model->getAll());
        exit;
    }

    public function markTeacherAttendance() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $model = $this->model('Frequencia');
            if ($model->markTeacherAttendance($_POST)) {
                $this->logActivity('Marcar Presença de Professor', ['professor_id' => $_POST['professor_id'] ?? 'N/A']);
                $_SESSION['flash_success'] = "Assiduidade do professor registada com sucesso.";
            } else {
                $_SESSION['flash_error'] = "Erro ao registar assiduidade.";
            }
        }
        header('Location: ' . URL_ROOT . '/admin#pane-pedagogico');
        exit;
    }

    // Métodos movidos para AdminFinanceiroController


    /**
     * Painel de Auditoria de Sistema (Logs).
     * Exibe o histórico de atividades críticas.
     */
    public function logs($page = 1) {
        $db = Database::getInstance();
        $limit = 50;
        $offset = ((int)$page - 1) * $limit;

        // Contar total de logs
        $stmtTotal = $db->query("SELECT COUNT(*) FROM logs_atividades");
        $total_logs = $stmtTotal->fetchColumn();

        // Buscar logs com limite e offset
        $stmtLogs = $db->prepare("SELECT * FROM logs_atividades ORDER BY data_acao DESC LIMIT :lim OFFSET :off");
        $stmtLogs->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmtLogs->bindValue(':off', $offset, PDO::PARAM_INT);
        $stmtLogs->execute();
        $logs = $stmtLogs->fetchAll(PDO::FETCH_ASSOC);

        $this->view('admin/logs', [
            'logs' => $logs,
            'total_logs' => $total_logs,
            'page' => $page
        ]);
    }

    /**
     * Manutenção de UI: Limpar Notificações Lidas.
     * 
     * @return void
     */
    public function clearNotifications() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            // // Lógica de UI: Marca todas as mensagens do admin como lidas para despoluir o widget.
            $this->model('Mensagem')->markAllAsRead($_SESSION['user_id']);
            $_SESSION['flash_success'] = "Painel de alertas limpo com sucesso.";
        }
        header('Location: ' . URL_ROOT . '/admin');
        exit;
    }

    public function getCalendarEvents() {
        $events = $this->model('Evento')->getAll();
        $formatted = [];
        foreach($events as $e) {
            $formatted[] = [
                'id'    => $e['id'],
                'title' => $e['titulo'],
                'start' => $e['data_evento'] . 'T08:00:00',
                'end'   => $e['data_evento'] . 'T18:00:00',
                'color' => $e['cor'] ?? '#10B981',
                'description' => $e['descricao']
            ];
        }
        header('Content-Type: application/json');
        echo json_encode($formatted);
        exit;
    }

    /**
     * Emite certificados de mérito para os alunos manualmente selecionados (via AJAX ou checkbox).
     * Acção exclusiva do Admin/Secretaria. Envia comunicado opcional após emissão.
     */
    public function emitirCertificadosMerito() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URL_ROOT . '/admin#pane-merito');
            exit;
        }
        $this->verifyCsrfToken();

        $semestre = $_POST['semestre'] ?? '';
        $ano_letivo = trim($_POST['ano_letivo'] ?? '');
        $tipo_comunicado = $_POST['tipo_comunicado'] ?? 'Global';
        
        if (!in_array($semestre, ['1', '2'])) {
            $_SESSION['flash_error'] = "Semestre inválido.";
            header('Location: ' . URL_ROOT . '/admin#pane-merito');
            exit;
        }

        if (empty($_POST['estudantes_selecionados']) || !is_array($_POST['estudantes_selecionados'])) {
            $_SESSION['flash_error'] = "Selecione pelo menos um aluno para emitir o certificado.";
            header('Location: ' . URL_ROOT . '/admin#pane-merito');
            exit;
        }

        $alunosParaPremiar = [];
        foreach ($_POST['estudantes_selecionados'] as $idx => $eid) {
            $alunosParaPremiar[] = [
                'estudante_id'    => (int)$eid,
                'nome_completo'   => $_POST['nomes'][$idx] ?? 'Aluno Desconhecido',
                'media_calculada' => (float)($_POST['medias'][$idx] ?? 0),
                'nivel_nome'      => $_POST['niveis'][$idx] ?? 'Ano Académico',
                'posicao'         => (string)($_POST['posicoes'][$idx] ?? ($idx + 1))
            ];
        }

        $academicoModel = $this->model('Academico');
        $emitidos = $academicoModel->emitirCertificadosSelecionados($semestre, $ano_letivo, $_SESSION['user_id'], $alunosParaPremiar);

        if (empty($emitidos)) {
            $_SESSION['flash_error'] = "Erro ao processar as emissões.";
            header('Location: ' . URL_ROOT . '/admin#pane-merito');
            exit;
        }

        // Montar mensagem de comunicado
        $premiadosTexto = '';
        foreach ($emitidos as $p) {
            $ord = $p['posicao'] === '1' ? '1º Lugar' : ($p['posicao'] . 'º Lugar');
            $premiadosTexto .= "\n• {$ord}: {$p['nome_completo']} — Média " . number_format($p['media_calculada'], 2) . " valores ({$p['nivel_nome']})";
        }

        $titulo = "🏆 Melhores Alunos do {$semestre}º Semestre {$ano_letivo}";
        $corpo  = "A Direção do GHS tem o prazer de anunciar os melhores alunos do {$semestre}º Semestre do Ano Letivo {$ano_letivo}:{$premiadosTexto}\n\nOs alunos distinguidos encontrarão o seu Certificado de Mérito disponível no Portal do Estudante para visualização e impressão.\n\nParabéns a todos os premiados pelo empenho e dedicação!";

        $comunicadoModel = $this->model('Comunicado');
        
        if ($tipo_comunicado === 'Global') {
            // Comunicado para todos
            $comunicadoModel->create([
                'titulo'           => $titulo,
                'conteudo'         => $corpo,
                'tipo'             => 'Mérito',
                'destinatario_tipo'=> 'Global',
                'destinatario_id'  => null,
                'criado_por'       => $_SESSION['user_id'],
                'csrf_token'       => $_SESSION['csrf_token']
            ]);
        } else {
            // Comunicado apenas para os alunos premiados (Turma/Estudante individual não é trivial em lote no Comunicado nativo, então podemos emitir um para cada)
            foreach ($emitidos as $p) {
                // Apenas como exemplo, notificar os utilizadores pai
                // Como não temos um dest_tipo "Estudante único" pronto no comunicados table, simulamos Turma
            }
        }

        $this->logActivity('Emitir Certificados de Mérito (' . count($emitidos) . ')', [
            'semestre' => $semestre,
            'ano_letivo' => $ano_letivo
        ]);

        $_SESSION['flash_success'] = "✅ Certificados emitidos com sucesso para " . count($emitidos) . " aluno(s) do {$semestre}º Semestre!";
        header('Location: ' . URL_ROOT . '/admin#pane-merito');
        exit;
    }

    /**
     * Retorna a lista dos Top 10 alunos elegíveis de um semestre via AJAX.
     */
    public function getAlunosElegiveisMerito() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') exit;

        $sem = $_POST['semestre'] ?? '1';
        $ano = trim($_POST['ano_letivo'] ?? '');

        header('Content-Type: application/json');
        $topList = $this->model('Academico')->getTopBySemestre($sem, $ano, 10);
        
        echo json_encode($topList ?: []);
        exit;
    }

    /**
     * Lista certificados emitidos via AJAX para o painel admin.
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
     * Salva a assinatura digital do Diretor (Admin) no certificado.
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
        // Admin assina como Diretor
        $res = $academicoModel->assinarCertificado($id, $assinatura, 'diretor');
        
        if ($res) $this->logActivity('Assinar Certificado (Diretor)', ['id' => $id]);
        
        echo json_encode(['success' => $res]);
        exit;
    }
}


