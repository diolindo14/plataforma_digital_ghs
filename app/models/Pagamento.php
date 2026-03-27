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

    /**
     * Regista um pagamento manual realizado presencialmente na secretaria.
     * 
     * @param array $data Conjunto de dados do formulário financeiro.
     * @return bool Sucesso ou falha na operação de inserção.
     * 
     * // Refatoração: O mapeamento de meses hardcoded pode ser extraído para um Helper.
     * // Sugestão: Usar um array associativo constante na classe para evitar recriação em cada chamada.
     */
    public function createManual($data) {
        $meses = ['Janeiro'=>1, 'Fevereiro'=>2, 'Março'=>3, 'Abril'=>4, 'Maio'=>5, 'Junho'=>6, 'Julho'=>7, 'Agosto'=>8, 'Setembro'=>9, 'Outubro'=>10, 'Novembro'=>11, 'Dezembro'=>12];
        $mes = isset($meses[$data['mes_referencia']]) ? $meses[$data['mes_referencia']] : null;

        $stmt = $this->db->prepare('INSERT INTO pagamentos (estudante_id, descricao, mes_referencia, ano_letivo, valor, data_pagamento, data_vencimento, forma_pagamento, comprovativo_arquivo, status, processado_por, observacoes) VALUES (:estudante_id, :descricao, :mes_referencia, :ano_letivo, :valor, :data_pagamento, :data_vencimento, :forma_pagamento, :comprovativo_arquivo, :status, :processado_por, :observacoes)');
        
        // // Clean Code: A lógica de mapeamento de métodos de pagamento simplificada com array de de-para.
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
        
        // // Identificação de Pontos Cegos: Dependência direta de $_SESSION. 
        $stmt->bindValue(':processado_por', $_SESSION['user_id'] ?? 1); 
        $stmt->bindValue(':observacoes', $data['observacoes'] ?? null);

        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Recupera todos os pagamentos com relacionamentos (estudante e operador) com paginação.
     * 
     * @param int $page O número da página atual.
     * @param int $limit O número de registros por página.
     * @return array Uma lista de pagamentos.
     * 
     * // Auditoria (Pilar 4): Implementada Paginação para otimizar performance.
     */
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

    /**
     * Conta o número total de pagamentos.
     * 
     * @return int O número total de pagamentos.
     */
    public function countAll() {
        return $this->db->query("SELECT COUNT(*) FROM pagamentos")->fetchColumn();
    }

    /**
     * Lista pagamentos de um estudante específico (Histórico Financeiro do Aluno).
     */
    public function getPagamentosPorEstudante($estudante_id) {
        $stmt = $this->db->prepare('SELECT * FROM pagamentos WHERE estudante_id = :estudante_id ORDER BY data_criacao DESC');
        $stmt->bindValue(':estudante_id', $estudante_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um pagamento específico por ID para visualização detalhada ou edição.
     */
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

    /**
     * Atualização genérica de status de pagamento.
     */
    public function atualizarStatus($id, $status) {
        $stmt = $this->db->prepare('UPDATE pagamentos SET status = :status WHERE id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':status', $status);
        return $stmt->execute();
    }

    /**
     * Fluxo de Aprovação: Valida um pagamento submetido originalmente como pendente.
     */
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

    /**
     * Fluxo de Rejeição: Invalida um pagamento (ex: comprovativo falso ou ilegível).
     */
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
    
    /**
     * Estatística: Calcula o montante total arrecadado num período específico.
     */
    public function getEstatisticasMensais($mes, $ano) {
        $stmt = $this->db->prepare("SELECT SUM(valor) as total_arrecadado FROM pagamentos WHERE status = 'Pago' AND MONTH(data_pagamento) = :mes AND YEAR(data_pagamento) = :ano");
        $stmt->bindValue(':mes', $mes);
        $stmt->bindValue(':ano', $ano);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Tabelas de Apoio: Lista os tipos de pagamento configurados (ex: Propinas, Emolumentos).
     */
    public function getTiposPagamento() {
        $stmt = $this->db->prepare("SELECT * FROM tipos_pagamento ORDER BY nome ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
