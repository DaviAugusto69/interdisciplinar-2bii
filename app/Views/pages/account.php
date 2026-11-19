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

        <form id="account-form" method="post" class="account-form" data-status-message="<?= e($unavailable) ?>">
            <?php
            foreach ($fields as [$id, $label, $type, $autocomplete, $icon, $placeholder, $wide]):
            ?>
                <div class="account-field<?= $wide ? ' account-wide' : '' ?>">
                    <label for="<?= e($id) ?>"><?= e($label) ?></label>
                    <div class="account-input">
                        <i class="bi bi-<?= e($icon) ?>" aria-hidden="true"></i>
                        <input id="<?= e($id) ?>" type="<?= e($type) ?>" autocomplete="<?= e($autocomplete) ?>"
                            placeholder="<?= e($placeholder) ?>"<?= $id === 'cpf' ? ' inputmode="numeric" maxlength="14"' : '' ?> required>
                    </div>
                </div>
            <?php endforeach; ?>
            <button class="account-primary account-submit account-wide" type="submit"><?= e($submitLabel) ?></button>
        </form>

        <p class="account-signin"><?= e($footerPrompt) ?> <a href="<?= e(url('pages/' . $footerRoute)) ?>"><?= e($footerLink) ?></a></p>
        <p id="account-status" role="status"></p>
    </div>
</section>

<script>
    const accountForm = document.getElementById('account-form');
    const accountStatus = document.getElementById('account-status');
    const password = document.getElementById('senha');
    const confirmation = document.getElementById('confirmacao');

    confirmation?.addEventListener('input', () => confirmation.setCustomValidity(''));
    password?.addEventListener('input', () => confirmation?.setCustomValidity(''));
    accountForm.addEventListener('submit', (event) => {
        event.preventDefault();
        if (confirmation && password.value !== confirmation.value) {
            confirmation.setCustomValidity('As senhas precisam ser iguais.');
            confirmation.reportValidity();
            return;
        }

        accountStatus.textContent = accountForm.dataset.statusMessage;
    });
</script>
