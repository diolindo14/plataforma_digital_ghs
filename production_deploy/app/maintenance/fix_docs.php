<?php
require_once 'core/Database.php';
$db = Database::getInstance();

$upload_dir = __DIR__ . '/public/uploads/matriculas/';
if (!is_dir($upload_dir)) {
    die("Upload directory not found.");
}

$files = scandir($upload_dir);
$count = 0;

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    
    // Pattern: {matricula_id}_{field}.{ext}
    // Fields: doc_bi, doc_foto, doc_cert, doc_comprovativo
    if (preg_match('/^(\d+)_(doc_\w+)\.\w+$/', $file, $matches)) {
        $mid = $matches[1];
        $field = $matches[2];
        
        $types = [
            'doc_bi' => 'BI',
            'doc_foto' => 'Fotografia',
            'doc_cert' => 'Certificado',
            'doc_comprovativo' => 'Comprovativo_Pagamento'
        ];
        
        if (isset($types[$field])) {
            $tipo = $types[$field];
            
            // Check if already exists
            $stmtCheck = $db->prepare("SELECT id FROM documentos_matricula WHERE matricula_id = :mid AND tipo_documento = :tipo");
            $stmtCheck->execute([':mid' => $mid, ':tipo' => $tipo]);
            if (!$stmtCheck->fetch()) {
                $stmt = $db->prepare("INSERT INTO documentos_matricula (matricula_id, tipo_documento, nome_arquivo, caminho_arquivo) VALUES (:mid, :tipo, :nome, :caminho)");
                if ($stmt->execute([
                    ':mid' => $mid,
                    ':tipo' => $tipo,
                    ':nome' => $file,
                    ':caminho' => $upload_dir . $file
                ])) {
                    $count++;
                }
            }
        }
    }
}

echo "Procedimento concluído. $count documentos vinculados.";
