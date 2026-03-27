<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sucesso - GHS</title>
    <!-- CSS Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <style> body { font-family: 'Outfit', sans-serif; background-color: #f8f9fa; } </style>
</head>
<body class="d-flex align-items-center justify-content-center" style="height:100vh;">
    
    <div class="text-center p-5 bg-white border-0" style="border-radius:24px; max-width: 600px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1);">
        <div style="width: 100px; height: 100px; background-color: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
            <ion-icon name="checkmark-done-outline" style="color: #10B981; font-size: 3.5rem;"></ion-icon>
        </div>
        
        <h2 class="fw-bold mt-3 text-dark">Matrícula Concluída!</h2>
        <p class="text-muted fs-5 mt-3 mb-4">A sua documentação e comprovativo foram enviados com sucesso. O seu processo passará agora pela fase de validação da secretaria.</p>
        
        <div class="d-flex flex-column gap-3">
            <a href="<?= URL_ROOT ?>/" class="btn btn-success rounded-pill fw-bold py-3 fs-5">Regressar à Página Inicial</a>
        </div>
    </div>
    
</body>
</html>
