<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Hard & Softh - Escola Superior de Informática</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f8f9fa; scroll-behavior: smooth; color: #334155; }
        
        /* Navbar */
        .navbar { background-color: rgba(248, 249, 250, 0.98); padding: 5px 0; border-bottom: 2px solid #e2e8f0; }
        .nav-link { color: #475569 !important; font-weight: 600; font-size: 1.05rem; transition: 0.3s; margin: 0 10px; }
        .nav-link:hover { color: #10b981 !important; }
        .brand-text { display: flex; flex-direction: column; justify-content: center; }
        .brand-title { font-weight: 800; font-size: 1.1rem; color: #0f172a; line-height: 1.2; }
        .brand-sub { font-size: 0.8rem; color: #64748b; font-weight: 500; }

        /* Hero */
        .hero { background: linear-gradient(to right, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.3) 100%), url('<?= URL_ROOT ?>/img/ghs.jpg') center/cover no-repeat; color: white; padding: 180px 0 60px 0; min-height: 95vh; display: flex; flex-direction: column; justify-content: space-between; position: relative; }
        .hero h1 { font-weight: 800; font-size: 4.5rem; letter-spacing: -1px; line-height: 1.1; margin-bottom: 20px; }
        .hero h1 span { color: #f59e0b; }
        .hero p { font-size: 1.35rem; font-weight: 400; max-width: 750px; line-height: 1.6; margin-bottom: 40px; color: #f1f5f9; }
        
        .badge-hero { background-color: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.5); color: #10b981; padding: 10px 25px; border-radius: 30px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 30px; font-size: 0.95rem; }
        
        .btn-warning-custom { background-color: #f59e0b; color: #0f172a; font-weight: 800; border: none; padding: 14px 30px; border-radius: 8px; transition: 0.3s; text-decoration: none; font-size: 1.1rem; }
        .btn-warning-custom:hover { background-color: #d97706; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3); color: #0f172a; }
        
        .btn-outline-custom2 { border: 1px solid rgba(255,255,255,0.4); color: white; background: rgba(0,0,0,0.3); font-weight: 600; padding: 14px 30px; border-radius: 8px; transition: 0.3s; backdrop-filter: blur(5px); text-decoration: none; font-size: 1.1rem; }
        .btn-outline-custom2:hover { background: rgba(255,255,255,0.1); border-color: white; color: white; }

        /* Stats inside hero */
        .hero-stats { margin-top: 60px; display: flex; gap: 40px; flex-wrap: wrap; }
        .hero-stat-item { display: flex; flex-direction: column; }
        .hero-stat-item h3 { font-size: 1.8rem; font-weight: 800; margin-bottom: 0; display: flex; align-items: center; gap: 10px; color: white; }
        .hero-stat-item p { font-size: 0.95rem; color: #cbd5e1; margin: 0; }
        
        /* Navbar Links Extras */
        .nav-login-btn { font-weight: 700; color: #0f172a; text-decoration: none; transition: 0.3s; font-size: 1.05rem; }
        .nav-login-btn:hover { color: #10b981; }
        .nav-register-btn { background-color: #10b981; color: white; font-weight: 700; padding: 10px 24px; border-radius: 8px; text-decoration: none; transition: 0.3s; font-size: 1.05rem; }
        .nav-register-btn:hover { background-color: #059669; color: white; }

        /* Sections common */
        .section-padding { padding: 100px 0; }
        .section-title { font-weight: 800; font-size: 2.5rem; color: #0f172a; margin-bottom: 15px; position: relative; d-inline-block; }
        .section-subtitle { font-size: 1.1rem; color: #64748b; margin-bottom: 50px; text-transform: uppercase; font-weight: 600; letter-spacing: 2px; }

        /* Oferta Academica */
        .card-academico { background: white; border-radius: 16px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; height: 100%; transition: 0.3s; display: flex; flex-direction: column; text-align: left; }
        .card-academico:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(16, 185, 129, 0.1); }
        .badge-ano { background-color: #ecfdf5; color: #10b981; padding: 6px 16px; border-radius: 20px; font-weight: 700; font-size: 0.85rem; display: inline-block; width: fit-content; margin-bottom: 20px; }
        .pill-esp { background: white; border: 1px solid #e2e8f0; padding: 12px 24px; border-radius: 30px; font-weight: 600; color: #334155; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.02); transition: 0.3s; font-size: 0.95rem; }
        .pill-esp:hover { border-color: #10b981; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.15); }

        /* Plataforma Digital Features */
        .feature-box { background: white; padding: 40px 30px; border-radius: 20px; text-align: center; height: 100%; transition: 0.3s; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05); }
        .feature-box:hover { transform: translateY(-10px); }
        .feature-icon { width: 70px; height: 70px; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; color: #10b981; font-size: 2rem; }
        .feature-title { font-weight: 700; font-size: 1.2rem; color: #0f172a; margin-bottom: 15px; }
        .feature-desc { color: #64748b; font-size: 0.95rem; line-height: 1.6; margin-bottom: 0; }

        /* CTA Strip */
        .cta-strip { background: #1a5632; padding: 80px 0; color: white; text-align: center; }
        .cta-strip h2 { font-weight: 800; font-size: 3rem; margin-bottom: 20px; }
        
        /* Contact Form */
        .contact-info-item { display: flex; align-items: center; margin-bottom: 25px; }
        .contact-icon { width: 45px; height: 45px; background: #ecfdf5; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #10b981; font-size: 1.3rem; margin-right: 15px; flex-shrink: 0; }
        .contact-text h5 { font-weight: 700; font-size: 1rem; margin-bottom: 2px; color: #0f172a; }
        .contact-text p { color: #64748b; font-size: 0.9rem; margin-bottom: 0; }
        
        .bank-card { background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-top: 30px; }
        .bank-card h6 { font-weight: 700; font-size: 0.9rem; color: #0f172a; margin-bottom: 5px; }
        .bank-card p { font-size: 0.85rem; color: #64748b; margin-bottom: 0; font-weight: 600; }
        
        .contact-form { background: white; padding: 40px; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 10px 30px rgba(0,0,0,0.03); }
        .form-label-custom { font-weight: 700; color: #0f172a; font-size: 0.85rem; margin-bottom: 8px; }
        .form-control-custom { background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 12px 18px; border-radius: 8px; font-size: 0.9rem; color: #334155; }
        .form-control-custom:focus { background-color: white; border-color: #10b981; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1); }

        /* Footer */
        footer { background: #05080f; color: #94a3b8; padding: 80px 0 40px 0; font-size: 0.95rem; }
        .footer-logo { color: white; font-weight: 800; font-size: 1.5rem; display: flex; align-items: center; gap: 12px; margin-bottom: 25px; }
        .footer-logo-circle { width: 45px; height: 45px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .footer-heading { color: white; font-weight: 700; font-size: 1.1rem; margin-bottom: 25px; }
        .footer-links { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 12px; color: #94a3b8; font-size: 1rem; }
        .footer-links a { color: #94a3b8; text-decoration: none; transition: 0.3s; }
        .footer-links a:hover { color: white; }
        .footer-bottom { padding-top: 40px; margin-top: 60px; border-top: 1px solid rgba(255, 255, 255, 0.05); text-align: center; color: #64748b; font-size: 0.9rem; position: relative; }
        
        /* Scroll Top Button */
        .btn-scroll-top { position: absolute; right: 0; bottom: 40px; width: 50px; height: 50px; background-color: #1a5632; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; text-decoration: none; transition: 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.2); z-index: 100; border: none; }
        .btn-scroll-top:hover { background-color: #144528; transform: translateY(-5px); color: white; }
        @media (max-width: 991px) { .btn-scroll-top { position: fixed; right: 20px; bottom: 20px; } }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top shadow-sm">
        <div class="container">            <a class="navbar-brand d-flex align-items-center" href="<?= URL_ROOT ?>/">
                <img src="<?= URL_ROOT ?>/img/logo.jpg" alt="Green Hard & Softh" style="height: 60px; object-fit: contain;">
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <ion-icon name="menu" class="text-dark fs-1"></ion-icon>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav mx-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#inicio">Início</a></li>
                    <li class="nav-item"><a class="nav-link" href="#curso">Curso</a></li>
                    <li class="nav-item"><a class="nav-link" href="#sobre">Sobre</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= URL_ROOT ?>/matricula">Inscrição</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contacto">Contacto</a></li>
                </ul>
                <div class="d-flex align-items-center gap-4 mt-3 mt-lg-0 pb-3 pb-lg-0">
                    <a href="<?= URL_ROOT ?>/auth" class="nav-login-btn">Entrar</a>
                    <a href="<?= URL_ROOT ?>/matricula" class="nav-register-btn">Inscrever-se</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="hero" id="topo">
        <div class="container h-100 d-flex flex-column justify-content-center">
            <div class="row">
                <div class="col-lg-9 col-xl-8">
                    <div class="badge-hero">
                        <ion-icon name="school-outline" class="fs-5 text-warning"></ion-icon> Inscrições Abertas 2025/2026
                    </div>
                    
                    <h1>O Futuro é <span>Hoje</span></h1>
                    <p>A Green Hard & Softh é a primeira e única Escola Superior especializada em TIC na Guiné-Bissau. Licenciatura em Engenharia Informática com 5 especializações.</p>
                    
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="<?= URL_ROOT ?>/matricula" class="btn-warning-custom d-inline-flex align-items-center gap-2">
                            Inscrever-se Agora <ion-icon name="arrow-forward-outline"></ion-icon>
                        </a>
                        <a href="#curso" class="btn-outline-custom2">
                            Ver Plano Curricular
                        </a>
                    </div>

                    <div class="hero-stats">
                        <div class="hero-stat-item">
                            <h3><ion-icon name="school" class="text-warning"></ion-icon> Desde 2007</h3>
                            <p>A formar profissionais</p>
                        </div>
                        <div class="hero-stat-item">
                            <h3>5 Anos</h3>
                            <p>Licenciatura</p>
                        </div>
                        <div class="hero-stat-item">
                            <h3>5</h3>
                            <p>Especializações</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Oferta Academica (Curso) -->
    <section id="curso" class="section-padding" style="background-color: #f8fafc;">
        <div class="container-fluid px-lg-5">
            <div class="text-center mb-5 pb-3">
                <span class="text-success text-uppercase fw-bold" style="letter-spacing: 1.5px; font-size: 0.85rem;">Oferta Académica</span>
                <h2 class="fw-bold mt-2 mb-3" style="color: #0f172a; font-size: 2.5rem;">Licenciatura em Engenharia Informática</h2>
                <p class="fs-5 text-muted mx-auto" style="max-width: 600px;">
                    Curso de 5 anos com formação prática e orientada ao mercado de trabalho, culminando numa especialização à escolha.
                </p>
            </div>

            <!-- 5 Column Grid -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-5 g-4 mb-5">
                <!-- 1 Ano -->
                <div class="col">
                    <div class="card-academico">
                        <span class="badge-ano">1º Ano</span>
                        <h5 class="fw-bold text-dark mt-2 mb-3">Fundamentos</h5>
                        <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.6;">Matemática, Física, Português, Tecnologias Informáticas, Inglês, Química, Aplicações Informáticas, Geométrica Descritivas A e B, Metodologia Científica Guia para Eficiências nos Estudos</p>
                        <strong class="text-success" style="font-size: 1.05rem;">26.500 XOF/mês</strong>
                    </div>
                </div>
                
                <!-- 2 Ano -->
                <div class="col">
                    <div class="card-academico">
                        <span class="badge-ano">2º Ano</span>
                        <h5 class="fw-bold text-dark mt-2 mb-3">Bases Técnicas</h5>
                        <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.6;">Análise Matemática, Fundamentos de Arquitetura de Computadores, Álgebra Linear, Geométrica Analítica Vetorial, Mecânica e Electricidade, Introdução a Programação, Circuitos para Comunicações, Programação Orientada a Objectos, Algoritmos e Estruturas de Dados, Português, Inglês</p>
                        <strong class="text-success" style="font-size: 1.05rem;">29.000 XOF/mês</strong>
                    </div>
                </div>

                <!-- 3 Ano -->
                <div class="col">
                    <div class="card-academico">
                        <span class="badge-ano">3º Ano</span>
                        <h5 class="fw-bold text-dark mt-2 mb-3">Desenvolvimento</h5>
                        <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.6;">Sistemas Operativos, Bases de Dados, Redes Digitais — Fundamentos, Gestão e Contabilidade Empresarial, Teoria da Computação, Hardware e Microprocessador, Concepção e Desenvolvimento de Sistemas, Programação em Rede</p>
                        <strong class="text-success" style="font-size: 1.05rem;">29.000 XOF/mês</strong>
                    </div>
                </div>

                <!-- 4 Ano -->
                <div class="col">
                    <div class="card-academico">
                        <span class="badge-ano">4º Ano</span>
                        <h5 class="fw-bold text-dark mt-2 mb-3">Avançado</h5>
                        <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.6;">Engenharia de Software, Inteligência Artificial, Sistemas Distribuídos, Processamento de Informação, Multimédia e Computação Gráfica, Redes Digitais - Sistemas, Aplicação e Serviços, Interação Pessoa-Máquina, Redes Digitais - Segurança, Multimédia e Gestão, Tecnologias para Sistemas Inteligentes, Metodogias Científica</p>
                        <strong class="text-success" style="font-size: 1.05rem;">31.500 XOF/mês</strong>
                    </div>
                </div>

                <!-- 5 Ano -->
                <div class="col">
                    <div class="card-academico">
                        <span class="badge-ano">5º Ano</span>
                        <h5 class="fw-bold text-dark mt-2 mb-3">Especialização</h5>
                        <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.6;">Projeto final e área de especialização escolhida</p>
                        <strong class="text-success" style="font-size: 1.05rem;">35.000 XOF/mês</strong>
                    </div>
                </div>
            </div>

            <!-- Especializações Pill Row -->
            <div class="text-center mt-5 pt-4">
                <h4 class="fw-bold text-dark mb-4 pb-2">Especializações (5º Ano)</h4>
                <div class="d-flex justify-content-center flex-wrap gap-3">
                    <span class="pill-esp"><ion-icon name="hardware-chip-outline" class="text-success fs-5"></ion-icon> Hardware & Robótica</span>
                    <span class="pill-esp"><span class="text-success fw-bold fs-5">&lt;&gt;</span> Programação</span>
                    <span class="pill-esp"><ion-icon name="server-outline" class="text-success fs-5"></ion-icon> Banco de Dados</span>
                    <span class="pill-esp"><ion-icon name="shield-checkmark-outline" class="text-success fs-5"></ion-icon> Redes de Computadores</span>
                    <span class="pill-esp"><ion-icon name="medkit-outline" class="text-success fs-5"></ion-icon> Engenharia Médica</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Sobre -->
    <section id="sobre" class="section-padding" style="background-color: #f8fafc;">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <!-- Coluna Esquerda: Textos + Horarios -->
                <div class="col-lg-6 pe-lg-5">
                    <span class="text-success text-uppercase fw-bold" style="letter-spacing: 1.5px; font-size: 0.85rem;">Sobre Nós</span>
                    <h2 class="fw-bold mt-2 mb-4" style="color: #0f172a; font-size: 2.5rem; line-height: 1.2;">Formando o Futuro Digital<br>da Guiné-Bissau</h2>
                    <p class="fs-5 text-muted mb-4" style="line-height: 1.7;">Fundada em 2007, a Green Hard & Softh é a primeira e única instituição de ensino superior especializada em Tecnologias de Informação e Comunicação na Guiné-Bissau. Com professores especializados e metodologias inovadoras, preparamos profissionais prontos para o mercado de trabalho.</p>
                    <p class="fs-5 text-muted mb-5" style="line-height: 1.7;">Localizada na Av. Combatente Liberdade da Pátria, a nossa missão é democratizar o acesso ao ensino de tecnologia, criando oportunidades estruturais massivas para jovens guineenses.</p>
                    
                    <!-- Horários de Aula Card -->
                    <div class="border rounded-4 p-4 bg-white" style="border-color: #e2e8f0 !important; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                        <h5 class="fw-bold mb-3 pb-2 text-dark d-flex align-items-center gap-2"><ion-icon name="time-outline" class="text-success fs-4"></ion-icon> Horários de Aula</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <span class="d-block text-dark fw-bold mb-1">Manhã</span>
                                <span class="text-muted small">07:20 – 13:50</span>
                            </div>
                            <div class="col-md-4">
                                <span class="d-block text-dark fw-bold mb-1">Tarde</span>
                                <span class="text-muted small">13:00 – 19:15</span>
                            </div>
                            <div class="col-md-4">
                                <span class="d-block text-dark fw-bold mb-1">Noite</span>
                                <span class="text-muted small">17:45 – 24:00</span>
                            </div>
                        </div>
                        
                        <p class="text-muted small mb-0 mt-4 pt-3 border-top border-light">Sábados: Reposição de aulas, seminários, workshops e atividades desportivas.</p>
                    </div>
                </div>
                
                <!-- Coluna Direita: Missão, Visão e Valores -->
                <div class="col-lg-6 ps-lg-4">
                    <div class="d-flex flex-column gap-4">
                        <!-- Missão -->
                        <div class="border rounded-4 p-4 bg-white d-flex flex-column flex-sm-row gap-4 align-items-sm-start" style="border-color: #e2e8f0 !important; box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(16,185,129,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.02)';">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 55px; height: 55px; background-color: #ecfdf5;">
                                <ion-icon name="disc-outline" class="text-success fs-3"></ion-icon>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-2">Missão</h5>
                                <p class="text-muted small mb-0" style="line-height: 1.6;">Formar profissionais de excelência na área de TIC, capazes de transformar a sociedade guineense e contribuir para o desenvolvimento do país.</p>
                            </div>
                        </div>
                        
                        <!-- Visão -->
                        <div class="border rounded-4 p-4 bg-white d-flex flex-column flex-sm-row gap-4 align-items-sm-start" style="border-color: #e2e8f0 !important; box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(16,185,129,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.02)';">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 55px; height: 55px; background-color: #ecfdf5;">
                                <ion-icon name="bulb-outline" class="text-success fs-3"></ion-icon>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-2">Visão</h5>
                                <p class="text-muted small mb-0" style="line-height: 1.6;">Ser a escola superior de referência em informática e tecnologia na Guiné-Bissau e na sub-região da África Ocidental.</p>
                            </div>
                        </div>
                        
                        <!-- Valores -->
                        <div class="border rounded-4 p-4 bg-white d-flex flex-column flex-sm-row gap-4 align-items-sm-start" style="border-color: #e2e8f0 !important; box-shadow: 0 4px 15px rgba(0,0,0,0.02); transition: 0.3s;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(16,185,129,0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.02)';">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 55px; height: 55px; background-color: #ecfdf5;">
                                <ion-icon name="ribbon-outline" class="text-success fs-3"></ion-icon>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-2">Valores</h5>
                                <p class="text-muted small mb-0" style="line-height: 1.6;">Inovação, excelência, integridade, inclusão e compromisso com o desenvolvimento social e tecnológico.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Plataforma Digital Grid -->
    <section class="section-padding" style="background-color: #f1f5f9;">
        <div class="container">
            <div class="text-center mb-5 pb-3">
                <span class="section-subtitle">Plataforma Digital</span>
                <h2 class="section-title">Tudo Num Só Lugar</h2>
                <p class="fs-5 text-muted">Uma plataforma completa para estudantes, professores e administração resolvida no mesmo ecossistema.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon"><ion-icon name="laptop-outline"></ion-icon></div>
                        <h3 class="feature-title">Matrícula Online</h3>
                        <p class="feature-desc">Processo de matrícula 100% digital com upload de documentos e acompanhamento em tempo real.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon"><ion-icon name="person-outline"></ion-icon></div>
                        <h3 class="feature-title">Portal do Aluno</h3>
                        <p class="feature-desc">Acesso a notas, horários, materiais didáticos e histórico académico num só lugar.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon"><ion-icon name="card-outline"></ion-icon></div>
                        <h3 class="feature-title">Pagamentos Digitais</h3>
                        <p class="feature-desc">Gestão de propinas e pagamentos online com geração automática de comprovativos mensais.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon"><ion-icon name="stats-chart-outline"></ion-icon></div>
                        <h3 class="feature-title">Dashboard Académico</h3>
                        <p class="feature-desc">Estatísticas e relatórios complexos para a administração acompanhar o desempenho da escola.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon"><ion-icon name="book-outline"></ion-icon></div>
                        <h3 class="feature-title">Materiais Didáticos</h3>
                        <p class="feature-desc">Download de conteúdos, apresentações, exercícios e PDFs organizados por disciplina e professor.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-box">
                        <div class="feature-icon"><ion-icon name="chatbubbles-outline"></ion-icon></div>
                        <h3 class="feature-title">Comunicação Integrada</h3>
                        <p class="feature-desc">Mensagens internas, avisos e notificações push automáticas entre escola, alunos e professores.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Strip -->
    <section class="cta-strip">
        <div class="container">
            <h2>O Futuro é Hoje – Inscreve-te!</h2>
            <p class="fs-5 opacity-75 mb-5 max-w-700 mx-auto" style="max-width: 600px;">Junta-te à Green Hard & Softh e constrói o teu futuro na tecnologia. Licenciatura em Engenharia Informática com 5 especializações de última geração.</p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="<?= URL_ROOT ?>/matricula" class="btn-warning-custom px-5 py-3 shadow-lg d-flex align-items-center gap-2">
                    Inscrever-se Agora <ion-icon name="arrow-forward-outline"></ion-icon>
                </a>
                <a href="#contacto" class="btn-outline-custom2 px-5 py-3">Falar Connosco</a>
            </div>
        </div>
    </section>

    <!-- Contacto -->
    <section id="contacto" class="section-padding bg-white">
        <div class="container">
            <div class="text-center mb-5 pb-3">
                <span class="text-success text-uppercase fw-bold" style="letter-spacing: 2px; font-size: 0.85rem;">Contacto</span>
                <h2 class="fw-bold mt-2 display-5" style="color: #0f172a;">Entre em Contacto</h2>
            </div>

            <div class="row g-5">
                <!-- Informações -->
                <div class="col-lg-5">
                    <h4 class="fw-bold mb-4" style="font-size: 1.25rem; color: #0f172a;">Informações</h4>
                    
                    <div class="contact-info-item">
                        <div class="contact-icon"><ion-icon name="location-outline"></ion-icon></div>
                        <div class="contact-text">
                            <h5>Localização</h5>
                            <p>Av. Combatente Liberdade da Pátria, Guiné-Bissau</p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-icon"><ion-icon name="call-outline"></ion-icon></div>
                        <div class="contact-text">
                            <h5>Telefones / WhatsApp</h5>
                            <p>
                                <a href="https://wa.me/245966651249" target="_blank" style="color: inherit; text-decoration: none;">
                                    <ion-icon name="logo-whatsapp" style="color: #25d366; vertical-align: middle;"></ion-icon> +245 96 665 12 49
                                </a><br>
                                +245 95 529 54 75
                            </p>
                        </div>
                    </div>
                    
                    <div class="contact-info-item">
                        <div class="contact-icon"><ion-icon name="mail-outline"></ion-icon></div>
                        <div class="contact-text">
                            <h5>Email</h5>
                            <p>ghsespf@hotmail.com</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-icon"><ion-icon name="time-outline"></ion-icon></div>
                        <div class="contact-text">
                            <h5>Horário de Atendimento</h5>
                            <p>Segunda a Sexta: 7h20 – 19h15</p>
                        </div>
                    </div>

                    <div class="bank-card shadow-sm">
                        <h6>Dados Bancários</h6>
                        <p>BAO Nº 18044010166</p>
                    </div>
                </div>

                <!-- Formulário -->
                <div class="col-lg-7">
                    <div class="contact-form shadow-sm">
                        <h5 class="fw-bold mb-4" style="font-size: 1.1rem;">Enviar Mensagem</h5>
                        <form action="#" method="POST">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    

                            <div class="row g-3 mb-3">
                                <div class="col-md-7">
                                    <input type="text" class="form-control-custom w-100" placeholder="Nome completo" required>
                                </div>
                                <div class="col-md-5">
                                    <input type="email" class="form-control-custom w-100" placeholder="Email" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control-custom w-100" placeholder="Telefone" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control-custom w-100" placeholder="Assunto" required>
                            </div>
                            <div class="mb-4">
                                <textarea class="form-control-custom w-100" rows="4" placeholder="A sua mensagem..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success-custom w-100 py-3 fw-bold text-white rounded-3 shadow-sm" style="background-color: #1a5632;">Enviar Mensagem</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4 pe-lg-5">
                    <div class="footer-logo">
                        <div class="footer-logo-circle">
                            <img src="<?= URL_ROOT ?>/img/logo.jpg" alt="Green Hard & Softh" style="height: 60px; object-fit: contain;">
                        </div>
                        Green Hard & Softh
                    </div>
                    <p class="mb-3" style="color: #cbd5e1; line-height: 1.6;">Primeira e única Escola Superior de Informática e TIC na Guiné-Bissau. A formar profissionais desde 2007.</p>
                    <p class="fst-italic" style="color: #64748b; font-size: 0.9rem;">"O Futuro é Hoje"</p>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-heading">Especializações</h5>
                    <ul class="footer-links">
                        <li><a href="#">Hardware & Robótica</a></li>
                        <li><a href="#">Programação</a></li>
                        <li><a href="#">Banco de Dados</a></li>
                        <li><a href="#">Redes de Computadores</a></li>
                        <li><a href="#">Engenharia Médica</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-heading">Plataforma</h5>
                    <ul class="footer-links">
                        <li><a href="<?= URL_ROOT ?>/auth">Portal do Aluno</a></li>
                        <li><a href="<?= URL_ROOT ?>/auth">Área do Professor</a></li>
                        <li><a href="<?= URL_ROOT ?>/matricula">Inscrição Online</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-heading">Contacto</h5>
                    <ul class="footer-links">
                        <li>Av. Combatente Liberdade da Pátria</li>
                        <li>Guiné-Bissau</li>
                        <li class="mt-3">
                            <a href="https://wa.me/245966651249" target="_blank" style="color: inherit; text-decoration: none;">
                                <ion-icon name="logo-whatsapp" style="color: #25d366; vertical-align: middle;"></ion-icon> +245 96 665 12 49
                            </a>
                        </li>
                        <li>+245 95 529 54 75</li>
                        <li class="mt-3">ghsespf@hotmail.com</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p class="mb-1">© 2026 Green Hard & Softh – Escola Superior de Informática. Todos os direitos reservados.</p>
                <p class="mb-0">Desenvolvido por <strong>Diosives Pedro Nunes Crobute</strong></p>
                
                <a href="#inicio" class="btn-scroll-top">
                    <ion-icon name="arrow-up-outline"></ion-icon>
                </a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
