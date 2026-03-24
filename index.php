<?php
date_default_timezone_set('UTC');
session_start();

require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/Database.php';

try {
    $app = new App();
} catch (Throwable $e) {
    // Log do erro crítico no servidor
    $logFile = __DIR__ . '/app/logs/error.log';
    $message = "[" . date('Y-m-d H:i:s') . "] CRITICAL ERROR: " . $e->getMessage() . " em " . $e->getFile() . " na linha " . $e->getLine() . PHP_EOL;
    error_log($message, 3, $logFile);

    // Redirecionar para interface amigável
    if (file_exists(__DIR__ . '/public/error_500.php')) {
        include __DIR__ . '/public/error_500.php';
    } else {
        http_response_code(500);
        echo "<h1>500 - Erro Interno do Servidor</h1>";
        echo "<p>Ocorreu um erro inesperado. A equipa técnica já foi notificada.</p>";
    }
    exit;
}
