<?php
class Comunicado {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($data) {
        $stmt = $this->db->prepare('INSERT INTO comunicados (titulo, mensagem, tipo, prioridade, destinatario_tipo, destinatario_id, data_publicacao, criado_por, ativo, agendado) VALUES (:titulo, :mensagem, :tipo, :prioridade, :destinatario_tipo, :destinatario_id, :data_publicacao, :criado_por, 1, 0)');
        
        $tipo = 'Geral';
        $destTipo = 'Todos';
        $destId = null;

        if ($data['tipo_destinatario'] == 'Todos') {
            $tipo = 'Geral';
        } else if ($data['tipo_destinatario'] == 'Alunos') {
            $tipo = 'Alunos';
        } else if ($data['tipo_destinatario'] == 'Professores') {
            $tipo = 'Professores';
        } else if ($data['tipo_destinatario'] == 'Turma_Especifica') {
            $tipo = 'Turma';
            $destTipo = 'Turma';
            $destId = !empty($data['turma_id']) ? $data['turma_id'] : null;
        }

        $stmt->bindValue(':titulo', $data['titulo']);
        $stmt->bindValue(':mensagem', $data['conteudo']);
        $stmt->bindValue(':tipo', $tipo);
        $stmt->bindValue(':prioridade', 'Normal');
        $stmt->bindValue(':destinatario_tipo', $destTipo);
        $stmt->bindValue(':destinatario_id', $destId);
        $stmt->bindValue(':data_publicacao', date('Y-m-d H:i:s'));
        $stmt->bindValue(':criado_por', $_SESSION['user_id'] ?? 1);

        return $stmt->execute();
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT c.*, c.tipo as alvo, c.mensagem as conteudo, u.nome_completo as autor_nome, t.codigo as turma_nome 
                                     FROM comunicados c 
                                     JOIN utilizadores u ON c.criado_por = u.id 
                                     LEFT JOIN turmas t ON c.destinatario_id = t.id AND c.destinatario_tipo = 'Turma'
                                     WHERE c.data_publicacao >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                                     ORDER BY c.data_publicacao DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getComunicadosParaUtilizador($utilizadorId, $tipoUser, $turmaId = null) {
        $query = "SELECT c.*, c.tipo as alvo, c.mensagem as conteudo, u.nome_completo as autor_nome, 
                  (SELECT COUNT(*) FROM leitura_comunicados cl WHERE cl.comunicado_id = c.id AND cl.utilizador_id = :user_id_lido) as lido 
                  FROM comunicados c 
                  JOIN utilizadores u ON c.criado_por = u.id 
                  WHERE c.data_publicacao >= DATE_SUB(NOW(), INTERVAL 7 DAY) AND (c.tipo = 'Geral' ";
        
        if ($tipoUser == 'professor') {
            $query .= " OR c.tipo = 'Professores' ";
            $query .= " OR c.criado_por = :criador_id "; 
        } else if ($tipoUser == 'aluno') {
            $query .= " OR c.tipo = 'Alunos' ";
            if ($turmaId) {
                $query .= " OR (c.tipo = 'Turma' AND c.destinatario_tipo = 'Turma' AND c.destinatario_id = :turma_id) ";
            }
        }
        
        $query .= ") AND c.id NOT IN (SELECT comunicado_id FROM leitura_comunicados_excluidos WHERE utilizador_id = :user_id_excluido) ORDER BY c.data_publicacao DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':user_id_lido', $utilizadorId);
        $stmt->bindValue(':user_id_excluido', $utilizadorId);
        
        if ($tipoUser == 'professor') {
            $stmt->bindValue(':criador_id', $utilizadorId);
        }
        
        if ($turmaId && $tipoUser == 'aluno') {
            $stmt->bindValue(':turma_id', $turmaId);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT c.*, c.tipo as alvo, c.mensagem as conteudo, u.nome_completo as autor_nome, t.codigo as turma_nome 
                                     FROM comunicados c 
                                     JOIN utilizadores u ON c.criado_por = u.id 
                                     LEFT JOIN turmas t ON c.destinatario_id = t.id AND c.destinatario_tipo = 'Turma'
                                     WHERE c.id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function marcarComoLido($comunicadoId, $utilizadorId) {
        $stmt = $this->db->prepare('INSERT IGNORE INTO leitura_comunicados (comunicado_id, utilizador_id, data_leitura) VALUES (:comunicado_id, :utilizador_id, NOW())');
        $stmt->bindValue(':comunicado_id', $comunicadoId);
        $stmt->bindValue(':utilizador_id', $utilizadorId);
        return $stmt->execute();
    }

    public function getLeiturasPorComunicado($comunicadoId) {
        $stmt = $this->db->prepare('SELECT u.nome_completo, u.tipo, cl.data_leitura 
                                     FROM leitura_comunicados cl 
                                     JOIN utilizadores u ON cl.utilizador_id = u.id 
                                     WHERE cl.comunicado_id = :comunicado_id 
                                     ORDER BY cl.data_leitura DESC');
        $stmt->bindValue(':comunicado_id', $comunicadoId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getNotificacoesNaoLidas($utilizadorId, $tipoUser, $turmaId = null) {
        $comunicados = $this->getComunicadosParaUtilizador($utilizadorId, $tipoUser, $turmaId);
        $naoLidos = 0;
        foreach ($comunicados as $com) {
            if ($com['lido'] == 0) {
                $naoLidos++;
            }
        }
        return $naoLidos;
    }
    public function delete($id, $criado_por) {
        // First check if user is the creator
        $stmt = $this->db->prepare('SELECT id FROM comunicados WHERE id = :id AND criado_por = :criado_por');
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':criado_por', $criado_por);
        $stmt->execute();
        if (!$stmt->fetch()) {
            return false;
        }

        // Excluir registros de leitura antes para evitar violação de chave estrangeira
        $stmtLeitura = $this->db->prepare('DELETE FROM leitura_comunicados WHERE comunicado_id = :id');
        $stmtLeitura->bindValue(':id', $id);
        $stmtLeitura->execute();
        
        $stmtDel = $this->db->prepare('DELETE FROM comunicados WHERE id = :id AND criado_por = :criado_por');
        $stmtDel->bindValue(':id', $id);
        $stmtDel->bindValue(':criado_por', $criado_por);
        return $stmtDel->execute();
    }

    public function excluirParaUtilizador($utilizadorId, $comunicadoId) {
        $stmt = $this->db->prepare('INSERT IGNORE INTO leitura_comunicados_excluidos (utilizador_id, comunicado_id) VALUES (:utilizador_id, :comunicado_id)');
        $stmt->bindValue(':utilizador_id', $utilizadorId);
        $stmt->bindValue(':comunicado_id', $comunicadoId);
        return $stmt->execute();
    }
}
