@extends('layouts.game')

@section('content')
<div class="header">
    <h1>📊 Estadísticas de {{ $theme->name }}</h1>
    <p>Tu desempeño en esta categoría</p>
</div>

<div class="game-card">
    <div class="grid grid-2" style="margin-bottom: 20px;">
        <div style="background: #f8fafc; padding: 20px; border-radius: 8px;">
            <div style="font-size: 0.9em; color: #666; margin-bottom: 10px;">Total de Partidas</div>
            <div style="font-size: 2em; font-weight: bold; color: var(--color-primary);">
                {{ $stats['total_sessions'] ?? 0 }}
            </div>
        </div>

        <div style="background: #f8fafc; padding: 20px; border-radius: 8px;">
            <div style="font-size: 0.9em; color: #666; margin-bottom: 10px;">Puntuación Promedio</div>
            <div style="font-size: 2em; font-weight: bold; color: var(--color-primary);">
                {{ number_format($stats['average_score'] ?? 0, 0) }}
            </div>
        </div>

        <div style="background: #f8fafc; padding: 20px; border-radius: 8px;">
            <div style="font-size: 0.9em; color: #666; margin-bottom: 10px;">Precisión Promedio</div>
            <div style="font-size: 2em; font-weight: bold; color: var(--color-success);">
                {{ number_format($stats['average_accuracy'] ?? 0, 1) }}%
            </div>
        </div>

        <div style="background: #f8fafc; padding: 20px; border-radius: 8px;">
            <div style="font-size: 0.9em; color: #666; margin-bottom: 10px;">Tiempo Jugado</div>
            <div style="font-size: 2em; font-weight: bold; color: var(--color-warning);">
                {{ number_format($stats['total_playtime'] ?? 0 / 60, 1) }}min
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ route('games.selectTheme', 'catch-the-character') }}" class="btn btn-primary">
            Jugar de Nuevo
        </a>
        <a href="{{ route('games.stats') }}" class="btn btn-secondary" style="margin-left: 10px;">
            Ver Estadísticas Generales
        </a>
    </div>
</div>
@endsection
