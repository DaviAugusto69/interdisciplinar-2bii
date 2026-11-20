<?php
/** @var array $users */
/** @var string $search */
/** @var string $csrf */
?>

<div class="container py-5">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <a href="<?= e(url('admin/index')) ?>" class="text-decoration-none d-inline-block mb-2">
                <i class="bi bi-arrow-left"></i> Voltar ao painel
            </a>
            <h2 class="fw-bold mb-1">Gerenciar usuários</h2>
            <p class="text-muted mb-0">Consulte os alunos cadastrados, acompanhe o progresso e remova contas.</p>
        </div>

        <form method="get" action="<?= e(url('admin/users')) ?>" class="d-flex gap-2">
            <input type="text"
                   name="q"
                   value="<?= e($search) ?>"
                   class="form-control"
                   placeholder="Buscar por nome ou e-mail..."
                   style="min-width: 260px;">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <?php if ($search !== ''): ?>
                <a href="<?= e(url('admin/users')) ?>" class="btn btn-outline-secondary">Limpar</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <?php if (empty($users)): ?>
                <p class="p-4 mb-0 text-muted">
                    <?= $search !== '' ? 'Nenhum usuário encontrado para "' . e($search) . '".' : 'Nenhum usuário cadastrado ainda.' ?>
                </p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Progresso</th>
                                <th class="text-end pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $item): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold">#<?= (int) $item['id'] ?></td>
                                    <td class="fw-bold"><?= e($item['name']) ?></td>
                                    <td class="text-muted"><?= e($item['email']) ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?= (int) $item['completed_count'] ?> aula(s) concluída(s)
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <form method="post"
                                              action="<?= e(url('admin/deleteUser/' . (int) $item['id'])) ?>"
                                              data-name="<?= e($item['name']) ?>"
                                              onsubmit="return confirm('Tem certeza que deseja excluir o usuário: ' + this.dataset.name + '? Todo o progresso dele será removido.');">
                                            <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>