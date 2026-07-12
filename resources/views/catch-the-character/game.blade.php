@extends('layouts.game')

@section('styles')
<style>
    .game-container {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        display: flex;
        flex-direction: column;
        height: calc(100vh - 200px);
        max-height: 800px;
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
        overflow: hidden;
    }

    .character-card {
        position: absolute;
        background: white;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.1s ease;
        user-select: none;
        min-width: 80px;
    }

    .character-card:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }

    .character-card.correct {
        border: 3px solid var(--color-success);
    }

    .character-card.incorrect {
        border: 3px solid var(--color-error);
    }

    .hanzi {
        font-size: 2.5em;
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
    }

    .pinyin {
        font-size: 0.9em;
        color: #666;
    }

    .game-footer {
        background: #f8fafc;
        padding: 15px 20px;
        text-align: center;
        border-top: 1px solid #e2e8f0;
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
<div class="header" style="margin-bottom: 20px;">
    <h1>🎯 {{ $theme->name }}</h1>
    <p>Identifica correctamente los caracteres</p>
</div>

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
                Volver a Seleccionar Tema
            </button>
            <button class="btn btn-secondary" onclick="location.href='{{ route('games.index') }}'">
                Menú Principal
            </button>
        </div>
    </div>
</div>

<div style="text-align: center; margin-top: 20px;">
    <button class="btn btn-secondary" onclick="location.href='{{ route('games.selectTheme', 'catch-the-character') }}'">
        ← Seleccionar Otro Tema
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
    function renderCharacters() {
        elements.gameBoard.innerHTML = '';

        // Calcular grid de columnas (máx 4 caracteres por fila)
        const cardWidth = 100;
        const spacing = 15;
        const boardWidth = elements.gameBoard.offsetWidth;
        const maxColumns = Math.max(1, Math.floor((boardWidth - spacing) / (cardWidth + spacing)));
        
        gameState.characters.forEach((char, index) => {
            const card = document.createElement('div');
            card.className = 'character-card';
            card.innerHTML = `
                <div class="hanzi">${char.hanzi}</div>
                <div class="pinyin">${char.pinyin}</div>
            `;

            // Calcular posición horizontal fija basada en índice
            const column = index % maxColumns;
            const row = Math.floor(index / maxColumns);
            const leftPosition = (boardWidth / maxColumns) * column + spacing;
            
            // Tiempo de caída: 4-5 segundos (más suave que antes)
            const fallTime = 4000 + (index * 200); // Efecto cascada
            const startTime = Date.now() + (index * 100); // Delay progresivo
            
            const topStart = -100;
            const topEnd = elements.gameBoard.offsetHeight;

            function animate() {
                const elapsed = Date.now() - startTime;
                
                // Aún no empieza
                if (elapsed < 0) {
                    requestAnimationFrame(animate);
                    return;
                }
                
                const progress = Math.min(1, elapsed / fallTime);

                if (progress >= 1) {
                    // Carácter llegó al fondo
                    card.remove();
                    if (char.is_correct) {
                        handleMissedCharacter();
                    }
                    return;
                }

                // Solo animar vertical (TOP), horizontal es fijo
                const top = topStart + (topEnd - topStart) * progress;
                card.style.top = top + 'px';
                card.style.left = leftPosition + 'px';

                requestAnimationFrame(animate);
            }

            card.onclick = () => handleCharacterClick(char, card);
            elements.gameBoard.appendChild(card);
            animate();
        });
    }

    // Manejar clic en carácter
    async function handleCharacterClick(character, cardElement) {
        if (!gameState.isGameActive) return;

        const isCorrect = character.is_correct;

        // Visual feedback
        cardElement.classList.add(isCorrect ? 'correct' : 'incorrect');

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

                // Reproducir sonido (será implementado después)
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

        // Temporizador
        const timerInterval = setInterval(() => {
            gameState.timeRemaining--;
            elements.timer.textContent = gameState.timeRemaining + 's';

            const progress = (gameState.timeRemaining / gameState.duration) * 100;
            elements.progressFill.style.width = progress + '%';

            if (gameState.timeRemaining <= 0) {
                clearInterval(timerInterval);
                clearInterval(generatorInterval);
                endGame();
            }
        }, 1000);

        // Generar caracteres continuamente cada 2.5 segundos
        const generatorInterval = setInterval(() => {
            if (gameState.isGameActive) {
                generateNewRound();
            }
        }, 2500);
    }

    // Generar nuevo lote de caracteres
    async function generateNewRound() {
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
            if (data.success) {
                gameState.characters = data.characters;
                renderCharacters();
            }
        } catch (error) {
            console.error('Error generando nuevo lote:', error);
        }
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
