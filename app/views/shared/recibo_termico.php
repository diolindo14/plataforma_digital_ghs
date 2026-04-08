<?php
/**
 * GHS - Modernização do Recibo de Pagamento (Térmico)
 * Layout corrigido e adaptado para o novo padrão gráfico (Pilar 4).
 * Inclui sistema de autenticação via QR Code offline.
 */
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pagamento GHS - #<?= str_pad($data['p']['id'], 6, '0', STR_PAD_LEFT) ?></title>
    <!-- QRCode Lib (Local/Offline) -->
    <script src="<?= URL_ROOT ?>/public/js/qrcode.min.js"></script>
    <style>
        :root {
            --ghs-green: #2ecc71;
            --ghs-dark: #2c3e50;
            --text-color: #000;
        }

        @page { size: 80mm auto; margin: 0; }
        
        body { 
            font-family: 'Courier New', Courier, monospace; 
            width: 70mm; 
            margin: 0 auto; 
            padding: 5mm; 
            font-size: 11px; 
            line-height: 1.4;
            color: var(--text-color);
            background: #fff;
        }

        .no-print { 
            background: #f1f5f9; 
            padding: 10px; 
            margin-bottom: 20px; 
            text-align: center; 
            border-radius: 8px;
            width: 100%;
            box-sizing: border-box;
        }

        .btn-print { 
            background: #10b981; 
            color: #fff; 
            padding: 8px 16px; 
            border: none; 
            border-radius: 6px; 
            font-weight: 700; 
            cursor: pointer;
            font-family: sans-serif;
        }

        .header { text-align: center; margin-bottom: 5mm; }
        
        .logo-container { 
            margin-bottom: 3mm;
            text-align: center;
        }
        
        .logo-container img { 
            width: 30mm; 
            height: 30mm; 
            border-radius: 50%;
            object-fit: contain;
        }

        .company-name { font-size: 16px; font-weight: 900; letter-spacing: 1px; display: block; }
        .tagline { font-size: 11px; color: #555; }
        .tel { font-size: 12px; margin-top: 2mm; display: block; }

        .divider {
            border-top: 1px dashed #000;
            margin: 4mm 0;
        }

        .title {
            text-align: center;
            font-size: 14px;
            font-weight: 900;
            text-decoration: underline;
            margin-bottom: 4mm;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1.5mm;
        }

        .info-label {
            font-weight: bold;
            width: 35%;
        }

        .info-value {
            text-align: right;
            width: 65%;
            font-weight: 500;
        }

        .desc-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 4mm;
        }

        .desc-table th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding: 1mm 0;
            font-weight: 900;
        }

        .desc-table td {
            padding: 2mm 0;
            border-bottom: 0.5px solid #eee;
        }

        .text-right {
            text-align: right;
        }

        .total-box {
            border: 1.5px solid #000;
            padding: 3mm;
            margin: 5mm 0;
            text-align: center;
        }

        .total-box .box-label {
            font-size: 10px;
            font-weight: bold;
            display: block;
        }

        .total-box .box-value {
            font-size: 20px;
            font-weight: 900;
        }

        .method-row {
            margin: 4mm 0;
            display: flex;
            justify-content: space-between;
        }

        .qr-section {
            text-align: center;
            padding: 5mm 0;
        }

        #qrcode {
            display: inline-block;
            padding: 2mm;
            background: #fff;
        }

        .qr-caption {
            font-size: 9px;
            margin-top: 2mm;
            display: block;
            color: #666;
        }

        .footer-msg {
            text-align: center;
            font-weight: bold;
            margin-top: 5mm;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <!-- Painel de Impressão (Não sai no papel) -->
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">IMPRIMIR AGORA</button>
    </div>

    <!-- Interface GHS Éducation -->
    <div class="header">
        <div class="logo-container">
            <img src="<?= URL_ROOT ?>/public/img/logo.jpg" alt="Logo GHS">
        </div>
        <span class="company-name">GHS "O futuro é hoje"</span>
        <span class="tagline">Ensino Digital & Tecnologia</span><br>
        <span class="tel">Tel: +245 95529 54 75</span>
    </div>

    <div class="divider"></div>

    <div class="title">RECIBO DE PAGAMENTO</div>

    <div class="info-row">
        <span class="info-label">N.° RECIBO:</span>
        <span class="info-value">#<?= str_pad($data['p']['id'], 6, '0', STR_PAD_LEFT) ?></span>
    </div>

    <div class="info-row">
        <span class="info-label">DATA:</span>
        <span
            class="info-value"><?= date('d/m/Y H:i', strtotime($data['p']['data_pagamento'] ?? $data['p']['data_criacao'])) ?></span>
    </div>

    <div class="info-row">
        <span class="info-label">OPERADOR:</span>
        <span
            class="info-value"><?= htmlspecialchars($data['p']['registado_por_nome'] ?? $_SESSION['user_name'] ?? 'Malam Djob') ?></span>
    </div>

    <div class="divider" style="margin: 3mm 0;"></div>

    <div class="info-row" style="align-items: flex-start; margin-bottom: 3mm;">
        <span class="info-label">ALUNO:</span>
        <span class="info-value"
            style="text-align: right; line-height: 1.2;"><?= htmlspecialchars($data['p']['estudante_nome'] ?? 'N/A') ?></span>
    </div>

    <div class="info-row">
        <span class="info-label">ID ALUNO:</span>
        <span class="info-value">#<?= $data['p']['estudante_id'] ?? '137' ?></span>
    </div>

    <div class="divider" style="margin: 3mm 0;"></div>

    <table class="desc-table">
        <thead>
            <tr>
                <th>DESCRIÇÃO</th>
                <th class="text-right">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?= htmlspecialchars($data['p']['descricao'] ?? 'Propina') ?></td>
                <td class="text-right"><?= number_format($data['p']['valor'], 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>

    <div class="total-box">
        <span class="box-label">TOTAL PAGO (XOF)</span>
        <span class="box-value"><?= number_format($data['p']['valor'], 0, ',', '.') ?></span>
    </div>

    <div class="method-row">
        <span class="info-label">MÉTODO:</span>
        <span class="info-value"
            style="text-align: right;"><?= htmlspecialchars($data['p']['forma_pagamento'] ?? 'Numerário') ?></span>
    </div>

    <div class="qr-section">
        <div id="qrcode"></div>
        <span class="qr-caption">Autenticação Digital GHS</span>
    </div>

    <div class="divider" style="border-style: dotted;"></div>

    <div class="footer-msg">
        OBRIGADO PELA PREFERÊNCIA!<br>
        <span style="font-size: 9px; font-weight: normal; margin-top: 1mm; display: block;">Conserve este talão como
            prova oficial.</span>
    </div>

    <!-- Script de Geração de QR Code Online/Offline -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Se a lib qrcodejs falhar por não existir (offline a carregar CDNs), 
            // podemos injetar ou aguardar. Pero aqui garantimos via local no public/js/
            const qrcode = new QRCode(document.getElementById("qrcode"), {
                text: "GHS:<?= $data['p']['id'] ?>;<?= $data['p']['estudante_id'] ?>;<?= $data['p']['valor'] ?>",
                width: 128,
                height: 128,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        });
    </script>
</body>

</html>