<?php
/**
 * Contestacao.php — Modelo de Máquina de Estados para Contestação de Avaliação
 *
 * Implementa o fluxo formal de 8 etapas:
 * PENDENTE → RESPONDIDO|RESOLVIDO → IMPASSE (auto) → EM_MEDIACAO
 *   → AGUARDANDO_COMPARECIMENTO → ENCERRADO
 *
 * Regras de Negócio:
 *   - Apenas 1 contra-argumentação do aluno é permitida
 *   - Impasse detectado automaticamente ao receber contra-argumento após RESPONDIDO
 *   - Todas as notificações são automáticas (via tabela mensagens)
 *   - Nenhuma resposta pode ser editada após envio
 *   - Convocação obrigatória em caso de impasse
 */
class Contestacao {

    private $db;

    // Mapa de todos os estados válidos do sistema
    const ESTADOS = [
        'PENDENTE'                   => 'Pendente',
        'RESPONDIDO'                 => 'Respondido',
        'RESOLVIDO'                  => 'Resolvido',
        'IMPASSE'                    => 'Impasse',
        'EM_MEDIACAO'                => 'Em_Mediacao',
        'AGUARDANDO_COMPARECIMENTO'  => 'Aguardando_Comparecimento',
        'ENCERRADO'                  => 'Encerrado',
        'CONCORDADO'                 => 'Concordado',
    ];

