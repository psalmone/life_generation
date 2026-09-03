<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Membership Registration - Life Generation' ?></title>
    <meta name="description" content="Register for your Life Generation membership. Fill in your name, age, address, and email to get started.">
    <link rel="icon" type="image/png" href="<?= site_url('assets/images/lifegen-logo.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            /* Minimal Clean Palette: Orange, Blue & Crisp White */
            --bg-body:            #fafaf9;
            --card-bg:            #ffffff;
            --card-border:        #f1f1ef;
            --card-border-subtle: #f5f5f4;
            --input-bg:           #fbfbfb;
            --input-border:       #e7e5e4;
            
            /* Orange accents */
            --orange-primary:     #f97316;
            --orange-hover:       #ea580c;
            --orange-soft:        #fff7ed;
            --orange-border:      #fed7aa;
            --orange-glow:        rgba(249, 115, 22, 0.18);

            /* Blue accents */
            --blue-primary:       #2563eb;
            --blue-hover:         #1d4ed8;
            --blue-soft:          #eff6ff;
            --blue-border:        #dbeafe;
            --blue-glow:          rgba(37, 99, 235, 0.16);

            /* System Tokens */
            --primary:            var(--orange-primary);
            --primary-hover:      var(--orange-hover);
            --accent-blue:        var(--blue-primary);
            --accent-blue-hover:  var(--blue-hover);

            --text-primary:       #1c1917;
            --text-secondary:     #57534e;
            --text-muted:         #78716c;
            --text-light:         #a8a29e;
            
            --success:            #16a34a;
            --error:              #dc2626;
            --shadow-card:        0 20px 45px -12px rgba(28, 25, 23, 0.06), 0 0 0 1px rgba(0, 0, 0, 0.03);
            --shadow-btn:         0 4px 14px rgba(249, 115, 22, 0.24);
            --shadow-btn-hover:   0 8px 20px rgba(249, 115, 22, 0.32);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        body {
            background-color: var(--bg-body);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 36px 16px;
            position: relative;
            overflow-x: hidden;
            color: var(--text-primary);
        }

        /* Minimal soft ambient backdrops */
        .ambient-glow-1 {
            position: fixed; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.06) 0%, rgba(249, 115, 22, 0.01) 50%, transparent 70%);
            top: -120px; right: -120px; z-index: 0;
            pointer-events: none; filter: blur(55px);
        }
        .ambient-glow-2 {
            position: fixed; width: 550px; height: 550px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.05) 0%, rgba(37, 99, 235, 0.01) 50%, transparent 70%);
            bottom: -100px; left: -100px; z-index: 0;
            pointer-events: none; filter: blur(55px);
        }

        .container {
            width: 100%;
            max-width: 600px;
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

        /* Card */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 26px;
            padding: 42px 38px;
            box-shadow: var(--shadow-card);
            animation: cardIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Header */
        .header { text-align: center; margin-bottom: 28px; }

        .logo-badge {
            width: 68px; height: 68px;
            border-radius: 18px;
            display: inline-flex; align-items: center; justify-content: center;
            margin-bottom: 16px;
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
            background: var(--blue-soft);
            color: var(--blue-primary);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 12px;
            border: 1px solid var(--blue-border);
        }

        .brand-tag span.dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--blue-primary);
        }

        .title {
            font-size: 1.7rem; font-weight: 800;
            letter-spacing: -0.03em; color: var(--text-primary); margin-bottom: 6px;
        }
        .subtitle { font-size: 0.9rem; color: var(--text-muted); line-height: 1.45; }

        /* Step badge */
        .form-section-label {
            display: flex; align-items: center; gap: 10px;
            margin: 24px 0 16px;
        }
        .form-section-label .section-badge {
            width: 24px; height: 24px; border-radius: 50%;
            background: var(--orange-soft);
            border: 1px solid var(--orange-border);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 800; color: var(--orange-primary); flex-shrink: 0;
        }
        .form-section-label .section-title {
            font-size: 0.78rem; font-weight: 800; color: var(--text-secondary);
            text-transform: uppercase; letter-spacing: 0.06em;
        }
        .section-divider {
            flex: 1; height: 1px; background: var(--card-border);
        }

        /* Alerts */
        .alert {
            padding: 12px 16px; border-radius: 12px;
            font-size: 0.85rem; margin-bottom: 22px;
            display: flex; align-items: flex-start; gap: 12px;
            line-height: 1.45; animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .alert-error   { background: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; }
        .alert-success { background: #f0fdf4; border: 1px solid #dcfce7; color: #166534; }

        /* Form Grid */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .col-span-2 { grid-column: span 2; }

        .form-group { display: flex; flex-direction: column; gap: 6px; }

        .form-label {
            font-size: 0.78rem; font-weight: 700; color: var(--text-secondary);
            display: flex; justify-content: space-between; align-items: center;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .form-label .req { color: var(--orange-primary); margin-left: 3px; }

        .input-wrapper { position: relative; display: flex; align-items: center; }

        .input-icon-left {
            position: absolute; left: 14px;
            color: var(--text-light);
            display: flex; align-items: center; justify-content: center;
            pointer-events: none; transition: color 0.2s;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 12px;
            padding: 12px 14px 12px 42px;
            color: var(--text-primary);
            font-size: 0.925rem; outline: none;
            transition: all 0.2s ease;
        }
        .form-input::placeholder, .form-textarea::placeholder {
            color: #a8a29e;
            font-size: 0.88rem;
        }
        .form-textarea {
            padding-left: 42px;
            min-height: 80px;
            resize: vertical;
            line-height: 1.5;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--orange-primary);
            box-shadow: 0 0 0 3px var(--orange-glow);
            background: #ffffff;
        }
        .input-wrapper:focus-within .input-icon-left { color: var(--orange-primary); }

        .input-wrapper.textarea-wrapper .input-icon-left { top: 14px; align-items: flex-start; }

        .toggle-password-btn {
            position: absolute; right: 12px;
            background: none; border: none; color: var(--text-muted);
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            padding: 6px; border-radius: 8px; transition: all 0.2s;
        }
        .toggle-password-btn:hover { color: var(--text-primary); background: #f5f5f4; }

        .info-note {
            display: flex; align-items: flex-start; gap: 10px;
            background: var(--blue-soft);
            border: 1px solid var(--blue-border);
            border-radius: 12px; padding: 11px 14px;
            font-size: 0.825rem; color: #1e40af; line-height: 1.45;
        }
        .info-note svg { flex-shrink: 0; color: var(--blue-primary); margin-top: 1px; }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
            border: none; border-radius: 12px;
            padding: 14px 22px; color: #fff;
            font-size: 0.95rem; font-weight: 700; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            box-shadow: var(--shadow-btn);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); margin-top: 24px;
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-btn-hover);
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        }
        .btn-submit:active { transform: translateY(1px); }
        .btn-submit .spinner { display: none; }
        .btn-submit.loading .spinner { display: block; animation: spin 0.8s linear infinite; }
        .btn-submit.loading .btn-text { display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .card-footer {
            text-align: center; margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--card-border-subtle);
            font-size: 0.88rem; color: var(--text-secondary);
        }
        .link-blue { color: var(--blue-primary); text-decoration: none; font-weight: 700; transition: color 0.15s ease; }
        .link-blue:hover { color: var(--blue-hover); text-decoration: underline; }

        .guarantee {
            margin-top: 16px;
            display: flex; align-items: center; justify-content: center;
            gap: 7px; font-size: 0.78rem; color: var(--text-muted);
        }
        .guarantee svg { color: var(--blue-primary); }

        @media (max-width: 600px) {
            .form-grid { grid-template-columns: 1fr; }
            .col-span-2 { grid-column: span 1; }
            .card { padding: 32px 20px; }
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
                    <span class="brand-tag"><span class="dot"></span> Fellowship Registration</span>
                </div>
                <h1 class="title">Join Life Generation</h1>
                <p class="subtitle">Complete your membership registration below</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error" role="alert">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div><?= htmlspecialchars($error) ?></div>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success" role="alert">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:1px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div><?= $success ?></div>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('register') ?>" method="POST" autocomplete="off" id="regForm" novalidate>

                <!-- ── Section 1: Personal Information ── -->
                <div class="form-section-label">
                    <div class="section-badge">1</div>
                    <span class="section-title">Personal Information</span>
                    <div class="section-divider"></div>
                </div>

                <div class="form-grid">

                    <!-- Full Name -->
                    <div class="form-group col-span-2">
                        <label class="form-label" for="full_name">
                            Full Name <span class="req">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon-left">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </span>
                            <input
                                type="text"
                                name="full_name"
                                id="full_name"
                                class="form-input"
                                placeholder="e.g. Juan dela Cruz"
                                value="<?= isset($old['full_name']) ? htmlspecialchars($old['full_name']) : '' ?>"
                                required
                                autofocus
                            >
                        </div>
                    </div>

                    <!-- Email Address -->
                    <div class="form-group">
                        <label class="form-label" for="email">
                            Email Address <span class="req">*</span>
                        </label>
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
                                placeholder="you@example.com"
                                value="<?= isset($old['email']) ? htmlspecialchars($old['email']) : '' ?>"
                                required
                            >
                        </div>
                    </div>

                    <!-- Age -->
                    <div class="form-group">
                        <label class="form-label" for="age">
                            Age <span class="req">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon-left">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <input
                                type="number"
                                name="age"
                                id="age"
                                class="form-input"
                                placeholder="e.g. 28"
                                min="1"
                                max="125"
                                value="<?= isset($old['age']) ? htmlspecialchars($old['age']) : '' ?>"
                                required
                            >
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="form-group col-span-2">
                        <label class="form-label" for="address">
                            Complete Address <span class="req">*</span>
                        </label>
                        <div class="input-wrapper textarea-wrapper">
                            <span class="input-icon-left">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </span>
                            <textarea
                                name="address"
                                id="address"
                                class="form-textarea"
                                placeholder="Street, Barangay / City, Province, Postal Code"
                                required
                            ><?= isset($old['address']) ? htmlspecialchars($old['address']) : '' ?></textarea>
                        </div>
                    </div>

                </div>

                <!-- ── Section 2: Account Security ── -->
                <div class="form-section-label">
                    <div class="section-badge">2</div>
                    <span class="section-title">Account Security</span>
                    <div class="section-divider"></div>
                </div>

                <div class="info-note" style="margin-bottom: 16px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>You can log in with your <strong>email</strong>, <strong>username</strong>, or your <strong>Member ID</strong> after registration.</span>
                </div>

                <div class="form-grid">

                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label" for="password">
                            Password <span class="req">*</span>
                        </label>
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
                                placeholder="Min. 6 characters"
                                required
                                minlength="6"
                            >
                            <button type="button" class="toggle-password-btn" id="togglePwBtn" aria-label="Toggle password visibility">
                                <svg id="pwEyeOpen" style="display:none;" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="pwEyeClosed" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="form-label" for="password_confirm">
                            Confirm Password <span class="req">*</span>
                        </label>
                        <div class="input-wrapper">
                            <span class="input-icon-left">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </span>
                            <input
                                type="password"
                                name="password_confirm"
                                id="password_confirm"
                                class="form-input"
                                placeholder="Repeat password"
                                required
                            >
                        </div>
                    </div>

                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <span class="btn-text">Complete Membership Registration</span>
                    <svg class="spinner" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                    </svg>
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="btn-arrow">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>

            <div class="guarantee">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>Instant digital Member ID will be generated upon registration</span>
            </div>

            <div class="card-footer">
                Already a member? <a href="<?= site_url('login') ?>" class="link-blue">Sign In Here</a>
            </div>

        </div>
    </div>

    <script>
        const pwInput     = document.getElementById('password');
        const toggleBtn   = document.getElementById('togglePwBtn');
        const eyeOpen     = document.getElementById('pwEyeOpen');
        const eyeClosed   = document.getElementById('pwEyeClosed');

        toggleBtn.addEventListener('click', () => {
            const isPw = pwInput.getAttribute('type') === 'password';
            pwInput.setAttribute('type', isPw ? 'text' : 'password');
            eyeOpen.style.display   = isPw ? 'block' : 'none';
            eyeClosed.style.display = isPw ? 'none'  : 'block';
        });

        const form     = document.getElementById('regForm');
        const pwConf   = document.getElementById('password_confirm');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', (e) => {
            if (pwInput.value !== pwConf.value) {
                e.preventDefault();
                pwConf.setCustomValidity('Passwords do not match.');
                pwConf.reportValidity();
                return;
            }
            pwConf.setCustomValidity('');
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        });

        pwConf.addEventListener('input', () => {
            if (pwInput.value === pwConf.value) {
                pwConf.setCustomValidity('');
            }
        });
    </script>
</body>
</html>