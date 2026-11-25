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
$settingsSection = $form ?? $ok;
$name = $user['name'] ?? 'Aluno';
$nameParts = preg_split('/\s+/u', trim($name), -1, PREG_SPLIT_NO_EMPTY) ?: [];
$initials = mb_strtoupper(mb_substr($nameParts[0] ?? 'A', 0, 1)
    . (count($nameParts) > 1 ? mb_substr($nameParts[count($nameParts) - 1], 0, 1) : ''));
$percent = $lessonCount > 0 ? (int) round(100 * $completedCount / $lessonCount) : 0;
?>
<section class="eda-page profile-shell" aria-labelledby="profile-title">
    <div class="profile-workspace">
        <header class="profile-heading">
            <h1 id="profile-title"><i class="bi bi-person-circle" aria-hidden="true"></i> Meu perfil</h1>
            <div class="profile-heading-actions">
                <a href="#configuracoes" class="btn btn-outline-primary"><i class="bi bi-gear" aria-hidden="true"></i> Configurações</a>
                <form method="post" action="<?= e(url('pages/logout')) ?>">
                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                    <button type="submit" class="btn btn-outline-primary"><i class="bi bi-box-arrow-right" aria-hidden="true"></i> Sair da conta</button>
                </form>
            </div>
        </header>

        <div class="profile-identity">
            <span class="profile-avatar" aria-hidden="true"><?= e($initials) ?></span>
            <div class="profile-identity-text">
                <h2><?= e($name) ?></h2>
                <span class="profile-role"><i class="bi bi-mortarboard" aria-hidden="true"></i> Estudante</span>
            </div>
            <?php if ($user): ?>
                <a href="#dados" class="btn btn-primary btn-lg profile-edit"><i class="bi bi-pencil" aria-hidden="true"></i> Editar perfil</a>
            <?php endif; ?>
        </div>

        <section class="profile-panel profile-progress" aria-labelledby="profile-progress-title">
            <div class="profile-progress-heading">
                <h2 id="profile-progress-title"><i class="bi bi-bar-chart-fill" aria-hidden="true"></i> Meu progresso</h2>
                <strong><?= e($percent) ?>% concluído</strong>
            </div>
            <progress value="<?= e($percent) ?>" max="100" aria-labelledby="profile-progress-title"></progress>
            <div class="profile-progress-footer">
                <p><?= e($completedCount) ?> de <?= e($lessonCount) ?> aulas concluídas</p>
                <a href="<?= e(url('pages/painel')) ?>" class="btn btn-outline-primary">Ver progresso</a>
            </div>
        </section>

        <div class="profile-overview-grid">
            <section class="profile-panel" aria-labelledby="profile-lessons-title">
                <h2 class="profile-panel-title profile-panel-title-green" id="profile-lessons-title"><i class="bi bi-book" aria-hidden="true"></i> Continue aprendendo</h2>
                <?php if (!$pendingLessons): ?>
                    <p class="profile-empty"><?= $lessonCount ? 'Você concluiu todas as aulas publicadas.' : 'Ainda não há aulas publicadas.' ?></p>
                <?php else: ?>
                    <ul class="profile-list">
                        <?php foreach ($pendingLessons as $lesson): ?>
                            <li>
                                <a href="<?= e(url('pages/aprendizagem') . '?aula=' . rawurlencode($lesson[0])) ?>">
                                    <span class="profile-list-icon"><i class="bi bi-<?= e($lesson[2]) ?>" aria-hidden="true"></i></span>
                                    <span><strong><?= e($lesson[1]) ?></strong><?php if ($lesson[7] !== null): ?><small><?= e($lesson[7]) ?></small><?php endif; ?></span>
                                    <i class="bi bi-chevron-right profile-list-arrow" aria-hidden="true"></i>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>
            <section class="profile-panel" aria-labelledby="profile-history-title">
                <h2 class="profile-panel-title" id="profile-history-title"><i class="bi bi-clock-history" aria-hidden="true"></i> Atividades recentes</h2>
                <?php if (!$recentActivities): ?>
                    <p class="profile-empty">As aulas e atividades que você concluir aparecerão aqui.</p>
                <?php else: ?>
                    <ul class="profile-list">
                        <?php foreach ($recentActivities as $activity): ?>
                            <li class="profile-history-item">
                                <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                                <span><strong><?= e($activity['title']) ?></strong><small><?= $activity['kind'] === 'lesson' ? 'Aula concluída' : 'Atividade concluída' ?></small></span>
                                <time datetime="<?= e(str_replace(' ', 'T', $activity['completed_at'])) ?>"><?= e(date('d/m/Y', strtotime($activity['completed_at']))) ?></time>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>
        </div>

        <section class="profile-settings" id="configuracoes" aria-labelledby="profile-settings-title">
            <h2 class="h3 fw-bold mb-3" id="profile-settings-title">Configurações</h2>
            <div class="profile-settings-list">
                    <?php if ($user !== null): ?>
                    <details class="profile-panel profile-settings-item" name="profile-settings"<?= $settingsSection === 'dados' ? ' open' : '' ?>>
                        <summary>
                            <i class="bi bi-person" aria-hidden="true"></i>
                            <span><strong>Dados pessoais</strong><small>Nome, e-mail, telefone e endereço</small></span>
                        </summary>
                        <div class="profile-settings-body" id="dados">
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
                    </details>

                    <details class="profile-panel profile-settings-item" name="profile-settings"<?= $settingsSection === 'senha' ? ' open' : '' ?>>
                        <summary>
                            <i class="bi bi-key" aria-hidden="true"></i>
                            <span><strong>Senha</strong><small>Altere a senha de acesso</small></span>
                        </summary>
                        <div class="profile-settings-body" id="senha">
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
                    </details>

                    <?php endif; ?>

                    <details class="profile-panel profile-settings-item" name="profile-settings"<?= $settingsSection === 'prefs' ? ' open' : '' ?>>
                        <summary>
                            <i class="bi bi-universal-access-circle" aria-hidden="true"></i>
                            <span><strong>Acessibilidade</strong><small>Tamanho da letra, tema e contraste</small></span>
                        </summary>
                    <form class="profile-settings-body" method="post" action="<?= e(url('pages/updatePreferences')) ?>" id="prefs">
                        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

                        <?php if ($ok === 'prefs'): ?>
                            <div class="alert alert-success" role="status">Preferências salvas! Elas valem para todas as páginas.</div>
                        <?php endif; ?>
                        <?php if (isset($ePrefs['prefs'])): ?>
                            <div class="alert alert-danger" role="alert"><?= e($ePrefs['prefs']) ?></div>
                        <?php endif; ?>

                        <div class="mb-4" id="fonte">
                                <h3 class="h5 fw-bolder mb-2">Tamanho da letra</h3>
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

                        <div class="mb-4" id="tema">
                                <h3 class="h5 fw-bolder mb-2">Contraste e tema</h3>
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

                        <div class="d-grid gap-3">
                            <button class="btn btn-primary btn-lg" type="submit" name="acao" value="salvar">Salvar preferências</button>
                            <button class="btn btn-outline-primary" type="submit" name="acao" value="restaurar"><i class="bi bi-arrow-counterclockwise me-1" aria-hidden="true"></i>Restaurar padrão</button>
                        </div>
                    </form>
                    </details>
                    <?php if ($user): ?>
                        <details class="profile-panel profile-settings-item profile-settings-delete" name="profile-settings">
                            <summary>
                                <i class="bi bi-trash" aria-hidden="true"></i>
                                <span><strong>Excluir minha conta</strong></span>
                            </summary>
                            <div class="profile-settings-body">
                                    <p>Ao confirmar, você sairá do EDA e perderá o acesso à conta. Seu histórico será guardado e somente um administrador poderá restaurar o acesso.</p>
                                    <form method="post" action="<?= e(url('pages/deleteAccount')) ?>">
                                        <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                        <button class="btn btn-danger" type="submit" name="confirm_delete" value="1">Confirmar exclusão da minha conta</button>
                                    </form>
                            </div>
                        </details>
                    <?php endif; ?>
            </div>
        </section>
    </div>
</section>
