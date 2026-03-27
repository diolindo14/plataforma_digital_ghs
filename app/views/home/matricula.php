<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GHS - Inscrição Online</title>
    <!-- CSS Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f8fafc; color: #334155; }
        .step { display: none; }
        .step.active { display: block; animation: fadeEffect 0.5s; }
        @keyframes fadeEffect { from {opacity: 0; transform: translateY(10px);} to {opacity: 1; transform: translateY(0);} }
        
        .top-header { background-color: #1a5632; color: white; padding: 25px 0; position: relative; }
        .back-link { position: absolute; top: 25px; right: 15px; color: rgba(255,255,255,0.8); text-decoration: none; font-size: 0.85rem; display: flex; align-items: center; gap: 5px; transition: 0.3s; }
        .back-link:hover { color: white; transform: translateX(-3px); }
        
        /* Stepper Pills */
        .stepper-container { display: flex; justify-content: center; align-items: center; gap: 15px; flex-wrap: wrap; margin-top: 30px; margin-bottom: 30px; }
        .stepper-pill { background-color: #f1f5f9; color: #94a3b8; padding: 8px 20px; border-radius: 30px; font-weight: 600; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 8px; border: 1px solid #e2e8f0; transition: 0.3s; }
        .stepper-pill.active { background-color: #1a5632; color: white; border-color: #1a5632; box-shadow: 0 4px 10px rgba(26, 86, 50, 0.2); }
        .stepper-pill.completed { background-color: #d1fae5; color: #10b981; border-color: #d1fae5; }
        .stepper-line { height: 2px; width: 40px; background-color: #e2e8f0; }

        /* Form Details */
        .form-label { font-weight: 700; color: #0f172a; font-size: 0.85rem; margin-bottom: 8px; }
        .form-control, .form-select { border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; font-size: 0.95rem; color: #334155; width: 100%; box-sizing: border-box; background-color: #f8fafc; }
        .form-control:focus, .form-select:focus { border-color: #1a5632; box-shadow: 0 0 0 4px rgba(26, 86, 50, 0.1); background-color: white; }
        .form-control::placeholder { color: #cbd5e1; }

        .btn-success-custom { background-color: #1a5632; color: white; font-weight: 600; border-radius: 8px; padding: 12px 24px; border: none; transition: 0.3s; font-size: 0.95rem; }
        .btn-success-custom:hover { background-color: #144528; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(26, 86, 50, 0.2); }
        .btn-light-custom { background-color: #f8fafc; color: #94a3b8; font-weight: 600; border-radius: 8px; padding: 12px 24px; border: 1px solid #e2e8f0; transition: 0.3s; font-size: 0.95rem; }
        .btn-light-custom:hover:not(:disabled) { background-color: #f1f5f9; color: #475569; }
        .btn-light-custom:disabled { opacity: 0.6; cursor: not-allowed; }

        /* Floating Info Boxes */
        .info-box { background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); height: 100%; text-align: left; }
        
        /* Upload Zones */
        .upload-zone { border: 1px dashed #cbd5e1; border-radius: 8px; padding: 25px 20px; text-align: center; background-color: #f8fafc; cursor: pointer; transition: 0.3s; position: relative; }
        .upload-zone:hover { border-color: #1a5632; background-color: #f0fdf4; }
        .upload-input { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 2; }
        .file-status { display: none; font-size: 0.85rem; color: #10b981; font-weight: 600; margin-top: 10px; z-index: 10; font-family: monospace; }
        
        .info-box h6 { font-weight: 700; color: #0f172a; margin-bottom: 15px; display: flex; align-items: center; gap: 8px; font-size: 1rem; }
        .info-box ul { padding-left: 20px; margin-bottom: 0; }
        .info-box li { font-size: 0.85rem; color: #64748b; margin-bottom: 6px; }
        .info-box strong { color: #0f172a; }
    </style>
</head>
<body>

    <!-- Header Verde Escuro -->
    <div class="top-header">
        <div class="container position-relative">
            <a href="<?= URL_ROOT ?>/" class="back-link"><ion-icon name="arrow-back-outline"></ion-icon> Voltar ao site</a>
            
            <div class="d-flex align-items-center gap-3 ms-md-5 ps-md-4 mt-3 mt-md-0">
                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 bg-white p-1" style="height: 60px;">
                    <img src="<?= URL_ROOT ?>/img/logo.jpg" alt="Logo GHS" style="height: 100%; object-fit: contain;">
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-white fs-4">Inscrição Online</h3>
                    <p class="mb-0" style="font-size: 0.8rem; color: rgba(255,255,255,0.8); letter-spacing: 0.5px;">Licenciatura em Engenharia Informática</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Container Principal do Formulário -->
    <div class="container mt-4 mb-5 pb-5">
        
        <!-- Stepper (Indicadores de Progresso) -->
        <div class="stepper-container">
            <div class="stepper-pill active" id="ind-1"><ion-icon name="person"></ion-icon> Dados Pessoais</div>
            <div class="stepper-line"></div>
            <div class="stepper-pill" id="ind-2"><ion-icon name="school-outline"></ion-icon> Dados Académicos</div>
            <div class="stepper-line"></div>
            <div class="stepper-pill" id="ind-3"><ion-icon name="cloud-upload-outline"></ion-icon> Documentos</div>
            <div class="stepper-line"></div>
            <div class="stepper-pill" id="ind-4"><ion-icon name="card-outline"></ion-icon> Pagamento</div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8" style="max-width: 850px;">
                <!-- Main Form Card -->
                <div class="card bg-white shadow-sm" style="border-radius: 12px; border: 1px solid #e2e8f0;">
                    <div class="card-body p-4 p-md-5">
                        <form action="<?= URL_ROOT ?>/matricula/submit" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    

                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            
                            <!-- STEP 1: Dados Pessoais -->
                            <div class="step active" id="step-1">
                                <h4 class="fw-bold text-dark mb-1">Dados Pessoais</h4>
                                <p class="text-muted small mb-4 pb-3 border-bottom border-light" style="font-size: 0.85rem;">Preencha os seus dados de identificação.</p>
                                
                                <div class="row g-4 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Nome Completo *</label>
                                        <input type="text" name="nome" class="form-control" placeholder="Ex: Mamadu Baldé" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Data de Nascimento *</label>
                                        <!-- Note: Since original didn't have data_nascimento in backend, adding it here visually as requested, but might need db update -->
                                        <input type="date" name="data_nascimento" class="form-control" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Nº do B.I. *</label>
                                        <input type="text" name="bi" class="form-control" placeholder="Número do Bilhete de Identidade" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Nacionalidade *</label>
                                        <input type="text" name="nacionalidade" class="form-control" value="Guineense" required>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Sexo *</label>
                                        <select name="sexo" class="form-select" required>
                                            <option value="" disabled selected>Selecionar</option>
                                            <option>Masculino</option>
                                            <option>Feminino</option>
                                        </select>
                                    </div>
                                     <div class="col-md-6">
                                         <label class="form-label">Estado Civil / Género</label>
                                         <select name="estado_civil" class="form-select">
                                             <option value="" disabled selected>Selecionar</option>
                                             <option>Solteiro/a</option><option>Casado/a</option><option>Divorciado/a</option><option>Viúvo/a</option>
                                         </select>
                                     </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Telefone *</label>
                                        <input type="text" name="telefone" class="form-control" placeholder="+245 9X XXX XX XX" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="seu.email@exemplo.com" required>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label class="form-label">Endereço / Morada</label>
                                        <input type="text" name="morada" class="form-control" placeholder="Bairro, cidade">
                                    </div>
                                                                        <div class="col-12">
                                         <label class="form-label">Nome do/a Encarregado/a de Educação</label>
                                         <input type="text" name="encarregado_nome" class="form-control" placeholder="Nome completo do/a encarregado/a">
                                     </div>
                                                                        <div class="col-12">
                                         <label class="form-label">Telefone do/a Encarregado/a</label>
                                         <input type="text" name="encarregado_telefone" class="form-control" placeholder="+245 9X XXX XX XX">
                                     </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-5 pt-3 border-top border-light">
                                    <button type="button" class="btn-light-custom" disabled><ion-icon name="arrow-back-outline" class="align-middle me-1"></ion-icon> Anterior</button>
                                    <button type="button" class="btn-success-custom" onclick="nextStep(2)">Seguinte <ion-icon name="arrow-forward-outline" class="align-middle ms-1"></ion-icon></button>
                                </div>
                            </div>

                            <!-- STEP 2: Dados Acadêmicos -->
                            <div class="step" id="step-2">
                                <h4 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">Dados Acadêmicos</h4>
                                <p class="text-muted small mb-4 pb-3 border-bottom border-light" style="font-size: 0.85rem;">Informações sobre a sua formação anterior e o curso pretendido.</p>
                                
                                <div class="row g-4 mb-4">
                                    <div class="col-12">
                                        <label class="form-label">Tipo de Candidatura *</label>
                                        <select name="tipo_candidatura" class="form-select" required>
                                            <option value="" disabled selected>Selecionar</option>
                                            <option value="Novo Ingresso">Novo Ingresso</option>
                                            <option value="Estudante Interno">Estudante Interno</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Turno Pretendido *</label>
                                        <select name="turno" class="form-select" required>
                                            <option value="" disabled selected>Selecionar turno</option>
                                            <option value="Manhã">Manhã (07:20 - 13:50)</option>
                                            <option value="Tarde">Tarde (13:00 - 19:15)</option>
                                            <option value="Noite">Noite (17:45 - 00:00)</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Escola de Proveniência *</label>
                                        <input type="text" name="escola" class="form-control" placeholder="Nome da escola onde concluiu os estudos" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Ano de Conclusão *</label>
                                        <input type="number" name="ano_conclusao" class="form-control" placeholder="Ex: 2024" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Média Final</label>
                                        <input type="text" name="media" class="form-control" placeholder="Ex: 14">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Especialização de Interesse (5º ano)</label>
                                        <select name="especializacao" class="form-select">
                                            <option value="" disabled selected>Selecionar especialização</option>
                                            <option>Hardware & Robótica</option>
                                            <option>Programação</option>
                                            <option>Banco de Dados</option>
                                            <option>Redes de Computadores</option>
                                            <option>Engenharia Médica</option>
                                        </select>
                                        <div class="mt-2" style="font-size: 0.75rem; color: #94a3b8;">Poderá alterar a sua escolha até ao 4º ano.</div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Motivação (opcional)</label>
                                        <textarea name="motivacao" class="form-control" rows="3" placeholder="Conte-nos por que deseja estudar Engenharia Informática na Green Hard & Softh..."></textarea>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-5 pt-3 border-top border-light">
                                    <button type="button" class="btn-light-custom" onclick="nextStep(1)"><ion-icon name="arrow-back-outline" class="align-middle me-1"></ion-icon> Anterior</button>
                                    <button type="button" class="btn-success-custom" onclick="nextStep(3)">Seguinte <ion-icon name="arrow-forward-outline" class="align-middle ms-1"></ion-icon></button>
                                </div>
                            </div>

                            <!-- STEP 3: Documentação -->
                            <div class="step" id="step-3">
                                <h4 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">Upload de Documentos</h4>
                                <p class="text-muted small mb-4 pb-3 border-bottom border-light" style="font-size: 0.85rem;">Carregue os documentos necessários para a sua inscrição. Formatos aceites: PDF, JPG, PNG (máx. 5MB cada).</p>
                                
                                <div class="row g-4 mb-4">
                                    <div class="col-12">
                                        <label class="form-label text-dark">Cópia do Bilhete de Identidade (B.I.) *</label>
                                        <div class="upload-zone">
                                            <input type="file" name="doc_bi" class="upload-input" accept=".pdf,.jpg,.png" required onchange="showFileName(this)">
                                            <ion-icon name="cloud-upload-outline" class="fs-4 text-muted mb-2"></ion-icon>
                                            <p class="mb-1 text-dark small">Arraste o ficheiro ou <strong class="text-success">clique para carregar</strong></p>
                                            <p class="mb-0 text-muted" style="font-size: 0.75rem;">PDF, JPG ou PNG até 5MB</p>
                                            <div class="file-status"></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-dark">Fotografias Tipo Passe (2 fotos) *</label>
                                        <div class="upload-zone">
                                            <input type="file" name="doc_foto" class="upload-input" accept=".jpg,.png" required onchange="showFileName(this)">
                                            <ion-icon name="cloud-upload-outline" class="fs-4 text-muted mb-2"></ion-icon>
                                            <p class="mb-1 text-dark small">Arraste o ficheiro ou <strong class="text-success">clique para carregar</strong></p>
                                            <p class="mb-0 text-muted" style="font-size: 0.75rem;">PDF, JPG ou PNG até 5MB</p>
                                            <div class="file-status"></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-dark">Certificado de Habilitações *</label>
                                        <div class="upload-zone">
                                            <input type="file" name="doc_cert" class="upload-input" accept=".pdf,.jpg,.png" required onchange="showFileName(this)">
                                            <ion-icon name="cloud-upload-outline" class="fs-4 text-muted mb-2"></ion-icon>
                                            <p class="mb-1 text-dark small">Arraste o ficheiro ou <strong class="text-success">clique para carregar</strong></p>
                                            <p class="mb-0 text-muted" style="font-size: 0.75rem;">PDF, JPG ou PNG até 5MB</p>
                                            <div class="file-status"></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-dark">Comprovativo de Pagamento da Inscrição *</label>
                                        <div class="upload-zone">
                                            <input type="file" name="doc_comprovativo" class="upload-input" accept=".pdf,.jpg,.png" required onchange="showFileName(this)">
                                            <ion-icon name="cloud-upload-outline" class="fs-4 text-muted mb-2"></ion-icon>
                                            <p class="mb-1 text-dark small">Arraste o ficheiro ou <strong class="text-success">clique para carregar</strong></p>
                                            <p class="mb-0 text-muted" style="font-size: 0.75rem;">PDF, JPG ou PNG até 5MB</p>
                                            <div class="file-status"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-5 pt-3 border-top border-light">
                                    <button type="button" class="btn-light-custom" onclick="nextStep(2)"><ion-icon name="arrow-back-outline" class="align-middle me-1"></ion-icon> Anterior</button>
                                    <button type="button" class="btn-success-custom" onclick="nextStep(4)">Seguinte <ion-icon name="arrow-forward-outline" class="align-middle ms-1"></ion-icon></button>
                                </div>
                            </div>

                            <!-- STEP 4: Pagamento -->
                            <div class="step" id="step-4">
                                <h4 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">Finalização e Revisão</h4>
                                <p class="text-muted small mb-4 pb-3 border-bottom border-light" style="font-size: 0.85rem;">Revise os termos e confirme a sua submissão.</p>
                                
                                <div class="row g-4 mb-4">
                                    <div class="col-md-12">
                                        <div class="p-4 rounded-3 text-dark mb-3" style="background-color: #f1f5f9; border: 1px dashed #cbd5e1;">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="small fw-bold">Taxa de Inscrição Regulamentada</span>
                                                <span class="small text-muted">Confirmada</span>
                                            </div>
                                            <div class="d-flex justify-content-between fs-5">
                                                <strong class="text-dark">Submissão Bancária BAO</strong>
                                                <strong class="text-success">Nº 18044010166</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-check mt-3 bg-white p-4 rounded-3" style="border: 1px solid #e2e8f0; transition: 0.3s; cursor: pointer;" onmouseover="this.style.borderColor='#1a5632'; this.style.backgroundColor='#f0fdf4';" onmouseout="this.style.borderColor='#e2e8f0'; this.style.backgroundColor='white';">
                                            <div class="d-flex align-items-center">
                                                <input class="form-check-input ms-0 me-3 mt-0 flex-shrink-0" type="checkbox" required id="confirmaCheckbox" style="transform: scale(1.4); cursor: pointer;">
                                                <label class="form-check-label text-dark small" for="confirmaCheckbox" style="cursor: pointer;">
                                                    <strong>Submeto sob compromisso de honra.</strong> Declaro que toda a informação prestada é verdadeira, e confirmo ter efetuado o carregamento de todos os comprovativos obrigatórios no passo anterior.
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-5 pt-3 border-top border-light">
                                    <button type="button" class="btn-light-custom" onclick="nextStep(3)"><ion-icon name="arrow-back-outline" class="align-middle me-1"></ion-icon> Anterior</button>
                                    <button type="submit" class="btn-success-custom border-2">Finalizar Inscrição <ion-icon name="checkmark-done-outline" class="align-middle ms-1"></ion-icon></button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div> <!-- End Card -->
                
                <!-- Bottom Informational Grids (Documentos e Taxas) -->
                <div class="row g-4 mt-1">
                    <div class="col-md-6">
                        <div class="info-box">
                            <h6><ion-icon name="document-text" class="text-danger fs-5"></ion-icon> Documentos Necessários</h6>
                            <ul>
                                <li>Cópia do B.I.</li>
                                <li>2 fotografias tipo passe</li>
                                <li>Certificado de Habilitações</li>
                                <li>Comprovativo de pagamento</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <h6><ion-icon name="wallet" class="text-warning fs-5"></ion-icon> Taxas de Inscrição</h6>
                            <ul>
                                <li>Novo Ingresso: <strong>15.000 XOF</strong></li>
                                <li>Estudante Interno: <strong>10.000 XOF</strong></li>
                                <li>Cartão de Estudante: <strong>2.500 XOF/Ano</strong></li>
                                <li>Caderneta de Notas: <strong>3.000 XOF/Ano</strong></li>
                            </ul>
                            <div class="mt-3 small pt-2 border-top border-light text-muted">
                                BAO Nº <strong>18044010166</strong>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <script>
        function showFileName(input) {
            let fileNameBox = input.parentElement.querySelector('.file-status');
            if (input.files && input.files.length > 0) {
                fileNameBox.innerHTML = "<ion-icon name='checkmark-done-circle-outline' class='align-middle fs-5 me-1'></ion-icon>" + input.files[0].name;
                fileNameBox.style.display = "block";
                input.parentElement.style.borderColor = "#10b981";
                input.parentElement.style.backgroundColor = "#ecfdf5";
            } else {
                fileNameBox.style.display = "none";
                input.parentElement.style.borderColor = "#cbd5e1";
                input.parentElement.style.backgroundColor = "#f8fafc";
            }
        }

        function nextStep(step) {
            // Esconder todas as janelas do formulário
            document.querySelectorAll('.step').forEach(el => el.classList.remove('active'));
            
            // Limpar estados ativos e completos dos indicadores
            document.querySelectorAll('.stepper-pill').forEach(el => {
                el.classList.remove('active');
                el.classList.remove('completed');
                
                // Restaurar ícone original, se guardado
                let icon = el.querySelector('ion-icon');
                if (icon && el.dataset.origIcon) {
                    icon.setAttribute('name', el.dataset.origIcon);
                }
            });
            
            // Ativar a janela atual
            document.getElementById(`step-${step}`).classList.add('active');
            
            // Ativar o indicador alvo
            let activePill = document.getElementById(`ind-${step}`);
            if (activePill) {
                activePill.classList.add('active');
            }

            // Marcar todos os indicadores passados como 'completed' com o ícone checkmark
            for (let i = 1; i < step; i++) {
                let pill = document.getElementById(`ind-${i}`);
                if (pill) {
                    pill.classList.add('completed');
                    let icon = pill.querySelector('ion-icon');
                    if (icon) {
                        if (!pill.dataset.origIcon) {
                            pill.dataset.origIcon = icon.getAttribute('name');
                        }
                        icon.setAttribute('name', 'checkmark-circle-outline');
                    }
                }
            }
            
            // Subir suavemente
            window.scrollTo({top: 0, behavior: 'smooth'});
        }
    </script>
</body>
</html>
