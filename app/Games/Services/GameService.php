<?php

namespace App\Games\Services;

use App\Games\Contracts\GameServiceInterface;
use App\Games\DTOs\CharacterDTO;
use App\Games\DTOs\GameSessionDTO;
use App\Games\Repositories\CharacterRepository;
use App\Games\Repositories\GameSessionRepository;
use App\Games\Repositories\ThemeRepository;
use App\Models\GameSession;

class GameService implements GameServiceInterface
{
    public function __construct(
        protected CharacterRepository $characterRepository,
        protected GameSessionRepository $gameSessionRepository,
        protected ThemeRepository $themeRepository,
        protected LevelConfigService $levelConfigService,
    ) {}

    /**
     * Iniciar una nueva sesión de juego
     */
    public function startGame(int $themeId, int $level = 1): GameSessionDTO
    {
        $sessionDTO = new GameSessionDTO(
            theme_id: $themeId,
            game_type: 'catch_the_character',
            level_reached: $level,
        );

        return $sessionDTO;
    }

    /**
     * Generar caracteres para una ronda
     */
    public function generateCharacters(int $themeId, int $level): array
    {
        $config = $this->levelConfigService->getConfig($level);
        $correctCount = $config['characters_per_round'];
        $distractorCount = $this->levelConfigService->calculateDistracterCount($correctCount, $level);

        // Obtener caracteres correctos del tema
        $correctCharacters = $this->characterRepository
            ->getRandomByThemeAndMaxLevel($themeId, $level, $correctCount);

        // Obtener distractores de otros temas
        $distractors = $this->generateDistracters($themeId, $distractorCount);

        $characters = [];

        // Agregar caracteres correctos
        foreach ($correctCharacters as $character) {
            $characters[] = [
                'id' => $character->id,
                'hanzi' => $character->hanzi,
                'pinyin' => $character->pinyin,
                'meaning' => $character->meaning,
                'is_correct' => true,
            ];
        }

        // Agregar distractores
        foreach ($distractors as $distractor) {
            $characters[] = [
                'id' => $distractor->id,
                'hanzi' => $distractor->hanzi,
                'pinyin' => $distractor->pinyin,
                'meaning' => $distractor->meaning,
                'is_correct' => false,
            ];
        }

        // Mezclar
        shuffle($characters);

        return $characters;
    }

    /**
     * Registrar un acierto
     */
    public function recordHit(GameSessionDTO $session): GameSessionDTO
    {
        $session->hits++;
        $session->score += $this->levelConfigService->getPointsForHit();
        $session->accuracy = $this->calculateAccuracy($session->hits, $session->mistakes);

        return $session;
    }

    /**
     * Registrar un error
     */
    public function recordMistake(GameSessionDTO $session): GameSessionDTO
    {
        $session->mistakes++;
        $session->score += $this->levelConfigService->getPenaltyForMistake();
        $session->accuracy = $this->calculateAccuracy($session->hits, $session->mistakes);

        return $session;
    }

    /**
     * Registrar carácter no capturado
     */
    public function recordMissedCharacter(GameSessionDTO $session): GameSessionDTO
    {
        $session->mistakes++;
        $session->score += $this->levelConfigService->getPenaltyForMissed();
        $session->accuracy = $this->calculateAccuracy($session->hits, $session->mistakes);

        return $session;
    }

    /**
     * Calcular puntuación
     */
    public function calculateScore(int $hits, int $mistakes): int
    {
        $score = ($hits * $this->levelConfigService->getPointsForHit());
        $score += ($mistakes * $this->levelConfigService->getPenaltyForMistake());

        return max(0, $score);
    }

    /**
     * Calcular precisión
     */
    public function calculateAccuracy(int $hits, int $mistakes): float
    {
        if ($hits + $mistakes === 0) {
            return 0.0;
        }

        return round(($hits / ($hits + $mistakes)) * 100, 2);
    }

    /**
     * Finalizar sesión de juego
     */
    public function endGame(GameSessionDTO $session, int $duration): GameSessionDTO
    {
        $session->duration = $duration;
        $session->accuracy = $this->calculateAccuracy($session->hits, $session->mistakes);

        return $session;
    }

    /**
     * Guardar sesión en base de datos
     */
    public function saveGameSession(GameSessionDTO $session): GameSession
    {
        return $this->gameSessionRepository->create($session);
    }

    /**
     * Validar si el carácter pertenece al tema
     */
    public function validateCharacter(CharacterDTO $character, int $themeId): bool
    {
        return $this->characterRepository->belongsToTheme($character->id, $themeId);
    }

    /**
     * Generar distractores de otros temas
     */
    private function generateDistracters(int $themeId, int $count): array
    {
        $allThemes = $this->themeRepository->getAll();
        $otherThemes = $allThemes->filter(fn($theme) => $theme->id !== $themeId);

        if ($otherThemes->isEmpty()) {
            // Si solo hay un tema, retorna caracteres del mismo
            return $this->characterRepository
                ->getRandomByTheme($themeId, $count)
                ->toArray();
        }

        $distractors = [];
        $attemps = 0;
        $maxAttempts = 50;

        while (count($distractors) < $count && $attemps < $maxAttempts) {
            $randomTheme = $otherThemes->random();
            $randomChar = $this->characterRepository
                ->getRandomByTheme($randomTheme->id, 1)
                ->first();

            if ($randomChar && !in_array($randomChar->id, array_column($distractors, 'id'))) {
                $distractors[] = $randomChar;
            }

            $attemps++;
        }

        return $distractors;
    }
}
