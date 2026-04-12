<?php
$ftp_server = "ftpupload.net";
$ftp_user_name = "if0_41574650";
$ftp_user_pass = "0svEjAnMHnX";
$local_dir = "C:/xampp/htdocs/green/production_deploy/";
$remote_dir = "/htdocs/"; // InfinityFree default folder

echo "Conectando ao FTP $ftp_server...\n";
$conn_id = ftp_connect($ftp_server) or die("Não foi possível conectar ao $ftp_server");
$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

if (!$login_result) {
    die("Falha no login FTP!");
}

ftp_pasv($conn_id, true);
echo "Conectado com sucesso!\n";

function ftp_sync($conn_id, $local_dir, $remote_dir) {
    echo "Sincronizando: $local_dir -> $remote_dir\n";
    if(!@ftp_chdir($conn_id, $remote_dir)) {
        ftp_mkdir($conn_id, $remote_dir);
    }
    
    $d = dir($local_dir);
    while($file = $d->read()) {
        if ($file != "." && $file != "..") {
            $local_file = $local_dir . '/' . $file;
            $remote_file = $remote_dir . '/' . $file;
            
            if (is_dir($local_file)) {
                ftp_sync($conn_id, $local_file, $remote_file);
            } else {
                // Upload ficheiro
                if (ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY)) {
                    echo "✓ Upload: $local_file\n";
                } else {
                    echo "❌ Erro ao enviar: $local_file\n";
                }
            }
        }
    }
    $d->close();
}

ftp_sync($conn_id, $local_dir, $remote_dir);
ftp_close($conn_id);
echo "Upload concluído!\n";
