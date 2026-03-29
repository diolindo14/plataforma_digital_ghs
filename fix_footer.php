<?php
$file = 'c:/xampp/htdocs/green/app/views/admin/dashboard.php';
$content = file_get_contents($file);

// Ponto de ancoragem seguro (antes da corrupção)
$anchor = "                    borderSkipped: false\n                }]\n            },";
$pos = strpos($content, $anchor);

if ($pos === false) {
    die("Erro: Anchor não encontrado no ficheiro.");
}

$cleanPart = substr($content, 0, $pos + strlen($anchor));

$footer = <<<'EOD'

            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.parsed.y} aluno(s)`
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { precision: 0 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // ── Gráfico de Pizza: Distribuição por Turno ─────────
    const pieCtx = document.getElementById('pieChart');
    if (pieCtx) {
        const chartTurnos = <?= json_encode($data['chartData']['turnos'] ?? ['Manhã'=>0,'Tarde'=>0,'Noite'=>0]) ?>;

        new Chart(pieCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(chartTurnos),
                datasets: [{
                    data: Object.values(chartTurnos),
                    backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#EF4444'],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                cutout: '60%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 16, font: { size: 13 } } },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.parsed} aluno(s) – ${ctx.label}`
                        }
                    }
                }
            }
        });
    }

    // ── Aprovação / Rejeição de Matrículas ────────────────
    $(document).on('click', '.btn-approve-matricula', function () {
        const id = $(this).data('id');
        if (id && confirm('Confirmar aprovação desta matrícula e criação de conta de aluno?')) {
            window.location.href = '<?= URL_ROOT ?>/admin/approveMatricula/' + id;
        }
    });

    $(document).on('click', '.btn-reject-matricula', function () {
        const id = $(this).data('id');
        if (id) {
            $('#rejectForm').attr('action', '<?= URL_ROOT ?>/admin/rejectMatricula/' + id);
            new bootstrap.Modal(document.getElementById('rejectModal')).show();
        }
    });

    // ── Edição de Itens via data-atributos ────────────────
    $(document).on('click', '.btn-edit-ano', function () {
        const d = $(this).data();
        $('#ano_id').val(d.id);
        $('#ano_numero').val(d.numero);
        $('#ano_nome').val(d.nome);
        $('#ano_descricao').val(d.desc);
        $('#ano_mensalidade').val(d.valor);
        $('#ano_ordem').val(d.ordem);
        $('#anoModalTitle').text('Editar Ano Curricular');
        new bootstrap.Modal(document.getElementById('anoModal')).show();
    });

    $(document).on('click', '.btn-edit-disciplina', function () {
        const d = $(this).data();
        $('#disc_id').val(d.id);
        $('#disc_codigo').val(d.codigo);
        $('#disc_nome').val(d.nome);
        $('#disc_ano').val(d.ano);
        $('#disc_carga').val(d.carga);
        $('#disc_credito').val(d.credito);
        $('#disc_desc').val(d.desc);
        $('#disciplinaModalTitle').text('Editar Disciplina');
        new bootstrap.Modal(document.getElementById('disciplinaModal')).show();
    });

    $(document).on('click', '.btn-edit-esp', function () {
        const d = $(this).data();
        $('#esp_id').val(d.id);
        $('#esp_codigo').val(d.codigo);
        $('#esp_nome').val(d.nome);
        $('#esp_descricao').val(d.desc);
        $('#esp_vagas').val(d.vagas);
        $('#esp_ativa').prop('checked', d.ativa == 1);
        $('#espModalTitle').text('Editar Especialidade');
        new bootstrap.Modal(document.getElementById('especialidadeModal')).show();
    });

    // ── Botão flutuante scroll-to-top ─────────────────────
    const topBtn = $('<button id="scrollTop" class="btn" style="position:fixed;bottom:24px;right:24px;width:44px;height:44px;border-radius:50%;background:#10B981;color:#fff;display:none;z-index:9999;box-shadow:0 4px 12px rgba(0,0,0,.2);"><i>↑</i></button>');
    topBtn.appendTo('body');
    $(window).on('scroll', () => {
        if ($(this).scrollTop() > 300) topBtn.fadeIn(); else topBtn.fadeOut();
    });
    topBtn.on('click', () => $('html,body').animate({ scrollTop: 0 }, 400));

});

function convocarComMotivo(eid, did) {
    const motivo = prompt("Por favor, indique o motivo da convocatória (Este texto será enviado ao Aluno e ao Professor):", "Convocatória oficial para resolução de conflito de notas.");
    if (motivo !== null) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `<?= URL_ROOT ?>/admin/convocarPartes/${eid}/${did}`;
        
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = 'csrf_token';
        csrf.value = '<?= $_SESSION['csrf_token'] ?>';
        form.appendChild(csrf);
        
        const motInput = document.createElement('input');
        motInput.type = 'hidden';
        motInput.name = 'motivo';
        motInput.value = motivo;
        form.appendChild(motInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

    <!-- Modal de Rejeição de Matrícula -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true" style="z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered">
            <form id="rejectForm" method="POST" class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="modal-header bg-danger text-white border-0 py-3">
                    <h5 class="modal-title fw-bold"><ion-icon name="alert-circle-outline" class="me-2"></ion-icon> Rejeitar Matrícula</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">Indique o motivo da rejeição. Esta informação será enviada ao estudante.</p>
                    <div class="form-floating">
                        <textarea class="form-control" name="motivo" placeholder="Motivo da Rejeição" id="rejection_motivo" style="height: 120px" required></textarea>
                        <label for="rejection_motivo">Motivo detalhado...</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4">
                    <button type="button" class="btn btn-light px-4 rounded-pill fw-bold" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger px-4 rounded-pill fw-bold shadow-sm">Confirmar Rejeição</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de Visualização de Documentos -->
    <div class="modal fade" id="documentViewerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="background: #1e293b;">
                <div class="modal-header border-0 bg-dark text-white px-4 py-3">
                    <div class="d-flex align-items-center gap-2">
                         <ion-icon name="document-attach-outline" class="fs-4 text-primary"></ion-icon>
                         <h5 class="modal-title fw-bold mb-0" id="viewerModalLabel">Visualizador de Ficheiros</h5>
                    </div>
                    <div class="btn-group btn-group-sm ms-auto me-3" id="docSelector">
                        <button class="btn btn-outline-light" onclick="loadSpecialDoc('bi')" id="btn-bi">BI</button>
                        <button class="btn btn-outline-light" onclick="loadSpecialDoc('cert')" id="btn-cert">Certificado</button>
                        <button class="btn btn-outline-light" onclick="loadSpecialDoc('comp')" id="btn-comp">Recibo</button>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" id="viewerContent" style="min-height: 80vh; background: #0f172a;">
                     <!-- Injeto Iframe ou Img aqui via JS -->
                </div>
            </div>
        </div>
    </div>

</body>
</html>
EOD;

file_put_contents($file, $cleanPart . $footer);
echo "Recuperação concluída.";
?>
