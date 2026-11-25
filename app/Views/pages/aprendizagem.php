<?php
$lessonUrl = static fn (string $slug): string => url('pages/aprendizagem') . '?aula=' . rawurlencode($slug);
$lessonNumber = $lessonIndex + 1;
$lessonCount = count($lessons);
$lesson = $lessons[$lessonIndex] ?? null;
if (!$lesson): ?>
    <section class="container py-5">
        <h1>Aprendizagem</h1>
        <p>Ainda não há aulas publicadas.</p>
    </section>
<?php return; endif;
[$lessonSlug, $lessonTitle, , $description, $type, , $bodyText, $category] = $lesson;
?>

<div class="lesson-page">
    <div class="lesson-layout">
        <nav class="lesson-menu" aria-label="Navegação da aprendizagem">
            <a href="<?= e(url('pages/index')) ?>"><i class="bi bi-house-door" aria-hidden="true"></i> Início</a>
            <a href="<?= e(url('pages/aprendizagem')) ?>" aria-current="page"><i class="bi bi-book" aria-hidden="true"></i> Aprender</a>
            <a href="#ajuda-aula"><i class="bi bi-question-circle" aria-hidden="true"></i> Ajuda</a>
        </nav>

        <section class="lesson-outline" aria-labelledby="lesson-module-title">
            <h1 id="lesson-module-title"><?= e($category ?? 'Aprendizagem') ?></h1>
            <p>Aula <?= e($lessonNumber) ?></p>
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
            <?php if ($description): ?><p><?= e($description) ?></p><?php endif; ?>
            <?php if ($mediaUrl && $type === 'video'): ?>
                <div class="lesson-video"><video controls preload="metadata" playsinline src="<?= e($mediaUrl) ?>" aria-labelledby="lesson-title"></video></div>
            <?php elseif ($mediaUrl && $type === 'audio'): ?>
                <audio controls preload="none" src="<?= e($mediaUrl) ?>" aria-labelledby="lesson-title"></audio>
            <?php elseif ($mediaUrl && $type === 'imagem'): ?>
                <img class="img-fluid" src="<?= e($mediaUrl) ?>" alt="<?= e($description ?: $lessonTitle) ?>">
            <?php endif; ?>

            <div class="mb-3">
                <a class="btn-material-aula" href="<?= e(url('pages/material')) ?>?aula=<?= e($lessonNumber) ?>">
                    <i class="bi bi-journal-text" aria-hidden="true"></i> Abrir material da aula
                </a>
            </div>

            <?php if ($bodyText): ?><p><?= nl2br(e($bodyText)) ?></p><?php endif; ?>

            <?php if ($completed): ?>
                <p role="status">Aula concluída.</p>
            <?php else: ?>
                <form method="post" action="<?= e(url('pages/completeLesson')) ?>" class="mb-3">
                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                    <input type="hidden" name="lesson" value="<?= e($lessonSlug) ?>">
                    <button class="btn btn-primary" type="submit">Marcar aula como concluída</button>
                </form>
            <?php endif; ?>

            <?php if ($activities): ?>
                <section id="atividades" aria-labelledby="activities-title">
                    <h3 id="activities-title">Atividades da aula</h3>
                    <?php if ($activityMessage): ?><p role="status"><?= e($activityMessage) ?></p><?php endif; ?>
                    <?php foreach ($activities as $activity): ?>
                        <article class="mb-3">
                            <h4><?= e($activity['title']) ?></h4>
                            <p><?= nl2br(e($activity['prompt'])) ?></p>
                            <?php if ($activity['completed_at']): ?>
                                <p>Atividade concluída.</p>
                            <?php else: ?>
                                <form method="post" action="<?= e(url('pages/answerActivity')) ?>">
                                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                    <input type="hidden" name="lesson" value="<?= e($lessonSlug) ?>">
                                    <input type="hidden" name="activity" value="<?= e($activity['id']) ?>">
                                    <?php if ($activity['choices']): ?>
                                        <fieldset class="mb-3">
                                            <legend>Escolha a resposta</legend>
                                            <?php foreach (explode("\n", $activity['choices']) as $index => $choice): ?>
                                                <?php $optionId = 'answer-' . $activity['id'] . '-' . $index; ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="answer" id="<?= e($optionId) ?>" value="<?= e($choice) ?>" required>
                                                    <label class="form-check-label" for="<?= e($optionId) ?>"><?= e($choice) ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        </fieldset>
                                    <?php elseif ($activity['requires_answer']): ?>
                                        <label for="answer-<?= e($activity['id']) ?>">Sua resposta</label>
                                        <input class="form-control mb-2" id="answer-<?= e($activity['id']) ?>" name="answer" required maxlength="150">
                                    <?php endif; ?>
                                    <button class="btn btn-secondary" type="submit"><?= $activity['requires_answer'] ? 'Enviar resposta' : 'Concluir atividade' ?></button>
                                </form>
                            <?php endif; ?>
                        </article>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>

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
                <p><a href="<?= e(url('pages/help') . '#suporte') ?>">Enviar uma dúvida ao suporte</a></p>
            </details>
        </section>
    </div>
</div>