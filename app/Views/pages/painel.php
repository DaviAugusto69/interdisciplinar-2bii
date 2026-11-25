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
                <h2 id="modules-title">Módulos</h2>
                <div class="learning-grid">
                    <?php foreach ($modules as $name => $module): ?>
                        <article class="learning-card">
                            <h3><?= e($name) ?></h3>
                            <p><?= e($module['completed']) ?> de <?= e($module['total']) ?> aulas concluídas.</p>
                            <?php if ($module['completed'] === $module['total']): ?><p>Módulo concluído.</p><?php endif; ?>
                            <a href="<?= e(url('pages/aprendizagem') . '?aula=' . rawurlencode($module['slug'])) ?>">Abrir módulo <?= e($name) ?></a>
                        </article>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($recentLessons): ?>
            <section class="learning-modules" aria-labelledby="recent-lessons-title">
                <h2 id="recent-lessons-title">Últimas aulas publicadas</h2>
                <ul>
                    <?php foreach ($recentLessons as $recent): ?>
                        <li><a href="<?= e(url('pages/aprendizagem') . '?aula=' . rawurlencode($recent['slug'])) ?>"><?= e($recent['title']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
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

        <form method="post" action="<?= e(url('pages/logout')) ?>">
            <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
            <button class="btn btn-outline-primary" type="submit">Sair da conta</button>
        </form>
    </div>
</div>
