<?php
require_once __DIR__ . '/../helpers/BackupManager.php';

class Nota {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function saveNotasRow($data) {
        $estudante_id = $data['estudante_id'];
        $turma_id = $data['turma_id'];
        $disciplina_id = $data['disciplina_id'];
        $professor_id = $_SESSION['user_id'];

        // 0. VERIFICAÇÃO DE SEGURANÇA: Bloquear se o aluno já concordou
        $stmtLock = $this->db->prepare("SELECT status FROM concordancia_notas WHERE estudante_id = :eid AND turma_id = :tid AND disciplina_id = :did");
        $stmtLock->execute([':eid' => $estudante_id, ':tid' => $turma_id, ':did' => $disciplina_id]);
        $lockStatus = $stmtLock->fetchColumn();

        if ($lockStatus === 'Concordado' || $lockStatus === 'Resolvido') {
            return false; // Nota trancada - Retornar antes de iniciar transação
        }

        try {
            $this->db->beginTransaction();

            // Map types to IDs and support up to 3 instances each (1, 2, 3)
            $components = [
                'tpc' => 1,
                'ap'  => 2,
                'tpi' => 3,
                'ce'  => 4
            ];

            foreach ($components as $key => $tipo_id) {
                for ($i = 1; $i <= 3; $i++) {
                    $input_key = $key . '_' . $i;
                    if (!isset($data[$input_key]) || $data[$input_key] === '') continue;
                    
                    $valor = $data[$input_key];
                    $desc  = "$i" . "º " . strtoupper($key);

                    // 1. Find or create the specific avaliacao record (matched by description)
                    $stmt = $this->db->prepare("SELECT id FROM avaliacoes WHERE turma_id = :tid AND disciplina_id = :did AND tipo_avaliacao_id = :tipo_id AND descricao = :desc LIMIT 1");
                    $stmt->execute([':tid' => $turma_id, ':did' => $disciplina_id, ':tipo_id' => $tipo_id, ':desc' => $desc]);
                    $avali = $stmt->fetch();
                    
                    if ($avali) {
                        $avaliacao_id = $avali['id'];
                    } else {
                        $stmtIns = $this->db->prepare("INSERT INTO avaliacoes (turma_id, disciplina_id, tipo_avaliacao_id, descricao) VALUES (:tid, :did, :tipo_id, :desc)");
                        $stmtIns->execute([':tid' => $turma_id, ':did' => $disciplina_id, ':tipo_id' => $tipo_id, ':desc' => $desc]);
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
            }

            // --- 🟢 EXAME (Sempre Único) ---
            if (isset($data['exame']) && $data['exame'] !== '') {
                $valor = $data['exame'];
                $stmt = $this->db->prepare("SELECT id FROM avaliacoes WHERE turma_id = :tid AND disciplina_id = :did AND tipo_avaliacao_id = 5 LIMIT 1");
                $stmt->execute([':tid' => $turma_id, ':did' => $disciplina_id]);
                $avali = $stmt->fetch();
                $avaliacao_id = $avali ? $avali['id'] : null;
                if (!$avaliacao_id) {
                    $stmtIns = $this->db->prepare("INSERT INTO avaliacoes (turma_id, disciplina_id, tipo_avaliacao_id, descricao) VALUES (:tid, :did, 5, 'Exame Final')");
                    $stmtIns->execute([':tid' => $turma_id, ':did' => $disciplina_id]);
                    $avaliacao_id = $this->db->lastInsertId();
                }

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
            
            // Mark pending complaints/orders as fulfilled for this student/turma/discipline
            $stmtRes = $this->db->prepare("
                UPDATE concordancia_notas 
                SET status = CASE WHEN status = 'Aguardando_Correcao' THEN 'Encerrado' ELSE 'Respondido' END, 
                    resposta_professor = :resp,
                    data_resposta = NOW() 
                WHERE estudante_id = :eid AND turma_id = :tid AND disciplina_id = :did 
                  AND status IN ('Reclamado', 'Aguardando_Correcao', 'Pendente')
            ");
            $stmtRes->execute([
                ':eid' => $estudante_id, 
                ':tid' => $turma_id, 
                ':did' => $disciplina_id,
                ':resp' => $data['resposta_professor'] ?? 'Nota corrigida conforme ordem administrativa.'
            ]);

            $this->db->commit();
            
            // Backup em tempo real após mudança crítica (Pilar 3: Integridade)
            BackupManager::createCheckpoint('Check Grads');

            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getNotasByTurma($turma_id, $disciplina_id) {
        $stmt = $this->db->prepare("
            SELECT n.estudante_id, ta.nome as tipo_nome, n.nota as valor, ta.id as tipo_id, a.descricao,
                   cn.status as feedback_status, cn.comentario as feedback_comentario, cn.resposta_professor
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
                    'feedback_comentario' => $r['feedback_comentario'],
                    'resposta_professor' => $r['resposta_professor']
                ];
            }
            
            $tid = $r['tipo_id'];
            $desc = $r['descricao'];
            
            // Armazenar por tipo e descrição para o frontend identificar (ex: 1º TPC)
            if (!isset($packed[$eid]['notas'][$tid])) {
                $packed[$eid]['notas'][$tid] = [];
            }
            $packed[$eid]['notas'][$tid][$desc] = $r['valor'];
        }
        return $packed;
    }

    public function getRelatorioGeral($professor_id = null) {
        // Query to get all grades with student, turma and discipline info
        $sql = "
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
            JOIN disciplinas d ON a.disciplina_id = d.id";

        if ($professor_id) {
            $sql .= " JOIN professor_disciplina pd ON d.id = pd.disciplina_id AND t.id = pd.turma_id 
                      WHERE pd.professor_id = :pid";
        }

        $sql .= " ORDER BY t.codigo, d.nome, u.nome_completo";

        $stmt = $this->db->prepare($sql);
        if ($professor_id) {
            $stmt->execute([':pid' => $professor_id]);
        } else {
            $stmt->execute();
        }
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $report = [];
        foreach ($results as $r) {
            $key = $r['turma_id'] . '_' . $r['disciplina_id'] . '_' . $r['estudante_id'];
            if (!isset($report[$key])) {
                $report[$key] = [
                    'estudante' => $r['estudante_nome'],
                    'estudante_id' => $r['estudante_id'],
                    'turma' => $r['turma_codigo'],
                    'disciplina' => $r['disciplina_nome'],
                    'turma_id' => $r['turma_id'],
                    'disciplina_id' => $r['disciplina_id'],
                    'confirmado_admin' => (bool)$r['confirmado_admin'],
                    'notas' => [1=>[], 2=>[], 3=>[], 4=>[], 5=>[]]
                ];
            }
            $report[$key]['notas'][$r['tipo_id']][] = $r['nota'];
        }

        // Calculate totals and averages
        foreach ($report as &$row) {
            $ac_total = 0;
            for ($i = 1; $i <= 4; $i++) {
                if (!empty($row['notas'][$i])) {
                    $vals = array_filter($row['notas'][$i], fn($v) => $v !== null && $v !== '');
                    if (!empty($vals)) $ac_total += array_sum($vals) / count($vals);
                }
            }

            $exame_vals = array_filter($row['notas'][5], fn($v) => $v !== null && $v !== '');
            $exame = !empty($exame_vals) ? array_sum($exame_vals) / count($exame_vals) : null;

            $row['total_ac'] = $ac_total;
            $row['exame'] = $exame;
            $row['media_final'] = ($exame !== null) ? ($ac_total + $exame) / 2 : null;
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

            // Se o aluno está a reclamar novamente após uma resposta ou se apenas está a atualizar/reforçar a queixa
            if ($status === 'Reclamado') {
                // Só incrementa o contador (e arrisca bloqueio) se estiver a rejeitar uma resposta do professor
                // Se já estava 'Reclamado', apenas atualizamos o comentário/data sem contar como nova "instância" de conflito
                if ($exist['status'] === 'Respondido' || $exist['status'] === 'Resolvido' || $exist['status'] === 'Concordado') {
                    $novoContador++;
                }
                
                if ($novoContador >= 2) {
                    $bloqueado = 1;
                    $novoStatus = 'Impasse';
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

    /**
     * Registra a concordância definitiva do aluno com a nota.
     */
    public function registarConcordancia($notaId, $estudanteId) {
        // Encontrar os dados da nota para atualizar a tabela de concordancia
        $stmtNota = $this->db->prepare("
            SELECT a.turma_id, a.disciplina_id 
            FROM notas n
            JOIN avaliacoes a ON n.avaliacao_id = a.id
            WHERE n.id = :id AND n.estudante_id = :eid
        ");
        $stmtNota->execute([':id' => $notaId, ':eid' => $estudanteId]);
        $nota = $stmtNota->fetch();

        if (!$nota) return false;

        $stmt = $this->db->prepare("
            INSERT INTO concordancia_notas (estudante_id, turma_id, disciplina_id, status, data_resposta)
            VALUES (:eid, :tid, :did, 'Concordado', NOW())
            ON DUPLICATE KEY UPDATE status = 'Concordado', data_resposta = NOW()
        ");
        return $stmt->execute([
            ':eid' => $estudanteId,
            ':tid' => $nota['turma_id'],
            ':did' => $nota['disciplina_id']
        ]);
    }

    public function getLogsAtivos() {
        $stmt = $this->db->prepare("
            SELECT l.*, u.nome_completo as aluno, t.codigo as turma, d.nome as disciplina, ta.nome as tipo, p.nome_completo as autor
            FROM logs_notas l
            JOIN estudantes e ON l.estudante_id = e.id
            JOIN utilizadores u ON e.utilizador_id = u.id
            JOIN turmas t ON l.turma_id = t.id
            JOIN disciplinas d ON l.disciplina_id = d.id
            JOIN tipos_avaliacao ta ON l.tipo_avaliacao_id = ta.id
            JOIN utilizadores p ON l.alterado_por = p.id
            ORDER BY l.data_alteracao DESC LIMIT 100
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNotaEstudante($estudante_id, $disciplina_id) {
        // Agora busca todas as notas, pois pode haver múltiplas para o mesmo tipo
        $stmt = $this->db->prepare("
            SELECT n.nota, a.tipo_avaliacao_id as tipo_id
            FROM notas n
            JOIN avaliacoes a ON n.avaliacao_id = a.id
            WHERE n.estudante_id = :eid AND a.disciplina_id = :did
        ");
        $stmt->execute([':eid' => $estudante_id, ':did' => $disciplina_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $grupos = [1=>[], 2=>[], 3=>[], 4=>[], 5=>null];
        foreach ($results as $r) {
            $tid = $r['tipo_id'];
            if ($tid == 5) {
                $grupos[5] = $r['nota']; // Exame é único
            } else {
                $grupos[$tid][] = (float)$r['nota'];
            }
        }

        // Calcula a média para cada componente de AC
        $ac_total = 0;
        foreach ([1, 2, 3, 4] as $tid) {
            if (!empty($grupos[$tid])) {
                $media_componente = array_sum($grupos[$tid]) / count($grupos[$tid]);
                $ac_total += $media_componente;
            }
        }

        $exame = $grupos[5];
        $media_final = ($exame !== null) ? ($ac_total + $exame) / 2 : null;

        return [
            'total_ac' => $ac_total,
            'media_final' => $media_final
        ];
    }
}
