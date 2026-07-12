<?php

namespace App\Http\Controllers;

use App\Games\Services\CatchTheCharacterService;
use App\Games\Repositories\ThemeRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class CatchTheCharacterController extends Controller
{
    public function __construct(
        private CatchTheCharacterService $gameService,
        private ThemeRepository $themeRepository,
    ) {}

    /**
     * Mostrar página del juego
     */
    public function show(int $themeId): View
    {
        $theme = $this->themeRepository->getWithCharacters($themeId);

        if (!$theme) {
            abort(404, 'Tema no encontrado');
        }

        $gameData = $this->gameService->initializeGame($themeId, level: 1);
        $gamePoints = $this->gameService->getGamePoints();

        return view('catch-the-character.game', [
            'theme' => $theme,
            'gameData' => $gameData,
            'gamePoints' => $gamePoints,
        ]);
    }

    /**
     * API: Obtener primera tanda de caracteres
     */
    public function getCharacters(Request $request, int $themeId): JsonResponse
    {
        $validated = $request->validate([
            'level' => 'required|integer|min:1|max:5',
        ]);

        $theme = $this->themeRepository->getById($themeId);

        if (!$theme) {
            return response()->json(['error' => 'Tema no encontrado'], 404);
        }

        $characters = $this->gameService->generateFirstRound($themeId, $validated['level']);

        return response()->json([
            'success' => true,
            'characters' => $characters,
            'theme_id' => $themeId,
        ]);
    }

    /**
     * API: Procesar clic en un carácter
     */
    public function processCharacterClick(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'character_id' => 'required|integer',
            'theme_id' => 'required|integer',
            'is_correct' => 'required|boolean',
            'current_score' => 'required|integer',
            'current_hits' => 'required|integer',
            'current_mistakes' => 'required|integer',
            'current_level' => 'required|integer|min:1|max:5',
        ]);

        // Verificar que el tema existe
        $theme = $this->themeRepository->getById($validated['theme_id']);
        if (!$theme) {
            return response()->json(['error' => 'Tema no encontrado'], 404);
        }

        // Crear DTO temporal con estado actual
        $session = new \App\Games\DTOs\GameSessionDTO(
            theme_id: $validated['theme_id'],
            game_type: 'catch_the_character',
            score: $validated['current_score'],
            hits: $validated['current_hits'],
            mistakes: $validated['current_mistakes'],
            level_reached: $validated['current_level'],
        );

        // Procesar el clic
        $updatedSession = $this->gameService->processCharacterClick(
            $session,
            $validated['character_id'],
            $validated['theme_id'],
            $validated['is_correct']
        );

        // Generar nuevos caracteres
        $newCharacters = $this->gameService->generateFirstRound(
            $validated['theme_id'],
            $validated['current_level']
        );

        return response()->json([
            'success' => true,
            'is_correct' => $validated['is_correct'],
            'new_score' => $updatedSession->score,
            'new_hits' => $updatedSession->hits,
            'new_mistakes' => $updatedSession->mistakes,
            'accuracy' => $updatedSession->accuracy,
            'new_characters' => $newCharacters,
        ]);
    }

    /**
     * API: Procesar carácter no capturado
     */
    public function processMissedCharacter(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'theme_id' => 'required|integer',
            'current_score' => 'required|integer',
            'current_hits' => 'required|integer',
            'current_mistakes' => 'required|integer',
            'current_level' => 'required|integer|min:1|max:5',
        ]);

        $theme = $this->themeRepository->getById($validated['theme_id']);
        if (!$theme) {
            return response()->json(['error' => 'Tema no encontrado'], 404);
        }

        $session = new \App\Games\DTOs\GameSessionDTO(
            theme_id: $validated['theme_id'],
            game_type: 'catch_the_character',
            score: $validated['current_score'],
            hits: $validated['current_hits'],
            mistakes: $validated['current_mistakes'],
            level_reached: $validated['current_level'],
        );

        $updatedSession = $this->gameService->processCharacterMissed($session);

        return response()->json([
            'success' => true,
            'new_score' => $updatedSession->score,
            'new_hits' => $updatedSession->hits,
            'new_mistakes' => $updatedSession->mistakes,
            'accuracy' => $updatedSession->accuracy,
        ]);
    }

    /**
     * API: Finalizar partida y guardar estadísticas
     */
    public function endGame(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'theme_id' => 'required|integer',
            'score' => 'required|integer',
            'hits' => 'required|integer',
            'mistakes' => 'required|integer',
            'duration' => 'required|integer',
            'level' => 'required|integer|min:1|max:5',
        ]);

        $theme = $this->themeRepository->getById($validated['theme_id']);
        if (!$theme) {
            return response()->json(['error' => 'Tema no encontrado'], 404);
        }

        // Crear sesión para guardar
        $sessionDTO = new \App\Games\DTOs\GameSessionDTO(
            theme_id: $validated['theme_id'],
            game_type: 'catch_the_character',
            score: $validated['score'],
            hits: $validated['hits'],
            mistakes: $validated['mistakes'],
            accuracy: $validated['hits'] + $validated['mistakes'] > 0 
                ? round(($validated['hits'] / ($validated['hits'] + $validated['mistakes'])) * 100, 2)
                : 0,
            duration: $validated['duration'],
            level_reached: $validated['level'],
        );

        // Guardar en BD
        $gameSession = \App\Games\Services\GameService::class;
        $service = app($gameSession);
        $savedSession = $service->saveGameSession($sessionDTO);

        return response()->json([
            'success' => true,
            'session_id' => $savedSession->id,
            'message' => 'Partida guardada exitosamente',
        ]);
    }
}
