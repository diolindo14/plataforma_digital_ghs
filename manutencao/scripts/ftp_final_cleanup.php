<?php
$ftp_server = "ftpupload.net";
$ftp_user_name = "if0_41574650";
$ftp_user_pass = "0svEjAnMHnX";

$conn_id = ftp_connect($ftp_server);
ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
ftp_pasv($conn_id, true);

@ftp_delete($conn_id, "/htdocs/db_inspect.php");
@ftp_delete($conn_id, "/htdocs/apply_db_patch_v2.php");
@ftp_delete($conn_id, "/htdocs/purge_redes.php");
ftp_close($conn_id);
echo "Cleanup done.";
?>
