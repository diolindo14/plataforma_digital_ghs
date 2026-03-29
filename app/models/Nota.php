<?php
class Nota {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function saveNotasRow($data) {
        try {
            $this->db->beginTransaction();
            
            $estudante_id = $data['estudante_id'];
            $turma_id = $data['turma_id'];
            $disciplina_id = $data['disciplina_id'];
            $professor_id = $_SESSION['user_id'];
            
            // Map types to IDs from tipos_avaliacao
            $map = [
                'tpc' => 1,
                'ap' => 2,
                'tpi' => 3,
                'ce' => 4,
                'exame' => 5
            ];

            foreach ($map as $key => $tipo_id) {
                if (!isset($data[$key]) || $data[$key] === '') continue;
                
                $valor = $data[$key];

                // 1. Find or create the avaliacao record for this class/type
                $stmt = $this->db->prepare("SELECT id FROM avaliacoes WHERE turma_id = :tid AND disciplina_id = :did AND tipo_avaliacao_id = :tipo_id LIMIT 1");
                $stmt->execute([':tid' => $turma_id, ':did' => $disciplina_id, ':tipo_id' => $tipo_id]);
                $avali = $stmt->fetch();
                
                if ($avali) {
                    $avaliacao_id = $avali['id'];
                } else {
                    $stmtIns = $this->db->prepare("INSERT INTO avaliacoes (turma_id, disciplina_id, tipo_avaliacao_id, descricao) VALUES (:tid, :did, :tipo_id, :desc)");
                    $stmtIns->execute([':tid' => $turma_id, ':did' => $disciplina_id, ':tipo_id' => $tipo_id, ':desc' => 'Lançamento Automático']);
                    $avaliacao_id = $this->db->lastInsertId();
                }

                // 2. Upsert the grade
                $stmtCheck = $this->db->prepare("SELECT id FROM notas WHERE estudante_id = :eid AND avaliacao_id = :aid LIMIT 1");
                $stmtCheck->execute([':eid' => $estudante_id, ':aid' => $avaliacao_id]);
                $notaExist = $stmtCheck->fetch();

                if ($notaExist) {
                    $stmtUpd = $this->db->prepare("UPDATE notas SET nota = :val, lancado_por = :lpor, data_atualizacao = NOW() WHERE id = :nid");
                    $stmtUpd->execute([':val' => $valor, ':lpor' => $professor_id, ':nid' => $notaExist['id']]);
                } else {
                    $stmtAdd = $this->db->prepare("INSERT INTO notas (estudante_id, avaliacao_id, nota, lancado_por) VALUES (:eid, :aid, :val, :lpor)");
                    $stmtAdd->execute([':eid' => $estudante_id, ':aid' => $avaliacao_id, ':val' => $valor, ':lpor' => $professor_id]);
                }
            }
            
            // Mark pending complaints as resolved for this student/turma/discipline
            $stmtRes = $this->db->prepare("
                UPDATE concordancia_notas 
                SET status = 'Respondido', 
                    resposta_professor = :resp,
                    data_resposta = NOW() 
                WHERE estudante_id = :eid AND turma_id = :tid AND disciplina_id = :did AND status = 'Reclamado'
            ");
            $stmtRes->execute([
                ':eid' => $estudante_id, 
                ':tid' => $turma_id, 
                ':did' => $disciplina_id,
                ':resp' => $data['resposta_professor'] ?? null
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getNotasByTurma($turma_id, $disciplina_id) {
        $stmt = $this->db->prepare("
            SELECT n.estudante_id, ta.nome as tipo_nome, n.nota as valor, ta.id as tipo_id,
                   cn.status as feedback_status, cn.comentario as feedback_comentario
            FROM notas n
            JOIN avaliacoes a ON n.avaliacao_id = a.id
            JOIN tipos_avaliacao ta ON a.tipo_avaliacao_id = ta.id
            LEFT JOIN concordancia_notas cn ON n.estudante_id = cn.estudante_id 
                AND a.turma_id = cn.turma_id 
                AND a.disciplina_id = cn.disciplina_id
            WHERE a.turma_id = :tid AND a.disciplina_id = :did
        ");
        $stmt->execute([':tid' => $turma_id, ':did' => $disciplina_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $packed = [];
        foreach ($results as $r) {
            $eid = $r['estudante_id'];
            if (!isset($packed[$eid])) {
                $packed[$eid] = [
                    'notas' => [],
                    'feedback_status' => $r['feedback_status'],
                    'feedback_comentario' => $r['feedback_comentario']
                ];
            }
            $packed[$eid]['notas'][$r['tipo_id']] = $r['valor'];
        }
        return $packed;
    }

    public function getRelatorioGeral() {
        // Query to get all grades with student, turma and discipline info
        $stmt = $this->db->prepare("
            SELECT 
                u.nome_completo as estudante_nome,
                t.codigo as turma_codigo,
                d.nome as disciplina_nome,
                n.estudante_id,
                a.turma_id,
                a.disciplina_id,
                ta.id as tipo_id,
                n.nota,
                n.confirmado_admin
            FROM notas n
            JOIN avaliacoes a ON n.avaliacao_id = a.id
            JOIN tipos_avaliacao ta ON a.tipo_avaliacao_id = ta.id
            JOIN estudantes e ON n.estudante_id = e.id
            JOIN utilizadores u ON e.utilizador_id = u.id
            JOIN turmas t ON a.turma_id = t.id
            JOIN disciplinas d ON a.disciplina_id = d.id
            ORDER BY t.codigo, d.nome, u.nome_completo
        ");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $report = [];
        foreach ($results as $r) {
            $key = $r['turma_id'] . '_' . $r['disciplina_id'] . '_' . $r['estudante_id'];
            if (!isset($report[$key])) {
                $report[$key] = [
                    'estudante' => $r['estudante_nome'],
                    'turma' => $r['turma_codigo'],
                    'disciplina' => $r['disciplina_nome'],
                    'turma_id' => $r['turma_id'],
                    'disciplina_id' => $r['disciplina_id'],
                    'confirmado_admin' => (bool)$r['confirmado_admin'],
                    'notas' => [1=>0, 2=>0, 3=>0, 4=>0, 5=>null]
                ];
            }
            $report[$key]['notas'][$r['tipo_id']] = $r['nota'];
        }

        // Calculate totals and averages
        foreach ($report as &$row) {
            $ac_total = $row['notas'][1] + $row['notas'][2] + $row['notas'][3] + $row['notas'][4];
            $row['total_ac'] = $ac_total;
            $row['media_final'] = ($row['notas'][5] !== null) ? ($ac_total + $row['notas'][5]) / 2 : null;
        }

        return $report;
    }
    public function registrarFeedback($estudante_id, $turma_id, $disciplina_id, $status, $comentario = null) {
        // Primeiro, verificar se já existe um feedback
        $stmtCheck = $this->db->prepare("SELECT status, contador_reclamacoes FROM concordancia_notas WHERE estudante_id = :eid AND turma_id = :tid AND disciplina_id = :did");
        $stmtCheck->execute([':eid' => $estudante_id, ':tid' => $turma_id, ':did' => $disciplina_id]);
        $exist = $stmtCheck->fetch();

        if ($exist) {
            $novoStatus = $status;
            $novoContador = $exist['contador_reclamacoes'];
            $bloqueado = 0;

            // Se o aluno está a reclamar novamente após uma resolução/concordância
            if ($status === 'Reclamado' && ($exist['status'] === 'Resolvido' || $exist['status'] === 'Concordado' || $exist['status'] === 'Reclamado')) {
                // Apenas incrementa se o status anterior era de resolução ou se estamos forçando o fluxo
                // O requisito diz: se houver 2 reclamações para a mesma nota.
                $novoContador++;
                if ($novoContador >= 2) {
                    $bloqueado = 1;
                }
            }

            $stmt = $this->db->prepare("
                UPDATE concordancia_notas 
                SET status = :status, 
                    comentario = :com, 
                    contador_reclamacoes = :cont, 
                    bloqueado_admin = :bloq,
                    data_resposta = NOW()
                WHERE estudante_id = :eid AND turma_id = :tid AND disciplina_id = :did
            ");
            $success = $stmt->execute([
                ':status' => $novoStatus,
                ':com' => $comentario,
                ':cont' => $novoContador,
                ':bloq' => $bloqueado,
                ':eid' => $estudante_id,
                ':tid' => $turma_id,
                ':did' => $disciplina_id
            ]);
            return ['success' => $success, 'contador' => $novoContador, 'bloqueado' => (bool)$bloqueado];
        } else {
            // Primeiro registo
            $stmt = $this->db->prepare("
                INSERT INTO concordancia_notas (estudante_id, turma_id, disciplina_id, status, comentario, contador_reclamacoes, data_resposta)
                VALUES (:eid, :tid, :did, :status, :com, 1, NOW())
            ");
            $success = $stmt->execute([
                ':eid' => $estudante_id,
                ':tid' => $turma_id,
                ':did' => $disciplina_id,
                ':status' => $status,
                ':com' => $comentario
            ]);
            return ['success' => $success, 'contador' => 1, 'bloqueado' => false];
        }
    }

    public function getFeedbacksParaProfessor($professor_id) {
        $stmt = $this->db->prepare("
            SELECT cn.*, u.nome_completo as estudante_nome, t.codigo as turma_codigo, d.nome as disciplina_nome
            FROM concordancia_notas cn
            JOIN estudantes e ON cn.estudante_id = e.id
            JOIN utilizadores u ON e.utilizador_id = u.id
            JOIN turmas t ON cn.turma_id = t.id
            JOIN disciplinas d ON cn.disciplina_id = d.id
            JOIN professor_disciplina pd ON d.id = pd.disciplina_id AND t.id = pd.turma_id
            WHERE pd.professor_id = :pid AND cn.status = 'Reclamado'
            ORDER BY cn.data_resposta DESC
        ");
        $stmt->execute([':pid' => $professor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getConflitosNotas() {
        $stmt = $this->db->prepare("
            SELECT cn.*, u_est.nome_completo as estudante_nome, t.codigo as turma_codigo, d.nome as disciplina_nome,
                   u_prof.nome_completo as professor_nome
            FROM concordancia_notas cn
            JOIN estudantes e ON cn.estudante_id = e.id
            JOIN utilizadores u_est ON e.utilizador_id = u_est.id
            JOIN turmas t ON cn.turma_id = t.id
            JOIN disciplinas d ON cn.disciplina_id = d.id
            JOIN professor_disciplina pd ON d.id = pd.disciplina_id AND t.id = pd.turma_id
            JOIN professores p ON pd.professor_id = p.id
            JOIN utilizadores u_prof ON p.utilizador_id = u_prof.id
            WHERE cn.bloqueado_admin = 1
            ORDER BY cn.data_resposta DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function resolverConflito($estudante_id, $disciplina_id) {
        $stmt = $this->db->prepare("
            UPDATE concordancia_notas 
            SET contador_reclamacoes = 0, 
                bloqueado_admin = 0, 
                status = 'Resolvido' 
            WHERE estudante_id = :eid AND disciplina_id = :did
        ");
        return $stmt->execute([
            ':eid' => $estudante_id,
            ':did' => $disciplina_id
        ]);
    }

    public function getConflitoDetalhes($estudante_id, $disciplina_id) {
        $stmt = $this->db->prepare("
            SELECT u_est.id as estudante_user_id, u_prof.id as professor_user_id, d.nome as disciplina_nome
            FROM concordancia_notas cn
            JOIN estudantes e ON cn.estudante_id = e.id
            JOIN utilizadores u_est ON e.utilizador_id = u_est.id
            JOIN turmas t ON cn.turma_id = t.id
            JOIN disciplinas d ON cn.disciplina_id = d.id
            JOIN professor_disciplina pd ON d.id = pd.disciplina_id AND t.id = pd.turma_id
            JOIN professores p ON pd.professor_id = p.id
            JOIN utilizadores u_prof ON p.utilizador_id = u_prof.id
            WHERE cn.estudante_id = :eid AND cn.disciplina_id = :did
            LIMIT 1
        ");
        $stmt->execute([':eid' => $estudante_id, ':did' => $disciplina_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function responderReclamacao($estudante_id, $turma_id, $disciplina_id, $resposta) {
        $stmt = $this->db->prepare("
            UPDATE concordancia_notas 
            SET status = 'Respondido', 
                resposta_professor = :resp, 
                data_resposta = NOW() 
            WHERE estudante_id = :eid AND turma_id = :tid AND disciplina_id = :did AND status = 'Reclamado'
        ");
        return $stmt->execute([
            ':resp' => $resposta, 
            ':eid' => $estudante_id, 
            ':tid' => $turma_id, 
            ':did' => $disciplina_id
        ]);
    }
}
