<?php
$ftp_server = "ftpupload.net";
$ftp_user_name = "if0_41574650";
$ftp_user_pass = "0svEjAnMHnX";

$conn_id = ftp_connect($ftp_server) or die("Não foi possível conectar");
ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
ftp_pasv($conn_id, true);

$files = [
    "app/views/estudante/dashboard.php" => "/htdocs/app/views/estudante/dashboard.php",
    "app/views/professor/dashboard.php" => "/htdocs/app/views/professor/dashboard.php"
];

foreach ($files as $local => $remote) {
    if (ftp_put($conn_id, $remote, "C:/xampp/htdocs/green/$local", FTP_BINARY)) {
        echo "✓ Upload: $local\n";
    } else {
        echo "❌ Erro ao enviar: $local\n";
    }
}

ftp_close($conn_id);
?>
