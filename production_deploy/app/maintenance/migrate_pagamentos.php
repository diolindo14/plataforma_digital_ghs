<?php
require_once 'core/Database.php';
$db = Database::getInstance();
$needed = ['descricao', 'valor', 'comprovativo_path', 'observacoes', 'status', 'data_criacao'];
$existing = array_column($db->query("SHOW COLUMNS FROM pagamentos")->fetchAll(PDO::FETCH_ASSOC), 'Field');

foreach ($needed as $col) {
    if (!in_array($col, $existing)) {
        $sql = match($col) {
            'descricao'        => "ALTER TABLE pagamentos ADD COLUMN descricao VARCHAR(255) NULL AFTER estudante_id",
            'valor'            => "ALTER TABLE pagamentos ADD COLUMN valor DECIMAL(10,2) NULL AFTER descricao",
            'comprovativo_path'=> "ALTER TABLE pagamentos ADD COLUMN comprovativo_path VARCHAR(500) NULL AFTER valor",
            'observacoes'      => "ALTER TABLE pagamentos ADD COLUMN observacoes TEXT NULL AFTER comprovativo_path",
            'status'           => "ALTER TABLE pagamentos ADD COLUMN status ENUM('Pendente','Validado','Rejeitado') NOT NULL DEFAULT 'Pendente' AFTER observacoes",
            'data_criacao'     => "ALTER TABLE pagamentos ADD COLUMN data_criacao DATETIME NULL AFTER status",
        };
        $db->exec($sql);
        echo "ADDED: $col\n";
    } else {
        echo "OK: $col\n";
    }
}
echo "Done.\n";
