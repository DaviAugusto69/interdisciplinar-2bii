<div class="learning-panel course-catalog">
    <div class="container px-3 px-md-4">
        <header class="learning-intro">
            <h1>Escolha seu curso</h1>
            <p>Explore os cursos do EDA e avance no seu ritmo.</p>
        </header>

        <section class="learning-modules" aria-labelledby="course-catalog-title">
            <h2 id="course-catalog-title">Cursos disponíveis</h2>
            <?php if ($courses): ?>
                <div class="learning-grid">
                    <?php foreach ($courses as $course): ?>
                        <?php
                        $courseHref = url('pages/aprendizagem') . ($course['category_id'] === null
                            ? '?aula=' . rawurlencode($course['slug'])
                            : '?curso=' . rawurlencode((string) $course['category_id']));
                        ?>
                        <article class="learning-card course-catalog-card">
                            <span class="course-catalog-icon"><i class="bi bi-<?= e($course['icon']) ?>" aria-hidden="true"></i></span>
                            <h3><?= e($course['name']) ?></h3>
                            <p><?= e($course['description']) ?></p>
                            <p class="course-catalog-count"><?= e($course['count']) ?> aulas</p>
                            <a href="<?= e($courseHref) ?>">Abrir curso <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Ainda não há cursos publicados.</p>
            <?php endif; ?>
        </section>
    </div>
</div>
