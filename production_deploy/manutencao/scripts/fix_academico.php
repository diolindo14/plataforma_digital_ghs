<?php
$file = 'app/models/Academico.php';
$content = file_get_contents($file);

$old_sql_1 = '            $sql = "
                INSERT INTO certificados_merito 
                    (estudante_id, semestre, ano_letivo, posicao, media, nivel_nome, emitido_por, data_emissao)
                VALUES 
                    (:eid, :semestre, :ano, :posicao, :media, :nivel, :emitido_por, NOW())
                ON DUPLICATE KEY UPDATE
                    posicao = VALUES(posicao),
                    media = VALUES(media),
                    nivel_nome = VALUES(nivel_nome),
                    emitido_por = VALUES(emitido_por),
                    data_emissao = NOW()
            ";';

$new_sql_1 = '            $sql = "
                INSERT INTO certificados_merito 
                    (estudante_id, semestre, ano_letivo, posicao, media, nivel_nome, emitido_por, data_emissao, status)
                VALUES 
                    (:eid, :semestre, :ano, :posicao, :media, :nivel, :emitido_por, NOW(), \'Publicado\')
                ON DUPLICATE KEY UPDATE
                    posicao = VALUES(posicao),
                    media = VALUES(media),
                    nivel_nome = VALUES(nivel_nome),
                    emitido_por = VALUES(emitido_por),
                    status = \'Publicado\',
                    data_emissao = NOW()
            ";';

// Replace all occurrences (both methods have the same SQL block)
$new_content = str_replace($old_sql_1, $new_sql_1, $content);

if ($new_content !== $content) {
    file_put_contents($file, $new_content);
    echo "Success: Updated Academico.php\n";
} else {
    echo "Error: Could not find target SQL block in Academico.php\n";
    // Debug: output a small part to check line endings
    echo "First 100 chars of target:\n" . substr($old_sql_1, 0, 100) . "\n";
}
?>
