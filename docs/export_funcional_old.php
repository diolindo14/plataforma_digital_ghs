<?php
// Manual do Utilizador GHS v1.0
?>
<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <title>GHS ÔÇö Manual do Utilizador v1.3</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
        :root { --primary: #111827; --accent: #2563eb; --accent-green: #059669; --light: #f9fafb; --border: #e2e8f0; --text: #374151; --muted: #6b7280; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; color: var(--text); background: #fff; padding: 40px 60px; line-height: 1.6; font-size: 13px; }
        /* CAPA */
        .cover { display: flex; align-items: flex-start; justify-content: space-between; border-bottom: 2px solid var(--border); padding-bottom: 25px; margin-bottom: 35px; }
        .cover-left .logo { font-size: 16px; font-weight: 700; color: var(--primary); letter-spacing: 0.5px; }
        .cover-left .logo span { color: var(--accent); }
        .cover-left h1 { font-size: 26px; font-weight: 700; color: var(--primary); margin: 6px 0; }
        .cover-left p { color: var(--muted); font-size: 13px; }
        .cover-right { text-align: right; font-size: 12px; color: var(--muted); }
        .cover-right .version { display: inline-block; background: var(--light); border: 1px solid var(--border); color: var(--primary); padding: 3px 10px; border-radius: 4px; font-size: 11px; font-weight: 600; margin-bottom: 6px; }
        h2 { font-size: 15px; font-weight: 700; color: var(--primary); margin: 30px 0 12px; padding-bottom: 6px; border-bottom: 1px solid var(--border); }
        h3 { font-size: 14px; font-weight: 600; color: var(--primary); margin: 18px 0 8px; }
        h4 { font-size: 13px; font-weight: 600; color: var(--primary); margin: 12px 0 6px; }
        p { margin-bottom: 10px; text-align: justify; }
        ul, ol { padding-left: 20px; margin-bottom: 12px; }
        li { margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0 25px; font-size: 12px; border: 1px solid var(--border); }
        thead th { background: var(--light); color: var(--primary); padding: 10px 12px; text-align: left; font-weight: 600; border-bottom: 1px solid var(--border); }
        tbody td { border-bottom: 1px solid var(--border); padding: 8px 12px; vertical-align: top; }
        tbody tr:nth-child(even) td { background: #fafbfc; }
        .badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 500; border: 1px solid transparent; }
        .badge-green { background: #f0fdf4; color: #166534; border-color: #bbf7d0; }
        .badge-blue { background: #eff6ff; color: #1e40af; border-color: #bfdbfe; }
        .badge-yellow { background: #fefce8; color: #854d0e; border-color: #fef08a; }
        .badge-red { background: #fef2f2; color: #991b1b; border-color: #fecaca; }
        .badge-orange { background: #fff7ed; color: #c2410c; border-color: #ffedd5; }
        /* CONTAINERS */
        .info-box { background: #eff6ff; border: 1px solid #bfdbfe; border-left: 3px solid var(--accent); border-radius: 4px; padding: 12px 16px; margin: 12px 0; }
        .warning-box { background: #fefce8; border: 1px solid #fef08a; border-left: 3px solid #eab308; border-radius: 4px; padding: 12px 16px; margin: 12px 0; }
        .success-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-left: 3px solid var(--accent-green); border-radius: 4px; padding: 12px 16px; margin: 12px 0; }
        .danger-box { background: #fef2f2; border: 1px solid #fecaca; border-left: 3px solid #ef4444; border-radius: 4px; padding: 12px 16px; margin: 12px 0; }
        .info-box strong, .warning-box strong, .success-box strong, .danger-box strong { display: block; margin-bottom: 4px; color: var(--primary); }
        .steps { counter-reset: step; list-style: none; padding: 0; }
        .steps li { counter-increment: step; display: flex; gap: 12px; align-items: flex-start; margin-bottom: 12px; }
        .steps li::before { content: counter(step); display: flex; align-items: center; justify-content: center; width: 22px; height: 22px; min-width: 22px; background: var(--light); color: var(--primary); border: 1px solid var(--border); border-radius: 50%; font-weight: 600; font-size: 11px; margin-top: 2px; }
        .steps li strong { color: var(--primary); display: block; }
        code { font-family: 'JetBrains Mono', monospace; background: var(--light); padding: 2px 4px; border-radius: 3px; font-size: 11px; color: #b91c1c; border: 1px solid var(--border); }
        pre { font-family: 'JetBrains Mono', monospace; background: var(--light); color: var(--primary); padding: 12px; border-radius: 4px; font-size: 11px; line-height: 1.4; margin: 12px 0; overflow-x: auto; border: 1px solid var(--border); }
        pre .comment { color: var(--muted); }
        pre .key { color: var(--accent); }
        pre .val { color: var(--accent-green); }
        .metrics { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin: 15px 0 25px; }
        .metric-card { border: 1px solid var(--border); border-radius: 6px; padding: 16px; text-align: center; background: var(--light); }
        .metric-card .number { font-size: 24px; font-weight: 700; color: var(--primary); }
        .metric-card .label { font-size: 11px; color: var(--muted); margin-top: 4px; }
        .faq-item { border: 1px solid var(--border); border-radius: 4px; margin-bottom: 8px; overflow: hidden; }
        .faq-q { background: var(--light); padding: 10px 14px; font-weight: 600; color: var(--primary); font-size: 12px; }
        .faq-a { padding: 10px 14px; font-size: 12px; border-top: 1px solid var(--border); }
        .footer { margin-top: 40px; padding-top: 15px; font-size: 11px; color: var(--muted); display: flex; justify-content: space-between; border-top: 1px solid var(--border); }
        @media print {
            @page {
                margin: 0.5cm;
            }

            body { padding: 30px 40px; }
            .cover-bar { display: none; }
            .cover { border-bottom: 2px solid #ccc; padding-bottom: 15px; margin-bottom: 20px; }
            table, pre, .info-box, .warning-box, .success-box, .metric-card { box-shadow: none; border: 1px solid #ccc; }
        }
    </style>
</head>

<body>



    <div class="cover">
        <div class="cover-left">
            <img src="../img/logo.jpg" alt="GREEN HARD &amp; SOFTH" style="height: 60px; border-radius: 6px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <h1>Manual do Utilizador</h1>
            <p>Guia completo de uso para Estudantes, Professores e Secretaria</p>
        </div>
        <div class="cover-right">
            <div class="version">v1.3</div><br>
            <strong>Data:</strong> Abril 2026<br>
            <strong>P├║blico-Alvo:</strong> Todos os Utilizadores<br>
            <strong>Autor:</strong> Diosives Crobute
        </div>
    </div>

    
    

        <h2>1. Introdu├º├úo e Acesso ├á Plataforma</h2>
        <p>A plataforma GHS est├í acess├¡vel via browser (Google Chrome, Firefox, Edge) no endere├ºo configurado pela
            institui├º├úo. O processo de autentica├º├úo ├® protegido por m├║ltiplas camadas de seguran├ºa e inclui valida├º├úo
            institucional.</p>

        <h3>1.1 Como Fazer Login</h3>
        <ol class="steps">
            <li>
                <div><strong>Aceda ao Endere├ºo Institucional</strong> A plataforma pode ser acedida de duas formas:
                    <ul style="margin-top: 8px;">
                        <li>­ƒîÉ <strong>Online (Produ├º├úo):</strong> <a href="https://escola-ghs.wuaze.com" target="_blank" style="color:var(--accent); text-decoration:none; font-weight:600;">https://escola-ghs.wuaze.com</a> ÔÇö dispon├¡vel a qualquer hora, de qualquer dispositivo com internet.</li>
                        <li>­ƒûÑ´©Å <strong>Local (Desenvolvimento):</strong> <code>http://localhost/green/auth</code> ÔÇö para uso interno com XAMPP, somente na rede local do servidor.</li>
                    </ul>
                </div>
            </li>
            <div style="text-align: center; margin: 15px 0;">
                <img src="../public/img/login_screen.png" alt="Interface de Login GHS" style="max-width: 300px; border-radius: 8px; border: 1px solid var(--border); box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <p style="font-size: 10px; color: var(--muted); margin-top: 5px;">Interface do Portal Institucional GHS</p>
            </div>
            <li>
                <div><strong>Introduza as Suas Credenciais</strong> Preencha o campo de Email e Password com os dados
                    recebidos no momento da matr├¡cula ou contrata├º├úo.</div>
            </li>

            <li>
                <div><strong>Ser├í Redirecionado Automaticamente</strong> O sistema identifica o seu perfil (Estudante,
                    Professor, Secretaria ou Administrador) e abre o portal correspondente.</div>
            </li>
        </ol>

        <div class="warning-box">
            <strong>ÔÜá´©Å Primeiro Acesso:</strong> Na primeira vez que aceder, ser├í obrigado a alterar a password
            tempor├íria
            fornecida pela secretaria. Escolha uma password com pelo menos 8 caracteres.
        </div>

        <h2>2. Portal do Estudante</h2>
        <p>O portal do estudante ├® o ponto central de auto-servi├ºo acad├®mico e financeiro. Est├í organizado em
            separadores
            tem├íticos acess├¡veis a partir do menu lateral.</p>

        <h3>2.1 Dashboard e Notifica├º├Áes</h3>
        <p>Ao entrar, o aluno v├¬ um painel resumo com o estado atual das propinas, os comunicados n├úo lidos e os alertas
            acad├®micos importantes. Comunicados urgentes da dire├º├úo aparecem destacados no topo do ecr├ú.</p>
        <div class="info-box">
            <strong>­ƒô▒ Experi├¬ncia Mobile & Desktop:</strong> O portal ├® agora totalmente adapt├ível. No telem├│vel, os cart├Áes e menus ajustam-se automaticamente para uso com o polegar. No computador, o layout expande-se para mostrar mais informa├º├úo de forma organizada (Grid System v2.0).
        </div>

        <h3>2.2 Submiss├úo de Matr├¡cula (Novo Aluno)</h3>
        <ol class="steps">
            <li>
                <div><strong>Aceda a "Nova Matr├¡cula"</strong> No menu lateral, clique em "Matr├¡culas" e de seguida em
                    "Submeter Nova Matr├¡cula".</div>
            </li>
            <li>
                <div><strong>Selecione o Ano e Turno</strong> Escolha o ano curricular pretendido (1┬║ ao 4┬║ Ano) e o
                    turno
                    dispon├¡vel (Manh├ú, Tarde ou Noite).</div>
            </li>
            <li>
                <div><strong>Fa├ºa Upload dos Documentos</strong> Anexe os documentos obrigat├│rios ÔÇö Bilhete de
                    Identidade,
                    Certificado de Habilita├º├Áes e Comprovativo de Pagamento da Inscri├º├úo. Apenas PDF e imagens s├úo
                    aceites.
                </div>
            </li>
            <li>
                <div><strong>Aguarde a Valida├º├úo</strong> A secretaria ir├í analisar os seus documentos e aprovar ou
                    rejeitar
                    a candidatura com uma justifica├º├úo. Receber├í uma notifica├º├úo no portal.</div>
            </li>
        </ol>

        <h3>2.3 Renova├º├úo de Ano / Nova Inscri├º├úo (Estudante Interno)</h3>
        <p>Alunos que j├í t├¬m conta ativa na plataforma beneficiam de um processo de inscri├º├úo <strong>simplificado e
                acelerado</strong>. O sistema reconhece automaticamente o perfil do aluno e remove os passos
            desnecess├írios.</p>
        <ol class="steps">
            <li>
                <div><strong>Fa├ºa Login e Aceda ao Formul├írio</strong> Entre na plataforma com as suas credenciais e
                    clique em "Nova Matr├¡cula" no menu lateral. O tipo "Estudante Interno" ├® selecionado automaticamente.
                </div>
            </li>
            <li>
                <div><strong>Confirme os seus Dados</strong> Os campos pessoais (nome, B.I., email, telefone) s├úo
                    pr├®-preenchidos com os dados do seu perfil. Verifique e corrija se necess├írio.</div>
            </li>
            <li>
                <div><strong>Selecione o Turno</strong> Escolha o turno pretendido para o novo ciclo letivo (Manh├ú,
                    Tarde ou Noite).</div>
            </li>
            <li>
                <div><strong>Submeta a Candidatura</strong> Clique em "Submeter". N├úo ├® necess├írio carregar documentos
                    acad├®micos pois estes j├í constam do seu processo na secretaria.</div>
            </li>
        </ol>
        <div class="info-box">
            <strong>­ƒÆí Campos Removidos para Alunos Internos:</strong> Os campos de Escola de Proveni├¬ncia, Ano de
            Conclus├úo, M├®dia Final, Motiva├º├úo e Certificado de Habilita├º├Áes <strong>n├úo aparecem</strong> no
            formul├írio
            para alunos j├í registados ÔÇö estes dados j├í existem no sistema e n├úo precisam de ser repetidos.
        </div>



        <h3>2.3 Consulta de Notas e Pautas</h3>
        <p>No separador "Notas", o aluno pode visualizar as suas avalia├º├Áes organizadas por disciplina. A estrutura da
            pauta
            inclui:</p>
        <table>
            <thead>
                <tr>
                    <th>Componente</th>
                    <th>Peso</th>
                    <th>Descri├º├úo</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>AC1, AC2, AC3, AC4</td>
                    <td>Avalia├º├úo Cont├¡nua</td>
                    <td>Testes e trabalhos ao longo do semestre, lan├ºados pelo docente.</td>
                </tr>
                <tr>
                    <td>Exame Final</td>
                    <td>Exame</td>
                    <td>Avalia├º├úo final do semestre. Determina a nota final junto com o AC.</td>
                </tr>
                <tr>
                    <td>Nota Final</td>
                    <td><strong>(AC + Exame) ├À 2</strong></td>
                    <td>M├®dia aritm├®tica. Valor ÔëÑ 12 = Aprovado. Entre 8 e 11.9 = Recurso. &lt; 8 = Reprovado.</td>
                </tr>
                <tr>
                    <td>Admiss├úo ao Exame</td>
                    <td><strong>M├¡nimo 8.0 AC</strong></td>
                    <td>Alunos com m├®dia AC inferior a 8.0 s├úo reprovados automaticamente.</td>
                </tr>
            </tbody>
        </table>

        <h3>2.3.1 Ciclo de Valida├º├úo e Contesta├º├úo de Notas</h3>
        <p>Para garantir a integridade pedag├│gica, a plataforma utiliza um sistema de valida├º├úo ativa em quatro fases:</p>
        <ol class="steps">
            <li>
                <div><strong>Estado: Pendente (Contesta├º├úo)</strong> Se o aluno discordar da nota, clica em "Contestar" e descreve o motivo. O professor recebe uma notifica├º├úo imediata para rever o caso.</div>
            </li>
            <li>
                <div><strong>Estado: Respondido</strong> O professor analisa a reclama├º├úo e envia uma resposta formal pelo portal. O professor pode optar por manter a nota original (com justificativa) ou proceder ├á retifica├º├úo.</div>
            </li>
            <li>
                <div><strong>Estado: Concordado / Resolvido</strong> Ap├│s a resposta do docente, o aluno deve ler o parecer. Caso concorde, clica em "Aceitar Resposta", o que encerra o processo de avalia├º├úo daquela disciplina e tranca a nota para altera├º├Áes.</div>
            </li>
            <li>
                <div><strong>Media├º├úo (Impasse)</strong> Caso o aluno e o professor n├úo cheguem a um acordo, o processo ├® escalado para a Coordena├º├úo Acad├®mica (Administrador) para uma decis├úo final e definitiva.</div>
            </li>
        </ol>
        <div class="success-box">
            <strong>Ô£à Importante:</strong> Assim que clicar em <strong>"Confirmar Nota"</strong> (mesmo sem contesta├º├úo pr├®via), a nota ├® considerada definitiva para efeitos de pauta oficial e o processo ├® dado como encerrado.
        </div>

        <div class="danger-box">
            <strong>­ƒÜ¿ Alerta de Convocat├│ria (Novo v1.3):</strong> Se a sua reclama├º├úo chegar a um impasse, a Administra├º├úo agendar├í uma reuni├úo. Um <strong>Alerta Vermelho</strong> aparecer├í no topo do seu portal com a data, hora e local. Este alerta ├® fixo e s├│ desaparecer├í ap├│s a conclus├úo da media├º├úo.
        </div>

        <h3>2.4 Hor├írios Din├ómicos e Interativos (Novo)</h3>
        <p>O separador "Hor├írios" apresenta a grade semanal completa da turma do aluno, com informa├º├úo sobre a
            disciplina, o
            docente respons├ível e a sala atribu├¡da. O hor├írio ├® gerado automaticamente pela administra├º├úo e atualizado
            sempre que houver altera├º├Áes.</p>

        <h3>2.5 Hist├│rico Acad├®mico Global (Novo)</h3>
        <p>O separador "Hist├│rico" apresenta o percurso acad├®mico completo e vital├¡cio do aluno na institui├º├úo. Para
            cada
            ano letivo e semestre, s├úo mostradas todas as disciplinas conclu├¡das com a respetiva nota final e o estatuto
            (Aprovado/Reprovado). Este registo ├® imut├ível e pode servir de base para emiss├úo de certid├Áes.</p>

        <h3>2.6 Gest├úo de Propinas e Pagamentos</h3>
        <ol class="steps">
            <li>
                <div><strong>Consulte a Situa├º├úo Financeira</strong> No separador "Financeiro", veja o extrato de
                    propinas,
                    os valores em d├¡vida e os pagamentos j├í validados.</div>
            </li>
            <li>
                <div><strong>Efetue o Dep├│sito Banc├írio</strong> Realize a transfer├¬ncia para o NIB da institui├º├úo
                    indicado
                    na plataforma.</div>
            </li>
            <li>
                <div><strong>Submeta o Comprovativo</strong> Fa├ºa o upload do tal├úo de transfer├¬ncia ou dep├│sito em
                    "Submeter Pagamento".</div>
            </li>
            <li>
                <div><strong>Aceda ao Recibo T├®rmico (POS)</strong> Ap├│s a valida├º├úo, poder├í descarregar um recibo 
                em formato t├®rmico (80mm) com a imagem oficial institucional "O futuro ├® hoje!". 
                <br><strong>Melhoria v1.2:</strong> Ao clicar em imprimir, o recibo abre automaticamente numa <strong>nova aba do navegador</strong>, permitindo que imprima e feche a aba sem perder a sua posi├º├úo no portal principal.</div>
            </li>
        </ol>

        <h3>2.7 Certificados de M├®rito (Novo)</h3>
        <p>Se o aluno integrar o ranking dos melhores do semestre ou da escola, o seu certificado de m├®rito estar├í
            vis├¡vel
            no separador "Conquistas" com a medalha correspondente (­ƒÑç ­ƒÑê ­ƒÑë). O certificado ├® emitido pela dire├º├úo e
            pode
            ser partilhado digitalmente.</p>

        <h2>3. Portal do Professor</h2>

        <h3>3.1 Lan├ºamento de Notas</h3>
        <p>No separador "Pautas", o professor seleciona a turma e a disciplina, e preenche as notas por tipo de
            avalia├º├úo
            (AC1 a AC4 e Exame Final). O sistema calcula automaticamente a nota final e identifica alunos em situa├º├úo de
            recurso ou reprova├º├úo.</p>
        <div class="success-box">
            <strong>Ô£à Valida├º├úo em Tempo Real:</strong> Ao inserir as notas, o sistema valida os valores automaticamente
            e
            alerta para poss├¡veis erros (ex: nota fora do intervalo 0-20). As notas s├│ ficam vis├¡veis para os alunos
            ap├│s
            confirma├º├úo do docente.
        </div>

        <h3>3.2 Sum├írios Digitais e Registo de Faltas</h3>
        <p>Ap├│s cada aula, o professor deve registar o sum├írio digital descrevendo os conte├║dos lecionados e marcar as
            presen├ºas dos alunos. As faltas s├úo contabilizadas automaticamente e ficam vis├¡veis no portal do aluno.</p>

        <h3>3.3 Resposta a Reclama├º├Áes de Notas</h3>
        <p>No separador "Reclama├º├Áes", o docente visualiza todas as reclama├º├Áes submetidas pelos alunos ├ás suas
            disciplinas.
            Para cada reclama├º├úo, poder├í manter a nota (com justifica├º├úo escrita) ou propor uma altera├º├úo que fica
            sujeita a
            aprova├º├úo pela administra├º├úo.</p>

        <h3>3.4 Consulta de Hor├írios</h3>
        <p>O professor tem acesso ao seu hor├írio semanal completo com as turmas, disciplinas e salas atribu├¡das. Este
            hor├írio ├® configurado pela administra├º├úo e pode ser consultado em qualquer momento no portal.</p>

        <h2>4. Portal da Secretaria e Tesouraria</h2>

        <h3>4.1 Valida├º├úo de Matr├¡culas com Integrador Visual</h3>
        <p>A lista de matr├¡culas pendentes est├í dispon├¡vel no separador "Matr├¡culas". Para cada candidatura, a
            secretaria
            pode clicar no ├¡cone de "visualizar" para abrir o <strong>Integrador Visual Documental</strong> ÔÇö uma
            interface
            nativa que apresenta os documentos do aluno (B.I., Certificados) diretamente no browser via PDF.js, sem
            necessidade de descarregar os ficheiros.</p>
        <ul>
            <li>Use os bot├Áes de navega├º├úo para alternar entre documentos.</li>
            <li>Utilize o zoom e a rota├º├úo integrados para verificar os detalhes.</li>
            <li>Clique em <strong>"Aprovar"</strong> para ativar a conta do aluno e aloc├í-lo automaticamente ├á turma
                compat├¡vel.</li>
            <li>Clique em <strong>"Rejeitar"</strong> e escreva a justifica├º├úo para que o aluno seja notificado.</li>
        </ul>

        <h3>4.2 Registo Manual de Pagamentos (Novo)</h3>
        <p>Para pagamentos presenciais (dep├│sitos em balc├úo), a secretaria pode registar o pagamento manualmente no
            separador "Tesouraria > Registar Pagamento". O sistema gera automaticamente um recibo digital com n├║mero de
            s├®rie e atualiza o extrato financeiro do aluno de forma imediata.</p>

        <h2>5. Funcionalidades Comuns a Todos os Portais</h2>

        <h3>5.1 Sistema de Comunicados com Rastreamento</h3>
        <p>A Secretaria e a Administra├º├úo podem publicar comunicados que aparecem em todos os portais. Cada comunicado
            inclui um sistema de <strong>Read Tracking</strong> ÔÇö o sistema regista quem leu e quando, garantindo que a
            informa├º├úo cr├¡tica foi recebida. Os comunicados expiram automaticamente ao fim de 7 dias para manter as
            interfaces limpas.</p>

        <h3>5.2 Visualiza├º├úo de Documentos (PDF.js)</h3>
        <p>A plataforma integra um visualizador de documentos PDF nativo no browser, sem que os ficheiros sejam
            descarregados para o computador. Funcionalidades dispon├¡veis:</p>
        <ul>
            <li><strong>Zoom In/Out</strong>: Amplie para verificar detalhes de documentos oficiais.</li>
            <li><strong>Rota├º├úo</strong>: Corrija a orienta├º├úo de documentos digitalizados.</li>
            <li><strong>Navega├º├úo por P├íginas</strong>: Percorra documentos multi-p├ígina com facilidade.</li>
        </ul>

        <h2>6. Perguntas Frequentes (FAQ)</h2>

        <div class="faq-item">
            <div class="faq-q">ÔØô Esqueci a minha password. O que devo fazer?</div>
            <div class="faq-a">Contacte a secretaria da institui├º├úo pessoalmente ou por email. Um administrativo ir├í
                efetuar
                o reset da password e fornecer-lhe novas credenciais tempor├írias.</div>
        </div>

        <div class="faq-item">
            <div class="faq-q">ÔØô A minha matr├¡cula est├í "Pendente" h├í v├írios dias. ├ë normal?</div>
            <div class="faq-a">O prazo de an├ílise pode variar. Se ap├│s 5 dias ├║teis n├úo receber resposta, contacte a
                secretaria referenciando o n├║mero da sua matr├¡cula vis├¡vel na plataforma.</div>
        </div>

        <div class="faq-item">
            <div class="faq-q">ÔØô Submeti o pagamento mas o meu acesso ainda est├í bloqueado. Porqu├¬?</div>
            <div class="faq-a">O comprovativo precisa de ser validado manualmente pela tesouraria. Ap├│s a valida├º├úo, o
                acesso ├® ativado automaticamente. Aguarde 1 dia ├║til ap├│s a submiss├úo.</div>
        </div>

        <div class="faq-item">
            <div class="faq-q">ÔØô Como sei se fui aprovado ou se preciso de fazer exame de recurso?</div>
            <div class="faq-a">Ap├│s o lan├ºamento de todas as notas pelo docente, o seu portal mostrar├í o estatuto de
                cada
                disciplina: "Aprovado", "Recurso" ou "Reprovado". Receber├í tamb├®m uma notifica├º├úo autom├ítica.</div>
        </div>

        <div class="faq-item">
            <div class="faq-q">ÔØô O meu hist├│rico acad├®mico mostra dados incorretos. O que fazer?</div>
            <div class="faq-a">Os dados do Hist├│rico Global s├úo calculados automaticamente com base nas notas lan├ºadas
                pelos
                docentes. Em caso de erro, dirija-se ├á secretaria com o comprovativo de avalia├º├úo original.</div>
        </div>

    <div class="footer">
        <span>&copy; 2026 Green Hard &amp; Softh ÔÇö Escola Superior de Inform├ítica. <strong>By Diosives
                Crobute</strong></span>
        <span>Manual do Utilizador v1.1</span>
    </div>

</body>

</html>

