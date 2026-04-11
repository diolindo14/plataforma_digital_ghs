<?php
/**
 * Modelo User (Utilizador) - Corrigido com todos os métodos de Segurança e Gestão.
 */
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

    public function insertUser($nome, $email, $senha, $tipo, $status = 'pendente') {
        // Se o email não for vazio, verificamos se já está em uso
        if (!empty($email)) {
            $check = $this->findByEmail($email);
            if ($check) return false;
        }
        
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO utilizadores (nome_completo, email, senha, tipo, status) VALUES (:nome, :email, :senha, :tipo, :status)");
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':email', $email); // Pode ser vazio ou NULL
        $stmt->bindValue(':senha', $hash);
        $stmt->bindValue(':tipo', $tipo);
        $stmt->bindValue(':status', $status);
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function updateUser($id, $data) {
        $fields = [];
        if (array_key_exists('nome_completo', $data)) $fields[] = "nome_completo = :nome";
        if (array_key_exists('email', $data)) {
            // Verificação de unicidade apenas se o email for alterado e não estiver vazio
            if (!empty($data['email'])) {
                $existing = $this->findByEmail($data['email']);
                if ($existing && $existing['id'] != $id) return false;
            }
            $fields[] = "email = :email";
        }
        if (array_key_exists('status', $data)) $fields[] = "status = :status";
        if (!empty($data['senha'])) $fields[] = "senha = :senha";

        if (empty($fields)) return true;
        $sql = "UPDATE utilizadores SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        if (array_key_exists('nome_completo', $data)) $stmt->bindValue(':nome', $data['nome_completo']);
        if (array_key_exists('email', $data)) $stmt->bindValue(':email', $data['email']);
        if (array_key_exists('status', $data)) $stmt->bindValue(':status', $data['status']);
        if (!empty($data['senha'])) $stmt->bindValue(':senha', password_hash($data['senha'], PASSWORD_DEFAULT));
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function deleteUser($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM utilizadores WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) { return false; }
    }

    public function registrarAcesso($userId) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido';
        $stmt = $this->db->prepare("INSERT INTO log_acessos (utilizador_id, ip_address, user_agent, data_acesso) VALUES (:uid, :ip, :ua, NOW())");
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':ip', $ip);
        $stmt->bindValue(':ua', $ua);
        return $stmt->execute();
    }

    public function getRecentAccesses($limit = 50) {
        $stmt = $this->db->prepare("SELECT la.*, u.nome_completo, u.tipo FROM log_acessos la JOIN utilizadores u ON la.utilizador_id = u.id ORDER BY la.data_acesso DESC LIMIT :limit");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function incrementLoginAttempts($id) {
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
            if (time() < $bloqueio) return true; 
            else {
                $this->resetLoginAttempts($user['id']);
                return false;
            }
        }
        return false;
    }

    public function set2FACode($id, $code) {
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
             $this->db->prepare("UPDATE utilizadores SET codigo_2fa = NULL, expiracao_2fa = NULL WHERE id = ?")->execute([$id]);
             return true;
        }
        return false;
    }

    public function setRecoveryToken($email, $token) {
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
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $this->db->prepare("UPDATE utilizadores SET senha = :senha, token_recuperacao = NULL, token_expira = NULL WHERE id = :id")->execute([':senha' => $hash, ':id' => $user['id']]);
            return true;
        }
        return false;
    }

    public function createStudentProfile($userId, $data) {
        $stmtCheck = $this->db->prepare("SELECT id FROM estudantes WHERE utilizador_id = :uid");
        $stmtCheck->execute([':uid' => $userId]);
        $exists = $stmtCheck->fetch();
        if ($exists) {
            $sql = "UPDATE estudantes SET bi = :bi, data_nascimento = :dn, telefone = :tel, morada = :morada, estado_civil = :ec, nome_encarregado = :en, telefone_encarregado = :et WHERE utilizador_id = :uid";
        } else {
            $sql = "INSERT INTO estudantes (utilizador_id, bi, data_nascimento, telefone, morada, estado_civil, nome_encarregado, telefone_encarregado) VALUES (:uid, :bi, :dn, :tel, :morada, :ec, :en, :et)";
        }
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':uid' => $userId, ':bi' => $data['bi'] ?? '', ':dn' => $data['data_nascimento'] ?? null, ':tel' => $data['telefone'] ?? '', ':morada' => $data['morada'] ?? '', ':ec' => $data['estado_civil'] ?? '', ':en' => $data['encarregado_nome'] ?? '', ':et' => $data['encarregado_telefone'] ?? ''
        ]);
    }

    public function getPendingUsers() {
        $stmt = $this->db->prepare("SELECT * FROM utilizadores WHERE status = 'pendente'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
