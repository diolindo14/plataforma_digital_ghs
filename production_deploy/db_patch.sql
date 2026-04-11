-- Patch SQL (Abril 2026)
-- Resolve o problema da associação errada da Turma 4T1 ao subject (ID 30 -> 43)

BEGIN;

UPDATE professor_disciplina SET disciplina_id = 43 WHERE turma_id = 7 AND disciplina_id = 30;
UPDATE avaliacoes SET disciplina_id = 43 WHERE turma_id = 7 AND disciplina_id = 30;
UPDATE concordancia_notas SET disciplina_id = 43 WHERE turma_id = 7 AND disciplina_id = 30;
UPDATE frequencias SET disciplina_id = 43 WHERE turma_id = 7 AND disciplina_id = 30;
UPDATE sumarios SET disciplina_id = 43 WHERE turma_id = 7 AND disciplina_id = 30;

COMMIT;
