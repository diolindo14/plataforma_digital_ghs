<?php
$ftp_server = "ftpupload.net";
$ftp_user_name = "if0_41574650";
$ftp_user_pass = "0svEjAnMHnX";
$local_file = "C:/xampp/htdocs/green/apply_db_patch_v2.php";
$remote_file = "/htdocs/apply_db_patch_v2.php";

$conn_id = ftp_connect($ftp_server);
ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
ftp_pasv($conn_id, true);

if (ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY)) {
    echo "Upload OK\n";
} else {
    echo "Erro\n";
}
ftp_close($conn_id);
?>
