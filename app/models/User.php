<?php
class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM utilizadores WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM utilizadores WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function updateLastAccess($id) {
        $stmt = $this->db->prepare("UPDATE utilizadores SET ultimo_acesso = NOW() WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function insertUser($nome, $email, $senha, $tipo) {
        $check = $this->findByEmail($email);
        if ($check) return false;
        
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO utilizadores (nome_completo, email, senha, tipo, status) VALUES (:nome, :email, :senha, :tipo, 'ativo')");
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':senha', $hash);
        $stmt->bindValue(':tipo', $tipo);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function confirmEmail($token) {
        $stmt = $this->db->prepare("UPDATE utilizadores SET status = 'ativo', token_confirmacao = NULL WHERE token_confirmacao = :token AND status = 'pendente'");
        $stmt->bindValue(':token', $token);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function incrementLoginAttempts($id) {
        // Bloquear se tentativas >= 5 (por 15 minutos)
        $stmt = $this->db->prepare("UPDATE utilizadores SET tentativas_login = tentativas_login + 1, bloqueado_ate = CASE WHEN tentativas_login + 1 >= 5 THEN DATE_ADD(NOW(), INTERVAL 15 MINUTE) ELSE bloqueado_ate END WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function resetLoginAttempts($id) {
        $stmt = $this->db->prepare("UPDATE utilizadores SET tentativas_login = 0, bloqueado_ate = NULL WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function isAccountLocked($user) {
        if (!empty($user['bloqueado_ate'])) {
            $bloqueio = strtotime($user['bloqueado_ate']);
            if (time() < $bloqueio) {
                return true; // Ainda bloqueado
            } else {
                // Já passou o tempo de bloqueio, resetar as tentativas
                $this->resetLoginAttempts($user['id']);
                return false;
            }
        }
        return false;
    }

    public function set2FACode($id, $code) {
        // Expira em 10 minutos
        $stmt = $this->db->prepare("UPDATE utilizadores SET codigo_2fa = :code, expiracao_2fa = DATE_ADD(NOW(), INTERVAL 10 MINUTE) WHERE id = :id");
        $stmt->bindValue(':code', $code);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function verify2FACode($id, $code) {
        $stmt = $this->db->prepare("SELECT id FROM utilizadores WHERE id = :id AND codigo_2fa = :code AND expiracao_2fa > NOW()");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':code', $code);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            // Sucesso, limpar 2FA
            $cleanStmt = $this->db->prepare("UPDATE utilizadores SET codigo_2fa = NULL, expiracao_2fa = NULL WHERE id = :id");
            $cleanStmt->bindValue(':id', $id, PDO::PARAM_INT);
            $cleanStmt->execute();
            return true;
        }
        return false;
    }

    public function setRecoveryToken($email, $token) {
        // Expira em 1 hora
        $stmt = $this->db->prepare("UPDATE utilizadores SET token_recuperacao = :token, token_expira = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = :email");
        $stmt->bindValue(':token', $token);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function resetPassword($token, $newPassword) {
        $stmt = $this->db->prepare("SELECT id FROM utilizadores WHERE token_recuperacao = :token AND token_expira > NOW()");
        $stmt->bindValue(':token', $token);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            $hash = password_hash($newPassword, PASSWORD_BCRYPT);
            $updateStmt = $this->db->prepare("UPDATE utilizadores SET senha = :senha, token_recuperacao = NULL, token_expira = NULL WHERE id = :id");
            $updateStmt->bindValue(':senha', $hash);
            $updateStmt->bindValue(':id', $user['id'], PDO::PARAM_INT);
            $updateStmt->execute();
            return true;
        }
        return false;
    }
    public function updateUser($id, $data) {
        $sql = "UPDATE utilizadores SET nome_completo = :nome, email = :email";
        if (!empty($data['senha'])) {
            $sql .= ", senha = :senha";
        }
        $sql .= " WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nome', $data['nome_completo']);
        $stmt->bindValue(':email', $data['email']);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        if (!empty($data['senha'])) {
            $hash = password_hash($data['senha'], PASSWORD_BCRYPT);
            $stmt->bindValue(':senha', $hash);
        }
        
        return $stmt->execute();
    }

    public function updatePassword($id, $newPassword) {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE utilizadores SET senha = :senha, requires_pw_change = 0 WHERE id = :id");
        $stmt->bindValue(':senha', $hash);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function deleteUser($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM utilizadores WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getPendingUsers() {
        $stmt = $this->db->prepare("SELECT * FROM utilizadores WHERE status = 'pendente' ORDER BY data_criacao DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
