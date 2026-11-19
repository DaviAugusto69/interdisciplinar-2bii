<?php

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Lesson;
use App\Models\User;


class AdminController
{
    // Exibe a tela de login administrativo
    public function login(): void
    {
        session_start();

        if (isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/index'));
            exit;
        }

        $_SESSION['csrf_admin'] ??= bin2hex(random_bytes(32));

        $this->render('admin/login', [
            'title' => 'Acesso administrativo',
            'csrf' => $_SESSION['csrf_admin'],
            'error' => ''
        ]);
    }

    // Verifica as informações de acesso
    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Método não permitido');
        }

        session_start();

        if (!hash_equals(
            $_SESSION['csrf_admin'] ?? '',
            $_POST['csrf'] ?? ''
        )) {
            http_response_code(403);
            exit('Token inválido');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $admin = Admin::findByEmail($email);

        if (!$admin || !password_verify($password, $admin['password'])) {
            $this->render('admin/login', [
                'title' => 'Acesso administrativo',
                'csrf' => $_SESSION['csrf_admin'],
                'error' => 'E-mail ou senha incorretos.'
            ]);
            return;
        }

        session_regenerate_id(true);

        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];

        header('Location: ' . url('admin/index'));
        exit;
    }

    // Exibe o painel administrativo
    public function index(): void
    {
        session_start();

        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit;
        }

       $this->render('admin/index', [
    'title' => 'Painel administrativo',
    'adminName' => $_SESSION['admin_name'],
    'totalUsers' => User::count(),
    'totalContents' => Lesson::count()
]);

    }

    // Renderiza as páginas
    private function render(string $view, array $data = []): void
    {
        $base = __DIR__ . '/../Views/';

        $renderFile = static function (
            string $__file,
            array $__data
        ): string {
            extract($__data, EXTR_SKIP);
            ob_start();
            require $__file;
            return ob_get_clean();
        };

        $content = $renderFile($base . $view . '.php', $data);

        $vars = $data + ['content' => $content];

        echo $renderFile(
            $base . 'layouts/main.php',
            $vars
        );
    }
}
