<div class="container py-5" style="max-width: 760px;">
    <a href="<?= e(url('admin/users')) ?>" class="d-inline-block mb-3">
        <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Voltar para alunos
    </a>
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-md-5">
            <h1 class="h2 mb-4"><?= e($title) ?></h1>
            <?php if (isset($errors['form'])): ?>
                <p class="alert alert-danger" role="alert"><?= e($errors['form']) ?></p>
            <?php endif; ?>
            <form method="post" action="<?= e($action) ?>">
                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                <?php foreach ([
                    ['name', 'Nome completo', 'text', 'name', 100, true],
                    ['email', 'E-mail', 'email', 'email', 150, true],
                    ['cpf', 'CPF', 'text', 'off', 14, true],
                    ['address', 'Endereço', 'text', 'street-address', 250, $creating],
                    ['phone', 'Telefone (opcional)', 'tel', 'tel', 20, false],
                ] as [$field, $label, $type, $autocomplete, $length, $required]): ?>
                    <div class="mb-3">
                        <label class="form-label" for="<?= e($field) ?>"><?= e($label) ?></label>
                        <input class="form-control<?= is_invalid($errors, $field) ?>" id="<?= e($field) ?>"
                               name="<?= e($field) ?>" type="<?= e($type) ?>" autocomplete="<?= e($autocomplete) ?>"
                               maxlength="<?= e($length) ?>" value="<?= e($input[$field] ?? '') ?>"
                               <?= $required ? 'required' : '' ?>>
                        <?= feedback($errors, $field) ?>
                    </div>
                <?php endforeach; ?>
                <?php if ($creating): ?>
                    <?php foreach (['new_password' => 'Senha inicial', 'confirm_password' => 'Confirmar senha'] as $field => $label): ?>
                        <div class="mb-3">
                            <label class="form-label" for="<?= e($field) ?>"><?= e($label) ?></label>
                            <input class="form-control<?= is_invalid($errors, $field) ?>" type="password"
                                   id="<?= e($field) ?>" name="<?= e($field) ?>" autocomplete="new-password" required>
                            <?= feedback($errors, $field) ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-primary" type="submit"><?= $creating ? 'Cadastrar aluno' : 'Salvar alterações' ?></button>
                    <a class="btn btn-outline-secondary" href="<?= e(url('admin/users')) ?>">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
