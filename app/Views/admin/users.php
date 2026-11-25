<?php
/** @var array $users */
/** @var string $search */
/** @var string $csrf */
?>

<section class="container py-4 py-md-5" aria-labelledby="admin-users-title">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <a href="<?= e(url('admin/index')) ?>" class="text-decoration-none d-inline-block mb-2">
                <i class="bi bi-arrow-left"></i> Voltar ao painel
            </a>
            <h1 class="h2 fw-bold mb-1" id="admin-users-title">Gerenciar usuários</h1>
            <p class="text-muted mb-0">Cadastre e edite alunos, acompanhe o progresso e delete contas da lista ativa.</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="<?= e(url('admin/archives#alunos')) ?>" class="btn btn-outline-primary">Ver deletados</a>
            <a href="<?= e(url('admin/userForm')) ?>" class="btn btn-primary">
                <i class="bi bi-person-plus me-1" aria-hidden="true"></i> Cadastrar aluno
            </a>
        </div>
    </div>

    <?php if (!empty($notice)): ?>
        <p class="alert alert-success" role="status"><?= e($notice) ?></p>
    <?php endif; ?>

    <form method="get" action="<?= e(url('admin/users')) ?>" class="card shadow-sm border-0 rounded-4 p-3 p-md-4 mb-4" role="search">
        <label for="admin-user-search" class="form-label fw-semibold">Buscar por nome ou e-mail</label>
        <div class="d-flex flex-column flex-sm-row gap-2">
            <input type="search" id="admin-user-search" name="q" value="<?= e($search) ?>" class="form-control">
            <button type="submit" class="btn btn-outline-primary">
                <i class="bi bi-search me-1" aria-hidden="true"></i> Buscar
            </button>
            <?php if ($search !== ''): ?>
                <a href="<?= e(url('admin/users')) ?>" class="btn btn-outline-secondary">Limpar</a>
            <?php endif; ?>
        </div>
    </form>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-transparent px-3 px-md-4 py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h2 class="h5 mb-0"><?= $search === '' ? 'Alunos ativos' : 'Resultados da busca' ?></h2>
            <span class="text-muted"><?= e(count($users)) ?> <?= count($users) === 1 ? 'aluno' : 'alunos' ?></span>
        </div>
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
                                <th scope="col" class="ps-4">ID</th>
                                <th scope="col">Nome</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Progresso</th>
                                <th scope="col" class="text-end pe-4">Ações</th>
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
                                        <div><?= e($item['activities_completed']) ?> atividades · <?= e($item['points']) ?> pontos</div>
                                        <div><?= e($item['access_count']) ?> acessos</div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex flex-wrap justify-content-end gap-2">
                                        <a href="<?= e(url('admin/userForm/' . (int) $item['id'])) ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil me-1" aria-hidden="true"></i> Editar
                                        </a>
                                        <form method="post"
                                              action="<?= e(url('admin/deleteUser/' . (int) $item['id'])) ?>"
                                              data-name="<?= e($item['name']) ?>"
                                              onsubmit="return confirm('Deseja deletar a conta de ' + this.dataset.name + '? O acesso será desativado. A conta e o histórico ficarão em Deletados.');">
                                            <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-archive me-1" aria-hidden="true"></i> Deletar
                                            </button>
                                        </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</section>
