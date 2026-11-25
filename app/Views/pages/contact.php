<?php
$accountIcon = 'person-plus';
$heading = 'Crie sua conta';
$intro = 'Faça seu cadastro e comece sua jornada pelo mundo digital.';
$submitLabel = 'Criar minha conta';
$formAction = url('pages/register');
$footerPrompt = 'Já possui uma conta?';
$footerLink = 'Entrar';
$footerRoute = 'login';
$fields = [
    ['name', 'Nome completo', 'text', 'name', 'person', 'Digite seu nome completo', true],
    ['email', 'E-mail', 'email', 'email', 'envelope', 'exemplo@email.com', false],
    ['new_password', 'Senha', 'password', 'new-password', 'lock', 'Use 8 ou mais caracteres, com letras e números', false],
    ['confirm_password', 'Confirmar senha', 'password', 'new-password', 'lock', 'Repita sua senha', false],
];

require __DIR__ . '/account.php';
