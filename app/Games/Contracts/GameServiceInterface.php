<?php

namespace App\Games\Contracts;

use App\Games\DTOs\CharacterDTO;
use App\Games\DTOs\GameSessionDTO;
use Illuminate\Database\Eloquent\Collection;

/**
 * Interface para servicios de juegos.
 * Permite implementar diferentes tipos de juegos de forma extensible.
 */
interface GameServiceInterface
{
    /**
     * Iniciar una nueva sesión de juego
     */
    public function startGame(int $themeId, int $level = 1): GameSessionDTO;

    /**
     * Generar caracteres para una ronda
     */
    public function generateCharacters(int $themeId, int $level): array;

    /**
     * Registrar un acierto
     */
    public function recordHit(GameSessionDTO $session): GameSessionDTO;

    /**
     * Registrar un error
     */
    public function recordMistake(GameSessionDTO $session): GameSessionDTO;

    /**
     * Registrar carácter no capturado
     */
    public function recordMissedCharacter(GameSessionDTO $session): GameSessionDTO;

    /**
     * Calcular puntuación
     */
    public function calculateScore(int $hits, int $mistakes): int;

    /**
     * Calcular precisión
     */
    public function calculateAccuracy(int $hits, int $mistakes): float;

    /**
     * Finalizar sesión de juego
     */
    public function endGame(GameSessionDTO $session, int $duration): GameSessionDTO;

    /**
     * Guardar sesión en base de datos
     */
    public function saveGameSession(GameSessionDTO $session): \App\Models\GameSession;

    /**
     * Validar si el carácter pertenece al tema
     */
    public function validateCharacter(CharacterDTO $character, int $themeId): bool;
}
