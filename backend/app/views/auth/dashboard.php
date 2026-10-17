<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Membership Dashboard - Life Generation' ?></title>
    <meta name="description" content="Life Generation membership dashboard. Manage profile, D-Group, ministries, events, and attendance check-in.">
    <link rel="icon" type="image/png" href="<?= site_url('assets/images/lifegen-logo.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- QR Code Generator & Scanner Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <!-- Early Theme Initialization to prevent flash -->
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('theme');
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const theme = savedTheme ? savedTheme : (systemPrefersDark ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
            } catch(e) {}
        })();
    </script>
    <style>
        :root {
            --color-scheme:       light;
            /* ── Minimal Pastel Palette (Orange, Soft Blue, Crisp White) ── */
            --bg-body:            #fafaf9; /* Warm stone/white minimal backdrop */
            --card-bg:            #ffffff;
            --card-border:        #f1f1ef;
            --card-border-subtle: #f7f7f6;
            
            /* Pastel & Primary Blues */
            --blue-primary:       #3b82f6;
            --blue-soft:          #eff6ff;
            --blue-border:        #dbeafe;
            --blue-hover:         #2563eb;
            --blue-muted:         #60a5fa;

            /* Warm Pastel Oranges */
            --orange-primary:     #f97316;
            --orange-soft:        #fff7ed;
            --orange-border:      #ffedd5;
            --orange-hover:       #ea580c;
            --orange-muted:       #fb923c;
            --orange-pill:        #fed7aa;

            /* System Tokens */
            --primary:            var(--orange-primary);
            --primary-light:      var(--orange-soft);
            --primary-border:     var(--orange-border);
            --primary-gradient:   linear-gradient(135deg, #fb923c 0%, #f97316 100%);
            --blue-gradient:      linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
            --hero-gradient:      linear-gradient(135deg, #fff7ed 0%, #ffffff 50%, #eff6ff 100%);
            
            /* Text Hierarchy */
            --text-primary:       #1c1917;
            --text-secondary:     #44403c;
            --text-muted:         #78716c;
            --text-light:         #a8a29e;

            /* State Indicators */
            --success:            #10b981;
            --success-light:      #f0fdf4;
            --success-border:     #dcfce7;
            --danger:             #f43f5e;
            --danger-light:       #fff1f2;
            --danger-border:      #ffe4e6;
            --warning:            #f59e0b;
            --warning-light:      #fffbeb;
            --warning-border:     #fef3c7;

            /* Soft Minimal Shadows */
            --shadow-xs:          0 1px 2px rgba(28, 25, 23, 0.03);
            --shadow-sm:          0 2px 5px rgba(28, 25, 23, 0.04);
            --shadow-md:          0 8px 20px -4px rgba(28, 25, 23, 0.06);
            --shadow-lg:          0 16px 32px -8px rgba(28, 25, 23, 0.08);

            /* Radius */
            --radius-sm:          8px;
            --radius-md:          12px;
            --radius-lg:          18px;
            --radius-xl:          24px;
            --sidebar-width:      260px;
        }

        /* ── Dark Mode Design Tokens ── */
        [data-theme='dark'] {
            --color-scheme:       dark;
            --bg-body:            #0d1117;
            --card-bg:            #161b22;
            --card-border:        #30363d;
            --card-border-subtle: #21262d;

            --blue-primary:       #38bdf8;
            --blue-soft:          rgba(56, 189, 248, 0.12);
            --blue-border:        rgba(56, 189, 248, 0.28);
            --blue-hover:         #0284c7;
            --blue-muted:         #7dd3fc;

            --orange-primary:     #fb923c;
            --orange-soft:        rgba(251, 146, 60, 0.12);
            --orange-border:      rgba(251, 146, 60, 0.26);
            --orange-hover:       #f97316;
            --orange-muted:       #fdba74;
            --orange-pill:        #7c2d12;

            --primary:            var(--orange-primary);
            --primary-light:      var(--orange-soft);
            --primary-border:     var(--orange-border);
            --primary-gradient:   linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            --blue-gradient:      linear-gradient(135deg, #0284c7 0%, #2563eb 100%);
            --hero-gradient:      linear-gradient(135deg, #1c1917 0%, #161b22 50%, #0f172a 100%);

            --text-primary:       #f0f6fc;
            --text-secondary:     #c9d1d9;
            --text-muted:         #8b949e;
            --text-light:         #6e7681;

            --success:            #34d399;
            --success-light:      rgba(52, 211, 153, 0.12);
            --success-border:     rgba(52, 211, 153, 0.25);
            --danger:             #f87171;
            --danger-light:       rgba(248, 113, 113, 0.12);
            --danger-border:      rgba(248, 113, 113, 0.25);
            --warning:            #fbbf24;
            --warning-light:      rgba(251, 191, 36, 0.12);
            --warning-border:     rgba(251, 191, 36, 0.25);

            --shadow-xs:          0 1px 2px rgba(0, 0, 0, 0.35);
            --shadow-sm:          0 2px 6px rgba(0, 0, 0, 0.45);
            --shadow-md:          0 8px 24px -4px rgba(0, 0, 0, 0.55);
            --shadow-lg:          0 16px 36px -8px rgba(0, 0, 0, 0.65);
        }

        /* ── Dark Mode Component Adjustments ── */
        [data-theme='dark'] body {
            background-color: var(--bg-body);
            background-image: 
                radial-gradient(at 0% 0%, rgba(249, 115, 22, 0.07) 0px, transparent 45%),
                radial-gradient(at 100% 0%, rgba(56, 189, 248, 0.07) 0px, transparent 45%),
                radial-gradient(at 50% 100%, rgba(249, 115, 22, 0.04) 0px, transparent 50%);
        }

        [data-theme='dark'] .sidebar,
        [data-theme='dark'] .topbar,
        [data-theme='dark'] .brand-badge,
        [data-theme='dark'] .sidebar-user-card,
        [data-theme='dark'] .topbar-btn,
        [data-theme='dark'] .date-bar,
        [data-theme='dark'] .stat-box,
        [data-theme='dark'] .item-card,
        [data-theme='dark'] .user-app-badge,
        [data-theme='dark'] .modal-card,
        [data-theme='dark'] .form-input,
        [data-theme='dark'] .form-textarea,
        [data-theme='dark'] .form-select,
        [data-theme='dark'] .clean-table th,
        [data-theme='dark'] .clean-table td {
            background-color: var(--card-bg);
            border-color: var(--card-border);
        }

        [data-theme='dark'] .topbar {
            background: rgba(22, 27, 34, 0.88);
        }

        [data-theme='dark'] .topbar-btn {
            color: var(--text-secondary);
        }

        [data-theme='dark'] .topbar-btn:hover {
            background: #21262d;
            border-color: #30363d;
            color: var(--orange-primary);
        }

        [data-theme='dark'] .topbar-theme-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-xs);
        }

        [data-theme='dark'] .form-input,
        [data-theme='dark'] .form-textarea,
        [data-theme='dark'] .form-select {
            background: #0d1117;
            color: var(--text-primary);
            border-color: var(--card-border);
        }

        [data-theme='dark'] .clean-table th {
            background: #1c2128;
            color: var(--text-muted);
            border-color: var(--card-border);
        }

        [data-theme='dark'] .clean-table td {
            border-color: var(--card-border-subtle);
        }

        [data-theme='dark'] .clean-table tr:hover td {
            background: rgba(255, 255, 255, 0.03);
        }

        [data-theme='dark'] .btn-sidebar-logout,
        [data-theme='dark'] .btn-action-toggle,
        [data-theme='dark'] .role-tag {
            background: #21262d;
            border-color: var(--card-border);
            color: var(--text-secondary);
        }

        [data-theme='dark'] .membership-id-card {
            border-color: rgba(56, 189, 248, 0.25);
            box-shadow: 0 14px 34px -6px rgba(0, 0, 0, 0.6);
        }

        [data-theme='dark'] .card-brand-sub,
        [data-theme='dark'] .card-info-val {
            color: #e0f2fe;
        }

        [data-theme='dark'] .card-brand-title,
        [data-theme='dark'] .card-id-label,
        [data-theme='dark'] .card-info-label {
            color: #94a3b8;
        }

        [data-theme='dark'] .card-avatar {
            color: #e0f2fe;
        }

        [data-theme='dark'] .modal-close-btn {
            background: #21262d;
            color: var(--text-muted);
        }

        [data-theme='dark'] .modal-close-btn:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        /* Light mode default for topbar theme toggle */
        .topbar-theme-toggle {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            background: #ffffff;
            border: 1px solid var(--card-border);
            color: var(--text-secondary);
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: var(--shadow-xs);
        }

        .topbar-theme-toggle:hover {
            background: var(--orange-soft);
            border-color: var(--orange-border);
            color: var(--orange-primary);
            transform: translateY(-1px);
        }

        .topbar-theme-toggle svg {
            width: 17px;
            height: 17px;
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .topbar-theme-toggle:hover svg {
            transform: rotate(20deg) scale(1.1);
        }

        *, *::before, *::after {
            box-sizing: border-box; margin: 0; padding: 0;
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: var(--bg-body);
            min-height: 100vh;
            color: var(--text-primary);
            display: flex;
            -webkit-font-smoothing: antialiased;
            background-image: 
                radial-gradient(at 0% 0%, rgba(249, 115, 22, 0.035) 0px, transparent 45%),
                radial-gradient(at 100% 0%, rgba(59, 130, 246, 0.035) 0px, transparent 45%),
                radial-gradient(at 50% 100%, rgba(254, 215, 170, 0.04) 0px, transparent 50%);
        }

        /* ── Sidebar (Minimalist White with Pastel Orange Accents) ── */
        .sidebar {
            width: var(--sidebar-width);
            background: #ffffff;
            border-right: 1px solid var(--card-border);
            height: 100vh;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 24px 16px 20px;
            z-index: 100;
            flex-shrink: 0;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-primary);
            padding: 4px 8px 18px;
            border-bottom: 1px solid var(--card-border-subtle);
            margin-bottom: 16px;
        }
        .brand-badge {
            width: 38px; height: 38px;
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            background: #ffffff; border: 1px solid var(--card-border);
            overflow: hidden; flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(249, 115, 22, 0.08);
            transition: transform 0.2s ease;
        }
        .sidebar-brand:hover .brand-badge {
            transform: scale(1.04);
        }
        .brand-badge img { width: 100%; height: 100%; object-fit: contain; }
        .brand-title { font-size: 1.02rem; font-weight: 800; letter-spacing: -0.02em; }
        .brand-title span {
            color: var(--orange-primary);
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 4px;
            list-style: none;
        }
        .nav-section-label {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-light);
            font-weight: 800;
            padding: 14px 12px 6px;
        }
        .nav-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 9px 13px;
            border-radius: var(--radius-md);
            font-size: 0.86rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            border: none;
            background: transparent;
            cursor: pointer;
            text-align: left;
            transition: all 0.18s ease;
            position: relative;
        }
        .nav-btn:hover {
            color: var(--orange-primary);
            background: var(--orange-soft);
        }
        .nav-btn.active {
            color: var(--orange-primary);
            background: var(--orange-soft);
            font-weight: 700;
            box-shadow: inset 3px 0 0 var(--orange-primary);
        }
        .nav-btn svg {
            width: 18px; height: 18px; flex-shrink: 0;
            transition: transform 0.2s ease;
            stroke-width: 1.8;
        }
        .nav-btn:hover svg, .nav-btn.active svg {
            color: var(--orange-primary);
        }

        .sidebar-footer {
            border-top: 1px solid var(--card-border-subtle);
            padding-top: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .sidebar-user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .sidebar-user-card:hover {
            background: var(--orange-soft);
            border-color: var(--orange-border);
        }
        .avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: var(--primary-gradient);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.82rem; color: #fff;
            flex-shrink: 0;
            overflow: hidden;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 6px rgba(249, 115, 22, 0.2);
        }
        .avatar img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .user-name {
            font-size: 0.84rem; font-weight: 700; color: var(--text-primary);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px;
        }
        .user-role-badge {
            font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.04em;
        }

        .btn-sidebar-logout {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 8px 12px; border-radius: var(--radius-md);
            background: #fafaf9; border: 1px solid var(--card-border);
            color: var(--text-muted); font-size: 0.82rem; font-weight: 600;
            text-decoration: none; transition: all 0.18s ease;
        }
        .btn-sidebar-logout:hover {
            background: var(--danger-light);
            border-color: var(--danger-border);
            color: var(--danger);
        }

        /* ── Main Content Area ── */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .topbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--card-border);
            padding: 14px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
        }
        .topbar-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }
        .topbar-right {
            display: flex; align-items: center; gap: 10px;
        }
        .topbar-btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 7px 13px; border-radius: var(--radius-md);
            background: #ffffff; border: 1px solid var(--card-border);
            color: var(--text-secondary); font-size: 0.82rem; font-weight: 600;
            text-decoration: none; transition: all 0.2s ease;
            box-shadow: var(--shadow-xs);
        }
        .topbar-btn:hover {
            background: #fafaf9;
            border-color: #e7e5e4;
            color: var(--orange-primary);
            transform: translateY(-1px);
        }
        .topbar-btn.topbar-btn-primary {
            background: var(--orange-soft);
            border-color: var(--orange-border);
            color: var(--orange-primary);
            font-weight: 700;
        }
        .topbar-btn.topbar-btn-primary:hover {
            background: #ffedd5;
        }

        .main-container {
            max-width: 1140px;
            width: 100%;
            margin: 0 auto;
            padding: 28px 24px 72px;
        }

        /* ── Alerts ── */
        .alert {
            padding: 12px 16px; border-radius: var(--radius-md);
            margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
            font-size: 0.86rem; font-weight: 600;
            box-shadow: var(--shadow-xs);
        }
        .alert-success { background: var(--success-light); border: 1px solid var(--success-border); color: #047857; }
        .alert-error   { background: var(--danger-light); border: 1px solid var(--danger-border); color: #be123c; }

        /* ── Tab Views ── */
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
            animation: fadeIn 0.2s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Hero Banner with Atmospheric Church Background ── */
        .welcome-hero {
            background-color: #0b1120;
            background-image: linear-gradient(90deg, rgba(11, 17, 32, 0.96) 0%, rgba(11, 17, 32, 0.88) 45%, rgba(11, 17, 32, 0.45) 80%, rgba(11, 17, 32, 0.25) 100%), url('<?= site_url("assets/images/dashboard_hero_bg.jpg") ?>');
            background-size: cover;
            background-position: center right;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-xl);
            padding: 32px 34px;
            color: #ffffff;
            margin-bottom: 20px;
            box-shadow: 0 10px 28px -6px rgba(11, 17, 32, 0.35);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            min-height: 200px;
        }
        @media (max-width: 768px) {
            .welcome-hero {
                background-image: linear-gradient(180deg, rgba(11, 17, 32, 0.94) 0%, rgba(11, 17, 32, 0.85) 100%), url('<?= site_url("assets/images/dashboard_hero_bg.jpg") ?>');
                padding: 22px 20px;
            }
        }
        .welcome-hero-content {
            position: relative;
            z-index: 2;
            max-width: 620px;
        }
        .welcome-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 9999px;
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #fed7aa;
            margin-bottom: 10px;
            backdrop-filter: blur(6px);
        }
        .welcome-headline {
            font-size: clamp(1.4rem, 2.5vw, 1.85rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.25;
            margin-bottom: 8px;
            color: #ffffff;
        }
        .welcome-headline span {
            color: #fb923c;
        }
        .welcome-subtext {
            font-size: 0.88rem;
            color: #cbd5e1;
            line-height: 1.6;
            margin-bottom: 18px;
        }
        .welcome-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn-welcome-primary {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
            color: #ffffff;
            font-size: 0.84rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.18s ease;
            box-shadow: 0 4px 14px rgba(234, 88, 12, 0.35);
        }
        .btn-welcome-primary:hover {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(234, 88, 12, 0.45);
        }
        .btn-welcome-ghost {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 16px;
            border-radius: var(--radius-md);
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            font-size: 0.84rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.25);
            transition: all 0.18s ease;
            backdrop-filter: blur(6px);
        }
        .btn-welcome-ghost:hover {
            background: rgba(255, 255, 255, 0.22);
            border-color: rgba(255, 255, 255, 0.4);
            color: #ffffff;
            transform: translateY(-1px);
        }

        /* ── Date & Day Bar ── */
        .date-bar {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 12px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-xs);
        }
        .date-bar-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .date-icon {
            width: 34px; height: 34px;
            border-radius: 8px;
            background: var(--orange-soft);
            border: 1px solid var(--orange-border);
            display: flex; align-items: center; justify-content: center;
            color: var(--orange-primary);
            flex-shrink: 0;
        }
        .date-day { font-size: 0.95rem; font-weight: 800; color: var(--text-primary); }
        .date-full { font-size: 0.78rem; color: var(--text-muted); margin-top: 1px; }
        .date-greeting {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ── Member Overview Top Grid ── */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1.12fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
            align-items: stretch;
        }
        @media (max-width: 960px) { .dashboard-grid { grid-template-columns: 1fr; } }

        /* ── Minimal Digital Membership Pass (Light Blue) ── */
        .membership-card {
            background: linear-gradient(145deg, #eff6ff 0%, #dbeafe 55%, #e0f2fe 100%);
            border-radius: var(--radius-xl);
            padding: 24px 26px;
            color: #1e3a5f;
            display: flex; flex-direction: column;
            justify-content: space-between;
            min-height: 280px;
            box-shadow: 0 4px 20px -4px rgba(37, 99, 235, 0.14), 0 0 0 1px rgba(37, 99, 235, 0.08);
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(37, 99, 235, 0.14);
        }
        .membership-card::after {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 150px; height: 150px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(249, 115, 22, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }
        .membership-card::before {
            content: '';
            position: absolute;
            bottom: -40px; left: -40px;
            width: 130px; height: 130px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .card-top {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 18px;
            position: relative; z-index: 2;
        }
        .card-brand-title {
            font-size: 0.68rem; text-transform: uppercase;
            letter-spacing: 0.12em; color: #64748b; font-weight: 800;
        }
        .card-brand-sub {
            font-size: 1.05rem; font-weight: 800; color: #1e3a5f; margin-top: 2px;
            letter-spacing: -0.01em;
        }
        .status-pill {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; border-radius: 9999px;
            font-size: 0.7rem; font-weight: 700;
            backdrop-filter: blur(8px);
        }
        .status-pill.active { background: rgba(34, 197, 94, 0.14); color: #15803d; border: 1px solid rgba(34, 197, 94, 0.3); }
        .status-pill.inactive { background: rgba(239, 68, 68, 0.12); color: #b91c1c; border: 1px solid rgba(239, 68, 68, 0.25); }
        .status-dot-sm { width: 6px; height: 6px; border-radius: 50%; background: currentColor; box-shadow: 0 0 6px currentColor; }

        /* Card Header with Photo & QR Box */
        .card-profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            position: relative; z-index: 2;
        }
        .card-profile-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .card-avatar {
            width: 52px; height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.12) 0%, rgba(37, 99, 235, 0.05) 100%);
            border: 2px solid rgba(37, 99, 235, 0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; font-weight: 800; color: #1e3a5f;
            overflow: hidden; flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.12);
        }
        .card-avatar img {
            width: 100%; height: 100%; object-fit: cover;
        }

        /* Card QR Code Frame */
        .card-qr-box {
            background: #ffffff;
            padding: 5px;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            transition: all 0.2s ease;
        }
        .card-qr-box:hover {
            transform: translateY(-1px) scale(1.03);
        }
        .card-qr-box canvas {
            display: block;
            border-radius: 4px;
        }
        .card-qr-hint {
            font-size: 0.55rem;
            color: #0f172a;
            font-weight: 800;
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .card-id-row {
            background: rgba(37, 99, 235, 0.06);
            border: 1px solid rgba(37, 99, 235, 0.15);
            border-radius: var(--radius-md);
            padding: 8px 12px;
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 14px;
            position: relative; z-index: 2;
        }
        .card-id-label { font-size: 0.62rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.06em; font-weight: 700; }
        .card-id-value { font-family: 'SF Mono', Monaco, Consolas, monospace; font-size: 0.94rem; font-weight: 800; color: #ea580c; margin-top: 1px; }
        .btn-copy-id {
            background: rgba(37, 99, 235, 0.1);
            border: 1px solid rgba(37, 99, 235, 0.2); color: #1e40af;
            padding: 4px 9px; border-radius: var(--radius-sm);
            font-size: 0.72rem; font-weight: 700; cursor: pointer;
            display: inline-flex; align-items: center; gap: 4px;
            transition: all 0.15s ease;
        }
        .btn-copy-id:hover { background: rgba(37, 99, 235, 0.18); color: #1e3a5f; }

        .card-info-grid {
            display: grid; grid-template-columns: repeat(2, 1fr);
            gap: 10px; margin-bottom: 14px;
            position: relative; z-index: 2;
        }
        .card-info-label { font-size: 0.62rem; color: #64748b; text-transform: uppercase; font-weight: 700; letter-spacing: 0.04em; }
        .card-info-val { font-size: 0.82rem; font-weight: 700; color: #1e3a5f; margin-top: 1px; }

        .card-badges-row {
            display: flex; flex-wrap: wrap; gap: 6px;
            padding-top: 12px; border-top: 1px solid rgba(37, 99, 235, 0.12);
            position: relative; z-index: 2;
        }
        .card-tag {
            background: rgba(37, 99, 235, 0.08);
            color: #1e40af;
            padding: 3px 8px; border-radius: 6px;
            font-size: 0.7rem; font-weight: 700;
            display: inline-flex; align-items: center; gap: 5px;
            border: 1px solid rgba(37, 99, 235, 0.15);
        }
        .card-tag.ministry { background: rgba(249, 115, 22, 0.12); color: #c2410c; border-color: rgba(249, 115, 22, 0.25); }
        .card-tag.dgroup { background: rgba(37, 99, 235, 0.1); color: #1d4ed8; border-color: rgba(37, 99, 235, 0.2); }

        /* ── Minimalist Glass Panels ── */
        .glass-panel {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-xl);
            padding: 24px 26px;
            box-shadow: var(--shadow-xs);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .panel-title {
            font-size: 1.05rem; font-weight: 800; color: var(--text-primary);
            margin-bottom: 16px; letter-spacing: -0.01em;
            display: flex; align-items: center; gap: 8px;
        }

        /* ── Avatar Uploader ── */
        .avatar-upload-box {
            border: 2px dashed #fed7aa;
            border-radius: var(--radius-lg);
            padding: 20px;
            text-align: center;
            background: var(--orange-soft);
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
        }
        .avatar-upload-box:hover, .avatar-upload-box.drag-over {
            border-color: var(--orange-primary);
            background: #ffedd5;
            transform: scale(1.008);
        }
        .avatar-upload-preview {
            width: 72px; height: 72px;
            border-radius: 50%;
            margin: 0 auto 8px;
            background: #ffffff;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            border: 3px solid #ffffff;
            box-shadow: 0 4px 10px rgba(249, 115, 22, 0.15);
            transition: transform 0.2s ease;
        }
        .avatar-upload-box:hover .avatar-upload-preview {
            transform: scale(1.04);
        }
        .avatar-upload-preview img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .avatar-upload-text {
            font-size: 0.82rem; font-weight: 700; color: var(--text-primary); margin-bottom: 2px;
        }
        .avatar-upload-hint {
            font-size: 0.7rem; color: var(--text-muted);
        }
        .avatar-upload-box input[type="file"] {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            opacity: 0; cursor: pointer; z-index: 10;
        }

        .avatar-actions-row {
            display: flex; gap: 8px; margin-bottom: 16px; justify-content: center;
        }
        .btn-avatar-action {
            padding: 6px 12px; border-radius: var(--radius-md);
            font-size: 0.76rem; font-weight: 700; cursor: pointer;
            display: inline-flex; align-items: center; gap: 5px;
            border: 1px solid transparent; text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-avatar-change { background: var(--orange-soft); color: var(--orange-primary); border-color: var(--orange-border); }
        .btn-avatar-change:hover { background: #ffedd5; }
        .btn-avatar-remove { background: var(--danger-light); color: var(--danger); border-color: var(--danger-border); }
        .btn-avatar-remove:hover { background: #fee2e2; }

        /* ── Minimal Forms ── */
        .form-group { margin-bottom: 12px; }
        .form-label {
            display: block; font-size: 0.74rem; font-weight: 700;
            color: var(--text-secondary); margin-bottom: 4px;
            text-transform: uppercase; letter-spacing: 0.04em;
        }
        .form-input, .form-textarea, .form-select {
            width: 100%; padding: 9px 13px;
            border: 1px solid var(--card-border);
            border-radius: var(--radius-md);
            font-size: 0.86rem; color: var(--text-primary);
            background: #fafaf9;
            transition: all 0.18s ease;
        }
        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none; border-color: var(--orange-primary);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.12);
        }
        .form-textarea { min-height: 60px; resize: vertical; }
        .form-row { display: grid; grid-template-columns: 1.5fr 1fr; gap: 10px; }

        .btn-save {
            width: 100%; background: var(--primary-gradient);
            color: #ffffff; border: none;
            padding: 10px 16px; border-radius: var(--radius-md);
            font-size: 0.88rem; font-weight: 700; cursor: pointer;
            display: inline-flex; align-items: center; justify-content: center; gap: 7px;
            transition: all 0.18s ease;
            box-shadow: 0 2px 8px rgba(249, 115, 22, 0.25);
            margin-top: 4px;
        }
        .btn-save:hover {
            background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.35);
            transform: translateY(-1px);
        }

        /* ── Section Cards ── */
        .section-box {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-xl);
            padding: 26px 28px;
            box-shadow: var(--shadow-xs);
            margin-bottom: 20px;
        }
        .section-header-row {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 20px;
        }
        .section-title { font-size: 1.1rem; font-weight: 800; color: var(--text-primary); letter-spacing: -0.01em; }
        .section-desc { font-size: 0.82rem; color: var(--text-muted); margin-top: 2px; }

        /* ── Admin Stats ── */
        .stats-row {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 14px; margin-bottom: 20px;
        }
        @media (max-width: 650px) { .stats-row { grid-template-columns: 1fr; } }
        .stat-box {
            background: #ffffff; border: 1px solid var(--card-border);
            border-radius: var(--radius-lg); padding: 16px 18px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: var(--shadow-xs);
            transition: all 0.2s ease;
        }
        .stat-box:hover {
            transform: translateY(-2px);
            border-color: var(--orange-border);
            box-shadow: var(--shadow-sm);
        }
        .stat-title { font-size: 0.74rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.04em; }
        .stat-val { font-size: 1.4rem; font-weight: 800; color: var(--text-primary); margin-top: 3px; letter-spacing: -0.02em; }
        .stat-icon-wrapper {
            width: 40px; height: 40px; border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            background: var(--orange-soft); border: 1px solid var(--orange-border);
            color: var(--orange-primary);
        }

        /* ── Minimal Tables ── */
        .table-responsive { overflow-x: auto; border-radius: var(--radius-md); border: 1px solid var(--card-border); }
        .clean-table {
            width: 100%; border-collapse: collapse; text-align: left;
            font-size: 0.84rem;
        }
        .clean-table th {
            padding: 10px 14px; background: #fafaf9;
            border-bottom: 1px solid var(--card-border);
            color: var(--text-muted); font-weight: 700; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em;
        }
        .clean-table td {
            padding: 12px 14px; border-bottom: 1px solid #f5f5f4;
            color: var(--text-primary); vertical-align: middle;
        }
        .clean-table tr:last-child td { border-bottom: none; }
        .clean-table tr:hover td { background: #fafaf9; }

        .table-avatar {
            width: 30px; height: 30px; border-radius: 50%;
            background: var(--blue-soft); color: var(--blue-primary);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.74rem; font-weight: 700;
            overflow: hidden; flex-shrink: 0;
        }
        .table-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .btn-action-sm {
            padding: 5px 11px; border-radius: var(--radius-md);
            font-size: 0.76rem; font-weight: 700; cursor: pointer;
            border: 1px solid transparent; text-decoration: none;
            display: inline-flex; align-items: center; gap: 5px;
            transition: all 0.15s ease;
        }
        .btn-action-approve { background: var(--success-light); color: #047857; border-color: var(--success-border); }
        .btn-action-approve:hover { background: #d1fae5; }
        .btn-action-reject  { background: var(--danger-light); color: #be123c; border-color: var(--danger-border); }
        .btn-action-reject:hover { background: #ffe4e6; }
        .btn-action-toggle  { background: #fafaf9; color: var(--text-secondary); border-color: var(--card-border); }
        .btn-action-toggle:hover { background: #f5f5f4; border-color: #e7e5e4; }

        /* ── Cards Grid ── */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
            gap: 16px;
        }
        .item-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 20px;
            display: flex; flex-direction: column; justify-content: space-between;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-xs);
        }
        .item-card:hover {
            border-color: var(--orange-border);
            box-shadow: var(--shadow-sm);
            transform: translateY(-2px);
        }
        .item-card.enrolled {
            border-color: var(--blue-border); background: linear-gradient(180deg, #ffffff 0%, var(--blue-soft) 100%);
            box-shadow: 0 2px 10px rgba(59, 130, 246, 0.06);
        }
        .item-header {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 8px;
        }
        .item-title { font-size: 0.98rem; font-weight: 800; color: var(--text-primary); letter-spacing: -0.01em; }
        .item-meta { display: flex; flex-direction: column; gap: 5px; margin: 6px 0 10px; }
        .item-meta-row {
            display: flex; align-items: center; gap: 6px;
            font-size: 0.78rem; color: var(--text-secondary);
        }
        .item-desc { font-size: 0.8rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 14px; }

        .btn-card-primary {
            width: 100%; background: var(--primary-gradient);
            color: #ffffff; border: none;
            padding: 8px 12px; border-radius: var(--radius-md);
            font-size: 0.82rem; font-weight: 700; cursor: pointer;
            display: inline-flex; align-items: center; justify-content: center; gap: 5px;
            transition: all 0.18s ease;
            box-shadow: 0 2px 6px rgba(249, 115, 22, 0.2);
        }
        .btn-card-primary:hover {
            background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
            transform: translateY(-1px);
        }

        .btn-card-danger {
            width: 100%; background: var(--danger-light);
            color: var(--danger); border: 1px solid var(--danger-border);
            padding: 8px 12px; border-radius: var(--radius-md);
            font-size: 0.82rem; font-weight: 700; cursor: pointer;
            transition: all 0.15s ease;
        }
        .btn-card-danger:hover { background: #ffe4e6; }

        .tag-status {
            padding: 3px 8px; border-radius: 6px;
            font-size: 0.7rem; font-weight: 700;
        }
        .tag-status.pending  { background: var(--warning-light); color: #b45309; border: 1px solid var(--warning-border); }
        .tag-status.approved { background: var(--success-light); color: #047857; border: 1px solid var(--success-border); }
        .tag-status.rejected { background: var(--danger-light); color: #be123c; border: 1px solid var(--danger-border); }

        .user-app-badge {
            background: #ffffff; border: 1px solid var(--card-border);
            border-radius: var(--radius-md); padding: 10px 14px;
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 16px;
        }

        .role-tag {
            background: #fafaf9; color: var(--text-secondary);
            border: 1px solid var(--card-border);
            padding: 3px 7px; border-radius: 6px;
            font-size: 0.68rem; font-weight: 800; text-transform: uppercase;
        }
        .role-tag.admin { background: var(--orange-soft); color: var(--orange-primary); border-color: var(--orange-border); }

        /* QR Scanner Container */
        .scanner-container {
            border: 2px dashed #cbd5e1;
            border-radius: var(--radius-lg);
            overflow: hidden;
            background: #0f172a;
            margin-bottom: 16px;
            max-width: 480px;
            margin: 0 auto 16px;
            box-shadow: var(--shadow-md);
        }

        /* ── Floating Modal Overlay & Card ── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeInOverlay 0.2s ease;
        }
        @keyframes fadeInOverlay {
            from { opacity: 0; }
            to   { opacity: 1; }
        }
        .modal-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-xl);
            padding: 28px 30px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(249, 115, 22, 0.08);
            animation: slideUpModal 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        @keyframes slideUpModal {
            from { opacity: 0; transform: translateY(24px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--card-border);
        }
        .modal-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.01em;
        }
        .modal-close-btn {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: var(--card-border);
            border: none;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            color: var(--text-muted);
            transition: all 0.15s ease;
            line-height: 1;
        }
        .modal-close-btn:hover {
            background: #fee2e2;
            color: #dc2626;
        }
        .modal-footer {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--card-border);
        }
        .modal-footer .btn-cancel {
            flex: 1;
            padding: 10px 16px;
            border-radius: var(--radius-md);
            border: 1px solid var(--card-border);
            background: transparent;
            color: var(--text-secondary);
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .modal-footer .btn-cancel:hover {
            background: var(--card-border);
        }

        /* Mobile sidebar button defaults (hidden on desktop) */
        .mobile-sidebar-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            background: #ffffff;
            border: 1px solid var(--card-border);
            color: var(--text-primary);
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-xs);
            flex-shrink: 0;
        }

        .mobile-sidebar-toggle:hover {
            background: var(--orange-soft);
            color: var(--orange-primary);
            border-color: var(--orange-border);
        }

        .mobile-sidebar-close {
            display: none;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--card-border-subtle);
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.15s ease;
            flex-shrink: 0;
            margin-bottom: 16px;
        }

        .mobile-sidebar-close:hover {
            background: #fee2e2;
            color: #dc2626;
        }

        .mobile-sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 199;
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .mobile-sidebar-backdrop.active {
            display: block;
            opacity: 1;
        }

        [data-theme='dark'] .mobile-sidebar-toggle {
            background: var(--card-bg);
            border-color: var(--card-border);
            color: var(--text-primary);
        }

        [data-theme='dark'] .mobile-sidebar-close {
            background: #21262d;
            color: var(--text-muted);
        }

        /* ── Comprehensive Mobile Responsiveness ── */
        @media (max-width: 991px) {
            .mobile-sidebar-toggle { display: inline-flex; }
            .mobile-sidebar-close { display: inline-flex; }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                height: 100vh;
                height: 100dvh;
                z-index: 200;
                transform: translateX(-100%);
                transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1);
                box-shadow: 0 0 35px rgba(0, 0, 0, 0.25);
                overflow-y: auto;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .topbar {
                padding: 12px 18px;
            }

            .main-container {
                padding: 18px 16px 56px;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }
        }

        @media (max-width: 640px) {
            .topbar {
                padding: 10px 14px;
            }

            .topbar-title {
                font-size: 1rem;
            }

            .topbar-btn span {
                display: none; /* Icon-only on very compact mobile screens */
            }

            .topbar-btn {
                padding: 7px 9px;
            }

            .main-container {
                padding: 14px 12px 48px;
            }

            .welcome-hero {
                padding: 20px 16px;
                min-height: auto;
            }

            .welcome-headline {
                font-size: 1.3rem;
            }

            .welcome-actions {
                flex-direction: column;
                width: 100%;
            }

            .welcome-actions .btn-welcome-primary,
            .welcome-actions .btn-welcome-ghost {
                width: 100%;
                justify-content: center;
            }

            .date-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
                padding: 10px 14px;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }

            .cards-grid {
                grid-template-columns: 1fr;
            }

            .membership-card {
                padding: 18px 16px;
            }

            .card-info-grid {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .section-box {
                padding: 18px 16px;
            }

            .modal-card {
                padding: 20px 18px;
                max-width: 95vw;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }

        /* ── Paperwork & PDF Printing Support ── */
        @media print {
            body {
                background: #ffffff !important;
                background-image: none !important;
                color: #000000 !important;
                display: block !important;
            }
            .sidebar, .topbar, .hide-on-print, .btn-welcome-primary, .btn-welcome-ghost, .btn-avatar-action, form button, .stats-row, #volunteerSearchInput, #volunteerStatusFilter, select, input, .alert {
                display: none !important;
            }
            .main-wrapper {
                height: auto !important;
                overflow: visible !important;
            }
            .main-container {
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .section-box {
                border: 1px solid #d1d5db !important;
                box-shadow: none !important;
                padding: 16px !important;
                break-inside: avoid;
            }
            .clean-table {
                width: 100% !important;
                border-collapse: collapse !important;
            }
            .clean-table th, .clean-table td {
                border: 1px solid #cbd5e1 !important;
                padding: 8px 10px !important;
                color: #000000 !important;
            }
            .clean-table th {
                background: #f1f5f9 !important;
                font-weight: 700 !important;
            }
            .tag-status {
                border: 1px solid #94a3b8 !important;
                color: #000000 !important;
                background: #ffffff !important;
            }
        }
    </style>
</head>
<body>

    <?php
        $user   = $user   ?? [];
        $member = $member ?? [];

        $display_name   = $member['full_name'] ?? $user['username'] ?? 'Member';
        $avatar_initial = strtoupper(substr($display_name, 0, 1));
        $user_role      = $user['role'] ?? 'user';
        $member_active  = !empty($member['is_active']);
        $avatar_url     = !empty($member['avatar']) ? site_url($member['avatar']) : null;
        $member_id_code = $member['member_id'] ?? 'LG-PENDING';
    ?>

    <!-- Mobile Sidebar Backdrop -->
    <div class="mobile-sidebar-backdrop" id="mobileSidebarBackdrop" onclick="closeSidebarMenu()"></div>

    <!-- ── LEFT SIDEBAR NAVIGATION ── -->
    <aside class="sidebar" id="mainSidebar">
        <div>
            <!-- Brand -->
            <div style="display:flex; align-items:center; justify-content:space-between;">
                <a href="<?= site_url('') ?>" class="sidebar-brand" style="flex:1;">
                    <div class="brand-badge">
                        <img src="<?= site_url('assets/images/lifegen-logo.png') ?>" alt="Logo">
                    </div>
                    <div class="brand-title">Life <span>Generation</span></div>
                </a>
                <button type="button" class="mobile-sidebar-close" onclick="closeSidebarMenu()" aria-label="Close navigation menu">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <ul class="nav-menu">
                <li class="nav-section-label">Menu</li>
                <li>
                    <button type="button" class="nav-btn active" onclick="showTab('overview', this)">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </button>
                </li>
                <li>
                    <button type="button" class="nav-btn" onclick="showTab('profile', this)">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>My Profile</span>
                    </button>
                </li>
                <li>
                    <button type="button" class="nav-btn" onclick="showTab('events', this)">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Church Events</span>
                    </button>
                </li>
                <li>
                    <button type="button" class="nav-btn" onclick="showTab('dgroup', this)">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>D-Groups</span>
                    </button>
                </li>
                <li>
                    <button type="button" class="nav-btn" onclick="showTab('ministry', this)">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Ministries</span>
                    </button>
                </li>
                <li>
                    <a href="<?= site_url('prayer-wall') ?>" class="nav-btn">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span>Prayer Wall</span>
                    </a>
                </li>

                <?php if ($user_role === 'admin'): ?>
                    <li class="nav-section-label" style="margin-top:8px;">Admin Operations</li>
                    <li>
                        <button type="button" class="nav-btn" onclick="showTab('admin-attendance', this)">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            <span>QR Attendance & Check-in</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="nav-btn" onclick="showTab('admin-events', this)">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            <span>Manage Events</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="nav-btn" onclick="showTab('admin-dgroups', this)">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Manage D-Groups</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="nav-btn" onclick="showTab('admin-ministries', this)">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>Manage Ministries</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="nav-btn" onclick="showTab('admin-members', this)">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>Members Directory</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="nav-btn" onclick="showTab('admin-ministry-apps', this)">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            <span>Volunteer Requests</span>
                        </button>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <div class="sidebar-user-card" onclick="showTab('profile', document.querySelector('[onclick*=\'profile\']'))" title="View & edit profile">
                <div class="avatar">
                    <?php if ($avatar_url): ?>
                        <img src="<?= $avatar_url ?>" alt="Avatar">
                    <?php else: ?>
                        <?= $avatar_initial ?>
                    <?php endif; ?>
                </div>
                <div style="flex:1; min-width:0;">
                    <div class="user-name"><?= htmlspecialchars($display_name) ?></div>
                    <div class="user-role-badge"><?= htmlspecialchars($user_role) ?> · View Profile</div>
                </div>
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--text-muted); flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </div>
            <button type="button" onclick="openLogoutConfirm()" class="btn-sidebar-logout">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span>Sign Out</span>
            </button>
        </div>
    </aside>

    <!-- ── MAIN CONTENT ── -->
    <div class="main-wrapper">

        <!-- Top Header -->
        <header class="topbar">
            <div class="topbar-left" style="display:flex; align-items:center; gap:12px;">
                <button type="button" class="mobile-sidebar-toggle" id="mobileSidebarToggle" aria-label="Open navigation menu" onclick="toggleSidebarMenu()">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="topbar-title" id="pageTitle">Dashboard</h1>
            </div>
            <div class="topbar-right">
                <a href="<?= site_url('') ?>" class="topbar-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Website Home</span>
                </a>
                <button type="button" class="topbar-btn" onclick="showTab('profile', document.querySelector('[onclick*=\'profile\']'))" style="background:#eff6ff; color:var(--primary); border-color:#bfdbfe; font-weight:700;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>My Profile</span>
                </button>
                <button type="button" class="topbar-theme-toggle" id="themeToggleBtn" onclick="toggleTheme()" aria-label="Toggle Dark/Light Mode" title="Toggle Dark/Light Mode">
                    <svg class="theme-icon-sun" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <svg class="theme-icon-moon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </button>
            </div>
        </header>

        <main class="main-container">

            <!-- Alerts -->
            <?php if (!empty($success)): ?>
                <div class="alert alert-success" role="alert">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    <span><?= $success ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error" role="alert">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <!-- ── TAB 1: OVERVIEW ── -->
            <div id="tab-overview" class="tab-content active">
                
                <!-- 1. Life Generation Member Welcome Hero -->
                <div class="welcome-hero">
                    <div class="welcome-hero-content">
                        <div class="welcome-tag">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            <span>Official Church Member</span>
                        </div>
                        <h2 class="welcome-headline">Welcome home, <span><?= htmlspecialchars($display_name) ?>!</span></h2>
                        <p class="welcome-subtext">
                            You are an essential part of the <strong>Life Generation Community</strong>. Connect with your Discipleship Group, serve in church ministries, RSVP for Sunday celebrations, and grow in your walk with Christ.
                        </p>
                        <div class="welcome-actions">
                            <button type="button" class="btn-welcome-primary" onclick="showTab('events', document.querySelector('[onclick*=\'events\']'))">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Browse Upcoming Events</span>
                            </button>
                            <a href="<?= site_url('prayer-wall') ?>" class="btn-welcome-ghost">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                <span>Post Prayer Request</span>
                            </a>
                            <button type="button" class="btn-welcome-ghost" onclick="showTab('profile', document.querySelector('[onclick*=\'profile\']'))">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>Edit Profile</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 2. Date & Day Bar -->
                <div class="date-bar">
                    <div class="date-bar-left">
                        <div class="date-icon">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <div class="date-day"><?= date('l') ?></div>
                            <div class="date-full"><?= date('F j, Y') ?></div>
                        </div>
                    </div>
                    <div class="date-greeting" style="display:flex; align-items:center; gap:5px;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--orange-primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <span>Have a blessed day!</span>
                    </div>
                </div>

                <!-- 3. Main Dashboard Grid: Digital ID Card + Member Journey Hub -->
                <div class="dashboard-grid">
                    <!-- Digital Member Card with QR Check-in Code -->
                    <div>
                        <div class="membership-card">
                            <div>
                                <div class="card-top">
                                    <div>
                                        <div class="card-brand-title">Life Generation</div>
                                        <div class="card-brand-sub">Official Member Card</div>
                                    </div>
                                    <span class="status-pill <?= $member_active ? 'active' : 'inactive' ?>">
                                        <span class="status-dot-sm"></span>
                                        <span><?= $member_active ? 'Active Member' : 'Inactive' ?></span>
                                    </span>
                                </div>

                                <!-- Card Profile Header with QR Frame -->
                                <div class="card-profile-header">
                                    <div class="card-profile-left">
                                        <div class="card-avatar">
                                            <?php if ($avatar_url): ?>
                                                <img src="<?= $avatar_url ?>" alt="Avatar">
                                            <?php else: ?>
                                                <?= $avatar_initial ?>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div style="font-size:1.1rem; font-weight:800; color:#1e3a5f; letter-spacing:-0.01em;"><?= htmlspecialchars($member['full_name'] ?? '—') ?></div>
                                            <div style="font-size:0.78rem; color:#64748b; font-weight:600;">@<?= htmlspecialchars($user['username'] ?? '') ?></div>
                                        </div>
                                    </div>

                                    <!-- QR Code Canvas for Attendance -->
                                    <div class="card-qr-box" title="Scan this QR code at church events to check in!">
                                        <canvas id="memberQrCanvas" width="58" height="58"></canvas>
                                        <span class="card-qr-hint">Event Pass</span>
                                    </div>
                                </div>

                                <!-- ID Box -->
                                <div class="card-id-row">
                                    <div>
                                        <div class="card-id-label">Member ID Code</div>
                                        <div class="card-id-value" id="memberIdText"><?= htmlspecialchars($member_id_code) ?></div>
                                    </div>
                                    <button type="button" class="btn-copy-id" onclick="copyMemberId()" id="copyBtn">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        <span>Copy</span>
                                    </button>
                                </div>

                                <!-- Info Grid -->
                                <div class="card-info-grid">
                                    <div>
                                        <div class="card-info-label">Email Address</div>
                                        <div class="card-info-val"><?= htmlspecialchars($member['email'] ?? $user['email'] ?? '—') ?></div>
                                    </div>
                                    <div>
                                        <div class="card-info-label">Age</div>
                                        <div class="card-info-val"><?= !empty($member['age']) ? htmlspecialchars($member['age']) . ' yrs old' : '—' ?></div>
                                    </div>
                                    <div>
                                        <div class="card-info-label">Ministry Assignment</div>
                                        <div class="card-info-val" style="color: <?= !empty($current_ministry) ? '#1d4ed8' : '#94a3b8' ?>;">
                                            <?= !empty($current_ministry) ? htmlspecialchars($current_ministry['name']) : 'None' ?>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="card-info-label">Discipleship Group</div>
                                        <div class="card-info-val" style="color: <?= !empty($current_dgroup) ? '#7c3aed' : '#94a3b8' ?>;">
                                            <?= !empty($current_dgroup) ? htmlspecialchars($current_dgroup['name']) : 'None' ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Enrolled Badges in Card -->
                            <div class="card-badges-row">
                                <?php if (!empty($current_ministry)): ?>
                                    <span class="card-tag ministry">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        <span>Ministry: <?= htmlspecialchars($current_ministry['name']) ?></span>
                                    </span>
                                <?php endif; ?>

                                <?php if (!empty($current_dgroup)): ?>
                                    <span class="card-tag dgroup">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <span>D-Group: <?= htmlspecialchars($current_dgroup['name']) ?></span>
                                    </span>
                                <?php endif; ?>

                                <?php if (empty($current_ministry) && empty($current_dgroup)): ?>
                                    <span class="card-tag" style="color:#94a3b8;">
                                        <span>Not enrolled in any group yet</span>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Member Journey & Church Engagement Hub -->
                    <div class="glass-panel" style="display:flex; flex-direction:column; justify-content:space-between;">
                        <div>
                            <div class="panel-title" style="margin-bottom:12px;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>My LifeGen Journey</span>
                            </div>
                            <p style="font-size:0.84rem; color:var(--text-muted); margin-bottom:18px; line-height:1.5;">
                                Stay active in spiritual growth, discipleship fellowship, and community service.
                            </p>

                            <!-- Status Checklist / Cards -->
                            <div style="display:flex; flex-direction:column; gap:12px;">
                                <!-- D-Group Status -->
                                <div style="background:#ffffff; border:1px solid var(--card-border); border-radius:var(--radius-md); padding:12px 14px; display:flex; align-items:center; justify-content:space-between;">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <div style="width:34px; height:34px; border-radius:8px; background:var(--blue-soft); border:1px solid var(--blue-border); color:var(--blue-primary); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        </div>
                                        <div>
                                            <div style="font-size:0.84rem; font-weight:700; color:var(--text-primary);">Discipleship Group (D-Group)</div>
                                            <div style="font-size:0.75rem; color:var(--text-muted);">
                                                <?= !empty($current_dgroup) ? 'Enrolled in <strong>' . htmlspecialchars($current_dgroup['name']) . '</strong>' : 'Not joined yet' ?>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" onclick="showTab('dgroup', document.querySelector('[onclick*=\'dgroup\']'))" class="btn-action-sm <?= !empty($current_dgroup) ? 'btn-action-toggle' : 'btn-action-approve' ?>">
                                        <?= !empty($current_dgroup) ? 'View Group' : 'Join a Group →' ?>
                                    </button>
                                </div>

                                <!-- Ministry Status -->
                                <div style="background:#ffffff; border:1px solid var(--card-border); border-radius:var(--radius-md); padding:12px 14px; display:flex; align-items:center; justify-content:space-between;">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <div style="width:34px; height:34px; border-radius:8px; background:var(--orange-soft); border:1px solid var(--orange-border); color:var(--orange-primary); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        </div>
                                        <div>
                                            <div style="font-size:0.84rem; font-weight:700; color:var(--text-primary);">Ministry Volunteering</div>
                                            <div style="font-size:0.75rem; color:var(--text-muted);">
                                                <?= !empty($current_ministry) ? 'Serving in <strong>' . htmlspecialchars($current_ministry['name']) . '</strong>' : 'Explore volunteer teams' ?>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" onclick="showTab('ministry', document.querySelector('[onclick*=\'ministry\']'))" class="btn-action-sm <?= !empty($current_ministry) ? 'btn-action-toggle' : 'btn-action-approve' ?>">
                                        <?= !empty($current_ministry) ? 'View Ministry' : 'Volunteer →' ?>
                                    </button>
                                </div>

                                <!-- Attendance Pass Help -->
                                <div style="background:var(--blue-soft); border:1px solid var(--blue-border); border-radius:var(--radius-md); padding:12px 14px; display:flex; align-items:center; justify-content:space-between;">
                                    <div style="display:flex; align-items:center; gap:12px;">
                                        <div style="width:34px; height:34px; border-radius:8px; background:#ffffff; border:1px solid var(--blue-border); color:var(--blue-primary); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                        </div>
                                        <div>
                                            <div style="font-size:0.84rem; font-weight:700; color:var(--blue-hover);">QR Check-in Pass Ready</div>
                                            <div style="font-size:0.75rem; color:var(--blue-primary);">Present your card QR at the church entrance</div>
                                        </div>
                                    </div>
                                    <button type="button" onclick="copyMemberId()" class="btn-action-sm btn-action-toggle" style="background:#ffffff; color:var(--blue-hover); border-color:var(--blue-border);">
                                        Copy ID
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Profile Link -->
                        <div style="margin-top:18px; padding-top:14px; border-top:1px solid var(--card-border-subtle); display:flex; align-items:center; justify-content:space-between;">
                            <span style="font-size:0.78rem; color:var(--text-muted);">Need to change email, address, or avatar?</span>
                            <button type="button" onclick="showTab('profile', document.querySelector('[onclick*=\'profile\']'))" style="background:none; border:none; color:var(--orange-primary); font-weight:700; font-size:0.82rem; cursor:pointer; text-decoration:underline;">
                                Go to My Profile →
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 4. Quick Community Shortcuts Row -->
                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap:16px;">
                    <div class="item-card" onclick="document.querySelector('[onclick*=\'events\']').click()" style="cursor:pointer;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div class="stat-icon-wrapper" style="color:var(--blue-primary); background:var(--blue-soft); border-color:var(--blue-border);">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="item-title">Church Events</div>
                                <div style="font-size:0.78rem; color:var(--text-muted); margin-top:2px;"><?= count($upcoming_events ?? []) ?> upcoming services & events →</div>
                            </div>
                        </div>
                    </div>

                    <div class="item-card" onclick="document.querySelector('[onclick*=\'dgroup\']').click()" style="cursor:pointer;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div class="stat-icon-wrapper" style="color:var(--blue-hover); background:var(--blue-soft); border-color:var(--blue-border);">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div>
                                <div class="item-title">Discipleship Groups</div>
                                <div style="font-size:0.78rem; color:var(--text-muted); margin-top:2px;"><?= !empty($current_dgroup) ? 'Enrolled: ' . htmlspecialchars($current_dgroup['name']) : 'Browse available groups →' ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="item-card" onclick="document.querySelector('[onclick*=\'ministry\']').click()" style="cursor:pointer;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div class="stat-icon-wrapper" style="color:var(--orange-primary); background:var(--orange-soft); border-color:var(--orange-border);">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <div>
                                <div class="item-title">Ministry Volunteers</div>
                                <div style="font-size:0.78rem; color:var(--text-muted); margin-top:2px;"><?= !empty($current_ministry) ? 'Serving: ' . htmlspecialchars($current_ministry['name']) : 'Find a ministry team →' ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── TAB: DEDICATED PROFILE VIEW ── -->
            <div id="tab-profile" class="tab-content">
                <div class="section-box">
                    <div class="section-header-row">
                        <div>
                            <h2 class="section-title">My Member Profile & Settings</h2>
                            <p class="section-desc">Manage your personal information, contact details, profile photo, and membership credentials</p>
                        </div>
                    </div>

                    <div class="dashboard-grid" style="margin-bottom:0; align-items:start;">
                        <!-- Left Side: Profile Photo Upload & Membership Card Info -->
                        <div style="display:flex; flex-direction:column; gap:20px;">
                            <!-- Profile Photo Card with Drag & Drop Uploader -->
                            <div class="glass-panel" style="text-align:center;">
                                <div class="panel-title" style="justify-content:center; margin-bottom:16px;">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Profile Photo</span>
                                </div>

                                <!-- Drag and Drop Avatar Form -->
                                <form action="<?= site_url('dashboard/upload-avatar') ?>" method="POST" enctype="multipart/form-data" id="avatarForm">
                                    <div class="avatar-upload-box" id="avatarDropZone" title="Click or drag an image here to update photo">
                                        <div class="avatar-upload-preview" id="avatarPreview">
                                            <?php if ($avatar_url): ?>
                                                <img src="<?= $avatar_url ?>" alt="Avatar" id="previewImg">
                                            <?php else: ?>
                                                <span id="previewInitial" style="font-weight:800; color:var(--primary); font-size:1.5rem;"><?= $avatar_initial ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="avatar-upload-text">
                                            <strong>Click or drag photo here</strong> to <?= $avatar_url ? 'change' : 'upload' ?>
                                        </div>
                                        <div class="avatar-upload-hint">Supports PNG, JPG, JPEG, WEBP up to 5MB</div>
                                        <input type="file" name="avatar" id="avatarInput" accept="image/png, image/jpeg, image/webp, image/gif" onchange="submitAvatarForm()">
                                    </div>
                                </form>

                                <!-- Avatar Actions (Choose / Remove) -->
                                <?php if ($avatar_url): ?>
                                    <div class="avatar-actions-row" style="margin-bottom:0;">
                                        <label for="avatarInput" class="btn-avatar-action btn-avatar-change" style="cursor:pointer;">
                                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            <span>Choose New Photo</span>
                                        </label>
                                        <form action="<?= site_url('dashboard/remove-avatar') ?>" method="POST" onsubmit="return confirm('Remove your profile photo and reset to default?')" style="margin:0;">
                                            <button type="submit" class="btn-avatar-action btn-avatar-remove">
                                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span>Remove</span>
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Account Details Box -->
                            <div class="glass-panel" style="padding:22px 24px;">
                                <div style="display:flex; justify-content:space-between; margin-bottom:12px; font-size:0.86rem;">
                                    <span style="color:var(--text-muted); font-weight:600;">Member ID:</span>
                                    <strong style="font-family:monospace; color:var(--primary); font-size:0.95rem;"><?= htmlspecialchars($member_id_code) ?></strong>
                                </div>
                                <div style="display:flex; justify-content:space-between; margin-bottom:12px; font-size:0.86rem;">
                                    <span style="color:var(--text-muted); font-weight:600;">Username:</span>
                                    <span style="color:var(--text-primary); font-weight:700;">@<?= htmlspecialchars($user['username'] ?? '') ?></span>
                                </div>
                                <div style="display:flex; justify-content:space-between; margin-bottom:12px; font-size:0.86rem;">
                                    <span style="color:var(--text-muted); font-weight:600;">Role:</span>
                                    <span class="role-tag <?= $user_role === 'admin' ? 'admin' : '' ?>"><?= htmlspecialchars($user_role) ?></span>
                                </div>
                                <div style="display:flex; justify-content:space-between; font-size:0.86rem;">
                                    <span style="color:var(--text-muted); font-weight:600;">Status:</span>
                                    <span style="color:var(--success); font-weight:700;">Active & Verified</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side: Edit Personal Information Form -->
                        <div class="glass-panel">
                            <div class="panel-title">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--primary);"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                <span>Edit Personal Details</span>
                            </div>

                            <form action="<?= site_url('dashboard/update-profile') ?>" method="POST">
                                <div class="form-group">
                                    <label class="form-label" for="profile_full_name">Full Name</label>
                                    <input type="text" name="full_name" id="profile_full_name" class="form-input"
                                           value="<?= htmlspecialchars($member['full_name'] ?? '') ?>" required>
                                </div>

                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="profile_email">Email Address</label>
                                        <input type="email" name="email" id="profile_email" class="form-input"
                                               value="<?= htmlspecialchars($member['email'] ?? $user['email'] ?? '') ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="profile_age">Age</label>
                                        <input type="number" name="age" id="profile_age" class="form-input"
                                               min="1" max="125"
                                               value="<?= htmlspecialchars($member['age'] ?? '') ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="profile_address">Residential Address</label>
                                    <textarea name="address" id="profile_address" class="form-textarea" style="min-height:90px;" required><?= htmlspecialchars($member['address'] ?? '') ?></textarea>
                                </div>

                                <button type="submit" class="btn-save" style="margin-top:8px;">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>Update Member Profile</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── TAB 2: CHURCH EVENTS & RSVP ── -->
            <div id="tab-events" class="tab-content">
                <div class="section-box">
                    <div class="section-header-row">
                        <div>
                            <h2 class="section-title">Upcoming Services & Events</h2>
                            <p class="section-desc">RSVP for upcoming Sunday celebrations, youth gatherings, and church conferences</p>
                        </div>
                    </div>

                    <?php if (!empty($upcoming_events)): ?>
                        <div class="cards-grid">
                            <?php foreach ($upcoming_events as $ev): ?>
                                <?php
                                    $has_rsvpd = isset($user_rsvps[$ev['id']]) && $user_rsvps[$ev['id']]['rsvp_status'] === 'attending';
                                    $is_checked_in = isset($user_rsvps[$ev['id']]) && !empty($user_rsvps[$ev['id']]['checked_in']);
                                ?>
                                <div class="item-card <?= $has_rsvpd ? 'enrolled' : '' ?>">
                                    <div>
                                        <?php if (!empty($ev['banner_image'])): ?>
                                            <div style="margin:-18px -18px 14px -18px; max-height:140px; overflow:hidden; border-top-left-radius:var(--radius-md); border-top-right-radius:var(--radius-md);">
                                                <img src="<?= site_url($ev['banner_image']) ?>" alt="Event Flyer" style="width:100%; height:140px; object-fit:cover; display:block;">
                                            </div>
                                        <?php endif; ?>

                                        <div class="item-header">
                                            <div class="item-title"><?= htmlspecialchars($ev['title']) ?></div>
                                            <?php if ($is_checked_in): ?>
                                                <span class="tag-status approved" style="display:inline-flex; align-items:center; gap:4px;">
                                                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                    <span>Checked In</span>
                                                </span>
                                            <?php elseif ($has_rsvpd): ?>
                                                <span class="tag-status approved">RSVP Confirmed</span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="item-meta">
                                            <div class="item-meta-row">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                <span><strong><?= date('M j, Y (D)', strtotime($ev['event_date'])) ?></strong> · <?= htmlspecialchars($ev['event_time']) ?></span>
                                            </div>
                                            <div class="item-meta-row">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                <span><?= htmlspecialchars($ev['location']) ?></span>
                                            </div>
                                            <div class="item-meta-row">
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                <span><?= (int)$ev['rsvp_count'] ?> registered <?= !empty($ev['capacity']) ? '/ ' . (int)$ev['capacity'] . ' max' : '' ?></span>
                                            </div>
                                        </div>

                                        <?php if (!empty($ev['description'])): ?>
                                            <p class="item-desc"><?= htmlspecialchars($ev['description']) ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($has_rsvpd): ?>
                                        <form action="<?= site_url('events/cancel-rsvp') ?>" method="POST" style="margin-top:auto;">
                                            <input type="hidden" name="event_id" value="<?= (int)$ev['id'] ?>">
                                            <button type="submit" class="btn-card-danger" onclick="return confirm('Cancel your RSVP for this event?')">Cancel RSVP</button>
                                        </form>
                                    <?php else: ?>
                                        <form action="<?= site_url('events/rsvp') ?>" method="POST" style="margin-top:auto;">
                                            <input type="hidden" name="event_id" value="<?= (int)$ev['id'] ?>">
                                            <button type="submit" class="btn-card-primary">
                                                <span>RSVP / I'm Attending</span>
                                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="text-align:center; padding:32px; color:var(--text-muted);">No upcoming church events scheduled at this moment.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ── TAB 3: D-GROUPS ── -->
            <div id="tab-dgroup" class="tab-content">
                <div class="section-box">
                    <div class="section-header-row">
                        <div>
                            <h2 class="section-title">Discipleship Group (D-Group)</h2>
                            <p class="section-desc">Connect for fellowship, mentorship, and spiritual growth</p>
                        </div>
                    </div>

                    <?php if (!empty($current_dgroup)): ?>
                        <!-- Enrolled Card -->
                        <div class="item-card enrolled" style="max-width: 600px;">
                            <div>
                                <?php if (!empty($current_dgroup['image'])): ?>
                                    <div style="margin:-20px -20px 16px -20px; max-height:170px; overflow:hidden; border-top-left-radius:var(--radius-md); border-top-right-radius:var(--radius-md);">
                                        <img src="<?= site_url($current_dgroup['image']) ?>" alt="D-Group Photo" style="width:100%; height:170px; object-fit:cover; display:block;">
                                    </div>
                                <?php endif; ?>

                                <div class="item-header">
                                    <div>
                                        <span class="tag-status approved" style="margin-bottom:6px; display:inline-block;">Enrolled</span>
                                        <div class="item-title" style="font-size:1.1rem;"><?= htmlspecialchars($current_dgroup['name']) ?></div>
                                    </div>
                                </div>

                                <div class="item-meta">
                                    <div class="item-meta-row">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span>Leader: <strong><?= htmlspecialchars($current_dgroup['leader_name']) ?></strong></span>
                                    </div>
                                    <div class="item-meta-row">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span><?= htmlspecialchars($current_dgroup['schedule']) ?></span>
                                    </div>
                                    <div class="item-meta-row">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span><?= htmlspecialchars($current_dgroup['location']) ?></span>
                                    </div>
                                </div>

                                <?php if (!empty($current_dgroup['description'])): ?>
                                    <p class="item-desc"><?= htmlspecialchars($current_dgroup['description']) ?></p>
                                <?php endif; ?>
                            </div>

                            <form action="<?= site_url('dgroups/leave') ?>" method="POST" style="margin-top:12px;">
                                <button type="submit" class="btn-card-danger" onclick="return confirm('Leave this D-Group?')">Leave Group</button>
                            </form>
                        </div>
                    <?php else: ?>
                        <!-- Directory Grid -->
                        <?php if (!empty($all_dgroups)): ?>
                            <div class="cards-grid">
                                <?php foreach ($all_dgroups as $dg): ?>
                                    <div class="item-card">
                                        <div>
                                            <?php if (!empty($dg['image'])): ?>
                                                <div style="margin:-20px -20px 14px -20px; max-height:150px; overflow:hidden; border-top-left-radius:var(--radius-md); border-top-right-radius:var(--radius-md);">
                                                    <img src="<?= site_url($dg['image']) ?>" alt="D-Group Photo" style="width:100%; height:150px; object-fit:cover; display:block;">
                                                </div>
                                            <?php endif; ?>

                                            <div class="item-header">
                                                <div class="item-title"><?= htmlspecialchars($dg['name']) ?></div>
                                                <span class="tag-status" style="background:#f1f5f9; color:var(--text-secondary);">
                                                    <?= (int)$dg['member_count'] ?> member<?= $dg['member_count'] !== 1 ? 's' : '' ?>
                                                </span>
                                            </div>

                                            <div class="item-meta">
                                                <div class="item-meta-row">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                    <span><?= htmlspecialchars($dg['leader_name']) ?></span>
                                                </div>
                                                <div class="item-meta-row">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    <span><?= htmlspecialchars($dg['schedule']) ?></span>
                                                </div>
                                                <div class="item-meta-row">
                                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    <span><?= htmlspecialchars($dg['location']) ?></span>
                                                </div>
                                            </div>

                                            <?php if (!empty($dg['description'])): ?>
                                                <p class="item-desc"><?= htmlspecialchars($dg['description']) ?></p>
                                            <?php endif; ?>
                                        </div>

                                        <form action="<?= site_url('dgroups/join') ?>" method="POST" style="margin-top:auto;">
                                            <input type="hidden" name="dgroup_id" value="<?= (int)$dg['id'] ?>">
                                            <button type="submit" class="btn-card-primary">
                                                <span>Join Group</span>
                                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div style="text-align:center; padding:32px; color:var(--text-muted);">No D-Groups available at this time.</div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ── TAB 4: MINISTRIES ── -->
            <div id="tab-ministry" class="tab-content">
                <div class="section-box">
                    <div class="section-header-row">
                        <div>
                            <h2 class="section-title">Ministry Volunteer Sign-up</h2>
                            <p class="section-desc">Apply to serve in active church ministry teams</p>
                        </div>
                    </div>

                    <!-- Active Ministry Involvement -->
                    <?php if (!empty($current_ministry)): ?>
                        <div class="user-app-badge" style="background:#f0fdf4; border-color:#bbf7d0;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <svg width="16" height="16" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span style="font-size:0.85rem; font-weight:600; color:#15803d;">
                                    You are currently serving in the <strong><?= htmlspecialchars($current_ministry['name']) ?></strong>.
                                </span>
                            </div>
                            <span class="tag-status approved">Approved Member</span>
                        </div>
                    <?php endif; ?>

                    <!-- Available Ministries Grid -->
                    <?php if (!empty($ministries)): ?>
                        <div class="cards-grid">
                            <?php foreach ($ministries as $min): ?>
                                <div class="item-card <?= ((int)($member['ministry_id'] ?? 0) === (int)$min['id']) ? 'enrolled' : '' ?>">
                                    <div>
                                        <?php if (!empty($min['image'])): ?>
                                            <div style="margin:-18px -18px 14px -18px; max-height:130px; overflow:hidden; border-top-left-radius:var(--radius-md); border-top-right-radius:var(--radius-md);">
                                                <img src="<?= site_url($min['image']) ?>" alt="Ministry" style="width:100%; height:130px; object-fit:cover; display:block;">
                                            </div>
                                        <?php endif; ?>

                                        <div class="item-header">
                                            <div class="item-title">
                                                <span style="margin-right:4px;"><?= htmlspecialchars($min['icon'] ?? '✦') ?></span>
                                                <?= htmlspecialchars($min['name']) ?>
                                            </div>
                                            <?php if ($min['has_applied']): ?>
                                                <span class="tag-status <?= $min['application_status'] ?>">
                                                    <?= ucfirst($min['application_status']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (!empty($min['description'])): ?>
                                            <p class="item-desc"><?= htmlspecialchars($min['description']) ?></p>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($min['has_applied']): ?>
                                        <?php if ($min['application_status'] === 'pending'): ?>
                                            <form action="<?= site_url('ministry/cancel') ?>" method="POST" style="margin-top:auto;">
                                                <input type="hidden" name="ministry_id" value="<?= (int)$min['id'] ?>">
                                                <button type="submit" class="btn-card-danger" onclick="return confirm('Withdraw application?')">Withdraw Application</button>
                                            </form>
                                        <?php elseif ($min['application_status'] === 'approved'): ?>
                                            <div style="font-size:0.78rem; font-weight:600; color:var(--success); margin-top:auto;">
                                                Active Ministry Team
                                            </div>
                                        <?php else: ?>
                                            <div style="font-size:0.78rem; color:var(--text-muted); margin-top:auto;">
                                                Application Reviewed
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <form action="<?= site_url('ministry/apply') ?>" method="POST" style="margin-top:auto;">
                                            <input type="hidden" name="ministry_id" value="<?= (int)$min['id'] ?>">
                                            <button type="submit" class="btn-card-primary">
                                                <span>Volunteer</span>
                                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div style="text-align:center; padding:32px; color:var(--text-muted);">No active ministry openings.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- ── TAB 5: ADMIN QR ATTENDANCE CHECK-IN (ADMIN ONLY) ── -->
            <?php if ($user_role === 'admin'): ?>
                <div id="tab-admin-attendance" class="tab-content">
                    <section class="section-box">
                        <div class="section-header-row">
                            <div>
                                <h2 class="section-title">QR Code Event Check-in Station</h2>
                                <p class="section-desc">Scan a member's digital ID QR code from their phone or type their Member ID</p>
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px;">
                            <!-- Scanner & Manual Entry Form -->
                            <div>
                                <form action="<?= site_url('admin/events/check-in') ?>" method="POST" id="checkInForm">
                                    <div class="form-group">
                                        <label class="form-label" for="checkin_event_id">Select Event for Attendance</label>
                                        <select name="event_id" id="checkin_event_id" class="form-select" required>
                                            <?php foreach ($all_events_admin as $e): ?>
                                                <option value="<?= (int)$e['id'] ?>">
                                                    <?= htmlspecialchars($e['title']) ?> (<?= date('M j, Y', strtotime($e['event_date'])) ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Camera QR Scanner -->
                                    <div style="margin: 16px 0;">
                                        <button type="button" class="btn-save" id="btnToggleScanner" style="background:#0f172a; margin-bottom:10px;" onclick="toggleQrScanner()">
                                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <span id="scannerBtnText">Open Camera QR Scanner</span>
                                        </button>
                                        <div id="qrReader" class="scanner-container" style="display:none;"></div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="manual_member_id">Member Identification Code (or Scanned Result)</label>
                                        <input type="text" name="member_id" id="manual_member_id" class="form-input" placeholder="e.g. LG-2026-DEM01" required style="font-family:monospace; font-size:1rem; font-weight:700; color:var(--primary);">
                                    </div>

                                    <button type="submit" class="btn-save">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Confirm Event Check-in</span>
                                    </button>
                                </form>
                            </div>

                            <!-- Live Attendance Instructions & Stats -->
                            <div style="background:#f8fafc; border:1px solid var(--card-border); border-radius:var(--radius-md); padding:18px;">
                                <h3 style="font-size:0.9rem; font-weight:700; margin-bottom:10px;">How It Works:</h3>
                                <ul style="font-size:0.8rem; color:var(--text-secondary); line-height:1.6; padding-left:18px; margin-bottom:16px;">
                                    <li>Members display the <strong>QR Event Pass</strong> located on their Digital Member Card.</li>
                                    <li>Click <strong>Open Camera QR Scanner</strong> to instantly read the member's code from any smartphone or webcam.</li>
                                    <li>Attendance is immediately logged into church analytics with a precise timestamp.</li>
                                </ul>

                                <div style="border-top:1px solid var(--card-border); padding-top:14px;">
                                    <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Recent Events Check-in Overview</div>
                                    <div style="margin-top:8px; display:flex; flex-direction:column; gap:8px;">
                                        <?php foreach ($all_events_admin as $ea): ?>
                                            <div style="display:flex; justify-content:space-between; font-size:0.78rem; background:#ffffff; padding:8px 12px; border-radius:6px; border:1px solid var(--card-border);">
                                                <span style="font-weight:600;"><?= htmlspecialchars($ea['title']) ?></span>
                                                <span style="color:var(--success); font-weight:700;"><?= (int)$ea['checked_in_count'] ?> checked in</span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- ── TAB 6: ADMIN MANAGE EVENTS (CREATE/DELETE) ── -->
                <div id="tab-admin-events" class="tab-content">
                    <div style="display:grid; grid-template-columns: 1fr 1.6fr; gap:24px;">
                        <!-- Create Event Form with Visual Invitation Banner Upload -->
                        <div class="section-box">
                            <h2 class="section-title" style="margin-bottom:14px;">Create Church Event</h2>
                            <form action="<?= site_url('admin/events/create') ?>" method="POST" enctype="multipart/form-data" id="createEventForm">
                                <div class="form-group">
                                    <label class="form-label" for="ev_title">Event Title</label>
                                    <input type="text" name="title" id="ev_title" class="form-input" placeholder="e.g. Sunday Celebration Service" required>
                                </div>

                                <!-- Visual Invitation Flyer / Banner Upload (Drag & Drop) -->
                                <div class="form-group">
                                    <label class="form-label">Visual Invitation / Event Flyer (Optional)</label>
                                    <div class="avatar-upload-box" id="eventBannerDropZone" style="border-radius:var(--radius-sm); padding:12px;">
                                        <div id="bannerPreviewContainer" style="display:none; margin-bottom:8px; border-radius:6px; overflow:hidden; max-height:140px; border:1px solid var(--card-border);">
                                            <img src="" id="eventBannerPreview" style="width:100%; height:auto; object-fit:cover;" alt="Event Banner">
                                        </div>
                                        <div id="bannerPlaceholderText">
                                            <svg width="24" height="24" fill="none" stroke="var(--primary)" stroke-width="1.75" viewBox="0 0 24 24" style="margin:0 auto 4px; display:block;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <div class="avatar-upload-text"><strong>Drag or choose event invitation flyer</strong></div>
                                            <div class="avatar-upload-hint">PNG, JPG, WEBP flyer image up to 5MB</div>
                                        </div>
                                        <input type="file" name="banner_image" id="eventBannerInput" accept="image/png, image/jpeg, image/webp, image/gif" onchange="previewEventBanner()">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="ev_location">Location / Venue</label>
                                    <input type="text" name="location" id="ev_location" class="form-input" placeholder="e.g. Main Sanctuary" required>
                                </div>
                                <div class="form-row">
                                    <div class="form-group">
                                        <label class="form-label" for="ev_date">Date</label>
                                        <input type="date" name="event_date" id="ev_date" class="form-input" required value="<?= date('Y-m-d', strtotime('+3 days')) ?>">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="ev_time">Time</label>
                                        <input type="text" name="event_time" id="ev_time" class="form-input" placeholder="10:00 AM" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="ev_cap">Capacity (Optional)</label>
                                    <input type="number" name="capacity" id="ev_cap" class="form-input" placeholder="e.g. 250">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="ev_desc">Description</label>
                                    <textarea name="description" id="ev_desc" class="form-textarea" placeholder="Event details and instructions for attendees..."></textarea>
                                </div>

                                <button type="submit" class="btn-save">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    <span>Publish Event</span>
                                </button>
                            </form>
                        </div>

                        <!-- Events Roster Table -->
                        <div class="section-box">
                            <h2 class="section-title" style="margin-bottom:14px;">All Scheduled Events</h2>
                            <div class="table-responsive">
                                <table class="clean-table">
                                    <thead>
                                        <tr>
                                            <th>Banner</th>
                                            <th>Event Title</th>
                                            <th>Date & Time</th>
                                            <th>RSVPs</th>
                                            <th>Checked In</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($all_events_admin)): ?>
                                            <?php foreach ($all_events_admin as $ev): ?>
                                                <tr>
                                                    <td>
                                                        <?php if (!empty($ev['banner_image'])): ?>
                                                            <img src="<?= site_url($ev['banner_image']) ?>" alt="Banner" style="width:48px; height:32px; object-fit:cover; border-radius:4px; border:1px solid var(--card-border);">
                                                        <?php else: ?>
                                                            <div style="width:48px; height:32px; background:#f1f5f9; border-radius:4px; display:flex; align-items:center; justify-content:center; color:var(--text-muted); font-size:0.65rem;">No flyer</div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div style="font-weight:700;"><?= htmlspecialchars($ev['title']) ?></div>
                                                        <div style="font-size:0.72rem; color:var(--text-muted);"><?= htmlspecialchars($ev['location']) ?></div>
                                                    </td>
                                                    <td style="font-size:0.78rem;">
                                                        <div><?= date('M j, Y', strtotime($ev['event_date'])) ?></div>
                                                        <div style="color:var(--text-muted);"><?= htmlspecialchars($ev['event_time']) ?></div>
                                                    </td>
                                                    <td>
                                                        <span class="tag-status approved"><?= (int)$ev['rsvp_count'] ?> RSVP</span>
                                                    </td>
                                                    <td>
                                                        <span class="tag-status" style="background:#ede9fe; color:#6d28d9; border:1px solid #ddd6fe;"><?= (int)$ev['checked_in_count'] ?> attended</span>
                                                    </td>
                                                    <td>
                                                        <div style="display:flex; gap:6px; align-items:center;">
                                                            <button type="button" class="btn-action-sm btn-action-approve" onclick='openEditEventModal(<?= json_encode($ev) ?>)'>
                                                                Edit
                                                            </button>
                                                            <form action="<?= site_url('admin/events/delete') ?>" method="POST" onsubmit="return confirm('Delete this event and its attendance records?')" style="margin:0;">
                                                                <input type="hidden" name="event_id" value="<?= (int)$ev['id'] ?>">
                                                                <button type="submit" class="btn-action-sm btn-action-reject">Delete</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" style="text-align:center; padding:24px; color:var(--text-muted);">No events published yet.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ── EDIT EVENT MODAL ── -->
                <div id="editEventModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15, 23, 42, 0.6); backdrop-filter:blur(3px); z-index:9999; align-items:center; justify-content:center; padding:20px;">
                    <div style="background:#ffffff; width:100%; max-width:540px; border-radius:var(--radius-lg); padding:24px; box-shadow:var(--shadow-lg); max-height:90vh; overflow-y:auto;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                            <h2 style="font-size:1.05rem; font-weight:800;">Edit Church Event</h2>
                            <button type="button" onclick="closeEditEventModal()" style="background:transparent; border:none; cursor:pointer; color:var(--text-muted); display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:50%;">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <form action="<?= site_url('admin/events/update') ?>" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="event_id" id="edit_ev_id">

                            <div class="form-group">
                                <label class="form-label" for="edit_ev_title">Event Title</label>
                                <input type="text" name="title" id="edit_ev_title" class="form-input" required>
                            </div>

                            <!-- Edit Visual Invitation Flyer Upload -->
                            <div class="form-group">
                                <label class="form-label">Update Visual Invitation Flyer (Optional)</label>
                                <div class="avatar-upload-box" id="editEventBannerDropZone" style="border-radius:var(--radius-sm); padding:12px;">
                                    <div id="editBannerPreviewContainer" style="display:none; margin-bottom:8px; border-radius:6px; overflow:hidden; max-height:140px; border:1px solid var(--card-border);">
                                        <img src="" id="editEventBannerPreview" style="width:100%; height:auto; object-fit:cover;" alt="Event Banner">
                                    </div>
                                    <div id="editBannerPlaceholderText">
                                        <svg width="24" height="24" fill="none" stroke="var(--primary)" stroke-width="1.75" viewBox="0 0 24 24" style="margin:0 auto 4px; display:block;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <div class="avatar-upload-text"><strong>Drag or choose new event flyer</strong></div>
                                        <div class="avatar-upload-hint">Leave blank to keep existing flyer</div>
                                    </div>
                                    <input type="file" name="banner_image" id="editEventBannerInput" accept="image/png, image/jpeg, image/webp, image/gif" onchange="previewEditEventBanner()">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="edit_ev_location">Location / Venue</label>
                                <input type="text" name="location" id="edit_ev_location" class="form-input" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="edit_ev_date">Date</label>
                                    <input type="date" name="event_date" id="edit_ev_date" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="edit_ev_time">Time</label>
                                    <input type="text" name="event_time" id="edit_ev_time" class="form-input" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="edit_ev_cap">Capacity (Optional)</label>
                                <input type="number" name="capacity" id="edit_ev_cap" class="form-input">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="edit_ev_desc">Description</label>
                                <textarea name="description" id="edit_ev_desc" class="form-textarea"></textarea>
                            </div>

                            <div style="display:flex; gap:10px; margin-top:16px;">
                                <button type="button" onclick="closeEditEventModal()" class="btn-avatar-action" style="flex:1; justify-content:center; background:#f1f5f9; color:var(--text-secondary);">Cancel</button>
                                <button type="submit" class="btn-save" style="flex:2;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>Save Event Changes</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ── TAB 7: ADMIN MEMBERS DIRECTORY ── -->
                <div id="tab-admin-members" class="tab-content">
                    <section class="section-box">
                        <div class="section-header-row">
                            <div>
                                <h2 class="section-title">Member Directory</h2>
                                <p class="section-desc">Review, search, manage roles, and export registered church members</p>
                            </div>
                            <div style="display:flex; gap:10px; align-items:center;">
                                <a href="<?= site_url('admin/export-members') ?>" class="btn-avatar-action btn-avatar-change" style="padding:8px 14px; background:#16a34a; color:#ffffff; border:none; text-decoration:none; font-weight:700;">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Export to CSV / Excel</span>
                                </a>
                                <button type="button" onclick="printMemberDirectoryDoc()" class="btn-avatar-action btn-avatar-change" style="padding:8px 14px; background:var(--orange-primary); color:#ffffff; border:none; font-weight:700; cursor:pointer;">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    <span>Print / PDF Report</span>
                                </button>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="stats-row">
                            <div class="stat-box">
                                <div>
                                    <div class="stat-title">Total Members</div>
                                    <div class="stat-val"><?= (int)($total_members ?? 0) ?></div>
                                </div>
                                <div class="stat-icon-wrapper">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                            </div>
                            <div class="stat-box">
                                <div>
                                    <div class="stat-title">Active Members</div>
                                    <div class="stat-val" style="color:var(--success);"><?= (int)($active_members ?? 0) ?></div>
                                </div>
                                <div class="stat-icon-wrapper">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                            <div class="stat-box">
                                <div>
                                    <div class="stat-title">Access Level</div>
                                    <div class="stat-val" style="font-size:0.95rem; color:var(--primary); margin-top:6px;">Administrator</div>
                                </div>
                                <div class="stat-icon-wrapper">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Live Search & Filter Bar -->
                        <div style="margin-bottom:16px; display:flex; gap:12px; flex-wrap:wrap;">
                            <div style="flex:1; min-width:240px; position:relative;">
                                <input type="text" id="memberSearchInput" class="form-input" placeholder="Search by name, username, email, ID, or ministry..." onkeyup="filterMemberTable()" style="padding-left:36px;">
                                <svg style="position:absolute; left:11px; top:10px; color:var(--text-muted);" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <select id="roleFilterSelect" class="form-select" onchange="filterMemberTable()" style="width:auto; min-width:140px;">
                                <option value="">All Roles</option>
                                <option value="user">Members (User)</option>
                                <option value="moderator">Moderators</option>
                                <option value="admin">Administrators</option>
                            </select>
                            <select id="statusFilterSelect" class="form-select" onchange="filterMemberTable()" style="width:auto; min-width:140px;">
                                <option value="">All Statuses</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <!-- Member Table -->
                        <div class="table-responsive">
                            <table class="clean-table" id="membersRosterTable">
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Member ID</th>
                                        <th>Name / Username</th>
                                        <th>Email</th>
                                        <th>Ministry</th>
                                        <th>D-Group</th>
                                        <th>Role / Permission</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($all_members)): ?>
                                        <?php foreach ($all_members as $m): ?>
                                            <?php
                                                $m_avatar = !empty($m['avatar']) ? site_url($m['avatar']) : null;
                                                $m_initial = strtoupper(substr($m['full_name'] ?? $m['username'] ?? 'M', 0, 1));
                                                $current_m_role = $m['role'] ?? 'user';
                                                $is_self = ($m['user_id'] ?? null) == $user['id'];
                                            ?>
                                            <tr class="member-table-row" 
                                                data-search="<?= strtolower(htmlspecialchars(($m['full_name'] ?? '') . ' ' . ($m['username'] ?? '') . ' ' . ($m['email'] ?? '') . ' ' . ($m['member_id'] ?? '') . ' ' . ($m['ministry_name'] ?? '') . ' ' . ($m['dgroup_name'] ?? ''))) ?>"
                                                data-role="<?= strtolower($current_m_role) ?>"
                                                data-status="<?= !empty($m['is_active']) ? 'active' : 'inactive' ?>">
                                                <td>
                                                    <div class="table-avatar">
                                                        <?php if ($m_avatar): ?>
                                                            <img src="<?= $m_avatar ?>" alt="Avatar">
                                                        <?php else: ?>
                                                            <?= $m_initial ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td style="font-family:monospace; font-weight:600; color:var(--primary);">
                                                    <?= htmlspecialchars($m['member_id'] ?? '—') ?>
                                                </td>
                                                <td>
                                                    <div style="font-weight:600;"><?= htmlspecialchars($m['full_name'] ?? '—') ?></div>
                                                    <div style="font-size:0.75rem; color:var(--text-muted);">@<?= htmlspecialchars($m['username'] ?? '—') ?></div>
                                                </td>
                                                <td><?= htmlspecialchars($m['email'] ?? '—') ?></td>
                                                <td>
                                                    <?php if (!empty($m['ministry_name'])): ?>
                                                        <span class="tag-status approved"><?= htmlspecialchars($m['ministry_name']) ?></span>
                                                    <?php else: ?>
                                                        <span style="color:var(--text-muted);">None</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($m['dgroup_name'])): ?>
                                                        <span class="tag-status" style="background:#f3e8ff; color:#7e22ce; border:1px solid #e9d5ff;"><?= htmlspecialchars($m['dgroup_name']) ?></span>
                                                    <?php else: ?>
                                                        <span style="color:var(--text-muted);">None</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!$is_self): ?>
                                                        <form action="<?= site_url('admin/update-member-role') ?>" method="POST" style="margin:0; display:flex; align-items:center; gap:6px;">
                                                            <input type="hidden" name="user_id" value="<?= (int)$m['user_id'] ?>">
                                                            <select name="role" class="form-select" onchange="this.form.submit()" style="padding:3px 6px; font-size:0.75rem; width:auto; font-weight:600; cursor:pointer;">
                                                                <option value="user" <?= $current_m_role === 'user' ? 'selected' : '' ?>>Member</option>
                                                                <option value="moderator" <?= $current_m_role === 'moderator' ? 'selected' : '' ?>>Moderator</option>
                                                                <option value="admin" <?= $current_m_role === 'admin' ? 'selected' : '' ?>>Admin</option>
                                                            </select>
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="role-tag admin">ADMIN (YOU)</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($m['is_active'])): ?>
                                                        <span class="tag-status approved">Active</span>
                                                    <?php else: ?>
                                                        <span class="tag-status rejected">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!$is_self): ?>
                                                        <form action="<?= site_url('admin/toggle-member-status') ?>" method="POST" style="margin:0;">
                                                            <input type="hidden" name="member_row_id" value="<?= (int)$m['member_row_id'] ?>">
                                                            <button type="submit" class="btn-action-sm btn-action-toggle">
                                                                <?= !empty($m['is_active']) ? 'Deactivate' : 'Activate' ?>
                                                            </button>
                                                        </form>
                                                    <?php else: ?>
                                                        <span style="font-size:0.75rem; color:var(--text-muted);">(Current Admin)</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" style="text-align:center; padding:24px; color:var(--text-muted);">No members registered yet.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

                <!-- ── TAB 8: ADMIN VOLUNTEER REQUESTS ── -->
                <div id="tab-admin-ministry-apps" class="tab-content">
                    <section class="section-box">
                        <div class="section-header-row" style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:14px;">
                            <div>
                                <h2 class="section-title">Review Volunteer Applications</h2>
                                <p class="section-desc">Approve, reject, or manage ministry team requests</p>
                            </div>
                            <!-- Export and Print Document Actions -->
                            <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                                <button type="button" onclick="printVolunteerRequestsDoc()" class="btn-avatar-action" style="padding:8px 14px; background:#ffffff; color:var(--text-secondary); border:1px solid var(--card-border); border-radius:var(--radius-md); font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px; box-shadow:var(--shadow-xs); transition:all 0.18s ease;">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    <span>Print / PDF Document</span>
                                </button>
                                <a href="<?= site_url('admin/export-volunteer-requests') ?>" class="btn-avatar-action btn-avatar-change" style="padding:8px 14px; background:#16a34a; color:#ffffff; border:none; text-decoration:none; font-weight:700; border-radius:var(--radius-md); display:inline-flex; align-items:center; gap:6px; box-shadow:0 2px 8px rgba(22, 163, 74, 0.25);">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Export to Excel / CSV</span>
                                </a>
                            </div>
                        </div>

                        <!-- Stats Row for Volunteer Requests -->
                        <?php
                            $total_v_apps = count($all_ministry_apps ?? []);
                            $pending_v_apps = count(array_filter($all_ministry_apps ?? [], fn($a) => ($a['status'] ?? '') === 'pending'));
                            $approved_v_apps = count(array_filter($all_ministry_apps ?? [], fn($a) => ($a['status'] ?? '') === 'approved'));
                        ?>
                        <div class="stats-row" style="margin-bottom:20px;">
                            <div class="stat-box">
                                <div>
                                    <div class="stat-title">Total Requests</div>
                                    <div class="stat-val"><?= $total_v_apps ?></div>
                                </div>
                                <div class="stat-icon-wrapper">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                            </div>
                            <div class="stat-box">
                                <div>
                                    <div class="stat-title">Pending Review</div>
                                    <div class="stat-val" style="color:var(--orange-primary);"><?= $pending_v_apps ?></div>
                                </div>
                                <div class="stat-icon-wrapper">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                            <div class="stat-box">
                                <div>
                                    <div class="stat-title">Approved Volunteers</div>
                                    <div class="stat-val" style="color:var(--success);"><?= $approved_v_apps ?></div>
                                </div>
                                <div class="stat-icon-wrapper">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($all_ministry_apps)): ?>
                            <!-- Live Filter Bar -->
                            <div style="margin-bottom:16px; display:flex; gap:12px; flex-wrap:wrap;">
                                <div style="flex:1; min-width:240px; position:relative;">
                                    <input type="text" id="volunteerSearchInput" class="form-input" placeholder="Search applicant, ministry team, or status..." onkeyup="filterVolunteerTable()" style="padding-left:36px;">
                                    <svg style="position:absolute; left:11px; top:11px; color:var(--text-muted);" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <select id="volunteerStatusFilter" class="form-select" onchange="filterVolunteerTable()" style="width:auto; min-width:140px;">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>

                            <div class="table-responsive" id="volunteerTablePrintArea">
                                <table class="clean-table" id="volunteerApplicationsTable">
                                    <thead>
                                        <tr>
                                            <th>Ministry</th>
                                            <th>Applicant</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th class="hide-on-print">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($all_ministry_apps as $app): ?>
                                            <tr class="volunteer-row" data-search="<?= strtolower(htmlspecialchars(($app['ministry_name'] ?? '') . ' ' . ($app['member_name'] ?? '') . ' ' . ($app['member_email'] ?? '') . ' ' . ($app['member_id'] ?? '') . ' ' . ($app['status'] ?? ''))) ?>" data-status="<?= strtolower($app['status'] ?? '') ?>">
                                                <td>
                                                    <strong><?= htmlspecialchars($app['ministry_name']) ?></strong>
                                                    <?php if (!empty($app['message'])): ?>
                                                        <div style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">"<?= htmlspecialchars($app['message']) ?>"</div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div style="font-weight:700; color:var(--text-primary);"><?= htmlspecialchars($app['member_name']) ?></div>
                                                    <?php if (!empty($app['member_id'])): ?>
                                                        <div style="font-size:0.75rem; font-family:monospace; color:var(--primary);"><?= htmlspecialchars($app['member_id']) ?></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($app['member_email'])): ?>
                                                        <div style="font-size:0.73rem; color:var(--text-muted);"><?= htmlspecialchars($app['member_email']) ?></div>
                                                    <?php endif; ?>
                                                </td>
                                                <td><span class="tag-status <?= $app['status'] ?>"><?= strtoupper($app['status']) ?></span></td>
                                                <td style="font-size:0.75rem; color:var(--text-muted); white-space:nowrap;"><?= date('M j, Y', strtotime($app['created_at'])) ?></td>
                                                <td class="hide-on-print">
                                                    <div style="display:flex; gap:6px; align-items:center; flex-wrap:wrap;">
                                                        <form action="<?= site_url('admin/ministry/update-application') ?>" method="POST" style="display:inline-flex; gap:4px; margin:0;">
                                                            <input type="hidden" name="app_id" value="<?= (int)$app['id'] ?>">
                                                            <?php if ($app['status'] !== 'approved'): ?>
                                                                <button type="submit" name="status" value="approved" class="btn-action-sm btn-action-approve" title="Approve volunteer request">Approve</button>
                                                            <?php endif; ?>
                                                            <?php if ($app['status'] !== 'rejected'): ?>
                                                                <button type="submit" name="status" value="rejected" class="btn-action-sm btn-action-reject" title="Reject volunteer request">Reject</button>
                                                            <?php endif; ?>
                                                        </form>
                                                        <!-- Delete Action -->
                                                        <form action="<?= site_url('admin/ministry/delete-application') ?>" method="POST" style="display:inline-flex; margin:0;" onsubmit="return confirm('Are you sure you want to permanently delete the volunteer request of <?= htmlspecialchars(addslashes($app['member_name'])) ?> for <?= htmlspecialchars(addslashes($app['ministry_name'])) ?>?');">
                                                            <input type="hidden" name="app_id" value="<?= (int)$app['id'] ?>">
                                                            <button type="submit" class="btn-action-sm" style="background:#fff1f2; color:#be123c; border:1px solid #fecdd3; display:inline-flex; align-items:center; gap:4px;" title="Delete this application record">
                                                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                <span>Delete</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div style="text-align:center; padding:32px; color:var(--text-muted);">No volunteer applications submitted yet.</div>
                        <?php endif; ?>
                    </section>
                </div>

                <!-- ── TAB: ADMIN MANAGE MINISTRIES (CREATE / EDIT PHOTO / DELETE) ── -->
                <div id="tab-admin-ministries" class="tab-content">
                    <div style="display:grid; grid-template-columns: 1fr 1.6fr; gap:24px;">
                        <!-- Create Ministry Form with Photo Upload -->
                        <div class="section-box">
                            <h2 class="section-title" style="margin-bottom:14px;">Add Ministry Team</h2>
                            <form action="<?= site_url('admin/ministries/create') ?>" method="POST" enctype="multipart/form-data" id="createMinistryForm">
                                <div class="form-group">
                                    <label class="form-label" for="min_name">Ministry Name</label>
                                    <input type="text" name="name" id="min_name" class="form-input" placeholder="e.g. Worship Team, Media & Tech" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="min_icon">Ministry Icon Symbol</label>
                                    <input type="text" name="icon" id="min_icon" class="form-input" placeholder="e.g. ✦, ★, ✝, ◈" value="✦">
                                </div>

                                <!-- Ministry Team Photo Upload (Drag & Drop) -->
                                <div class="form-group">
                                    <label class="form-label">Ministry Photo / Banner (Optional)</label>
                                    <div class="avatar-upload-box" id="minPhotoDropZone" style="border-radius:var(--radius-sm); padding:12px;">
                                        <div id="minPhotoPreviewContainer" style="display:none; margin-bottom:8px; border-radius:6px; overflow:hidden; max-height:140px; border:1px solid var(--card-border);">
                                            <img src="" id="minPhotoPreview" style="width:100%; height:auto; object-fit:cover;" alt="Ministry Photo">
                                        </div>
                                        <div id="minPlaceholderText">
                                            <svg width="24" height="24" fill="none" stroke="var(--primary)" stroke-width="1.75" viewBox="0 0 24 24" style="margin:0 auto 4px; display:block;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <div class="avatar-upload-text"><strong>Drag or choose ministry photo</strong></div>
                                            <div class="avatar-upload-hint">PNG, JPG, WEBP team photo up to 5MB</div>
                                        </div>
                                        <input type="file" name="image" id="minPhotoInput" accept="image/png, image/jpeg, image/webp, image/gif" onchange="previewMinistryPhoto()">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="min_desc">Description / Roles</label>
                                    <textarea name="description" id="min_desc" class="form-textarea" placeholder="Explain the mission, roles, and responsibilities for volunteers..."></textarea>
                                </div>

                                <button type="submit" class="btn-save">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    <span>Add Ministry Team</span>
                                </button>
                            </form>
                        </div>

                        <!-- Existing Ministries List with Visual Photos -->
                        <div class="section-box">
                            <h2 class="section-title" style="margin-bottom:14px;">Active Ministry Teams (<?= count($all_ministries_admin ?? []) ?>)</h2>
                            <?php if (!empty($all_ministries_admin)): ?>
                                <div class="table-responsive">
                                    <table class="clean-table">
                                        <thead>
                                            <tr>
                                                <th>Photo</th>
                                                <th>Ministry Team</th>
                                                <th>Volunteers</th>
                                                <th>Pending</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($all_ministries_admin as $am): ?>
                                                <tr>
                                                    <td>
                                                        <?php if (!empty($am['image'])): ?>
                                                            <img src="<?= site_url($am['image']) ?>" alt="Photo" style="width:50px; height:36px; object-fit:cover; border-radius:6px; border:1px solid var(--card-border);">
                                                        <?php else: ?>
                                                            <div style="width:50px; height:36px; border-radius:6px; background:var(--orange-soft); border:1px solid var(--orange-border); display:flex; align-items:center; justify-content:center; font-size:1rem;">
                                                                <?= htmlspecialchars($am['icon'] ?? '✦') ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div style="font-weight:700; color:var(--text-main);"><?= htmlspecialchars($am['name']) ?></div>
                                                        <div style="font-size:0.75rem; color:var(--text-muted); max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                            <?= htmlspecialchars($am['description'] ?? 'No description') ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="tag-status approved"><?= (int)($am['member_count'] ?? 0) ?> active</span>
                                                    </td>
                                                    <td>
                                                        <?php if ((int)($am['pending_apps_count'] ?? 0) > 0): ?>
                                                            <span class="tag-status pending" onclick="showTab('admin-ministry-apps', document.querySelector('[onclick*=\'admin-ministry-apps\']'))" style="cursor:pointer;" title="View Applications">
                                                                <?= (int)$am['pending_apps_count'] ?> pending
                                                            </span>
                                                        <?php else: ?>
                                                            <span style="font-size:0.75rem; color:var(--text-muted);">0</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div style="display:flex; gap:6px; align-items:center;">
                                                            <button type="button" class="btn-action-sm btn-action-approve" onclick="openEditMinistryModal(<?= htmlspecialchars(json_encode($am), ENT_QUOTES, 'UTF-8') ?>)">
                                                                Edit / Photo
                                                            </button>
                                                            <form action="<?= site_url('admin/ministries/delete') ?>" method="POST" style="margin:0;">
                                                                <input type="hidden" name="ministry_id" value="<?= (int)$am['id'] ?>">
                                                                <button type="submit" class="btn-action-sm btn-action-reject" onclick="return confirm('Delete ministry <?= htmlspecialchars(addslashes($am['name'])) ?>?')">
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div style="text-align:center; padding:32px; color:var(--text-muted);">No ministries created yet.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- ── TAB: ADMIN MANAGE D-GROUPS (CREATE / EDIT PHOTO / DELETE) ── -->
                <div id="tab-admin-dgroups" class="tab-content">
                    <div style="display:grid; grid-template-columns: 1fr 1.6fr; gap:24px;">
                        <!-- Create D-Group Form with Photo Upload -->
                        <div class="section-box">
                            <h2 class="section-title" style="margin-bottom:14px;">Add Discipleship Group</h2>
                            <form action="<?= site_url('admin/dgroups/create') ?>" method="POST" enctype="multipart/form-data" id="createDgroupForm">
                                <div class="form-group">
                                    <label class="form-label" for="dg_name">Group Name</label>
                                    <input type="text" name="name" id="dg_name" class="form-input" placeholder="e.g. LifeGen Young Adults, Men of Faith" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="dg_leader">Leader Name</label>
                                    <input type="text" name="leader_name" id="dg_leader" class="form-input" placeholder="e.g. Pastor Marcus Rivera" required>
                                </div>

                                <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                                    <div class="form-group">
                                        <label class="form-label" for="dg_sched">Schedule</label>
                                        <input type="text" name="schedule" id="dg_sched" class="form-input" placeholder="e.g. Saturdays 4:00 PM" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="dg_loc">Location / Room</label>
                                        <input type="text" name="location" id="dg_loc" class="form-input" placeholder="e.g. Room 204 or Zoom" required>
                                    </div>
                                </div>

                                <!-- D-Group Photo Upload (Drag & Drop) -->
                                <div class="form-group">
                                    <label class="form-label">Group Photo / Banner (Optional)</label>
                                    <div class="avatar-upload-box" id="dgroupPhotoDropZone" style="border-radius:var(--radius-sm); padding:12px;">
                                        <div id="dgPhotoPreviewContainer" style="display:none; margin-bottom:8px; border-radius:6px; overflow:hidden; max-height:140px; border:1px solid var(--card-border);">
                                            <img src="" id="dgPhotoPreview" style="width:100%; height:auto; object-fit:cover;" alt="D-Group Photo">
                                        </div>
                                        <div id="dgPlaceholderText">
                                            <svg width="24" height="24" fill="none" stroke="var(--primary)" stroke-width="1.75" viewBox="0 0 24 24" style="margin:0 auto 4px; display:block;"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <div class="avatar-upload-text"><strong>Drag or choose D-Group photo</strong></div>
                                            <div class="avatar-upload-hint">PNG, JPG, WEBP group photo up to 5MB</div>
                                        </div>
                                        <input type="file" name="image" id="dgPhotoInput" accept="image/png, image/jpeg, image/webp, image/gif" onchange="previewDgroupPhoto()">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="dg_desc">Description</label>
                                    <textarea name="description" id="dg_desc" class="form-textarea" placeholder="Explain the focus, demographic, and spiritual goals of this D-Group..."></textarea>
                                </div>

                                <button type="submit" class="btn-save">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    <span>Add D-Group</span>
                                </button>
                            </form>
                        </div>

                        <!-- Existing D-Groups List with Photos -->
                        <div class="section-box">
                            <h2 class="section-title" style="margin-bottom:14px;">Active Discipleship Groups (<?= count($all_dgroups_admin ?? []) ?>)</h2>
                            <?php if (!empty($all_dgroups_admin)): ?>
                                <div class="table-responsive">
                                    <table class="clean-table">
                                        <thead>
                                            <tr>
                                                <th>Photo</th>
                                                <th>Group & Leader</th>
                                                <th>Schedule / Location</th>
                                                <th>Members</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($all_dgroups_admin as $adg): ?>
                                                <tr>
                                                    <td>
                                                        <?php if (!empty($adg['image'])): ?>
                                                            <img src="<?= site_url($adg['image']) ?>" alt="Photo" style="width:52px; height:38px; object-fit:cover; border-radius:6px; border:1px solid var(--card-border);">
                                                        <?php else: ?>
                                                            <div style="width:52px; height:38px; border-radius:6px; background:#eff6ff; border:1px solid #bfdbfe; display:flex; align-items:center; justify-content:center; color:#2563eb;">
                                                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div style="font-weight:700; color:var(--text-main);"><?= htmlspecialchars($adg['name']) ?></div>
                                                        <div style="font-size:0.75rem; color:var(--text-muted);">Leader: <?= htmlspecialchars($adg['leader_name']) ?></div>
                                                    </td>
                                                    <td>
                                                        <div style="font-size:0.8rem; font-weight:600;"><?= htmlspecialchars($adg['schedule']) ?></div>
                                                        <div style="font-size:0.75rem; color:var(--text-muted);"><?= htmlspecialchars($adg['location']) ?></div>
                                                    </td>
                                                    <td>
                                                        <span class="tag-status approved"><?= (int)($adg['member_count'] ?? 0) ?> member<?= (int)($adg['member_count'] ?? 0) !== 1 ? 's' : '' ?></span>
                                                    </td>
                                                    <td>
                                                        <div style="display:flex; gap:6px; align-items:center;">
                                                            <button type="button" class="btn-action-sm btn-action-approve" onclick="openEditDgroupModal(<?= htmlspecialchars(json_encode($adg), ENT_QUOTES, 'UTF-8') ?>)">
                                                                Edit / Photo
                                                            </button>
                                                            <form action="<?= site_url('admin/dgroups/delete') ?>" method="POST" style="margin:0;">
                                                                <input type="hidden" name="dgroup_id" value="<?= (int)$adg['id'] ?>">
                                                                <button type="submit" class="btn-action-sm btn-action-reject" onclick="return confirm('Delete D-Group <?= htmlspecialchars(addslashes($adg['name'])) ?>?')">
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div style="text-align:center; padding:32px; color:var(--text-muted);">No D-Groups created yet.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Modal: Edit D-Group Details & Photo -->
                <div id="editDgroupModal" class="modal-overlay" style="display:none;" onclick="if(event.target === this) closeEditDgroupModal()">
                    <div class="modal-card" style="max-width:560px;">
                        <div class="modal-header">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:36px; height:36px; border-radius:10px; background:var(--blue-soft); border:1px solid var(--blue-border); display:flex; align-items:center; justify-content:center;">
                                    <svg width="16" height="16" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <div class="modal-title">Edit Discipleship Group</div>
                                    <div style="font-size:0.76rem; color:var(--text-muted); margin-top:1px;">Update group info and photo</div>
                                </div>
                            </div>
                            <button type="button" class="modal-close-btn" onclick="closeEditDgroupModal()">&times;</button>
                        </div>
                        <form action="<?= site_url('admin/dgroups/update') ?>" method="POST" enctype="multipart/form-data" id="editDgroupForm">
                            <input type="hidden" name="dgroup_id" id="edit_dg_id">

                            <div class="form-group">
                                <label class="form-label" for="edit_dg_name">Group Name</label>
                                <input type="text" name="name" id="edit_dg_name" class="form-input" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="edit_dg_leader">Leader Name</label>
                                <input type="text" name="leader_name" id="edit_dg_leader" class="form-input" required>
                            </div>

                            <div class="form-row" style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                                <div class="form-group">
                                    <label class="form-label" for="edit_dg_sched">Schedule</label>
                                    <input type="text" name="schedule" id="edit_dg_sched" class="form-input" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="edit_dg_loc">Location / Room</label>
                                    <input type="text" name="location" id="edit_dg_loc" class="form-input" required>
                                </div>
                            </div>

                            <!-- Photo Replacement Upload -->
                            <div class="form-group">
                                <label class="form-label">Replace D-Group Photo</label>
                                <div class="avatar-upload-box" id="editDgroupPhotoDropZone" style="border-radius:var(--radius-sm); padding:12px;">
                                    <div id="editDgPhotoPreviewContainer" style="margin-bottom:8px; border-radius:6px; overflow:hidden; max-height:140px; border:1px solid var(--card-border);">
                                        <img src="" id="editDgPhotoPreview" style="width:100%; height:auto; object-fit:cover;" alt="Preview">
                                    </div>
                                    <div class="avatar-upload-text"><strong>Click to select new photo</strong></div>
                                    <input type="file" name="image" id="editDgPhotoInput" accept="image/png, image/jpeg, image/webp, image/gif" onchange="previewEditDgroupPhoto()">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="edit_dg_desc">Description</label>
                                <textarea name="description" id="edit_dg_desc" class="form-textarea"></textarea>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn-cancel" onclick="closeEditDgroupModal()">Cancel</button>
                                <button type="submit" class="btn-save" style="flex:2;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>Save D-Group Changes</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal: Edit Ministry Details & Photo -->
                <div id="editMinistryModal" class="modal-overlay" style="display:none;" onclick="if(event.target === this) closeEditMinistryModal()">
                    <div class="modal-card" style="max-width:560px;">
                        <div class="modal-header">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:36px; height:36px; border-radius:10px; background:var(--orange-soft); border:1px solid var(--orange-border); display:flex; align-items:center; justify-content:center;">
                                    <svg width="16" height="16" fill="none" stroke="var(--orange-primary)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                </div>
                                <div>
                                    <div class="modal-title">Edit Ministry Team</div>
                                    <div style="font-size:0.76rem; color:var(--text-muted); margin-top:1px;">Update ministry info and photo</div>
                                </div>
                            </div>
                            <button type="button" class="modal-close-btn" onclick="closeEditMinistryModal()">&times;</button>
                        </div>
                        <form action="<?= site_url('admin/ministries/update') ?>" method="POST" enctype="multipart/form-data" id="editMinistryForm">
                            <input type="hidden" name="ministry_id" id="edit_min_id">

                            <div class="form-group">
                                <label class="form-label" for="edit_min_name">Ministry Name</label>
                                <input type="text" name="name" id="edit_min_name" class="form-input" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="edit_min_icon">Icon / Emoji</label>
                                <input type="text" name="icon" id="edit_min_icon" class="form-input">
                            </div>

                            <!-- Photo Replacement Upload -->
                            <div class="form-group">
                                <label class="form-label">Replace Ministry Photo / Banner</label>
                                <div class="avatar-upload-box" id="editMinPhotoDropZone" style="border-radius:var(--radius-sm); padding:12px;">
                                    <div id="editMinPhotoPreviewContainer" style="margin-bottom:8px; border-radius:6px; overflow:hidden; max-height:140px; border:1px solid var(--card-border);">
                                        <img src="" id="editMinPhotoPreview" style="width:100%; height:auto; object-fit:cover;" alt="Preview">
                                    </div>
                                    <div class="avatar-upload-text"><strong>Click to select new photo</strong></div>
                                    <input type="file" name="image" id="editMinPhotoInput" accept="image/png, image/jpeg, image/webp, image/gif" onchange="previewEditMinistryPhoto()">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="edit_min_desc">Description</label>
                                <textarea name="description" id="edit_min_desc" class="form-textarea"></textarea>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn-cancel" onclick="closeEditMinistryModal()">Cancel</button>
                                <button type="submit" class="btn-save" style="flex:2;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <span>Save Ministry Changes</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

        </main>
    </div>

    <script>
        // Tab Navigation - Globally available
        function showTab(tabName, btnElement) {
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(t => t.classList.remove('active'));

            const targetTab = document.getElementById('tab-' + tabName);
            if (targetTab) {
                targetTab.classList.add('active');
            }

            const buttons = document.querySelectorAll('.nav-btn');
            buttons.forEach(b => b.classList.remove('active'));
            if (btnElement) {
                btnElement.classList.add('active');
            }

            const titles = {
                'overview': 'Dashboard',
                'profile': 'My Member Profile',
                'events': 'Church Events & RSVP',
                'dgroup': 'Discipleship Groups',
                'ministry': 'Ministries & Volunteering',
                'admin-attendance': 'QR Event Check-in Station',
                'admin-events': 'Event Management',
                'admin-dgroups': 'Manage Discipleship Groups',
                'admin-ministries': 'Manage Ministry Teams',
                'admin-members': 'Members Directory',
                'admin-ministry-apps': 'Volunteer Requests'
            };
            const titleElement = document.getElementById('pageTitle');
            if (titleElement && titles[tabName]) {
                titleElement.textContent = titles[tabName];
            }

            sessionStorage.setItem('activeDashboardTab', tabName);
        }

        // D-Group Photo Previews & Modals
        function previewDgroupPhoto() {
            const input = document.getElementById('dgPhotoInput');
            const preview = document.getElementById('dgPhotoPreview');
            const container = document.getElementById('dgPhotoPreviewContainer');
            const placeholder = document.getElementById('dgPlaceholderText');
            if (input && input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewEditDgroupPhoto() {
            const input = document.getElementById('editDgPhotoInput');
            const preview = document.getElementById('editDgPhotoPreview');
            const container = document.getElementById('editDgPhotoPreviewContainer');
            if (input && input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function openEditDgroupModal(dgData) {
            const modal = document.getElementById('editDgroupModal');
            if (!modal) return;

            document.getElementById('edit_dg_id').value = dgData.id;
            document.getElementById('edit_dg_name').value = dgData.name || '';
            document.getElementById('edit_dg_leader').value = dgData.leader_name || '';
            document.getElementById('edit_dg_sched').value = dgData.schedule || '';
            document.getElementById('edit_dg_loc').value = dgData.location || '';
            document.getElementById('edit_dg_desc').value = dgData.description || '';

            const previewImg = document.getElementById('editDgPhotoPreview');
            const container = document.getElementById('editDgPhotoPreviewContainer');
            if (dgData.image) {
                previewImg.src = "<?= site_url('') ?>" + dgData.image.replace(/^\//, '');
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
                previewImg.src = '';
            }

            modal.style.display = 'flex';
        }

        function closeEditDgroupModal() {
            const modal = document.getElementById('editDgroupModal');
            if (modal) modal.style.display = 'none';
        }

        // Ministry Photo Previews & Modals
        function previewMinistryPhoto() {
            const input = document.getElementById('minPhotoInput');
            const preview = document.getElementById('minPhotoPreview');
            const container = document.getElementById('minPhotoPreviewContainer');
            const placeholder = document.getElementById('minPlaceholderText');
            if (input && input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewEditMinistryPhoto() {
            const input = document.getElementById('editMinPhotoInput');
            const preview = document.getElementById('editMinPhotoPreview');
            const container = document.getElementById('editMinPhotoPreviewContainer');
            if (input && input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function openEditMinistryModal(minData) {
            const modal = document.getElementById('editMinistryModal');
            if (!modal) return;

            document.getElementById('edit_min_id').value = minData.id;
            document.getElementById('edit_min_name').value = minData.name || '';
            document.getElementById('edit_min_icon').value = minData.icon || '✦';
            document.getElementById('edit_min_desc').value = minData.description || '';

            const previewImg = document.getElementById('editMinPhotoPreview');
            const container = document.getElementById('editMinPhotoPreviewContainer');
            if (minData.image) {
                previewImg.src = "<?= site_url('') ?>" + minData.image.replace(/^\//, '');
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
                previewImg.src = '';
            }

            modal.style.display = 'flex';
        }

        function closeEditMinistryModal() {
            const modal = document.getElementById('editMinistryModal');
            if (modal) modal.style.display = 'none';
        }

        // QR Code Scanner State & Logic
        let html5QrScanner = null;
        let isScannerRunning = false;

        function toggleQrScanner() {
            const qrReaderDiv = document.getElementById('qrReader');
            const btnText = document.getElementById('scannerBtnText');

            if (!isScannerRunning) {
                if (!window.Html5Qrcode) {
                    alert('QR scanner library is loading. Please try again.');
                    return;
                }
                qrReaderDiv.style.display = 'block';
                btnText.textContent = 'Stop Scanner';
                isScannerRunning = true;

                html5QrScanner = new Html5Qrcode("qrReader");
                html5QrScanner.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: { width: 250, height: 250 } },
                    (decodedText) => {
                        document.getElementById('manual_member_id').value = decodedText.trim();
                        html5QrScanner.stop().then(() => {
                            qrReaderDiv.style.display = 'none';
                            btnText.textContent = 'Open Camera QR Scanner';
                            isScannerRunning = false;
                            document.getElementById('checkInForm').submit();
                        });
                    },
                    (errorMessage) => {
                        // ignore scan search frames
                    }
                ).catch(err => {
                    alert('Camera access error: ' + err);
                    qrReaderDiv.style.display = 'none';
                    btnText.textContent = 'Open Camera QR Scanner';
                    isScannerRunning = false;
                });
            } else {
                if (html5QrScanner) {
                    html5QrScanner.stop().then(() => {
                        qrReaderDiv.style.display = 'none';
                        btnText.textContent = 'Open Camera QR Scanner';
                        isScannerRunning = false;
                    });
                }
            }
        }

        // Avatar Upload Form & Live Preview
        function submitAvatarForm() {
            const form      = document.getElementById('avatarForm');
            const fileInput = document.getElementById('avatarInput');
            if (fileInput && fileInput.files && fileInput.files[0]) {
                const file = fileInput.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('avatarPreview');
                    if (preview) {
                        preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
                    }
                };
                reader.readAsDataURL(file);
                if (form) form.submit();
            }
        }

        // Event Flyer Live Preview
        function previewEventBanner() {
            const input = document.getElementById('eventBannerInput');
            const previewContainer = document.getElementById('bannerPreviewContainer');
            const previewImg = document.getElementById('eventBannerPreview');
            const placeholder = document.getElementById('bannerPlaceholderText');

            if (input && input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (previewImg) previewImg.src = e.target.result;
                    if (previewContainer) previewContainer.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Filter Member Directory Table by text, role, status
        function filterMemberTable() {
            const searchInput = document.getElementById('memberSearchInput');
            const roleSelect   = document.getElementById('roleFilterSelect');
            const statusSelect = document.getElementById('statusFilterSelect');

            const query  = searchInput ? searchInput.value.toLowerCase().trim() : '';
            const role   = roleSelect ? roleSelect.value.toLowerCase() : '';
            const status = statusSelect ? statusSelect.value.toLowerCase() : '';

            const rows = document.querySelectorAll('.member-table-row');
            rows.forEach(row => {
                const rowSearch = row.getAttribute('data-search') || '';
                const rowRole   = row.getAttribute('data-role') || '';
                const rowStatus = row.getAttribute('data-status') || '';

                const matchesQuery  = !query || rowSearch.includes(query);
                const matchesRole   = !role || rowRole === role;
                const matchesStatus = !status || rowStatus === status;

                if (matchesQuery && matchesRole && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Print & Save as PDF for Member Directory
        function printMemberDirectoryDoc() {
            const originalTitle = document.title;
            const printDate = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            document.title = 'Life_Generation_Member_Directory_' + printDate.replace(/\s+/g, '_');

            const tableEl = document.getElementById('membersRosterTable');
            if (!tableEl) { window.print(); return; }

            // Build a clean copy of the table — strip action column & photo column
            const clonedTable = tableEl.cloneNode(true);

            // Remove "Photo" (col 0) and "Action" (last col) from header
            const headerRow = clonedTable.querySelector('thead tr');
            if (headerRow) {
                // Remove last th (Action) and first th (Photo)
                if (headerRow.lastElementChild) headerRow.removeChild(headerRow.lastElementChild);
                if (headerRow.firstElementChild) headerRow.removeChild(headerRow.firstElementChild);
            }
            // Remove photo cell (first td) and action cell (last td) from every body row
            clonedTable.querySelectorAll('tbody tr').forEach(row => {
                // skip hidden rows
                if (row.style.display === 'none') { row.parentNode.removeChild(row); return; }
                if (row.lastElementChild) row.removeChild(row.lastElementChild);
                if (row.firstElementChild) row.removeChild(row.firstElementChild);
            });

            // Simplify role cells – replace dropdowns with text
            clonedTable.querySelectorAll('select').forEach(sel => {
                const span = document.createElement('span');
                span.textContent = sel.options[sel.selectedIndex]?.text || sel.value;
                sel.parentNode.replaceChild(span, sel);
            });

            const tableHtml = clonedTable.outerHTML;
            const totalRows = clonedTable.querySelectorAll('tbody tr').length;

            const printWindow = window.open('', '_blank', 'width=1050,height=800');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Life Generation – Member Directory Report</title>
                    <style>
                        * { box-sizing: border-box; margin: 0; padding: 0; }
                        body {
                            font-family: Arial, sans-serif;
                            margin: 30px 40px;
                            color: #1e293b;
                            font-size: 13px;
                        }
                        .doc-header {
                            display: flex;
                            align-items: flex-start;
                            justify-content: space-between;
                            border-bottom: 3px solid #ea580c;
                            padding-bottom: 14px;
                            margin-bottom: 20px;
                        }
                        .church-name {
                            font-size: 22px;
                            font-weight: 900;
                            color: #0f172a;
                            letter-spacing: -0.5px;
                        }
                        .church-name span { color: #ea580c; }
                        .doc-subtitle {
                            font-size: 13px;
                            color: #64748b;
                            margin-top: 4px;
                        }
                        .meta-box {
                            text-align: right;
                            font-size: 12px;
                            color: #475569;
                            line-height: 1.7;
                        }
                        .meta-box strong { color: #0f172a; }
                        .summary-row {
                            display: flex;
                            gap: 16px;
                            margin-bottom: 18px;
                        }
                        .summary-card {
                            flex: 1;
                            border: 1px solid #e2e8f0;
                            border-radius: 8px;
                            padding: 10px 14px;
                            background: #f8fafc;
                        }
                        .summary-label { font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.06em; }
                        .summary-val { font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 2px; }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            font-size: 11.5px;
                        }
                        th {
                            background: #f1f5f9;
                            border: 1px solid #cbd5e1;
                            padding: 7px 10px;
                            font-weight: 700;
                            color: #0f172a;
                            text-align: left;
                        }
                        td {
                            border: 1px solid #e2e8f0;
                            padding: 7px 10px;
                            vertical-align: middle;
                        }
                        tr:nth-child(even) td { background: #f8fafc; }
                        .tag-status {
                            display: inline-block;
                            padding: 2px 7px;
                            border-radius: 4px;
                            font-size: 10px;
                            font-weight: 700;
                            border: 1px solid #94a3b8;
                        }
                        .tag-status.approved { background: #dcfce7; color: #166534; border-color: #86efac; }
                        .tag-status.rejected { background: #fee2e2; color: #991b1b; border-color: #fca5a5; }
                        .doc-footer {
                            margin-top: 36px;
                            padding-top: 12px;
                            border-top: 1px solid #e2e8f0;
                            display: flex;
                            justify-content: space-between;
                            font-size: 10px;
                            color: #94a3b8;
                        }
                    </style>
                </head>
                <body>
                    <div class="doc-header">
                        <div>
                            <div class="church-name">LIFE <span>GENERATION</span> COMMUNITY</div>
                            <div class="doc-subtitle">Official Church Membership Directory &amp; Roster</div>
                        </div>
                        <div class="meta-box">
                            <div><strong>Date Generated:</strong> ${printDate}</div>
                            <div><strong>Generated By:</strong> Administrator</div>
                            <div><strong>Document:</strong> Member Directory</div>
                        </div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-card">
                            <div class="summary-label">Total Members Listed</div>
                            <div class="summary-val">${totalRows}</div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-label">Report Type</div>
                            <div class="summary-val" style="font-size:14px; padding-top:4px;">Church Roster</div>
                        </div>
                        <div class="summary-card">
                            <div class="summary-label">Classification</div>
                            <div class="summary-val" style="font-size:14px; padding-top:4px;">Confidential</div>
                        </div>
                    </div>

                    ${tableHtml}

                    <div class="doc-footer">
                        <div>Life Generation Christian Community &bull; Official Administrative Document</div>
                        <div>Confidential &bull; For Church Use Only &bull; ${printDate}</div>
                    </div>

                    <script>
                        window.onload = function() { window.focus(); window.print(); };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
            document.title = originalTitle;
        }

        // Filter Volunteer Applications Table
        function filterVolunteerTable() {
            const searchInput  = document.getElementById('volunteerSearchInput');
            const statusSelect = document.getElementById('volunteerStatusFilter');

            const query  = searchInput ? searchInput.value.toLowerCase().trim() : '';
            const status = statusSelect ? statusSelect.value.toLowerCase().trim() : '';

            const rows = document.querySelectorAll('.volunteer-row');
            rows.forEach(row => {
                const rowSearch = row.getAttribute('data-search') || '';
                const rowStatus = row.getAttribute('data-status') || '';

                const matchesQuery  = !query || rowSearch.includes(query);
                const matchesStatus = !status || rowStatus === status;

                if (matchesQuery && matchesStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Print & Save as PDF for Volunteer Applications
        function printVolunteerRequestsDoc() {
            const originalTitle = document.title;
            const printDate = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
            document.title = 'Life_Generation_Volunteer_Applications_' + printDate.replace(/\s+/g, '_');

            // Open dedicated printable paperwork window for clean paper export/PDF
            const tableEl = document.getElementById('volunteerApplicationsTable');
            if (!tableEl) {
                window.print();
                return;
            }

            const printWindow = window.open('', '_blank', 'width=950,height=750');
            const tableHtml = tableEl.outerHTML;

            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>Life Generation - Volunteer Applications Report</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            margin: 30px 40px;
                            color: #1e293b;
                        }
                        .doc-header {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            border-bottom: 2px solid #ea580c;
                            padding-bottom: 12px;
                            margin-bottom: 24px;
                        }
                        .doc-title {
                            font-size: 20px;
                            font-weight: 800;
                            color: #0f172a;
                            margin: 0 0 4px;
                        }
                        .doc-sub {
                            font-size: 13px;
                            color: #64748b;
                            margin: 0;
                        }
                        .meta-info {
                            font-size: 12px;
                            color: #475569;
                            text-align: right;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 10px;
                            font-size: 12px;
                        }
                        th, td {
                            border: 1px solid #cbd5e1;
                            padding: 8px 10px;
                            text-align: left;
                        }
                        th {
                            background-color: #f8fafc;
                            font-weight: bold;
                            color: #0f172a;
                        }
                        .tag-status {
                            display: inline-block;
                            padding: 2px 6px;
                            border-radius: 4px;
                            font-size: 10px;
                            font-weight: bold;
                            border: 1px solid #94a3b8;
                        }
                        .hide-on-print {
                            display: none !important;
                        }
                        .doc-footer {
                            margin-top: 40px;
                            padding-top: 12px;
                            border-top: 1px solid #e2e8f0;
                            display: flex;
                            justify-content: space-between;
                            font-size: 11px;
                            color: #94a3b8;
                        }
                    </style>
                </head>
                <body>
                    <div class="doc-header">
                        <div>
                            <div class="doc-title">LIFE GENERATION COMMUNITY</div>
                            <p class="doc-sub">Volunteer Ministry Applications & Paperwork Roster</p>
                        </div>
                        <div class="meta-info">
                            <div><strong>Date Generated:</strong> ${printDate}</div>
                            <div><strong>Generated By:</strong> Administrator</div>
                        </div>
                    </div>

                    ${tableHtml}

                    <div class="doc-footer">
                        <div>Life Generation Community Official Document</div>
                        <div>Confidential &bull; Ministry Operations Record</div>
                    </div>

                    <script>
                        window.onload = function() {
                            window.focus();
                            window.print();
                        };
                    <\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
            document.title = originalTitle;
        }

        // Copy Member ID to Clipboard
        function copyMemberId() {
            const idTextEl = document.getElementById('memberIdText');
            if (!idTextEl) return;
            const idText = idTextEl.innerText.trim();
            const btn    = document.getElementById('copyBtn');
            navigator.clipboard.writeText(idText).then(() => {
                if (btn) {
                    btn.innerHTML = '<span>Copied!</span>';
                    btn.style.background = '#16a34a';
                    setTimeout(() => {
                        btn.innerHTML = '<svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg><span>Copy</span>';
                        btn.style.background = '';
                    }, 2000);
                }
            }).catch(() => {
                prompt('Copy Member ID:', idText);
            });
        }

        // Open Edit Event Modal and populate fields
        function openEditEventModal(eventData) {
            const modal = document.getElementById('editEventModal');
            if (!modal) return;

            document.getElementById('edit_ev_id').value       = eventData.id || '';
            document.getElementById('edit_ev_title').value    = eventData.title || '';
            document.getElementById('edit_ev_location').value = eventData.location || '';
            document.getElementById('edit_ev_date').value     = eventData.event_date || '';
            document.getElementById('edit_ev_time').value     = eventData.event_time || '';
            document.getElementById('edit_ev_cap').value      = eventData.capacity || '';
            document.getElementById('edit_ev_desc').value     = eventData.description || '';

            const previewContainer = document.getElementById('editBannerPreviewContainer');
            const previewImg       = document.getElementById('editEventBannerPreview');
            const placeholder      = document.getElementById('editBannerPlaceholderText');

            if (eventData.banner_image) {
                previewImg.src = "<?= site_url('') ?>" + eventData.banner_image.replace(/^\//, '');
                previewContainer.style.display = 'block';
                if (placeholder) placeholder.style.display = 'none';
            } else {
                previewContainer.style.display = 'none';
                if (placeholder) placeholder.style.display = 'block';
            }

            modal.style.display = 'flex';
        }

        function closeEditEventModal() {
            const modal = document.getElementById('editEventModal');
            if (modal) modal.style.display = 'none';
        }

        // Live Preview for Edit Event Flyer
        function previewEditEventBanner() {
            const input = document.getElementById('editEventBannerInput');
            const previewContainer = document.getElementById('editBannerPreviewContainer');
            const previewImg = document.getElementById('editEventBannerPreview');
            const placeholder = document.getElementById('editBannerPlaceholderText');

            if (input && input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (previewImg) previewImg.src = e.target.result;
                    if (previewContainer) previewContainer.style.display = 'block';
                    if (placeholder) placeholder.style.display = 'none';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Document Ready Initializer
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Generate QR Code on Member Card
            const memberId = "<?= addslashes($member_id_code) ?>";
            const qrCanvas = document.getElementById('memberQrCanvas');
            if (qrCanvas && window.QRCode) {
                try {
                    QRCode.toCanvas(qrCanvas, memberId, {
                        width: 58,
                        margin: 1,
                        color: { dark: '#0f172a', light: '#ffffff' }
                    });
                } catch (e) {
                    console.log('QR Code generation notice:', e);
                }
            }

            // 2. Avatar Drag & Drop zone
            const avatarDropZone  = document.getElementById('avatarDropZone');
            const avatarInput     = document.getElementById('avatarInput');
            if (avatarDropZone && avatarInput) {
                ['dragenter', 'dragover'].forEach(eventName => {
                    avatarDropZone.addEventListener(eventName, (e) => {
                        e.preventDefault(); e.stopPropagation();
                        avatarDropZone.classList.add('drag-over');
                    }, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    avatarDropZone.addEventListener(eventName, (e) => {
                        e.preventDefault(); e.stopPropagation();
                        avatarDropZone.classList.remove('drag-over');
                    }, false);
                });

                avatarDropZone.addEventListener('drop', (e) => {
                    if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                        try {
                            avatarInput.files = e.dataTransfer.files;
                            submitAvatarForm();
                        } catch (err) {
                            const dt = new DataTransfer();
                            dt.items.add(e.dataTransfer.files[0]);
                            avatarInput.files = dt.files;
                            submitAvatarForm();
                        }
                    }
                });
            }

            // 3. Event Banner Flyer Drag & Drop zone (Create)
            const eventDropZone = document.getElementById('eventBannerDropZone');
            const eventBannerInput = document.getElementById('eventBannerInput');
            if (eventDropZone && eventBannerInput) {
                ['dragenter', 'dragover'].forEach(eventName => {
                    eventDropZone.addEventListener(eventName, (e) => {
                        e.preventDefault(); e.stopPropagation();
                        eventDropZone.classList.add('drag-over');
                    }, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    eventDropZone.addEventListener(eventName, (e) => {
                        e.preventDefault(); e.stopPropagation();
                        eventDropZone.classList.remove('drag-over');
                    }, false);
                });

                eventDropZone.addEventListener('drop', (e) => {
                    if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                        try {
                            eventBannerInput.files = e.dataTransfer.files;
                            previewEventBanner();
                        } catch (err) {
                            const dt = new DataTransfer();
                            dt.items.add(e.dataTransfer.files[0]);
                            eventBannerInput.files = dt.files;
                            previewEventBanner();
                        }
                    }
                });
            }

            // 4. Edit Event Flyer Drag & Drop zone
            const editEventDropZone = document.getElementById('editEventBannerDropZone');
            const editEventBannerInput = document.getElementById('editEventBannerInput');
            if (editEventDropZone && editEventBannerInput) {
                ['dragenter', 'dragover'].forEach(eventName => {
                    editEventDropZone.addEventListener(eventName, (e) => {
                        e.preventDefault(); e.stopPropagation();
                        editEventDropZone.classList.add('drag-over');
                    }, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    editEventDropZone.addEventListener(eventName, (e) => {
                        e.preventDefault(); e.stopPropagation();
                        editEventDropZone.classList.remove('drag-over');
                    }, false);
                });

                editEventDropZone.addEventListener('drop', (e) => {
                    if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                        try {
                            editEventBannerInput.files = e.dataTransfer.files;
                            previewEditEventBanner();
                        } catch (err) {
                            const dt = new DataTransfer();
                            dt.items.add(e.dataTransfer.files[0]);
                            editEventBannerInput.files = dt.files;
                            previewEditEventBanner();
                        }
                    }
                });
            }

            // 5. Ministry Photo Drag & Drop zone
            const minDropZone = document.getElementById('minPhotoDropZone');
            const minPhotoInput = document.getElementById('minPhotoInput');
            if (minDropZone && minPhotoInput) {
                ['dragenter', 'dragover'].forEach(eventName => {
                    minDropZone.addEventListener(eventName, (e) => {
                        e.preventDefault(); e.stopPropagation();
                        minDropZone.classList.add('drag-over');
                    }, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    minDropZone.addEventListener(eventName, (e) => {
                        e.preventDefault(); e.stopPropagation();
                        minDropZone.classList.remove('drag-over');
                    }, false);
                });

                minDropZone.addEventListener('drop', (e) => {
                    if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                        try {
                            minPhotoInput.files = e.dataTransfer.files;
                            previewMinistryPhoto();
                        } catch (err) {
                            const dt = new DataTransfer();
                            dt.items.add(e.dataTransfer.files[0]);
                            minPhotoInput.files = dt.files;
                            previewMinistryPhoto();
                        }
                    }
                });
            }

            // 6. D-Group Photo Drag & Drop zone (Create)
            const dgDropZone = document.getElementById('dgroupPhotoDropZone');
            const dgPhotoInput = document.getElementById('dgPhotoInput');
            if (dgDropZone && dgPhotoInput) {
                ['dragenter', 'dragover'].forEach(eventName => {
                    dgDropZone.addEventListener(eventName, (e) => {
                        e.preventDefault(); e.stopPropagation();
                        dgDropZone.classList.add('drag-over');
                    }, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dgDropZone.addEventListener(eventName, (e) => {
                        e.preventDefault(); e.stopPropagation();
                        dgDropZone.classList.remove('drag-over');
                    }, false);
                });

                dgDropZone.addEventListener('drop', (e) => {
                    if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                        try {
                            dgPhotoInput.files = e.dataTransfer.files;
                            previewDgroupPhoto();
                        } catch (err) {
                            const dt = new DataTransfer();
                            dt.items.add(e.dataTransfer.files[0]);
                            dgPhotoInput.files = dt.files;
                            previewDgroupPhoto();
                        }
                    }
                });
            }

            // 7. Edit D-Group Photo Drag & Drop zone
            const editDgDropZone = document.getElementById('editDgroupPhotoDropZone');
            const editDgPhotoInput = document.getElementById('editDgPhotoInput');
            if (editDgDropZone && editDgPhotoInput) {
                ['dragenter', 'dragover'].forEach(eventName => {
                    editDgDropZone.addEventListener(eventName, (e) => {
                        e.preventDefault(); e.stopPropagation();
                        editDgDropZone.classList.add('drag-over');
                    }, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    editDgDropZone.addEventListener(eventName, (e) => {
                        e.preventDefault(); e.stopPropagation();
                        editDgDropZone.classList.remove('drag-over');
                    }, false);
                });

                editDgDropZone.addEventListener('drop', (e) => {
                    if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length > 0) {
                        try {
                            editDgPhotoInput.files = e.dataTransfer.files;
                            previewEditDgroupPhoto();
                        } catch (err) {
                            const dt = new DataTransfer();
                            dt.items.add(e.dataTransfer.files[0]);
                            editDgPhotoInput.files = dt.files;
                            previewEditDgroupPhoto();
                        }
                    }
                });
            }

            // 8. Restore active tab
            const savedTab = sessionStorage.getItem('activeDashboardTab');
            if (savedTab) {
                const btn = document.querySelector(`[onclick*="'${savedTab}'"]`) || document.querySelector(`[onclick*="${savedTab}"]`);
                if (btn) {
                    showTab(savedTab, btn);
                }
            }
        });
    </script>

    <!-- ── Logout Confirmation Modal ── -->
    <div id="logoutConfirmModal" class="modal-overlay" style="display:none;" onclick="if(event.target===this) closeLogoutConfirm()">
        <div class="modal-card" style="max-width:400px; text-align:center;">
            <!-- Icon -->
            <div style="margin: 0 auto 16px; width:60px; height:60px; border-radius:50%; background:#fff1f2; border:2px solid #fecdd3; display:flex; align-items:center; justify-content:center;">
                <svg width="26" height="26" fill="none" stroke="#e11d48" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </div>

            <div style="font-size:1.15rem; font-weight:800; color:var(--text-primary); margin-bottom:8px;">Sign Out?</div>
            <p style="font-size:0.875rem; color:var(--text-muted); line-height:1.6; margin-bottom:24px;">
                Are you sure you want to sign out of your<br>Life Generation member account?
            </p>

            <div style="display:flex; gap:10px; justify-content:center;">
                <button type="button" onclick="closeLogoutConfirm()" style="flex:1; padding:10px 16px; border-radius:var(--radius-md); border:1px solid var(--card-border); background:transparent; color:var(--text-secondary); font-size:0.88rem; font-weight:600; cursor:pointer; transition:all 0.15s;">
                    Cancel
                </button>
                <a href="<?= site_url('logout') ?>" style="flex:1; padding:10px 16px; border-radius:var(--radius-md); background:linear-gradient(135deg,#e11d48,#be123c); color:#ffffff; font-size:0.88rem; font-weight:700; text-decoration:none; display:flex; align-items:center; justify-content:center; gap:7px; transition:all 0.15s; box-shadow:0 2px 8px rgba(225,29,72,0.3);">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Yes, Sign Out
                </a>
            </div>
        </div>
    </div>

    <script>
        function openLogoutConfirm() {
            const modal = document.getElementById('logoutConfirmModal');
            if (modal) modal.style.display = 'flex';
        }
        function closeLogoutConfirm() {
            const modal = document.getElementById('logoutConfirmModal');
            if (modal) modal.style.display = 'none';
        }
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLogoutConfirm();
        });

        // ── Light / Dark Mode Toggle Logic ──
        function updateThemeUI(theme) {
            const btn = document.getElementById('themeToggleBtn');
            if (!btn) return;
            const sunIcon = btn.querySelector('.theme-icon-sun');
            const moonIcon = btn.querySelector('.theme-icon-moon');
            if (theme === 'dark') {
                if (sunIcon) sunIcon.style.display = 'block';
                if (moonIcon) moonIcon.style.display = 'none';
                btn.setAttribute('title', 'Switch to Light Mode');
            } else {
                if (sunIcon) sunIcon.style.display = 'none';
                if (moonIcon) moonIcon.style.display = 'block';
                btn.setAttribute('title', 'Switch to Dark Mode');
            }
        }

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            try {
                localStorage.setItem('theme', newTheme);
            } catch(e) {}
            updateThemeUI(newTheme);
        }

        // Initialize UI icon on DOMContentLoaded
        document.addEventListener('DOMContentLoaded', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            updateThemeUI(currentTheme);
        });

        // ── Mobile Sidebar Drawer Logic ──
        function toggleSidebarMenu() {
            const sidebar = document.getElementById('mainSidebar');
            const backdrop = document.getElementById('mobileSidebarBackdrop');
            if (!sidebar) return;
            const isOpen = sidebar.classList.contains('mobile-open');
            if (isOpen) {
                closeSidebarMenu();
            } else {
                sidebar.classList.add('mobile-open');
                if (backdrop) backdrop.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeSidebarMenu() {
            const sidebar = document.getElementById('mainSidebar');
            const backdrop = document.getElementById('mobileSidebarBackdrop');
            if (sidebar) sidebar.classList.remove('mobile-open');
            if (backdrop) backdrop.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close mobile sidebar on navigation button click
        document.querySelectorAll('.sidebar .nav-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                if (window.innerWidth <= 991) {
                    closeSidebarMenu();
                }
            });
        });
    </script>
</body>
</html>