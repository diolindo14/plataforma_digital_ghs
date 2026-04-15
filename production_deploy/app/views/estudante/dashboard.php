<?php /** @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Estudante - GHS</title>
    <!-- CSS Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- DataTables CSS for Export Buttons -->
    <link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <link rel="stylesheet" href="<?= URL_ROOT ?>/public/css/responsive_global.css">
    <style>
        :root {
            --ghs-primary: #10B981;
            --ghs-secondary: #3B82F6;
            --ghs-dark: #0F172A;
            --ghs-slate: #1E293B;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.4);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #f0f2f5;
            color: #334155;
            overflow-x: hidden;
        }

        /* As definições de .sidebar, .content e Media Queries foram movidas para responsive_global.css 
           para garantir consistência em todo o sistema. */

        .mobile-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1100;
            background: white;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: none;
            align-items: center;
            justify-content: center;
            border: none;
            font-size: 1.5rem;
            color: var(--ghs-dark);
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.1);
            border-color: rgba(16, 185, 129, 0.2);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            font-size: 1.6rem;
            transition: 0.3s;
        }

        .profile-btn {
            background: white;
            border-radius: 15px;
            padding: 8px 15px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            cursor: pointer;
            transition: 0.3s;
        }

        .profile-btn:hover {
            background: #f8fafc;
            border-color: var(--ghs-primary);
        }

        .tab-pane {
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.98);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .btn-premium {
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }

        .btn-premium-success {
            background: var(--ghs-primary);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-premium-success:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .table-premium {
            border-radius: 15px;
            overflow: hidden;
            border: none !important;
        }

        .table-premium thead th {
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 15px;
            border: none;
        }

        .table-premium tbody td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .indicator-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
    </style>

</head>

<body class="bg-light">

    <!-- Overlay p/ Mobile -->
    <div class="ghs-sidebar-overlay" onclick="toggleSidebar()"></div>

    <?php 
    // Detetar se há convocatórias activas para alerta de topo
    $temConvocatoria = false;
    $infoConvocatoria = null;
    foreach($data['notas'] as $n) {
        if ($n['feedback_status'] === 'Aguardando_Comparecimento') {
            $temConvocatoria = true;
            $infoConvocatoria = $n;
            break;
        }
    }
    ?>

    <?php if ($temConvocatoria): ?>
    <div class="alert alert-danger border-0 rounded-0 m-0 py-3 shadow-lg position-sticky top-0 w-100 d-flex align-items-center justify-content-center gap-3" style="z-index: 1050; background: linear-gradient(90deg, #dc3545, #b02a37);">
        <div class="d-flex align-items-center gap-2 text-white">
            <ion-icon name="alert-circle" style="font-size: 1.8rem;"></ion-icon>
            <div class="fw-bold">
                <span class="d-block text-uppercase small opacity-75">Convocatória Urgente</span>
                Reunião de Mediação: <?= date('d/m/Y', strtotime($infoConvocatoria['data_reuniao'])) ?> às <?= substr($infoConvocatoria['hora_reuniao'],0,5) ?> (<?= htmlspecialchars($infoConvocatoria['local_reuniao']) ?>)
            </div>
        </div>
        <button class="btn btn-sm btn-light fw-bold rounded-pill px-3" onclick="$('#tab-notas').tab('show'); document.getElementById('tab-notas').scrollIntoView();">Ver Detalhes</button>
    </div>
    <?php endif; ?>

    <!-- Cabeçalho Mobile -->
    <header class="ghs-mobile-header shadow-sm">
        <button class="btn btn-link text-dark p-0 border-0" onclick="toggleSidebar()">
            <ion-icon name="menu-outline" style="font-size: 2rem;"></ion-icon>
        </button>
        <div class="d-flex align-items-center gap-2">
            <img src="<?= URL_ROOT ?>/img/logo.jpg" alt="Logo" style="width: 35px; height: 35px; border-radius: 50%;">
            <span class="fw-bold">GHS Estudante</span>
        </div>
        <div>
            <ion-icon name="notifications-outline" class="fs-4 text-muted"></ion-icon>
        </div>
    </header>

    <div class="d-flex overflow-hidden">
    <!-- Sidebar -->
    <nav class="sidebar ghs-sidebar shadow-lg d-flex flex-column justify-content-start">
        <div>
            <div class="sidebar-brand text-center mb-4 mt-2 border-bottom border-light border-opacity-10 pb-3">
                <div style="width: 64px; height: 64px; border-radius: 50%; border: 2px solid var(--ghs-primary); display: flex; align-items: center; justify-content: center; background: #fff; margin: 0 auto; overflow: hidden;">
                    <img src="<?= URL_ROOT ?>/img/logo.jpg" alt="Logo GHS" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <h5 class="fw-bold text-white mb-1 mt-3" style="font-size: .95rem;">Green Hard & Softh</h5>
                <span class="badge" style="background:rgba(16,185,129,.15); color:var(--ghs-primary); border:1px solid rgba(16,185,129,.3); font-size: .65rem; letter-spacing: .06em;">PORTAL ESTUDANTE</span>
            </div>

            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="tab-home" data-bs-toggle="pill" data-bs-target="#pane-home" role="tab"><ion-icon name="grid-outline"></ion-icon> Meu Painel</a>
                <a class="nav-link" id="tab-horario" data-bs-toggle="pill" data-bs-target="#pane-horario" role="tab"><ion-icon name="calendar-outline"></ion-icon> Horário & Calendário</a>
                <a class="nav-link" id="tab-notas" data-bs-toggle="pill" data-bs-target="#pane-notas" role="tab"><ion-icon name="pie-chart-outline"></ion-icon> Avaliação Contínua</a>
                <a class="nav-link" id="tab-historico" data-bs-toggle="pill" data-bs-target="#pane-historico" role="tab"><ion-icon name="document-text-outline"></ion-icon> Histórico Académico</a>
                <a class="nav-link" id="tab-materiais" data-bs-toggle="pill" data-bs-target="#pane-materiais" role="tab"><ion-icon name="folder-open-outline"></ion-icon> Materiais Didáticos</a>
                <a class="nav-link" id="tab-sumarios" data-bs-toggle="pill" data-bs-target="#pane-sumarios" role="tab"><ion-icon name="reader-outline"></ion-icon> Sumários de Aula</a>

                <a class="nav-link" id="tab-financeiro" data-bs-toggle="pill" data-bs-target="#pane-financeiro" role="tab"><ion-icon name="wallet-outline"></ion-icon> Pagamentos</a>
                <a class="nav-link" id="tab-comunicados" data-bs-toggle="pill" data-bs-target="#pane-comunicados" role="tab"><ion-icon name="notifications-outline"></ion-icon> Comunicados & Alertas</a>
                <a class="nav-link text-info fw-bold" href="<?= URL_ROOT ?>/matricula"><ion-icon name="add-circle-outline"></ion-icon> Nova Matrícula</a>
                <a class="nav-link text-warning mb-1" href="<?= URL_ROOT ?>/"><ion-icon name="earth-outline"></ion-icon> Voltar ao Site</a>
                <a class="nav-link text-danger fw-bold" href="<?= URL_ROOT ?>/auth/logout"><ion-icon name="log-out-outline"></ion-icon> Terminar Sessão</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="content ghs-content flex-grow-1">
        <div class="ghs-container">
            <div class="ghs-content-header d-none d-lg-block mb-4">

        <?php if (isset($data['alerta_matricula'])): ?>
            <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 border-start border-4 border-warning rounded-3 mb-4"
                role="alert">
                <div class="d-flex align-items-center">
                    <ion-icon name="time-outline" class="me-3 fs-3 text-warning"></ion-icon>
                    <div>
                        <h6 class="fw-bold mb-1">Prazo de Matrícula Crítico</h6>
                        <p class="mb-0 small">
                            <?= is_array($data['alerta_matricula'] ?? null) ? implode(' ', $data['alerta_matricula']) : (string) ($data['alerta_matricula'] ?? '') ?>
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($data['smart_delinquency']) && $data['smart_delinquency']['is_delinquent']): ?>
            <div class="alert alert-danger shadow-sm border-0 border-start border-4 border-danger rounded-3 mb-4"
                role="alert">
                <div class="d-flex align-items-center">
                    <ion-icon name="alert-circle" class="me-3 fs-3 text-danger"></ion-icon>
                    <div>
                        <h6 class="fw-bold mb-1">Atraso de Pagamento Detectado</h6>
                        <p class="mb-0 small">A sua conta encontra-se irregular. Foram detectados
                            <strong><?= (int) ($data['smart_delinquency']['missing_months'] ?? 0) ?> meses</strong> de
                            mensalidades em atraso. Por favor, regularize a sua situação na tesouraria.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php
        $uploadErrors = [
            'size' => 'O ficheiro é demasiado grande. Máximo permitido: 5MB.',
            'ext' => 'Tipo de ficheiro não permitido. Use PDF, JPG ou PNG.',
            'mime' => 'O conteúdo do ficheiro não corresponde à extensão declarada.',
            'upload' => 'Erro ao guardar o ficheiro. Tente novamente.',
        ];
        if (isset($_GET['error']) && isset($uploadErrors[$_GET['error']])): ?>
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4" role="alert">
                <ion-icon name="alert-circle-outline" class="me-2 fs-5"></ion-icon>
                <strong>Erro no Upload:</strong> <?= $uploadErrors[$_GET['error']] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4" role="alert">
                <ion-icon name="checkmark-circle-outline" class="me-2 fs-5"></ion-icon>
                <strong>Comprovativo enviado!</strong> Aguarda validação pela secretaria.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Header Profile & Global Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <div>
                <?php
                $hora = (int) date('H');
                if ($hora >= 5 && $hora < 12) {
                    $saudacao = 'Bom dia';
                } elseif ($hora >= 12 && $hora < 19) {
                    $saudacao = 'Boa tarde';
                } else {
                    $saudacao = 'Boa noite';
                }
                $primeiro_nome = htmlspecialchars(explode(' ', is_array($data['estudante']) ? (string) $data['estudante']['nome_completo'] : (string) ($_SESSION['user_name'] ?? 'Aluno'))[0]);
                ?>
                <h2 class="fw-bold text-dark mb-1"><?= $saudacao ?>, <?= $primeiro_nome ?> 👋</h2>
                <p class="text-muted mb-0 small">
                    Turma <?= $this->e($data['estudante']['turma_codigo'] ?? 'S/ Turma') ?> •
                    <?= $this->e($data['estudante']['ano_curso_id'] ?? 'Ano') ?>º Ano Académico
                </p>
            </div>
            <div class="d-flex gap-3 align-items-center">
                <!-- Controle do Menu (Visível Apenas em Desktop, substituindo Toggle Mobile que é Absoluto) -->

                <div class="position-relative d-none d-sm-block" style="cursor: pointer;"
                    onclick="document.getElementById('tab-comunicados').click()">
                    <div class="bg-white p-2 text-dark rounded-circle shadow-sm border border-success border-opacity-25 d-flex align-items-center justify-content-center"
                        style="width:42px;height:42px; transition: 0.3s;" onmouseover="this.style.background='#f8fafc'"
                        onmouseout="this.style.background='#fff'">
                        <ion-icon name="notifications-outline" class="fs-5"></ion-icon>
                    </div>
                    <?php if (!empty($data['unread_count'])): ?>
                        <span
                            class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger border border-white"
                            style="width:20px; height:20px; display:flex; align-items:center; justify-content:center; font-size:10px;"><?= (string) $data['unread_count'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="dropdown">
                    <div class="d-flex align-items-center gap-2 border px-3 py-2 rounded-pill bg-white shadow-sm dropdown-toggle" data-bs-toggle="dropdown" style="cursor: pointer;">
                        <div
                            style="width: 32px; height: 32px; border-radius: 50%; overflow: hidden; border: 2px solid var(--ghs-primary);">
                            <img src="<?= (is_array($data['estudante']) && !empty($data['estudante']['foto_perfil'])) ? URL_ROOT . '/' . $data['estudante']['foto_perfil'] : URL_ROOT . '/img/user-default.png' ?>"
                                alt="Perfil" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="d-none d-sm-block">
                            <div class="fw-bold small text-dark lh-1" style="margin-bottom:2px;"><?= $primeiro_nome ?></div>
                            <div class="text-muted" style="font-size: 10px; line-height: 1;">Estudante Ativo</div>
                        </div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px; margin-top: 10px; min-width: 200px;">
                        <li><a class="dropdown-item fw-bold text-dark py-2" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal"><ion-icon name="key-outline" class="me-2 fs-5 align-middle"></ion-icon> Alterar Senha</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item fw-bold text-danger py-2" href="<?= URL_ROOT ?>/auth/logout"><ion-icon name="log-out-outline" class="me-2 fs-5 align-middle"></ion-icon> Terminar Sessão</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Flash Info (certificado não disponível) -->
        <?php if (!empty($_SESSION['flash_info'])): ?>
            <div class="alert alert-info alert-dismissible fade show shadow-sm border-0 rounded-3 mb-4" role="alert">
                <ion-icon name="information-circle-outline" class="me-2"></ion-icon>
                <?= htmlspecialchars($_SESSION['flash_info']) ?>
                <?php unset($_SESSION['flash_info']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        </div>

        <!-- Tab Panes Content -->
        <div class="tab-content" id="v-pills-tabContent">

            <!-- Dashboard Home -->
            <div class="tab-pane fade show active" id="pane-home" role="tabpanel">

                <!-- 📢 ALERTAS DE CONVOCATÓRIA (Mediação) -->
                <?php if (!empty($data['convocatorias'])): ?>
                    <?php foreach ($data['convocatorias'] as $conv): ?>
                        <div class="alert alert-danger shadow-sm border-0 border-start border-4 border-danger rounded-4 mb-4 p-4" role="alert">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="bg-danger bg-opacity-10 p-3 rounded-circle text-danger">
                                    <ion-icon name="calendar" class="fs-2"></ion-icon>
                                </div>
                                <div>
                                    <h4 class="fw-bold mb-1 text-danger">Convocatória de Mediação Presencial</h4>
                                    <p class="mb-0 text-muted">A sua contestação da disciplina <strong><?= htmlspecialchars($conv['disciplina'] ?? 'N/A') ?></strong> foi escalada para mediação presencial.</p>
                                </div>
                            </div>
                            <div class="row g-3 bg-white bg-opacity-50 p-3 rounded-4 border">
                                <div class="col-md-3">
                                    <div class="small fw-bold text-muted text-uppercase mb-1">Data Agendada</div>
                                    <div class="fw-bold fs-5 text-dark"><?= !empty($conv['data_reuniao']) ? date('d/m/Y', strtotime($conv['data_reuniao'])) : 'N/A' ?></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small fw-bold text-muted text-uppercase mb-1">Hora</div>
                                    <div class="fw-bold fs-5 text-dark"><?= !empty($conv['hora_reuniao']) ? substr($conv['hora_reuniao'], 0, 5) : 'N/A' ?></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small fw-bold text-muted text-uppercase mb-1">Local / Gabinete</div>
                                    <div class="fw-bold fs-5 text-dark"><?= htmlspecialchars($conv['local_reuniao'] ?? 'N/A') ?></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="small fw-bold text-muted text-uppercase mb-1">Referência</div>
                                    <div class="badge bg-danger rounded-pill px-3">OBRIGATÓRIO</div>
                                </div>
                                <div class="col-12 mt-3 pt-3 border-top">
                                    <small class="fw-bold text-danger d-block mb-1">Motivo da Convocação:</small>
                                    <p class="mb-0 small text-muted">"<?= nl2br(htmlspecialchars($conv['motivo_convocacao'] ?? 'Motivo não especificado.')) ?>"</p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
<!-- 🏆 CERTIFICADOS DE MÉRITO EMITIDOS OFICIALMENTE -->
                <?php if (!empty($data['certificados_emitidos'])): ?>
                    <div class="row g-3 mb-4">
                        <?php foreach ($data['certificados_emitidos'] as $idx => $cert): ?>
                            <?php
                            $isFst = $cert['posicao'] === '1';
                            $semLabel = $cert['semestre'] === '1' ? '1º Semestre' : '2º Semestre';
                            $anoLabel = $cert['ano_letivo'];
                            $gradStart = $isFst ? '#F59E0B' : '#10B981';
                            $gradEnd = $isFst ? '#EF4444' : '#3B82F6';
                            $shadowC = $isFst ? 'rgba(245,158,11,0.3)' : 'rgba(16,185,129,0.25)';
                            $emoji = $isFst ? '🥇' : '🥈';
                            $titulo = $isFst ? 'MELHOR ALUNO — 1º LUGAR' : 'SEGUNDO MELHOR ALUNO — 2º LUGAR';
                            ?>
                            <div class="col-12">
                                <div
                                    style="background: linear-gradient(135deg, <?= $gradStart ?> 0%, <?= $gradEnd ?> 100%); border-radius: 20px; padding: 20px 28px; display: flex; align-items: center; gap: 20px; box-shadow: 0 12px 30px <?= $shadowC ?>; position: relative; overflow: hidden;">
                                    <div
                                        style="position:absolute; right:-20px; top:-20px; font-size:8rem; opacity:0.1; transform:rotate(15deg); color:white;">
                                        <ion-icon name="trophy"></ion-icon>
                                    </div>
                                    <span
                                        style="font-size:2.8rem; filter:drop-shadow(0 4px 8px rgba(0,0,0,0.2));"><?= $emoji ?></span>
                                    <div style="flex-grow:1; position:relative; z-index:1;">
                                        <h5
                                            style="color:white; margin:0; font-weight:800; font-size:1rem; letter-spacing:-0.5px;">
                                            <?= $titulo ?></h5>
                                        <p style="color:rgba(255,255,255,0.9); margin:0; font-size:0.85rem;">
                                            <?= $semLabel ?> &bull; <?= $anoLabel ?> &bull; Média:
                                            <strong><?= number_format((float) $cert['media'], 1) ?> valores</strong>
                                        </p>
                                    </div>
                                    <a href="<?= URL_ROOT ?>/estudante/certificado?id=<?= $idx ?>" target="_blank"
                                        class="btn btn-light fw-bold rounded-pill px-4 shadow-sm flex-shrink-0"
                                        style="color: <?= $gradEnd ?>; white-space: nowrap;">
                                        <ion-icon name="document-text-outline" class="me-1"></ion-icon> Ver &amp; Imprimir
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <!-- Ocultação Estrita do Quadro de Mérito Concluída -->

                <?php if ($data['can_renew'] && $data['next_year']): ?>
                    <div class="card border-0 shadow-sm mb-4 bg-success text-white">
                        <div class="card-body d-flex justify-content-between align-items-center p-4">
                            <div>
                                <h4 class="fw-bold mb-1"><ion-icon name="ribbon-outline" class="me-2"></ion-icon>Parabéns!
                                    Estás pronto para o próximo nível.</h4>
                                <p class="mb-0 opacity-75">Transitou com sucesso para o
                                    <strong><?= $data['next_year']['nome'] ?></strong>. Realize a sua renovação simplificada
                                    agora.</p>
                            </div>
                            <button class="btn btn-light text-success fw-bold rounded-pill px-4" data-bs-toggle="modal"
                                data-bs-target="#modalRenovacao">
                                <ion-icon name="sync-outline" class="me-1"></ion-icon> Renovar Agora
                            </button>
                        </div>
                    </div>
                <?php elseif (isset($data['detailed_status'])): ?>
                    <?php if ($data['detailed_status']['status'] === 'Recurso'): ?>
                        <div class="card border-0 shadow-sm mb-4 bg-warning text-dark">
                            <div class="card-body d-flex justify-content-between align-items-center p-4">
                                <div>
                                    <h4 class="fw-bold mb-1"><ion-icon name="alert-circle-outline"
                                            class="me-2"></ion-icon>Atenção: Disciplinas em Recurso</h4>
                                    <p class="mb-0 opacity-75">Tens
                                        <strong><?= $data['detailed_status']['recurso_subjects'] ?></strong> disciplina(s) com
                                        nota entre 8 e 11. Deves realizar o exame de recurso.</p>
                                </div>
                                <button class="btn btn-dark fw-bold rounded-pill px-4" disabled>
                                    Aguardar Recurso
                                </button>
                            </div>
                        </div>
                    <?php elseif ($data['detailed_status']['status'] === 'Reprovado'): ?>
                        <div class="card border-0 shadow-sm mb-4 bg-danger text-white">
                            <div class="card-body d-flex justify-content-between align-items-center p-4">
                                <div>
                                    <h4 class="fw-bold mb-1"><ion-icon name="close-circle-outline"
                                            class="me-2"></ion-icon>Reprovação: Repetição de Ano</h4>
                                    <p class="mb-0 opacity-75">Tens disciplinas com nota insuficiente (6 ou menos). Podes
                                        renovar a matrícula para repetir este nível.</p>
                                </div>
                                <button class="btn btn-light text-danger fw-bold rounded-pill px-4" data-bs-toggle="modal"
                                    data-bs-target="#modalRenovacaoRepeticao">
                                    <ion-icon name="refresh-outline" class="me-1"></ion-icon> Repetir Nível
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="glass-card card border-0 h-100">
                            <div class="card-body p-4 text-center">
                                <div class="stat-icon bg-success bg-opacity-10 text-success mx-auto mb-3">
                                    <ion-icon name="analytics-outline"></ion-icon>
                                </div>
                                <h6 class="text-muted small fw-bold mb-2 opacity-75">Média Global</h6>
                                <h2 class="fw-bold mb-0 text-dark">
                                    <?= empty($data['media_geral']) ? 'N/A' : number_format((float) $data['media_geral'], 1) ?>
                                </h2>
                                <div class="progress mt-3" style="height: 6px; border-radius: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: <?= (isset($data['media_geral']) ? ($data['media_geral'] / 20 * 100) : 0) ?>%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="glass-card card border-0 h-100">
                            <div class="card-body p-4 text-center">
                                <div class="stat-icon bg-primary bg-opacity-10 text-primary mx-auto mb-3">
                                    <ion-icon name="flash-outline"></ion-icon>
                                </div>
                                <h6 class="text-muted small fw-bold mb-2 opacity-75">Desempenho AC</h6>
                                <h2 class="fw-bold mb-0 text-primary"><?= $data['desempenho_ac'] ?? 0 ?>%</h2>
                                <div class="progress mt-3" style="height: 6px; border-radius: 10px;">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: <?= $data['desempenho_ac'] ?? 0 ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="glass-card card border-0 h-100">
                            <div class="card-body p-4 text-center">
                                <div class="stat-icon bg-warning bg-opacity-10 text-warning mx-auto mb-3">
                                    <ion-icon name="calendar-clear-outline"></ion-icon>
                                </div>
                                <h6 class="text-muted small fw-bold mb-2 opacity-75">Faltas Registadas</h6>
                                <h2 class="fw-bold mb-0 text-dark"><?= $data['faltas_count'] ?? 0 ?></h2>
                                <small class="text-muted mt-2 d-block">Aulas perdidas</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="glass-card card border-0 h-100">
                            <div class="card-body p-4 text-center">
                                <div
                                    class="stat-icon <?= $data['smart_delinquency']['is_delinquent'] ? 'bg-danger' : 'bg-info' ?> bg-opacity-10 <?= $data['smart_delinquency']['is_delinquent'] ? 'text-danger' : 'text-info' ?> mx-auto mb-3">
                                    <ion-icon name="wallet-outline"></ion-icon>
                                </div>
                                <h6 class="text-muted small fw-bold mb-2 opacity-75">Propina</h6>
                                <?php if ($data['smart_delinquency']['is_delinquent']): ?>
                                    <div
                                        class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill mb-1 p-2 px-3 fw-bold">
                                        IRREGULAR</div>
                                    <div class="text-danger fw-bold" style="font-size: 0.8rem;">
                                        <?= $data['smart_delinquency']['missing_months'] ?> meses em falta</div>
                                <?php elseif (!empty($data['pendencias_count'])): ?>
                                    <div
                                        class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill mb-1 p-2 px-3 fw-bold">
                                        PENDENTE</div>
                                <?php else: ?>
                                    <div
                                        class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill mb-1 p-2 px-3 fw-bold">
                                        REGULAR</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="glass-card card border-0 h-100">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3 d-flex align-items-center gap-2 text-dark"><ion-icon
                                        name="megaphone-outline" class="text-warning"></ion-icon> Avisos Recentes</h5>
                                <?php if (empty($data['comunicados'])): ?>
                                    <div class="p-4 text-center opacity-50">
                                        <ion-icon name="notifications-off-outline" class="fs-1 mb-2"></ion-icon>
                                        <p class="small mb-0">Sem avisos recentes.</p>
                                    </div>
                                <?php else: ?>
                                    <?php $count = 0;
                                    foreach ($data['comunicados'] as $c):
                                        if ($count++ >= 3)
                                            break; ?>
                                        <div class="p-3 rounded-4 mb-2 border-start border-4 border-<?= ($c['tipo'] == 'Geral') ? 'primary' : 'warning' ?>"
                                            style="background: rgba(0,0,0,0.02);">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-primary small rounded-pill border border-primary border-opacity-10 py-1 px-3">
                                                    <?= htmlspecialchars((string) ($c['titulo'] ?? 'Comunicado')) ?>
                                                </span>
                                                <?php if ($c['lido']): ?>
                                                    <button onclick="removerComunicado(<?= $c['id'] ?>)"
                                                        class="btn btn-sm btn-link text-danger p-0" title="Eliminar da lista">
                                                        <ion-icon name="trash-outline"></ion-icon>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                            <strong
                                                class="text-dark small d-block"><?= htmlspecialchars((string) ($c['titulo'] ?? 'Aviso')) ?></strong>
                                            <div class="text-muted mt-1" style="font-size: 0.8rem;">
                                                <?= htmlspecialchars(mb_strimwidth((string) ($c['conteudo'] ?? ''), 0, 100, "...")) ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="glass-card card border-0 h-100">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3 d-flex align-items-center gap-2 text-dark"><ion-icon
                                        name="time-outline" class="text-primary"></ion-icon> Próximas Aulas</h5>
                                <div class="list-group list-group-flush border-0">
                                    <?php
                                    $hoje = ['Monday' => 'Segunda', 'Tuesday' => 'Terça', 'Wednesday' => 'Quarta', 'Thursday' => 'Quinta', 'Friday' => 'Sexta', 'Saturday' => 'Sábado', 'Sunday' => 'Domingo'][date('l')];
                                    $agora = date('H:i');
                                    $temAulasHoje = false;
                                    if (!empty($data['horario'])):
                                        foreach ($data['horario'] as $h):
                                            if ($h['dia_semana'] == $hoje && substr($h['hora_inicio'], 0, 5) >= $agora):
                                                $temAulasHoje = true;
                                                ?>
                                                <div
                                                    class="list-group-item d-flex justify-content-between align-items-center bg-transparent border-0 border-bottom px-0 py-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div
                                                            class="bg-primary bg-opacity-10 text-primary p-2 rounded-3 fw-bold small">
                                                            <?= substr($h['hora_inicio'], 0, 5) ?></div>
                                                        <div class="fw-bold text-dark small">
                                                            <?= htmlspecialchars((string) ($h['disciplina_nome'] ?? 'Disciplina')) ?>
                                                        </div>
                                                    </div>
                                                    <span
                                                        class="badge bg-light text-muted border fw-bold px-3 py-2 rounded-pill">Sala
                                                        <?= htmlspecialchars((string) ($h['sala'] ?? '-')) ?></span>
                                                </div>
                                            <?php
                                            endif;
                                        endforeach;
                                    endif;
                                    if (!$temAulasHoje): ?>
                                        <div class="p-4 text-center opacity-50">
                                            <ion-icon name="cafe-outline" class="fs-1 mb-2"></ion-icon>
                                            <p class="small mb-0">Sem aulas pendentes para hoje.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Horário & Calendário -->
            <div class="tab-pane fade" id="pane-horario" role="tabpanel">
                <div class="d-flex justify-content-between align-items-end mb-4">
                    <div>
                        <h4 class="fw-bold mb-1">Grade Horária Semanal -
                            <?= $this->e($data['estudante']['turma_codigo'] ?? 'S/ Turma') ?></h4>
                        <p class="text-muted small">Consulte os horários oficiais para o
                            <?= $this->e($data['estudante']['ano_curso_id'] ?? '1') ?>º Ano.</p>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-body p-3">
                        <?php
                        $gridData = $data['gridData'] ?? [];
                        $turmaInfo = [
                            'codigo' => $data['estudante']['turma_codigo'] ?? '',
                            'turno' => $data['estudante']['turno'] ?? '',
                            'nivel' => ($data['estudante']['ano_curso_id'] ?? '1') . 'º ANO'
                        ];
                        if (file_exists(__DIR__ . '/../shared/horario_grid.php')) {
                            include __DIR__ . '/../shared/horario_grid.php';
                        }
                        ?>
                    </div>
                </div>

                <hr class="my-5">

                <!-- Calendar Section -->
                <div class="d-flex justify-content-between align-items-end mb-3">
                    <div>
                        <h4 class="fw-bold mb-2">Calendário Escolar Interativo</h4>
                        <div class="d-flex flex-wrap gap-3 mt-2">
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:12px;height:12px;background:#f59e0b;border-radius:2px;"></div><span
                                    class="extra-small text-muted fw-bold">Ano Letivo / Férias</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:12px;height:12px;background:#ef4444;border-radius:2px;"></div><span
                                    class="extra-small text-muted fw-bold">Exames</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:12px;height:12px;background:#1e3a8a;border-radius:2px;"></div><span
                                    class="extra-small text-muted fw-bold">Feriados</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:12px;height:12px;background:#60a5fa;border-radius:2px;"></div><span
                                    class="extra-small text-muted fw-bold">Recurso</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:12px;height:12px;background:#14532d;border-radius:2px;"></div><span
                                    class="extra-small text-muted fw-bold">Semana Transitória</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:12px;height:12px;background:#4ade80;border-radius:2px;"></div><span
                                    class="extra-small text-muted fw-bold">Palestras</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="calendar" style="min-height:700px;background:white;padding:20px;border-radius:12px;"
                    class="shadow-sm mt-4"></div>
            </div>

            <!-- Minhas Notas e AC -->
            <div class="tab-pane fade" id="pane-notas" role="tabpanel">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4">Avaliação Contínua Atual</h4>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle table-bordered text-center datatable-simple">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-start">Disciplina</th>
                                        <th>TPC (2)</th>
                                        <th>AP (3)</th>
                                        <th>TPI (5)</th>
                                        <th>CE (10)</th>
                                        <th class="text-white bg-success">Σ AC (20)</th>
                                        <th>Média Exame</th>
                                        <th class="text-end">Acção / Feedback</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($data['notas'])): ?>
                                    <?php foreach ($data['notas'] as $n): ?>
                                        <tr>
                                            <td class="text-start fw-bold">
                                                <?= htmlspecialchars($n['disciplina']) ?>
                                                <div class="mt-1">
                                                    <?php if ($n['bloqueado_admin']): ?>
                                                        <span
                                                            class="badge bg-danger text-white border border-danger small"><ion-icon
                                                                name="alert-circle"></ion-icon> Bloqueio Anti-Fraude: Mediação Admin
                                                            Pendente</span>
                                                    <?php elseif ($n['feedback_status'] == 'Concordado' || $n['feedback_status'] == 'Resolvido'): ?>
                                                        <span
                                                            class="badge bg-success-subtle text-success border border-success border-opacity-25 small"><ion-icon
                                                                name="checkmark-circle"></ion-icon> Nota
                                                            Finalizada/Concordada</span>
                                                    <?php elseif ($n['feedback_status'] == 'Reclamado'): ?>
                                                        <span
                                                            class="badge bg-danger-subtle text-danger border border-danger border-opacity-25 small"
                                                            title="<?= htmlspecialchars($n['feedback_comentario'] ?? '') ?>"><ion-icon
                                                                name="warning"></ion-icon> Aguarda Revisão do Professor
                                                            (<?= $n['contador_reclamacoes'] ?>)</span>
                                                    <?php elseif ($n['feedback_status'] == 'Respondido'): ?>
                                                        <div>
                                                            <span
                                                                class="badge bg-info-subtle text-info border border-info border-opacity-25 small"><ion-icon
                                                                    name="chatbubble-outline"></ion-icon> Resposta do Professor
                                                                Disponível</span>
                                                            <?php if (!empty($n['resposta_professor'])): ?>
                                                                <div
                                                                    class="mt-1 p-2 bg-light rounded small text-muted border-start border-3 border-info">
                                                                    <ion-icon name="return-down-forward-outline"
                                                                        class="me-1"></ion-icon><?= htmlspecialchars($n['resposta_professor']) ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php elseif ($n['feedback_status'] == 'Impasse'): ?>
                                                        <span
                                                            class="badge bg-danger text-white border border-danger small"><ion-icon
                                                                name="alert-circle"></ion-icon> Em Mediação pela Adm. Acência</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-light text-muted border small">Aguarda Validação</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td><?= isset($n['notas'][1]) && $n['notas'][1] !== null && $n['notas'][1] !== '' ? number_format((float)$n['notas'][1], 1) : '-' ?></td>
                                            <td><?= isset($n['notas'][2]) && $n['notas'][2] !== null && $n['notas'][2] !== '' ? number_format((float)$n['notas'][2], 1) : '-' ?></td>
                                            <td><?= isset($n['notas'][3]) && $n['notas'][3] !== null && $n['notas'][3] !== '' ? number_format((float)$n['notas'][3], 1) : '-' ?></td>
                                            <td><?= isset($n['notas'][4]) && $n['notas'][4] !== null && $n['notas'][4] !== '' ? number_format((float)$n['notas'][4], 1) : '-' ?></td>
                                            <td class="fw-bold text-primary fs-5"><?= number_format((float)($n['total_ac'] ?? 0), 1) ?></td>
                                            <td class="bg-light-subtle">
                                                <div class="fw-bold"><?= $n['notas'][5] !== null ? number_format((float)$n['notas'][5], 1) : '-' ?></div>
                                                <small class="text-muted d-block" style="font-size: 0.7rem;">Média:
                                                    <?= $n['nota_final'] ? number_format($n['nota_final'], 1) : '-' ?></small>
                                            </td>
                                            <td class="text-end">
                                                <?php if ($n['bloqueado_admin']): ?>
                                                    <small class="text-danger fw-bold"><ion-icon name="lock-closed"></ion-icon> Pendente Admin</small>
                                                <?php elseif ($n['feedback_status'] == 'Concordado' || $n['feedback_status'] == 'Encerrado'): ?>
                                                    <div class="text-success d-flex align-items-center gap-1 justify-content-end">
                                                        <ion-icon name="checkmark-done-circle" class="fs-5"></ion-icon>
                                                        <small class="fw-bold">Nota Validada</small>
                                                    </div>
                                                <?php elseif ($n['feedback_status'] == 'Reclamado'): ?>
                                                    <small class="text-danger fw-bold"><ion-icon name="time"></ion-icon> Aguarda Prof.</small>
                                                <?php elseif ($n['feedback_status'] == 'Respondido' || $n['feedback_status'] == 'Resolvido'): ?>
                                                    <div class="d-grid gap-1">
                                                        <button
                                                            onclick="aceitarNota(<?= $n['turma_id'] ?>, <?= $n['disciplina_id'] ?>)"
                                                            class="btn btn-sm btn-success fw-bold py-1 shadow-sm"><ion-icon
                                                                name="checkmark-circle"></ion-icon> Confirmar</button>
                                                        <button
                                                            onclick="abrirModalReacao(<?= $n['turma_id'] ?>, <?= $n['disciplina_id'] ?>)"
                                                            class="btn btn-sm btn-outline-danger fw-bold py-1 px-1" style="font-size: 0.70rem;">Discordar (Escalar)</button>
                                                    </div>
                                                <?php elseif ($n['feedback_status'] == 'Impasse' || $n['feedback_status'] == 'Em_Mediacao' || $n['feedback_status'] == 'Aguardando_Comparecimento'): ?>
                                                    <?php if ($n['feedback_status'] == 'Aguardando_Comparecimento'): ?>
                                                        <div class="d-grid gap-1">
                                                            <div class="badge bg-danger p-2 shadow-sm mb-1">
                                                                <ion-icon name="calendar-outline" class="me-1"></ion-icon> OBRIGATÓRIO: MEDIAÇÃO
                                                            </div>
                                                            <div class="small text-danger fw-bold text-center">
                                                                <?= date('d/m/Y', strtotime($n['data_reuniao'])) ?> - <?= substr($n['hora_reuniao'], 0, 5) ?><br>
                                                                Local: <?= htmlspecialchars($n['local_reuniao']) ?>
                                                            </div>
                                                        </div>
                                                    <?php else: ?>
                                                        <ion-icon name="lock-closed" class="text-danger"></ion-icon> <small class="text-danger fw-bold">Sob Mediação</small>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <!-- Estado Inicial: Aguardando Ação do Aluno -->
                                                    <div class="d-grid gap-1">
                                                        <button
                                                            onclick="aceitarNota(<?= $n['turma_id'] ?>, <?= $n['disciplina_id'] ?>)"
                                                            class="btn btn-sm btn-success fw-bold py-1 shadow-sm">
                                                            <ion-icon name="checkmark-circle"></ion-icon> Confirmar Nota
                                                        </button>
                                                        <button
                                                            onclick="abrirModalContestacao(<?= $n['turma_id'] ?>, <?= $n['disciplina_id'] ?>)"
                                                            class="btn btn-sm btn-outline-danger fw-bold py-1" style="font-size: 0.75rem;">
                                                            <ion-icon name="hand-right"></ion-icon> Contestar
                                                        </button>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">Ainda sem avaliações carregadas.</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2 small text-muted">
                            <ion-icon name="information-circle-outline"></ion-icon>
                            A <strong>Soma AC (Avaliação Contínua)</strong> é a base para o acesso ao exame. Para
                            aprovação final, deve validar as suas notas acima.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Histórico Académico com Exportação PDF -->
        <div class="tab-pane fade" id="pane-historico" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Histórico Académico Global</h4>
                    <p class="text-muted mb-4">Registo vitalício das disciplinas concluídas no currículo de Engenharia
                        Informática.</p>
                    <div class="table-responsive">
                        <table id="table-historico" class="table table-striped table-hover align-middle w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>Ano Referência</th>
                                    <th>Disciplina</th>
                                    <th>Média AC</th>
                                    <th>Nota Exame</th>
                                    <th>Nota Final</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data['historico_global'])): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <ion-icon name="journal-outline"
                                                style="font-size:2.5rem;opacity:0.2"></ion-icon>
                                            <p class="mt-2">Ainda não há registos académicos.</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($data['historico_global'] as $h): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($h['ano']) ?> / Sem <?= $h['semestre'] ?></td>
                                            <td class="fw-bold"><?= htmlspecialchars($h['disciplina']) ?></td>
                                            <td><?= number_format($h['total_ac'], 1) ?></td>
                                            <td><?= $h['notas'][5] !== null ? number_format($h['notas'][5], 1) : '-' ?></td>
                                            <td class="fw-bold text-primary fs-5">
                                                <?= $h['nota_final'] !== null ? number_format($h['nota_final'], 1) : '<span class="fs-6 text-muted">—</span>' ?>
                                            </td>
                                            <td>
                                                <?php if ($h['status'] === 'Aprovado'): ?>
                                                    <span
                                                        class="badge bg-success-subtle text-success border border-success border-opacity-25">Aprovado</span>
                                                <?php elseif ($h['status'] === 'Recurso'): ?>
                                                    <span
                                                        class="badge bg-warning-subtle text-warning border border-warning border-opacity-25">Recurso</span>
                                                <?php elseif ($h['status'] === 'Reprovado'): ?>
                                                    <span
                                                        class="badge bg-danger-subtle text-danger border border-danger border-opacity-25">Reprovado</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary-subtle text-secondary border">Em Curso</span>
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

        <!-- Tab: Mérito & Diplomas — removido do portal aluno (apenas Admin/Secretaria) -->

        <!-- Materiais Didáticos -->
        <div class="tab-pane fade" id="pane-materiais" role="tabpanel">
            <h4 class="fw-bold mb-4">Conteúdos e Materiais Didáticos</h4>
            <div class="row g-3">
                <?php if (empty($data['materiais'])): ?>
                    <div class="col-12 text-center py-5 text-muted">
                        <ion-icon name="folder-open-outline" style="font-size: 3rem; opacity: 0.2;"></ion-icon>
                        <p class="mt-2">Ainda não foram partilhados materiais com a tua turma.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($data['materiais'] as $m): ?>
                        <div class="col-md-6">
                            <div
                                class="card border-0 shadow-sm border-start border-4 border-<?= ($m['tipo_ficheiro'] == 'pdf') ? 'danger' : 'primary' ?>">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="fw-bold mb-1"><?= htmlspecialchars($m['titulo']) ?></h6>
                                        <small class="text-muted"><?= htmlspecialchars($m['disciplina_nome']) ?> •
                                            <?= htmlspecialchars($m['professor_nome']) ?> •
                                            <?= strtoupper($m['tipo_ficheiro']) ?></small>
                                    </div>
                                    <a href="<?= URL_ROOT ?>/<?= $m['caminho_ficheiro'] ?>" target="_blank"
                                        class="btn btn-sm btn-light rounded-circle p-2">
                                        <ion-icon name="download" class="fs-4 text-dark"></ion-icon>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sumários de Aula -->
        <div class="tab-pane fade" id="pane-sumarios" role="tabpanel">
            <div class="glass-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Sumários de Aula</h4>
                        <p class="text-muted small mb-0">Confira o que foi lecionado em cada aula pelos seus
                            professores.</p>
                    </div>
                    <div class="badge bg-primary bg-opacity-10 text-primary p-2 px-3 rounded-pill">
                        <ion-icon name="reader" class="me-1"></ion-icon> <?= count($data['sumarios'] ?? []) ?> Registos
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-premium datatable-simple">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Disciplina</th>
                                <th>Professor</th>
                                <th>Tempo</th>
                                <th>Conteúdo Lecionado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($data['sumarios'])): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Ainda não há sumários registados
                                        para as suas turmas.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($data['sumarios'] as $s): ?>
                                    <tr>
                                        <td class="fw-bold"><?= date('d/m/Y', strtotime($s['data'])) ?></td>
                                        <td>
                                            <span
                                                class="badge bg-secondary bg-opacity-10 text-secondary"><?= $this->e($s['disciplina_nome']) ?></span>
                                        </td>
                                        <td><?= $this->e($s['professor_nome']) ?></td>
                                        <td><span class="text-muted small"><?= $this->e($s['tempo']) ?></span></td>
                                        <td style="max-width: 400px;">
                                            <div class="text-dark small"><?= nl2br($this->e($s['conteudo'])) ?></div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagamentos -->
        <div class="tab-pane fade" id="pane-financeiro" role="tabpanel">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-4">
                        <h4 class="fw-bold">Gestão Financeira</h4>
                        <div>
                            <a href="<?= URL_ROOT ?>/estudante/downloadReciboMatricula" target="_blank" class="btn btn-outline-primary mb-2 mb-md-0 me-md-2 shadow-sm">
                                <ion-icon name="print-outline"></ion-icon> Imprimir Recibo 10º Mês (e Inscrição)
                            </a>
                            <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalPagamento">
                                <ion-icon name="cash-outline"></ion-icon> Pagar Mensalidade
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle datatable-simple">
                            <thead class="table-light">
                                <tr>
                                    <th>Referência</th>
                                    <th>Descrição</th>
                                    <th>Valor (XOF)</th>
                                    <th>Vencimento</th>
                                    <th>Status</th>
                                    <th>Recibo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data['pagamentos'])): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-3">Sem registo de pagamentos.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($data['pagamentos'] as $p): ?>
                                        <tr>
                                            <td class="fw-bold text-muted">#<?= str_pad($p['id'], 4, '0', STR_PAD_LEFT) ?></td>
                                            <td><?= $this->e($p['descricao']) ?></td>
                                            <td class="fw-bold"><?= number_format($p['valor'], 0, ',', '.') ?></td>
                                            <td><?= date('d/m/Y', strtotime($p['data_vencimento'] ?? $p['data_criacao'])) ?></td>
                                            <td>
                                                <?php if ($p['status'] === 'Pago'): ?>
                                                    <span class="badge bg-success">Pago</span>
                                                <?php elseif ($p['status'] === 'Pendente'): ?>
                                                    <span class="badge bg-warning text-dark">Pendente Valid.</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger"><?= $p['status'] ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($p['status'] === 'Pago'): ?>
                                                    <a href="<?= URL_ROOT ?>/estudante/downloadRecibo/<?= $p['id'] ?>" target="_blank"
                                                        class="btn btn-sm btn-outline-secondary"><ion-icon
                                                            name="document-text"></ion-icon> Baixar</a>
                                                <?php else: ?>
                                                    <button class="btn btn-sm btn-light" disabled>Aguarde</button>
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

        <!-- Mural de Avisos (Comunicados) -->
        <div class="tab-pane fade" id="pane-comunicados" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h4 class="fw-bold mb-0 d-flex align-items-center gap-2">
                        <ion-icon name="megaphone-outline" class="text-primary"></ion-icon>
                        Mural de Avisos & Comunicados
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <?php if (empty($data['comunicados'])): ?>
                            <div class="col-12 text-center py-5">
                                <ion-icon name="mail-unread-outline" style="font-size: 4rem; color: #CBD5E1;"></ion-icon>
                                <p class="text-muted mt-3">Não há novos avisos no seu mural.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach ($data['comunicados'] as $c): ?>
                                <div class="col-md-6">
                                    <div
                                        class="card h-100 border-0 shadow-sm bg-light rounded-4 overflow-hidden position-relative">
                                        <?php if ($c['lido'] == 0): ?>
                                            <div class="position-absolute top-0 end-0 p-3">
                                                <span class="badge bg-danger rounded-pill shadow-sm">Novo</span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                                    <ion-icon name="notifications" class="text-primary fs-4"></ion-icon>
                                                </div>
                                                <div>
                                                    <h6 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($c['titulo']) ?>
                                                    </h6>
                                                    <small
                                                        class="text-muted"><?= date('d/m/Y', strtotime($c['data_publicacao'])) ?>
                                                        • Por: <?= htmlspecialchars($c['autor_nome']) ?></small>
                                                </div>
                                            </div>
                                            <p class="text-muted small mb-4" style="line-height: 1.6;">
                                                <?= nl2br($this->e($c['conteudo'])) ?>
                                            </p>
                                            <?php if ($c['lido'] == 0): ?>
                                                <div class="text-end">
                                                    <button onclick="marcarComoLido(<?= $c['id'] ?>, this)"
                                                        class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                                                        <ion-icon name="checkmark-done-outline" class="me-1"></ion-icon> Marcar como
                                                        Lido
                                                    </button>
                                                </div>
                                            <?php else: ?>
                                                <div class="text-end">
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
        </div> <!-- End .ghs-container -->
    </div>

    <!-- Scripts -->
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<?php if(isset($_SESSION['must_change_password']) && $_SESSION['must_change_password'] === true): ?>
<script>
    $(document).ready(function() {
        var pwModal = new bootstrap.Modal(document.getElementById('changePasswordModal'), {
            keyboard: false,
            backdrop: 'static'
        });
        pwModal.show();
        // Hide the "Mais Tarde" button since it's mandatory
        $('#changePasswordModal .btn-secondary').hide();
        $('#changePasswordModal .btn-close').hide();
    });
