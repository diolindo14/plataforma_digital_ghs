<?php
/**
 * AdminFinanceiroController - Gestão de Fluxo de Caixa (Pilar 4)
 */
class AdminFinanceiroController extends Controller {
    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . URL_ROOT . '/auth');
            exit;
        }
    }

    public function validarPagamento($id) {
        $this->verifyCsrfToken();
        $db = Database::getInstance(); 
        $stmt = $db->prepare("SELECT p.*, u.nome_completo FROM pagamentos p JOIN estudantes e ON p.estudante_id = e.id JOIN utilizadores u ON e.utilizador_id = u.id WHERE p.id = :id");
        $stmt->execute([':id' => $id]);
        $p = $stmt->fetch();

        $this->logActivity('Validar Pagamento Admin', ['pagamento_id' => $id]); 
        
        if ($this->model('Pagamento')->aprovarPagamento($id, $_SESSION['user_id'])) {
            // // Integração: Notifica a Secretaria (Workflow GHS)
            $notif = "O Administrador validou o pagamento de " . ($p['nome_completo'] ?? 'N/A') . " (ID: $id).";
            $this->model('Mensagem')->notifyGroup('secretaria', $notif, $_SESSION['user_id']); 
            
            $_SESSION['flash_success'] = "Pagamento validado e aprovado com sucesso.";
        } else {
            $_SESSION['flash_error'] = "Erro ao validar pagamento.";
        }
        header('Location: ' . URL_ROOT . '/admin#pane-financeiro'); 
        exit;
    }

    public function rejeitarPagamento($id) {
        $this->verifyCsrfToken();
        $motivo = $_POST['motivo'] ?? 'Documentação incompleta';
        if ($this->model('Pagamento')->rejeitarComMotivo($id, $_SESSION['user_id'], $motivo)) {
            $this->logActivity('Rejeitar Pagamento Admin', ['id' => $id]);
            $_SESSION['flash_success'] = "Pagamento rejeitado.";
        }
        header('Location: ' . URL_ROOT . '/admin#pane-financeiro');
        exit;
    }
}
