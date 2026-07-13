<?php

namespace App\Http\Controllers;

use App\Games\Services\StrokeOrderService;
use App\Games\Repositories\ThemeRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class StrokeOrderController extends Controller
{
    public function __construct(
        private StrokeOrderService $strokeOrderService,
        private ThemeRepository $themeRepository,
    ) {}

    /**
     * Mostrar pantalla del juego de orden de trazos
     */
    public function show(int $themeId, Request $request): View
    {
        $theme = $this->themeRepository->getById($themeId);
        if (!$theme) {
            abort(404, 'Tema no encontrado');
        }

        $level = (int) $request->input('level', 1);
        $gameData = $this->strokeOrderService->initializeGame($themeId, $level);

        return view('stroke-order.game', [
            'theme' => $theme,
            'gameData' => $gameData,
        ]);
    }

    /**
     * API: Obtener carácter aleatorio para ordenar trazos
     */
    public function getCharacter(Request $request, int $themeId): JsonResponse
    {
        $level = (int) $request->input('level', 1);
        $character = $this->strokeOrderService->getRandomCharacter($themeId, $level);

        if (!$character) {
            return response()->json([
                'success' => false,
                'message' => 'No hay caracteres disponibles para este tema',
            ]);
        }

        return response()->json([
            'success' => true,
            'character' => $character,
        ]);
    }

    /**
     * API: Registrar intento de ordenamiento
     */
    public function recordAttempt(Request $request): JsonResponse
    {
        $data = $request->validate([
            'theme_id' => 'required|integer',
            'character_id' => 'required|integer',
            'current_score' => 'required|integer',
            'correct' => 'required|boolean',
            'current_attempts' => 'required|integer',
        ]);

        $result = $this->strokeOrderService->recordAttempt(
            $data['theme_id'],
            $data['character_id'],
            $data['current_score'],
            $data['correct'],
            $data['current_attempts'],
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

        $this->strokeOrderService->saveGameSession(
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
