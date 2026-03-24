<?php
class Especialidade {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM especializacoes ORDER BY nome ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createEspecialidade($data) {
        $stmt = $this->db->prepare("INSERT INTO especializacoes (codigo, nome, descricao, vagas, ativa) 
                                    VALUES (:codigo, :nome, :descricao, :vagas, 1)");
        $stmt->bindValue(':codigo', $data['codigo']);
        $stmt->bindValue(':nome', $data['nome']);
        $stmt->bindValue(':descricao', $data['descricao']);
        $stmt->bindValue(':vagas', $data['vagas'] ?? 30);
        return $stmt->execute();
    }

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

    public function deleteEspecialidade($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM especializacoes WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
