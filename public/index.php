<?php
declare(strict_types=1);

// 0) Caminho base: '/projeto_mvc' em subpasta, '' em VirtualHost
$base = dirname(dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', rtrim(str_replace('\\', '/', $base), '/'));


// 1) Autoload
spl_autoload_register(function (string $class) {
    $file = __DIR__ . '/../app/'
        . str_replace(['App\\', '\\'], ['', '/'], $class) . '.php';
    if (is_file($file))
        require $file;
});

require __DIR__ . '/../app/helpers.php';

// 2) Interpretação da URL
$route = $_GET['url'] ?? 'pages/index';
if (!is_string($route) || !preg_match('~^[a-zA-Z0-9/_-]*$~D', $route)) {
    http_response_code(404);
    exit;
}
$url = explode('/', trim($route, '/'));
$url[0] = strtolower($url[0] ?: 'pages');
$controller = 'App\\Controllers\\' . ucfirst($url[0]) . 'Controller';
$method = $url[1] ?? 'index';
$params = array_slice($url, 2);

// 3) Validação e despacho
if (!class_exists($controller) || !method_exists($controller, $method)
    || !(new ReflectionMethod($controller, $method))->isPublic()
    || str_starts_with($method, '__')) {
    http_response_code(404);
    exit("Página não encontrada");
}

ini_set('session.use_strict_mode', '1');
session_set_cookie_params([
    'httponly' => true,
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'samesite' => 'Lax',
    'path' => BASE_URL ?: '/',
]);
session_start();
if ($url[0] === 'user') {
    if (strtolower($method) === 'create' && $_SERVER['REQUEST_METHOD'] === 'GET') {
        header('Location: ' . url('pages/contact'), true, 303);
        exit;
    }
    if (!isset($_SESSION['admin_id'])) {
        header('Location: ' . url('admin/login'), true, 303);
        exit;
    }
    if (!in_array(strtolower($method), ['index', 'show'], true)) {
        http_response_code(404);
        exit;
    }
}
try {
    (new $controller())->$method(...$params);
} catch (PDOException $error) {
    error_log('EDA: falha no banco (SQLSTATE ' . $error->getCode() . ').');
    http_response_code(503);
    exit('O serviço está temporariamente indisponível. Tente novamente em instantes.');
}