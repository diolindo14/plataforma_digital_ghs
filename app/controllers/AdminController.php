<?php
class AdminController extends Controller {
    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /green/auth');
            exit;
        }
    }

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
        $data['matriculas'] = $this->model('Matricula')->getPendingEnrollments();
        $data['estudantes'] = $this->model('Estudante')->getAllStudents();
        
        // Novos dados pedagógicos
        $frequenciaModel = $this->model('Frequencia');
        $data['sumarios'] = $frequenciaModel->getAllSummaries();
        $data['frequencias_report'] = $frequenciaModel->getFrequenciaRelatorio();
        $data['notas_report'] = $this->model('Nota')->getRelatorioGeral();
        $data['atrasos_sumarios'] = $frequenciaModel->getMissingSummaries();
        $data['teacher_attendance_report'] = $frequenciaModel->getTeacherAttendanceReport();
        $data['detailed_attendance'] = $frequenciaModel->getDetailedAttendanceLog();

        $data['tipos_pagamento'] = $this->model('Pagamento')->getTiposPagamento();
        $data['professores'] = $this->model('Professor')->getAllProfessors();
        $data['secretarios'] = $this->model('Administrador')->getAllSecretarios();
        $data['todas_disciplinas'] = $this->model('Disciplina')->getAll();
        
        $this->view('admin/dashboard', $data);
    }

    public function saveTurma() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $turmaModel = $this->model('Turma');
            if ($turmaModel->createTurma($_POST)) {
                $this->logActivity('Criar Turma', ['codigo' => $_POST['codigo'] ?? 'N/A']);
                $_SESSION['flash_success'] = "Turma criada com sucesso.";
            } else {
                $_SESSION['flash_error'] = "Erro ao criar turma.";
            }
            header('Location: /green/admin');
            exit;
        }
    }

    public function saveAno() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $model = $this->model('Academico');
            $id = $_POST['id'] ?? null;
            $res = $id ? $model->updateAno($id, $_POST) : $model->createAno($_POST);
            
            if ($res) {
                $this->logActivity($id ? 'Atualizar Ano Curricular' : 'Criar Ano Curricular', ['id' => $id]);
                $_SESSION['flash_success'] = "Ano curricular salvo com sucesso.";
            } else {
                $_SESSION['flash_error'] = "Erro ao salvar ano curricular.";
            }
            header('Location: /green/admin');
            exit;
        }
    }

    public function deleteAno($id) {
        $model = $this->model('Academico');
        if ($model->deleteAno($id)) {
            $this->logActivity('Remover Ano Curricular', ['id' => $id]);
            $_SESSION['flash_success'] = "Ano curricular removido.";
        } else {
            $_SESSION['flash_error'] = "Erro ao remover (pode haver dados vinculados).";
        }
        header('Location: /green/admin');
        exit;
    }

    public function saveDisciplina() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $model = $this->model('Disciplina');
            $id = $_POST['id'] ?? null;
            $res = $id ? $model->update($id, $_POST) : $model->create($_POST);
            
            if ($res) {
                $this->logActivity($id ? 'Atualizar Disciplina' : 'Criar Disciplina', ['id' => $id, 'nome' => $_POST['nome'] ?? 'N/A']);
                $_SESSION['flash_success'] = "Disciplina salva com sucesso.";
            } else {
                $_SESSION['flash_error'] = "Erro ao salvar disciplina.";
            }
            header('Location: /green/admin');
            exit;
        }
    }

    public function deleteDisciplina($id) {
        $model = $this->model('Disciplina');
        if ($model->delete($id)) {
            $this->logActivity('Remover Disciplina', ['id' => $id]);
            $_SESSION['flash_success'] = "Disciplina removida.";
        } else {
            $_SESSION['flash_error'] = "Erro ao remover disciplina.";
        }
        header('Location: /green/admin');
        exit;
    }

    public function saveEspecialidade() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $model = $this->model('Especialidade');
            $id = $_POST['id'] ?? null;
            $res = $id ? $model->updateEspecialidade($id, $_POST) : $model->createEspecialidade($_POST);
            
            if ($res) {
                $this->logActivity($id ? 'Atualizar Especialidade' : 'Criar Especialidade', ['id' => $id, 'nome' => $_POST['nome'] ?? 'N/A']);
                $_SESSION['flash_success'] = "Especialidade salva com sucesso.";
            } else {
                $_SESSION['flash_error'] = "Erro ao salvar especialidade.";
            }
            header('Location: /green/admin');
            exit;
        }
    }

    public function deleteEspecialidade($id) {
        $model = $this->model('Especialidade');
        if ($model->deleteEspecialidade($id)) {
            $this->logActivity('Remover Especialidade', ['id' => $id]);
            $_SESSION['flash_success'] = "Especialidade removida.";
        } else {
            $_SESSION['flash_error'] = "Erro ao remover especialidade.";
        }
        header('Location: /green/admin');
        exit;
    }

    public function saveProfessor() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $model = $this->model('Professor');
            $id = $_POST['id'] ?? null;
            
            if ($id) {
                $res = $model->updateManual($id, $_POST);
                $msg = "Professor atualizado com sucesso.";
            } else {
                $res = $model->createManual($_POST);
                $msg = "Professor cadastrado com sucesso.";
            }

            if ($res) {
                $this->logActivity($id ? 'Atualizar Professor' : 'Criar Professor', ['id' => $id, 'nome' => $_POST['nome'] ?? 'N/A']);
                $_SESSION['flash_success'] = $msg;
            } else {
                $_SESSION['flash_error'] = "Erro ao salvar professor. Email duplicado?";
            }
            header('Location: /green/admin');
            exit;
        }
    }

    public function deleteProfessor($id) {
        $model = $this->model('Professor');
        if ($model->deleteProfessor($id)) {
            $this->logActivity('Remover Professor', ['id' => $id]);
            $_SESSION['flash_success'] = "Professor e respetiva conta removidos.";
        } else {
            $_SESSION['flash_error'] = "Erro ao remover professor.";
        }
        header('Location: /green/admin');
        exit;
    }

    public function saveHorario() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $model = $this->model('Horario');
            $res = $model->allocate($_POST);
            
            if ($res['success']) {
                $this->logActivity('Alocar Horário', ['turma_id' => $_POST['turma_id'] ?? 'N/A']);
                $_SESSION['flash_success'] = "Horário alocado com sucesso.";
            } else {
                $_SESSION['flash_error'] = $res['message'] ?? "Erro ao alocar horário.";
            }
            header('Location: /green/admin');
            exit;
        }
    }

    public function deleteHorario($id) {
        $model = $this->model('Horario');
        if ($model->delete($id)) {
            $this->logActivity('Remover Slot de Horário', ['id' => $id]);
            $_SESSION['flash_success'] = "Horário removido.";
        } else {
            $_SESSION['flash_error'] = "Erro ao remover horário.";
        }
        header('Location: /green/admin');
        exit;
    }

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
            header('Location: /green/admin');
            exit;
        }
    }

    public function getHorariosAjax($turma_id) {
        $model = $this->model('Horario');
        $horarios = $model->getHorarioByTurma($turma_id);
        
        $dias = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta'];
        $tempos = [
            '1º' => ['07:20', '08:50'],
            '2º' => ['08:55', '10:25'],
            '3º' => ['10:45', '12:15'],
            '4º' => ['12:20', '13:50'],
            'N1' => ['17:45', '19:15'],
            'N2' => ['19:20', '20:50'],
            'N3' => ['21:00', '22:30'],
            'N4' => ['22:35', '24:00']
        ];

        if (empty($horarios)) {
            echo '<p class="text-center text-muted py-4">Nenhum horário alocado para esta turma.</p>';
            return;
        }

        echo '<div class="table-responsive"><table class="table table-bordered table-sm text-center small align-middle">
                <thead class="table-dark">
                    <tr><th>TEMPO</th><th>HORA</th>';
        foreach ($dias as $d) echo '<th>' . strtoupper($d) . '</th>';
        echo '</tr></thead><tbody>';

        foreach ($tempos as $t_label => $t_horas) {
            echo '<tr>
                    <td class="fw-bold bg-light">'.$t_label.'</td>
                    <td class="small nowrap">'.$t_horas[0].' – '.$t_horas[1].'</td>';
            foreach ($dias as $d) {
                $found = false;
                foreach ($horarios as $h) {
                    if ($h['dia_semana'] == $d && substr($h['hora_inicio'], 0, 5) == $t_horas[0]) {
                        echo '<td class="p-2">
                                <div class="fw-bold text-primary">'.$h['nome_display'].' ('.$h['sigla'].')</div>
                                <div class="text-muted" style="font-size:0.7rem">'.$h['sala'].'</div>
                                <div class="mt-1"><button class="btn btn-xs p-0 text-danger" onclick="if(confirm(\'Remover slot?\')) window.location.href=\'/green/admin/deleteHorario/'.$h['id'].'\'"><ion-icon name="close-circle-outline"></ion-icon></button></div>
                              </td>';
                        $found = true;
                        break;
                    }
                }
                if (!$found) echo '<td class="text-muted">-</td>';
            }
            echo '</tr>';
        }
        echo '</tbody></table></div>';
    }

    public function approveMatricula($id) {
        $this->verifyCsrfToken();
        $matriculaModel = $this->model('Matricula');
        $db = Database::getInstance();
        
        // 1. Get Matricula Data
        $stmt = $db->prepare("SELECT m.*, u.email, u.id as user_id FROM matriculas m JOIN estudantes e ON m.estudante_id = e.id JOIN utilizadores u ON e.utilizador_id = u.id WHERE m.id = :id");
        $stmt->execute([':id' => $id]);
        $m = $stmt->fetch();

        if ($matriculaModel->updateStatus($id, 'Aprovada', $_SESSION['user_id'])) {
            $this->logActivity('Aprovar Matrícula', ['matricula_id' => $id, 'aluno' => $m['email'] ?? 'N/A']);
            // 2. Activate User
            $db->prepare("UPDATE utilizadores SET status = 'ativo' WHERE id = :uid")->execute([':uid' => $m['user_id']]);

            // 3. Auto-allocate Turma
            $stmtT = $db->prepare("SELECT id FROM turmas WHERE ano_id = :ano AND turno = :turno AND vagas > (SELECT COUNT(*) FROM matriculas WHERE turma_id = turmas.id) LIMIT 1");
            $stmtT->execute([':ano' => $m['ano_curso_id'], ':turno' => $m['turno']]);
            $t = $stmtT->fetch();
            
            if ($t) {
                $matriculaModel->assignToTurma($id, $t['id']);
                $_SESSION['flash_success'] = "Matrícula aprovada! O aluno foi ativado e alocado automaticamente à turma compatível.";
            } else {
                $_SESSION['flash_success'] = "Matrícula aprovada e aluno ativado! No entanto, não foi encontrada turma com vagas para o ".$m['ano_curso_id']."º ano (".$m['turno']."). Alocação manual necessária.";
            }
        } else {
            $_SESSION['flash_error'] = "Erro ao aprovar matrícula.";
        }
        header('Location: /green/admin');
        exit;
    }

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
                // Update
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
            header('Location: /green/admin');
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
        header('Location: /green/admin');
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
        header('Location: /green/admin');
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
        header('Location: /green/admin');
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
            header('Location: /green/admin');
            exit;
        }
    }

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
            header('Location: /green/admin#pane-professores');
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
            header('Location: /green/admin#pane-professores');
            exit;
        }
    }

    public function getProfessorData($id) {
        $prof = $this->model('Professor')->findById($id);
        $atribuicoes = $this->model('Professor')->getAssignedClasses($id);
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
            header('Location: /green/admin');
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
        header('Location: /green/admin#pane-turmas');
        exit;
    }

    public function getComunicadoStats($id) {
        $stats = $this->model('Comunicado')->getLeiturasPorComunicado($id);
        header('Content-Type: application/json');
        echo json_encode($stats);
        exit;
    }

    // Novos Métodos para Horário Modelo (Por Ano)
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
            $_SESSION['flash_success'] = "Slot de horário modelo salvo.";
            header('Location: /green/admin#pills-anos');
            exit;
        }
    }

    public function getHorarioModeloAjax($ano_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT hm.*, d.nome as disciplina_nome 
                             FROM horarios_modelo hm 
                             LEFT JOIN disciplinas d ON hm.disciplina_id = d.id 
                             WHERE hm.ano_id = :aid
                             ORDER BY FIELD(hm.dia_semana, 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'), hm.hora_inicio");
        $stmt->execute([':aid' => $ano_id]);
        $slots = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($slots)) {
            echo '<p class="text-center text-muted py-3">Sem modelo definido para este ano.</p>';
        } else {
            echo '<table class="table table-sm small">
                    <thead><tr><th>Dia</th><th>Hora</th><th>Disciplina</th><th>Ação</th></tr></thead>
                    <tbody>';
            foreach ($slots as $s) {
                echo '<tr>
                        <td>'.$s['dia_semana'].'</td>
                        <td>'.substr($s['hora_inicio'],0,5).'-'.substr($s['hora_fim'],0,5).'</td>
                        <td>'.($s['disciplina_nome'] ?? 'A definir').'</td>
                        <td><button class="btn btn-xs text-danger" onclick="deleteModeloSlot('.$s['id'].', '.$s['ano_id'].')"><ion-icon name="trash"></ion-icon></button></td>
                      </tr>';
            }
            echo '</tbody></table>';
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
            header('Location: /green/admin');
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
        header('Location: /green/admin');
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
    public function confirmSummary($id) {
        $this->logActivity('Confirmar Sumário', ['sumario_id' => $id]);
        $db = Database::getInstance();
        $db->prepare("UPDATE sumarios SET confirmado_admin = 1 WHERE id = :id")->execute([':id' => $id]);
        $_SESSION['flash_success'] = "Sumário confirmado como recebido.";
        header('Location: /green/admin#pane-pedagogico');
        exit;
    }

    public function confirmGrades() {
        $turma_id = $_GET['turma_id'] ?? 0;
        $disciplina_id = $_GET['disciplina_id'] ?? 0;
        $this->logActivity('Confirmar Lote de Notas', ['turma_id' => $turma_id, 'disciplina_id' => $disciplina_id]);
        $db = Database::getInstance();
        // Here we confirm all grades for a specific class/subject
        $db->prepare("
            UPDATE notas n
            JOIN avaliacoes a ON n.avaliacao_id = a.id
            SET n.confirmado_admin = 1
            WHERE a.turma_id = :tid AND a.disciplina_id = :did
        ")->execute([':tid' => $turma_id, ':did' => $disciplina_id]);
        
        $_SESSION['flash_success'] = "Lote de notas confirmado com sucesso.";
        header('Location: /green/admin#pane-pedagogico');
        exit;
    }

    public function confirmAttendance() {
        $turma_id = $_GET['turma_id'] ?? 0;
        $disciplina_id = $_GET['disciplina_id'] ?? 0;
        $this->logActivity('Confirmar Lote de Frequências', ['turma_id' => $turma_id, 'disciplina_id' => $disciplina_id]);
        $db = Database::getInstance();
        $db->prepare("UPDATE frequencias SET confirmado_admin = 1 WHERE turma_id = :tid AND disciplina_id = :did")->execute([':tid' => $turma_id, ':did' => $disciplina_id]);
        $_SESSION['flash_success'] = "Lote de frequências confirmado.";
        header('Location: /green/admin#pane-pedagogico');
        exit;
    }


    // ─── Calendário Escolar ─── (Métodos movidos para o final para persistência)


    public function alocarAluno() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /green/admin');
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
            header('Location: /green/admin#pane-alunos');
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
            header('Location: /green/admin#pane-alunos');
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

        header('Location: /green/admin#pane-alunos');
        exit;
    }

    public function getEventosAjax() {
        header('Content-Type: application/json');
        $model = $this->model('Evento');
        echo json_encode($model->getAll());
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
        header('Location: /green/admin#pane-calendario');
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
        header('Location: /green/admin#pane-calendario');
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
        header('Location: /green/admin#pane-pedagogico');
        exit;
    }

    public function validarPagamento($id) {
        $this->logActivity('Validar Pagamento Admin', ['pagamento_id' => $id]);
        $model = $this->model('Pagamento');
        if ($model->aprovarPagamento($id, $_SESSION['user_id'])) {
            $_SESSION['flash_success'] = "Pagamento validado e aprovado com sucesso.";
        } else {
            $_SESSION['flash_error'] = "Erro ao validar pagamento; tente novamente.";
        }
        header('Location: /green/admin#pane-financeiro');
        exit;
    }

    public function rejeitarPagamento($id) {
        $this->verifyCsrfToken();
        $model = $this->model('Pagamento');
        $motivo = $_POST['motivo'] ?? 'Sem motivo especificado';
        if ($model->rejeitarComMotivo($id, $_SESSION['user_id'], $motivo)) {
            $this->logActivity('Rejeitar Pagamento Admin', ['pagamento_id' => $id, 'motivo' => $motivo]);
            $_SESSION['flash_success'] = "Pagamento rejeitado. O estudante será notificado com o motivo.";
        } else {
            $_SESSION['flash_error'] = "Erro ao rejeitar pagamento.";
        }
        header('Location: /green/admin#pane-financeiro');
        exit;
    }

    public function createSecretaria() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'nome' => filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS),
                'email' => filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL),
                'bi' => filter_input(INPUT_POST, 'bi', FILTER_SANITIZE_SPECIAL_CHARS),
                'telefone' => filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS),
                'data_contratacao' => $_POST['data_contratacao'] ?? date('Y-m-d'),
                'senha' => $_POST['senha'] ?? '123456'
            ];

            if (!$data['email']) {
                $_SESSION['flash_error'] = "E-mail inválido.";
                header('Location: /green/admin#pane-secretaria');
                exit;
            }

            $userModel = $this->model('User');
            if ($userModel->findByEmail($data['email'])) {
                $_SESSION['flash_error'] = "O email fornecido já se encontra registado na plataforma.";
                header('Location: /green/admin#pane-secretaria');
                exit;
            }

            $adminModel = $this->model('Administrador');
            if ($adminModel->createSecretaria($data)) {
                $_SESSION['flash_success'] = "Secretário(a) registado(a) com sucesso.";
            } else {
                $_SESSION['flash_error'] = "Erro ao registar o perfil de secretariado.";
            }
            
            header('Location: /green/admin#pane-secretaria');
            exit;
        }
    }

    public function deleteSecretaria($id) {
        $adminModel = $this->model('Administrador');
        $this->logActivity('Remover Secretário/a', ['user_id' => $id]);
        if ($adminModel->deleteSecretaria($id)) {
            $_SESSION['flash_success'] = "Membro da secretaria removido com sucesso.";
        } else {
            $_SESSION['flash_error'] = "Erro ao remover membro da secretaria.";
        }
        header('Location: /green/admin#pane-secretaria');
        exit;
    }
}

