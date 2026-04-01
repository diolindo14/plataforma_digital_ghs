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
        body { font-family: 'Outfit', sans-serif; background-color: #f1f5f9; }
        .sidebar { background-color: #0F172A; min-height: 100vh; color: white; padding-top: 1.5rem; position: fixed; width: 260px; z-index: 10; }
        .sidebar .nav-link { color: #cbd5e1; text-decoration: none; padding: 12px 20px; display: flex; align-items: center; gap: 10px; transition: 0.3s; font-weight: 500; cursor: pointer; border-radius:0; border-left: 4px solid transparent;}
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #1E293B; color: #10B981; border-left: 4px solid #10B981; }
        .content { margin-left: 260px; padding: 40px; }
        .tab-pane { animation: fadeIn 0.4s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        

    </style>
</head>
<body>
<!-- Modal Agendar Evento (Professor) -->
<div class="modal fade" id="profEventoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?= URL_ROOT ?>/professor/saveEvento" method="POST" class="modal-content border-0 shadow-lg">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    

            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold">Agendar Nova Atividade</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-bold">Título da Atividade</label>
                        <input type="text" name="titulo" class="form-control" placeholder="Ex: Mini-Teste 01 / Aula de Revisão" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Data e Hora</label>
                        <input type="datetime-local" name="data_evento" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Tipo</label>
                        <select name="tipo" class="form-select">
                            <option value="Exame">Exame / Teste</option>
                            <option value="Trabalho">Entrega de Trabalho</option>
                            <option value="Aula Extra">Aula Extra</option>
                            <option value="Outro">Outro</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Turma Alvo</label>
                        <select name="destinatario_id" class="form-select" required>
                            <?php foreach($data['classes'] as $c): ?>
                                <option value="<?= $c['turma_id'] ?>">Turma <?= $c['turma_codigo'] ?> (<?= $c['disciplina_nome'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="destinatario_tipo" value="Turma">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Cor de Destaque (Legenda Oficial)</label>
                        <select name="cor" class="form-select">
                            <option value="#ef4444" style="background:#ef4444; color:white;">Vermelho: Exames Semestrais</option>
                            <option value="#60a5fa" style="background:#60a5fa; color:white;">Azul Claro: Prova de Recurso</option>
                            <option value="#14532d" style="background:#14532d; color:white;">Verde Escuro: Semana Transitória</option>
                            <option value="#4ade80" style="background:#4ade80; color:white;">Verde Claro: Palestras AAESHS</option>
                            <option value="#78350f" style="background:#78350f; color:white;">Marrom: Futebol / Excursão</option>
                            <option value="#f59e0b" style="background:#f59e0b; color:white;">Laranja: Outros Eventos</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-bold">Descrição Adicional</label>
                        <textarea name="descricao" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary fw-bold">Agendar Agora</button>
            </div>
        </form>
    </div>
</div>

<div class="d-flex">
    <!-- Sidebar -->
    <nav class="sidebar shadow-lg d-flex flex-column justify-content-between">
        <div>
            <div class="text-center mb-4 mt-2">
                <div style="width: 80px; height: 80px; border-radius: 50%; border: 2px solid #10B981; display:flex; align-items:center; justify-content:center; background:white; margin: 0 auto; overflow:hidden;">
                    <img src="<?= URL_ROOT ?>/public/img/logo.jpg" alt="Logo GHS" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <h5 class="fw-bold mt-2 text-white">Portal GHS</h5>
                <span class="badge bg-secondary mb-3">Docente</span>
            </div>
            
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                <a class="nav-link active" id="tab-home" data-bs-toggle="pill" href="#pane-home"><ion-icon name="grid-outline"></ion-icon> Dashboard Resumo</a>
                <a class="nav-link" id="tab-notas" data-bs-toggle="pill" href="#pane-notas"><ion-icon name="create-outline"></ion-icon> Lançamento de Notas</a>
                <a class="nav-link text-success fw-bold" id="tab-relatorios" data-bs-toggle="pill" href="#pane-relatorios">
                    <ion-icon name="document-text-outline"></ion-icon> Pautas & Relatórios
                </a>
                <a class="nav-link" id="tab-chamada" data-bs-toggle="pill" href="#pane-chamada"><ion-icon name="people-outline"></ion-icon> Frequência / Chamada</a>
                <a class="nav-link" id="tab-materiais" data-bs-toggle="pill" href="#pane-materiais"><ion-icon name="cloud-upload-outline"></ion-icon> Upload de Materiais</a>
                <a class="nav-link" id="tab-calendario" data-bs-toggle="pill" href="#pane-calendario"><ion-icon name="calendar-outline"></ion-icon> Calendário Acadêmico</a>
                <a class="nav-link" id="tab-comunicados" data-bs-toggle="pill" href="#pane-comunicados"><ion-icon name="chatbubbles-outline"></ion-icon> Comunicados & Alertas</a>
                <a class="nav-link text-danger fw-bold position-relative" id="tab-reclamacoes" data-bs-toggle="pill" href="#pane-reclamacoes">
                    <ion-icon name="warning-outline"></ion-icon> Reclamações de Notas
                    <?php if(count($data['reclamacoes']) > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light">
                            <?= count($data['reclamacoes']) ?>
                        </span>
                    <?php endif; ?>
                </a>
                <a class="nav-link text-info fw-bold" id="tab-assiduidade" data-bs-toggle="pill" href="#pane-assiduidade">
                    <ion-icon name="calendar-check-outline"></ion-icon> Minha Assiduidade
                </a>
            </div>
        </div>

        <div class="pb-4 w-100">
            <a class="nav-link text-warning mb-1" href="<?= URL_ROOT ?>/"><ion-icon name="earth-outline"></ion-icon> Voltar ao Site</a>
            <a class="nav-link text-danger fw-bold" href="<?= URL_ROOT ?>/auth/logout"><ion-icon name="log-out-outline"></ion-icon> Terminar Sessão</a>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="content flex-grow-1">
        
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <div>
                <h2 class="fw-bold text-dark">Portal do Professor</h2>
                <p class="text-muted mb-0">Gestão Pedagógica - Ano Letivo 2026/2027</p>
            </div>
            <div class="d-flex gap-3 align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2 border px-3 py-2 rounded-pill bg-white shadow-sm">
                        <ion-icon name="person-circle" style="font-size: 1.8rem; color: #10B981;"></ion-icon>
                        <span class="fw-bold text-dark"><?= $this->e($_SESSION['user_name']) ?></span>
                    </div>
                    <a href="<?= URL_ROOT ?>/auth/logout" class="btn btn-sm btn-outline-danger border-0 d-flex align-items-center gap-1 fw-bold">
                        <ion-icon name="log-out-outline"></ion-icon> Sair
                    </a>
                </div>
            </div>
        </div>

        <!-- Hidden labels for printing official header -->
        <?php 
            $current_turma_label = 'N/A';
            $current_nivel_label = 'ANO';
            foreach($data['classes'] as $c) {
                if ($c['turma_id'] == $data['selected_turma']) {
                    $current_turma_label = $c['turma_codigo'];
                    $current_nivel_label = $c['turno'] ?? 'ANO';
                    break;
                }
            }
        ?>
        <div id="print-turma-label" style="display:none;"><?= $current_turma_label ?></div>
        <div id="print-nivel-label" style="display:none;"><?= $current_nivel_label ?></div>
        
        <!-- 🏆 QUADRO DE MÉRITO (visível quando há dados) -->
        <?php if (!empty($data['ranking_escola'])): ?>
        <div class="mb-4">
            <?php
                $ranking_escola = $data['ranking_escola'];
                $ranking_nivel  = $data['ranking_nivel'];
                $show_details   = false; // Professor vê apenas o Top 3 da escola
                include __DIR__ . '/../partials/merit_board.php';
            ?>
        </div>
        <?php endif; ?>

        <div class="tab-content" id="v-pills-tabContent">
            
            <!-- Dashboard Home -->
            <div class="tab-pane fade show active" id="pane-home">

                <!-- 📢 ALERTAS DE CONVOCATÓRIA (Mediação Profissional) -->
                <?php if (!empty($data['contestacoes_historico'])): ?>
                    <?php foreach ($data['contestacoes_historico'] as $h): ?>
                        <?php if ($h['status'] === 'Aguardando_Comparecimento'): ?>
                            <div class="alert alert-danger shadow-sm border-0 border-start border-4 border-danger rounded-4 mb-4 p-4" role="alert">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger">
                                        <ion-icon name="warning" class="fs-2"></ion-icon>
                                    </div>
                                    <div>
                                        <h4 class="fw-bold mb-1 text-danger">Convocação Administrativa: Mediação Acadêmica em Curso</h4>
                                        <p class="mb-0 text-muted">A Administração agendou uma reunião obrigatória para resolver a contestação do aluno <strong><?= htmlspecialchars($h['estudante_nome']) ?></strong>.</p>
                                    </div>
                                </div>
                                <div class="row g-3 bg-white bg-opacity-50 p-3 rounded-4 border">
                                    <div class="col-md-3">
                                        <div class="small fw-bold text-muted text-uppercase mb-1">Data Agendada</div>
                                        <div class="fw-bold fs-5 text-dark"><?= date('d/m/Y', strtotime($h['data_reuniao'])) ?></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small fw-bold text-muted text-uppercase mb-1">Hora</div>
                                        <div class="fw-bold fs-5 text-dark"><?= substr($h['hora_reuniao'], 0, 5) ?></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small fw-bold text-muted text-uppercase mb-1">Local / Gabinete</div>
                                        <div class="fw-bold fs-5 text-dark"><?= htmlspecialchars($h['local_reuniao']) ?></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small fw-bold text-muted text-uppercase mb-1">Status</div>
                                        <div class="badge bg-danger rounded-pill px-3">COMPARECIMENTO OBRIGATÓRIO</div>
                                    </div>
                                    <div class="col-12 mt-3 pt-3 border-top">
                                        <small class="fw-bold text-danger d-block mb-1">Motivo / Memorando:</small>
                                        <p class="mb-0 small italic text-muted">"<?= nl2br(htmlspecialchars($h['motivo_convocacao'])) ?>"</p>
                                    </div>
                                </div>
                                <div class="mt-3 text-end">
                                    <button class="btn btn-sm btn-dark fw-bold rounded-pill" onclick="$('#tab-reclamacoes').tab('show');">Ir para Centro de Contestações</button>
                                </div>
                            </div>
                        <?php elseif ($h['status'] === 'Aguardando_Correcao'): ?>
                            <div class="alert alert-warning shadow-sm border-0 border-start border-4 border-warning rounded-4 mb-4 p-4" role="alert">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                                        <ion-icon name="alert-circle" class="fs-2"></ion-icon>
                                    </div>
                                    <div>
                                        <h4 class="fw-bold mb-1 text-dark">⚠️ Ordem de Rectificação de Notas</h4>
                                        <p class="mb-0 text-muted">A Administração Académica deliberou a favor da reclamação do aluno <strong><?= htmlspecialchars($h['estudante_nome']) ?></strong>.</p>
                                    </div>
                                </div>
                                <div class="bg-white bg-opacity-50 p-3 rounded-4 border mb-3">
                                    <small class="fw-bold text-muted text-uppercase small d-block mb-1">Ata da Decisão:</small>
                                    <p class="mb-0 small italic">"<?= nl2br(htmlspecialchars($h['decisao_final'])) ?>"</p>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-danger rounded-pill px-3 py-2">PENDENTE DE EXECUÇÃO</span>
                                    <button class="btn btn-warning fw-bold rounded-pill px-4" onclick="switchClass('<?= $h['turma_id'] ?>|<?= $h['disciplina_id'] ?>'); $('#tab-notas').tab('show');">
                                        <ion-icon name="create-outline"></ion-icon> Ir Lançar Correção
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm" style="border-left: 5px solid #10B981 !important;">
                            <div class="card-body">
                                <p class="text-muted fw-bold mb-1 text-uppercase small">Disciplinas Atribuídas</p>
                                <h3 class="fw-bold mb-0 text-dark"><?= count($data['classes']) ?> <small class="text-muted fs-6">Turmas</small></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm" style="border-left: 5px solid #3B82F6 !important;">
                            <div class="card-body">
                                <p class="text-muted fw-bold mb-1 text-uppercase small">Alocação de Turmas</p>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php foreach($data['classes'] as $c): ?>
                                        <span class="badge bg-primary bg-opacity-10 text-primary" title="<?= $this->e($c['disciplina_nome']) ?>"><?= $this->e($c['turma_codigo']) ?></span>
                                    <?php endforeach; ?>
                                    <?php if(empty($data['classes'])): ?><h3 class="fw-bold mb-0 text-primary">N/A</h3><?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm" style="border-left: 5px solid #F59E0B !important;">
                            <div class="card-body">
                                <p class="text-muted fw-bold mb-1 text-uppercase small">Avisos Pendentes</p>
                                <h3 class="fw-bold mb-0 text-warning text-dark"><?= count($data['comunicados']) ?> <small class="text-muted fs-6">mensagens</small></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                            <ion-icon name="today" class="text-primary"></ion-icon> O Seu Horário (Hoje, <?= $data['hoje'] ?>)
                        </h5>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3"><?= count($data['horario_hoje']) ?> Aulas</span>
                    </div>
                    <div class="card-footer bg-white border-0 text-center pb-3">
                        <button class="btn btn-sm btn-outline-primary fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#horarioCompletoModal">
                            <ion-icon name="calendar" class="me-1"></ion-icon> Ver Grade Horária Completa
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php if(empty($data['horario_hoje'])): ?>
                                <div class="p-5 text-center text-muted">
                                    <ion-icon name="calendar-clear-outline" style="font-size: 3rem; opacity: 0.3;"></ion-icon>
                                    <p class="mt-2 mb-0">Sem atividades letivas para hoje.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach($data['horario_hoje'] as $h): ?>
                                    <div class="list-group-item p-4 border-0 hover-bg-light transition-all">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="d-flex gap-3">
                                                <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success d-flex flex-column align-items-center justify-content-center" style="min-width: 90px;">
                                                    <?php 
                                                    $h_inicio = substr($h['hora_inicio'],0,5);
                                                    $tempo_nome = "Aula";
                                                    if (in_array($h_inicio, ['07:20', '17:45'])) $tempo_nome = "1º Tempo";
                                                    else if (in_array($h_inicio, ['08:55', '19:20'])) $tempo_nome = "2º Tempo";
                                                    else if (in_array($h_inicio, ['10:45', '21:00'])) $tempo_nome = "3º Tempo";
                                                    else if (in_array($h_inicio, ['12:20', '22:35'])) $tempo_nome = "4º Tempo";
                                                    ?>
                                                    <ion-icon name="time-outline" class="fs-4 mb-1"></ion-icon>
                                                    <span class="fw-bold small"><?= $tempo_nome ?></span>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-1"><?= $this->e($h['nome_display']) ?> (<?= $this->e($h['sigla']) ?>)</h6>
                                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                                        <span class="badge bg-light border text-secondary fw-medium"><ion-icon name="time"></ion-icon> <?= substr($h['hora_inicio'],0,5) ?> - <?= substr($h['hora_fim'],0,5) ?></span>
                                                        <span class="badge bg-light border text-secondary fw-medium"><ion-icon name="sunny"></ion-icon> Período: <?= $h['turno'] ?? 'N/A' ?></span>
                                                        <span class="badge bg-light border text-secondary fw-medium"><ion-icon name="people"></ion-icon> Turma <?= $this->e($h['turma_codigo']) ?></span>
                                                        <span class="badge bg-light border text-secondary fw-medium"><ion-icon name="location"></ion-icon> Sala <?= $this->e($h['sala']) ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm" onclick="$('#tab-chamada').tab('show'); setTimeout(() => switchClass('<?= $h['turma_id'] ?>|<?= $h['disciplina_id'] ?>'), 100);">
                                                <ion-icon name="checkbox-outline"></ion-icon> Abrir Chamada
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Relatórios Acadêmicos -->
            <div class="tab-pane fade" id="pane-relatorios">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-0">Centro de Relatórios & Pautas</h4>
                        <p class="text-muted small mb-0">Consolidado Geral de Aproveitamento (AC + Exames)</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-dark d-flex align-items-center gap-2" onclick="printSection('relatorios-print')">
                            <ion-icon name="print-outline"></ion-icon> Imprimir Pauta Oficial
                        </button>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-4 overflow-hidden" id="relatorios-print">
                    <div class="card-header bg-white py-3 border-bottom border-light d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success bg-opacity-10 p-2 rounded-3 text-success">
                                <ion-icon name="stats-chart" class="fs-4"></ion-icon>
                            </div>
                            <h5 class="fw-bold mb-0">Pauta Geral de Aproveitamento</h5>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 datatable-export">
                                <thead class="table-light text-muted small">
                                    <tr class="text-uppercase" style="font-size: 10px; letter-spacing: 0.05em;">
                                        <th class="ps-4">Código / Turma</th>
                                        <th>Disciplina</th>
                                        <th>Estudante</th>
                                        <th class="text-center">Total AC<br><small>(20 pts)</small></th>
                                        <th class="text-center">Exame<br><small>(20 pts)</small></th>
                                        <th class="text-center bg-dark text-white">Média Final<br><small>(20 pts)</small></th>
                                        <th class="text-center">Situação Final</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($data['relatorio_notas'])): ?>
                                        <tr><td colspan="7" class="text-center py-5 text-muted">Ainda não há notas lançadas para as suas turmas.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($data['relatorio_notas'] as $r): ?>
                                            <tr>
                                                <td class="ps-4 fw-bold">
                                                    <span class="badge bg-light text-dark border"><?= $r['turma'] ?></span>
                                                </td>
                                                <td class="small fw-semibold"><?= $r['disciplina'] ?></td>
                                                <td>
                                                    <div class="fw-bold text-dark"><?= $this->e($r['estudante']) ?></div>
                                                    <div class="text-muted" style="font-size: 0.7rem;">ID: #<?= $r['estudante_id'] ?></div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="fw-bold <?= $r['total_ac'] < 10 ? 'text-danger' : 'text-success' ?>">
                                                        <?= number_format($r['total_ac'], 1) ?>
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="fw-bold">
                                                        <?= ($r['notas'][5] !== null) ? number_format($r['notas'][5], 1) : '<span class="text-muted">---</span>' ?>
                                                    </span>
                                                </td>
                                                <td class="text-center bg-light fw-bold fs-5">
                                                    <?php if($r['media_final'] !== null): ?>
                                                        <span class="<?= $r['media_final'] < 10 ? 'text-danger' : 'text-success' ?>">
                                                            <?= number_format($r['media_final'], 1) ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted">---</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php 
                                                        $ac = (float)$r['total_ac'];
                                                        $media = $r['media_final'];
                                                        $exame = $r['notas'][5];
                                                        
                                                        if($media === null): ?>
                                                            <span class="badge bg-secondary">Admitido ao Exame</span>
                                                        <?php elseif($media >= 12): ?>
                                                            <span class="badge bg-success">Aprovado</span>
                                                        <?php elseif($media >= 8): ?>
                                                            <span class="badge bg-warning text-dark">Recurso</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-danger">Reprovado</span>
                                                        <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notas -->
            <div class="tab-pane fade" id="pane-notas">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">Lançamento de Avaliações</h4>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" onchange="switchClass(this.value)">
                            <?php foreach($data['classes'] as $c): ?>
                                <option value="<?= $c['turma_id'] ?>|<?= $c['disciplina_id'] ?>" <?= ($data['selected_turma'] == $c['turma_id'] && $data['selected_disciplina'] == $c['disciplina_id']) ? 'selected' : '' ?>>
                                    Turma <?= $c['turma_codigo'] ?> - <?= $c['disciplina_nome'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-sm btn-outline-success" onclick="printSection('pane-notas')"><ion-icon name="print-outline"></ion-icon> Imprimir</button>
                    </div>
                </div>
                
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-success mb-2"> Avaliação Contínua (20 pontos)</h5>
                        <p class="text-muted small border-bottom pb-3">A Métrica Institucional define: TPC (2) | AP (3) | TPI (5) | CE (10)</p>

                        <div class="table-responsive mt-3">
                            <table class="table table-hover align-middle datatable-simple">
                                <thead class="table-light">
                                    <tr>
                                        <th>Matrícula</th>
                                        <th>Nome do Estudante</th>
                                        <th class="text-center" style="width: 80px;">Trabalho<br><small>(TPC - 2)</small></th>
                                        <th class="text-center" style="width: 80px;">Avaliação<br><small>(AP - 3)</small></th>
                                        <th class="text-center" style="width: 80px;">Trabalho<br><small>(TPI - 5)</small></th>
                                        <th class="text-center" style="width: 80px;">CE<br><small>(Máx 10)</small></th>
                                        <th class="text-center text-white bg-success">Total AC<br><small>(20 pts)</small></th>
                                        <th class="text-center border-start border-primary" style="width: 90px;">Exame Semestral</th>
                                        <th class="text-center text-white bg-dark">Média Final</th>
                                        <th class="text-center">Feedback / Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($data['students'])): ?>
                                        <tr><td colspan="9" class="text-center">Nenhum aluno matriculado nesta turma.</td></tr>
                                    <?php else: ?>
                                        <?php 
                                            $turma_id = $data['classes'][0]['turma_id'] ?? 0;
                                            $disc_id = $data['classes'][0]['disciplina_id'] ?? 0;
                                        ?>
                                        <?php foreach($data['students'] as $s): ?>
                                            <?php $sn = $data['notas'][$s['id']] ?? []; ?>
                                            <?php 
                                                $is_locked = in_array($sn['feedback_status'] ?? '', ['Concordado', 'Resolvido']);
                                            ?>
                                            <tr data-student-id="<?= $s['id'] ?>" data-turma-id="<?= $turma_id ?>" data-disc-id="<?= $disc_id ?>" class="<?= $is_locked ? 'table-light' : '' ?>">
                                                <td>
                                                    <?= $s['id'] ?>
                                                    <?php if($is_locked): ?>
                                                        <ion-icon name="lock-closed" class="text-secondary" title="Nota Trancada (Aluno Concordou)"></ion-icon>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="fw-bold"><?= $this->e($s['nome_completo']) ?></td>
                                                <td><input type="number" step="0.1" class="form-control form-control-sm text-center val-tpc" value="<?= $sn[1] ?? '' ?>" <?= $is_locked ? 'readonly disabled' : '' ?>></td>
                                                <td><input type="number" step="0.1" class="form-control form-control-sm text-center val-ap" value="<?= $sn[2] ?? '' ?>" <?= $is_locked ? 'readonly disabled' : '' ?>></td>
                                                <td><input type="number" step="0.1" class="form-control form-control-sm text-center val-tpi" value="<?= $sn[3] ?? '' ?>" <?= $is_locked ? 'readonly disabled' : '' ?>></td>
                                                <td><input type="number" step="0.1" class="form-control form-control-sm text-center val-ce" value="<?= $sn[4] ?? '' ?>" <?= $is_locked ? 'readonly disabled' : '' ?>></td>
                                                <td class="fw-bold text-success text-center fs-5 text-total-ac">
                                                    <?php 
                                                        $total = floatval($sn[1]??0) + floatval($sn[2]??0) + floatval($sn[3]??0) + floatval($sn[4]??0);
                                                        echo number_format($total, 1);
                                                    ?>
                                                </td>
                                                <td class="border-start border-primary">
                                                    <?php $pode_fazer_exame = $total >= 8; ?>
                                                    <input type="number" step="0.1" class="form-control form-control-sm text-center val-exame" 
                                                           value="<?= $sn[5] ?? '' ?>" 
                                                           <?= ($is_locked || !$pode_fazer_exame) ? 'readonly disabled' : '' ?>
                                                           placeholder="<?= !$pode_fazer_exame ? 'Reprovado' : '' ?>">
                                                </td>
                                                <td class="text-center fw-bold fs-5 text-media-final">
                                                    <?php 
                                                        $exame = $sn[5] ?? null;
                                                        if($total < 8) {
                                                            echo '<span class="text-danger" style="font-size: 0.7rem;">Reprovado</span>';
                                                        } elseif($exame !== null && $exame !== '') {
                                                            echo number_format(($total + floatval($exame)) / 2, 1);
                                                        } else {
                                                            echo '-';
                                                        }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if($is_locked): ?>
                                                        <span class="badge bg-secondary w-100 py-2"><ion-icon name="lock-closed-outline"></ion-icon> Trancado</span>
                                                    <?php else: ?>
                                                        <div class="mb-1">
                                                            <textarea class="form-control form-control-sm val-resposta" placeholder="Resposta/Feedback..." rows="1"><?= htmlspecialchars($sn['resposta_professor'] ?? '') ?></textarea>
                                                        </div>
                                                        <button class="btn btn-sm btn-success btn-save-nota w-100" onclick="saveNota(this)">Guardar</button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Frequência / Chamada -->
            <div class="tab-pane fade" id="pane-chamada">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold mb-0">Livro de Ponto & Frequência dos estudantes</h4>
                            <input type="date" class="form-control w-25" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-4 mb-4">
                            <label class="form-label small fw-bold">Selecionar Turma & Disciplina</label>
                            <select class="form-select border-0 bg-light shadow-sm" style="border-radius: 8px;" onchange="switchClass(this.value)">
                                <?php if(empty($data['classes'])): ?>
                                    <option disabled>Nenhuma turma atribuída</option>
                                <?php else: ?>
                                    <?php foreach($data['classes'] as $c): ?>
                                        <option value="<?= $c['turma_id'] ?>|<?= $c['disciplina_id'] ?>" <?= ($data['selected_turma'] == $c['turma_id'] && $data['selected_disciplina'] == $c['disciplina_id']) ? 'selected' : '' ?>>
                                            <?= $c['turma_codigo'] ?> - <?= $c['disciplina_nome'] ?> (<?= $c['turno'] ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-4">
                            <label class="form-label small fw-bold">Tempo / Aula</label>
                            <select id="tempoAula" class="form-select border-0 bg-light shadow-sm" style="border-radius: 8px;">
                                <option value="1º Tempo">1º Tempo</option>
                                <option value="2º Tempo">2º Tempo</option>
                                <option value="3º Tempo">3º Tempo</option>
                                <option value="4º Tempo">4º Tempo</option>
                            </select>
                        </div>
                    </div>
                        <div class="table-responsive">
                            <table id="tabelaChamada" class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0">Estudante</th>
                                        <th class="border-0 text-center">Grupo</th>
                                        <th class="border-0 text-center">Status</th>
                                        <th class="border-0 text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($data['students'])): ?>
                                        <tr><td colspan="3" class="text-center py-4">Nenhum estudante encontrado nesta turma.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($data['students'] as $s): ?>
                                            <tr data-student-id="<?= $s['id'] ?>">
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle text-primary">
                                                            <ion-icon name="person"></ion-icon>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark"><?= $this->e($s['nome_completo']) ?></div>
                                                            <div class="text-muted" style="font-size: 0.75rem;">BI: <?= $this->e($s['bi']) ?></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-light text-dark border fw-bold"><?= $this->e($s['grupo'] ?? 'G1') ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge status-badge bg-success bg-opacity-10 text-success rounded-pill px-3">Presente</span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm btn-group-presenca" data-status="P">
                                                        <button class="btn btn-outline-success active" onclick="setPresenca(this, 'P')" title="Marcar Presença">PRESENTE</button>
                                                        <button class="btn btn-outline-danger" onclick="setPresenca(this, 'F')" title="Marcar Falta">F</button>
                                                        <button class="btn btn-outline-warning" onclick="setPresenca(this, 'J')" title="Justificar Falta">J</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            <label class="form-label fw-bold small">Conteúdo do Sumário (O que foi lecionado hoje?)</label>
                            <textarea id="sumarioConteudo" class="form-control bg-light" rows="3" placeholder="Ex: Introdução às Redes Neurais e Backpropagation..."></textarea>
                        </div>
                        <div class="mt-4 text-center">
                            <button type="button" class="btn btn-success btn-lg px-5 shadow-sm" onclick="submeterSumario(this)">
                                <ion-icon name="checkmark-circle-outline"></ion-icon> Finalizar Sumário e Presenças
                            </button>
                        </div>



                        <hr class="my-5">

                        <h5 class="fw-bold mb-3 mt-4 text-secondary">
                            <ion-icon name="list-outline" class="me-2"></ion-icon> Histórico de Sumários Enviados
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover border rounded">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data</th>
                                        <th>Turma</th>
                                        <th>Conteúdo do Sumário</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($data['meus_sumarios'])): ?>
                                        <tr><td colspan="3" class="text-center text-muted py-3">Nenhum sumário enviado ainda.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($data['meus_sumarios'] as $ms): ?>
                                            <tr>
                                                <td class="small nowrap"><?= date('d/m/Y', strtotime($ms['data'])) ?></td>
                                                <td><span class="badge bg-light text-dark"><?= $ms['turma_codigo'] ?></span></td>
                                                <td class="small text-muted"><?= nl2br($this->e($ms['conteudo'])) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Materiais -->
            <div class="tab-pane fade" id="pane-materiais">
                <div class="card shadow-sm border-0 border-top border-4 border-primary">
                    <div class="card-body p-5 text-center">
                        <ion-icon name="cloud-upload" style="font-size: 5rem; color: #3B82F6;"></ion-icon>
                        <h4 class="fw-bold mt-3">Partilha de Material Didático</h4>
                        <p class="text-muted">Partilhe PDFs, Slides e Exercícios com a turma selecionada.</p>
                        
                        <div class="row text-start mt-4 justify-content-center">
                            <div class="col-md-6">
                                <form id="formUploadMaterial" enctype="multipart/form-data">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Selecionar Turma & Disciplina</label>
                                        <select name="turma_id" class="form-select border-0 bg-light shadow-sm" style="border-radius: 8px;">
                                            <?php if(empty($data['classes'])): ?>
                                                <option disabled>Nenhuma turma atribuída</option>
                                            <?php else: ?>
                                                <?php foreach($data['classes'] as $c): ?>
                                                    <option value="<?= $c['turma_id'] ?>" data-disc="<?= $c['disciplina_id'] ?>">
                                                        <?= $c['turma_codigo'] ?> - <?= $c['disciplina_nome'] ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <input type="hidden" name="disciplina_id" id="upl_disc_id" value="<?= $data['classes'][0]['disciplina_id'] ?? 0 ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-bold small">Ficheiro Suportado (PDF / PPTX / ZIP)</label>
                                        <input type="file" name="ficheiro" class="form-control border-primary bg-light" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="fw-bold small">Título Visível para os Estudantes</label>
                                        <input type="text" name="titulo" class="form-control" placeholder="Ex: Ficha de Exercícios 01" required>
                                    </div>

                                    <button type="button" onclick="publicarMaterial()" class="btn btn-success w-100 fw-bold py-3 shadow-sm">
                                        <ion-icon name="send"></ion-icon> Publicar Material Online
                                    </button>
                                </form>
                            </div>
                        </div>

                        <hr class="my-5">

                        <div class="text-start mt-4">
                            <h5 class="fw-bold mb-4">Seus Materiais Publicados</h5>
                            <div class="row g-3">
                                <?php if(empty($data['meus_materiais'])): ?>
                                    <div class="col-12 text-center py-4 text-muted">Ainda não publicou nenhum material.</div>
                                <?php else: ?>
                                    <?php foreach($data['meus_materiais'] as $m): ?>
                                        <div class="col-md-6">
                                            <div class="card border-0 shadow-sm border-start border-4 border-success">
                                                <div class="card-body d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="fw-bold mb-1"><?= htmlspecialchars($m['titulo']) ?></h6>
                                                        <small class="text-muted"><?= $m['turma_codigo'] ?> • <?= $m['disciplina_nome'] ?> • <?= strtoupper($m['tipo_ficheiro']) ?></small>
                                                    </div>
                                                    <a href="<?= URL_ROOT ?>/<?= $m['caminho_ficheiro'] ?>" target="_blank" class="btn btn-sm btn-light rounded-circle p-2">
                                                        <ion-icon name="download" class="fs-4 text-dark"></ion-icon>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendário Acadêmico -->
            <div class="tab-pane fade" id="pane-calendario">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div id="calendar-prof" style="min-height: 500px;"></div>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white py-3 border-bottom border-light">
                        <h6 class="fw-bold mb-0">Listagem de Agendamentos Próprios</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-muted small">
                                    <tr>
                                        <th>Data/Hora</th>
                                        <th>Título</th>
                                        <th>Tipo</th>
                                        <th class="text-end">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($data['agendamentos_proprios'])): ?>
                                        <tr><td colspan="4" class="text-center py-4 text-muted">Sem agendamentos futuros.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($data['agendamentos_proprios'] as $ev): ?>
                                            <tr>
                                                <td class="small fw-bold"><?= date('d/m/Y H:i', strtotime($ev['data_evento'])) ?></td>
                                                <td class="small"><?= htmlspecialchars($ev['titulo']) ?></td>
                                                <td><span class="badge bg-primary bg-opacity-10 text-primary small"><?= $ev['tipo'] ?></span></td>
                                                <td class="text-end">
                                                    <button onclick="deleteEvento(<?= $ev['id'] ?>)" class="btn btn-sm btn-outline-danger border-0">
                                                        <ion-icon name="trash-outline"></ion-icon>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-4 mt-4">
                    <div class="card-header bg-white py-3 border-bottom border-light">
                        <h6 class="fw-bold mb-0">Eventos Globais e Feriados</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data</th>
                                        <th>Evento</th>
                                        <th>Tipo</th>
                                        <th>Alcance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($data['eventos_globais'])): ?>
                                        <tr><td colspan="4" class="text-center py-4 text-muted">Nenhum evento global registado.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($data['eventos_globais'] as $e): ?>
                                            <tr>
                                                <td class="fw-bold" style="white-space: nowrap;">
                                                    <?php if ($e['data_evento'] != $e['data_fim']): ?>
                                                        <?= date('d/m/Y', strtotime($e['data_evento'])) ?> a <?= date('d/m/Y', strtotime($e['data_fim'])) ?>
                                                    <?php else: ?>
                                                        <?= date('d/m/Y', strtotime($e['data_evento'])) ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div style="width:12px; height:12px; border-radius:3px; background:<?= $e['cor'] ?>"></div>
                                                        <?= htmlspecialchars($e['titulo']) ?>
                                                    </div>
                                                </td>
                                                <td><span class="badge bg-light text-dark border"><?= $e['tipo'] ?></span></td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        <?= ($e['destinatario_tipo'] == 'Global') ? 'Público: Todos' : htmlspecialchars($e['destinatario_tipo']) ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Legend Official Code -->
                <div class="mt-4 p-4 bg-white shadow-sm rounded-4 border-start border-4 border-success">
                    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                        <ion-icon name="color-palette-outline" class="text-success"></ion-icon>
                        Legenda Oficial do Ano Letivo
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-3 d-flex align-items-center gap-2">
                            <div style="width:18px; height:18px; background:#f59e0b; border-radius:4px;"></div>
                            <span class="small fw-bold text-dark">Ano Letivo / Férias</span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center gap-2">
                            <div style="width:18px; height:18px; background:#ef4444; border-radius:4px;"></div>
                            <span class="small fw-bold text-dark">Exames</span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center gap-2">
                            <div style="width:18px; height:18px; background:#1e3a8a; border-radius:4px;"></div>
                            <span class="small fw-bold text-dark">Feriados</span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center gap-2">
                            <div style="width:18px; height:18px; background:#60a5fa; border-radius:4px;"></div>
                            <span class="small fw-bold text-dark">Recurso</span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center gap-2">
                            <div style="width:18px; height:18px; background:#14532d; border-radius:4px;"></div>
                            <span class="small fw-bold text-dark">Semana Transitória</span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center gap-2">
                            <div style="width:18px; height:18px; background:#4ade80; border-radius:4px;"></div>
                            <span class="small fw-bold text-dark">Palestras</span>
                        </div>
                        <div class="col-md-3 d-flex align-items-center gap-2">
                            <div style="width:18px; height:18px; background:#78350f; border-radius:4px;"></div>
                            <span class="small fw-bold text-dark">Futebol / Excursão</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comunicados -->
            <div class="tab-pane fade" id="pane-comunicados">
                <div class="row g-4">
                    <!-- Enviar -->
                    <div class="col-md-5">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <h4 class="fw-bold mb-4">Enviar Comunicado</h4>
                                <form id="formEnviarComunicado">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Título</label>
                                        <input type="text" name="titulo" class="form-control bg-light" placeholder="Assunto do aviso..." required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Mensagem</label>
                                        <textarea name="conteudo" class="form-control bg-light" rows="4" placeholder="Escreva a mensagem para a turma..." required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Destinatário</label>
                                        <select name="turma_id" class="form-select bg-light">
                                            <?php foreach($data['classes'] as $c): ?>
                                                <option value="<?= $c['turma_id'] ?>">Turma <?= $c['turma_codigo'] ?> (<?= $c['disciplina_nome'] ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="hidden" name="tipo_destinatario" value="Turma_Especifica">
                                    </div>
                                    <button type="button" onclick="enviarComunicado()" class="btn btn-primary w-100"><ion-icon name="send"></ion-icon> Enviar Agora</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Recebidos -->
                    <div class="col-md-7">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <h4 class="fw-bold mb-4">Mural de Avisos</h4>
                                <div class="row overflow-auto" style="max-height: 500px;">
                                    <?php if(empty($data['comunicados'])): ?>
                                        <p class="text-center text-muted py-3">Nenhum comunicado encontrado.</p>
                                    <?php else: ?>
                                        <?php foreach($data['comunicados'] as $c): ?>
                                            <div class="col-md-6 mb-4">
                                                <div class="card h-100 border-0 shadow-sm bg-light-subtle rounded-4 overflow-hidden position-relative">
                                                    <?php if($c['lido'] == 0): ?>
                                                        <div class="position-absolute top-0 end-0 p-3">
                                                            <span class="badge bg-danger rounded-pill shadow-sm">Novo</span>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="card-body p-4">
                                                        <div class="d-flex align-items-center mb-3">
                                                            <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                                                <ion-icon name="megaphone-outline" class="text-primary fs-4"></ion-icon>
                                                            </div>
                                                            <div>
                                                                <h6 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($c['titulo']) ?></h6>
                                                                <small class="text-muted"><?= date('d/m/Y', strtotime($c['data_publicacao'])) ?> • Por: <?= htmlspecialchars($c['autor_nome']) ?></small>
                                                            </div>
                                                            <?php if(isset($c['criado_por']) && $c['criado_por'] == $_SESSION['user_id']): ?>
                                                                <div class="ms-auto align-self-start">
                                                                    <button onclick="excluirComunicado(<?= $c['id'] ?>)" class="btn btn-sm btn-outline-danger border-0 p-1" title="Excluir Aviso">
                                                                        <ion-icon name="trash-outline" class="fs-5 m-0"></ion-icon>
                                                                    </button>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <p class="text-muted small mb-4" style="line-height: 1.6;">
                                                            <?= nl2br(htmlspecialchars($c['conteudo'])) ?>
                                                        </p>
                                                        <?php if($c['lido'] == 0): ?>
                                                            <div class="text-end">
                                                                <button onclick="marcarComoLido(<?= $c['id'] ?>, this)" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                                                                    <ion-icon name="checkmark-done-outline" class="me-1"></ion-icon> Marcar como Lido
                                                                </button>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <button onclick="removerComunicado(<?= $c['id'] ?>)" class="btn btn-sm btn-link text-danger p-0" title="Eliminar da lista">
                                                                    <ion-icon name="trash-outline"></ion-icon> Remover
                                                                </button>
                                                                <span class="text-success small fw-bold">
                                                                    <ion-icon name="checkmark-circle-outline" class="me-1"></ion-icon> Lido
                                                                </span>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reclamações de Notas -->
            <div class="tab-pane fade" id="pane-reclamacoes">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="fw-bold mb-0">Centro de Contestações</h4>
                                <p class="text-muted small mb-0">Gerencie as contestações de notas com rastreabilidade completa.</p>
                            </div>
                            <div class="d-flex gap-2">
                                <?php $pendentes_count = count($data['contestacoes_pendentes'] ?? []); ?>
                                <?php if ($pendentes_count > 0): ?>
                                    <span class="badge bg-danger rounded-pill fs-6 px-3"><?= $pendentes_count ?> Pendente(s)</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- 🚨 ALERTAS DE CORRECÇÃO OBRIGATÓRIA -->
                        <?php if (!empty($data['contestacoes_historico'])): ?>
                            <?php foreach ($data['contestacoes_historico'] as $h): ?>
                                <?php if ($h['status'] === 'Aguardando_Correcao'): ?>
                                    <div class="alert alert-danger border-0 border-start border-4 border-danger shadow-sm rounded-4 mb-4 p-4">
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger">
                                                <ion-icon name="alert-circle" class="fs-2"></ion-icon>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-0 text-dark">Ordem Administrativa: Rectificação de Notas</h5>
                                                <span class="small text-muted">A administração decidiu a favor do aluno <strong><?= htmlspecialchars($h['estudante_nome']) ?></strong>. Proceda à correcção.</span>
                                            </div>
                                        </div>
                                        <div class="mt-2 p-3 bg-white bg-opacity-50 rounded-4 border">
                                            <small class="fw-bold text-danger text-uppercase small d-block mb-1">Fundamentação da Decisão:</small>
                                            <p class="mb-0 small italic">"<?= nl2br(htmlspecialchars($h['decisao_final'])) ?>"</p>
                                        </div>
                                        <div class="mt-3 text-end">
                                            <a href="#pane-notas" class="btn btn-sm btn-danger fw-bold rounded-pill px-4" onclick="switchClass('<?= $h['turma_id'] ?>|<?= $h['disciplina_id'] ?>'); $('#tab-notas').tab('show');">
                                                <ion-icon name="create-outline"></ion-icon> Corrigir Notas Agora
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- 📋 ALERTAS DE CONVOCATÓRIA -->
                        <?php if (!empty($data['contestacoes_historico'])): ?>
                            <?php foreach ($data['contestacoes_historico'] as $h): ?>
                                <?php if ($h['status'] === 'Aguardando_Comparecimento'): ?>
                                    <div class="alert alert-warning border-0 border-start border-4 border-warning shadow-sm rounded-4 mb-4 p-4">
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            <div class="bg-warning bg-opacity-20 p-3 rounded-circle text-warning">
                                                <ion-icon name="business" class="fs-2"></ion-icon>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold mb-0 text-dark">Convocação Administrativa: Mediação Acadêmica</h5>
                                                <span class="small text-muted">Referente ao aluno: <strong><?= htmlspecialchars($h['estudante_nome']) ?></strong> (<?= htmlspecialchars($h['disciplina_nome']) ?>)</span>
                                            </div>
                                        </div>
                                        <div class="row g-3 bg-white bg-opacity-50 p-3 rounded-4 border">
                                            <div class="col-md-3">
                                                <div class="small fw-bold text-muted text-uppercase mb-1">Data</div>
                                                <div class="fw-bold fs-5"><?= date('d/m/Y', strtotime($h['data_reuniao'])) ?></div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="small fw-bold text-muted text-uppercase mb-1">Hora</div>
                                                <div class="fw-bold fs-5"><?= substr($h['hora_reuniao'], 0, 5) ?></div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small fw-bold text-muted text-uppercase mb-1">Local / Gabinete</div>
                                                <div class="fw-bold fs-5"><?= htmlspecialchars($h['local_reuniao']) ?></div>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <span class="badge bg-dark rounded-pill px-3">COMPARECIMENTO OBRIGATÓRIO</span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Sub-tabs -->
                        <ul class="nav nav-tabs mb-4" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#sub-cont-pendentes">
                                    <ion-icon name="alert-circle-outline" class="me-1"></ion-icon>
                                    Aguardam Resposta
                                    <?php if ($pendentes_count > 0): ?>
                                        <span class="badge bg-danger ms-1"><?= $pendentes_count ?></span>
                                    <?php endif; ?>
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sub-cont-historico">
                                    <ion-icon name="time-outline" class="me-1"></ion-icon>
                                    Histórico Completo
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Pendentes -->
                            <div class="tab-pane fade show active" id="sub-cont-pendentes">
                                <?php if (empty($data['contestacoes_pendentes'])): ?>
                                    <div class="text-center py-5">
                                        <ion-icon name="checkmark-circle-outline" style="font-size:3rem; color:#22c55e;"></ion-icon>
                                        <p class="text-muted mt-3">Nenhuma contestação aguarda a sua resposta.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="row g-3">
                                        <?php foreach ($data['contestacoes_pendentes'] as $c): ?>
                                            <div class="col-12">
                                                <div class="card border-start border-4 border-danger shadow-sm">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                                            <div>
                                                                <div class="fw-bold text-dark"><?= htmlspecialchars($c['estudante_nome']) ?></div>
                                                                <div class="d-flex gap-2 mt-1 flex-wrap">
                                                                    <span class="badge bg-secondary"><?= $c['turma_codigo'] ?></span>
                                                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25"><?= htmlspecialchars($c['disciplina_nome']) ?></span>
                                                                    <span class="badge bg-light text-muted border small"><?= date('d/m/Y H:i', strtotime($c['data_abertura'] ?? $c['data_resposta'])) ?></span>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex gap-2">
                                                                <button class="btn btn-sm btn-primary fw-bold rounded-pill" onclick="switchClass('<?= $c['turma_id'] ?>|<?= $c['disciplina_id'] ?>'); $('#tab-notas').tab('show');">
                                                                    <ion-icon name="create-outline"></ion-icon> Corrigir Nota
                                                                </button>
                                                                <button class="btn btn-sm btn-danger fw-bold rounded-pill"
                                                                    onclick="abrirRespostaContestacao(<?= $c['estudante_id_real'] ?>, '<?= htmlspecialchars($c['estudante_nome']) ?>', <?= $c['turma_id'] ?>, <?= $c['disciplina_id'] ?>)">
                                                                    <ion-icon name="chatbubble-ellipses-outline"></ion-icon> Responder
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="mt-3 p-3 bg-danger bg-opacity-10 rounded border-start border-3 border-danger">
                                                            <small class="text-muted fw-bold d-block mb-1"><ion-icon name="megaphone-outline"></ion-icon> Argumentação do Aluno:</small>
                                                            <p class="mb-0 small"><?= nl2br(htmlspecialchars($c['comentario'])) ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Histórico -->
                            <div class="tab-pane fade" id="sub-cont-historico">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle datatable-simple">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Data</th>
                                                <th>Estudante</th>
                                                <th>Turma / Disc.</th>
                                                <th>Estado</th>
                                                <th>Detalhes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($data['contestacoes_historico'])): ?>
                                                <tr><td colspan="5" class="text-center py-4 text-muted">Sem histórico de contestações.</td></tr>
                                            <?php else: ?>
                                                <?php foreach ($data['contestacoes_historico'] as $h): ?>
                                                    <?php
                                                    $badge = match($h['status']) {
                                                        'Pendente','Reclamado' => 'bg-danger',
                                                        'Respondido'           => 'bg-info text-dark',
                                                        'Resolvido'            => 'bg-success',
                                                        'Impasse'              => 'bg-dark',
                                                        'Em_Mediacao'          => 'bg-warning text-dark',
                                                        'Aguardando_Comparecimento' => 'bg-warning text-dark',
                                                        'Aguardando_Correcao'  => 'bg-danger animate-pulse',
                                                        'Encerrado','Concordado' => 'bg-secondary',
                                                        default => 'bg-secondary'
                                                    };
                                                    ?>
                                                    <tr>
                                                        <td class="small"><?= date('d/m/Y', strtotime($h['data_resposta'])) ?></td>
                                                        <td class="fw-bold"><?= htmlspecialchars($h['estudante_nome']) ?></td>
                                                        <td>
                                                            <span class="badge bg-secondary me-1"><?= $h['turma_codigo'] ?></span>
                                                            <small><?= htmlspecialchars($h['disciplina_nome']) ?></small>
                                                        </td>
                                                        <td><span class="badge <?= $badge ?>"><?= $h['status'] ?></span></td>
                                                        <td class="small text-muted" style="max-width:250px">
                                                            <?= htmlspecialchars(mb_strimwidth($h['comentario'] ?? '', 0, 80, '...')) ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- /tab-content -->
                    </div>
                </div>
            </div><!-- Close pane-reclamacoes -->

            <!-- Minha Assiduidade -->
            <div class="tab-pane fade" id="pane-assiduidade">
                <div class="card shadow-sm border-0 border-top border-4 border-info">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold mb-0">Meu Histórico de Assiduidade</h4>
                            <button class="btn btn-sm btn-outline-info" onclick="printSection('pane-assiduidade')"><ion-icon name="print-outline"></ion-icon> Imprimir</button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle datatable-simple">
                                <thead class="table-light">
                                    <tr>
                                        <th>Data</th>
                                        <th>Tempo</th>
                                        <th>Turma / Disciplina</th>
                                        <th>Status</th>
                                        <th>Justificativa / Observação</th>
                                        <th>Validado Por</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($data['minha_assiduidade'])): ?>
                                        <tr><td colspan="6" class="text-center py-4 text-muted">Nenhum registo de assiduidade encontrado.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($data['minha_assiduidade'] as $a): ?>
                                            <tr>
                                                <td class="fw-bold"><?= date('d/m/Y', strtotime($a['data'])) ?></td>
                                                <td><span class="badge bg-light text-dark border"><?= $a['tempo'] ?></span></td>
                                                <td><div class="small fw-bold text-primary"><?= $a['turma_codigo'] ?></div><div class="extra-small text-muted"><?= $a['disciplina_nome'] ?></div></td>
                                                <td>
                                                    <?php if($a['status'] === 'Presença'): ?>
                                                        <span class="badge bg-success"><ion-icon name="checkmark-circle"></ion-icon> Presente</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger"><ion-icon name="close-circle"></ion-icon> Falta</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="small">
                                                    <?php if(!empty($a['justificacao'])): ?>
                                                        <div class="p-2 bg-light rounded italic border-start border-3 border-warning">
                                                            "<?= htmlspecialchars($a['justificacao']) ?>"
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted italic">Sem observações</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="small text-muted"><?= htmlspecialchars($a['marcado_por_nome'] ?? 'Admin') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<!-- Modal Horário Completo -->
<div class="modal fade" id="horarioCompletoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg px-2">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-dark pt-3">Grade Horária Semanal - Docente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="card border-0 shadow-none">
                    <div class="card-body p-4">
                        <?php
                        $gridData = $data['gridData'] ?? [];
                        $turmaInfo = [
                            'codigo' => 'HORÁRIO DOCENTE',
                            'nivel' => $data['professor']['nome_completo'] ?? ''
                        ];
                        if (file_exists(__DIR__ . '/../shared/horario_grid.php')) {
                            include __DIR__ . '/../shared/horario_grid.php';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pb-4">
                <button type="button" class="btn btn-secondary px-4 fw-bold shadow-sm" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>

$(document).ready(function() {
    // Atualizar disciplina_id ao mudar a turma no upload
    $('#formUploadMaterial select[name="turma_id"]').on('change', function() {
        const discId = $(this).find(':selected').data('disc');
        $('#upl_disc_id').val(discId);
    });

    $.fn.dataTable.ext.errMode = 'none';
    $('.datatable-simple').each(function() {
        try {
            if (!$.fn.DataTable.isDataTable(this)) {
                $(this).DataTable({
                    language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-PT.json' },
                    pageLength: 10, bLengthChange: false, info: false, retrieve: true
                });
            }
        } catch(e) { /* skip tables with column count issues */ }
    });

    // Subir ao Topo Global
    $('<button id="backToTop" class="btn btn-dark shadow-lg" style="position:fixed; bottom:30px; right:30px; border-radius:50%; width:50px; height:50px; display:none; z-index:999; display:flex; align-items:center; justify-content:center;"><ion-icon name="arrow-up-outline"></ion-icon></button>').appendTo('body');
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) { $('#backToTop').fadeIn(); } else { $('#backToTop').fadeOut(); }
    });
    $('#backToTop').click(function() { $('html, body').animate({scrollTop: 0}, 400); return false; });
});

function saveNota(btn) {
    const row = $(btn).closest('tr');
    const data = {
        estudante_id: row.data('student-id'),
        turma_id: row.data('turma-id'),
        disciplina_id: row.data('disc-id'),
        tpc: row.find('.val-tpc').val(),
        ap: row.find('.val-ap').val(),
        tpi: row.find('.val-tpi').val(),
        ce: row.find('.val-ce').val(),
        exame: row.find('.val-exame').val(),
        resposta_professor: row.find('.val-resposta').val(),
        csrf_token: '<?php echo $_SESSION['csrf_token']; ?>'
    };

    $(btn).html('<span class="spinner-border spinner-border-sm"></span>').prop('disabled', true);

    $.post('<?= URL_ROOT ?>/professor/saveNota', data, function(res) {
        if(res.success) {
            alert('Notas salvas com sucesso!');
            location.reload(); // Recarregar para ver o total atualizado
        } else {
            alert('Erro ao salvar notas.');
        }
    }, 'json').always(function() {
        $(btn).text('Guardar').prop('disabled', false);
    });
}

function enviarComunicado() {
    const form = $('#formEnviarComunicado');
    const btn = form.find('button');
    const data = form.serialize();

    if(!form.find('textarea').val() || !form.find('input[name="titulo"]').val()) {
        alert('Por favor, preencha o título e a mensagem.');
        return;
    }

    btn.html('<span class="spinner-border spinner-border-sm"></span>').prop('disabled', true);

    $.post('<?= URL_ROOT ?>/professor/saveComunicado', data, function(res) {
        if(res.success) {
            alert('Comunicado enviado com sucesso!');
            location.reload();
        } else {
            alert('Erro ao enviar comunicado.');
        }
    }, 'json').always(function() {
        btn.html('<ion-icon name="send"></ion-icon> Enviar Agora').prop('disabled', false);
    });
}

function excluirComunicado(id) {
    if (confirm('Tem certeza que deseja excluir este aviso? Ele sumirá para todos os alunos e professores desta turma.')) {
        $.post('<?= URL_ROOT ?>/professor/deleteComunicado', { id: id, csrf_token: '<?php echo $_SESSION['csrf_token']; ?>' }, function(res) {
            if (res.success) {
                location.reload();
            } else {
                alert('Erro ao excluir o aviso. Você só pode excluir os avisos criados por você mesmo.');
            }
        }, 'json');
    }
}

function setPresenca(btn, status) {
    const el = $(btn);
    const row = el.closest('tr');
    const badge = row.find('.status-badge');
    const group = row.find('.btn-group-presenca');
    
    // 1. Resetar irmãos
    el.siblings('button').removeClass('active btn-success btn-danger btn-warning').addClass('btn-outline-secondary');
    el.siblings('button').each(function() {
        const s = $(this).attr('onclick');
        if (s.includes("'P'")) $(this).text('P').addClass('btn-outline-success').removeClass('btn-outline-secondary');
        if (s.includes("'F'")) $(this).text('F').addClass('btn-outline-danger').removeClass('btn-outline-secondary');
        if (s.includes("'J'")) $(this).text('J').addClass('btn-outline-warning').removeClass('btn-outline-secondary');
    });

    // 2. Ativar este botão
    el.addClass('active').removeClass('btn-outline-success btn-outline-danger btn-outline-warning btn-outline-secondary');
    if (status === 'P') {
        el.text('PRESENTE').addClass('btn-success');
        badge.text('Presente').removeClass('bg-danger bg-warning text-danger text-warning').addClass('bg-success text-success');
    } else if (status === 'F') {
        el.text('FALTA').addClass('btn-danger');
        badge.text('Falta').removeClass('bg-success bg-warning text-success text-warning').addClass('bg-danger text-danger');
    } else if (status === 'J') {
        el.text('JUSTIFICADA').addClass('btn-warning');
        badge.text('Justificada').removeClass('bg-success bg-danger text-success text-danger').addClass('bg-warning text-warning');
    }
    
    group.attr('data-status', status);
}

function submeterSumario(btn) {
    const conteudo = $('#sumarioConteudo').val();
    if (!conteudo) {
        alert('Por favor, preencha o conteúdo do sumário.');
        return;
    }

    const presencas = {};
    $('#tabelaChamada tbody tr').each(function() {
        const estId = $(this).data('student-id');
        const status = $(this).find('.btn-group-presenca').data('status');
        if (estId) presencas[estId] = status;
    });

    const data = {
        data: $('#pane-chamada input[type="date"]').val(),
        turma_id: $('#pane-chamada select').first().val().split('|')[0],
        disciplina_id: $('#pane-chamada select').first().val().split('|')[1], 
        tempo: $('#tempoAula').val(),
        conteudo: conteudo,
        presencas: presencas,
        csrf_token: '<?php echo $_SESSION['csrf_token']; ?>'
    };

    $(btn).html('<span class="spinner-border spinner-border-sm"></span> Submetendo...').prop('disabled', true);

    $.post('<?= URL_ROOT ?>/professor/saveSummary', data, function(res) {
        if (res.success) {
            alert('Sumário e Chamada submetidos com sucesso!');
            location.reload();
        } else {
            alert('Erro ao submeter. Verifique se preencheu todos os campos.');
        }
    }, 'json').always(function() {
        $(btn).html('<ion-icon name="checkmark-done-outline"></ion-icon> Submeter Sumário e Chamada').prop('disabled', false);
    });
}

function marcarComoLido(id, btn) {
    const card = $(btn).closest('.card');
    $(btn).html('<span class="spinner-border spinner-border-sm"></span>...').prop('disabled', true);
    
    $.post('<?= URL_ROOT ?>/professor/marcarLido', { comunicado_id: id, csrf_token: '<?php echo $_SESSION['csrf_token']; ?>' }, function(res) {
        if (res.success) {
            // Remove unread badge
            card.find('.badge.bg-danger').fadeOut();
            // Replace button with "Lido" status
            $(btn).parent().html('<span class="text-success small fw-bold"><ion-icon name="checkmark-circle-outline" class="me-1"></ion-icon> Lido</span>');
        } else {
            alert('Erro ao marcar como lido.');
            $(btn).html('<ion-icon name="checkmark-done-outline" class="me-1"></ion-icon> Marcar como Lido').prop('disabled', false);
        }
    }, 'json');
}

function publicarMaterial() {
    const form = document.getElementById('formUploadMaterial');
    const formData = new FormData(form);
    const btn = $(form).find('button');

    if (!form.titulo.value || !form.ficheiro.value) {
        alert('Por favor, preencha o título e selecione um ficheiro.');
        return;
    }

    btn.html('<span class="spinner-border spinner-border-sm"></span> A publicar...').prop('disabled', true);

    $.ajax({
        url: '<?= URL_ROOT ?>/professor/uploadMaterial',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(res) {
            if (res.success) {
                alert('Material publicado com sucesso!');
                location.reload();
            } else {
                alert('Erro: ' + (res.message || 'Falha no upload.'));
            }
        },
        error: function() {
            alert('Erro crítico ao enviar ficheiro. Verifique o tamanho do ficheiro.');
        },
        complete: function() {
             btn.html('<ion-icon name="send"></ion-icon> Publicar Material Online').prop('disabled', false);
        }
    });
}
    // Switch Class Function
    function switchClass(val) {
        if (!val) return;
        const parts = val.split('|');
        const activeTab = $('.nav-link.active').attr('href').replace('#', '');
        window.location.href = `<?= URL_ROOT ?>/professor?turma_id=${parts[0]}&disciplina_id=${parts[1]}&tab=${activeTab}`;
    }

    // Restore Tab and Live Grades
    $(document).ready(function() {
        $.fn.dataTable.ext.errMode = 'none';
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab) {
            const tabEl = document.querySelector(`#tab-${tab}`);
            if (tabEl) {
                const bsTab = new bootstrap.Tab(tabEl);
                bsTab.show();
            }
        }

        // Live Calculation for Grades
        $('.val-tpc, .val-ap, .val-tpi, .val-ce, .val-exame').on('input', function() {
            const row = $(this).closest('tr');
            const tpc = parseFloat(row.find('.val-tpc').val()) || 0;
            const ap = parseFloat(row.find('.val-ap').val()) || 0;
            const tpi = parseFloat(row.find('.val-tpi').val()) || 0;
            const ce = parseFloat(row.find('.val-ce').val()) || 0;
            const exameInput = row.find('.val-exame').val();
            
            const totalAc = (tpc + ap + tpi + ce);
            row.find('.text-total-ac').text(totalAc.toFixed(1));
            
            if (exameInput !== '') {
                const ex = parseFloat(exameInput) || 0;
                const media = (totalAc + ex) / 2;
                row.find('.text-media-final').text(media.toFixed(1));
            } else {
                row.find('.text-media-final').text('-');
            }
        });
    });

