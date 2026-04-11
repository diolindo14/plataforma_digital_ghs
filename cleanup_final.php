<?php
$lines = file('app/views/estudante/dashboard.php');

// Remover a linha 380 que está fechando o main prematuramente
// E limpar o final do arquivo (1191-1194)
$cleaned = [];
foreach ($lines as $i => $line) {
    if ($i === 379) continue; // Linha 380
    if ($i >= 1191 && $i <= 1194) continue; // Linhas 1192-1195
    $cleaned[] = $line;
}

file_put_contents('app/views/estudante/dashboard.php', implode('', $cleaned));
echo "Cleaned 5 more lines.\n";
