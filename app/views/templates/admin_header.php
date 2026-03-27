<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GHS - Painel Administrativo</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #0B1120; color: white; padding-top: 20px; }
        .sidebar h4 { color: white; font-weight: 700; margin-bottom: 30px; }
        .sidebar .nav-link { color: #94A3B8; display: flex; align-items: center; gap: 10px; padding: 12px 20px; font-weight: 500; transition: 0.3s; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #1E293B; color: #10B981; border-left: 4px solid #10B981; }
        .sidebar ion-icon { font-size: 1.25rem; }
        .content { padding: 30px; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Navigation -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <h4 class="text-center">
                <ion-icon name="leaf" style="color: #10B981;"></ion-icon> Portal Green
            </h4>
            <div class="position-sticky mt-4">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], 'admin') !== false && !strpos($_SERVER['REQUEST_URI'], 'alunos') ? 'active' : '' ?>" href="<?= URL_ROOT ?>/admin">
                            <ion-icon name="grid-outline"></ion-icon> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#alunos">
                            <ion-icon name="people-outline"></ion-icon> Gestão de Alunos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#turmas">
                            <ion-icon name="business-outline"></ion-icon> Gestão de Turmas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#matriculas">
                            <ion-icon name="document-text-outline"></ion-icon> Matrículas
                        </a>
                    </li>
                </ul>

                <hr style="border-color: #1E293B; margin: 30px 20px;">

                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= URL_ROOT ?>/" target="_blank">
                            <ion-icon name="earth-outline"></ion-icon> Ver Site Público
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger fw-bold" href="<?= URL_ROOT ?>/auth/logout">
                            <ion-icon name="log-out-outline"></ion-icon> Terminar Sessão
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- Main Content Area -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <h1 class="h2 fw-bold">Painel de Controlo da Direção</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Exportar PDF</button>
                    </div>
                </div>
            </div>