function printSection(paneId) {
    const pane = document.getElementById(paneId);
    if (!pane) return;

    // Capture current values of all inputs/textareas
    const clonedPane = pane.cloneNode(true);
    const inputs = pane.querySelectorAll('input, textarea, select');
    const clonedInputs = clonedPane.querySelectorAll('input, textarea, select');
    
    inputs.forEach((input, index) => {
        if (clonedInputs[index]) {
            const span = document.createElement('span');
            if (input.tagName === 'SELECT') {
                const selectedOption = input.options[input.selectedIndex];
                span.textContent = selectedOption ? selectedOption.text : '-';
            } else {
                span.textContent = input.value || '-';
                // Preserve styling if it's a total column
                if (input.classList.contains('text-total-ac') || input.classList.contains('text-media-final')) {
                    span.className = 'fw-bold fs-5';
                }
            }
            clonedInputs[index].parentNode.replaceChild(span, clonedInputs[index]);
        }
    });

    const printWindow = window.open('', '_blank', 'width=1000,height=800');
    const cssLinks = Array.from(document.querySelectorAll('link[rel="stylesheet"]'))
        .map(l => `<link rel="stylesheet" href="${l.href}">`)
        .join('\n');

    // Official Header Logic
    const turmaLabel = $('#print-turma-label').text() || 'N/A';
    const nivelLabel = $('#print-nivel-label').text() || 'ANO';
    const officialHeader = `
        <div class="text-center mb-4" style="font-size: 1.3rem; font-weight: 800; font-family: 'Outfit', sans-serif; border-bottom: 3px solid #1a1a1a; padding-bottom: 15px; margin-bottom: 25px; color: #1a1a1a; text-transform: uppercase;">
            (Grupo: GHS-${turmaLabel} | HORARIO | Nivel: ${nivelLabel})
        </div>
    `;

    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            ${cssLinks}
            <style>
                body { padding: 40px; font-family: 'Outfit', sans-serif; background: white !important; }
                .card { border: none !important; box-shadow: none !important; }
                .btn, .form-select, .nav, .sidebar, #backToTop, .dataTables_length, .dataTables_filter, .dataTables_info, .dataTables_paginate { display: none !important; }
                table { width: 100% !important; border-collapse: collapse !important; margin-top: 20px; }
                th, td { border: 1px solid #ddd !important; padding: 10px !important; text-align: left; }
                th { background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; color-adjust: exact; }
                @media print {
                    .no-print { display: none !important; }
                    body { padding: 0; margin: 0; }
                    @page { margin: 1cm; }
                }
            </style>
        </head>
        <body>
            ${officialHeader}
            <div class="print-content">
                ${clonedPane.innerHTML}
            </div>
            <div class="mt-5 text-end text-muted small">
                Documento emitido em: ${new Date().toLocaleString()}
            </div>
        </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => { printWindow.print(); }, 800);
}

