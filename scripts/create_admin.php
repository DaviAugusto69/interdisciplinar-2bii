
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

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("\nNome ou e-mail inválido.\n");
}

if (strlen($password) < 10) {
    exit("\nA senha precisa ter pelo menos 10 caracteres.\n");
}

if (\App\Models\Admin::findByEmail($email)) {
    exit("\nEste administrador já está cadastrado.\n");
}

\App\Models\Admin::create($name, $email, $password);

echo "\nAdministrador cadastrado com sucesso!\n";
