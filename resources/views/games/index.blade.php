@extends('layouts.game')

@section('styles')
<style>
    .hero { margin-bottom: 48px; }
    .hero h1 { font-size: 2.2rem; margin-bottom: 10px; }
    .hero p { font-size: 1.05rem; max-width: 480px; margin: 0 auto; }

    .games-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        margin-bottom: 16px;
    }

    .game-tile {
        background: var(--color-surface);
        border-radius: var(--radius-lg);
        padding: 32px 28px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--color-border-light);
        transition: all var(--transition-base);
        animation: slideUp 0.5s ease both;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .game-tile:nth-child(2) { animation-delay: 0.08s; }
    .game-tile:nth-child(3) { animation-delay: 0.16s; }

    .game-tile:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-4px);
        border-color: var(--color-border);
    }

    .game-tile .tile-head { display: flex; align-items: center; gap: 16px; }
    .game-tile .tile-icon {
        width: 52px; height: 52px; border-radius: var(--radius-md);
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .game-tile .tile-icon.c1 { background: var(--color-primary-50); color: var(--color-primary); }
    .game-tile .tile-icon.c2 { background: var(--color-secondary-50); color: var(--color-secondary); }
    .game-tile .tile-icon.c3 { background: var(--color-accent-50); color: var(--color-accent); }

    .game-tile h2 { font-size: 1.1rem; font-weight: 700; color: var(--color-text); }
    .game-tile .tile-desc { color: var(--color-text-muted); font-size: 0.88rem; line-height: 1.55; }

    .tile-actions { display: flex; flex-direction: column; gap: 8px; margin-top: auto; }

    @media (max-width: 700px) {
        .hero h1 { font-size: 1.6rem; }
        .games-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="header hero">
    <h1><i data-lucide="sparkles"></i> Aprende Hanzi Jugando</h1>
    <p>Domina los caracteres chinos mediante minijuegos educativos</p>
</div>

<div class="games-grid">
    <div class="game-tile">
        <div class="tile-head">
            <div class="tile-icon c1"><i data-lucide="crosshair" style="width:24px;height:24px;"></i></div>
            <div>
                <h2>Catch the Character</h2>
            </div>
        </div>
        <p class="tile-desc">Identifica los caracteres correctos antes de que lleguen al fondo de la pantalla</p>
        <div class="tile-actions">
            <a href="{{ route('games.selectTheme', 'catch-the-character') }}" class="btn btn-primary" style="width:100%;">
                <i data-lucide="play" style="width:16px;height:16px;"></i> Jugar Ahora
            </a>
            <a href="{{ route('games.stats') }}" class="btn btn-secondary" style="width:100%;">
                <i data-lucide="bar-chart-3" style="width:16px;height:16px;"></i> Ver Estadisticas
            </a>
        </div>
    </div>

    <div class="game-tile">
        <div class="tile-head">
            <div class="tile-icon c2"><i data-lucide="layers" style="width:24px;height:24px;"></i></div>
            <div>
                <h2>Memory Hanzi</h2>
            </div>
        </div>
        <p class="tile-desc">Empareja caracteres con su pinyin y aprende a dibujarlos</p>
        <div class="tile-actions">
            <a href="{{ route('games.selectTheme', 'memory-game') }}" class="btn btn-primary" style="width:100%;">
                <i data-lucide="play" style="width:16px;height:16px;"></i> Jugar Ahora
            </a>
            <a href="{{ route('games.stats') }}" class="btn btn-secondary" style="width:100%;">
                <i data-lucide="bar-chart-3" style="width:16px;height:16px;"></i> Ver Estadisticas
            </a>
        </div>
    </div>

    <div class="game-tile">
        <div class="tile-head">
            <div class="tile-icon c3"><i data-lucide="pen-tool" style="width:24px;height:24px;"></i></div>
            <div>
                <h2>Orden de Trazos</h2>
            </div>
        </div>
        <p class="tile-desc">Ordena los trazos de un caracter chino en el orden correcto</p>
        <div class="tile-actions">
            <a href="{{ route('games.selectTheme', 'stroke-order') }}" class="btn btn-primary" style="width:100%;">
                <i data-lucide="play" style="width:16px;height:16px;"></i> Jugar Ahora
            </a>
            <a href="{{ route('games.stats') }}" class="btn btn-secondary" style="width:100%;">
                <i data-lucide="bar-chart-3" style="width:16px;height:16px;"></i> Ver Estadisticas
            </a>
        </div>
    </div>
</div>

<div style="text-align:center;">
    <a href="/" class="back-link"><i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Volver al Inicio</a>
</div>
@endsection
