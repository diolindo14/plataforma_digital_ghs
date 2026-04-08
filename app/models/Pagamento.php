<?php
/**
 * Modelo Pagamento
 * 
 * @package Models
 * @author Senior Software Engineer / Mentor
 * 
 * Documentação Funcional:
 * Este modelo é responsável por toda a camada financeira da aplicação. Ele gere o registo 
 * de pagamentos manuais (feitos na secretaria) e o fluxo de aprovação de pagamentos 
 * submetidos por estudantes.
 */
class Pagamento {
    /** @var PDO Conexão com a base de dados */
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function createManual($data) {
        $meses = ['Janeiro'=>1, 'Fevereiro'=>2, 'Março'=>3, 'Abril'=>4, 'Maio'=>5, 'Junho'=>6, 'Julho'=>7, 'Agosto'=>8, 'Setembro'=>9, 'Outubro'=>10, 'Novembro'=>11, 'Dezembro'=>12];
        $mes = isset($meses[$data['mes_referencia']]) ? $meses[$data['mes_referencia']] : null;

        $stmt = $this->db->prepare('INSERT INTO pagamentos (estudante_id, descricao, mes_referencia, ano_letivo, valor, data_pagamento, data_vencimento, forma_pagamento, comprovativo_arquivo, status, processado_por, observacoes) VALUES (:estudante_id, :descricao, :mes_referencia, :ano_letivo, :valor, :data_pagamento, :data_vencimento, :forma_pagamento, :comprovativo_arquivo, :status, :processado_por, :observacoes)');
        
        $forma = 'Dinheiro'; 
        if (isset($data['metodo_pagamento'])) {
            $map = [
                'Transferência Bancária' => 'Transferência',
                'Depósito' => 'Dinheiro',
                'Mobile Money' => 'Mobile Money',
                'Numerário' => 'Dinheiro',
                'Propina' => 'Propina'
            ];
            $forma = $map[$data['metodo_pagamento']] ?? 'Dinheiro';
        }

        if (!isset($data['estudante_id'])) return false;
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

    public function getAll($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        $stmt = $this->db->prepare('SELECT p.*, ue.nome_completo as estudante_nome, e.bi, ua.nome_completo as registado_por_nome 
                                     FROM pagamentos p 
                                     JOIN estudantes e ON p.estudante_id = e.id 
                                     JOIN utilizadores ue ON e.utilizador_id = ue.id
                                     LEFT JOIN utilizadores ua ON p.processado_por = ua.id 
                                     ORDER BY p.data_criacao DESC
                                     LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAll() {
        return $this->db->query("SELECT COUNT(*) FROM pagamentos")->fetchColumn();
    }

    public function getPagamentosPorEstudante($estudante_id) {
        $stmt = $this->db->prepare('SELECT * FROM pagamentos WHERE estudante_id = :estudante_id ORDER BY data_criacao DESC');
        $stmt->bindValue(':estudante_id', $estudante_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPagamentoById($id) {
        return $this->getById($id);
    }

    public function getById($id) {
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
    
    public function registrarTaxasMatricula($estudante_id, $admin_id, $mensalidade = 0, $tipo_aluno = 'Novo Ingresso') {
        $ano_letivo = date('Y') . '/' . (date('Y') + 1);
        $taxa_inscricao = ($tipo_aluno === 'Novo Ingresso') ? 15000 : 10000;
        
        $tipo_label = ($tipo_aluno) ? "- $tipo_aluno" : "";
        $itens = [
            ['nome' => "Taxa de Inscrição $tipo_label", 'valor' => $taxa_inscricao, 'mes' => 0],
            ['nome' => "10º Mês (Mensalidade de Julho)", 'valor' => $mensalidade, 'mes' => 7],
            ['nome' => "Taxa de Associação de Estudante (TAE)", 'valor' => 1000, 'mes' => 0],
            ['nome' => "Selos de Estado (Legalização)", 'valor' => 2000, 'mes' => 0],
            ['nome' => "Cartão de Estudante", 'valor' => 2500, 'mes' => 0],
            ['nome' => "Caderneta de Notas", 'valor' => 3000, 'mes' => 0]
        ];

        foreach ($itens as $item) {
            $stmtCheck = $this->db->prepare("SELECT id FROM pagamentos WHERE estudante_id = :eid AND descricao LIKE :desc AND (ano_letivo = :ano OR ano_letivo = :ano2)");
            $stmtCheck->execute([
                ':eid' => $estudante_id, 
                ':desc' => $item['nome'] . '%',
                ':ano' => $ano_letivo,
                ':ano2' => date('Y')
            ]);
            
            if ($stmtCheck->fetch()) continue;

            $stmt = $this->db->prepare('
                INSERT INTO pagamentos (
                    estudante_id, descricao, mes_referencia, ano_letivo, valor, 
                    data_pagamento, data_vencimento, forma_pagamento, status, 
                    processado_por, observacoes
                ) VALUES (
                    :eid, :desc, :mes, :ano, :valor, 
                    NOW(), NOW(), "Numerário", "Pago", :admin, 
                    "Pagamento automático de taxas obrigatórias no acto da matrícula (Workflow GHS)."
                )
            ');
            
            $stmt->execute([
                ':eid'   => $estudante_id,
                ':desc'  => $item['nome'] . " - Acto de Matrícula",
                ':mes'   => $item['mes'],
                ':ano'   => $ano_letivo,
                ':valor' => $item['valor'],
                ':admin' => $admin_id
            ]);
        }
        return true;
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

    public function getClusterByPaymentId($id) {
        $p = $this->getById($id);
        if (!$p) return [];

        if (stripos($p['descricao'], 'Acto de Matrícula') === false) {
            return [$p];
        }

        $stmt = $this->db->prepare("
            SELECT p.*, ue.nome_completo as estudante_nome, e.bi, ua.nome_completo as registado_por_nome 
            FROM pagamentos p 
            JOIN estudantes e ON p.estudante_id = e.id 
            JOIN utilizadores ue ON e.utilizador_id = ue.id
            LEFT JOIN utilizadores ua ON p.processado_por = ua.id 
            WHERE p.estudante_id = :eid 
              AND ABS(TIMESTAMPDIFF(MINUTE, p.data_criacao, :dc)) <= 5
            ORDER BY p.valor DESC
        ");
        $stmt->execute([
            ':eid' => $p['estudante_id'],
            ':dc'  => $p['data_criacao']
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
