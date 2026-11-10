<!DOCTYPE html>
<!--
  EDA - Elderly Digital Accessibility | NOVO DESIGN 01: PÁGINA INICIAL (arquivo único)
  Como usar: dê dois cliques no arquivo (abre no navegador; precisa de internet por causa do Bootstrap).
  Layout e grade: Bootstrap. Cores e acabamento: CSS próprio, com as cores escritas direto.
  Cada trecho tem um comentário: NOME | BOOTSTRAP: classes usadas | FUNÇÃO
-->
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <!-- FUNÇÃO: página responsiva (celular, tablet, computador) | RNF09 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EDA - Elderly Digital Accessibility</title>

    <!-- FUNÇÃO: fonte de alta legibilidade, Bootstrap 5.3 (CSS) e ícones -->
    <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* ===== CSS PRÓPRIO =====
           Paleta: azul #2563A6 | azul escuro #173B63 | azul claro #EAF3FC | cinza claro #F5F7FA
                   cinza escuro #263445 | verde #2A7558 | verde escuro #1F5A44 | verde claro #E5F4EE | borda #DCE5EF */

        /* 1. BASE | FUNÇÃO: fonte, cor do texto e tamanho (18px, maior que o padrão de 16px) */
        html { font-size: 112.5%; }
        html[data-fonte="grande"] { font-size: 125%; }      /* botão A+ (RF11, RNF06) */
        html[data-fonte="maior"]  { font-size: 137.5%; }
        body { font-family: 'Atkinson Hyperlegible', system-ui, 'Segoe UI', Arial, sans-serif; color: #263445; line-height: 1.65; }
        h1, h2, h3 { color: #173B63; font-weight: 700; line-height: 1.25; }
        p { max-width: 68ch; }

        /* 2. BOTÕES | FUNÇÃO: cores do EDA e área de clique ampla (RNF04) */
        .btn {
            --bs-btn-padding-y: .6rem; --bs-btn-padding-x: 1.5rem;
            --bs-btn-font-weight: 700; --bs-btn-border-radius: .75rem;
            min-height: 3.25rem;
            display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
        }
        .btn-primary {
            --bs-btn-bg: #2563A6; --bs-btn-border-color: #2563A6;
            --bs-btn-hover-bg: #173B63; --bs-btn-hover-border-color: #173B63;
            --bs-btn-active-bg: #173B63; --bs-btn-active-border-color: #173B63;
        }
        .btn-outline-primary {
            --bs-btn-color: #2563A6; --bs-btn-border-color: #2563A6;
            --bs-btn-hover-bg: #2563A6; --bs-btn-hover-border-color: #2563A6;
            --bs-btn-active-bg: #173B63; --bs-btn-active-border-color: #173B63;
        }
        .btn-outline-success {
            --bs-btn-color: #2A7558; --bs-btn-border-color: #2A7558;
            --bs-btn-hover-bg: #2A7558; --bs-btn-hover-border-color: #2A7558;
            --bs-btn-active-bg: #1F5A44; --bs-btn-active-border-color: #1F5A44;
        }
        .btn-fonte { min-width: 2.75rem; min-height: 2.75rem; padding: .25rem .75rem; }

        /* 3. NAVBAR | FUNÇÃO: fundo branco, marca e links com ícone */
        .eda-navbar { background: #fff; border-bottom: 1px solid #DCE5EF; }
        .eda-navbar .navbar-brand { color: #173B63; font-weight: 700; font-size: 1.5rem; }
        .eda-navbar .nav-link { color: #263445; font-weight: 700; padding: .75rem 1rem; border-radius: .75rem; display: flex; align-items: center; gap: .5rem; }
        .eda-navbar .nav-link:hover { background: #EAF3FC; color: #173B63; }
        .eda-navbar .nav-link.active { background: #EAF3FC; color: #2563A6; }
        .eda-logo { width: 2.5rem; height: 2.5rem; border-radius: .75rem; background: #2563A6; color: #fff; font-size: 1.25rem; display: inline-flex; align-items: center; justify-content: center; }

        /* 4. DESTAQUE (HERO) | FUNÇÃO: faixa azul-clara e título grande */
        .eda-hero { background: #EAF3FC; padding: 4rem 0; }
        .eda-hero h1 { font-size: clamp(2rem, 5vw, 3rem); }
        .eda-hero .lead { font-size: 1.25rem; }

        /* 5. CARDS | FUNÇÃO: cartão branco, borda clara, cantos arredondados e sombra discreta */
        .eda-card { background: #fff; border: 1px solid #DCE5EF; border-radius: 1rem; box-shadow: 0 2px 10px rgba(23, 59, 99, .10); color: #263445; }
        a.eda-card:hover { border-color: #2563A6; color: #263445; }
        .eda-icone { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: #EAF3FC; color: #2563A6; font-size: 1.75rem; display: inline-flex; align-items: center; justify-content: center; }
        .eda-icone-verde { background: #E5F4EE; color: #2A7558; }

        /* 6. DESAFIO DO DIA | FUNÇÃO: cartão do desafio (etiqueta verde e caixa com o exemplo) */
        .eda-desafio { border-radius: 1.25rem; }
        .eda-etiqueta { display: inline-block; background: #E5F4EE; color: #1F5A44; border-radius: 2rem; padding: .2rem .9rem; font-weight: 700; font-size: .9rem; }
        .eda-exemplo { background: #F5F7FA; border-radius: .75rem; padding: .75rem 1rem; }

        /* 7. DICA DE SEGURANÇA | FUNÇÃO: faixa verde-clara com borda verde */
        .eda-secao { padding: 3.5rem 0; }
        .eda-dica { background: #E5F4EE; border: 1px solid #2A7558; border-radius: 1rem; padding: 1.5rem; color: #263445; }
        .eda-dica h2 { color: #1F5A44; }
        .eda-dica .eda-icone { background: #fff; color: #2A7558; }

        /* 8. RODAPÉ | FUNÇÃO: fundo azul escuro e links brancos */
        .eda-rodape { background: #173B63; color: #fff; padding: 2.5rem 0; }
        .eda-rodape h2 { color: #fff; }
        .eda-rodape a { color: #fff; text-decoration: underline; }
        .eda-rodape p { max-width: none; }

        /* 9. ACESSIBILIDADE | FUNÇÃO: foco visível para quem usa teclado */
        :focus-visible { outline: 3px solid #173B63; outline-offset: 3px; }
        @media (prefers-reduced-motion: reduce) { * { transition: none !important; animation: none !important; } }
    </style>
</head>
<body>

    <!-- ===== NAVBAR | BOOTSTRAP: navbar, navbar-expand-lg, collapse, navbar-nav, nav-link | RF05
         FUNÇÃO: menu principal. Vira "hambúrguer" no celular. ===== -->
    <header>
        <nav class="navbar navbar-expand-lg eda-navbar" aria-label="Menu principal">
            <div class="container">

                <!-- Marca (logo + nome) -->
                <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                    <span class="eda-logo" aria-hidden="true"><i class="bi bi-mortarboard"></i></span>
                    EDA
                </a>

                <!-- Botão hambúrguer (só aparece em tela pequena) -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuEda"
                        aria-controls="menuEda" aria-expanded="false" aria-label="Abrir menu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="menuEda">

                    <!-- LINKS DO MENU | "Início" é a página atual (active + aria-current).
                         Os href="#" viram rotas reais (/aprendizagem, /atividades...) quando existirem os Controllers. -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3">
                        <li class="nav-item"><a class="nav-link active" href="#" aria-current="page"><i class="bi bi-house-door" aria-hidden="true"></i>Início</a></li>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-book" aria-hidden="true"></i>Aprender</a></li>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-pencil-square" aria-hidden="true"></i>Atividades</a></li>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-shield-check" aria-hidden="true"></i>Segurança</a></li>
                        <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-question-circle" aria-hidden="true"></i>Ajuda</a></li>
                    </ul>

                    <div class="d-flex flex-wrap align-items-center gap-2">

                        <!-- TAMANHO DO TEXTO | BOOTSTRAP: btn-group | RF11 e RNF06 (lógica no <script> no final) -->
                        <div class="btn-group" role="group" aria-label="Tamanho do texto">
                            <button type="button" id="fonte-menos" class="btn btn-outline-primary btn-fonte" aria-label="Diminuir texto">A-</button>
                            <button type="button" id="fonte-mais" class="btn btn-outline-primary btn-fonte" aria-label="Aumentar texto">A+</button>
                        </div>

                        <!-- ENTRAR E CADASTRAR | BOOTSTRAP: btn | RF01 e RNF02 -->
                        <a class="btn btn-outline-primary" href="#">Entrar</a>
                        <a class="btn btn-primary" href="#">Criar conta</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main>

        <!-- ===== 1. DESTAQUE (HERO) | BOOTSTRAP: container, row, col-lg-*, align-items-center, lead, btn-lg
             FUNÇÃO: explicar o EDA em poucos segundos, levar ao cadastro e já oferecer o desafio do dia. ===== -->
        <section class="eda-hero">
            <div class="container">
                <div class="row align-items-center g-5">

                    <!-- Texto de boas-vindas e botões -->
                    <div class="col-lg-7">
                        <h1>Aprenda a usar a tecnologia, sem pressa</h1>
                        <p class="lead mt-3">Aulas curtas, desafios diários e dicas para você se proteger de golpes na internet.</p>
                        <div class="d-flex flex-wrap gap-3 mt-4">
                            <a class="btn btn-primary btn-lg" href="#">Começar a aprender</a>
                            <a class="btn btn-outline-primary btn-lg" href="#">Ver as aulas</a>
                        </div>
                    </div>

                    <!-- DESAFIO DO DIA | BOOTSTRAP: card, card-body, d-flex, flex-fill -->
                    <div class="col-lg-5">
                        <div class="card eda-card eda-desafio">
                            <div class="card-body p-4">
                                <span class="eda-etiqueta">Desafio do dia</span>
                                <h2 class="h5 mt-3">Esta mensagem é golpe ou é segura?</h2>
                                <p class="eda-exemplo my-3">“Seu banco pede sua senha.”</p>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary flex-fill">Golpe</button>
                                    <button type="button" class="btn btn-outline-primary flex-fill">Segura</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ===== 2. ÁREAS DO SITE | BOOTSTRAP: grid (row, col-12, col-md-6, col-lg-3), card, card-body, h-100 | RF05
             FUNÇÃO: atalhos para Aprender, Atividades, Segurança e Ajuda. O card inteiro é um link (clique grande). ===== -->
        <section class="eda-secao" aria-labelledby="titulo-areas">
            <div class="container">
                <h2 id="titulo-areas" class="mb-4">O que você encontra aqui</h2>
                <div class="row g-4">

                    <div class="col-12 col-md-6 col-lg-3">
                        <a class="card eda-card h-100 text-decoration-none" href="#">
                            <div class="card-body p-4 d-flex flex-column gap-3">
                                <span class="eda-icone" aria-hidden="true"><i class="bi bi-book"></i></span>
                                <h3 class="h5 mb-0">Aprender</h3>
                                <p class="mb-0">Vídeos, áudios e texto no seu ritmo.</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <a class="card eda-card h-100 text-decoration-none" href="#">
                            <div class="card-body p-4 d-flex flex-column gap-3">
                                <span class="eda-icone" aria-hidden="true"><i class="bi bi-pencil-square"></i></span>
                                <h3 class="h5 mb-0">Atividades</h3>
                                <p class="mb-0">Quizzes e simulações para praticar.</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <a class="card eda-card h-100 text-decoration-none" href="#">
                            <div class="card-body p-4 d-flex flex-column gap-3">
                                <span class="eda-icone eda-icone-verde" aria-hidden="true"><i class="bi bi-shield-check"></i></span>
                                <h3 class="h5 mb-0">Segurança</h3>
                                <p class="mb-0">Reconheça golpes e proteja suas senhas.</p>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <a class="card eda-card h-100 text-decoration-none" href="#">
                            <div class="card-body p-4 d-flex flex-column gap-3">
                                <span class="eda-icone" aria-hidden="true"><i class="bi bi-question-circle"></i></span>
                                <h3 class="h5 mb-0">Ajuda</h3>
                                <p class="mb-0">Perguntas frequentes e suporte.</p>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </section>

        <!-- ===== 3. DICA DE SEGURANÇA | BOOTSTRAP: container, d-flex, flex-wrap, align-items-center, gap-3
             FUNÇÃO: reforçar o tema de golpes e phishing (Segurança Digital). ===== -->
        <section class="pb-5">
            <div class="container">
                <div class="eda-dica d-flex flex-wrap align-items-center gap-3" role="note">
                    <span class="eda-icone" aria-hidden="true"><i class="bi bi-shield-check"></i></span>
                    <div class="flex-grow-1">
                        <h2 class="h5 mb-1">Dica de segurança</h2>
                        <p class="mb-0">Seu banco nunca pede senha por mensagem. Se pedir, é golpe.</p>
                    </div>
                    <a class="btn btn-outline-success" href="#">Mais dicas</a>
                </div>
            </div>
        </section>

    </main>

    <!-- ===== RODAPÉ | BOOTSTRAP: container, row, col-md-6, col-6 col-md-3 | RF11 e RF13
         FUNÇÃO: fecha a página; leva para perguntas frequentes, suporte e acessibilidade. ===== -->
    <footer class="eda-rodape">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <h2 class="h4">EDA</h2>
                    <p class="mb-0">Aprender com calma, segurança e confiança.</p>
                </div>
                <div class="col-6 col-md-3">
                    <h2 class="h6">Ajuda</h2>
                    <p class="mb-1"><a href="#">Perguntas frequentes</a></p>
                    <p class="mb-0"><a href="#">Fale com o suporte</a></p>
                </div>
                <div class="col-6 col-md-3">
                    <h2 class="h6">Acessibilidade</h2>
                    <p class="mb-1"><a href="#">Configurações</a></p>
                    <p class="mb-0"><a href="#">Contraste e tema</a></p>
                </div>
            </div>
            <hr class="border-light opacity-25">
            <p class="mb-0 small">Projeto Interdisciplinar, IF Baiano Campus Guanambi, 2026.</p>
        </div>
    </footer>

    <!-- FUNÇÃO: JavaScript do Bootstrap (necessário para o menu hambúrguer) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /* ===== JAVASCRIPT PRÓPRIO: TAMANHO DA FONTE | FUNÇÃO: botões A- e A+ (RF11, RNF06).
           A escolha fica salva no navegador (localStorage). Salvar no banco, por usuário, é etapa futura. ===== */
        (function () {
            var NIVEIS = ['normal', 'grande', 'maior'];
            var html = document.documentElement;
            function ler() { try { return localStorage.getItem('eda_fonte') || 'normal'; } catch (e) { return 'normal'; } }
            function aplicar(n) {
                if (NIVEIS.indexOf(n) === -1) { n = 'normal'; }
                html.setAttribute('data-fonte', n);
                try { localStorage.setItem('eda_fonte', n); } catch (e) { /* ignora */ }
            }
            aplicar(ler());
            document.getElementById('fonte-menos').addEventListener('click', function () {
                aplicar(NIVEIS[Math.max(0, NIVEIS.indexOf(ler()) - 1)]);
            });
            document.getElementById('fonte-mais').addEventListener('click', function () {
                aplicar(NIVEIS[Math.min(NIVEIS.length - 1, NIVEIS.indexOf(ler()) + 1)]);
            });
        })();
    </script>
</body>
</html>