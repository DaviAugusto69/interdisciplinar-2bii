<?php
$accountIcon = 'key';
$heading = 'Recupere sua senha';
$intro = 'Informe o e-mail cadastrado para solicitar a redefinição da senha.';
$submitLabel = 'Enviar link de recuperação';
$unavailable = 'O envio do link de recuperação ainda não está disponível.';
$footerPrompt = 'Lembrou sua senha?';
$footerLink = 'Entrar';
$footerRoute = 'login';
$fields = [
    ['email', 'E-mail', 'email', 'email', 'envelope', 'exemplo@email.com', true],
];

require __DIR__ . '/account.php';
