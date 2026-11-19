<?php
$accountIcon = 'box-arrow-in-right';
$heading = 'Entre na sua conta';
$intro = 'Acesse sua conta para continuar aprendendo com o EDA.';
$submitLabel = 'Entrar';
$unavailable = 'O acesso à conta ainda não está disponível.';
$footerPrompt = 'Ainda não tem uma conta?';
$footerLink = 'Cadastrar';
$footerRoute = 'contact';
$fields = [
    ['email', 'E-mail', 'email', 'username', 'envelope', 'exemplo@email.com', true],
    ['senha', 'Senha', 'password', 'current-password', 'lock', 'Digite sua senha', true],
];

require __DIR__ . '/account.php';
