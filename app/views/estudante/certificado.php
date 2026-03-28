<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado de Mérito - GHS</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ghs-primary: #10B981;
            --ghs-dark: #1E293B;
            --gold: #D4AF37;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f5f9;
            font-family: 'Outfit', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* A4 Landscape Dimensions */
        @page { 
            size: A4 landscape; 
            margin: 0; 
        }
        
        .certificate-container {
            width: 297mm;
            height: 210mm;
            background: #fff;
            position: relative;
            padding: 15mm;
            border: 10mm solid var(--ghs-dark);
            box-shadow: 0 20px 50px rgba(0,0,0,0.1);
            background-image: 
                radial-gradient(circle at 100% 100%, rgba(16, 185, 129, 0.03) 0%, transparent 40%),
                radial-gradient(circle at 0% 0%, rgba(59, 130, 246, 0.03) 0%, transparent 40%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Inner border ornament */
        .inner-border {
            position: absolute;
            top: 4mm; left: 4mm; right: 4mm; bottom: 4mm;
            border: 1px solid #e2e8f0;
            pointer-events: none;
        }

        .corner {
            position: absolute;
            width: 40mm;
            height: 40mm;
            border: 5mm solid var(--ghs-primary);
        }
        .top-left { top: -5mm; left: -5mm; border-right: 0; border-bottom: 0; }
        .top-right { top: -5mm; right: -5mm; border-left: 0; border-bottom: 0; }
        .bottom-left { bottom: -5mm; left: -5mm; border-right: 0; border-top: 0; }
        .bottom-right { bottom: -5mm; right: -5mm; border-left: 0; border-top: 0; }

        .header { text-align: center; }
        .logo { width: 80px; margin-bottom: 10px; border-radius: 12px; }
        .institution { font-size: 1.2rem; font-weight: 800; color: var(--ghs-dark); text-transform: uppercase; letter-spacing: 5px; margin-bottom: 2px; }
        .sub-header { font-size: 0.8rem; color: #64748b; text-transform: uppercase; letter-spacing: 3px; }
        
        .title-area { text-align: center; margin-top: 15px; }
        .cert-title { font-family: 'Playfair Display', serif; font-size: 4rem; color: var(--ghs-primary); margin: 0; line-height: 1; }
        .cert-subtitle { font-size: 1.1rem; color: #64748b; text-transform: uppercase; letter-spacing: 4px; margin-top: 5px; }

        .content { text-align: center; padding: 0 25mm; margin-top: 15px; }
        .intro { font-size: 1.2rem; color: #475569; }
        .student-name { font-family: 'Playfair Display', serif; font-size: 2.8rem; color: var(--ghs-dark); margin: 15px 0; border-bottom: 2px solid #f1f5f9; display: inline-block; padding: 0 20px 5px; }
        .achievement { font-size: 1.4rem; font-weight: 800; color: var(--ghs-primary); margin: 10px 0; }
        .meta-info { font-size: 1rem; color: #64748b; font-style: italic; margin-top: 10px; }
        
        .footer { display: flex; justify-content: space-around; align-items: flex-end; margin-bottom: 10mm; }
        .sig-block { text-align: center; width: 30%; }
        .sig-line { border-top: 1px solid var(--ghs-dark); margin-bottom: 8px; width: 100%; }
        .sig-name { font-weight: 700; font-size: 0.85rem; color: var(--ghs-dark); }
        .sig-title { font-size: 0.7rem; color: #64748b; text-transform: uppercase; }

        .qr-section { text-align: center; position: absolute; bottom: 20mm; right: 20mm; }
        .qr-code { width: 90px; height: 90px; margin-bottom: 5px; border: 4px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .auth-code { font-size: 0.6rem; color: #94a3b8; font-family: monospace; }
        
        .btn-print { position: fixed; top: 20px; right: 20px; background: var(--ghs-primary); color: white; padding: 12px 24px; border-radius: 50px; text-decoration: none; font-weight: 700; box-shadow: 0 10px 20px rgba(16,185,129,0.2); z-index: 1000; animation: bounce 2s infinite; }
        @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }

        @media print {
            html, body { 
                height: 100%; 
                margin: 0 !important; 
                padding: 0 !important; 
                overflow: hidden;
            }
            .btn-print { display: none !important; }
            .certificate-container { 
                box-shadow: none !important; 
                margin: 0 !important; 
                border-width: 10mm !important; 
                width: 297mm !important; 
                height: 210mm !important;
                page-break-after: avoid;
                page-break-before: avoid;
            }
        }
    </style>
</head>
<body>

    <a href="javascript:window.print()" class="btn-print">🖨 Imprimir Certificado (A4)</a>

    <div class="certificate-container">
        <div class="inner-border"></div>
        <div class="corner top-left"></div>
        <div class="corner top-right"></div>
        <div class="corner bottom-left"></div>
        <div class="corner bottom-right"></div>

        <div class="header">
            <img src="<?= URL_ROOT ?>/img/logo.jpg" alt="Logo GHS" class="logo">
            <div class="institution">Green Hard & Softh</div>
            <div class="sub-header">Excelência Académica e Reconhecimento</div>
        </div>

        <div class="title-area">
            <h1 class="cert-title">Certificado de Mérito</h1>
            <div class="cert-subtitle"><?= (string)($data['periodo'] ?? 'Anual') ?></div>
        </div>

        <div class="content">
            <div class="intro">A Direção Geral outorga este título de distinção ao estudante:</div>
            <div class="student-name"><?= htmlspecialchars((string)($data['nome'] ?? 'Estudante')) ?></div>
            <div class="achievement"><?= htmlspecialchars((string)($data['winner_type'] ?? 'Mérito Académico')) ?></div>
            <div class="meta-info">
                Nível: <strong><?= htmlspecialchars((string)($data['nivel_nome'] ?? 'Ano Letivo')) ?></strong> | 
                Média Obtida: <strong><?= number_format((float)($data['media'] ?? 0), 1) ?> valores</strong>
            </div>
            <p style="font-size: 0.85rem; color: #94a3b8; margin-top: 25px; line-height: 1.5; font-style: italic;">
                "O mérito é a recompensa do esforço contínuo e da dedicação aos estudos. <br>
                Este certificado reconhece a proeminência académica demonstrada durante o <?= (string)($data['periodo'] ?? 'ano letivo') ?>."
            </p>
        </div>

        <div class="footer">
            <div class="sig-block">
                <?php if (!empty($data['assinatura_secretaria'])): ?>
                    <img src="<?= $data['assinatura_secretaria'] ?>" alt="Assinatura Secretaria" style="height: 60px; margin-bottom: -20px; position: relative; z-index: 2;">
                <?php else: ?>
                    <div style="font-family: 'Playfair Display', serif; font-size: 1.4rem; font-style: italic; color: #1e293b; height: 35px;">Secretaria Geral</div>
                <?php endif; ?>
                <div class="sig-line"></div>
                <div class="sig-name">Validação Institucional</div>
                <div class="sig-title">GHS CAMPUS</div>
            </div>
            
            <div class="sig-block">
                <img src="<?= URL_ROOT ?>/img/carimbo_ghs.png" alt="" style="width: 80px; opacity: 0.15; position: absolute; margin-top: -50px; margin-left: -40px;">
                <?php if (!empty($data['assinatura_diretor'])): ?>
                    <img src="<?= $data['assinatura_diretor'] ?>" alt="Assinatura Diretor" style="height: 60px; margin-bottom: -20px; position: relative; z-index: 2;">
                <?php else: ?>
                    <div style="font-family: 'Playfair Display', serif; font-size: 1.4rem; font-style: italic; color: #1e293b; height: 35px;">Direção Geral</div>
                <?php endif; ?>
                <div class="sig-line"></div>
                <div class="sig-name"><?= htmlspecialchars((string)($data['assinatura'] ?? 'Direção GHS')) ?></div>
                <div class="sig-title">Assinatura Certificada</div>
            </div>
        </div>
        
        <div class="qr-section">
            <?php 
                $justificativa = "PREMIADO POR EXCELENCIA ACADEMICA: " . ($data['nome'] ?? '') . " atingiu a media de " . number_format((float)($data['media'] ?? 0), 2) . " no " . ($data['periodo'] ?? '') . ", situando-se no Top Elegivel (Lugar " . ($data['posicao_num'] ?? '1') . ") do GHS CAMPUS.";
                $qrDataString = "CERTIFICADO DE MÉRITO - GHS\n" .
                                "Estudante: " . ($data['nome'] ?? 'N/A') . "\n" .
                                "Média Final: " . number_format((float)($data['media'] ?? 0), 2) . "\n" .
                                "Posição: " . ($data['winner_type'] ?? 'N/A') . "\n" .
                                "Nível: " . ($data['nivel_nome'] ?? 'N/A') . "\n" .
                                "ID: #" . str_pad(($data['cert_id'] ?? 0), 6, '0', STR_PAD_LEFT) . "\n" .
                                "Autenticação: Válida\n" .
                                "Justificativa: " . $justificativa;
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($qrDataString);
            ?>
            <img src="<?= $qrUrl ?>" class="qr-code" alt="QR Code Autenticidade">
            <div class="auth-code">REF: <?= strtoupper(bin2hex(random_bytes(4))) ?></div>
            <div style="font-size: 0.5rem; color: #cbd5e1; margin-top: 2px;">DOCUMENTO DIGITALMENTE AUTENTICADO</div>
        </div>
        
        <div style="position: absolute; bottom: 10mm; left: 15mm; font-size: 0.7rem; color: #94a3b8;">
            Data de Emissão: <?= (string)($data['data_emissao'] ?? date('d/m/Y')) ?> | GHS
        </div>
    </div>

</body>
</html>


</body>
</html>
