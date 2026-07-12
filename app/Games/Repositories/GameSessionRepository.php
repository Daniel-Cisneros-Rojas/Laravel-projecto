<?php

namespace App\Games\Repositories;

use App\Games\DTOs\GameSessionDTO;
use App\Models\GameSession;
use Illuminate\Database\Eloquent\Collection;

class GameSessionRepository
{
    /**
     * Crear nueva sesión
     */
    public function create(GameSessionDTO $dto): GameSession
    {
        return GameSession::create(array_merge(
            $dto->toArray(),
            ['started_at' => now()]
        ));
    }

    /**
     * Actualizar sesión
     */
    public function update(int $id, GameSessionDTO $dto): GameSession
    {
        $session = GameSession::find($id);
        $session->update($dto->toArray());
        return $session;
    }

    /**
     * Obtener sesión por ID
     */
    public function getById(int $id): ?GameSession
    {
        return GameSession::find($id);
    }

    /**
     * Obtener todas las sesiones de un tema
     */
    public function getByTheme(int $themeId): Collection
    {
        return GameSession::where('theme_id', $themeId)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Obtener últimas sesiones de un juego
     */
    public function getLatestByGameType(string $gameType, int $limit = 10): Collection
    {
        return GameSession::where('game_type', $gameType)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtener estadísticas de un tema
     */
    public function getThemeStats(int $themeId): array
    {
        $sessions = GameSession::where('theme_id', $themeId)->get();

        return [
            'total_sessions' => $sessions->count(),
            'average_score' => $sessions->avg('score'),
            'average_accuracy' => $sessions->avg('accuracy'),
            'best_score' => $sessions->max('score'),
            'total_playtime' => $sessions->sum('duration'),
        ];
    }

    /**
     * Obtener estadísticas de un juego
     */
    public function getGameStats(string $gameType): array
    {
        $sessions = GameSession::where('game_type', $gameType)->get();

        return [
            'total_sessions' => $sessions->count(),
            'average_score' => $sessions->avg('score'),
            'average_accuracy' => $sessions->avg('accuracy'),
            'best_score' => $sessions->max('score'),
        ];
    }

    /**
     * Finalizar sesión
     */
    public function finish(int $id, int $duration): GameSession
    {
        $session = GameSession::find($id);
        $session->update([
            'duration' => $duration,
            'ended_at' => now(),
        ]);
        return $session;
    }
}
