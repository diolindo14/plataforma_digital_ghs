<?php
$ftp_server = "ftpupload.net";
$ftp_user_name = "if0_41574650";
$ftp_user_pass = "0svEjAnMHnX";

$conn_id = ftp_connect($ftp_server) or die("Não foi possível conectar");
ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
ftp_pasv($conn_id, true);

// Upload .htaccess
$htaccess_loc = "C:/xampp/htdocs/green/production_deploy/.htaccess";
$htaccess_rem = "/htdocs/.htaccess";
if (ftp_put($conn_id, $htaccess_rem, $htaccess_loc, FTP_ASCII)) {
    echo ".htaccess OK\n";
} else {
    echo ".htaccess Erro\n";
}

// Upload config.php
$config_loc = "C:/xampp/htdocs/green/production_deploy/core/config.php";
$config_rem = "/htdocs/core/config.php";
if (ftp_put($conn_id, $config_rem, $config_loc, FTP_BINARY)) {
    echo "config.php OK\n";
} else {
    echo "config.php Erro\n";
}

ftp_close($conn_id);
?>
