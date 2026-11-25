<?php

namespace App\Validators;

use App\Models\SupportMessage;

class ProfileValidator
{
    /** @return array<string,string> campo => mensagem de erro */
    public function profile(array $input): array
    {
        $errors = [];

        $name = $input['name'] ?? '';
        if (!is_string($name) || trim($name) === '') {
            $errors['name'] = 'Informe seu nome.';
        } elseif (mb_strlen($name) < 3) {
            $errors['name'] = 'Digite seu nome completo.';
        } elseif (mb_strlen($name) > 100) {
            $errors['name'] = 'O nome deve ter no máximo 100 caracteres.';
        }

        $email = $input['email'] ?? '';
        if (
            !is_string($email) || mb_strlen($email) > 150
            || !filter_var($email, FILTER_VALIDATE_EMAIL)
        ) {
            $errors['email'] = 'Informe um e-mail válido. Exemplo: nome@email.com';
        }

        $phone = $input['phone'] ?? '';
        if (!is_string($phone)) {
            $errors['phone'] = 'Telefone inválido.';
        } elseif ($phone !== '') {
            $digits = preg_replace('/\D/', '', $phone);
            if (mb_strlen($phone) > 20 || !in_array(strlen($digits), [10, 11], true)) {
                $errors['phone'] = 'Digite o telefone com DDD. Exemplo: (77) 99999-9999';
            }
        }

        return $errors;
    }

    /** @return array<string,string> */
    public function password(array $input, bool $hasPassword): array
    {
        $errors = [];

        $current = is_string($input['current_password'] ?? null) ? $input['current_password'] : '';
        $new     = is_string($input['new_password'] ?? null) ? $input['new_password'] : '';
        $confirm = is_string($input['confirm_password'] ?? null) ? $input['confirm_password'] : '';

        if ($hasPassword && $current === '') {
            $errors['current_password'] = 'Digite sua senha atual.';
        }

        if (str_contains($new, "\0")) {
            $errors['new_password'] = 'Informe uma senha válida.';
        } elseif (strlen($new) < 8) {
            $errors['new_password'] = 'A nova senha precisa ter pelo menos 8 caracteres.';
        } elseif (strlen($new) > 72) {
            $errors['new_password'] = 'A nova senha deve ter no máximo 72 caracteres.';
        } elseif (!preg_match('/[A-Za-z]/', $new) || !preg_match('/\d/', $new)) {
            $errors['new_password'] = 'Use letras e números na nova senha.';
        } elseif ($hasPassword && $new === $current) {
            $errors['new_password'] = 'A nova senha deve ser diferente da atual.';
        }

        if ($confirm !== $new) {
            $errors['confirm_password'] = 'As senhas não são iguais.';
        }

        return $errors;
    }

    /** @return array<string,string> */
    public function support(array $input): array
    {
        $errors = [];

        $name = $input['name'] ?? '';
        if (!is_string($name) || mb_strlen(trim($name)) < 3) {
            $errors['name'] = 'Digite seu nome completo.';
        } elseif (mb_strlen($name) > 100) {
            $errors['name'] = 'O nome deve ter no máximo 100 caracteres.';
        }

        $email = $input['email'] ?? '';
        if (
            !is_string($email) || mb_strlen($email) > 150
            || !filter_var($email, FILTER_VALIDATE_EMAIL)
        ) {
            $errors['email'] = 'Informe um e-mail válido. Exemplo: nome@email.com';
        }

        $subject = $input['subject'] ?? '';
        if (!is_string($subject) || !array_key_exists($subject, SupportMessage::SUBJECTS)) {
            $errors['subject'] = 'Escolha um assunto.';
        }

        $message = $input['message'] ?? '';
        if (!is_string($message) || mb_strlen(trim($message)) < 10) {
            $errors['message'] = 'Escreva sua mensagem (pelo menos 10 letras).';
        } elseif (mb_strlen($message) > 2000) {
            $errors['message'] = 'A mensagem deve ter no máximo 2000 caracteres.';
        }

        return $errors;
    }
}
