<?php
require_once 'core/config.php';
require_once 'core/Database.php';

$db = Database::getInstance();

function createStudent($name, $email, $password, $ano_id, $turma_id, $turno, $tipo, $esp_id = null) {
    global $db;
    
    // 1. Create User
    $hash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmtUser = $db->prepare("INSERT IGNORE INTO utilizadores (nome_completo, email, senha, tipo, status, data_aprovacao) VALUES (:nome, :email, :senha, 'aluno', 'ativo', NOW())");
        $stmtUser->execute([':nome' => $name, ':email' => $email, ':senha' => $hash]);
        $userId = $db->lastInsertId();
        
        if (!$userId) {
             // Se já existia, pegar o ID
             $stmtF = $db->prepare("SELECT id FROM utilizadores WHERE email = :email");
             $stmtF->execute([':email' => $email]);
             $userId = $stmtF->fetchColumn();
        }

        // 2. Create Student Profile
        $stmtStudent = $db->prepare("INSERT IGNORE INTO estudantes (utilizador_id, bi) VALUES (:uid, :bi)");
        $stmtStudent->execute([':uid' => $userId, ':bi' => '000'.rand(1000,9999)]);
        $studentId = $db->lastInsertId();
        
        if (!$studentId) {
             $stmtS = $db->prepare("SELECT id FROM estudantes WHERE utilizador_id = :uid");
             $stmtS->execute([':uid' => $userId]);
             $studentId = $stmtS->fetchColumn();
        }

        // 3. Create Enrollment
        $stmtMatricula = $db->prepare("INSERT IGNORE INTO matriculas (estudante_id, ano_id, turma_id, especializacao_id, turno, tipo, status, data_inscricao) VALUES (:sid, :aid, :tid, :esp, :turno, :tipo, 'Aprovada', NOW())");
        
        $stmtMatricula->execute([
            ':sid' => $studentId,
            ':aid' => $ano_id,
            ':tid' => $turma_id,
            ':esp' => $esp_id,
            ':turno' => $turno,
            ':tipo' => $tipo
        ]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// Find IDs for mapping
$turma_res = $db->query("SELECT id, codigo FROM turmas")->fetchAll(PDO::FETCH_KEY_PAIR);
$ano_res = $db->query("SELECT id, ordem FROM anos")->fetchAll(PDO::FETCH_KEY_PAIR);
$ano_flip = array_flip($ano_res);
$esp_res = $db->query("SELECT id, nome FROM especializacoes")->fetchAll(PDO::FETCH_KEY_PAIR);
$esp_flip = array_flip($esp_res);

$students_data = [
    'GHS-1M1' => ['ano' => 1, 'turno' => 'Manhã', 'esp' => null, 'names' => ['Adalgiza da Costa','Adul Carimo Baldé','Aminata Djamila Injai','António Alberto Lopes','Artimiza Augusto Tcham','Benoni Domingos Pereira','Binto Camará','Bissiqué Joaquim','Brolim António Damas','Burama Gil Pombo','Carlita Mangar','Davicson Lona Mbana','Desejado Correia Forbs','Eleutério Emilio Dias Monteiro','Elson Correia','Emanuel Duarte Djata','Eugénio João Pereira','Eusébio Tcherno Mamudo Baldé','Fina Pereira','Isaias Paulino Incundé','Jacinto Siverino Mancanha','João Saliu Monteiro','Lucas Paulo Ialá','Luinela Edvises Papa Cá','Moises Sá','Naziana Nisco de Carvalho','Nucia Tobana Vasna','Raisa Ucalute Gomes','Rudilson Daivaneo Semedo Cá','Sabado Carlos Ntumbo','Saico Umaro Só','São João Fernando Carissali','Silvano Augusto','Tcherno Mamadú Camará','Timotio Sete','Vanilson Gomes da Costa']],
    'GHS-1T1' => ['ano' => 1, 'turno' => 'Tarde', 'esp' => null, 'names' => ['Adelio Cá','Aderito António Marcos','Amade Embaló','Amadu Djulde Djaló','Amadú E. Da Silva Nbotche','Amonique Cá','Bedamone Sandussa Nandiba','Benvinda Indiba','Binta Cassamá','Dabana Lóa Na Tcharré','Danilson Roel da Silva','Desejado Ilidio Dias','Edimilson Augusto Malel','Emanuela Nino Sambú','Emerson Dias Baldé','Eugénio Marcelo Semedo','Fodé Amara Sissé','Iasene Purna Ntchala','Ibraima Baldé','Jacira Correia','Juliana Gomes da Silva','Junior Augusto Landim','Leovanio Silvano Mendes','Mindo Aduquir Júnior da Silva','Mohamadu Baldé','Neia Ntchama','Nicaela Alfredo Correia','Pedro Seidi','Ronaldinho Edmilson Gomes Pereira','Salimatu Candé','Silvio Luis Caetano','Ussumane Sané','Valdimira Cabral Gomes','Vasco Alexandre Na Cul']],
    'GHS-1N1' => ['ano' => 1, 'turno' => 'Noite', 'esp' => null, 'names' => ['Manjupe Lais da Costa','Alfredo Tchuda','Arafam Cand','Calido Djau','Elizio João Pereira','Emanuel Biussum Iurna','Sabino Carvalho','Serginho Pedro Gomes Vilela']],
    'GHS-2M1' => ['ano' => 2, 'turno' => 'Manhã', 'esp' => null, 'names' => ['Aliu Águas','Armando Ié','Bidam-Mone Na Camine','Celestina Gomes','Cesaltina Joaquim Bailambi','Daimara Correia Mendes','Endem Camará','Fredinilton Pereira Bassali','Isis Djibril Camará','Jacir Abubacar Moreno Turé','Jovane Daniel Cutende','Juelson Mendes','Juscelino Lopes','Leorooney Mendes Sá Correia','Marcos Bissunha Ntchama','Marcos M. Nampunque','Mariama Tumane Quadé','Ncaram Bunha Cumba','Nelson Duarte da Silva','Nghale Wid Cumba','Tuncam Embaló','Zaira Alanam Nichudê']],
    'GHS-2T1' => ['ano' => 2, 'turno' => 'Tarde', 'esp' => null, 'names' => ['Andre Djata','António Tidjane Camará','Carlos Alberto Correia','Djabu Joãozinho da Costa','Eliana Correia Mendes','Iaia Seidi','Ijaquiel Armando Sanca','José Silva Nhaga','Luisella Mané Lopes dos Santos','Mamadu Baldé','Sat-na Faie Siga','Sinaider F. da Silva','Suaila Djata','Zafenate Quintino Mondi']],
    'GHS-3M1' => ['ano' => 3, 'turno' => 'Manhã', 'esp' => null, 'names' => ['Carlos Isnaba Bidonga','Dias Domingos Ialá','Dutim Rodrigues','Elizabete Marena','Elizio da Silva','Hatissary Thayssa Sá Nogueira','Junaid Ibn Abulai Conté','Massirem da Costa Djaló','Samba Baldé','Serifo Amadú Fadil Seidi','Tidjane Indjai','Tcherno Camará']],
    'GHS-3T1' => ['ano' => 3, 'turno' => 'Tarde', 'esp' => null, 'names' => ['Buba Martinho Na Forna','Claus Roxin Jorge da Costa','Fatumata Seidi','Gershon Quadé Ié','Jaqueline M. M. Lopes Nonaque','Naida Na Biatchiba','Tussem Mendes','Ussumane Ponqué']],
    'GHS-4T1' => ['ano' => 4, 'turno' => 'Tarde', 'esp' => null, 'names' => ['Amadú Julde Djaló','Dingana Nimina Embana','Diosives Pedro Nunes Crobute','Djibril Tchamo','Elizabete Vaz Moreno','Erikson Wogna Fanda','Fernando Augusto Malú','Francisco N. Na Nhassé','Idjatu Dabó','Ivan Sajo Samananco','Luizela Sanhá Pereira Tecanhe','Tiago Yalá']],
    'GHS-5TRD1' => ['ano' => 5, 'turno' => 'Tarde', 'esp' => 'Redes de Computadores', 'names' => ['Adulai Camará','Aléssio José Rebelo Barbosa','Alqueia Nanque','Cabomarim Filipe Catame','Domingos Fafé','Ela Candé','Francisco Andre da Silva','Isnaba Conha Inta-a','Issa Djau','Madjer Moaquim Malam Sanhá Baió','Mafudje Bá Jau','Miriam Nhaga','Roberto Kabi Naguadé','Uleimato Jaló','Valeri Cardose']],
    'GHS-5NBD1' => ['ano' => 5, 'turno' => 'Noite', 'esp' => 'Banco de Dados', 'names' => ['Artimiza Iano Sá','Badora Agostinho Djata','Bubacar Baldé','Felklin Pedro da Silva Júnior','Ieró Baldé','Ivana Ivanovica de Oliveira Quelute','Leonardo António Kassama','Nadia Lopes Nank Ié','Pier Tanhá','Quintino Nunes']],
];

echo "Iniciando...\n";
foreach ($students_data as $turma_code => $data) {
    echo "Processando $turma_code...\n";
    $ano_id = $ano_flip[$data['ano']] ?? null;
    $turma_id = array_search($turma_code, $turma_res);
    $esp_id = ($data['esp']) ? ($esp_flip[$data['esp']] ?? null) : null;
    
    if (!$ano_id || !$turma_id) {
        echo "AVISO: Falha nos IDs para $turma_code\n";
        continue;
    }

    foreach ($data['names'] as $full_name) {
        $parts = explode(' ', strtolower($full_name));
        $first = $parts[0] ?? 'aluno';
        $last = $parts[count($parts)-1] ?? 'ghs';
        $email = $first . '.' . $last . rand(1000,9999) . '@ghs.school';
        $password = 'ghs123456';
        $tipo = ($data['ano'] == 1) ? 'Novo Ingresso' : 'Renovação';

        createStudent($full_name, $email, $password, $ano_id, $turma_id, $data['turno'], $tipo, $esp_id);
    }
}
echo "Processo Finalizado.";
