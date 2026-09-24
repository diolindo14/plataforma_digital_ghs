<?php
/**
 * Helper de Ficheiros - Segurança e Validação (Pilar 3)
 */
class FileHelper {
    /**
     * Valida e executa o upload de um ficheiro.
     */
    public static function upload($file, $destination, $allowedTypes = ALLOWED_EXTENSIONS) {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Nenhum ficheiro enviado ou erro no upload.'];
        }

        $maxSize = defined('MAX_FILE_SIZE') ? MAX_FILE_SIZE : 5242880; // Fallback 5MB (Pilar 3)
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'O ficheiro excede o tamanho máximo permitido (5MB).'];
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!is_array($allowedTypes)) {
            $allowedTypes = is_array(ALLOWED_EXTENSIONS) ? ALLOWED_EXTENSIONS : ['jpg', 'jpeg', 'png', 'pdf'];
        }
        if (!in_array($extension, $allowedTypes)) {
            return ['success' => false, 'message' => 'Tipo de ficheiro não permitido.'];
        }

        // Gera nome único para evitar colisões e XSS no nome do arquivo
        $newFileName = uniqid('ghs_', true) . '.' . $extension;
        $targetPath = $destination . '/' . $newFileName;

        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => true, 'fileName' => $newFileName];
        }

        return ['success' => false, 'message' => 'Erro ao mover o ficheiro para o servidor.'];
    }

    /**
     * Valida, corta de forma inteligente e redimensiona a imagem para o padrão oficial de Fotografia Tipo Passe (3,5 x 4,5 cm / 35 x 45 mm).
     * Proporção 7:9 (35:45), gerando imagem com alta resolução (420 x 540 px) sem distorcer ou achatar o rosto.
     *
     * @param array $file Array $_FILES['doc_foto']
     * @param string $destination Pasta de destino
     * @param int $targetWidth Largura em px (padrão: 420)
     * @param int $targetHeight Altura em px (padrão: 540 - mantendo a proporção 35x45 mm)
     * @return array ['success' => bool, 'fileName' => string, 'message' => string]
     */
    public static function uploadAndCropPassPhoto($file, $destination, $targetWidth = 420, $targetHeight = 540) {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Nenhum ficheiro enviado ou erro no upload.'];
        }

        $maxSize = defined('MAX_FILE_SIZE') ? MAX_FILE_SIZE : 5242880; // 5MB
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'A fotografia excede o tamanho máximo permitido (5MB).'];
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions)) {
            return ['success' => false, 'message' => 'Formato de imagem não permitido. Por favor use JPG, JPEG, PNG ou WEBP.'];
        }

        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $newFileName = 'passe_' . uniqid('', true) . '.jpg';
        $targetPath = rtrim($destination, '/') . '/' . $newFileName;

        // Se a extensão GD estiver disponível, faz o recorte facial inteligente e redimensionamento proporcional
        if (extension_loaded('gd') && function_exists('imagecreatetruecolor')) {
            $imageInfo = @getimagesize($file['tmp_name']);
            if ($imageInfo) {
                $mime = $imageInfo['mime'];
                $srcImage = null;

                switch ($mime) {
                    case 'image/jpeg':
                    case 'image/pjpeg':
                        $srcImage = @imagecreatefromjpeg($file['tmp_name']);
                        break;
                    case 'image/png':
                        $srcImage = @imagecreatefrompng($file['tmp_name']);
                        break;
                    case 'image/webp':
                        if (function_exists('imagecreatefromwebp')) {
                            $srcImage = @imagecreatefromwebp($file['tmp_name']);
                        }
                        break;
                }

                if ($srcImage) {
                    // Correção de orientação EXIF (fotos enviadas por smartphones)
                    if (function_exists('exif_read_data') && ($mime === 'image/jpeg' || $mime === 'image/pjpeg')) {
                        $exif = @exif_read_data($file['tmp_name']);
                        if (!empty($exif['Orientation'])) {
                            switch ($exif['Orientation']) {
                                case 3:
                                    $srcImage = imagerotate($srcImage, 180, 0);
                                    break;
                                case 6:
                                    $srcImage = imagerotate($srcImage, -90, 0);
                                    break;
                                case 8:
                                    $srcImage = imagerotate($srcImage, 90, 0);
                                    break;
                            }
                        }
                    }

                    $origWidth = imagesx($srcImage);
                    $origHeight = imagesy($srcImage);

                    $targetRatio = $targetWidth / $targetHeight; // 35 / 45 = 0.7777...
                    $origRatio = $origWidth / $origHeight;

                    if ($origRatio > $targetRatio) {
                        // Imagem é mais larga que 3,5x4,5: corta nas laterais (centralizado)
                        $cropHeight = $origHeight;
                        $cropWidth = (int)round($origHeight * $targetRatio);
                        $srcX = (int)round(($origWidth - $cropWidth) / 2);
                        $srcY = 0;
                    } else {
                        // Imagem é mais alta que 3,5x4,5: corta em cima/baixo com enquadramento facial no terço superior
                        $cropWidth = $origWidth;
                        $cropHeight = (int)round($origWidth / $targetRatio);
                        $srcX = 0;
                        $srcY = (int)round(($origHeight - $cropHeight) * 0.20);
                        if ($srcY < 0) $srcY = 0;
                        if ($srcY + $cropHeight > $origHeight) {
                            $srcY = $origHeight - $cropHeight;
                        }
                    }

                    $destImage = imagecreatetruecolor($targetWidth, $targetHeight);

                    // Preencher fundo com branco sólido
                    $white = imagecolorallocate($destImage, 255, 255, 255);
                    imagefill($destImage, 0, 0, $white);

                    // Redimensionamento de alta qualidade
                    imagecopyresampled(
                        $destImage,
                        $srcImage,
                        0, 0,
                        $srcX, $srcY,
                        $targetWidth, $targetHeight,
                        $cropWidth, $cropHeight
                    );

                    // Salvar como JPG com qualidade otimizada de 92%
                    $saved = imagejpeg($destImage, $targetPath, 92);

                    imagedestroy($srcImage);
                    imagedestroy($destImage);

                    if ($saved) {
                        return ['success' => true, 'fileName' => $newFileName];
                    }
                }
            }
        }

        // Fallback seguro caso GD não esteja disponível
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => true, 'fileName' => $newFileName];
        }

        return ['success' => false, 'message' => 'Erro ao processar e salvar a fotografia.'];
    }

    /**
     * Apaga um ficheiro se ele existir.
     */
    public static function delete($path) {
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }
}