function abrirRespostaContestacao(estId, nome, tid, did) {
    $('#resp_est_id').val(estId);
    $('#resp_turma_id').val(tid);
    $('#resp_disc_id').val(did);
    $('#resp_nome_aluno').text(nome);
    $('#textoResposta').val('');
    $('#alterou_nota_check').prop('checked', false);
    const modal = new bootstrap.Modal(document.getElementById('modalRespostaContestacao'));
    modal.show();
}

function salvarRespostaContestacao() {
    const btn = $('#btnSalvarResposta');
    const data = {
        estudante_id: $('#resp_est_id').val(),
        turma_id: $('#resp_turma_id').val(),
        disciplina_id: $('#resp_disc_id').val(),
        resposta: $('#textoResposta').val(),
        alterou_nota: $('#alterou_nota_check').is(':checked') ? 1 : 0,
        csrf_token: '<?php echo $_SESSION['csrf_token']; ?>'
    };

    if (!data.resposta.trim()) {
        alert('Por favor, escreva uma resposta / justificativa.');
        return;
    }

    btn.html('<span class="spinner-border spinner-border-sm"></span> a enviar...').prop('disabled', true);
    
    $.post('<?= URL_ROOT ?>/contestacao/responder', data, function(res) {
        if (res.success) {
            alert('Resposta enviada com sucesso! O processo de contestação foi atualizado.');
            location.reload();
        } else {
            alert('Erro ao enviar resposta: ' + (res.message || 'Falha técnica.'));
        }
    }, 'json').fail(function() {
        alert('Erro crítico ao comunicar com o servidor.');
    }).always(function() {
        btn.text('Enviar Resposta').prop('disabled', false);
    });
}

