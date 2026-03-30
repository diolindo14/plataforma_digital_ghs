<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel da Direção - GHS</title>
    <!-- CSS Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Scripts Base -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f1f5f9;
        }

        /* ── Sidebar ────────────────────────────────────── */
        .sidebar {
            background: linear-gradient(180deg, #0B1120 0%, #0f1e35 100%);
            height: 100vh;
            color: white;
            position: fixed;
            width: 270px;
            z-index: 10;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            overflow: hidden;
        }

        .sidebar-brand {
            padding: 1.2rem 1rem .8rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
            flex-shrink: 0;
        }

        .sidebar-brand .logo-wrap {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            border: 2px solid #10B981;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            margin: 0 auto;
            overflow: hidden;
        }

        .sidebar-brand .logo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar-brand h5 {
            font-size: .95rem;
            font-weight: 700;
            margin-top: .5rem;
            margin-bottom: .1rem;
        }

        .sidebar-section-label {
            font-size: .58rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #475569;
            padding: .7rem 1.4rem .25rem;
            flex-shrink: 0;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: .25rem;
            min-height: 0;
        }

        .sidebar-nav::-webkit-scrollbar {
            width: 3px;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 3px;
        }

        .sidebar .nav-link {
            color: #94A3B8;
            text-decoration: none;
            padding: 9px 18px;
            font-size: .85rem;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
            font-weight: 500;
            border-left: 3px solid transparent;
            border-radius: 0;
            margin: 0;
        }

        .sidebar .nav-link ion-icon {
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .sidebar .nav-link:hover {
            background: rgba(16, 185, 129, .08);
            color: #10B981;
            border-left-color: rgba(16, 185, 129, .4);
        }

        .sidebar .nav-link.active {
            background: rgba(16, 185, 129, .15);
            color: #10B981;
            border-left-color: #10B981;
            font-weight: 600;
        }

        .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding: .75rem .5rem;
            flex-shrink: 0;
            background: rgba(0, 0, 0, 0.2);
        }

        .sidebar-footer .nav-link {
            font-size: .8rem;
            padding: 7px 18px;
        }

        /* ── Content ────────────────────────────────────── */
        .content {
            margin-left: 270px;
            padding: 36px 40px;
        }

        .tab-pane {
            animation: fadeIn 0.35s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hover-scale {
            transition: transform .2s;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body>


    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar shadow-lg">

            <!-- Brand / Logo -->
            <div class="sidebar-brand">
                <div class="logo-wrap">
                    <img src="<?= URL_ROOT ?>/img/logo.jpg" alt="Logo GHS">
                </div>
                <h5 class="text-white mb-1">Portal GHS</h5>
                <span class="badge mb-1"
                    style="background:rgba(220,38,38,.25); color:#f87171; font-size:.65rem; letter-spacing:.06em;">DIREÇÃO
                    &amp; ADMIN</span>
                <div class="mt-2 d-flex align-items-center justify-content-center gap-2">
                    <ion-icon name="person-circle-outline" style="color:#10B981; font-size:1rem;"></ion-icon>
                    <span class="text-white-50"
                        style="font-size:.75rem;"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></span>
                </div>
            </div>

            <!-- Nav Links -->
            <div class="sidebar-nav">

                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                    <a class="nav-link active" id="tab-home" data-bs-toggle="pill" data-bs-target="#pane-home"
                        href="javascript:void(0)" role="tab">
                        <ion-icon name="grid-outline"></ion-icon> Dashboard Analytics
                    </a>

                    <div class="sidebar-section-label mt-3">Corpo Escolar</div>
                    <a class="nav-link" id="tab-alunos" data-bs-toggle="pill" data-bs-target="#pane-alunos"
                        href="javascript:void(0)" role="tab">
                        <ion-icon name="people-outline"></ion-icon> Base de Dados Escolar
                    </a>
                    <a class="nav-link" id="tab-professores" data-bs-toggle="pill" data-bs-target="#pane-professores"
                        href="javascript:void(0)" role="tab">
                        <ion-icon name="briefcase-outline"></ion-icon> Corpo Docente
                    </a>
                    <a class="nav-link" id="tab-secretaria" data-bs-toggle="pill" data-bs-target="#pane-secretaria"
                        href="javascript:void(0)" role="tab">
                        <ion-icon name="ribbon-outline"></ion-icon> Equipa da Secretaria
                    </a>
                    <a class="nav-link" id="tab-turmas" data-bs-toggle="pill" data-bs-target="#pane-turmas"
                        href="javascript:void(0)" role="tab">
                        <ion-icon name="business-outline"></ion-icon> Turmas e Cursos
                    </a>
                    <a class="nav-link d-flex align-items-center" id="tab-matriculas" data-bs-toggle="pill" data-bs-target="#pane-matriculas"
                        href="javascript:void(0)" role="tab">
                        <ion-icon name="document-text-outline" class="me-2"></ion-icon> Validação de Matrículas
                        <?php if (!empty($data['matriculas'])): ?>
                            <span class="badge bg-warning text-dark ms-auto" style="font-size: 0.65rem; padding: 0.35em 0.65em;"><?= count($data['matriculas']) ?></span>
                        <?php endif; ?>
                    </a>
                    <a class="nav-link" id="tab-pendentes" data-bs-toggle="pill" data-bs-target="#pane-pendentes"
                        href="javascript:void(0)" role="tab">
                        <ion-icon name="person-add-outline"></ion-icon> Aprovação de Contas
                        <?php if (!empty($data['pendentes'])): ?>
                            <span class="badge bg-danger ms-auto"><?= count($data['pendentes']) ?></span>
                        <?php endif; ?>
                    </a>

                    <div class="sidebar-section-label mt-3">Gestão Académica & Financeira</div>
                    <a class="nav-link" id="tab-pedagogico" data-bs-toggle="pill" data-bs-target="#pane-pedagogico"
                        href="javascript:void(0)" role="tab">
                        <ion-icon name="journal-outline"></ion-icon> Acompanhamento Pedagógico
                    </a>
                    <a class="nav-link d-flex align-items-center" id="tab-financeiro" data-bs-toggle="pill" data-bs-target="#pane-financeiro"
                        href="javascript:void(0)" role="tab">
                        <ion-icon name="cash-outline" class="me-2"></ion-icon> Tesouraria
                        <?php 
                            $pendingFin = array_filter($data['pagamentos'] ?? [], function($p) {
                                return $p['status'] === 'Pendente' && !empty($p['comprovativo_arquivo']);
                            });
                            if (!empty($pendingFin)): 
                        ?>
                            <span class="badge bg-primary ms-auto" style="font-size: 0.65rem; padding: 0.35em 0.65em;"><?= count($pendingFin) ?></span>
                        <?php endif; ?>
                    </a>
                    <a class="nav-link" id="tab-merito" data-bs-toggle="pill" data-bs-target="#pane-merito"
                        href="javascript:void(0)" role="tab">
                        <ion-icon name="trophy-outline"></ion-icon> Gestão de Mérito
                    </a>

                    <div class="sidebar-section-label mt-3">Ações Institucionais</div>
                    <a class="nav-link" id="tab-notificacoes" data-bs-toggle="pill" data-bs-target="#pane-notificacoes"
                        href="javascript:void(0)" role="tab">
                        <ion-icon name="notifications-outline"></ion-icon> Histórico de Alertas
                        <?php if (!empty($data['mensagens_painel'])): ?>
                            <span class="badge bg-primary ms-auto"><?= count($data['mensagens_painel']) ?></span>
                        <?php endif; ?>
                    </a>
                    <a class="nav-link" id="tab-calendario" data-bs-toggle="pill" data-bs-target="#pane-calendario"
                        href="javascript:void(0)" role="tab">
                        <ion-icon name="calendar-number-outline"></ion-icon> Calendário Escolar
                    </a>
                    <a class="nav-link" id="tab-auditoria" data-bs-toggle="pill" data-bs-target="#pane-auditoria"
                        href="javascript:void(0)" role="tab">
                        <ion-icon name="shield-half-outline"></ion-icon> Auditoria de Acessos
                    </a>
                </div>

                <div class="sidebar-section-label">Navegação Externa</div>
                <div class="nav flex-column nav-pills">
                    <a class="nav-link text-warning" href="<?= URL_ROOT ?>/" target="_blank">
                        <ion-icon name="earth-outline"></ion-icon> Voltar ao Site Público
                    </a>
                    <a class="nav-link text-danger fw-bold" href="<?= URL_ROOT ?>/auth/logout">
                        <ion-icon name="log-out-outline"></ion-icon> Terminar Sessão
                    </a>
                </div>
            </div>

        </nav>

        <!-- Main Content -->
        <main class="content flex-grow-1">

            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <div>
                    <h2 class="fw-bold text-dark">Painel Administrativo da Direção</h2>
                    <p class="text-muted mb-0">Controlo Total - Green Hard & Softh</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2 border px-3 py-2 rounded-pill bg-white shadow-sm">
                        <ion-icon name="person-circle" style="font-size: 1.8rem; color: #DC2626;"></ion-icon>
                        <span class="fw-bold text-dark"><?= $this->e($_SESSION['user_name']) ?></span>
                    </div>
                    <a href="<?= URL_ROOT ?>/auth/logout"
                        class="btn btn-sm btn-outline-danger border-0 d-flex align-items-center gap-1 fw-bold">
                        <ion-icon name="log-out-outline"></ion-icon> Sair
                    </a>
                </div>
            </div>

            <?php if (isset($_SESSION['flash_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $this->e($_SESSION['flash_success']);
                    unset($_SESSION['flash_success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['flash_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $this->e($_SESSION['flash_error']);
                    unset($_SESSION['flash_error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- 🏆 QUADRO DE MÉRITO ACADÉMICO (visível apenas quando há dados) -->
            <?php if (!empty($ranking_escola)): ?>
                <div class="row mb-3">
                    <div class="col-12 col-xl-4">
                        <?php
                        $show_details = true;
                        include __DIR__ . '/../partials/merit_board.php';
                        ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="tab-content" id="v-pills-tabContent">

                <!-- Gestão de Mérito Escolar -->
                <div class="tab-pane fade" id="pane-merito" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="fw-bold mb-0 text-dark">Gestão de Mérito Semestral</h2>
                            <p class="text-muted small">Atribuição oficial de certificados aos 2 melhores alunos por
                                semestre.</p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4 bg-light border border-success border-opacity-25 rounded-4">
                            <h5 class="fw-bold text-success mb-3"><ion-icon name="ribbon-outline"
                                    class="me-2"></ion-icon> Emitir Certificados de Mérito</h5>
                            <p class="small text-muted mb-4">Selecione o semestre e o ano letivo para listar os melhores
                                alunos (Top 10). Escolha os premiados e defina o alcance do comunicado.</p>

                            <form id="formMeritoPesquisa" onsubmit="event.preventDefault(); carregarTopAlunos();">
                                <input type="hidden" id="csrf_token_merito" value="<?= $_SESSION['csrf_token'] ?>">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small">Semestre Concluído</label>
                                        <select id="merito_semestre" class="form-select shadow-sm" required>
                                            <option value="1">1º Semestre</option>
                                            <option value="2">2º Semestre</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold small">Ano Letivo Base</label>
                                        <input type="text" id="merito_ano_letivo" class="form-control shadow-sm"
                                            value="<?= date('Y') . '/' . (date('Y') + 1) ?>" required>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="submit" class="btn btn-warning w-100 fw-bold shadow-sm"
                                            id="btnProcurarElegiveis">
                                            <ion-icon name="search-outline"></ion-icon> Procurar Elegíveis
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Etapa 2: Resultados e Emissão -->
                            <div id="resultado_merito_container" class="mt-4 pt-4 border-top" style="display:none;">
                                <h6 class="fw-bold text-dark mb-3">Selecione os Alunos a Premiar</h6>
                                <form action="<?= URL_ROOT ?>/admin/emitirCertificadosMerito" method="POST"
                                    id="formMeritoEmissao" onsubmit="return confirmarEmissaoMerito(this);">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="semestre" id="hdn_semestre">
                                    <input type="hidden" name="ano_letivo" id="hdn_ano_letivo">

                                    <div class="table-responsive mb-4">
                                        <table class="table table-sm table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width:50px;" class="text-center">#</th>
                                                    <th>Nome do Aluno</th>
                                                    <th>Nível/Turma</th>
                                                    <th>Média (0-20)</th>
                                                    <th style="width:80px;">Posição</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbody_alunos_merito">
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row g-3 align-items-center">
                                        <div class="col-md-7">
                                            <label class="form-label fw-bold small mt-2">Tipo de Comunicado
                                                Institucional</label>
                                            <select name="tipo_comunicado" class="form-select shadow-sm border-0">
                                                <option value="Global">Emitir para toda a comunidade (Notificar Todos)
                                                </option>
                                                <option value="Privado">Emitir apenas para o aluno em questão (Discreto)
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-md-5 d-flex align-items-end">
                                            <button type="submit"
                                                class="btn btn-success fw-bold w-100 mt-4 shadow-sm py-2">
                                                <ion-icon name="flash" class="me-1"></ion-icon> Emitir Selecionados
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 border-bottom border-light">
                            <h6 class="fw-bold mb-0">Histórico de Certificados Emitidos</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle"
                                    id="tabelaCertificadosEmitidos">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Ano Letivo</th>
                                            <th>Semestre</th>
                                            <th>Aluno Premiado</th>
                                            <th>Posição</th>
                                            <th>Média Final</th>
                                            <th>Status/Assinaturas</th>
                                            <th class="text-end">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Carregado via AJAX -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aprovação de Contas Pendentes -->
                <div class="tab-pane fade" id="pane-pendentes" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="fw-bold mb-0 text-dark">Aprovação de Contas</h2>
                            <p class="text-muted small">Validar novos registos de alunos realizados via login público.
                            </p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <?php if (empty($data['pendentes'])): ?>
                                <div class="text-center py-5 text-muted">
                                    <ion-icon name="checkmark-done-circle-outline" style="font-size: 4rem;"
                                        class="opacity-25"></ion-icon>
                                    <h5 class="mt-3">Nenhum registo pendente</h5>
                                    <p class="small">Todos os novos cadastros já foram processados.</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle datatable-simple">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nome Completo</th>
                                                <th>E-mail</th>
                                                <th>Data de Registo</th>
                                                <th class="text-end">Acções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data['pendentes'] as $u): ?>
                                                <tr>
                                                    <td class="fw-bold text-dark"><?= htmlspecialchars($u['nome_completo']) ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($u['email']) ?></td>
                                                    <td><?= date('d/m/Y H:i', strtotime($u['data_criacao'])) ?></td>
                                                    <td class="text-end">
                                                        <form action="<?= URL_ROOT ?>/admin/approveAccount/<?= $u['id'] ?>"
                                                            method="POST" class="d-inline">
                                                            <input type="hidden" name="csrf_token"
                                                                value="<?= $_SESSION['csrf_token'] ?>">
                                                            <button type="submit" class="btn btn-sm btn-success fw-bold px-3">
                                                                <ion-icon name="checkmark-outline" class="me-1"></ion-icon>
                                                                Aprovar
                                                            </button>
                                                        </form>
                                                        <a href="<?= URL_ROOT ?>/admin/deleteUser/<?= $u['id'] ?>"
                                                            class="btn btn-sm btn-outline-danger ms-1"
                                                            onclick="return confirm('Rejeitar e excluir este registo?')">
                                                            <ion-icon name="trash-outline"></ion-icon>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Calendário Escolar -->
                <div class="tab-pane fade" id="pane-calendario" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="fw-bold mb-0">Calendário Escolar</h2>
                            <p class="text-muted">Gerencie feriados, exames e eventos globais.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary shadow-sm fw-bold border-0"
                                style="background: linear-gradient(135deg, #10B981, #059669);" data-bs-toggle="modal"
                                onclick="clearEventoForm()" data-bs-target="#eventoModal">
                                <ion-icon name="add-circle-outline"></ion-icon> Novo Evento
                            </button>
                        </div>
                    </div>



                    <div class="row g-4">
                        <div class="col-lg-12">
                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-header bg-white py-3 border-bottom border-light">
                                    <h6 class="fw-bold mb-0">Lista de Eventos Registados</h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0" id="table-eventos">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Data</th>
                                                    <th>Título</th>
                                                    <th>Tipo</th>
                                                    <th>Alcance</th>
                                                    <th>Criado Por</th>
                                                    <th class="text-end">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody id="eventosList">
                                                <!-- AJAX will populate this -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mt-4">
                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-header bg-white py-3 border-bottom border-light">
                                    <h6 class="fw-bold mb-0">Calendário Interativo</h6>
                                </div>
                                <div class="card-body p-4">
                                    <div id="calendar-admin" style="min-height: 600px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 p-4 bg-white shadow-sm rounded-4 border-start border-4 border-primary">
                        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
                            <ion-icon name="color-palette-outline" class="text-primary"></ion-icon>
                            Legenda Oficial do Ano Letivo
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-3 d-flex align-items-center gap-2">
                                <div style="width:18px; height:18px; background:#f59e0b; border-radius:4px;"></div>
                                <span class="small fw-bold text-dark">Início/Fim & Férias</span>
                            </div>
                            <div class="col-md-3 d-flex align-items-center gap-2">
                                <div style="width:18px; height:18px; background:#ef4444; border-radius:4px;"></div>
                                <span class="small fw-bold text-dark">Exames Semestrais</span>
                            </div>
                            <div class="col-md-3 d-flex align-items-center gap-2">
                                <div style="width:18px; height:18px; background:#1e3a8a; border-radius:4px;"></div>
                                <span class="small fw-bold text-dark">Feriados Nacionais</span>
                            </div>
                            <div class="col-md-3 d-flex align-items-center gap-2">
                                <div style="width:18px; height:18px; background:#60a5fa; border-radius:4px;"></div>
                                <span class="small fw-bold text-dark">Prova de Recurso</span>
                            </div>
                            <div class="col-md-3 d-flex align-items-center gap-2">
                                <div style="width:18px; height:18px; background:#14532d; border-radius:4px;"></div>
                                <span class="small fw-bold text-dark">Semana Transitória</span>
                            </div>
                            <div class="col-md-3 d-flex align-items-center gap-2">
                                <div style="width:18px; height:18px; background:#4ade80; border-radius:4px;"></div>
                                <span class="small fw-bold text-dark">Palestras AAESHS</span>
                            </div>
                            <div class="col-md-3 d-flex align-items-center gap-2">
                                <div style="width:18px; height:18px; background:#78350f; border-radius:4px;"></div>
                                <span class="small fw-bold text-dark">Futebol / Excursão</span>
                            </div>
                            <div class="col-md-3 d-flex align-items-center gap-2">
                                <div style="width:18px; height:18px; background:#6b7280; border-radius:4px;"></div>
                                <span class="small fw-bold text-dark">Assembleia Geral</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dashboard Home (Chart.js) -->
                <div class="tab-pane fade show active" id="pane-home">
                    <!-- Cards -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0 bg-white p-3 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-success bg-opacity-10 text-success p-3 rounded-3 me-3">
                                        <ion-icon name="people" class="fs-1"></ion-icon>
                                    </div>
                                    <div>
                                        <p class="text-muted small fw-bold text-uppercase mb-0">Alunos Ativos</p>
                                        <h2 class="fw-bold mb-0"><?= $data['stats']['alunos_ativos'] ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0 bg-white p-3 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-primary bg-opacity-10 text-primary p-3 rounded-3 me-3">
                                        <ion-icon name="calendar" class="fs-1"></ion-icon>
                                    </div>
                                    <div>
                                        <p class="text-muted small fw-bold text-uppercase mb-0">Turmas Ativas</p>
                                        <h2 class="fw-bold mb-0"><?= $data['stats']['turmas_abertas'] ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0 bg-white p-3 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-warning bg-opacity-10 text-warning p-3 rounded-3 me-3">
                                        <ion-icon name="document-text" class="fs-1"></ion-icon>
                                    </div>
                                    <div>
                                        <p class="text-muted small fw-bold text-uppercase mb-0">Matrículas Pendentes</p>
                                        <h2 class="fw-bold mb-0"><?= $data['stats']['matriculas_pendentes'] ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Novos Indicadores Financeiros -->
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 bg-white p-3 mb-3 border-start border-success border-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-success text-white p-3 rounded-3 me-3 shadow-sm">
                                        <ion-icon name="cash" class="fs-1"></ion-icon>
                                    </div>
                                    <div>
                                        <p class="text-muted small fw-bold text-uppercase mb-0">Receita do Mês (XOF)</p>
                                        <h2 class="fw-bold mb-0 text-success">
                                            <?= number_format($data['stats']['pagamentos_mes'], 0, ',', '.') ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0 bg-white p-3 mb-3 border-start border-danger border-4">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-danger text-white p-3 rounded-3 me-3 shadow-sm">
                                        <ion-icon name="alert-circle" class="fs-1"></ion-icon>
                                    </div>
                                    <div>
                                        <p class="text-muted small fw-bold text-uppercase mb-0">Inadimplência (Alunos)
                                        </p>
                                        <h2 class="fw-bold mb-0 text-danger"><?= $data['stats']['inadimplencia'] ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── SECÇÃO DE NOTIFICAÇÕES DE SISTEMA (GHS Workflow) ── -->
                    <div class="row g-4 mb-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="card-header bg-white py-3 border-bottom border-light">
                                    <h6 class="fw-bold mb-0 d-flex align-items-center gap-2 text-dark">
                                        <ion-icon name="notifications-circle-outline"
                                            class="text-primary fs-4"></ion-icon>
                                        Alertas de Validação e Atividade da Secretaria
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush"
                                        style="max-height: 250px; overflow-y: auto;">
                                        <?php if (empty($data['mensagens_painel'])): ?>
                                            <!-- Estado vazio quando não há alertas -->
                                            <div class="p-4 text-center text-muted">
                                                <p class="small mb-0">Nenhum alerta pendente no momento.</p>
                                            </div>
                                        <?php else: ?>
                                            <?php foreach ($data['mensagens_painel'] as $msg): ?>
                                                <!-- Item de notificação individual -->
                                                <div
                                                    class="list-group-item list-group-item-action border-0 px-4 py-3 d-flex align-items-start gap-3">
                                                    <div class="p-2 bg-primary bg-opacity-10 rounded-circle text-primary">
                                                        <ion-icon name="information-circle"></ion-icon>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                                            <span
                                                                class="fw-bold small text-dark"><?= htmlspecialchars($msg['assunto']) ?></span>
                                                            <span class="text-muted" style="font-size: 0.7rem;">
                                                                <?= date('d/m/Y H:i', strtotime($msg['data_criacao'])) ?>
                                                            </span>
                                                        </div>
                                                        <div class="text-muted small" style="line-height: 1.4;">
                                                            <?= htmlspecialchars($msg['mensagem']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div
                                    class="card-footer bg-light py-2 d-flex justify-content-between align-items-center px-4">
                                    <form action="<?= URL_ROOT ?>/admin/clearNotifications" method="POST"
                                        class="d-inline">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <button type="submit"
                                            class="btn btn-sm btn-link text-decoration-none small text-muted p-0">Limpar
                                            lidas</button>
                                    </form>
                                    <a href="javascript:void(0)"
                                        onclick="document.getElementById('tab-notificacoes').click()"
                                        class="text-decoration-none small fw-bold text-primary">Ver histórico
                                        completo</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ── SECÇÃO DE CONFLITOS DE NOTAS (Anti-Fraude) ── -->
                    <div class="row g-4 mb-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden border-start border-4 border-danger">
                                <div class="card-header bg-white py-3 border-bottom border-light">
                                    <h6 class="fw-bold mb-0 d-flex align-items-center gap-2 text-danger">
                                        <ion-icon name="shield-checkmark-outline" class="fs-4"></ion-icon>
                                        Monitorização Anti-Fraude: Reclamações de Notas Críticas
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Estudante</th>
                                                    <th>Disciplina / Turma</th>
                                                    <th>Professor</th>
                                                    <th>Nº Reclamações</th>
                                                    <th>Última Resposta Prof.</th>
                                                    <th class="text-end">Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($data['conflitos_notas'])): ?>
                                                    <tr>
                                                        <td colspan="6" class="text-center py-4 text-muted">
                                                            <ion-icon name="checkmark-done-circle-outline" class="fs-2 opacity-25 d-block mx-auto mb-2"></ion-icon>
                                                            Nenhuma irregularidade detetada. Todas as notas estão em conformidade.
                                                        </td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($data['conflitos_notas'] as $c): ?>
                                                        <tr>
                                                            <td class="fw-bold"><?= htmlspecialchars($c['estudante_nome']) ?></td>
                                                            <td>
                                                                <div class="small fw-semibold"><?= htmlspecialchars($c['disciplina_nome']) ?></div>
                                                                <div class="text-muted small"><?= htmlspecialchars($c['turma_codigo']) ?></div>
                                                            </td>
                                                            <td class="small"><?= htmlspecialchars($c['professor_nome']) ?></td>
                                                            <td>
                                                                <span class="badge bg-danger rounded-pill px-3"><?= $c['contador_reclamacoes'] ?></span>
                                                            </td>
                                                            <td class="small italic text-muted">
                                                                "<?= htmlspecialchars(substr($c['resposta_professor'] ?? 'Nenhuma', 0, 50)) ?>..."
                                                            </td>
                                                            <td class="text-end">
                                                                <button type="button" class="btn btn-sm btn-danger shadow-sm" onclick="convocarComMotivo(<?= $c['estudante_id'] ?>, <?= $c['disciplina_id'] ?>)">
                                                                    <ion-icon name="megaphone-outline" class="me-1"></ion-icon> Convocar Partes
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
                        </div>
                    </div>

                    <!-- ── TABELA DE AUDITORIA DE ACESSOS (Monitorização Inteligente) ── -->
                    <div class="row g-4 mb-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden border-start border-4 border-info">
                                <div class="card-header bg-white py-3 border-bottom border-light">
                                    <h6 class="fw-bold mb-0 d-flex align-items-center gap-2 text-info">
                                        <ion-icon name="eye-outline" class="fs-4"></ion-icon>
                                        Log de Acessos Recentes (Totalmente Silencioso)
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0 datatable-simple">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Utilizador</th>
                                                    <th>Papel</th>
                                                    <th>IP de Origem</th>
                                                    <th>Navegador / Sistema</th>
                                                    <th>Data/Hora do Acesso</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($data['logs_acesso'])): ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center py-4 text-muted small">Sem registos de acesso recentes.</td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($data['logs_acesso'] as $log): ?>
                                                        <tr>
                                                            <td class="fw-bold text-dark"><?= htmlspecialchars($log['nome_completo']) ?></td>
                                                            <td><span class="badge bg-light text-dark text-uppercase" style="font-size: 0.65rem;"><?= $log['tipo'] ?></span></td>
                                                            <td class="small fw-bold text-secondary text-nowrap"><?= $log['ip_address'] ?></td>
                                                            <td class="small text-muted" title="<?= htmlspecialchars($log['user_agent']) ?>">
                                                                <?= substr(htmlspecialchars($log['user_agent']), 0, 40) ?>...
                                                            </td>
                                                            <td>
                                                                <span class="small fw-bold border-start border-3 border-info ps-2">
                                                                    <?= date('d/m/Y H:i:s', strtotime($log['data_acesso'])) ?>
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
                        </div>
                    </div>



                    <!-- 🏆 QUADRO DE MÉRITO (Ranking Global) -->
                    <div class="row g-4 mb-4">
                        <div class="col-12">
                            <?php
                            $ranking_escola = $data['ranking_escola'] ?? [];
                            $ranking_nivel = $data['ranking_nivel'] ?? [];
                            $show_details = true; // No Admin, mostramos todos os detalhes
                            include __DIR__ . '/../partials/merit_board.php';
                            ?>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="row g-4 d-flex align-items-stretch">
                        <div class="col-md-6 h-100">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-4">Estatística: Alunos por Ano Letivo</h5>
                                    <canvas id="barChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 h-100">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-4">Estatística: Distribuição por Turno</h5>
                                    <div style="max-width: 300px; margin: 0 auto;">
                                        <canvas id="pieChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Histórico Completo de Notificações de Sistema -->
                <div class="tab-pane fade" id="pane-notificacoes" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="fw-bold mb-0 text-dark">Histórico de Alertas de Sistema</h2>
                            <p class="text-muted small">Registo completo de comunicações automáticas entre Administração
                                e Secretaria.</p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <?php if (empty($data['mensagens_historico'])): ?>
                                <div class="text-center py-5 text-muted">
                                    <ion-icon name="mail-open-outline" style="font-size: 4rem; opacity: 0.2;"></ion-icon>
                                    <h5 class="mt-3">Sem alertas no histórico</h5>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle datatable-simple">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Data/Hora</th>
                                                <th>Assunto</th>
                                                <th>Mensagem</th>
                                                <th>Remetente</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data['mensagens_historico'] as $msg): ?>
                                                <tr class="<?= $msg['lida'] ? 'opacity-75' : 'bg-primary bg-opacity-10' ?>">
                                                    <td class="small text-muted" style="white-space: nowrap;">
                                                        <ion-icon name="time-outline" class="me-1"></ion-icon>
                                                        <?= date('d/m/Y H:i', strtotime($msg['data_criacao'])) ?>
                                                    </td>
                                                    <td><span
                                                            class="badge bg-primary bg-opacity-10 text-primary fw-bold"><?= htmlspecialchars($msg['assunto']) ?></span>
                                                    </td>
                                                    <td class="small text-dark"><?= htmlspecialchars($msg['mensagem']) ?></td>
                                                    <td class="small fw-bold">
                                                        <?= htmlspecialchars($msg['remetente_nome'] ?? 'Sistema') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div> <!-- End of pane-notificacoes -->

                <!-- Formulários DataTables CRUD Estudantes -->
                <div class="tab-pane fade" id="pane-alunos">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div></div>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#studentModal"
                                    onclick="clearStudentForm()"><ion-icon name="add-outline"></ion-icon> Novo Aluno
                                    Interno</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle datatable-simple">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Nº Proc.</th>
                                            <th>Nome Completo</th>
                                            <th>BI</th>
                                            <th>Data Nasc.</th>
                                            <th>Telefone</th>
                                            <th>Nível</th>
                                            <th>Turma/Ano</th>
                                            <th>Estado</th>
                                            <th class="text-end">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($data['estudantes'])): ?>
                                            <?php foreach ($data['estudantes'] as $e): ?>
                                                <tr>
                                                    <td class="fw-bold"><?= $e['id'] ?></td>
                                                    <td><?= htmlspecialchars($e['nome_completo']) ?></td>
                                                    <td><?= htmlspecialchars($e['bi']) ?></td>
                                                    <td><?= date('d/m/Y', strtotime($e['data_nascimento'])) ?></td>
                                                    <td><?= htmlspecialchars($e['telefone']) ?></td>
                                                    <td><?= htmlspecialchars($e['nivel'] ?? 'N/A') ?></td>
                                                    <td><?= htmlspecialchars(($e['turma'] ?? 'Externo') . ' / ' . ($e['nivel'] ?? 'Pendente')) ?>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-<?= $e['user_status'] === 'ativo' ? 'success' : 'warning' ?>">
                                                            <?= ucfirst($e['user_status'] ?? 'Pendente') ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="dropdown d-inline-block">
                                                            <button class="btn btn-sm btn-light border dropdown-toggle"
                                                                type="button" data-bs-toggle="dropdown">
                                                                Ações
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                                <li><a class="dropdown-item"
                                                                        href="javascript:viewStudent(<?= $e['utilizador_id'] ?>)"><ion-icon
                                                                            name="eye-outline"
                                                                            class="me-2 text-primary"></ion-icon> Ver Perfil</a>
                                                                </li>
                                                                <li><a class="dropdown-item btn-edit-student" href="#"
                                                                        data-id="<?= $e['utilizador_id'] ?>"
                                                                        data-nome="<?= htmlspecialchars($e['nome_completo']) ?>"
                                                                        data-email="<?= htmlspecialchars($e['email'] ?? '') ?>"
                                                                        data-bi="<?= htmlspecialchars($e['bi']) ?>"
                                                                        data-nascimento="<?= $e['data_nascimento'] ?>"
                                                                        data-telefone="<?= htmlspecialchars($e['telefone']) ?>"
                                                                        data-telefone_alt="<?= htmlspecialchars($e['telefone_alternativo'] ?? '') ?>"
                                                                        data-estado_civil="<?= $e['estado_civil'] ?? 'Solteiro/a' ?>"
                                                                        data-cidade="<?= htmlspecialchars($e['cidade'] ?? '') ?>"
                                                                        data-bairro="<?= htmlspecialchars($e['bairro'] ?? '') ?>"
                                                                        data-morada="<?= htmlspecialchars($e['morada'] ?? '') ?>"
                                                                        data-escola="<?= htmlspecialchars($e['escola'] ?? '') ?>"
                                                                        data-ano_conclusao="<?= $e['ano_conclusao'] ?? '' ?>"
                                                                        data-media="<?= $e['media'] ?? '' ?>"
                                                                        data-encarregado_nome="<?= htmlspecialchars($e['encarregado_nome'] ?? '') ?>"
                                                                        data-encarregado_telefone="<?= htmlspecialchars($e['encarregado_telefone'] ?? '') ?>"
                                                                        data-sexo="<?= $e['sexo'] ?>" data-bs-toggle="modal"
                                                                        data-bs-target="#studentModal"><ion-icon
                                                                            name="create-outline" class="me-2"></ion-icon>
                                                                        Editar</a></li>

                                                                <?php if ($e['user_status'] === 'ativo'): ?>
                                                                    <li><a class="dropdown-item text-warning"
                                                                            href="<?= URL_ROOT ?>/admin/toggleStudentStatus/<?= $e['utilizador_id'] ?>/suspenso"><ion-icon
                                                                                name="pause-circle-outline" class="me-2"></ion-icon>
                                                                            Suspender</a></li>
                                                                <?php else: ?>
                                                                    <li><a class="dropdown-item text-success"
                                                                            href="<?= URL_ROOT ?>/admin/toggleStudentStatus/<?= $e['utilizador_id'] ?>/ativo"><ion-icon
                                                                                name="play-circle-outline" class="me-2"></ion-icon>
                                                                            Ativar</a></li>
                                                                <?php endif; ?>
                                                                <li><a class="dropdown-item text-success fw-bold" href="#"
                                                                        onclick="prepareAlocacao(<?= $e['id'] ?>, '<?= htmlspecialchars($e['nome_completo']) ?>')"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#alocarAlunoModal"><ion-icon
                                                                            name="school-outline" class="me-2"></ion-icon>
                                                                        Alocar em Turma</a></li>
                                                                <li><a class="dropdown-item text-info"
                                                                        href="<?= URL_ROOT ?>/admin/resetStudentPassword/<?= $e['utilizador_id'] ?>"
                                                                        onclick="return confirm('Resetar senha para 123456 ou aleatória?')"><ion-icon
                                                                            name="key-outline" class="me-2"></ion-icon> Resetar
                                                                        Senha</a></li>
                                                                <li>
                                                                    <hr class="dropdown-divider">
                                                                </li>
                                                                <li><a class="dropdown-item text-danger"
                                                                        href="javascript:confirmDeleteStudent(<?= $e['utilizador_id'] ?>)"><ion-icon
                                                                            name="trash-outline" class="me-2"></ion-icon>
                                                                        Excluir</a></li>
                                                            </ul>
                                                        </div>
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

                <!-- Gestão de Professores -->
                <div class="tab-pane fade" id="pane-professores">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 class="fw-bold mb-0 text-dark">Corpo Docente</h4>
                                    <p class="text-muted small">Gerencie professores e suas atribuições pedagógicas.</p>
                                </div>
                                <button
                                    class="btn btn-primary px-4 py-2 fw-bold shadow-sm border-0 d-flex align-items-center gap-2"
                                    style="background: linear-gradient(135deg, #3b82f6, #2563eb);"
                                    data-bs-toggle="modal" data-bs-target="#professorModal"
                                    onclick="clearProfessorForm()">
                                    <ion-icon name="person-add-outline"></ion-icon> Novo Professor
                                </button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle datatable-simple">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nome Completo / Email</th>
                                            <th>Especialidade</th>
                                            <th>Grau</th>
                                            <th>AtribuiçõesAtuais</th>
                                            <th>Estado</th>
                                            <th class="text-end">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($data['professores'])): ?>
                                            <?php foreach ($data['professores'] as $p): ?>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-dark">
                                                            <?= htmlspecialchars($p['nome_completo']) ?></div>
                                                        <div class="small text-muted"><?= htmlspecialchars($p['email']) ?></div>
                                                    </td>
                                                    <td><span
                                                            class="badge bg-light text-primary"><?= htmlspecialchars($p['especialidade']) ?></span>
                                                    </td>
                                                    <td><span
                                                            class="small fw-semibold"><?= htmlspecialchars($p['grau_academico'] ?? 'N/A') ?></span>
                                                    </td>
                                                    <td>
                                                        <?php if (!empty($p['atribuicoes_info'])): ?>
                                                            <div class="d-flex flex-wrap gap-1">
                                                                <?php $ats = explode(' | ', $p['atribuicoes_info']); ?>
                                                                <?php foreach ($ats as $at): ?>
                                                                    <span
                                                                        class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25"
                                                                        style="font-size: 0.65rem;">
                                                                        <ion-icon name="bookmark-outline"
                                                                            class="me-1"></ion-icon><?= htmlspecialchars($at) ?>
                                                                    </span>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <span class="text-muted small italic">Sem turmas</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge rounded-pill bg-<?= $p['user_status'] === 'ativo' ? 'success' : 'danger' ?> bg-opacity-10 text-<?= $p['user_status'] === 'ativo' ? 'success' : 'danger' ?>">
                                                            <?= ucfirst($p['user_status']) ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <button class="btn btn-sm btn-light border shadow-sm"
                                                            onclick="editProfessor(<?= $p['id'] ?>)" title="Editar Professor">
                                                            <ion-icon name="create-outline" class="text-primary"></ion-icon>
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
                </div>



                <!-- Matrículas & Document Viewer -->
                <div class="tab-pane fade" id="pane-matriculas">
                    <div class="row">
                        <div class="col-md-10 mx-auto">
                            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                                <div class="card-header bg-white py-4 border-0 d-flex justify-content-between align-items-center">
                                    <h4 class="fw-bold mb-0">Fila de Triagem (Matrículas Pendentes)</h4>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                            <?= count($data['matriculas'] ?? []) ?> Processos Pendentes
                                        </span>
                                        <button class="btn btn-success btn-sm fw-bold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#matriculaManualModal">
                                            <ion-icon name="person-add-outline" class="me-1"></ion-icon> Nova Matrícula
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="ps-4 py-3">Estudante</th>
                                                    <th class="py-3">Ano / Curso</th>
                                                    <th class="py-3 text-center">Tipo</th>
                                                    <th class="py-3 text-center">Documentos</th>
                                                    <th class="text-end pe-4 py-3">Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($data['matriculas'])): ?>
                                                    <tr><td colspan="5" class="p-5 text-center text-muted">Sem matrículas pendentes.</td></tr>
                                                <?php else: ?>
                                                    <?php foreach ($data['matriculas'] as $m): ?>
                                                        <tr>
                                                            <td class="ps-4">
                                                                <div class="fw-bold text-dark"><?= htmlspecialchars($m['nome']) ?></div>
                                                                <small class="text-muted">Proc: #<?= $m['id'] ?></small>
                                                            </td>
                                                            <td>
                                                                <div class="small fw-medium"><?= htmlspecialchars($m['ano_letivo']) ?></div>
                                                                <div class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($m['ano_nome'] ?? 'Ano n/d') ?></div>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge bg-<?= ($m['tipo'] == 'Renovação') ? 'info' : 'secondary px-2' ?> small" style="font-size: 0.7rem;">
                                                                    <?= htmlspecialchars($m['tipo'] ?? 'Novo') ?>
                                                                </span>
                                                            </td>
                                                            <td class="text-center">
                                                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 btn-view-docs"
                                                                    data-bs-toggle="modal" data-bs-target="#documentViewerModal"
                                                                    data-id="<?= $m['id'] ?>" data-nome="<?= htmlspecialchars($m['nome']) ?>"
                                                                    data-bi="<?= $m['bi_arquivo'] ?? '' ?>"
                                                                    data-foto="<?= $m['foto_arquivo'] ?? '' ?>"
                                                                    data-cert="<?= $m['certificado_arquivo'] ?? '' ?>"
                                                                    data-comp="<?= $m['comprovativo_arquivo'] ?? '' ?>">
                                                                    <ion-icon name="documents-outline" class="me-1"></ion-icon> Ficheiros
                                                                </button>
                                                            </td>
                                                            <td class="text-end pe-4">
                                                                <div class="btn-group btn-group-sm">
                                                                    <button class="btn btn-success fw-bold btn-approve-matricula" data-id="<?= $m['id'] ?>">Aprovar</button>
                                                                    <button class="btn btn-danger fw-bold btn-reject-matricula" data-id="<?= $m['id'] ?>">Rejeitar</button>
                                                                </div>
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
                    </div>
                </div>

                <!-- Turmas & Especializações -->
                <div class="tab-pane fade" id="pane-turmas">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card shadow-sm border-0">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between mb-3">
                                        <h4 class="fw-bold">Gestão de Turmas Formadas</h4><button
                                            class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#turmaModal">+ Nova Turma</button>
                                    </div>
                                    <table class="table table-hover datatable-simple">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Cód. Turma</th>
                                                <th>Ano</th>
                                                <th>Turno</th>
                                                <th>Vagas</th>
                                                <th class="text-end">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($data['turmas'])): ?>
                                                <?php foreach ($data['turmas'] as $t): ?>
                                                    <tr>
                                                        <td class="fw-bold"><?= htmlspecialchars($t['codigo']) ?></td>
                                                        <td><?= htmlspecialchars($t['ano_nome']) ?></td>
                                                        <td><?= htmlspecialchars($t['turno']) ?></td>
                                                        <td><?= $t['vagas'] ?></td>
                                                        <td class="text-end">
                                                            <div class="btn-group">
                                                                <button class="btn btn-sm btn-light"
                                                                    onclick="viewTurma(<?= $t['id'] ?>)"
                                                                    title="Visualizar Detalhes"><ion-icon
                                                                        name="eye-outline"></ion-icon></button>
                                                                <button class="btn btn-sm btn-info text-white"
                                                                    onclick="viewTurmaStudents(<?= $t['id'] ?>, '<?= $t['codigo'] ?>')"
                                                                    title="Ver Alunos"><ion-icon
                                                                        name="people-outline"></ion-icon></button>
                                                                <button class="btn btn-sm btn-primary"
                                                                    onclick="showHorario(<?= $t['id'] ?>, '<?= htmlspecialchars($t['codigo']) ?>')"
                                                                    title="Gerir Horário"><ion-icon
                                                                        name="time-outline"></ion-icon></button>
                                                                <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                                    data-bs-target="#alocarEstudanteModal"
                                                                    onclick="prepareAllocation(<?= $t['id'] ?>, '<?= htmlspecialchars($t['codigo']) ?>')"
                                                                    title="Alocar Aluno"><ion-icon
                                                                        name="person-add-outline"></ion-icon></button>
                                                                <button class="btn btn-sm btn-outline-danger"
                                                                    onclick="if(confirm('Tem certeza que deseja excluir esta turma?')) window.location.href='<?= URL_ROOT ?>/admin/deleteTurma/<?= $t['id'] ?>'"
                                                                    title="Excluir"><ion-icon
                                                                        name="trash-outline"></ion-icon></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-body p-4">
                                    <h4 class="fw-bold mb-4">Gestão Escolar Avançada</h4>
                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" data-bs-toggle="pill"
                                                data-bs-target="#pills-anos" type="button">Anos Curriculares</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" data-bs-toggle="pill"
                                                data-bs-target="#pills-disciplinas" type="button">Disciplinas
                                                (Curriculo)</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" data-bs-toggle="pill"
                                                data-bs-target="#pills-especialidades" type="button">Especialidades (5º
                                                Ano)</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" data-bs-toggle="pill"
                                                data-bs-target="#pills-professores" type="button">Professores</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content border p-3 rounded bg-light bg-opacity-50">
                                        <!-- Aba Anos -->
                                        <div class="tab-pane fade show active" id="pills-anos">
                                            <div class="d-flex justify-content-between mb-3">
                                                <h5>Configuração de Ciclos e Mensalidades</h5><button
                                                    class="btn btn-sm btn-dark" data-bs-toggle="modal"
                                                    data-bs-target="#anoModal" onclick="clearAnoForm()">+ Novo
                                                    Ano</button>
                                            </div>
                                            <table class="table table-sm bg-white shadow-sm rounded">
                                                <thead>
                                                    <tr>
                                                        <th>Ordem</th>
                                                        <th>Nome</th>
                                                        <th>Mensalidade</th>
                                                        <th class="text-end">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($data['anos'] as $ano): ?>
                                                        <tr>
                                                            <td><?= $ano['ordem'] ?></td>
                                                            <td class="fw-bold"><?= htmlspecialchars($ano['nome']) ?></td>
                                                            <td><?= number_format($ano['mensalidade'], 0, ',', '.') ?> XOF
                                                            </td>
                                                            <td class="text-end">
                                                                <button class="btn btn-sm btn-info text-white"
                                                                    onclick="showModelo(<?= $ano['id'] ?>, '<?= htmlspecialchars($ano['nome']) ?>')"><ion-icon
                                                                        name="calendar-outline"></ion-icon> Modelo</button>
                                                                <button class="btn btn-sm btn-outline-primary btn-edit-ano"
                                                                    data-id="<?= $ano['id'] ?>"
                                                                    data-numero="<?= $ano['numero'] ?>"
                                                                    data-nome="<?= htmlspecialchars($ano['nome']) ?>"
                                                                    data-desc="<?= htmlspecialchars($ano['descricao']) ?>"
                                                                    data-valor="<?= $ano['mensalidade'] ?>"
                                                                    data-ordem="<?= $ano['ordem'] ?>"><ion-icon
                                                                        name="create-outline"></ion-icon></button>
                                                                <button class="btn btn-sm btn-outline-danger"
                                                                    onclick="confirmDeleteAno(<?= $ano['id'] ?>)"><ion-icon
                                                                        name="trash-outline"></ion-icon></button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- Aba Disciplinas -->
                                        <div class="tab-pane fade" id="pills-disciplinas">
                                            <div class="d-flex justify-content-between mb-3">
                                                <h5>Curriculo e Carga Horária</h5><button class="btn btn-sm btn-dark"
                                                    data-bs-toggle="modal" data-bs-target="#disciplinaModal"
                                                    onclick="clearDisciplinaForm()">+ Nova Disciplina</button>
                                            </div>
                                            <table class="table table-sm bg-white shadow-sm rounded datatable-simple">
                                                <thead>
                                                    <tr>
                                                        <th>Cód</th>
                                                        <th>Nome</th>
                                                        <th>Ano</th>
                                                        <th>Carga H.</th>
                                                        <th class="text-end">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($data['disciplinas'] as $d): ?>
                                                        <tr>
                                                            <td class="small text-muted">
                                                                <?= htmlspecialchars($d['codigo']) ?></td>
                                                            <td class="fw-bold"><?= htmlspecialchars($d['nome']) ?></td>
                                                            <td><?= htmlspecialchars($d['ano_nome']) ?></td>
                                                            <td><?= $d['carga_horaria'] ?>h</td>
                                                            <td class="text-end">
                                                                <button
                                                                    class="btn btn-sm btn-outline-primary btn-edit-disciplina"
                                                                    data-id="<?= $d['id'] ?>"
                                                                    data-codigo="<?= htmlspecialchars($d['codigo']) ?>"
                                                                    data-nome="<?= htmlspecialchars($d['nome']) ?>"
                                                                    data-ano="<?= $d['ano_id'] ?>"
                                                                    data-carga="<?= $d['carga_horaria'] ?>"
                                                                    data-credito="<?= $d['credito'] ?>"
                                                                    data-desc="<?= htmlspecialchars($d['descricao']) ?>"><ion-icon
                                                                        name="create-outline"></ion-icon></button>
                                                                <button class="btn btn-sm btn-outline-danger"
                                                                    onclick="confirmDeleteDisciplina(<?= $d['id'] ?>)"><ion-icon
                                                                        name="trash-outline"></ion-icon></button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- Aba Especialidades -->
                                        <div class="tab-pane fade" id="pills-especialidades">
                                            <div class="d-flex justify-content-between mb-3">
                                                <h5>Áreas de Especialização</h5><button class="btn btn-sm btn-dark"
                                                    data-bs-toggle="modal" data-bs-target="#especialidadeModal"
                                                    onclick="clearEspForm()">+ Nova Especialidade</button>
                                            </div>
                                            <table class="table table-sm bg-white shadow-sm rounded">
                                                <thead>
                                                    <tr>
                                                        <th>Cód</th>
                                                        <th>Nome</th>
                                                        <th>Vagas</th>
                                                        <th class="text-end">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($data['especialidades'] as $es): ?>
                                                        <tr>
                                                            <td class="text-muted"><?= htmlspecialchars($es['codigo']) ?>
                                                            </td>
                                                            <td class="fw-bold text-primary">
                                                                <?= htmlspecialchars($es['nome']) ?></td>
                                                            <td><?= $es['vagas'] ?></td>
                                                            <td class="text-end">
                                                                <button class="btn btn-sm btn-outline-primary btn-edit-esp"
                                                                    data-id="<?= $es['id'] ?>"
                                                                    data-codigo="<?= htmlspecialchars($es['codigo']) ?>"
                                                                    data-nome="<?= htmlspecialchars($es['nome']) ?>"
                                                                    data-desc="<?= htmlspecialchars($es['descricao']) ?>"
                                                                    data-vagas="<?= $es['vagas'] ?>"
                                                                    data-ativa="<?= $es['ativa'] ?>"><ion-icon
                                                                        name="create-outline"></ion-icon></button>
                                                                <button class="btn btn-sm btn-outline-danger"
                                                                    onclick="confirmDeleteEsp(<?= $es['id'] ?>)"><ion-icon
                                                                        name="trash-outline"></ion-icon></button>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <!-- Aba Professores -->
                                        <div class="tab-pane fade" id="pills-professores">
                                            <div class="d-flex justify-content-between mb-3">
                                                <h5>Corpo Docente</h5><button class="btn btn-sm btn-dark"
                                                    data-bs-toggle="modal" data-bs-target="#professorModal">+ Cadastrar
                                                    Professor</button>
                                            </div>
                                            <table class="table table-sm bg-white shadow-sm rounded datatable-simple">
                                                <thead>
                                                    <tr>
                                                        <th>Nome</th>
                                                        <th>Especialidade</th>
                                                        <th>Email</th>
                                                        <th class="text-end">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($data['professores'] as $p): ?>
                                                        <tr>
                                                            <td class="fw-bold"><?= htmlspecialchars($p['nome_completo']) ?>
                                                            </td>
                                                            <td><?= htmlspecialchars($p['especialidade']) ?></td>
                                                            <td class="small text-muted">
                                                                <?= htmlspecialchars($p['email']) ?></td>
                                                            <td class="text-end">
                                                                <div class="btn-group">
                                                                    <button class="btn btn-sm btn-light"
                                                                        onclick="viewProfessor(<?= $p['id'] ?>)"
                                                                        title="Visualizar"><ion-icon
                                                                            name="eye-outline"></ion-icon></button>
                                                                    <button class="btn btn-sm btn-outline-primary"
                                                                        onclick="editProfessor(<?= $p['id'] ?>)"
                                                                        title="Editar"><ion-icon
                                                                            name="create-outline"></ion-icon></button>
                                                                    <button class="btn btn-sm btn-outline-danger"
                                                                        onclick="confirmDeleteProfessor(<?= $p['id'] ?>)"
                                                                        title="Eliminar"><ion-icon
                                                                            name="trash-outline"></ion-icon></button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tesouraria -->
                <div class="tab-pane fade" id="pane-financeiro">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 class="fw-bold mb-0">Gestão de Tesouraria</h4>
                                    <?php
                                    $pendentes = array_filter($data['pagamentos'], fn($p) => $p['status'] === 'Pendente');
                                    if (count($pendentes) > 0):
                                        ?>
                                        <span class="badge bg-warning text-dark mt-1">
                                            <ion-icon name="time-outline"></ion-icon>
                                            <?= count($pendentes) ?> pagamento(s) aguardam validação
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="<?= URL_ROOT ?>/admin/exportFinanceiro"
                                        class="btn btn-outline-dark"><ion-icon name="download-outline"></ion-icon>
                                        Exportar Excel</a>
                                    <button class="btn btn-success fw-bold" data-bs-toggle="modal"
                                        data-bs-target="#pagamentoModal"><ion-icon name="add-circle-outline"></ion-icon>
                                        Registar Pagamento</button>
                                </div>
                            </div>

                            <?php if (count($pendentes) > 0): ?>
                                <!-- Bloco de Aprovação Rápida -->
                                <div class="alert alert-warning border-0 rounded-3 mb-4">
                                    <h6 class="fw-bold mb-2"><ion-icon name="alert-circle-outline"></ion-icon> Comprovativos
                                        Pendentes de Validação</h6>
                                    <div class="row g-2">
                                        <?php foreach ($pendentes as $pp): ?>
                                            <div class="col-md-6">
                                                <div class="card border-warning border-opacity-50 shadow-sm">
                                                    <div class="card-body py-2 px-3">
                                                        <div class="d-flex justify-content-between align-items-start">
                                                            <div>
                                                                <strong
                                                                    class="d-block"><?= htmlspecialchars($pp['estudante_nome']) ?></strong>
                                                                <small
                                                                    class="text-muted"><?= htmlspecialchars($pp['descricao']) ?>
                                                                    — <?= number_format($pp['valor'], 0, ',', '.') ?>
                                                                    XOF</small>
                                                                <br><small class="text-muted">Submetido:
                                                                    <?= date('d/m/Y H:i', strtotime($pp['data_criacao'])) ?></small>
                                                            </div>
                                                            <div class="d-flex flex-column gap-1 ms-2">
                                                                <?php if (!empty($pp['comprovativo_arquivo'])): ?>
                                                                    <a href="<?= URL_ROOT ?>/<?= $pp['comprovativo_arquivo'] ?>"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-primary py-0 px-2"
                                                                        title="Ver Comprovativo">
                                                                        <ion-icon name="eye-outline"></ion-icon> Ver
                                                                    </a>
                                                                <?php endif; ?>
                                                                <a href="<?= URL_ROOT ?>/admin/validarPagamento/<?= $pp['id'] ?>"
                                                                    class="btn btn-sm btn-success py-0 px-2 fw-bold"
                                                                    title="Aprovar">
                                                                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                                                                    Aprovar
                                                                </a>
                                                                <a href="<?= URL_ROOT ?>/admin/rejeitarPagamento/<?= $pp['id'] ?>"
                                                                    class="btn btn-sm btn-outline-danger py-0 px-2"
                                                                    onclick="return confirm('Rejeitar este pagamento?')">
                                                                    <ion-icon name="close-circle-outline"></ion-icon> Rejeitar
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="table-responsive">
                                <table class="table table-hover datatable-simple">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Data</th>
                                            <th>Estudante</th>
                                            <th>Descrição</th>
                                            <th>Valor</th>
                                            <th>Forma</th>
                                            <th>Status</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($data['pagamentos'] as $p): ?>
                                            <tr class="<?= $p['status'] === 'Pendente' ? 'table-warning' : '' ?>">
                                                <td><?= date('d/m/Y', strtotime($p['data_pagamento'] ?? $p['data_criacao'])) ?>
                                                </td>
                                                <td class="fw-bold"><?= htmlspecialchars($p['estudante_nome']) ?></td>
                                                <td><?= htmlspecialchars($p['descricao']) ?></td>
                                                <td class="text-primary fw-bold">
                                                    <?= number_format($p['valor'], 0, ',', '.') ?> XOF</td>
                                                <td>
                                                    <?php if (!empty($p['forma_pagamento'])): ?>
                                                        <?= htmlspecialchars($p['forma_pagamento']) ?>
                                                    <?php elseif (!empty($p['metodo_pagamento'])): ?>
                                                        <?= htmlspecialchars($p['metodo_pagamento']) ?>
                                                    <?php else: ?>
                                                        <span
                                                            class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25">Via
                                                            Portal Aluno</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-<?= $p['status'] === 'Pago' ? 'success' : ($p['status'] === 'Rejeitado' ? 'danger' : 'warning text-dark') ?>">
                                                        <?= $p['status'] ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-1">
                                                        <?php if (!empty($p['comprovativo_arquivo'])): ?>
                                                            <a href="<?= URL_ROOT ?>/<?= $p['comprovativo_arquivo'] ?>"
                                                                target="_blank" class="btn btn-xs btn-outline-primary py-0 px-1"
                                                                title="Ver Comprovativo"><ion-icon
                                                                    name="document-outline"></ion-icon></a>
                                                        <?php endif; ?>
                                                        <?php if ($p['status'] === 'Pendente'): ?>
                                                            <a href="<?= URL_ROOT ?>/admin/validarPagamento/<?= $p['id'] ?>"
                                                                class="btn btn-xs btn-success py-0 px-1"
                                                                title="Validar"><ion-icon
                                                                    name="checkmark-circle-outline"></ion-icon></a>
                                                            <a href="<?= URL_ROOT ?>/admin/rejeitarPagamento/<?= $p['id'] ?>"
                                                                class="btn btn-xs btn-outline-danger py-0 px-1" title="Rejeitar"
                                                                onclick="return confirm('Rejeitar este pagamento?')"><ion-icon
                                                                    name="close-circle-outline"></ion-icon></a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Acompanhamento Pedagógico -->
                <div class="tab-pane fade" id="pane-pedagogico">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h4 class="fw-bold mb-4">Gestão e Acompanhamento Pedagógico</h4>

                            <ul class="nav nav-tabs mb-4" id="pedagogico-tabs" role="tablist">
                                <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#sub-sumarios">Sumários Diários</button></li>
                                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#sub-monitoramento-sumarios"><span
                                            class="badge bg-danger p-1 me-1"><?= count($data['atrasos_sumarios']) ?></span>
                                        Monitoramento de Sumários</button></li>
                                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#sub-frequencias">Controlo de Assiduidade</button></li>
                                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#sub-assiduidade-professores">Assiduidade de
                                        Professores</button></li>
                                <li class="nav-item"><button class="nav-link" data-bs-toggle="tab"
                                        data-bs-target="#sub-medias">Relatório de Médias Gerais</button></li>
                            </ul>

                            <div class="tab-content">
                                <!-- Sumários -->
                                <div class="tab-pane fade show active" id="sub-sumarios">
                                    <div class="table-responsive">
                                        <table class="table table-hover datatable-simple">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Data / Tempo</th>
                                                    <th>Professor</th>
                                                    <th>Turma / Disc</th>
                                                    <th>Sumário</th>
                                                    <th class="text-center">Presenças</th>
                                                    <th>Assiduidade Prof.</th>
                                                    <th>Estado Admin</th>
                                                    <th class="text-end">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data['sumarios'] as $s): ?>
                                                    <tr>
                                                        <td>
                                                            <div class="fw-bold"><?= date('d/m/Y', strtotime($s['data'])) ?>
                                                            </div>
                                                            <span class="badge bg-info bg-opacity-10 text-info small"
                                                                style="font-size: 0.7rem;"><?= htmlspecialchars($s['tempo'] ?? '1º Tempo') ?></span>
                                                        </td>
                                                        <td class="fw-bold"><?= htmlspecialchars($s['professor_nome']) ?>
                                                        </td>
                                                        <td><span
                                                                class="badge bg-light text-dark"><?= $s['turma_codigo'] ?></span><br><small><?= $s['disciplina_nome'] ?></small>
                                                        </td>
                                                        <td class="small"><?= nl2br(htmlspecialchars($s['conteudo'])) ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <span
                                                                class="badge bg-success bg-opacity-10 text-success small"><?= $s['P'] ?>
                                                                P</span>
                                                            <span
                                                                class="badge bg-danger bg-opacity-10 text-danger small"><?= $s['F'] ?>
                                                                F</span>
                                                        </td>
                                                        <td>
                                                            <?php if (!empty($s['status_professor'])): ?>
                                                                <?php if ($s['status_professor'] === 'Presença'): ?>
                                                                    <span class="badge bg-success"><ion-icon
                                                                            name="checkmark-circle"></ion-icon> Presente</span>
                                                                <?php else: ?>
                                                                    <span class="badge bg-danger"><ion-icon
                                                                            name="close-circle"></ion-icon> Falta</span>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <form action="<?= URL_ROOT ?>/admin/markTeacherAttendance"
                                                                    method="POST" class="d-inline">
                                                                    <input type="hidden" name="csrf_token"
                                                                        value="<?= $_SESSION['csrf_token'] ?>">




                                                                    <input type="hidden" name="professor_id"
                                                                        value="<?= $s['professor_id'] ?>">
                                                                    <input type="hidden" name="turma_id"
                                                                        value="<?= $s['turma_id'] ?>">
                                                                    <input type="hidden" name="disciplina_id"
                                                                        value="<?= $s['disciplina_id'] ?>">
                                                                    <input type="hidden" name="tempo"
                                                                        value="<?= $s['tempo'] ?>">
                                                                    <div class="input-group input-group-sm mb-1"
                                                                        style="max-width: 150px;">
                                                                        <input type="text" name="justificacao"
                                                                            class="form-control" placeholder="Justificativa..."
                                                                            style="font-size: 0.7rem;">
                                                                    </div>
                                                                    <div class="btn-group btn-group-xs shadow-sm">
                                                                        <button type="submit" name="status" value="Presença"
                                                                            class="btn btn-outline-success border-0 px-1 py-0"
                                                                            title="Marcar Presença"><ion-icon
                                                                                name="checkmark-circle"></ion-icon> P</button>
                                                                        <button type="submit" name="status" value="Falta"
                                                                            class="btn btn-outline-danger border-0 px-1 py-0"
                                                                            title="Marcar Falta"><ion-icon
                                                                                name="close-circle"></ion-icon> F</button>
                                                                    </div>
                                                                </form>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($s['confirmado_admin'] ?? false): ?>
                                                                <span class="badge bg-success"><ion-icon
                                                                        name="checkmark-done-circle"></ion-icon> Recebido</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-warning text-dark">Pendente</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-end">
                                                            <?php if (!($s['confirmado_admin'] ?? false)): ?>
                                                                <a href="<?= URL_ROOT ?>/admin/confirmSummary/<?= $s['id'] ?>"
                                                                    class="btn btn-sm btn-success fw-bold">Confirmar</a>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Monitoramento de Sumários (Atrasos) -->
                                <div class="tab-pane fade" id="sub-monitoramento-sumarios">
                                    <div class="alert alert-info border-0 shadow-sm d-flex align-items-center mb-4">
                                        <ion-icon name="information-circle-outline" class="fs-4 me-2"></ion-icon>
                                        <div>Aulas do dia de hoje que terminaram há mais de 1 hora e ainda não possuem
                                            sumário registado.</div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-hover datatable-simple">
                                            <thead class="bg-danger bg-opacity-10 text-danger">
                                                <tr>
                                                    <th>Horário</th>
                                                    <th>Professor</th>
                                                    <th>Turma / Disciplina</th>
                                                    <th>Sala</th>
                                                    <th class="text-center">Ação</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($data['atrasos_sumarios'])): ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center py-4 text-muted">Nenhum atraso
                                                            detetado no momento. Todos os sumários estão em dia!</td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($data['atrasos_sumarios'] as $at): ?>
                                                        <tr>
                                                            <td><span class="fw-bold"><?= $at['hora_inicio'] ?> -
                                                                    <?= $at['hora_fim'] ?></span></td>
                                                            <td>
                                                                <div class="fw-bold">
                                                                    <?= htmlspecialchars($at['professor_nome']) ?></div>
                                                            </td>
                                                            <td><span
                                                                    class="badge bg-light text-dark"><?= $at['turma_codigo'] ?></span><br><small><?= $at['disciplina_nome'] ?></small>
                                                            </td>
                                                            <td><?= htmlspecialchars($at['sala']) ?></td>
                                                            <td class="text-center">
                                                                <?php if (!empty($at['status_professor'])): ?>
                                                                    <?php if ($at['status_professor'] === 'Presença'): ?>
                                                                        <span class="badge bg-success">Presente</span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-danger">Falta</span>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <form action="<?= URL_ROOT ?>/admin/markTeacherAttendance"
                                                                        method="POST" class="d-block gap-1 justify-content-center">
                                                                        <input type="hidden" name="csrf_token"
                                                                            value="<?= $_SESSION['csrf_token'] ?>">





                                                                        <input type="hidden" name="professor_id"
                                                                            value="<?= $at['professor_id'] ?>">
                                                                        <input type="hidden" name="turma_id"
                                                                            value="<?= $at['turma_id'] ?>">
                                                                        <input type="hidden" name="disciplina_id"
                                                                            value="<?= $at['disciplina_id'] ?>">
                                                                        <input type="hidden" name="tempo"
                                                                            value="<?= $at['hora_inicio'] ?> - <?= $at['hora_fim'] ?>">
                                                                        <input type="text" name="justificacao"
                                                                            class="form-control form-control-sm mb-1"
                                                                            placeholder="Motivo..." style="font-size: 0.7rem;">
                                                                        <div class="d-flex gap-1 justify-content-center">
                                                                            <button type="submit" name="status" value="Presença"
                                                                                class="btn btn-success btn-sm fw-bold">P</button>
                                                                            <button type="submit" name="status" value="Falta"
                                                                                class="btn btn-danger btn-sm fw-bold">F</button>
                                                                        </div>
                                                                    </form>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Frequências -->
                                <div class="tab-pane fade" id="sub-frequencias">
                                    <div class="table-responsive">
                                        <table class="table table-hover datatable-simple">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Data</th>
                                                    <th>Estudante</th>
                                                    <th>Grupo</th>
                                                    <th>Disciplina / Tempo</th>
                                                    <th>Professor</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-end">Ação Admin</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data['detailed_attendance'] as $da): ?>
                                                    <tr>
                                                        <td><?= date('d/m/Y', strtotime($da['data'])) ?></td>
                                                        <td class="fw-bold"><?= htmlspecialchars($da['estudante_nome']) ?>
                                                        </td>
                                                        <td><span class="badge bg-light text-dark border fw-bold"><?= htmlspecialchars($da['grupo'] ?? 'G1') ?></span></td>
                                                        <td>
                                                            <div class="small fw-bold text-dark">
                                                                <?= htmlspecialchars($da['disciplina_nome']) ?></div>
                                                            <span class="badge bg-info bg-opacity-10 text-info"
                                                                style="font-size: 0.65rem;"><?= htmlspecialchars($da['tempo'] ?? '1º Tempo') ?></span>
                                                        </td>
                                                        <td class="small text-muted">
                                                            <?= htmlspecialchars($da['professor_nome']) ?></td>
                                                        <td class="text-center">
                                                            <?php if ($da['status'] === 'P'): ?>
                                                                <span
                                                                    class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">Presente</span>
                                                            <?php elseif ($da['status'] === 'F'): ?>
                                                                <span
                                                                    class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2">Falta</span>
                                                            <?php else: ?>
                                                                <span
                                                                    class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2">Justificada</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-end">
                                                            <?php if ($da['confirmado_admin']): ?>
                                                                <span class="badge bg-success">Confirmado</span>
                                                            <?php else: ?>
                                                                <a href="<?= URL_ROOT ?>/admin/confirmAttendance?turma_id=<?= $da['turma_id'] ?>&disciplina_id=<?= $da['disciplina_id'] ?>"
                                                                    class="btn btn-xs btn-outline-success py-0"
                                                                    style="font-size: 0.65rem;">Confirmar Lote</a>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Assiduidade de Professores (Histórico) -->
                                <div class="tab-pane fade" id="sub-assiduidade-professores">
                                    <div class="table-responsive">
                                        <table class="table table-hover datatable-simple">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Data</th>
                                                    <th>Professor</th>
                                                    <th>Turma / Disciplina</th>
                                                    <th class="text-center">Status</th>
                                                    <th>Justificativa</th>
                                                    <th>Marcado Por</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data['teacher_attendance_report'] as $tar): ?>
                                                    <tr>
                                                        <td><?= date('d/m/Y', strtotime($tar['data'])) ?></td>
                                                        <td class="fw-bold"><?= htmlspecialchars($tar['professor_nome']) ?>
                                                        </td>
                                                        <td><span
                                                                class="badge bg-light text-dark"><?= $tar['turma_codigo'] ?></span>
                                                            <?= htmlspecialchars($tar['disciplina_nome']) ?></td>
                                                        <td class="text-center">
                                                            <?php if ($tar['status'] === 'Presença'): ?>
                                                                <span class="badge bg-success">Presença</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-danger">Falta</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="small italic text-muted">
                                                            <?= htmlspecialchars($tar['justificacao'] ?? '-') ?></td>
                                                        <td class="small text-muted">
                                                            <?= htmlspecialchars($tar['marcado_por_nome']) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Médias Gerais -->
                                <div class="tab-pane fade" id="sub-medias">
                                    <div class="table-responsive">
                                        <table class="table table-hover datatable-simple">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Estudante</th>
                                                    <th>Turma / Disciplina</th>
                                                    <th class="text-center">Total AC</th>
                                                    <th class="text-center">Exame</th>
                                                    <th class="text-center fw-bold">Média Final</th>
                                                    <th class="text-center">Ações Admin</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($data['notas_report'] as $n): ?>
                                                    <tr>
                                                        <td class="fw-bold"><?= htmlspecialchars($n['estudante']) ?></td>
                                                        <td><span class="badge bg-secondary"><?= $n['turma'] ?></span>
                                                            <?= $n['disciplina'] ?></td>
                                                        <td class="text-center"><?= number_format($n['total_ac'], 1) ?></td>
                                                        <td class="text-center">
                                                            <?= $n['notas'][5] !== null ? number_format($n['notas'][5], 1) : '-' ?>
                                                        </td>
                                                        <td class="text-center fw-bold text-primary">
                                                            <?= $n['media_final'] !== null ? number_format($n['media_final'], 1) : '-' ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php if ($n['confirmado_admin'] ?? false): ?>
                                                                <span class="badge bg-success">Confirmado</span>
                                                            <?php else: ?>
                                                                <a href="<?= URL_ROOT ?>/admin/confirmGrades?turma_id=<?= $n['turma_id'] ?>&disciplina_id=<?= $n['disciplina_id'] ?? 0 ?>"
                                                                    class="btn btn-sm btn-outline-success py-0"
                                                                    style="font-size: 0.7rem;">Confirmar Lote</a>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comunicados -->
                <div class="tab-pane fade" id="pane-comunicados">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="fw-bold mb-0">Painel de Comunicados</h4>
                                <button class="btn btn-primary fw-bold" data-bs-toggle="modal"
                                    data-bs-target="#comunicadoModal"><ion-icon name="megaphone-outline"></ion-icon>
                                    Novo Comunicado</button>
                            </div>
                            <div class="row g-4">
                                <?php if (empty($data['comunicados'])): ?>
                                    <div class="col-12 text-center py-5">
                                        <ion-icon name="mail-outline" style="font-size: 4rem; opacity: 0.2;"></ion-icon>
                                        <p class="text-muted mt-3">Nenhum comunicado enviado.</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($data['comunicados'] as $c): ?>
                                        <div class="col-md-6">
                                            <div class="card border-0 bg-light shadow-sm h-100">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span class="badge bg-primary"><?= ucfirst($c['alvo']) ?></span>
                                                        <small
                                                            class="text-muted"><?= date('d/m/Y H:i', strtotime($c['data_publicacao'])) ?></small>
                                                    </div>
                                                    <h5 class="fw-bold"><?= htmlspecialchars($c['titulo']) ?></h5>
                                                    <p class="text-muted small mb-3">
                                                        <?= nl2br(htmlspecialchars(substr($c['conteudo'], 0, 150))) ?>...</p>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-secondary">Por:
                                                            <?= htmlspecialchars($c['autor_nome'] ?? 'Admin') ?></small>
                                                        <button class="btn btn-sm btn-link text-decoration-none"
                                                            onclick="showStats(<?= $c['id'] ?>, '<?= htmlspecialchars(addslashes($c['titulo'])) ?>')">Ver
                                                            Estatísticas</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Equipa da Secretaria -->
                <div class="tab-pane fade" id="pane-secretaria">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 class="fw-bold mb-0">Gestão da Equipa da Secretaria</h4>
                                    <p class="text-muted small">Registe e gira os membros do secretariado com acesso
                                        administrativo.</p>
                                </div>
                                <button class="btn btn-primary fw-bold" data-bs-toggle="modal"
                                    data-bs-target="#secretariaModal">
                                    <ion-icon name="person-add-outline"></ion-icon> Novo Secretário/a
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="border-0 small fw-bold text-muted text-uppercase">Nome Completo
                                            </th>
                                            <th class="border-0 small fw-bold text-muted text-uppercase">Email</th>
                                            <th class="border-0 small fw-bold text-muted text-uppercase">B.I.</th>
                                            <th class="border-0 small fw-bold text-muted text-uppercase">Telefone</th>
                                            <th class="border-0 small fw-bold text-muted text-uppercase">Contratado em
                                            </th>
                                            <th class="border-0 small fw-bold text-muted text-uppercase">Status</th>
                                            <th class="border-0 small fw-bold text-muted text-uppercase text-end">Ações
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($data['secretarios'])): ?>
                                            <tr>
                                                <td colspan="5" class="text-center py-4 text-muted">Nenhum secretário
                                                    registado.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($data['secretarios'] as $s): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-soft-primary text-primary rounded-circle p-2 me-2">
                                                                <ion-icon name="person"></ion-icon>
                                                            </div>
                                                            <span
                                                                class="fw-bold text-dark"><?= htmlspecialchars($s['nome_completo']) ?></span>
                                                        </div>
                                                    </td>
                                                    <td><?= htmlspecialchars($s['email']) ?></td>
                                                    <td><span
                                                            class="small fw-bold"><?= htmlspecialchars($s['bi'] ?? 'N/A') ?></span>
                                                    </td>
                                                    <td><?= htmlspecialchars($s['telefone'] ?? 'N/A') ?></td>
                                                    <td><small
                                                            class="text-muted"><?= !empty($s['data_contratacao']) ? date('d/m/Y', strtotime($s['data_contratacao'])) : 'N/A' ?></small>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge bg-<?= $s['user_status'] == 'ativo' ? 'success' : 'warning' ?>">
                                                            <?= ucfirst($s['user_status']) ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm btn-light text-danger"
                                                                onclick="confirmDeleteSecretaria(<?= $s['id'] ?>)">
                                                                <ion-icon name="trash-outline"></ion-icon>
                                                            </button>
                                                        </div>
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

                <!-- Painel de Auditoria Independente -->
                <div class="tab-pane fade" id="pane-auditoria" role="tabpanel">
                     <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2 class="fw-bold mb-0 text-dark">Logs de Auditoria de Acesso</h2>
                            <p class="text-muted small">Monitorização em tempo real de todas as sessões iniciadas na plataforma.</p>
                        </div>
                    </div>
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle datatable-simple" id="table-logs-auditoria">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nome do Utilizador</th>
                                            <th>Perfil</th>
                                            <th>Endereço IP</th>
                                            <th>Data e Hora</th>
                                            <th>Dispositivo (User Agent)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($data['logs_acesso'])): ?>
                                            <tr><td colspan="5" class="text-center py-5">Nenhum log disponível.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($data['logs_acesso'] as $log): ?>
                                                <tr>
                                                    <td class="fw-bold"><?= htmlspecialchars($log['nome_completo']) ?></td>
                                                    <td><span class="badge bg-secondary"><?= strtoupper($log['tipo']) ?></span></td>
                                                    <td><code><?= $log['ip_address'] ?></code></td>
                                                    <td class="fw-bold text-primary"><?= date('d/m/Y H:i:s', strtotime($log['data_acesso'])) ?></td>
                                                    <td class="small text-muted text-wrap" style="max-width: 300px;"><?= htmlspecialchars($log['user_agent']) ?></td>
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

    <!-- === SECCAO DE MODAIS CONSOLIDADA (PARTE 1) === -->

    <!-- Modal Agendar Evento Central -->
    <div class="modal fade" id="eventoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= URL_ROOT ?>/admin/saveEvento" method="POST" id="eventForm"
                class="modal-content border-0 shadow-lg">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="eventoModalTitle">Registar Novo Evento Escolar</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="evento_id">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-bold">Título do Evento</label>
                            <input type="text" name="titulo" id="evento_titulo" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Data e Hora</label>
                            <input type="datetime-local" name="data_evento" id="evento_data" class="form-control"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Tipo</label>
                            <select name="tipo" id="evento_tipo" class="form-select">
                                <option value="Feriado">Feriado Nacional</option>
                                <option value="Exame">Exames Semestrais</option>
                                <option value="Evento">Evento / Convívio</option>
                                <option value="Reunião">Reunião</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Alcance</label>
                            <select name="destinatario_tipo" id="evento_dest_tipo" class="form-select"
                                onchange="toggleEventoDest(this.value)">
                                <option value="Global">Todos</option>
                                <option value="Professores">Professores</option>
                                <option value="Alunos">Alunos</option>
                                <option value="Ano">Ano Curricular</option>
                                <option value="Turma">Turma</option>
                            </select>
                        </div>
                        <div class="col-12 d-none" id="evento_dest_id_wrapper">
                            <select name="destinatario_id" id="evento_dest_id" class="form-select"></select>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Cor</label>
                            <select name="cor" id="evento_cor" class="form-select">
                                <option value="#f59e0b">Laranja</option>
                                <option value="#ef4444">Vermelho</option>
                                <option value="#1e3a8a">Azul</option>
                                <option value="#10b981">Verde</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold">Agendar Evento</button>
                </div>
            </form>
        </div>
    </div>



    <!-- Modal Alocar Aluno -->
    <div class="modal fade" id="alocarAlunoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= URL_ROOT ?>/admin/alocarAluno" method="POST" class="modal-content border-0 shadow-lg">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold">Alocar Aluno em Turma</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="estudante_id" id="aloc_estudante_id">
                    <div class="alert alert-info py-2 small mb-3">Estudante: <strong id="aloc_estudante_nome"></strong>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold small">Turma</label>
                            <select name="turma_id" class="form-select" required>
                                <option value="">Selecionar...</option>
                                <?php foreach ($data['turmas'] as $t): ?>
                                    <option value="<?= $t['id'] ?>"><?= $t['codigo'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success fw-bold">Confirmar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Fim da Parte 1 -->


    <!-- === SECCAO DE MODAIS CONSOLIDADA (PARTE 2) === -->

    <!-- Modal Especialidade -->
    <div class="modal fade" id="especialidadeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= URL_ROOT ?>/admin/saveEspecialidade" method="POST"
                class="modal-content border-0 shadow-lg">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="id" id="esp_id">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" id="espModalTitle">Nova Especialidade</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Código</label>
                        <input type="text" name="codigo" id="esp_codigo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nome da Especialidade</label>
                        <input type="text" name="nome" id="esp_nome" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Vagas Totais</label>
                        <input type="number" name="vagas" id="esp_vagas" class="form-control" value="30">
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-modal="dismiss">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold">Salvar Especialidade</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Rejeitar Matrícula -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= URL_ROOT ?>/admin/rejectMatricula" method="POST" class="modal-content border-0 shadow-lg">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="id" id="reject_matricula_id">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold">Rejeitar Matrícula</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-danger fw-bold">Deseja rejeitar esta matrícula?</p>
                    <textarea name="motivo" class="form-control" rows="3" placeholder="Motivo da rejeição..."
                        required></textarea>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-danger fw-bold">Confirmar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Professor -->
    <div class="modal fade" id="professorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="<?= URL_ROOT ?>/admin/createProfessor" id="profForm" method="POST"
                class="modal-content border-0 shadow-lg">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="id" id="prof_id">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="profModalTitle">Gerir Professor</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Nome Completo</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <h6 class="fw-bold mt-4">Atribuições</h6>
                    <div id="atribuicoes-container" class="border rounded p-2 bg-light"></div>
                    <button type="button" class="btn btn-sm btn-link mt-2" onclick="addAtribuicaoRow()">+ Adicionar
                        Atribuição</button>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark fw-bold">Salvar Professor</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Pagamento -->
    <div class="modal fade" id="pagamentoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= URL_ROOT ?>/admin/savePagamento" method="POST" class="modal-content border-0 shadow-lg">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold">Registar Pagamento</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Aluno</label>
                        <select name="estudante_id" class="form-select" required>
                            <option value="">Selecionar aluno...</option>
                            <?php if (!empty($data['todos_alunos'])):
                                foreach ($data['todos_alunos'] as $al): ?>
                                    <option value="<?= $al['id'] ?>"><?= htmlspecialchars($al['nome_completo']) ?></option>
                                <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Valor (XOF)</label>
                        <input type="number" name="valor" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Data</label>
                            <input type="date" name="data_pagamento" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold">Método</label>
                            <select name="metodo_pagamento" class="form-select">
                                <option value="Numerário">Numerário</option>
                                <option value="Transferência">Transferência</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success fw-bold">Registar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Template Atribuição -->
    <template id="atribuicao-template">
        <div class="atribuicao-row d-flex gap-2 mb-2">
            <select name="turma_id[]" class="form-select form-select-sm" required>
                <option value="">Turma...</option>
                <?php foreach ($data['turmas'] as $t): ?>
                    <option value="<?= $t['id'] ?>"><?= $t['codigo'] ?></option>
                <?php endforeach; ?>
            </select>
            <select name="disciplina_id[]" class="form-select form-select-sm" required>
                <option value="">Disciplina...</option>
                <?php foreach ($data['disciplinas'] as $ds): ?>
                    <option value="<?= $ds['id'] ?>"><?= $ds['nome'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAtribuicaoRow(this)">✕</button>
        </div>
    </template>

    <!-- Fim da Parte 2 -->

    <!-- === SECCAO DE MODAIS CONSOLIDADA (PARTE 3) === -->

    <!-- Modal Visualizar Turma -->
    <div class="modal fade" id="viewTurmaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title fw-bold"><ion-icon name="information-circle-outline"></ion-icon> Detalhes da
                        Turma</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="viewTurmaContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Alunos da Turma -->
    <div class="modal fade" id="turmaStudentsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title fw-bold"><ion-icon name="people"></ion-icon> Alunos: <span
                            id="turmaAlunos_codigo"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="turmaStudentsContent" style="max-height: 500px; overflow-y: auto;">
                    <div class="text-center py-4">
                        <div class="spinner-border text-info"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Visualizar Professor -->
    <div class="modal fade" id="viewProfessorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold"><ion-icon name="person-circle-outline"></ion-icon> Perfil do Docente
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="viewProfessorContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-dark"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Visualizar Estudante -->
    <div class="modal fade" id="viewStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title fw-bold"><ion-icon name="person-circle-half-outline"></ion-icon> Perfil do
                        Estudante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="viewStudentContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Secretaria -->
    <div class="modal fade" id="secretariaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= URL_ROOT ?>/admin/createSecretaria" method="POST"
                class="modal-content border-0 shadow-lg">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold">Novo Membro de Secretaria</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nome Completo</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email de Acesso</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Senha de Acesso</label>
                        <input type="password" name="senha" class="form-control" value="123456" required>
                        <div class="form-text">Padrão: 123456</div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-dark fw-bold">Criar Acesso</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Fim da Parte 3 -->

    <!-- Modal Horário -->
    <div class="modal fade" id="horarioModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">Grade Horária: <span id="horario_turma_codigo"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-4 border-end">
                            <h6 class="fw-bold mb-3 border-bottom pb-2">Novo Slot de Aula</h6>
                            <form action="<?= URL_ROOT ?>/admin/saveHorario" method="POST" class="row g-2">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">





                                <input type="hidden" name="turma_id" id="horario_turma_id">
                                <div class="col-12 mb-2">
                                    <a id="btn_apply_modelo" href="#"
                                        class="btn btn-sm btn-outline-success w-100 fw-bold"><ion-icon
                                            name="copy-outline"></ion-icon> Carregar Modelo do Ano</a>
                                </div>
                                <div class="col-12">
                                    <label class="small fw-bold">Disciplina</label>
                                    <select name="disciplina_id" class="form-select form-select-sm" required>
                                        <?php foreach ($data['disciplinas'] as $d): ?>
                                            <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nome']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="small fw-bold">Professor</label>
                                    <select name="professor_id" class="form-select form-select-sm" required>
                                        <?php foreach ($data['professores'] as $p): ?>
                                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome_completo']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="small fw-bold">Dia</label>
                                    <select name="dia_semana" class="form-select form-select-sm" required>
                                        <option>Segunda</option>
                                        <option>Terça</option>
                                        <option>Quarta</option>
                                        <option>Quinta</option>
                                        <option>Sexta</option>
                                        <option>Sábado</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="small fw-bold">Tempo / Horário</label>
                                    <select id="select_tempo_horario" class="form-select form-select-sm"
                                        onchange="updateTempoValues(this, 'horario_ini', 'horario_fim')" required>
                                        <option value="" disabled selected>Selecionar tempo...</option>
                                        <optgroup label="Manhã / Tarde">
                                            <option value="07:20|08:50">1º Tempo (07:20 – 08:50)</option>
                                            <option value="08:55|10:25">2º Tempo (08:55 – 10:25)</option>
                                            <option value="10:45|12:15">3º Tempo (10:45 – 12:15)</option>
                                            <option value="12:20|13:50">4º Tempo (12:20 – 13:50)</option>
                                        </optgroup>
                                        <optgroup label="Noite">
                                            <option value="17:45|19:15">N1 (17:45 – 19:15)</option>
                                            <option value="19:20|20:50">N2 (19:20 – 20:50)</option>
                                            <option value="21:00|22:30">N3 (21:00 – 22:30)</option>
                                            <option value="22:35|24:00">N4 (22:35 – 24:00)</option>
                                        </optgroup>
                                    </select>
                                    <input type="hidden" name="hora_inicio" id="horario_ini">
                                    <input type="hidden" name="hora_fim" id="horario_fim">
                                </div>
                                <div class="col-12">
                                    <label class="small fw-bold">Sala (Ex: LAB1, Sala 4)</label>
                                    <input type="text" name="sala" class="form-control form-control-sm" required>
                                </div>
                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">Alocar
                                        Slot</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-8">
                            <h6 class="fw-bold mb-3 border-bottom pb-2">Horários Alocados</h6>
                            <div id="horarios_list" class="small overflow-auto" style="max-height: 400px;">
                                <p class="text-center text-muted py-5">Carregando horários...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Alocar Estudante -->
    <div class="modal fade" id="alocarEstudanteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="<?= URL_ROOT ?>/admin/assignStudentToTurma" method="POST"
                class="modal-content border-0 shadow-lg">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">





                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title fw-bold">Alocar Aluno à Turma: <span id="aloca_turma_codigo"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" name="turma_id" id="aloca_turma_id">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Estudantes com Matrícula
                            Aprovada</label>
                        <select name="matricula_id" class="form-select" required size="10">
                            <?php if (empty($data['approved_matriculas'])): ?>
                                <option disabled>Nenhum aluno aprovado aguardando turma.</option>
                            <?php else: ?>
                                <?php foreach ($data['approved_matriculas'] as $am): ?>
                                    <option value="<?= $am['id'] ?>"><?= htmlspecialchars($am['estudante_nome']) ?>
                                        (<?= htmlspecialchars($am['ano_nome']) ?>)</option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success fw-bold text-white">Confirmar Alocação</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Estudante -->
    <div class="modal fade" id="studentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="<?= URL_ROOT ?>/admin/saveStudent" method="POST" class="modal-content border-0 shadow-lg">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <input type="hidden" name="id" id="student_id">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="modalTitle">Novo Aluno Interno</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nome Completo</label>
                            <input type="text" name="nome" id="student_nome" class="form-control" required
                                placeholder="Ex: Dioclécio Fernandes">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Email
                                Institucional</label>
                            <input type="email" name="email" id="student_email" class="form-control" required
                                placeholder="aluno@green.gw">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Palavra-passe (Opcional p/
                                Editar)</label>
                            <input type="password" name="password" id="student_password" class="form-control"
                                placeholder="Mínimo 6 caracteres">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">B.I. / Passaporte</label>
                            <input type="text" name="bi" id="student_bi" class="form-control" required
                                placeholder="000000000GB">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Data de Nascimento</label>
                            <input type="date" name="data_nascimento" id="student_nascimento" class="form-control"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Sexo</label>
                            <select name="sexo" id="student_sexo" class="form-select" required>
                                <option value="Masculino">Masculino</option>
                                <option value="Feminino">Feminino</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Telefone
                                Alternativo</label>
                            <input type="text" name="telefone_alternativo" id="student_telefone_alt"
                                class="form-control" placeholder="+245 ...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Estado Civil</label>
                            <select name="estado_civil" id="student_estado_civil" class="form-select">
                                <option value="Solteiro/a">Solteiro/a</option>
                                <option value="Casado/a">Casado/a</option>
                                <option value="Divorciado/a">Divorciado/a</option>
                                <option value="Viúvo/a">Viúvo/a</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Cidade</label>
                            <input type="text" name="cidade" id="student_cidade" class="form-control"
                                placeholder="Bissau">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Bairro</label>
                            <input type="text" name="bairro" id="student_bairro" class="form-control"
                                placeholder="Bairro Militar">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Morada Específica</label>
                            <input type="text" name="morada" id="student_morada" class="form-control"
                                placeholder="Rua, Casa nº...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Escola de
                                Proveniência</label>
                            <input type="text" name="escola" id="student_escola" class="form-control"
                                placeholder="Liceu Nacional...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Ano Conclusão</label>
                            <input type="number" name="ano_conclusao" id="student_ano_conclusao" class="form-control"
                                value="<?= date('Y') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Média Final</label>
                            <input type="number" step="0.01" name="media" id="student_media" class="form-control"
                                placeholder="14.5">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nome do
                                Encarregado</label>
                            <input type="text" name="encarregado_nome" id="student_encarregado_nome"
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Telefone do
                                Encarregado</label>
                            <input type="text" name="encarregado_telefone" id="student_encarregado_telefone"
                                class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold" id="btnSubmit">Criar Aluno</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function clearStudentForm() {
            $('#student_id').val('');
            $('#student_nome').val('');
            $('#student_email').val('');
            $('#student_password').val('');
            $('#student_bi').val('');
            $('#student_nascimento').val('');
            $('#student_sexo').val('Masculino');
            $('#student_telefone').val('');
            $('#student_telefone_alt').val('');
            $('#student_estado_civil').val('Solteiro');
            $('#student_cidade').val('');
            $('#student_bairro').val('');
            $('#student_morada').val('');
            $('#student_escola').val('');
            $('#student_ano_conclusao').val('<?= date('Y') ?>');
            $('#student_media').val('');
            $('#student_encarregado_nome').val('');
            $('#student_encarregado_telefone').val('');
            $('#modalTitle').text('Novo Aluno Interno');
            $('#btnSubmit').text('Criar Aluno');
        }

        $(document).on('click', '.btn-edit-student', function () {
            const d = $(this).data();
            $('#student_id').val(d.id);
            $('#student_nome').val(d.nome);
            $('#student_email').val(d.email);
            $('#student_bi').val(d.bi);
            $('#student_nascimento').val(d.nascimento);
            $('#student_sexo').val(d.sexo);
            $('#student_telefone').val(d.telefone);
            $('#student_telefone_alt').val(d.telefone_alt);
            $('#student_estado_civil').val(d.estado_civil);
            $('#student_cidade').val(d.cidade);
            $('#student_bairro').val(d.bairro);
            $('#student_morada').val(d.morada);
            $('#student_escola').val(d.escola);
            $('#student_ano_conclusao').val(d.ano_conclusao);
            $('#student_media').val(d.media);
            $('#student_encarregado_nome').val(d.encarregado_nome);
            $('#student_encarregado_telefone').val(d.encarregado_telefone);
            $('#student_password').val('');
            $('#modalTitle').text('Editar Estudante: ' + d.nome);
            $('#btnSubmit').text('Salvar Alterações');
        });

        function confirmDeleteStudent(id) {
            if (confirm('Tem a certeza que deseja excluir permanentemente este estudante?')) {
                window.location.href = '<?= URL_ROOT ?>/admin/deleteStudent/' + id;
            }
        }
    </script>

    <script>
        let currentDocData = null;

        function loadSpecialDoc(type) {
            if (!currentDocData) return;
            const file = currentDocData[type];
            const modalLabel = document.getElementById('viewerModalLabel');

            $('#docSelector .btn').removeClass('btn-light').addClass('btn-outline-light');
            $(`#btn-${type}`).removeClass('btn-outline-light').addClass('btn-light');

            if (!file || file.trim() === '') {
                $('#viewerContent').html('<div class="text-center py-5 text-white opacity-50"><ion-icon name="warning-outline" style="font-size: 5rem;"></ion-icon><h4 class="mt-3">Ficheiro não anexado.</h4></div>');
                modalLabel.textContent = 'Documento Indisponível';
                return;
            }

            // file may be just the filename (e.g. ghs_xxx.pdf) or a full relative path
            const url = file.startsWith('public/') || file.startsWith('/') 
                ? '<?= URL_ROOT ?>/' + file 
                : '<?= URL_ROOT ?>/public/uploads/matriculas/' + file;
            const ext = file.split('.').pop().toLowerCase();
            
            const titles = { 
                'bi': 'Bilhete de Identidade / ID', 
                'foto': 'Fotografia do Estudante',
                'cert': 'Certificado Escolar / Disciplinas', 
                'comp': 'Comprovativo de Depósito / Recibo' 
            };
            modalLabel.textContent = (titles[type] || 'Visualização') + ' — ' + (currentDocData['nome'] || '');

            if (ext === 'pdf') {
                $('#viewerContent').html(`<iframe src="${url}#toolbar=1" style="width:100%; height:80vh; border:none;"></iframe>`);
            } else if (['jpg','jpeg','png','gif','webp'].includes(ext)) {
                $('#viewerContent').html(`<div class="p-4 text-center"><img src="${url}" class="img-fluid rounded shadow-lg" style="max-height: 75vh;" onerror="this.parentElement.innerHTML='<p class=\'text-white text-center mt-5\'>Imagem não encontrada no servidor.</p>'"></div>`);
            } else {
                $('#viewerContent').html(`<div class="text-center py-5 text-white opacity-50"><ion-icon name="document-outline" style="font-size: 5rem;"></ion-icon><h4 class="mt-3">Formato não suportado para pré-visualização.</h4><a href="${url}" target="_blank" class="btn btn-outline-light mt-3">Descarregar Ficheiro</a></div>`);
            }
        }

        $(document).ready(function () {
            // Document Viewer Trigger logic
            const docViewerModal = document.getElementById('documentViewerModal');
            if (docViewerModal) {
                docViewerModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    if (!button) return;
                    currentDocData = $(button).data();
                    // Reset viewer
                    $('#viewerContent').html('<div class="text-center py-5 text-white opacity-50"><span class="spinner-border text-light"></span></div>');
                    // Start on BI, fall back to first available doc
                    const firstAvail = currentDocData['bi'] ? 'bi' : (currentDocData['foto'] ? 'foto' : (currentDocData['cert'] ? 'cert' : 'comp'));
                    loadSpecialDoc(firstAvail);
                });
            }
        });

        $(document).on('click', '.btn-approve-matricula', function () {
            const id = $(this).data('id');
            if (id && confirm('Confirmar aprovação desta matrícula e criação de conta de aluno?')) {
                window.location.href = '<?= URL_ROOT ?>/admin/approveMatricula/' + id;
            }
        });
    </script>


    <!-- Modal Novo Comunicado -->
    <div class="modal fade" id="comunicadoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="<?= URL_ROOT ?>/admin/saveComunicado" method="POST" class="modal-content border-0 shadow-lg">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">Criar Novo Comunicado</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Título do Comunicado</label>
                        <input type="text" name="titulo" class="form-control" required
                            placeholder="Ex: Início das Provas do 1º Semestre">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Público-Alvo</label>
                        <select name="alvo" class="form-select">
                            <option value="todos">Todos os Utilizadores</option>
                            <option value="estudantes">Apenas Estudantes</option>
                            <option value="professores">Apenas Professores</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Conteúdo da Mensagem</label>
                        <textarea name="conteudo" class="form-control" rows="6" required
                            placeholder="Escreva aqui os detalhes do comunicado..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Status</label>
                            <select name="status" class="form-select">
                                <option value="publicado">Publicar Imediatamente</option>
                                <option value="rascunho">Salvar como Rascunho</option>
                                <option value="agendado">Agendar Publicação</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted text-uppercase">Data de Agendamento
                                (Opcional)</label>
                            <input type="datetime-local" name="data_agendamento" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary fw-bold">Enviar Comunicado</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function showStats(id, titulo) {
            document.getElementById('statsTitle').innerText = titulo;
            const tbody = document.getElementById('statsTableBody');
            const emptyMsg = document.getElementById('statsEmptyMessage');
            tbody.innerHTML = '<tr><td colspan="3" class="text-center"><div class="spinner-border spinner-border-sm text-info"></div> A carregar...</td></tr>';
            emptyMsg.classList.add('d-none');

            var modal = new bootstrap.Modal(document.getElementById('statsModal'));
            modal.show();

            fetch('<?= URL_ROOT ?>/admin/getComunicadoStats/' + id)
                .then(response => response.json())
                .then(data => {
                    tbody.innerHTML = '';
                    if (data.length === 0) {
                        emptyMsg.classList.remove('d-none');
                    } else {
                        data.forEach(leitura => {
                            const row = `<tr>
                        <td class="fw-bold">${leitura.nome_completo}</td>
                        <td><span class="badge bg-secondary">${leitura.tipo}</span></td>
                        <td class="small text-muted">${new Date(leitura.data_leitura).toLocaleString()}</td>
                    </tr>`;
                            tbody.innerHTML += row;
                        });
                    }
                })
                .catch(err => {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Erro ao carregar dados.</td></tr>';
                });
        }
    </script>
    <!-- Modal Modelo de Horário por Ano -->
    <div class="modal fade" id="modeloModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title fw-bold">Modelo de Horário Base: <span id="modelo_ano_nome"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-5 border-end">
                            <h6 class="fw-bold mb-3">Adicionar Slot ao Modelo</h6>
                            <form action="<?= URL_ROOT ?>/admin/saveHorarioModelo" method="POST" class="row g-2">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">





                                <input type="hidden" name="ano_id" id="modelo_ano_id">
                                <div class="col-12">
                                    <label class="small fw-bold">Disciplina</label>
                                    <select name="disciplina_id" class="form-select form-select-sm" required>
                                        <option value="">Selecione...</option>
                                        <?php foreach ($data['disciplinas'] as $d): ?>
                                            <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nome']) ?>
                                                (<?= $d['ano_nome'] ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="small fw-bold">Dia da Semana</label>
                                    <select name="dia_semana" class="form-select form-select-sm" required>
                                        <option>Segunda</option>
                                        <option>Terça</option>
                                        <option>Quarta</option>
                                        <option>Quinta</option>
                                        <option>Sexta</option>
                                        <option>Sábado</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="small fw-bold">Tempo / Horário</label>
                                    <select class="form-select form-select-sm"
                                        onchange="updateTempoValues(this, 'modelo_ini', 'modelo_fim')" required>
                                        <option value="" disabled selected>Selecionar tempo...</option>
                                        <optgroup label="Manhã / Tarde">
                                            <option value="07:20|08:50">1º Tempo (07:20 – 08:50)</option>
                                            <option value="08:55|10:25">2º Tempo (08:55 – 10:25)</option>
                                            <option value="10:45|12:15">3º Tempo (10:45 – 12:15)</option>
                                            <option value="12:20|13:50">4º Tempo (12:20 – 13:50)</option>
                                        </optgroup>
                                        <optgroup label="Noite">
                                            <option value="17:45|19:15">N1 (17:45 – 19:15)</option>
                                            <option value="19:20|20:50">N2 (19:20 – 20:50)</option>
                                            <option value="21:00|22:30">N3 (21:00 – 22:30)</option>
                                            <option value="22:35|24:00">N4 (22:35 – 24:00)</option>
                                        </optgroup>
                                    </select>
                                    <input type="hidden" name="hora_inicio" id="modelo_ini">
                                    <input type="hidden" name="hora_fim" id="modelo_fim">
                                </div>
                                <div class="col-12">
                                    <label class="small fw-bold">Sala/Local Padrão</label>
                                    <input type="text" name="sala" class="form-control form-control-sm"
                                        placeholder="Ex: Sala 1, Lab 2">
                                </div>
                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn btn-info text-white w-100 fw-bold shadow-sm">Salvar
                                        no Modelo</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-7">
                            <h6 class="fw-bold mb-3">Slots do Modelo</h6>
                            <div id="modelo_list" class="overflow-auto" style="max-height: 400px;">
                                <p class="text-center text-muted py-5">Carregando...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showModelo(id, nome) {
            $('#modelo_ano_nome').text(nome);
            $('#modelo_ano_id').val(id);
            $('#modelo_list').html('<div class="text-center py-5"><div class="spinner-border text-info"></div><p class="mt-2 small">A carregar slots...</p></div>');

            const modal = new bootstrap.Modal(document.getElementById('modeloModal'));
            modal.show();

            fetch('<?= URL_ROOT ?>/admin/getHorarioModeloAjax/' + id)
                .then(r => r.text())
                .then(html => $('#modelo_list').html(html));
        }

        function deleteModeloSlot(id, anoId) {
            if (confirm('Deseja remover este slot de aula do modelo deste ano?')) {
                fetch('<?= URL_ROOT ?>/admin/deleteHorarioModelo/' + id)
                    .then(() => showModelo(anoId, $('#modelo_ano_nome').text()));
            }
        }

        // Sobrescrever showHorario para incluir o link do modelo dinâmico
        var originalShowHorario = showHorario;
        showHorario = function (id, codigo) {
            originalShowHorario(id, codigo);

            fetch('<?= URL_ROOT ?>/admin/getTurmaInfo/' + id)
                .then(r => r.json())
                .then(turma => {
                    $('#btn_apply_modelo').attr('href', '<?= URL_ROOT ?>/admin/replicateModeloToTurma/' + turma.ano_id + '/' + id);
                    if (turma.has_horario) {
                        $('#btn_apply_modelo').addClass('disabled').text('Horário já Populado');
                    } else {
                        $('#btn_apply_modelo').removeClass('disabled').html('<ion-icon name="copy-outline"></ion-icon> Carregar Modelo do Ano ' + turma.ano_nome);
                    }
                }).catch(err => console.error(err));
        };

        function viewTurma(id) {
            $('#viewTurmaContent').html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
            const modal = new bootstrap.Modal(document.getElementById('viewTurmaModal'));
            modal.show();

            fetch('<?= URL_ROOT ?>/admin/getTurmaInfo/' + id)
                .then(r => r.json())
                .then(t => {
                    let html = `
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold">CÓDIGO DA TURMA</span>
                        <span class="fw-bold">${t.codigo}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold">ANO CURRICULAR</span>
                        <span>${t.ano_nome}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold">TURNO</span>
                        <span class="badge bg-secondary">${t.turno}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold">VAGAS TOTAIS</span>
                        <span>${t.vagas}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span class="text-muted small fw-bold">ESTADO DO HORÁRIO</span>
                        <span class="badge bg-${t.has_horario > 0 ? 'success' : 'warning'}">${t.has_horario > 0 ? 'Definido' : 'Pendente'}</span>
                    </div>
                </div>
            `;
                    $('#viewTurmaContent').html(html);
                });
        }

        function viewTurmaStudents(id, codigo) {
            $('#turmaAlunos_codigo').text(codigo);
            $('#turmaStudentsContent').html('<div class="text-center py-4"><div class="spinner-border text-info"></div></div>');
            const modal = new bootstrap.Modal(document.getElementById('turmaStudentsModal'));
            modal.show();

            fetch('<?= URL_ROOT ?>/admin/getTurmaStudentsAjax/' + id)
                .then(r => r.text())
                .then(html => $('#turmaStudentsContent').html(html));
        }

        function viewProfessor(id) {
            $('#viewProfessorContent').html('<div class="text-center py-4"><div class="spinner-border text-dark"></div></div>');
            const modal = new bootstrap.Modal(document.getElementById('viewProfessorModal'));
            modal.show();

            fetch('<?= URL_ROOT ?>/admin/getProfessorInfo/' + id)
                .then(r => r.json())
                .then(p => {
                    let html = `
                <div class="text-center mb-4">
                    <ion-icon name="person-circle" style="font-size: 5rem;" class="text-secondary"></ion-icon>
                    <h4 class="fw-bold mb-0">${p.nome_completo}</h4>
                    <span class="badge bg-dark">${p.especialidade}</span>
                    <div class="mt-2 text-primary small fw-bold">Turma: ${p.turma_nome || 'Nenhuma'}</div>
                </div>
                <div class="list-group list-group-flush small">
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Email:</span>
                        <span class="fw-bold">${p.email}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Telefone:</span>
                        <span class="fw-bold">${p.telefone || 'N/A'}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">BI / Doc:</span>
                        <span class="fw-bold">${p.bi || 'N/A'}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Grau Académico:</span>
                        <span class="fw-bold">${p.grau_academico || 'N/A'}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Data Contratação:</span>
                        <span class="fw-bold">${p.data_contratacao ? new Date(p.data_contratacao).toLocaleDateString() : 'N/A'}</span>
                    </div>
                </div>
                <div class="mt-4">
                    <h6 class="fw-bold border-bottom pb-2">Ações Rápidas</h6>
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-outline-primary" onclick="editProfessor(${p.id}); bootstrap.Modal.getInstance(document.getElementById('viewProfessorModal')).hide();">Editar Perfil</button>
                    </div>
                </div>
            `;
                    $('#viewProfessorContent').html(html);
                });
        }

        function viewStudent(id) {
            $('#viewStudentContent').html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
            const modal = new bootstrap.Modal(document.getElementById('viewStudentModal'));
            modal.show();

            fetch('<?= URL_ROOT ?>/admin/getStudentDetails/' + id)
                .then(r => r.json())
                .then(s => {
                    let html = `
                <div class="text-center mb-4">
                    <ion-icon name="person-circle" style="font-size: 5rem;" class="text-primary"></ion-icon>
                    <h4 class="fw-bold mb-0">${s.nome_completo}</h4>
                    <span class="badge bg-light text-dark border">${s.turma || 'Sem Turma'}</span>
                </div>
                <div class="list-group list-group-flush small">
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Processo Nº:</span>
                        <span class="fw-bold">#${s.id}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Email:</span>
                        <span class="fw-bold">${s.email}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">BI / Passaporte:</span>
                        <span class="fw-bold">${s.bi}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Nascimento:</span>
                        <span class="fw-bold">${new Date(s.data_nascimento).toLocaleDateString()}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Telefone:</span>
                        <span class="fw-bold">${s.telefone || 'N/A'}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Morada:</span>
                        <span class="fw-bold">${s.cidade || ''}, ${s.bairro || ''} - ${s.morada || 'N/A'}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Estado Civil:</span>
                        <span class="fw-bold">${s.estado_civil || 'Solteiro'}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Tel. Alternativo:</span>
                        <span class="fw-bold">${s.telefone_alternativo || 'N/A'}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Escola Origem:</span>
                        <span class="fw-bold">${s.escola || 'N/A'} (${s.ano_conclusao || ''})</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Média Final:</span>
                        <span class="fw-bold text-success">${s.media || '0'}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Encarregado:</span>
                        <span class="fw-bold">${s.encarregado_nome || 'N/A'} (${s.encarregado_telefone || ''})</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between">
                        <span class="text-muted">Estado da Conta:</span>
                        <span class="badge bg-${s.user_status === 'ativo' ? 'success' : 'warning'}">${s.user_status.toUpperCase()}</span>
                    </div>
                </div>
                <div class="mt-4">
                    <h6 class="fw-bold border-bottom pb-2">Opções de Gestão</h6>
                    <div class="d-grid gap-2">
                        <button class="btn btn-sm btn-outline-primary" onclick="bootstrap.Modal.getInstance(document.getElementById('viewStudentModal')).hide(); $('.btn-edit-student[data-id=\\'${s.utilizador_id}\\']').click();">Editar Informações</button>
                    </div>
                </div>
            `;
                    $('#viewStudentContent').html(html);
                });
        }

        function editProfessor(id) {
            $('#profModalTitle').text('Editar Perfil do Professor');
            $('#btnProfSubmit').text('Salvar Alterações');
            $('#profForm').attr('action', '<?= URL_ROOT ?>/admin/updateProfessor');

            $('#atribuicoes-container').html('<div class="text-center py-2"><div class="spinner-border spinner-border-sm text-primary"></div></div>');

            fetch('<?= URL_ROOT ?>/admin/getProfessorData/' + id)
                .then(r => r.json())
                .then(data => {
                    const p = data.prof;
                    const ats = data.atribuicoes;

                    $('#prof_id').val(p.id);
                    $('#professorModal input[name="nome"]').val(p.nome_completo);
                    $('#professorModal input[name="email"]').val(p.email);
                    $('#professorModal input[name="bi"]').val(p.bi);
                    $('#professorModal input[name="telefone"]').val(p.telefone);
                    $('#prof_grau').val(p.grau_academico || 'Licenciado');
                    $('#prof_contratacao').val(p.data_contratacao || '');
                    $('#professorModal input[name="especialidade"]').val(p.especialidade);
                    $('#professorModal input[name="senha"]').val('').attr('required', false);
                    $('#professorModal .form-text').text('Deixe em branco para manter a senha atual.');

                    // Atribuições
                    $('#atribuicoes-container').empty();
                    if (ats && ats.length > 0) {
                        ats.forEach(at => {
                            addAtribuicaoRow(at.turma_id, at.disciplina_id);
                        });
                    } else {
                        addAtribuicaoRow();
                    }

                    const modal = new bootstrap.Modal(document.getElementById('professorModal'));
                    modal.show();
                });
        }

        function clearProfessorForm() {
            $('#profModalTitle').text('Cadastrar Novo Professor');
            $('#btnProfSubmit').text('Criar Conta');
            $('#profForm').attr('action', '<?= URL_ROOT ?>/admin/createProfessor');
            $('#prof_id').val('');
            $('#professorModal form')[0].reset();
            $('input[name="senha"]').attr('required', true);
            $('.form-text').text('Padrão: 123456');

            $('#atribuicoes-container').empty();
            addAtribuicaoRow();
        }

        function addAtribuicaoRow(turmaId = '', disciplinaId = '') {
            const template = document.getElementById('atribuicao-template');
            const container = document.getElementById('atribuicoes-container');
            const clone = template.content.cloneNode(true);

            if (turmaId) clone.querySelector('select[name="turma_id[]"]').value = turmaId;
            if (disciplinaId) clone.querySelector('select[name="disciplina_id[]"]').value = disciplinaId;

            container.appendChild(clone);
        }

        function removeAtribuicaoRow(btn) {
            const container = document.getElementById('atribuicoes-container');
            if (container.querySelectorAll('.atribuicao-row').length > 1) {
                btn.closest('.atribuicao-row').remove();
            } else {
                alert('É necessário pelo menos uma atribuição.');
            }
        }

        // Vincular o reset ao botão "Cadastrar Professor"
        $('[data-bs-target="#professorModal"]').on('click', function () {
            clearProfessorForm();
        });

        function updateTempoValues(select, iniId, fimId) {
            const val = select.value;
            if (val) {
                const parts = val.split('|');
                document.getElementById(iniId).value = parts[0];
                document.getElementById(fimId).value = parts[1];
            }
        }

        // Calendar Events JS
        function clearEventoForm() {
            $('#evento_id').val('');
            $('#eventForm')[0].reset();
            $('#eventoModalTitle').text('Registar Evento no Calendário');
            $('#evento_dest_id_wrapper').addClass('d-none');
        }

        function loadEventos() {
            $('#eventosList').html('<tr><td colspan="6" class="text-center py-4"><div class="spinner-border text-primary"></div></td></tr>');
            fetch('<?= URL_ROOT ?>/admin/getEventosAjax')
                .then(r => r.json())
                .then(data => {
                    let html = '';
                    if (data.length === 0) {
                        html = '<tr><td colspan="6" class="text-center text-muted py-4">Nenhum evento registado.</td></tr>';
                    } else {
                        data.forEach(e => {
                            const dataFormatada = e.data_evento_display;
                            html += `
                        <tr>
                            <td><span class="fw-bold" style="white-space:nowrap;">${dataFormatada}</span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:12px; height:12px; border-radius:3px; background:${e.cor}"></div>
                                    <span class="fw-bold">${e.titulo}</span>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark border">${e.tipo}</span></td>
                            <td><span class="badge bg-secondary">${e.destinatario_tipo}</span></td>
                            <td>${e.autor_nome}</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-light text-danger" onclick="deleteEvento(${e.id})"><ion-icon name="trash-outline"></ion-icon></button>
                            </td>
                        </tr>
                    `;
                        });
                    }
                    $('#eventosList').html(html);
                });
        }

        function deleteEvento(id) {
            if (confirm('Deseja remover este evento do calendário?')) {
                window.location.href = '<?= URL_ROOT ?>/admin/deleteEvento/' + id;
            }
        }

        function toggleEventoDest(tipo) {
            const wrapper = $('#evento_dest_id_wrapper');
            const select = $('#evento_dest_id');

            if (tipo === 'Global') {
                wrapper.addClass('d-none');
                select.attr('required', false);
            } else {
                wrapper.removeClass('d-none');
                select.attr('required', true);
                select.html('<option value="">A carregar...</option>');

                const endpoint = (tipo === 'Ano') ? '<?= URL_ROOT ?>/admin/getAnosJson' : '<?= URL_ROOT ?>/admin/getTurmasJson';
                fetch(endpoint)
                    .then(r => r.json())
                    .then(data => {
                        let html = '<option value="">Selecionar alvo...</option>';
                        data.forEach(item => {
                            html += `<option value="${item.id}">${item.nome || item.codigo}</option>`;
                        });
                        select.html(html);
                    });
            }
        }

        // Initial load for calendar tab
        $('[data-bs-target="#pane-calendario"]').on('shown.bs.tab', function () {
            loadEventos();

            // --- FULLCALENDAR INTERATIVO (Pilar 7) ---
            var calendarEl = document.getElementById('calendar-admin');
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
                    events: '<?= URL_ROOT ?>/admin/getCalendarEvents',
                    themeSystem: 'bootstrap5',
                    eventClick: function (info) {
                        alert('Evento: ' + info.event.title + '\nDescrição: ' + (info.event.extendedProps.description || 'Sem descrição'));
                    }
                });

                $('button[data-bs-target="#pane-calendario"], a[data-bs-target="#pane-calendario"]').on('shown.bs.tab', function () {
                    calendar.render();
                });
            }
        });

        function prepareAlocacao(estudanteId, nome) {
            document.getElementById('aloc_estudante_id').value = estudanteId;
            document.getElementById('aloc_estudante_nome').textContent = nome;
        }

        // Carregar Certificados Emitidos
        function loadCertificadosEmitidos() {
            const tbody = $('#tabelaCertificadosEmitidos tbody');
            tbody.html('<tr><td colspan="7" class="text-center py-4"><div class="spinner-border text-success"></div></td></tr>');

            fetch('<?= URL_ROOT ?>/admin/getCertificadosEmitidos')
                .then(r => r.json())
                .then(data => {
                    let html = '';
                    if (data.length === 0) {
                        html = '<tr><td colspan="7" class="text-center text-muted py-4">Nenhum certificado de mérito emitido até ao momento.</td></tr>';
                    } else {
                        data.forEach(c => {
                            const isFst = c.posicao === '1';
                            const badge = isFst ? '<span class="badge bg-warning text-dark"><ion-icon name="trophy"></ion-icon> 1º Lugar</span>' : '<span class="badge bg-secondary"><ion-icon name="medal"></ion-icon> 2º Lugar</span>';
                            const dFormatada = new Date(c.data_emissao).toLocaleDateString('pt-PT');

                            // Lógica de Status e Assinatura
                            let statusHtml = '';
                            let actionHtml = '';

                            if (c.status === 'Publicado') {
                                statusHtml = '<span class="badge bg-success"><ion-icon name="checkmark-done-circle"></ion-icon> Publicado</span>';
                                actionHtml = `<button class="btn btn-sm btn-outline-primary" onclick="window.open('<?= URL_ROOT ?>/estudante/certificado/${c.id}', '_blank')"><ion-icon name="eye"></ion-icon></button>`;
                            } else {
                                const assDir = c.assinatura_diretor ? '<span class="text-success small">Diretor ✓</span>' : '<span class="text-muted small">Diretor ✗</span>';
                                const assSec = c.assinatura_secretaria ? '<span class="text-success small">Secretaria ✓</span>' : '<span class="text-muted small">Secretaria ✗</span>';
                                statusHtml = `<div class="d-flex flex-column gap-1">${assDir}${assSec}</div>`;

                                // Botão de assinar conforme papel (Admin assume Diretor)
                                if (!c.assinatura_diretor) {
                                    actionHtml = `<button class="btn btn-sm btn-dark" onclick="abrirModalAssinaturaCert(${c.id}, '${c.estudante_nome}')"><ion-icon name="pencil"></ion-icon> Assinar</button>`;
                                } else {
                                    actionHtml = `<span class="badge bg-light text-dark border">Aguardando Sec.</span>`;
                                }
                            }

                            html += `
                        <tr>
                            <td class="fw-bold">${c.ano_letivo}</td>
                            <td>${c.semestre}º Semestre</td>
                            <td>
                                <div class="fw-bold text-dark">${c.estudante_nome}</div>
                                <div class="small text-muted">${c.nivel_nome || 'N/A'}</div>
                            </td>
                            <td>${badge}</td>
                            <td class="fw-bold text-success">${parseFloat(c.media).toFixed(2)}</td>
                            <td>${statusHtml}</td>
                            <td class="text-end">${actionHtml}</td>
                        </tr>
                    `;
                        });
                    }
                    tbody.html(html);
                })
                .catch(err => {
                    tbody.html('<tr><td colspan="7" class="text-center text-danger py-4">Erro ao carregar dados.</td></tr>');
                });
        }

        function abrirModalAssinaturaCert(id, nome) {
            if (confirm("Deseja marcar o certificado de " + nome + " como assinado pelo Diretor?")) {
                // Lógica simplificada sem assinatura digital
                $.post('<?= URL_ROOT ?>/admin/assinarCertificado', {
                    id: id,
                    papel: 'diretor',
                    assinatura: 'assinatura_presencial' // Placeholder
                }, function (res) {
                    if (res.success) {
                        alert("Certificado marcado como assinado.");
                        loadCertificadosEmitidos();
                    } else {
                        alert("Erro ao validar assinatura.");
                    }
                }, 'json');
            }
        }


        // Disparar carga quando abrir o separador de mérito
        $('[data-bs-target="#pane-merito"]').on('shown.bs.tab', function () {
            loadCertificadosEmitidos();
        });
        // Carregar Top 10 Alunos pentru Mérito
        function carregarTopAlunos() {
            const sem = $('#merito_semestre').val();
            const ano = $('#merito_ano_letivo').val();
            const btn = $('#btnProcurarElegiveis');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> A procurar...');

            $.ajax({
                url: '<?= URL_ROOT ?>/admin/getAlunosElegiveisMerito',
                type: 'POST',
                data: {
                    semestre: sem,
                    ano_letivo: ano,
                    csrf_token: $('#csrf_token_merito').val()
                },
                success: function (data) {
                    btn.prop('disabled', false).html('<ion-icon name="search-outline"></ion-icon> Procurar Elegíveis');
                    $('#tbody_alunos_merito').empty();

                    if (!data || data.length === 0) {
                        $('#tbody_alunos_merito').html('<tr><td colspan="5" class="text-center py-4 text-muted">Nenhum aluno atingiu os critérios (exames validados) neste semestre.</td></tr>');
                    } else {
                        let html = '';
                        data.forEach((aluno, index) => {
                            const mediaPositiva = parseFloat(aluno.media_calculada) >= 10;
                            html += `
                    <tr>
                        <td class="text-center">
                            <input class="form-check-input merit-check" type="checkbox" name="estudantes_selecionados[]" value="${aluno.estudante_id}" ${index < 2 && mediaPositiva ? 'checked' : ''}>
                        </td>
                        <td>
                            <input type="hidden" name="nomes[]" value="${aluno.nome_completo}" disabled>
                            ${aluno.nome_completo}
                        </td>
                        <td>
                            <input type="hidden" name="niveis[]" value="${aluno.nivel_nome || 'N/A'}" disabled>
                            ${aluno.nivel_nome || 'N/A'}
                        </td>
                        <td class="fw-bold text-${mediaPositiva ? 'success' : 'danger'}">
                            <input type="hidden" name="medias[]" value="${aluno.media_calculada}" disabled>
                            ${aluno.media_calculada}
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm text-center" name="posicoes[]" value="${index + 1}" min="1" disabled>
                        </td>
                    </tr>`;
                        });
                        $('#tbody_alunos_merito').html(html);

                        // Ativar os inputs desativados apenas quando o checkbox for selecionado, para o POST
                        $('.merit-check').on('change', function () {
                            const row = $(this).closest('tr');
                            const inputs = row.find('input[type="hidden"], input[type="number"]');
                            if (this.checked) {
                                inputs.removeAttr('disabled');
                            } else {
                                inputs.attr('disabled', 'disabled');
                            }
                        });
                        $('.merit-check').trigger('change');
                    }

                    $('#hdn_semestre').val(sem);
                    $('#hdn_ano_letivo').val(ano);
                    $('#resultado_merito_container').slideDown();
                },
                error: function (err) {
                    btn.prop('disabled', false).html('<ion-icon name="search-outline"></ion-icon> Procurar Elegíveis');
                    alert('Erro de servidor ao pesquisar os Top Alunos.');
                }
            });
        }

        function confirmarEmissaoMerito(form) {
            const selecionados = $(form).find('.merit-check:checked').length;
            if (selecionados === 0) {
                alert("Por favor, selecione pelo menos um aluno na lista.");
                return false;
            }
            const txt = selecionados > 2 ? 'Selecionaste mais do que 2 alunos. Tens a certeza que queres prosseguir?' : 'Confirmas a emissão dos certificados para o(s) ' + selecionados + ' aluno(s) com o envio para: ' + $('select[name="tipo_comunicado"]').val() + '?';
            return confirm(txt);
        }
    </script>

