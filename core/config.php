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
