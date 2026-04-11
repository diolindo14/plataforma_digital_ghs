Add-Type -AssemblyName System.Drawing
$imgDir = "c:\xampp\htdocs\green\img"
$files = Get-ChildItem "$imgDir\*.bmp"
foreach ($file in $files) {
    $pngName = $file.FullName.Replace(".bmp", ".png")
    if (-not (Test-Path $pngName)) {
        $bmp = New-Object System.Drawing.Bitmap($file.FullName)
        $bmp.Save($pngName, [System.Drawing.Imaging.ImageFormat]::Png)
        $bmp.Dispose()
        Write-Host "Converted $($file.Name) to PNG."
    }
}
