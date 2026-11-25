<?php
namespace App\Controllers;

use App\Models\SupportMessage;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Progress;
use App\Models\AccessLog;
use App\Models\Learning;
use App\Validators\ProfileValidator;
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
        $flash = $this->takeFlash();
        $this->render('pages/contact', [
            'title' => 'Cadastro',
            'accountPage' => 'contact',
            'csrf' => $this->csrfToken(),
            'errors' => $flash['errors'] ?? [],
            'old' => $flash['old'] ?? [],
            'ok' => ($flash['ok'] ?? null) === 'cadastro',
        ]);
    }

    public function register(): void
    {
        $this->requirePost();

        $input = ['name' => $this->post('name'), 'email' => $this->post('email')];
        $input['cpf'] = $this->post('cpf');
        $input['address'] = $this->post('address');
        $cpf = preg_replace('/[.\-]/', '', $input['cpf']);
        $password = $this->post('new_password', false);
        $errors = (new UserValidator())->validate($input)
            + (new ProfileValidator())->password([
                'new_password' => $password,
                'confirm_password' => $this->post('confirm_password', false),
            ], false);
        if (!preg_match('/^\d{11}$/D', $cpf) || preg_match('/^(\d)\1{10}$/D', $cpf)) {
            $errors['cpf'] = 'Informe um CPF válido.';
        } else {
            foreach ([9, 10] as $length) {
                $sum = 0;
                for ($i = 0; $i < $length; $i++) {
                    $sum += (int) $cpf[$i] * ($length + 1 - $i);
                }
                if (((10 * $sum) % 11) % 10 !== (int) $cpf[$length]) {
                    $errors['cpf'] = 'Informe um CPF válido.';
                }
            }
        }
        if ($input['address'] === '' || mb_strlen($input['address']) > 250) {
            $errors['address'] = 'Informe um endereço de até 250 caracteres.';
        }
        if (str_contains($password, "\0")) {
            $errors['new_password'] = 'Informe uma senha válida.';
        }
        if ($errors) {
            $this->redirectBack('pages/contact', 'account-form', ['errors' => $errors, 'old' => $input]);
        }

        try {
            $id = User::register($input['name'], $input['email'], password_hash($password, PASSWORD_DEFAULT), $cpf, $input['address']);
        } catch (\PDOException $exception) {
            $message = 'Não foi possível criar a conta. Tente novamente.';
            $field = 'form';
            if (($exception->errorInfo[1] ?? null) === 1062) {
                $message = 'Já existe uma conta com o e-mail ou CPF informado.';
            } else {
                error_log('Cadastro: falha ao salvar usuário (SQLSTATE ' . $exception->getCode() . ').');
            }
            $this->redirectBack('pages/contact', 'account-form', [
                'errors' => [$field => $message], 'old' => $input,
            ]);
        }

        session_regenerate_id(true);
        $_SESSION = ['user_id' => $id];
        AccessLog::record($id);
        $this->redirectBack('pages/contact', 'account-status', ['ok' => 'cadastro']);
    }

    public function login(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->currentUserId();
            header('Location: ' . url('pages/painel'), true, 303);
            return;
        }
        $flash = $this->takeFlash();
        $this->render('pages/login', [
            'title' => 'Entrar',
            'accountPage' => 'login',
            'csrf' => $this->csrfToken(),
            'errors' => $flash['errors'] ?? [],
            'old' => $flash['old'] ?? [],
        ]);
    }

    public function authenticate(): void
    {
        $this->requirePost();
        $email = $this->post('email');
        $password = $this->post('senha', false);
        $user = mb_strlen($email) <= 150 && filter_var($email, FILTER_VALIDATE_EMAIL)
            ? User::credentials($email) : null;
        if (!$user || !$user['password_hash'] || strlen($password) > 72
            || !password_verify($password, $user['password_hash'])) {
            $this->redirectBack('pages/login', 'account-form', [
                'errors' => ['form' => 'E-mail ou senha incorretos.'],
                'old' => ['email' => $email],
            ]);
        }

        $id = (int) $user['id'];
        session_regenerate_id(true);
        $_SESSION = ['user_id' => $id];
        $preferences = User::preferences($id);
        if (isset($preferences['fonte'], $preferences['tema'])) {
            $_SESSION['eda_preferences'] = $preferences;
        }
        AccessLog::record($id);
        header('Location: ' . url('pages/painel'), true, 303);
    }

    public function logout(): void
    {
        $this->requirePost();
        $_SESSION = [];
        session_regenerate_id(true);
        header('Location: ' . url('pages/login'), true, 303);
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
        $id = $this->currentUserId();
        $lessons = Lesson::all();
        $completed = Progress::completedLessons($id);
        $nextLesson = null;
        $modules = [];
        foreach ($lessons as $lesson) {
            $done = in_array($lesson[0], $completed, true);
            if (!$done && $nextLesson === null) {
                $nextLesson = $lesson[0];
            }
            $category = $lesson[7];
            if ($category !== null) {
                $modules[$category] ??= ['total' => 0, 'completed' => 0, 'slug' => $lesson[0]];
                $modules[$category]['total']++;
                $modules[$category]['completed'] += (int) $done;
            }
        }
        $this->render('pages/painel', [
            'title' => 'Meu painel',
            'total' => count($lessons),
            'completed' => count($completed),
            'modules' => $modules,
            'nextLesson' => $nextLesson,
            'rewards' => Learning::rewardsForUser($id),
            'recentLessons' => Lesson::recentPublications(),
        ]);
    }

    public function aprendizagem(): void
    {
        $id = $this->currentUserId();
        $lessons = Lesson::all();
        $requestedLesson = $_GET['aula'] ?? '';
        $lessonIndex = array_search($requestedLesson, array_column($lessons, 0), true);
        $lessonIndex = $lessonIndex === false ? 0 : $lessonIndex;
        $lesson = $lessons[$lessonIndex] ?? null;
        $mediaUrl = Lesson::mediaUrl($lesson[5] ?? '');
        $activities = $lesson ? Learning::activitiesForLesson($lesson[0], $id) : [];
        $flash = $this->takeFlash();

        $this->render('pages/aprendizagem', [
            'title' => 'Aprendizagem',
            'lessons' => $lessons,
            'lessonIndex' => $lessonIndex,
            'mediaUrl' => $mediaUrl,
            'activities' => $activities,
            'activityMessage' => $flash['activityMessage'] ?? null,
            'completed' => $lesson && in_array($lesson[0], Progress::completedLessons($id), true),
            'csrf' => $this->csrfToken(),
        ]);
    }

    public function completeLesson(): void
    {
        $this->requirePost();
        $id = $this->currentUserId();
        $slug = $this->post('lesson');
        if ($slug === '' || strlen($slug) > 120) {
            http_response_code(422);
            exit('Selecione uma aula.');
        }
        Progress::markCompleted($id, $slug);
        header('Location: ' . url('pages/aprendizagem') . '?aula=' . rawurlencode($slug), true, 303);
    }

    public function answerActivity(): void
    {
        $this->requirePost();
        $id = $this->currentUserId();
        $activity = (int) $this->post('activity');
        $slug = $this->post('lesson');
        $answer = $this->post('answer');
        if ($activity <= 0 || strlen($slug) > 120 || mb_strlen($answer) > 150) {
            http_response_code(422);
            exit('Confira a resposta enviada.');
        }
        $result = Learning::answer($id, $activity, $slug, $answer);
        $_SESSION['flash'] = ['activityMessage' => $result ? 'Atividade concluída.' : 'Resposta incorreta. Tente novamente.'];
        header('Location: ' . url('pages/aprendizagem') . '?aula=' . rawurlencode($slug) . '#atividades', true, 303);
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

    public function profile(): void
    {
        session_status() === PHP_SESSION_NONE && session_start();
        $id = $this->currentUserId();
        $user = User::profile($id);

        $flash = $this->takeFlash();
        $this->render('pages/profile', [
            'title'  => 'Perfil e configurações',
            'hero'   => false,
            'user'   => $user,
            'prefs'  => prefs(),
            'csrf'   => $this->csrfToken(),
            'ok'     => $flash['ok'] ?? null,
            'form'   => $flash['form'] ?? null,
            'errors' => $flash['errors'] ?? [],
            'old'    => $flash['old'] ?? [],
        ]);
    }

    public function updateProfile(): void
    {
        $this->requirePost();

        $id = $this->currentUserId();
        $input = [
            'name'  => $this->post('name'),
            'email' => $this->post('email'),
            'phone' => $this->post('phone'),
            'address' => $this->post('address'),
        ];

        $errors = (new ProfileValidator())->profile($input);
        if (mb_strlen($input['address']) > 250) {
            $errors['address'] = 'O endereço deve ter até 250 caracteres.';
        }
        if (!$errors && User::emailTakenByOther($input['email'], $id)) {
            $errors['email'] = 'Este e-mail já está cadastrado.';
        }
        if ($errors) {
            $this->redirectBack('pages/profile', 'dados', [
                'form' => 'dados', 'errors' => $errors, 'old' => $input,
            ]);
        }

        User::updateProfile($id, $input['name'], $input['email'], $input['phone'], $input['address']);
        $this->redirectBack('pages/profile', 'dados', ['ok' => 'dados']);
    }

    public function updatePassword(): void
    {
        $this->requirePost();

        $id   = $this->currentUserId();
        $user = User::profile($id);
        if (!$user) {
            http_response_code(404);
            exit('Usuário não encontrado');
        }

        // Senhas não passam por trim() e nunca voltam preenchidas na tela.
        $input = [
            'current_password' => $this->post('current_password', false),
            'new_password'     => $this->post('new_password', false),
            'confirm_password' => $this->post('confirm_password', false),
        ];

        $errors = (new ProfileValidator())->password($input, $user['has_password']);
        if (!$errors && $user['has_password']) {
            $hash = User::passwordHash($id);
            if ($hash === null || !password_verify($input['current_password'], $hash)) {
                $errors['current_password'] = 'A senha atual está incorreta.';
            }
        }
        if ($errors) {
            $this->redirectBack('pages/profile', 'senha', ['form' => 'senha', 'errors' => $errors]);
        }

        User::setPassword($id, password_hash($input['new_password'], PASSWORD_DEFAULT));
        $this->redirectBack('pages/profile', 'senha', ['ok' => 'senha']);
    }

    public function updatePreferences(): void
    {
        $this->requirePost();

        $id = $this->currentUserId();
        $validas = prefs_validas();
        if ($this->post('acao') === 'restaurar') {
            $fonte = 'normal';
            $tema  = 'claro';
        } else {
            $fonte = $this->post('fonte');
            $tema  = $this->post('tema');
            if (!in_array($fonte, $validas['fonte'], true) || !in_array($tema, $validas['tema'], true)) {
                $this->redirectBack('pages/profile', 'prefs', [
                    'form' => 'prefs', 'errors' => ['prefs' => 'Escolha uma opção de cada grupo.'],
                ]);
            }
        }

        User::savePreferences($id, $fonte, $tema);
        $_SESSION['eda_preferences'] = ['fonte' => $fonte, 'tema' => $tema];
        $this->redirectBack('pages/profile', 'prefs', ['ok' => 'prefs']);
    }

    // ------------------------------------------------------------------
    // AJUDA E SUPORTE
    // GET  /pages/help?passo=2   mostra a página (passo do tutorial guiado)
    // POST /pages/sendSupport    grava a mensagem para o suporte
    // ------------------------------------------------------------------

    public function help(): void
    {
        $passos = [
            ['icone' => 'bi-person-plus',   'titulo' => 'Crie sua conta',
             'texto' => 'Informe seu nome e seu e-mail e escolha uma senha. Leva menos de dois minutos.'],
            ['icone' => 'bi-sliders',       'titulo' => 'Deixe o site do seu jeito',
             'texto' => 'Em Perfil e configurações, aumente as letras e escolha o tema que for mais confortável para seus olhos.'],
            ['icone' => 'bi-book',          'titulo' => 'Escolha uma aula',
             'texto' => 'Na área Aprender, escolha um assunto e estude no seu ritmo, com vídeo, áudio ou texto.'],
            ['icone' => 'bi-pencil-square', 'titulo' => 'Pratique e tire dúvidas',
             'texto' => 'Faça os quizzes e as simulações da área Atividades. Se travar, volte aqui ou fale com o suporte.'],
        ];
        $passo = (int) ($_GET['passo'] ?? 1);
        $passo = max(1, min(count($passos), $passo));

        $flash = $this->takeFlash();
        $this->render('pages/help', [
            'title'    => 'Ajuda e suporte',
            'hero'     => false,
            'csrf'     => $this->csrfToken(),
            'passos'   => $passos,
            'passo'    => $passo,
            'assuntos' => SupportMessage::SUBJECTS,
            'ok'       => $flash['ok'] ?? null,
            'errors'   => $flash['errors'] ?? [],
            'old'      => $flash['old'] ?? [],
        ]);
    }

    public function sendSupport(): void
    {
        $this->requirePost();

        $input = [
            'name'    => $this->post('name'),
            'email'   => $this->post('email'),
            'subject' => $this->post('subject'),
            'message' => $this->post('message'),
        ];

        $errors = (new ProfileValidator())->support($input);
        if ($errors) {
            $this->redirectBack('pages/help', 'suporte', ['errors' => $errors, 'old' => $input]);
        }

        SupportMessage::create($input['name'], $input['email'], $input['subject'], $input['message']);
        $this->redirectBack('pages/help', 'suporte', ['ok' => 'suporte']);
    }

    // ------------------------------------------------------------------
    // Apoio
    // ------------------------------------------------------------------

    private function currentUserId(): int
    {
        session_status() === PHP_SESSION_NONE && session_start();
        $id = (int) ($_SESSION['user_id'] ?? 0);
        if ($id <= 0 || !User::find($id)) {
            unset($_SESSION['user_id'], $_SESSION['eda_preferences']);
            header('Location: ' . url('pages/login'), true, 303);
            exit;
        }
        return $id;
    }

    /** Lê um campo do POST como texto (campos enviados como array viram ''). */
    private function post(string $key, bool $trim = true): string
    {
        $value = $_POST[$key] ?? '';
        if (!is_string($value)) {
            return '';
        }
        return $trim ? trim($value) : $value;
    }

    private function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            exit('Método não permitido');
        }
        $this->checkCsrf();
    }

    /** Post/Redirect/Get: guarda o recado na sessão e volta para a página (com âncora). */
    private function redirectBack(string $route, string $anchor, array $flash): void
    {
        session_status() === PHP_SESSION_NONE && session_start();
        $_SESSION['flash'] = $flash;
        header('Location: ' . url($route) . '#' . $anchor, true, 303);
        exit;
    }

    /** Lê e apaga o recado da sessão (aparece uma única vez). */
    private function takeFlash(): array
    {
        session_status() === PHP_SESSION_NONE && session_start();
        $flash = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);
        return is_array($flash) ? $flash : [];
    }

    private function csrfToken(): string
    {
        session_status() === PHP_SESSION_NONE && session_start();
        return $_SESSION['csrf'] ??= bin2hex(random_bytes(32));
    }

    private function checkCsrf(): void
    {
        session_status() === PHP_SESSION_NONE && session_start();
        $token = $_SESSION['csrf'] ?? '';
        if ($token === '' || !hash_equals($token, $this->post('csrf', false))) {
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
        if (isset($_SESSION['user_id'])) {
            $vars['logoutCsrf'] = $this->csrfToken();
        }
        echo $renderFile($base . 'layouts/main.php', $vars);
    }
}
