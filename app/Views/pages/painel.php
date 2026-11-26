<div class="learning-panel">
    <div class="container px-3 px-md-4">
        <header class="learning-intro">
            <h1>Meu painel</h1>
            <p>Continue sua jornada digital com o EDA.</p>
        </header>
        <section class="learning-progress" aria-labelledby="learning-progress-title">
            <div class="learning-progress-content">
                <h2 id="learning-progress-title">Seu progresso</h2>
                <?php if ($total > 0): ?>
                    <p><?= e($completed) ?> de <?= e($total) ?> aulas concluídas.</p>
                    <progress value="<?= e($completed) ?>" max="<?= e($total) ?>" aria-label="Aulas concluídas"></progress>
                    <?php if ($completed === $total): ?>
                        <p role="status">Você concluiu todas as aulas publicadas.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p>Ainda não há aulas publicadas.</p>
                <?php endif; ?>
                <a class="btn btn-light fw-bold" href="<?= e(url('pages/aprendizagem') . ($nextLesson ? '?aula=' . rawurlencode($nextLesson) : '')) ?>">
                    <?= $nextLesson ? 'Continuar aprendendo' : 'Ver aulas' ?>
                </a>
            </div>
            <img src="<?= e(str_replace(' ', '%20', asset('assets/EDA PAGINA INICIAL.png'))) ?>" width="6123" height="4082" alt="">
        </section>

        <?php if ($modules): ?>
            <section class="learning-modules" aria-labelledby="modules-title">
                <h2 id="modules-title">Cursos</h2>
                <div class="dashboard-course-grid">
                    <?php foreach ($modules as $name => $module): ?>
                        <?php $courseProgress = $module['total'] > 0 ? (int) round(($module['completed'] / $module['total']) * 100) : 0; ?>
                        <article class="learning-card dashboard-course-card">
                            <div class="dashboard-course-card-top">
                                <span class="dashboard-course-icon"><i class="bi bi-<?= e($module['icon']) ?>" aria-hidden="true"></i></span>
                                <span class="dashboard-course-label">Curso</span>
                            </div>
                            <h3><?= e($name) ?></h3>
                            <p><?= e($module['completed']) ?> de <?= e($module['total']) ?> aulas concluídas.</p>
                            <?php if ($module['completed'] === $module['total']): ?><p>Curso concluído.</p><?php endif; ?>
                            <progress class="dashboard-course-meter" value="<?= e($courseProgress) ?>" max="100" aria-label="Progresso no curso <?= e($name) ?>"></progress>
                            <a class="dashboard-card-link" href="<?= e(url('pages/aprendizagem') . '?aula=' . rawurlencode($module['slug'])) ?>">Abrir curso <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($recentLessons): ?>
            <section class="learning-modules" aria-labelledby="recent-lessons-title">
                <h2 id="recent-lessons-title">Últimas aulas publicadas</h2>
                <div class="dashboard-lesson-grid">
                    <?php foreach ($recentLessons as $recent): ?>
                        <?php $lessonIcon = trim((string) ($recent['icon'] ?? '')) ?: 'play-circle'; ?>
                        <a class="dashboard-lesson-card" href="<?= e(url('pages/aprendizagem') . '?aula=' . rawurlencode($recent['slug'])) ?>">
                            <span class="dashboard-lesson-icon"><i class="bi bi-<?= e($lessonIcon) ?>" aria-hidden="true"></i></span>
                            <span class="dashboard-lesson-copy">
                                <?php if (!empty($recent['category'])): ?><small><?= e($recent['category']) ?></small><?php endif; ?>
                                <strong><?= e($recent['title']) ?></strong>
                                <?php if (!empty($recent['description'])): ?><span><?= e($recent['description']) ?></span><?php endif; ?>
                            </span>
                            <i class="bi bi-arrow-up-right dashboard-lesson-arrow" aria-hidden="true"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <section class="learning-activities" aria-labelledby="rewards-title">
            <h2 id="rewards-title">Minhas recompensas</h2>
            <?php if (!$rewards): ?><p>Você ainda não conquistou recompensas.</p><?php endif; ?>
            <?php foreach ($rewards as $reward): ?>
                <article class="learning-activity">
                    <h3><?= e($reward['name']) ?></h3>
                    <p><?= e($reward['description']) ?></p>
                </article>
            <?php endforeach; ?>
        </section>

    </div>
</div>
