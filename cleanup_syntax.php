<?php
$file = 'app/views/estudante/dashboard.php';
$content = file_get_contents($file);

// 1. Localizar o bloco problemático
$startStr = '<!-- 📢 ALERTAS DE CONVOCATÓRIA (Mediação) -->';
$endStr = '<!-- 🏆 CERTIFICADOS DE MÉRITO EMITIDOS OFICIALMENTE -->';

$startPos = strpos($content, $startStr);
$endPos = strpos($content, $endStr);

if ($startPos !== false && $endPos !== false) {
    $preBlock = substr($content, 0, $startPos);
    $postBlock = substr($content, $endPos);
    
    $cleanBlock = <<<'EOD'
<!-- 📢 ALERTAS DE CONVOCATÓRIA (Mediação) -->
                <?php if (!empty($data['notas'])): ?>
                    <?php foreach ($data['notas'] as $n): ?>
                        <?php if (isset($n['feedback_status']) && $n['feedback_status'] === 'Aguardando_Comparecimento'): ?>
                            <div class="alert alert-danger shadow-sm border-0 border-start border-4 border-danger rounded-4 mb-4 p-4" role="alert">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger">
                                        <ion-icon name="calendar" class="fs-2"></ion-icon>
                                    </div>
                                    <div>
                                        <h4 class="fw-bold mb-1 text-danger">Convocatória de Mediação Presencial</h4>
                                        <p class="mb-0 text-muted">A sua contestação da disciplina <strong><?= htmlspecialchars($n['disciplina'] ?? 'N/A') ?></strong> foi escalada para mediação.</p>
                                    </div>
                                </div>
                                <div class="row g-3 bg-white bg-opacity-50 p-3 rounded-4 border">
                                    <div class="col-md-3">
                                        <div class="small fw-bold text-muted text-uppercase mb-1">Data Agendada</div>
                                        <div class="fw-bold fs-5 text-dark"><?= !empty($n['data_reuniao']) ? date('d/m/Y', strtotime($n['data_reuniao'])) : 'N/A' ?></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small fw-bold text-muted text-uppercase mb-1">Hora</div>
                                        <div class="fw-bold fs-5 text-dark"><?= !empty($n['hora_reuniao']) ? substr($n['hora_reuniao'], 0, 5) : 'N/A' ?></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small fw-bold text-muted text-uppercase mb-1">Local / Gabinete</div>
                                        <div class="fw-bold fs-5 text-dark"><?= htmlspecialchars($n['local_reuniao'] ?? 'N/A') ?></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small fw-bold text-muted text-uppercase mb-1">Referência</div>
                                        <div class="badge bg-danger rounded-pill px-3">OBRIGATÓRIO</div>
                                    </div>
                                    <div class="col-12 mt-3 pt-3 border-top">
                                        <small class="fw-bold text-danger d-block mb-1">Motivo da Convocação:</small>
                                        <p class="mb-0 small italic text-muted">"<?= nl2br(htmlspecialchars($n['motivo_convocacao'] ?? 'Motivo não especificado.')) ?>"</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

EOD;

    $newContent = $preBlock . $cleanBlock . $postBlock;
    file_put_contents($file, $newContent);
    echo "Success!";
} else {
    echo "Could not find start or end marker. Start: " . ($startPos === false ? "No" : "Yes") . " End: " . ($endPos === false ? "No" : "Yes");
}
