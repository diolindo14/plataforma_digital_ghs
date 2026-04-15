<?php
include 'app/config/config.php';
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'ghsespf_db';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $stmt = $db->prepare('SELECT DISTINCT status FROM matriculas');
    $stmt->execute();
    print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
