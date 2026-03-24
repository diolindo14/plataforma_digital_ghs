<?php
require_once 'core/Database.php';

// Try to connect to both possible DB names just in case
$dbNames = ['ghsespf_db', 'green'];
$success = false;

foreach ($dbNames as $dbName) {
    try {
        $dsn = "mysql:host=127.0.0.1;dbname=$dbName;charset=utf8mb4";
        $pdo = new PDO($dsn, 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        
        echo "Connected to database: $dbName\n";
        
        $tables = ['sumarios', 'notas', 'frequencias'];
        foreach ($tables as $table) {
            echo "Checking table '$table' in $dbName...\n";
            // Check if column exists
            $check = $pdo->query("SHOW COLUMNS FROM $table LIKE 'confirmado_admin'")->fetch();
            if (!$check) {
                echo "Adding 'confirmado_admin' to $table...\n";
                $pdo->exec("ALTER TABLE $table ADD COLUMN confirmado_admin TINYINT(1) DEFAULT 0");
            } else {
                echo "Column already exists in $table.\n";
            }
        }
        $success = true;
        break; // Stop if success
    } catch (Exception $e) {
        echo "Failed to process database '$dbName': " . $e->getMessage() . "\n";
    }
}

if ($success) {
    echo "\nDatabase migration completed successfully!\n";
} else {
    echo "\nCRITICAL: Migration failed on all attempts.\n";
    exit(1);
}
