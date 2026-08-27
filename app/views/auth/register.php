<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Create an Account' ?></title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #090d16;
            --card-bg: rgba(18, 24, 38, 0.75);
            --card-border: rgba(255, 255, 255, 0.08);
            --input-bg: rgba(15, 23, 42, 0.65);
            --input-border: rgba(255, 255, 255, 0.12);
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --primary-glow: rgba(99, 102, 241, 0.35);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --success: #10b981;
            --error: #ef4444;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow-x: hidden;
            color: var(--text-primary);
        }

        .ambient-glow-1 {
            position: fixed;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.22) 0%, rgba(99, 102, 241, 0) 70%);
            top: -100px;
            right: -100px;
            z-index: 0;
            pointer-events: none;
            filter: blur(40px);
        }

        .ambient-glow-2 {
            position: fixed;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.2) 0%, rgba(168, 85, 247, 0) 70%);
            bottom: -100px;
            left: -100px;
            z-index: 0;
            pointer-events: none;
            filter: blur(40px);
        }

        .container {
            width: 100%;
            max-width: 480px;
            position: relative;
            z-index: 1;
        }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            padding: 36px;
            box-shadow: 0 20px 50px -10px rgba(0, 0, 0, 0.7),
                        0 0 0 1px rgba(255, 255, 255, 0.05);
        }

        .header {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo-badge {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 8px 20px -4px var(--primary-glow);
        }

        .logo-badge svg {
            width: 26px;
            height: 26px;
            color: #ffffff;
        }

        .title {
            font-size: 1.65rem;
            font-weight: 700;
            letter-spacing: -0.025em;
            color: #ffffff;
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.875rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            line-height: 1.4;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.12);
            border: 1px solid rgba(16, 185, 129, 0.25);
            color: #6ee7b7;
        }

        .form-group {
            margin-bottom: 16px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
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
            color: var(--text-muted);
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        .form-input {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 12px;
            padding: 12px 42px 12px 42px;
            font-size: 0.92rem;
            color: var(--text-primary);
            outline: none;
            transition: all 0.25s ease;
        }

        .form-input::placeholder {
            color: var(--text-muted);
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
            background: rgba(15, 23, 42, 0.85);
        }

        .form-group:focus-within .input-icon-left {
            color: var(--primary);
        }

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
            background: rgba(255, 255, 255, 0.08);
        }

        .password-feedback {
            font-size: 0.75rem;
            margin-top: 4px;
            color: var(--text-muted);
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-size: 0.98rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 15px -3px var(--primary-glow);
            margin-top: 16px;
        }

        .btn-submit:hover {
            opacity: 0.95;
            transform: translateY(-1px);
            box-shadow: 0 8px 25px -4px var(--primary-glow);
        }

        .card-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 0.88rem;
            color: var(--text-secondary);
        }

        .link {
            color: #818cf8;
            text-decoration: none;
            font-weight: 600;
        }

        .link:hover {
            color: #a5b4fc;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <div class="container">
        <div class="card">
            <div class="header">
                <div class="logo-badge">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <h1 class="title">Create Account</h1>
                <p class="subtitle">Join Life Generation today</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('register') ?>" method="POST" autocomplete="off">
                
                <!-- Username -->
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <div class="input-wrapper">
                        <span class="input-icon-left">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </span>
                        <input 
                            type="text" 
                            name="username" 
                            id="username" 
                            class="form-input" 
                            placeholder="Choose a username"
                            value="<?= isset($old['username']) ? htmlspecialchars($old['username']) : '' ?>"
                            required 
                            autofocus
                        >
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <div class="input-wrapper">
                        <span class="input-icon-left">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="form-input" 
                            placeholder="name@example.com"
                            value="<?= isset($old['email']) ? htmlspecialchars($old['email']) : '' ?>"
                            required
                        >
                    </div>
                </div>

                <!-- Password with Toggle -->
                <div class="form-group">
                    <label class="form-label" for="regPassword">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon-left">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input 
                            type="password" 
                            name="password" 
                            id="regPassword" 
                            class="form-input" 
                            placeholder="At least 6 characters"
                            required
                        >
                        <button type="button" class="toggle-password-btn" id="regToggleBtn" title="Toggle password visibility">
                            <svg id="regEyeOpen" style="display: none;" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="regEyeClosed" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label class="form-label" for="regPasswordConfirm">Confirm Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon-left">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </span>
                        <input 
                            type="password" 
                            name="password_confirm" 
                            id="regPasswordConfirm" 
                            class="form-input" 
                            placeholder="Repeat password"
                            required
                        >
                    </div>
                    <div class="password-feedback" id="matchFeedback">
                        <span>Passwords are hashed with BCrypt prior to storing.</span>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <span>Create Account</span>
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            <div class="card-footer">
                Already have an account? <a href="<?= site_url('login') ?>" class="link">Sign in here</a>
            </div>
        </div>
    </div>

    <script>
        const regPassword = document.getElementById('regPassword');
        const regToggleBtn = document.getElementById('regToggleBtn');
        const regEyeOpen = document.getElementById('regEyeOpen');
        const regEyeClosed = document.getElementById('regEyeClosed');
        const regPasswordConfirm = document.getElementById('regPasswordConfirm');
        const matchFeedback = document.getElementById('matchFeedback');

        regToggleBtn.addEventListener('click', function() {
            const isPassword = regPassword.getAttribute('type') === 'password';
            if (isPassword) {
                regPassword.setAttribute('type', 'text');
                regPasswordConfirm.setAttribute('type', 'text');
                regEyeOpen.style.display = 'block';
                regEyeClosed.style.display = 'none';
                regToggleBtn.style.color = 'var(--primary)';
            } else {
                regPassword.setAttribute('type', 'password');
                regPasswordConfirm.setAttribute('type', 'password');
                regEyeOpen.style.display = 'none';
                regEyeClosed.style.display = 'block';
                regToggleBtn.style.color = 'var(--text-muted)';
            }
        });

        // Real-time password match feedback
        function checkMatch() {
            const p1 = regPassword.value;
            const p2 = regPasswordConfirm.value;
            if (p2.length > 0) {
                if (p1 === p2) {
                    matchFeedback.innerHTML = '<span style="color: var(--success);">✓ Passwords match</span>';
                } else {
                    matchFeedback.innerHTML = '<span style="color: var(--error);">✕ Passwords do not match yet</span>';
                }
            } else {
                matchFeedback.innerHTML = '<span>Passwords are hashed with BCrypt prior to storing.</span>';
            }
        }

        regPassword.addEventListener('input', checkMatch);
        regPasswordConfirm.addEventListener('input', checkMatch);
    </script>
</body>
</html>
