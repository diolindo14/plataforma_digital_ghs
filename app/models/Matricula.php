<?php
class Matricula {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function createEnrollment($data) {
        $stmt = $this->db->prepare("INSERT INTO matriculas (estudante_id, ano_letivo, ano_curso_id, turno, tipo, status, data_matricula, observacoes) 
                                    VALUES (:estudante_id, :ano_letivo, :ano_id, :turno, :tipo, 'Pendente', NOW(), :obs)");
        
        $stmt->bindValue(':estudante_id', $data['user_id']);
        $stmt->bindValue(':ano_letivo', date('Y'));
        $stmt->bindValue(':ano_id', $data['ano_id']);
        $stmt->bindValue(':turno', $data['turno']);
        $stmt->bindValue(':tipo', $data['tipo']);
        $stmt->bindValue(':obs', $data['motivo']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function getPendingEnrollments() {
        $stmt = $this->db->prepare("
            SELECT m.*, u.nome_completo as nome,
                   (SELECT nome_arquivo FROM documentos_matricula WHERE matricula_id = m.id AND tipo_documento = 'BI' LIMIT 1) as bi_arquivo,
                   (SELECT nome_arquivo FROM documentos_matricula WHERE matricula_id = m.id AND tipo_documento = 'Certificado' LIMIT 1) as certificado_arquivo,
                   (SELECT nome_arquivo FROM documentos_matricula WHERE matricula_id = m.id AND tipo_documento = 'Comprovativo_Pagamento' LIMIT 1) as comprovativo_arquivo
            FROM matriculas m 
            JOIN estudantes e ON m.estudante_id = e.id 
            JOIN utilizadores u ON e.utilizador_id = u.id 
            WHERE m.status = 'Pendente' OR m.status = 'Em validacao'
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateStatus($id, $status, $admin_id, $motivo = null) {
        $sql = "UPDATE matriculas SET status = :status, aprovado_por = :admin_id, data_aprovacao = NOW()";
        if ($motivo) {
            $sql .= ", motivo_rejeicao = :motivo";
        }
        $sql .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':admin_id', $admin_id);
        if ($motivo) $stmt->bindValue(':motivo', $motivo);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function assignToTurma($id, $turma_id) {
        $stmt = $this->db->prepare("UPDATE matriculas SET turma_id = :tid WHERE id = :id");
        return $stmt->execute([':tid' => $turma_id, ':id' => $id]);
    }

    public function getApprovedWithoutTurma() {
        $stmt = $this->db->prepare("
            SELECT m.*, u.nome_completo as estudante_nome, a.nome as ano_nome
            FROM matriculas m
            JOIN estudantes e ON m.estudante_id = e.id
            JOIN utilizadores u ON e.utilizador_id = u.id
            JOIN anos a ON m.ano_curso_id = a.id
            WHERE m.status = 'Aprovada' AND m.turma_id IS NULL
            ORDER BY m.data_matricula DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function saveDocument($matricula_id, $tipo, $nome, $caminho) {
        $stmt = $this->db->prepare("INSERT INTO documentos_matricula (matricula_id, tipo_documento, nome_arquivo, caminho_arquivo) 
                                 VALUES (:mid, :tipo, :nome, :caminho)");
        return $stmt->execute([
            ':mid' => $matricula_id,
            ':tipo' => $tipo,
            ':nome' => $nome,
            ':caminho' => $caminho
        ]);
    }

    public function getCurrentYearInfo($estudante_id) {
        $stmt = $this->db->prepare("
            SELECT m.ano_curso_id, a.nome as ano_nome, a.ordem
            FROM matriculas m
            JOIN anos a ON m.ano_curso_id = a.id
            WHERE m.estudante_id = :eid AND m.status = 'Aprovada'
            ORDER BY a.ordem DESC, m.id DESC LIMIT 1
        ");
        $stmt->execute([':eid' => $estudante_id]);
        return $stmt->fetch();
    }

    public function getDetailedAcademicStatus($estudante_id) {
        $current = $this->getCurrentYearInfo($estudante_id);
        if (!$current) return ['status' => 'Pendente', 'can_transit' => false];

        $stmtAll = $this->db->prepare("SELECT id FROM disciplinas WHERE ano_id = :aid");
        $stmtAll->execute([':aid' => $current['ano_curso_id']]);
        $allSubjects = $stmtAll->fetchAll();
        $totalSubjects = count($allSubjects);

        if ($totalSubjects == 0) return ['status' => 'Aprovado', 'can_transit' => true];

        $stmtGrades = $this->db->prepare("
            SELECT d.id as disciplina_id,
                   (SUM(CASE WHEN a.tipo_avaliacao_id IN (1,2,3,4) THEN n.nota ELSE 0 END) + 
                    MAX(CASE WHEN a.tipo_avaliacao_id = 5 THEN n.nota ELSE 0 END)) / 2 as media_final
            FROM disciplinas d
            LEFT JOIN avaliacoes a ON a.disciplina_id = d.id
            LEFT JOIN notas n ON n.avaliacao_id = a.id AND n.estudante_id = :eid AND n.confirmado_admin = 1
            WHERE d.ano_id = :aid
            GROUP BY d.id
        ");
        $stmtGrades->execute([':eid' => $estudante_id, ':aid' => $current['ano_curso_id']]);
        $grades = $stmtGrades->fetchAll();

        $passedCount = 0;
        $recursoCount = 0;
        $reprovadoCount = 0;
        $missingCount = 0;

        foreach ($grades as $g) {
            $media = $g['media_final'];
            if ($media === null) {
                $missingCount++;
            } elseif ($media >= 12) {
                $passedCount++;
            } elseif ($media >= 8) {
                $recursoCount++;
            } else {
                $reprovadoCount++;
            }
        }

        // If missing grades, we can't decide yet, assume not passed
        if ($missingCount > 0) return ['status' => 'Pendente', 'can_transit' => false];

        // Rule: If any grade <= 7 OR more than 3 negatives (< 12), then Reprovado (Repeat year)
        if ($reprovadoCount > 0 || ($recursoCount + $reprovadoCount) > 3) {
            return ['status' => 'Reprovado', 'can_transit' => false, 'failed_subjects' => ($recursoCount + $reprovadoCount)];
        }

        if ($recursoCount > 0) {
            return ['status' => 'Recurso', 'can_transit' => false, 'recurso_subjects' => $recursoCount];
        }

        return ['status' => 'Aprovado', 'can_transit' => ($passedCount == $totalSubjects)];
    }

    public function isEligibleForRenewal($estudante_id) {
        $status = $this->getDetailedAcademicStatus($estudante_id);
        return $status['can_transit'];
    }
}
