<?php
/** @var string $title */
/** @var string $action */
/** @var ?array $lesson */
/** @var string $csrf */
/** @var string $error */

$selectedType = $lesson['type'] ?? 'video';
?>

<div class="container py-5" style="max-width: 760px;">

    <a href="<?= e(url('admin/contents')) ?>" class="text-decoration-none d-inline-block mb-3">
        <i class="bi bi-arrow-left"></i> Voltar para conteúdos
    </a>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-md-5">
            <h2 class="fw-bold mb-4"><?= e($title) ?></h2>

            <?php if ($error !== ''): ?>
                <div class="alert alert-danger" role="alert">
                    <?= e($error) ?>
                </div>
            <?php endif; ?>

            <form method="post" action="<?= e($action) ?>">
                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Título da aula / conteúdo *</label>
                    <input type="text"
                           class="form-control"
                           id="title"
                           name="title"
                           required
                           value="<?= e($lesson['title'] ?? '') ?>"
                           placeholder="Ex.: Como identificar mensagens falsas no WhatsApp">
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="type" class="form-label fw-semibold">Formato do material *</label>
                        <select class="form-select" id="type" name="type">
                            <option value="video" <?= $selectedType === 'video' ? 'selected' : '' ?>>Vídeo</option>
                            <option value="texto" <?= $selectedType === 'texto' ? 'selected' : '' ?>>Texto</option>
                            <option value="audio" <?= $selectedType === 'audio' ? 'selected' : '' ?>>Áudio</option>
                            <option value="imagem" <?= $selectedType === 'imagem' ? 'selected' : '' ?>>Imagem</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="icon" class="form-label fw-semibold">Ícone (Bootstrap Icons)</label>
                        <input type="text"
                               class="form-control"
                               id="icon"
                               name="icon"
                               value="<?= e($lesson['icon'] ?? 'play-btn') ?>"
                               placeholder="Ex.: play-btn, phone, book, shield-check">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">Descrição curta</label>
                    <textarea class="form-control"
                              id="description"
                              name="description"
                              rows="2"
                              placeholder="Resumo simples do que o aluno vai aprender."><?= e($lesson['description'] ?? '') ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="media_url" class="form-label fw-semibold">Caminho ou link da mídia (vídeo, áudio ou imagem)</label>
                    <input type="text"
                           class="form-control"
                           id="media_url"
                           name="media_url"
                           value="<?= e($lesson['media_url'] ?? '') ?>"
                           placeholder="Ex.: assets/nome-do-video.mp4">
                </div>

                <div class="mb-4">
                    <label for="body_text" class="form-label fw-semibold">Conteúdo em texto / roteiro da aula</label>
                    <textarea class="form-control"
                              id="body_text"
                              name="body_text"
                              rows="5"
                              placeholder="Escreva aqui o texto explicativo para leitura acessível."><?= e($lesson['body_text'] ?? '') ?></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        Salvar conteúdo
                    </button>
                    <a href="<?= e(url('admin/contents')) ?>" class="btn btn-outline-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</div>