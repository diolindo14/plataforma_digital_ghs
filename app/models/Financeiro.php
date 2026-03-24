<?php
class Financeiro {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
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
