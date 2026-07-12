<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SumaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\CatchTheCharacterController;

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