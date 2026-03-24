<?php
class Academico {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function getAnos() {
        $stmt = $this->db->prepare("SELECT * FROM anos ORDER BY ordem ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAnoById($id) {
        $stmt = $this->db->prepare("SELECT * FROM anos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function createAno($data) {
        $stmt = $this->db->prepare("INSERT INTO anos (numero, nome, descricao, mensalidade, ordem) 
                                    VALUES (:numero, :nome, :descricao, :mensalidade, :ordem)");
        return $stmt->execute([
            ':numero' => $data['numero'],
            ':nome' => $data['nome'],
            ':descricao' => $data['descricao'],
            ':mensalidade' => $data['mensalidade'],
            ':ordem' => $data['ordem']
        ]);
    }

    public function updateAno($id, $data) {
        $stmt = $this->db->prepare("UPDATE anos SET numero = :numero, nome = :nome, descricao = :descricao, 
                                    mensalidade = :mensalidade, ordem = :ordem WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':numero' => $data['numero'],
            ':nome' => $data['nome'],
            ':descricao' => $data['descricao'],
            ':mensalidade' => $data['mensalidade'],
            ':ordem' => $data['ordem']
        ]);
    }

    public function deleteAno($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM anos WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getScheduleByTurma($turma_id) {
        $stmt = $this->db->prepare("
            SELECT h.*, d.nome as disciplina_nome
            FROM horarios h
            JOIN disciplinas d ON h.disciplina_id = d.id
            WHERE h.turma_id = :tid
            ORDER BY FIELD(h.dia_semana, 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'), h.hora_inicio
        ");
        $stmt->execute([':tid' => $turma_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGradesByStudent($estudante_id) {
        $stmt = $this->db->prepare("
            SELECT 
                d.nome as disciplina_nome,
                ta.id as tipo_id,
                n.nota,
                a.turma_id,
                a.disciplina_id,
                cn.status as feedback_status,
                cn.comentario as feedback_comentario
            FROM notas n
            JOIN avaliacoes a ON n.avaliacao_id = a.id
            JOIN tipos_avaliacao ta ON a.tipo_avaliacao_id = ta.id
            JOIN disciplinas d ON a.disciplina_id = d.id
            LEFT JOIN concordancia_notas cn ON n.estudante_id = cn.estudante_id 
                AND a.turma_id = cn.turma_id 
                AND a.disciplina_id = cn.disciplina_id
            WHERE n.estudante_id = :eid
        ");
        $stmt->execute([':eid' => $estudante_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $grouped = [];
        foreach ($results as $r) {
            $name = $r['disciplina_nome'];
            if (!isset($grouped[$name])) {
                $grouped[$name] = [
                    'disciplina' => $name,
                    'turma_id' => $r['turma_id'],
                    'disciplina_id' => $r['disciplina_id'],
                    'feedback_status' => $r['feedback_status'] ?? 'Pendente',
                    'feedback_comentario' => $r['feedback_comentario'],
                    'notas' => [1=>0, 2=>0, 3=>0, 4=>0, 5=>null]
                ];
            }
            $grouped[$name]['notas'][$r['tipo_id']] = $r['nota'];
        }

        foreach ($grouped as &$row) {
            $ac = $row['notas'][1] + $row['notas'][2] + $row['notas'][3] + $row['notas'][4];
            $row['total_ac'] = $ac;
            $row['nota_final'] = ($row['notas'][5] !== null) ? ($ac + $row['notas'][5]) / 2 : null;
        }

        return $grouped;
    }

    public function getGlobalHistory($estudante_id) {
        $stmt = $this->db->prepare("
            SELECT 
                d.nome as disciplina_nome,
                a.ano_letivo,
                a.semestre,
                ta.id as tipo_id,
                n.nota
            FROM notas n
            JOIN avaliacoes a ON n.avaliacao_id = a.id
            JOIN tipos_avaliacao ta ON a.tipo_avaliacao_id = ta.id
            JOIN disciplinas d ON a.disciplina_id = d.id
            WHERE n.estudante_id = :eid
            ORDER BY a.ano_letivo DESC, a.semestre DESC, d.nome ASC
        ");
        $stmt->execute([':eid' => $estudante_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $history = [];
        foreach ($results as $r) {
            $key = $r['ano_letivo'] . '_' . $r['semestre'] . '_' . $r['disciplina_nome'];
            if (!isset($history[$key])) {
                $history[$key] = [
                    'ano' => $r['ano_letivo'],
                    'semestre' => $r['semestre'],
                    'disciplina' => $r['disciplina_nome'],
                    'notas' => [1=>0, 2=>0, 3=>0, 4=>0, 5=>null]
                ];
            }
            $history[$key]['notas'][$r['tipo_id']] = $r['nota'];
        }

        foreach ($history as &$row) {
            $ac = $row['notas'][1] + $row['notas'][2] + $row['notas'][3] + $row['notas'][4];
            $row['total_ac'] = $ac;
            // Cálculo: (Σ AC + Exame) / 2
            $row['nota_final'] = ($row['notas'][5] !== null) ? ($ac + $row['notas'][5]) / 2 : null;
            
            if ($row['nota_final'] !== null) {
                $row['status'] = ($row['nota_final'] >= 10) ? 'Aprovado' : 'Reprovado';
            } else {
                $row['status'] = 'Em Curso';
            }
        }

        return array_values($history);
    }
}
