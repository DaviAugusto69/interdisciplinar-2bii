<?php
// Recebe ?aula=1 (ou 2, 3, 4, 5) pela URL; padrão é 1
$aulaId = isset($_GET['aula']) ? (int)$_GET['aula'] : 1;

$materiais = [
    1 => [
        'titulo' => 'Aula 1: Introdução ao celular',
        'subtitulo' => 'Primeiros passos para ligar, desbloquear e segurar o aparelho com segurança.',
        'explicacao' => [
            '<strong>Botão Ligar/Desligar (Power):</strong> Fica geralmente na lateral direita do aparelho. Pressione e segure por 3 segundos para ligar ou desligar.',
            '<strong>Botões de Volume:</strong> Ficam logo acima ou abaixo do botão de ligar. A parte superior aumenta o som e a inferior diminui.',
            '<strong>Como segurar:</strong> Apoie o aparelho na palma de uma mão e use o dedo indicador ou polegar da outra mão para tocar na tela com leveza — não precisa apertar com força.',
            '<strong>Carregamento:</strong> Conecte o cabo na entrada inferior do celular sempre com cuidado, sem forçar o encaixe.'
        ],
        'dica' => 'Nunca deixe o celular carregando debaixo do travesseiro ou em locais muito quentes.',
        'quiz' => [
            [
                'pergunta' => '1. Como devemos tocar na tela de um celular moderno (touchscreen)?',
                'opcoes' => [
                    'Apertando com bastante força usando a unha',
                    'Com um toque leve usando a ponta do dedo',
                    'Batendo duas vezes bem rápido em qualquer lugar'
                ],
                'correta' => 1,
                'feedback' => 'Isso mesmo! As telas atuais são sensíveis ao calor e ao toque leve da pele.'
            ],
            [
                'pergunta' => '2. Para ligar um celular que está totalmente desligado, o que devemos fazer?',
                'opcoes' => [
                    'Segurar o botão lateral (Power) por cerca de 3 segundos',
                    'Apenas dar um toque rápido na tela',
                    'Aumentar o volume no máximo'
                ],
                'correta' => 0,
                'feedback' => 'Correto! Segurar o botão lateral por alguns segundos ativa a inicialização do aparelho.'
            ]
        ]
    ],
    2 => [
        'titulo' => 'Aula 2: Conhecendo a tela',
        'subtitulo' => 'Entenda os ícones, notificações, Wi-Fi e como voltar à tela inicial.',
        'explicacao' => [
            '<strong>Tela Inicial:</strong> É onde ficam os aplicativos (ícones quadrados ou redondos), o relógio e a previsão do tempo.',
            '<strong>Barra Superior (Notificações):</strong> Deslizando o dedo do topo da tela para baixo, você vê a bateria, o sinal de Wi-Fi e o ajuste de brilho.',
            '<strong>Botões de Navegação (parte inferior):</strong> O ícone de <em>Casinha (ou bolinha)</em> volta direto para o início; a <em>Setinha</em> volta uma tela anterior.'
        ],
        'dica' => 'Se você entrar em algum lugar sem querer e ficar perdido, basta tocar no botão de Início (casinha) na parte de baixo da tela.',
        'quiz' => [
            [
                'pergunta' => '1. Qual botão na parte inferior da tela leva você de volta para a tela principal?',
                'opcoes' => [
                    'O botão de volume',
                    'O ícone de Início (casinha ou círculo central)',
                    'O ícone da câmera'
                ],
                'correta' => 1,
                'feedback' => 'Perfeito! O botão Início sempre retorna à tela principal do celular.'
            ],
            [
                'pergunta' => '2. Onde podemos verificar o nível de bateria e o sinal de Wi-Fi?',
                'opcoes' => [
                    'No canto superior da tela',
                    'Na traseira do aparelho',
                    'Dentro da calculadora'
                ],
                'correta' => 0,
                'feedback' => 'Muito bem! O canto superior mostra a hora, a bateria e a conexão com a internet.'
            ]
        ]
    ],
    3 => [
        'titulo' => 'Aula 3: Fazendo uma ligação',
        'subtitulo' => 'Como usar o teclado numérico, atender chamadas e ativar o viva-voz.',
        'explicacao' => [
            '<strong>Aplicativo Telefone:</strong> Tem o ícone de um gancho de telefone (geralmente verde ou azul).',
            '<strong>Discando um número:</strong> Toque em "Teclado", digite o DDD (ex: 11) + o número e toque no botão verde para chamar.',
            '<strong>Viva-voz:</strong> Durante a ligação, toque no ícone de alto-falante para ouvir a pessoa sem precisar encostar o celular no ouvido.'
        ],
        'dica' => 'Para encerrar uma chamada, toque sempre no botão vermelho com o símbolo do telefone.',
        'quiz' => [
            [
                'pergunta' => '1. Qual recurso permite ouvir a ligação sem encostar o celular na orelha?',
                'opcoes' => [
                    'Modo Avião',
                    'Viva-voz (ícone de alto-falante)',
                    'Lanterna'
                ],
                'correta' => 1,
                'feedback' => 'Exato! O viva-voz aumenta o som para você conversar com mais conforto.'
            ]
        ]
    ],
    4 => [
        'titulo' => 'Aula 4: Salvando contatos',
        'subtitulo' => 'Guarde o telefone de familiares e médicos na agenda para não precisar decorar.',
        'explicacao' => [
            '<strong>Abrir Contatos:</strong> Entre no aplicativo "Contatos" ou na aba "Contatos" dentro do app Telefone.',
            '<strong>Criar novo:</strong> Toque no sinal de <strong>+ (Mais)</strong> ou em "Criar novo contato".',
            '<strong>Preencher dados:</strong> Digite o nome da pessoa (ex: "Filha Maria") e o número com DDD, depois toque em <strong>Salvar</strong>.'
        ],
        'dica' => 'Coloque sempre o DDD antes do número (ex: 11 99999-9999) para o contato funcionar também no WhatsApp.',
        'quiz' => [
            [
                'pergunta' => '1. Por que é importante salvar o número já com o DDD?',
                'opcoes' => [
                    'Para gastar menos bateria',
                    'Para funcionar em ligações e também aparecer no WhatsApp',
                    'Porque o celular não aceita números sem DDD'
                ],
                'correta' => 1,
                'feedback' => 'Correto! Com o DDD salvo, você consegue ligar e mandar mensagens no WhatsApp facilmente.'
            ]
        ]
    ],
    5 => [
        'titulo' => 'Aula 5: Revisão da aula',
        'subtitulo' => 'Relembre os principais pontos aprendidos neste módulo.',
        'explicacao' => [
            'Você aprendeu a ligar, desbloquear e segurar o celular com segurança.',
            'Conheceu os botões de navegação da tela e como ajustar brilho e Wi-Fi.',
            'Praticou como realizar chamadas, usar o viva-voz e salvar contatos importantes na agenda.'
        ],
        'dica' => 'Parabéns por concluir o módulo! Pratique salvando o número de um familiar hoje mesmo.',
        'quiz' => [
            [
                'pergunta' => '1. O que fazer se aparecer uma mensagem estranha pedindo sua senha bancária?',
                'opcoes' => [
                    'Responder imediatamente com seus dados',
                    'Não clicar, não responder e pedir ajuda a um familiar de confiança',
                    'Encaminhar para todos os contatos'
                ],
                'correta' => 1,
                'feedback' => 'Excelente! Bancos nunca pedem senhas por mensagem ou ligação.'
            ]
        ]
    ]
];

