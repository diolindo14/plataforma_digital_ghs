<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Recibo GHS - #<?= str_pad($data['pagamento']['id'] ?? 0, 6, '0', STR_PAD_LEFT) ?></title>
    <style>
        /* ── Thermal POS Printer Optimizer (80mm) ─────────────────── */
        @page { 
            size: 80mm auto; 
            margin: 0; 
        }
        
        * { box-sizing: border-box; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        
        body { 
            font-family: 'Courier New', Courier, monospace; /* Standard POS Font */
            background-color: #ffffff; 
            color: #000; 
            margin: 0; 
            padding: 5mm;
            width: 80mm;
            font-size: 11pt;
            line-height: 1.2;
        }

        .thermal-receipt {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .header {
            text-align: center;
            margin-bottom: 5mm;
            width: 100%;
        }

        .logo-img {
            width: 35mm;
            margin-bottom: 2mm;
            filter: grayscale(100%); /* Thermal printers only print black */
        }

        .brand-info h2 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .brand-info p {
            margin: 1mm 0;
            font-size: 9pt;
        }

        .divider {
            width: 100%;
            border-top: 1px dashed #000;
            margin: 3mm 0;
        }

        .receipt-info {
            width: 100%;
            margin-bottom: 4mm;
        }

        .receipt-title {
            font-size: 13pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 2mm;
            text-decoration: underline;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1mm;
            font-size: 10pt;
        }

        .label { font-weight: bold; }
        .value { text-align: right; overflow-wrap: break-word; max-width: 50mm; }

        .item-table {
            width: 100%;
            margin: 4mm 0;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 2mm;
        }

        .total-section {
            width: 100%;
            text-align: center;
            border: 2px solid #000;
            padding: 3mm;
            margin: 4mm 0;
        }

        .total-amount {
            font-size: 18pt;
            font-weight: 900;
        }

        .footer {
            text-align: center;
            font-size: 9pt;
            margin-top: 5mm;
        }

        .barcode {
            margin: 5mm 0;
            font-family: 'Libre Barcode 39', cursive;
            font-size: 24pt;
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
        }

        @media print {
            .controls { display: none !important; }
            body { padding: 2mm; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="controls">
        <button class="btn" onclick="window.print()">IMPRIMIR POS</button>
        <button class="btn" onclick="window.close()">FECHAR</button>
    </div>

    <div class="thermal-receipt">
        <div class="header">
            <img src="<?= URL_ROOT ?>/public/img/logo.jpg" alt="Logo" class="logo-img">
            <div class="brand-info">
                <h2>GHS ÉDUCATION</h2>
                <p>Ensino Digital & Tecnologia</p>
                <p>NIF: 0987654321</p>
                <p>Tel: +244 9XX XXX XXX</p>
            </div>
        </div>

        <div class="divider"></div>

        <div class="receipt-info">
            <div class="receipt-title">RECIBO DE PAGAMENTO</div>
            <div class="info-row">
                <span class="label">N.º RECIBO:</span>
                <span class="value">#<?= str_pad($data['pagamento']['id'] ?? 0, 6, '0', STR_PAD_LEFT) ?></span>
            </div>
            <div class="info-row">
                <span class="label">DATA:</span>
                <span class="value"><?= date('d/m/Y H:i', strtotime($data['pagamento']['data_pagamento'] ?? 'now')) ?></span>
            </div>
            <div class="info-row">
                <span class="label">OPERADOR:</span>
                <span class="value"><?= htmlspecialchars($data['pagamento']['registado_por_nome'] ?? 'SI') ?></span>
            </div>
        </div>

        <div class="divider"></div>

        <div class="receipt-info">
            <div class="info-row">
                <span class="label">ALUNO:</span>
                <span class="value"><?= strtoupper(htmlspecialchars($data['pagamento']['estudante_nome'] ?? '---')) ?></span>
            </div>
            <div class="info-row">
                <span class="label">ID ALUNO:</span>
                <span class="value">#<?= $data['pagamento']['estudante_id'] ?? '---' ?></span>
            </div>
        </div>

        <div class="divider"></div>

        <div class="item-table">
            <div class="item-row">
                <span>DESCRIÇÃO</span>
                <span>TOTAL</span>
            </div>
            <div class="info-row" style="margin-top: 2mm;">
                <span style="font-size: 9pt; width: 45mm;"><?= htmlspecialchars($data['pagamento']['descricao'] ?? 'Serviço Académico') ?></span>
                <span class="value"><?= number_format($data['pagamento']['valor'] ?? 0, 0, ',', '.') ?></span>
            </div>
            <?php if(!empty($data['pagamento']['mes_referencia'])): ?>
            <div class="info-row">
                <span class="label">MES:</span>
                <span class="value"><?= htmlspecialchars($data['pagamento']['mes_referencia']) ?></span>
            </div>
            <?php endif; ?>
        </div>

        <div class="total-section">
            <div style="font-size: 10pt;">TOTAL PAGO (XOF)</div>
            <div class="total-amount"><?= number_format($data['pagamento']['valor'] ?? 0, 0, ',', '.') ?></div>
        </div>

        <div class="info-row">
            <span class="label">METODO:</span>
            <span class="value"><?= htmlspecialchars($data['pagamento']['forma_pagamento'] ?? 'Numerário') ?></span>
        </div>

        <div class="divider"></div>

        <div class="footer">
            <p><strong>OBRIGADO PELA PREFERÊNCIA!</strong></p>
            <p style="font-size: 8pt; margin-top: 2mm;">
                Conservar este talão como prova oficial.<br>
                Software GHS - Versão 2.4.0
            </p>
        </div>

        <div class="divider" style="border-top-style: dotted;"></div>
    </div>

</body>
</html>
