<?php
require_once 'core/Config.php';
require_once 'core/Database.php';
$db = Database::getInstance();
$email = 'diretor_ghs@gmail.com';
$hash = password_hash('samba_ghs@2007', PASSWORD_DEFAULT);
$stmt = $db->prepare("UPDATE utilizadores SET email = ?, senha = ?, status = 'ativo' WHERE tipo = 'admin'");
$stmt->execute([$email, $hash]);
if ($stmt->rowCount() > 0) echo "Sucesso: Admin atualizado.";
else {
    $stmt = $db->prepare("INSERT INTO utilizadores (nome_completo, email, senha, tipo, status) VALUES (?, ?, ?, 'admin', 'ativo')");
    $stmt->execute(['Diretor Geral', $email, $hash]);
    echo "Sucesso: Novo admin criado.";
}
