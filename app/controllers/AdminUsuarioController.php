<?php
/**
 * AdminUsuarioController - Gestão de RH e Utilizadores (Pilar 4)
 */
class AdminUsuarioController extends Controller {
    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . URL_ROOT . '/auth');
            exit;
        }
    }

    public function saveProfessor() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $model = $this->model('Professor');
            $id = $_POST['id'] ?? null;
            $res = $id ? $model->updateManual($id, $_POST) : $model->createManual($_POST);
            
            if ($res) {
                $_SESSION['flash_success'] = "Professor salvo com sucesso.";
            } else {
                $_SESSION['flash_error'] = "Erro ao salvar professor.";
            }
            header('Location: ' . URL_ROOT . '/admin');
            exit;
        }
    }

    public function deleteProfessor($id) {
        if ($this->model('Professor')->deleteProfessor($id)) {
            $this->logActivity('Remover Professor', ['id' => $id]);
            $_SESSION['flash_success'] = "Professor removido.";
        } else {
            $_SESSION['flash_error'] = "Erro ao remover professor.";
        }
        header('Location: ' . URL_ROOT . '/admin#pane-professores');
        exit;
    }

    public function createSecretaria() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            // ... lógica de criação (transferida do AdminController)
            $adminModel = $this->model('Administrador');
            if ($adminModel->createSecretaria($_POST)) {
                $_SESSION['flash_success'] = "Secretaria criada.";
            }
            header('Location: ' . URL_ROOT . '/admin#pane-secretaria');
            exit;
        }
    }

    public function deleteSecretaria($id) {
        if ($this->model('Administrador')->deleteSecretaria($id)) {
            $_SESSION['flash_success'] = "Removida.";
        }
        header('Location: ' . URL_ROOT . '/admin#pane-secretaria');
        exit;
    }
}
