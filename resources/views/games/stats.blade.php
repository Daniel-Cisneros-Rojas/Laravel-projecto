@extends('layouts.game')

@section('content')
<div class="header">
    <h1>📊 Estadísticas Generales</h1>
    <p>Tu progreso en el juego</p>
</div>

<div class="game-card">
    <h2 style="color: var(--color-primary); margin-bottom: 20px;">Catch the Character</h2>

    <div class="grid grid-2" style="margin-bottom: 20px;">
        <div style="background: #f8fafc; padding: 20px; border-radius: 8px;">
            <div style="font-size: 0.9em; color: #666; margin-bottom: 10px;">Total de Partidas</div>
            <div style="font-size: 2em; font-weight: bold; color: var(--color-primary);">
                {{ $catchTheCharacterStats['total_sessions'] ?? 0 }}
            </div>
        </div>

        <div style="background: #f8fafc; padding: 20px; border-radius: 8px;">
            <div style="font-size: 0.9em; color: #666; margin-bottom: 10px;">Puntuación Promedio</div>
            <div style="font-size: 2em; font-weight: bold; color: var(--color-primary);">
                {{ number_format($catchTheCharacterStats['average_score'] ?? 0, 0) }}
            </div>
        </div>

        <div style="background: #f8fafc; padding: 20px; border-radius: 8px;">
            <div style="font-size: 0.9em; color: #666; margin-bottom: 10px;">Precisión Promedio</div>
            <div style="font-size: 2em; font-weight: bold; color: var(--color-success);">
                {{ number_format($catchTheCharacterStats['average_accuracy'] ?? 0, 1) }}%
            </div>
        </div>

        <div style="background: #f8fafc; padding: 20px; border-radius: 8px;">
            <div style="font-size: 0.9em; color: #666; margin-bottom: 10px;">Mejor Puntuación</div>
            <div style="font-size: 2em; font-weight: bold; color: var(--color-warning);">
                {{ number_format($catchTheCharacterStats['best_score'] ?? 0, 0) }}
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ route('games.index') }}" class="btn btn-primary">
            Volver al Menú
        </a>
    </div>
</div>
@endsection
