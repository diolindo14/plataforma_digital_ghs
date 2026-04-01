<?php
/**
 * ContestacaoController.php — HTTP Layer para o fluxo completo de contestação
 *
 * Rotas disponíveis:
 *   POST /contestacao/abrir             — Aluno abre contestação
 *   POST /contestacao/responder         — Professor responde
 *   POST /contestacao/reagir            — Aluno aceita ou contra-argumenta
 *   POST /contestacao/convocar          — Admin convoca partes
 *   POST /contestacao/decidir           — Admin registra decisão final
 *   POST /contestacao/concordar         — Aluno concorda (legado compatível)
 *   GET  /contestacao/detalhes/:eid/:did — AJAX: detalhes de uma contestação
 */
class ContestacaoController extends Controller {

    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . URL_ROOT . '/auth');
            exit;
        }
        // Gerar CSRF se ainda não existe
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    // ────────────────────────────────────────────────────────
    // PASSO 1: Aluno abre uma contestação
    // ────────────────────────────────────────────────────────
    public function abrir() {
        $this->_requireRole('aluno');
        $this->_requireCsrf();

        $estudante_id    = (int) ($_POST['estudante_id']    ?? 0);
        $turma_id        = (int) ($_POST['turma_id']        ?? 0);
        $disciplina_id   = (int) ($_POST['disciplina_id']   ?? 0);
        $justificativa   = trim($_POST['justificativa']     ?? '');

        if (!$estudante_id || !$turma_id || !$disciplina_id) {
            $this->_jsonError('Dados incompletos.');
        }

        $model  = $this->model('Contestacao');
        $result = $model->abrir($estudante_id, $turma_id, $disciplina_id, $justificativa);

        $this->_jsonResponse($result);
    }

    // ────────────────────────────────────────────────────────
    // PASSO 2: Professor responde à contestação
    // ────────────────────────────────────────────────────────
    public function responder() {
        $this->_requireRole('professor');
        $this->_requireCsrf();

        $estudante_id  = (int)   ($_POST['estudante_id']  ?? 0);
        $turma_id      = (int)   ($_POST['turma_id']      ?? 0);
        $disciplina_id = (int)   ($_POST['disciplina_id'] ?? 0);
        $resposta      = trim(   $_POST['resposta']       ?? '');
        $alterou_nota  = isset($_POST['alterou_nota']) && $_POST['alterou_nota'] === '1';

        if (empty($resposta)) {
            $this->_jsonError('A resposta não pode estar vazia.');
        }



        $model  = $this->model('Contestacao');
        $result = $model->responderDocente(
            $estudante_id, $turma_id, $disciplina_id,
            $_SESSION['user_id'], $resposta, $alterou_nota
        );

        $this->_jsonResponse($result);
    }

    // ────────────────────────────────────────────────────────
    // PASSO 3 & 4: Aluno reage (aceita ou contra-argumenta)
    // ────────────────────────────────────────────────────────
    public function reagir() {
        $this->_requireRole('aluno');
        $this->_requireCsrf();

        $estudante_id    = (int) ($_POST['estudante_id']    ?? 0);
        $turma_id        = (int) ($_POST['turma_id']        ?? 0);
        $disciplina_id   = (int) ($_POST['disciplina_id']   ?? 0);
        $acao            = trim( $_POST['acao']             ?? '');
        $contra_argumento = trim($_POST['contra_argumento'] ?? '');

        if (!in_array($acao, ['aceitar', 'contra_argumentar'])) {
            $this->_jsonError('Acção inválida.');
        }

        $model  = $this->model('Contestacao');
        $result = $model->reagirAluno(
            $estudante_id, $turma_id, $disciplina_id, $acao, $contra_argumento
        );

        $this->_jsonResponse($result);
    }

    // ────────────────────────────────────────────────────────
    // PASSO 6: Admin convoca partes
    // ────────────────────────────────────────────────────────
    public function convocar() {
        $this->_requireRole('admin');
        $this->_requireCsrf();

        $estudante_id    = (int) ($_POST['estudante_id']    ?? 0);
        $disciplina_id   = (int) ($_POST['disciplina_id']   ?? 0);
        $data_reuniao    = trim( $_POST['data_reuniao']     ?? '');
        $hora_reuniao    = trim( $_POST['hora_reuniao']     ?? '');
        $local           = trim( $_POST['local_reuniao']    ?? '');
        $motivo          = trim( $_POST['motivo_convocacao']?? '');

        if (!$estudante_id || !$disciplina_id || !$data_reuniao || !$hora_reuniao || empty($local)) {
            $this->_jsonError('Todos os campos da convocatória são obrigatórios.');
        }

        $model  = $this->model('Contestacao');
        $result = $model->convocarPartes(
            $estudante_id, $disciplina_id, $_SESSION['user_id'],
            $data_reuniao, $hora_reuniao, $local, $motivo
        );

        if ($result['success']) {
            $_SESSION['flash_success'] = $result['message'];
        } else {
            $_SESSION['flash_error'] = $result['message'];
        }
        header('Location: ' . URL_ROOT . '/admin');
        exit;
    }

    // ────────────────────────────────────────────────────────
    // PASSO 7 & 8: Admin registra decisão e encerra
    // ────────────────────────────────────────────────────────
    public function decidir() {
        $this->_requireRole('admin');
        $this->_requireCsrf();

        $estudante_id       = (int) ($_POST['estudante_id']       ?? 0);
        $disciplina_id      = (int) ($_POST['disciplina_id']      ?? 0);
        $decisao            = trim( $_POST['decisao_final']       ?? '');
        $presenca_aluno     = isset($_POST['presenca_aluno'])     ? 1 : 0;
        $presenca_professor = isset($_POST['presenca_professor']) ? 1 : 0;

        if (empty($decisao)) {
            $_SESSION['flash_error'] = 'A decisão final é obrigatória.';
            header('Location: ' . URL_ROOT . '/admin');
            exit;
        }

        $model  = $this->model('Contestacao');
        $result = $model->registrarDecisao(
            $estudante_id, $disciplina_id, $_SESSION['user_id'],
            $decisao, $presenca_aluno, $presenca_professor
        );

        $_SESSION[$result['success'] ? 'flash_success' : 'flash_error'] = $result['message'];
        header('Location: ' . URL_ROOT . '/admin');
        exit;
    }

    // ────────────────────────────────────────────────────────
    // AJAX: Detalhes de uma contestação
    // ────────────────────────────────────────────────────────
    public function detalhes($estudante_id, $disciplina_id) {
        header('Content-Type: application/json');
        $db = Database::getInstance();

        $stmt = $db->prepare("
            SELECT cn.*, d.nome as disciplina_nome, t.codigo as turma_codigo,
                   u_est.nome_completo as estudante_nome,
                   u_prof.nome_completo as professor_nome,
                   u_med.nome_completo as mediador_nome
            FROM concordancia_notas cn
            JOIN estudantes e ON cn.estudante_id = e.id
            JOIN utilizadores u_est ON e.utilizador_id = u_est.id
            JOIN disciplinas d ON cn.disciplina_id = d.id
            JOIN turmas t ON cn.turma_id = t.id
            LEFT JOIN professor_disciplina pd ON pd.disciplina_id = cn.disciplina_id AND pd.turma_id = cn.turma_id
            LEFT JOIN professores p ON pd.professor_id = p.id
            LEFT JOIN utilizadores u_prof ON p.utilizador_id = u_prof.id
            LEFT JOIN utilizadores u_med ON cn.mediado_por = u_med.id
            WHERE cn.estudante_id = :eid AND cn.disciplina_id = :did
            ORDER BY cn.data_resposta DESC LIMIT 1
        ");
        $stmt->execute([':eid' => (int)$estudante_id, ':did' => (int)$disciplina_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode($data ?: ['error' => 'Não encontrado']);
        exit;
    }

    // ────────────────────────────────────────────────────────
    // HELPERS PRIVADOS
    // ────────────────────────────────────────────────────────
    private function _requireRole($role) {
        if (($_SESSION['user_role'] ?? '') !== $role) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Acesso não autorizado.']);
            exit;
        }
    }

    private function _requireCsrf() {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            $this->_jsonError('Token de segurança inválido. Recarregue a página.');
        }
    }

    private function _jsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    private function _jsonError($message, $code = 400) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $message]);
        exit;
    }
}
