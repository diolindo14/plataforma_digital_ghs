-- Limpeza de teste
DELETE FROM matriculas WHERE estudante_id IN (SELECT id FROM estudantes WHERE utilizador_id IN (SELECT id FROM utilizadores WHERE email = 'estudante_renovacao@green.com'));
DELETE FROM estudantes WHERE utilizador_id IN (SELECT id FROM utilizadores WHERE email = 'estudante_renovacao@green.com');
DELETE FROM utilizadores WHERE email = 'estudante_renovacao@green.com';
