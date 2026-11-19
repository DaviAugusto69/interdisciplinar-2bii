<?php
namespace App\Controllers;

use App\Models\SupportMessage;
use App\Models\User;
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

    public function profile(): void
    {
        session_status() === PHP_SESSION_NONE && session_start();
        $id = (int) ($_SESSION['user_id'] ?? 0);
        $user = $id > 0 ? User::profile($id) : null;

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
        ];

        $errors = (new ProfileValidator())->profile($input);
        if (!$errors && User::emailTakenByOther($input['email'], $id)) {
            $errors['email'] = 'Este e-mail já está cadastrado.';
        }
        if ($errors) {
            $this->redirectBack('pages/profile', 'dados', [
                'form' => 'dados', 'errors' => $errors, 'old' => $input,
            ]);
        }

        User::updateProfile($id, $input['name'], $input['email'], $input['phone']);
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

        $opcoes = [
            'expires'  => time() + 60 * 60 * 24 * 365,
            'path'     => BASE_URL === '' ? '/' : BASE_URL,
            'samesite' => 'Lax',
            'httponly' => true,
        ];
        setcookie('eda_fonte', $fonte, $opcoes);
        setcookie('eda_tema', $tema, $opcoes);

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
        if ($id <= 0) {
            http_response_code(401);
            exit('Entre para alterar os dados da sua conta.');
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