function removerComunicado(id) {
    if (confirm('Deseja remover este comunicado da sua lista? Esta ação não pode ser desfeita.')) {
        $.post('<?= URL_ROOT ?>/professor/removerComunicado', {
            comunicado_id: id,
            csrf_token: '<?php echo $_SESSION['csrf_token']; ?>'
        }, function(res) {
            if (res.success) {
                location.reload();
            } else {
                alert('Erro ao remover comunicado.');
            }
        }, 'json');
    }
}

function deleteEvento(id) {
    if (confirm('Deseja cancelar este agendamento?')) {
        window.location.href = '<?= URL_ROOT ?>/professor/deleteEvento/' + id;
    }
}

// --- FULLCALENDAR INTERATIVO (Pilar 7) ---
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar-prof');
    if (calendarEl) {
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'pt-pt',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            buttonText: {
                today: 'Hoje',
                month: 'Mês',
                week: 'Semana',
                list: 'Agenda'
            },
            events: '<?= URL_ROOT ?>/professor/getCalendarEvents',
            themeSystem: 'bootstrap5',
            eventClick: function(info) {
                alert('Evento: ' + info.event.title + '\nDescrição: ' + (info.event.extendedProps.description || 'Consulta da agenda institucional'));
            }
        });

        // Re-render when tab is shown
        $('button[data-bs-target="#pane-calendario"], a.nav-link[data-pane="pane-calendario"]').on('shown.bs.tab', function () {
            calendar.render();
        });
        
        // Custom trigger for sidebar clicks if needed
        $('.sidebar a[onclick*="pane-calendario"]').on('click', function() {
           setTimeout(() => calendar.render(), 200);
        });
    }
});
</script>

