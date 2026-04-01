<?php
require_once 'core/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();

try {
    // 1. Restaurar Admin com ID 1
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $db->prepare("DELETE FROM utilizadores WHERE id = 1")->execute();
    $db->prepare("INSERT INTO utilizadores (id, nome_completo, email, senha, tipo, status, data_aprovacao) 
                  VALUES (1, 'Administrador GHS', 'admin@ghs.com', ?, 'admin', 'ativo', NOW())")->execute([$hash]);

    // 2. Garantir dados básicos
    $db->exec("INSERT IGNORE INTO anos (id, nome, ordem) VALUES (1, '1º Ano', 1), (2, '2º Ano', 2), (3, '3º Ano', 3), (4, '4º Ano', 4), (5, '5º Ano', 5)");
    $db->exec("INSERT IGNORE INTO especializacoes (id, nome) VALUES (1, 'Hardware & Robótica'), (2, 'Programação'), (3, 'Banco de Dados'), (4, 'Redes de Computadores'), (5, 'Engenharia Médica')");
    $db->exec("INSERT IGNORE INTO turmas (codigo, turno) VALUES ('GHS-1M1', 'Manhã'), ('GHS-1T1', 'Tarde'), ('GHS-1N1', 'Noite'), ('GHS-2M1', 'Manhã'), ('GHS-2T1', 'Tarde'), ('GHS-3M1', 'Manhã'), ('GHS-3T1', 'Tarde'), ('GHS-4T1', 'Tarde'), ('GHS-5TRD1', 'Tarde'), ('GHS-5NBD1', 'Noite')");

    // 3. Mapeamento de Turmas e Alunos
    $turmas = $db->query("SELECT id, codigo FROM turmas")->fetchAll(PDO::FETCH_KEY_PAIR);
    $anos = [1=>1, 2=>2, 3=>3, 4=>4, 5=>5]; // Mapeamento ordem -> id
    $esps = [1=>null, 2=>null, 3=>null, 4=>null, 5=>null, 'RD'=>4, 'BD'=>3];

    $data = [
        ['code'=>'GHS-1M1', 'ano'=>1, 'esp'=>null, 'turno'=>'Manhã', 'names'=>['Adalgiza da Costa','Adul Carimo Baldé','Aminata Djamila Injai','António Alberto Lopes','Artimiza Augusto Tcham','Benoni Domingos Pereira','Binto Camará','Bissiqué Joaquim','Brolim António Damas','Burama Gil Pombo','Carlita Mangar','Davicson Lona Mbana','Desejado Correia Forbs','Eleutério Emilio Dias Monteiro','Elson Correia','Emanuel Duarte Djata','Eugénio João Pereira','Eusébio Tcherno Mamudo Baldé','Fina Pereira','Isaias Paulino Incundé','Jacinto Siverino Mancanha','João Saliu Monteiro','Lucas Paulo Ialá','Luinela Edvises Papa Cá','Moises Sá','Naziana Nisco de Carvalho','Nucia Tobana Vasna','Raisa Ucalute Gomes','Rudilson Daivaneo Semedo Cá','Sabado Carlos Ntumbo','Saico Umaro Só','São João Fernando Carissali','Silvano Augusto','Tcherno Mamadú Camará','Timotio Sete','Vanilson Gomes da Costa']],
        ['code'=>'GHS-1T1', 'ano'=>1, 'esp'=>null, 'turno'=>'Tarde', 'names'=>['Adelio Cá','Aderito António Marcos','Amade Embaló','Amadu Djulde Djaló','Amadú E. Da Silva Nbotche','Amonique Cá','Bedamone Sandussa Nandiba','Benvinda Indiba','Binta Cassamá','Dabana Lóa Na Tcharré','Danilson Roel da Silva','Desejado Ilidio Dias','Edimilson Augusto Malel','Emanuela Nino Sambú','Emerson Dias Baldé','Eugénio Marcelo Semedo','Fodé Amara Sissé','Iasene Purna Ntchala','Ibraima Baldé','Jacira Correia','Juliana Gomes da Silva','Junior Augusto Landim','Leovanio Silvano Mendes','Mindo Aduquir Júnior da Silva','Mohamadu Baldé','Neia Ntchama','Nicaela Alfredo Correia','Pedro Seidi','Ronaldinho Edmilson Gomes Pereira','Salimatu Candé','Silvio Luis Caetano','Ussumane Sané','Valdimira Cabral Gomes','Vasco Alexandre Na Cul']],
        ['code'=>'GHS-1N1', 'ano'=>1, 'esp'=>null, 'turno'=>'Noite', 'names'=>['Manjupe Lais da Costa','Alfredo Tchuda','Arafam Cand','Calido Djau','Elizio João Pereira','Emanuel Biussum Iurna','Sabino Carvalho','Serginho Pedro Gomes Vilela']],
        ['code'=>'GHS-2M1', 'ano'=>2, 'esp'=>null, 'turno'=>'Manhã', 'names'=>['Aliu Águas','Armando Ié','Bidam-Mone Na Camine','Celestina Gomes','Cesaltina Joaquim Bailambi','Daimara Correia Mendes','Endem Camará','Fredinilton Pereira Bassali','Isis Djibril Camará','Jacir Abubacar Moreno Turé','Jovane Daniel Cutende','Juelson Mendes','Juscelino Lopes','Leorooney Mendes Sá Correia','Marcos Bissunha Ntchama','Marcos M. Nampunque','Mariama Tumane Quadé','Ncaram Bunha Cumba','Nelson Duarte da Silva','Nghale Wid Cumba','Tuncam Embaló','Zaira Alanam Nichudê']],
        ['code'=>'GHS-2T1', 'ano'=>2, 'esp'=>null, 'turno'=>'Tarde', 'names'=>['Andre Djata','António Tidjane Camará','Carlos Alberto Correia','Djabu Joãozinho da Costa','Eliana Correia Mendes','Iaia Seidi','Ijaquiel Armando Sanca','José Silva Nhaga','Luisella Mané Lopes dos Santos','Mamadu Baldé','Sat-na Faie Siga','Sinaider F. da Silva','Suaila Djata','Zafenate Quintino Mondi']],
        ['code'=>'GHS-3M1', 'ano'=>3, 'esp'=>null, 'turno'=>'Manhã', 'names'=>['Carlos Isnaba Bidonga','Dias Domingos Ialá','Dutim Rodrigues','Elizabete Marena','Elizio da Silva','Hatissary Thayssa Sá Nogueira','Junaid Ibn Abulai Conté','Massirem da Costa Djaló','Samba Baldé','Serifo Amadú Fadil Seidi','Tidjane Indjai','Tcherno Camará']],
        ['code'=>'GHS-3T1', 'ano'=>3, 'esp'=>null, 'turno'=>'Tarde', 'names'=>['Buba Martinho Na Forna','Claus Roxin Jorge da Costa','Fatumata Seidi','Gershon Quadé Ié','Jaqueline M. M. Lopes Nonaque','Naida Na Biatchiba','Tussem Mendes','Ussumane Ponqué']],
        ['code'=>'GHS-4T1', 'ano'=>4, 'esp'=>null, 'turno'=>'Tarde', 'names'=>['Amadú Julde Djaló','Dingana Nimina Embana','Diosives Pedro Nunes Crobute','Djibril Tchamo','Elizabete Vaz Moreno','Erikson Wogna Fanda','Fernando Augusto Malú','Francisco N. Na Nhassé','Idjatu Dabó','Ivan Sajo Samananco','Luizela Sanhá Pereira Tecanhe','Tiago Yalá']],
        ['code'=>'GHS-5TRD1', 'ano'=>5, 'esp'=>4, 'turno'=>'Tarde', 'names'=>['Adulai Camará','Aléssio José Rebelo Barbosa','Alqueia Nanque','Cabomarim Filipe Catame','Domingos Fafé','Ela Candé','Francisco Andre da Silva','Isnaba Conha Inta-a','Issa Djau','Madjer Moaquim Malam Sanhá Baió','Mafudje Bá Jau','Miriam Nhaga','Roberto Kabi Naguadé','Uleimato Jaló','Valeri Cardose']],
        ['code'=>'GHS-5NBD1', 'ano'=>5, 'esp'=>3, 'turno'=>'Noite', 'names'=>['Artimiza Iano Sá','Badora Agostinho Djata','Bubacar Baldé','Felklin Pedro da Silva Júnior','Ieró Baldé','Ivana Ivanovica de Oliveira Quelute','Leonardo António Kassama','Nadia Lopes Nank Ié','Pier Tanhá','Quintino Nunes']],
    ];

    foreach ($data as $group) {
        $turma_id = array_search($group['code'], $turmas);
        foreach ($group['names'] as $name) {
            $email = str_replace(' ', '.', strtolower($name)) . rand(10,99) . '@ghs.school';
            $db->prepare("INSERT IGNORE INTO utilizadores (nome_completo, email, senha, tipo, status, data_aprovacao) VALUES (?, ?, ?, 'aluno', 'ativo', NOW())")->execute([$name, $email, $hash]);
            $uid = $db->lastInsertId();
            if($uid) {
                $db->prepare("INSERT IGNORE INTO estudantes (utilizador_id, bi) VALUES (?, ?)")->execute([$uid, 'BI'.rand(10000,99999)]);
                $sid = $db->lastInsertId();
                $db->prepare("INSERT INTO matriculas (estudante_id, ano_letivo, ano_curso_id, especializacao_id, turno, tipo, status, data_matricula, turma_id) VALUES (?, ?, ?, ?, ?, ?, 'Aprovada', NOW(), ?)")
                   ->execute([$sid, '2025/2026', $group['ano'], $group['esp'], $group['turno'], ($group['ano']==1?'Novo Ingresso':'Renovação'), $turma_id]);
            }
        }
    }
    echo "SISTEMA RECUPERADO.";
} catch (Exception $e) { echo "ERRO: " . $e->getMessage(); }
