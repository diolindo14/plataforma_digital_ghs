<?php 
/** @var $this Controller */
if (!function_exists('calcAvg')) {
    function calcAvg($v1, $v2, $v3) {
        $sum = 0; $count = 0;
        if(!empty($v1) || $v1 === '0' || $v1 === 0) { $sum += (float)$v1; $count++; }
        if(!empty($v2) || $v2 === '0' || $v2 === 0) { $sum += (float)$v2; $count++; }
        if(!empty($v3) || $v3 === '0' || $v3 === 0) { $sum += (float)$v3; $count++; }
        return $count > 0 ? ($sum / $count) : 0;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área Docente - GHS</title>
    <!-- CSS Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <style>
        :root {
            --ghs-primary: #10B981;
            --ghs-secondary: #3B82F6;
            --ghs-dark: #0F172A;
            --ghs-slate: #1E293B;
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.4);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #f0f2f5;
            color: #334155;
            overflow-x: hidden;
        }

        .sidebar {
            background-color: var(--ghs-dark);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            width: 260px;
            z-index: 1050;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 25px rgba(0, 0, 0, 0.15);
            padding-top: 1.5rem;
        }

        .sidebar .nav-link {
            color: #94a3b8;
            text-decoration: none;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: 0.3s;
            font-weight: 500;
            border-left: 4px solid transparent;
            margin-bottom: 4px;
            cursor: pointer;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar .nav-link.active {
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.15), transparent);
            color: var(--ghs-primary);
            border-left-color: var(--ghs-primary);
            font-weight: 600;
        }

        .content {
            margin-left: 260px;
            padding: 30px;
            min-height: 100vh;
            transition: all 0.4s ease;
            background: radial-gradient(circle at 10% 10%, rgba(16, 185, 129, 0.03), transparent 600px);
        }

        @media (max-width: 991.98px) {
            .sidebar { left: -260px; }
            .sidebar.active { left: 0; }
            .content { margin-left: 0; padding: 20px; padding-top: 85px; }
            .mobile-header { display: flex !important; }
        }

        .mobile-header {
            position: fixed; top: 0; left: 0; right: 0; height: 70px;
            background: white; z-index: 1051; display: none; align-items: center;
            padding: 0 15px; border-bottom: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .mobile-toggle {
            background: #f1f5f9; width: 45px; height: 45px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: var(--ghs-dark); cursor: pointer; border: none; font-size: 1.5rem;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
            transition: 0.3s ease;
            margin-bottom: 25px;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.08);
        }

        .stat-icon {
            width: 50px; height: 50px; display: flex; align-items: center;
            justify-content: center; border-radius: 15px; font-size: 1.4rem;
        }

        .profile-btn {
            background: white; border-radius: 12px; padding: 6px 12px;
            border: 1px solid #e2e8f0; display: flex; align-items: center;
            gap: 10px; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        .profile-btn:hover { border-color: var(--ghs-primary); background: #f8fafc; }

        .form-control, .form-select {
            border-radius: 12px; padding: 12px; border: 1px solid #e2e8f0;
            background: #f8fafc; transition: 0.3s;
        }

        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
            border-color: var(--ghs-primary); background: white;
        }

        .btn-premium {
            border-radius: 12px; padding: 12px 24px; font-weight: 600;
            display: flex; align-items: center; gap: 8px; transition: 0.3s;
            border: none;
        }

        .btn-premium-success {
            background: var(--ghs-primary); color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
        }

        .btn-premium-success:hover {
            background: #059669; transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
        }

        .table-premium { border-radius: 15px; overflow: hidden; border: none !important; }
        .table-premium thead th {
            background: #f8fafc; color: #64748b; font-weight: 600;
            text-transform: uppercase; font-size: 0.7rem; letter-spacing: 1px;
            padding: 15px; border: none;
        }
        .table-premium tbody td { padding: 15px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }

        .badge-premium { padding: 6px 12px; border-radius: 8px; font-weight: 600; font-size: 0.75rem; }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body>
    <div class="mobile-header d-lg-none">
        <button class="mobile-toggle ms-2" id="sidebarToggle">
            <ion-icon name="menu-outline"></ion-icon>
        </button>
        <div class="ms-3 d-flex align-items-center gap-2">
            <img src="<?= URL_ROOT ?>/img/logo.jpg" alt="Logo" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 2px solid var(--ghs-primary);">
            <span class="fw-bold text-dark">GHS Docente</span>
        </div>
    </div>

    <aside class="sidebar shadow-lg">
        <div class="text-center mb-4 mt-2 px-3">
            <img src="<?= URL_ROOT ?>/img/logo.jpg" alt="Logo" style="width: 70px; height: 70px; border-radius: 50%; border: 3px solid var(--ghs-primary); margin-bottom: 10px; object-fit: cover;">
            <h6 class="fw-bold text-white mb-0">Green Hard & Softh</h6>
            <small class="text-success fw-bold text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Portal Docente</small>
        </div>

        <nav class="nav flex-column flex-grow-1 px-2" id="v-pills-tab" role="tablist">
            <button class="nav-link active border-0 text-start w-100" id="tab-home" data-bs-toggle="pill" data-bs-target="#pane-home" type="button" role="tab">
                <ion-icon name="grid-outline"></ion-icon> Meu Painel
            </button>
            <button class="nav-link border-0 text-start w-100" id="tab-notas" data-bs-toggle="pill" data-bs-target="#pane-notas" type="button" role="tab">
                <ion-icon name="create-outline"></ion-icon> Lançamento Notas
            </button>
            <button class="nav-link border-0 text-start w-100" id="tab-chamada" data-bs-toggle="pill" data-bs-target="#pane-chamada" type="button" role="tab">
                <ion-icon name="people-outline"></ion-icon> Chamada & Sumário
            </button>
            <button class="nav-link border-0 text-start w-100" id="tab-materiais" data-bs-toggle="pill" data-bs-target="#pane-materiais" type="button" role="tab">
                <ion-icon name="cloud-upload-outline"></ion-icon> Materiais Didáticos
            </button>
            <button class="nav-link border-0 text-start w-100" id="tab-reclamacoes" data-bs-toggle="pill" data-bs-target="#pane-reclamacoes" type="button" role="tab">
                <ion-icon name="chatbox-ellipses-outline"></ion-icon> Contestações
                <?php if (!empty($data['contestacoes_pendentes'])): ?>
                    <span class="badge bg-danger rounded-pill ms-auto" style="font-size: 0.6rem;"><?= count($data['contestacoes_pendentes']) ?></span>
                <?php endif; ?>
            </button>
            <button class="nav-link border-0 text-start w-100" id="tab-assiduidade" data-bs-toggle="pill" data-bs-target="#pane-assiduidade" type="button" role="tab">
                <ion-icon name="calendar-outline"></ion-icon> Minha Assiduidade
            </button>
            <button class="nav-link border-0 text-start w-100" id="tab-calendario" data-bs-toggle="pill" data-bs-target="#pane-calendario" type="button" role="tab">
                <ion-icon name="calendar-number-outline"></ion-icon> Horário & Agenda
            </button>
            <button class="nav-link border-0 text-start w-100" id="tab-comunicados" data-bs-toggle="pill" data-bs-target="#pane-comunicados" type="button" role="tab">
                <ion-icon name="megaphone-outline"></ion-icon> Mural de Avisos
            </button>
        </nav>

        <div class="px-3 pb-4">
            <a href="<?= URL_ROOT ?>/utilizadores/logout" class="nav-link text-danger border-0 d-flex align-items-center gap-2">
                <ion-icon name="log-out-outline"></ion-icon> Sair do Sistema
            </a>
        </div>
    </aside>

    <main class="content">
        <!-- Top Bar -->
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-none d-lg-block">
                <h4 class="fw-bold mb-0">Bem-vindo, <?= explode(' ', $data['professor']['nome_completo'])[0] ?> 👋</h4>
                <p class="text-muted small mb-0">Gestão Académica em Tempo Real</p>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="profile-btn d-flex align-items-center">
                    <img src="<?= URL_ROOT ?>/img/logo.jpg" alt="Avatar" style="width: 32px; height: 32px; border-radius: 8px; object-fit: cover;">
                    <div class="d-none d-md-block text-start">
                        <div class="fw-bold small lh-1"><?= $data['professor']['nome_completo'] ?></div>
                        <small class="text-muted" style="font-size: 0.7rem;">ID: #<?= $data['professor']['utilizador_id'] ?></small>
                    </div>
                    <ion-icon name="chevron-down-outline" class="text-muted small"></ion-icon>
                </div>
            </div>
        </header>

        <div class="tab-content" id="v-pills-tabContent">
            
            <!-- Dashboard Home -->
            <div class="tab-pane fade show active" id="pane-home">
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="glass-card p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="stat-icon bg-success bg-opacity-10 text-success">
                                    <ion-icon name="people-outline"></ion-icon>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-0 small fw-bold">Turmas Ativas</h6>
                                    <h3 class="fw-bold mb-0 text-dark"><?= count($data['classes']) ?></h3>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-1">
                                <?php foreach($data['classes'] as $c): ?>
                                    <span class="badge bg-light text-dark border extra-small"><?= $c['turma_codigo'] ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                                    <ion-icon name="document-text-outline"></ion-icon>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-0 small fw-bold">Aulas Dadas</h6>
                                    <h3 class="fw-bold mb-0 text-dark"><?= count($data['minha_assiduidade'] ?? []) ?></h3>
                                </div>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-primary" style="width: 80%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="glass-card p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                                    <ion-icon name="notifications-outline"></ion-icon>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-0 small fw-bold">Avisos Pendentes</h6>
                                    <h3 class="fw-bold mb-0 text-dark">2</h3>
                                </div>
                            </div>
                            <small class="text-muted italic">Último aviso enviado em <?= date('d/m') ?></small>
                        </div>
                    </div>
                </div>

                <!-- Quadro de Mérito -->
                <?php if (!empty($data['ranking_escola'])): ?>
                    <div class="glass-card p-0 overflow-hidden mb-4">
                        <div class="p-4 border-bottom bg-light bg-opacity-50">
                            <h5 class="fw-bold mb-0"><ion-icon name="trophy-outline" class="text-warning"></ion-icon> Quadro de Excelência Académica</h5>
                        </div>
                        <div class="p-4">
                            <?php 
                            $ranking_escola = $data['ranking_escola'];
                            $ranking_nivel  = $data['ranking_nivel'];
                            $show_details = false;
                            include __DIR__ . '/../partials/merit_board.php';
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Convocatórias -->
                <?php if (!empty($data['contestacoes_historico'])): ?>
                    <?php foreach ($data['contestacoes_historico'] as $h): ?>
                        <?php if ($h['status'] === 'Aguardando_Comparecimento'): ?>
                            <div class="glass-card border-start border-danger border-4 p-4 mb-4">
                                <div class="d-flex align-items-center gap-3 mb-3 text-danger">
                                    <ion-icon name="warning" class="fs-2"></ion-icon>
                                    <h5 class="fw-bold mb-0">Convocação Administrativa: Mediação Acadêmica</h5>
                                </div>
                                <p class="text-muted">Reunião obrigatória para resolver a contestação do aluno <strong><?= htmlspecialchars($h['estudante_nome']) ?></strong>.</p>
                                <div class="row g-3 bg-light p-3 rounded-4">
                                    <div class="col-md-4"><small class="d-block text-muted text-uppercase fw-bold" style="font-size:0.6rem;">Data / Hora</small><strong><?= date('d/m/Y', strtotime($h['data_reuniao'])) ?> - <?= substr($h['hora_reuniao'], 0, 5) ?></strong></div>
                                    <div class="col-md-4"><small class="d-block text-muted text-uppercase fw-bold" style="font-size:0.6rem;">Local</small><strong><?= htmlspecialchars($h['local_reuniao']) ?></strong></div>
                                    <div class="col-md-4"><small class="d-block text-muted text-uppercase fw-bold" style="font-size:0.6rem;">Status</small><span class="badge bg-danger rounded-pill">URGENTE</span></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Tab Notas -->
            <div class="tab-pane fade" id="pane-notas">
                <div class="glass-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Lançamento de Notas</h5>
                        <div class="d-flex gap-2">
                             <select class="form-select form-select-sm" style="width: auto;" onchange="switchClass(this.value)">
                                <?php foreach($data['classes'] as $c): ?>
                                    <option value="<?= $c['turma_id'] ?>|<?= $c['disciplina_id'] ?>" <?= ($c['turma_id'] == $data['selected_turma'] && $c['disciplina_id'] == $data['selected_disciplina']) ? 'selected' : '' ?>>
                                        <?= $c['turma_codigo'] ?> - <?= $c['disciplina_nome'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-sm btn-dark px-3 rounded-pill" onclick="window.print()"><ion-icon name="print-outline"></ion-icon> Pauta</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-premium align-middle">
                            <thead>
                                <tr>
                                    <th width="250">Estudante</th>
                                    <th>TPC (Média)</th>
                                    <th>AP (Média)</th>
                                    <th>TPI (Média)</th>
                                    <th>CE (Média)</th>
                                    <th>Total AC</th>
                                    <th>Exame</th>
                                    <th>Média Final</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['students'])): ?>
                                    <?php foreach ($data['students'] as $s): ?>
                                        <tr data-student-id="<?= $s['id'] ?>" data-turma-id="<?= $data['selected_turma'] ?>" data-disc-id="<?= $data['selected_disciplina'] ?>">
                                            <td>
                                                <div class="fw-bold text-dark"><?= $s['nome_completo'] ?></div>
                                                <div class="extra-small text-muted">ID: #<?= $s['processo_id'] ?></div>
                                            </td>
                                            <!-- TPC -->
                                            <td>
                                                <div class="d-flex gap-1 mb-1">
                                                    <input type="number" step="0.1" class="form-control form-control-sm val-tpc1" value="<?= $s['tpc1'] ?? '' ?>" style="width:50px;">
                                                    <input type="number" step="0.1" class="form-control form-control-sm val-tpc2" value="<?= $s['tpc2'] ?? '' ?>" style="width:50px;">
                                                    <input type="number" step="0.1" class="form-control form-control-sm val-tpc3" value="<?= $s['tpc3'] ?? '' ?>" style="width:50px;">
                                                </div>
                                                <span class="badge bg-success bg-opacity-10 text-success res-avg-tpc"><?= number_format(calcAvg($s['tpc1'], $s['tpc2'], $s['tpc3']), 1) ?></span>
                                            </td>
                                            <!-- AP -->
                                            <td>
                                                <div class="d-flex gap-1 mb-1">
                                                    <input type="number" step="0.1" class="form-control form-control-sm val-ap1" value="<?= $s['ap1'] ?? '' ?>" style="width:50px;">
                                                    <input type="number" step="0.1" class="form-control form-control-sm val-ap2" value="<?= $s['ap2'] ?? '' ?>" style="width:50px;">
                                                    <input type="number" step="0.1" class="form-control form-control-sm val-ap3" value="<?= $s['ap3'] ?? '' ?>" style="width:50px;">
                                                </div>
                                                <span class="badge bg-primary bg-opacity-10 text-primary res-avg-ap"><?= number_format(calcAvg($s['ap1'], $s['ap2'], $s['ap3']), 1) ?></span>
                                            </td>
                                            <!-- TPI -->
                                            <td>
                                                <div class="d-flex gap-1 mb-1">
                                                    <input type="number" step="0.1" class="form-control form-control-sm val-tpi1" value="<?= $s['tpi1'] ?? '' ?>" style="width:50px;">
                                                    <input type="number" step="0.1" class="form-control form-control-sm val-tpi2" value="<?= $s['tpi2'] ?? '' ?>" style="width:50px;">
                                                    <input type="number" step="0.1" class="form-control form-control-sm val-tpi3" value="<?= $s['tpi3'] ?? '' ?>" style="width:50px;">
                                                </div>
                                                <span class="badge bg-warning bg-opacity-10 text-warning res-avg-tpi"><?= number_format(calcAvg($s['tpi1'], $s['tpi2'], $s['tpi3']), 1) ?></span>
                                            </td>
                                            <!-- CE -->
                                            <td>
                                                <div class="d-flex gap-1 mb-1">
                                                    <input type="number" step="0.1" class="form-control form-control-sm val-ce1" value="<?= $s['ce1'] ?? '' ?>" style="width:50px;">
                                                    <input type="number" step="0.1" class="form-control form-control-sm val-ce2" value="<?= $s['ce2'] ?? '' ?>" style="width:50px;">
                                                    <input type="number" step="0.1" class="form-control form-control-sm val-ce3" value="<?= $s['ce3'] ?? '' ?>" style="width:50px;">
                                                </div>
                                                <span class="badge bg-info bg-opacity-10 text-info res-avg-ce"><?= number_format(calcAvg($s['ce1'], $s['ce2'], $s['ce3']), 1) ?></span>
                                            </td>
                                            <td class="fw-bold text-total-ac"><?= number_format($s['total_ac'], 1) ?></td>
                                            <td><input type="number" step="0.1" class="form-control form-control-sm val-exame" value="<?= $s['exame'] ?? '' ?>" style="width:60px;" <?= $s['total_ac'] < 8 ? 'disabled' : '' ?>></td>
                                            <td class="fw-bold text-media-final"><?= ($s['media_final'] > 0) ? number_format($s['media_final'], 1) : '-' ?></td>
                                            <td>
                                                <button onclick="saveNota(this)" class="btn btn-sm btn-success rounded-3"><ion-icon name="save-outline"></ion-icon></button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tab Chamada -->
            <div class="tab-pane fade" id="pane-chamada">
                 <div class="glass-card p-4">
                    <h5 class="fw-bold mb-4">Chamada & Sumário de Aula</h5>
                    <div class="row g-3 mb-4">
                         <div class="col-md-4">
                             <label class="form-label small fw-bold">Tempo de Aula</label>
                             <select id="tempoAula" class="form-select">
                                 <option value="1º Tempo">1º Tempo</option>
                                 <option value="2º Tempo">2º Tempo</option>
                                 <option value="1º e 2º Tempos">1º e 2º Tempos</option>
                             </select>
                         </div>
                         <div class="col-md-8">
                             <label class="form-label small fw-bold">Conteúdo Ministrado</label>
                             <textarea id="sumarioConteudo" class="form-control" rows="1" placeholder="Ex: Introdução aos Algoritmos e lógica..."></textarea>
                         </div>
                    </div>
                    
                    <div class="table-responsive mb-4">
                        <table class="table table-premium align-middle" id="tabelaChamada">
                            <thead>
                                <tr>
                                    <th>Estudante</th>
                                    <th width="200">Presença</th>
                                    <th>Status Atual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['students'])): ?>
                                    <?php foreach ($data['students'] as $s): ?>
                                        <tr data-student-id="<?= $s['id'] ?>">
                                            <td>
                                                <div class="fw-bold"><?= $s['nome_completo'] ?></div>
                                                <small class="text-muted">#<?= $s['processo_id'] ?></small>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-presenca w-100" data-status="P">
                                                    <button type="button" class="btn btn-sm btn-success active" onclick="setPresenca(this, 'P')">P</button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="setPresenca(this, 'F')">F</button>
                                                </div>
                                            </td>
                                            <td><span class="badge status-badge bg-success bg-opacity-10 text-success">Presente</span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end">
                        <button onclick="submeterSumario(this)" class="btn btn-premium btn-premium-success">Finalizar Sumário</button>
                    </div>
                </div>
            </div>

            <!-- Outras Tabs simples para completar -->
            <div class="tab-pane fade" id="pane-materiais">
                <div class="glass-card p-4">
                    <h5 class="fw-bold mb-4">Publicar Material Didático</h5>
                    <form id="formUploadMaterial" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Turma / Disciplina</label>
                            <select name="turma_id" class="form-select">
                                <?php foreach($data['classes'] as $c): ?>
                                    <option value="<?= $c['turma_id'] ?>" data-disc="<?= $c['disciplina_id'] ?>"><?= $c['turma_codigo'] ?> - <?= $c['disciplina_nome'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="disciplina_id" id="upl_disc_id" value="<?= $data['classes'][0]['disciplina_id'] ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Título do Material</label>
                            <input type="text" name="titulo" class="form-control" placeholder="Ex: Tutorial PHP">
                        </div>
                        <div class="col-12">
                             <label class="form-label small fw-bold">Arquivo (PDF, DOCX, ZIP)</label>
                             <input type="file" name="arquivo" class="form-control">
                        </div>
                        <div class="col-12 text-end">
                            <button type="button" onclick="publicarMaterial()" class="btn btn-premium btn-premium-success">Publicar Agora</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="tab-pane fade" id="pane-calendario">
                <div class="glass-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                         <h5 class="fw-bold mb-0">Horário & Agenda Docente</h5>
                         <button class="btn btn-sm btn-dark rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#profEventoModal">+ Novo Evento</button>
                    </div>
                    <div id="calendar" style="height: 600px;"></div>
                </div>
            </div>

             <div class="tab-pane fade" id="pane-reclamacoes">
                <div class="glass-card p-4">
                    <h5 class="fw-bold mb-4">Contestações de Notas</h5>
                    <div class="table-responsive">
                         <table class="table table-premium">
                             <thead>
                                 <tr>
                                     <th>Data</th>
                                     <th>Estudante</th>
                                     <th>Motivo</th>
                                     <th>Status</th>
                                     <th>Ação</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 <?php foreach($data['contestacoes_historico'] ?? [] as $c): ?>
                                     <tr>
                                         <td><?= date('d/m/Y', strtotime($c['data_reclamacao'])) ?></td>
                                         <td><strong><?= $c['estudante_nome'] ?></strong></td>
                                         <td class="small italic">"<?= $c['motivo_professor'] ?>"</td>
                                         <td><span class="badge bg-warning text-dark"><?= $c['status'] ?></span></td>
                                         <td><button class="btn btn-sm btn-primary rounded-pill" onclick="abrirModalResposta(<?= $c['estudante_id'] ?>, '<?= $c['estudante_nome'] ?>', <?= $c['turma_id'] ?>, <?= $c['disciplina_id'] ?>)">Responder</button></td>
                                     </tr>
                                 <?php endforeach; ?>
                             </tbody>
                         </table>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Modais -->
    <div class="modal fade" id="profEventoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= URL_ROOT ?>/professor/saveEvento" method="POST" class="modal-content border-0 shadow-lg">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="modal-header bg-dark text-white border-0">
                    <h5 class="modal-title fw-bold">Agendar Nova Atividade</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                         <div class="col-12"><label class="form-label small fw-bold">Título</label><input type="text" name="titulo" class="form-control" required></div>
                         <div class="col-md-6"><label class="form-label small fw-bold">Data/Hora</label><input type="datetime-local" name="data_evento" class="form-control" required></div>
                         <div class="col-md-6">
                             <label class="form-label small fw-bold">Tipo</label>
                             <select name="tipo" class="form-select"><option value="Exame">Avaliação</option><option value="Aula Extra">Aula Extra</option></select>
                         </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-premium btn-premium-success w-100">Agendar Agora</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Resposta Contestação -->
    <div class="modal fade" id="modalRespostaContestacao" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold">Responder à Contestação</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p>Respondendo ao aluno: <strong id="resp_nome_aluno"></strong></p>
                    <input type="hidden" id="resp_est_id"><input type="hidden" id="resp_turma_id"><input type="hidden" id="resp_disc_id">
                    <textarea id="textoResposta" class="form-control" rows="4" placeholder="Sua resposta..."></textarea>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" onclick="salvarRespostaContestacao()" class="btn btn-danger">Enviar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $.fn.dataTable.ext.errMode = 'none';
            $('.datatable-simple').DataTable({ language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-PT.json' }, info: false, retrieve: true });

            const hash = window.location.hash || "#pane-home";
            const triggerEl = document.querySelector(`.nav-link[data-bs-target="${hash}"]`);
            if (triggerEl) bootstrap.Tab.getOrCreateInstance(triggerEl).show();

            $('.nav-link[data-bs-toggle="pill"]').on('shown.bs.tab', function(e) {
                window.location.hash = $(e.target).data('bs-target');
                if (window.innerWidth < 992) $('.sidebar').removeClass('active');
            });

            $(document).on('input', '.val-tpc1, .val-tpc2, .val-tpc3, .val-ap1, .val-ap2, .val-ap3, .val-tpi1, .val-tpi2, .val-tpi3, .val-ce1, .val-ce2, .val-ce3, .val-exame', function() {
                const row = $(this).closest('tr');
                const getAvg = (p) => {
                    let s = 0, c = 0;
                    for(let i=1; i<=3; i++) { let v = parseFloat(row.find('.val-'+p+i).val()); if(!isNaN(v)) { s+=v; c++; } }
                    return c > 0 ? (s/c) : 0;
                };
                const tpc=getAvg('tpc'), ap=getAvg('ap'), tpi=getAvg('tpi'), ce=getAvg('ce');
                row.find('.res-avg-tpc').text(tpc.toFixed(1)); row.find('.res-avg-ap').text(ap.toFixed(1));
                row.find('.res-avg-tpi').text(tpi.toFixed(1)); row.find('.res-avg-ce').text(ce.toFixed(1));
                const totalAc = tpc+ap+tpi+ce; row.find('.text-total-ac').text(totalAc.toFixed(1));
                const ex = parseFloat(row.find('.val-exame').val());
                if(!isNaN(ex)) row.find('.text-media-final').text(((totalAc+ex)/2).toFixed(1));
            });
            $('#sidebarToggle').click(() => $('.sidebar').toggleClass('active'));
        });

        function switchClass(v) { 
            const p = v.split('|'); 
            const tab = $('.nav-link.active').data('bs-target')?.replace('#pane-', '') || 'home';
            window.location.href = `<?= URL_ROOT ?>/professor?turma_id=${p[0]}&disciplina_id=${p[1]}&tab=${tab}`;
        }
        function saveNota(btn) {
            const row=$(btn).closest('tr'), data={
                estudante_id: row.data('student-id'), turma_id: row.data('turma-id'), disciplina_id: row.data('disc-id'),
                tpc1:row.find('.val-tpc1').val(), tpc2:row.find('.val-tpc2').val(), tpc3:row.find('.val-tpc3').val(),
                ap1:row.find('.val-ap1').val(), ap2:row.find('.val-ap2').val(), ap3:row.find('.val-ap3').val(),
                tpi1:row.find('.val-tpi1').val(), tpi2:row.find('.val-tpi2').val(), tpi3:row.find('.val-tpi3').val(),
                ce1:row.find('.val-ce1').val(), ce2:row.find('.val-ce2').val(), ce3:row.find('.val-ce3').val(),
                exame:row.find('.val-exame').val(), csrf_token: '<?= $_SESSION['csrf_token'] ?>'
            };
            $(btn).prop('disabled',true).html('...');
            $.post('<?= URL_ROOT ?>/professor/saveNota', data, (res) => { alert(res.success?'Salvo!':'Erro'); location.reload(); }, 'json');
        }
        function setPresenca(btn,s) {
            $(btn).addClass('active btn-success').siblings().removeClass('active btn-success');
            const row = $(btn).closest('tr');
            row.find('.status-badge').text(s==='P'?'Presente':'Falta').toggleClass('bg-success text-success', s==='P').toggleClass('bg-danger text-danger', s==='F');
        }
        function submeterSumario(btn) {
            const data = {
                turma_id: $('select[onchange^="switchClass"]').val().split('|')[0],
                disciplina_id: $('select[onchange^="switchClass"]').val().split('|')[1],
                tempo: $('#tempoAula').val(), conteudo: $('#sumarioConteudo').val(),
                presencas: $('#tabelaChamada tbody tr').map(function() { return { estudante_id: $(this).data('student-id'), status: 'P' }; }).get(),
                csrf_token: '<?= $_SESSION['csrf_token'] ?>'
            };
            $.post('<?= URL_ROOT ?>/professor/saveSummary', data, () => location.reload());
        }
    </script>
</body>
</html>