<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Recibo Matrícula - <?= $data['ano_letivo'] ?></title>
    <style>
        /* ── Thermal POS Printer Optimizer (80mm) ─────────────────── */
        @page { 
            size: 80mm auto; 
            margin: 0; 
        }
        
        * { box-sizing: border-box; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        
        body { 
            font-family: 'Courier New', Courier, monospace;
            background-color: #ffffff; 
            color: #000; 
            margin: 0; 
            padding: 2mm;
            width: 80mm;
            font-size: 10pt;
            line-height: 1.1;
        }

        .thermal-receipt {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-left: 1px dotted #999;
            border-right: 1px dotted #999;
            padding: 0 2mm;
            margin-left: 1mm;
        }

        .header {
            text-align: center;
            margin-bottom: 3mm;
            width: 100%;
        }

        .logo-img {
            width: 32mm;
            margin-bottom: 2mm;
        }

        .brand-info h2 {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .brand-info p {
            margin: 1mm 0;
            font-size: 8.5pt;
        }

        .divider {
            width: 100%;
            border-top: 1px dashed #000;
            margin: 2mm 0;
        }

        .receipt-info {
            width: 100%;
            margin-bottom: 2mm;
        }

        .receipt-title {
            font-size: 12pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1mm;
            text-decoration: underline;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8mm;
            font-size: 9.5pt;
        }

        .label { font-weight: bold; }
        .value { text-align: right; overflow-wrap: break-word; max-width: 50mm; }

        .item-table {
            width: 100%;
            margin: 2mm 0;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5mm;
        }
        
        .item-desc {
            font-size: 8.5pt;
            max-width: 45mm;
            word-wrap: break-word;
        }

        .item-val {
            font-size: 9pt;
            font-weight: bold;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 10pt;
            border-bottom: 1px solid #000;
            padding-bottom: 1mm;
            margin-bottom: 1.5mm;
        }

        .total-section {
            width: 100%;
            text-align: center;
            border: 1.5px solid #000;
            padding: 2mm;
            margin: 3mm 0;
        }

        .total-amount {
            font-size: 16pt;
            font-weight: 900;
        }

        .qr-section {
            text-align: center;
            margin: 3mm 0;
        }

        .qr-code {
            width: 30mm;
            height: 30mm;
        }

        .footer {
            text-align: center;
            font-size: 8.5pt;
            margin-top: 2mm;
        }

        /* ── Controls (Hidden in Print) ────────────────────────── */
        .controls {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 100;
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            border: 1px solid #000;
            background: #fff;
            color: #000;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        @media print {
            .controls { display: none !important; }
            body { padding: 1mm; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="controls">
        <button class="btn" onclick="window.print()">IMPRIMIR POS</button>
        <button class="btn" onclick="fecharRecibo()">FECHAR</button>
    </div>

    <?php 
        $primeiro = $data['pagamentos'][0];
        $total = 0;
        $descricoes = [];
        $ids = [];
        foreach($data['pagamentos'] as $p) {
            $total += $p['valor'];
            $ids[] = $p['id'];
        }
        $idsStr = implode("-", $ids);
    ?>

    <div class="thermal-receipt">
        <div class="header">
            <img src="<?= URL_ROOT ?>/img/logo.jpg" alt="Logo" class="logo-img">
            <div class="brand-info">
                <h2>GHS "O futuro é hoje"</h2>
                <p>Ensino Digital & Tecnologia</p>
                <p>Tel: +245 95529 54 75</p>
            </div>
        </div>

        <div class="divider"></div>

        <div class="receipt-info">
            <div class="receipt-title">RECIBO DE MATRÍCULA</div>
            <div class="info-row">
                <span class="label">ANO LECTIVO:</span>
                <span class="value"><?= htmlspecialchars($data['ano_letivo']) ?></span>
            </div>
            <div class="info-row">
                <span class="label">DATA:</span>
                <span class="value"><?= date('d/m/Y H:i', strtotime($primeiro['data_pagamento'])) ?></span>
            </div>
            <div class="info-row">
                <span class="label">OPERADOR:</span>
                <span class="value"><?= htmlspecialchars($primeiro['registado_por_nome'] ?? 'Secretaria Automática') ?></span>
            </div>
        </div>

        <div class="divider"></div>

        <div class="receipt-info">
            <div class="info-row">
                <span class="label">ALUNO:</span>
                <span class="value"><?= strtoupper(htmlspecialchars($primeiro['estudante_nome'] ?? '---')) ?></span>
            </div>
            <div class="info-row">
                <span class="label">ID ALUNO:</span>
                <span class="value">#<?= $primeiro['estudante_id'] ?? '---' ?></span>
            </div>
        </div>

        <div class="divider"></div>

        <div class="item-table">
            <div class="item-header">
                <span>DESCRIÇÃO</span>
                <span>VALOR</span>
            </div>
            
            <?php foreach($data['pagamentos'] as $p): ?>
            <div class="item-row">
                <span class="item-desc"><?= htmlspecialchars(str_replace(' - Acto de Matrícula', '', $p['descricao'])) ?></span>
                <span class="item-val"><?= number_format($p['valor'] ?? 0, 0, ',', '.') ?></span>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="total-section">
            <div style="font-size: 9pt; font-weight: bold;">TOTAL PAGO (XOF)</div>
            <div class="total-amount"><?= number_format($total, 0, ',', '.') ?></div>
        </div>

        <div class="info-row" style="width: 100%;">
            <span class="label">MÉTODO:</span>
            <span class="value"><?= htmlspecialchars($primeiro['forma_pagamento'] ?? 'Numerário') ?></span>
        </div>

        <div class="qr-section">
            <?php 
                $qrData = "RECIBOMATRICULA:{$idsStr}|VALOR:{$total}|ALUNO:{$primeiro['estudante_id']}";
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=" . urlencode($qrData);
            ?>
            <img src="<?= $qrUrl ?>" alt="QR Code" class="qr-code" style="width: 25mm; height: 25mm;">
            <p style="font-size: 7pt; margin-top: 0.5mm; opacity: 0.7;">Autenticação Digital GHS</p>
        </div>

        <div class="divider" style="margin: 1mm 0;"></div>

        <div class="footer" style="margin-top: 1mm;">
            <p style="margin: 0;"><strong>OBRIGADO PELA PREFERÊNCIA!</strong></p>
            <p style="font-size: 8pt; margin-top: 0.5mm;">
                Conserve este talão como prova oficial.
            </p>
        </div>

        <div class="divider" style="border-top-style: dotted; margin: 1mm 0 2mm 0;"></div>
    </div>

    <script>
        function fecharRecibo() {
            if (window.opener || window.history.length > 1) {
                window.close();
                setTimeout(() => { history.back(); }, 100);
            } else {
                window.location.href = '<?= URL_ROOT ?>/estudante';
            }
        }
    </script>
</body>
</html>
