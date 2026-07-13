<?php

namespace App\Http\Controllers;

use App\Games\Repositories\ThemeRepository;
use App\Games\Repositories\GameSessionRepository;
use Illuminate\View\View;

class GameController extends Controller
{
    public function __construct(
        private ThemeRepository $themeRepository,
        private GameSessionRepository $gameSessionRepository,
    ) {}

    /**
     * Mostrar menú principal con todos los juegos disponibles
     */
    public function index(): View
    {
        $themes = $this->themeRepository->getAll();

        return view('games.index', [
            'themes' => $themes,
        ]);
    }

    /**
     * Mostrar selección de temas para un juego
     */
    public function selectTheme(string $gameSlug): View
    {
        $games = config('game.games');
        $game = collect($games)->firstWhere('slug', $gameSlug);

        if (!$game) {
            abort(404, 'Juego no encontrado');
        }

        $themes = $this->themeRepository->getAll();

        return view('games.theme-selection', [
            'game' => $game,
            'gameSlug' => $gameSlug,
            'themes' => $themes,
        ]);
    }

    /**
     * Mostrar estadísticas generales
     */
    public function stats(): View
    {
        $catchTheCharacterStats = $this->gameSessionRepository->getGameStats('catch_the_character');
        $memoryGameStats = $this->gameSessionRepository->getGameStats('memory_hanzi');

        return view('games.stats', [
            'catchTheCharacterStats' => $catchTheCharacterStats,
            'memoryGameStats' => $memoryGameStats,
        ]);
    }

    /**
     * Mostrar estadísticas de un tema
     */
    public function themeStats(int $themeId): View
    {
        $theme = $this->themeRepository->getById($themeId);

        if (!$theme) {
            abort(404, 'Tema no encontrado');
        }

        $stats = $this->gameSessionRepository->getThemeStats($themeId);

        return view('games.theme-stats', [
            'theme' => $theme,
            'stats' => $stats,
        ]);
    }
}
