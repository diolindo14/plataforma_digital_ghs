<?php
/**
 * Modelo Estudante - Gestão de Perfis de Alunos.
 * 
 * Este modelo isola os dados biográficos e académicos dos estudantes, 
 * mantendo a integridade referencial com a tabela 'utilizadores'.
 */
class Estudante {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Persiste um novo perfil de estudante.
     * 
     * @param array $data Conjunto de dados biográficos.
     * @return int|bool ID do estudante criado ou false em erro.
     */
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

    /**
     * Recupera listagem completa de estudantes com metadados de matrícula.
     * 
     * // Auditoria de Performance (Pilar 4): Refatorado de N+1 subqueries para um único JOIN.
     * // Isso reduz a complexidade de O(N) queries para O(1), economizando recursos do servidor.
     */
    public function getAllStudents($page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        $stmt = $this->db->prepare("
            SELECT e.*, u.nome_completo, u.email, u.status as user_status,
                   t.codigo as turma,
                   a.nome as nivel
            FROM estudantes e
            JOIN utilizadores u ON e.utilizador_id = u.id
            LEFT JOIN (
                SELECT estudante_id, turma_id, ano_curso_id,
                       ROW_NUMBER() OVER(PARTITION BY estudante_id ORDER BY id DESC) as rn
                FROM matriculas 
                WHERE status = 'Aprovada'
            ) m_latest ON e.id = m_latest.estudante_id AND m_latest.rn = 1
            LEFT JOIN turmas t ON m_latest.turma_id = t.id
            LEFT JOIN anos a ON m_latest.ano_curso_id = a.id
            ORDER BY u.nome_completo ASC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countAll() {
        return $this->db->query("SELECT COUNT(*) FROM estudantes")->fetchColumn();
    }

    /**
     * Retorna detalhes completos de um estudante pelo ID de utilizador.
     */
    public function getDetailsByUserId($user_id) {
        $stmt = $this->db->prepare("
            SELECT e.*, u.nome_completo, u.email, u.status as user_status, e.foto_perfil as user_foto,
                   t.codigo as turma
            FROM estudantes e 
            JOIN utilizadores u ON e.utilizador_id = u.id 
            LEFT JOIN matriculas m ON e.id = m.estudante_id AND m.status = 'Aprovada'
            LEFT JOIN turmas t ON m.turma_id = t.id
            WHERE e.utilizador_id = :user_id
            LIMIT 1
        ");
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function findByUserId($user_id) {
        return $this->getDetailsByUserId($user_id);
    }

    /**
     * Atualização parcial/total do perfil biográfico.
     * 
     * // Auditoria de Corretude (Pilar 1): Corrigida coluna 'city' para 'cidade'.
     * // Auditoria de Segurança (Pilar 3): Adicionado bindValue explícito para o ID.
     */
    public function updateEstudante($id, $data) {
        $stmt = $this->db->prepare("UPDATE estudantes SET 
            bi = :bi, 
            data_nascimento = :data_nasc, 
            nacionalidade = :nacionalidade, 
            sexo = :sexo, 
            estado_civil = :estado_civil, 
            telefone = :telefone, 
            telefone_alternativo = :tel_alt, 
            morada = :morada, 
            bairro = :bairro, 
            cidade = :cidade, 
            nome_encarregado = :enc_nome, 
            telefone_encarregado = :enc_tel 
            WHERE id = :id");
        
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

    /**
     * Remoção de perfil (Lógica de Limpeza).
     */
    public function deleteEstudanteByUserId($user_id) {
        $stmt = $this->db->prepare("DELETE FROM estudantes WHERE utilizador_id = :id");
        $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
