<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Pagamento - GHS</title>
    <style>
        @page { size: 14.8cm 10.5cm; margin: 4mm; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 20px; box-sizing: border-box; display: flex; justify-content: center; }
        .receipt-box { width: 14cm; min-height: 9.5cm; background: white; padding: 12px; box-sizing: border-box; border: 1px solid #e2e8f0; border-radius: 8px; display: flex; flex-direction: column; position: relative; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #10b981; padding-bottom: 8px; margin-bottom: 10px; }
        .logo-container { display: flex; align-items: center; gap: 8px; }
        .logo-img { width: 35px; height: 35px; border-radius: 6px; border: 1px solid #10b981; object-fit: cover; }
        .school-name { font-size: 14px; font-weight: 800; color: #0f172a; margin: 0; letter-spacing: -0.5px; }
        .info { margin-bottom: 10px; line-height: 1.3; font-size: 9px; }
        .info p { margin: 2px 0; }
        .footer { margin-top: auto; text-align: center; font-size: 8px; color: #64748b; border-top: 1px solid #e2e8f0; padding-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th { background-color: #f1f5f9; padding: 4px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 8px; text-transform: uppercase; color: #64748b; }
        td { padding: 4px; text-align: left; font-size: 9px; border-bottom: 1px dashed #e2e8f0; }
        .total-row td { background-color: #f8fafc; font-size: 12px; font-weight: 800; color: #10b981; border: none; }
        
        @media print { 
            .no-print { display: none; } 
            body { background: white; padding: 0; margin: 0; display: block; } 
            .receipt-box { border: none; padding: 0; width: 100%; height: 100%; border-radius: 0; } 
        }
        
        .btn-close-window { 
            position: absolute; top: -40px; right: 0; padding: 8px 15px; 
            background: #ef4444; color: white; border: none; border-radius: 6px; 
            cursor: pointer; font-size: 12px; font-weight: 700; transition: all 0.2s;
            display: flex; align-items: center; gap: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn-close-window:hover { background: #dc2626; }
    </style>
</head>
<body onload="window.print()">
    <div style="position: relative;">
        <button class="btn-close-window no-print" onclick="fecharOuVoltar()">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            Fechar Janela
        </button>

        <div class="receipt-box">
            <div class="header">
                <div class="logo-container">
                    <img src="<?= URL_ROOT ?>/img/logo.jpg" alt="Logo Escola" class="logo-img">
                    <div class="school-name">Green Hard & Softh</div>
                </div>
                <div style="text-align: right; display: flex; flex-direction: column; justify-content: center;">
                    <h1 style="margin:0; font-size: 16px; color: #0f172a;">RECIBO</h1>
                    <p style="margin:2px 0 0 0; color: #64748b; font-weight: 600; font-size: 9px;">Ref: #<?= str_pad($data['pagamento']['id'] ?? 0, 6, '0', STR_PAD_LEFT) ?></p>
                </div>
            </div>

            <div class="info">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div>
                        <p style="color: #64748b; font-size: 8px; text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">DADOS DO ALUNO</p>
                        <p><strong>Nome:</strong> <?= htmlspecialchars($data['pagamento']['estudante_nome'] ?? $_SESSION['user_name'] ?? 'N/A') ?></p>
                        <p><strong>BI:</strong> <?= htmlspecialchars($data['pagamento']['bi'] ?? 'N/A') ?></p>
                    </div>
                    <div style="text-align: right;">
                        <p style="color: #64748b; font-size: 8px; text-transform: uppercase; font-weight: 700; margin-bottom: 2px;">DETALHES DA EMISSÃO</p>
                        <p><strong>Emitido em:</strong> <?= date('d/m/Y H:i') ?></p>
                        <p><strong>Operador:</strong> Sistema Digital GHS</p>
                    </div>
                </div>
                
                <hr style="border: 0; border-top: 1px dotted #cbd5e1; margin: 8px 0;">
                
                <p style="margin-bottom: 6px;"><strong>Período Referente:</strong> <?php 
                    $m = $data['pagamento']['mes_referencia'] ?? null;
                    if ($m) {
                        if (is_numeric($m)) {
                            $meses = [1=>'Janeiro', 2=>'Fevereiro', 3=>'Março', 4=>'Abril', 5=>'Maio', 6=>'Junho', 7=>'Julho', 8=>'Agosto', 9=>'Setembro', 10=>'Outubro', 11=>'Novembro', 12=>'Dezembro'];
                            echo $meses[(int)$m] ?? $m;
                        } else {
                            echo htmlspecialchars($m);
                        }
                    } else {
                        echo date('F', strtotime($data['pagamento']['data_pagamento'] ?? $data['pagamento']['data_criacao'] ?? 'now'));
                    }
                    echo ' / ' . htmlspecialchars($data['pagamento']['ano_letivo'] ?? date('Y'));
                ?></p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Descrição dos Serviços / Taxas</th>
                        <th style="text-align: right;">Valor (XOF)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-weight: 600; font-size: 10px;"><?= htmlspecialchars($data['pagamento']['descricao'] ?? 'N/A') ?></td>
                        <td style="text-align: right; font-weight: 600;"><?= number_format($data['pagamento']['valor'] ?? 0, 0, ',', '.') ?></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="total-row">
                        <td style="text-align: right; font-weight: 700;">TOTAL PAGO:</td>
                        <td style="text-align: right;"><?= number_format($data['pagamento']['valor'] ?? 0, 0, ',', '.') ?> XOF</td>
                    </tr>
                </tfoot>
            </table>

            <div style="margin-top: 10px; padding: 6px; background-color: #f8fafc; border-radius: 4px; border-left: 3px solid #10b981;">
                <p style="margin: 0; font-size: 7px; color: #475569;"><strong>Obs:</strong> Pagamento validado digitalmente. Serve como prova oficial de quitação.</p>
            </div>

            <div class="footer">
                <p style="margin-bottom: 2px;">Este documento foi gerado eletronicamente e é válido sem assinatura manuscrita.</p>
                <p style="font-weight: 700;">&copy; <?= date('Y') ?> Green Hard & Softh - O futuro é Hoje</p>
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
            // Fallback para fechar se ainda estiver aberto (pode ser bloqueado pelo browser)
            setTimeout(function() {
                if(!window.closed) window.location.href = '<?= URL_ROOT ?>/estudante';
            }, 300);
        }
    </script>
</body>
</html>
