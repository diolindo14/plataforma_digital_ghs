<?php
$db = new PDO('mysql:host=localhost;dbname=ghsespf_db', 'root', '');
$db->exec("SET NAMES utf8mb4");
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$data = [
    "1M1" => [
        "Segunda" => ["IGE/lab1", "QUIM/S1", "TI/S1", "PORT/S1"],
        "Terça"   => ["FIS/S1", "MAT/S1", "TI/S1", null],
        "Quarta"  => ["MAT/S1", "FIS/S1", "ING/S1", null],
        "Quinta"  => ["IGE/lab1", "APL/S1", "GDA/S1", "QUIM/S1"],
        "Sexta"   => ["MAT/S1", "GDA/S1", "PORT/S1", "ING/S1"]
    ],
    "1T1" => [
        "Segunda" => [null, "TI/S1", "GDA/S1", "IGE/LAB1"],
        "Terça"   => ["QUIM/S1", "APL/S1", "IGE/LAB1", "FIS/S1"],
        "Quarta"  => ["PORT/S1", "TI/S1", "MAT/S1", "FIS/S1"],
        "Quinta"  => [null, "GDA/S1", "MAT/S1", "ING/S1"],
        "Sexta"   => ["PORT/S1", "QUIM/S1", "MAT/S1", "ING/S1"]
    ],
    "1N1" => [
        "Segunda" => ["QUIM/S1", "IGE/LAB1", "TI/S1", "GDA/S1"],
        "Terça"   => ["MAT/SR", "FIS/S1", "GDA/S1", null],
        "Quarta"  => ["PORT/SR", "FIS/S1", "IGE/LAB1", "ING/S1"],
        "Quinta"  => ["MAT/SR", "QUIM/S1", "APL/S1", null],
        "Sexta"   => ["MAT/SR", "ING/S1", "TI/S1", "PORT/S1"]
    ],
    "2M1" => [
        "Segunda" => ["MAT/S2", "IGE/LAB1", "POO/S2", null],
        "Terça"   => ["PORT/S2", "AED/LAB1", "ALGA/S2", "MAT/S3"],
        "Quarta"  => ["PORT/S2", "AED/LAB1", "ALGA/S2", "MAT/S2"],
        "Quinta"  => ["ECC/S2", "ECC/S2", "ALGA/S2", "ING/S2"],
        "Sexta"   => ["ING/S2", "IGE/LAB1", "POO/S2", null]
    ],
    "2T1" => [
        "Segunda" => ["PORT/S2", "ECC/S2", "AED/LAB2", null],
        "Terça"   => ["PORT/BIB", "MAT/S2", "ALGA/S2", null],
        "Quarta"  => ["ECC/S3", "AED/LAB1", "IGE/LAB1", "ING/S2"],
        "Quinta"  => ["POO/S3", "MAT/S2", "ALGA/S2", "ING/S2"],
        "Sexta"   => ["ALGA/S2", "MAT/S2", "IGE/LAB1", "POO/S2"]
    ],
    "2N1" => [
        "Segunda" => ["POO/S2", "AED/S2", "ECC/S2", "IGE/LAB1"],
        "Terça"   => ["ALGA/S2", "MAT/S2", "ING/S2", "PORT/S2"],
        "Quarta"  => ["POO/BIB", "MAT/S2", "ALGA/S2", null],
        "Quinta"  => ["AED/LAB1", "ECC/S2", "IGE/LAB1", "PORT/S2"],
        "Sexta"   => ["ALGA/BIB", "MAT/S2", "ING/S2", null]
    ],
    "3M1" => [
        "Segunda" => ["HM/S3", "SO/S3", "JAVA/LAB1", "FBD/S3"],
        "Terça"   => ["CDSI/S3", "PHP/S3", "RD1/LAB1", "HM/S3"],
        "Quarta"  => ["PHP/LAB1", "SO/S3", "FBD/S3", null],
        "Quinta"  => ["CDSI/S3", "TC/S3", "JAVASCR/S3", null],
        "Sexta"   => ["PHP/LAB1", "FBD/S3", "RD1/S3", "TC/S3"]
    ],
    "3T1" => [
        "Segunda" => ["HM/BIB", "TC/S3", "SO/S3", "PHP/S3"],
        "Terça"   => [null, "RD1/S3", "PHP/S3", "CDSI/LAB1"],
        "Quarta"  => ["RD1/LAB1", "FBD/S3", "SO/S3", "TC/S3"],
        "Quinta"  => ["PHP/BIB", "FBD/S3", "JAVASCR/S3", "CDSI/S3"],
        "Sexta"   => [null, "HM/S3", "FBD/S3", "JAVASCR/LAB1"]
    ],
    "4T1" => [
        "Segunda" => ["PI/LAB3", "RD2/LAB1", "IPM/LAB3", "SID/LAB3"],
        "Terça"   => ["MCG/LAB1", "IA/LAB3", "TSI/LAB3", "MC/LAB3"],
        "Quarta"  => ["MCG/LAB3", "RD2/LAB2", "ES/LAB3", "SID/LAB3"],
        "Quinta"  => ["IPM/LAB3", "MC/LAB3", "TSI/LAB3", null],
        "Sexta"   => ["PI/LAB3", "ES/LAB3", "IA/LAB3", null]
    ],
    "4N1" => [
        "Segunda" => ["IA/BIB", "ES/LAB3", "TSI/LAB3", "IPM/LAB2"],
        "Terça"   => ["PI/BIB", "TSI/LAB3", "RD2/LAB1", "MCG/LAB3"],
        "Quarta"  => [null, "SID/LAB3", "MC/LAB3", "MCG/LAB3"],
        "Quinta"  => ["IA/BIB", "MC/LAB3", "PI/LAB3", "IPM/LAB3"],
        "Sexta"   => ["SID/S3", "RD2/LAB3", "ES/LAB3", null]
    ],
    "5NBD1" => [
        "Segunda" => ["SQL/LAB2", "AO/S3", "JAVA/LAB1", null],
        "Terça"   => ["SQL/S8", "VBNET/LAB1", "MA/LAB3", null],
        "Quarta"  => ["AO/LAB2", "JAVA/LAB1", "MA/S3", null],
        "Quinta"  => ["SQL/LAB2", "JAVA/S3", "VBNET/LAB2", null],
        "Sexta"   => ["AO/LAB2", "VBNET/LAB1", "MC/LAB1", "MC/S3"]
    ],
    "5TRD1" => [
        "Segunda" => ["IS/LAB1", "LINUX/LAB3", "AO/LAB1", null],
        "Terça"   => ["SR/LAB3", "MC/BIB", "LINUX/LAB2", null],
        "Quarta"  => ["WT/BIB", "SR/LAB3", "IS/S2", null],
        "Quinta"  => [null, "SR/BIB", "AO/LAB1", "WT/LAB3"],
        "Sexta"   => ["IS/LAB2", "LINUX/LAB1", "WT/S2", "MC/LAB3"]
    ]
];

