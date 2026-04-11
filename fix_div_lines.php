<?php
$lines = file('app/views/estudante/dashboard.php');

// Remover as linhas extra que identifiquei (1-indexed em view_file, 0-indexed aqui)
// 381, 382, 393
$to_remove = [380, 381, 392]; // Array indices are 1 less than line numbers
$cleaned = [];
foreach ($lines as $i => $line) {
    if (in_array($i, $to_remove)) continue;
    $cleaned[] = $line;
}

file_put_contents('app/views/estudante/dashboard.php', implode('', $cleaned));
echo "Cleaned 3 lines.\n";
