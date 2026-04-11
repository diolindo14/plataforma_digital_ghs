<?php
/**
 * Configurações Gerais do Sistema GHS
 */

// Configurações da Base de Dados
define('DB_HOST', 'sql111.infinityfree.com');
define('DB_NAME', 'if0_41574650_ghs_sistema');
define('DB_USER', 'if0_41574650');
define('DB_PASS', '0svEjAnMHnX');

// Configurações Globais
define('URL_ROOT', '');
define('APP_NAME', 'GHS - Green Hard & Softh');

// Configurações de Segurança
define('SESSION_LIFETIME', 1800); // 30 minutos
define('CSRF_NAME', 'ghs_csrf_token');

// Configurações Académicas
define('YEAR_START_DATE', '2025-10-15');
define('YEAR_END_DATE', '2026-07-22');
define('TOTAL_MONTHS_YEAR', 10);
define('PAYMENT_DUE_DAY', 15);

// Configurações de arquivos
define('ALLOWED_EXTENSIONS', ['pdf', 'jpg', 'jpeg', 'png']);
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB em bytes (Pilar 3)
