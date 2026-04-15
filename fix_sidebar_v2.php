<?php
$f = 'c:/xampp/htdocs/green/production_deploy/app/views/estudante/dashboard.php';
$content = file($f);
// In Turn 41 fix, I had 1727 lines. Now I have 1718 because I deleted lines.
// Let's just fix the sidebar area precisely.
$all = implode('', $content);

$new_sidebar = '<nav class="sidebar ghs-sidebar shadow-lg d-flex flex-column justify-content-start">' . "\n" .
'        <div>' . "\n" .
'            <div class="sidebar-brand text-center mb-4 mt-2 border-bottom border-light border-opacity-10 pb-3">' . "\n" .
'                <div style="width: 64px; height: 64px; border-radius: 50%; border: 2px solid var(--ghs-primary); display: flex; align-items: center; justify-content: center; background: #fff; margin: 0 auto; overflow: hidden;">' . "\n" .
'                    <img src="<?= URL_ROOT ?>/img/logo.jpg" alt="Logo GHS" style="width: 100%; height: 100%; object-fit: cover;">' . "\n" .
'                </div>' . "\n" .
'                <h5 class="fw-bold text-white mb-1 mt-3" style="font-size: .95rem;">Green Hard & Softh</h5>' . "\n" .
'                <span class="badge" style="background:rgba(16,185,129,.15); color:var(--ghs-primary); border:1px solid rgba(16,185,129,.3); font-size: .65rem; letter-spacing: .06em;">PORTAL ESTUDANTE</span>' . "\n" .
'            </div>' . "\n\n" .
'            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">' . "\n" .
'                <a class="nav-link active" id="tab-home" data-bs-toggle="pill" data-bs-target="#pane-home" role="tab"><ion-icon name="grid-outline"></ion-icon> Meu Painel</a>' . "\n" .
'                <a class="nav-link" id="tab-horario" data-bs-toggle="pill" data-bs-target="#pane-horario" role="tab"><ion-icon name="calendar-outline"></ion-icon> Horário & Calendário</a>' . "\n" .
'                <a class="nav-link" id="tab-notas" data-bs-toggle="pill" data-bs-target="#pane-notas" role="tab"><ion-icon name="pie-chart-outline"></ion-icon> Avaliação Contínua</a>' . "\n" .
'                <a class="nav-link" id="tab-historico" data-bs-toggle="pill" data-bs-target="#pane-historico" role="tab"><ion-icon name="document-text-outline"></ion-icon> Histórico Académico</a>' . "\n" .
'                <a class="nav-link" id="tab-materiais" data-bs-toggle="pill" data-bs-target="#pane-materiais" role="tab"><ion-icon name="folder-open-outline"></ion-icon> Materiais Didáticos</a>' . "\n" .
'                <a class="nav-link" id="tab-sumarios" data-bs-toggle="pill" data-bs-target="#pane-sumarios" role="tab"><ion-icon name="reader-outline"></ion-icon> Sumários de Aula</a>' . "\n\n" .
'                <a class="nav-link" id="tab-financeiro" data-bs-toggle="pill" data-bs-target="#pane-financeiro" role="tab"><ion-icon name="wallet-outline"></ion-icon> Pagamentos</a>' . "\n" .
'                <a class="nav-link" id="tab-comunicados" data-bs-toggle="pill" data-bs-target="#pane-comunicados" role="tab"><ion-icon name="notifications-outline"></ion-icon> Comunicados & Alertas</a>' . "\n" .
'                <a class="nav-link text-info fw-bold" href="<?= URL_ROOT ?>/matricula"><ion-icon name="add-circle-outline"></ion-icon> Nova Matrícula</a>' . "\n" .
'                <a class="nav-link text-warning mb-1" href="<?= URL_ROOT ?>/"><ion-icon name="earth-outline"></ion-icon> Voltar ao Site</a>' . "\n" .
'                <a class="nav-link text-danger fw-bold" href="<?= URL_ROOT ?>/auth/logout"><ion-icon name="log-out-outline"></ion-icon> Terminar Sessão</a>' . "\n" .
'            </div>' . "\n" .
'        </div>' . "\n" .
'    </nav>';

// Find the start and end of the sidebar in the original content
$start_tag = '<nav class="sidebar ghs-sidebar shadow-lg d-flex flex-column">';
$end_tag = '</nav>';

$start_pos = strpos($all, $start_tag);
$end_pos = strpos($all, $end_tag, $start_pos);

if ($start_pos !== false && $end_pos !== false) {
    $before = substr($all, 0, $start_pos);
    $after = substr($all, $end_pos + strlen($end_tag));
    $final = $before . $new_sidebar . $after;
    file_put_contents($f, $final);
    echo "Fixed Student Production Dashboard Sidebar completely\n";
} else {
    echo "Could not find sidebar tags\n";
}

// Fixed Professor Production Dashboard Sidebar
$f2 = 'c:/xampp/htdocs/green/production_deploy/app/views/professor/dashboard.php';
$content2 = file_get_contents($f2);
$new_prof_sidebar = '<nav class="sidebar ghs-sidebar shadow-lg d-flex flex-column justify-content-start">';
$old_prof_sidebar = '<nav class="sidebar ghs-sidebar shadow-lg d-flex flex-column">';
$content2 = str_replace($old_prof_sidebar, $new_prof_sidebar, $content2);
file_put_contents($f2, $content2);
echo "Added justify-content-start to Professor Production Dashboard\n";
