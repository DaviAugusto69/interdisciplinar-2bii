<?php

namespace App\Controllers;

use App\Models\AccessLog;
use App\Models\Admin;
use App\Models\Lesson;
use App\Models\Learning;
use App\Models\Progress;
use App\Models\User;

class AdminController
{
    public function login(): void
    {

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


        $this->checkCsrf();

        $email = $this->post('email');
        $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

        $admin = mb_strlen($email) <= 150 && filter_var($email, FILTER_VALIDATE_EMAIL)
            ? Admin::findByEmail($email) : null;

        if (!$admin || !password_verify($password, $admin['password'])) {
            $this->render('admin/login', [
                'title' => 'Acesso administrativo',
                'csrf' => $_SESSION['csrf_admin'],
                'error' => 'E-mail ou senha incorretos.'
            ]);
            return;
        }

        session_regenerate_id(true);

        $_SESSION = [];
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];

        header('Location: ' . url('admin/index'));
        exit;
    }

    public function index(): void
    {

        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit;
        }

        $this->render('admin/index', [
            'title' => 'Painel administrativo',
            'adminName' => $_SESSION['admin_name'],
            'csrf' => $_SESSION['csrf_admin'] ??= bin2hex(random_bytes(32)),
            'totalUsers' => User::count(),
            'totalContents' => Lesson::count(),
            'completedActivities' => Progress::countCompleted(),
            'totalAccesses' => AccessLog::countAll(),
        ]);
    }

    public function contents(): void
    {

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

        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit;
        }

        $_SESSION['csrf_admin'] ??= bin2hex(random_bytes(32));

        $this->render('admin/content-form', [
            'title'  => 'Novo conteúdo',
            'action' => url('admin/storeContent'),
            'lesson' => null,
            'categories' => Learning::catalog('categories'),
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


        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit;
        }

        $this->checkCsrf();

        $title       = $this->post('title');
        $description = $this->post('description');
        $type        = $this->post('type');
        $icon        = $this->post('icon');
        $mediaUrl    = $this->post('media_url');
        $bodyText    = $this->post('body_text');
        $categoryId  = (int) $this->post('category_id');
        $categories  = Learning::catalog('categories');

        if ($title === '' || mb_strlen($title) > 150 || mb_strlen($icon) > 50
            || strlen($description) > 65535 || strlen($bodyText) > 65535 || mb_strlen($mediaUrl) > 255
            || ($mediaUrl !== '' && Lesson::mediaUrl($mediaUrl) === null)
            || ($categoryId !== 0 && !in_array($categoryId, array_map('intval', array_column($categories, 'id')), true))
            || !in_array($type, ['video', 'texto', 'audio', 'imagem'], true)) {
            $this->render('admin/content-form', [
                'title'  => 'Novo conteúdo',
                'action' => url('admin/storeContent'),
                'lesson' => array_filter($_POST, 'is_string'),
                'csrf'   => $_SESSION['csrf_admin'],
                'categories' => $categories,
                'error'  => 'Revise os campos e use um link HTTP(S) ou um arquivo de mídia em assets.',
            ]);
            return;
        }

        $slug = substr($this->slugify($title), 0, 95) . '-' . bin2hex(random_bytes(8));

        Lesson::create([
            'slug'        => $slug,
            'title'       => $title,
            'description' => $description !== '' ? $description : null,
            'type'        => $type,
            'icon'        => $icon !== '' ? $icon : 'play-btn',
            'media_url'   => $mediaUrl !== '' ? $mediaUrl : null,
            'body_text'   => $bodyText !== '' ? $bodyText : null,
            'is_published' => (int) ($this->post('is_published') === '1'),
            'category_id' => $categoryId ?: null,
        ]);

        header('Location: ' . url('admin/contents'));
        exit;
    }

    public function editContent(string $id = '0'): void
    {

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
            'categories' => Learning::catalog('categories'),
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


        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit;
        }

        $this->checkCsrf();

        $contentId   = (int) $id;
        $title       = $this->post('title');
        $description = $this->post('description');
        $type        = $this->post('type');
        $icon        = $this->post('icon');
        $mediaUrl    = $this->post('media_url');
        $bodyText    = $this->post('body_text');
        $categoryId  = (int) $this->post('category_id');
        $categories  = Learning::catalog('categories');

        if ($title === '' || mb_strlen($title) > 150 || mb_strlen($icon) > 50
            || strlen($description) > 65535 || strlen($bodyText) > 65535 || mb_strlen($mediaUrl) > 255
            || ($mediaUrl !== '' && Lesson::mediaUrl($mediaUrl) === null)
            || ($categoryId !== 0 && !in_array($categoryId, array_map('intval', array_column($categories, 'id')), true))
            || !in_array($type, ['video', 'texto', 'audio', 'imagem'], true)) {
            $this->render('admin/content-form', [
                'title'  => 'Editar conteúdo',
                'action' => url('admin/updateContent/' . $contentId),
                'lesson' => array_filter($_POST, 'is_string') + ['id' => $contentId],
                'csrf'   => $_SESSION['csrf_admin'],
                'categories' => $categories,
                'error'  => 'Revise os campos e use um link HTTP(S) ou um arquivo de mídia em assets.',
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
            'is_published' => (int) ($this->post('is_published') === '1'),
            'category_id' => $categoryId ?: null,
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


        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit;
        }

        $this->checkCsrf();

        Lesson::delete((int) $id);

        header('Location: ' . url('admin/contents'));
        exit;
    }

    public function users(): void
    {

        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit;
        }

        $_SESSION['csrf_admin'] ??= bin2hex(random_bytes(32));
        $search = is_string($_GET['q'] ?? null) ? trim($_GET['q']) : '';

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


        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit;
        }

        $this->checkCsrf();

        User::delete((int) $id);

        header('Location: ' . url('admin/users'));
        exit;
    }

    public function reports(): void
    {

        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            exit;
        }

        $this->render('admin/reports', [
            'title'               => 'Relatórios',
            'totalUsers'          => User::count(),
            'totalContents'       => Lesson::count(),
            'completedActivities' => Progress::countCompleted(),
            'totalAccesses'       => AccessLog::countAll(),
            'topLessons'          => Progress::topLessons(),
            'recentAccesses'      => AccessLog::recent(),
        ]);
    }

    public function logout(): void
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }
        $this->checkCsrf();
        $_SESSION = [];
        session_regenerate_id(true);

        header('Location: ' . url('admin/login'));
        exit;
    }

    public function learning(): void
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            return;
        }

        $this->render('admin/learning', [
            'title' => 'Categorias, atividades e recompensas',
            'csrf' => $_SESSION['csrf_admin'] ??= bin2hex(random_bytes(32)),
            'categories' => Learning::catalog('categories'),
            'activities' => Learning::catalog('activities'),
            'rewards' => Learning::catalog('rewards'),
            'lessons' => Lesson::allAdmin(),
        ]);
    }

    public function saveLearning(): void
    {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . url('admin/login'));
            return;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }
        $this->checkCsrf();

        $table = $this->post('kind');
        $id = (int) $this->post('id');
        $name = $this->post('name');
        $title = $this->post('title');
        $prompt = $this->post('prompt');
        $answer = $this->post('correct_answer');
        $choices = array_values(array_unique(array_filter(
            array_map('trim', preg_split('/\R/', $this->post('choices'))),
            static fn ($choice) => $choice !== ''
        )));
        $choicesValid = !$choices || (count($choices) >= 2 && count($choices) <= 6
            && max(array_map('mb_strlen', $choices)) <= 150 && in_array($answer, $choices, true));
        $lessonId = (int) $this->post('lesson_id');
        $description = $this->post('description');
        $points = filter_var($this->post('points'), FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 1000000]]);
        $active = (int) ($this->post('is_active') === '1');

        $data = match ($table) {
            'categories' => $name !== '' && mb_strlen($name) <= 100 ? ['name' => $name] : null,
            'activities' => $title !== '' && mb_strlen($title) <= 150 && $prompt !== '' && strlen($prompt) <= 65535
                 && mb_strlen($answer) <= 150 && $choicesValid && $points !== false && Lesson::find($lessonId)
                ? ['lesson_id' => $lessonId, 'title' => $title, 'prompt' => $prompt,
                   'choices' => $choices ? implode("\n", $choices) : null,
                   'correct_answer' => $answer === '' ? null : $answer, 'points' => $points, 'is_active' => $active] : null,
            'rewards' => $name !== '' && mb_strlen($name) <= 100 && $description !== '' && strlen($description) <= 65535
                 && $points !== false
                ? ['name' => $name, 'description' => $description, 'required_points' => $points, 'is_active' => $active] : null,
            default => null,
        };
        if ($data === null || $id < 0) {
            http_response_code(422);
            exit('Confira os dados do formulário.');
        }
        try {
            Learning::save($table, $id, $data);
        } catch (\PDOException $error) {
            if (($error->errorInfo[1] ?? null) !== 1062) {
                throw $error;
            }
            http_response_code(422);
            exit('Já existe um registro com esse nome.');
        }
        header('Location: ' . url('admin/learning'), true, 303);
    }

    private function checkCsrf(): void
    {
        $token = $_SESSION['csrf_admin'] ?? '';
        if ($token === '' || !hash_equals($token, $this->post('csrf'))) {
            http_response_code(403);
            exit('Token inválido');
        }
    }

    private function post(string $key): string
    {
        return is_string($_POST[$key] ?? null) ? trim($_POST[$key]) : '';
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
