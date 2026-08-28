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
    <style>
        :root {
            --bg-body: #f8fafc;
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --input-bg: #f8fafc;
            --input-border: #cbd5e1;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-glow: rgba(37, 99, 235, 0.2);
            --amber-gold: #d97706;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --success: #16a34a;
            --error: #dc2626;
            --shadow-card: 0 20px 40px -10px rgba(15, 23, 42, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.04);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: var(--bg-body);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow-x: hidden;
            color: var(--text-primary);
        }

        /* Ambient Glow Backgrounds */
        .ambient-glow-1 {
            position: fixed;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.08) 0%, transparent 70%);
            top: -100px;
            left: -100px;
            z-index: 0;
            pointer-events: none;
            filter: blur(40px);
        }

        .ambient-glow-2 {
            position: fixed;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.08) 0%, transparent 70%);
            bottom: -100px;
            right: -100px;
            z-index: 0;
            pointer-events: none;
            filter: blur(40px);
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
            gap: 6px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 600;
            margin-bottom: 20px;
            transition: color 0.2s ease;
        }

        .back-home:hover {
            color: var(--primary);
        }

        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            padding: 40px 36px;
            box-shadow: var(--shadow-card);
            transition: transform 0.3s ease;
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-badge {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #f59e0b 100%);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 8px 20px -4px var(--primary-glow);
            color: #ffffff;
        }

        .logo-badge svg {
            width: 28px;
            height: 28px;
        }

        .title {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 0.95rem;
            color: var(--text-secondary);
        }

        /* Flash Message Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.875rem;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            line-height: 1.4;
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
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text-secondary);
            margin-bottom: 8px;
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
            justify-content: center;
            transition: color 0.2s;
        }

        .form-input {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 12px;
            padding: 13px 44px 13px 44px;
            font-size: 0.95rem;
            color: var(--text-primary);
            outline: none;
            transition: all 0.25s ease;
        }

        .form-input.no-left-icon {
            padding-left: 16px;
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
            background: #ffffff;
        }

        .form-group:focus-within .input-icon-left {
            color: var(--primary);
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
            background: #e2e8f0;
        }

        .toggle-password-btn:active {
            transform: scale(0.92);
        }

        .password-feedback {
            font-size: 0.78rem;
            margin-top: 6px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 15px -3px var(--primary-glow);
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 8px 25px -4px var(--primary-glow);
        }

        .btn-submit:active {
            transform: translateY(1px);
        }

        /* Footer */
        .card-footer {
            margin-top: 28px;
            text-align: center;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            transition: color 0.2s;
        }

        .link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .migration-hint {
            margin-top: 24px;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-muted);
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
                    <!-- Cross Symbol -->
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v20M7 8h10"/>
                    </svg>
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
                New to the fellowship? <a href="<?= site_url('register') ?>" class="link">Join as a Member</a>
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
