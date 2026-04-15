<?php
error_reporting(E_ALL); ini_set("display_errors", 1);
session_start();
$_SESSION["user_id"] = 138;
$_SESSION["perfil"] = "Estudante";
$_SESSION["flash_info"] = "";
define("URL_ROOT", "https://escola-ghs.wuaze.com/green");

// We simply require Controller and invoke the method
require_once "core/Controller.php";
require_once "core/Database.php";
// Hardcode some config since we dont want to rely on index.php
define("DB_HOST", "sql111.infinityfree.com");
define("DB_NAME", "if0_41574650_ghs_sistema");
define("DB_USER", "if0_41574650");
define("DB_PASS", "0svEjAnMHnX");

require_once "app/controllers/EstudanteController.php";
$controller = new EstudanteController();

ob_start();
try {
    $controller->dashboard();
    $html = ob_get_clean();
    echo "DASHBOARD RENDERED COMPLETELY. Length: " . strlen($html) . "\n";
    // Check if pane-horario has any content
    $start = strpos($html, "pane-horario");
    if ($start !== false) {
        $substr = substr($html, $start, 500);
        echo "FOUND pane-horario. Preview:\n" . htmlspecialchars($substr);
    } else {
        echo "PANE HORARIO NOT FOUND IN HTML!";
    }
} catch (Throwable $e) {
    ob_end_clean();
    echo "FATAL ERROR ON PRODUCTION DASHBOARD: " . $e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile();
}
