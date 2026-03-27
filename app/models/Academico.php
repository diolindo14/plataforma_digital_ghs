<?php
/**
 * Modelo Académico - Motor de Regras e Performance Estudantil.
 * 
 * Centraliza a lógica de anos letivos, processamento de pautas (notas) 
 * e consolidação de históricos académicos.
 */
class Academico {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Lista os níveis/anos configurados na instituição.
     */
    public function getAnos() {
        $stmt = $this->db->prepare("SELECT * FROM anos ORDER BY ordem ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Recupera um ano específico pelo ID.
     */
    public function getAnoById($id) {
        $stmt = $this->db->prepare("SELECT * FROM anos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Cria um novo nível académico/ano.
     */
    public function createAno($data) {
        $stmt = $this->db->prepare("INSERT INTO anos (numero, nome, descricao, mensalidade, ordem) 
                                    VALUES (:numero, :nome, :descricao, :mensalidade, :ordem)");
        return $stmt->execute([
            ':numero' => $data['numero'],
            ':nome' => $data['nome'],
            ':descricao' => $data['descricao'],
            ':mensalidade' => $data['mensalidade'],
            ':ordem' => $data['ordem']
        ]);
    }

    /**
     * Atualiza configurações de um ano (ex: valor da mensalidade).
     */
    public function updateAno($id, $data) {
        $stmt = $this->db->prepare("UPDATE anos SET numero = :numero, nome = :nome, descricao = :descricao, 
                                    mensalidade = :mensalidade, ordem = :ordem WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':numero' => $data['numero'],
            ':nome' => $data['nome'],
            ':descricao' => $data['descricao'],
            ':mensalidade' => $data['mensalidade'],
            ':ordem' => $data['ordem']
        ]);
    }

    /**
     * Remove um ano lectivo.
     */
    public function deleteAno($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM anos WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Recupera a grade de horários de uma turma.
     */
    public function getScheduleByTurma($turma_id) {
        $stmt = $this->db->prepare("
            SELECT h.*, d.nome as disciplina_nome
            FROM horarios h
            JOIN disciplinas d ON h.disciplina_id = d.id
            WHERE h.turma_id = :tid
            ORDER BY FIELD(h.dia_semana, 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'), h.hora_inicio
        ");
        $stmt->execute([':tid' => $turma_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Motor de Cálculo de Notas por Estudante.
     * 
     * Documentação Funcional:
     * 1. Agrupa notas de diferentes avaliações por disciplina.
     * 2. Integra o status de conformidade ('concordancia_notas').
     * 3. Calcula o Total de AC (Avaliação Contínua).
     * 4. Calcula a Nota Final (Média entre AC e Exame).
     * 
     * // Mentoria: Este método encapsula o rigor académico do sistema. 
     * // A complexidade aqui é justificada pela necessidade de pivotar dados 
     * // relacionais para uma vista de "pauta horizontal" no frontend.
     */
    public function getGradesByStudent($estudante_id) {
        $stmt = $this->db->prepare("
            SELECT 
                d.nome as disciplina_nome,
                ta.id as tipo_id,
                n.nota,
                a.turma_id,
                a.disciplina_id,
                cn.status as feedback_status,
                cn.comentario as feedback_comentario,
                cn.resposta_professor
            FROM notas n
            JOIN avaliacoes a ON n.avaliacao_id = a.id
            JOIN tipos_avaliacao ta ON a.tipo_avaliacao_id = ta.id
            JOIN disciplinas d ON a.disciplina_id = d.id
            LEFT JOIN concordancia_notas cn ON n.estudante_id = cn.estudante_id 
                AND a.turma_id = cn.turma_id 
                AND a.disciplina_id = cn.disciplina_id
            WHERE n.estudante_id = :eid
        ");
        $stmt->execute([':eid' => $estudante_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $grouped = [];
        foreach ($results as $r) {
            $name = $r['disciplina_nome'];
            if (!isset($grouped[$name])) {
                $grouped[$name] = [
                    'disciplina' => $name,
                    'turma_id' => $r['turma_id'],
                    'disciplina_id' => $r['disciplina_id'],
                    'feedback_status' => $r['feedback_status'] ?? 'Pendente',
                    'feedback_comentario' => $r['feedback_comentario'],
                    'resposta_professor' => $r['resposta_professor'] ?? null,
                    'notas' => [1=>0, 2=>0, 3=>0, 4=>0, 5=>null]
                ];
            }
            $grouped[$name]['notas'][$r['tipo_id']] = $r['nota'];
        }

        foreach ($grouped as &$row) {
            $ac = $row['notas'][1] + $row['notas'][2] + $row['notas'][3] + $row['notas'][4];
            $row['total_ac'] = $ac;
            // Cálculo: Média Aritmética entre AC e Exame Final
            $row['nota_final'] = ($row['notas'][5] !== null) ? ($ac + $row['notas'][5]) / 2 : null;
        }

        return $grouped;
    }

    /**
     * Consolidação de Histórico Global.
     * Semelhante ao cálculo de notas, mas agregando por Ano Letivo e Semestre.
     */
    public function getGlobalHistory($estudante_id) {
        $stmt = $this->db->prepare("
            SELECT 
                d.nome as disciplina_nome,
                a.ano_letivo,
                a.semestre,
                ta.id as tipo_id,
                n.nota
            FROM notas n
            JOIN avaliacoes a ON n.avaliacao_id = a.id
            JOIN tipos_avaliacao ta ON a.tipo_avaliacao_id = ta.id
            JOIN disciplinas d ON a.disciplina_id = d.id
            WHERE n.estudante_id = :eid
            ORDER BY a.ano_letivo DESC, a.semestre DESC, d.nome ASC
        ");
        $stmt->execute([':eid' => $estudante_id]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $history = [];
        foreach ($results as $r) {
            $key = $r['ano_letivo'] . '_' . $r['semestre'] . '_' . $r['disciplina_nome'];
            if (!isset($history[$key])) {
                $history[$key] = [
                    'ano' => $r['ano_letivo'],
                    'semestre' => $r['semestre'],
                    'disciplina' => $r['disciplina_nome'],
                    'notas' => [1=>0, 2=>0, 3=>0, 4=>0, 5=>null]
                ];
            }
            $history[$key]['notas'][$r['tipo_id']] = $r['nota'];
        }

        foreach ($history as &$row) {
            $ac = $row['notas'][1] + $row['notas'][2] + $row['notas'][3] + $row['notas'][4];
            $row['total_ac'] = $ac;
            $row['nota_final'] = ($row['notas'][5] !== null) ? ($ac + $row['notas'][5]) / 2 : null;
            
            if ($row['nota_final'] !== null) {
                $row['status'] = ($row['nota_final'] >= 10) ? 'Aprovado' : 'Reprovado';
            } else {
                $row['status'] = 'Em Curso';
            }
        }

        return array_values($history);
    }

    /**
     * =====================================================
     *  🏆 SISTEMA DE MÉRITO ACADÉMICO (Ranking)
     * =====================================================
     *
     * getRankingByNivel()  - Melhor aluno de cada nível/ano de curso
     * getRankingEscola()   - Melhor aluno absoluto da instituição
     *
     * Algoritmo:
     *   Nota_Final_Disciplina = (AC1 + AC2 + AC3 + AC4 + Exame) / 2
     *   Média_Geral_Aluno     = AVG(Nota_Final_Disciplina) de todas as disciplinas com exame
     */

    /**
     * Retorna o melhor aluno de cada nível/ano de curso.
     * Apenas conta disciplinas onde o exame final foi lançado.
     *
     * @return array  Lista de rankings por nível com foto, nome, média e nível
     */
    public function getRankingByNivel() {
        $sql = "
            SELECT 
                ano_id,
                nivel_nome,
                nivel_ordem,
                estudante_id,
                nome,
                foto_perfil,
                AVG(nota_disciplina) AS media_geral,
                COUNT(disciplina_id) AS num_disciplinas
            FROM (
                SELECT
                    a.id          AS ano_id,
                    a.nome        AS nivel_nome,
                    a.ordem       AS nivel_ordem,
                    e.id          AS estudante_id,
                    u.nome_completo AS nome,
                    e.foto_perfil,
                    av.disciplina_id,
                    (
                        COALESCE(MAX(CASE WHEN ta.id = 1 THEN n.nota END), 0) +
                        COALESCE(MAX(CASE WHEN ta.id = 2 THEN n.nota END), 0) +
                        COALESCE(MAX(CASE WHEN ta.id = 3 THEN n.nota END), 0) +
                        COALESCE(MAX(CASE WHEN ta.id = 4 THEN n.nota END), 0) +
                        COALESCE(MAX(CASE WHEN ta.id = 5 THEN n.nota END), 0)
                    ) / 2 AS nota_disciplina
                FROM notas n
                JOIN avaliacoes av ON n.avaliacao_id = av.id
                JOIN tipos_avaliacao ta ON av.tipo_avaliacao_id = ta.id
                JOIN estudantes e ON n.estudante_id = e.id
                JOIN utilizadores u ON e.utilizador_id = u.id
                JOIN matriculas m ON m.estudante_id = e.id AND m.status = 'Aprovada'
                JOIN anos a ON m.ano_curso_id = a.id
                GROUP BY a.id, a.nome, a.ordem, e.id, u.nome_completo, e.foto_perfil, av.disciplina_id
                HAVING MAX(CASE WHEN ta.id = 5 THEN 1 ELSE 0 END) > 0
            ) AS notas_finais
            GROUP BY ano_id, nivel_nome, nivel_ordem, estudante_id, nome, foto_perfil
            HAVING num_disciplinas >= 1
            ORDER BY nivel_ordem ASC, media_geral DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Agrupar por nível e pegar apenas o 1.º de cada
        $ranking = [];
        foreach ($rows as $row) {
            if (!isset($ranking[$row['ano_id']])) {
                $ranking[$row['ano_id']] = $row;
                $ranking[$row['ano_id']]['posicao'] = 1;
            }
        }
        return array_values($ranking);
    }

    /**
     * Retorna o melhor aluno absoluto de toda a escola (Top 3).
     *
     * @param int $limit  Número de alunos a retornar (padrão: 3)
     * @return array
     */
    public function getRankingEscola($limit = 3) {
        $sql = "
            SELECT 
                estudante_id,
                nome,
                foto_perfil,
                nivel_nome,
                AVG(nota_disciplina) AS media_geral,
                COUNT(disciplina_id) AS num_disciplinas
            FROM (
                SELECT
                    e.id          AS estudante_id,
                    u.nome_completo AS nome,
                    e.foto_perfil,
                    a.nome        AS nivel_nome,
                    av.disciplina_id,
                    (
                        COALESCE(MAX(CASE WHEN ta.id = 1 THEN n.nota END), 0) +
                        COALESCE(MAX(CASE WHEN ta.id = 2 THEN n.nota END), 0) +
                        COALESCE(MAX(CASE WHEN ta.id = 3 THEN n.nota END), 0) +
                        COALESCE(MAX(CASE WHEN ta.id = 4 THEN n.nota END), 0) +
                        COALESCE(MAX(CASE WHEN ta.id = 5 THEN n.nota END), 0)
                    ) / 2 AS nota_disciplina
                FROM notas n
                JOIN avaliacoes av ON n.avaliacao_id = av.id
                JOIN tipos_avaliacao ta ON av.tipo_avaliacao_id = ta.id
                JOIN estudantes e ON n.estudante_id = e.id
                JOIN utilizadores u ON e.utilizador_id = u.id
                JOIN matriculas m ON m.estudante_id = e.id AND m.status = 'Aprovada'
                JOIN anos a ON m.ano_curso_id = a.id
                GROUP BY e.id, u.nome_completo, e.foto_perfil, a.nome, av.disciplina_id
                HAVING MAX(CASE WHEN ta.id = 5 THEN 1 ELSE 0 END) > 0
            ) AS notas_finais
            GROUP BY estudante_id, nome, foto_perfil, nivel_nome
            HAVING num_disciplinas >= 1
            ORDER BY media_geral DESC
            LIMIT :lim
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':lim', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Adicionar medalha/posição
        foreach ($rows as $i => &$r) {
            $r['posicao'] = $i + 1;
            $r['medalha'] = ['🥇', '🥈', '🥉'][$i] ?? '🏅';
        }
        return $rows;
    }

    /**
     * Retorna a posição exata de um estudante no seu nível e na escola toda.
     * Motor de Ranking Multi-Período (1º Sem, 2º Sem, Anual).
     */
    public function getDetailedStudentRank($estudante_id, $semestre = null) {
        $whereSemestre = $semestre ? "AND av.semestre = :sem" : "";
        $params = $semestre ? [':sem' => $semestre] : [];

        $sql = "
            SELECT 
                estudante_id,
                ano_id,
                SUM(nota_disciplina) / COUNT(disciplina_id) AS media_geral
            FROM (
                SELECT
                    e.id          AS estudante_id,
                    m.ano_curso_id AS ano_id,
                    av.disciplina_id,
                    (
                        COALESCE(MAX(CASE WHEN ta.id = 1 THEN n.nota END), 0) +
                        COALESCE(MAX(CASE WHEN ta.id = 2 THEN n.nota END), 0) +
                        COALESCE(MAX(CASE WHEN ta.id = 3 THEN n.nota END), 0) +
                        COALESCE(MAX(CASE WHEN ta.id = 4 THEN n.nota END), 0) +
                        COALESCE(MAX(CASE WHEN ta.id = 5 THEN n.nota END), 0)
                    ) / 2 AS nota_disciplina
                FROM notas n
                JOIN avaliacoes av ON n.avaliacao_id = av.id
                JOIN tipos_avaliacao ta ON av.tipo_avaliacao_id = ta.id
                JOIN estudantes e ON n.estudante_id = e.id
                JOIN matriculas m ON m.estudante_id = e.id AND m.status = 'Aprovada'
                WHERE 1=1 $whereSemestre
                GROUP BY e.id, m.ano_curso_id, av.disciplina_id
            ) AS notas_finais
            GROUP BY estudante_id, ano_id
            ORDER BY media_geral DESC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $all_ranks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $my_media = 0; $my_ano_id = 0;
        foreach ($all_ranks as $rank) {
            if ((int)$rank['estudante_id'] === (int)$estudante_id) {
                $my_media = $rank['media_geral'];
                $my_ano_id = $rank['ano_id'];
                break;
            }
        }

        if ($my_media == 0) return ['posicao_nivel' => 0, 'total_nivel' => 0, 'posicao_escola' => 0, 'total_escola' => 0, 'media' => '0.0'];

        $count_escola = 1; $count_nivel = 1; $total_nivel = 0;
        foreach ($all_ranks as $rank) {
            if ($rank['media_geral'] > $my_media) {
                $count_escola++;
                if ((int)$rank['ano_id'] === (int)$my_ano_id) $count_nivel++;
            }
            if ((int)$rank['ano_id'] === (int)$my_ano_id) $total_nivel++;
        }

        return [
            'posicao_nivel' => $count_nivel, 'total_nivel' => $total_nivel,
            'posicao_escola'=> $count_escola, 'total_escola' => count($all_ranks),
            'media' => number_format($my_media, 2)
        ];
    }

    public function getStudentRankPosition($estudante_id) {
        $s1 = $this->getDetailedStudentRank($estudante_id, 1);
        $s2 = $this->getDetailedStudentRank($estudante_id, 2);
        $anual = $this->getDetailedStudentRank($estudante_id);
        
        $conquistas = [];
        $periods = ['1º Semestre' => $s1, '2º Semestre' => $s2, 'Anual' => $anual];

        foreach ($periods as $label => $rank) {
            if ($rank['posicao_escola'] >= 1 && $rank['posicao_escola'] <= 3) {
                $conquistas[] = [
                    'tipo' => 'Escola',
                    'posicao' => $rank['posicao_escola'],
                    'media' => $rank['media'],
                    'periodo' => $label
                ];
            }
            if ($rank['posicao_nivel'] === 1) {
                $sql = "SELECT ac.nome FROM ano_curso ac JOIN matriculas m ON m.ano_curso_id = ac.id WHERE m.estudante_id = :id LIMIT 1";
                $stmt = $this->db->prepare($sql); $stmt->execute(['id' => $estudante_id]);
                $nivel = $stmt->fetch(PDO::FETCH_ASSOC);
                $conquistas[] = [
                    'tipo' => 'Nível',
                    'posicao' => 1,
                    'media' => $rank['media'],
                    'nivel_nome' => $nivel['nome'] ?? 'Ano Letivo',
                    'periodo' => $label
                ];
            }
        }

        return !empty($conquistas) ? $conquistas : false;
    }
}
