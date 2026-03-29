<?php
class Frequencia {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function saveSummary($data) {
        try {
            $this->db->beginTransaction();

            $stmtP = $this->db->prepare("SELECT id FROM professores WHERE utilizador_id = ?");
            $stmtP->execute([$_SESSION['user_id']]);
            $profId = $stmtP->fetchColumn();

            // 1. Inserir Sumário
            $stmt = $this->db->prepare("
                INSERT INTO sumarios (professor_id, turma_id, disciplina_id, tempo, data, conteudo, assinatura_digital)
                VALUES (:pid, :tid, :did, :tempo, :data, :cont, :ass)
            ");
            $stmt->execute([
                ':pid' => $profId,
                ':tid' => $data['turma_id'],
                ':did' => $data['disciplina_id'],
                ':tempo' => $data['tempo'] ?? '1º Tempo',
                ':data' => $data['data'],
                ':cont' => $data['conteudo'],
                ':ass' => $data['assinatura'] ?? null
            ]);
            $sumario_id = $this->db->lastInsertId();

            // 2. Inserir Frequências
            if (!empty($data['presencas'])) {
                foreach ($data['presencas'] as $est_id => $status) {
                    $stmtF = $this->db->prepare("
                        INSERT INTO frequencias (sumario_id, estudante_id, turma_id, disciplina_id, data, status)
                        VALUES (:sid, :eid, :tid, :did, :data, :status)
                    ");
                    $stmtF->execute([
                        ':sid' => $sumario_id,
                        ':eid' => $est_id,
                        ':tid' => $data['turma_id'],
                        ':did' => $data['disciplina_id'],
                        ':data' => $data['data'],
                        ':status' => $status
                    ]);
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            return false;
        }
    }

    public function getAllSummaries() {
        $stmt = $this->db->prepare("
            SELECT s.*, u.nome_completo as professor_nome, t.codigo as turma_codigo, d.nome as disciplina_nome,
                   (SELECT COUNT(*) FROM frequencias WHERE sumario_id = s.id AND status = 'P') as P,
                   (SELECT COUNT(*) FROM frequencias WHERE sumario_id = s.id AND status = 'F') as F,
                   (SELECT COUNT(*) FROM frequencias WHERE sumario_id = s.id AND status = 'J') as J,
                   ap.status as status_professor
            FROM sumarios s
            JOIN professores p ON s.professor_id = p.id
            JOIN utilizadores u ON p.utilizador_id = u.id
            JOIN turmas t ON s.turma_id = t.id
            JOIN disciplinas d ON s.disciplina_id = d.id
            LEFT JOIN assiduidade_professores ap ON ap.professor_id = s.professor_id 
                 AND ap.turma_id = s.turma_id AND ap.disciplina_id = s.disciplina_id 
                 AND ap.data = s.data AND ap.tempo = s.tempo
            ORDER BY s.data DESC, s.tempo DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSummariesByProfessor($profId) {
        $stmt = $this->db->prepare("
            SELECT s.*, u.nome_completo as professor_nome, t.codigo as turma_codigo, d.nome as disciplina_nome
            FROM sumarios s
            JOIN professores p ON s.professor_id = p.id
            JOIN utilizadores u ON p.utilizador_id = u.id
            JOIN turmas t ON s.turma_id = t.id
            JOIN disciplinas d ON s.disciplina_id = d.id
            WHERE s.professor_id = :pid
            ORDER BY s.data DESC
        ");
        $stmt->execute([':pid' => $profId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFrequenciaRelatorio() {
        $stmt = $this->db->prepare("
            SELECT 
                u.nome_completo as estudante_nome,
                t.codigo as turma_codigo,
                d.nome as disciplina_nome,
                f.status,
                COUNT(*) as total,
                f.turma_id,
                f.disciplina_id,
                m.grupo
            FROM frequencias f
            JOIN estudantes e ON f.estudante_id = e.id
            JOIN utilizadores u ON e.utilizador_id = u.id
            JOIN turmas t ON f.turma_id = t.id
            JOIN disciplinas d ON f.disciplina_id = d.id
            LEFT JOIN matriculas m ON e.id = m.estudante_id AND m.turma_id = f.turma_id
            GROUP BY f.estudante_id, f.turma_id, f.disciplina_id, f.status
        ");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $report = [];
        foreach ($results as $r) {
            $key = $r['estudante_nome'] . '_' . $r['turma_codigo'];
            if (!isset($report[$key])) {
                $report[$key] = [
                    'estudante' => $r['estudante_nome'],
                    'turma' => $r['turma_codigo'],
                    'grupo' => $r['grupo'] ?? 'G1',
                    'turma_id' => $r['turma_id'],
                    'disciplina_id' => $r['disciplina_id'],
                    'P' => 0, 'F' => 0, 'J' => 0
                ];
            }
            $report[$key][$r['status']] = $r['total'];
        }
        return $report;
    }
    public function getMissingSummaries() {
        $diasSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
        $hojeStr = $diasSemana[date('w')];
        $agora = date('H:i:s', strtotime('-1 hour'));

        $stmt = $this->db->prepare("
            SELECT h.*, d.nome as disciplina_nome, u.nome_completo as professor_nome, t.codigo as turma_codigo,
                   ap.status as status_professor
            FROM horarios h
            JOIN disciplinas d ON h.disciplina_id = d.id
            JOIN professores p ON h.professor_id = p.id
            JOIN utilizadores u ON p.utilizador_id = u.id
            JOIN turmas t ON h.turma_id = t.id
            LEFT JOIN sumarios s ON h.professor_id = s.professor_id 
                AND h.turma_id = s.turma_id 
                AND h.disciplina_id = s.disciplina_id 
                AND s.data = CURRENT_DATE
                AND (s.tempo = h.hora_inicio OR s.tempo = CONCAT(h.hora_inicio, ' - ', h.hora_fim))
            LEFT JOIN assiduidade_professores ap ON ap.professor_id = h.professor_id 
                AND ap.turma_id = h.turma_id AND ap.disciplina_id = h.disciplina_id 
                AND ap.data = CURRENT_DATE AND (ap.tempo = h.hora_inicio OR ap.tempo = CONCAT(h.hora_inicio, ' - ', h.hora_fim))
            WHERE h.dia_semana = :dia AND h.hora_fim < :agora AND s.id IS NULL
        ");
        $stmt->execute([':dia' => $hojeStr, ':agora' => $agora]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDetailedAttendanceLog() {
        $stmt = $this->db->prepare("
            SELECT f.data, u_est.nome_completo as estudante_nome, d.nome as disciplina_nome, 
                   s.tempo, u_prof.nome_completo as professor_nome, f.status, f.confirmado_admin,
                   s.turma_id, s.disciplina_id, m.grupo
            FROM frequencias f
            JOIN sumarios s ON f.sumario_id = s.id
            JOIN estudantes e ON f.estudante_id = e.id
            JOIN utilizadores u_est ON e.utilizador_id = u_est.id
            JOIN disciplinas d ON f.disciplina_id = d.id
            JOIN professores p ON s.professor_id = p.id
            JOIN utilizadores u_prof ON p.utilizador_id = u_prof.id
            LEFT JOIN matriculas m ON e.id = m.estudante_id AND m.turma_id = s.turma_id
            ORDER BY f.data DESC, s.tempo ASC, u_est.nome_completo ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markTeacherAttendance($data) {
        $stmt = $this->db->prepare("
            INSERT INTO assiduidade_professores (professor_id, turma_id, disciplina_id, data, tempo, status, marcado_por, justificacao)
            VALUES (:pid, :tid, :did, :data, :tempo, :status, :mid, :just)
            ON DUPLICATE KEY UPDATE status = :status_upd, marcado_por = :mid_upd, justificacao = :just_upd
        ");
        return $stmt->execute([
            ':pid' => $data['professor_id'],
            ':tid' => $data['turma_id'],
            ':did' => $data['disciplina_id'],
            ':data' => $data['data'] ?? date('Y-m-d'),
            ':tempo' => $data['tempo'] ?? '1º Tempo',
            ':status' => $data['status'] ?? 'Falta',
            ':mid' => $_SESSION['user_id'],
            ':just' => $data['justificacao'] ?? null,
            ':status_upd' => $data['status'] ?? 'Falta',
            ':mid_upd' => $_SESSION['user_id'],
            ':just_upd' => $data['justificacao'] ?? null
        ]);
    }

    public function getTeacherAttendanceReport() {
        $stmt = $this->db->prepare("
            SELECT ap.*, u_prof.nome_completo as professor_nome, t.codigo as turma_codigo, d.nome as disciplina_nome, u_admin.nome_completo as marcado_por_nome
            FROM assiduidade_professores ap
            JOIN professores p ON ap.professor_id = p.id
            JOIN utilizadores u_prof ON p.utilizador_id = u_prof.id
            JOIN turmas t ON ap.turma_id = t.id
            JOIN disciplinas d ON ap.disciplina_id = d.id
            JOIN utilizadores u_admin ON ap.marcado_por = u_admin.id
            ORDER BY ap.data DESC, ap.id DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDetailedAttendanceForProfessor($professor_id) {
        $stmt = $this->db->prepare("
            SELECT 
                ap.id, ap.professor_id, ap.turma_id, ap.disciplina_id, ap.data, ap.tempo, ap.status, 
                ap.justificacao, u_admin.nome_completo as marcado_por_nome, t.codigo as turma_codigo, d.nome as disciplina_nome
            FROM assiduidade_professores ap
            LEFT JOIN turmas t ON ap.turma_id = t.id
            LEFT JOIN disciplinas d ON ap.disciplina_id = d.id
            LEFT JOIN utilizadores u_admin ON ap.marcado_por = u_admin.id
            WHERE ap.professor_id = :pid

            UNION ALL

            SELECT 
                s.id, s.professor_id, s.turma_id, s.disciplina_id, s.data, s.tempo, 
                'Presença' as status, 
                CONCAT('Sumário: ', SUBSTRING(s.conteudo, 1, 50), '...') as justificacao,
                'Sistema (Auto)' as marcado_por_nome,
                t.codigo as turma_codigo, d.nome as disciplina_nome
            FROM sumarios s
            LEFT JOIN turmas t ON s.turma_id = t.id
            LEFT JOIN disciplinas d ON s.disciplina_id = d.id
            WHERE s.professor_id = :pid2
            AND NOT EXISTS (
                SELECT 1 FROM assiduidade_professores ap2 
                WHERE ap2.professor_id = s.professor_id 
                AND ap2.data = s.data 
                AND ap2.tempo = s.tempo
            )

            ORDER BY data DESC, tempo DESC
        ");
        $stmt->execute([':pid' => $professor_id, ':pid2' => $professor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSummariesByStudent($student_id) {
        $stmt = $this->db->prepare("
            SELECT s.*, 
                   u.nome_completo as professor_nome, 
                   d.nome as disciplina_nome, 
                   t.codigo as turma_codigo
            FROM sumarios s
            JOIN turmas t ON s.turma_id = t.id
            JOIN disciplinas d ON s.disciplina_id = d.id
            JOIN professores p ON s.professor_id = p.id
            JOIN utilizadores u ON p.utilizador_id = u.id
            WHERE s.turma_id IN (
                SELECT turma_id 
                FROM matriculas 
                WHERE estudante_id = :sid 
                AND (status = 'Aprovada' OR status = 'Pendente' OR status = 'ativo')
            )
            ORDER BY s.data DESC, s.tempo DESC
        ");
        $stmt->execute([':sid' => $student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
