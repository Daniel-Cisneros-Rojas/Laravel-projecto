@extends('layouts.game')

@section('content')
<div class="header">
    <h1>🎮 Selecciona un Tema</h1>
    <p>Elige la categoría que quieres aprender</p>
</div>

<div class="grid grid-3">
    @foreach($themes as $theme)
    <a href="{{ route('catchTheCharacter.show', $theme->id) }}" class="game-card" style="text-decoration: none; cursor: pointer; display: block; transition: all 0.3s ease;">
        <div style="display: flex; align-items: center; justify-content: center; height: 120px; background: linear-gradient(135deg, {{ $theme->color_primary }}, {{ $theme->color_secondary }}); border-radius: 10px; margin-bottom: 20px;">
            <span style="font-size: 3em;">{{ $theme->name[0] }}</span>
        </div>

        <h3 style="color: var(--color-primary); margin-bottom: 10px; text-align: center;">{{ $theme->name }}</h3>
        <p style="color: #666; text-align: center; font-size: 0.9em; margin-bottom: 15px;">{{ $theme->description }}</p>

        <div style="text-align: center;">
            <span class="btn btn-primary" style="display: inline-block;">
                Jugar
            </span>
        </div>
    </a>
    @endforeach
</div>

<div style="text-align: center; margin-top: 40px;">
    <a href="{{ route('games.index') }}" class="btn btn-secondary">← Volver al Menú</a>
</div>
@endsection
