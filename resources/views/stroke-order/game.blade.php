@extends('layouts.game')

@section('styles')
<style>
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
        --color-secondary-dark: #d47573;
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
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { overflow: hidden !important; }
    .container { max-width: none !important; padding: 0 !important; margin: 0 !important; height: 100vh !important; }

    .game-shell {
        display: flex; flex-direction: column; height: 100vh; width: 100vw; position: fixed; inset: 0;
        background: var(--color-bg);
        color: var(--color-text); font-family: var(--font-sans);
    }

    .hud {
        display: flex; align-items: center; justify-content: space-between; padding: 14px 24px;
        background: var(--color-surface);
        border-bottom: 1px solid var(--color-border-light); flex-shrink: 0; z-index: 10;
        gap: 12px; flex-wrap: wrap;
    }
    .hud-brand { display: flex; align-items: center; gap: 10px; min-width: 0; }
    .hud-brand h2 {
        font-size: 1.05rem; font-weight: 700; white-space: nowrap;
        color: var(--color-text); background: none; -webkit-text-fill-color: unset;
    }
    .hud-badge {
        font-size: 0.65rem; padding: 3px 8px; border-radius: 20px;
        background: var(--color-primary-50); color: var(--color-primary);
        font-weight: 600; letter-spacing: 0.03em; text-transform: uppercase; white-space: nowrap;
    }
    .hud-stats { display: flex; gap: 6px; flex-wrap: wrap; }
    .stat-chip {
        display: flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 10px;
        background: var(--color-bg); border: 1px solid var(--color-border-light);
        font-size: 0.8rem; white-space: nowrap; transition: background 0.2s;
    }
    .stat-chip .icon { font-size: 0.9rem; opacity: 0.7; display: flex; align-items: center; }
    .stat-chip .label { color: var(--color-text-muted); font-size: 0.7rem; }
    .stat-chip .value { font-weight: 700; font-size: 0.95rem; }
    .stat-chip.timer .value { font-variant-numeric: tabular-nums; }
    .stat-chip.mistakes .value { color: var(--color-secondary); }

    .exit-chip {
        background: var(--color-secondary-50); border: 1px solid rgba(232,145,143,0.3);
        color: var(--color-secondary); text-decoration: none; cursor: pointer; transition: all 0.25s;
    }
    .exit-chip:hover { background: rgba(232,145,143,0.15); border-color: rgba(232,145,143,0.5); color: var(--color-secondary-dark); }

    .board {
        flex: 1; display: flex; flex-direction: row; align-items: stretch;
        justify-content: center; padding: 20px 24px; overflow: auto; gap: 24px;
    }

    .preview-panel {
        flex: 0 0 40%; max-width: 400px; min-width: 220px;
        display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 16px;
        background: var(--color-surface); border: 1px solid var(--color-border-light);
        border-radius: var(--radius-xl); padding: 28px 20px; box-shadow: var(--shadow-sm);
    }
    .preview-panel .hanzi-label {
        font-size: 0.7rem; color: var(--color-text-muted); text-transform: uppercase;
        letter-spacing: 0.1em; font-weight: 600;
    }
    .char-anim-wrap {
        width: 180px; height: 180px; position: relative;
        border-radius: var(--radius-lg); overflow: hidden;
        background: var(--color-bg); border: 1px solid var(--color-border-light);
    }
    .char-preview .pinyin {
        font-size: 1.1rem; color: var(--color-text-muted);
    }

    .strokes-panel {
        flex: 1 1 55%; min-width: 0;
        display: flex; flex-direction: column; align-items: center; gap: 12px;
    }
    .strokes-label {
        font-size: 0.8rem; color: var(--color-text-muted); text-transform: uppercase;
        letter-spacing: 0.08em; font-weight: 600;
    }
    .strokes-grid {
        display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;
        min-height: 120px; padding: 16px; border-radius: var(--radius-lg);
        background: var(--color-surface); border: 2px dashed var(--color-border);
        transition: border-color 0.3s; flex: 1; align-content: flex-start;
    }
    .strokes-grid.drag-over { border-color: {{ $theme->color_primary }}; }

    .stroke-card {
        width: 160px; height: 180px; border-radius: 14px; cursor: grab; user-select: none;
        background: var(--color-surface); border: 2px solid var(--color-border);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 4px; transition: all 0.2s; position: relative; touch-action: none;
    }
    .stroke-card:active { cursor: grabbing; }
    .stroke-card:hover { border-color: var(--color-primary-100); background: var(--color-primary-50); transform: scale(1.05); }
    .stroke-card.dragging { opacity: 0.4; transform: scale(0.95); }
    .stroke-card.drag-target { border-color: {{ $theme->color_primary }}; background: rgba(139, 92, 246, 0.1); }

    .stroke-card .stroke-num {
        position: absolute; top: 6px; left: 8px; font-size: 0.7rem; font-weight: 700;
        color: var(--color-text-light);
    }
    .stroke-card svg { width: 130px; height: 130px; transform: scaleY(-1); }
    .stroke-card svg .stroke-outline { fill: none; stroke: var(--color-text-light); stroke-width: 80; stroke-linecap: round; stroke-linejoin: round; }
    .stroke-card svg .stroke-prev { fill: none; stroke: var(--color-text); stroke-width: 80; stroke-linecap: round; stroke-linejoin: round; opacity: 0.8; }
    .stroke-card svg .stroke-current { fill: none; stroke: var(--color-primary); stroke-width: 80; stroke-linecap: round; stroke-linejoin: round; filter: drop-shadow(0 0 4px rgba(124,110,240,0.3)); }

    .stroke-card.correct { border-color: var(--color-accent); background: var(--color-accent-50); }
    .stroke-card.wrong { border-color: var(--color-secondary); background: var(--color-secondary-50); animation: shake 0.4s ease; }

    .stroke-card.pos-correct { border-color: var(--color-accent); background: rgba(107,197,160,0.08); }
    .stroke-card.pos-wrong { border-color: var(--color-secondary); background: rgba(232,145,143,0.08); }

    .stroke-card.locked {
        border-color: var(--color-accent); background: var(--color-accent-50);
        cursor: default; pointer-events: none;
    }
    .stroke-card.locked .lock-icon {
        display: block; position: absolute; top: 4px; right: 6px; color: var(--color-accent); width: 14px; height: 14px;
    }

    .actions { display: flex; gap: 12px; justify-content: center; margin-top: auto; padding-top: 8px; }
    .actions button {
        padding: 12px 32px; border-radius: 12px; border: none;
        font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: all 0.25s;
    }
    .btn-check {
        background: var(--color-primary); color: #fff;
        box-shadow: 0 2px 8px rgba(124,110,240,0.25);
    }
    .btn-check:hover { box-shadow: 0 4px 16px rgba(124,110,240,0.35); transform: translateY(-1px); }
    .btn-check:disabled { opacity: 0.4; cursor: not-allowed; transform: none; box-shadow: none; }
    .btn-skip {
        background: var(--color-bg); color: var(--color-text-muted);
        border: 1px solid var(--color-border);
    }
    .btn-skip:hover { background: var(--color-primary-50); color: var(--color-primary); }

    .footer-bar {
        flex-shrink: 0; padding: 10px 24px; background: var(--color-surface);
        border-top: 1px solid var(--color-border-light);
        display: flex; flex-direction: column; gap: 6px; align-items: center;
    }
    .timer-track { width: 100%; height: 4px; border-radius: 2px; background: var(--color-border-light); overflow: hidden; }
    .timer-fill {
        height: 100%; border-radius: 2px;
        background: linear-gradient(90deg, {{ $theme->color_primary }}, {{ $theme->color_secondary }});
        transition: width 1s linear;
    }
    .timer-fill.warning { background: linear-gradient(90deg, #f59e0b, #ef4444); }
    .footer-hint { font-size: 0.72rem; color: var(--color-text-muted); }

    .overlay {
        display: none; position: fixed; inset: 0;
        background: rgba(45, 42, 74, 0.4); backdrop-filter: blur(8px);
        z-index: 500; align-items: center; justify-content: center;
    }
    .overlay.visible { display: flex; }

    .modal {
        background: var(--color-surface); border: 1px solid var(--color-border-light);
        border-radius: 24px; padding: 40px; text-align: center; width: min(480px, 90vw);
        box-shadow: var(--shadow-xl);
        animation: popIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .modal h2 {
        font-size: 1.6rem; font-weight: 800; margin-bottom: 4px;
        color: var(--color-text); background: none; -webkit-text-fill-color: unset;
    }
    .modal .subtitle { color: var(--color-text-muted); font-size: 0.85rem; margin-bottom: 28px; }
    .result-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 28px; }
    .result-item {
        background: var(--color-bg); border: 1px solid var(--color-border-light);
        border-radius: 14px; padding: 16px 12px;
    }
    .result-item .r-label {
        font-size: 0.7rem; color: var(--color-text-muted); text-transform: uppercase;
        letter-spacing: 0.06em; margin-bottom: 6px;
    }
    .result-item .r-value { font-size: 1.6rem; font-weight: 800; color: var(--color-text); }
    .result-item .r-value.accent { color: var(--color-primary); }
    .modal-actions { display: flex; flex-direction: column; gap: 10px; }
    .modal-actions button, .modal-actions a {
        padding: 12px; border-radius: 12px; font-weight: 700; font-size: 0.9rem;
        cursor: pointer; border: none; transition: all 0.25s; text-decoration: none;
        text-align: center; display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .modal-actions .btn-main {
        background: var(--color-primary); color: #fff;
    }
    .modal-actions .btn-main:hover { box-shadow: 0 6px 20px rgba(124,110,240,0.35); transform: translateY(-2px); }
    .modal-actions .btn-ghost {
        background: var(--color-bg); color: var(--color-text-muted);
        border: 1px solid var(--color-border);
    }
    .modal-actions .btn-ghost:hover { background: var(--color-primary-50); color: var(--color-primary); }

    .loading-msg { color: var(--color-text-muted); font-size: 0.9rem; padding: 40px; text-align: center; width: 100%; align-self: center; }

    .particle {
        position: fixed; width: 6px; height: 6px; border-radius: 50%;
        pointer-events: none; z-index: 100; opacity: 1;
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
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20% { transform: translateX(-6px); }
        40% { transform: translateX(6px); }
        60% { transform: translateX(-4px); }
        80% { transform: translateX(4px); }
    }
    @keyframes successPulse {
        0% { box-shadow: 0 0 0 0 rgba(107,197,160,0.5); }
        70% { box-shadow: 0 0 0 12px rgba(107,197,160,0); }
        100% { box-shadow: 0 0 0 0 rgba(107,197,160,0); }
    }

    @media (max-width: 700px) {
        .board { flex-direction: column; padding: 14px; gap: 16px; }
        .preview-panel { flex: none; max-width: none; min-width: 0; flex-direction: row; gap: 16px; padding: 16px 20px; }
        .char-anim-wrap { width: 100px; height: 100px; flex-shrink: 0; }
        .strokes-panel { flex: none; }
        .stroke-card { width: 100px; height: 120px; }
        .stroke-card svg { width: 78px; height: 78px; }
        .hud { padding: 10px 14px; }
        .stat-chip { padding: 4px 10px; font-size: 0.72rem; }
    }
</style>
@endsection

@section('content')
<div class="game-shell">
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
                <span class="icon"><i data-lucide="check" style="width:14px;height:14px;"></i></span>
                <span class="label">Acertados</span>
                <span class="value" id="hits">0</span>
            </div>
            <div class="stat-chip mistakes">
                <span class="icon"><i data-lucide="x" style="width:14px;height:14px;"></i></span>
                <span class="value" id="mistakes">0</span>
            </div>
            <div class="stat-chip timer">
                <span class="icon"><i data-lucide="clock" style="width:14px;height:14px;"></i></span>
                <span class="value" id="timer">{{ $gameData['duration'] }}s</span>
            </div>
            <a href="{{ route('games.selectTheme', 'stroke-order') }}" class="stat-chip exit-chip" onclick="return confirm('¿Seguro que quieres salir? Perderás el progreso.')">
                <span class="icon"><i data-lucide="log-out" style="width:14px;height:14px;"></i></span>
                <span class="label">Salir</span>
            </a>
        </div>
    </div>

    <div class="board" id="board">
        <div class="preview-panel" id="charPreview" style="display:none;">
            <div class="char-anim-wrap" id="charAnimWrap"></div>
            <div class="char-preview">
                <span class="hanzi-label">Pinyin</span>
                <span class="pinyin" id="currentPinyin"></span>
            </div>
        </div>

        <div class="strokes-panel" id="strokesArea" style="display:none;">
            <span class="strokes-label">Arrastra los trazos al orden correcto</span>
            <div class="strokes-grid" id="strokesGrid"></div>
            <div class="actions" id="gameActions">
                <button class="btn-skip" onclick="nextCharacter()">Saltar</button>
                <button class="btn-check" id="btnCheck" onclick="checkOrder()">Verificar Orden</button>
            </div>
        </div>

        <div class="loading-msg" id="loadingMsg">Cargando carácter...</div>
    </div>

    <div class="footer-bar">
        <div class="timer-track">
            <div class="timer-fill" id="timerFill" style="width:100%"></div>
        </div>
        <span class="footer-hint" id="hint">Ordena los trazos del carácter en el orden correcto</span>
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
                <div class="r-label">Acertados</div>
                <div class="r-value" id="finalHits">0</div>
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
            <button class="btn-main" onclick="restartGame()"><i data-lucide="rotate-ccw" style="width:16px;height:16px;"></i> Jugar de Nuevo</button>
            <a class="btn-ghost" href="{{ route('games.selectTheme', 'stroke-order') }}"><i data-lucide="palette" style="width:16px;height:16px;"></i> Cambiar Tema</a>
            <a class="btn-ghost" href="{{ route('games.index') }}"><i data-lucide="home" style="width:16px;height:16px;"></i> Menú Principal</a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/hanzi-writer@3.5/dist/hanzi-writer.min.js"></script>
<script>
(function () {
    'use strict';

    const CFG = {
        themeId: {{ $theme->id }},
        level: {{ $gameData['level'] }},
        duration: {{ $gameData['duration'] }},
        correctScore: {{ $gameData['correct_score'] }},
        maxAttempts: {{ $gameData['max_attempts'] }},
        csrf: '{{ csrf_token() }}',
        routes: {
            character: '/stroke-order/api/' + {{ $theme->id }} + '/character',
            attempt: '/stroke-order/api/attempt',
            end: '/stroke-order/api/end',
        },
    };

    const state = {
        score: 0, hits: 0, mistakes: 0, timeLeft: CFG.duration,
        active: false, ended: false, timerRef: null,
        character: null, strokeCount: 0, correctOrder: [],
        currentAttempts: 0, loading: false,
        previewWriter: null, animTimerRef: null,
    };

    const $ = (id) => document.getElementById(id);
    const dom = {
        score: $('score'), hits: $('hits'), mistakes: $('mistakes'),
        timer: $('timer'), timerFill: $('timerFill'), hint: $('hint'),
        loadingMsg: $('loadingMsg'), charPreview: $('charPreview'),
        currentPinyin: $('currentPinyin'),
        strokesArea: $('strokesArea'), strokesGrid: $('strokesGrid'),
        gameActions: $('gameActions'), btnCheck: $('btnCheck'),
        gameOverModal: $('gameOverModal'),
        finalScore: $('finalScore'), finalHits: $('finalHits'),
        finalMistakes: $('finalMistakes'), finalAccuracy: $('finalAccuracy'),
        resultTitle: $('resultTitle'), resultSubtitle: $('resultSubtitle'),
    };

    async function api(url, body = {}) {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CFG.csrf },
            body: JSON.stringify(body),
        });
        return res.json();
    }

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
            osc.connect(gain); gain.connect(audioCtx.destination);
            osc.start(); osc.stop(audioCtx.currentTime + dur);
        } catch (_) {}
    }
    function soundCorrect() { playTone(880, 'triangle', 0.15); setTimeout(() => playTone(1320, 'triangle', 0.12), 80); }
    function soundWrong() { playTone(220, 'square', 0.2); }
    function soundWin() { playTone(660, 'triangle', 0.1); setTimeout(() => playTone(990, 'triangle', 0.1), 80); setTimeout(() => playTone(1320, 'triangle', 0.2), 160); }

    function burst(x, y, cls, count) {
        for (let i = 0; i < count; i++) {
            const p = document.createElement('span');
            p.className = 'particle ' + cls;
            const angle = (Math.PI * 2 * i) / count;
            const dist = 18 + Math.random() * 28;
            p.style.left = x + 'px'; p.style.top = y + 'px';
            p.style.setProperty('--px', Math.cos(angle) * dist + 'px');
            p.style.setProperty('--py', Math.sin(angle) * dist + 'px');
            document.body.appendChild(p);
            setTimeout(() => p.remove(), 700);
        }
    }

    function updateHUD() {
        dom.score.textContent = state.score;
        dom.hits.textContent = state.hits;
        dom.mistakes.textContent = state.mistakes;
    }

    function startTimer() {
        if (state.timerRef) return;
        state.timerRef = setInterval(() => {
            state.timeLeft--;
            dom.timer.textContent = state.timeLeft + 's';
            const pct = (state.timeLeft / CFG.duration) * 100;
            dom.timerFill.style.width = pct + '%';
            dom.timerFill.classList.toggle('warning', pct < 25);
            if (state.timeLeft <= 0) { clearInterval(state.timerRef); endGame(); }
        }, 1000);
    }

    function stopTimer() { if (state.timerRef) { clearInterval(state.timerRef); state.timerRef = null; } }

    function renderStrokeCard(index, allStrokes, currentIndex, size, pad) {
        const card = document.createElement('div');
        card.className = 'stroke-card';
        card.dataset.index = index;
        card.draggable = true;
        card.style.animation = `popIn 0.3s ease ${index * 0.05}s both`;

        const num = document.createElement('span');
        num.className = 'stroke-num';
        num.textContent = index + 1;

        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('viewBox', `${-pad} ${-pad} ${size + pad * 2} ${size + pad * 2}`);
        svg.setAttribute('preserveAspectRatio', 'xMidYMid meet');

        for (let s = 0; s < allStrokes.length; s++) {
            const p = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            p.setAttribute('d', allStrokes[s]);
            if (s < currentIndex) {
                p.setAttribute('class', 'stroke-prev');
            } else if (s === currentIndex) {
                p.setAttribute('class', 'stroke-current');
            } else {
                p.setAttribute('class', 'stroke-outline');
            }
            svg.appendChild(p);
        }

        const lockIcon = document.createElement('i');
        lockIcon.setAttribute('data-lucide', 'lock');
        lockIcon.className = 'lock-icon';
        lockIcon.style.display = 'none';

        card.appendChild(num);
        card.appendChild(svg);
        card.appendChild(lockIcon);

        card.addEventListener('dragstart', onDragStart);
        card.addEventListener('dragend', onDragEnd);
        card.addEventListener('dragover', onDragOver);
        card.addEventListener('drop', onDrop);

        card.addEventListener('touchstart', onTouchStart, { passive: false });
        card.addEventListener('touchmove', onTouchMove, { passive: false });
        card.addEventListener('touchend', onTouchEnd);

        return card;
    }

    let dragSrcEl = null;
    let touchClone = null;
    let touchSource = null;

    function onDragStart(e) {
        if (this.classList.contains('locked')) { e.preventDefault(); return; }
        dragSrcEl = this;
        this.classList.add('dragging');
        clearPositionFeedback();
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', this.dataset.index);
    }

    function onDragEnd() {
        this.classList.remove('dragging');
        dom.strokesGrid.querySelectorAll('.stroke-card').forEach(c => c.classList.remove('drag-target'));
    }

    function onDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        this.classList.toggle('drag-target', dragSrcEl && !this.classList.contains('locked'));
    }

    function onDrop(e) {
        e.preventDefault();
        this.classList.remove('drag-target');
        if (dragSrcEl === this || this.classList.contains('locked') || dragSrcEl.classList.contains('locked')) return;

        swapPositions(dragSrcEl, this);
        updateCardNumbers();
    }

    function onTouchStart(e) {
        if (e.touches.length !== 1) return;
        if (this.classList.contains('locked')) return;
        e.preventDefault();
        clearPositionFeedback();
        touchSource = this;
        const touch = e.touches[0];
        const rect = this.getBoundingClientRect();

        touchClone = this.cloneNode(true);
        touchClone.style.position = 'fixed';
        touchClone.style.zIndex = '9999';
        touchClone.style.width = rect.width + 'px';
        touchClone.style.height = rect.height + 'px';
        touchClone.style.left = (touch.clientX - rect.width / 2) + 'px';
        touchClone.style.top = (touch.clientY - rect.height / 2) + 'px';
        touchClone.style.opacity = '0.85';
        touchClone.style.pointerEvents = 'none';
        touchClone.style.transition = 'none';
        document.body.appendChild(touchClone);

        this.classList.add('dragging');
    }

    function onTouchMove(e) {
        if (!touchClone || !touchSource) return;
        e.preventDefault();
        const touch = e.touches[0];
        const rect = touchClone.getBoundingClientRect();
        touchClone.style.left = (touch.clientX - rect.width / 2) + 'px';
        touchClone.style.top = (touch.clientY - rect.height / 2) + 'px';

        dom.strokesGrid.querySelectorAll('.stroke-card').forEach(c => {
            const r = c.getBoundingClientRect();
            const inside = touch.clientX >= r.left && touch.clientX <= r.right &&
                           touch.clientY >= r.top && touch.clientY <= r.bottom;
            c.classList.toggle('drag-target', inside && c !== touchSource && !c.classList.contains('locked'));
        });
    }

    function onTouchEnd(e) {
        if (!touchClone || !touchSource) return;
        touchClone.remove();
        touchClone = null;
        touchSource.classList.remove('dragging');

        const touch = e.changedTouches[0];
        let dropTarget = null;
        dom.strokesGrid.querySelectorAll('.stroke-card').forEach(c => {
            c.classList.remove('drag-target');
            const r = c.getBoundingClientRect();
            if (touch.clientX >= r.left && touch.clientX <= r.right &&
                touch.clientY >= r.top && touch.clientY <= r.bottom && c !== touchSource) {
                dropTarget = c;
            }
        });

        if (dropTarget && !dropTarget.classList.contains('locked') && !touchSource.classList.contains('locked')) {
            swapPositions(touchSource, dropTarget);
            updateCardNumbers();
        }

        touchSource = null;
    }

    function updateCardNumbers() {
        getVisualCards().forEach((card, i) => {
            const num = card.querySelector('.stroke-num');
            if (num) num.textContent = i + 1;
        });
    }

    async function loadCharacter() {
        if (state.loading) return;
        state.loading = true;
        state.currentAttempts = 0;
        checking = false;

        dom.loadingMsg.style.display = 'block';
        dom.charPreview.style.display = 'none';
        dom.strokesArea.style.display = 'none';

        try {
            const data = await api(CFG.routes.character, { level: CFG.level });
            if (!data.success || !data.character) {
                dom.loadingMsg.textContent = 'No hay caracteres disponibles';
                return;
            }

            state.character = data.character;
            dom.currentPinyin.textContent = data.character.pinyin;

            const hanziStr = data.character.hanzi;
            const chars = [...hanziStr].filter(c => c.trim());

            async function fetchCharData(singleChar) {
                try {
                    return await new Promise((resolve, reject) => {
                        const timeout = setTimeout(() => reject(new Error('Timeout')), 10000);
                        HanziWriter.loadCharacterData(singleChar).then(d => {
                            clearTimeout(timeout);
                            resolve(d);
                        }).catch(reject);
                    });
                } catch (_) {
                    const res = await fetch('https://cdn.jsdelivr.net/npm/hanzi-writer-data@2.0/' + encodeURIComponent(singleChar) + '.json');
                    if (!res.ok) throw new Error('No stroke data for ' + singleChar);
                    return await res.json();
                }
            }

            let charData;
            if (chars.length === 1) {
                charData = await fetchCharData(chars[0]);
            } else {
                const allData = await Promise.all(chars.map(c => fetchCharData(c)));
                const mergedStrokes = [];
                const mergedMedians = [];
                let totalWidth = 0;
                for (const d of allData) {
                    const offset = totalWidth;
                    mergedStrokes.push(...d.strokes);
                    mergedMedians.push(...d.medians.map(medians =>
                        medians.map(([x, y]) => [x + offset, y])
                    ));
                    totalWidth += d.width;
                }
                charData = {
                    strokes: mergedStrokes,
                    medians: mergedMedians,
                    width: totalWidth,
                };
            }

            state.strokeCount = charData.strokes.length;
            state.correctOrder = charData.strokes.map((_, i) => i);

            renderStrokes(charData);
            if (typeof lucide !== 'undefined') lucide.createIcons();

            if (state.animTimerRef) { clearInterval(state.animTimerRef); state.animTimerRef = null; }
            try { HanziWriter.remove && HanziWriter.remove('charAnimWrap'); } catch (_) {}
            const wrap = dom.charPreview.querySelector('#charAnimWrap');
            if (wrap) wrap.innerHTML = '';

            const firstChar = chars[0];
            state.previewWriter = HanziWriter.create('charAnimWrap', firstChar, {
                width: 180, height: 180, padding: 10,
                showOutline: true, showCharacter: true,
                strokeColor: '#7c6ef0', outlineColor: 'rgba(124,110,240,0.15)',
                strokeAnimationSpeed: 1.2, delayBetweenStrokes: 200,
            });
            setTimeout(() => {
                try { state.previewWriter.animateCharacter(); } catch (_) {}
            }, 400);
            state.animTimerRef = setInterval(() => {
                if (!state.ended) try { state.previewWriter.animateCharacter(); } catch (_) {}
            }, 8000);

            dom.loadingMsg.style.display = 'none';
            dom.charPreview.style.display = 'flex';
            dom.strokesArea.style.display = 'flex';
            dom.btnCheck.disabled = false;
            state.loading = false;
            state.active = true;

        } catch (err) {
            console.error('Error loading character:', err);
            dom.loadingMsg.textContent = 'Error al cargar carácter. Reintentando...';
            setTimeout(() => { state.loading = false; loadCharacter(); }, 2000);
        }
    }

    function renderStrokes(charData) {
        dom.strokesGrid.innerHTML = '';
        const shuffled = [...state.correctOrder];
        for (let i = shuffled.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
        }

        const size = charData.width || 1024;
        const pad = 180;
        shuffled.forEach((origIndex, pos) => {
            const card = renderStrokeCard(pos, charData.strokes, origIndex, size, pad);
            card.dataset.correctIndex = origIndex;
            card.style.order = pos;
            dom.strokesGrid.appendChild(card);
        });
    }

    let checking = false;

    function swapPositions(a, b) {
        const oa = a.style.order;
        a.style.order = b.style.order;
        b.style.order = oa;
    }

    function getVisualCards() {
        return [...dom.strokesGrid.children].sort((a, b) => (parseInt(a.style.order) || 0) - (parseInt(b.style.order) || 0));
    }

    function getUserOrder() {
        return getVisualCards().map(c => parseInt(c.dataset.correctIndex));
    }

    function showPositionFeedback() {
        const visual = getVisualCards();
        visual.forEach((card, i) => {
            if (card.classList.contains('locked')) return;
            card.classList.remove('pos-correct', 'pos-wrong');
            if (parseInt(card.dataset.correctIndex) === state.correctOrder[i]) {
                card.classList.add('pos-correct');
            } else {
                card.classList.add('pos-wrong');
            }
        });
    }

    function clearPositionFeedback() {
        dom.strokesGrid.querySelectorAll('.stroke-card').forEach(c => {
            c.classList.remove('pos-correct', 'pos-wrong');
        });
    }

    window.checkOrder = function () {
        if (checking || state.ended || state.loading) return;
        checking = true;
        dom.btnCheck.disabled = true;

        const visual = getVisualCards();
        const userOrder = getUserOrder();
        const isCorrect = userOrder.every((val, i) => val === state.correctOrder[i]);

        if (isCorrect) {
            soundWin();
            visual.forEach((card, i) => {
                setTimeout(() => {
                    card.classList.add('correct');
                    card.style.animation = 'successPulse 0.6s ease';
                    const r = card.getBoundingClientRect();
                    burst(r.left + r.width / 2, r.top + r.height / 2, 'hit', 6);
                }, i * 80);
            });

            state.score += CFG.correctScore;
            state.hits++;

            api(CFG.routes.attempt, {
                theme_id: CFG.themeId,
                character_id: state.character.id,
                current_score: state.score,
                correct: true,
                current_attempts: state.currentAttempts,
            }).catch(() => {});

            updateHUD();
            setTimeout(() => { checking = false; nextCharacter(); }, 1500);
        } else {
            soundWrong();
            state.currentAttempts++;
            state.mistakes++;

            api(CFG.routes.attempt, {
                theme_id: CFG.themeId,
                character_id: state.character.id,
                current_score: state.score,
                correct: false,
                current_attempts: state.currentAttempts,
            }).catch(() => {});

            visual.forEach((card, i) => {
                if (userOrder[i] !== state.correctOrder[i]) {
                    card.classList.add('wrong');
                    const r = card.getBoundingClientRect();
                    burst(r.left + r.width / 2, r.top + r.height / 2, 'miss', 4);
                } else {
                    card.classList.add('correct');
                }
            });

            updateHUD();

            setTimeout(() => {
                visual.forEach((card, i) => {
                    card.classList.remove('wrong', 'correct');
                    const lockIcon = card.querySelector('.lock-icon');
                    if (userOrder[i] === state.correctOrder[i]) {
                        card.classList.add('locked');
                        card.draggable = false;
                        if (lockIcon) lockIcon.style.display = 'block';
                    } else {
                        if (lockIcon) lockIcon.style.display = 'none';
                    }
                });
                showPositionFeedback();
                checking = false;
                dom.btnCheck.disabled = false;
            }, 1200);
        }
    };

    window.nextCharacter = function () {
        state.active = false;
        loadCharacter();
    };

    window.restartGame = function () {
        dom.gameOverModal.classList.remove('visible');
        state.score = 0; state.hits = 0; state.mistakes = 0;
        state.timeLeft = CFG.duration; state.ended = false;
        if (state.animTimerRef) { clearInterval(state.animTimerRef); state.animTimerRef = null; }
        dom.timer.textContent = CFG.duration + 's';
        dom.timerFill.style.width = '100%';
        dom.timerFill.classList.remove('warning');
        updateHUD();
        startTimer();
        loadCharacter();
    };

    async function endGame() {
        if (state.ended) return;
        state.ended = true;
        state.active = false;
        stopTimer();
        if (state.animTimerRef) { clearInterval(state.animTimerRef); state.animTimerRef = null; }

        api(CFG.routes.end, {
            theme_id: CFG.themeId,
            score: state.score,
            hits: state.hits,
            mistakes: state.mistakes,
            duration: CFG.duration - state.timeLeft,
            level: CFG.level,
        }).catch(() => {});

        const total = state.hits + state.mistakes;
        const accuracy = total > 0 ? Math.round((state.hits / total) * 100) : 0;

        if (state.hits > 0) {
            dom.resultTitle.textContent = '¡Buen trabajo!';
            dom.resultSubtitle.textContent = 'Caracteres ordenados correctamente';
        } else {
            dom.resultTitle.textContent = '¡Tiempo Agotado!';
            dom.resultSubtitle.textContent = 'Intenta de nuevo para mejorar';
        }

        dom.finalScore.textContent = state.score;
        dom.finalHits.textContent = state.hits;
        dom.finalMistakes.textContent = state.mistakes;
        dom.finalAccuracy.textContent = accuracy + '%';
        dom.gameOverModal.classList.add('visible');
    }

    updateHUD();
    startTimer();
    loadCharacter();
})();
</script>
@endsection