    // Transições permitidas: [estado_atual => [estado_destino...]]
    const TRANSICOES = [
        'Pendente'                   => ['Respondido', 'Resolvido'],
        'Respondido'                 => ['Impasse', 'Encerrado'],
        'Resolvido'                  => ['Encerrado', 'Concordado'],
        'Impasse'                    => ['Em_Mediacao'],
        'Em_Mediacao'                => ['Aguardando_Comparecimento'],
        'Aguardando_Comparecimento'  => ['Encerrado'],
        'Encerrado'                  => [],
        'Concordado'                 => [],
        // Estado legado
        'Reclamado'                  => ['Respondido', 'Resolvido'],
    ];

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // ────────────────────────────────────────────────────────────
    // PASSO 1: Abertura da Contestação (pelo Aluno)
    // ────────────────────────────────────────────────────────────
    /**
     * Abre uma nova contestação ou reabre se o estado anterior for RESPONDIDO.
     * @return array ['success', 'contestacao_id', 'message']
     */
    public function abrir($estudante_id, $turma_id, $disciplina_id, $justificativa) {
        if (empty(trim($justificativa))) {
            return ['success' => false, 'message' => 'A justificativa é obrigatória.'];
        }

        // Verificar se já existe uma contestação aberta ou em mediação
        $stmt = $this->db->prepare("
            SELECT id, status FROM concordancia_notas
            WHERE estudante_id = :eid AND turma_id = :tid AND disciplina_id = :did
        ");
        $stmt->execute([':eid' => $estudante_id, ':tid' => $turma_id, ':did' => $disciplina_id]);
        $existe = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existe) {
            $status = $existe['status'];
            // Bloquear novos pedidos se processo activo
            $estadosBloqueados = ['Pendente', 'Reclamado', 'Impasse', 'Em_Mediacao', 'Aguardando_Comparecimento'];
            if (in_array($status, $estadosBloqueados)) {
                return ['success' => false, 'message' => "Já existe uma contestação activa com o estado: {$status}."];
            }
            // Bloquear se já encerrado definitivamente
            if ($status === 'Encerrado' || $status === 'Concordado') {
                return ['success' => false, 'message' => 'Esta contestação foi encerrada definitivamente. Não é possível reabrir.'];
            }
        }

        $this->db->beginTransaction();
        try {
            if ($existe) {
                // Reabrir (ex: caso RESOLVIDO onde aluno não concorda ainda)
                $stmt = $this->db->prepare("
                    UPDATE concordancia_notas
                    SET status = 'Pendente',
                        comentario = :just,
                        data_abertura = NOW(),
                        data_impasse = NULL,
                        data_escalacao = NULL,
                        contra_argumentacao = NULL,
                        contador_reclamacoes = 0,
                        bloqueado_admin = 0
                    WHERE id = :id
                ");
                $stmt->execute([':just' => $justificativa, ':id' => $existe['id']]);
                $contestacaoId = $existe['id'];
            } else {
                $stmt = $this->db->prepare("
                    INSERT INTO concordancia_notas
                        (estudante_id, turma_id, disciplina_id, status, comentario, data_abertura, contador_reclamacoes, data_resposta)
                    VALUES
                        (:eid, :tid, :did, 'Pendente', :just, NOW(), 1, NOW())
                ");
                $stmt->execute([
                    ':eid' => $estudante_id, ':tid' => $turma_id,
                    ':did' => $disciplina_id, ':just' => $justificativa
                ]);
                $contestacaoId = $this->db->lastInsertId();
            }

            // Notificar professor automaticamente
            $this->_notificarProfessor($estudante_id, $turma_id, $disciplina_id, $contestacaoId);

            $this->db->commit();
            return ['success' => true, 'contestacao_id' => $contestacaoId,
                    'message' => 'Contestação registada. O docente foi notificado.'];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Erro interno: ' . $e->getMessage()];
        }
    }

    // ────────────────────────────────────────────────────────────
    // PASSO 2: Resposta do Docente
    // ────────────────────────────────────────────────────────────
    /**
     * @param bool $alterou_nota Se verdadeiro → RESOLVIDO, senão → RESPONDIDO
     */
    public function responderDocente($estudante_id, $turma_id, $disciplina_id, $professor_user_id, $resposta, $alterou_nota = false) {
        $contestacao = $this->_buscar($estudante_id, $turma_id, $disciplina_id);
        if (!$contestacao) {
            return ['success' => false, 'message' => 'Contestação não encontrada.'];
        }

        $estadosPermitidos = ['Pendente', 'Reclamado'];
        if (!in_array($contestacao['status'], $estadosPermitidos)) {
            return ['success' => false,
                    'message' => "Resposta não permitida no estado actual: {$contestacao['status']}."];
        }

        $novoStatus = $alterou_nota ? 'Resolvido' : 'Respondido';

        $stmt = $this->db->prepare("
            UPDATE concordancia_notas
            SET status = :status,
                resposta_professor = :resp,
                data_resposta = NOW()
            WHERE estudante_id = :eid AND turma_id = :tid AND disciplina_id = :did
        ");
        $ok = $stmt->execute([
            ':status' => $novoStatus, ':resp' => $resposta,
            ':eid' => $estudante_id, ':tid' => $turma_id, ':did' => $disciplina_id
        ]);

        if ($ok) {
            // Notificar aluno
            $this->_notificarEstudante(
                $estudante_id, $contestacao['disciplina_id'] ?? $disciplina_id,
                $novoStatus === 'Resolvido'
                    ? 'O docente actualizou a sua nota. Por favor, verifique.'
                    : 'O docente respondeu à sua contestação. Pode aceitar ou apresentar contra-argumentação.'
            );
        }

        return ['success' => $ok, 'novo_status' => $novoStatus];
    }

    // ────────────────────────────────────────────────────────────
    // PASSO 3 & 4: Reacção do Aluno + Detecção Automática de Impasse
    // ────────────────────────────────────────────────────────────
    /**
     * @param string $acao  'aceitar' | 'contra_argumentar'
     */
    public function reagirAluno($estudante_id, $turma_id, $disciplina_id, $acao, $contra_argumento = null) {
        $contestacao = $this->_buscar($estudante_id, $turma_id, $disciplina_id);
        if (!$contestacao) {
            return ['success' => false, 'message' => 'Contestação não encontrada.'];
        }

        if (!in_array($contestacao['status'], ['Respondido', 'Resolvido'])) {
            return ['success' => false, 'message' => 'Acção não permitida no estado actual.'];
        }

        // Verificar se já enviou contra-argumentação (só 1 é permitida)
        if ($acao === 'contra_argumentar' && !empty($contestacao['contra_argumentacao'])) {
            return ['success' => false,
                    'message' => 'Já enviou uma contra-argumentação. Apenas uma é permitida por processo.'];
        }

        $this->db->beginTransaction();
        try {
            if ($acao === 'aceitar') {
                $stmt = $this->db->prepare("
                    UPDATE concordancia_notas
                    SET status = 'Encerrado', data_resposta = NOW()
                    WHERE estudante_id = :eid AND turma_id = :tid AND disciplina_id = :did
                ");
                $stmt->execute([':eid' => $estudante_id, ':tid' => $turma_id, ':did' => $disciplina_id]);
                $this->db->commit();
                return ['success' => true, 'novo_status' => 'Encerrado',
                        'message' => 'Processo encerrado. Obrigado pela sua colaboração.'];
            }

            // ── CONTRA-ARGUMENTAÇÃO → Detectar Impasse automaticamente ──
            if (empty(trim($contra_argumento))) {
                $this->db->rollBack();
                return ['success' => false, 'message' => 'A contra-argumentação não pode estar vazia.'];
            }

            // Impasse detectado!
            $stmt = $this->db->prepare("
                UPDATE concordancia_notas
                SET status        = 'Impasse',
                    contra_argumentacao = :arg,
                    data_impasse  = NOW(),
                    bloqueado_admin = 1,
                    contador_reclamacoes = contador_reclamacoes + 1
                WHERE estudante_id = :eid AND turma_id = :tid AND disciplina_id = :did
            ");
            $stmt->execute([
                ':arg' => $contra_argumento,
                ':eid' => $estudante_id, ':tid' => $turma_id, ':did' => $disciplina_id
            ]);

            // ── PASSO 5: Escalar automaticamente para EM_MEDIACAO ──
            $this->_escalarParaAdmin($estudante_id, $turma_id, $disciplina_id);

            $this->db->commit();
            return ['success' => true, 'novo_status' => 'Em_Mediacao',
                    'message' => 'Impasse detectado. A Administração Académica foi notificada e irá mediar o processo.'];
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => 'Erro: ' . $e->getMessage()];
        }
    }

    // ────────────────────────────────────────────────────────────
    // PASSO 5 (interno): Escalar para Administração
    // ────────────────────────────────────────────────────────────
    private function _escalarParaAdmin($estudante_id, $turma_id, $disciplina_id) {
        // Actualizar para EM_MEDIACAO
        $stmt = $this->db->prepare("
            UPDATE concordancia_notas
            SET status = 'Em_Mediacao', data_escalacao = NOW()
            WHERE estudante_id = :eid AND turma_id = :tid AND disciplina_id = :did
        ");
        $stmt->execute([':eid' => $estudante_id, ':tid' => $turma_id, ':did' => $disciplina_id]);

        // Obter dados para notificação
        $info = $this->_getInfoCompleta($estudante_id, $turma_id, $disciplina_id);
        if (!$info) return;

        // Notificar todos os administradores
        $admins = $this->db->query("
            SELECT u.id FROM utilizadores u WHERE u.tipo = 'admin' AND u.status = 'ativo' LIMIT 5
        ")->fetchAll(PDO::FETCH_COLUMN);

        $assunto = "🚨 IMPASSE — Contestação de Nota: {$info['disciplina_nome']}";
        $mensagem = "Foi detectado um IMPASSE na contestação da nota de "
                  . "{$info['estudante_nome']} na disciplina {$info['disciplina_nome']} (Turma: {$info['turma_codigo']}).\n\n"
                  . "Contestação do aluno: {$info['comentario']}\n"
                  . "Resposta do docente: {$info['resposta_professor']}\n"
                  . "Contra-argumentação: {$info['contra_argumentacao']}\n\n"
                  . "Acção necessária: Convoque as partes para mediação presencial.";

        foreach ($admins as $adminId) {
            $this->_enviarMensagem(1, $adminId, $assunto, $mensagem);
        }
    }

    // ────────────────────────────────────────────────────────────
    // PASSO 6: Convocar Partes (pela Administração)
    // ────────────────────────────────────────────────────────────
    public function convocarPartes($estudante_id, $disciplina_id, $admin_user_id, $data_reuniao, $hora_reuniao, $local, $motivo) {
        // Buscar por estudante+disciplina (sem turma_id específico, mais flexível)
        $stmt = $this->db->prepare("
            SELECT cn.*, d.nome as disciplina_nome, u_est.id as estudante_user_id,
                   u_est.nome_completo as estudante_nome,
                   t.codigo as turma_codigo, pd.professor_id,
                   u_prof.id as professor_user_id, u_prof.nome_completo as professor_nome
            FROM concordancia_notas cn
            JOIN estudantes e ON cn.estudante_id = e.id
            JOIN utilizadores u_est ON e.utilizador_id = u_est.id
            JOIN disciplinas d ON cn.disciplina_id = d.id
            JOIN turmas t ON cn.turma_id = t.id
            LEFT JOIN professor_disciplina pd ON pd.disciplina_id = cn.disciplina_id AND pd.turma_id = cn.turma_id
            LEFT JOIN professores p ON pd.professor_id = p.id
            LEFT JOIN utilizadores u_prof ON p.utilizador_id = u_prof.id
            WHERE cn.estudante_id = :eid AND cn.disciplina_id = :did
              AND cn.status IN ('Impasse', 'Em_Mediacao')
            ORDER BY cn.data_escalacao DESC LIMIT 1
        ");
        $stmt->execute([':eid' => $estudante_id, ':did' => $disciplina_id]);
        $contestacao = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$contestacao) {
            return ['success' => false, 'message' => 'Contestação em mediação não encontrada.'];
        }

        if (!in_array($contestacao['status'], ['Impasse', 'Em_Mediacao'])) {
            return ['success' => false, 'message' => 'Convocação apenas disponível para processos em mediação.'];
        }

        // Actualizar com dados da convocatória
        $stmt = $this->db->prepare("
            UPDATE concordancia_notas
            SET status                = 'Aguardando_Comparecimento',
                data_reuniao          = :dr,
                hora_reuniao          = :hr,
                local_reuniao         = :local,
                motivo_convocacao     = :motivo,
                mediado_por           = :admin
            WHERE id = :id
        ");
        $ok = $stmt->execute([
            ':dr'    => $data_reuniao,   ':hr'    => $hora_reuniao,
            ':local' => $local,          ':motivo'=> $motivo,
            ':admin' => $admin_user_id,  ':id'    => $contestacao['id']
        ]);

        if (!$ok) return ['success' => false, 'message' => 'Erro ao registar convocatória.'];

        // Notificar Aluno
        $msgAluno = "📋 CONVOCATÓRIA — Contestação de Nota: {$contestacao['disciplina_nome']}\n\n"
                  . "Foi agendada uma reunião de mediação para resolver a sua contestação.\n\n"
                  . "📅 Data: " . date('d/m/Y', strtotime($data_reuniao)) . "\n"
                  . "⏰ Hora: " . substr($hora_reuniao, 0, 5) . "\n"
                  . "📍 Local: {$local}\n"
                  . "📌 Motivo: {$motivo}\n\n"
                  . "A sua presença é obrigatória. Em caso de impossibilidade, contacte a administração.";
        $this->_enviarMensagem($admin_user_id, $contestacao['estudante_user_id'],
            "Convocatória: Mediação de Nota — {$contestacao['disciplina_nome']}", $msgAluno);

        // Notificar Professor (se existir)
        if (!empty($contestacao['professor_user_id'])) {
            $msgProf = "📋 CONVOCATÓRIA — Mediação de Nota: {$contestacao['disciplina_nome']}\n\n"
                     . "Foi agendada uma reunião de mediação referente à contestação do(a) aluno(a) {$contestacao['estudante_nome']}.\n\n"
                     . "📅 Data: " . date('d/m/Y', strtotime($data_reuniao)) . "\n"
                     . "⏰ Hora: " . substr($hora_reuniao, 0, 5) . "\n"
                     . "📍 Local: {$local}\n"
                     . "📌 Motivo: {$motivo}\n\n"
                     . "A sua comparência é obrigatória.";
            $this->_enviarMensagem($admin_user_id, $contestacao['professor_user_id'],
                "Convocatória: Mediação de Nota — {$contestacao['disciplina_nome']}", $msgProf);
        }

        return ['success' => true, 'message' => 'Partes convocadas com sucesso. Notificações enviadas.'];
    }

    // ────────────────────────────────────────────────────────────
    // PASSO 7 & 8: Registar Decisão Final e Encerrar
    // ────────────────────────────────────────────────────────────
    public function registrarDecisao($estudante_id, $disciplina_id, $admin_user_id, $decisao, $presenca_aluno, $presenca_professor) {
        $stmt = $this->db->prepare("
            SELECT id, status, estudante_user_id_view.*
            FROM concordancia_notas cn
            JOIN estudantes e ON cn.estudante_id = e.id
            WHERE cn.estudante_id = :eid AND cn.disciplina_id = :did
              AND cn.status = 'Aguardando_Comparecimento'
            ORDER BY cn.data_escalacao DESC LIMIT 1
        ");
        // Query simplificada
        $stmt = $this->db->prepare("
            SELECT cn.id, cn.status FROM concordancia_notas cn
            WHERE cn.estudante_id = :eid AND cn.disciplina_id = :did
              AND cn.status = 'Aguardando_Comparecimento'
            LIMIT 1
        ");
        $stmt->execute([':eid' => $estudante_id, ':did' => $disciplina_id]);
        $contestacao = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$contestacao) {
            return ['success' => false, 'message' => 'Nenhuma contestação aguardando comparecimento.'];
        }

        $stmt = $this->db->prepare("
            UPDATE concordancia_notas
            SET status               = 'Encerrado',
                decisao_final        = :decisao,
                presenca_aluno       = :pa,
                presenca_professor   = :pp,
                mediado_por          = :admin,
                data_decisao         = NOW(),
                bloqueado_admin      = 0
            WHERE id = :id
        ");
        $ok = $stmt->execute([
            ':decisao' => $decisao,
            ':pa'      => $presenca_aluno ? 1 : 0,
            ':pp'      => $presenca_professor ? 1 : 0,
            ':admin'   => $admin_user_id,
            ':id'      => $contestacao['id']
        ]);

        if ($ok) {
            // Notificar ambas as partes sobre o encerramento
            $info = $this->_getInfoByContestacaoId($contestacao['id']);
            if ($info) {
                $msgEncerramento = "✅ Processo de Contestação Encerrado\n\n"
                                 . "Disciplina: {$info['disciplina_nome']}\n"
                                 . "Decisão da Administração:\n\n{$decisao}";
                $this->_enviarMensagem($admin_user_id, $info['estudante_user_id'],
                    'Contestação Encerrada: ' . $info['disciplina_nome'], $msgEncerramento);
                    
                if (!empty($info['professor_user_id'])) {
                    $this->_enviarMensagem($admin_user_id, $info['professor_user_id'],
                        'Contestação Encerrada: ' . $info['disciplina_nome'], $msgEncerramento);
                }
            }
        }

        return ['success' => $ok, 'message' => $ok ? 'Decisão registada. Processo encerrado.' : 'Erro ao registar decisão.'];
    }

    // ────────────────────────────────────────────────────────────
    // QUERIES DE CONSULTA
    // ────────────────────────────────────────────────────────────

    /** Todas as contestações de um aluno */
    public function getDoAluno($estudante_id) {
        $stmt = $this->db->prepare("
            SELECT cn.*, d.nome as disciplina_nome, t.codigo as turma_codigo,
                   u_prof.nome_completo as professor_nome
            FROM concordancia_notas cn
            JOIN disciplinas d ON cn.disciplina_id = d.id
            JOIN turmas t ON cn.turma_id = t.id
            LEFT JOIN professor_disciplina pd ON pd.disciplina_id = cn.disciplina_id AND pd.turma_id = cn.turma_id
            LEFT JOIN professores p ON pd.professor_id = p.id
            LEFT JOIN utilizadores u_prof ON p.utilizador_id = u_prof.id
            WHERE cn.estudante_id = :eid
            ORDER BY cn.data_resposta DESC
        ");
        $stmt->execute([':eid' => $estudante_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Contestações pendentes de resposta para um professor */
    public function getPendentesDocente($professor_id) {
        $stmt = $this->db->prepare("
            SELECT cn.*, u.nome_completo as estudante_nome,
                   t.codigo as turma_codigo, d.nome as disciplina_nome,
                   e.id as estudante_id_real
            FROM concordancia_notas cn
            JOIN estudantes e ON cn.estudante_id = e.id
            JOIN utilizadores u ON e.utilizador_id = u.id
            JOIN turmas t ON cn.turma_id = t.id
            JOIN disciplinas d ON cn.disciplina_id = d.id
            JOIN professor_disciplina pd ON pd.disciplina_id = cn.disciplina_id AND pd.turma_id = cn.turma_id
            WHERE pd.professor_id = :pid
              AND cn.status IN ('Pendente', 'Reclamado')
            ORDER BY cn.data_abertura ASC
        ");
        $stmt->execute([':pid' => $professor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Histórico completo para um professor (todos os estados) */
    public function getHistoricoDocente($professor_id) {
        $stmt = $this->db->prepare("
            SELECT cn.*, u.nome_completo as estudante_nome,
                   t.codigo as turma_codigo, d.nome as disciplina_nome
            FROM concordancia_notas cn
            JOIN estudantes e ON cn.estudante_id = e.id
            JOIN utilizadores u ON e.utilizador_id = u.id
            JOIN turmas t ON cn.turma_id = t.id
            JOIN disciplinas d ON cn.disciplina_id = d.id
            JOIN professor_disciplina pd ON pd.disciplina_id = cn.disciplina_id AND pd.turma_id = cn.turma_id
            WHERE pd.professor_id = :pid
            ORDER BY cn.data_resposta DESC
        ");
        $stmt->execute([':pid' => $professor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Todos os impasses / processos em mediação (para Admin) */
    public function getEmMediacao() {
        $stmt = $this->db->query("
            SELECT cn.*, u_est.nome_completo as estudante_nome,
                   t.codigo as turma_codigo, d.nome as disciplina_nome,
                   u_prof.nome_completo as professor_nome,
                   u_med.nome_completo as mediador_nome
            FROM concordancia_notas cn
            JOIN estudantes e ON cn.estudante_id = e.id
            JOIN utilizadores u_est ON e.utilizador_id = u_est.id
            JOIN turmas t ON cn.turma_id = t.id
            JOIN disciplinas d ON cn.disciplina_id = d.id
            LEFT JOIN professor_disciplina pd ON pd.disciplina_id = cn.disciplina_id AND pd.turma_id = cn.turma_id
            LEFT JOIN professores p ON pd.professor_id = p.id
            LEFT JOIN utilizadores u_prof ON p.utilizador_id = u_prof.id
            LEFT JOIN utilizadores u_med ON cn.mediado_por = u_med.id
            WHERE cn.status IN ('Impasse', 'Em_Mediacao', 'Aguardando_Comparecimento')
            ORDER BY cn.data_escalacao DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Painel Admin: todas as contestações com histórico completo */
    public function getAllParaAdmin() {
        $stmt = $this->db->query("
            SELECT cn.*, u_est.nome_completo as estudante_nome,
                   t.codigo as turma_codigo, d.nome as disciplina_nome,
                   u_prof.nome_completo as professor_nome,
                   u_med.nome_completo as mediador_nome
            FROM concordancia_notas cn
            JOIN estudantes e ON cn.estudante_id = e.id
            JOIN utilizadores u_est ON e.utilizador_id = u_est.id
            JOIN turmas t ON cn.turma_id = t.id
            JOIN disciplinas d ON cn.disciplina_id = d.id
            LEFT JOIN professor_disciplina pd ON pd.disciplina_id = cn.disciplina_id AND pd.turma_id = cn.turma_id
            LEFT JOIN professores p ON pd.professor_id = p.id
            LEFT JOIN utilizadores u_prof ON p.utilizador_id = u_prof.id
            LEFT JOIN utilizadores u_med ON cn.mediado_por = u_med.id
            ORDER BY FIELD(cn.status,'Em_Mediacao','Aguardando_Comparecimento','Impasse','Pendente','Reclamado','Respondido','Resolvido','Encerrado','Concordado'),
                     cn.data_resposta DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ────────────────────────────────────────────────────────────
    // MÉTODOS PRIVADOS AUXILIARES
    // ────────────────────────────────────────────────────────────
    private function _buscar($estudante_id, $turma_id, $disciplina_id) {
        $stmt = $this->db->prepare("
            SELECT * FROM concordancia_notas
            WHERE estudante_id = :eid AND turma_id = :tid AND disciplina_id = :did
            ORDER BY data_resposta DESC LIMIT 1
        ");
        $stmt->execute([':eid' => $estudante_id, ':tid' => $turma_id, ':did' => $disciplina_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function _getInfoCompleta($estudante_id, $turma_id, $disciplina_id) {
        $stmt = $this->db->prepare("
            SELECT cn.comentario, cn.resposta_professor, cn.contra_argumentacao,
                   u_est.nome_completo as estudante_nome, d.nome as disciplina_nome,
                   t.codigo as turma_codigo
            FROM concordancia_notas cn
            JOIN estudantes e ON cn.estudante_id = e.id
            JOIN utilizadores u_est ON e.utilizador_id = u_est.id
            JOIN disciplinas d ON cn.disciplina_id = d.id
            JOIN turmas t ON cn.turma_id = t.id
            WHERE cn.estudante_id = :eid AND cn.turma_id = :tid AND cn.disciplina_id = :did
            LIMIT 1
        ");
        $stmt->execute([':eid' => $estudante_id, ':tid' => $turma_id, ':did' => $disciplina_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function _getInfoByContestacaoId($id) {
        $stmt = $this->db->prepare("
            SELECT cn.*, d.nome as disciplina_nome, t.codigo as turma_codigo,
                   u_est.id as estudante_user_id, u_prof.id as professor_user_id
            FROM concordancia_notas cn
            JOIN estudantes e ON cn.estudante_id = e.id
            JOIN utilizadores u_est ON e.utilizador_id = u_est.id
            JOIN disciplinas d ON cn.disciplina_id = d.id
            JOIN turmas t ON cn.turma_id = t.id
            LEFT JOIN professor_disciplina pd ON pd.disciplina_id = cn.disciplina_id AND pd.turma_id = cn.turma_id
            LEFT JOIN professores p ON pd.professor_id = p.id
            LEFT JOIN utilizadores u_prof ON p.utilizador_id = u_prof.id
            WHERE cn.id = :id LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function _notificarProfessor($estudante_id, $turma_id, $disciplina_id, $contestacaoId) {
        $stmt = $this->db->prepare("
            SELECT u_prof.id as prof_user_id, u_est.nome_completo as est_nome, d.nome as disc_nome
            FROM professor_disciplina pd
            JOIN professores p ON pd.professor_id = p.id
            JOIN utilizadores u_prof ON p.utilizador_id = u_prof.id
            JOIN estudantes e ON e.id = :eid
            JOIN utilizadores u_est ON e.utilizador_id = u_est.id
            JOIN disciplinas d ON d.id = pd.disciplina_id
            WHERE pd.turma_id = :tid AND pd.disciplina_id = :did
            LIMIT 1
        ");
        $stmt->execute([':eid' => $estudante_id, ':tid' => $turma_id, ':did' => $disciplina_id]);
        $r = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$r) return;

        $remetenteId = $this->_getAdminId();
        $this->_enviarMensagem(
            $remetenteId,
            $r['prof_user_id'],
            "Nova Contestação de Nota — {$r['disc_nome']}",
            "O(A) aluno(a) {$r['est_nome']} contestou a sua avaliação em {$r['disc_nome']}.\n"
          . "Por favor, aceda ao seu portal para responder dentro do prazo."
        );
    }

    private function _notificarEstudante($estudante_id, $disciplina_id, $mensagem_texto) {
        $stmt = $this->db->prepare("
            SELECT u.id FROM estudantes e JOIN utilizadores u ON e.utilizador_id = u.id WHERE e.id = :eid
        ");
        $stmt->execute([':eid' => $estudante_id]);
        $userId = $stmt->fetchColumn();
        if (!$userId) return;

        $stmtD = $this->db->prepare("SELECT nome FROM disciplinas WHERE id = :did");
        $stmtD->execute([':did' => $disciplina_id]);
        $discNome = $stmtD->fetchColumn() ?: 'Disciplina';

        $remetenteId = $this->_getAdminId();
        $this->_enviarMensagem($remetenteId, $userId,
            "Actualização da Contestação — {$discNome}", $mensagem_texto);
    }

    private function _enviarMensagem($remetente_id, $destinatario_id, $assunto, $mensagem) {
        $stmt = $this->db->prepare("
            INSERT INTO mensagens (remetente_id, destinatario_id, assunto, mensagem, lida)
            VALUES (:rid, :did, :assunto, :msg, 0)
        ");
        $stmt->execute([
            ':rid' => $remetente_id, ':did' => $destinatario_id,
            ':assunto' => $assunto, ':msg' => $mensagem
        ]);
    }

    private function _getAdminId() {
        $stmt = $this->db->query("SELECT id FROM utilizadores WHERE tipo='admin' AND status='ativo' LIMIT 1");
        return $stmt->fetchColumn() ?: 1;
    }
}
