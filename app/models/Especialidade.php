<?php
/**
 * Modelo Especialidade - Gestão de Cursos/Áreas de Estudo.
 * 
 * Define os agrupamentos pedagógicos da instituição.
 */
class Especialidade {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Lista todas as especializações ativas.
     */
    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM especializacoes ORDER BY nome ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Cria uma nova especialização.
     */
    public function createEspecialidade($data) {
        $stmt = $this->db->prepare("INSERT INTO especializacoes (codigo, nome, descricao, vagas, ativa) 
                                    VALUES (:codigo, :nome, :descricao, :vagas, 1)");
        $stmt->bindValue(':codigo', $data['codigo']);
        $stmt->bindValue(':nome', $data['nome']);
        $stmt->bindValue(':descricao', $data['descricao']);
        $stmt->bindValue(':vagas', $data['vagas'] ?? 30);
        return $stmt->execute();
    }

    /**
     * Atualiza dados da especialização.
     */
    public function updateEspecialidade($id, $data) {
        $stmt = $this->db->prepare("UPDATE especializacoes SET codigo = :codigo, nome = :nome, 
                                    descricao = :descricao, vagas = :vagas, ativa = :ativa WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':codigo' => $data['codigo'],
            ':nome' => $data['nome'],
            ':descricao' => $data['descricao'],
            ':vagas' => $data['vagas'],
            ':ativa' => $data['ativa']
        ]);
    }

    /**
     * Remove uma especialização.
     */
    public function deleteEspecialidade($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM especializacoes WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
