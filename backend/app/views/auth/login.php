<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Sign In - Life Generation' ?></title>
    <meta name="description" content="Sign in to the Life Generation fellowship portal to connect with your community.">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpeg" href="<?= site_url('assets/images/lifegen-logo.png') ?>">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --orange: #f97316;
            --orange-dark: #ea580c;
            --orange-glow: rgba(249,115,22,0.22);
            --white: #ffffff;
            --text-dark: #111827;
            --text-mid: #374151;
            --text-muted: #6b7280;
            --input-border: #d1d5db;
            --input-focus: #f97316;
            --error: #dc2626;
            --success: #16a34a;
            --radius-card: 20px;
            --radius-input: 10px;
            --radius-btn: 10px;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
            min-height: 100vh;
            overflow: hidden;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ─── MOSAIC BACKGROUND ──────────────────────────────── */
        .mosaic-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-template-rows: repeat(3, 1fr);
            gap: 4px;
            background: #0d0d0d;
            transform: scale(1.04);
        }

        .mosaic-tile {
            overflow: hidden;
            position: relative;
        }

        /* Hero photo tiles (uses the real dashboard hero image) */
        .tile-photo {
            background: url('<?= site_url("assets/images/dashboard_hero_bg.jpg") ?>') center/cover no-repeat;
        }
        .tile-photo:nth-child(1)  { background-position: 0% 0%; }
        .tile-photo:nth-child(2)  { background-position: 20% 0%; }
        .tile-photo:nth-child(3)  { background-position: 40% 0%; }
        .tile-photo:nth-child(4)  { background-position: 60% 0%; }
        .tile-photo:nth-child(5)  { background-position: 80% 0%; }
        .tile-photo:nth-child(6)  { background-position: 0% 50%; }
        .tile-photo:nth-child(7)  { background-position: 20% 50%; }
        .tile-photo:nth-child(8)  { background-position: 40% 50%; }
        .tile-photo:nth-child(9)  { background-position: 60% 50%; }
        .tile-photo:nth-child(10) { background-position: 80% 50%; }
        .tile-photo:nth-child(11) { background-position: 0% 100%; }
        .tile-photo:nth-child(12) { background-position: 20% 100%; }
        .tile-photo:nth-child(13) { background-position: 40% 100%; }
        .tile-photo:nth-child(14) { background-position: 60% 100%; }
        .tile-photo:nth-child(15) { background-position: 80% 100%; }

        /* Gradient accent tiles scattered through the grid */
        .tile-gradient-1 { background: linear-gradient(135deg, #f97316 0%, #fb923c 100%); }
        .tile-gradient-2 { background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%); }
        .tile-gradient-3 { background: linear-gradient(135deg, #0ea5e9 0%, #38bdf8 100%); }
        .tile-gradient-4 { background: linear-gradient(135deg, #16a34a 0%, #4ade80 100%); }
        .tile-gradient-5 { background: linear-gradient(135deg, #dc2626 0%, #f87171 100%); }
        .tile-gradient-6 { background: linear-gradient(135deg, #ca8a04 0%, #fde047 100%); }

        /* Text overlay on gradient tiles */
        .tile-text-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: flex-end;
            padding: 14px;
        }
        .tile-text-overlay span {
            font-size: clamp(10px, 1.2vw, 15px);
            font-weight: 800;
            color: rgba(255,255,255,0.92);
            line-height: 1.15;
            letter-spacing: -0.01em;
            font-style: italic;
            text-shadow: 0 2px 8px rgba(0,0,0,0.25);
        }

        /* Subtle dark vignette overlay on photo tiles */
        .tile-photo::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.18);
        }

        /* Slow pan animation on tiles */
        @keyframes panTile {
            0%   { background-position-y: 0%; }
            50%  { background-position-y: 100%; }
            100% { background-position-y: 0%; }
        }
        .tile-photo:nth-child(odd)  { animation: panTile 20s ease-in-out infinite; }
        .tile-photo:nth-child(even) { animation: panTile 24s ease-in-out infinite reverse; }

        /* ─── DARK OVERLAY OVER WHOLE BG ──────────────────────── */
        .bg-overlay {
            position: fixed;
            inset: 0;
            z-index: 1;
            background: rgba(0, 0, 0, 0.42);
            backdrop-filter: blur(1px);
        }

        /* ─── CENTERED CARD ───────────────────────────────────── */
        .page-wrapper {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 460px;
            padding: 20px 16px;
        }

        .card {
            background: #ffffff;
            border-radius: var(--radius-card);
            padding: 40px 36px 32px;
            box-shadow:
                0 32px 80px rgba(0,0,0,0.35),
                0 8px 24px rgba(0,0,0,0.12);
            animation: cardIn 0.55s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(28px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Logo */
        .logo-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .logo-wrap img {
            width: 52px;
            height: 52px;
            object-fit: contain;
        }

        /* Heading */
        .card-title {
            text-align: center;
            font-size: 1.55rem;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.025em;
            margin-bottom: 6px;
        }

        .card-subtitle {
            text-align: center;
            font-size: 0.875rem;
            color: var(--text-muted);
            line-height: 1.55;
            margin-bottom: 28px;
        }
        .card-subtitle strong {
            color: var(--text-mid);
            font-weight: 600;
        }

        /* ─── ALERTS ──────────────────────────────────────────── */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 0.845rem;
            font-weight: 500;
            margin-bottom: 20px;
            line-height: 1.45;
        }
        .alert svg { flex-shrink: 0; margin-top: 1px; }
        .alert-error   { background: #fef2f2; color: var(--error);   border: 1px solid #fecaca; }
        .alert-success { background: #f0fdf4; color: var(--success); border: 1px solid #bbf7d0; }

        /* ─── FORM ────────────────────────────────────────────── */
        .form-group { margin-bottom: 16px; }

        .form-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text-mid);
            margin-bottom: 7px;
            letter-spacing: 0.01em;
        }
        .required-star { color: var(--orange); margin-left: 2px; }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 13px;
            color: #9ca3af;
            pointer-events: none;
            display: flex;
        }

        .form-input {
            width: 100%;
            padding: 12px 44px 12px 40px;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-dark);
            background: #f9fafb;
            border: 1.5px solid var(--input-border);
            border-radius: var(--radius-input);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .form-input::placeholder { color: #b0b7c3; font-weight: 400; }
        .form-input:focus {
            border-color: var(--input-focus);
            background: #fff;
            box-shadow: 0 0 0 3.5px rgba(249,115,22,0.13);
        }

        /* Password right icon */
        .toggle-password-btn {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: #9ca3af;
            display: flex;
            align-items: center;
            border-radius: 6px;
            transition: color 0.2s;
        }
        .toggle-password-btn:hover { color: var(--orange); }

        .password-hint {
            margin-top: 5px;
            font-size: 0.775rem;
            color: var(--text-muted);
            padding-left: 2px;
        }

        /* Submit button */
        .btn-submit {
            width: 100%;
            margin-top: 8px;
            padding: 13px 20px;
            background: #111827;
            color: #fff;
            border: none;
            border-radius: var(--radius-btn);
            font-family: inherit;
            font-size: 0.94rem;
            font-weight: 700;
            letter-spacing: 0.01em;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.22s, transform 0.15s, box-shadow 0.22s;
            position: relative;
            overflow: hidden;
        }
        .btn-submit::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,0);
            transition: background 0.2s;
        }
        .btn-submit:hover {
            background: #1f2937;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(17,24,39,0.28);
        }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit:disabled { opacity: 0.65; cursor: not-allowed; transform: none; }

        /* ─── DIVIDER ─────────────────────────────────────────── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0;
        }
        .divider-line {
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }
        .divider-text {
            font-size: 0.78rem;
            font-weight: 600;
            color: #9ca3af;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        /* ─── FOOTER LINK ─────────────────────────────────────── */
        .card-footer-text {
            text-align: center;
            margin-top: 24px;
            font-size: 0.855rem;
            color: var(--text-muted);
        }
        .card-footer-text a {
            color: var(--orange);
            font-weight: 700;
            text-decoration: none;
            transition: color 0.18s;
        }
        .card-footer-text a:hover { color: var(--orange-dark); text-decoration: underline; }

        /* Back link above card */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: rgba(255,255,255,0.82);
            font-size: 0.84rem;
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 18px;
            transition: color 0.18s, gap 0.18s;
        }
        .back-link:hover { color: #fff; gap: 10px; }

        /* Loading spinner */
        @keyframes spin { to { transform: rotate(360deg); } }
        .spinner {
            width: 18px; height: 18px;
            border: 2.5px solid rgba(255,255,255,0.35);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.65s linear infinite;
            display: none;
        }

        @media (max-width: 480px) {
            .card { padding: 30px 22px 26px; }
            .mosaic-bg { grid-template-columns: repeat(3, 1fr); }
        }
    </style>
</head>
<body>

    <!-- MOSAIC BACKGROUND -->
    <div class="mosaic-bg" aria-hidden="true">
        <!-- Row 1 -->
        <div class="mosaic-tile tile-photo"></div>
        <div class="mosaic-tile tile-gradient-1">
            <div class="tile-text-overlay"><span>Life<br>Generation</span></div>
        </div>
        <div class="mosaic-tile tile-photo"></div>
        <div class="mosaic-tile tile-gradient-2">
            <div class="tile-text-overlay"><span>Faith &<br>Community</span></div>
        </div>
        <div class="mosaic-tile tile-photo"></div>

        <!-- Row 2 -->
        <div class="mosaic-tile tile-gradient-3">
            <div class="tile-text-overlay"><span>Grow<br>Together</span></div>
        </div>
        <div class="mosaic-tile tile-photo"></div>
        <div class="mosaic-tile tile-photo"></div>
        <div class="mosaic-tile tile-photo"></div>
        <div class="mosaic-tile tile-gradient-4">
            <div class="tile-text-overlay"><span>You're<br>Welcome<br>Here</span></div>
        </div>

        <!-- Row 3 -->
        <div class="mosaic-tile tile-photo"></div>
        <div class="mosaic-tile tile-gradient-5">
            <div class="tile-text-overlay"><span>One<br>Family</span></div>
        </div>
        <div class="mosaic-tile tile-photo"></div>
        <div class="mosaic-tile tile-gradient-6">
            <div class="tile-text-overlay"><span>Fam Fam<br>Fam</span></div>
        </div>
        <div class="mosaic-tile tile-photo"></div>
    </div>

    <!-- Dark overlay -->
    <div class="bg-overlay" aria-hidden="true"></div>

    <!-- MAIN CONTENT -->
    <div class="page-wrapper">
        <a href="<?= site_url('/') ?>" class="back-link">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Back to Community Home
        </a>

        <div class="card">
            <!-- Logo -->
            <div class="logo-wrap">
                <img src="<?= site_url('assets/images/lifegen-logo.png') ?>" alt="Life Generation Logo">
            </div>

            <h1 class="card-title">Welcome!</h1>
            <p class="card-subtitle">
                Log in to <strong>Life Generation</strong> to continue to the fellowship portal.
            </p>

            <!-- Error Alert -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-error" role="alert">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- Success Alert -->
            <?php if (!empty($success)): ?>
                <div class="alert alert-success" role="alert">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span><?= htmlspecialchars($success) ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('login') ?>" method="POST" autocomplete="off" id="loginForm">

                <!-- Identifier -->
                <div class="form-group">
                    <label class="form-label" for="identifier">
                        Email address <span class="required-star">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon-left">
                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            name="identifier"
                            id="identifier"
                            class="form-input"
                            placeholder="Member ID, username, or email"
                            value="<?= isset($username_val) ? htmlspecialchars($username_val) : '' ?>"
                            required
                            autofocus
                        >
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label" for="password">
                        Password <span class="required-star">*</span>
                    </label>
                    <div class="input-wrapper">
                        <span class="input-icon-left">
                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-input"
                            placeholder="••••••••••••"
                            required
                        >
                        <button type="button" class="toggle-password-btn" id="togglePasswordBtn" title="Toggle password visibility" aria-label="Toggle password visibility">
                            <svg id="eyeOpenIcon" style="display:none;" width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eyeClosedIcon" width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    <p class="password-hint" id="pwFeedback">Masked securely while typing.</p>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <span id="btnText">Continue</span>
                    <div class="spinner" id="btnSpinner"></div>
                    <svg id="btnArrow" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            <p class="card-footer-text">
                First time with us? <a href="<?= site_url('register') ?>">Sign up here</a>
            </p>
        </div>
    </div>

    <script>
        // Password toggle
        const passwordInput   = document.getElementById('password');
        const toggleBtn       = document.getElementById('togglePasswordBtn');
        const eyeOpenIcon     = document.getElementById('eyeOpenIcon');
        const eyeClosedIcon   = document.getElementById('eyeClosedIcon');
        const pwFeedback      = document.getElementById('pwFeedback');

        toggleBtn.addEventListener('click', function () {
            const isHidden = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isHidden ? 'text' : 'password');
            eyeOpenIcon.style.display   = isHidden ? 'block' : 'none';
            eyeClosedIcon.style.display = isHidden ? 'none'  : 'block';
            toggleBtn.style.color       = isHidden ? 'var(--orange)' : '';
            pwFeedback.textContent      = isHidden ? 'Password is visible.' : 'Masked securely while typing.';
        });

        passwordInput.addEventListener('input', function () {
            if (this.getAttribute('type') === 'password') {
                pwFeedback.textContent = this.value.length > 0
                    ? this.value.length + ' characters entered.'
                    : 'Masked securely while typing.';
            }
        });

        // Loading state on submit
        const form      = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText   = document.getElementById('btnText');
        const spinner   = document.getElementById('btnSpinner');
        const btnArrow  = document.getElementById('btnArrow');

        form.addEventListener('submit', function () {
            submitBtn.disabled      = true;
            btnText.textContent     = 'Signing in...';
            spinner.style.display   = 'block';
            btnArrow.style.display  = 'none';
        });
    </script>

</body>
</html>
