<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Recibo GHS - #<?= str_pad($data['pagamento']['id'] ?? 0, 6, '0', STR_PAD_LEFT) ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        /* ── Print Settings ────────────────────────────────────── */
        @page { 
            size: A4 portrait; 
            margin: 0; /* Removes browser headers/footers */
        }
        
        * { box-sizing: border-box; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f1f5f9; 
            color: #1e293b; 
            margin: 0; 
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        /* ── Receipt Layout ────────────────────────────────────── */
        .receipt-container {
            width: 100%;
            max-width: 800px;
            margin-top: 50px;
            position: relative;
        }

        .receipt-card {
            background: #ffffff;
            width: 100%;
            height: auto;
            min-height: 12cm; /* Fits comfortably in A4 */
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* ── Decorative Elements ────────────────────────────────── */
        .receipt-card::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 6px;
            background: linear-gradient(90deg, #10b981 0%, #3b82f6 100%);
            border-radius: 16px 16px 0 0;
        }

        /* ── Header ────────────────────────────────────────────── */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-img {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid #10b981;
        }

        .brand-info h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .brand-info p {
            margin: 0;
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
        }

        .receipt-title {
            text-align: right;
        }

        .receipt-title h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 900;
            color: #10b981;
            line-height: 1;
        }

        .receipt-title span {
            font-size: 13px;
            font-weight: 600;
            color: #94a3b8;
        }

        /* ── Information Grid ──────────────────────────────────── */
        .info-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 40px;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
        }

        .info-section h4 {
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin: 0 0 10px 0;
        }

        .info-item {
            display: flex;
            margin-bottom: 5px;
            font-size: 13px;
        }

        .info-label {
            color: #64748b;
            font-weight: 500;
            width: 80px;
        }

        .info-value {
            color: #1e293b;
            font-weight: 600;
        }

        /* ── Table ─────────────────────────────────────────────── */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th {
            text-align: left;
            padding: 12px 15px;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            border-bottom: 2px solid #f1f5f9;
        }

        td {
            padding: 18px 15px;
            font-size: 14px;
            color: #334155;
            border-bottom: 1px solid #f1f5f9;
        }

        .total-row td {
            background: #f8fafc;
            border-bottom: none;
            padding: 25px 15px;
        }

        .total-label {
            font-size: 16px;
            font-weight: 700;
            color: #64748b;
            text-align: right;
        }

        .total-amount {
            font-size: 24px;
            font-weight: 800;
            color: #10b981;
            text-align: right;
        }

        /* ── Footer ────────────────────────────────────────────── */
        .receipt-footer {
            margin-top: auto;
            text-align: center;
            padding-top: 30px;
            border-top: 1px dashed #e2e8f0;
        }

        .status-badge {
            display: inline-block;
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            padding: 6px 16px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .legal-notice {
            font-size: 10px;
            color: #94a3b8;
            line-height: 1.5;
            margin: 0;
        }

        .copyright {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            margin-top: 10px;
        }

        /* ── Controls ──────────────────────────────────────────── */
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
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .btn-print { background: #10b981; color: white; }
        .btn-print:hover { background: #059669; transform: translateY(-1px); }

        .btn-back { background: #64748b; color: white; }
        .btn-back:hover { background: #475569; transform: translateY(-1px); }

        /* ── Media Queries ─────────────────────────────────────── */
        @media print {
            body { background: white; padding: 0; margin: 0; }
            .receipt-container { margin: 10mm auto; max-width: 100%; }
            .receipt-card { box-shadow: none; border: none; padding: 15mm; }
            .controls { display: none !important; }
            .receipt-card::before { left: 15mm; right: 15mm; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="controls">
        <button class="btn btn-print" onclick="window.print()">Imprimir Recibo</button>
        <button class="btn btn-back" onclick="fecharOuVoltar()">X</button>
    </div>

    <div class="receipt-container">
        <div class="receipt-card">
            <div class="header">
                <div class="brand">
                    <img src="<?= URL_ROOT ?>/img/logo.jpg" alt="GHS" class="logo-img">
                    <div class="brand-info">
                        <h2>GREEN HARD & SOFTH</h2>
                        <p>Plataforma de Gestão Digital Educacional</p>
                    </div>
                </div>
                <div class="receipt-title">
                    <h1>RECIBO</h1>
                    <span>#<?= str_pad($data['pagamento']['id'] ?? 0, 6, '0', STR_PAD_LEFT) ?></span>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-section">
                    <h4>Identificação do Aluno</h4>
                    <div class="info-item">
                        <span class="info-label">Nome:</span>
                        <span class="info-value"><?= htmlspecialchars($data['pagamento']['estudante_nome'] ?? 'Documento Interno') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Documento:</span>
                        <span class="info-value">BI / ID N.º <?= htmlspecialchars($data['pagamento']['bi'] ?? '---') ?></span>
                    </div>
                </div>
                <div class="info-section" style="border-left: 1px solid #e2e8f0; padding-left: 40px;">
                    <h4>Dados Financeiros</h4>
                    <div class="info-item">
                        <span class="info-label">Data:</span>
                        <span class="info-value"><?= date('d/m/Y H:i', strtotime($data['pagamento']['data_pagamento'] ?? 'now')) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Período:</span>
                        <span class="info-value"><?php 
                            $m = $data['pagamento']['mes_referencia'] ?? null;
                            if ($m) {
                                if (is_numeric($m)) {
                                    $meses = [1=>'Janeiro', 2=>'Fevereiro', 3=>'Março', 4=>'Abril', 5=>'Maio', 6=>'Junho', 7=>'Julho', 8=>'Agosto', 9=>'Setembro', 10=>'Outubro', 11=>'Novembro', 12=>'Dezembro'];
                                    echo $meses[(int)$m] ?? $m;
                                } else { echo htmlspecialchars($m); }
                            } else { echo date('F'); }
                            echo ' / ' . htmlspecialchars($data['pagamento']['ano_letivo'] ?? date('Y'));
                        ?></span>
                    </div>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th width="70%">Descrição da Transação / Servico</th>
                        <th style="text-align: right;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: #1e293b;"><?= htmlspecialchars($data['pagamento']['descricao'] ?? 'Serviço Académico') ?></div>
                            <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Pagamento processado via Sistema Digital de Gestão GHS.</div>
                        </td>
                        <td style="text-align: right; font-weight: 700;">
                            <?= number_format($data['pagamento']['valor'] ?? 0, 0, ',', '.') ?> XOF
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td class="total-label">TOTAL LIQUIDADO</td>
                        <td class="total-amount"><?= number_format($data['pagamento']['valor'] ?? 0, 0, ',', '.') ?> XOF</td>
                    </tr>
                </tfoot>
            </table>

            <div class="receipt-footer">
                <div class="status-badge">ORDEM DE PAGAMENTO VALIDADA</div>
                <p class="legal-notice">
                    Este recibo é gerado automaticamente pelo Sistema Digital GHS.<br>
                    Constitui prova oficial de quitação financeira para os fins devidos na instituição.
                </p>
                <p class="copyright">&copy; <?= date('Y') ?> Green Hard & Soft - O Futuro é Hoje</p>
            </div>
        </div>
    </div>

    <script>
        function fecharOuVoltar() {
            if (window.opener || window.history.length === 1) {
                window.close();
            } else {
                window.history.back();
            }
        }
    </script>
</body>
</html>
