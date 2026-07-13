<?php

namespace App\Providers;

use App\Games\Contracts\GameServiceInterface;
use App\Games\Repositories\CharacterRepository;
use App\Games\Repositories\GameSessionRepository;
use App\Games\Repositories\ThemeRepository;
use App\Games\Services\CatchTheCharacterService;
use App\Games\Services\MemoryGameService;
use App\Games\Services\GameService;
use App\Games\Services\LevelConfigService;
use Illuminate\Support\ServiceProvider;

class GameServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar servicios singleton
        $this->app->singleton(LevelConfigService::class, function () {
            return new LevelConfigService();
        });

        // Registrar repositorios
        $this->app->singleton(CharacterRepository::class, function () {
            return new CharacterRepository();
        });

        $this->app->singleton(ThemeRepository::class, function () {
            return new ThemeRepository();
        });

        $this->app->singleton(GameSessionRepository::class, function () {
            return new GameSessionRepository();
        });

        // Registrar GameService como implementación de la interfaz
        $this->app->singleton(GameServiceInterface::class, function ($app) {
            return new GameService(
                characterRepository: $app->make(CharacterRepository::class),
                gameSessionRepository: $app->make(GameSessionRepository::class),
                themeRepository: $app->make(ThemeRepository::class),
                levelConfigService: $app->make(LevelConfigService::class),
            );
        });

        // También registrar GameService directamente
        $this->app->singleton(GameService::class, function ($app) {
            return $app->make(GameServiceInterface::class);
        });

        // Registrar servicio específico del juego
        $this->app->singleton(CatchTheCharacterService::class, function ($app) {
            return new CatchTheCharacterService(
                gameService: $app->make(GameService::class),
                levelConfigService: $app->make(LevelConfigService::class),
                characterRepository: $app->make(CharacterRepository::class),
            );
        });

        // Registrar servicio del juego de Memorama
        $this->app->singleton(MemoryGameService::class, function ($app) {
            return new MemoryGameService(
                characterRepository: $app->make(CharacterRepository::class),
                themeRepository: $app->make(ThemeRepository::class),
                gameSessionRepository: $app->make(GameSessionRepository::class),
                levelConfigService: $app->make(LevelConfigService::class),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
