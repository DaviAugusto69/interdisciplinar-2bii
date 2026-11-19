<?php
/**
 * Variáveis enviadas pelo PagesController::help() (via extract no render).
 * @var string      $csrf
 * @var array       $passos
 * @var int         $passo
 * @var array       $assuntos
 * @var string|null $ok
 * @var array       $errors
 * @var array       $old
 */
$pAtual = $passos[$passo - 1];
$total  = count($passos);
$pct    = (int) round($passo / $total * 100);
?>
<section class="py-5 eda-page">
    <div class="container px-5">
        <!-- Ajuda e suporte-->
        <div class="bg-light rounded-4 py-5 px-4 px-md-5">
            <div class="text-center mb-5">
                <div class="feature bg-primary bg-gradient-primary-to-secondary text-white rounded-3 mb-3"><i class="bi bi-question-circle" aria-hidden="true"></i></div>
                <h1 class="fw-bolder">Ajuda e suporte</h1>
                <p class="lead fw-normal text-muted mb-0">Tire suas dúvidas, siga o passo a passo ou fale com a gente.</p>
            </div>

            <!-- Atalhos para as seções da página-->
            <nav class="d-flex flex-wrap justify-content-center gap-2 mb-5" aria-label="Seções desta página">
                <a class="btn btn-outline-primary" href="#faq"><i class="bi bi-chat-left-text me-1" aria-hidden="true"></i>Perguntas frequentes</a>
                <a class="btn btn-outline-primary" href="#tutorial"><i class="bi bi-signpost me-1" aria-hidden="true"></i>Tutorial guiado</a>
                <a class="btn btn-outline-primary" href="#suporte"><i class="bi bi-envelope me-1" aria-hidden="true"></i>Falar com o suporte</a>
            </nav>

            <div class="row gx-5 justify-content-center">
                <div class="col-lg-10 col-xl-8">

                    <!-- 1. Perguntas frequentes (FAQ) - acordeão do Bootstrap-->
                    <div class="card shadow border-0 rounded-4 mb-4" id="faq">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="h4 fw-bolder text-primary mb-4">Perguntas frequentes</h2>
                            <div class="accordion" id="listaFaq">
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="faqTitulo1">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faqResp1" aria-expanded="true" aria-controls="faqResp1">Como aumento o tamanho das letras?</button>
                                    </h3>
                                    <div id="faqResp1" class="accordion-collapse collapse show" aria-labelledby="faqTitulo1" data-bs-parent="#listaFaq">
                                        <div class="accordion-body">Abra a página <a href="<?= e(url('pages/profile')) ?>#fonte">Perfil e configurações</a> e escolha <strong>Grande</strong> ou <strong>Muito grande</strong> em &ldquo;Tamanho da letra&rdquo;. A mudança vale para todo o site e fica salva.</div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="faqTitulo2">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqResp2" aria-expanded="false" aria-controls="faqResp2">Como deixo a tela mais fácil de enxergar?</button>
                                    </h3>
                                    <div id="faqResp2" class="accordion-collapse collapse" aria-labelledby="faqTitulo2" data-bs-parent="#listaFaq">
                                        <div class="accordion-body">Em <a href="<?= e(url('pages/profile')) ?>#tema">Contraste e tema</a> você pode escolher o tema <strong>Escuro</strong>, bom para a noite, ou o <strong>Alto contraste</strong>, com preto, branco e amarelo.</div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="faqTitulo3">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqResp3" aria-expanded="false" aria-controls="faqResp3">Esqueci minha senha. O que eu faço?</button>
                                    </h3>
                                    <div id="faqResp3" class="accordion-collapse collapse" aria-labelledby="faqTitulo3" data-bs-parent="#listaFaq">
                                        <div class="accordion-body">Se você ainda lembra da senha atual, troque em <a href="<?= e(url('pages/profile')) ?>#senha">Alterar senha</a>. Se não lembra, use o formulário de <a href="#suporte">suporte</a> logo abaixo e conte o que aconteceu. Nós ajudamos você a entrar de novo.</div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="faqTitulo4">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqResp4" aria-expanded="false" aria-controls="faqResp4">Como sei se uma mensagem é golpe?</button>
                                    </h3>
                                    <div id="faqResp4" class="accordion-collapse collapse" aria-labelledby="faqTitulo4" data-bs-parent="#listaFaq">
                                        <div class="accordion-body">Desconfie de mensagens com pressa, ameaça ou promessa fácil demais. Seu banco nunca pede senha por mensagem. Na dúvida, não clique no link e ligue para o número que está no seu cartão.</div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="faqTitulo5">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqResp5" aria-expanded="false" aria-controls="faqResp5">Posso estudar no meu ritmo?</button>
                                    </h3>
                                    <div id="faqResp5" class="accordion-collapse collapse" aria-labelledby="faqTitulo5" data-bs-parent="#listaFaq">
                                        <div class="accordion-body">Sim. Não há pressa nem prazo. Você pode parar, voltar e repetir a aula quantas vezes quiser.</div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h3 class="accordion-header" id="faqTitulo6">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqResp6" aria-expanded="false" aria-controls="faqResp6">Não achei minha dúvida aqui. E agora?</button>
                                    </h3>
                                    <div id="faqResp6" class="accordion-collapse collapse" aria-labelledby="faqTitulo6" data-bs-parent="#listaFaq">
                                        <div class="accordion-body">Escreva para o <a href="#suporte">suporte</a>. Quanto mais você contar, mais fácil fica de ajudar.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Tutorial guiado - o passo vem da URL (?passo=2), sem JavaScript-->
                    <div class="card shadow border-0 rounded-4 mb-4" id="tutorial">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="h4 fw-bolder text-primary mb-2">Tutorial guiado</h2>
                            <p class="text-muted mb-4">Quatro passos rápidos para começar. Avance no seu tempo.</p>

                            <p class="fw-bolder mb-2">Passo <?= e($passo) ?> de <?= e($total) ?></p>
                            <div class="progress mb-4" role="progressbar" aria-label="Progresso do tutorial" aria-valuenow="<?= e($pct) ?>" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar" style="width: <?= e($pct) ?>%"></div>
                            </div>

                            <div class="text-center bg-light rounded-4 p-4 mb-4">
                                <div class="eda-passo-icone feature bg-primary bg-gradient-primary-to-secondary text-white rounded-3 mb-3"><i class="bi <?= e($pAtual['icone']) ?>" aria-hidden="true"></i></div>
                                <h3 class="h5 fw-bolder"><?= e($pAtual['titulo']) ?></h3>
                                <p class="mb-0"><?= e($pAtual['texto']) ?></p>
                            </div>

                            <div class="d-flex justify-content-between gap-3">
                                <?php if ($passo > 1): ?>
                                    <a class="btn btn-outline-primary btn-lg" href="<?= e(url('pages/help') . '?passo=' . ($passo - 1)) ?>#tutorial"><i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Anterior</a>
                                <?php else: ?>
                                    <span class="btn btn-outline-primary btn-lg disabled" aria-disabled="true"><i class="bi bi-arrow-left me-1" aria-hidden="true"></i>Anterior</span>
                                <?php endif; ?>
                                <?php if ($passo < $total): ?>
                                    <a class="btn btn-primary btn-lg" href="<?= e(url('pages/help') . '?passo=' . ($passo + 1)) ?>#tutorial">Próximo<i class="bi bi-arrow-right ms-1" aria-hidden="true"></i></a>
                                <?php else: ?>
                                    <a class="btn btn-primary btn-lg" href="<?= e(url('pages/help') . '?passo=1') ?>#tutorial">Recomeçar<i class="bi bi-arrow-counterclockwise ms-1" aria-hidden="true"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Contato com o suporte-->
                    <div class="card shadow border-0 rounded-4" id="suporte">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="h4 fw-bolder text-primary mb-2">Falar com o suporte</h2>
                            <p class="text-muted mb-4">Conte o que está acontecendo e nós respondemos por e-mail.</p>
                            <form method="post" action="<?= e(url('pages/sendSupport')) ?>" novalidate>
                                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                                <!-- Name input-->
                                <div class="form-floating mb-3">
                                    <input class="form-control<?= is_invalid($errors, 'name') ?>" id="name" name="name" type="text" placeholder="Digite seu nome..." autocomplete="name" value="<?= e($old['name'] ?? '') ?>" />
                                    <label for="name">Nome completo</label>
                                    <?= feedback($errors, 'name') ?>
                                </div>
                                <!-- Email address input-->
                                <div class="form-floating mb-3">
                                    <input class="form-control<?= is_invalid($errors, 'email') ?>" id="email" name="email" type="email" placeholder="nome@exemplo.com" autocomplete="email" value="<?= e($old['email'] ?? '') ?>" />
                                    <label for="email">E-mail</label>
                                    <?= feedback($errors, 'email') ?>
                                </div>
                                <!-- Subject select-->
                                <div class="form-floating mb-3">
                                    <select class="form-select<?= is_invalid($errors, 'subject') ?>" id="subject" name="subject">
                                        <option value="">Escolha uma opção</option>
                                        <?php foreach ($assuntos as $chave => $rotulo): ?>
                                            <option value="<?= e($chave) ?>" <?= ($old['subject'] ?? '') === $chave ? ' selected' : '' ?>><?= e($rotulo) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="subject">Assunto</label>
                                    <?= feedback($errors, 'subject') ?>
                                </div>
                                <!-- Message input-->
                                <div class="form-floating mb-3">
                                    <textarea class="form-control<?= is_invalid($errors, 'message') ?>" id="message" name="message" placeholder="Escreva sua mensagem aqui..." style="height: 10rem"><?= e($old['message'] ?? '') ?></textarea>
                                    <label for="message">Mensagem</label>
                                    <?= feedback($errors, 'message') ?>
                                </div>
                                <!-- Submit success message-->
                                <?php if ($ok === 'suporte'): ?>
                                    <div class="alert alert-success" role="status">
                                        <div class="fw-bolder">Mensagem enviada com sucesso!</div>
                                        Obrigado por escrever. Responderemos no e-mail que você informou.
                                    </div>
                                <?php endif; ?>
                                <!-- Submit Button-->
                                <div class="d-grid"><button class="btn btn-primary btn-lg" type="submit">Enviar mensagem</button></div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>