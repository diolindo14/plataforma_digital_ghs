<?php
require_once 'core/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();

try {
    // 1. Limpeza total (Exceto Administrador ID 1)
    $db->exec("DELETE FROM matriculas");
    $db->exec("DELETE FROM estudantes");
    $db->exec("DELETE FROM utilizadores WHERE id != 1");
    // Resetar auto-incrementos (Opcional, mas mantém tudo limpo)
    $db->exec("ALTER TABLE utilizadores AUTO_INCREMENT = 2");
    $db->exec("ALTER TABLE estudantes AUTO_INCREMENT = 1");
    $db->exec("ALTER TABLE matriculas AUTO_INCREMENT = 1");

    function createStudent($name, $ano_id, $turma_id, $turno, $tipo, $esp_id = null) {
        global $db;
        $name_clean = trim($name);
        $parts = explode(' ', strtolower($name_clean));
        $first = $parts[0];
        $last = $parts[count($parts)-1];
        // Email ÚNICO por nome para evitar duplicados em execuções futuras
        $email = $first . '.' . $last . '@ghs.school'; 
        $hash = password_hash('ghs123456', PASSWORD_DEFAULT);

        // Se o email já existe (por ex, dois alunos com mesmo nome/apelido), adiciona sufixo
        $stmtC = $db->prepare("SELECT id FROM utilizadores WHERE email = ?");
        $stmtC->execute([$email]);
        if ($stmtC->fetch()) {
            $email = $first . '.' . $last . rand(10,99) . '@ghs.school';
        }

        $db->prepare("INSERT INTO utilizadores (nome_completo, email, senha, tipo, status, data_aprovacao) VALUES (?, ?, ?, 'aluno', 'ativo', NOW())")->execute([$name_clean, $email, $hash]);
        $uid = $db->lastInsertId();
        
        $db->prepare("INSERT INTO estudantes (utilizador_id, bi) VALUES (?, ?)")->execute([$uid, 'BI'.rand(10000,99999)]);
        $sid = $db->lastInsertId();
        
        $db->prepare("INSERT INTO matriculas (estudante_id, ano_letivo, ano_curso_id, especializacao_id, turno, tipo, status, data_matricula, turma_id) VALUES (?, ?, ?, ?, ?, ?, 'Aprovada', NOW(), ?)")
           ->execute([$sid, '2025/2026', $ano_id, $esp_id, $turno, $tipo, $turma_id]);
    }

    $turmas = $db->query("SELECT id, codigo FROM turmas")->fetchAll(PDO::FETCH_KEY_PAIR);
    
    $students_list = [
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

    $count = 0;
    foreach ($students_list as $group) {
        $turma_id = array_search($group['code'], $turmas);
        $tipo = ($group['ano'] == 1) ? 'Novo Ingresso' : 'Renovação';
        foreach ($group['names'] as $name) {
            createStudent($name, $group['ano'], $turma_id, $group['turno'], $tipo, $group['esp']);
            $count++;
        }
    }
    echo "CORREÇÃO CONCLUÍDA: $count ALUNOS RESTAURADOS.";
} catch (Exception $e) { echo "ERRO: " . $e->getMessage(); }
