USE eda_database;

INSERT INTO categories (name)
VALUES ('Segurança Digital')
ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id);

SET @security_category_id = (SELECT id FROM categories WHERE name = 'Segurança Digital' LIMIT 1);

INSERT INTO lessons (slug, title, description, type, icon, media_url, body_text, is_published, category_id, published_at)
SELECT 'seguranca-banco-mensagem', 'O banco mandou mensagem?',
       'Saiba como conferir um contato antes de responder, clicar ou compartilhar informações.',
       'video', 'bank', NULL,
       'Pare antes de responder. Uma mensagem pode copiar o nome, a foto e o logotipo de um banco verdadeiro. Isso não confirma quem enviou.\n\nNunca compartilhe sua senha, código recebido por SMS ou WhatsApp, número completo do cartão ou código de segurança com alguém que entrou em contato.\n\nNão use links nem telefones enviados na mensagem. Abra o aplicativo oficial que você já usa ou ligue para o número impresso no cartão. Se houver uma atividade suspeita, confirme por esse canal.\n\nSe a mensagem parecer falsa, não responda: bloqueie e denuncie o contato no aplicativo.',
       1, @security_category_id, CURRENT_TIMESTAMP
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM lessons WHERE slug = 'seguranca-banco-mensagem');

INSERT INTO lessons (slug, title, description, type, icon, media_url, body_text, is_published, category_id, published_at)
SELECT 'seguranca-premio-rifa', 'Você ganhou uma rifa?',
       'Reconheça falsas promessas de prêmio e saiba o que conferir antes de agir.',
       'video', 'gift', NULL,
       'Desconfie de prêmios inesperados, principalmente se você não se lembra de ter participado. Golpistas podem dizer que você ganhou uma rifa, sorteio ou benefício para apressar uma decisão.\n\nConfira o resultado diretamente no canal oficial de quem organizou o sorteio. Não confie apenas em uma mensagem, perfil ou comprovante enviado por quem diz ser o organizador.\n\nNão pague taxa, frete ou “liberação” por Pix para receber um prêmio. Não envie documentos, senhas ou códigos de confirmação para provar sua identidade.\n\nSe houver pressão para pagar ou decidir na hora, pare e converse com alguém de confiança antes de continuar.',
       1, @security_category_id, CURRENT_TIMESTAMP
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM lessons WHERE slug = 'seguranca-premio-rifa');

INSERT INTO lessons (slug, title, description, type, icon, media_url, body_text, is_published, category_id, published_at)
SELECT 'seguranca-links-sites', 'Links e sites suspeitos',
       'Aprenda a chegar a um site com mais segurança e a desconfiar de endereços estranhos.',
       'video', 'link-45deg', NULL,
       'Evite abrir links inesperados recebidos por mensagem, e-mail ou redes sociais. O endereço pode imitar o de uma loja, banco ou serviço conhecido, com letras trocadas ou palavras extras.\n\nQuando precisar acessar um serviço, abra o aplicativo oficial ou digite o endereço que você já conhece. Evite baixar arquivos ou instalar aplicativos oferecidos por páginas desconhecidas.\n\nO cadeado e o “https” indicam uma conexão protegida, mas não provam que o site pertence a uma empresa legítima. Confira o endereço completo e confirme por outro canal se tiver dúvida.\n\nFeche a página se aparecerem alertas alarmantes, pedidos inesperados de senha ou instruções para instalar programas e permitir acesso remoto.',
       1, @security_category_id, CURRENT_TIMESTAMP
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM lessons WHERE slug = 'seguranca-links-sites');

INSERT INTO lessons (slug, title, description, type, icon, media_url, body_text, is_published, category_id, published_at)
SELECT 'seguranca-senhas-codigos', 'Senhas e códigos são pessoais',
       'Proteja suas contas e entenda por que códigos de confirmação devem ficar só com você.',
       'video', 'key', NULL,
       'Use senhas diferentes para contas importantes. Uma frase longa e fácil de lembrar costuma ser melhor do que uma senha curta e previsível. Não anote senhas em lugares visíveis nem as envie por mensagem.\n\nCódigos de confirmação recebidos por SMS ou aplicativo funcionam como uma chave temporária. Não os compartilhe, mesmo que a pessoa diga ser do banco, da loja ou do suporte.\n\nAtive o bloqueio de tela do celular e mantenha o aparelho e os aplicativos atualizados. Se disponível, ative a confirmação em duas etapas nas suas contas.\n\nSe alguém pedir para instalar um aplicativo ou compartilhar a tela para “ajudar”, pare e procure o suporte por um canal oficial.',
       1, @security_category_id, CURRENT_TIMESTAMP
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM lessons WHERE slug = 'seguranca-senhas-codigos');

INSERT INTO lessons (slug, title, description, type, icon, media_url, body_text, is_published, category_id, published_at)
SELECT 'seguranca-se-voce-clicou', 'E se eu já cliquei?',
       'Saiba como agir com calma se abriu um link, informou dados ou fez um pagamento.',
       'video', 'life-preserver', NULL,
       'Feche a página e não continue a conversa. Não instale aplicativos, não informe mais dados e não compartilhe códigos. Peça ajuda a alguém de confiança se não souber qual é o próximo passo.\n\nSe você informou dados bancários, ligue imediatamente para o banco usando o aplicativo oficial ou o número do cartão. Conte o que aconteceu e peça orientação para proteger a conta e os cartões.\n\nSe digitou uma senha que usa em outros serviços, troque-a nesses serviços usando um aparelho confiável. Se fez um pagamento, avise o banco o quanto antes e guarde mensagens e comprovantes.\n\nVocê não precisa resolver tudo sozinho. Agir rápido e procurar os canais corretos ajuda a reduzir possíveis prejuízos.',
       1, @security_category_id, CURRENT_TIMESTAMP
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM lessons WHERE slug = 'seguranca-se-voce-clicou');

