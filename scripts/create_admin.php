
<?php

require_once __DIR__ . '/../app/Models/Database.php';
require_once __DIR__ . '/../app/Models/Admin.php';

echo "=== CADASTRO DO ADMINISTRADOR EDA ===\n\n";

echo "Nome: ";
$name = trim(fgets(STDIN));

echo "E-mail: ";
$email = trim(fgets(STDIN));

echo "Senha (mínimo 10 caracteres): ";
$password = rtrim(fgets(STDIN), "\r\n");

if ($name === '' || mb_strlen($name) > 100
    || mb_strlen($email) > 150 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("\nNome ou e-mail inválido.\n");
}

if (strlen($password) < 10 || strlen($password) > 72 || str_contains($password, "\0")) {
    exit("\nA senha precisa ter entre 10 e 72 bytes e ser válida.\n");
}

if (\App\Models\Admin::findByEmail($email)) {
    exit("\nEste administrador já está cadastrado.\n");
}

\App\Models\Admin::create($name, $email, $password);

echo "\nAdministrador cadastrado com sucesso!\n";
