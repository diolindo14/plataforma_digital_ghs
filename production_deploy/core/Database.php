<?php
/**
 * Classe de Conexão com a Base de Dados (Padrão Singleton)
 * 
 * @package Core
 * @author Senior Software Engineer / Mentor
 * 
 * Documentação Funcional:
 * Esta classe é responsável por garantir que exista apenas UMA conexão ativa com a base de dados
 * durante todo o ciclo de vida da requisição (Padrão Singleton). Isso economiza recursos do servidor
 * e evita o estouro do limite de conexões do MySQL.
 */
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            // // Auditoria (Pilar 3): Credenciais agora vêm do config.php
            $this->pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            // Fail-soft: Regista o erro e morre graciosamente
            error_log("DB Error: " . $e->getMessage());
            die("Lamentamos, ocorreu um erro técnico de ligação à base de dados.");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}
