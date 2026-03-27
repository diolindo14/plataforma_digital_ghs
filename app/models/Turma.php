<?php
/**
 * Modelo Turma - Gestão de Agrupamentos de Estudantes.
 * 
 * Responsável pela organização física e lógica dos alunos, vinculando-os 
 * a um ano/nível específico e gerindo a ocupação de vagas.
 */
class Turma {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Recupera todas as turmas com o nome legível do ano curso.
     */
    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT t.*, a.nome as ano_nome 
            FROM turmas t
            JOIN anos a ON t.ano_id = a.id
            ORDER BY a.ordem ASC, t.codigo ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Localiza uma turma específica e verifica a existência de horários configurados.
     */
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT t.*, a.nome as ano_nome, 
            (SELECT COUNT(*) FROM horarios WHERE turma_id = t.id) as has_horario
            FROM turmas t 
            JOIN anos a ON t.ano_id = a.id 
            WHERE t.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Persiste uma nova turma no sistema.
     * 
     * // Sugestão: Adicionar validação de unicidade para o 'codigo' 
     * // antes da inserção para evitar erros de base de dados silenciados.
     */
    public function createTurma($data) {
        $stmt = $this->db->prepare("INSERT INTO turmas (codigo, ano_id, turno, numero_turma, sala_principal, vagas, ativa) 
                                    VALUES (:codigo, :ano_id, :turno, :numero, :sala, :vagas, 1)");
        $stmt->bindValue(':codigo', $data['codigo']);
        $stmt->bindValue(':ano_id', $data['ano_id']);
        $stmt->bindValue(':turno', $data['turno']);
        $stmt->bindValue(':numero', $data['numero_turma']);
        $stmt->bindValue(':sala', $data['sala_principal']);
        $stmt->bindValue(':vagas', $data['vagas'] ?? 30);
        return $stmt->execute();
    }

    /**
     * Remove uma turma.
     * 
     * // Atenção: O catch genérico pode ocultar erros de Constraint (FK) 
     * // se a turma possuir alunos ou horários vinculados.
     */
    public function deleteTurma($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM turmas WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
