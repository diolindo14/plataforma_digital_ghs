<?php
$ftp_server = "ftpupload.net";
$ftp_user = "if0_41574650";
$ftp_pass = "0svEjAnMHnX";

$conn_id = ftp_connect($ftp_server);
if (!$conn_id) die("Erro de FTP.\n");

ftp_login($conn_id, $ftp_user, $ftp_pass);
ftp_pasv($conn_id, true);

$files = [
    "app/views/estudante/dashboard.php"
];

foreach ($files as $file) {
    echo "Fazendo upload de $file...\n";
    if (ftp_put($conn_id, "htdocs/$file", $file, FTP_BINARY)) echo "OK\n"; else echo "ERRO\n";
}

ftp_close($conn_id);
echo "Upload concluído!\n";
