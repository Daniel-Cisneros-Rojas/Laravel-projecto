@extends('layouts.game')

@section('content')
<div class="header">
    <h1>🎮 Aprende Hanzi Jugando</h1>
    <p>Domina los caracteres chinos mediante minijuegos educativos</p>
</div>

<div class="grid grid-2">
    <!-- Catch the Character -->
    <div class="game-card">
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="font-size: 3em; margin-bottom: 10px;">🎯</div>
            <h2 style="color: var(--color-primary); margin-bottom: 10px;">Catch the Character</h2>
            <p style="color: #666; margin-bottom: 15px;">Identifica los caracteres correctos antes de que lleguen al fondo de la pantalla</p>
        </div>

        <div style="display: flex; flex-direction: column; gap: 10px;">
            <a href="{{ route('games.selectTheme', 'catch-the-character') }}" class="btn btn-primary">
                Jugar Ahora
            </a>
            <a href="{{ route('games.stats') }}" class="btn btn-secondary">
                Ver Estadísticas
            </a>
        </div>
    </div>

    <!-- Placeholder para futuros juegos -->
    <div class="game-card" style="opacity: 0.6; position: relative;">
        <div style="text-align: center; margin-bottom: 20px;">
            <div style="font-size: 3em; margin-bottom: 10px;">🎴</div>
            <h2 style="color: #ccc; margin-bottom: 10px;">Memory Hanzi</h2>
            <p style="color: #999; margin-bottom: 15px;">Próximamente disponible</p>
        </div>

        <div style="display: flex; gap: 10px;">
            <button class="btn btn-primary" disabled style="flex: 1; opacity: 0.5; cursor: not-allowed;">
                Próximamente
            </button>
        </div>
    </div>
</div>

<div style="text-align: center; margin-top: 40px;">
    <a href="/" class="btn btn-secondary">← Volver al Inicio</a>
</div>
@endsection
