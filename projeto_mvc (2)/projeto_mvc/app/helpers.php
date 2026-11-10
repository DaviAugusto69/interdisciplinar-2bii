<?php
// e(): escapa texto para HTML (previne XSS)
function e(?string $value): string
{
return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

// url(): monta um link interno respeitando o BASE_URL
function url(string $path = ''): string
{
return BASE_URL . '/' . ltrim($path, '/');
}

// asset(): monta o link de um arquivo estático e acrescenta a versão
function asset(string $path): string
{
$file = __DIR__ . '/../public/' . ltrim($path, '/');
$version = is_file($file) ? filemtime($file) : time();
return BASE_URL . '/' . ltrim($path, '/') . '?v=' . $version;
}