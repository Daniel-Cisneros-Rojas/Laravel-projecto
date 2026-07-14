@extends('layouts.game')

@section('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    :root {
        --font-sans: 'Inter', ui-sans-serif, system-ui, -apple-system, sans-serif;
        --color-primary: #7c6ef0;
        --color-primary-light: #a8a0f7;
        --color-primary-dark: #6354d9;
        --color-primary-50: #f0eefb;
        --color-primary-100: #e3dffa;
        --color-primary-200: #cdc6f5;
        --color-secondary: #e8918f;
        --color-secondary-50: #fdf2f2;
        --color-accent: #6bc5a0;
        --color-accent-50: #edf8f3;
        --color-accent-dark: #4eaa84;
        --color-warm: #e5a76e;
        --color-warm-50: #fdf6ee;
        --color-bg: #faf9fe;
        --color-surface: #ffffff;
        --color-text: #2d2a4a;
        --color-text-muted: #7a7695;
        --color-text-light: #a5a2b8;
        --color-border: #e8e6f0;
        --color-border-light: #f0eef5;
        --radius-sm: 8px;
        --radius-md: 12px;
        --radius-lg: 16px;
        --radius-xl: 20px;
        --shadow-sm: 0 1px 3px rgba(45,42,74,0.04), 0 1px 2px rgba(45,42,74,0.03);
        --shadow-md: 0 4px 12px rgba(45,42,74,0.06), 0 2px 4px rgba(45,42,74,0.04);
        --shadow-lg: 0 12px 32px rgba(45,42,74,0.08), 0 4px 8px rgba(45,42,74,0.04);
        --shadow-xl: 0 20px 48px rgba(45,42,74,0.10), 0 8px 16px rgba(45,42,74,0.05);
        --transition-fast: 0.15s ease;
        --transition-base: 0.25s ease;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body { overflow: hidden !important; }

    .container { max-width: none !important; padding: 0 !important; margin: 0 !important; height: 100vh !important; }

    /* ── Layout ───────────────────────────────────── */
    .game-shell {
        display: flex;
        flex-direction: column;
        height: 100vh;
        width: 100vw;
        position: fixed;
        inset: 0;
        background: var(--color-bg);
        color: var(--color-text);
        font-family: var(--font-sans);
    }

    /* ── Header / HUD ─────────────────────────────── */
    .hud {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 24px;
        background: var(--color-surface);
        border-bottom: 1px solid var(--color-border-light);
        flex-shrink: 0;
        z-index: 10;
        gap: 12px;
        flex-wrap: wrap;
    }

    .hud-brand {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .hud-brand h2 {
        font-size: 1.05rem;
        font-weight: 700;
        white-space: nowrap;
        color: var(--color-text);
    }

    .hud-badge {
        font-size: 0.65rem;
        padding: 3px 8px;
        border-radius: 20px;
        background: var(--color-primary-50);
        color: var(--color-primary);
        font-weight: 600;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .hud-stats {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .stat-chip {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 10px;
        background: var(--color-bg);
        border: 1px solid var(--color-border-light);
        font-size: 0.8rem;
        white-space: nowrap;
        color: var(--color-text);
        transition: background 0.2s;
    }

    .stat-chip .icon { font-size: 0.9rem; opacity: 0.7; }
    .stat-chip .label { color: var(--color-text-muted); font-size: 0.7rem; }
    .stat-chip .value { font-weight: 700; font-size: 0.95rem; }
    .stat-chip.timer .value { font-variant-numeric: tabular-nums; }
    .stat-chip.mistakes .value { color: var(--color-secondary); }

    .exit-chip {
        background: var(--color-secondary-50);
        border: 1px solid rgba(232,145,143,0.3);
        color: var(--color-secondary);
        text-decoration: none;
        cursor: pointer;
        transition: all 0.25s;
    }

    .exit-chip:hover {
        background: rgba(232,145,143,0.15);
        border-color: rgba(232,145,143,0.4);
        color: var(--color-secondary-dark, #d47270);
    }

    /* ── Mode selector ────────────────────────────── */
    .mode-bar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 24px;
        background: var(--color-surface);
        border-bottom: 1px solid var(--color-border-light);
        flex-shrink: 0;
    }

    .mode-btn {
        padding: 6px 18px;
        border-radius: 8px;
        border: 1px solid var(--color-border);
        background: var(--color-surface);
        color: var(--color-text-muted);
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s;
    }

    .mode-btn:hover {
        background: var(--color-primary-50);
        color: var(--color-primary);
    }

    .mode-btn.active {
        background: var(--color-primary);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 2px 12px rgba(124,110,240,0.25);
    }

    /* ── Board ────────────────────────────────────── */
    .board {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 24px;
        overflow: auto;
        position: relative;
    }

    .grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        width: 100%;
        max-width: 520px;
    }

    /* ── Card ─────────────────────────────────────── */
    .card {
        aspect-ratio: 3 / 4;
        perspective: 800px;
        cursor: pointer;
        position: relative;
    }

    .card-inner {
        position: relative;
        width: 100%;
        height: 100%;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        transform-style: preserve-3d;
    }

    .card.flipped .card-inner { transform: rotateY(180deg); }

    .card-face {
        position: absolute;
        inset: 0;
        border-radius: 14px;
        backface-visibility: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        overflow: hidden;
    }

    /* Front */
    .card-front {
        background: var(--color-surface);
        border: 1px solid var(--color-border-light);
        box-shadow: var(--shadow-sm);
    }

    .card-front::before {
        content: '?';
        font-size: 2rem;
        font-weight: 800;
        color: var(--color-text-light);
    }

    .card:hover .card-front {
        background: var(--color-primary-50);
        border-color: var(--color-primary-100);
        transform: scale(1.03);
    }

    .card:hover .card-inner { transform: scale(1.03); }
    .card.flipped:hover .card-inner { transform: rotateY(180deg) scale(1.03); }

    /* Back */
    .card-back {
        transform: rotateY(180deg);
        border: 2px solid transparent;
        padding: 8px;
    }

    .card-back.type-character {
        background: linear-gradient(145deg, {{ $theme->color_primary }}, {{ $theme->color_secondary }});
        border-color: {{ $theme->color_primary }};
    }

    .card-back.type-pinyin,
    .card-back.type-meaning {
        background: var(--color-bg);
        border: 1px solid var(--color-border);
    }

    .card-back .card-value {
        font-weight: 700;
        text-align: center;
        line-height: 1.15;
        word-break: break-word;
    }

    .card-back.type-character .card-value {
        font-size: 2.2rem;
        color: #fff;
    }

    .card-back.type-pinyin .card-value {
        font-size: 1rem;
        color: var(--color-text);
    }

    .card-back.type-meaning .card-value {
        font-size: 0.85rem;
        color: var(--color-text);
    }

    .card-back .card-sub {
        font-size: 0.65rem;
        color: var(--color-text-muted);
        margin-top: 4px;
    }

    /* Matched state */
    .card.matched .card-inner { transform: rotateY(180deg); }
    .card.matched .card-back {
        border-color: var(--color-accent);
        box-shadow: 0 0 20px rgba(107,197,160,0.25), inset 0 0 20px rgba(107,197,160,0.1);
    }

    .card.matched::after {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: 16px;
        border: 2px solid var(--color-accent);
        animation: matchPulse 1.5s ease-in-out infinite;
        pointer-events: none;
        z-index: 2;
    }

    /* Wrong flash */
    .card.wrong .card-inner { animation: shake 0.4s ease; }
    .card.wrong .card-back { border-color: var(--color-secondary); }

    @keyframes matchPulse {
        0%, 100% { opacity: 0.4; transform: scale(1); }
        50% { opacity: 0; transform: scale(1.06); }
    }

    @keyframes shake {
        0%, 100% { transform: rotateY(180deg) translateX(0); }
        20% { transform: rotateY(180deg) translateX(-6px); }
        40% { transform: rotateY(180deg) translateX(6px); }
        60% { transform: rotateY(180deg) translateX(-4px); }
        80% { transform: rotateY(180deg) translateX(4px); }
    }

    /* ── Progress / Footer ────────────────────────── */
    .footer-bar {
        flex-shrink: 0;
        padding: 10px 24px;
        background: var(--color-surface);
        border-top: 1px solid var(--color-border-light);
        display: flex;
        flex-direction: column;
        gap: 6px;
        align-items: center;
    }

    .timer-track {
        width: 100%;
        height: 4px;
        border-radius: 2px;
        background: var(--color-border-light);
        overflow: hidden;
    }

    .timer-fill {
        height: 100%;
        border-radius: 2px;
        background: linear-gradient(90deg, {{ $theme->color_primary }}, {{ $theme->color_secondary }});
        transition: width 1s linear;
    }

    .timer-fill.warning { background: linear-gradient(90deg, #f59e0b, #ef4444); }

    .footer-hint {
        font-size: 0.72rem;
        color: var(--color-text-muted);
    }

    /* ── Overlays shared ──────────────────────────── */
    .overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(45, 42, 74, 0.4);
        backdrop-filter: blur(8px);
        z-index: 500;
        align-items: center;
        justify-content: center;
    }

    .overlay.visible { display: flex; }

    /* ── Drawing overlay ──────────────────────────── */
    .drawing-card {
        background: var(--color-surface);
        border: 1px solid var(--color-border-light);
        border-radius: var(--radius-xl);
        padding: 32px;
        text-align: center;
        width: min(400px, 90vw);
        box-shadow: var(--shadow-xl);
        animation: popIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .drawing-card h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--color-text);
        margin-bottom: 6px;
    }

    .drawing-card .sub {
        font-size: 0.82rem;
        color: var(--color-text-muted);
        margin-bottom: 20px;
    }

    #character-target-div {
        margin: 0 auto 20px;
        background: var(--color-bg);
        border-radius: var(--radius-md);
        border: 1px solid var(--color-border-light);
    }

    .drawing-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .drawing-actions button {
        padding: 10px 24px;
        border-radius: var(--radius-sm);
        border: none;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-skip {
        background: var(--color-bg);
        color: var(--color-text-muted);
        border: 1px solid var(--color-border);
    }

    .btn-skip:hover { background: var(--color-primary-50); color: var(--color-primary); }

    .btn-confirm {
        background: var(--color-primary);
        color: #fff;
    }

    .btn-confirm:hover { box-shadow: 0 4px 16px rgba(124,110,240,0.35); transform: translateY(-1px); }

    /* ── Game Over modal ──────────────────────────── */
    .modal {
        background: var(--color-surface);
        border: 1px solid var(--color-border-light);
        border-radius: var(--radius-xl);
        padding: 40px;
        text-align: center;
        width: min(480px, 90vw);
        box-shadow: var(--shadow-xl);
        animation: popIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .modal h2 {
        font-size: 1.6rem;
        font-weight: 800;
        margin-bottom: 4px;
        color: var(--color-text);
    }

    .modal .subtitle {
        color: var(--color-text-muted);
        font-size: 0.85rem;
        margin-bottom: 28px;
    }

    .result-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 28px;
    }

    .result-item {
        background: var(--color-bg);
        border: 1px solid var(--color-border-light);
        border-radius: 14px;
        padding: 16px 12px;
    }

    .result-item .r-label {
        font-size: 0.7rem;
        color: var(--color-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 6px;
    }

    .result-item .r-value {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--color-text);
    }

    .result-item .r-value.accent { color: var(--color-primary); }

    .modal-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .modal-actions button,
    .modal-actions a {
        padding: 12px;
        border-radius: var(--radius-md);
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        border: none;
        transition: all 0.25s;
        text-decoration: none;
        text-align: center;
        display: block;
    }

    .modal-actions .btn-main {
        background: var(--color-primary);
        color: #fff;
    }

    .modal-actions .btn-main:hover { box-shadow: 0 6px 20px rgba(124,110,240,0.3); transform: translateY(-2px); }

    .modal-actions .btn-ghost {
        background: var(--color-bg);
        color: var(--color-text-muted);
        border: 1px solid var(--color-border);
    }

    .modal-actions .btn-ghost:hover { background: var(--color-primary-50); color: var(--color-primary); }

    /* ── Particles ────────────────────────────────── */
    .particle {
        position: fixed;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        pointer-events: none;
        z-index: 100;
        opacity: 1;
        animation: particleFly 0.7s ease-out forwards;
    }

    .particle.hit { background: var(--color-accent); box-shadow: 0 0 6px var(--color-accent); }
    .particle.miss { background: var(--color-secondary); box-shadow: 0 0 6px var(--color-secondary); }

    @keyframes particleFly {
        0% { transform: translate(0,0) scale(1); opacity: 1; }
        100% { transform: translate(var(--px), var(--py)) scale(0); opacity: 0; }
    }

    @keyframes popIn {
        0% { opacity: 0; transform: scale(0.85) translateY(20px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }

    @keyframes cardEntry {
        0% { opacity: 0; transform: scale(0.7) translateY(16px); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }

    /* ── Responsive ───────────────────────────────── */
    @media (max-width: 520px) {
        .grid { grid-template-columns: repeat(3, 1fr); gap: 10px; max-width: 340px; }
        .card-back.type-character .card-value { font-size: 1.6rem; }
        .card-back.type-pinyin .card-value { font-size: 0.85rem; }
        .card-back.type-meaning .card-value { font-size: 0.72rem; }
        .hud { padding: 10px 14px; }
        .stat-chip { padding: 4px 10px; font-size: 0.72rem; }
    }

    @media (max-width: 360px) {
        .grid { grid-template-columns: repeat(3, 1fr); gap: 8px; }
    }
</style>
@endsection

@section('content')
<div class="game-shell">
    <!-- HUD -->
    <div class="hud">
        <div class="hud-brand">
            <h2>{{ $theme->name }}</h2>
            <span class="hud-badge">{{ $gameData['level_name'] }}</span>
        </div>

        <div class="hud-stats">
            <div class="stat-chip">
                <span class="icon"><i data-lucide="star" style="width:14px;height:14px;"></i></span>
                <span class="value" id="score">0</span>
            </div>
            <div class="stat-chip">
                <span class="icon"><i data-lucide="layers" style="width:14px;height:14px;"></i></span>
                <span class="label">Parejas</span>
                <span class="value" id="matches">0/{{ $gameData['pairs_count'] }}</span>
            </div>
            <div class="stat-chip mistakes">
                <span class="icon"><i data-lucide="x" style="width:14px;height:14px;"></i></span>
                <span class="value" id="mistakes">0</span>
            </div>
            <div class="stat-chip timer">
                <span class="icon"><i data-lucide="clock" style="width:14px;height:14px;"></i></span>
                <span class="value" id="timer">{{ $gameData['duration'] }}s</span>
            </div>
            <a href="{{ route('games.selectTheme', 'memory-game') }}" class="stat-chip exit-chip" onclick="return confirm('¿Seguro que quieres salir? Perderás el progreso.')">
                <span class="icon"><i data-lucide="log-out" style="width:14px;height:14px;"></i></span>
                <span class="label">Salir</span>
            </a>
        </div>
    </div>

    <!-- Mode selector -->
    @php $modes = config('game.memory_game.modes'); @endphp
    @if(count($modes) > 1)
    <div class="mode-bar">
        @foreach($modes as $key => $mode)
        <button class="mode-btn {{ $gameData['mode'] === $key ? 'active' : '' }}" data-mode="{{ $key }}" onclick="switchMode('{{ $key }}')">
            {{ $mode['name'] }}
        </button>
        @endforeach
    </div>
    @endif

    <!-- Board -->
    <div class="board" id="board">
        <div class="grid" id="grid"></div>
    </div>

    <!-- Footer -->
    <div class="footer-bar">
        <div class="timer-track">
            <div class="timer-fill" id="timerFill" style="width:100%"></div>
        </div>
        <span class="footer-hint" id="hint">Encuentra las parejas de caracteres</span>
    </div>
</div>

<!-- Drawing Overlay -->
<div class="overlay" id="drawingOverlay">
    <div class="drawing-card">
        <h3 id="drawingLabel"><i data-lucide="pen-tool" style="width:18px;height:18px;vertical-align:-3px;"></i> Observa como se dibuja</h3>
        <p class="sub" id="drawingSub">Memoriza el orden de los trazos</p>
        <div id="character-target-div" style="width:200px;height:200px;"></div>
        <div class="drawing-actions" id="drawingActions" style="display:none;">
            <button class="btn-skip" onclick="skipDrawing()">Saltar</button>
            <button class="btn-confirm" onclick="confirmDrawing()">Continuar</button>
        </div>
    </div>
</div>

<!-- Game Over Modal -->
<div class="overlay" id="gameOverModal">
    <div class="modal">
        <h2 id="resultTitle">¡Partida Finalizada!</h2>
        <p class="subtitle" id="resultSubtitle">Buen intento</p>

        <div class="result-grid">
            <div class="result-item">
                <div class="r-label">Puntuación</div>
                <div class="r-value accent" id="finalScore">0</div>
            </div>
            <div class="result-item">
                <div class="r-label">Parejas</div>
                <div class="r-value" id="finalMatches">0</div>
            </div>
            <div class="result-item">
                <div class="r-label">Errores</div>
                <div class="r-value" id="finalMistakes">0</div>
            </div>
            <div class="result-item">
                <div class="r-label">Precisión</div>
                <div class="r-value" id="finalAccuracy">0%</div>
            </div>
        </div>

        <div class="modal-actions">
            <button class="btn-main" onclick="restartSameMode()"><i data-lucide="rotate-ccw" style="width:16px;height:16px;"></i> Jugar de Nuevo</button>
            <a class="btn-ghost" href="{{ route('games.selectTheme', 'memory-game') }}"><i data-lucide="palette" style="width:16px;height:16px;"></i> Cambiar Tema</a>
            <a class="btn-ghost" href="{{ route('games.index') }}"><i data-lucide="home" style="width:16px;height:16px;"></i> Menu Principal</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/hanzi-writer@3.5/dist/hanzi-writer.min.js"></script>
<script>
(function () {
    'use strict';

    /* ── Config from PHP ─────────────────────────── */
    const CFG = {
        themeId: {{ $theme->id }},
        level: {{ $gameData['level'] }},
        mode: '{{ $gameData["mode"] }}',
        duration: {{ $gameData['duration'] }},
        pairsCount: {{ $gameData['pairs_count'] }},
        matchScore: {{ $gameData['match_score'] }},
        bonusPerfect: {{ $gameData['drawing_bonus_perfect'] }},
        bonusGood: {{ $gameData['drawing_bonus_good'] }},
        csrf: '{{ csrf_token() }}',
        routes: {
            pairs: '/memory-game/api/' + {{ $theme->id }} + '/pairs',
            match: '/memory-game/api/match',
            mismatch: '/memory-game/api/mismatch',
            drawing: '/memory-game/api/drawing',
            end: '/memory-game/api/end',
            gameUrl: '/memory-game/' + {{ $theme->id }},
        },
    };

    /* ── State ───────────────────────────────────── */
    const state = {
        score: 0,
        matchesFound: 0,
        mistakes: 0,
        timeLeft: CFG.duration,
        active: false,
        ended: false,
        cards: [],
        flipped: [],
        matchedIds: new Set(),
        timerRef: null,
        drawingChar: null,
        drawingMistakes: 0,
        drawingPhase: null,
        writer: null,
        locked: false,
    };

    /* ── DOM refs ────────────────────────────────── */
    const $ = (id) => document.getElementById(id);
    const dom = {
        grid: $('grid'),
        score: $('score'),
        matches: $('matches'),
        mistakes: $('mistakes'),
        timer: $('timer'),
        timerFill: $('timerFill'),
        hint: $('hint'),
        drawingOverlay: $('drawingOverlay'),
        gameOverModal: $('gameOverModal'),
        finalScore: $('finalScore'),
        finalMatches: $('finalMatches'),
        finalMistakes: $('finalMistakes'),
        finalAccuracy: $('finalAccuracy'),
        resultTitle: $('resultTitle'),
        resultSubtitle: $('resultSubtitle'),
    };

    /* ── API helper ──────────────────────────────── */
    async function api(url, body = {}) {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CFG.csrf },
            body: JSON.stringify(body),
        });
        return res.json();
    }

    /* ── Sound ───────────────────────────────────── */
    let audioCtx;
    function playTone(freq, type, dur) {
        try {
            if (!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = type;
            osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
            gain.gain.setValueAtTime(0.04, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + dur);
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.start();
            osc.stop(audioCtx.currentTime + dur);
        } catch (_) {}
    }

    function soundCorrect() { playTone(880, 'triangle', 0.15); setTimeout(() => playTone(1320, 'triangle', 0.12), 80); }
    function soundWrong() { playTone(220, 'square', 0.2); }
    function soundMatch() { playTone(660, 'triangle', 0.1); setTimeout(() => playTone(990, 'triangle', 0.1), 60); setTimeout(() => playTone(1320, 'triangle', 0.15), 120); }

    /* ── Particles ───────────────────────────────── */
    function burst(x, y, cls, count = 8) {
        for (let i = 0; i < count; i++) {
            const p = document.createElement('span');
            p.className = 'particle ' + cls;
            const angle = (Math.PI * 2 * i) / count;
            const dist = 18 + Math.random() * 28;
            p.style.left = x + 'px';
            p.style.top = y + 'px';
            p.style.setProperty('--px', Math.cos(angle) * dist + 'px');
            p.style.setProperty('--py', Math.sin(angle) * dist + 'px');
            document.body.appendChild(p);
            setTimeout(() => p.remove(), 700);
        }
    }

    /* ── Render cards ────────────────────────────── */
    function renderCards() {
        dom.grid.innerHTML = '';
        state.cards.forEach((card, i) => {
            const el = document.createElement('div');
            el.className = 'card';
            el.dataset.index = i;
            el.style.animation = `cardEntry 0.35s ease ${i * 0.04}s both`;

            const typeClass = 'type-' + card.type;

            el.innerHTML = `
                <div class="card-inner">
                    <div class="card-face card-front"></div>
                    <div class="card-face card-back ${typeClass}">
                        <span class="card-value">${escapeHtml(card.display)}</span>
                        ${card.type !== 'character' ? '<span class="card-sub">' + escapeHtml(card.hanzi || '') + '</span>' : ''}
                    </div>
                </div>`;

            el.addEventListener('click', () => onCardClick(i));
            dom.grid.appendChild(el);
        });
    }

    function escapeHtml(str) {
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    /* ── Card click ──────────────────────────────── */
    function onCardClick(index) {
        if (!state.active || state.locked) return;
        if (state.matchedIds.has(index) || state.flipped.includes(index)) return;
        if (state.flipped.length >= 2) return;

        state.flipped.push(index);
        const el = dom.grid.children[index];
        el.classList.add('flipped');

        if (state.flipped.length === 2) {
            state.locked = true;
            setTimeout(checkMatch, 700);
        }
    }

    /* ── Check match ─────────────────────────────── */
    function checkMatch() {
        const [i1, i2] = state.flipped;
        const c1 = state.cards[i1];
        const c2 = state.cards[i2];
        const isMatch = c1.pair_id === c2.pair_id;

        if (isMatch) {
            state.matchedIds.add(i1);
            state.matchedIds.add(i2);
            state.matchesFound++;
            state.score += CFG.matchScore;
            state.flipped = [];
            state.locked = false;
            state.active = false;

            const el1 = dom.grid.children[i1];
            const el2 = dom.grid.children[i2];
            el1.classList.add('matched');
            el2.classList.add('matched');

            soundMatch();

            const r1 = el1.getBoundingClientRect();
            const r2 = el2.getBoundingClientRect();
            burst(r1.left + r1.width / 2, r1.top + r1.height / 2, 'hit');
            burst(r2.left + r2.width / 2, r2.top + r2.height / 2, 'hit');

            api(CFG.routes.match, {
                theme_id: CFG.themeId,
                current_score: state.score,
                current_hits: state.matchesFound - 1,
                current_mistakes: state.mistakes,
                current_level: CFG.level,
            }).catch(() => {});

            updateHUD();

            const hanzi = c1.type === 'character' ? c1.value : (c1.hanzi || c2.hanzi);
            if (hanzi) {
                setTimeout(() => showDrawing(hanzi), 400);
            } else if (state.matchesFound === CFG.pairsCount) {
                setTimeout(endGame, 500);
            } else {
                state.active = true;
            }
        } else {
            soundWrong();

            const el1 = dom.grid.children[i1];
            const el2 = dom.grid.children[i2];
            el1.classList.add('wrong');
            el2.classList.add('wrong');

            setTimeout(() => {
                el1.classList.remove('flipped', 'wrong');
                el2.classList.remove('flipped', 'wrong');
            }, 500);

            state.mistakes++;
            state.flipped = [];
            state.locked = false;
            updateHUD();

            api(CFG.routes.mismatch, {
                theme_id: CFG.themeId,
                current_score: state.score,
                current_hits: state.matchesFound,
                current_mistakes: state.mistakes - 1,
                current_level: CFG.level,
            }).catch(() => {});
        }
    }

    /* ── Drawing challenge ───────────────────────── */
    function showDrawing(hanzi) {
        state.active = false;
        state.locked = true;
        stopTimer();
        state.drawingChar = hanzi;
        state.drawingMistakes = 0;
        state.drawingPhase = 'animating';
        dom.drawingOverlay.classList.add('visible');
        updateDrawingUI();

        if (state.writer) { state.writer.destroy(); state.writer = null; }
        document.getElementById('character-target-div').innerHTML = '';

        requestAnimationFrame(() => {
            try {
                state.writer = HanziWriter.create('character-target-div', hanzi, {
                    width: 200,
                    height: 200,
                    padding: 5,
                    showCharacter: false,
                    showOutline: true,
                    outlineColor: 'rgba(124,110,240,0.15)',
                    drawingColor: '#7c6ef0',
                    highlightColor: '#34d399',
                    strokeAnimationSpeed: 1.5,
                    delayBetweenStrokes: 200,
                    strokeColor: '#7c6ef0',
                });

                state.writer.animateCharacter({
                    onComplete: () => {
                        state.drawingPhase = 'quiz';
                        updateDrawingUI();
                        state.writer.quiz({
                            onMistake: () => { state.drawingMistakes++; },
                            onCorrectStroke: () => {},
                            onComplete: () => {
                                state.drawingPhase = 'complete';
                                updateDrawingUI();
                            },
                        });
                    },
                });
            } catch (e) {
                console.warn('HanziWriter error:', e);
                state.drawingPhase = 'quiz';
                updateDrawingUI();
            }
        });
    }

    function updateDrawingUI() {
        const label = document.getElementById('drawingLabel');
        const sub = document.getElementById('drawingSub');
        const actions = document.getElementById('drawingActions');
        if (!label) return;

        switch (state.drawingPhase) {
            case 'animating':
                label.innerHTML = '<i data-lucide="pen-tool" style="width:18px;height:18px;vertical-align:-3px;"></i> Observa como se dibuja';
                sub.textContent = 'Memoriza el orden de los trazos';
                actions.style.display = 'none';
                break;
            case 'quiz':
                label.innerHTML = '<i data-lucide="pen-tool" style="width:18px;height:18px;vertical-align:-3px;"></i> Dibuja el caracter';
                sub.textContent = 'Traza los trazos en orden correcto';
                actions.style.display = 'flex';
                break;
            case 'complete':
                label.textContent = '¡Correcto!';
                sub.textContent = 'Presiona continuar';
                actions.style.display = 'flex';
                break;
        }
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    window.confirmDrawing = async function () {
        if (!state.drawingChar) return;

        try {
            const data = await api(CFG.routes.drawing, {
                theme_id: CFG.themeId,
                current_score: state.score,
                current_hits: state.matchesFound,
                current_mistakes: state.mistakes,
                current_level: CFG.level,
                drawing_mistakes: state.drawingMistakes,
            });

            if (data && data.success) {
                state.score = data.new_score;
                updateHUD();
            }
        } catch (err) {
            console.warn('Error saving drawing:', err);
        }

        closeDrawing();
    };

    window.skipDrawing = function () { closeDrawing(); };

    function closeDrawing() {
        dom.drawingOverlay.classList.remove('visible');
        try { if (state.writer) { state.writer.destroy(); } } catch (_) {}
        state.writer = null;
        state.drawingChar = null;
        state.drawingPhase = null;
        state.locked = false;
        if (!state.ended) {
            if (state.matchesFound === CFG.pairsCount) {
                setTimeout(endGame, 500);
            } else {
                state.active = true;
                startTimer();
            }
        }
    }

    /* ── Mode switching ──────────────────────────── */
    window.switchMode = function (mode) {
        CFG.mode = mode;
        document.querySelectorAll('.mode-btn').forEach(b => {
            b.classList.toggle('active', b.dataset.mode === mode);
        });
        resetGame();
    };

    /* ── HUD ─────────────────────────────────────── */
    function updateHUD() {
        dom.score.textContent = state.score;
        dom.matches.textContent = state.matchesFound + '/' + CFG.pairsCount;
        dom.mistakes.textContent = state.mistakes;

        const hints = {
            'hanzi-pinyin': 'Encuentra las parejas Hanzi ↔ Pinyin',
            'hanzi-meaning': 'Encuentra las parejas Hanzi ↔ Significado',
        };
        dom.hint.textContent = hints[CFG.mode] || hints['hanzi-pinyin'];
    }

    /* ── Timer ───────────────────────────────────── */
    function startTimer() {
        if (state.timerRef) return;
        state.timerRef = setInterval(() => {
            state.timeLeft--;
            dom.timer.textContent = state.timeLeft + 's';

            const pct = (state.timeLeft / CFG.duration) * 100;
            dom.timerFill.style.width = pct + '%';
            dom.timerFill.classList.toggle('warning', pct < 25);

            if (state.timeLeft <= 0) {
                clearInterval(state.timerRef);
                endGame();
            }
        }, 1000);
    }

    function stopTimer() {
        if (state.timerRef) { clearInterval(state.timerRef); state.timerRef = null; }
    }

    /* ── End game ────────────────────────────────── */
    async function endGame() {
        if (state.ended) return;
        state.ended = true;
        state.active = false;
        stopTimer();
        closeDrawing();

        await api(CFG.routes.end, {
            theme_id: CFG.themeId,
            score: state.score,
            hits: state.matchesFound,
            mistakes: state.mistakes,
            duration: CFG.duration - state.timeLeft,
            level: CFG.level,
        });

        const total = state.matchesFound + state.mistakes;
        const accuracy = total > 0 ? Math.round((state.matchesFound / total) * 100) : 0;

        if (state.matchesFound === CFG.pairsCount) {
            dom.resultTitle.textContent = '¡Felicidades!';
            dom.resultSubtitle.textContent = 'Completaste todas las parejas';
        } else {
            dom.resultTitle.textContent = '¡Tiempo Agotado!';
            dom.resultSubtitle.textContent = 'Intenta de nuevo para mejorar';
        }

        dom.finalScore.textContent = state.score;
        dom.finalMatches.textContent = state.matchesFound + '/' + CFG.pairsCount;
        dom.finalMistakes.textContent = state.mistakes;
        dom.finalAccuracy.textContent = accuracy + '%';
        dom.gameOverModal.classList.add('visible');
    }

    /* ── Restart ─────────────────────────────────── */
    window.restartSameMode = function () {
        dom.gameOverModal.classList.remove('visible');
        resetGame();
    };

    async function resetGame() {
        stopTimer();
        Object.assign(state, {
            score: 0, matchesFound: 0, mistakes: 0,
            timeLeft: CFG.duration, active: false, ended: false,
            cards: [], flipped: [], matchedIds: new Set(),
            locked: false, drawingChar: null, drawingMistakes: 0, drawingPhase: null,
        });
        dom.timer.textContent = CFG.duration + 's';
        dom.timerFill.style.width = '100%';
        dom.timerFill.classList.remove('warning');
        dom.gameOverModal.classList.remove('visible');
        dom.drawingOverlay.classList.remove('visible');
        updateHUD();
        await initGame();
    }

    /* ── Init ────────────────────────────────────── */
    async function initGame() {
        try {
            const data = await api(CFG.routes.pairs, { level: CFG.level, mode: CFG.mode });
            if (data.success) {
                state.cards = data.pairs;
                renderCards();
                state.active = true;
                updateHUD();
                startTimer();
            }
        } catch (err) {
            console.error('Error initializing game:', err);
        }
    }

    /* ── Bootstrap ───────────────────────────────── */
    updateHUD();
    initGame();
})();
</script>
@endsection
