<?php

namespace App\Games\Enums;

enum GameType: string
{
    case CATCH_THE_CHARACTER = 'catch_the_character';
    case MEMORY_HANZI = 'memory_hanzi';
    case FLASHCARDS = 'flashcards';
    case STROKE_ORDER = 'stroke_order';
    case HANZI_WRITING = 'hanzi_writing';
    case LISTEN_AND_SELECT = 'listen_and_select';
    case MATCH_HANZI = 'match_hanzi';
    case QUIZ_WRITING = 'quiz_writing';

    public function label(): string
    {
        return match ($this) {
            self::CATCH_THE_CHARACTER => 'Catch the Character',
            self::MEMORY_HANZI => 'Memory Hanzi',
            self::FLASHCARDS => 'Flashcards',
            self::STROKE_ORDER => 'Stroke Order',
            self::HANZI_WRITING => 'Hanzi Writing',
            self::LISTEN_AND_SELECT => 'Listen and Select',
            self::MATCH_HANZI => 'Match Hanzi',
            self::QUIZ_WRITING => 'Quiz Writing',
        };
    }
}
