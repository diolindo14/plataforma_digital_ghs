<?php
// app/views/admin/partials/horario_tabela.php
$dias = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
$tempos = [
    '1º' => ['07:20', '08:50'], '2º' => ['08:55', '10:25'],
    '3º' => ['10:45', '12:15'], '4º' => ['12:20', '13:50'],
    'N1' => ['17:45', '19:15'], 'N2' => ['19:20', '20:50'],
    'N3' => ['21:00', '22:30'], 'N4' => ['22:35', '24:00']
];

if (empty($horarios)) {
    echo '<p class="text-center text-muted py-4">Nenhum horário alocado para esta turma.</p>';
    return;
}
?>
<div class="table-responsive">
    <table class="table table-bordered text-center align-middle mb-0" style="border: 1px solid #000 !important; background: #fff;">
        <thead style="background-color: #f8f9fa;">
            <tr style="border-bottom: 2px solid #000;">
                <th style="border: 1px solid #000; padding: 10px; font-weight: bold; width: 80px;">TEMPO</th>
                <th style="border: 1px solid #000; padding: 10px; font-weight: bold; width: 100px;">HORA</th>
                <?php foreach ($dias as $d): ?>
                    <th style="border: 1px solid #000; padding: 10px; font-weight: bold;"><?php echo strtoupper($d); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tempos as $t_label => $t_horas): ?>
                <tr style="border-bottom: 1px solid #000;">
                    <td style="border: 1px solid #000; font-weight: bold; background: #fdfdfd;"><?php echo $t_label; ?></td>
                    <td style="border: 1px solid #000; font-size: 0.8rem; background: #fdfdfd;"><?php echo $t_horas[0]; ?> - <?php echo $t_horas[1]; ?></td>
                    <?php foreach ($dias as $d): ?>
                        <td style="border: 1px solid #000; padding: 0; min-width: 120px; height: 60px; vertical-align: top;">
                            <?php 
                            foreach ($horarios as $h): 
                                if ($h['dia_semana'] == $d && substr($h['hora_inicio'], 0, 5) == $t_horas[0]):
                            ?>
                                <div style="display: flex; flex-direction: column; height: 100%;">
                                    <div style="padding: 5px; font-weight: bold; border-bottom: 1px solid #eee; flex: 1; display: flex; align-items: center; justify-content: center;">
                                        <?php echo htmlspecialchars($h['sigla']); ?>
                                    </div>
                                    <div style="padding: 3px; font-size: 0.75rem; color: #666; background: #fafafa; flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                        <span><?php echo htmlspecialchars($h['sala']); ?></span>
                                        <button class="btn btn-link text-danger p-0 mt-1" style="font-size: 0.7rem; text-decoration: none;" onclick="if(confirm('Remover slot?')) window.location.href='<?= URL_ROOT ?>/admin/deleteHorario/<?php echo $h['id']; ?>'">
                                            <ion-icon name="trash-outline"></ion-icon>
                                        </button>
                                    </div>
                                </div>
                            <?php 
                                    break;
                                endif; 
                            endforeach; 
                            ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
