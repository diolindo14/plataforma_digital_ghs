<?php
/**
 * Shared Schedule Grid Partial
 * Usage: include this file after setting $gridData = $horarioModel->buildWeeklyGrid($turma_id)
 * and $turmaInfo = ['codigo' => ..., 'turno' => ...]
 */

$diasOrder = ['Segunda','Terça','Quarta','Quinta','Sexta'];

// Subject color map by type / category
$colorMap = [
    // Exatas (Lighter Blues/Purples)
    'MAT'=>'#60a5fa','FIS'=>'#818cf8','QUIM'=>'#a78bfa','ALGA'=>'#c084fc',
    // Línguas (Greens)
    'PORT'=>'#34d399','ING'=>'#10b981',
    // Informática base (Yellows/Oranges)
    'IGE'=>'#fbbf24','TI'=>'#f59e0b','APL'=>'#f97316','GDA'=>'#ea580c',
    // Programação (Reds/Pinks)
    'POO'=>'#f87171','AED'=>'#ef4444','PHP'=>'#f43f5e','JAVASCR'=>'#fb7185',
    'JAVASTD'=>'#e11d48','VBNET'=>'#be123c',
    // Sistemas (Cyans/Blues)
    'HM'=>'#38bdf8','SO'=>'#0ea5e9','TC'=>'#2dd4bf','SID'=>'#14b8a6',
    'FBD'=>'#06b6d4','RD1'=>'#0891b2','RD2'=>'#0369a1',
    // 4º Ano (Purples/Teals)
    'IA'=>'#8b5cf6','PI'=>'#d946ef','ES'=>'#ec4899','TSI'=>'#f472b6',
    'MC'=>'#94a3b8','IPM'=>'#cbd5e1','MCG'=>'#64748b',
    // 5º Ano BD (Emeralds)
    'SQLSRV'=>'#2dd4bf','AO'=>'#14b8a6','MA'=>'#0d9488',
    // 5º Ano Redes (Limes)
    'IS'=>'#facc15','SR'=>'#eab308','WT'=>'#a3e635','AD'=>'#84cc16','LINUX'=>'#65a30d',
];
?>

