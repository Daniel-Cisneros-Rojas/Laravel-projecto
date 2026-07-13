@extends('layouts.game')

@section('styles')
<style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { overflow: hidden !important; }
    .container { max-width: none !important; padding: 0 !important; margin: 0 !important; height: 100vh !important; }

    .game-shell {
        display: flex; flex-direction: column; height: 100vh; width: 100vw; position: fixed; inset: 0;
        background: linear-gradient(160deg, #0f0c29 0%, #1a1145 40%, #24243e 100%);
        color: #e2e8f0; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    .hud {
        display: flex; align-items: center; justify-content: space-between; padding: 14px 24px;
        background: rgba(255,255,255,0.04); backdrop-filter: blur(12px);
        border-bottom: 1px solid rgba(255,255,255,0.06); flex-shrink: 0; z-index: 10;
        gap: 12px; flex-wrap: wrap;
    }
    .hud-brand { display: flex; align-items: center; gap: 10px; min-width: 0; }
    .hud-brand h2 {
        font-size: 1.05rem; font-weight: 700; white-space: nowrap;
        background: linear-gradient(135deg, {{ $theme->color_primary }}, {{ $theme->color_secondary }});
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .hud-badge {
        font-size: 0.65rem; padding: 3px 8px; border-radius: 20px;
        background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.6);
        font-weight: 600; letter-spacing: 0.03em; text-transform: uppercase; white-space: nowrap;
    }
    .hud-stats { display: flex; gap: 6px; flex-wrap: wrap; }
    .stat-chip {
        display: flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 10px;
        background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.06);
        font-size: 0.8rem; white-space: nowrap; transition: background 0.2s;
    }
    .stat-chip .icon { font-size: 0.9rem; opacity: 0.7; }
    .stat-chip .label { color: rgba(255,255,255,0.5); font-size: 0.7rem; }
    .stat-chip .value { font-weight: 700; font-size: 0.95rem; }
    .stat-chip.timer .value { font-variant-numeric: tabular-nums; }
    .stat-chip.mistakes .value { color: #f87171; }

    .exit-chip {
        background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2);
        color: rgba(239, 68, 68, 0.7); text-decoration: none; cursor: pointer; transition: all 0.25s;
    }
    .exit-chip:hover { background: rgba(239, 68, 68, 0.2); border-color: rgba(239, 68, 68, 0.4); color: #f87171; }

    .board {
        flex: 1; display: flex; flex-direction: column; align-items: center;
        justify-content: center; padding: 24px; overflow: auto; gap: 24px;
    }

    .char-preview {
        display: flex; flex-direction: column; align-items: center; gap: 6px;
    }
    .char-preview .hanzi {
        font-size: 3.5rem; font-weight: 800; color: #fff;
        text-shadow: 0 0 20px {{ $theme->color_primary }}66;
    }
    .char-preview .pinyin {
        font-size: 1rem; color: rgba(255,255,255,0.5);
    }

    .strokes-area {
        display: flex; flex-direction: column; align-items: center; gap: 12px; width: 100%; max-width: 600px;
    }
    .strokes-label {
        font-size: 0.8rem; color: rgba(255,255,255,0.4); text-transform: uppercase;
        letter-spacing: 0.08em; font-weight: 600;
    }
    .strokes-grid {
        display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;
        min-height: 120px; padding: 16px; border-radius: 16px;
        background: rgba(255,255,255,0.03); border: 2px dashed rgba(255,255,255,0.08);
        transition: border-color 0.3s;
    }
    .strokes-grid.drag-over { border-color: {{ $theme->color_primary }}; }

    .stroke-card {
        width: 140px; height: 160px; border-radius: 14px; cursor: grab; user-select: none;
        background: rgba(255,255,255,0.06); border: 2px solid rgba(255,255,255,0.1);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 4px; transition: all 0.2s; position: relative; touch-action: none;
    }
    .stroke-card:active { cursor: grabbing; }
    .stroke-card:hover { border-color: rgba(255,255,255,0.2); background: rgba(255,255,255,0.09); transform: scale(1.05); }
    .stroke-card.dragging { opacity: 0.4; transform: scale(0.95); }
    .stroke-card.drag-target { border-color: {{ $theme->color_primary }}; background: rgba(139, 92, 246, 0.1); }

    .stroke-card .stroke-num {
        position: absolute; top: 6px; left: 8px; font-size: 0.7rem; font-weight: 700;
        color: rgba(255,255,255,0.3);
    }
    .stroke-card svg { width: 110px; height: 110px; }
    .stroke-card svg .stroke-outline { fill: none; stroke: rgba(255,255,255,0.12); stroke-width: 80; stroke-linecap: round; stroke-linejoin: round; }
    .stroke-card svg .stroke-prev { fill: none; stroke: rgba(255,255,255,0.4); stroke-width: 80; stroke-linecap: round; stroke-linejoin: round; }
    .stroke-card svg .stroke-current { fill: none; stroke: rgba(255,255,255,0.9); stroke-width: 80; stroke-linecap: round; stroke-linejoin: round; }

    .stroke-card.correct { border-color: #34d399; background: rgba(52,211,153,0.12); }
    .stroke-card.wrong { border-color: #f87171; background: rgba(248,113,113,0.12); animation: shake 0.4s ease; }

    .stroke-card.pos-correct { border-color: #34d399; background: rgba(52,211,153,0.08); }
    .stroke-card.pos-wrong { border-color: #f87171; background: rgba(248,113,113,0.08); }

    .actions { display: flex; gap: 12px; justify-content: center; }
    .actions button {
        padding: 12px 32px; border-radius: 12px; border: none;
        font-weight: 700; font-size: 0.9rem; cursor: pointer; transition: all 0.25s;
    }
    .btn-check {
        background: linear-gradient(135deg, {{ $theme->color_primary }}, {{ $theme->color_secondary }});
        color: #fff;
    }
    .btn-check:hover { box-shadow: 0 4px 16px {{ $theme->color_primary }}66; transform: translateY(-1px); }
    .btn-check:disabled { opacity: 0.4; cursor: not-allowed; transform: none; box-shadow: none; }
    .btn-skip {
        background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.6);
    }
    .btn-skip:hover { background: rgba(255,255,255,0.12); color: #fff; }

    .footer-bar {
        flex-shrink: 0; padding: 10px 24px; background: rgba(255,255,255,0.03);
        border-top: 1px solid rgba(255,255,255,0.06);
        display: flex; flex-direction: column; gap: 6px; align-items: center;
    }
    .timer-track { width: 100%; height: 4px; border-radius: 2px; background: rgba(255,255,255,0.06); overflow: hidden; }
    .timer-fill {
        height: 100%; border-radius: 2px;
        background: linear-gradient(90deg, {{ $theme->color_primary }}, {{ $theme->color_secondary }});
        transition: width 1s linear;
    }
    .timer-fill.warning { background: linear-gradient(90deg, #f59e0b, #ef4444); }
    .footer-hint { font-size: 0.72rem; color: rgba(255,255,255,0.35); }

    .overlay {
        display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.75);
        backdrop-filter: blur(6px); z-index: 500; align-items: center; justify-content: center;
    }
    .overlay.visible { display: flex; }

    .modal {
        background: #1e1b3a; border: 1px solid rgba(255,255,255,0.1);
        border-radius: 24px; padding: 40px; text-align: center; width: min(480px, 90vw);
        animation: popIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .modal h2 {
        font-size: 1.6rem; font-weight: 800; margin-bottom: 4px;
        background: linear-gradient(135deg, {{ $theme->color_primary }}, {{ $theme->color_secondary }});
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .modal .subtitle { color: rgba(255,255,255,0.4); font-size: 0.85rem; margin-bottom: 28px; }
    .result-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 28px; }
    .result-item {
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);
        border-radius: 14px; padding: 16px 12px;
    }
    .result-item .r-label {
        font-size: 0.7rem; color: rgba(255,255,255,0.4); text-transform: uppercase;
        letter-spacing: 0.06em; margin-bottom: 6px;
    }
    .result-item .r-value { font-size: 1.6rem; font-weight: 800; color: #fff; }
    .result-item .r-value.accent { color: {{ $theme->color_primary }}; }
    .modal-actions { display: flex; flex-direction: column; gap: 10px; }
    .modal-actions button, .modal-actions a {
        padding: 12px; border-radius: 12px; font-weight: 700; font-size: 0.9rem;
        cursor: pointer; border: none; transition: all 0.25s; text-decoration: none;
        text-align: center; display: block;
    }
    .modal-actions .btn-main {
        background: linear-gradient(135deg, {{ $theme->color_primary }}, {{ $theme->color_secondary }});
        color: #fff;
    }
    .modal-actions .btn-main:hover { box-shadow: 0 6px 20px {{ $theme->color_primary }}55; transform: translateY(-2px); }
    .modal-actions .btn-ghost { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.6); }
    .modal-actions .btn-ghost:hover { background: rgba(255,255,255,0.10); color: #fff; }

    .loading-msg { color: rgba(255,255,255,0.4); font-size: 0.9rem; padding: 40px; text-align: center; }

    .particle {
        position: fixed; width: 6px; height: 6px; border-radius: 50%;
        pointer-events: none; z-index: 100; opacity: 1;
        animation: particleFly 0.7s ease-out forwards;
    }
    .particle.hit { background: #34d399; box-shadow: 0 0 6px #34d399; }
    .particle.miss { background: #f87171; box-shadow: 0 0 6px #f87171; }

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
        0% { box-shadow: 0 0 0 0 rgba(52,211,153,0.5); }
        70% { box-shadow: 0 0 0 12px rgba(52,211,153,0); }
        100% { box-shadow: 0 0 0 0 rgba(52,211,153,0); }
    }

    @media (max-width: 520px) {
        .stroke-card { width: 100px; height: 120px; }
        .stroke-card svg { width: 78px; height: 78px; }
        .char-preview .hanzi { font-size: 2.5rem; }
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
                <span class="icon">⭐</span>
                <span class="value" id="score">0</span>
            </div>
            <div class="stat-chip">
                <span class="icon">✅</span>
                <span class="label">Acertados</span>
                <span class="value" id="hits">0</span>
            </div>
            <div class="stat-chip mistakes">
                <span class="icon">✕</span>
                <span class="value" id="mistakes">0</span>
            </div>
            <div class="stat-chip timer">
                <span class="icon">⏱</span>
                <span class="value" id="timer">{{ $gameData['duration'] }}s</span>
            </div>
            <a href="{{ route('games.selectTheme', 'stroke-order') }}" class="stat-chip exit-chip" onclick="return confirm('¿Seguro que quieres salir? Perderás el progreso.')">
                <span class="icon">✕</span>
                <span class="label">Salir</span>
            </a>
        </div>
    </div>

    <div class="board" id="board">
        <div class="char-preview" id="charPreview" style="display:none;">
            <span class="hanzi" id="currentHanzi"></span>
            <span class="pinyin" id="currentPinyin"></span>
        </div>

        <div class="strokes-area" id="strokesArea" style="display:none;">
            <span class="strokes-label">Arrastra los trazos al orden correcto</span>
            <div class="strokes-grid" id="strokesGrid"></div>
        </div>

        <div class="loading-msg" id="loadingMsg">Cargando carácter...</div>

        <div class="actions" id="gameActions" style="display:none;">
            <button class="btn-skip" onclick="nextCharacter()">Saltar</button>
            <button class="btn-check" id="btnCheck" onclick="checkOrder()">Verificar Orden</button>
        </div>
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
            <button class="btn-main" onclick="restartGame()">Jugar de Nuevo</button>
            <a class="btn-ghost" href="{{ route('games.selectTheme', 'stroke-order') }}">Cambiar Tema</a>
            <a class="btn-ghost" href="{{ route('games.index') }}">Menú Principal</a>
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
    };

    const $ = (id) => document.getElementById(id);
    const dom = {
        score: $('score'), hits: $('hits'), mistakes: $('mistakes'),
        timer: $('timer'), timerFill: $('timerFill'), hint: $('hint'),
        loadingMsg: $('loadingMsg'), charPreview: $('charPreview'),
        currentHanzi: $('currentHanzi'), currentPinyin: $('currentPinyin'),
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

        card.appendChild(num);
        card.appendChild(svg);

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
        this.classList.add('drag-target');
    }

    function onDrop(e) {
        e.preventDefault();
        this.classList.remove('drag-target');
        if (dragSrcEl === this) return;

        const grid = dom.strokesGrid;
        const allCards = [...grid.children];
        const fromIdx = allCards.indexOf(dragSrcEl);
        const toIdx = allCards.indexOf(this);

        if (fromIdx < toIdx) {
            grid.insertBefore(dragSrcEl, this.nextSibling);
        } else {
            grid.insertBefore(dragSrcEl, this);
        }
        updateCardNumbers();
    }

    function onTouchStart(e) {
        if (e.touches.length !== 1) return;
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
            c.classList.toggle('drag-target', inside && c !== touchSource);
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

        if (dropTarget) {
            const grid = dom.strokesGrid;
            const allCards = [...grid.children];
            const fromIdx = allCards.indexOf(touchSource);
            const toIdx = allCards.indexOf(dropTarget);
            if (fromIdx < toIdx) {
                grid.insertBefore(touchSource, dropTarget.nextSibling);
            } else {
                grid.insertBefore(touchSource, dropTarget);
            }
            updateCardNumbers();
        }

        touchSource = null;
    }

    function updateCardNumbers() {
        dom.strokesGrid.querySelectorAll('.stroke-card').forEach((card, i) => {
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
        dom.gameActions.style.display = 'none';

        try {
            const data = await api(CFG.routes.character, { level: CFG.level });
            if (!data.success || !data.character) {
                dom.loadingMsg.textContent = 'No hay caracteres disponibles';
                return;
            }

            state.character = data.character;
            dom.currentHanzi.textContent = data.character.hanzi;
            dom.currentPinyin.textContent = data.character.pinyin;

            let charData;
            try {
                charData = await new Promise((resolve, reject) => {
                    const timeout = setTimeout(() => reject(new Error('Timeout')), 10000);
                    HanziWriter.loadCharacterData(data.character.hanzi).then(d => {
                        clearTimeout(timeout);
                        resolve(d);
                    }).catch(reject);
                });
            } catch (_) {
                const res = await fetch('https://cdn.jsdelivr.net/npm/hanzi-writer-data@2.0/' + encodeURIComponent(data.character.hanzi) + '.json');
                if (!res.ok) throw new Error('No stroke data');
                charData = await res.json();
            }

            state.strokeCount = charData.strokes.length;
            state.correctOrder = charData.strokes.map((_, i) => i);

            renderStrokes(charData);

            dom.loadingMsg.style.display = 'none';
            dom.charPreview.style.display = 'flex';
            dom.strokesArea.style.display = 'flex';
            dom.gameActions.style.display = 'flex';
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
            dom.strokesGrid.appendChild(card);
        });
    }

    let checking = false;

    function getUserOrder() {
        return [...dom.strokesGrid.children].map(c => parseInt(c.dataset.correctIndex));
    }

    function showPositionFeedback() {
        const userOrder = getUserOrder();
        dom.strokesGrid.querySelectorAll('.stroke-card').forEach((card, i) => {
            card.classList.remove('pos-correct', 'pos-wrong');
            if (userOrder[i] === state.correctOrder[i]) {
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

        const cards = [...dom.strokesGrid.children];
        const userOrder = getUserOrder();
        const isCorrect = userOrder.every((val, i) => val === state.correctOrder[i]);

        if (isCorrect) {
            soundWin();
            cards.forEach((card, i) => {
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

            cards.forEach((card, i) => {
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
                cards.forEach(c => { c.classList.remove('wrong', 'correct'); });
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