<!-- Modal Resposta Contestação -->
<div class="modal fade" id="modalRespostaContestacao" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold">Responder à Contestação</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p>Respondendo ao aluno: <strong id="resp_nome_aluno"></strong></p>
                <input type="hidden" id="resp_est_id">
                <input type="hidden" id="resp_turma_id">
                <input type="hidden" id="resp_disc_id">
                
                <div class="mb-3">
                    <label class="form-label small fw-bold">Sua Resposta / Justificativa</label>
                    <textarea id="textoResposta" class="form-control" rows="4" placeholder="Explique a nota ao aluno, seja empático e claro..."></textarea>
                </div>
                
                <div class="form-check form-switch mb-3 text-start">
                    <input class="form-check-input" type="checkbox" role="switch" id="alterou_nota_check">
                    <label class="form-check-label small fw-bold text-success" for="alterou_nota_check">
                        Já procedi à alteração da nota do aluno no Pessoal Docente.
                    </label>
                </div>

                <div class="alert alert-info small mb-0">
                    <ion-icon name="information-circle"></ion-icon> Se manteve a nota, a contestação passará ao estado <strong>Respondido</strong>. Caso o aluno rebata, o processo gerará um <strong class="text-danger">Impasse</strong> encaminhado à mediação escolar.
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btnSalvarResposta" onclick="salvarRespostaContestacao()" class="btn btn-danger fw-bold">Enviar Resposta</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
