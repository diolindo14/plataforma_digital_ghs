<?php
// Manual do Utilizador GHS v5.0
?>
<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <title>GHS — Manual do Utilizador v5.0</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1e293b;
            --accent: #3b82f6;
            --accent-green: #10b981;
            --light: #f8fafc;
            --border: #e2e8f0;
            --text: #334155;
            --muted: #64748b;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background: #fff;
            padding: 50px 60px;
            line-height: 1.7;
            font-size: 14px;
        }

        /* Cabeçalho */
        .cover {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            border-bottom: 3px solid var(--accent);
            padding-bottom: 25px;
            margin-bottom: 35px;
        }

        .cover-left .logo {
            font-size: 22px;
            font-weight: 700;
            color: var(--primary);
        }

        .cover-left .logo span {
            color: var(--accent);
        }

        .cover-left h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
            margin-top: 8px;
        }

        .cover-left p {
            color: var(--muted);
            font-size: 13px;
            margin-top: 4px;
        }

        .cover-right {
            text-align: right;
            font-size: 12px;
            color: var(--muted);
        }

        .cover-right .version {
            display: inline-block;
            background: var(--accent);
            color: #fff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        h2 {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            border-left: 4px solid var(--accent);
            padding-left: 12px;
            margin: 35px 0 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        h3 {
            font-size: 14px;
            font-weight: 600;
            color: var(--accent);
            margin: 22px 0 8px;
        }

        h4 {
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
            margin: 15px 0 6px;
        }

        p {
            margin-bottom: 10px;
            text-align: justify;
        }

        ul,
        ol {
            padding-left: 20px;
            margin-bottom: 12px;
        }

        li {
            margin-bottom: 6px;
        }

        /* Tabelas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0 25px;
            font-size: 13px;
        }

        thead th {
            background: var(--primary);
            color: #fff;
            padding: 11px 14px;
            text-align: left;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        tbody td {
            border: 1px solid var(--border);
            padding: 10px 14px;
            vertical-align: top;
        }

        tbody tr:nth-child(even) td {
            background: var(--light);
        }

        /* Passo a passo */
        .steps {
            counter-reset: step;
            list-style: none;
            padding: 0;
        }

        .steps li {
            counter-increment: step;
            display: flex;
            gap: 15px;
            align-items: flex-start;
            margin-bottom: 14px;
        }

        .steps li::before {
            content: counter(step);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            min-width: 28px;
            background: var(--accent);
            color: #fff;
            border-radius: 50%;
            font-weight: 700;
            font-size: 12px;
            margin-top: 2px;
        }

        .steps li div {
            flex: 1;
        }

        .steps li strong {
            color: var(--primary);
            display: block;
        }

        /* Caixas */
        .info-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-left: 4px solid var(--accent);
            border-radius: 6px;
            padding: 14px 18px;
            margin: 12px 0 20px;
        }

        .warning-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-left: 4px solid #f59e0b;
            border-radius: 6px;
            padding: 14px 18px;
            margin: 12px 0 20px;
        }

        .success-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-left: 4px solid var(--accent-green);
            border-radius: 6px;
            padding: 14px 18px;
            margin: 12px 0 20px;
        }

        .info-box strong,
        .warning-box strong,
        .success-box strong {
            display: block;
            margin-bottom: 4px;
        }

        /* Perfil card */
        .portal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 15px 0 25px;
        }

        .portal-card {
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 18px;
            background: var(--light);
        }

        .portal-card .portal-icon {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .portal-card h4 {
            margin: 0 0 8px;
            color: var(--primary);
            font-size: 14px;
        }

        .portal-card p {
            font-size: 12px;
            color: var(--muted);
            margin: 0;
        }

        /* FAQ */
        .faq-item {
            border: 1px solid var(--border);
            border-radius: 6px;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .faq-q {
            background: var(--light);
            padding: 12px 16px;
            font-weight: 600;
            color: var(--primary);
            font-size: 13px;
        }

        .faq-a {
            padding: 12px 16px;
            font-size: 13px;
            border-top: 1px solid var(--border);
        }

        /* Rodapé */
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            font-size: 11px;
            color: var(--muted);
            display: flex;
            justify-content: space-between;
        }

        .no-print {
            background: #fefce8;
            border: 1px solid #fde047;
            border-radius: 6px;
            padding: 10px 20px;
            margin-bottom: 30px;
            text-align: center;
            font-size: 13px;
        }

        @media print {
            body {
                padding: 30px 40px;
            }

            .no-print {
                display: none;
            }

            .portal-grid {
                break-inside: avoid;
            }
        }
    </style>
</head>

<body>



    <div class="cover">
        <div class="cover-left">
            <div class="logo">GREEN HARD &amp; <span>SOFTH</span></div>
            <h1>Manual do Utilizador</h1>
            <p>Guia completo de uso para Estudantes, Professores e Secretaria</p>
        </div>
        <div class="cover-right">
            <div class="version">v5.0</div><br>
            <strong>Data:</strong> Março 2026<br>
            <strong>Público-Alvo:</strong> Todos os Utilizadores<br>
            <strong>Autor:</strong> Diosives Crobute
        </div>
    </div>

    <h2>1. Introdução e Acesso à Plataforma</h2>
    <p>A plataforma GHS está acessível via browser (Google Chrome, Firefox, Edge) no endereço configurado pela
        instituição. O processo de autenticação é protegido por múltiplas camadas de segurança e inclui validação
        institucional.</p>

    <h3>1.1 Como Fazer Login</h3>
    <ol class="steps">
        <li>
            <div><strong>Aceda ao Endereço Institucional</strong> Abra o browser e navegue para o endereço fornecido
                pela secretaria (ex: <code>https://ghs.escola.ao/auth</code>).</div>
        </li>
        <li>
            <div><strong>Introduza as Suas Credenciais</strong> Preencha o campo de Email e Password com os dados
                recebidos no momento da matrícula ou contratação.</div>
        </li>
        <li>
            <div><strong>Resolva o Desafio de Segurança</strong> Se solicitado, introduza o código CAPTCHA apresentado
                na imagem. Este passo protege a sua conta contra acessos automáticos.</div>
        </li>
        <li>
            <div><strong>Será Redirecionado Automaticamente</strong> O sistema identifica o seu perfil (Estudante,
                Professor, Secretaria ou Administrador) e abre o portal correspondente.</div>
        </li>
    </ol>

    <div class="warning-box">
        <strong>⚠️ Primeiro Acesso:</strong> Na primeira vez que aceder, será obrigado a alterar a password temporária
        fornecida pela secretaria. Escolha uma password com pelo menos 8 caracteres.
    </div>

    <h2>2. Portal do Estudante</h2>
    <p>O portal do estudante é o ponto central de auto-serviço académico e financeiro. Está organizado em separadores
        temáticos acessíveis a partir do menu lateral.</p>

    <h3>2.1 Dashboard e Notificações</h3>
    <p>Ao entrar, o aluno vê um painel resumo com o estado atual das propinas, os comunicados não lidos e os alertas
        académicos importantes. Comunicados urgentes da direção aparecem destacados no topo do ecrã.</p>

    <h3>2.2 Submissão de Matrícula (Novo Aluno)</h3>
    <ol class="steps">
        <li>
            <div><strong>Aceda a "Nova Matrícula"</strong> No menu lateral, clique em "Matrículas" e de seguida em
                "Submeter Nova Matrícula".</div>
        </li>
        <li>
            <div><strong>Selecione o Ano e Turno</strong> Escolha o ano curricular pretendido (1º ao 4º Ano) e o turno
                disponível (Manhã, Tarde ou Noite).</div>
        </li>
        <li>
            <div><strong>Faça Upload dos Documentos</strong> Anexe os documentos obrigatórios — Bilhete de Identidade,
                Certificado de Habilitações e Comprovativo de Pagamento da Inscrição. Apenas PDF e imagens são aceites.
            </div>
        </li>
        <li>
            <div><strong>Aguarde a Validação</strong> A secretaria irá analisar os seus documentos e aprovar ou rejeitar
                a candidatura com uma justificação. Receberá uma notificação no portal.</div>
        </li>
    </ol>

    <h3>2.3 Consulta de Notas e Pautas</h3>
    <p>No separador "Notas", o aluno pode visualizar as suas avaliações organizadas por disciplina. A estrutura da pauta
        inclui:</p>
    <table>
        <thead>
            <tr>
                <th>Componente</th>
                <th>Peso</th>
                <th>Descrição</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>AC1, AC2, AC3, AC4</td>
                <td>Avaliação Contínua</td>
                <td>Testes e trabalhos ao longo do semestre, lançados pelo docente.</td>
            </tr>
            <tr>
                <td>Exame Final</td>
                <td>Exame</td>
                <td>Avaliação final do semestre. Determina a nota final junto com o AC.</td>
            </tr>
            <tr>
                <td>Nota Final</td>
                <td><strong>(AC + Exame) ÷ 2</strong></td>
                <td>Média aritmética. Valor ≥ 12 = Aprovado. Entre 8-11 = Recurso. &lt; 8 = Reprovado.</td>
            </tr>
        </tbody>
    </table>

    <div class="info-box">
        <strong>💡 Reclamação de Nota:</strong> Se discordar de uma avaliação, pode submeter uma reclamação diretamente
        na página de notas. O professor receberá a reclamação e deverá responder no prazo definido. Cada aluno tem um
        limite de reclamações por disciplina.
    </div>

    <h3>2.4 Horários Dinâmicos e Interativos (Novo)</h3>
    <p>O separador "Horários" apresenta a grade semanal completa da turma do aluno, com informação sobre a disciplina, o
        docente responsável e a sala atribuída. O horário é gerado automaticamente pela administração e atualizado
        sempre que houver alterações.</p>

    <h3>2.5 Histórico Académico Global (Novo)</h3>
    <p>O separador "Histórico" apresenta o percurso académico completo e vitalício do aluno na instituição. Para cada
        ano letivo e semestre, são mostradas todas as disciplinas concluídas com a respetiva nota final e o estatuto
        (Aprovado/Reprovado). Este registo é imutável e pode servir de base para emissão de certidões.</p>

    <h3>2.6 Gestão de Propinas e Pagamentos</h3>
    <ol class="steps">
        <li>
            <div><strong>Consulte a Situação Financeira</strong> No separador "Financeiro", veja o extrato de propinas,
                os valores em dívida e os pagamentos já validados.</div>
        </li>
        <li>
            <div><strong>Efetue o Depósito Bancário</strong> Realize a transferência para o NIB da instituição indicado
                na plataforma.</div>
        </li>
        <li>
            <div><strong>Submeta o Comprovativo</strong> Faça o upload do talão de transferência ou depósito em
                "Submeter Pagamento".</div>
        </li>
        <li>
            <div><strong>Aguarde a Validação</strong> A tesouraria irá confirmar o pagamento. Após aprovação, receberá
                um recibo digital disponível para download.</div>
        </li>
    </ol>

    <h3>2.7 Certificados de Mérito (Novo)</h3>
    <p>Se o aluno integrar o ranking dos melhores do semestre ou da escola, o seu certificado de mérito estará visível
        no separador "Conquistas" com a medalha correspondente (🥇 🥈 🥉). O certificado é emitido pela direção e pode
        ser partilhado digitalmente.</p>

    <h2>3. Portal do Professor</h2>

    <h3>3.1 Lançamento de Notas</h3>
    <p>No separador "Pautas", o professor seleciona a turma e a disciplina, e preenche as notas por tipo de avaliação
        (AC1 a AC4 e Exame Final). O sistema calcula automaticamente a nota final e identifica alunos em situação de
        recurso ou reprovação.</p>
    <div class="success-box">
        <strong>✅ Validação em Tempo Real:</strong> Ao inserir as notas, o sistema valida os valores automaticamente e
        alerta para possíveis erros (ex: nota fora do intervalo 0-20). As notas só ficam visíveis para os alunos após
        confirmação do docente.
    </div>

    <h3>3.2 Sumários Digitais e Registo de Faltas</h3>
    <p>Após cada aula, o professor deve registar o sumário digital descrevendo os conteúdos lecionados e marcar as
        presenças dos alunos. As faltas são contabilizadas automaticamente e ficam visíveis no portal do aluno.</p>

    <h3>3.3 Resposta a Reclamações de Notas</h3>
    <p>No separador "Reclamações", o docente visualiza todas as reclamações submetidas pelos alunos às suas disciplinas.
        Para cada reclamação, poderá manter a nota (com justificação escrita) ou propor uma alteração que fica sujeita a
        aprovação pela administração.</p>

    <h3>3.4 Consulta de Horários</h3>
    <p>O professor tem acesso ao seu horário semanal completo com as turmas, disciplinas e salas atribuídas. Este
        horário é configurado pela administração e pode ser consultado em qualquer momento no portal.</p>

    <h2>4. Portal da Secretaria e Tesouraria</h2>

    <h3>4.1 Validação de Matrículas com Integrador Visual</h3>
    <p>A lista de matrículas pendentes está disponível no separador "Matrículas". Para cada candidatura, a secretaria
        pode clicar no ícone de "visualizar" para abrir o <strong>Integrador Visual Documental</strong> — uma interface
        nativa que apresenta os documentos do aluno (B.I., Certificados) diretamente no browser via PDF.js, sem
        necessidade de descarregar os ficheiros.</p>
    <ul>
        <li>Use os botões de navegação para alternar entre documentos.</li>
        <li>Utilize o zoom e a rotação integrados para verificar os detalhes.</li>
        <li>Clique em <strong>"Aprovar"</strong> para ativar a conta do aluno e alocá-lo automaticamente à turma
            compatível.</li>
        <li>Clique em <strong>"Rejeitar"</strong> e escreva a justificação para que o aluno seja notificado.</li>
    </ul>

    <h3>4.2 Registo Manual de Pagamentos (Novo)</h3>
    <p>Para pagamentos presenciais (depósitos em balcão), a secretaria pode registar o pagamento manualmente no
        separador "Tesouraria > Registar Pagamento". O sistema gera automaticamente um recibo digital com número de
        série e atualiza o extrato financeiro do aluno de forma imediata.</p>

    <h2>5. Funcionalidades Comuns a Todos os Portais</h2>

    <h3>5.1 Sistema de Comunicados com Rastreamento</h3>
    <p>A Secretaria e a Administração podem publicar comunicados que aparecem em todos os portais. Cada comunicado
        inclui um sistema de <strong>Read Tracking</strong> — o sistema regista quem leu e quando, garantindo que a
        informação crítica foi recebida. Os comunicados expiram automaticamente ao fim de 7 dias para manter as
        interfaces limpas.</p>

    <h3>5.2 Visualização de Documentos (PDF.js)</h3>
    <p>A plataforma integra um visualizador de documentos PDF nativo no browser, sem que os ficheiros sejam
        descarregados para o computador. Funcionalidades disponíveis:</p>
    <ul>
        <li><strong>Zoom In/Out</strong>: Amplie para verificar detalhes de documentos oficiais.</li>
        <li><strong>Rotação</strong>: Corrija a orientação de documentos digitalizados.</li>
        <li><strong>Navegação por Páginas</strong>: Percorra documentos multi-página com facilidade.</li>
    </ul>

    <h2>6. Perguntas Frequentes (FAQ)</h2>

    <div class="faq-item">
        <div class="faq-q">❓ Esqueci a minha password. O que devo fazer?</div>
        <div class="faq-a">Contacte a secretaria da instituição pessoalmente ou por email. Um administrativo irá efetuar
            o reset da password e fornecer-lhe novas credenciais temporárias.</div>
    </div>

    <div class="faq-item">
        <div class="faq-q">❓ A minha matrícula está "Pendente" há vários dias. É normal?</div>
        <div class="faq-a">O prazo de análise pode variar. Se após 5 dias úteis não receber resposta, contacte a
            secretaria referenciando o número da sua matrícula visível na plataforma.</div>
    </div>

    <div class="faq-item">
        <div class="faq-q">❓ Submeti o pagamento mas o meu acesso ainda está bloqueado. Porquê?</div>
        <div class="faq-a">O comprovativo precisa de ser validado manualmente pela tesouraria. Após a validação, o
            acesso é ativado automaticamente. Aguarde 1 dia útil após a submissão.</div>
    </div>

    <div class="faq-item">
        <div class="faq-q">❓ Como sei se fui aprovado ou se preciso de fazer exame de recurso?</div>
        <div class="faq-a">Após o lançamento de todas as notas pelo docente, o seu portal mostrará o estatuto de cada
            disciplina: "Aprovado", "Recurso" ou "Reprovado". Receberá também uma notificação automática.</div>
    </div>

    <div class="faq-item">
        <div class="faq-q">❓ O meu histórico académico mostra dados incorretos. O que fazer?</div>
        <div class="faq-a">Os dados do Histórico Global são calculados automaticamente com base nas notas lançadas pelos
            docentes. Em caso de erro, dirija-se à secretaria com o comprovativo de avaliação original.</div>
    </div>

    <div class="footer">
        <span>&copy; 2026 Green Hard &amp; Softh — Escola Superior de Informática. <strong>By Diosives
                Crobute</strong></span>
        <span>Manual do Utilizador v5.0</span>
    </div>

</body>

</html>