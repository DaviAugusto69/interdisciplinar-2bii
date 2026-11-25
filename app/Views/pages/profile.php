<?php

/**
 * Variáveis enviadas pelo PagesController::profile() (via extract no render).
 * @var array|null  $user
 * @var array       $prefs
 * @var string      $csrf
 * @var string|null $ok
 * @var string|null $form
 * @var array       $errors
 * @var array       $old
 */
$eDados = $form === 'dados' ? $errors : [];
$eSenha = $form === 'senha' ? $errors : [];
$ePrefs = $form === 'prefs' ? $errors : [];
$oDados = $form === 'dados' ? $old : [];
?>
<section class="py-5 eda-page">
    <div class="container px-5">
        <!-- Perfil e configurações-->
        <div class="bg-light rounded-4 py-5 px-4 px-md-5">
            <div class="text-center mb-5">
                <div class="feature bg-primary bg-gradient-primary-to-secondary text-white rounded-3 mb-3"><i class="bi bi-person-circle" aria-hidden="true"></i></div>
                <h1 class="fw-bolder">Perfil e configurações</h1>
                <p class="lead fw-normal text-muted mb-0">Seus dados, sua senha e o jeito que o site aparece para você.</p>
            </div>

            <!-- Atalhos para as seções da página-->
            <nav class="d-flex flex-wrap justify-content-center gap-2 mb-5" aria-label="Seções desta página">
                <a class="btn btn-outline-primary" href="#dados"><i class="bi bi-person me-1" aria-hidden="true"></i>Dados pessoais</a>
                <a class="btn btn-outline-primary" href="#senha"><i class="bi bi-key me-1" aria-hidden="true"></i>Senha</a>
                <a class="btn btn-outline-primary" href="#fonte"><i class="bi bi-type me-1" aria-hidden="true"></i>Tamanho da letra</a>
                <a class="btn btn-outline-primary" href="#tema"><i class="bi bi-circle-half me-1" aria-hidden="true"></i>Contraste e tema</a>
            </nav>

            <div class="row gx-5 justify-content-center">
                <div class="col-lg-8 col-xl-6">

                    <!-- 1. Dados pessoais-->
                    <?php if ($user !== null): ?>
                    <div class="card shadow border-0 rounded-4 mb-4" id="dados">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="h4 fw-bolder text-primary mb-4">Dados pessoais</h2>
                            <form method="post" action="<?= e(url('pages/updateProfile')) ?>" novalidate>
                                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                <!-- Name input-->
                                <div class="form-floating mb-3">
                                    <input class="form-control<?= is_invalid($eDados, 'name') ?>" id="name" name="name" type="text" placeholder="Digite seu nome..." autocomplete="name" value="<?= e($oDados['name'] ?? $user['name']) ?>" />
                                    <label for="name">Nome completo</label>
                                    <?= feedback($eDados, 'name') ?>
                                </div>
                                <!-- Email address input-->
                                <div class="form-floating mb-3">
                                    <input class="form-control<?= is_invalid($eDados, 'email') ?>" id="email" name="email" type="email" placeholder="nome@exemplo.com" autocomplete="email" value="<?= e($oDados['email'] ?? $user['email']) ?>" />
                                    <label for="email">E-mail</label>
                                    <?= feedback($eDados, 'email') ?>
                                </div>
                                <!-- Phone number input-->
                                <div class="form-floating mb-3">
                                    <input class="form-control<?= is_invalid($eDados, 'phone') ?>" id="phone" name="phone" type="tel" placeholder="(77) 99999-9999" autocomplete="tel" value="<?= e($oDados['phone'] ?? $user['phone']) ?>" />
                                    <label for="phone">Telefone com DDD (opcional)</label>
                                    <?= feedback($eDados, 'phone') ?>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control<?= is_invalid($eDados, 'address') ?>" id="address" name="address" type="text" maxlength="250" autocomplete="street-address" placeholder="Endereço" value="<?= e($oDados['address'] ?? $user['address']) ?>">
                                    <label for="address">Endereço</label>
                                    <?= feedback($eDados, 'address') ?>
                                </div>
                                <!-- Success message-->
                                <?php if ($ok === 'dados'): ?>
                                    <div class="alert alert-success" role="status">Dados salvos com sucesso!</div>
                                <?php endif; ?>
                                <!-- Submit Button-->
                                <div class="d-grid"><button class="btn btn-primary btn-lg" type="submit">Salvar dados</button></div>
                            </form>
                        </div>
                    </div>

                    <!-- 2. Alterar senha-->
                    <div class="card shadow border-0 rounded-4 mb-4" id="senha">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="h4 fw-bolder text-primary mb-2">Alterar senha</h2>
                            <p class="text-muted mb-4">
                                <?= $user['has_password']
                                    ? 'Use pelo menos 8 caracteres, com letras e números. Nunca compartilhe sua senha com ninguém.'
                                    : 'Você ainda não tem uma senha. Crie uma agora: pelo menos 8 caracteres, com letras e números.' ?>
                            </p>
                            <form method="post" action="<?= e(url('pages/updatePassword')) ?>" novalidate>
                                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                <?php if ($user['has_password']): ?>
                                    <div class="form-floating mb-3">
                                        <input class="form-control<?= is_invalid($eSenha, 'current_password') ?>" id="current_password" name="current_password" type="password" placeholder="Senha atual" autocomplete="current-password" />
                                        <label for="current_password">Senha atual</label>
                                        <?= feedback($eSenha, 'current_password') ?>
                                    </div>
                                <?php endif; ?>
                                <div class="form-floating mb-3">
                                    <input class="form-control<?= is_invalid($eSenha, 'new_password') ?>" id="new_password" name="new_password" type="password" placeholder="Nova senha" autocomplete="new-password" />
                                    <label for="new_password">Nova senha</label>
                                    <?= feedback($eSenha, 'new_password') ?>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control<?= is_invalid($eSenha, 'confirm_password') ?>" id="confirm_password" name="confirm_password" type="password" placeholder="Repita a nova senha" autocomplete="new-password" />
                                    <label for="confirm_password">Repita a nova senha</label>
                                    <?= feedback($eSenha, 'confirm_password') ?>
                                </div>
                                <!-- Success message-->
                                <?php if ($ok === 'senha'): ?>
                                    <div class="alert alert-success" role="status">Senha alterada com sucesso!</div>
                                <?php endif; ?>
                                <!-- Submit Button-->
                                <div class="d-grid"><button class="btn btn-primary btn-lg" type="submit"><?= $user['has_password'] ? 'Alterar senha' : 'Criar senha' ?></button></div>
                            </form>
                        </div>
                    </div>

                    <?php endif; ?>

                    <!-- 3 e 4. Tamanho da letra + Contraste e tema (um só formulário)-->
                    <form method="post" action="<?= e(url('pages/updatePreferences')) ?>" id="prefs">
                        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

                        <?php if ($ok === 'prefs'): ?>
                            <div class="alert alert-success" role="status">Preferências salvas! Elas valem para todas as páginas.</div>
                        <?php endif; ?>
                        <?php if (isset($ePrefs['prefs'])): ?>
                            <div class="alert alert-danger" role="alert"><?= e($ePrefs['prefs']) ?></div>
                        <?php endif; ?>

                        <div class="card shadow border-0 rounded-4 mb-4" id="fonte">
                            <div class="card-body p-4 p-md-5">
                                <h2 class="h4 fw-bolder text-primary mb-2">Tamanho da letra</h2>
                                <p class="text-muted mb-4">Escolha o tamanho que for mais confortável para ler.</p>
                                <div class="btn-group w-100 mb-3" role="group" aria-label="Tamanho da letra">
                                    <input class="btn-check" type="radio" name="fonte" id="fonteNormal" value="normal" autocomplete="off" <?= $prefs['fonte'] === 'normal' ? 'checked' : '' ?> />
                                    <label class="btn btn-outline-primary" for="fonteNormal">Normal</label>
                                    <input class="btn-check" type="radio" name="fonte" id="fonteGrande" value="grande" autocomplete="off" <?= $prefs['fonte'] === 'grande' ? 'checked' : '' ?> />
                                    <label class="btn btn-outline-primary" for="fonteGrande">Grande</label>
                                    <input class="btn-check" type="radio" name="fonte" id="fonteMaior" value="maior" autocomplete="off" <?= $prefs['fonte'] === 'maior' ? 'checked' : '' ?> />
                                    <label class="btn btn-outline-primary" for="fonteMaior">Muito grande</label>
                                </div>
                                <p class="bg-light rounded-3 p-3 mb-0">Este texto mostra o tamanho que está em uso agora. Clique em &ldquo;Salvar preferências&rdquo; para trocar.</p>
                            </div>
                        </div>

                        <div class="card shadow border-0 rounded-4 mb-4" id="tema">
                            <div class="card-body p-4 p-md-5">
                                <h2 class="h4 fw-bolder text-primary mb-2">Contraste e tema</h2>
                                <p class="text-muted mb-4">Escolha as cores que deixam a leitura mais fácil para você.</p>
                                <div class="row row-cols-1 g-3" role="group" aria-label="Tema do site">
                                    <div class="col">
                                        <input class="btn-check" type="radio" name="tema" id="temaClaro" value="claro" autocomplete="off" <?= $prefs['tema'] === 'claro' ? 'checked' : '' ?> />
                                        <label class="btn btn-outline-primary eda-opcao w-100 p-3" for="temaClaro">
                                            <i class="bi bi-sun me-2" aria-hidden="true"></i><strong>Claro</strong>
                                            <span class="d-block small">Fundo branco, o padrão do site.</span>
                                        </label>
                                    </div>
                                    <div class="col">
                                        <input class="btn-check" type="radio" name="tema" id="temaEscuro" value="escuro" autocomplete="off" <?= $prefs['tema'] === 'escuro' ? 'checked' : '' ?> />
                                        <label class="btn btn-outline-primary eda-opcao w-100 p-3" for="temaEscuro">
                                            <i class="bi bi-moon-stars me-2" aria-hidden="true"></i><strong>Escuro</strong>
                                            <span class="d-block small">Fundo escuro, descansa a vista à noite.</span>
                                        </label>
                                    </div>
                                    <div class="col">
                                        <input class="btn-check" type="radio" name="tema" id="temaContraste" value="contraste" autocomplete="off" <?= $prefs['tema'] === 'contraste' ? 'checked' : '' ?> />
                                        <label class="btn btn-outline-primary eda-opcao w-100 p-3" for="temaContraste">
                                            <i class="bi bi-circle-half me-2" aria-hidden="true"></i><strong>Alto contraste</strong>
                                            <span class="d-block small">Preto, branco e amarelo, para enxergar melhor.</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-3">
                            <button class="btn btn-primary btn-lg" type="submit" name="acao" value="salvar">Salvar preferências</button>
                            <button class="btn btn-outline-primary" type="submit" name="acao" value="restaurar"><i class="bi bi-arrow-counterclockwise me-1" aria-hidden="true"></i>Restaurar padrão</button>
                        </div>
                    </form>
                    <?php if ($user): ?>
                        <div class="card shadow border-0 rounded-4 mt-4">
                            <div class="card-body p-4 p-md-5">
                                <h2 class="h4 fw-bolder mb-3">Excluir minha conta</h2>
                                <details>
                                    <summary class="btn btn-outline-danger">Excluir minha conta</summary>
                                    <p class="mt-3">Ao confirmar, você sairá do EDA e perderá o acesso à conta. Seu histórico será guardado e somente um administrador poderá restaurar o acesso.</p>
                                    <form method="post" action="<?= e(url('pages/deleteAccount')) ?>">
                                        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                        <button class="btn btn-danger" type="submit" name="confirm_delete" value="1">Confirmar exclusão da minha conta</button>
                                    </form>
                                </details>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
