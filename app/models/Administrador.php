<?php
class Administrador {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
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
