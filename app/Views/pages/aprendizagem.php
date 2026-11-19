<?php
$lessonUrl = static fn (string $slug): string => url('pages/aprendizagem') . '?aula=' . rawurlencode($slug);
$lessonNumber = $lessonIndex + 1;
$lessonCount = count($lessons);
$lessonTitle = $lessons[$lessonIndex][1];
?>

<div class="lesson-page">
    <div class="lesson-layout">
        <nav class="lesson-menu" aria-label="Navegação da aprendizagem">
            <a href="<?= e(url('pages/index')) ?>"><i class="bi bi-house-door" aria-hidden="true"></i> Início</a>
            <a href="<?= e(url('pages/aprendizagem')) ?>" aria-current="page"><i class="bi bi-book" aria-hidden="true"></i> Aprender</a>
            <a href="#ajuda-aula"><i class="bi bi-question-circle" aria-hidden="true"></i> Ajuda</a>
        </nav>

        <section class="lesson-outline" aria-labelledby="lesson-module-title">
            <h1 id="lesson-module-title">Aprendendo a usar o celular</h1>
            <p>Módulo 1 · Aula <?= e($lessonNumber) ?></p>
            <div class="lesson-position">
                <progress value="<?= e($lessonNumber) ?>" max="<?= e($lessonCount) ?>" aria-label="Posição na sequência de aulas"></progress>
                <span><?= e($lessonNumber) ?> de <?= e($lessonCount) ?> aulas</span>
            </div>
            <nav aria-label="Aulas do módulo">
                <?php foreach ($lessons as $index => [$slug, $name, $icon]): ?>
                    <a href="<?= e($lessonUrl($slug)) ?>"<?= $lessonIndex === $index ? ' aria-current="page"' : '' ?>>
                        <i class="bi bi-<?= e($icon) ?>" aria-hidden="true"></i> <?= e($name) ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </section>

        <section class="lesson-main" aria-labelledby="lesson-title">
            <h2 id="lesson-title"><?= e($lessonTitle) ?></h2>
            <div class="lesson-video">
                <?php if ($videoPath !== null): ?>
                    <video controls preload="metadata" playsinline aria-labelledby="lesson-title">
                        <source src="<?= e(asset($videoPath)) ?>" type="video/mp4">
                        Seu navegador não consegue reproduzir este vídeo.
                    </video>
                <?php else: ?>
                    <div class="lesson-placeholder">
                        <i class="bi bi-play-circle" aria-hidden="true"></i>
                        <p>O vídeo desta aula será disponibilizado em breve.</p>
                    </div>
                <?php endif; ?>
            </div>

            <nav class="lesson-controls" aria-label="Aula anterior e próxima">
                <?php if (isset($lessons[$lessonIndex - 1])): ?>
                    <a class="btn btn-primary" href="<?= e($lessonUrl($lessons[$lessonIndex - 1][0])) ?>"><i class="bi bi-chevron-left" aria-hidden="true"></i> Aula anterior</a>
                <?php else: ?>
                    <button class="btn btn-primary" type="button" disabled><i class="bi bi-chevron-left" aria-hidden="true"></i> Aula anterior</button>
                <?php endif; ?>
                <?php if (isset($lessons[$lessonIndex + 1])): ?>
                    <a class="btn btn-secondary" href="<?= e($lessonUrl($lessons[$lessonIndex + 1][0])) ?>">Próxima aula <i class="bi bi-chevron-right" aria-hidden="true"></i></a>
                <?php else: ?>
                    <a class="btn btn-secondary" href="<?= e(url('pages/painel')) ?>">Voltar ao painel <i class="bi bi-chevron-right" aria-hidden="true"></i></a>
                <?php endif; ?>
            </nav>

            <details class="lesson-help" id="ajuda-aula">
                <summary><i class="bi bi-chat-dots" aria-hidden="true"></i> Tem alguma dúvida sobre esta aula?</summary>
                <p>O envio de dúvidas ainda não está disponível pelo site.</p>
            </details>
        </section>
    </div>
</div>
