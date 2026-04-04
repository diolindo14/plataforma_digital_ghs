import ftplib
import io

FTP_HOST = 'ftpupload.net'
FTP_USER = 'if0_41574650'
FTP_PASS = '0svEjAnMHnX'

php_code = """<?php
require_once 'core/Database.php';
$db = Database::getInstance();
$hash = '$2y$10$Z2gZWA9tC9q1JEkOGbtz1u3BqOTc1B6X3.JqabWipEBXADqIotyL.';
$stmt = $db->prepare("UPDATE utilizadores SET email='diretor_ghs@gmail.com', senha=:hash WHERE tipo='admin'");
$stmt->execute([':hash' => $hash]);
echo "<h1>CREDENCIAS ATUALIZADAS!</h1><p>Pode fechar esta aba e tentar fazer o login.</p>";
@unlink(__FILE__);
"""

ftp = ftplib.FTP(FTP_HOST)
ftp.login(FTP_USER, FTP_PASS)
ftp.cwd('/htdocs')
ftp.storbinary('STOR reset_admin.php', io.BytesIO(php_code.encode('utf-8')))
ftp.quit()
print("RESET FILE UPLOADED")
