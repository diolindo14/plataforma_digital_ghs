<?php
require_once 'core/Database.php';
$db = Database::getInstance();

$sql = "CREATE TABLE IF NOT EXISTS concordancia_notas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    estudante_id INT NOT NULL,
    turma_id INT NOT NULL,
    disciplina_id INT NOT NULL,
    status ENUM('Pendente', 'Concordado', 'Reclamado') DEFAULT 'Pendente',
    comentario TEXT,
    data_resposta DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (estudante_id) REFERENCES estudantes(id),
    FOREIGN KEY (turma_id) REFERENCES turmas(id),
    FOREIGN KEY (disciplina_id) REFERENCES disciplinas(id),
    UNIQUE KEY (estudante_id, turma_id, disciplina_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

try {
    $db->exec($sql);
    echo "Tabela concordancia_notas criada com sucesso!\n";
} catch (PDOException $e) {
    echo "Erro ao criar tabela: " . $e->getMessage() . "\n";
}
