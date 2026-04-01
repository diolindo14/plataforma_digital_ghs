<?php
class Financeiro {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getSmartInadimplenciaCount() {
        $sql = "SELECT id FROM estudantes e JOIN matriculas m ON e.id = m.estudante_id WHERE m.status = 'Aprovada' GROUP BY e.id";
        $stmt = $this->db->query($sql);
        $estudantes = $stmt->fetchAll();
        $total = 0;
        foreach ($estudantes as $e) {
            $status = $this->getStudentDelinquencyStatus($e['id']);
            if ($status['is_delinquent']) $total++;
        }
        return $total;
    }

    public function getStudentDelinquencyStatus($student_id) {
        $today = new DateTime();
        $start = new DateTime(YEAR_START_DATE);
        
        if ($today < $start) {
            $months_expected = 0;
        } else {
            $diff = $start->diff($today);
            $months_expected = ($diff->y * 12) + $diff->m + 1;
            
            // Se hoje ainda não chegou no dia de pagamento (15), o mês atual ainda não é considerado em atraso (opcional: user decide se quer notificar ANTES ou APOS)
            // Requisito: "notificado para o pagamento em 15 de cada mes"
            if ((int)$today->format('d') < PAYMENT_DUE_DAY) {
                $months_expected--;
            }
            
            if ($months_expected > TOTAL_MONTHS_YEAR) $months_expected = TOTAL_MONTHS_YEAR;
            if ($months_expected < 0) $months_expected = 0;
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) as payments_done FROM pagamentos WHERE estudante_id = :sid AND status = 'Pago'");
        $stmt->execute([':sid' => $student_id]);
        $payments_done = (int)($stmt->fetch()['payments_done'] ?? 0);
        
        $missing = $months_expected - $payments_done;
        return [
            'is_delinquent' => ($missing > 0),
            'missing_months' => ($missing > 0 ? $missing : 0),
            'expected' => $months_expected,
            'done' => $payments_done
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
