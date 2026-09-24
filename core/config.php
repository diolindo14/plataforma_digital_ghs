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

// Configurações Académicas
define('YEAR_START_DATE', '2025-10-15');
define('YEAR_END_DATE', '2026-07-22');
define('TOTAL_MONTHS_YEAR', 10);
define('PAYMENT_DUE_DAY', 15);

// Configurações de arquivos
define('ALLOWED_EXTENSIONS', ['pdf', 'jpg', 'jpeg', 'png']);
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB em bytes (Pilar 3)

// Configurações de Envio de Email (SMTP)
define('MAIL_ENABLED', true);
define('MAIL_DRIVER', 'mail'); // Mude para 'smtp' quando preencher as credenciais abaixo
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587); // 587 para TLS ou 465 para SSL
define('MAIL_ENCRYPTION', 'tls'); // 'tls' ou 'ssl'
define('MAIL_USERNAME', ''); // Seu endereço de email (ex: secretaria@gmail.com)
define('MAIL_PASSWORD', ''); // Senha de aplicativo (gerada na conta de email)
define('MAIL_FROM_ADDRESS', 'no-reply@ghs.gw');
define('MAIL_FROM_NAME', 'GHS - Green Hard & Soft');
