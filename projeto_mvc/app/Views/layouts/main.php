<?php
$pageTitle = isset($title) && is_string($title) ? $title : 'Projeto MVC';
$pageContent = isset($content) && is_string($content) ? $content : '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <link rel="stylesheet" href="<?= asset('css/estilo.css') ?>">
</head>
<body>
    <header class="topo">
        <nav>
            <a href="<?= url('user/index') ?>">Usu&aacute;rios</a>
            <a href="<?= url('user/create') ?>">Novo usu&aacute;rio</a>
        </nav>
    </header>
    <main>
        <?= $pageContent ?>
    </main>
    <script src="<?= asset('js/app.js') ?>" defer></script>
</body>
</html>
