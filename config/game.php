<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Game Configuration
    |--------------------------------------------------------------------------
    |
    | Configuración centralizada para los minijuegos educativos
    |
    */

    'games' => [
        'catch_the_character' => [
            'name' => 'Catch the Character',
            'slug' => 'catch-the-character',
            'description' => 'Aprende identificando Hanzi',
            'icon' => '🎮',
        ],
    ],

    'levels' => [
        1 => [
            'name' => 'Principiante',
            'characters_per_round' => 5,
            'fall_speed' => 3.0,           // segundos
            'distractors_ratio' => 0.2,    // 20% distractores
            'duration' => 60,              // segundos
            'min_characters_on_screen' => 3,
        ],
        2 => [
            'name' => 'Principiante +',
            'characters_per_round' => 8,
            'fall_speed' => 2.5,
            'distractors_ratio' => 0.3,
            'duration' => 75,
            'min_characters_on_screen' => 4,
        ],
        3 => [
            'name' => 'Intermedio',
            'characters_per_round' => 12,
            'fall_speed' => 2.0,
            'distractors_ratio' => 0.4,
            'duration' => 90,
            'min_characters_on_screen' => 5,
        ],
        4 => [
            'name' => 'Intermedio +',
            'characters_per_round' => 16,
            'fall_speed' => 1.5,
            'distractors_ratio' => 0.5,
            'duration' => 105,
            'min_characters_on_screen' => 6,
        ],
        5 => [
            'name' => 'Avanzado',
            'characters_per_round' => 20,
            'fall_speed' => 1.0,
            'distractors_ratio' => 0.6,
            'duration' => 120,
            'min_characters_on_screen' => 8,
        ],
    ],

    'scoring' => [
        'correct_hit' => 10,
        'incorrect_click' => -5,
        'missed_character' => -10,
    ],

    'ui' => [
        'primary_color' => '#8b5cf6',
        'success_color' => '#10b981',
        'error_color' => '#ef4444',
        'warning_color' => '#f59e0b',
    ],
];