</script>
<?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables & Export Plugins -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

    <!-- Modal Pagamento -->
    <div class="modal fade" id="modalPagamento" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="fw-bold mb-0"><ion-icon name="cash-outline"
                                class="text-success me-2"></ion-icon>Registar Pagamento de Mensalidade</h5>
                        <p class="text-muted small mb-0">Submeta o comprovativo para validação pela secretaria.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">
                    <div class="alert alert-info border-0 rounded-3 small">
                        <ion-icon name="information-circle-outline" class="me-1"></ion-icon>
                        O seu pagamento será validado pela secretaria em até 48h úteis.
                    </div>
                    <form id="formPagamento" action="<?= URL_ROOT ?>/estudante/registarPagamento" method="post"
                        enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">



                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Referência / Mês</label>
                            <input type="text" name="referencia" class="form-control bg-light"
                                placeholder="Ex: Mensalidade de Março de 2026" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Valor Pago (XOF)</label>
                            <input type="number" name="valor" class="form-control bg-light" placeholder="31500"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Comprovativo (imagem ou PDF)</label>
                            <input type="file" name="comprovativo" class="form-control" accept="image/*,.pdf" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Observações (opcional)</label>
                            <textarea name="observacoes" class="form-control bg-light" rows="2"
                                placeholder="Ex: Pago via transferência bancária..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold">
                            <ion-icon name="cloud-upload-outline" class="me-1"></ion-icon> Enviar Comprovativo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Renovação de Matrícula -->
    <div class="modal fade" id="modalRenovacao" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="fw-bold mb-0 text-success"><ion-icon name="sync-outline"
                                class="me-2"></ion-icon>Renovação de Matrícula Simplificada</h5>
                        <p class="text-muted small mb-0">Você transitou de ano! Confirme sua vaga para o próximo nível.
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">
                    <div class="alert alert-success border-0 rounded-3 small py-2 mb-3">
                        <ion-icon name="checkmark-circle-outline" class="me-1"></ion-icon>
                        <strong>Nível Seguinte:</strong> <?= $data['next_year']['nome'] ?? 'Próximo Ano' ?>
                    </div>

                    <form action="<?= URL_ROOT ?>/estudante/renewEnrollment" method="post"
                        enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">



                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Turno de Preferência</label>
                            <select name="turno" class="form-select bg-light border-0">
                                <option value="Manhã" <?= ($data['estudante']['turno'] ?? '') == 'Manhã' ? 'selected' : '' ?>>Manhã</option>
                                <option value="Tarde" <?= ($data['estudante']['turno'] ?? '') == 'Tarde' ? 'selected' : '' ?>>Tarde</option>
                                <option value="Noite" <?= ($data['estudante']['turno'] ?? '') == 'Noite' ? 'selected' : '' ?>>Noite</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Comprovativo de Taxa de Renovação
                                (XOF)</label>
                            <input type="file" name="comprovativo" class="form-control" accept="image/*,.pdf" required>
                            <div class="form-text small">Não é necessário reenviar BI ou Certificados para renovação.
                            </div>
                        </div>
                        <div class="alert bg-light border-0 small mt-2">
                            <ion-icon name="information-circle-outline" class="text-info me-1"></ion-icon>
                            Ao clicar em "Confirmar Renovação", os seus dados serão enviados à secretaria para validação
                            final.
                        </div>
                        <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold py-2 mt-2">
                            Confirmar Renovação para o <?= $data['next_year']['nome'] ?? 'Próximo Ano' ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Renovação de Matrícula (Repetição de Ano) -->
    <div class="modal fade" id="modalRenovacaoRepeticao" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0 pb-0">
                    <div>
                        <h5 class="fw-bold mb-0 text-danger"><ion-icon name="refresh-outline"
                                class="me-2"></ion-icon>Renovação por Repetição</h5>
                        <p class="text-muted small mb-0">Através deste portal pode renovar para o mesmo nível.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-3">
                    <div class="alert alert-danger border-0 rounded-3 small py-2 mb-3">
                        <ion-icon name="information-circle-outline" class="me-1"></ion-icon>
                        <strong>Nível a Repetir:</strong> <?= $data['estudante']['nivel'] ?? 'N/A' ?>
                    </div>

                    <form action="<?= URL_ROOT ?>/estudante/renewEnrollment" method="post"
                        enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">



                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="is_repetition" value="1">

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Modalidade de Repetição</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="observacoes" id="modoA"
                                    value="Repetição: Apenas disciplinas falhadas" checked>
                                <label class="form-check-label small" for="modoA">
                                    Estudar apenas as disciplinas falhadas
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="observacoes" id="modoB"
                                    value="Repetição: Todas as disciplinas">
                                <label class="form-check-label small" for="modoB">
                                    Estudar todas as disciplinas do ano novamente
                                </label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Turno de Preferência</label>
                            <select name="turno" class="form-select bg-light border-0">
                                <option value="Manhã" <?= ($data['estudante']['turno'] ?? '') == 'Manhã' ? 'selected' : '' ?>>Manhã</option>
                                <option value="Tarde" <?= ($data['estudante']['turno'] ?? '') == 'Tarde' ? 'selected' : '' ?>>Tarde</option>
                                <option value="Noite" <?= ($data['estudante']['turno'] ?? '') == 'Noite' ? 'selected' : '' ?>>Noite</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Comprovativo de Taxa (XOF)</label>
                            <input type="file" name="comprovativo" class="form-control" accept="image/*,.pdf" required>
                        </div>

                        <button type="submit" class="btn btn-danger w-100 rounded-pill fw-bold py-2 mt-2">
                            Confirmar Renovação de Repetição
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Contestação -->
    <div class="modal fade" id="modalContestacao" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-danger text-white border-0 pb-3">
                    <h5 class="fw-bold mb-0">Iniciar Contestação Oficial</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">O seu pedido será encaminhado ao docente responsável. Fundamente bem a sua solicitação com base nos elementos de avaliação.</p>
                    <form id="formContestacao" onsubmit="enviarContestacao(event, this)">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="estudante_id" value="<?= $data['estudante']['id'] ?>">
                        <input type="hidden" name="turma_id" id="cont_turma_id">
                        <input type="hidden" name="disciplina_id" id="cont_disciplina_id">
                        <textarea name="justificativa" class="form-control bg-light mb-3 p-3 border-0 shadow-sm" rows="4"
                            placeholder="Ex: A minha nota do teste escrito não coincide com a correção apresentada..."
                            required></textarea>
                        <button type="submit" class="btn btn-danger py-2 w-100 rounded-pill fw-bold shadow">
                            Enviar Contestação
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Reação (Contra-Argumentação) -->
    <div class="modal fade" id="modalReacao" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-dark text-white border-0 pb-3">
                    <h5 class="fw-bold mb-0">Reagir à Resposta do Docente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning small border-0 mb-3 shadow-sm rounded-3">
                        <ion-icon name="warning"></ion-icon> <strong>Atenção:</strong> Ao contra-argumentar, o sistema detectará um <strong>IMPASSE</strong>, e o processo será alvo de uma mediação presencial onde a sua comparência será obrigatória.
                    </div>
                    <form id="formReacao" onsubmit="enviarReacao(event, this)">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="estudante_id" value="<?= $data['estudante']['id'] ?>">
                        <input type="hidden" name="turma_id" id="reac_turma_id">
                        <input type="hidden" name="disciplina_id" id="reac_disciplina_id">
                        <input type="hidden" name="acao" value="contra_argumentar">
                        <textarea name="contra_argumento" class="form-control bg-light mb-3 p-3 border-0 shadow-sm" rows="4"
                            placeholder="Apresente as suas provas sólidas para prosseguir com a mediação..."
                            required></textarea>
                        <button type="submit" class="btn btn-dark py-2 w-100 rounded-pill fw-bold shadow">
                            Escalar Processo para Mediação
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Alteração de Password Obrigatória -->
    <div class="modal fade" id="modalForcePassword" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="fw-bold mb-0"><ion-icon name="lock-closed-outline" class="me-2"></ion-icon>Segurança
                        Obrigatória</h5>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning border-0 small mb-4">
                        <ion-icon name="alert-circle-outline" class="me-1"></ion-icon>
                        Detectamos que está a usar uma password temporária ou padrão. Para proteger os seus dados
                        académicos e financeiros, <strong>deve escolher uma nova password robusta</strong> antes de
                        continuar.
                    </div>
                    <form action="<?= URL_ROOT ?>/estudante/changePassword" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">



                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nova Password (mín. 6 caracteres)</label>
                            <input type="password" name="new_password" class="form-control" placeholder="******"
                                required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Confirmar Nova Password</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="******"
                                required minlength="6">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-3 mt-2 shadow">
                            <ion-icon name="save-outline" class="me-1"></ion-icon> Validar e Atualizar Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // DataTables: initialize individually to prevent column-count mismatch errors
            $.fn.dataTable.ext.errMode = 'none';
            $('.datatable-simple').each(function () {
                try {
                    if (!$.fn.DataTable.isDataTable(this)) {
                        $(this).DataTable({
                            language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-PT.json' },
                            pageLength: 5, bLengthChange: false, info: false, retrieve: true
                        });
                    }
                } catch (e) { console.warn('DataTable init error:', e); }
            });

            // Historic Table with DataTables Export PDF/Excel
            if ($('#table-historico').length && !$.fn.DataTable.isDataTable('#table-historico')) {
                $('#table-historico').DataTable({
                    language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-PT.json' },
                    dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>rtip',
                    buttons: [
                        { extend: 'excelHtml5', className: 'btn btn-success btn-sm', text: '<ion-icon name="grid"></ion-icon> Excel' },
                        { extend: 'pdfHtml5', className: 'btn btn-danger btn-sm', text: '<ion-icon name="document"></ion-icon> PDF' }
                    ]
                });
            }

            // FullCalendar Initialization inside Tabs
            var calendarBuilt = false;
            $('a[data-bs-toggle="pill"]').on('shown.bs.tab', function (e) {
                if (e.target.id === 'tab-horario' && !calendarBuilt) {
                    calendarBuilt = true;
                    var calendarEl = document.getElementById('calendar');
                    if (calendarEl) {
                        var calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'timeGridWeek',
                            headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
                            locale: 'pt',
                            hiddenDays: [0],
                            slotMinTime: "13:00:00",
                            slotMaxTime: "22:00:00",
                            allDaySlot: false,
                            events: '<?= URL_ROOT ?>/estudante/getCalendarEvents'
                        });
                        calendar.render();
                    }
                }
            });

        }); // Close document.ready

        function abrirModalContestacao(tid, did) {
            $('#cont_turma_id').val(tid);
            $('#cont_disciplina_id').val(did);
            new bootstrap.Modal(document.getElementById('modalContestacao')).show();
        }

        function abrirModalReacao(tid, did) {
            $('#reac_turma_id').val(tid);
            $('#reac_disciplina_id').val(did);
            new bootstrap.Modal(document.getElementById('modalReacao')).show();
        }

        function enviarContestacao(e, form) {
            e.preventDefault();
            const btn = $(form).find('button');
            const originalContent = btn.html();
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> A enviar...');
            $.post('<?= URL_ROOT ?>/contestacao/abrir', $(form).serialize(), function(res) {
                if (res.success) {
                    alert(res.message);
                    location.reload();
                } else {
                    alert('Erro: ' + (res.message || 'Falha técnica.'));
                    btn.prop('disabled', false).html(originalContent);
                }
            }, 'json').fail(function() {
                alert('Erro crítico ao comunicar com o servidor.');
                btn.prop('disabled', false).html(originalContent);
            });
        }

        function enviarReacao(e, form) {
            e.preventDefault();
            const btn = $(form).find('button');
            const originalContent = btn.html();
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> A processar...');
            $.post('<?= URL_ROOT ?>/contestacao/reagir', $(form).serialize(), function(res) {
                if (res.success) {
                    alert(res.message);
                    location.reload();
                } else {
                    alert('Erro: ' + (res.message || 'Falha técnica.'));
                    btn.prop('disabled', false).html(originalContent);
                }
            }, 'json').fail(function() {
                alert('Erro crítico ao comunicar com o servidor.');
                btn.prop('disabled', false).html(originalContent);
            });
        }

        function aceitarNota(tid, did) {
            if (confirm("Ao confirmar a receção/resolução da nota, o processo de contestação será encerrado permanentemente. Confirma conclusão?")) {
                $.post('<?= URL_ROOT ?>/contestacao/reagir', {
                    csrf_token: '<?= $_SESSION['csrf_token'] ?>',
                    estudante_id: <?= isset($data['estudante']['id']) ? $data['estudante']['id'] : '0' ?>,
                    turma_id: tid,
                    disciplina_id: did,
                    acao: 'aceitar'
                }, function(res) {
                    if (res.success) location.reload();
                    else alert('Erro: ' + (res.message || 'Falha ao encerrar processo.'));
                }, 'json').fail(function() {
                    alert('Erro de rede.');
                });
            }
        }

        function marcarComoLido(id, btn) {
            const card = $(btn).closest('.card');
            $(btn).html('<span class="spinner-border spinner-border-sm"></span>...').prop('disabled', true);

            $.post('<?= URL_ROOT ?>/estudante/marcarLido', {
                comunicado_id: id,
                csrf_token: '<?php echo $_SESSION['csrf_token']; ?>'
            }, function (res) {
                if (res.success) {
                    // Remove unread badge from card
                    card.find('.badge.bg-danger').fadeOut();
                    // Replace button with "Lido" status
                    $(btn).parent().html('<span class="text-success small fw-bold"><ion-icon name="checkmark-circle-outline" class="me-1"></ion-icon> Lido</span>');

                    // Re-fetch count or decrement it in UI
                    let badge = $('.btn-light .badge');
                    if (badge.length > 0) {
                        let count = parseInt(badge.text());
                        if (count > 1) {
                            badge.text(count - 1);
                        } else {
                            badge.fadeOut();
                        }
                    }
                } else {
                    alert('Erro ao marcar como lido.');
                    $(btn).html('<ion-icon name="checkmark-done-outline" class="me-1"></ion-icon> Marcar como Lido').prop('disabled', false);
                }
            }, 'json');
        }

        function removerComunicado(id) {
            if (confirm('Deseja remover este comunicado da sua lista? Esta ação não pode ser desfeita.')) {
                $.post('<?= URL_ROOT ?>/estudante/removerComunicado', {
                    comunicado_id: id,
                    csrf_token: '<?php echo $_SESSION['csrf_token']; ?>'
                }, function (res) {
                    if (res.success) {
                        location.reload();
                    } else {
                        alert('Erro ao remover comunicado.');
                    }
                }, 'json');
            }
        }

        // Check for mandatory password change
        <?php if (isset($_SESSION['must_change_password']) && $_SESSION['must_change_password']): ?>
            $(document).ready(function () {
                const forceModal = new bootstrap.Modal(document.getElementById('modalForcePassword'));
                forceModal.show();
            });
        <?php endif; ?>

        function toggleSidebar() {
            $('.ghs-sidebar').toggleClass('active');
            $('.ghs-sidebar-overlay').toggleClass('active');
            $('body').toggleClass('overflow-hidden');
        }

        $(document).ready(function () {
             // Esconder ao clicar em mobile
             if($(window).width() <= 1024) {
                 $('.ghs-sidebar .nav-link').on('click', function() {
                     toggleSidebar();
                 });
             }
        });
    </script>
</body>

</html>

