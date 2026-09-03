<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Sign In - Life Generation' ?></title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpeg" href="<?= site_url('assets/images/lifegen-logo.png') ?>">
    <style>
        :root {
            /* Minimal Clean Palette: Orange, Blue & Crisp White */
            --bg-body: #fafaf9;
            --card-bg: #ffffff;
            --card-border: #f1f1ef;
            --card-border-subtle: #f5f5f4;
            --input-bg: #fbfbfb;
            --input-border: #e7e5e4;
            --input-focus-border: #f97316;
            
            /* Orange accents */
            --orange-primary: #f97316;
            --orange-hover: #ea580c;
            --orange-soft: #fff7ed;
            --orange-border: #fed7aa;
            --orange-glow: rgba(249, 115, 22, 0.18);

            /* Blue accents */
            --blue-primary: #2563eb;
            --blue-hover: #1d4ed8;
            --blue-soft: #eff6ff;
            --blue-border: #dbeafe;
            --blue-glow: rgba(37, 99, 235, 0.16);

            /* System Tokens */
            --primary: var(--orange-primary);
            --primary-hover: var(--orange-hover);
            --accent-blue: var(--blue-primary);
            --accent-blue-hover: var(--blue-hover);
            
            --text-primary: #1c1917;
            --text-secondary: #57534e;
            --text-muted: #78716c;
            --text-light: #a8a29e;
            
            --success: #16a34a;
            --error: #dc2626;
            --shadow-subtle: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -2px rgba(0, 0, 0, 0.02);
            --shadow-card: 0 20px 45px -12px rgba(28, 25, 23, 0.06), 0 0 0 1px rgba(0, 0, 0, 0.03);
            --shadow-hover: 0 12px 28px -4px rgba(249, 115, 22, 0.28);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        body {
            background-color: var(--bg-body);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
            position: relative;
            overflow-x: hidden;
            color: var(--text-primary);
        }

        /* Minimal soft ambient backdrops */
        .ambient-glow-1 {
            position: fixed;
            width: 550px;
            height: 550px;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.06) 0%, rgba(249, 115, 22, 0.01) 50%, transparent 70%);
            top: -120px;
            right: -100px;
            z-index: 0;
            pointer-events: none;
            filter: blur(50px);
        }

        .ambient-glow-2 {
            position: fixed;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.05) 0%, rgba(37, 99, 235, 0.01) 50%, transparent 70%);
            bottom: -100px;
            left: -100px;
            z-index: 0;
            pointer-events: none;
            filter: blur(50px);
        }

        .container {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
        }

        .back-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.86rem;
            font-weight: 600;
            margin-bottom: 20px;
            padding: 6px 12px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid var(--card-border);
            backdrop-filter: blur(8px);
            transition: all 0.2s ease;
        }

        .back-home:hover {
            color: var(--orange-primary);
            background: #ffffff;
            border-color: var(--orange-border);
            transform: translateX(-2px);
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            padding: 42px 38px;
            box-shadow: var(--shadow-card);
            transition: box-shadow 0.3s ease;
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-badge {
            width: 68px;
            height: 68px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            background: #ffffff;
            border: 1px solid #f1f1ef;
            box-shadow: 0 8px 24px rgba(249, 115, 22, 0.08), 0 2px 6px rgba(37, 99, 235, 0.05);
            padding: 8px;
            transition: transform 0.25s ease;
        }

        .logo-badge:hover {
            transform: scale(1.04);
        }

        .logo-badge img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 9999px;
            background: var(--orange-soft);
            color: var(--orange-primary);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 12px;
            border: 1px solid var(--orange-border);
        }

        .brand-tag span.dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--orange-primary);
        }

        .title {
            font-size: 1.65rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.45;
        }

        /* Flash Message Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.85rem;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            line-height: 1.45;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            color: #991b1b;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            color: #166534;
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--text-secondary);
            margin-bottom: 7px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon-left {
            position: absolute;
            left: 14px;
            color: var(--text-light);
            pointer-events: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .form-input {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 12px;
            padding: 13px 44px 13px 42px;
            font-size: 0.925rem;
            color: var(--text-primary);
            outline: none;
            transition: all 0.2s ease;
        }

        .form-input.no-left-icon {
            padding-left: 16px;
        }

        .form-input::placeholder {
            color: #a8a29e;
            font-size: 0.88rem;
        }

        .form-input:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 3px var(--orange-glow);
            background: #ffffff;
        }

        .form-group:focus-within .input-icon-left {
            color: var(--orange-primary);
        }

        /* Toggle Password Button */
        .toggle-password-btn {
            position: absolute;
            right: 12px;
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .toggle-password-btn:hover {
            color: var(--text-primary);
            background: #f5f5f4;
        }

        .toggle-password-btn:active {
            transform: scale(0.92);
        }

        .password-feedback {
            font-size: 0.76rem;
            margin-top: 6px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Minimal Primary Button - Orange with smooth feel */
        .btn-submit {
            width: 100%;
            padding: 13px 20px;
            background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 14px rgba(249, 115, 22, 0.24);
            margin-top: 12px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(249, 115, 22, 0.32);
        }

        .btn-submit:active {
            transform: translateY(1px);
            box-shadow: 0 2px 8px rgba(249, 115, 22, 0.2);
        }

        /* Secondary action / Member switch */
        .card-footer {
            margin-top: 26px;
            padding-top: 20px;
            border-top: 1px solid var(--card-border-subtle);
            text-align: center;
            font-size: 0.88rem;
            color: var(--text-secondary);
        }

        .link-blue {
            color: var(--blue-primary);
            text-decoration: none;
            font-weight: 700;
            transition: color 0.15s ease;
        }

        .link-blue:hover {
            color: var(--blue-hover);
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <div class="container">
        <a href="<?= site_url('') ?>" class="back-home">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Back to Community Home
        </a>

        <div class="card">
            <div class="header">
                <div class="logo-badge">
                    <img src="<?= site_url('assets/images/lifegen-logo.png') ?>" alt="Life Generation Logo">
                </div>
                <div>
                    <span class="brand-tag"><span class="dot"></span> Life Generation Portal</span>
                </div>
                <h1 class="title">Member Sign In</h1>
                <p class="subtitle">Enter your details to access the fellowship portal</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error" role="alert">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success" role="alert">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span><?= htmlspecialchars($success) ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('login') ?>" method="POST" autocomplete="off" id="loginForm">
                
                <!-- Username / Email / Member ID -->
                <div class="form-group">
                    <label class="form-label" for="identifier">Member ID, Username, or Email</label>
                    <div class="input-wrapper">
                        <span class="input-icon-left">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <input 
                            type="text" 
                            name="identifier" 
                            id="identifier" 
                            class="form-input" 
                            placeholder="e.g. LG-2026-XXXX, user@example.com"
                            value="<?= isset($username_val) ? htmlspecialchars($username_val) : '' ?>"
                            required 
                            autofocus
                        >
                    </div>
                </div>

                <!-- Password with interactive Show / Hide Toggle -->
                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon-left">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                        <!-- Password Visibility Toggle Button -->
                        <button type="button" class="toggle-password-btn" id="togglePasswordBtn" title="Toggle password visibility" aria-label="Toggle password visibility">
                            <svg id="eyeOpenIcon" style="display: none;" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eyeClosedIcon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                    <div class="password-feedback" id="pwFeedback">
                        <span>Masked securely while typing.</span>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <span>Sign In</span>
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            <div class="card-footer">
                New to the fellowship? <a href="<?= site_url('register') ?>" class="link-blue">Join as a Member</a>
            </div>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const eyeOpenIcon = document.getElementById('eyeOpenIcon');
        const eyeClosedIcon = document.getElementById('eyeClosedIcon');
        const pwFeedback = document.getElementById('pwFeedback');

        togglePasswordBtn.addEventListener('click', function() {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            
            if (isPassword) {
                passwordInput.setAttribute('type', 'text');
                eyeOpenIcon.style.display = 'block';
                eyeClosedIcon.style.display = 'none';
                togglePasswordBtn.style.color = 'var(--primary)';
                pwFeedback.innerHTML = '<span style="color: var(--primary); font-weight:600;">Password is visible</span>';
            } else {
                passwordInput.setAttribute('type', 'password');
                eyeOpenIcon.style.display = 'none';
                eyeClosedIcon.style.display = 'block';
                togglePasswordBtn.style.color = 'var(--text-muted)';
                pwFeedback.innerHTML = '<span>Masked securely while typing.</span>';
            }
        });

        passwordInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                if (passwordInput.getAttribute('type') === 'password') {
                    pwFeedback.innerHTML = '<span><strong>' + this.value.length + '</strong> characters entered</span>';
                }
            } else {
                pwFeedback.innerHTML = '<span>Masked securely while typing.</span>';
            }
        });
    </script>
</body>
</html>