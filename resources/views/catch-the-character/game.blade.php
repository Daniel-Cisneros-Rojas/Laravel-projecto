@extends('layouts.game')

@section('styles')
<style>
    body {
        font-family: var(--font-sans);
        overflow: hidden !important;
    }

    .container {
        max-width: none !important;
        padding: 0 !important;
        margin: 0 !important;
        height: 100vh !important;
    }

    .game-container {
        background: var(--color-surface);
        border-radius: 0;
        overflow: hidden;
        box-shadow: none;
        display: flex;
        flex-direction: column;
        height: 100vh;
        width: 100vw;
        position: fixed;
        top: 0;
        left: 0;
    }

    .game-header {
        background: var(--color-surface);
        border-bottom: 1px solid var(--color-border-light);
        color: var(--color-text);
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        flex-shrink: 0;
        min-height: 120px;
    }

    .game-header h2 {
        margin: 0;
        font-size: 1.5em;
    }

    .hud-stat {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
    }

    .hud-label {
        font-size: 0.8em;
        color: var(--color-text-muted);
    }

    .hud-value {
        font-size: 1.5em;
        font-weight: bold;
        color: var(--color-text);
    }

    .exit-chip {
        display: flex; align-items: center; gap: 6px; padding: 8px 14px;
        background: var(--color-secondary-50); border: 1px solid rgba(232,145,143,0.3);
        border-radius: 999px; color: var(--color-secondary); font-weight: 600;
        font-size: 0.82rem; cursor: pointer; transition: all 0.25s;
        text-decoration: none; white-space: nowrap;
    }
    .exit-chip:hover { background: rgba(232,145,143,0.15); border-color: rgba(232,145,143,0.5); }

    .game-board {
        flex: 1;
        position: relative;
        background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%);
        overflow: hidden;
    }

    .character-card {
        position: absolute;
        background: var(--color-surface);
        border: 1px solid var(--color-border-light);
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        box-shadow: var(--shadow-md);
        transition: all 0.1s ease;
        user-select: none;
        min-width: 80px;
    }

    .character-card:hover {
        transform: scale(1.05);
        box-shadow: var(--shadow-lg);
    }

    .character-card.correct {
        border: 3px solid var(--color-success);
        animation: correctPop 0.35s ease;
    }

    .character-card.incorrect {
        border: 3px solid var(--color-error);
        animation: incorrectShake 0.35s ease;
    }

    .feedback-particle {
        position: absolute;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        pointer-events: none;
        z-index: 100;
        opacity: 1;
        animation: particleOut 0.8s ease-out forwards;
    }

    .feedback-particle.correct {
        background: var(--color-accent);
        box-shadow: 0 0 8px rgba(107,197,160,0.5);
    }

    .feedback-particle.incorrect {
        background: var(--color-secondary);
        box-shadow: 0 0 8px rgba(232,145,143,0.5);
    }

    .hanzi {
        font-size: 2.5em;
        font-weight: bold;
        color: var(--color-text);
        margin-bottom: 5px;
    }

    .pinyin {
        font-size: 0.9em;
        color: var(--color-text-muted);
    }

    .game-footer {
        background: var(--color-surface);
        padding: 15px 20px;
        text-align: center;
        border-top: 1px solid var(--color-border-light);
    }

    .progress-bar {
        width: 100%;
        height: 6px;
        background: var(--color-border);
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--color-primary), var(--color-primary-light));
        transition: width 0.1s linear;
    }

    .game-over-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(45, 42, 74, 0.4);
        backdrop-filter: blur(8px);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }

    .game-over-modal.show {
        display: flex;
    }

    .modal-content {
        background: var(--color-surface);
        border-radius: var(--radius-lg);
        padding: 40px;
        text-align: center;
        max-width: 500px;
        box-shadow: var(--shadow-lg);
        animation: slideUp 0.3s ease;
    }

    .modal-content h2 {
        color: var(--color-text);
        margin-bottom: 20px;
        font-size: 2em;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 25px;
    }

    .stat-item {
        background: var(--color-bg);
        border: 1px solid var(--color-border-light);
        border-radius: var(--radius-md);
        padding: 15px;
    }

    .stat-label {
        font-size: 0.9em;
        color: var(--color-text-muted);
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 1.8em;
        font-weight: bold;
        color: var(--color-primary);
    }

    @keyframes correctPop {
        0% { transform: scale(0.92); }
        50% { transform: scale(1.08); }
        100% { transform: scale(1); }
    }

    @keyframes incorrectShake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-4px); }
        50% { transform: translateX(4px); }
        75% { transform: translateX(-2px); }
    }

    @keyframes particleOut {
        0% {
            transform: translate(0, 0) scale(1);
            opacity: 1;
        }
        100% {
            transform: translate(var(--dx), var(--dy)) scale(0);
            opacity: 0;
        }
    }

    @media (max-width: 768px) {
        .game-container {
            max-height: 600px;
            height: auto;
        }

        .game-header {
            flex-direction: column;
            text-align: center;
        }

        .hanzi {
            font-size: 2em;
        }

        .modal-content {
            margin: 20px;
        }
    }
