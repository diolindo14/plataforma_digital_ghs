<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal da Secretaria - Green</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f8fafc; }
        .sidebar { background: #0f172a; min-height: 100vh; color: white; padding: 20px; position: fixed; width: 260px; }
        .sidebar a { color: #cbd5e1; text-decoration: none; display: flex; align-items: center; padding: 12px 15px; border-radius: 8px; margin-bottom: 5px; transition: 0.3s; cursor: pointer; }
        .sidebar a:hover, .sidebar a.active { background: #1e293b; color: #10b981; }
        .main-content { margin-left: 260px; padding: 30px; width: calc(100% - 260px); }
        .card-stat { background: white; border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); padding: 20px; height: 100%; }
        .icon-box { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 15px; }
        .tab-pane { animation: fadeIn 0.3s ease-in-out; }
                @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* Responsive Mobile Styles (GHS) */
        @media (max-width: 991.98px) {
            .sidebar { left: -260px; transition: left 0.3s ease-in-out; z-index: 1050; }
            .sidebar.active { left: 0; }
            .main-content { margin-left: 0 !important; width: 100% !important; padding: 1rem !important; padding-top: 80px !important; }
            .mobile-toggle { display: flex !important; }
        }
        .mobile-toggle { position: fixed; top: 15px; left: 15px; z-index: 1051; background: #0f172a; width: 45px; height: 45px; border-radius: 8px; display: none; align-items: center; justify-content: center; border: 1px solid rgba(255, 255, 255, 0.1); color: white; font-size: 1.5rem; cursor: pointer; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); }
            .mobile-header { position: fixed; top: 0; left: 0; right: 0; height: 65px; background: #0f172a; z-index: 1051; display: flex; align-items: center; padding: 0 15px; border-bottom: 1px solid rgba(255,255,255,0.1); box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
        .mobile-logo { display: flex; align-items: center; gap: 10px; margin-left: 55px; color: white; font-weight: bold; }
        .mobile-logo img { width: 35px; height: 35px; border-radius: 50%; object-fit: cover; border: 1px solid #10b981; }
        .mobile-toggle { position: relative; top: 0; left: 0; display: flex; margin-right: 0; }
    </style>
</head>
<body>
        <div class="mobile-header d-lg-none">
        <button class="mobile-toggle" id="sidebarToggle">
            <ion-icon name="menu-outline"></ion-icon>
        </button>
        <div class="mobile-logo">
            <img src="<?= URL_ROOT ?>/img/logo.jpg" alt="Logo">
            <span>Secretaria GHS</span>
        </div>
    </div>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="d-flex align-items-center gap-3 mb-5 px-2">
                <div style="width: 40px; height: 40px; border-radius: 50%; border: 2px solid #10b981; background: white; overflow: hidden;">
                    <img src="<?= URL_ROOT ?>/img/logo.jpg" alt="Logo" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <h5 class="mb-0 fw-bold">Secretaria</h5>
            </div>
            
            <nav class="nav flex-column nav-pills" id="v-pills-tab" role="tablist">
                <a class="nav-link active" data-bs-toggle="pill" data-bs-target="#pane-dashboard" role="tab"><ion-icon name="grid-outline" class="me-2"></ion-icon> Dashboard</a>
                <a class="nav-link d-flex align-items-center" id="tab-matriculas" data-bs-toggle="pill" data-bs-target="#pane-matriculas" href="javascript:void(0)" role="tab">
                    <ion-icon name="people-outline" class="me-2"></ion-icon> Matrículas
                    <?php if(!empty($data['matriculas_pendentes'])): ?>
                        <span class="badge bg-warning text-dark rounded-pill ms-auto" style="font-size: 0.65rem; padding: 0.35em 0.65em;"><?= count($data['matriculas_pendentes']) ?></span>
                    <?php endif; ?>
                </a>
                <a class="nav-link d-flex align-items-center" id="tab-financeiro" data-bs-toggle="pill" data-bs-target="#pane-pagamentos" href="javascript:void(0)" role="tab">
                    <ion-icon name="card-outline" class="me-2"></ion-icon> Pagamentos
                    <?php if(!empty($data['pagamentos_validar'])): ?>
                        <span class="badge bg-primary rounded-pill ms-auto" style="font-size: 0.65rem; padding: 0.35em 0.65em;"><?= count($data['pagamentos_validar']) ?></span>
                    <?php endif; ?>
                </a>
                <a class="nav-link" data-bs-toggle="pill" data-bs-target="#pane-comunicados" role="tab"><ion-icon name="megaphone-outline" class="me-2"></ion-icon> Comunicados</a>
                <a class="nav-link d-flex align-items-center" id="tab-notificacoes" data-bs-toggle="pill" data-bs-target="#pane-notificacoes" role="tab">
                    <ion-icon name="notifications-outline" class="me-2"></ion-icon> Alertas
                    <?php if(!empty($data['mensagens_painel'])): ?>
                        <span class="badge bg-danger rounded-pill ms-auto" style="font-size: 0.65rem; padding: 0.35em 0.65em;"><?= count($data['mensagens_painel']) ?></span>
                    <?php endif; ?>
                </a>
                <a class="nav-link" data-bs-toggle="pill" data-bs-target="#pane-merito" role="tab">
                    <ion-icon name="ribbon-outline" class="me-2"></ion-icon> Mérito & Certificados
                </a>
            </nav>

            <hr class="border-secondary my-4">
            <a href="<?= URL_ROOT ?>/" class="text-light mb-2"><ion-icon name="home-outline" class="me-2"></ion-icon> Voltar ao Site</a>
            <a href="<?= URL_ROOT ?>/auth/logout" class="text-danger"><ion-icon name="log-out-outline" class="me-2"></ion-icon> Sair</a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Flash Messages -->
            <?php if(isset($_SESSION['flash_success'])): ?>
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <?= $this->e($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if(isset($_SESSION['flash_error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <?= $this->e($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- 🏆 QUADRO DE MÉRITO (só aparece quando há dados disponíveis) -->
            <?php if (!empty($data['ranking_escola'])): ?>
            <div class="row mb-4">
                <div class="col-12 col-xl-4">
                    <?php 
                        $ranking_escola = $data['ranking_escola'];
                        $ranking_nivel = $data['ranking_nivel'];
                        $show_details = true; 
                        include __DIR__ . '/../partials/merit_board.php'; 
                    ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">Bem-vindo(a), <?= htmlspecialchars($data['nome'] ?? 'Colaborador') ?></h2>
                <div class="dropdown">
                    <div class="d-flex align-items-center gap-2 border px-3 py-2 rounded-pill bg-white shadow-sm dropdown-toggle" data-bs-toggle="dropdown" style="cursor: pointer;">
                        <ion-icon name="person-circle" style="font-size: 1.8rem; color: #10B981;"></ion-icon>
                        <span class="fw-bold text-dark d-none d-sm-inline"><?= htmlspecialchars($data['nome'] ?? 'Colaborador') ?></span>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px; margin-top: 10px; min-width: 200px;">
                        <li><a class="dropdown-item fw-bold text-dark py-2" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal"><ion-icon name="key-outline" class="me-2 fs-5 align-middle"></ion-icon> Alterar Senha</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item fw-bold text-danger py-2" href="<?= URL_ROOT ?>/auth/logout"><ion-icon name="log-out-outline" class="me-2 fs-5 align-middle"></ion-icon> Sair</a></li>
                    </ul>
                </div>
            </div>

            <div class="tab-content" id="v-pills-tabContent">
                <!-- Dashboard Pane -->
                <div class="tab-pane fade show active" id="pane-dashboard" role="tabpanel">
                    <div class="row g-4 mb-5">
                        <div class="col-md-4">
                            <div class="card-stat">
                                <div class="icon-box" style="background: #ecfdf5; color: #10b981;">
                                    <ion-icon name="document-text-outline"></ion-icon>
                                </div>
                                <h3 class="fw-bold mb-1"><?= $data['stats']['matriculas_pendentes'] ?? 0 ?></h3>
                                <p class="text-muted mb-0 small fw-bold">Matrículas Pendentes</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-stat">
                                <div class="icon-box" style="background: #eff6ff; color: #3b82f6;">
                                    <ion-icon name="wallet-outline"></ion-icon>
                                </div>
                                <h3 class="fw-bold mb-1"><?= $data['stats']['pagamentos_validar'] ?? 0 ?></h3>
                                <p class="text-muted mb-0 small fw-bold">Pagamentos a Validar</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-stat">
                                <div class="icon-box" style="background: #fffbeb; color: #f59e0b;">
                                    <ion-icon name="chatbubbles-outline"></ion-icon>
                                </div>
                                <h3 class="fw-bold mb-1"><?= count($data['comunicados'] ?? []) ?></h3>
                                <p class="text-muted mb-0 small fw-bold">Comunicados Ativos</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- ── ALERTAS DA ADMINISTRAÇÃO (GHS Workflow) ── -->
                    <div class="card border-0 shadow-sm rounded-4 p-4">
                        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2 text-dark">
                            <ion-icon name="notifications-circle-outline" class="text-primary fs-4"></ion-icon>
                            Alertas da Administração
                        </h5>
                        <div class="list-group list-group-flush" style="max-height: 300px; overflow-y: auto;">
                            <?php if(empty($data['mensagens_painel'])): ?>
                                <!-- Estado vazio informativo -->
                                <div class="p-3 text-center text-muted">
                                    <p class="small mb-0">Nenhuma instrução pendente.</p>
                                </div>
                            <?php else: ?>
                                <?php foreach($data['mensagens_painel'] as $msg): ?>
                                    <!-- Item de alerta individual vindo do modelo Mensagem -->
                                    <div class="list-group-item list-group-item-action border-0 px-0 py-3 d-flex align-items-start gap-3">
                                        <div class="p-2 bg-primary bg-opacity-10 rounded-circle text-primary shadow-sm">
                                            <ion-icon name="information-circle"></ion-icon>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-bold small text-dark"><?= htmlspecialchars($msg['assunto'] ?? 'Notificação') ?></span>
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
                        <div class="mt-4 border-top pt-3 d-flex justify-content-between">
                            <form action="<?= URL_ROOT ?>/secretaria/clearNotifications" method="POST" class="d-inline">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <button type="submit" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                    <ion-icon name="trash-outline" class="me-1"></ion-icon> Limpar alertas lidos
                                </button>
                            </form>
                            <button onclick="document.getElementById('tab-notificacoes').click()" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                                <ion-icon name="list-outline" class="me-1"></ion-icon> Ver histórico completo
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Histórico de Alertas da Administração -->
                <div class="tab-pane fade" id="pane-notificacoes" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold text-dark mb-0">Histórico de Alertas da Administração</h3>
                    </div>
                    
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <?php if(empty($data['mensagens_historico'])): ?>
                                <div class="text-center py-5 text-muted">
                                    <ion-icon name="mail-open-outline" style="font-size: 3rem; opacity: 0.3;"></ion-icon>
                                    <p class="mt-2">Sem histórico de mensagens.</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Data</th>
                                                <th>Assunto</th>
                                                <th>Mensagem</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($data['mensagens_historico'] as $msg): ?>
                                                <tr class="<?= $msg['lida'] ? 'opacity-75' : 'bg-primary bg-opacity-10' ?>">
                                                    <td class="small text-muted" style="white-space: nowrap;"><?= date('d/m/Y H:i', strtotime($msg['data_criacao'])) ?></td>
                                                    <td><span class="badge bg-primary bg-opacity-10 text-primary"><?= htmlspecialchars($msg['assunto'] ?? 'Alerta') ?></span></td>
                                                    <td class="small"><?= htmlspecialchars($msg['mensagem']) ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Painel de Matrículas (Expanded List with Modal Viewer) -->
                <div class="tab-pane fade" id="pane-matriculas" role="tabpanel">
                    <div class="row">
                        <div class="col-md-10 mx-auto">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="card-header bg-white py-4 border-0 d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0 fw-bold text-dark">Fila de Triagem (Matrículas Pendentes)</h4>
                                    <div class="d-flex gap-2 align-items-center">
                                        <button class="btn btn-success btn-sm rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#matriculaManualModal">
                                            <ion-icon name="person-add-outline" class="me-1"></ion-icon> Nova Matrícula
                                        </button>
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                            <?= count($data['matriculas_pendentes'] ?? []) ?> Processos Aguardando
                                        </span>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="ps-4 border-0 py-3">Estudante</th>
                                                <th class="border-0 py-3">Ano Letivo</th>
                                                <th class="border-0 py-3 text-center">Documentação</th>
                                                <th class="text-end pe-4 border-0 py-3">Ações de Validação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(empty($data['matriculas_pendentes'])): ?>
                                                <tr><td colspan="4" class="text-center py-5 text-muted">Nenhuma matrícula pendente.</td></tr>
                                            <?php else: ?>
                                                <?php foreach($data['matriculas_pendentes'] as $m): ?>
                                                    <tr>
                                                        <td class="ps-4">
                                                            <div class="fw-bold text-dark fs-6"><?= $this->e($m['nome']) ?></div>
                                                            <div class="text-muted small">Processo: #<?= $m['id'] ?></div>
                                                        </td>
                                                        <td>
                                                            <span class="text-muted fw-medium"><?= htmlspecialchars($m['ano_letivo']) ?></span>
                                                        </td>
                                                        <td class="text-center">
                                                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3 btn-view-docs" 
                                                                    data-bs-toggle="modal" data-bs-target="#documentViewerModal"
                                                                    data-id="<?= $m['id'] ?>" 
                                                                    data-nome="<?= htmlspecialchars($m['nome']) ?>"
                                                                    data-bi="<?= $m['bi_arquivo'] ?? '' ?>"
                                                                    data-foto="<?= $m['foto_arquivo'] ?? '' ?>"
                                                                    data-cert="<?= $m['certificado_arquivo'] ?? '' ?>"
                                                                    data-comp="<?= $m['comprovativo_arquivo'] ?? '' ?>">
                                                                <ion-icon name="documents-outline" class="me-1"></ion-icon> Ver Ficheiros
                                                            </button>
                                                        </td>
                                                        <td class="text-end pe-4">
                                                            <div class="btn-group">
                                                                <form action="<?= URL_ROOT ?>/secretaria/approveMatricula/<?= $m['id'] ?>" method="POST" class="d-inline">
                                                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                                    <button type="submit" class="btn btn-success btn-sm px-3 rounded-start border-0 shadow-sm" onclick="return confirm('Confirmar aprovação deste processo?')">
                                                                        Aprovar
                                                                    </button>
                                                                </form>
                                                                <button class="btn btn-danger btn-sm px-3 rounded-end border-0 shadow-sm" data-bs-toggle="modal" data-bs-target="#rejectModal<?= $m['id'] ?>">
                                                                    Rejeitar
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
                </div>
         </div>
                    </div>

                    <!-- Modais de Rejeição -> fora da tabela -->
                    <?php if(!empty($data['matriculas_pendentes'])): ?>
                        <?php foreach($data['matriculas_pendentes'] as $m): ?>
                            <div class="modal fade" id="rejectModal<?= $m['id'] ?>" tabindex="-1" style="z-index: 9999;">
                                <div class="modal-dialog">
                                    <form action="<?= URL_ROOT ?>/secretaria/rejectMatricula/<?= $m['id'] ?>" method="POST" class="modal-content border-0 shadow-lg">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title fw-bold">Rejeitar Matrícula</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <label class="form-label fw-bold small">Motivo da Rejeição</label>
                                            <textarea name="motivo" class="form-control" rows="3" required placeholder="Ex: Documentação de BI ilegível..."></textarea>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Voltar</button>
                                            <button type="submit" class="btn btn-danger fw-bold">Confirmar Rejeição</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Pagamentos Pane -->
                <div class="tab-pane fade" id="pane-pagamentos" role="tabpanel">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-white py-3 border-0">
                            <h5 class="mb-0 fw-bold">Validação de Pagamentos (Comprovativos)</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light shadow-sm">
                                    <tr>
                                        <th class="ps-4">Estudante / Descrição</th>
                                        <th>Valor (XOF)</th>
                                        <th>Vencimento</th>
                                        <th class="text-center">Comprovativo</th>
                                        <th class="text-end pe-4">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($data['pagamentos_validar'])): ?>
                                        <tr><td colspan="5" class="text-center py-5 text-muted">Nenhum pagamento com comprovativo pendente de validação.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($data['pagamentos_validar'] as $p): ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="fw-bold text-dark"><?= $this->e($p['aluno_nome'] ?? 'N/A') ?></div>
                                                    <div class="text-muted small"><?= $this->e($p['descricao']) ?></div>
                                                </td>
                                                <td class="fw-bold"><?= number_format($p['valor'], 0, ',', '.') ?></td>
                                                <td><?= date('d/m/Y', strtotime($p['data_vencimento'])) ?></td>
                                                <td class="text-center">
                                                    <a href="<?= URL_ROOT ?>/<?= $p['comprovativo_arquivo'] ?>" target="_blank" class="btn btn-xs btn-outline-primary py-0"><ion-icon name="document-attach-outline"></ion-icon> Ver</a>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <form action="<?= URL_ROOT ?>/secretaria/validatePayment/<?= $p['id'] ?>" method="POST" style="display:inline;">
                                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-primary border-0 shadow-sm" onclick="return confirm('Validar este pagamento?')"><ion-icon name="card-outline" class="me-1"></ion-icon> Validar</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Comunicados Pane -->
                <div class="tab-pane fade" id="pane-comunicados" role="tabpanel">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                                <h5 class="fw-bold mb-3">Novo Comunicado</h5>
                                <form action="<?= URL_ROOT ?>/secretaria/saveComunicado" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    

    

                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Título</label>
                                        <input type="text" name="titulo" class="form-control" required placeholder="Ex: Aviso de Feriado">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Mensagem</label>
                                        <textarea name="conteudo" class="form-control" rows="5" required placeholder="Texto do comunicado..."></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Para:</label>
                                        <select name="tipo_destinatario" class="form-select">
                                            <option value="Todos">Todos os utilizadores</option>
                                            <option value="Alunos">Apenas Estudantes</option>
                                            <option value="Professores">Apenas Professores</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 fw-bold border-0 shadow-sm">Publicar Comunicado</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="card-header bg-white py-3 border-0">
                                    <h5 class="mb-0 fw-bold">Comunicados Recentes</h5>
                                </div>
                                <div class="p-4">
                                    <?php if(empty($data['comunicados'])): ?>
                                        <p class="text-center text-muted py-5">Sem comunicados registados.</p>
                                    <?php else: ?>
                                        <?php foreach(array_slice($data['comunicados'], 0, 5) as $c): ?>
                                            <div class="d-flex gap-3 mb-4 border-bottom pb-3">
                                                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3 h-100"><ion-icon name="megaphone-outline" class="fs-4"></ion-icon></div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <h6 class="fw-bold mb-1"><?= $this->e($c['titulo']) ?></h6>
                                                        <span class="badge bg-light text-muted fw-normal" style="font-size: 0.7rem;"><?= date('d/m/Y', strtotime($c['data_criacao'])) ?></span>
                                                    </div>
                                                    <p class="text-muted small mb-1"><?= nl2br($this->e($c['conteudo'])) ?></p>
                                                    <span class="badge bg-light text-primary fw-normal" style="font-size: 0.65rem;">Alvo: <?= ucfirst($c['tipo_utilizador'] ?? 'Todos') ?></span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                    </div>
                </div>

                <!-- Painel de Mérito e Certificados (Secretaria) -->
                <div class="tab-pane fade" id="pane-merito" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold mb-0 text-dark">Gestão de Certificados de Mérito</h2>
                        <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm">Validação & Assinatura Secretaria</span>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white py-3 border-bottom border-light">
                            <h6 class="fw-bold mb-0">Certificados Aguardando Assinatura / Publicados</h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle" id="tabelaCertificadosEmitidos">
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
            </div>
        </div>
    </div>
    


    <script>
        // JS para Certificados na Secretaria
        function loadCertificadosEmitidos() {
            const tbody = $('#tabelaCertificadosEmitidos tbody');
            tbody.html('<tr><td colspan="7" class="text-center py-4"><div class="spinner-border text-success"></div></td></tr>');
            
            fetch('<?= URL_ROOT ?>/secretaria/getCertificadosEmitidos')
                .then(r => r.json())
                .then(data => {
                    let html = '';
                    if (data.length === 0) {
                        html = '<tr><td colspan="7" class="text-center text-muted py-4">Nenhum certificado disponível para validação.</td></tr>';
                    } else {
                        data.forEach(c => {
                            const isFst = c.posicao === '1';
                            const badge = isFst ? '<span class="badge bg-warning text-dark">1º Lugar</span>' : '<span class="badge bg-secondary">2º Lugar</span>';
                            
                            let statusHtml = '';
                            let actionHtml = '';
                            
                            if (c.status === 'Publicado') {
                                statusHtml = '<span class="badge bg-success">Publicado</span>';
                                actionHtml = `<button class="btn btn-sm btn-outline-primary" onclick="window.open('<?= URL_ROOT ?>/estudante/certificado/${c.id}', '_blank')"><ion-icon name="eye"></ion-icon></button>`;
                            } else {
                                const assDir = c.assinatura_diretor ? '<span class="text-success small">Diretor ✓</span>' : '<span class="text-muted small">Diretor ✗</span>';
                                const assSec = c.assinatura_secretaria ? '<span class="text-success small">Secretaria ✓</span>' : '<span class="text-muted small">Secretaria ✗</span>';
                                statusHtml = `<div class="d-flex flex-column gap-1">${assDir}${assSec}</div>`;
                                
                                if (!c.assinatura_secretaria) {
                                    actionHtml = `<button class="btn btn-sm btn-success" onclick="abrirModalAssinaturaCert(${c.id}, '${c.estudante_nome}')"><ion-icon name="pencil"></ion-icon> Assinar</button>`;
                                } else {
                                    actionHtml = `<span class="badge bg-light text-dark border">Aguardando Diretor</span>`;
                                }
                            }

                            html += `
                                <tr>
                                    <td>${c.ano_letivo}</td>
                                    <td>${c.semestre}º</td>
                                    <td><div class="fw-bold">${c.estudante_nome}</div></td>
                                    <td>${badge}</td>
                                    <td class="fw-bold text-success">${parseFloat(c.media).toFixed(2)}</td>
                                    <td>${statusHtml}</td>
                                    <td class="text-end">${actionHtml}</td>
                                </tr>
                            `;
                        });
                    }
                    tbody.html(html);
                });
        }

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

            // file may be just the filename or a full relative path
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

        $(document).ready(function() {
            // Document Viewer Trigger logic
            const docViewerModal = document.getElementById('documentViewerModal');
            if (docViewerModal) {
                docViewerModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    currentDocData = $(button).data();
                    loadSpecialDoc('bi');
                });
            }
            
            // Restaura lógica de Assinaturas e Certificados
            $('[data-bs-target="#pane-merito"]').on('shown.bs.tab', function() {
                if (typeof loadCertificadosEmitidos === 'function') loadCertificadosEmitidos();
            });

            $('.modal').on('hidden.bs.modal', function() {
                $(this).find('form').trigger('reset');
            });
        });

        function abrirModalAssinaturaCert(id, nome) {
            if (confirm("Deseja marcar o certificado de " + nome + " como assinado pela Secretaria?")) {
                $.post('<?= URL_ROOT ?>/secretaria/assinarCertificado', {
                    id: id,
                    assinatura: 'assinatura_presencial'
                }, function(res) {
                    if (res.success) {
                        alert("Certificado marcado como assinado.");
                        if (typeof loadCertificadosEmitidos === 'function') loadCertificadosEmitidos();
                    } else alert('Erro ao validar assinatura.');
                }, 'json');
            }
        }
    </script>
    <!-- Core Assinaturas GHS -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script src="<?= URL_ROOT ?>/public/js/signatures_core.js"></script>

    <!-- Modal de Visualização de Documentos (Posição Segura no Fundo) -->
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
                <div class="modal-body p-0" id="viewerContent" style="min-height: 80vh;">
                     <!-- Injeto Iframe ou Img aqui -->
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Nova Matrícula Manual -->
    <div class="modal fade" id="matriculaManualModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <form class="modal-content border-0 shadow-lg" action="<?= URL_ROOT ?>/secretaria/createMatriculaManual" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="modal-header bg-success text-white border-0">
                    <h5 class="modal-title fw-bold">
                        <ion-icon name="person-add-outline" class="me-2"></ion-icon> Nova Matrícula — Registo Manual (Secretaria)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 rounded-3 small mb-4">
                        <ion-icon name="information-circle-outline" class="me-1"></ion-icon>
                        Esta matrícula será criada e <strong>aprovada automaticamente</strong>. Utilize para alunos que se inscrevem presencialmente no balcão.
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nome Completo *</label>
                            <input type="text" name="nome" class="form-control form-control-sm" placeholder="Ex: João Silva" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase">Email *</label>
                            <input type="email" name="email" class="form-control form-control-sm" placeholder="aluno@email.com" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Nº BI *</label>
                            <input type="text" name="bi" class="form-control form-control-sm" placeholder="Ex: 123456789" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Telefone</label>
                            <input type="text" name="telefone" class="form-control form-control-sm" placeholder="Ex: 955123456">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Data Nascimento</label>
                            <input type="date" name="data_nascimento" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Ano do Curso *</label>
                            <select name="ano_id" class="form-select form-select-sm" required>
                                <option value="1">1º Ano</option>
                                <option value="2">2º Ano</option>
                                <option value="3">3º Ano</option>
                                <option value="4">4º Ano</option>
                                <option value="5">5º Ano</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Turno *</label>
                            <select name="turno" class="form-select form-select-sm" required>
                                <option value="Manhã">Manhã</option>
                                <option value="Tarde">Tarde</option>
                                <option value="Noite">Noite</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted text-uppercase">Tipo</label>
                            <select name="tipo" class="form-select form-select-sm">
                                <option value="Novo Ingresso">Novo Ingresso</option>
                                <option value="Renovação">Renovação</option>
                                <option value="Transferência">Transferência</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted text-uppercase">Comprovativo de Pagamento (opcional)</label>
                            <input type="file" name="comprovativo" class="form-control form-control-sm" accept="image/*,.pdf">
                            <div class="form-text" style="font-size: 0.7rem;">PDF ou imagem. Máx. 5MB.</div>
                        </div>
                    </div>
                    <div class="alert alert-warning border-0 rounded-3 small mt-3">
                        <ion-icon name="key-outline" class="me-1"></ion-icon>
                        A senha provisória será: <strong>ghs + últimos 4 dígitos do BI</strong>.
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4">
                    <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success px-5 rounded-pill fw-bold shadow-sm">
                        <ion-icon name="checkmark-circle-outline" class="me-1"></ion-icon> Criar Matrícula
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
        });

        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const toggle = document.getElementById('sidebarToggle');
            if (window.innerWidth <= 991.98 && !sidebar.contains(event.target) && !toggle.contains(event.target) && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });
    </script>
    <!-- Modal Alterar Senha Global -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="<?= URL_ROOT ?>/auth/changePassword" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="modal-header border-0 bg-light pb-2">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                        <ion-icon name="lock-closed" class="text-primary"></ion-icon> Alterar Credenciais
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 pt-3">
                    <div class="alert alert-info border-0 bg-opacity-10 text-primary small rounded-3 mb-4 d-flex align-items-start gap-2">
                        <ion-icon name="information-circle" class="fs-5 mt-1"></ion-icon>
                        <span>É necessário alterar a sua senha para garantir a segurança da sua conta após o primeiro login ou se pretender atualizar os seus dados.</span>
                    </div>
                    <!-- Nova Senha -->
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold text-muted small">Nova Senha <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><ion-icon name="key-outline" class="text-muted"></ion-icon></span>
                            <input type="password" name="new_password" class="form-control border-start-0 ps-0" placeholder="Mínimo de 6 caracteres" required minlength="6">
                        </div>
                    </div>
                    <!-- Confirmar Senha -->
                    <div class="col-12 mb-2">
                        <label class="form-label fw-bold text-muted small">Confirmar Nova Senha <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><ion-icon name="checkmark-done-outline" class="text-muted"></ion-icon></span>
                            <input type="password" name="confirm_password" class="form-control border-start-0 ps-0" placeholder="Repita a nova senha" required minlength="6">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light pb-4">
                    <button type="button" class="btn btn-secondary px-4 fw-bold rounded-pill" data-bs-dismiss="modal">Mais Tarde</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill d-flex align-items-center gap-2">
                        <ion-icon name="save-outline"></ion-icon> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if(isset($_SESSION['must_change_password']) && $_SESSION['must_change_password'] === true): ?>
    <script>
        $(document).ready(function() {
            var pwModal = new bootstrap.Modal(document.getElementById('changePasswordModal'), {
                keyboard: false,
                backdrop: 'static'
            });
            pwModal.show();
            $('#changePasswordModal .btn-secondary').hide();
            $('#changePasswordModal .btn-close').hide();
        });
    </script>
    <?php endif; ?>
</body>
</html>
