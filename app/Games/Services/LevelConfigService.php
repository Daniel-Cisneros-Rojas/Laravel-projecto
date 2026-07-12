<?php

namespace App\Games\Services;

class LevelConfigService
{
    /**
     * Obtener configuración de un nivel
     */
    public function getConfig(int $level): array
    {
        $levels = config('game.levels');

        if (!isset($levels[$level])) {
            return $levels[5]; // Retorna última configuración si no existe
        }

        return $levels[$level];
    }

    /**
     * Obtener velocidad de caída para un nivel
     */
    public function getFallSpeed(int $level): float
    {
        return $this->getConfig($level)['fall_speed'];
    }

    /**
     * Obtener cantidad de caracteres por ronda
     */
    public function getCharactersPerRound(int $level): int
    {
        return $this->getConfig($level)['characters_per_round'];
    }

    /**
     * Obtener ratio de distractores
     */
    public function getDistractorsRatio(int $level): float
    {
        return $this->getConfig($level)['distractors_ratio'];
    }

    /**
     * Obtener duración en segundos
     */
    public function getDuration(int $level): int
    {
        return $this->getConfig($level)['duration'];
    }

    /**
     * Obtener mínimo de caracteres en pantalla
     */
    public function getMinCharactersOnScreen(int $level): int
    {
        return $this->getConfig($level)['min_characters_on_screen'];
    }

    /**
     * Obtener nombre del nivel
     */
    public function getLevelName(int $level): string
    {
        return $this->getConfig($level)['name'];
    }

    /**
     * Calcular cantidad de distractores necesarios
     */
    public function calculateDistracterCount(int $correctCharacters, int $level): int
    {
        $ratio = $this->getDistractorsRatio($level);
        return max(1, (int)($correctCharacters * $ratio));
    }

    /**
     * Validar si el nivel es válido
     */
    public function isValidLevel(int $level): bool
    {
        $levels = config('game.levels');
        return isset($levels[$level]);
    }

    /**
     * Obtener máximo nivel configurado
     */
    public function getMaxLevel(): int
    {
        return max(array_keys(config('game.levels')));
    }

    /**
     * Obtener puntos por acierto
     */
    public function getPointsForHit(): int
    {
        return config('game.scoring.correct_hit', 10);
    }

    /**
     * Obtener penalización por error
     */
    public function getPenaltyForMistake(): int
    {
        return config('game.scoring.incorrect_click', -5);
    }

    /**
     * Obtener penalización por carácter no capturado
     */
    public function getPenaltyForMissed(): int
    {
        return config('game.scoring.missed_character', -10);
    }
}
