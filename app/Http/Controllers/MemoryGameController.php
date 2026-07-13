<?php

namespace App\Http\Controllers;

use App\Games\Services\MemoryGameService;
use App\Games\Repositories\ThemeRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class MemoryGameController extends Controller
{
    public function __construct(
        private MemoryGameService $memoryGameService,
        private ThemeRepository $themeRepository,
    ) {}

    /**
     * Mostrar pantalla del juego de memoria
     */
    public function show(int $themeId, Request $request): View
    {
        $theme = $this->themeRepository->getById($themeId);
        if (!$theme) {
            abort(404, 'Tema no encontrado');
        }

        $level = (int) $request->input('level', 1);
        $mode = $request->input('mode', config('game.memory_game.default_mode'));
        $gameData = $this->memoryGameService->initializeGame($themeId, $mode, $level);

        return view('memory-game.game', [
            'theme' => $theme,
            'gameData' => $gameData,
        ]);
    }

    /**
     * API: Generar pares para el memorama
     */
    public function generatePairs(Request $request, int $themeId): JsonResponse
    {
        $level = (int) $request->input('level', 1);
        $mode = $request->input('mode', config('game.memory_game.default_mode'));
        $gameData = $this->memoryGameService->initializeGame($themeId, $mode, $level);
        $pairs = $this->memoryGameService->generateMemoryPairs(
            $themeId,
            $level,
            $gameData['pairs_count'],
            $mode
        );

        return response()->json([
            'success' => true,
            'pairs' => $pairs,
            'pairs_count' => count($pairs) / 2,
            'theme_id' => $themeId,
            'mode' => $mode,
        ]);
    }

    /**
     * API: Registrar acierto en memoria
     */
    public function recordMatch(Request $request): JsonResponse
    {
        $data = $request->validate([
            'theme_id' => 'required|integer',
            'current_score' => 'required|integer',
            'current_hits' => 'required|integer',
            'current_mistakes' => 'required|integer',
            'current_level' => 'required|integer',
        ]);

        $result = $this->memoryGameService->recordMatch(
            $data['theme_id'],
            $data['current_score'],
            $data['current_hits'],
            $data['current_mistakes'],
            $data['current_level']
        );

        return response()->json($result);
    }

    /**
     * API: Registrar error en memoria
     */
    public function recordMismatch(Request $request): JsonResponse
    {
        $data = $request->validate([
            'theme_id' => 'required|integer',
            'current_score' => 'required|integer',
            'current_hits' => 'required|integer',
            'current_mistakes' => 'required|integer',
            'current_level' => 'required|integer',
        ]);

        $result = $this->memoryGameService->recordMismatch(
            $data['theme_id'],
            $data['current_score'],
            $data['current_hits'],
            $data['current_mistakes'],
            $data['current_level']
        );

        return response()->json($result);
    }

    /**
     * API: Registrar éxito en dibujo
     */
    public function recordDrawing(Request $request): JsonResponse
    {
        $data = $request->validate([
            'theme_id' => 'required|integer',
            'current_score' => 'required|integer',
            'current_hits' => 'required|integer',
            'current_mistakes' => 'required|integer',
            'current_level' => 'required|integer',
            'drawing_mistakes' => 'required|integer',
        ]);

        $result = $this->memoryGameService->recordDrawingSuccess(
            $data['theme_id'],
            $data['current_score'],
            $data['current_hits'],
            $data['current_mistakes'],
            $data['current_level'],
            $data['drawing_mistakes']
        );

        return response()->json($result);
    }

    /**
     * API: Finalizar juego
     */
    public function endGame(Request $request): JsonResponse
    {
        $data = $request->validate([
            'theme_id' => 'required|integer',
            'score' => 'required|integer',
            'hits' => 'required|integer',
            'mistakes' => 'required|integer',
            'duration' => 'required|integer',
            'level' => 'required|integer',
        ]);

        $this->memoryGameService->saveGameSession(
            $data['theme_id'],
            $data['score'],
            $data['hits'],
            $data['mistakes'],
            $data['duration'],
            $data['level']
        );

        return response()->json([
            'success' => true,
            'message' => 'Partida guardada correctamente',
        ]);
    }
}
