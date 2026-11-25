<?php
/** @var int $totalUsers */
/** @var int $totalContents */
/** @var int $completedActivities */
/** @var int $totalAccesses */
/** @var array $topLessons */
/** @var array $recentAccesses */
?>

<div class="container py-5">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <a href="<?= e(url('admin/index')) ?>" class="text-decoration-none d-inline-block mb-2">
                <i class="bi bi-arrow-left"></i> Voltar ao painel
            </a>
            <h2 class="fw-bold mb-1">Relatórios e indicadores</h2>
            <p class="text-muted mb-0">Resumo geral de engajamento, conclusão de conteúdos e acessos recentes.</p>
        </div>

        <button onclick="window.print()" class="btn btn-outline-primary">
            Imprimir relatório
        </button>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <small class="text-muted">Total de alunos</small>
                    <h3 class="fw-bold mb-0"><?= (int) $totalUsers ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <small class="text-muted">Conteúdos ativos</small>
                    <h3 class="fw-bold mb-0"><?= (int) $totalContents ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <small class="text-muted">Atividades concluídas</small>
                    <h3 class="fw-bold mb-0"><?= (int) $completedActivities ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <small class="text-muted">Acessos registrados</small>
                    <h3 class="fw-bold mb-0"><?= (int) $totalAccesses ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Conteúdos mais concluídos</h5>
                    <?php if (empty($topLessons)): ?>
                        <p class="text-muted mb-0">Nenhuma atividade concluída ainda.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Conteúdo</th>
                                        <th class="text-end">Conclusões</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($topLessons as $row): ?>
                                        <tr>
                                            <td><?= e($row['title']) ?></td>
                                            <td class="text-end fw-bold"><?= (int) $row['total'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Últimos acessos à plataforma</h5>
                    <?php if (empty($recentAccesses)): ?>
                        <p class="text-muted mb-0">Nenhum registro de acesso encontrado.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Usuário</th>
                                        <th class="text-end">Data e hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentAccesses as $log): ?>
                                        <tr>
                                            <td class="text-muted"><?= $log['user_id'] ? '#' . e($log['user_id']) : 'Visitante' ?></td>
                                            <td class="text-end text-muted"><?= e($log['accessed_at'] ?? $log['created_at'] ?? '—') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>