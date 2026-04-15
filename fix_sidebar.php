<?php
$f = 'c:/xampp/htdocs/green/production_deploy/app/views/estudante/dashboard.php';
$content = file($f);
$new = array_slice($content, 0, 254);
$new[] = '                <a class="nav-link text-info fw-bold" href="<?= URL_ROOT ?>/matricula"><ion-icon name="add-circle-outline"></ion-icon> Nova Matrícula</a>' . "\n";
$new[] = '            <a class="nav-link text-warning mb-1" href="<?= URL_ROOT ?>/"><ion-icon name="earth-outline"></ion-icon> Voltar ao Site</a>' . "\n";
$new[] = '            <a class="nav-link text-danger fw-bold" href="<?= URL_ROOT ?>/auth/logout"><ion-icon name="log-out-outline"></ion-icon> Terminar Sessão</a>' . "\n";
$new = array_merge($new, array_slice($content, 266));
file_put_contents($f, implode('', $new));
echo "Fixed Student Production Dashboard\n";
