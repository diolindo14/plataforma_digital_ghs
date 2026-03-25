<?php
class Financeiro {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getSmartInadimplenciaCount() {
        // Regra: Estudante está em atraso se (Meses decorridos desde a matrícula até o fim do mês anterior) > (Total de pagamentos pagos)
        $sql = "
            SELECT COUNT(*) as total FROM (
                SELECT e.id, 
                    (TIMESTAMPDIFF(MONTH, MIN(m.data_criacao), DATE_SUB(DATE_FORMAT(NOW(), '%Y-%m-01'), INTERVAL 1 DAY)) + 1) as months_expected,
                    (SELECT COUNT(*) FROM pagamentos p WHERE p.estudante_id = e.id AND p.status = 'Pago') as payments_done
                FROM estudantes e
                JOIN matriculas m ON e.id = m.estudante_id
                WHERE m.status = 'Aprovada'
                GROUP BY e.id
                HAVING payments_done < months_expected
            ) as subquery
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetch()['total'] ?? 0;
    }

    public function getStudentDelinquencyStatus($student_id) {
        // Regra: Meses decorridos desde a primeira matrícula até o fim do mês anterior
        $sql = "
            SELECT 
                (TIMESTAMPDIFF(MONTH, MIN(m.data_criacao), DATE_SUB(DATE_FORMAT(NOW(), '%Y-%m-01'), INTERVAL 1 DAY)) + 1) as months_expected,
                (SELECT COUNT(*) FROM pagamentos p WHERE p.estudante_id = :sid AND p.status = 'Pago') as payments_done
            FROM matriculas m
            WHERE m.estudante_id = :sid2 AND m.status = 'Aprovada'
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':sid' => $student_id, ':sid2' => $student_id]);
        $row = $stmt->fetch();
        
        $months_expected = (int)($row['months_expected'] ?? 0);
        if ($months_expected < 0) $months_expected = 0;
        $payments_done = (int)($row['payments_done'] ?? 0);
        
        $missing = $months_expected - $payments_done;
        return [
            'is_delinquent' => ($missing > 0),
            'missing_months' => ($missing > 0 ? $missing : 0)
        ];
    }

    public function getPaymentsByStudent($student_id) {
        $stmt = $this->db->prepare("SELECT * FROM pagamentos WHERE estudante_id = :id ORDER BY data_vencimento DESC");
        $stmt->bindValue(':id', $student_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function uploadProof($payment_id, $filename) {
        $stmt = $this->db->prepare("UPDATE pagamentos SET comprovativo_arquivo = :file, status = 'Pendente' WHERE id = :id");
        $stmt->bindValue(':file', $filename);
        $stmt->bindValue(':id', $payment_id);
        return $stmt->execute();
    }

    public function getGlobalStats() {
        $stats = [];
        
        // Alunos Ativos
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM utilizadores WHERE tipo = 'aluno' AND status = 'ativo'");
        $stats['alunos_ativos'] = $stmt->fetch()['total'];
        
        // Matrículas Pendentes
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM matriculas WHERE status = 'Pendente'");
        $stats['matriculas_pendentes'] = $stmt->fetch()['total'];
        
        // Professores
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM utilizadores WHERE tipo = 'professor'");
        $stats['professores'] = $stmt->fetch()['total'];
        
        return $stats;
    }
}
