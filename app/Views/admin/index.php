

<?php
/** @var string $adminName */
/** @var int $totalUsers */
/** @var int $totalContents */
?>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Painel Administrativo</h2>
            <p class="text-muted">
                Bem-vindo(a), <?= e($adminName) ?>!
            </p>
        </div>
    </div>

    <hr>

    <h4 class="fw-bold mb-3">Visão geral</h4>

    <div class="row g-4">

        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h6 class="text-muted">Usuários</h6>
                    <h3 class="fw-bold">
                    <?= (int) $totalUsers ?>
                    </h3>
                    <p class="mb-0 text-muted">Cadastrados</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h6 class="text-muted">Conteúdos</h6>
                    <h3 class="fw-bold">
                      <?= (int) $totalContents ?>
                    </h3>
                    <p class="mb-0 text-muted">Disponíveis</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h6 class="text-muted">Atividades</h6>
                    <h3 class="fw-bold">--</h3>
                    <p class="mb-0 text-muted">Concluídas</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h6 class="text-muted">Acessos</h6>
                    <h3 class="fw-bold">--</h3>
                    <p class="mb-0 text-muted">À plataforma</p>
                </div>
            </div>
        </div>

    </div>

    <h4 class="fw-bold mt-5 mb-3">Administração</h4>

    <div class="row g-4">

        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold">Gerenciar usuários</h5>
                    <p class="text-muted">
                        Consulte e gerencie os usuários cadastrados.
                    </p>
                    <a href="<?= e(url('user/index')) ?>"
                       class="btn btn-primary">
                        Acessar usuários
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4">
                    <h5 class="fw-bold">Gerenciar conteúdos</h5>
                    <p class="text-muted">
                        Administre os materiais educacionais do EDA.
                    </p>
                    <button class="btn btn-secondary" disabled>
                        Em desenvolvimento
                    </button>
                </div>
            </div>
        </div>

    </div>

</div>
