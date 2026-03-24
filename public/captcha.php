<?php
session_start();
ob_clean(); // Limpar qualquer saída anterior que possa corromper o header do PNG
ob_start();

// Verificar se GD está instalada
if (!extension_loaded('gd')) {
    error_log("Erro CAPTCHA: Extensão GD não está carregada.");
    exit("GD Library required");
}

// Gerar código aleatório (6 caracteres)
$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
$code = '';
for ($i = 0; $i < 6; $i++) {
    $code .= $chars[mt_rand(0, strlen($chars) - 1)];
}

// Armazenar hash na sessão
$_SESSION['captcha_secret'] = hash('sha256', strtolower($code));

header('Content-Type: image/png');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

// Configurar imagem
$width = 160;
$height = 50;
$image = imagecreatetruecolor($width, $height);

if (!$image) {
    error_log("Erro CAPTCHA: Falha ao criar true color image.");
    exit;
}

// Cores
$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 0, 0, 0);
$gray = imagecolorallocate($image, 200, 200, 200);

// Fundo
imagefilledrectangle($image, 0, 0, $width, $height, $white);

// Adicionar ruído (pontos)
for ($i = 0; $i < 100; $i++) {
    $noise_color = imagecolorallocate($image, mt_rand(150, 220), mt_rand(150, 220), mt_rand(150, 220));
    imagesetpixel($image, mt_rand(0, $width), mt_rand(0, $height), $noise_color);
}

// Adicionar ruído (linhas)
for ($i = 0; $i < 5; $i++) {
    $line_color = imagecolorallocate($image, mt_rand(180, 230), mt_rand(180, 230), mt_rand(180, 230));
    imageline($image, mt_rand(0, $width), mt_rand(0, $height), mt_rand(0, $width), mt_rand(0, $height), $line_color);
}

// Fontes TTF Sugeridas (Caminhos absolutos comuns no Windows)
$fontCandidates = [
    'C:\Windows\Fonts\arial.ttf',
    'C:\Windows\Fonts\tahoma.ttf',
    'C:\Windows\Fonts\verdana.ttf'
];

$font = null;
foreach ($fontCandidates as $candidate) {
    if (file_exists($candidate)) {
        $font = $candidate;
        break;
    }
}

if (!$font || !function_exists('imagettftext')) {
    // Fallback para fontes nativas se TTF falhar ou não houver suporte
    $char_width = $width / 6;
    for ($i = 0; $i < 6; $i++) {
        $char_color = imagecolorallocate($image, mt_rand(10, 80), mt_rand(10, 80), mt_rand(10, 80));
        imagestring($image, 5, 10 + ($i * 24), 18, $code[$i], $char_color);
    }
} else {
    // Desenhar caracteres individualmente com distorção
    for ($i = 0; $i < 6; $i++) {
        $char_color = imagecolorallocate($image, mt_rand(10, 80), mt_rand(10, 80), mt_rand(10, 80));
        $angle = mt_rand(-20, 20);
        $size = mt_rand(18, 22);
        $x = 15 + ($i * 24);
        $y = 35;
        try {
            imagettftext($image, $size, $angle, $x, $y, $char_color, $font, $code[$i]);
        } catch (Throwable $e) {
            imagestring($image, 5, $x, 18, $code[$i], $char_color);
        }
    }
}

// Output
header('Content-Type: image/png');
header('Cache-Control: no-cache, must-revalidate');
imagepng($image);
imagedestroy($image);
