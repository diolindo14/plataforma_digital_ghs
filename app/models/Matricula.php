<?php
/**
 * Modelo Matricula
 * 
 * @package Models
 * @author Senior Software Engineer / Mentor
 * 
 * Documentação Funcional:
 * Este modelo gere o ciclo de vida do estudante na instituição, desde a submissão de documentos
 * até ao cálculo complexo de progressão académica (Aprovação, Recurso ou Reprovação).
 */
class Matricula {
    /** @var PDO Conexão com a base de dados */
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Cria uma nova intenção de matrícula (Pendente).
     * 
     * @param array $data Dados do formulário (ano_id, turno, tipo, etc).
     * @return int|false ID da matrícula criada ou false.
     * 
     * // Identificação de Pontos Cegos: Falta validar se o aluno já tem uma matrícula
     * // 'Pendente' ou 'Aprovada' para o mesmo ano letivo. Isso evita duplicados.
     */
    public function createEnrollment($data) {
        $stmt = $this->db->prepare("INSERT INTO matriculas (estudante_id, ano_letivo, ano_curso_id, especializacao_id, turno, tipo, status, data_matricula, observacoes) 
                                    VALUES (:estudante_id, :ano_letivo, :ano_id, :esp_id, :turno, :tipo, 'Pendente', NOW(), :obs)");
        
        $stmt->bindValue(':estudante_id', $data['user_id']);
        $stmt->bindValue(':ano_letivo', date('Y'));
        $stmt->bindValue(':ano_id', $data['ano_id']);
        $stmt->bindValue(':esp_id', $data['especializacao_id'] ?? null);
        $stmt->bindValue(':turno', $data['turno']);
        $stmt->bindValue(':tipo', $data['tipo']);
        $stmt->bindValue(':obs', $data['motivo'] ?? '');
        
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Recupera matrículas que aguardam revisão da Secretaria/Admin.
     * 
     * // Análise de Performance: O uso de subconsultas correlacionadas (SELECT dentro de SELECT) 
     * // para buscar arquivos pode tornar a listagem lenta se houver muitos registros.
     * // Sugestão: Usar LEFT JOIN com a tabela de documentos.
     */
    public function getPendingEnrollments() {
        $stmt = $this->db->prepare("
            SELECT m.*, u.nome_completo as nome,
                   (SELECT nome_arquivo FROM documentos_matricula WHERE matricula_id = m.id AND tipo_documento = 'BI' LIMIT 1) as bi_arquivo,
                   (SELECT nome_arquivo FROM documentos_matricula WHERE matricula_id = m.id AND tipo_documento = 'Fotografia' LIMIT 1) as foto_arquivo,
                   (SELECT nome_arquivo FROM documentos_matricula WHERE matricula_id = m.id AND tipo_documento = 'Certificado' LIMIT 1) as certificado_arquivo,
                   (SELECT nome_arquivo FROM documentos_matricula WHERE matricula_id = m.id AND tipo_documento = 'Comprovativo_Pagamento' LIMIT 1) as comprovativo_arquivo
            FROM matriculas m 
            JOIN estudantes e ON m.estudante_id = e.id 
            JOIN utilizadores u ON e.utilizador_id = u.id 
            WHERE m.status = 'Pendente' OR m.status = 'Em validacao'
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Altera o estado da matrícula (Aprovação/Rejeição).
     */
    public function updateStatus($id, $status, $admin_id, $motivo = null) {
        // // Clean Code: Construção dinâmica de SQL. Embora funcional, 
        // // uma abordagem de Query Builder tornaria o código mais legível.
        $sql = "UPDATE matriculas SET status = :status, aprovado_por = :admin_id, data_aprovacao = NOW()";
        if ($motivo) {
            $sql .= ", motivo_rejeicao = :motivo";
        }
        $sql .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':admin_id', $admin_id);
        if ($motivo) $stmt->bindValue(':motivo', $motivo);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    /**
     * Aloca o estudante numa turma específica após aprovação.
     * 
     * // Sugestão: Validar se a turma tem vagas disponíveis e se o turno 
     * // da turma coincide com o turno da matrícula.
     */
    public function assignToTurma($id, $turma_id) {
        $stmt = $this->db->prepare("UPDATE matriculas SET turma_id = :tid WHERE id = :id");
        return $stmt->execute([':tid' => $turma_id, ':id' => $id]);
    }

    /**
     * Recupera listagem de alunos aprovados que ainda aguardam turma.
     */
    public function getApprovedWithoutTurma() {
        $stmt = $this->db->prepare("
            SELECT m.*, u.nome_completo as estudante_nome, a.nome as ano_nome
            FROM matriculas m
            JOIN estudantes e ON m.estudante_id = e.id
            JOIN utilizadores u ON e.utilizador_id = u.id
            JOIN anos a ON m.ano_curso_id = a.id
            WHERE m.status = 'Aprovada' AND m.turma_id IS NULL
            ORDER BY m.data_matricula DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Auxiliar para gestão de arquivos de documentos (Uploads).
     */
    public function saveDocument($matricula_id, $tipo, $nome, $caminho) {
        $stmt = $this->db->prepare("INSERT INTO documentos_matricula (matricula_id, tipo_documento, nome_arquivo, caminho_arquivo) 
                                 VALUES (:mid, :tipo, :nome, :caminho)");
        return $stmt->execute([
            ':mid' => $matricula_id,
            ':tipo' => $tipo,
            ':nome' => $nome,
            ':caminho' => $caminho
        ]);
    }

    /**
     * Busca informações sobre o último ano letivo aprovado do aluno.
     */
    public function getCurrentYearInfo($estudante_id) {
        $stmt = $this->db->prepare("
            SELECT m.ano_curso_id, a.nome as ano_nome, a.ordem
            FROM matriculas m
            JOIN anos a ON m.ano_curso_id = a.id
            WHERE m.estudante_id = :eid AND m.status = 'Aprovada'
            ORDER BY a.ordem DESC, m.id DESC LIMIT 1
        ");
        $stmt->execute([':eid' => $estudante_id]);
        return $stmt->fetch();
    }

    /**
     * Lógica Complexa: Cálculo do Status Académico.
     * Determina se o aluno passa de ano baseado na média ponderada e número de negativas.
     * 
     * @param int $estudante_id
     * @return array [status, can_transit, ...]
     * 
     * // Refatoração: Este método possui ALTA complexidade ciclomática. 
     * // Contém múltiplas regras de negócio (limite de 3 negativas, média de recorrencia, etc).
     * // Sugestão: Extrair estas regras para uma classe de serviço 'AcademicRulesEngine'.
     */
    public function getDetailedAcademicStatus($estudante_id) {
        $current = $this->getCurrentYearInfo($estudante_id);
        if (!$current) return ['status' => 'Pendente', 'can_transit' => false];

        // 1. Busca total de disciplinas do ano atual
        $stmtAll = $this->db->prepare("SELECT id FROM disciplinas WHERE ano_id = :aid");
        $stmtAll->execute([':aid' => $current['ano_curso_id']]);
        $allSubjects = $stmtAll->fetchAll();
        $totalSubjects = count($allSubjects);

        if ($totalSubjects == 0) return ['status' => 'Aprovado', 'can_transit' => true];

        // 2. Calcula média final por disciplina baseada na fórmula oficial (Testes + Exame/2)
        $stmtGrades = $this->db->prepare("
            SELECT d.id as disciplina_id,
                   (SUM(CASE WHEN a.tipo_avaliacao_id IN (1,2,3,4) THEN n.nota ELSE 0 END) + 
                    MAX(CASE WHEN a.tipo_avaliacao_id = 5 THEN n.nota ELSE 0 END)) / 2 as media_final
            FROM disciplinas d
            LEFT JOIN avaliacoes a ON a.disciplina_id = d.id
            LEFT JOIN notas n ON n.avaliacao_id = a.id AND n.estudante_id = :eid AND n.confirmado_admin = 1
            WHERE d.ano_id = :aid
            GROUP BY d.id
        ");
        $stmtGrades->execute([':eid' => $estudante_id, ':aid' => $current['ano_curso_id']]);
        $grades = $stmtGrades->fetchAll();

        $passedCount = 0;
        $recursoCount = 0;
        $reprovadoCount = 0;
        $missingCount = 0;

        foreach ($grades as $g) {
            $media = $g['media_final'];
            if ($media === null || $media == 0) {
                $missingCount++;
            } elseif ($media >= 12) {
                // Aprovado Direto
                $passedCount++;
            } elseif ($media >= 8) {
                // Elegível para Recurso (Exame de segunda época)
                $recursoCount++;
            } else {
                // Reprovado por Nota Insuficiente (< 8)
                $reprovadoCount++;
            }
        }

        // Caso faltem notas, o estado é pendente
        if ($missingCount > 0) return ['status' => 'Pendente', 'can_transit' => false];

        // Regra Pedagógica: Reprovado se houver nota < 8 OU mais que 3 negativas no total.
        if ($reprovadoCount > 0 || ($recursoCount + $reprovadoCount) > 3) {
            return ['status' => 'Reprovado', 'can_transit' => false, 'failed_subjects' => ($recursoCount + $reprovadoCount)];
        }

        // Se houver negativas mas entre 8 e 11, o aluno fica em recurso
        if ($recursoCount > 0) {
            return ['status' => 'Recurso', 'can_transit' => false, 'recurso_subjects' => $recursoCount];
        }

        // Aprovado com sucesso em todas as cadeiras
        return ['status' => 'Aprovado', 'can_transit' => ($passedCount == $totalSubjects)];
    }

    /**
     * Verifica se o aluno pode renovar a matrícula para o nível seguinte.
     */
    public function isEligibleForRenewal($estudante_id) {
        $status = $this->getDetailedAcademicStatus($estudante_id);
        return $status['can_transit'];
    }
}
