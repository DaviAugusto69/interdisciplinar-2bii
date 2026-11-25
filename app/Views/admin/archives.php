<div class="container py-4 py-md-5">
    <a href="<?= e(url('admin/index')) ?>" class="d-inline-block mb-3">Voltar ao painel</a>
    <h1 class="h2 fw-bold mb-4">Deletados</h1>

    <?php if ($notice): ?>
        <p class="alert alert-success" role="status"><?= e($notice) ?></p>
    <?php endif; ?>

    <section id="alunos" class="card shadow-sm border-0 rounded-4 mb-4" aria-labelledby="archived-users">
        <div class="card-body p-3 p-md-4">
            <h2 id="archived-users" class="h4">Alunos deletados</h2>
            <?php if (!$users): ?>
                <p class="mb-0">Nenhuma conta arquivada.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Progresso</th>
                                <th scope="col">Data do arquivamento</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= e($user['name']) ?></td>
                                    <td><?= e($user['email']) ?></td>
                                    <td><?= e($user['completed_count']) ?> aulas · <?= e($user['activities_completed']) ?> atividades · <?= e($user['points']) ?> pontos</td>
                                    <td><?= e($user['archived_at']) ?></td>
                                    <td>
                                        <form method="post" action="<?= e(url('admin/restoreUser/' . (int) $user['id'])) ?>">
                                            <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Restaurar conta</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </section>

</div>