<!-- ═══════════════════════════════════════════════════════
     DEPENDÊNCIAS JAVASCRIPT (ORDEM CORRECTA)
═══════════════════════════════════════════════════════ -->
<!-- jQuery e Bootstrap já foram carregados no head. -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<script>
$(document).ready(function () {
    // ── DataTables Fix (Pilar 7) ────
    $.fn.dataTable.ext.errMode = 'none';

    // ── DataTables (inicialização robusta individual) ────
    const dtConfig = {
        language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-PT.json' },
        pageLength: 10,
        bLengthChange: false,
        destroy: true,
        order: []
    };
    $('.datatable-simple').each(function () {
        const $tbl = $(this);
        // Só inicializar se a tabela tem colunas definidas no thead
        if ($tbl.find('thead tr th').length > 0) {
            try { $tbl.DataTable(dtConfig); }
            catch (e) { console.warn('DataTable init failed for #' + ($tbl.attr('id') || '?'), e.message); }
        }
    });

    // ── Gráfico de Barras: Alunos por Ano Letivo ─────────
    const barCtx = document.getElementById('barChart');
    if (barCtx) {
        const chartDataAnos = <?= json_encode(array_values($data['chartData']['anos'] ?? [0,0,0,0,0])) ?>;

        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['1º Ano', '2º Ano', '3º Ano', '4º Ano', '5º Ano'],
                datasets: [{
                    label: 'Alunos Matriculados',
                    data: chartDataAnos,
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.85)',
                        'rgba(59, 130, 246, 0.85)',
                        'rgba(245, 158, 11, 0.85)',
                        'rgba(239, 68, 68, 0.85)',
                        'rgba(139, 92, 246, 0.85)'
                    ],
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.parsed.y} aluno(s)`
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { precision: 0 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // ── Gráfico de Pizza: Distribuição por Turno ─────────
    const pieCtx = document.getElementById('pieChart');
    if (pieCtx) {
        const chartTurnos = <?= json_encode($data['chartData']['turnos'] ?? ['Manhã'=>0,'Tarde'=>0,'Noite'=>0]) ?>;

        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(chartTurnos),
                datasets: [{
                    data: Object.values(chartTurnos),
                    backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#EF4444'],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                cutout: '60%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 16, font: { size: 13 } } },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.parsed} aluno(s) – ${ctx.label}`
                        }
                    }
                }
            }
        });
    }

    // ── Aprovação / Rejeição de Matrículas ────────────────
    $(document).on('click', '.btn-approve-matricula', function () {
        const id = $(this).data('id');
        if (id && confirm('Confirmar aprovação desta matrícula e criação de conta de aluno?')) {
            window.location.href = '<?= URL_ROOT ?>/admin/approveMatricula/' + id;
        }
    });

    $(document).on('click', '.btn-reject-matricula', function () {
        const id = $(this).data('id');
        if (id) {
            $('#rejectForm').attr('action', '<?= URL_ROOT ?>/admin/rejectMatricula/' + id);
            new bootstrap.Modal(document.getElementById('rejectModal')).show();
        }
    });

    // ── Edição de Itens via data-atributos ────────────────
    $(document).on('click', '.btn-edit-ano', function () {
        const d = $(this).data();
        $('#ano_id').val(d.id);
        $('#ano_numero').val(d.numero);
        $('#ano_nome').val(d.nome);
        $('#ano_descricao').val(d.desc);
        $('#ano_mensalidade').val(d.valor);
        $('#ano_ordem').val(d.ordem);
        $('#anoModalTitle').text('Editar Ano Curricular');
        new bootstrap.Modal(document.getElementById('anoModal')).show();
    });

    $(document).on('click', '.btn-edit-disciplina', function () {
        const d = $(this).data();
        $('#disc_id').val(d.id);
        $('#disc_codigo').val(d.codigo);
        $('#disc_nome').val(d.nome);
        $('#disc_ano').val(d.ano);
        $('#disc_carga').val(d.carga);
        $('#disc_credito').val(d.credito);
        $('#disc_desc').val(d.desc);
        $('#disciplinaModalTitle').text('Editar Disciplina');
        new bootstrap.Modal(document.getElementById('disciplinaModal')).show();
    });

    $(document).on('click', '.btn-edit-esp', function () {
        const d = $(this).data();
        $('#esp_id').val(d.id);
        $('#esp_codigo').val(d.codigo);
        $('#esp_nome').val(d.nome);
        $('#esp_descricao').val(d.desc);
        $('#esp_vagas').val(d.vagas);
        $('#esp_ativa').prop('checked', d.ativa == 1);
        $('#espModalTitle').text('Editar Especialidade');
        new bootstrap.Modal(document.getElementById('especialidadeModal')).show();
    });

    // ── Estudantes (Edição e Visualização) ────────────────
    $(document).on('click', '.btn-edit-student', function (e) {
        e.preventDefault();
        const d = $(this).data();
        $('#student_id').val(d.id);
        $('#student_nome').val(d.nome);
        $('#student_email').val(d.email);
        $('#student_bi').val(d.bi);
        $('#student_nascimento').val(d.nascimento);
        $('#student_telefone').val(d.telefone);
        $('#student_telefone_alt').val(d.telefone_alt);
        $('#student_estado_civil').val(d.estado_civil);
        $('#student_cidade').val(d.cidade);
        $('#student_bairro').val(d.bairro);
        $('#student_morada').val(d.morada);
        $('#student_escola').val(d.escola);
        $('#student_ano_conclusao').val(d.ano_conclusao);
        $('#student_media').val(d.media);
        $('#student_encarregado_nome').val(d.encarregado_nome);
        $('#student_encarregado_telefone').val(d.encarregado_telefone);
        
        if (d.sexo) {
            $('#student_sexo').val(d.sexo);
        }

        $('#modalTitle').text('Editar Estudante Interno');
        $('#btnSubmit').text('Salvar Alterações');
        
        // Modal is triggered automatically by data-bs-target as defined in HTML,
        // but if we needed to trigger it manually:
        // new bootstrap.Modal(document.getElementById('studentModal')).show();
    });

    $('#studentModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('#student_id').val('');
        $('#modalTitle').text('Novo Aluno Interno');
        $('#btnSubmit').text('Criar Aluno');
    });

