<?php
class SecretariaController extends Controller {
    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'secretaria') {
            header('Location: /green/auth');
            exit;
        }
    }

    public function index() {
        $matriculaModel = $this->model('Matricula');
        $pagamentoModel = $this->model('Pagamento');
        $comunicadoModel = $this->model('Comunicado');
        $dashboardModel = $this->model('DashboardModel');

        $data = [
            'nome' => $_SESSION['user_name'] ?? 'Secretaria',
            'stats' => $dashboardModel->getSecretariaStats(),
            'matriculas_pendentes' => $matriculaModel->getPendingEnrollments(),
            'pagamentos_pendentes' => $pagamentoModel->getAll(), // Simplified for now, can filter if needed
            'comunicados' => $comunicadoModel->getAll(),
            'tipos_pagamento' => $pagamentoModel->getTiposPagamento(),
            'estudantes' => $this->model('Estudante')->getAllStudents()
        ];

        // Filter payments to keep only those pending validation if preferred
        $data['pagamentos_validar'] = array_filter($data['pagamentos_pendentes'], function($p) {
            return $p['status'] === 'Pendente' && !empty($p['comprovativo_arquivo']);
        });

        $this->view('secretaria/index', $data);
    }

    public function approveMatricula($id) {
        $this->verifyCsrfToken();
        $model = $this->model('Matricula');
        if ($model->updateStatus($id, 'Aprovada', $_SESSION['user_id'])) {
            $this->logActivity('Aprovar Matrícula', ['matricula_id' => $id]);
            $_SESSION['flash_success'] = "Matrícula aprovada com sucesso.";
        } else {
            $_SESSION['flash_error'] = "Erro ao aprovar matrícula.";
        }
        header('Location: /green/secretaria');
    }

    public function rejectMatricula($id) {
        $this->verifyCsrfToken();
        $model = $this->model('Matricula');
        $motivo = $_POST['motivo'] ?? 'Documentação incompleta';
        if ($model->updateStatus($id, 'Rejeitada', $_SESSION['user_id'], $motivo)) {
            $this->logActivity('Rejeitar Matrícula', ['matricula_id' => $id, 'motivo' => $motivo]);
            $_SESSION['flash_success'] = "Matrícula rejeitada.";
        } else {
            $_SESSION['flash_error'] = "Erro ao rejeitar matrícula.";
        }
        header('Location: /green/secretaria');
    }

    public function validatePayment($id) {
        $this->verifyCsrfToken();
        $model = $this->model('Pagamento');
        if ($model->aprovarPagamento($id, $_SESSION['user_id'])) {
            $this->logActivity('Validar Pagamento', ['pagamento_id' => $id]);
            $_SESSION['flash_success'] = "Pagamento validado com sucesso.";
        } else {
            $_SESSION['flash_error'] = "Erro ao validar pagamento.";
        }
        header('Location: /green/secretaria');
    }

    public function saveComunicado() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->verifyCsrfToken();
            $model = $this->model('Comunicado');
            if ($model->create($_POST)) {
                $_SESSION['flash_success'] = "Comunicado enviado.";
            } else {
                $_SESSION['flash_error'] = "Erro ao enviar comunicado.";
            }
            header('Location: /green/secretaria');
        }
    }
}
