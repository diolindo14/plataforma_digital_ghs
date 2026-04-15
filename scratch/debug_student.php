<?php
include 'app/config/config.php';
// We need the DB config.
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'ghsespf_db'; // Fixed name

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "--- USERS ---\n";
    $stmt = $db->prepare('SELECT id, nome_completo, status FROM utilizadores WHERE nome_completo LIKE :name');
    $stmt->execute([':name' => '%Diosives%']);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($users);
    
    if ($users) {
        $uid = $users[0]['id'];
        echo "\n--- ESTUDANTE ---\n";
        $stmt = $db->prepare('SELECT id, utilizador_id FROM estudantes WHERE utilizador_id = :uid');
        $stmt->execute([':uid' => $uid]);
        $est = $stmt->fetchAll(PDO::FETCH_ASSOC);
        print_r($est);
        
        if ($est) {
            $eid = $est[0]['id'];
            echo "\n--- MATRICULAS ---\n";
            $stmt = $db->prepare('SELECT * FROM matriculas WHERE estudante_id = :eid ORDER BY id DESC');
            $stmt->execute([':eid' => $eid]);
            print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