</style>
@endsection

@section('content')
<h1><i data-lucide="crosshair" style="color:var(--color-primary);vertical-align:-3px;"></i> {{ $theme->name }}</h1>

<div class="game-container">
    <!-- HUD (Heads Up Display) -->
    <div class="game-header">
        <h2 id="themeName">{{ $theme->name }}</h2>

        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            <div class="hud-stat">
                <div class="hud-label">Puntuación</div>
                <div class="hud-value" id="score">0</div>
            </div>

            <div class="hud-stat">
                <div class="hud-label">Aciertos</div>
                <div class="hud-value" id="hits" style="color: var(--color-success);">0</div>
            </div>

            <div class="hud-stat">
                <div class="hud-label">Errores</div>
                <div class="hud-value" id="mistakes" style="color: var(--color-error);">0</div>
            </div>

            <div class="hud-stat">
                <div class="hud-label">Precisión</div>
                <div class="hud-value" id="accuracy" style="color: var(--color-warning);">0%</div>
            </div>

            <div class="hud-stat">
                <div class="hud-label">Tiempo</div>
                <div class="hud-value" id="timer">{{ $gameData['duration'] }}s</div>
            </div>

            <div class="hud-stat">
                <div class="hud-label">Nivel</div>
                <div class="hud-value" id="level">{{ $gameData['level'] }}</div>
            </div>

            <a href="{{ route('games.selectTheme', ['gameSlug' => 'catch-the-character']) }}"
               onclick="return confirm('¿Seguro que quieres salir? Perderás el progreso actual.')"
               class="exit-chip">
                <i data-lucide="log-out" style="width:14px;height:14px;"></i> Salir
            </a>
        </div>
    </div>

    <!-- Game Board -->
    <div class="game-board" id="gameBoard">
        <!-- Los caracteres se añadirán aquí dinámicamente -->
    </div>

    <!-- Progress Bar -->
    <div class="game-footer">
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill" style="width: 100%;"></div>
        </div>
        <small id="instructions">El juego comenzará en breve. Haz clic en los caracteres correctos para tu categoría.</small>
    </div>
</div>

<!-- Game Over Modal -->
<div class="game-over-modal" id="gameOverModal">
    <div class="modal-content">
        <h2 id="resultTitle">¡Partida Finalizada!</h2>

        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-label">Puntuación Final</div>
                <div class="stat-value" id="finalScore">0</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Precisión</div>
                <div class="stat-value" id="finalAccuracy">0%</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Aciertos</div>
                <div class="stat-value" id="finalHits">0</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Errores</div>
                <div class="stat-value" id="finalMistakes">0</div>
            </div>
        </div>

        <div style="display: flex; gap: 10px; flex-direction: column;">
            <button class="btn btn-primary" onclick="location.href='{{ route('games.selectTheme', 'catch-the-character') }}'">
                <i data-lucide="palette" style="width:16px;height:16px;"></i> Volver a Seleccionar Tema
            </button>
            <button class="btn btn-secondary" onclick="location.href='{{ route('games.index') }}'">
                <i data-lucide="home" style="width:16px;height:16px;"></i> Menu Principal
            </button>
        </div>
    </div>