$allDiscs = $db->query("SELECT id, codigo, nome, ano_id FROM disciplinas")->fetchAll(PDO::FETCH_ASSOC);

function findDid($val, $ano, $allDiscs) {
    if(!$val) return null;
    $parts = explode('/', $val);
    $sigla = trim($parts[0]);
    $sala = $parts[1] ?? null;

    $aliases = [
        'PORT' => 'POR',
        'AD LINUX' => 'LINUX',
        'SQLSRV' => 'SQL',
        'JAVASTD' => 'JAVA',
        'VBNET' => 'VB',
        'MAT' => 'MAT1'
    ];
    $cleanSigla = $aliases[$sigla] ?? $sigla;

    // 1. EXACT match in Target Year
    foreach($allDiscs as $d) {
        if($d['codigo'] == $cleanSigla && $d['ano_id'] == $ano) return ['id' => $d['id'], 'sala' => $sala];
    }
    // 2. EXACT match in ANY Year (Crucial fallback for shared codes)
    foreach($allDiscs as $d) {
        if($d['codigo'] == $cleanSigla) return ['id' => $d['id'], 'sala' => $sala];
    }
    // 3. Try variations like 'MC' -> 'MC5' or 'MC'1
    foreach($allDiscs as $d) {
        if(($d['codigo'] == $cleanSigla.$ano) && $d['ano_id'] == $ano) return ['id' => $d['id'], 'sala' => $sala];
    }
    // 4. Flexible match in Target Year
    foreach($allDiscs as $d) {
        if(str_contains($d['codigo'], $cleanSigla) && $d['ano_id'] == $ano) return ['id' => $d['id'], 'sala' => $sala];
    }
    // 5. Global Fallback for strings
    foreach($allDiscs as $d) {
        if(str_contains($d['codigo'], $cleanSigla) || str_contains($d['nome'], $cleanSigla)) return ['id' => $d['id'], 'sala' => $sala];
    }
    return null;
}

$turmas = [];
foreach($db->query("SELECT id, codigo, turno, ano_id FROM turmas")->fetchAll(PDO::FETCH_ASSOC) as $r) {
    foreach(array_keys($data) as $k) {
        if(str_contains($r['codigo'], $k)) $turmas[$k] = $r;
    }
}

function getTimes($turno, $tempo) {
    $map = [
        'Manhã' => [1 => ['07:20','08:50'], 2 => ['08:55','10:25'], 3 => ['10:30','12:00'], 4 => ['12:05','13:35']],
        'Tarde' => [1 => ['13:00','14:30'], 2 => ['14:35','16:05'], 3 => ['16:10','17:40'], 4 => ['17:45','19:15']],
        'Noite' => [1 => ['17:45','19:15'], 2 => ['19:20','20:50'], 3 => ['20:55','22:25'], 4 => ['22:30','00:00']]
    ];
    return $map[$turno][$tempo] ?? ['--:--', '--:--'];
}

$db->exec("DELETE FROM horarios");
$stmt = $db->prepare("INSERT INTO horarios (turma_id, disciplina_id, dia_semana, hora_inicio, hora_fim, sala, tempo_aula) VALUES (?, ?, ?, ?, ?, ?, ?)");

foreach($data as $turmaKey => $dias) {
    if(!isset($turmas[$turmaKey])) continue;
    $t = $turmas[$turmaKey];
    foreach($dias as $dia => $slots) {
        foreach($slots as $idx => $val) {
            $tempo = $idx + 1;
            $res = findDid($val, $t['ano_id'], $allDiscs);
            if($res) {
                $times = getTimes($t['turno'], $tempo);
                $stmt->execute([$t['id'], $res['id'], $dia, $times[0], $times[1], $res['sala'] ?? '', $tempo]);
            }
        }
    }
}
echo "DONE";
