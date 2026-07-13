<?php

namespace App\Games\Services;

use App\Games\DTOs\GameSessionDTO;
use App\Games\Repositories\CharacterRepository;
use App\Games\Repositories\ThemeRepository;
use App\Games\Repositories\GameSessionRepository;

class MemoryGameService
{
    private const GAME_TYPE = 'memory_hanzi';

    public function __construct(
        private CharacterRepository $characterRepository,
        private ThemeRepository $themeRepository,
        private GameSessionRepository $gameSessionRepository,
        private LevelConfigService $levelConfigService,
    ) {}

    /**
     * Inicializar datos para una partida de Memorama
     */
    public function initializeGame(int $themeId, string $mode = 'hanzi-pinyin', int $level = 1): array
    {
        $config = $this->levelConfigService->getConfig($level);
        $memoryConfig = config('game.memory_game');

        $pairsCount = min(
            $memoryConfig['max_pairs'],
            max(2, (int) floor($config['characters_per_round'] * 0.6))
        );

        return [
            'theme_id' => $themeId,
            'level' => $level,
            'mode' => $this->resolveMode($mode),
            'duration' => $memoryConfig['duration'],
            'pairs_count' => $pairsCount,
            'level_name' => $this->levelConfigService->getLevelName($level),
            'match_score' => $memoryConfig['match_score'],
            'drawing_bonus_perfect' => $memoryConfig['drawing_bonus_perfect'],
            'drawing_bonus_good' => $memoryConfig['drawing_bonus_good'],
        ];
    }

    /**
     * Generar pares de caracteres para el memorama
     */
    public function generateMemoryPairs(int $themeId, int $level, int $pairsCount, string $mode = 'hanzi-pinyin'): array
    {
        $characters = $this->characterRepository->getRandomByThemeAndMaxLevel(
            $themeId,
            $level,
            $pairsCount
        );

        $pairs = [];
        $id = 0;
        $pairId = 0;

        foreach ($characters as $character) {
            $pairs[] = $this->buildCard(
                id: $id++,
                pairId: $pairId,
                type: 'character',
                value: $character->hanzi,
                display: $character->hanzi,
                meta: ['pinyin' => $character->pinyin, 'meaning' => $character->meaning],
            );

            $pairs[] = $this->buildSecondCard(
                id: $id++,
                pairId: $pairId,
                character: $character,
                mode: $mode,
            );

            $pairId++;
        }

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
        $matchScore = (int) config('game.memory_game.match_score', 50);

        return [
            'success' => true,
            'new_score' => $score + $matchScore,
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
        $perfect = (int) config('game.memory_game.drawing_bonus_perfect', 100);
        $good = (int) config('game.memory_game.drawing_bonus_good', 50);

        $bonusPoints = $drawingMistakes === 0 ? $perfect : $good;
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

    /**
     * Resolver y validar el modo de juego
     */
    private function resolveMode(string $mode): string
    {
        $validModes = array_keys(config('game.memory_game.modes', []));

        return in_array($mode, $validModes) ? $mode : config('game.memory_game.default_mode');
    }

    /**
     * Construir tarjeta base
     */
    private function buildCard(
        int $id,
        int $pairId,
        string $type,
        string $value,
        string $display,
        array $meta = [],
    ): array {
        return array_merge([
            'id' => $id,
            'type' => $type,
            'value' => $value,
            'display' => $display,
            'pair_id' => $pairId,
        ], $meta);
    }

    /**
     * Construir la segunda tarjeta de la pareja según el modo
     */
    private function buildSecondCard(
        int $id,
        int $pairId,
        object $character,
        string $mode,
    ): array {
        return match ($mode) {
            'hanzi-meaning' => $this->buildCard(
                id: $id,
                pairId: $pairId,
                type: 'meaning',
                value: $character->meaning,
                display: $character->meaning,
                meta: ['hanzi' => $character->hanzi, 'pinyin' => $character->pinyin],
            ),
            default => $this->buildCard(
                id: $id,
                pairId: $pairId,
                type: 'pinyin',
                value: $character->pinyin,
                display: $character->pinyin,
                meta: ['hanzi' => $character->hanzi, 'meaning' => $character->meaning],
            ),
        };
    }
}
