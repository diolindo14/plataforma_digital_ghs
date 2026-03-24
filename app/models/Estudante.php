<?php
class Estudante {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function createEstudante($data) {
        $stmt = $this->db->prepare("INSERT INTO estudantes (utilizador_id, bi, data_nascimento, nacionalidade, sexo, estado_civil, telefone, telefone_alternativo, morada, bairro, cidade, nome_encarregado, telefone_encarregado, escola_proveniencia, ano_conclusao, media_final) 
                                    VALUES (:user_id, :bi, :data_nasc, :nacionalidade, :sexo, :estado_civil, :telefone, :tel_alt, :morada, :bairro, :cidade, :encarregado_nome, :encarregado_tel, :escola, :ano_conclusao, :media)");
        
        $stmt->bindValue(':user_id', $data['utilizador_id'] ?? $data['user_id'] ?? null);
        $stmt->bindValue(':bi', $data['bi']);
        $stmt->bindValue(':data_nasc', $data['data_nascimento']);
        $stmt->bindValue(':nacionalidade', $data['nacionalidade']);
        $stmt->bindValue(':sexo', $data['sexo']);
        $stmt->bindValue(':estado_civil', $data['estado_civil'] ?? 'Solteiro');
        $stmt->bindValue(':telefone', $data['telefone']);
        $stmt->bindValue(':tel_alt', $data['telefone_alternativo'] ?? null);
        $stmt->bindValue(':morada', $data['morada']);
        $stmt->bindValue(':bairro', $data['bairro'] ?? null);
        $stmt->bindValue(':cidade', $data['cidade'] ?? 'Bissau');
        $stmt->bindValue(':encarregado_nome', $data['encarregado_nome']);
        $stmt->bindValue(':encarregado_tel', $data['encarregado_telefone']);
        $stmt->bindValue(':escola', $data['escola']);
        $stmt->bindValue(':ano_conclusao', $data['ano_conclusao']);
        $stmt->bindValue(':media', $data['media']);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function getAllStudents() {
        $stmt = $this->db->prepare("
            SELECT e.*, u.nome_completo, u.email, u.status as user_status,
            (SELECT t.codigo FROM matriculas m JOIN turmas t ON m.turma_id = t.id WHERE m.estudante_id = e.id AND m.status = 'Aprovada' ORDER BY m.id DESC LIMIT 1) as turma,
            (SELECT a.nome FROM matriculas m JOIN anos a ON m.ano_curso_id = a.id WHERE m.estudante_id = e.id AND m.status = 'Aprovada' ORDER BY m.id DESC LIMIT 1) as nivel
            FROM estudantes e
            JOIN utilizadores u ON e.utilizador_id = u.id
            ORDER BY u.nome_completo ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getDetailsByUserId($user_id) {
        $stmt = $this->db->prepare("
            SELECT e.*, u.nome_completo, u.email, u.status as user_status, e.foto_perfil as user_foto,
            (SELECT t.codigo FROM matriculas m JOIN turmas t ON m.turma_id = t.id WHERE m.estudante_id = e.id AND m.status = 'Aprovada' LIMIT 1) as turma
            FROM estudantes e 
            JOIN utilizadores u ON e.utilizador_id = u.id 
            WHERE e.utilizador_id = :user_id
        ");
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function findByUserId($user_id) {
        return $this->getDetailsByUserId($user_id);
    }
    public function updateEstudante($id, $data) {
        $stmt = $this->db->prepare("UPDATE estudantes SET bi = :bi, data_nascimento = :data_nasc, nacionalidade = :nacionalidade, sexo = :sexo, estado_civil = :estado_civil, telefone = :telefone, telefone_alternativo = :tel_alt, morada = :morada, bairro = :bairro, cidade = :cidade, nome_encarregado = :enc_nome, telefone_encarregado = :enc_tel WHERE id = :id");
        
        $stmt->bindValue(':bi', $data['bi']);
        $stmt->bindValue(':data_nasc', $data['data_nascimento']);
        $stmt->bindValue(':nacionalidade', $data['nacionalidade']);
        $stmt->bindValue(':sexo', $data['sexo']);
        $stmt->bindValue(':estado_civil', $data['estado_civil'] ?? 'Solteiro');
        $stmt->bindValue(':telefone', $data['telefone']);
        $stmt->bindValue(':tel_alt', $data['telefone_alternativo'] ?? null);
        $stmt->bindValue(':morada', $data['morada']);
        $stmt->bindValue(':bairro', $data['bairro'] ?? null);
        $stmt->bindValue(':cidade', $data['cidade'] ?? 'Bissau');
        $stmt->bindValue(':enc_nome', $data['encarregado_nome'] ?? null);
        $stmt->bindValue(':enc_tel', $data['encarregado_telefone'] ?? null);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function deleteEstudanteByUserId($user_id) {
        $stmt = $this->db->prepare("DELETE FROM estudantes WHERE utilizador_id = :id");
        $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
