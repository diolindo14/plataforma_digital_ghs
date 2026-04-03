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

// ⚡ Configurações de Notificações por Email (Pilar 6)
// Para notificações reais, preencha os dados de um servidor SMTP (ex: Gmail, SendGrid, Outlook)
define('MAIL_SMTP_HOST', ''); // Ex: smtp.gmail.com
define('MAIL_SMTP_USER', ''); // Ex: seu-email@gmail.com
define('MAIL_SMTP_PASS', ''); // Ex: sua-senha-app (Google)
define('MAIL_SMTP_PORT', 587); // 587 para TLS / 465 para SSL
define('MAIL_FROM', '');      // Endereço oficial (ex: no-reply@ghs.edu.gw)
