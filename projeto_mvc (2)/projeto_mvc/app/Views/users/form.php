<?php
$formTitle = isset($title) && is_string($title) ? $title : '';
$csrfToken = isset($csrf) && is_string($csrf) ? $csrf : '';
$oldValues = isset($old) && is_array($old) ? $old : [];
$formErrors = isset($errors) && is_array($errors) ? $errors : [];
?>

<h1><?= e($formTitle) ?></h1>

<form method="post" action="<?= url('user/store') ?>" data-validate>
    <input type="hidden" name="csrf" value="<?= e($csrfToken) ?>">

    <label>Nome
        <input name="name" value="<?= e($oldValues['name'] ?? '') ?>">
    </label>
    <?php if (isset($formErrors['name'])): ?>
        <p class="erro"><?= e($formErrors['name']) ?></p>
    <?php endif; ?>

    <label>E-mail
        <input name="email" value="<?= e($oldValues['email'] ?? '') ?>">
    </label>
    <?php if (isset($formErrors['email'])): ?>
        <p class="erro"><?= e($formErrors['email']) ?></p>
    <?php endif; ?>

    <button type="submit">Salvar</button>
</form>
