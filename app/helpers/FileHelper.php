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

        if ($file['size'] > MAX_FILE_SIZE) {
            return ['success' => false, 'message' => 'O ficheiro excede o tamanho máximo de 5MB.'];
        }

        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
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
     * Apaga um ficheiro se ele existir.
     */
    public static function delete($path) {
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }
}
