import ftplib
import io

FTP_HOST = 'ftpupload.net'
FTP_USER = 'if0_41574650'
FTP_PASS = '0svEjAnMHnX'

php_code = """<?php
require_once 'core/config.php';
require_once 'core/Database.php';

try {
    $db = Database::getInstance();
    $hash = '$2y$10$Z2gZWA9tC9q1JEkOGbtz1u3BqOTc1B6X3.JqabWipEBXADqIotyL.';
    
    // Tentar atualizar
    $stmt = $db->prepare("UPDATE utilizadores SET email='diretor_ghs@gmail.com', senha=? WHERE tipo='admin'");
    $stmt->execute([$hash]);

    echo "<div style='font-family:sans-serif; text-align:center; margin-top:50px;'>";
    echo "<h1 style='color:green;'>CREDENCIAS ATUALIZADAS!</h1>";
    echo "<p>Agora use:<br>E-mail: <b>diretor_ghs@gmail.com</b><br>Senha: <b>samba_ghs@2007</b></p>";
    echo "<a href='/'>Ir para o Login</a>";
    echo "</div>";

} catch (Exception $e) {
    echo "<h1>Erro Técnico</h1>: " . $e->getMessage();
}
@unlink(__FILE__);
"""

try:
    ftp = ftplib.FTP(FTP_HOST)
    ftp.login(FTP_USER, FTP_PASS)
    ftp.cwd('/htdocs')
    ftp.storbinary('STOR reset_admin.php', io.BytesIO(php_code.encode('utf-8')))
    ftp.quit()
    print("Sucesso!")
except Exception as e:
    print("ERRO:", e)