window.viewStudent = function(id) {
    const content = $('#viewStudentContent');
    content.html('<div class="text-center py-4"><div class="spinner-border text-primary"></div></div>');
    new bootstrap.Modal(document.getElementById('viewStudentModal')).show();
    
    $.ajax({
        url: '<?= URL_ROOT ?>/admin/getStudentDetails/' + id,
        type: 'GET',
        success: function(data) {
            if(!data || data.error) {
                content.html('<div class="alert alert-danger py-2">Não foi possível carregar os detalhes do aluno.</div>');
                return;
            }
            let html = `
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px; font-size: 24px;">
                        <ion-icon name="person"></ion-icon>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">${data.nome_completo}</h5>
                        <span class="badge bg-${data.user_status == 'ativo' ? 'success' : 'warning'} mt-1">${(data.user_status||'').toUpperCase()}</span>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6 border-end">
                        <p class="mb-1 small text-muted">Email Profissional/Pessoal</p>
                        <p class="fw-bold mb-0">${data.email || 'N/A'}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 small text-muted">B.I. / Passaporte</p>
                        <p class="fw-bold mb-0">${data.bi || 'N/A'}</p>
                    </div>
                    <div class="col-md-6 border-end">
                        <p class="mb-1 small text-muted">Telefone Principal</p>
                        <p class="fw-bold mb-0">${data.telefone || 'N/A'}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1 small text-muted">Telefone Alternativo</p>
                        <p class="fw-bold mb-0">${data.telefone_alternativo || 'N/A'}</p>
                    </div>
                    <div class="col-md-6 border-end mt-3 border-top pt-3">
                        <p class="mb-1 small text-muted">Género</p>
                        <p class="fw-bold mb-0">${data.sexo || 'N/A'}</p>
                    </div>
                    <div class="col-md-6 mt-3 border-top pt-3">
                        <p class="mb-1 small text-muted">Data de Nascimento</p>
                        <p class="fw-bold mb-0">${data.data_nascimento ? new Date(data.data_nascimento).toLocaleDateString('pt-PT') : 'N/A'}</p>
                    </div>
                    <div class="col-12 mt-3 border-top pt-3">
                        <p class="mb-1 small text-muted">Morada Completa</p>
                        <p class="fw-bold mb-0">${data.morada || ''} ${data.bairro ? '- ' + data.bairro : ''} ${data.cidade ? '(' + data.cidade + ')' : ''}</p>
                    </div>
                    <div class="col-12 mt-3 border-top pt-3">
                        <h6 class="fw-bold text-dark">Informação Académica Anterior</h6>
                        <div class="row mt-2">
                             <div class="col-6">Escola: <strong>${data.escola || 'N/A'}</strong></div>
                             <div class="col-6">Média Final: <strong>${data.media || 'N/A'}</strong></div>
                        </div>
                    </div>
                     <div class="col-12 mt-3 border-top pt-3">
                        <h6 class="fw-bold text-dark">Encarregado de Educação</h6>
                        <div class="row mt-2">
                             <div class="col-6">Nome: <strong>${data.encarregado_nome || 'N/A'}</strong></div>
                             <div class="col-6">Telefone: <strong>${data.encarregado_telefone || 'N/A'}</strong></div>
                        </div>
                    </div>
                </div>
            `;
            content.html(html);
        },
        error: function() {
            content.html('<div class="alert alert-danger py-2">Erro ao conectar com o servidor.</div>');
        }
    });
};

    // ── Botão flutuante scroll-to-top ─────────────────────
    const topBtn = $('<button id="scrollTop" class="btn" style="position:fixed;bottom:24px;right:24px;width:44px;height:44px;border-radius:50%;background:#10B981;color:#fff;display:none;z-index:9999;box-shadow:0 4px 12px rgba(0,0,0,.2);"><i>↑</i></button>');
    topBtn.appendTo('body');
    $(window).on('scroll', () => {
        if ($(this).scrollTop() > 300) topBtn.fadeIn(); else topBtn.fadeOut();
    });
    topBtn.on('click', () => $('html,body').animate({ scrollTop: 0 }, 400));

});

