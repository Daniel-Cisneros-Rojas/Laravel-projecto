<?php

namespace App\Http\Controllers;

use App\Games\Services\MemoryGameService;
use App\Games\Repositories\ThemeRepository;
use Illuminate\Http\Request;

class MemoryGameController extends Controller
{
    public function __construct(
        private MemoryGameService $memoryGameService,
        private ThemeRepository $themeRepository,
    ) {}

    /**
     * Mostrar pantalla del juego de memoria
     */
    public function show(int $themeId)
    {
        $theme = $this->themeRepository->getById($themeId);
        if (!$theme) {
            abort(404, 'Tema no encontrado');
        }

        $level = request()->input('level', 1);
        $gameData = $this->memoryGameService->initializeGame($themeId, $level);

        return view('memory-game.game', [
            'theme' => $theme,
            'gameData' => $gameData,
        ]);
    }

    /**
     * API: Generar pares para el memorama
     */
    public function generatePairs(Request $request, int $themeId)
    {
        $level = $request->input('level', 1);
        $gameData = $this->memoryGameService->initializeGame($themeId, $level);
        $pairs = $this->memoryGameService->generateMemoryPairs($themeId, $level, $gameData['pairs_count']);

        return response()->json([
            'success' => true,
            'pairs' => $pairs,
            'pairs_count' => count($pairs) / 2,
            'theme_id' => $themeId,
        ]);
    }

    /**
     * API: Registrar acierto en memoria
     */
    public function recordMatch(Request $request)
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
    public function recordMismatch(Request $request)
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
    public function recordDrawing(Request $request)
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
    public function endGame(Request $request)
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