<style>
.grade-table th, .grade-table td { font-size: 0.82rem; vertical-align: middle; text-align: center; padding: 0.35rem 0.5rem; }
.grade-table { min-width: 700px; border-collapse: separate; border-spacing: 2px; }
.slot-cell {
    border-radius: 8px;
    padding: 8px 6px;
    color: #fff;
    font-weight: 700;
    cursor: pointer;
    transition: transform 0.15s, box-shadow 0.15s;
    min-width: 80px;
}
.slot-cell:hover { transform: scale(1.05); box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
.slot-empty { background: #f1f5f9; color: #94a3b8; border-radius: 8px; font-size: 0.7rem; }
.slot-nome { font-size: 0.65rem; opacity: 0.9; font-weight: 500; }
.slot-sala { font-size: 0.6rem; opacity: 0.75; margin-top: 2px; }
.tempo-col { background: linear-gradient(135deg,#1e293b,#334155); color: #94a3b8; border-radius: 6px; padding: 8px 4px; min-width: 90px; }
.tempo-num { font-size: 1rem; font-weight: 800; color: #f1f5f9; }
.tempo-hora { font-size: 0.6rem; display: block; opacity: 0.6; }
.dia-header { background: linear-gradient(135deg,#0f172a,#1e3a5f); color: #60a5fa; font-weight: 700; font-size: 0.8rem; border-radius: 6px; }
.print-area { background: white; }
@media print {
    .no-print { display: none !important; }
    .slot-cell { color: #000 !important; border: 1px solid #ccc !important; background: #f9f9f9 !important; }
    .tempo-col { background: #eee !important; color: #000 !important; }
}
</style>

<div class="d-flex justify-content-between align-items-start mb-3 no-print">
    <div>
        <?php if (!empty($turmaInfo)): ?>
            <span class="badge fs-6 px-3 py-2" style="background:linear-gradient(135deg,#0f172a,#1e3a5f)">
                <ion-icon name="school-outline" class="me-1"></ion-icon>
                <?= htmlspecialchars($turmaInfo['codigo']) ?>
            </span>
            <span class="badge bg-secondary ms-2"><?= htmlspecialchars($turmaInfo['turno']) ?></span>
        <?php endif; ?>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-sm btn-outline-primary" onclick="window.print()">
            <ion-icon name="print-outline" class="me-1"></ion-icon> Imprimir
        </button>
        <button class="btn btn-sm btn-outline-success" id="btn-export-excel">
            <ion-icon name="grid-outline" class="me-1"></ion-icon> Excel
        </button>
        <button class="btn btn-sm btn-outline-danger" id="btn-export-pdf">
            <ion-icon name="document-outline" class="me-1"></ion-icon> PDF
        </button>
    </div>
</div>

<?php if (empty($gridData['tempos'])): ?>
    <div class="text-center py-5 text-muted">
        <ion-icon name="calendar-clear-outline" style="font-size:4rem; opacity:0.3;"></ion-icon>
        <p class="mt-3">Horário ainda não definido para esta turma.</p>
    </div>
<?php else: ?>

<div class="table-responsive print-area" id="horario-print-area">
<table class="table table-bordered grade-table" id="table-horario">
    <thead>
        <tr>
            <th class="tempo-col">Tempo</th>
            <?php foreach ($diasOrder as $dia): ?>
                <th class="dia-header"><?= $dia ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($gridData['tempos'] as $tempo => $horas): ?>
        <tr>
            <td class="tempo-col">
                <div class="tempo-num"><?= ((int)$tempo) + 1 ?>º</div>
                <span class="tempo-hora"><?= substr($horas['inicio'],0,5) ?> - <?= substr($horas['fim'],0,5) ?></span>
            </td>
            <?php foreach ($diasOrder as $dia): ?>
                <?php $slot = $gridData['grid'][$tempo][$dia] ?? null; ?>
                <td style="padding:4px;">
                <?php if ($slot): 
                    $sigla = $slot['sigla'];
                    $cor = $colorMap[$sigla] ?? '#64748b';
                    $nome = $slot['nome_display'];
                    $sala = $slot['sala'] ?? '';
                ?>
                    <div class="slot-cell" 
                         style="background:<?= $cor ?>;"
                         data-bs-toggle="tooltip" 
                         title="<?= htmlspecialchars($nome) ?> | Sala: <?= htmlspecialchars($sala) ?>">
                        <?= htmlspecialchars($sigla) ?>
                        <div class="slot-nome"><?= htmlspecialchars(strlen($nome)>22 ? substr($nome,0,20).'…' : $nome) ?></div>
                        <?php if ($sala): ?>
                            <div class="slot-sala"><ion-icon name="location-outline"></ion-icon> <?= htmlspecialchars($sala) ?></div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="slot-empty py-3">—</div>
                <?php endif; ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>

<!-- Legend -->
<div class="d-flex flex-wrap gap-2 mt-3 no-print" id="horario-legend">
    <?php 
    $seenSiglas = [];
    foreach ($gridData['grid'] as $tRow) {
        foreach ($tRow as $slot) {
            if (!empty($slot['sigla'])) $seenSiglas[$slot['sigla']] = $slot['nome_display'];
        }
    }
    foreach ($seenSiglas as $sig => $nm):
        $cor = $colorMap[$sig] ?? '#64748b';
    ?>
        <span class="badge rounded-pill px-3 py-2" style="background:<?= $cor ?>; font-size:0.7rem;"
              data-bs-toggle="tooltip" title="<?= htmlspecialchars($nm) ?>">
            <?= htmlspecialchars($sig) ?>
        </span>
    <?php endforeach; ?>
</div>

<script>
// Bootstrap tooltips
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
    new bootstrap.Tooltip(el, { trigger: 'hover', placement: 'top' });
});

// Excel export via DataTables (if available)
document.getElementById('btn-export-excel')?.addEventListener('click', function() {
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#table-horario').DataTable({destroy:true, dom:'B', buttons:['excel']}).buttons(0,null).trigger();
    } else {
        alert('Export Excel: DataTables não disponível.');
    }
});

// PDF export via browser print as fallback
document.getElementById('btn-export-pdf')?.addEventListener('click', function() {
    window.print();
});
</script>

<?php endif; ?>
