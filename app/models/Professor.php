<?php
/**
 * Modelo Professor - Gestão Docente e Atribuições Académicas.
 * 
 * Este modelo orquestra a relação entre o perfil docente, a conta de utilizador 
 * e as disciplinas vinculadas em turmas específicas.
 */
class Professor {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Listagem completa de professores com info de atribuições via GROUP_CONCAT.
     */
    public function getAllProfessors() {
        $stmt = $this->db->prepare("
            SELECT p.*, u.nome_completo, u.email, u.status as user_status,
                   (SELECT GROUP_CONCAT(CONCAT(t.codigo, ' - ', d.nome) SEPARATOR ' | ') 
                    FROM professor_disciplina pd
                    JOIN turmas t ON pd.turma_id = t.id
                    JOIN disciplinas d ON pd.disciplina_id = d.id
                    WHERE pd.professor_id = p.id) as atribuicoes_info
            FROM professores p 
            JOIN utilizadores u ON p.utilizador_id = u.id 
            ORDER BY u.nome_completo ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Processamento de Atualização em Fluxo Transacional (Atomicidade).
     * 
     * @param int $id ID do professor.
     * @param array $data Conjunto de dados para atualização.
     * @return bool Sucesso da operação.
     * 
     * // Mentoria: O uso de transacções (beginTransaction/commit) é vital 
     * // para evitar estados "orfãos" onde o utilizador muda mas o professor não.
     */
    public function updateManual($id, $data) {
        try {
            $this->db->beginTransaction();
            
            // 1. Obter utilizador_id
            $stmt = $this->db->prepare("SELECT utilizador_id FROM professores WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $prof = $stmt->fetch();
            $userId = $prof['utilizador_id'];

            // 2. Atualizar Utilizador
            $sqlU = "UPDATE utilizadores SET nome_completo = :nome, email = :email";
            $paramsU = [':nome' => $data['nome'], ':email' => $data['email'], ':uid' => $userId];
            if (!empty($data['senha'])) {
                $sqlU .= ", senha = :senha";
                $paramsU[':senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
            }
            $sqlU .= " WHERE id = :uid";
            $stmtU = $this->db->prepare($sqlU);
            $stmtU->execute($paramsU);

            // 3. Atualizar Perfil Professor
            $stmtP = $this->db->prepare("UPDATE professores SET bi = :bi, telefone = :tel, especialidade = :esp, grau_academico = :grau, data_contratacao = :data_con WHERE id = :id");
            $stmtP->execute([
                ':bi' => $data['bi'],
                ':tel' => $data['telefone'],
                ':esp' => $data['especialidade'],
                ':grau' => $data['grau_academico'] ?? null,
                ':data_con' => $data['data_contratacao'] ?? null,
                ':id' => $id
            ]);
            
            // 4. Reconstrução de Grade Curricular/Atribuições
            $stmtDel = $this->db->prepare("DELETE FROM professor_disciplina WHERE professor_id = :pid");
            $stmtDel->execute([':pid' => $id]);
            
            if (!empty($data['atribuicoes'])) {
                $stmtPD = $this->db->prepare("INSERT INTO professor_disciplina (professor_id, disciplina_id, turma_id, ano_letivo) VALUES (:pid, :did, :tid, :ano)");
                $ano = date('Y');
                foreach ($data['atribuicoes'] as $at) {
                    if (!empty($at['turma_id']) && !empty($at['disciplina_id'])) {
                        $stmtPD->execute([
                            ':pid' => $id,
                            ':did' => $at['disciplina_id'],
                            ':tid' => $at['turma_id'],
                            ':ano' => $ano
                        ]);
                    }
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Cadastro Integral de Docente com Segurança Atómica.
     */
    public function createManual($data) {
        try {
            $this->db->beginTransaction();
            
            // 1. Criar Utilizador
            $stmt = $this->db->prepare("INSERT INTO utilizadores (nome_completo, email, senha, tipo, status) VALUES (:nome, :email, :senha, 'professor', 'ativo')");
            $senhaHash = password_hash($data['senha'] ?? '123456', PASSWORD_DEFAULT);
            $stmt->execute([
                ':nome' => $data['nome'],
                ':email' => $data['email'],
                ':senha' => $senhaHash
            ]);
            $userId = $this->db->lastInsertId();
            
            // 2. Criar Perfil Professor
            $stmt = $this->db->prepare("INSERT INTO professores (utilizador_id, bi, telefone, especialidade, grau_academico, data_contratacao) VALUES (:uid, :bi, :tel, :esp, :grau, :data_con)");
            $stmt->execute([
                ':uid' => $userId,
                ':bi' => $data['bi'],
                ':tel' => $data['telefone'],
                ':esp' => $data['especialidade'] ?? '',
                ':grau' => $data['grau_academico'] ?? null,
                ':data_con' => $data['data_contratacao'] ?? date('Y-m-d')
            ]);
            $profId = $this->db->lastInsertId();
            
            // 3. Criar Alocações
            if (!empty($data['atribuicoes'])) {
                $stmtPD = $this->db->prepare("INSERT INTO professor_disciplina (professor_id, disciplina_id, turma_id, ano_letivo) VALUES (:pid, :did, :tid, :ano)");
                $ano = date('Y');
                foreach ($data['atribuicoes'] as $at) {
                    if (!empty($at['turma_id']) && !empty($at['disciplina_id'])) {
                        $stmtPD->execute([
                            ':pid' => $profId,
                            ':did' => $at['disciplina_id'],
                            ':tid' => $at['turma_id'],
                            ':ano' => $ano
                        ]);
                    }
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Retorna detalhes expandidos do docente pelo seu ID.
     */
    public function getDetails($id) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.nome_completo, u.email, u.status as user_status
            FROM professores p
            JOIN utilizadores u ON p.utilizador_id = u.id
            WHERE p.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $prof = $stmt->fetch();

        if ($prof) {
            $stmtPD = $this->db->prepare("
                SELECT pd.disciplina_id, pd.turma_id, t.codigo, t.turno 
                FROM professor_disciplina pd
                JOIN turmas t ON pd.turma_id = t.id
                WHERE pd.professor_id = :pid
            ");
            $stmtPD->execute([':pid' => $id]);
            $pds = $stmtPD->fetchAll();
            
            $prof['disciplinas_ids'] = array_column($pds, 'disciplina_id');
            $prof['turma_id'] = !empty($pds) ? $pds[0]['turma_id'] : null;
            $prof['turma_nome'] = !empty($pds) ? $pds[0]['codigo'] . ' - ' . $pds[0]['turno'] : null;
        }

        return $prof;
    }

    public function findById($id) {
        return $this->getDetails($id);
    }

    /**
     * Localiza perfil de professor a partir do ID do utilizador autenticado.
     */
    public function findByUserId($user_id) {
        $stmt = $this->db->prepare("
            SELECT p.*, u.nome_completo, u.email, u.status as user_status, p.foto_perfil as user_foto
            FROM professores p
            JOIN utilizadores u ON p.utilizador_id = u.id
            WHERE p.utilizador_id = :user_id
        ");
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetch();
    }

    /**
     * Retorna turmas e disciplinas vinculadas.
     */
    public function getAssignedClasses($professor_id) {
        $stmt = $this->db->prepare("
            SELECT pd.*, d.nome as disciplina_nome, t.codigo as turma_codigo, t.turno 
            FROM professor_disciplina pd
            JOIN disciplinas d ON pd.disciplina_id = d.id
            JOIN turmas t ON pd.turma_id = t.id
            WHERE pd.professor_id = :prof_id
        ");
        $stmt->bindValue(':prof_id', $professor_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Recupera listagem de alunos de uma turma vinculada ao professor.
     */
    public function getStudentsByTurma($turma_id) {
        $stmt = $this->db->prepare("
            SELECT e.*, u.nome_completo, m.grupo 
            FROM estudantes e
            JOIN utilizadores u ON e.utilizador_id = u.id
            JOIN matriculas m ON e.id = m.estudante_id
            WHERE m.turma_id = :turma_id AND m.status = 'Aprovada'
        ");
        $stmt->bindValue(':turma_id', $turma_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
