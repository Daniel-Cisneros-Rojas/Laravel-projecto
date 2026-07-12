<?php

namespace App\Games\Services;

use App\Games\DTOs\GameSessionDTO;
use App\Games\Repositories\CharacterRepository;

class CatchTheCharacterService
{
    public function __construct(
        private GameService $gameService,
        private LevelConfigService $levelConfigService,
        private CharacterRepository $characterRepository,
    ) {}

    /**
     * Generar datos iniciales para una partida
     */
    public function initializeGame(int $themeId, int $level = 1): array
    {
        $config = $this->levelConfigService->getConfig($level);

        return [
            'theme_id' => $themeId,
            'level' => $level,
            'duration' => $config['duration'],
            'fall_speed' => $config['fall_speed'],
            'characters_per_round' => $config['characters_per_round'],
            'min_characters_on_screen' => $config['min_characters_on_screen'],
            'level_name' => $this->levelConfigService->getLevelName($level),
        ];
    }

    /**
     * Generar primera tanda de caracteres
     */
    public function generateFirstRound(int $themeId, int $level): array
    {
        return $this->gameService->generateCharacters($themeId, $level);
    }

    /**
     * Procesar clic en un carácter
     */
    public function processCharacterClick(
        GameSessionDTO $session,
        int $characterId,
        int $themeId,
        bool $isCorrect
    ): GameSessionDTO {
        // Validar que el carácter pertenece al tema
        if (!$this->characterRepository->belongsToTheme($characterId, $themeId)) {
            return $session;
        }

        if ($isCorrect) {
            $session = $this->gameService->recordHit($session);
        } else {
            $session = $this->gameService->recordMistake($session);
        }

        return $session;
    }

    /**
     * Procesar carácter que llegó al fondo sin ser seleccionado
     */
    public function processCharacterMissed(GameSessionDTO $session): GameSessionDTO
    {
        return $this->gameService->recordMissedCharacter($session);
    }

    /**
     * Determinar si se debe cambiar de nivel
     */
    public function shouldLevelUp(GameSessionDTO $session, int $currentLevel): bool
    {
        $accuracy = $session->accuracy;
        $hits = $session->hits;

        $maxLevel = $this->levelConfigService->getMaxLevel();

        if ($currentLevel >= $maxLevel) {
            return false;
        }

        // Cambiar de nivel si tiene 80%+ de precisión y mínimo 20 aciertos
        return $accuracy >= 80 && $hits >= 20;
    }

    /**
     * Procesar fin de partida
     */
    public function endGameRound(GameSessionDTO $session, int $elapsedTime): array
    {
        $finalSession = $this->gameService->endGame($session, $elapsedTime);

        return [
            'session' => $finalSession,
            'score' => $finalSession->score,
            'hits' => $finalSession->hits,
            'mistakes' => $finalSession->mistakes,
            'accuracy' => $finalSession->accuracy,
            'duration' => $finalSession->duration,
            'level' => $finalSession->level_reached,
        ];
    }

    /**
     * Obtener puntos del juego
     */
    public function getGamePoints(): array
    {
        return [
            'correct_hit' => $this->levelConfigService->getPointsForHit(),
            'incorrect_click' => $this->levelConfigService->getPenaltyForMistake(),
            'missed_character' => $this->levelConfigService->getPenaltyForMissed(),
        ];
    }
}
