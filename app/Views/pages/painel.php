<?php
$modules = [
    ['celular', 'phone', 'Uso do celular', 'Aprenda as funções básicas do aparelho.',
        'Abra as configurações do celular e procure a seção Tela. Experimente mudar o brilho ou o tamanho do texto até encontrar uma leitura confortável.'],
    ['internet', 'globe', 'Internet e navegação', 'Descubra como acessar sites e pesquisar com segurança.',
        'Abra o navegador e pesquise pelo nome da biblioteca da sua cidade. Compare os endereços dos resultados antes de abrir o site oficial.'],
    ['seguranca', 'shield-check', 'Segurança digital', 'Proteja seus dados e evite golpes virtuais.',
        'Se receber uma mensagem urgente pedindo dinheiro ou um código, confirme o pedido por um canal que você já conhece antes de responder.'],
    ['pratica', 'puzzle', 'Prática e desafios', 'Atividades para fixar o que você aprendeu.',
        'Aumente o tamanho do texto no celular, pesquise o horário de atendimento de um serviço público e salve o endereço oficial nos favoritos.'],
];
?>

<div class="learning-panel">
    <div class="container px-3 px-md-4">
        <header class="learning-intro">
            <p>Meu painel</p>
            <h1>Olá! Vamos aprender?</h1>
            <p>Continue sua jornada digital com o EDA.</p>
        </header>

        <section class="learning-progress" aria-labelledby="learning-progress-title">
            <div class="learning-progress-content">
                <h2 id="learning-progress-title">Seu progresso</h2>
                <p>Exemplo de progresso com <strong>3 de 10</strong> atividades concluídas.</p>
                <div class="learning-meter">
                    <progress value="3" max="10" aria-label="Exemplo de progresso em atividades">30%</progress>
                    <span>30% concluído</span>
                </div>
                <a class="btn btn-light fw-bold" href="#modulos">
                    <i class="bi bi-play" aria-hidden="true"></i> Continuar aprendendo
                </a>
            </div>
            <img src="<?= e(str_replace(' ', '%20', asset('assets/EDA PAGINA INICIAL.png'))) ?>"
                width="6123" height="4082" alt="">
        </section>

        <section class="learning-modules" aria-labelledby="modulos">
            <h2 id="modulos">Módulos disponíveis</h2>
            <p>Escolha um tema e comece a aprender.</p>
            <div class="learning-grid">
                <?php foreach ($modules as [$id, $icon, $name, $description]): ?>
                    <article class="learning-card">
                        <div class="learning-card-heading">
                            <span class="learning-icon"><i class="bi bi-<?= e($icon) ?>" aria-hidden="true"></i></span>
                            <div>
                                <h3><?= e($name) ?></h3>
                                <p><?= e($description) ?></p>
                            </div>
                        </div>
                        <a href="#modulo-<?= e($id) ?>" aria-label="Acessar módulo <?= e($name) ?>">
                            Acessar módulo <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>

        <aside class="learning-support">
            <i class="bi bi-heart" aria-hidden="true"></i>
            <div>
                <strong>Aprenda no seu ritmo.</strong>
                <p>Estamos aqui para ajudar você em cada etapa.</p>
            </div>
        </aside>

        <section class="learning-activities" aria-labelledby="learning-activities-title">
            <h2 id="learning-activities-title">Primeiras atividades</h2>
            <?php foreach ($modules as [$id, , $name, , $activity]): ?>
                <article class="learning-activity" id="modulo-<?= e($id) ?>" tabindex="-1">
                    <h3><?= e($name) ?></h3>
                    <p><?= e($activity) ?></p>
                </article>
            <?php endforeach; ?>
        </section>
    </div>
</div>
