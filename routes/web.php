<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SumaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\CatchTheCharacterController;
use App\Http\Controllers\MemoryGameController;
use App\Http\Controllers\StrokeOrderController;

Route::get('/', function () {
    return redirect()->route('games.index');
});

Route::get('/inicio', function () {
    return view('inicio');
});

Route::get('/suma', [SumaController::class, 'index']);

Route::post('/suma', [SumaController::class, 'sumar']);

Route::get('/productos', [ProductoController::class, 'index']);

// Rutas de juegos
Route::prefix('games')->name('games.')->group(function () {
    // Menú principal
    Route::get('/', [GameController::class, 'index'])->name('index');

    // Seleccionar tema
    Route::get('/{gameSlug}/select-theme', [GameController::class, 'selectTheme'])->name('selectTheme');

    // Estadísticas generales
    Route::get('/stats', [GameController::class, 'stats'])->name('stats');

    // Estadísticas por tema
    Route::get('/theme/{themeId}/stats', [GameController::class, 'themeStats'])->name('themeStats');
});

// Rutas específicas de Catch the Character
Route::prefix('catch-the-character')->name('catchTheCharacter.')->group(function () {
    // Página del juego
    Route::get('/{themeId}', [CatchTheCharacterController::class, 'show'])->name('show');

    // APIs del juego
    Route::post('/api/{themeId}/characters', [CatchTheCharacterController::class, 'getCharacters'])->name('getCharacters');
    Route::post('/api/click', [CatchTheCharacterController::class, 'processCharacterClick'])->name('processClick');
    Route::post('/api/missed', [CatchTheCharacterController::class, 'processMissedCharacter'])->name('processMissed');
    Route::post('/api/end', [CatchTheCharacterController::class, 'endGame'])->name('endGame');
});

// Rutas específicas de Memory Game
Route::prefix('memory-game')->name('memoryGame.')->group(function () {
    // Página del juego
    Route::get('/{themeId}', [MemoryGameController::class, 'show'])->name('show');

    // APIs del juego
    Route::post('/api/{themeId}/pairs', [MemoryGameController::class, 'generatePairs'])->name('generatePairs');
    Route::post('/api/match', [MemoryGameController::class, 'recordMatch'])->name('recordMatch');
    Route::post('/api/mismatch', [MemoryGameController::class, 'recordMismatch'])->name('recordMismatch');
    Route::post('/api/drawing', [MemoryGameController::class, 'recordDrawing'])->name('recordDrawing');
    Route::post('/api/end', [MemoryGameController::class, 'endGame'])->name('endGame');
});

// Rutas específicas de Stroke Order
Route::prefix('stroke-order')->name('strokeOrder.')->group(function () {
    // Página del juego
    Route::get('/{themeId}', [StrokeOrderController::class, 'show'])->name('show');

    // APIs del juego
    Route::post('/api/{themeId}/character', [StrokeOrderController::class, 'getCharacter'])->name('getCharacter');
    Route::post('/api/attempt', [StrokeOrderController::class, 'recordAttempt'])->name('recordAttempt');
    Route::post('/api/end', [StrokeOrderController::class, 'endGame'])->name('endGame');
});