<section class="account-page" aria-labelledby="account-title">
    <div class="account-card">
        <header class="account-intro">
            <span class="account-icon" aria-hidden="true"><i class="bi bi-<?= e($accountIcon) ?>"></i></span>
            <h1 id="account-title"><?= e($heading) ?></h1>
            <p><?= e($intro) ?></p>
        </header>

        <nav class="account-options" aria-label="Opções de conta">
            <?php foreach (['login' => 'Entrar', 'contact' => 'Cadastrar', 'recoverPassword' => 'Recuperar senha'] as $route => $text): ?>
                <a href="<?= e(url('pages/' . $route)) ?>"<?= $accountPage === $route ? ' class="account-primary" aria-current="page"' : '' ?>><?= e($text) ?></a>
            <?php endforeach; ?>
        </nav>

        <?php if (!empty($errors['form'])): ?>
            <p role="alert"><?= e($errors['form']) ?></p>
        <?php endif; ?>
        <form id="account-form" method="post" class="account-form"
            <?php if (isset($formAction)): ?>action="<?= e($formAction) ?>"
            <?php else: ?>data-status-message="<?= e($unavailable) ?>"<?php endif; ?>>
            <?php if (isset($csrf)): ?>
                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
            <?php endif; ?>
            <?php
            foreach ($fields as [$id, $label, $type, $autocomplete, $icon, $placeholder, $wide]):
            ?>
                <div class="account-field<?= $wide ? ' account-wide' : '' ?>">
                    <label for="<?= e($id) ?>"><?= e($label) ?></label>
                    <div class="account-input">
                        <i class="bi bi-<?= e($icon) ?>" aria-hidden="true"></i>
                        <input id="<?= e($id) ?>" name="<?= e($id) ?>" type="<?= e($type) ?>" autocomplete="<?= e($autocomplete) ?>"
                            placeholder="<?= e($placeholder) ?>"<?php if (isset($old[$id])): ?> value="<?= e($old[$id]) ?>"<?php endif; ?>
                            <?php if (isset($errors[$id])): ?>aria-invalid="true" aria-describedby="<?= e($id) ?>-error"<?php endif; ?> required>
                    </div>
                    <?php if (isset($errors[$id])): ?>
                        <p id="<?= e($id) ?>-error" role="alert"><?= e($errors[$id]) ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <button class="account-primary account-submit account-wide" type="submit"><?= e($submitLabel) ?></button>
        </form>

        <p class="account-signin"><?= e($footerPrompt) ?> <a href="<?= e(url('pages/' . $footerRoute)) ?>"><?= e($footerLink) ?></a></p>
        <p id="account-status" role="status">
            <?php if (!empty($ok)): ?>
                Conta criada com sucesso. <a href="<?= e(url('pages/profile')) ?>">Ver meu perfil</a>
            <?php elseif (!empty($accountDeleted)): ?>
                Sua conta foi excluída e o acesso foi desativado.
            <?php endif; ?>
        </p>
    </div>
</section>

<?php if (!isset($formAction)): ?>
<script>
    const accountForm = document.getElementById('account-form');
    const accountStatus = document.getElementById('account-status');
    accountForm.addEventListener('submit', (event) => {
        event.preventDefault();
        accountStatus.textContent = accountForm.dataset.statusMessage;
    });
</script>
<?php endif; ?>
