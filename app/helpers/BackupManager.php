<?php
/**
 * BackupManager.php — Sistema de Backup em Tempo Real
 * 
 * Gera snapshots automáticos da base de dados imediatamente após
 * operações críticas (lançamento de notas, matrículas, etc.).
 * Mantém uma fila rotativa dos últimos 10 checkpoints.
 */
class BackupManager {

    /** Caminho absoluto para o repositório de backups */
    private static $backup_dir;

    /** Executável mysqldump do XAMPP */
    private static $mysqldump;

    /** Inicializar paths de forma lazy e baseada na estrutura real do projeto */
    private static function init() {
        if (!isset(self::$backup_dir)) {
            // __DIR__ = green/app/helpers
            self::$backup_dir = realpath(__DIR__ . '/../../database/backups') . DIRECTORY_SEPARATOR;

            // Detectar mysqldump: tenta variáveis de ambiente primeiro, depois fallback XAMPP
            self::$mysqldump = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';
        }
    }

    /**
     * Cria um snapshot imediato da base de dados.
     *
     * @param  string $reason  Etiqueta que identifica o motivo do checkpoint (ex: 'Grades', 'Manual')
     * @return bool            true se o backup foi criado com sucesso
     */
    public static function createCheckpoint($reason = 'AUTO') {
        self::init();

        if (!is_dir(self::$backup_dir)) {
            @mkdir(self::$backup_dir, 0775, true);
        }

        // Slug seguro: remover espaços e caracteres especiais
        $slug = preg_replace('/[^a-zA-Z0-9_-]/', '_', $reason);
        $filename = 'checkpoint_' . date('Ymd_His') . '_' . $slug . '.sql';
        $fullPath = self::$backup_dir . $filename;

        // Construir o comando mysqldump
        // Nota: DB_PASS é vazio em XAMPP local — evitar -p'' que causa erro em algumas versões
        $passArg = defined('DB_PASS') && DB_PASS !== '' ? '-p' . escapeshellarg(DB_PASS) : '';

        $command = sprintf(
            '"%s" -h %s -u %s %s %s > "%s" 2>&1',
            self::$mysqldump,
            escapeshellarg(DB_HOST),
            escapeshellarg(DB_USER),
            $passArg,
            escapeshellarg(DB_NAME),
            $fullPath
        );

        // Executar (síncrono — garante que o ficheiro existe antes de retornar)
        exec($command, $output, $exitCode);

        if ($exitCode === 0 && file_exists($fullPath) && filesize($fullPath) > 0) {
            self::log($filename, "OK: $reason");
            self::rotate();
            return true;
        }

        // Registar falha com detalhe do output
        self::log('ERROR', implode(' | ', $output) . " | Reason: $reason");
        return false;
    }

    /**
     * Mantém apenas os 10 checkpoints mais recentes.
     */
    private static function rotate($max = 10) {
        self::init();
        $files = glob(self::$backup_dir . 'checkpoint_*.sql');
        if (!$files) return;

        usort($files, fn($a, $b) => filemtime($a) - filemtime($b));

        while (count($files) > $max) {
            @unlink(array_shift($files));
        }
    }

    /**
     * Regista um evento de backup no ficheiro de log.
     */
    private static function log($file, $status) {
        // __DIR__ = green/app/helpers → logs em green/app/logs/
        $logFile = __DIR__ . '/../logs/backup.log';
        $dir = dirname($logFile);
        if (!is_dir($dir)) @mkdir($dir, 0775, true);

        $entry = sprintf("[%s] FILE: %-50s | %s\n", date('Y-m-d H:i:s'), $file, $status);
        file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
    }

    /**
     * Devolve a lista dos últimos $limit backups (mais recente primeiro).
     *
     * @return array  [ ['name' => ..., 'size' => ..., 'date' => ...], ... ]
     */
    public static function getLatestBackups($limit = 10) {
        self::init();

        if (!is_dir(self::$backup_dir)) return [];

        $files = glob(self::$backup_dir . 'checkpoint_*.sql');
        if (!$files) return [];

        usort($files, fn($a, $b) => filemtime($b) - filemtime($a));

        $result = [];
        foreach (array_slice($files, 0, $limit) as $f) {
            $size = filesize($f);
            $result[] = [
                'name' => basename($f),
                'size' => $size >= 1048576
                    ? round($size / 1048576, 1) . ' MB'
                    : round($size / 1024, 1) . ' KB',
                'date' => date('d/m/Y H:i:s', filemtime($f)),
            ];
        }
        return $result;
    }
}
