<?php
class Pagamento {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function createManual($data) {
        $meses = ['Janeiro'=>1, 'Fevereiro'=>2, 'Março'=>3, 'Abril'=>4, 'Maio'=>5, 'Junho'=>6, 'Julho'=>7, 'Agosto'=>8, 'Setembro'=>9, 'Outubro'=>10, 'Novembro'=>11, 'Dezembro'=>12];
        $mes = isset($meses[$data['mes_referencia']]) ? $meses[$data['mes_referencia']] : null;

        $stmt = $this->db->prepare('INSERT INTO pagamentos (estudante_id, descricao, mes_referencia, ano_letivo, valor, data_pagamento, data_vencimento, forma_pagamento, comprovativo_arquivo, status, processado_por, observacoes) VALUES (:estudante_id, :descricao, :mes_referencia, :ano_letivo, :valor, :data_pagamento, :data_vencimento, :forma_pagamento, :comprovativo_arquivo, :status, :processado_por, :observacoes)');
        
        $forma = 'Dinheiro'; // Default fallback
        if (isset($data['metodo_pagamento'])) {
            if ($data['metodo_pagamento'] == 'Transferência Bancária') $forma = 'Transferência';
            else if ($data['metodo_pagamento'] == 'Depósito') $forma = 'Dinheiro'; 
            else if ($data['metodo_pagamento'] == 'Mobile Money') $forma = 'Mobile Money';
            else if ($data['metodo_pagamento'] == 'Numerário') $forma = 'Dinheiro';
            else if ($data['metodo_pagamento'] == 'Propina') $forma = 'Propina';
        }

        $stmt->bindValue(':estudante_id', $data['estudante_id']);
        $stmt->bindValue(':descricao', $data['tipo_pagamento'] ?? 'Pagamento Manual');
        $stmt->bindValue(':mes_referencia', $mes);
        $stmt->bindValue(':ano_letivo', date('Y'));
        $stmt->bindValue(':valor', $data['valor']);
        $stmt->bindValue(':data_pagamento', date('Y-m-d'));
        $stmt->bindValue(':data_vencimento', date('Y-m-d'));
        $stmt->bindValue(':forma_pagamento', $forma);
        $stmt->bindValue(':comprovativo_arquivo', $data['comprovativo'] ?? null);
        $stmt->bindValue(':status', 'Pago');
        $stmt->bindValue(':processado_por', $_SESSION['user_id'] ?? 1); 
        $stmt->bindValue(':observacoes', $data['observacoes'] ?? null);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAll() {
        $stmt = $this->db->prepare('SELECT p.*, ue.nome_completo as estudante_nome, e.bi, ua.nome_completo as registado_por_nome 
                                     FROM pagamentos p 
                                     JOIN estudantes e ON p.estudante_id = e.id 
                                     JOIN utilizadores ue ON e.utilizador_id = ue.id
                                     LEFT JOIN utilizadores ua ON p.processado_por = ua.id 
                                     ORDER BY p.data_criacao DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPagamentosPorEstudante($estudante_id) {
        $stmt = $this->db->prepare('SELECT * FROM pagamentos WHERE estudante_id = :estudante_id ORDER BY data_criacao DESC');
        $stmt->bindValue(':estudante_id', $estudante_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPagamentoById($id) {
        $stmt = $this->db->prepare('SELECT p.*, ue.nome_completo as estudante_nome, e.bi, ua.nome_completo as registado_por_nome 
                                     FROM pagamentos p 
                                     JOIN estudantes e ON p.estudante_id = e.id 
                                     JOIN utilizadores ue ON e.utilizador_id = ue.id
                                     LEFT JOIN utilizadores ua ON p.processado_por = ua.id 
                                     WHERE p.id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarStatus($id, $status) {
        $stmt = $this->db->prepare('UPDATE pagamentos SET status = :status WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':status', $status);
        return $stmt->execute();
    }

    public function aprovarPagamento($id, $admin_id) {
        $stmt = $this->db->prepare("
            UPDATE pagamentos 
            SET status = 'Pago', 
                processado_por = :admin_id, 
                data_pagamento = NOW() 
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id' => $id,
            ':admin_id' => $admin_id
        ]);
    }

    public function rejeitarComMotivo($id, $admin_id, $motivo) {
        $stmt = $this->db->prepare("
            UPDATE pagamentos 
            SET status = 'Rejeitado',
                processado_por = :admin_id,
                observacoes = :motivo
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id'       => $id,
            ':admin_id' => $admin_id,
            ':motivo'   => $motivo
        ]);
    }
    
    public function getEstatisticasMensais($mes, $ano) {
        $stmt = $this->db->prepare("SELECT SUM(valor) as total_arrecadado FROM pagamentos WHERE status = 'Pago' AND MONTH(data_pagamento) = :mes AND YEAR(data_pagamento) = :ano");
        $stmt->bindValue(':mes', $mes);
        $stmt->bindValue(':ano', $ano);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTiposPagamento() {
        $stmt = $this->db->prepare("SELECT * FROM tipos_pagamento ORDER BY nome ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

