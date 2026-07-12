<?php

namespace App\Games\DTOs;

class GameSessionDTO
{
    public function __construct(
        public int $theme_id,
        public string $game_type,
        public int $score = 0,
        public int $hits = 0,
        public int $mistakes = 0,
        public float $accuracy = 0.0,
        public int $duration = 0,
        public int $level_reached = 1,
    ) {}

    /**
     * Crear DTO desde un modelo
     */
    public static function fromModel(\App\Models\GameSession $session): self
    {
        return new self(
            theme_id: $session->theme_id,
            game_type: $session->game_type,
            score: $session->score,
            hits: $session->hits,
            mistakes: $session->mistakes,
            accuracy: $session->accuracy,
            duration: $session->duration,
            level_reached: $session->level_reached,
        );
    }

    /**
     * Convertir a array para guardar en BD
     */
    public function toArray(): array
    {
        return [
            'theme_id' => $this->theme_id,
            'game_type' => $this->game_type,
            'score' => $this->score,
            'hits' => $this->hits,
            'mistakes' => $this->mistakes,
            'accuracy' => $this->accuracy,
            'duration' => $this->duration,
            'level_reached' => $this->level_reached,
        ];
    }
}
