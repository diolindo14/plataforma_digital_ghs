<?php
require_once 'core/Database.php';
$pdo = Database::getInstance();

// Clear existing events to avoid duplicates if re-run
// $pdo->exec("DELETE FROM eventos WHERE tipo IN ('Feriado', 'Academico', 'Ferias')");

$events = [
    // Datas Principais
    ['Início do Ano Letivo 2025/2026', 'Início oficial das atividades letivas', '2025-10-15 08:00:00', 'Academico', '#f59e0b', 'Global'],
    ['Fim do 1º Semestre', 'Encerramento das atividades do primeiro semestre', '2026-03-06 18:00:00', 'Academico', '#f59e0b', 'Global'],
    ['Início do 2º Semestre', 'Início das atividades do segundo semestre', '2026-03-23 08:00:00', 'Academico', '#f59e0b', 'Global'],
    ['Fim do Ano Letivo', 'Encerramento oficial do ano letivo', '2026-07-22 18:00:00', 'Academico', '#f59e0b', 'Global'],

    // Férias
    ['Férias de Natal (Início)', 'Período de interrupção letiva', '2025-12-22 00:00:00', 'Ferias', '#f59e0b', 'Global'],
    ['Férias de Natal (Fim)', 'Retorno às aulas', '2026-01-04 23:59:59', 'Ferias', '#f59e0b', 'Global'],
    ['Férias de Páscoa (Início)', 'Período de interrupção letiva', '2026-04-03 00:00:00', 'Ferias', '#f59e0b', 'Global'],
    ['Férias de Páscoa (Fim)', 'Retorno às aulas', '2026-04-06 23:59:59', 'Ferias', '#f59e0b', 'Global'],

    // Feriados
    ['Dia do Natal', 'Feriado Nacional', '2025-12-25 00:00:00', 'Feriado', '#1e3a8a', 'Global'],
    ['Dia do Novo Ano', 'Feriado Nacional', '2026-01-01 00:00:00', 'Feriado', '#1e3a8a', 'Global'],
    ['Dia dos Heróis Nacionais', 'Feriado Nacional', '2026-01-20 00:00:00', 'Feriado', '#1e3a8a', 'Global'],
    ['Dia dos Professores', 'Homenagem aos docentes', '2026-02-17 00:00:00', 'Feriado', '#1e3a8a', 'Global'],
    ['Dia da Mulher', 'Feriado Nacional', '2026-03-08 00:00:00', 'Feriado', '#1e3a8a', 'Global'],
    ['Ramadão', 'Feriado Religioso', '2026-03-20 00:00:00', 'Feriado', '#1e3a8a', 'Global'],
    ['Páscoa', 'Feriado Religioso', '2026-04-05 00:00:00', 'Feriado', '#1e3a8a', 'Global'],
    ['Dia dos Trabalhadores', 'Feriado Nacional', '2026-05-01 00:00:00', 'Feriado', '#1e3a8a', 'Global'],
    ['Tabaski', 'Feriado Religioso', '2026-05-27 00:00:00', 'Feriado', '#1e3a8a', 'Global'],
];

$stmt = $pdo->prepare("INSERT INTO eventos (titulo, descricao, data_evento, tipo, cor, destinatario_tipo, criado_por) VALUES (?, ?, ?, ?, ?, ?, 1)");

foreach ($events as $event) {
    $stmt->execute($event);
}

echo "Events seeded successfully!\n";
