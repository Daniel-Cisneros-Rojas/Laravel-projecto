@extends('layouts.game')

@section('styles')
<style>
    .stats-section {
        background: var(--color-surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border-light);
        padding: 28px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
        animation: slideUp 0.5s ease both;
    }
    .stats-section:nth-child(2) { animation-delay: 0.08s; }
    .stats-section:nth-child(3) { animation-delay: 0.16s; }

    .stats-section h2 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--color-text);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .stats-section h2 i { color: var(--color-primary); }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 14px;
    }
</style>
@endsection

@section('content')
<div class="header">
    <h1><i data-lucide="bar-chart-3" style="color:var(--color-primary);"></i> Estadisticas Generales</h1>
    <p>Tu progreso en los juegos</p>
</div>

<div class="stats-section">
    <h2><i data-lucide="crosshair"></i> Catch the Character</h2>
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label"><i data-lucide="gamepad-2" style="width:14px;height:14px;"></i> Total de Partidas</div>
            <div class="stat-value">{{ $catchTheCharacterStats['total_sessions'] ?? 0 }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label"><i data-lucide="star" style="width:14px;height:14px;"></i> Puntuacion Promedio</div>
            <div class="stat-value">{{ number_format($catchTheCharacterStats['average_score'] ?? 0, 0) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label"><i data-lucide="target" style="width:14px;height:14px;"></i> Precision Promedio</div>
            <div class="stat-value accent">{{ number_format($catchTheCharacterStats['average_accuracy'] ?? 0, 1) }}%</div>
        </div>
        <div class="stat-box">
            <div class="stat-label"><i data-lucide="trophy" style="width:14px;height:14px;"></i> Mejor Puntuacion</div>
            <div class="stat-value warm">{{ number_format($catchTheCharacterStats['best_score'] ?? 0, 0) }}</div>
        </div>
    </div>
</div>

<div class="stats-section">
    <h2><i data-lucide="layers"></i> Memory Hanzi</h2>
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label"><i data-lucide="gamepad-2" style="width:14px;height:14px;"></i> Total de Partidas</div>
            <div class="stat-value">{{ $memoryGameStats['total_sessions'] ?? 0 }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label"><i data-lucide="star" style="width:14px;height:14px;"></i> Puntuacion Promedio</div>
            <div class="stat-value">{{ number_format($memoryGameStats['average_score'] ?? 0, 0) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label"><i data-lucide="target" style="width:14px;height:14px;"></i> Precision Promedio</div>
            <div class="stat-value accent">{{ number_format($memoryGameStats['average_accuracy'] ?? 0, 1) }}%</div>
        </div>
        <div class="stat-box">
            <div class="stat-label"><i data-lucide="trophy" style="width:14px;height:14px;"></i> Mejor Puntuacion</div>
            <div class="stat-value warm">{{ number_format($memoryGameStats['best_score'] ?? 0, 0) }}</div>
        </div>
    </div>
</div>

<div class="stats-section">
    <h2><i data-lucide="pen-tool"></i> Orden de Trazos</h2>
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label"><i data-lucide="gamepad-2" style="width:14px;height:14px;"></i> Total de Partidas</div>
            <div class="stat-value">{{ $strokeOrderStats['total_sessions'] ?? 0 }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label"><i data-lucide="star" style="width:14px;height:14px;"></i> Puntuacion Promedio</div>
            <div class="stat-value">{{ number_format($strokeOrderStats['average_score'] ?? 0, 0) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label"><i data-lucide="target" style="width:14px;height:14px;"></i> Precision Promedio</div>
            <div class="stat-value accent">{{ number_format($strokeOrderStats['average_accuracy'] ?? 0, 1) }}%</div>
        </div>
        <div class="stat-box">
            <div class="stat-label"><i data-lucide="trophy" style="width:14px;height:14px;"></i> Mejor Puntuacion</div>
            <div class="stat-value warm">{{ number_format($strokeOrderStats['best_score'] ?? 0, 0) }}</div>
        </div>
    </div>
</div>

<div style="text-align:center;">
    <a href="{{ route('games.index') }}" class="btn btn-primary">
        <i data-lucide="home" style="width:16px;height:16px;"></i> Volver al Menu
    </a>
</div>
@endsection
