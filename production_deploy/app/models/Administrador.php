<?php
class Administrador {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }
    /**
     * Recupera logs de auditoria do sistema (Pilar 7).
     */
    public function getLogs($page = 1, $limit = 50) {
        $offset = ($page - 1) * $limit;
        $stmt = $this->db->prepare("SELECT * FROM auditoria ORDER BY data_acao DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countLogs() {
        return $this->db->query("SELECT COUNT(*) FROM auditoria")->fetchColumn();
    }

    public function getAllSecretarios() {
        $stmt = $this->db->prepare("
            SELECT a.*, u.nome_completo, u.email, u.status as user_status
            FROM administradores a 
            JOIN utilizadores u ON a.utilizador_id = u.id 
            WHERE a.cargo = 'Secretaria'
            ORDER BY u.nome_completo ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createSecretaria($data) {
        try {
            $this->db->beginTransaction();
            
            // 1. Criar Utilizador
            $stmt = $this->db->prepare("INSERT INTO utilizadores (nome_completo, email, senha, tipo, status) VALUES (:nome, :email, :senha, 'secretaria', 'ativo')");
            $senhaHash = password_hash($data['senha'] ?? '123456', PASSWORD_DEFAULT);
            $stmt->execute([
                ':nome' => $data['nome'],
                ':email' => $data['email'],
                ':senha' => $senhaHash
            ]);
            $userId = $this->db->lastInsertId();
            
            // 2. Criar Perfil Administrador (Cargo: Secretaria)
            $stmt = $this->db->prepare("INSERT INTO administradores (utilizador_id, cargo, bi, telefone, data_contratacao) VALUES (:uid, 'Secretaria', :bi, :tel, :data_con)");
            $stmt->execute([
                ':uid' => $userId,
                ':bi' => $data['bi'] ?? 'N/A',
                ':tel' => $data['telefone'] ?? '000000000',
                ':data_con' => $data['data_contratacao'] ?? date('Y-m-d')
            ]);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            if($this->db->inTransaction()){
                $this->db->rollBack();
            }
            return false;
        }
    }

    public function getSecretariaById($id) {
        $stmt = $this->db->prepare("
            SELECT a.*, u.nome_completo, u.email, u.status as user_status
            FROM administradores a 
            JOIN utilizadores u ON a.utilizador_id = u.id 
            WHERE a.id = :id AND a.cargo = 'Secretaria'
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function updateSecretaria($id, $data) {
        try {
            $this->db->beginTransaction();
            
            // 1. Obter utilizador_id
            $stmt = $this->db->prepare("SELECT utilizador_id FROM administradores WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $uid = $stmt->fetchColumn();

            // 2. Atualizar utilizador
            $sqlU = "UPDATE utilizadores SET nome_completo = :nome, email = :email";
            $paramsU = [':nome' => $data['nome'], ':email' => $data['email'], ':uid' => $uid];
            if (!empty($data['senha'])) {
                $sqlU .= ", senha = :senha";
                $paramsU[':senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
            }
            $sqlU .= " WHERE id = :uid";
            $this->db->prepare($sqlU)->execute($paramsU);

            // 3. Atualizar Perfil Admin
            $stmtA = $this->db->prepare("UPDATE administradores SET bi = :bi, telefone = :tel, data_contratacao = :data_con WHERE id = :id");
            $stmtA->execute([
                ':bi' => $data['bi'],
                ':tel' => $data['telefone'],
                ':data_con' => $data['data_contratacao'],
                ':id' => $id
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function deleteSecretaria($id) {
        try {
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare("SELECT utilizador_id FROM administradores WHERE id = :id AND cargo = 'Secretaria'");
            $stmt->execute([':id' => $id]);
            $admin = $stmt->fetch();
            
            if ($admin) {
                // Delete dependente
                $stmtDelA = $this->db->prepare("DELETE FROM administradores WHERE id = :id");
                $stmtDelA->execute([':id' => $id]);
                
                $stmtDelU = $this->db->prepare("DELETE FROM utilizadores WHERE id = :uid");
                $stmtDelU->execute([':uid' => $admin['utilizador_id']]);
                
                $this->db->commit();
                return true;
            }
            $this->db->rollBack();
            return false;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
