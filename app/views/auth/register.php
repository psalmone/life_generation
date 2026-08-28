<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Membership Registration - Life Generation' ?></title>
    <meta name="description" content="Register for your Life Generation membership. Fill in your name, age, address, and email to get started.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-body:        #f8fafc;
            --card-bg:        #ffffff;
            --card-border:    #e2e8f0;
            --input-bg:       #f8fafc;
            --input-border:   #cbd5e1;
            --primary:        #2563eb;
            --primary-hover:  #1d4ed8;
            --primary-glow:   rgba(37, 99, 235, 0.2);
            --amber-gold:     #d97706;
            --text-primary:   #0f172a;
            --text-secondary: #475569;
            --text-muted:     #64748b;
            --success:        #16a34a;
            --error:          #dc2626;
            --shadow-card:    0 20px 40px -10px rgba(15, 23, 42, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.04);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
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

        .ambient-glow-1 {
            position: fixed; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.08) 0%, transparent 70%);
            top: -120px; right: -120px; z-index: 0;
            pointer-events: none; filter: blur(55px);
        }
        .ambient-glow-2 {
            position: fixed; width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.08) 0%, transparent 70%);
            bottom: -100px; left: -100px; z-index: 0;
            pointer-events: none; filter: blur(55px);
        }

        .container {
            width: 100%;
            max-width: 640px;
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

        /* Card */
        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 28px;
            padding: 42px;
            box-shadow: var(--shadow-card);
            animation: cardIn 0.4s cubic-bezier(0.22,1,0.36,1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Header */
        .header { text-align: center; margin-bottom: 32px; }

        .logo-badge {
            width: 60px; height: 60px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #f59e0b 100%);
            border-radius: 18px;
            display: inline-flex; align-items: center; justify-content: center;
            margin-bottom: 18px;
            box-shadow: 0 12px 28px var(--primary-glow);
            color: #ffffff;
        }
        .logo-badge svg { width: 30px; height: 30px; }

        .title {
            font-size: 1.8rem; font-weight: 800;
            letter-spacing: -0.03em; color: #0f172a; margin-bottom: 8px;
        }
        .subtitle { font-size: 0.925rem; color: var(--text-secondary); }

        /* Step badge */
        .form-section-label {
            display: flex; align-items: center; gap: 10px;
            margin: 24px 0 16px;
        }
        .form-section-label .section-badge {
            width: 26px; height: 26px; border-radius: 50%;
            background: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700; color: #fff; flex-shrink: 0;
        }
        .form-section-label .section-title {
            font-size: 0.85rem; font-weight: 700; color: var(--text-primary);
            text-transform: uppercase; letter-spacing: 0.8px;
        }
        .section-divider {
            flex: 1; height: 1px; background: var(--card-border);
        }

        /* Alerts */
        .alert {
            padding: 14px 18px; border-radius: 14px;
            font-size: 0.875rem; margin-bottom: 24px;
            display: flex; align-items: flex-start; gap: 12px;
            line-height: 1.5; animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .alert-error   { background: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; }
        .alert-success { background: #f0fdf4; border: 1px solid #dcfce7; color: #166534; }

        /* Form Grid */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        .col-span-2 { grid-column: span 2; }

        .form-group { display: flex; flex-direction: column; gap: 7px; }

        .form-label {
            font-size: 0.825rem; font-weight: 700; color: var(--text-secondary);
            display: flex; justify-content: space-between; align-items: center;
        }
        .form-label .req { color: #dc2626; margin-left: 2px; }

        .input-wrapper { position: relative; display: flex; align-items: center; }

        .input-icon-left {
            position: absolute; left: 14px;
            color: var(--text-muted);
            display: flex; align-items: center; justify-content: center;
            pointer-events: none; transition: color 0.2s;
        }

        .form-input, .form-select, .form-textarea {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 12px;
            padding: 12px 14px 12px 44px;
            color: var(--text-primary);
            font-size: 0.925rem; outline: none;
            transition: all 0.2s ease;
        }
        .form-textarea {
            padding-left: 44px;
            min-height: 80px;
            resize: vertical;
            line-height: 1.5;
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
            background: #ffffff;
        }
        .input-wrapper:focus-within .input-icon-left { color: var(--primary); }

        .input-wrapper.textarea-wrapper .input-icon-left { top: 14px; align-items: flex-start; }

        .toggle-password-btn {
            position: absolute; right: 12px;
            background: none; border: none; color: var(--text-muted);
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            padding: 4px; border-radius: 6px; transition: color 0.2s;
        }
        .toggle-password-btn:hover { color: var(--text-primary); background: #e2e8f0; }

        /* Membership Tier Pills */
        .tier-picker { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; margin-top: 2px; }

        .tier-option { position: relative; }
        .tier-option input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
        .tier-label {
            display: flex; flex-direction: column; align-items: center;
            padding: 12px 6px; border: 1px solid var(--input-border);
            border-radius: 12px; background: var(--input-bg);
            cursor: pointer; transition: all 0.2s; text-align: center;
            font-size: 0.775rem; font-weight: 700; color: var(--text-secondary);
        }
        .tier-option input[type="radio"]:checked + .tier-label {
            border-color: var(--primary);
            background: #eff6ff;
            color: var(--primary);
            box-shadow: 0 0 0 1px var(--primary);
        }
        .tier-label:hover { border-color: var(--primary); }
        .tier-badge-icon { font-size: 1.2rem; margin-bottom: 5px; }

        .info-note {
            display: flex; align-items: flex-start; gap: 10px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 12px; padding: 12px 14px;
            font-size: 0.825rem; color: #1e40af; line-height: 1.5;
        }
        .info-note svg { flex-shrink: 0; color: var(--primary); margin-top: 1px; }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            background: var(--primary);
            border: none; border-radius: 14px;
            padding: 15px 24px; color: #fff;
            font-size: 1rem; font-weight: 700; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            box-shadow: 0 10px 25px var(--primary-glow);
            transition: all 0.25s ease; margin-top: 28px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(37, 99, 235, 0.35);
            background: var(--primary-hover);
        }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit .spinner { display: none; }
        .btn-submit.loading .spinner { display: block; animation: spin 0.8s linear infinite; }
        .btn-submit.loading .btn-text { display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }

        .card-footer {
            text-align: center; margin-top: 24px;
            font-size: 0.875rem; color: var(--text-secondary);
        }
        .link { color: var(--primary); text-decoration: none; font-weight: 700; transition: color 0.2s; }
        .link:hover { color: var(--primary-hover); text-decoration: underline; }

        .guarantee {
            margin-top: 20px;
            display: flex; align-items: center; justify-content: center;
            gap: 8px; font-size: 0.78rem; color: var(--text-muted);
        }

        @media (max-width: 600px) {
            .form-grid { grid-template-columns: 1fr; }
            .col-span-2 { grid-column: span 1; }
            .tier-picker { grid-template-columns: 1fr 1fr; }
            .card { padding: 28px 20px; }
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
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v20M7 8h10"/>
                    </svg>
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

                <!-- ── Section 2: Membership Tier ── -->
                <div class="form-section-label">
                    <div class="section-badge">2</div>
                    <span class="section-title">Membership Tier</span>
                    <div class="section-divider"></div>
                </div>

                <?php $current_tier = $old['membership_tier'] ?? 'Standard Member'; ?>
                <div class="tier-picker" role="group" aria-label="Select membership tier">
                    <div class="tier-option">
                        <input type="radio" name="membership_tier" id="tier_standard" value="Standard Member" <?= $current_tier === 'Standard Member' ? 'checked' : '' ?>>
                        <label for="tier_standard" class="tier-label">
                            <span class="tier-badge-icon">🥉</span>
                            <span>Standard</span>
                        </label>
                    </div>
                    <div class="tier-option">
                        <input type="radio" name="membership_tier" id="tier_silver" value="Silver Member" <?= $current_tier === 'Silver Member' ? 'checked' : '' ?>>
                        <label for="tier_silver" class="tier-label">
                            <span class="tier-badge-icon">🥈</span>
                            <span>Silver</span>
                        </label>
                    </div>
                    <div class="tier-option">
                        <input type="radio" name="membership_tier" id="tier_gold" value="Gold Member" <?= $current_tier === 'Gold Member' ? 'checked' : '' ?>>
                        <label for="tier_gold" class="tier-label">
                            <span class="tier-badge-icon">🥇</span>
                            <span>Gold</span>
                        </label>
                    </div>
                    <div class="tier-option">
                        <input type="radio" name="membership_tier" id="tier_vip" value="VIP Member" <?= $current_tier === 'VIP Member' ? 'checked' : '' ?>>
                        <label for="tier_vip" class="tier-label">
                            <span class="tier-badge-icon">💎</span>
                            <span>VIP</span>
                        </label>
                    </div>
                </div>

                <!-- ── Section 3: Account Security ── -->
                <div class="form-section-label">
                    <div class="section-badge">3</div>
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
                Already a member? <a href="<?= site_url('login') ?>" class="link">Sign In Here</a>
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
