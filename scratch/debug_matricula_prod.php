<?php

$db_host = 'sql111.infinityfree.com';
$db_name = 'if0_41574650_ghs_sistema';
$db_user = 'if0_41574650';
$db_pass = '0svEjAnMHnX';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    
    // Check user 138 (Diosives) in database
    $stmt = $pdo->query("SELECT * FROM utilizadores WHERE email = 'diosivespedro@gmail.com' OR email = 'diosives@gmail.com'");
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "USER INFO:\n";
    print_r($user);
    
    if ($user) {
        // Check estudante table
        $stmt = $pdo->query("SELECT * FROM estudantes WHERE utilizador_id = " . $user['id']);
        $est = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "\nESTUDANTE INFO:\n";
        print_r($est);
        
        if ($est) {
            // Check matriculas table
            $stmt = $pdo->query("SELECT * FROM matriculas WHERE estudante_id = " . $est['id'] . " ORDER BY id DESC");
            $mat = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "\nMATRICULAS:\n";
            print_r($mat);
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
