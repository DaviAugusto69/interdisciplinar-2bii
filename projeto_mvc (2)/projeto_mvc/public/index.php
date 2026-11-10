<?php
declare(strict_types=1);

// Base path: '/projeto_mvc' in a subdirectory, '' in a VirtualHost.
$base = dirname(dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', rtrim(str_replace('\\', '/', $base), '/'));

spl_autoload_register(function (string $class): void {
    $file = __DIR__ . '/../app/'
        . str_replace(['App\\', '\\'], ['', '/'], $class) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

require __DIR__ . '/../app/helpers.php';

$url = explode('/', trim($_GET['url'] ?? 'user/index', '/'));
$controller = 'App\\Controllers\\' . ucfirst($url[0]) . 'Controller';
$method = $url[1] ?? 'index';
$params = array_slice($url, 2);

if (!class_exists($controller)) {
    http_response_code(404);
    exit('Page not found');
}

$instance = new $controller();
if (!is_callable([$instance, $method])) {
    http_response_code(404);
    exit('Page not found');
}

$instance->$method(...$params);