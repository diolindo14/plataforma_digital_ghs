<?php
/**
 * Shared Schedule Grid Partial (Matching PDF Format) - Updated March 2026
 * Usage: include this file after setting $gridData = $horarioModel->buildWeeklyGrid($turma_id)
 * and $turmaInfo = ['codigo' => ..., 'turno' => ..., 'nivel' => ...]
 */

$diasOrder = ['Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
$diasLabels = ['SEG','TER','QUA','QUI','SEX','SÁB'];
?>

<style>
/* Clean Academic Style */
.horario-container { font-family: 'Inter', system-ui, -apple-system, sans-serif; color: #1e293b; }
.grade-table-edu { width: 100%; border-collapse: collapse; background: #fff; border: 1px solid #1e293b; }
.grade-table-edu th { background-color: #708238; color: #fff; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; padding: 10px 5px; border: 1px solid #1e293b; }
.grade-table-edu td { border: 1px solid #1e293b; padding: 0; vertical-align: middle; height: 60px; }

.col-tempo, .col-hora { text-align: center; font-weight: bold; background: #fff; width: 80px; }
.tempo-val { font-size: 1.1rem; }
.hora-val { font-size: 0.75rem; transform: rotate(-25deg); display: inline-block; white-space: nowrap; margin-top: 5px; }

/* Slot Design: Two rows */
.slot-wrapper { display: flex; flex-direction: column; height: 100%; width: 100%; }
.slot-subject { 
    flex: 1; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    font-weight: 700; 
    font-size: 0.9rem; 
    border-bottom: 0.5px solid #1e293b;
    padding: 4px;
}
.slot-room { 
    flex: 1; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    font-size: 0.8rem; 
    color: #475569;
    padding: 2px;
}

.slot-empty-edu { 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    height: 100%; width: 100%;
}
.slot-empty-edu svg { width: 100%; height: 100%; }
.slot-empty-edu line { stroke: #1e293b; stroke-width: 1; }

.header-info-edu { margin-bottom: 20px; font-weight: bold; font-size: 1.4rem; text-align: center; }

@media print {
    .no-print { display: none !important; }
    .horario-container { padding: 0; }
    .grade-table-edu th { background-color: #708238 !important; -webkit-print-color-adjust: exact; }
}
</style>

<div class="horario-container">
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <div>
            <?php if (!empty($turmaInfo)): ?>
                <h5 class="mb-0">
                    Grupo: <?= htmlspecialchars($turmaInfo['codigo']) ?> 
                    | Nivel: <?= htmlspecialchars($turmaInfo['nivel'] ?? '—') ?>
                </h5>
            <?php endif; ?>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-dark" onclick="window.print()">
                <ion-icon name="print-outline" class="me-1"></ion-icon> Imprimir PDF
            </button>
        </div>
    </div>

    <?php if (empty($gridData['tempos'])): ?>
        <div class="text-center py-5 text-muted border rounded bg-light">
            <p>Horário ainda não definido.</p>
        </div>
    <?php else: ?>

    <div class="header-info-edu d-none d-print-block">
        HORÁRIO 2º SEMESTRE 2025-2026
    </div>
    <div class="mb-2 text-center d-none d-print-block" style="font-size: 1.1rem;">
        Grupo: <?= htmlspecialchars($turmaInfo['codigo'] ?? 'N/A') ?> 
        | Horário: <?= isset($gridData['tempos'][0]) ? substr($gridData['tempos'][0]['inicio'], 0, 5) : '—' ?>
        | Nivel: <?= htmlspecialchars($turmaInfo['nivel'] ?? '—') ?>
    </div>

    <div class="table-responsive">
        <table class="grade-table-edu">
            <thead>
                <tr>
                    <th class="col-tempo">TEMPO</th>
                    <th class="col-hora">HORA</th>
                    <?php foreach ($diasLabels as $label): ?>
                        <th><?= $label ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gridData['tempos'] as $tempo => $horas): ?>
                    <tr>
                        <td class="col-tempo">
                            <span class="tempo-val"><?= ((int)$tempo) + 1 ?>º</span>
                        </td>
                        <td class="col-hora">
                            <span class="hora-val"><?= substr($horas['inicio'],0,5) ?> - <?= substr($horas['fim'],0,5) ?></span>
                        </td>
                        <?php foreach ($diasOrder as $dia): ?>
                            <td>
                                <?php $slot = $gridData['grid'][$tempo][$dia] ?? null; ?>
                                <?php if ($slot): ?>
                                    <div class="slot-wrapper">
                                        <div class="slot-subject"><?= htmlspecialchars($slot['sigla']) ?></div>
                                        <div class="slot-room"><?= htmlspecialchars($slot['sala'] ?? 'S1') ?></div>
                                    </div>
                                <?php else: ?>
                                    <div class="slot-empty-edu">
                                        <svg preserveAspectRatio="none" viewBox="0 0 100 100">
                                            <line x1="0" y1="0" x2="100" y2="100" />
                                            <line x1="100" y1="0" x2="0" y2="100" />
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="mt-4 text-center d-none d-print-block" style="font-family: serif; font-style: italic; font-size: 1.2rem;">
        O FUTURO É HOJE
    </div>

    <?php endif; ?>
</div>

<script>
// No-op for now as hover and tooltips are removed for formal style
</script>

