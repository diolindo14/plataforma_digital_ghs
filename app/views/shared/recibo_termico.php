<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Recibo GHS - #<?= $data['p']['id'] ?></title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; width: 300px; margin: 0; padding: 10px; font-size: 12px; }
        .header { text-align: center; border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 10px; }
        .logo { font-weight: bold; font-size: 16px; display: block; }
        .info { margin-bottom: 5px; }
        .total { font-size: 14px; font-weight: bold; border-top: 1px dashed #000; padding-top: 5px; margin-top: 10px; }
        .footer { text-align: center; font-size: 10px; margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="background: #f8f9fa; padding: 10px; text-align: center; border-bottom: 1px solid #ddd; margin-bottom: 20px; width: 100%;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">IMPRIMIR RECIBO</button>
    </div>

    <div class="header">
        <span class="logo">GHS - ESPF</span>
        <span>Complexo Escolar GHS</span><br>
        <span>Huambo, Angola</span><br>
        <small>Contribuinte: 5000123456</small>
    </div>

    <div class="info"><strong>RECIBO Nº:</strong> <?= str_pad($data['p']['id'], 6, '0', STR_PAD_LEFT) ?></div>
    <div class="info"><strong>DATA:</strong> <?= date('d/m/Y H:i', strtotime($data['p']['data_pagamento'] ?? $data['p']['data_criacao'])) ?></div>
    <div class="info"><strong>CLIENTE:</strong> <?= htmlspecialchars($data['p']['estudante_nome'] ?? 'N/A') ?></div>
    <hr style="border: 0; border-top: 1px dashed #000;">
    
    <div class="info"><strong>DESCRIÇÃO:</strong></div>
    <div class="info"><?= htmlspecialchars($data['p']['descricao']) ?></div>
    <div class="info">REF: <?= $data['p']['mes_referencia'] ?>/<?= $data['p']['ano_letivo'] ?></div>
    
    <div class="total">TOTAL PAGO: <?= number_format($data['p']['valor'], 0, ',', '.') ?> XOF</div>
    <div class="info">FORMA: <?= htmlspecialchars($data['p']['forma_pagamento'] ?? 'Numerário') ?></div>

    <div class="footer">
        Obrigado pela preferência!<br>
        Este documento não serve de factura.<br>
        Validado por: <?= $_SESSION['user_name'] ?? 'SISTEMA' ?>
    </div>
</body>
</html>
