<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro de Sistema - GHS</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background: #f8fafc; color: #1e293b; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .error-container { text-align: center; max-width: 500px; padding: 40px; background: white; border-radius: 24px; shadow: 0 10px 25px -5px rgba(0,0,0,0.1); }
        .icon { font-size: 80px; color: #ef4444; margin-bottom: 20px; }
        h1 { font-weight: 700; margin-bottom: 10px; color: #0f172a; }
        p { color: #64748b; line-height: 1.6; margin-bottom: 30px; }
        .btn { display: inline-flex; align-items: center; gap: 8px; background: #10b981; color: white; padding: 12px 24px; border-radius: 12px; text-decoration: none; font-weight: 600; transition: 0.3s; }
        .btn:hover { background: #059669; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="icon"><ion-icon name="construct-outline"></ion-icon></div>
        <h1>Interface em Manutenção</h1>
        <p>Lamentamos, mas ocorreu um erro técnico inesperado no servidor. A nossa equipa já foi notificada e o erro foi registado para correção imediata.</p>
        <a href="<?= URL_ROOT ?>/" class="btn">
            <ion-icon name="home-outline"></ion-icon>
            Voltar ao Início
        </a>
    </div>
</body>
</html>
