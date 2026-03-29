<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matrícula Enviada - GHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background: linear-gradient(135deg, #f0fdf4 0%, #eff6ff 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card-sucesso { max-width: 600px; width: 100%; border-radius: 24px; box-shadow: 0 25px 60px rgba(0,0,0,0.1); border: none; }
        .icon-circle { width: 90px; height: 90px; background: rgba(16, 185, 129, 0.12); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
        .credential-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 16px 20px; }
        .credential-label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; }
        .credential-value { font-size: 16px; font-weight: 700; color: #065f46; font-family: monospace; letter-spacing: 0.05em; }
        .step-item { display: flex; align-items: center; gap: 12px; padding: 8px 0; font-size: 14px; color: #374151; }
        .step-num { width: 26px; height: 26px; border-radius: 50%; background: #10b981; color: white; font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    </style>
</head>
<body>
    <div class="card-sucesso bg-white p-5">
        <div class="text-center">
            <div class="icon-circle">
                <ion-icon name="checkmark-done-outline" style="color:#10B981; font-size:3rem;"></ion-icon>
            </div>
            <h2 class="fw-bold text-dark mb-1">Matrícula Enviada!</h2>
            <p class="text-muted mb-4">Os seus documentos foram submetidos com sucesso. A secretaria irá validá-los brevemente.</p>
        </div>

        <?php if (!empty($_SESSION['matricula_email']) && !empty($_SESSION['matricula_senha_provisoria'])): ?>
        <div class="credential-box mb-4">
            <div class="row gy-3">
                <div class="col-12">
                    <div class="credential-label">O seu Email de Acesso</div>
                    <div class="credential-value"><?= htmlspecialchars($_SESSION['matricula_email']) ?></div>
                </div>
                <div class="col-12">
                    <div class="credential-label">Senha Provisória</div>
                    <div class="credential-value d-flex align-items-center gap-2">
                        <span id="senhaVal"><?= htmlspecialchars($_SESSION['matricula_senha_provisoria']) ?></span>
                        <button onclick="navigator.clipboard.writeText('<?= htmlspecialchars($_SESSION['matricula_senha_provisoria']) ?>'); this.innerText='✓'" class="btn btn-sm btn-outline-success border-0 py-0 px-2">Copiar</button>
                    </div>
                </div>
            </div>
            <div class="mt-3 text-muted" style="font-size:12px;">
                <ion-icon name="information-circle-outline"></ion-icon>
                Guarde estas credenciais. Pode alterar a senha após o primeiro login.
            </div>
        </div>
        <?php 
            // Limpar sessão após exibir
            unset($_SESSION['matricula_email'], $_SESSION['matricula_senha_provisoria']);
        ?>
        <?php endif; ?>

        <div class="mb-4">
            <p class="fw-semibold text-dark mb-2">Próximos Passos:</p>
            <div class="step-item"><div class="step-num">1</div> Faça login com as credenciais acima</div>
            <div class="step-item"><div class="step-num">2</div> Acompanhe o estado da validação da sua matrícula no painel</div>
            <div class="step-item"><div class="step-num">3</div> Após aprovação, será alocado a uma turma e terá acesso total</div>
        </div>

        <div class="d-flex flex-column gap-2">
            <a href="<?= URL_ROOT ?>/auth" class="btn btn-success rounded-pill fw-bold py-3 fs-6">
                <ion-icon name="log-in-outline" class="me-2"></ion-icon> Fazer Login Agora
            </a>
            <a href="<?= URL_ROOT ?>/" class="btn btn-outline-secondary rounded-pill py-2">
                Regressar à Página Inicial
            </a>
        </div>
    </div>
</body>
</html>
