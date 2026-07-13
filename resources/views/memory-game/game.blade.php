@extends('layouts.game')

@section('styles')
<style>
    body {
        overflow: hidden !important;
    }

    .container {
        max-width: none !important;
        padding: 0 !important;
        margin: 0 !important;
        height: 100vh !important;
    }

    .game-container {
        background: white;
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
        background: linear-gradient(135deg, {{ $theme->color_primary }}, {{ $theme->color_secondary }});
        color: white;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        flex-shrink: 0;
        min-height: 100px;
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
        opacity: 0.9;
    }

    .hud-value {
        font-size: 1.5em;
        font-weight: bold;
    }

    .game-board {
        flex: 1;
        position: relative;
        background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%);
        overflow: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px;
    }

    .memory-grid {
        display: grid;
        grid-template-columns: repeat(4, 100px);
        gap: 15px;
        justify-content: center;
    }

    .memory-card {
        width: 100px;
        height: 100px;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8em;
        font-weight: bold;
        text-align: center;
        padding: 10px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        user-select: none;
    }

    .memory-card:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }

    .memory-card.flipped {
        background: linear-gradient(135deg, {{ $theme->color_primary }}, {{ $theme->color_secondary }});
        color: white;
        border-color: {{ $theme->color_primary }};
    }

    .memory-card.matched {
        background: var(--color-success);
        color: white;
        border-color: var(--color-success);
        cursor: default;
        pointer-events: none;
    }

    .drawing-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 500;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    .drawing-overlay.show {
        display: flex;
    }

    .drawing-container {
        background: white;
        border-radius: 15px;
        padding: 30px;
        text-align: center;
        max-width: 400px;
        animation: slideUp 0.3s ease;
    }

    .drawing-container h3 {
        color: var(--color-primary);
        margin-bottom: 20px;
        font-size: 1.5em;
    }

    #character-target-div {
        margin: 20px auto;
        background: #f8fafc;
        border-radius: 8px;
    }

    .drawing-info {
        font-size: 0.9em;
        color: #666;
        margin-bottom: 15px;
    }

    .drawing-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .drawing-buttons button {
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .game-footer {
        background: #f8fafc;
        padding: 15px 20px;
        text-align: center;
        border-top: 1px solid #e2e8f0;
        flex-shrink: 0;
    }

    .progress-bar {
        width: 100%;
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--color-primary), var(--color-secondary));
        transition: width 0.1s linear;
    }

    .game-over-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }

    .game-over-modal.show {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 15px;
        padding: 40px;
        text-align: center;
        max-width: 500px;
        animation: slideUp 0.3s ease;
    }

    .modal-content h2 {
        color: var(--color-primary);
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
        background: #f8fafc;
        padding: 15px;
        border-radius: 8px;
    }

    .stat-label {
        font-size: 0.9em;
        color: #666;
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 1.8em;
        font-weight: bold;
        color: var(--color-primary);
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
        background: var(--color-success);
        box-shadow: 0 0 8px rgba(16, 185, 129, 0.5);
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

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
</style>
@endsection

@section('content')
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
                <div class="hud-label">Parejas</div>
                <div class="hud-value" id="matches">0</div>
            </div>

            <div class="hud-stat">
                <div class="hud-label">Errores</div>
                <div class="hud-value" id="mistakes" style="color: var(--color-error);">0</div>
            </div>

            <div class="hud-stat">
                <div class="hud-label">Tiempo</div>
                <div class="hud-value" id="timer">{{ $gameData['duration'] }}s</div>
            </div>

            <div class="hud-stat">
                <div class="hud-label">Nivel</div>
                <div class="hud-value" id="level">{{ $gameData['level'] }}</div>
            </div>
        </div>
    </div>

    <!-- Game Board -->
    <div class="game-board" id="gameBoard">
        <div class="memory-grid" id="memoryGrid"></div>
    </div>

    <!-- Progress Bar -->
    <div class="game-footer">
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill" style="width: 100%;"></div>
        </div>
        <small id="instructions">¡Encuentra las parejas de caracteres y su pinyin!</small>
    </div>
</div>

<!-- Drawing Overlay -->
<div class="drawing-overlay" id="drawingOverlay">
    <div class="drawing-container">
        <h3>¡Dibuja el carácter!</h3>
        <p class="drawing-info">Escribe el carácter dibujando sus trazos</p>
        <div id="character-target-div" style="width: 200px; height: 200px; margin: 0 auto;"></div>
        <div class="drawing-buttons">
            <button class="btn btn-secondary" onclick="resetDrawing()">Limpiar</button>
            <button class="btn btn-primary" onclick="completeDrawing()">Continuar</button>
        </div>
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
                <div class="stat-label">Parejas Encontradas</div>
                <div class="stat-value" id="finalMatches">0</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Errores</div>
                <div class="stat-value" id="finalMistakes">0</div>
            </div>
            <div class="stat-item">
                <div class="stat-label">Tasa de Acierto</div>
                <div class="stat-value" id="finalAccuracy">0%</div>
            </div>
        </div>

        <div style="display: flex; gap: 10px; flex-direction: column;">
            <button class="btn btn-primary" onclick="location.href='{{ route('games.selectTheme', 'memory-game') }}'">
                Volver a Seleccionar Tema
            </button>
            <button class="btn btn-secondary" onclick="location.href='{{ route('games.index') }}'">
                Menú Principal
            </button>
        </div>
    </div>
</div>

<div style="text-align: center; margin-top: 20px;">
    <button class="btn btn-secondary" onclick="location.href='{{ route('games.selectTheme', 'memory-game') }}'">
        ← Seleccionar Otro Tema
    </button>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/hanzi-writer@2.2.8/dist/hanzi-writer.umd.js"></script>
<script>
    // Estado del juego
    const gameState = {
        themeId: {{ $theme->id }},
        level: {{ $gameData['level'] }},
        score: 0,
        matches: 0,
        mistakes: 0,
        duration: {{ $gameData['duration'] }},
        timeRemaining: {{ $gameData['duration'] }},
        isGameActive: false,
        cards: [],
        flipped: [],
        matched: [],
        currentDrawing: null,
    };

    // Elementos del DOM
    const elements = {
        gameBoard: document.getElementById('gameBoard'),
        memoryGrid: document.getElementById('memoryGrid'),
        score: document.getElementById('score'),
        matches: document.getElementById('matches'),
        mistakes: document.getElementById('mistakes'),
        timer: document.getElementById('timer'),
        level: document.getElementById('level'),
        progressFill: document.getElementById('progressFill'),
        gameOverModal: document.getElementById('gameOverModal'),
        drawingOverlay: document.getElementById('drawingOverlay'),
        finalScore: document.getElementById('finalScore'),
        finalMatches: document.getElementById('finalMatches'),
        finalMistakes: document.getElementById('finalMistakes'),
        finalAccuracy: document.getElementById('finalAccuracy'),
    };

    let writer = null;

    // Inicializar el juego
    async function initializeGame() {
        try {
            const response = await fetch('/memory-game/api/' + gameState.themeId + '/pairs', {
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
                gameState.cards = data.pairs;
                renderCards();
                startGame();
            }
        } catch (error) {
            console.error('Error inicializando juego:', error);
        }
    }

    // Renderizar tarjetas de memoria
    function renderCards() {
        elements.memoryGrid.innerHTML = '';
        gameState.cards.forEach((card, index) => {
            const cardDiv = document.createElement('div');
            cardDiv.className = 'memory-card';
            cardDiv.id = `card-${index}`;
            cardDiv.textContent = '?';
            cardDiv.onclick = () => flipCard(index);
            elements.memoryGrid.appendChild(cardDiv);
        });
    }

    // Voltear tarjeta
    function flipCard(index) {
        if (!gameState.isGameActive) return;
        if (gameState.flipped.includes(index) || gameState.matched.includes(index)) return;
        if (gameState.flipped.length >= 2) return;

        gameState.flipped.push(index);
        const card = gameState.cards[index];
        const cardDiv = document.getElementById(`card-${index}`);

        cardDiv.classList.add('flipped');
        cardDiv.textContent = card.type === 'character' ? card.value : card.value;

        if (gameState.flipped.length === 2) {
            setTimeout(checkMatch, 800);
        }
    }

    // Verificar si las dos tarjetas coinciden
    async function checkMatch() {
        const [idx1, idx2] = gameState.flipped;
        const card1 = gameState.cards[idx1];
        const card2 = gameState.cards[idx2];

        const isMatch = card1.pair_id === card2.pair_id;

        if (isMatch) {
            // Acierto: mostrar en verde y comenzar el dibujo
            gameState.matched.push(idx1, idx2);
            gameState.matches++;
            gameState.flipped = [];
            updateHUD();
            playFeedbackSound(true);
            createFeedbackBurst(document.getElementById(`card-${idx1}`).getBoundingClientRect(), true);

            // Mostrar el overlay de dibujo
            setTimeout(() => {
                showDrawingChallenge(card1.hanzi || card2.hanzi);
            }, 500);

            // Verificar si ganó
            if (gameState.matched.length === gameState.cards.length) {
                setTimeout(endGame, 2000);
            }
        } else {
            // Error: voltear de vuelta
            const cardDiv1 = document.getElementById(`card-${idx1}`);
            const cardDiv2 = document.getElementById(`card-${idx2}`);
            cardDiv1.classList.remove('flipped');
            cardDiv2.classList.remove('flipped');
            cardDiv1.textContent = '?';
            cardDiv2.textContent = '?';
            gameState.mistakes++;
            gameState.flipped = [];
            updateHUD();
            playFeedbackSound(false);
        }
    }

    // Mostrar desafío de dibujo
    async function showDrawingChallenge(hanzi) {
        gameState.currentDrawing = {
            character: hanzi,
            mistakes: 0,
        };

        elements.drawingOverlay.classList.add('show');

        // Limpiar escritor anterior
        if (writer) {
            writer.destroy();
        }

        // Crear nuevo escritor
        writer = HanziWriter.create('character-target-div', hanzi, {
            width: 200,
            height: 200,
            showCharacter: false,
            padding: 5,
            strokeAnimationSpeed: 2,
        });

        writer.quiz({
            onMistake: (strokeData) => {
                gameState.currentDrawing.mistakes++;
                console.log(`Trazo incorrecto. Errores: ${strokeData.totalMistakes}`);
            },
            onCorrectStroke: (strokeData) => {
                console.log(`✓ Trazo ${strokeData.strokeNum} correcto!`);
            },
            onComplete: (summaryData) => {
                console.log(`¡Dibujado completo! Errores: ${summaryData.totalMistakes}`);
            },
        });
    }

    // Continuar después del dibujo
    async function completeDrawing() {
        if (!gameState.currentDrawing) return;

        try {
            const response = await fetch('/memory-game/api/drawing', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    theme_id: gameState.themeId,
                    current_score: gameState.score,
                    current_hits: gameState.matches,
                    current_mistakes: gameState.mistakes,
                    current_level: gameState.level,
                    drawing_mistakes: gameState.currentDrawing.mistakes,
                }),
            });

            const data = await response.json();
            if (data.success) {
                gameState.score = data.new_score;
                updateHUD();
            }
        } catch (error) {
            console.error('Error guardando dibujo:', error);
        }

        elements.drawingOverlay.classList.remove('show');
        gameState.currentDrawing = null;
    }

    // Limpiar dibujo
    function resetDrawing() {
        if (writer) {
            writer.destroy();
            writer = null;
            setTimeout(() => {
                showDrawingChallenge(gameState.currentDrawing.character);
            }, 100);
        }
    }

    // Actualizar HUD
    function updateHUD() {
        elements.score.textContent = gameState.score;
        elements.matches.textContent = gameState.matches;
        elements.mistakes.textContent = gameState.mistakes;
    }

    // Sonidos
    function playFeedbackSound(isCorrect) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
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

    // Partículas
    function createFeedbackBurst(rect, isCorrect) {
        const burstCount = 10;
        for (let i = 0; i < burstCount; i++) {
            const particle = document.createElement('span');
            particle.className = `feedback-particle ${isCorrect ? 'correct' : 'incorrect'}`;

            const angle = (Math.PI * 2 * i) / burstCount;
            const distance = 20 + Math.random() * 35;
            const dx = Math.cos(angle) * distance;
            const dy = Math.sin(angle) * distance - 10;

            particle.style.left = `${rect.left + rect.width / 2}px`;
            particle.style.top = `${rect.top + rect.height / 2}px`;
            particle.style.setProperty('--dx', `${dx}px`);
            particle.style.setProperty('--dy', `${dy}px`);

            elements.gameBoard.appendChild(particle);
            setTimeout(() => particle.remove(), 800);
        }
    }

    // Iniciar juego
    function startGame() {
        gameState.isGameActive = true;
        gameState.timeRemaining = gameState.duration;

        const timerInterval = setInterval(() => {
            gameState.timeRemaining--;
            elements.timer.textContent = gameState.timeRemaining + 's';

            const progress = (gameState.timeRemaining / gameState.duration) * 100;
            elements.progressFill.style.width = progress + '%';

            if (gameState.timeRemaining <= 0) {
                clearInterval(timerInterval);
                endGame();
            }
        }, 1000);
    }

    // Finalizar juego
    async function endGame() {
        gameState.isGameActive = false;

        try {
            const response = await fetch('/memory-game/api/end', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    theme_id: gameState.themeId,
                    score: gameState.score,
                    hits: gameState.matches,
                    mistakes: gameState.mistakes,
                    duration: gameState.duration,
                    level: gameState.level,
                }),
            });

            const data = await response.json();

            // Mostrar modal de fin de juego
            const totalAttempts = gameState.matches + gameState.mistakes;
            const accuracy = totalAttempts > 0 ? (gameState.matches / totalAttempts) * 100 : 0;

            elements.finalScore.textContent = gameState.score;
            elements.finalMatches.textContent = gameState.matches;
            elements.finalMistakes.textContent = gameState.mistakes;
            elements.finalAccuracy.textContent = Math.round(accuracy) + '%';

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

    // Iniciar juego cuando carga la página
    window.addEventListener('load', initializeGame);
</script>
@endsection
