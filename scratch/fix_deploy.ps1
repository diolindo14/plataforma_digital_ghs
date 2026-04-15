$ftp_user = "if0_41574650"
$ftp_pass = "0svEjAnMHnX"
$ftp_host = "ftpupload.net"
$local_base = "c:\xampp\htdocs\green\production_deploy"

$files = @(
    "app/views/estudante/dashboard.php",
    "app/models/Horario.php",
    "app/controllers/EstudanteController.php"
)

$creds = New-Object System.Net.NetworkCredential($ftp_user, $ftp_pass)

foreach ($f in $files) {
    $local = Join-Path $local_base ($f.Replace("/", "\"))
    $remote = "ftp://$ftp_host/htdocs/$f"
    
    try {
        $req = [System.Net.FtpWebRequest]::Create($remote)
        $req.Method = [System.Net.WebRequestMethods+Ftp]::UploadFile
        $req.Credentials = $creds
        $req.UsePassive = $true
        
        $bytes = [System.IO.File]::ReadAllBytes($local)
        $req.ContentLength = $bytes.Length
        
        $rs = $req.GetRequestStream()
        $rs.Write($bytes, 0, $bytes.Length)
        $rs.Close()
        
        $res = $req.GetResponse()
        $res.Close()
        Write-Host "SUCCESS: $f"
    } catch {
        Write-Host "ERROR: $f - $($_.Exception.Message)"
    }
}
