<?php
// Trying direct connection with 127.0.0.1 to avoid socket issues on Windows CLI
$host = '127.0.0.1';
$db   = 'ghsespf_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=3306";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     echo "Conectado com sucesso via 127.0.0.1\n";

     // Add contador_reclamacoes
     try {
         $pdo->exec("ALTER TABLE concordancia_notas ADD COLUMN contador_reclamacoes INT DEFAULT 1");
         echo "Coluna 'contador_reclamacoes' adicionada.\n";
     } catch (PDOException $e) {
         echo "Info: " . $e->getMessage() . "\n";
     }

     // Add bloqueado_admin
     try {
         $pdo->exec("ALTER TABLE concordancia_notas ADD COLUMN bloqueado_admin TINYINT(1) DEFAULT 0");
         echo "Coluna 'bloqueado_admin' adicionada.\n";
     } catch (PDOException $e) {
         echo "Info: " . $e->getMessage() . "\n";
     }

     echo "Operação concluída.";
} catch (\PDOException $e) {
     echo "Erro de conexão: " . $e->getMessage();
}
