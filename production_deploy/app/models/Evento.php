<?php
class Evento {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO eventos (titulo, descricao, data_evento, tipo, cor, destinatario_tipo, destinatario_id, criado_por) 
                                    VALUES (:titulo, :descricao, :data_evento, :tipo, :cor, :destinatario_tipo, :destinatario_id, :criado_por)");
        return $stmt->execute([
            ':titulo' => $data['titulo'],
            ':descricao' => $data['descricao'] ?? null,
            ':data_evento' => $data['data_evento'],
            ':tipo' => $data['tipo'] ?? 'Outro',
            ':cor' => $data['cor'] ?? '#3b82f6',
            ':destinatario_tipo' => $data['destinatario_tipo'] ?? 'Global',
            ':destinatario_id' => $data['destinatario_id'] ?? null,
            ':criado_por' => $_SESSION['user_id'] ?? 1
        ]);
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT e.*, COALESCE(u.nome_completo, 'Sistema') as autor_nome 
                                     FROM eventos e 
                                     LEFT JOIN utilizadores u ON e.criado_por = u.id 
                                     ORDER BY e.data_evento ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getForStudent($studentId) {
        // Encontrar a turma do aluno
        $stmtTurma = $this->db->prepare("SELECT m.turma_id, t.ano_id 
                                          FROM matriculas m 
                                          JOIN turmas t ON m.turma_id = t.id 
                                          WHERE m.estudante_id = :sid AND m.status = 'Aprovada' 
                                          ORDER BY m.id DESC LIMIT 1");
        $stmtTurma->execute([':sid' => $studentId]);
        $info = $stmtTurma->fetch(PDO::FETCH_ASSOC);
        
        $turmaId = $info['turma_id'] ?? null;
        $anoId = $info['ano_id'] ?? null;

        $query = "SELECT * FROM eventos WHERE destinatario_tipo = 'Global' ";
        $params = [];

        if ($anoId) {
            $query .= " OR (destinatario_tipo = 'Ano' AND destinatario_id = :ano_id) ";
            $params[':ano_id'] = $anoId;
        }
        if ($turmaId) {
            $query .= " OR (destinatario_tipo = 'Turma' AND destinatario_id = :turma_id) ";
            $params[':turma_id'] = $turmaId;
        }
        
        $query .= " OR (destinatario_tipo = 'Individual' AND destinatario_id = :user_id) ";
        $params[':user_id'] = $_SESSION['user_id'] ?? 0;

        $query = "SELECT e.*, COALESCE(u.nome_completo, 'Sistema') as autor_nome 
                  FROM (" . $query . ") e 
                  LEFT JOIN utilizadores u ON e.criado_por = u.id 
                  ORDER BY e.data_evento ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getForProfessor($profId) {
        $stmt = $this->db->prepare("
            SELECT * FROM eventos 
            WHERE criado_por = :pid 
               OR destinatario_tipo = 'Global' 
               OR destinatario_tipo = 'Professores'
            ORDER BY data_evento ASC
        ");
        $stmt->execute([':pid' => $profId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM eventos WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function marcarComoVisto($eventoId, $utilizadorId) {
        $stmt = $this->db->prepare('INSERT IGNORE INTO leitura_eventos (evento_id, utilizador_id, data_leitura) VALUES (:evento_id, :utilizador_id, NOW())');
        $stmt->bindValue(':evento_id', $eventoId);
        $stmt->bindValue(':utilizador_id', $utilizadorId);
        return $stmt->execute();
    }

    public function getVisualizacoes($eventoId) {
        $stmt = $this->db->prepare('SELECT u.nome_completo, u.tipo, le.data_leitura 
                                     FROM leitura_eventos le 
                                     JOIN utilizadores u ON le.utilizador_id = u.id 
                                     WHERE le.evento_id = :evento_id 
                                     ORDER BY le.data_leitura DESC');
        $stmt->bindValue(':evento_id', $eventoId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
