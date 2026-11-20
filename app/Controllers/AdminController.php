<?php

namespace App\Controllers;

use App\Models\AccessLog;
use App\Models\Admin;
use App\Models\Database;
use App\Models\Lesson;
use App\Models\Progress;
use App\Models\User;

class AdminController
{
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
            'totalContents' => Lesson::count(),
            'completedActivities' => Progress::countCompleted(),
            'totalAccesses' => AccessLog::countAll(),
        ]);
    }

    public function contents(): void
    {
        session_start();

        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit;
        }

        $_SESSION['csrf_admin'] ??= bin2hex(random_bytes(32));

        $this->render('admin/contents', [
            'title'    => 'Gerenciar conteúdos',
            'contents' => Lesson::allAdmin(),
            'csrf'     => $_SESSION['csrf_admin'],
        ]);
    }

    public function createContent(): void
    {
        session_start();

        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit;
        }

        $_SESSION['csrf_admin'] ??= bin2hex(random_bytes(32));

        $this->render('admin/content-form', [
            'title'  => 'Novo conteúdo',
            'action' => url('admin/storeContent'),
            'lesson' => null,
            'csrf'   => $_SESSION['csrf_admin'],
            'error'  => '',
        ]);
    }

    public function storeContent(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Método não permitido');
        }

        session_start();

        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit;
        }

        if (!hash_equals($_SESSION['csrf_admin'] ?? '', $_POST['csrf'] ?? '')) {
            http_response_code(403);
            exit('Token inválido');
        }

        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $type        = $_POST['type'] ?? 'video';
        $icon        = trim($_POST['icon'] ?? 'play-btn');
        $mediaUrl    = trim($_POST['media_url'] ?? '');
        $bodyText    = trim($_POST['body_text'] ?? '');

        if ($title === '' || !in_array($type, ['video', 'texto', 'audio', 'imagem'], true)) {
            $this->render('admin/content-form', [
                'title'  => 'Novo conteúdo',
                'action' => url('admin/storeContent'),
                'lesson' => $_POST,
                'csrf'   => $_SESSION['csrf_admin'],
                'error'  => 'Preencha o título e escolha um tipo válido.',
            ]);
            return;
        }

        $slug = $this->slugify($title) . '-' . substr((string) time(), -4);

        Lesson::create([
            'slug'        => $slug,
            'title'       => $title,
            'description' => $description !== '' ? $description : null,
            'type'        => $type,
            'icon'        => $icon !== '' ? $icon : 'play-btn',
            'media_url'   => $mediaUrl !== '' ? $mediaUrl : null,
            'body_text'   => $bodyText !== '' ? $bodyText : null,
        ]);

        header('Location: ' . url('admin/contents'));
        exit;
    }

    public function editContent(string $id = '0'): void
    {
        session_start();

        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit;
        }

        $lesson = Lesson::find((int) $id);
        if (!$lesson) {
            http_response_code(404);
            exit('Conteúdo não encontrado');
        }

        $_SESSION['csrf_admin'] ??= bin2hex(random_bytes(32));

        $this->render('admin/content-form', [
            'title'  => 'Editar conteúdo',
            'action' => url('admin/updateContent/' . (int) $id),
            'lesson' => $lesson,
            'csrf'   => $_SESSION['csrf_admin'],
            'error'  => '',
        ]);
    }

    public function updateContent(string $id = '0'): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Método não permitido');
        }

        session_start();

        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit;
        }

        if (!hash_equals($_SESSION['csrf_admin'] ?? '', $_POST['csrf'] ?? '')) {
            http_response_code(403);
            exit('Token inválido');
        }

        $contentId   = (int) $id;
        $title       = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $type        = $_POST['type'] ?? 'video';
        $icon        = trim($_POST['icon'] ?? 'play-btn');
        $mediaUrl    = trim($_POST['media_url'] ?? '');
        $bodyText    = trim($_POST['body_text'] ?? '');

        if ($title === '' || !in_array($type, ['video', 'texto', 'audio', 'imagem'], true)) {
            $this->render('admin/content-form', [
                'title'  => 'Editar conteúdo',
                'action' => url('admin/updateContent/' . $contentId),
                'lesson' => $_POST + ['id' => $contentId],
                'csrf'   => $_SESSION['csrf_admin'],
                'error'  => 'Preencha o título e escolha um tipo válido.',
            ]);
            return;
        }

        Lesson::update($contentId, [
            'title'       => $title,
            'description' => $description !== '' ? $description : null,
            'type'        => $type,
            'icon'        => $icon !== '' ? $icon : 'play-btn',
            'media_url'   => $mediaUrl !== '' ? $mediaUrl : null,
            'body_text'   => $bodyText !== '' ? $bodyText : null,
        ]);

        header('Location: ' . url('admin/contents'));
        exit;
    }

    public function deleteContent(string $id = '0'): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Método não permitido');
        }

        session_start();

        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit;
        }

        if (!hash_equals($_SESSION['csrf_admin'] ?? '', $_POST['csrf'] ?? '')) {
            http_response_code(403);
            exit('Token inválido');
        }

        Lesson::delete((int) $id);

        header('Location: ' . url('admin/contents'));
        exit;
    }

    public function users(): void
    {
        session_start();

        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit;
        }

        $_SESSION['csrf_admin'] ??= bin2hex(random_bytes(32));
        $search = trim($_GET['q'] ?? '');

        $this->render('admin/users', [
            'title'  => 'Gerenciar usuários',
            'users'  => User::allAdmin($search),
            'search' => $search,
            'csrf'   => $_SESSION['csrf_admin'],
        ]);
    }

    public function deleteUser(string $id = '0'): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Método não permitido');
        }

        session_start();

        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit;
        }

        if (!hash_equals($_SESSION['csrf_admin'] ?? '', $_POST['csrf'] ?? '')) {
            http_response_code(403);
            exit('Token inválido');
        }

        User::delete((int) $id);

        header('Location: ' . url('admin/users'));
        exit;
    }

    public function reports(): void
    {
        session_start();

        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit;
        }

        $pdo = Database::connect();

        $topLessons = $pdo->query(
            'SELECT l.title, COUNT(up.user_id) AS total
             FROM lessons l
             LEFT JOIN user_progress up ON up.lesson_slug = l.slug
             GROUP BY l.id, l.title
             ORDER BY total DESC, l.id ASC
             LIMIT 10'
        )->fetchAll();

        $recentAccesses = $pdo->query(
            'SELECT * FROM access_logs ORDER BY id DESC LIMIT 10'
        )->fetchAll();

        $this->render('admin/reports', [
            'title'               => 'Relatórios',
            'totalUsers'          => User::count(),
            'totalContents'       => Lesson::count(),
            'completedActivities' => Progress::countCompleted(),
            'totalAccesses'       => AccessLog::countAll(),
            'topLessons'          => $topLessons,
            'recentAccesses'      => $recentAccesses,
        ]);
    }

    public function logout(): void
    {
        session_start();

        unset($_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['csrf_admin']);

        header('Location: ' . url('admin/login'));
        exit;
    }

    private function slugify(string $text): string
    {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? 'aula';
        return trim($text, '-') ?: 'aula';
    }

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