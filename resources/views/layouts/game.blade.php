<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Aprende Hanzi Jugando' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
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
            --color-secondary-light: #f4b8b7;
            --color-secondary-dark: #d6706e;
            --color-secondary-50: #fdf2f2;

            --color-accent: #6bc5a0;
            --color-accent-light: #96d9c0;
            --color-accent-dark: #4eaa84;
            --color-accent-50: #edf8f3;

            --color-warm: #e5a76e;
            --color-warm-light: #f0c49a;
            --color-warm-dark: #d08e4f;
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
            --radius-full: 9999px;

            --shadow-sm: 0 1px 3px rgba(45, 42, 74, 0.04), 0 1px 2px rgba(45, 42, 74, 0.03);
            --shadow-md: 0 4px 12px rgba(45, 42, 74, 0.06), 0 2px 4px rgba(45, 42, 74, 0.04);
            --shadow-lg: 0 12px 32px rgba(45, 42, 74, 0.08), 0 4px 8px rgba(45, 42, 74, 0.04);
            --shadow-xl: 0 20px 48px rgba(45, 42, 74, 0.10), 0 8px 16px rgba(45, 42, 74, 0.05);

            --transition-fast: 0.15s ease;
            --transition-base: 0.25s ease;
            --transition-slow: 0.4s ease;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-sans);
            background: var(--color-bg);
            min-height: 100vh;
            color: var(--color-text);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .container {
            max-width: 1120px;
            margin: 0 auto;
            padding: 32px 24px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            animation: fadeIn 0.5s ease;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--color-text);
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }

        .header h1 i { vertical-align: -3px; margin-right: 8px; color: var(--color-primary); }

        .header p {
            font-size: 1rem;
            color: var(--color-text-muted);
            font-weight: 400;
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(-12px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes scaleIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

        .game-card {
            background: var(--color-surface);
            border-radius: var(--radius-lg);
            padding: 28px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--color-border-light);
            transition: all var(--transition-base);
            animation: slideUp 0.5s ease both;
        }

        .game-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
            border-color: var(--color-border);
        }

        .game-card h2 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--color-text);
            margin-bottom: 8px;
        }

        .game-card h2 i { vertical-align: -2px; margin-right: 6px; color: var(--color-primary); }

        .game-card p {
            color: var(--color-text-muted);
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .game-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .game-icon.primary { background: var(--color-primary-50); color: var(--color-primary); }
        .game-icon.secondary { background: var(--color-secondary-50); color: var(--color-secondary); }
        .game-icon.accent { background: var(--color-accent-50); color: var(--color-accent); }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 22px;
            border-radius: var(--radius-sm);
            border: none;
            font-family: var(--font-sans);
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-fast);
            text-decoration: none;
            text-align: center;
            line-height: 1.4;
        }

        .btn:active { transform: scale(0.97); }

        .btn-primary {
            background: var(--color-primary);
            color: #fff;
            box-shadow: 0 2px 8px rgba(124, 110, 240, 0.25);
        }
        .btn-primary:hover { background: var(--color-primary-dark); box-shadow: 0 4px 16px rgba(124, 110, 240, 0.35); }

        .btn-secondary {
            background: var(--color-primary-50);
            color: var(--color-primary);
        }
        .btn-secondary:hover { background: var(--color-primary-100); }

        .btn-success {
            background: var(--color-accent);
            color: #fff;
            box-shadow: 0 2px 8px rgba(107, 197, 160, 0.25);
        }
        .btn-success:hover { background: var(--color-accent-dark); }

        .btn-error {
            background: var(--color-secondary);
            color: #fff;
            box-shadow: 0 2px 8px rgba(232, 145, 143, 0.25);
        }
        .btn-error:hover { background: var(--color-secondary-dark); }

        .btn-ghost {
            background: transparent;
            color: var(--color-text-muted);
            border: 1px solid var(--color-border);
        }
        .btn-ghost:hover { background: var(--color-bg); color: var(--color-text); border-color: var(--color-border); }

        .btn-sm { padding: 7px 14px; font-size: 0.8rem; }

        .alert {
            padding: 14px 18px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            font-size: 0.9rem;
            animation: slideUp 0.3s ease;
        }
        .alert-success { background: var(--color-accent-50); color: #2d6a4f; border-left: 3px solid var(--color-accent); }
        .alert-error { background: var(--color-secondary-50); color: #8b2020; border-left: 3px solid var(--color-secondary); }
        .alert-warning { background: var(--color-warm-50); color: #8a5a2b; border-left: 3px solid var(--color-warm); }

        .grid { display: grid; gap: 20px; }
        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); }
        .grid-3 { grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }

        .stat-box {
            background: var(--color-bg);
            border: 1px solid var(--color-border-light);
            border-radius: var(--radius-md);
            padding: 20px;
            transition: all var(--transition-fast);
        }
        .stat-box:hover { border-color: var(--color-border); box-shadow: var(--shadow-sm); }
        .stat-box .stat-label { font-size: 0.78rem; color: var(--color-text-muted); font-weight: 500; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.04em; }
        .stat-box .stat-value { font-size: 1.75rem; font-weight: 800; color: var(--color-primary); letter-spacing: -0.02em; }
        .stat-box .stat-value.accent { color: var(--color-accent); }
        .stat-box .stat-value.warm { color: var(--color-warm); }
        .stat-box .stat-value.secondary { color: var(--color-secondary); }
        .stat-box i { margin-right: 4px; vertical-align: -2px; }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 32px;
            color: var(--color-text-muted);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: color var(--transition-fast);
        }
        .back-link:hover { color: var(--color-primary); }

        @media (max-width: 768px) {
            .header h1 { font-size: 1.5rem; }
            .container { padding: 24px 16px; }
            .game-card { padding: 22px; }
            .grid-3 { grid-template-columns: 1fr; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
    <script>document.addEventListener('DOMContentLoaded', () => { if (window.lucide) lucide.createIcons(); });</script>
    @yield('scripts')
</body>
</html>
