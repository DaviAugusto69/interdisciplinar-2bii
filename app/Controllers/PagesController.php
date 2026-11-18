<?php
namespace App\Controllers;

use App\Models\User;
use App\Validators\UserValidator;

class PagesController
{
    public function index(): void
    {
	
        $this->render('pages/index', [
            'title' => 'Inicio',
        ]);
    }

    public function contact(): void
    {
	
        $this->render('pages/contact', [
            'title' => 'Contato',
        ]);
    }

    public function resume(): void
    {
	
        $this->render('pages/resume', [
            'title' => 'Resumo',
        ]);
    }

    public function projects(): void
    {
	
        $this->render('pages/projects', [
            'title' => 'Projetos',
        ]);
    }

    private function csrfToken(): string
    {
        session_status() === PHP_SESSION_NONE && session_start();
        return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
    }

    private function checkCsrf(): void
    {
        session_status() === PHP_SESSION_NONE && session_start();
        if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
            http_response_code(403);
            exit('Token inválido');
        }
    }

    private function render(string $view, array $data = []): void
    {
        $base = __DIR__ . '/../Views/';

        $renderFile = static function (string $__file, array $__data): string {
            extract($__data, EXTR_SKIP);
            ob_start();
            require $__file;
            return ob_get_clean();
        };

        $content = $renderFile($base . $view . '.php', $data);
        $vars = $data + ['content' => $content];
        echo $renderFile($base . 'layouts/main.php', $vars);
    }
}
