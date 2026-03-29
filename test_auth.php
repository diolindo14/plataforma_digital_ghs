<?php
require_once 'app/core/Database.php';
require_once 'app/config/config.php';

// Simulação de inserção e verificação de senha
$password = 'Audit123!';
$hash_default = password_hash($password, PASSWORD_DEFAULT);
$hash_bcrypt  = password_hash($password, PASSWORD_BCRYPT);

echo "Teste de Password Hash:\n";
echo "Password: $password\n";
echo "Hash Default: $hash_default\n";
echo "Hash BCrypt:  $hash_bcrypt\n";

$verify_default = password_verify($password, $hash_default) ? "SUCESSO" : "FALHA";
$verify_bcrypt  = password_verify($password, $hash_bcrypt) ? "SUCESSO" : "FALHA";

echo "Verificação Default: $verify_default\n";
echo "Verificação BCrypt:  $verify_bcrypt\n";

// Teste de conexão com DB (apenas para garantir)
try {
    $db = Database::getInstance();
    echo "Conexão DB: SUCESSO\n";
} catch (Exception $e) {
    echo "Conexão DB: FALHA (" . $e->getMessage() . ")\n";
}
?>