</div>

<div style="text-align: center; margin-top: 20px;">
    <button class="btn btn-ghost" onclick="location.href='{{ route('games.selectTheme', 'catch-the-character') }}'">
        <i data-lucide="arrow-left" style="width:16px;height:16px;"></i> Seleccionar Otro Tema
    </button>
</div>
@endsection

@section('scripts')
<script>
    // Datos del juego
    const gameState = {
        themeId: {{ $theme->id }},
        level: {{ $gameData['level'] }},
        score: 0,
        hits: 0,
        mistakes: 0,
        accuracy: 0,
        duration: {{ $gameData['duration'] }},
        timeRemaining: {{ $gameData['duration'] }},
        isGameActive: false,
        characters: [],
        gamePoints: {!! json_encode($gamePoints) !!},
    };

    // Elementos del DOM
    const elements = {
        gameBoard: document.getElementById('gameBoard'),
        score: document.getElementById('score'),
        hits: document.getElementById('hits'),
        mistakes: document.getElementById('mistakes'),
        accuracy: document.getElementById('accuracy'),
        timer: document.getElementById('timer'),
        level: document.getElementById('level'),
        progressFill: document.getElementById('progressFill'),
        gameOverModal: document.getElementById('gameOverModal'),
        resultTitle: document.getElementById('resultTitle'),
        finalScore: document.getElementById('finalScore'),
        finalAccuracy: document.getElementById('finalAccuracy'),
        finalHits: document.getElementById('finalHits'),
        finalMistakes: document.getElementById('finalMistakes'),
    };

    // Inicializar el juego
    async function initializeGame() {
        try {
            // Obtener caracteres iniciales
            const response = await fetch('/catch-the-character/api/' + gameState.themeId + '/characters', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    level: gameState.level,
                }),
            });

            const data = await response.json();
            if (data.success) {
                gameState.characters = data.characters;
                renderCharacters();
                startGame();
            }
        } catch (error) {
            console.error('Error inicializando juego:', error);
        }
    }

    // Renderizar caracteres en el tablero
    // Variables globales para el sistema de columnas
    let columnTimers = {};
    const cardWidth = 100;
    const spacing = 15;
    let maxColumns = 4;
    let audioContext = null;

    // Calcular número de columnas
    function calculateColumns() {
        const boardWidth = elements.gameBoard.offsetWidth;
        maxColumns = Math.max(1, Math.floor((boardWidth - spacing) / (cardWidth + spacing)));
    }

    // Crear un único carácter cayendo
    function createFallingCharacter(character, columnIndex) {
        const card = document.createElement('div');
        card.className = 'character-card';
        card.innerHTML = `
            <div class="hanzi">${character.hanzi}</div>
            <div class="pinyin">${character.pinyin}</div>
        `;

        // Posición horizontal fija por columna
        const boardWidth = elements.gameBoard.offsetWidth;
        const leftPosition = (boardWidth / maxColumns) * columnIndex + spacing;
        
        // Tiempo de caída más lento para que parezca más natural
        const fallTime = 7000 + Math.random() * 2000;
        const startTime = Date.now();
        
        const topStart = -100;
        const topEnd = elements.gameBoard.offsetHeight;

        function animate() {
            const elapsed = Date.now() - startTime;
            const progress = Math.min(1, elapsed / fallTime);

            if (progress >= 1) {
                // El carácter llega al fondo y desaparece sin penalizar
                card.remove();
                return;
            }

            // Solo animar vertical (TOP), horizontal es fijo
            const top = topStart + (topEnd - topStart) * progress;
            card.style.top = top + 'px';
            card.style.left = leftPosition + 'px';

            requestAnimationFrame(animate);
        }

        card.onclick = (event) => handleCharacterClick(character, card, event);
        elements.gameBoard.appendChild(card);
        animate();
    }

    function ensureAudio() {
        if (!audioContext) {
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
        }

        if (audioContext.state === 'suspended') {
            audioContext.resume();
        }

        return audioContext;
    }

    function playFeedbackSound(isCorrect) {
        try {
            const ctx = ensureAudio();
            const oscillator = ctx.createOscillator();
            const gainNode = ctx.createGain();

            oscillator.type = isCorrect ? 'triangle' : 'square';
            oscillator.frequency.setValueAtTime(isCorrect ? 880 : 220, ctx.currentTime);
            oscillator.frequency.exponentialRampToValueAtTime(isCorrect ? 1320 : 180, ctx.currentTime + 0.15);

            gainNode.gain.setValueAtTime(0.03, ctx.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.2);

            oscillator.connect(gainNode);
            gainNode.connect(ctx.destination);
            oscillator.start();
            oscillator.stop(ctx.currentTime + 0.2);
        } catch (error) {
            console.warn('No se pudo reproducir el sonido:', error);
        }
    }

    function createFeedbackBurst(x, y, isCorrect) {
        const burstCount = 10;

        for (let i = 0; i < burstCount; i++) {
            const particle = document.createElement('span');
            particle.className = `feedback-particle ${isCorrect ? 'correct' : 'incorrect'}`;

            const angle = (Math.PI * 2 * i) / burstCount;
            const distance = 20 + Math.random() * 35;
            const dx = Math.cos(angle) * distance;
            const dy = Math.sin(angle) * distance - 10;

            particle.style.left = `${x - elements.gameBoard.getBoundingClientRect().left}px`;
            particle.style.top = `${y - elements.gameBoard.getBoundingClientRect().top}px`;
            particle.style.setProperty('--dx', `${dx}px`);
            particle.style.setProperty('--dy', `${dy}px`);

            elements.gameBoard.appendChild(particle);
            setTimeout(() => particle.remove(), 800);
        }
    }

    // Generar caracteres esporádicamente en columnas
    function startColumnGenerators() {
        calculateColumns();
        
        // Para cada columna, crear un generador independiente
        for (let col = 0; col < maxColumns; col++) {
            scheduleNextCharacter(col);
        }
    }

    // Programar el siguiente carácter en una columna específica
    async function scheduleNextCharacter(columnIndex) {
        if (!gameState.isGameActive) return;

        // Intervalo aleatorio: 1.5 a 3.5 segundos
        const delay = 1500 + Math.random() * 2000;

        // Limpiar timer anterior si existe
        if (columnTimers[columnIndex]) {
            clearTimeout(columnTimers[columnIndex]);
        }

        // Programar el siguiente carácter
        columnTimers[columnIndex] = setTimeout(async () => {
            if (!gameState.isGameActive) return;

            try {
                const response = await fetch('/catch-the-character/api/' + gameState.themeId + '/characters', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                    body: JSON.stringify({
                        level: gameState.level,
                    }),
                });

                const data = await response.json();
                if (data.success && data.characters.length > 0) {
                    // Tomar un carácter aleatorio del lote
                    const randomChar = data.characters[Math.floor(Math.random() * data.characters.length)];
                    createFallingCharacter(randomChar, columnIndex);
                }
            } catch (error) {
                console.error('Error generando carácter:', error);
            }

            // Programar el siguiente
            scheduleNextCharacter(columnIndex);
        }, delay);
    }

    // Renderizar caracteres (función simplificada)
    function renderCharacters() {
        // Esta función ahora solo limpia el board en inicialización
        elements.gameBoard.innerHTML = '';
    }

    // Manejar clic en carácter
    async function handleCharacterClick(character, cardElement, event) {
        if (!gameState.isGameActive) return;

        const isCorrect = character.is_correct;
        const clickX = event ? event.clientX : cardElement.getBoundingClientRect().left + 40;
        const clickY = event ? event.clientY : cardElement.getBoundingClientRect().top + 20;

        // Visual feedback
        cardElement.classList.add(isCorrect ? 'correct' : 'incorrect');
        cardElement.style.pointerEvents = 'none';
        createFeedbackBurst(clickX, clickY, isCorrect);
        playFeedbackSound(isCorrect);

        try {
            const response = await fetch('/catch-the-character/api/click', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    character_id: character.id,
                    theme_id: gameState.themeId,
                    is_correct: isCorrect,
                    current_score: gameState.score,
                    current_hits: gameState.hits,
                    current_mistakes: gameState.mistakes,
                    current_level: gameState.level,
                }),
            });

            const data = await response.json();
            if (data.success) {
                gameState.score = data.new_score;
                gameState.hits = data.new_hits;
                gameState.mistakes = data.new_mistakes;
                gameState.accuracy = data.accuracy;

                updateHUD();

                if (isCorrect) {
                    console.log('✓ Correcto!');
                } else {
                    console.log('✗ Incorrecto');
                }
            }
        } catch (error) {
            console.error('Error procesando clic:', error);
        }

        // Remover tarjeta después de feedback visual
        setTimeout(() => cardElement.remove(), 300);
    }

    // Manejar carácter no capturado
    async function handleMissedCharacter() {
        if (!gameState.isGameActive) return;

        try {
            const response = await fetch('/catch-the-character/api/missed', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    theme_id: gameState.themeId,
                    current_score: gameState.score,
                    current_hits: gameState.hits,
                    current_mistakes: gameState.mistakes,
                    current_level: gameState.level,
                }),
            });

            const data = await response.json();
            if (data.success) {
                gameState.score = data.new_score;
                gameState.hits = data.new_hits;
                gameState.mistakes = data.new_mistakes;
                gameState.accuracy = data.accuracy;

                updateHUD();
            }
        } catch (error) {
            console.error('Error procesando carácter no capturado:', error);
        }
    }

    // Actualizar HUD
    function updateHUD() {
        elements.score.textContent = gameState.score;
        elements.hits.textContent = gameState.hits;
        elements.mistakes.textContent = gameState.mistakes;
        elements.accuracy.textContent = Math.round(gameState.accuracy) + '%';
    }

    // Iniciar juego
    function startGame() {
        gameState.isGameActive = true;
        gameState.timeRemaining = gameState.duration;

        // Iniciar generadores de columnas
        startColumnGenerators();

        // Temporizador
        const timerInterval = setInterval(() => {
            gameState.timeRemaining--;
            elements.timer.textContent = gameState.timeRemaining + 's';

            const progress = (gameState.timeRemaining / gameState.duration) * 100;
            elements.progressFill.style.width = progress + '%';

            if (gameState.timeRemaining <= 0) {
                clearInterval(timerInterval);
                
                // Limpiar todos los timers de columnas
                Object.values(columnTimers).forEach(timer => clearTimeout(timer));
                
                endGame();
            }
        }, 1000);
    }

    // Finalizar juego
    async function endGame() {
        gameState.isGameActive = false;
        elements.gameBoard.innerHTML = '<p style="padding: 20px; text-align: center;">Procesando resultados...</p>';

        try {
            const response = await fetch('/catch-the-character/api/end', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    theme_id: gameState.themeId,
                    score: gameState.score,
                    hits: gameState.hits,
                    mistakes: gameState.mistakes,
                    duration: gameState.duration,
                    level: gameState.level,
                }),
            });

            const data = await response.json();

            // Mostrar modal de fin de juego
            elements.finalScore.textContent = gameState.score;
            elements.finalAccuracy.textContent = Math.round(gameState.accuracy) + '%';
            elements.finalHits.textContent = gameState.hits;
            elements.finalMistakes.textContent = gameState.mistakes;

            elements.gameOverModal.classList.add('show');
        } catch (error) {
            console.error('Error finalizando juego:', error);
        }
    }

    // Validar que existe el token CSRF
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = '{{ csrf_token() }}';
        document.head.appendChild(meta);
    }

    // Iniciar el juego cuando la página carga
    window.addEventListener('load', initializeGame);
</script>
@endsection
