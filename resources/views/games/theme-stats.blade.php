@extends('layouts.game')

@section('styles')
<style>
    .stats-section {
        background: var(--color-surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border-light);
        padding: 28px;
        box-shadow: var(--shadow-sm);
        animation: slideUp 0.5s ease both;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 14px;
        margin-bottom: 28px;
    }

    .stats-actions { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
</style>
@endsection

@section('content')
<div class="header">
    <h1><i data-lucide="bar-chart-3" style="color:var(--color-primary);"></i> Estadisticas de {{ $theme->name }}</h1>
    <p>Tu desempeno en esta categoria</p>
</div>

<div class="stats-section">
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label"><i data-lucide="gamepad-2" style="width:14px;height:14px;"></i> Total de Partidas</div>
            <div class="stat-value">{{ $stats['total_sessions'] ?? 0 }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label"><i data-lucide="star" style="width:14px;height:14px;"></i> Puntuacion Promedio</div>
            <div class="stat-value">{{ number_format($stats['average_score'] ?? 0, 0) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label"><i data-lucide="target" style="width:14px;height:14px;"></i> Precision Promedio</div>
            <div class="stat-value accent">{{ number_format($stats['average_accuracy'] ?? 0, 1) }}%</div>
        </div>
        <div class="stat-box">
            <div class="stat-label"><i data-lucide="clock" style="width:14px;height:14px;"></i> Tiempo Jugado</div>
            <div class="stat-value warm">{{ number_format($stats['total_playtime'] ?? 0 / 60, 1) }}min</div>
        </div>
    </div>

    <div class="stats-actions">
        <a href="{{ route('games.selectTheme', 'catch-the-character') }}" class="btn btn-primary">
            <i data-lucide="play" style="width:16px;height:16px;"></i> Jugar de Nuevo
        </a>
        <a href="{{ route('games.stats') }}" class="btn btn-secondary">
            <i data-lucide="bar-chart-3" style="width:16px;height:16px;"></i> Ver Estadisticas Generales
        </a>
    </div>
</div>
@endsection
