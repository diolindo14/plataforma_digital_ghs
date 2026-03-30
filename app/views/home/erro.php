<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro Técnico - GHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background: #f8fafc; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .error-card { max-width: 550px; width: 100%; background: white; border-radius: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.05); border: none; overflow: hidden; }
        .error-header { background: #fee2e2; padding: 40px; text-align: center; }
        .error-body { padding: 40px; text-align: center; }
        .icon-circle { width: 80px; height: 80px; background: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-size: 3rem; }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-header">
            <div class="icon-circle">
                <ion-icon name="alert-circle-outline"></ion-icon>
            </div>
        </div>
        <div class="error-body">
            <h2 class="fw-bold text-dark mb-3">Interface em Manutenção</h2>
            <p class="text-muted fs-5 mb-4">
                Lamentamos, mas ocorreu um erro técnico inesperado no servidor. 
                A nossa equipa já foi notificada e o erro foi registado para correção imediata.
            </p>
            <div class="d-grid gap-2">
                <a href="<?= URL_ROOT ?>/matricula" class="btn btn-dark rounded-pill fw-bold py-3">
                    Tentar Novamente
                </a>
                <a href="<?= URL_ROOT ?>/" class="btn btn-outline-secondary rounded-pill py-2">
                    Voltar ao Início
                </a>
            </div>
            <p class="mt-4 small text-muted">
                Se o erro persistir, contacte o suporte técnico da GHS.
            </p>
        </div>
    </div>
</body>
</html>
