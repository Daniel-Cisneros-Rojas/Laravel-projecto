@extends('layouts.game')

@section('styles')
<style>
    .themes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }

    .theme-tile {
        display: block;
        text-decoration: none;
        background: var(--color-surface);
        border-radius: var(--radius-lg);
        border: 1px solid var(--color-border-light);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: all var(--transition-base);
        animation: slideUp 0.4s ease both;
    }
    .theme-tile:nth-child(2) { animation-delay: 0.05s; }
    .theme-tile:nth-child(3) { animation-delay: 0.10s; }
    .theme-tile:nth-child(4) { animation-delay: 0.15s; }
    .theme-tile:nth-child(5) { animation-delay: 0.20s; }

    .theme-tile:hover {
        box-shadow: var(--shadow-lg);
        transform: translateY(-4px);
        border-color: var(--color-border);
    }

    .theme-banner {
        height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .theme-banner .theme-letter {
        font-size: 2.4rem;
        font-weight: 800;
        color: #fff;
        opacity: 0.9;
        text-shadow: 0 2px 8px rgba(0,0,0,0.12);
        position: relative;
        z-index: 1;
    }

    .theme-banner::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 60%);
    }

    .theme-body { padding: 20px; text-align: center; }
    .theme-body h3 { font-size: 1rem; font-weight: 700; color: var(--color-text); margin-bottom: 6px; }
    .theme-body p { font-size: 0.82rem; color: var(--color-text-muted); line-height: 1.45; margin-bottom: 16px; }

    .theme-body .btn { width: 100%; }

    @media (max-width: 600px) {
        .themes-grid { grid-template-columns: 1fr 1fr; gap: 14px; }
        .theme-banner { height: 80px; }
    }
</style>
@endsection

@section('content')
<div class="header">
    <h1><i data-lucide="palette" style="color:var(--color-primary);"></i> Selecciona un Tema</h1>
    <p>{{ $game['name'] }} — {{ $game['description'] }}</p>
</div>

<div class="themes-grid">
    @foreach($themes as $theme)
    <a href="{{ route($game['route'], $theme->id) }}" class="theme-tile">
        <div class="theme-banner" style="background: linear-gradient(135deg, {{ $theme->color_primary }}, {{ $theme->color_secondary }});">
            <span class="theme-letter">{{ mb_substr($theme->name, 0, 1) }}</span>
        </div>
        <div class="theme-body">
            <h3>{{ $theme->name }}</h3>
            <p>{{ $theme->description }}</p>
            <span class="btn btn-primary btn-sm">
                <i data-lucide="play" style="width:14px;height:14px;"></i> Jugar
            </span>
        </div>
    </a>
    @endforeach
</div>

<div style="text-align:center;">
    <a href="{{ route('games.index') }}" class="back-link"><i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Volver al Menu</a>
</div>
@endsection
