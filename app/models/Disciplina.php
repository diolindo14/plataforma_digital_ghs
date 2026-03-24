<?php
class Disciplina {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("
            SELECT d.*, a.nome as ano_nome 
            FROM disciplinas d
            JOIN anos a ON d.ano_id = a.id
            ORDER BY a.ordem ASC, d.nome ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByAno($ano_id) {
        $stmt = $this->db->prepare("SELECT * FROM disciplinas WHERE ano_id = :ano_id ORDER BY nome ASC");
        $stmt->execute([':ano_id' => $ano_id]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO disciplinas (codigo, nome, ano_id, carga_horaria, credito, descricao) 
                                    VALUES (:codigo, :nome, :ano_id, :carga, :credito, :descricao)");
        return $stmt->execute([
            ':codigo' => $data['codigo'],
            ':nome' => $data['nome'],
            ':ano_id' => $data['ano_id'],
            ':carga' => $data['carga_horaria'],
            ':credito' => $data['credito'] ?? 0,
            ':descricao' => $data['descricao'] ?? ''
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE disciplinas SET codigo = :codigo, nome = :nome, ano_id = :ano_id, 
                                    carga_horaria = :carga, credito = :credito, descricao = :descricao WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':codigo' => $data['codigo'],
            ':nome' => $data['nome'],
            ':ano_id' => $data['ano_id'],
            ':carga' => $data['carga_horaria'],
            ':credito' => $data['credito'],
            ':descricao' => $data['descricao']
        ]);
    }

    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM disciplinas WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
