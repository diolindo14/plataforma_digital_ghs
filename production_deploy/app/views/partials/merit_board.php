<?php
/**
 * Partial View: Painel de Mérito Académico (Ranking)
 *
 * Variáveis esperadas (injetadas pelo controlador):
 *   $ranking_escola : array  - Top 3 melhores alunos da escola (de getRankingEscola)
 *   $ranking_nivel  : array  - Melhor aluno por nível (de getRankingByNivel)
 *   $show_details   : bool   - Se true, mostra o ranking por nível (padrão false)
 */
$ranking_escola = $ranking_escola ?? [];
$ranking_nivel  = $ranking_nivel ?? [];
$show_details   = $show_details ?? true;

if (empty($ranking_escola) && empty($ranking_nivel)) return;

$medalhas = ['🥇', '🥈', '🥉'];
$cores_medalha = ['#F59E0B', '#9CA3AF', '#CD7C3D'];
?>

<div class="merit-panel card-glass" style="
    background: linear-gradient(135deg, rgba(16,185,129,0.08) 0%, rgba(59,130,246,0.08) 100%);
    border: 1px solid rgba(16,185,129,0.25);
    border-radius: 20px;
    padding: 24px;
    margin-bottom: 24px;
">
    <!-- Header -->
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px;">
        <div style="
            width:44px; height:44px; border-radius:50%;
            background: linear-gradient(135deg,#F59E0B,#EF4444);
            display:flex; align-items:center; justify-content:center;
            font-size:22px; box-shadow:0 4px 12px rgba(245,158,11,0.4);
        ">🏆</div>
        <div>
            <h3 style="margin:0; color:#0f172a; font-size:1.1rem; font-weight:700;">Quadro de Mérito</h3>
            <p style="margin:0; color:#64748b; font-size:0.78rem;">Melhores alunos da instituição</p>
        </div>
    </div>

    <?php if (!empty($ranking_escola)): ?>
    <!-- TOP 3 DA ESCOLA -->
    <div style="margin-bottom: <?= $show_details ? '20px' : '0' ?>;">
        <p style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin:0 0 12px;">🏟 Melhores da Escola</p>
        <div style="display:flex; flex-direction:column; gap:10px;">
            <?php foreach ($ranking_escola as $i => $aluno): ?>
            <div style="
                display:flex; align-items:center; gap:12px;
                background: rgba(255,255,255,0.6);
                border-radius:12px; padding:10px 14px;
                border-left: 4px solid <?= $cores_medalha[$i] ?? '#94a3b8' ?>;
                transition: transform .2s;
            " onmouseover="this.style.transform='translateX(4px)'" onmouseout="this.style.transform='translateX(0)'">
                <span style="font-size:1.6rem; min-width:32px; text-align:center;"><?= $aluno['medalha'] ?? $medalhas[$i] ?? '🏅' ?></span>
                <div style="
                    width:38px; height:38px; border-radius:50%; overflow:hidden;
                    background: linear-gradient(135deg,#3b82f6,#10b981);
                    display:flex; align-items:center; justify-content:center;
                    color:white; font-weight:700; font-size:0.85rem; flex-shrink:0;
                ">
                    <?php if (!empty($aluno['foto_perfil'])): ?>
                        <img src="<?= URL_ROOT ?>/<?= htmlspecialchars($aluno['foto_perfil']) ?>" style="width:100%;height:100%;object-fit:cover;" alt="">
                    <?php else: ?>
                        <?= strtoupper(substr($aluno['nome'], 0, 1)) ?>
                    <?php endif; ?>
                </div>
                <div style="flex:1; min-width:0;">
                    <p style="margin:0; font-weight:600; color:#0f172a; font-size:0.85rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        <?= htmlspecialchars($aluno['nome']) ?>
                    </p>
                    <p style="margin:0; color:#64748b; font-size:0.72rem;">
                        <?= htmlspecialchars($aluno['nivel_nome'] ?? '') ?>
                    </p>
                </div>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'aluno'): ?>
                <div style="text-align:right; flex-shrink:0;">
                    <p style="margin:0; font-weight:800; color:#94a3b8; font-size:0.9rem;" title="Nota Ocultada por Privacidade"><ion-icon name="lock-closed-outline" style="vertical-align:-2px; margin-right:2px;"></ion-icon>Privado</p>
                </div>
                <?php else: ?>
                <div style="text-align:right; flex-shrink:0;">
                    <p style="margin:0; font-weight:800; color:#10b981; font-size:1rem;"><?= number_format((float)($aluno['media_geral'] ?? 0), 1) ?></p>
                    <p style="margin:0; color:#94a3b8; font-size:0.68rem;">média</p>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($show_details && !empty($ranking_nivel)): ?>
    <!-- MELHOR POR NÍVEL -->
    <div>
        <p style="font-size:0.75rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:1px; margin:0 0 12px;">📚 Melhor por Nível</p>
        <div style="display:grid; gap:8px;">
            <?php foreach ($ranking_nivel as $entry): ?>
            <div style="
                display:flex; align-items:center; justify-content:space-between;
                background: rgba(255,255,255,0.5); border-radius:10px;
                padding: 8px 14px;
            ">
                <div style="display:flex; align-items:center; gap:10px;">
                    <span style="
                        background:rgba(99,102,241,0.15); color:#6366f1;
                        border-radius:6px; padding:2px 8px;
                        font-size:0.72rem; font-weight:700; white-space:nowrap;
                    "><?= htmlspecialchars($entry['nivel_nome']) ?></span>
                    <span style="color:#1e293b; font-size:0.82rem; font-weight:500;">
                        🥇 <?= htmlspecialchars($entry['nome']) ?>
                    </span>
                </div>
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'aluno'): ?>
                <span style="
                    font-weight:700; color:#94a3b8; font-size:0.75rem;
                    background:rgba(148,163,184,0.1); padding:2px 8px;
                    border-radius:20px;
                "><ion-icon name="lock-closed-outline" style="vertical-align:-2px;"></ion-icon> Privado</span>
                <?php else: ?>
                <span style="
                    font-weight:800; color:#059669; font-size:0.9rem;
                    background:rgba(16,185,129,0.1); padding:2px 10px;
                    border-radius:20px;
                "><?= number_format((float)($entry['media_geral'] ?? 0), 1) ?></span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
