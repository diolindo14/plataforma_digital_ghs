<?php
class Database {
    private static $instance = null;
    private $pdo;

    private $host = '127.0.0.1';
    private $db   = 'ghsespf_db';
    private $user = 'root';
    private $pass = ''; // XAMPP Default password
    private $charset = 'utf8mb4';

    private function __construct() {
        $dsn = "mysql:host=$this->host;dbname=$this->db;charset=$this->charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (\PDOException $e) {
            // Log do erro técnico de forma segura (fora do acesso público)
            $logFile = dirname(__DIR__) . '/app/logs/error.log';
            $message = "[" . date('Y-m-d H:i:s') . "] DB Connection Error: " . $e->getMessage() . PHP_EOL;
            error_log($message, 3, $logFile);

            // Exibir interface amigável para o utilizador
            if (file_exists(dirname(__DIR__) . '/public/error_500.php')) {
                include dirname(__DIR__) . '/public/error_500.php';
            } else {
                echo "Lamentamos, ocorreu um erro técnico. Por favor, tente mais tarde.";
            }
            exit;
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}
