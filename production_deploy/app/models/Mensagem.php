<?php
/**
 * Modelo Mensagem
 * 
 * @package Models
 * @author Senior Software Engineer / Mentor
 * 
 * Documentação Funcional:
 * Este modelo é o motor de comunicação do sistema Green. Ele gere desde mensagens privadas 
 * entre utilizadores até notificações automáticas disparadas por eventos de negócio 
 * (ex: aprovação de matrícula).
 */
class Mensagem {
    /** @var PDO Instância da conexão com a base de dados */
    private $db;

    public function __construct() {
        // Inicializa a conexão com a base de dados via Singleton para economia de recursos.
        $this->db = Database::getInstance();
    }

    /**
     * Envia uma mensagem individual.
     * 
     * @param int $remetente_id ID do utilizador que envia (0 para SISTEMA).
     * @param int $destinatario_id ID do utilizador que recebe.
     * @param string $assunto Título da mensagem.
     * @param string $conteudo Corpo da mensagem.
     * @return bool Sucesso ou falha na inserção.
     */
    public function send($remetente_id, $destinatario_id, $assunto, $conteudo) {
        // // Análise de Performance: Uso de Prepared Statements previne SQL Injection.
        $stmt = $this->db->prepare("INSERT INTO mensagens (remetente_id, destinatario_id, assunto, mensagem, data_criacao, lida) 
                                   VALUES (:rem, :dest, :ass, :cont, NOW(), 0)");
        return $stmt->execute([
            ':rem' => $remetente_id,
            ':dest' => $destinatario_id,
            ':ass' => $assunto,
            ':cont' => $conteudo
        ]);
    }

    /**
     * Notifica todos os utilizadores de um determinado grupo/role.
     * 
     * @param string $targetRole Papel alvo (ex: 'admin', 'secretaria').
     * @param string $content Conteúdo do alerta.
     * @param int $senderId ID do remetente (padrão 0 para Sistema).
     * @return bool Retorna true se todos foram processados, embora falhas individuais não parem o loop.
     */
    public function notifyGroup($targetRole, $content, $senderId = 0) {
        // Busca todos os utilizadores ativos do papel pretendido
        $stmt = $this->db->prepare("SELECT id FROM utilizadores WHERE tipo = :role AND status = 'ativo'");
        $stmt->execute([':role' => $targetRole]);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $success = true;
        
        // // Refatoração / Performance: O uso de loop para múltiplos INSERTs pode causar gargalos 
        // // se o grupo tiver centenas de membros (ex: Alunos).
        // // Sugestão: Construir um único INSERT com múltiplos VALUES() ou usar transações para otimizar o IO do disco.
        foreach ($users as $u) {
            if (!$this->send($senderId, $u['id'], "Notificação de Sistema", $content)) {
                $success = false;
            }
        }
        return $success;
    }

    /**
     * Recupera o histórico completo de mensagens recebidas.
     * 
     * @param int $userId ID do destinatário.
     * @return array Lista de mensagens com nomes dos remetentes.
     * 
     * // Identificação de Pontos Cegos: Falta de limite (LIMIT). 
     * // Se um utilizador tiver 5000 mensagens, esta consulta consumirá muita memória RAM do PHP.
     * // Sugestão: Implementar Paginação (LIMIT/OFFSET).
     */
    public function getReceivedMessages($userId, $page = 1, $limit = 20) {
        $offset = ($page - 1) * $limit;
        // // Auditoria (Pilar 4): Implementada Paginação para evitar estouro de memória.
        $stmt = $this->db->prepare("SELECT m.*, u.nome_completo as remetente_nome 
                                   FROM mensagens m 
                                   JOIN utilizadores u ON m.remetente_id = u.id 
                                   WHERE m.destinatario_id = :id 
                                   ORDER BY m.data_criacao DESC 
                                   LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countReceived($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM mensagens WHERE destinatario_id = :id");
        $stmt->execute([':id' => $userId]);
        return $stmt->fetchColumn();
    }

    /**
     * Recupera apenas alertas pendentes (não lidos) para o widget do Dashboard.
     */
    public function getUnreadMessages($userId) {
        // // Clean Code: Separação de responsabilidades. Mantém o dashboard leve carregando apenas o necessário.
        $stmt = $this->db->prepare("SELECT m.*, u.nome_completo as remetente_nome 
                                   FROM mensagens m 
                                   JOIN utilizadores u ON m.remetente_id = u.id 
                                   WHERE m.destinatario_id = :id AND m.lida = 0
                                   ORDER BY m.data_criacao DESC");
        $stmt->execute([':id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Marca todas as mensagens de um utilizador como lidas (Arquivamento lógico).
     */
    public function markAllAsRead($userId) {
        // // Auditoria: NOW() regista o momento exato da leitura.
        $stmt = $this->db->prepare("UPDATE mensagens SET lida = 1, data_leitura = NOW() 
                                   WHERE destinatario_id = :id AND lida = 0");
        return $stmt->execute([':id' => $userId]);
    }
}
