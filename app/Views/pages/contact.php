<?php
$accountIcon = 'person-plus';
$heading = 'Crie sua conta';
$intro = 'Faça seu cadastro e comece sua jornada pelo mundo digital.';
$submitLabel = 'Criar minha conta';
$unavailable = 'A criação de contas ainda não está disponível.';
$footerPrompt = 'Já possui uma conta?';
$footerLink = 'Entrar';
$footerRoute = 'login';
$fields = [
    ['nome', 'Nome completo', 'text', 'name', 'person', 'Digite seu nome completo', true],
    ['email', 'E-mail', 'email', 'email', 'envelope', 'exemplo@email.com', false],
    ['cpf', 'CPF', 'text', 'off', 'person-badge', '000.000.000-00', false],
    ['endereco', 'Endereço', 'text', 'street-address', 'geo-alt', 'Digite seu endereço completo', true],
    ['senha', 'Senha', 'password', 'new-password', 'lock', 'Crie uma senha', false],
    ['confirmacao', 'Confirmar senha', 'password', 'new-password', 'lock', 'Repita sua senha', false],
];

require __DIR__ . '/account.php';
