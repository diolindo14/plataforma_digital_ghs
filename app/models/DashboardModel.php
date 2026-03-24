<?php
class DashboardModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAdminStats() {
        $stats = [];
        
        // Alunos Ativos
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM estudantes e JOIN utilizadores u ON e.utilizador_id = u.id WHERE u.status = 'ativo'");
        $stats['alunos_ativos'] = $stmt->fetch()['total'] ?? 0;
        
        // Turmas Abertas
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM turmas");
        $stats['turmas_abertas'] = $stmt->fetch()['total'] ?? 0;
        
        // Professores
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM utilizadores WHERE tipo = 'professor'");
        $stats['professores'] = $stmt->fetch()['total'] ?? 0;
        
        // Matrículas Pendentes
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM matriculas WHERE status = 'Pendente' OR status = 'Em validacao'");
        $stats['matriculas_pendentes'] = $stmt->fetch()['total'] ?? 0;

        // Financeiro: Pagamentos do Mês (Pago)
        $mesAtual = date('m');
        $anoAtual = date('Y');
        $stmt = $this->db->prepare("SELECT SUM(valor) as total FROM pagamentos WHERE status = 'Pago' AND MONTH(data_pagamento) = :mes AND YEAR(data_pagamento) = :ano");
        $stmt->execute([':mes' => $mesAtual, ':ano' => $anoAtual]);
        $stats['pagamentos_mes'] = $stmt->fetch()['total'] ?? 0;

        // Inadimplência: Estudantes com pagamentos vencidos
        $stmt = $this->db->query("SELECT COUNT(DISTINCT estudante_id) as total FROM pagamentos WHERE status = 'Vencido' OR (status = 'Pendente' AND data_vencimento < CURRENT_DATE)");
        $stats['inadimplencia'] = $stmt->fetch()['total'] ?? 0;
        
        return $stats;
    }

    public function getAdminChartData() {
        $data = [
            'anos' => [0, 0, 0, 0, 0], // 1º a 5º 
            'turnos' => ['Manhã' => 0, 'Tarde' => 0, 'Noite' => 0]
        ];

        // Alunos por Ano (baseado em matrículas aprovadas no ano letivo corrente)
        $anoAtual = date('Y');
        $stmt = $this->db->query("SELECT ano_curso_id, COUNT(*) as total FROM matriculas WHERE status = 'Aprovada' GROUP BY ano_curso_id");
        while ($row = $stmt->fetch()) {
            $idx = (int)$row['ano_curso_id'] - 1;
            if ($idx >= 0 && $idx < 5) {
                $data['anos'][$idx] = (int)$row['total'];
            }
        }

        // Distribuição por Turno
        $stmt = $this->db->query("SELECT turno, COUNT(*) as total FROM matriculas WHERE status = 'Aprovada' GROUP BY turno");
        while ($row = $stmt->fetch()) {
            if (isset($data['turnos'][$row['turno']])) {
                $data['turnos'][$row['turno']] = (int)$row['total'];
            }
        }

        return $data;
    }

    public function getSecretariaStats() {
        $stats = [];
        
        // Matrículas Pendentes
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM matriculas WHERE status = 'Pendente' OR status = 'Em validacao'");
        $stats['matriculas_pendentes'] = $stmt->fetch()['total'];
        
        // Pagamentos a Validar (com comprovativo mas status pendente)
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM pagamentos WHERE status = 'Pendente' AND comprovativo_arquivo IS NOT NULL");
        $stats['pagamentos_validar'] = $stmt->fetch()['total'];

        return $stats;
    }
}
