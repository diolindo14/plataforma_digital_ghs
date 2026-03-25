Add-Type -AssemblyName System.Drawing
$bmp = New-Object System.Drawing.Bitmap("c:\xampp\htdocs\green\img\4T1.bmp")
$bmp.Save("c:\xampp\htdocs\green\img\4T1.png", [System.Drawing.Imaging.ImageFormat]::Png)
$bmp.Dispose()
