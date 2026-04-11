<?php
/**
 * deploy_to_live.php — GHS Production Sync Script
 * Sincroniza ficheiros críticos do ambiente local para production_deploy/
 * Depois, o conteúdo de production_deploy/ deve ser enviado via FTP para o servidor.
 */

$source = __DIR__ . '/';
$dest   = __DIR__ . '/production_deploy/';

if (!is_dir($dest)) {
    mkdir($dest, 0755, true);
}

// Pastas e ficheiros a sincronizar
$dirs = [
    'app',
    'core',
    'docs',
    'public',
    'img',
];

$rootFiles = [
    '.htaccess',
    'index.php',
];

$excludePatterns = [
    '/production_deploy',
    '/database/backups',
    '/app/logs',
    '/tmp',
    '/docs/dev',
    '/node_modules',
    '/.git',
];

function shouldExclude($path, $excludePatterns) {
    foreach ($excludePatterns as $pattern) {
        if (strpos($path, $pattern) !== false) return true;
    }
    return false;
}

function syncDir($srcDir, $destDir, $excludePatterns) {
    if (!is_dir($destDir)) mkdir($destDir, 0755, true);

    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($srcDir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    $count = 0;
    foreach ($items as $item) {
        $relativePath = substr($item->getPathname(), strlen($srcDir));
        if (shouldExclude($relativePath, $excludePatterns)) continue;

        $destPath = $destDir . $relativePath;

        if ($item->isDir()) {
            if (!is_dir($destPath)) mkdir($destPath, 0755, true);
        } else {
            $destFileDir = dirname($destPath);
            if (!is_dir($destFileDir)) mkdir($destFileDir, 0755, true);
            copy($item->getPathname(), $destPath);
            $count++;
        }
    }
    return $count;
}

echo "🚀 GHS Deploy — Sincronização para production_deploy/\n";
echo str_repeat('-', 50) . "\n\n";

$totalCopied = 0;

foreach ($dirs as $dir) {
    $srcPath = $source . $dir;
    if (!is_dir($srcPath)) { echo "⚠  Pasta não encontrada: $dir\n"; continue; }
    $copied = syncDir($srcPath, $dest . $dir, $excludePatterns);
    echo "✓ $dir/ — $copied ficheiros copiados\n";
    $totalCopied += $copied;
}

foreach ($rootFiles as $file) {
    $src = $source . $file;
    if (file_exists($src)) {
        copy($src, $dest . $file);
        echo "✓ $file\n";
        $totalCopied++;
    }
}

echo "\n" . str_repeat('-', 50) . "\n";
echo "✅ Deploy concluído. Total: $totalCopied ficheiros.\n";
echo "📂 Pasta pronta: production_deploy/\n";
echo "📡 Próximo passo: enviar production_deploy/ via FTP para o servidor InfinityFree.\n";
echo "🌐 URL de produção: https://escola-ghs.wuaze.com\n";
