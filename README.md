# EDA — Elderly Digital Accessibility

O EDA usa PHP MVC, PDO e um único banco MySQL ou MariaDB para a área do aluno e a administração. Requer PHP 8.0 ou superior com `pdo_mysql`, `mbstring` e `iconv`. No Apache, habilite `mod_rewrite` e a leitura das regras `.htaccess`.

## Atualizar o banco administrativo existente

1. Confirme que o servidor escolhido contém o banco da exportação administrativa. Na exportação fornecida, o nome é `meu_mvc` e as tabelas-base são `users`, `admins`, `lessons`, `user_progress` e `access_logs`.
2. Faça um backup e ensaie a atualização numa cópia protegida da estrutura. Se o banco compartilhado já existe, selecione-o diretamente no phpMyAdmin. Se for transferir o banco para outro servidor, importe a exportação original uma única vez no servidor escolhido e selecione o banco importado.
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

## Banco compartilhado em XAMPP, MAMP ou PHP CLI

Mantenha o servidor MySQL em uma rede privada. Para computadores fora da rede local, use a VPN aprovada pela equipe. Restrinja a porta no firewall aos computadores ou à sub-rede autorizada. O servidor deve escutar apenas nas interfaces previstas e a porta do banco deve permanecer fechada à internet.

Conceda ao usuário da aplicação apenas `SELECT`, `INSERT` e `UPDATE` no banco EDA. Execute a migração usando uma conta de manutenção com privilégios de DDL. Para conexões MySQL via TLS, use um hostname que corresponda ao certificado e distribua a CA aos integrantes. Configure a conta do servidor para exigir TLS.

Em cada computador, copie [config/database.example.php](config/database.example.php) para `config/database.local.php` e ajuste `host`, `port`, `name`, `user`, `pass` e, se usar TLS, `ssl_ca` com o caminho local da CA. O arquivo local fica fora do Git e protegido pelas regras do Apache. Outra opção é fornecer `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD` e, se aplicável, `DB_SSL_CA` no ambiente do processo PHP. As variáveis têm precedência. O PHP lê o ambiente do processo diretamente.

Para Apache no XAMPP ou MAMP, prefira apontar o DocumentRoot do virtual host para `public/`. A configuração em subpasta também usa as regras do projeto. Em desenvolvimento com PHP CLI, execute na raiz:

```bash
php -S 127.0.0.1:8000 -t public public/router.php
```

O acesso de aluno fica em `/pages/login` e o de administrador em `/admin/login`. Em ambiente compartilhado, sirva a aplicação por HTTPS e confirme que o PHP reconhece HTTPS para marcar o cookie de sessão como seguro.

## Operação e validação

- Em `/admin/learning`, cadastre categorias, defina atividades e configure recompensas por pontos. Marque a aula como publicada para que os alunos tenham acesso a ela. Atividades com resposta esperada são corrigidas no servidor. Use alternativas para criar quizzes e simulações de cenário que pedem ao aluno uma decisão. Atividades sem resposta esperada permitem confirmar uma prática.
- A administração pode acompanhar aulas, atividades concluídas, pontos e acessos por aluno. O painel do estudante mostra o progresso e as recompensas da própria conta.
- Agende no servidor backups diários com a conta apropriada, armazenamento protegido e retenção dos últimos 120 dias. Confirme o procedimento em uma restauração isolada e meça o tempo necessário para recuperar o serviço.
- Use a validação de implantação para verificar acesso pela rede privada, HTTPS, TLS do banco, restauração, tempo de resposta, disponibilidade e renderização em navegadores e dispositivos representativos.
