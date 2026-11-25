# EDA — Elderly Digital Accessibility

Cada integrante executa o EDA e usa o próprio banco local `eda_database` no MySQL ou MariaDB. A área do aluno e a administração usam esse mesmo banco local. Contas, aulas e progresso ficam no computador em que foram gravados. A equipe aplica manualmente as alterações que quiser reproduzir nas outras cópias.

## Executar com o banco local existente

1. Inicie o Apache e o MySQL no seu XAMPP ou MAMP. O EDA requer PHP 8.0 ou superior com `pdo_mysql`, `mbstring` e `iconv`. No Apache, habilite `mod_rewrite` e as regras `.htaccess`.
2. Abra o phpMyAdmin local e confira se o banco existente se chama `eda_database`.
3. Copie [config/database.example.php](config/database.example.php) para `config/database.local.php`. Use `127.0.0.1` em `host`, mantenha `eda_database` em `name` e preencha `port`, `user` e `pass` com os dados do MySQL do seu computador. A porta padrão é `3306`. Use senha vazia somente se o seu usuário MySQL estiver configurado assim. O arquivo local fica fora do Git.
4. Abra `http://localhost/interdisciplinar-2bii-1/pages/contact` no seu navegador, ajustando o nome da pasta na URL se necessário. Use `/pages/login` para alunos e `/admin/login` para administradores. Se precisar criar uma conta administrativa no seu banco, execute `php scripts/create_admin.php` na raiz do projeto, usando o PHP local.

As variáveis `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD` e `DB_SSL_CA` têm precedência sobre o arquivo local se estiverem definidas no processo PHP. Se configurou uma conexão compartilhada antes, remova essas variáveis antigas ou atualize-as para apontar para o seu próprio MySQL.

Também é possível executar o PHP CLI na raiz do projeto e abrir `http://127.0.0.1:8000`:

```bash
php -S 127.0.0.1:8000 -t public public/router.php
```

## Atualizar o banco administrativo existente

1. Se o banco local já tem as tabelas `activities` e `categories` e os campos `users.password_hash` e `lessons.is_published`, confira se a migração já foi aplicada.
2. Se o banco ainda corresponde à exportação administrativa original, com as tabelas-base `users`, `admins`, `lessons`, `user_progress` e `access_logs`, faça um backup e selecione o `eda_database` existente no phpMyAdmin local.
3. Importe [database/upgrade-eda.sql](database/upgrade-eda.sql) no banco selecionado. Execute uma vez. O script espera a estrutura da exportação fornecida. Para instalações com alterações locais prévias, compare o esquema antes de aplicar para evitar colisões com colunas ou tabelas já criadas. Operações DDL no MySQL têm commits próprios. Se uma etapa falhar, identifique a última etapa concluída antes de retomar.
4. Confirme que os registros preexistentes continuam presentes. A migração preserva contas, conteúdos e progresso, mantém as aulas anteriores publicadas e permite que novos cadastros omitam a data de nascimento. Um usuário legado sem `password_hash` precisa de habilitação de credencial por um canal de suporte que verifique sua identidade.
5. Crie um administrador, se necessário, executando `php scripts/create_admin.php` após configurar a conexão. Os administradores existentes continuam na tabela `admins`.

Os scripts anteriores em `database/` pertencem ao banco inicial de demonstração. Para a estrutura exportada, use a migração acima.

### Estrutura adicionada

- `users` recebe endereço, telefone, hash de senha, preferências de fonte e tema e data de arquivamento.
- `lessons` recebe categoria, publicação, data de publicação e arquivamento. O progresso reaproveita `user_progress`, incluindo sua chave única por aluno e aula.
- `categories`, `activities`, `activity_results`, `rewards` e `user_rewards` conectam aulas, atividades, desempenho e recompensas. As novas relações têm chaves estrangeiras. Os vínculos históricos de `user_progress` e `access_logs` continuam no formato da exportação.
- `support_messages` reaproveita a definição existente no script de perfil para registrar mensagens de ajuda.
- Arquivar um conteúdo ou uma conta retira o item das listas ativas e preserva o histórico.

## Operação e validação

- Em `/admin/learning`, cadastre categorias, defina atividades e configure recompensas por pontos. Marque a aula como publicada para que os alunos tenham acesso a ela. Atividades com resposta esperada são corrigidas no servidor. Use alternativas para criar quizzes e simulações de cenário que pedem ao aluno uma decisão. Atividades sem resposta esperada permitem confirmar uma prática.
- A administração pode acompanhar aulas, atividades concluídas, pontos e acessos por aluno. O painel do estudante mostra o progresso e as recompensas da própria conta.
- Faça backup local antes de aplicar alterações no banco. Verifique cadastro, login e administração em cada instalação.
