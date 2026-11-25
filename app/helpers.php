<?php
// e(): escapa texto para HTML (previne XSS)
function e(string|int|float|null $value): string
{
    return htmlspecialchars((string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// url(): monta um link interno respeitando o BASE_URL
function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

// asset(): monta o link de um arquivo estático e acrescenta a versão
function asset(string $path): string
{
    $file    = __DIR__ . '/../public/' . ltrim($path, '/');
    $version = is_file($file) ? filemtime($file) : false;
    return url($path) . ($version === false ? '' : '?v=' . $version);
}

// prefs_validas(): opções aceitas nas preferências (lista branca)
function prefs_validas(): array
{
    return [
        'fonte' => ['normal', 'grande', 'maior'],
        'tema'  => ['claro', 'escuro', 'contraste'],
    ];
}

// prefs(): tamanho da letra e tema escolhidos (cookies), sempre com valor válido
function prefs(): array
{
    if (isset($_SESSION['eda_preferences'])) {
        return $_SESSION['eda_preferences'];
    }

    $validas = prefs_validas();
    $fonte = $_COOKIE['eda_fonte'] ?? '';
    $tema  = $_COOKIE['eda_tema'] ?? '';
    return [
        'fonte' => in_array($fonte, $validas['fonte'], true) ? $fonte : 'normal',
        'tema'  => in_array($tema, $validas['tema'], true) ? $tema : 'claro',
    ];
}

// is_invalid(): acrescenta a classe is-invalid do Bootstrap quando o campo tem erro
function is_invalid(array $errors, string $field): string
{
    return isset($errors[$field]) ? ' is-invalid' : '';
}

// feedback(): mensagem de erro (invalid-feedback do Bootstrap) abaixo do campo
function feedback(array $errors, string $field): string
{
    if (!isset($errors[$field])) {
        return '';
    }
    return '<div class="invalid-feedback d-block">' . e($errors[$field]) . '</div>';
}
