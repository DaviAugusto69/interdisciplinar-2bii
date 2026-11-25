<?php
/** @var array $contents */
/** @var string $csrf */

$typeLabels = [
    'video'  => ['Vídeo', 'bi-play-btn'],
    'texto'  => ['Texto', 'bi-file-text'],
    'audio'  => ['Áudio', 'bi-volume-up'],
    'imagem' => ['Imagem', 'bi-image'],
];
?>

<div class="container py-5">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <a href="<?= e(url('admin/index')) ?>" class="text-decoration-none d-inline-block mb-2">
                <i class="bi bi-arrow-left"></i> Voltar ao painel
            </a>
            <h2 class="fw-bold mb-1">Gerenciar conteúdos</h2>
            <p class="text-muted mb-0">Cadastre, edite ou delete materiais da área dos alunos.</p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="<?= e(url('admin/createContent')) ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Novo conteúdo
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-0">
            <?php if (empty($contents)): ?>
                <p class="p-4 mb-0 text-muted">Nenhum conteúdo cadastrado ainda.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Título</th>
                                <th>Formato</th>
                                <th>Descrição</th>
                                <th class="text-end pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contents as $item): ?>
                                <?php [$label, $typeIcon] = $typeLabels[$item['type']] ?? ['Outro', 'bi-book']; ?>
                                <tr>
                                    <td class="ps-4 fw-semibold">#<?= (int) $item['id'] ?></td>
                                    <td>
                                        <div class="fw-bold"><?= e($item['title']) ?></div>
                                        <small class="text-muted"><?= $item['is_published'] ? 'Publicado' : 'Rascunho' ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="bi <?= e($typeIcon) ?>"></i> <?= e($label) ?>
                                        </span>
                                    </td>
                                    <td class="text-muted" style="max-width: 320px;">
                                        <?= e($item['description'] ?? '—') ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-inline-flex gap-2">
                                            <a href="<?= e(url('admin/editContent/' . (int) $item['id'])) ?>"
                                               class="btn btn-sm btn-outline-primary">
                                                Editar
                                            </a>

                                            <form method="post"
                                                  action="<?= e(url('admin/deleteContent/' . (int) $item['id'])) ?>"
                                                  data-title="<?= e($item['title']) ?>"
                                                  onsubmit="return confirm('Deseja deletar o conteúdo ' + this.dataset.title + '? Ele sairá da área dos alunos, e o histórico será preservado.');">
                                                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Deletar
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

</div>
