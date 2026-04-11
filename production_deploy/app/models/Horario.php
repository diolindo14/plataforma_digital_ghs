<?php
class Horario {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // ─── Core schedule retrieval ─────────────────────────────────

    /**
     * Fetch weekly schedule for a turma, grouped into a 2D array
     * [dia][tempo] = row data
     */
    public function getHorarioByTurma($turma_id) {
        $stmt = $this->db->prepare("
            SELECT h.*,
                   d.nome as disciplina_nome_db,
                   d.codigo as sigla,
                   d.nome as nome_display,
                   COALESCE(h.tempo_aula, 0) as tempo_num
            FROM horarios h
            JOIN disciplinas d ON h.disciplina_id = d.id
            WHERE h.turma_id = :tid
            ORDER BY FIELD(h.dia_semana,'Segunda','Terça','Quarta','Quinta','Sexta','Sábado'), 
                     h.hora_inicio
        ");
        $stmt->execute([':tid' => $turma_id]);
        return $stmt->fetchAll();
    }

    /**
     * Build grid: [tempo => [inicio,fim], dias => [dia => [slot...]]]
     */
    public function buildWeeklyGrid($turma_id) {
        $rows = $this->getHorarioByTurma($turma_id);
        $dias  = ['Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
        $grid  = [];      
        
        // Define default 4 slots based on shift
        $stmtT = $this->db->prepare("SELECT turno FROM turmas WHERE id = :tid");
        $stmtT->execute([':tid' => $turma_id]);
        $turno = $stmtT->fetchColumn() ?: 'Manhã';

        $tempos = $this->getDefaultTempos($turno);

        foreach ($rows as $r) {
            $t = (int)$r['tempo_num'];
            $d = $r['dia_semana'];
            if (isset($tempos[$t])) {
                $tempos[$t] = ['inicio' => substr($r['hora_inicio'], 0, 5), 'fim' => substr($r['hora_fim'], 0, 5)];
            }
            $grid[$t][$d] = $r;
        }
        ksort($tempos);
        return ['tempos' => $tempos, 'grid' => $grid, 'dias' => $dias];
    }

    public function buildWeeklyGridForProfessor($professor_id) {
        $rows = $this->getHorarioByProfessor($professor_id);
        $dias  = ['Segunda','Terça','Quarta','Quinta','Sexta','Sábado'];
        $grid  = [];
        $tempos = [];

        foreach ($rows as $r) {
            $t = (int)$r['tempo_num'];
            $d = $r['dia_semana'];
            if (!isset($tempos[$t])) {
                $tempos[$t] = ['inicio' => substr($r['hora_inicio'], 0, 5), 'fim' => substr($r['hora_fim'],0,5)];
            }
            $r['sigla'] = $r['sigla'] . " (" . $r['turma_codigo'] . ")";
            $grid[$t][$d] = $r;
        }

        if (empty($tempos)) {
            $tempos = $this->getDefaultTempos('Manhã');
        } else {
            $max = max(array_keys($tempos));
            if ($max < 4) $max = 4;
            for ($i = 1; $i <= $max; $i++) {
                if (!isset($tempos[$i])) $tempos[$i] = ['inicio' => '--:--', 'fim' => '--:--'];
            }
        }

        ksort($tempos);
        return ['tempos' => $tempos, 'grid' => $grid, 'dias' => $dias];
    }

    private function getDefaultTempos($turno) {
        if ($turno == 'Tarde') {
            return [
                1 => ['inicio' => '13:00', 'fim' => '14:30'],
                2 => ['inicio' => '14:35', 'fim' => '16:05'],
                3 => ['inicio' => '16:10', 'fim' => '17:40'],
                4 => ['inicio' => '17:45', 'fim' => '19:15']
            ];
        } elseif ($turno == 'Noite') {
            return [
                1 => ['inicio' => '17:45', 'fim' => '19:15'],
                2 => ['inicio' => '19:20', 'fim' => '20:50'],
                3 => ['inicio' => '20:55', 'fim' => '22:25'],
                4 => ['inicio' => '22:30', 'fim' => '00:00']
            ];
        } else {
            return [
                1 => ['inicio' => '07:20', 'fim' => '08:50'],
                2 => ['inicio' => '08:55', 'fim' => '10:25'],
                3 => ['inicio' => '10:30', 'fim' => '12:00'],
                4 => ['inicio' => '12:05', 'fim' => '13:35']
            ];
        }
    }

    // ─── Professor view ──────────────────────────────────────────

    public function getHorarioByProfessor($professor_id) {
        $stmt = $this->db->prepare("
            SELECT h.*,
                   d.codigo as sigla,
                   d.nome as nome_display,
                   COALESCE(h.tempo_aula, 0) as tempo_num,
                   t.codigo as turma_codigo, t.turno
            FROM horarios h
            JOIN disciplinas d ON h.disciplina_id = d.id
            JOIN turmas t ON h.turma_id = t.id
            WHERE h.professor_id = :pid
            ORDER BY FIELD(h.dia_semana,'Segunda','Terça','Quarta','Quinta','Sexta','Sábado'), h.hora_inicio
        ");
        $stmt->execute([':pid' => $professor_id]);
        return $stmt->fetchAll();
    }

    // ─── Admin CRUD ──────────────────────────────────────────────

    public function allocate($data) {
        // Skip conflict check if no professor assigned
        if (!empty($data['professor_id'])) {
            if ($this->hasConflict($data)) {
                return ['success' => false, 'message' => 'Conflito: Professor ou Sala ocupados neste horário.'];
            }
        }
        $stmt = $this->db->prepare("
            INSERT INTO horarios (turma_id, disciplina_id, professor_id, dia_semana, hora_inicio, hora_fim, sala, tempo_aula)
            VALUES (:tid, :did, :pid, :dia, :inicio, :fim, :sala, :tempo)
        ");
        $res = $stmt->execute([
            ':tid'   => $data['turma_id'],
            ':did'   => $data['disciplina_id'],
            ':pid'   => $data['professor_id'] ?? null,
            ':dia'   => $data['dia_semana'],
            ':inicio'=> $data['hora_inicio'],
            ':fim'   => $data['hora_fim'],
            ':sala'  => $data['sala'],
            ':tempo' => $data['tempo'] ?? null,
        ]);
        return $res ? ['success' => true, 'id' => $this->db->lastInsertId()]
                    : ['success' => false, 'message' => 'Erro ao salvar.'];
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE horarios SET 
                turma_id = :tid, disciplina_id = :did, dia_semana = :dia,
                hora_inicio = :inicio, hora_fim = :fim, sala = :sala,
                tempo_aula = :tempo
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id'    => $id,
            ':tid'   => $data['turma_id'],
            ':did'   => $data['disciplina_id'],
            ':dia'   => $data['dia_semana'],
            ':inicio'=> $data['hora_inicio'],
            ':fim'   => $data['hora_fim'],
            ':sala'  => $data['sala'],
            ':tempo' => $data['tempo'] ?? null,
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM horarios WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // ─── Helpers ─────────────────────────────────────────────────

    private function hasConflict($data) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM horarios 
            WHERE (professor_id = :pid OR sala = :sala) 
            AND dia_semana = :dia 
            AND ((hora_inicio < :fim AND hora_fim > :inicio))
        ");
        $stmt->execute([
            ':pid'   => $data['professor_id'] ?? null,
            ':sala'  => $data['sala'],
            ':dia'   => $data['dia_semana'],
            ':inicio'=> $data['hora_inicio'],
            ':fim'   => $data['hora_fim'],
        ]);
        return $stmt->fetchColumn() > 0;
    }

    /** Return all schedule rows flat (for admin DataTable) */
    public function getAllFlat() {
        $stmt = $this->db->prepare("
            SELECT h.id,
                   t.codigo as turma_codigo,
                   d.codigo as sigla,
                   d.nome as nome_display,
                   h.dia_semana, h.hora_inicio, h.hora_fim, h.sala,
                   COALESCE(h.tempo_aula, 0) as tempo_num,
                   IFNULL(u.nome_completo, '—') as professor_nome
            FROM horarios h
            JOIN turmas t ON h.turma_id = t.id
            JOIN disciplinas d ON h.disciplina_id = d.id
            LEFT JOIN professores p ON h.professor_id = p.id
            LEFT JOIN utilizadores u ON p.utilizador_id = u.id
            ORDER BY t.codigo, FIELD(h.dia_semana,'Segunda','Terça','Quarta','Quinta','Sexta','Sábado'), h.hora_inicio
        ");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
