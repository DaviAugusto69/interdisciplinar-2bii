<main class="container py-5">
    <a href="<?= e(url('admin/index')) ?>">Voltar ao painel administrativo</a>
    <h1 class="my-3"><?= e($title) ?></h1>
    <?php foreach (['categories' => ['Categorias', $categories], 'activities' => ['Atividades', $activities], 'rewards' => ['Recompensas', $rewards]] as $kind => [$label, $items]): ?>
        <section class="mb-4" aria-labelledby="<?= e($kind) ?>-title">
            <h2 id="<?= e($kind) ?>-title"><?= e($label) ?></h2>
            <?php foreach ([[], ...$items] as $item): ?>
                <?php
                $id = $item['id'] ?? 0;
                $prefix = $kind . '-' . $id . '-';
                ?>
                <details class="card p-3 mb-2">
                    <summary><?= e($item['name'] ?? $item['title'] ?? 'Adicionar') ?></summary>
                    <form class="mt-3" method="post" action="<?= e(url('admin/saveLearning')) ?>">
                        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                        <input type="hidden" name="kind" value="<?= e($kind) ?>">
                        <input type="hidden" name="id" value="<?= e($id) ?>">
                        <?php if ($kind === 'activities'): ?>
                            <label for="<?= e($prefix) ?>lesson" class="form-label">Aula</label>
                            <select id="<?= e($prefix) ?>lesson" name="lesson_id" class="form-select mb-2" required>
                                <option value="">Selecione</option>
                                <?php foreach ($lessons as $lesson): ?>
                                    <option value="<?= e($lesson['id']) ?>" <?= (int) ($item['lesson_id'] ?? 0) === (int) $lesson['id'] ? 'selected' : '' ?>><?= e($lesson['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <label for="<?= e($prefix) ?>title" class="form-label">Título</label>
                            <input id="<?= e($prefix) ?>title" class="form-control mb-2" name="title" required maxlength="150" value="<?= e($item['title'] ?? '') ?>">
                            <label for="<?= e($prefix) ?>prompt" class="form-label">Enunciado</label>
                            <textarea id="<?= e($prefix) ?>prompt" class="form-control mb-2" name="prompt" required><?= e($item['prompt'] ?? '') ?></textarea>
                            <label for="<?= e($prefix) ?>choices" class="form-label">Alternativas para quiz ou cenário (opcional)</label>
                            <textarea id="<?= e($prefix) ?>choices" class="form-control mb-2" name="choices" rows="3" placeholder="De 2 a 6 alternativas, uma por linha"><?= e($item['choices'] ?? '') ?></textarea>
                            <label for="<?= e($prefix) ?>answer" class="form-label">Resposta esperada (copie a alternativa correta, se houver)</label>
                            <input id="<?= e($prefix) ?>answer" class="form-control mb-2" name="correct_answer" maxlength="150" value="<?= e($item['correct_answer'] ?? '') ?>">
                        <?php else: ?>
                            <label for="<?= e($prefix) ?>name" class="form-label">Nome</label>
                            <input id="<?= e($prefix) ?>name" class="form-control mb-2" name="name" required maxlength="100" value="<?= e($item['name'] ?? '') ?>">
                        <?php endif; ?>
                        <?php if ($kind === 'rewards'): ?>
                            <label for="<?= e($prefix) ?>description" class="form-label">Descrição</label>
                            <textarea id="<?= e($prefix) ?>description" class="form-control mb-2" name="description" required><?= e($item['description'] ?? '') ?></textarea>
                        <?php endif; ?>
                        <?php if ($kind !== 'categories'): ?>
                            <label for="<?= e($prefix) ?>points" class="form-label"><?= $kind === 'rewards' ? 'Pontos necessários' : 'Pontos concedidos' ?></label>
                            <input id="<?= e($prefix) ?>points" class="form-control mb-2" name="points" type="number" min="0" max="1000000" required value="<?= e($item['points'] ?? $item['required_points'] ?? 0) ?>">
                            <label class="form-check-label mb-2">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" <?= ($item['is_active'] ?? 1) ? 'checked' : '' ?>> Ativo
                            </label>
                        <?php endif; ?>
                        <div><button type="submit" class="btn btn-primary">Salvar</button></div>
                    </form>
                </details>
            <?php endforeach; ?>
        </section>
    <?php endforeach; ?>
</main>
