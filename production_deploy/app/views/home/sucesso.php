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
        .status-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 16px 20px; }
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

        <div class="status-box mb-4">
            <div class="fw-bold text-success d-flex align-items-center gap-2">
                <ion-icon name="information-circle-outline" class="fs-5"></ion-icon>
                Submissão Registada com Sucesso
            </div>
            <p class="text-muted small mb-0 mt-2">
                O seu processo entrou na fila de triagem. Receberá uma notificação assim que houver uma atualização no estado da sua matrícula.
            </p>
        </div>

        <div class="mb-4">
            <p class="fw-semibold text-dark mb-2">Próximos Passos:</p>
            <div class="step-item"><div class="step-num">1</div> Aguarde a validação técnica da secretaria</div>
            <div class="step-item"><div class="step-num">2</div> Receba a confirmação oficial por email</div>
            <div class="step-item"><div class="step-num">3</div> Após aprovação, poderá aceder ao seu painel académico completo</div>
        </div>

        <div class="d-flex flex-column gap-2">
            <a href="<?= URL_ROOT ?>/auth" class="btn btn-success rounded-pill fw-bold py-3 fs-6">
                <ion-icon name="grid-outline" class="me-2"></ion-icon> Ir para Area de Login
            </a>
            <a href="<?= URL_ROOT ?>/" class="btn btn-outline-secondary rounded-pill py-2 text-center">
                Regressar à Página Inicial
            </a>
        </div>
        
        <?php 
            // Limpar dados de sessão relacionados à submissão
            unset($_SESSION['matricula_email'], $_SESSION['matricula_senha_provisoria'], $_SESSION['is_internal_enrollment']);
        ?>

    </div>
</body>
</html>
