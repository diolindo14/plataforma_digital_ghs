# Documentação Técnica: Green Hard & Softh (GHS)

## 1. Arquitetura e Estrutura (Padrão MVC)
O GHS foi programado e desenhado estritamente sobre **PHP 8.x** (sem frameworks redundantes - ex: Laravel/CodeIgniter) adotando o Padrão de Projeto Clássico **MVC (Model-View-Controller)**:
- **`app/core/`**: Motor do ecossistema. Contém as Classes abstratas essencias (`App.php`, `Controller.php`, `Database.php`). O encaminhamento (routing) processa parâmetros de URL (`/controller/método/arg`) via um `.htaccess` unificado.
- **`app/controllers/`**: Orquestra a requisição. Os controladores (`AdminController`, `ProfessorController`, `EstudanteController`, `AuthController` e departamentos) instanciam modelos e validam payloads em tempo real (`$_POST`, JSON).
- **`app/models/`**: Camada abstrata puramente baseada em Data Access Objects nativos com **PDO (PHP Data Objects)**. Usa `prepare()` para evitar Injeções de SQL.
- **`app/views/`**: Camada de Apresentação (UI). Usa HTML5, Bootstrap 5 e Ionicons puramente processados do lado do servidor via output-buffering em templates base.

## 2. Diagrama Lógico de Base de Dados (ghsespf_db)
A base de dados MySQL estrutura a lógica empresarial da escola a fundo:
- `utilizadores`: Centraliza Sessão/Hash/Tipo de Acesso e Perfil. Todos os dados são chaves estrangeiras (`user_id`). Relacionado aos sub-perfis: `estudantes`, `professores`.
- `matriculas` / `turmas` / `disciplinas` / `horarios`: A matriz multidimensional de relacionamento entre alunos, salas e tempos letivos.
- `notas` e `frequencia`: Registo atómico diário de cada transação de avaliação com logs de sumários anexáveis.
- `pagamentos`: Guarda tokens de validação para fluxos de aprovação de caixa financeiro.

## 3. Matriz de Segurança e Autenticação
1. Sequestro de Sessão e **XSS** mitigado via `htmlspecialchars()` implícito na camada `.php` de apresentação para prever *Script Injections*.
2. Proteção Cross-Site Request Forgery **(CSRF)** rigorosa incluída em todos os envios de formulários web (via gerador de token no ficheiro central `Controller.php` - `verifyCsrfToken()`).
3. Auditoria nativa de Log em Ficheiro e Base de dados: As rotas rastreiam acessos não autorizados. Funções sensíveis (`getEventosAjax`) usam `finfo_file` (Mime-Types dinâmicos baseados no tipo do Ficheiro) para bloqueio estrito contra carregamentos `.exe`, `.php` encapotados.

## 4. Endpoints e Rotas Notáveis (AJAX/Fetch)
- `AdminController::getEventosAjax`: Devolve a API `JSON` contendo a Array agrupada de eventos globais processados do SQL para o visualizador (FullCalendar / Listas de Dashboards) a partir da tabela `eventos` com `data_evento` e `data_fim` visual gerado on-the-fly (`data_evento_display`).
- `ProfessorController::uploadMaterial`: Lidar com `multipart/form-data`, validando `finfo` binariamente (extensões `.pdf`, `.pptx`). Responde também em `JSON`.

## 5. Implementação Funcional Complexa de Referência
**Deduplicação de Eventos Multi-Dias**: A plataforma GHS tem listagens longas consolidadas dinamicamente, permitindo popular `eventos` linha-a-linha sem poluir as tabelas de Agendamentos. 
```php
foreach($raw as $ev) {
    if(!isset($grouped[$key])) { $ev['data_fim'] = $ev['data_evento']; ... }
    ...
```
Esta filtragem inteligente de apresentação decorre puramente na Camada da Controller.
