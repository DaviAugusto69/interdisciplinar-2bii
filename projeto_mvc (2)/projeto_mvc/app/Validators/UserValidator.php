<?php
namespace App\Validators;

class UserValidator
{
    /** @return array<string, string> campo => mensagem de erro */
    public function validate(array $input): array
    {
        $errors = [];

        $name = $input['name'] ?? '';
        if (!is_string($name) || trim($name) === '') {
            $errors['name'] = 'Informe o nome.';
        } elseif (mb_strlen($name) > 100) {
            $errors['name'] = "O nome deve ter no m\u{00e1}ximo 100 caracteres.";
        }

        $email = $input['email'] ?? '';
        if (!is_string($email) || mb_strlen($email) > 150
            || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Informe um e-mail v\u{00e1}lido.";
        }

        return $errors;
    }
}