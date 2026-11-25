<?php
/** @var string $adminName */
/** @var int $totalUsers */
/** @var int $totalContents */
/** @var int $completedActivities */
/** @var int $totalAccesses */
?>

<div class="container py-5">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Painel Administrativo</h2>
            <p class="text-muted mb-0">
                Bem-vindo(a), <?= e($adminName) ?>!
            </p>
        </div>

        <a class="btn btn-outline-primary" href="<?= e(url('admin/learning')) ?>">Categorias, atividades e recompensas</a>
        <form action="<?= e(url('admin/logout')) ?>" method="post">
            <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
            <button class="btn btn-outline-danger" type="submit">Sair do painel</button>
        </form>
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
                    <h3 class="fw-bold">
                        <?= (int) $completedActivities ?>
                    </h3>
                    <p class="mb-0 text-muted">Concluídas</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body">
                    <h6 class="text-muted">Acessos</h6>
                    <h3 class="fw-bold">
                        <?= (int) $totalAccesses ?>
                    </h3>
                    <p class="mb-0 text-muted">À plataforma</p>
                </div>
            </div>
        </div>

    </div>

    <h4 class="fw-bold mt-5 mb-3">Administração</h4>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold">Gerenciar usuários</h5>
                        <p class="text-muted">
                            Consulte, pesquise e gerencie os usuários cadastrados.
                        </p>
                    </div>
                    <div>
                        <a href="<?= e(url('admin/users')) ?>" class="btn btn-primary">
                            Acessar usuários
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold">Gerenciar conteúdos</h5>
                        <p class="text-muted">
                            Administre os materiais educacionais do EDA.
                        </p>
                    </div>
                    <div>
                        <a href="<?= e(url('admin/contents')) ?>" class="btn btn-primary">
                            Acessar conteúdos
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="fw-bold">Relatórios</h5>
                        <p class="text-muted">
                            Acompanhe os indicadores de uso e engajamento da plataforma.
                        </p>
                    </div>
                    <div>
                        <a href="<?= e(url('admin/reports')) ?>" class="btn btn-primary">
                            Ver relatórios
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>