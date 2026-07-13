<?php

namespace App\Games\Services;

use App\Games\DTOs\GameSessionDTO;
use App\Games\Repositories\CharacterRepository;
use App\Games\Repositories\GameSessionRepository;

class StrokeOrderService
{
    private const GAME_TYPE = 'stroke_order';

    public function __construct(
        private CharacterRepository $characterRepository,
        private GameSessionRepository $gameSessionRepository,
    ) {}

    /**
     * Inicializar datos para una partida de Orden de Trazos
     */
    public function initializeGame(int $themeId, int $level = 1): array
    {
        $config = config('game.stroke_order');

        return [
            'theme_id' => $themeId,
            'level' => $level,
            'duration' => $config['duration'],
            'correct_score' => $config['correct_score'],
            'max_attempts' => $config['max_attempts'],
            'level_name' => $this->getLevelName($level),
        ];
    }

    /**
     * Obtener un carácter aleatorio del tema
     */
    public function getRandomCharacter(int $themeId, int $level = 1): ?array
    {
        $characters = $this->characterRepository->getRandomByThemeAndMaxLevel(
            $themeId,
            $level,
            1
        );

        if ($characters->isEmpty()) {
            return null;
        }

        $character = $characters->first();

        return [
            'id' => $character->id,
            'hanzi' => $character->hanzi,
            'pinyin' => $character->pinyin,
            'meaning' => $character->meaning,
        ];
    }

    /**
     * Registrar intento de ordenamiento
     */
    public function recordAttempt(
        int $themeId,
        int $characterId,
        int $score,
        bool $correct,
        int $attempts,
    ): array {
        $config = config('game.stroke_order');

        return [
            'success' => true,
            'correct' => $correct,
            'new_score' => $correct
                ? $score + $config['correct_score']
                : max(0, $score - $config['attempt_penalty']),
            'new_attempts' => $attempts + 1,
        ];
    }

    /**
     * Guardar sesión de juego
     */
    public function saveGameSession(
        int $themeId,
        int $score,
        int $hits,
        int $mistakes,
        int $duration,
        int $level
    ): void {
        $accuracy = $hits + $mistakes > 0
            ? round(($hits / ($hits + $mistakes)) * 100, 2)
            : 0.0;

        $dto = new GameSessionDTO(
            theme_id: $themeId,
            game_type: self::GAME_TYPE,
            score: $score,
            hits: $hits,
            mistakes: $mistakes,
            accuracy: $accuracy,
            duration: $duration,
            level_reached: $level,
        );

        $this->gameSessionRepository->create($dto);
    }

    private function getLevelName(int $level): string
    {
        return match (true) {
            $level <= 2 => 'Principiante',
            $level <= 4 => 'Intermedio',
            default => 'Avanzado',
        };
    }
}
