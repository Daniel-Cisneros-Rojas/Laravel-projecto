<?php

namespace App\Games\Services;

use App\Games\Repositories\CharacterRepository;
use App\Games\Repositories\ThemeRepository;
use App\Games\Repositories\GameSessionRepository;

class MemoryGameService
{
    public function __construct(
        private CharacterRepository $characterRepository,
        private ThemeRepository $themeRepository,
        private GameSessionRepository $gameSessionRepository,
        private LevelConfigService $levelConfigService,
    ) {}

    /**
     * Inicializar datos para una partida de Memorama
     */
    public function initializeGame(int $themeId, int $level = 1): array
    {
        $config = $this->levelConfigService->getConfig($level);
        $pairsCount = min(6, $config['characters_per_round']);

        return [
            'theme_id' => $themeId,
            'level' => $level,
            'duration' => 300, // 5 minutos para memoria
            'pairs_count' => $pairsCount,
            'level_name' => $this->levelConfigService->getLevelName($level),
        ];
    }

    /**
     * Generar pares de caracteres para el memorama
     */
    public function generateMemoryPairs(int $themeId, int $level, int $pairsCount): array
    {
        $characters = $this->characterRepository->getRandomByThemeAndMaxLevel(
            $themeId,
            $level,
            $pairsCount
        );

        $pairs = [];
        $id = 0;

        foreach ($characters as $character) {
            // Tarjeta con el carácter
            $pairs[] = [
                'id' => $id++,
                'type' => 'character',
                'value' => $character->hanzi,
                'pinyin' => $character->pinyin,
                'character_id' => $character->id,
                'pair_id' => (int)($id / 2),
            ];

            // Tarjeta con el pinyin
            $pairs[] = [
                'id' => $id++,
                'type' => 'pinyin',
                'value' => $character->pinyin,
                'hanzi' => $character->hanzi,
                'character_id' => $character->id,
                'pair_id' => (int)(($id - 1) / 2),
            ];
        }

        // Mezclar pares aleatoriamente
        shuffle($pairs);

        return $pairs;
    }

    /**
     * Registrar acierto en memoria
     */
    public function recordMatch(
        int $themeId,
        int $score,
        int $hits,
        int $mistakes,
        int $level
    ): array {
        $newScore = $score + 50;

        return [
            'success' => true,
            'new_score' => $newScore,
            'new_hits' => $hits + 1,
            'new_mistakes' => $mistakes,
        ];
    }

    /**
     * Registrar error en memoria
     */
    public function recordMismatch(
        int $themeId,
        int $score,
        int $hits,
        int $mistakes,
        int $level
    ): array {
        return [
            'success' => true,
            'new_score' => $score,
            'new_hits' => $hits,
            'new_mistakes' => $mistakes + 1,
        ];
    }

    /**
     * Registrar éxito en dibujo
     */
    public function recordDrawingSuccess(
        int $themeId,
        int $score,
        int $hits,
        int $mistakes,
        int $level,
        int $drawingMistakes = 0
    ): array {
        $bonusPoints = $drawingMistakes === 0 ? 100 : 50;
        $newScore = $score + $bonusPoints;

        return [
            'success' => true,
            'new_score' => $newScore,
            'new_hits' => $hits + 1,
            'new_mistakes' => $mistakes,
            'bonus' => $bonusPoints,
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
        $accuracy = $hits > 0 ? ($hits / ($hits + $mistakes)) * 100 : 0;

        $this->gameSessionRepository->create([
            'theme_id' => $themeId,
            'game_type' => 'memory',
            'score' => $score,
            'hits' => $hits,
            'mistakes' => $mistakes,
            'accuracy' => $accuracy,
            'duration' => $duration,
            'level' => $level,
        ]);
    }
}
