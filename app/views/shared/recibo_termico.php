<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Recibo GHS - #<?= str_pad($data['p']['id'] ?? 0, 6, '0', STR_PAD_LEFT) ?></title>
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
            border: 1.5px dashed #000; /* Pontilhado/Tracejado para guia de corte */
            padding: 3mm 4mm;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 2mm;
            width: 100%;
        }

        .logo-img {
            width: 28mm;
            margin-bottom: 2mm;
            filter: contrast(1.1);
        }

        .brand-info h2 {
            margin: 0;
            font-size: 13pt;
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
            font-size: 11pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 2mm;
            text-decoration: underline;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1mm;
            font-size: 9.5pt;
            align-items: flex-start;
        }

        .label { font-weight: bold; }
        .value { text-align: right; overflow-wrap: break-word; max-width: 45mm; }

        .item-table {
            width: 100%;
            margin: 3mm 0;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 10pt;
            border-bottom: 1.5px solid #000;
            padding-bottom: 1mm;
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

        .qr-section {
            text-align: center;
            margin: 4mm 0;
        }

        #qrcode {
            display: inline-block;
            margin-bottom: 1.5mm;
        }

        .footer {
            text-align: center;
            font-size: 9pt;
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
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            background: #10b981;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            font-size: 10pt;
        }
        .btn-close-view { background: #ef4444; }
        .btn-regularizar { background: #f59e0b; color: #000; font-size: 9pt; }

        @media print {
            .controls { display: none !important; }
            body { padding: 0; width: 80mm; }
            .thermal-receipt { 
                border: 1.5px dashed #000 !important; 
                padding: 4mm;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="controls">
        <button class="btn" onclick="window.print()">IMPRIMIR POS</button>
        <?php
        // Mostrar botão de regularização apenas se TAE ou Selos estiverem em falta
        $itens_db = isset($data['itens']) ? $data['itens'] : [$data['p']];
        $temTAE    = false;
        $temSelos  = false;
        foreach ($itens_db as $it) {
            if (stripos($it['descricao'], 'TAE') !== false) $temTAE   = true;
            if (stripos($it['descricao'], 'Selos') !== false) $temSelos = true;
        }
        if (!$temTAE || !$temSelos):
        ?>
        <form method="POST" action="<?= URL_ROOT ?>/admin/regularizarTaxas" style="display:inline;">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="hidden" name="pagamento_id" value="<?= $data['p']['id'] ?>">
            <input type="hidden" name="estudante_id" value="<?= $data['p']['estudante_id'] ?>">
            <input type="hidden" name="ano_letivo"   value="<?= $data['p']['ano_letivo'] ?>">
            <button type="submit" class="btn btn-regularizar"
                onclick="return confirm('Inserir TAE (1.000 XOF) e Selos de Estado (2.000 XOF) para este aluno?')">
                ⚠️ Regularizar TAE + Selos
            </button>
        </form>
        <?php endif; ?>
        <button class="btn btn-close-view" onclick="fecharRecibo()">FECHAR</button>
    </div>

    <div class="thermal-receipt">
        <div class="header">
            <img src="<?= URL_ROOT ?>/public/img/logo.jpg" alt="Logo" class="logo-img">
            <div class="brand-info">
                <h2>GHS "O futuro é hoje"</h2>
                <p>Ensino Digital & Tecnologia</p>
                <p>Tel: +245 95529 54 75</p>
                <p>Guiné-Bissau</p>
            </div>
        </div>

        <div class="divider"></div>

        <div class="receipt-info">
            <div class="receipt-title">RECIBO DE PAGAMENTO</div>
            <div class="info-row">
                <span class="label">N.º TALÃO:</span>
                <span class="value">#<?= str_pad($data['p']['id'] ?? 0, 6, '0', STR_PAD_LEFT) ?></span>
            </div>
            <div class="info-row">
                <span class="label">DATA/HORA:</span>
                <span class="value"><?= date('d/m/Y H:i', strtotime($data['p']['data_pagamento'] ?? 'now')) ?></span>
            </div>
            <div class="info-row">
                <span class="label">OPERADOR:</span>
                <span class="value"><?= htmlspecialchars($data['p']['registado_por_nome'] ?? 'Secretaria') ?></span>
            </div>
        </div>

        <div class="divider"></div>

        <div class="receipt-info">
            <div class="info-row">
                <span class="label">ALUNO:</span>
                <span class="value"><?= strtoupper(htmlspecialchars($data['p']['estudante_nome'] ?? '---')) ?></span>
            </div>
            <div class="info-row">
                <span class="label">ID:</span>
                <span class="value">#<?= $data['p']['estudante_id'] ?? '---' ?></span>
            </div>
        </div>

        <div class="divider"></div>

        <div class="item-table">
            <div class="item-header">
                <span>DESCRIÇÃO</span>
                <span>XOF</span>
            </div>
            <?php 
            $totalGeral = 0;
            $itemsToShow = isset($data['itens']) ? $data['itens'] : [$data['p']];
            foreach($itemsToShow as $item): 
                $totalGeral += $item['valor'];
            ?>
            <div class="info-row">
                <span style="font-size: 8.5pt; width: 42mm; font-weight: bold; line-height: 1.2;"><?= htmlspecialchars($item['descricao']) ?></span>
                <span class="value fw-bold"><?= number_format($item['valor'], 0, ',', '.') ?></span>
            </div>
            <?php endforeach; ?>

            <?php 
            // Taxas obrigatórias fixas — sempre no recibo
            $taxasFixas = [
                ['descricao' => 'Taxa de Associação de Estudante (TAE)', 'valor' => 1000],
                ['descricao' => 'Selos de Estado (Legalização)',          'valor' => 2000],
            ];
            foreach ($taxasFixas as $tf):
                $totalGeral += $tf['valor'];
            ?>
            <div class="info-row">
                <span style="font-size: 8.5pt; width: 42mm; font-weight: bold; line-height: 1.2;"><?= $tf['descricao'] ?></span>
                <span class="value fw-bold"><?= number_format($tf['valor'], 0, ',', '.') ?></span>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="total-section">
            <div style="font-size: 10pt; font-weight: bold; margin-bottom: 1mm;">TOTAL PAGO (XOF)</div>
            <div class="total-amount"><?= number_format($totalGeral, 0, ',', '.') ?></div>
        </div>

        <div class="info-row" style="width: 100%;">
            <span class="label">MÉTODO:</span>
            <span class="value"><?= htmlspecialchars($data['p']['forma_pagamento'] ?? 'Numerário') ?></span>
        </div>

        <div class="qr-section">
            <div id="qrcode">
                <?php 
                    require_once __DIR__ . '/../../helpers/QrCodeHelper.php';
                    echo QrCodeHelper::getSvg("GHS-VERIFY:".$data['p']['id'] ?? '0', 95); 
                ?>
            </div>
            <p style="font-size: 7.5pt; margin-top: 1mm; opacity: 0.8; font-weight: bold;">AUTENTICAÇÃO DIGITAL GHS</p>
        </div>

        <div class="divider"></div>

        <div class="footer">
            <p style="margin: 0; font-weight: bold;">OBRIGADO PELA PREFERÊNCIA!</p>
            <p style="font-size: 8.5pt; margin-top: 1.5mm;">
                O futuro é hoje! Conservar talão.
            </p>
        </div>

        <div class="divider" style="border-top-style: dotted;"></div>
    </div>

    <script>
        function fecharRecibo() {
            if (window.opener) {
                window.close();
            } else {
                history.back();
            }
        }
    </script>
</body>
</html>