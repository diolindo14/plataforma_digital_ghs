<?php
$ftp_server = "ftpupload.net";
$ftp_user_name = "if0_41574650";
$ftp_user_pass = "0svEjAnMHnX";
$local_file = "C:/xampp/htdocs/green/db_deep_search.php";
$remote_file = "/htdocs/db_deep_search.php";

$conn_id = ftp_connect($ftp_server);
ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
ftp_pasv($conn_id, true);
ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY);
ftp_close($conn_id);
?>
