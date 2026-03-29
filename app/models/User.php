<?php
/**
 * Modelo User (Utilizador)
 * 
 * @package Models
 * @author Senior Software Engineer / Mentor
 * 
 * Documentação Funcional:
 * Este modelo é o pilar de segurança da aplicação. Gere a identidade dos utilizadores, 
 * processos de autenticação, recuperação de conta e segurança multi-fator (2FA).
 */
class User {
    /** @var PDO Conexão com a base de dados */
    private $db;

    public function __construct() {
        // // Refatoração: Database::getInstance() garante uma única conexão.
        $this->db = Database::getInstance();
    }

    /**
     * Localiza um utilizador pelo endereço de email.
     * 
     * @param string $email
     * @return array|false
     * 
     * // Análise de Performance: O campo 'email' deve ser indexado para consultas O(1).
     */
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM utilizadores WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Busca utilizador por ID primário.
     */
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM utilizadores WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Atualiza o timestamp do último login.
     */
    public function updateLastAccess($id) {
        $stmt = $this->db->prepare("UPDATE utilizadores SET ultimo_acesso = NOW() WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Regista um novo utilizador com hashing seguro.
     */
    public function insertUser($nome, $email, $senha, $tipo, $status = 'pendente') {
        $check = $this->findByEmail($email);
        if ($check) return false;
        
        // // Segurança: BCrypt é o padrão ouro atual para passwords.
        $hash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare("INSERT INTO utilizadores (nome_completo, email, senha, tipo, status) VALUES (:nome, :email, :senha, :tipo, :status)");
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':senha', $hash);
        $stmt->bindValue(':tipo', $tipo);
        $stmt->bindValue(':status', $status);
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Confirmação de conta via Token de Email.
     */
    public function confirmEmail($token) {
        $stmt = $this->db->prepare("UPDATE utilizadores SET status = 'ativo', token_confirmacao = NULL WHERE token_confirmacao = :token AND status = 'pendente'");
        $stmt->bindValue(':token', $token);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Bloqueio Progressivo: Incrementa falhas de login.
     */
    public function incrementLoginAttempts($id) {
        $stmt = $this->db->prepare("UPDATE utilizadores SET tentativas_login = tentativas_login + 1, bloqueado_ate = CASE WHEN tentativas_login + 1 >= 5 THEN DATE_ADD(NOW(), INTERVAL 15 MINUTE) ELSE bloqueado_ate END WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Limpa o contador de tentativas após login bem-sucedido.
     */
    public function resetLoginAttempts($id) {
        $stmt = $this->db->prepare("UPDATE utilizadores SET tentativas_login = 0, bloqueado_ate = NULL WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Verifica restrição temporal de acesso (Anti-Brute Force).
     */
    public function isAccountLocked($user) {
        if (!empty($user['bloqueado_ate'])) {
            $bloqueio = strtotime($user['bloqueado_ate']);
            if (time() < $bloqueio) {
                return true; 
            } else {
                $this->resetLoginAttempts($user['id']);
                return false;
            }
        }
        return false;
    }

    /**
     * Define código 2FA temporário.
     */
    public function set2FACode($id, $code) {
        $stmt = $this->db->prepare("UPDATE utilizadores SET codigo_2fa = :code, expiracao_2fa = DATE_ADD(NOW(), INTERVAL 10 MINUTE) WHERE id = :id");
        $stmt->bindValue(':code', $code);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Valida código 2FA dentro da janela de tempo.
     */
    public function verify2FACode($id, $code) {
        $stmt = $this->db->prepare("SELECT id FROM utilizadores WHERE id = :id AND codigo_2fa = :code AND expiracao_2fa > NOW()");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':code', $code);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            $cleanStmt = $this->db->prepare("UPDATE utilizadores SET codigo_2fa = NULL, expiracao_2fa = NULL WHERE id = :id");
            $cleanStmt->bindValue(':id', $id, PDO::PARAM_INT);
            $cleanStmt->execute();
            return true;
        }
        return false;
    }

    /**
     * Gera token de recuperação de password.
     */
    public function setRecoveryToken($email, $token) {
        $stmt = $this->db->prepare("UPDATE utilizadores SET token_recuperacao = :token, token_expira = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = :email");
        $stmt->bindValue(':token', $token);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Efetiva a troca de password via Token.
     */
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

    /**
     * Edição de perfil (Dinâmica).
     */
    public function updateUser($id, $data) {
        $fields = [];
        if (array_key_exists('nome_completo', $data)) $fields[] = "nome_completo = :nome";
        if (array_key_exists('email', $data)) $fields[] = "email = :email";
        if (array_key_exists('status', $data)) $fields[] = "status = :status";
        if (!empty($data['senha'])) $fields[] = "senha = :senha";

        if (empty($fields)) return true;

        $sql = "UPDATE utilizadores SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        
        if (array_key_exists('nome_completo', $data)) $stmt->bindValue(':nome', $data['nome_completo']);
        if (array_key_exists('email', $data)) $stmt->bindValue(':email', $data['email']);
        if (array_key_exists('status', $data)) $stmt->bindValue(':status', $data['status']);
        if (!empty($data['senha'])) {
            $stmt->bindValue(':senha', password_hash($data['senha'], PASSWORD_BCRYPT));
        }
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Atualização forçada de password por ID.
     */
    public function updatePassword($id, $newPassword) {
        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE utilizadores SET senha = :senha, requires_pw_change = 0 WHERE id = :id");
        $stmt->bindValue(':senha', $hash);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Remoção (Auditável).
     */
    public function deleteUser($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM utilizadores WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Regista um acesso (login) na tabela de auditoria.
     */
    public function registrarAcesso($userId) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido';
        
        $stmt = $this->db->prepare("INSERT INTO log_acessos (utilizador_id, ip_address, user_agent, data_acesso) VALUES (:uid, :ip, :ua, NOW())");
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':ip', $ip);
        $stmt->bindValue(':ua', $ua);
        return $stmt->execute();
    }

    /**
     * Recupera os logs de acesso recentes para o painel admin.
     */
    public function getRecentAccesses($limit = 50) {
        $stmt = $this->db->prepare("
            SELECT la.*, u.nome_completo, u.tipo 
            FROM log_acessos la
            JOIN utilizadores u ON la.utilizador_id = u.id
            ORDER BY la.data_acesso DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lista novos registos aguardando aprovação.
     */
    public function getPendingUsers() {
        $stmt = $this->db->prepare("SELECT * FROM utilizadores WHERE status = 'pendente' ORDER BY data_criacao DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