function convocarComMotivo(eid, did) {
    const motivo = prompt("Por favor, indique o motivo da convocatória (Este texto será enviado ao Aluno e ao Professor):", "Convocatória oficial para resolução de conflito de notas.");
    if (motivo !== null) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= URL_ROOT ?>/admin/convocarPartes/${eid}/${did}`;
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = 'csrf_token';
        csrf.value = '<?= $_SESSION['csrf_token'] ?>';
        form.appendChild(csrf);
        
        const motInput = document.createElement('input');
        motInput.type = 'hidden';
        motInput.name = 'motivo';
        motInput.value = motivo;
        form.appendChild(motInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

    <!-- Modal de Rejeição de Matrícula -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true" style="z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered">
            <form id="rejectForm" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="modal-header bg-danger text-white border-0 py-3">
                    <h5 class="modal-title fw-bold"><ion-icon name="alert-circle-outline" class="me-2"></ion-icon> Rejeitar Matrícula</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">Indique o motivo da rejeição. Esta informação será enviada ao estudante.</p>
                    <div class="form-floating">
                        <textarea class="form-control" name="motivo" placeholder="Motivo da Rejeição" id="rejection_motivo" style="height: 120px" required></textarea>
                        <label for="rejection_motivo">Motivo detalhado...</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4">
                    <button type="button" class="btn btn-light px-4 rounded-pill fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger px-4 rounded-pill fw-bold shadow-sm">Confirmar Rejeição</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Visualização de Documentos -->
    <div class="modal fade" id="documentViewerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="background: #1e293b;">
                <div class="modal-header border-0 bg-dark text-white px-4 py-3">
                    <div class="d-flex align-items-center gap-2">
                         <ion-icon name="document-attach-outline" class="fs-4 text-primary"></ion-icon>
                         <h5 class="modal-title fw-bold mb-0" id="viewerModalLabel">Visualizador de Ficheiros</h5>
                    </div>
                    <div class="btn-group btn-group-sm ms-auto me-3" id="docSelector">
                        <button class="btn btn-outline-light" onclick="loadSpecialDoc('bi')" id="btn-bi">BI</button>
                        <button class="btn btn-outline-light" onclick="loadSpecialDoc('foto')" id="btn-foto">Foto</button>
                        <button class="btn btn-outline-light" onclick="loadSpecialDoc('cert')" id="btn-cert">Certificado</button>
                        <button class="btn btn-outline-light" onclick="loadSpecialDoc('comp')" id="btn-comp">Recibo</button>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" id="viewerContent" style="min-height: 80vh; background: #0f172a;">
                     <!-- Injeto Iframe ou Img aqui via JS -->
                </div>
            </div>
        </div>
    </div>
<!-- Modal Nova Matrícula Manual -->
<div class="modal fade" id="matriculaManualModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form class="modal-content border-0 shadow-lg" action="<?= URL_ROOT ?>/admin/createMatriculaManual" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <div class="modal-header bg-success text-white border-0">
                <h5 class="modal-title fw-bold">
                    <ion-icon name="person-add-outline" class="me-2"></ion-icon> Nova Matrícula — Registo Manual
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-info border-0 rounded-3 small mb-4">
                    <ion-icon name="information-circle-outline" class="me-1"></ion-icon>
                    Esta matrícula será criada e <strong>aprovada automaticamente</strong>. Utilize para alunos que se inscrevem presencialmente.
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nome Completo *</label>
                        <input type="text" name="nome" class="form-control" placeholder="Ex: João Silva" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted text-uppercase">Email *</label>
                        <input type="email" name="email" class="form-control" placeholder="aluno@email.com" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Nº BI *</label>
                        <input type="text" name="bi" class="form-control" placeholder="Ex: 123456789" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Telefone</label>
                        <input type="text" name="telefone" class="form-control" placeholder="Ex: 955123456">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Data Nascimento</label>
                        <input type="date" name="data_nascimento" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Ano do Curso *</label>
                        <select name="ano_id" class="form-select" required>
                            <?php foreach ($data['anos'] as $ano): ?>
                                <option value="<?= $ano['id'] ?>"><?= htmlspecialchars($ano['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Turno *</label>
                        <select name="turno" class="form-select" required>
                            <option value="Manhã">Manhã</option>
                            <option value="Tarde">Tarde</option>
                            <option value="Noite">Noite</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Tipo</label>
                        <select name="tipo" class="form-select">
                            <option value="Novo Ingresso">Novo Ingresso</option>
                            <option value="Renovação">Renovação</option>
                            <option value="Transferência">Transferência</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold small text-muted text-uppercase">Sexo</label>
                        <select name="sexo" class="form-select">
                            <option value="M">Masculino</option>
                            <option value="F">Feminino</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold small text-muted text-uppercase">Comprovativo de Pagamento (opcional)</label>
                        <input type="file" name="comprovativo" class="form-control" accept="image/*,.pdf">
                        <div class="form-text">PDF ou imagem. Máx. 5MB.</div>
                    </div>
                </div>
                <div class="alert alert-warning border-0 rounded-3 small mt-3">
                    <ion-icon name="key-outline" class="me-1"></ion-icon>
                    A senha provisória gerada será: <strong>ghs + últimos 4 dígitos do BI</strong>. Comunique ao aluno.
                </div>
            </div>
            <div class="modal-footer border-0 pb-4">
                <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success px-5 rounded-pill fw-bold shadow-sm">
                    <ion-icon name="checkmark-circle-outline" class="me-1"></ion-icon> Criar e Aprovar Matrícula
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>