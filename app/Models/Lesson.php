<?php

namespace App\Models;

class Lesson
{
    public static function all(): array
    {
        return [
            ['introducao-ao-celular', 'Introdução ao celular', 'play-btn'],
            ['conhecendo-a-tela', 'Conhecendo a tela', 'phone'],
            ['fazendo-uma-ligacao', 'Fazendo uma ligação', 'telephone'],
            ['salvando-contatos', 'Salvando contatos', 'people'],
            ['revisao-da-aula', 'Revisão da aula', 'check-circle'],
        ];
    }

    public static function count(): int
    {
        return count(self::all());
    }
}
