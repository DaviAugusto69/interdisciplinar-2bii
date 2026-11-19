<?php
namespace App\Controllers;

use App\Models\User;
use App\Validators\UserValidator;

class PagesController
{
    public function index(): void
    {
	
        $this->render('pages/index', [
            'title' => 'Início',
            'showHero' => true,
        ]);
    }

    public function contact(): void
    {
	
        $this->render('pages/contact', [
            'title' => 'Cadastro',
            'accountPage' => 'contact',
        ]);
    }

    public function login(): void
    {
        $this->render('pages/login', [
            'title' => 'Entrar',
            'accountPage' => 'login',
        ]);
    }

    public function recoverPassword(): void
    {
        $this->render('pages/recover-password', [
            'title' => 'Recuperar senha',
            'accountPage' => 'recoverPassword',
        ]);
    }

    public function painel(): void
    {
        $this->render('pages/painel', [
            'title' => 'Meu painel',
        ]);
    }

    public function aprendizagem(): void
    {
        $lessons = [
            ['introducao-ao-celular', 'Introdução ao celular', 'play-btn'],
            ['conhecendo-a-tela', 'Conhecendo a tela', 'phone'],
            ['fazendo-uma-ligacao', 'Fazendo uma ligação', 'telephone'],
            ['salvando-contatos', 'Salvando contatos', 'people'],
            ['revisao-da-aula', 'Revisão da aula', 'check-circle'],
        ];
        $requestedLesson = $_GET['aula'] ?? '';
        $lessonIndex = array_search($requestedLesson, array_column($lessons, 0), true);
        $lessonIndex = $lessonIndex === false ? 0 : $lessonIndex;
        $videoPath = 'assets/' . $lessons[$lessonIndex][0] . '.mp4';

        $this->render('pages/aprendizagem', [
            'title' => 'Aprendizagem',
            'lessons' => $lessons,
            'lessonIndex' => $lessonIndex,
            'videoPath' => is_file(__DIR__ . '/../../public/' . $videoPath) ? $videoPath : null,
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

    public function termos(): void
    {
        $this->render('pages/termos', [
            'title' => 'Termos de Uso',
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
