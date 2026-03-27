<?php
date_default_timezone_set('UTC');
session_start();

require_once 'core/config.php';

// Autoloader Dinâmico - Modernização (Pilar 4)
spl_autoload_register(function ($className) {
    $paths = [
        'core/' . $className . '.php',
        'app/models/' . $className . '.php',
        'app/helpers/' . $className . '.php'
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// require_once manuais removidos (Autoloader assume agora)

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
