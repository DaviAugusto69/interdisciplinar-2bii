<?php
namespace App\Controllers;

use App\Models\User;
use App\Validators\UserValidator;

class UserController
{
    public function index(): void
    {
        $this->render('users/index', [
            'title' => "Usu\u{00e1}rios",
            'users' => User::all(),
        ]);
    }

    public function show(string $id = ''): void
    {
        $user = User::find((int) $id);
        if (!$user) {
            http_response_code(404);
            exit("Usu\u{00e1}rio n\u{00e3}o encontrado");
        }

        $this->render('users/index', [
            'title' => "Usu\u{00e1}rio",
            'users' => [$user],
        ]);
    }

    public function create(): void
    {
        $this->render('users/form', [
            'title' => "Novo usu\u{00e1}rio",
            'errors' => [],
            'old' => [],
            'csrf' => $this->csrfToken(),
        ]);
    }

    public function store(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
            http_response_code(405);
            exit("M\u{00e9}todo n\u{00e3}o permitido");
        }

        $this->checkCsrf();
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $input = [
            'name' => is_string($name) ? trim($name) : $name,
            'email' => is_string($email) ? trim($email) : $email,
        ];

        $errors = (new UserValidator())->validate($input);
        if (!$errors && User::emailExists($input['email'])) {
            $errors['email'] = "Este e-mail j\u{00e1} est\u{00e1} cadastrado.";
        }

        if ($errors) {
            http_response_code(422);
            $this->render('users/form', [
                'title' => "Novo usu\u{00e1}rio",
                'errors' => $errors,
                'old' => $input,
                'csrf' => $this->csrfToken(),
            ]);
            return;
        }

        User::create($input['name'], $input['email']);
        header('Location: ' . url('user/index'));
        exit;
    }

    private function csrfToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
    }

    private function checkCsrf(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
            http_response_code(403);
            exit("Token inv\u{00e1}lido");
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