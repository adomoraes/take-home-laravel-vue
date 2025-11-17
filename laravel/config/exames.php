<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Opções para a criação e validação de Exames
    |--------------------------------------------------------------------------
    |
    | Aqui ficam os valores permitidos para campos específicos da entidade Exame.
    | Centralizar estes valores facilita a manutenção e evita que fiquem
    | espalhados pela aplicação (ex: em Form Requests).
    |
    */

    'grupos' => [
        'Individual',
        'Grupo 1',
        'Grupo 2',
        'Grupo 3',
        'Grupo 4',
        'Grupo 5',
    ],

    'lateralidades' => [
        'OD', // Olho Direito
        'OE', // Olho Esquerdo
        'AO', // Ambos os Olhos
    ],
];
