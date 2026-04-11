<?php
/**
 * AdminAcademicoController - Gestão Pedagógica (Pilar 4)
 */
class AdminAcademicoController extends Controller {
    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . URL_ROOT . '/auth');
            exit;
        }
    }

    public function saveTurma() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            if ($this->model('Turma')->createTurma($_POST)) {
                $this->logActivity('Criar Turma', ['codigo' => $_POST['codigo'] ?? 'N/A']);
                $_SESSION['flash_success'] = "Turma criada com sucesso.";
            } else {
                $_SESSION['flash_error'] = "Erro ao criar turma.";
            }
            header('Location: ' . URL_ROOT . '/admin');
            exit;
        }
    }

    public function saveAno() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $model = $this->model('Academico');
            $id = $_POST['id'] ?? null;
            $res = $id ? $model->updateAno($id, $_POST) : $model->createAno($_POST);
            
            if ($res) {
                $this->logActivity($id ? 'Atualizar Ano' : 'Criar Ano', ['id' => $id]);
                $_SESSION['flash_success'] = "Ano curricular salvo.";
            }
            header('Location: ' . URL_ROOT . '/admin');
            exit;
        }
    }

    public function deleteAno($id) {
        if ($this->model('Academico')->deleteAno($id)) {
            $this->logActivity('Remover Ano', ['id' => $id]);
            $_SESSION['flash_success'] = "Removido.";
        }
        header('Location: ' . URL_ROOT . '/admin');
        exit;
    }

    public function saveDisciplina() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $model = $this->model('Disciplina');
            $id = $_POST['id'] ?? null;
            $res = $id ? $model->update($id, $_POST) : $model->create($_POST);
            
            if ($res) {
                $this->logActivity($id ? 'Atualizar Disciplina' : 'Criar Disciplina', ['id' => $id]);
                $_SESSION['flash_success'] = "Disciplina salva.";
            }
            header('Location: ' . URL_ROOT . '/admin');
            exit;
        }
    }

    public function deleteDisciplina($id) {
        if ($this->model('Disciplina')->delete($id)) {
            $this->logActivity('Remover Disciplina', ['id' => $id]);
            $_SESSION['flash_success'] = "Removida.";
        }
        header('Location: ' . URL_ROOT . '/admin');
        exit;
    }

    public function saveEspecialidade() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $model = $this->model('Especialidade');
            $id = $_POST['id'] ?? null;
            $res = $id ? $model->updateEspecialidade($id, $_POST) : $model->createEspecialidade($_POST);
            
            if ($res) {
                $_SESSION['flash_success'] = "Especialidade salva.";
            }
            header('Location: ' . URL_ROOT . '/admin');
            exit;
        }
    }

    public function deleteEspecialidade($id) {
        if ($this->model('Especialidade')->delete($id)) {
            $this->logActivity('Remover Especialidade', ['id' => $id]);
            $_SESSION['flash_success'] = "Removida.";
        }
        header('Location: ' . URL_ROOT . '/admin');
        exit;
    }

    public function saveHorario() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $res = $this->model('Horario')->allocate($_POST);
            if ($res['success']) {
                $_SESSION['flash_success'] = "Horário alocado.";
            } else {
                $_SESSION['flash_error'] = $res['message'];
            }
            header('Location: ' . URL_ROOT . '/admin');
            exit;
        }
    }

    /**
     * AJAX: Get Horarios (Now using Partial View - Pilar 4)
     */
    public function getHorariosAjax($turma_id) {
        $horarios = $this->model('Horario')->getHorarioByTurma($turma_id);
        $this->partial('admin/partials/horario_tabela', ['horarios' => $horarios]);
    }
}