INSERT INTO activities (lesson_id, title, prompt, choices, correct_answer, points, is_active)
SELECT l.id, 'Confira antes de responder',
       'Uma mensagem diz ser do banco e pede o código que chegou por SMS. O que você faz?',
       CONCAT('Envio o código para evitar o bloqueio da conta.', CHAR(10),
              'Não compartilho o código e procuro o banco pelo aplicativo ou telefone oficial.', CHAR(10),
              'Clico no link da mensagem para confirmar meus dados.'),
       'Não compartilho o código e procuro o banco pelo aplicativo ou telefone oficial.', 10, 1
FROM lessons l
WHERE l.slug = 'seguranca-banco-mensagem'
  AND NOT EXISTS (SELECT 1 FROM activities a WHERE a.lesson_id = l.id AND a.title = 'Confira antes de responder');

INSERT INTO activities (lesson_id, title, prompt, choices, correct_answer, points, is_active)
SELECT l.id, 'Prêmio sem pagamento',
       'Você recebeu uma mensagem dizendo que ganhou uma rifa, mas precisa pagar uma taxa por Pix. Qual é a atitude mais segura?',
       CONCAT('Pagar logo para não perder o prêmio.', CHAR(10),
              'Enviar um código de confirmação para provar que sou o ganhador.', CHAR(10),
              'Não pagar nem enviar dados e conferir o resultado no canal oficial do organizador.'),
       'Não pagar nem enviar dados e conferir o resultado no canal oficial do organizador.', 10, 1
FROM lessons l
WHERE l.slug = 'seguranca-premio-rifa'
  AND NOT EXISTS (SELECT 1 FROM activities a WHERE a.lesson_id = l.id AND a.title = 'Prêmio sem pagamento');

INSERT INTO activities (lesson_id, title, prompt, choices, correct_answer, points, is_active)
SELECT l.id, 'Atenção ao endereço',
       'Um link de mensagem leva a uma página com cadeado, mas o endereço parece estranho. O que fazer?',
       CONCAT('Confiar, porque o cadeado garante que a empresa é verdadeira.', CHAR(10),
              'Fechar a página e acessar o serviço pelo aplicativo oficial ou endereço conhecido.', CHAR(10),
              'Informar meus dados para descobrir se a página funciona.'),
       'Fechar a página e acessar o serviço pelo aplicativo oficial ou endereço conhecido.', 10, 1
FROM lessons l
WHERE l.slug = 'seguranca-links-sites'
  AND NOT EXISTS (SELECT 1 FROM activities a WHERE a.lesson_id = l.id AND a.title = 'Atenção ao endereço');

INSERT INTO activities (lesson_id, title, prompt, choices, correct_answer, points, is_active)
SELECT l.id, 'Guarde seus códigos',
       'Uma pessoa que diz ser do suporte pede o código de confirmação enviado ao seu celular. O que fazer?',
       CONCAT('Passar o código se a pessoa souber meu nome.', CHAR(10),
              'Não compartilhar o código e procurar o suporte por um canal oficial.', CHAR(10),
              'Enviar uma foto da tela para a pessoa.'),
       'Não compartilhar o código e procurar o suporte por um canal oficial.', 10, 1
FROM lessons l
WHERE l.slug = 'seguranca-senhas-codigos'
  AND NOT EXISTS (SELECT 1 FROM activities a WHERE a.lesson_id = l.id AND a.title = 'Guarde seus códigos');

INSERT INTO activities (lesson_id, title, prompt, choices, correct_answer, points, is_active)
SELECT l.id, 'Aja com calma e rapidez',
       'Você digitou seus dados bancários em um site suspeito. Qual é um bom próximo passo?',
       CONCAT('Esperar para ver se acontece algo.', CHAR(10),
              'Responder à mensagem e pedir que apaguem os dados.', CHAR(10),
              'Contatar o banco pelo canal oficial imediatamente e seguir as orientações para proteger a conta.'),
       'Contatar o banco pelo canal oficial imediatamente e seguir as orientações para proteger a conta.', 10, 1
FROM lessons l
WHERE l.slug = 'seguranca-se-voce-clicou'
  AND NOT EXISTS (SELECT 1 FROM activities a WHERE a.lesson_id = l.id AND a.title = 'Aja com calma e rapidez');

-- Atualiza também cursos que já foram importados anteriormente.
UPDATE lessons SET type = 'video', media_url = NULL
WHERE slug = 'seguranca-banco-mensagem';
UPDATE lessons SET type = 'video', media_url = NULL
WHERE slug = 'seguranca-premio-rifa';
UPDATE lessons SET type = 'video', media_url = NULL
WHERE slug = 'seguranca-links-sites';
UPDATE lessons SET type = 'video', media_url = NULL
WHERE slug = 'seguranca-senhas-codigos';
UPDATE lessons SET type = 'video', media_url = NULL
WHERE slug = 'seguranca-se-voce-clicou';
