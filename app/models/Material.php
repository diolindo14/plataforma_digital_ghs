<?php
class Material {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO materiais (turma_id, disciplina_id, professor_id, titulo, nome_ficheiro, caminho_ficheiro, tipo_ficheiro)
            VALUES (:tid, :did, :pid, :titulo, :nome, :caminho, :tipo)
        ");
        return $stmt->execute([
            ':tid' => $data['turma_id'],
            ':did' => $data['disciplina_id'],
            ':pid' => $data['professor_id'],
            ':titulo' => $data['titulo'],
            ':nome' => $data['nome_ficheiro'],
            ':caminho' => $data['caminho_ficheiro'],
            ':tipo' => $data['tipo_ficheiro']
        ]);
    }

    public function getByTurma($turma_id) {
        $stmt = $this->db->prepare("
            SELECT m.*, d.nome as disciplina_nome, u.nome_completo as professor_nome
            FROM materiais m
            JOIN disciplinas d ON m.disciplina_id = d.id
            JOIN professores p ON m.professor_id = p.id
            JOIN utilizadores u ON p.utilizador_id = u.id
            WHERE m.turma_id = :tid
            ORDER BY m.data_upload DESC
        ");
        $stmt->execute([':tid' => $turma_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByProfessor($professor_id) {
        $stmt = $this->db->prepare("
            SELECT m.*, t.codigo as turma_codigo, d.nome as disciplina_nome
            FROM materiais m
            JOIN turmas t ON m.turma_id = t.id
            JOIN disciplinas d ON m.disciplina_id = d.id
            WHERE m.professor_id = :pid
            ORDER BY m.data_upload DESC
        ");
        $stmt->execute([':pid' => $professor_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
