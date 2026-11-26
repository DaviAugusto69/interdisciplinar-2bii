<section class="py-5 bg-white">
    <div class="container px-4 px-md-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h2 class="display-6 fw-bolder mb-3">
                    <span class="text-gradient d-inline">Veja como fazer seu cadastro</span>
                </h2>
                <p class="lead fw-light text-muted mb-4">
                    Assista ao passo a passo rápido abaixo para criar sua conta e começar a usar a plataforma.
                </p>

                <div class="ratio ratio-16x9 shadow rounded-4 overflow-hidden border">
                    <video controls preload="metadata">
                        <source src="<?= e(asset('assets/video-eda-tutorial.mp4')) ?>" type="video/mp4">
                        Seu navegador não suporta a reprodução de vídeos.
                    </video>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="sobre-eda" class="eda-about-section">
    <div class="container px-4 px-md-5 eda-about-content">
        <div class="eda-intro-grid">
            <div class="eda-intro-copy">
                <p class="eda-eyebrow"><span></span> Sobre o projeto</p>
                <h2 class="eda-about-title">Tecnologia mais acessível, <span class="text-gradient">no seu ritmo.</span></h2>
                <p class="eda-intro-lead">O EDA é um espaço de aprendizagem digital pensado para tornar a tecnologia mais clara, próxima e útil no dia a dia.</p>
                <p class="text-muted">Usar um celular, conversar por mensagem, fazer uma pesquisa ou acessar um serviço on-line pode trazer dúvidas para quem está começando. Reunimos orientações e atividades para ajudar você a entender cada etapa, praticar com tranquilidade e ganhar confiança para seguir por conta própria.</p>
                <p class="text-muted mb-0">EDA significa <strong>Elderly Digital Accessibility</strong> (Acessibilidade Digital para Pessoas Idosas). O projeto valoriza uma aprendizagem acolhedora e acessível, especialmente para pessoas que querem desenvolver suas habilidades digitais sem pressa e sem complicação.</p>
            </div>
            <aside class="eda-purpose-card" aria-label="Nossa proposta">
                <div class="eda-purpose-icon"><i class="bi bi-compass" aria-hidden="true"></i></div>
                <p class="eda-card-kicker">Nossa proposta</p>
                <h3>Aprender fazendo, com segurança e autonomia.</h3>
                <p>Explicações diretas, exemplos do cotidiano e recursos de acessibilidade ajudam você a transformar cada dúvida em um próximo passo.</p>
                <div class="eda-purpose-tags"><span><i class="bi bi-check2-circle" aria-hidden="true"></i> No seu tempo</span><span><i class="bi bi-check2-circle" aria-hidden="true"></i> Para a vida real</span></div>
            </aside>
        </div>

        <div class="eda-section-heading">
            <p class="eda-eyebrow"><span></span> Um caminho de aprendizagem</p>
            <h3>O que você encontra no EDA</h3>
            <p>Conteúdos e ferramentas para aprender, praticar e avançar com confiança.</p>
        </div>
        <div class="eda-feature-grid">
            <article class="eda-feature-card">
                <span class="eda-feature-icon"><i class="bi bi-play-circle" aria-hidden="true"></i></span>
                <h4>Aulas e materiais</h4>
                <p>Vídeos, textos, áudios e imagens explicam temas digitais em etapas fáceis de acompanhar.</p>
            </article>
            <article class="eda-feature-card">
                <span class="eda-feature-icon"><i class="bi bi-phone" aria-hidden="true"></i></span>
                <h4>Prática para o cotidiano</h4>
                <p>Atividades e simulações aproximam o aprendizado de situações que você pode encontrar no celular e na internet.</p>
            </article>
            <article class="eda-feature-card">
                <span class="eda-feature-icon"><i class="bi bi-shield-check" aria-hidden="true"></i></span>
                <h4>Mais segurança on-line</h4>
                <p>Aprenda cuidados importantes para reconhecer mensagens suspeitas e proteger suas informações.</p>
            </article>
            <article class="eda-feature-card">
                <span class="eda-feature-icon"><i class="bi bi-patch-question" aria-hidden="true"></i></span>
                <h4>Atividades e dúvidas</h4>
                <p>Quizzes ajudam a revisar o que foi aprendido, e a área de ajuda reúne orientações para usar a plataforma.</p>
            </article>
            <article class="eda-feature-card">
                <span class="eda-feature-icon"><i class="bi bi-graph-up-arrow" aria-hidden="true"></i></span>
                <h4>Seu progresso</h4>
                <p>Acompanhe as atividades concluídas e retome seus estudos de onde parou.</p>
            </article>
            <article class="eda-feature-card">
                <span class="eda-feature-icon"><i class="bi bi-universal-access-circle" aria-hidden="true"></i></span>
                <h4>Acessibilidade</h4>
                <p>Ajustes de fonte, contraste e tema ajudam a deixar a experiência mais confortável.</p>
            </article>
        </div>

        <div class="eda-mission-panel">
            <div>
                <p class="eda-eyebrow"><span></span> Nosso compromisso</p>
                <h3>Conhecimento digital deve estar ao alcance de todos.</h3>
                <p class="mb-0">Queremos apoiar cada pessoa para que use a tecnologia com mais independência, faça escolhas conscientes e participe do mundo digital com confiança. Cada pessoa aprende de um jeito — por isso, você pode explorar os conteúdos e avançar passo a passo.</p>
            </div>
            <a class="btn btn-primary btn-lg" href="<?= e(url('pages/aprendizagem')) ?>">Conheça os conteúdos <i class="bi bi-arrow-right ms-2" aria-hidden="true"></i></a>
        </div>
    </div>
</section>