$atual = $materiais[$aulaId] ?? $materiais[1];
?>

<section class="py-5">
    <div class="container px-4 px-md-5" style="max-width: 900px;">
        <div class="mb-4">
            <a href="<?= e(url('pages/aprendizagem')) ?>" class="btn btn-outline-primary fw-bold">
                <i class="bi bi-arrow-left"></i> Voltar para a aula
            </a>
        </div>

        <!-- Cabeçalho do Material -->
        <div class="material-card">
            <span class="badge bg-primary mb-2">Material de Apoio</span>
            <h1 class="h2 fw-bolder mb-2"><?= e($atual['titulo']) ?></h1>
            <p class="lead text-muted mb-4"><?= e($atual['subtitulo']) ?></p>

            <h2 class="h5 fw-bold mb-3"><i class="bi bi-book-half text-primary me-2"></i>Explicação Passo a Passo</h2>
            <ul class="list-group list-group-flush mb-4">
                <?php foreach ($atual['explicacao'] as $item): ?>
                    <li class="list-group-item py-3 fs-6"><?= $item ?></li>
                <?php endforeach; ?>
            </ul>

            <div class="alert alert-info d-flex align-items-center mb-0" role="alert">
                <i class="bi bi-lightbulb-fill fs-4 me-3"></i>
                <div><strong>Dica importante:</strong> <?= e($atual['dica']) ?></div>
            </div>
        </div>

        <!-- Quiz Interativo -->
        <div class="material-card">
            <h2 class="h4 fw-bolder mb-1"><i class="bi bi-patch-question-fill text-success me-2"></i>Quiz de Fixação</h2>
            <p class="text-muted mb-4">Clique na resposta que você considera correta para testar o que aprendeu:</p>

            <?php foreach ($atual['quiz'] as $qIndex => $questao): ?>
                <div class="mb-4 pb-3 border-bottom" data-quiz-box>
                    <p class="fw-bold fs-5 mb-3"><?= e($questao['pergunta']) ?></p>
                    <?php foreach ($questao['opcoes'] as $oIndex => $opcao): ?>
                        <button
                            type="button"
                            class="quiz-option"
                            data-correta="<?= $oIndex === $questao['correta'] ? '1' : '0' ?>"
                            onclick="verificarResposta(this)">
                            <?= e($opcao) ?>
                        </button>
                    <?php endforeach; ?>
                    <div class="quiz-feedback mt-2 fw-semibold" style="display:none;">
                        <?= e($questao['feedback']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
function verificarResposta(botao) {
    const caixa = botao.closest('[data-quiz-box]');
    const opcoes = caixa.querySelectorAll('.quiz-option');
    const feedback = caixa.querySelector('.quiz-feedback');
    const acertou = botao.dataset.correta === '1';

    opcoes.forEach(btn => {
        btn.disabled = true;
        if (btn.dataset.correta === '1') {
            btn.classList.add('correta');
        }
    });

    if (!acertou) {
        botao.classList.add('errada');
        feedback.className = 'quiz-feedback mt-2 fw-semibold text-danger';
        feedback.textContent = 'Quase lá! A resposta correta está destacada em verde acima.';
    } else {
        feedback.className = 'quiz-feedback mt-2 fw-semibold text-success';
    }
    feedback.style.display = 'block';
}
</script>