<?php
/**
 * GHS Direct FTP Upload Script
 */
$host = 'ftpupload.net';
$user = 'if0_41574650';
$pass = '0svEjAnMHnX';

echo "Conectando ao host: $host...\n";
$conn = ftp_connect($host, 21, 10);
if (!$conn) {
    die("ERRO: Não foi possível conectar ao servidor FTP.\n");
}

echo "Autenticando usuário: $user...\n";
if (!@ftp_login($conn, $user, $pass)) {
    die("ERRO: Falha na autenticação FTP. Verifique o usuário e senha.\n");
}

ftp_pasv($conn, true); // Modo passivo para servidores InfinityFree

$files = [
    'app/views/estudante/dashboard.php' => '/htdocs/app/views/estudante/dashboard.php',
    'app/views/professor/dashboard.php'   => '/htdocs/app/views/professor/dashboard.php'
];

$success = 0;
foreach ($files as $localRel => $remote) {
    $local = 'c:/xampp/htdocs/green/' . $localRel;
    echo "Enviando $localRel...\n";
    
    if (ftp_put($conn, $remote, $local, FTP_BINARY)) {
        echo "✅ Sucesso: $remote atualizado.\n";
        $success++;
    } else {
        echo "❌ ERRO: Não foi possível enviar $localRel para $remote.\n";
    }
}

ftp_close($conn);
echo "\n--- Processo concluído: $success de " . count($files) . " arquivos enviados ---\n";
