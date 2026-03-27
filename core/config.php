<?php
/**
 * Configurações Gerais do Sistema GHS
 */

// Configurações da Base de Dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'ghsespf_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configurações Globais
define('URL_ROOT', '/green');
define('APP_NAME', 'GHS - Green Hard & Soft');

// Configurações de Segurança
define('SESSION_LIFETIME', 1800); // 30 minutos
define('CSRF_NAME', 'ghs_csrf_token');

// Configurações de Upload
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf']);
