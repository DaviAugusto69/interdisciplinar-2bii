
<?php
/** @var string $csrf */
/** @var string $error */
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">
                        <h2 class="fw-bold">EDA</h2>
                        <p class="text-muted">
                            Acesso administrativo
                        </p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger">
                            <?= e($error) ?>
                        </div>
                    <?php endif; ?>

                    <form
                        action="<?= e(url('admin/authenticate')) ?>"
                        method="POST"
                    >

                        <input
                            type="hidden"
                            name="csrf"
                            value="<?= e($csrf) ?>"
                        >

                        <div class="mb-3">
                            <label class="form-label">
                                E-mail
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="Digite seu e-mail"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label class="form-label">
                                Senha
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                placeholder="Digite sua senha"
                                required
                            >
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >
                            Entrar no painel
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
