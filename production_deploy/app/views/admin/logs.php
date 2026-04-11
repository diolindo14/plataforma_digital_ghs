<?php
// app/views/admin/logs.php
?>
<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary"><ion-icon name="list-outline" class="me-2"></ion-icon> Auditoria de Sistema (Logs)</h5>
            <span class="badge bg-soft-primary text-primary"><?php echo $total_logs; ?> Registos</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>Data/Hora</th>
                            <th>Utilizador</th>
                            <th>Ação</th>
                            <th>Detalhes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $log): ?>
                            <tr>
                                <td class="text-nowrap"><?php echo date('d/m/Y H:i:s', strtotime($log['data_acao'])); ?></td>
                                <td><span class="badge bg-light text-dark">ID: <?php echo $log['utilizador_id']; ?></span></td>
                                <td><strong><?php echo htmlspecialchars($log['acao']); ?></strong></td>
                                <td class="text-muted small"><?php echo htmlspecialchars($log['descricao']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginação Helper (Pilar 4) -->
            <nav aria-label="Navegação de logs" class="mt-4">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= ceil($total_logs / 50); $i++): ?>
                        <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                            <a class="page-link" href="?url=admin/logs/<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>
