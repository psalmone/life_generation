<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Life Generation - Christian Community & Fellowship' ?></title>
    <meta name="description" content="Welcome to Life Generation Christian Community. A place of worship, discipleship, genuine fellowship, and spiritual growth.">
    <link rel="icon" type="image/jpeg" href="<?= site_url('assets/images/lifegen-logo.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            /* ── Minimal Pastel Palette (Orange, Soft Blue, Crisp White) ── */
            --bg-body:            #fafaf9;
            --bg-surface:         #ffffff;
            --bg-soft:            #f5f5f4;
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

            /* System Tokens */
            --primary:            var(--orange-primary);
            --primary-light:      var(--orange-soft);
            --primary-gradient:   linear-gradient(135deg, #fb923c 0%, #f97316 100%);
            --blue-gradient:      linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
            
            /* Text Hierarchy */
            --text-main:          #1c1917;
            --text-muted:         #78716c;
            --text-light:         #a8a29e;

            /* State Indicators */
            --success:            #10b981;
            --success-light:      #f0fdf4;
            --success-border:     #dcfce7;
            --danger:             #f43f5e;
            --danger-light:       #fff1f2;

            /* Soft Minimal Shadows */
            --shadow-xs:          0 1px 2px rgba(28, 25, 23, 0.03);
            --shadow-sm:          0 2px 5px rgba(28, 25, 23, 0.04);
            --shadow-md:          0 8px 20px -4px rgba(28, 25, 23, 0.06);
            --shadow-lg:          0 16px 32px -8px rgba(28, 25, 23, 0.08);

            /* Radius */
            --radius-md:          12px;
            --radius-lg:          18px;
            --radius-xl:          24px;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            line-height: 1.6;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
            background-image: 
                radial-gradient(at 0% 0%, rgba(249, 115, 22, 0.03) 0px, transparent 45%),
                radial-gradient(at 100% 0%, rgba(59, 130, 246, 0.03) 0px, transparent 45%),
                radial-gradient(at 50% 100%, rgba(254, 215, 170, 0.035) 0px, transparent 50%);
        }

        /* ── Header / Navigation ── */
        .navbar {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--card-border);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-xs);
        }

        .nav-container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-main);
        }

        .brand-logo {
            width: 42px;
            height: 42px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 2px 8px rgba(249, 115, 22, 0.1);
            border: 1px solid var(--card-border);
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-text h1 {
            font-size: 1.15rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.1;
            color: var(--text-main);
        }

        .brand-text span {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--orange-primary);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 26px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .nav-links a:hover {
            color: var(--orange-primary);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: var(--radius-md);
            font-size: 0.88rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
        }

        .btn-outline {
            background: #ffffff;
            color: var(--text-main);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow-xs);
        }

        .btn-outline:hover {
            background: var(--bg-soft);
            border-color: #e7e5e4;
            color: var(--orange-primary);
            transform: translateY(-1px);
        }

        .btn-primary {
            background: var(--primary-gradient);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(249, 115, 22, 0.25);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #ea580c 0%, #c2410c 100%);
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.35);
            transform: translateY(-1px);
        }

        .btn-blue {
            background: var(--blue-gradient);
            color: #ffffff;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.25);
        }

        .btn-blue:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.35);
            transform: translateY(-1px);
        }

        /* ── Event Notification Feature ── */
        .notif-wrapper {
            position: relative;
            display: inline-block;
        }

        .btn-notif-bell {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: var(--radius-md);
            background: #ffffff;
            border: 1px solid var(--card-border);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: var(--shadow-xs);
        }

        .btn-notif-bell:hover,
        .btn-notif-bell.active {
            background: var(--orange-soft);
            border-color: var(--orange-border);
            color: var(--orange-primary);
            transform: translateY(-1px);
        }

        .notif-badge {
            position: absolute;
            top: -3px;
            right: -3px;
            min-width: 18px;
            height: 18px;
            padding: 0 4px;
            border-radius: 9999px;
            background: var(--orange-primary);
            color: #ffffff;
            font-size: 0.68rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 6px rgba(249, 115, 22, 0.4);
            animation: pulse-badge 2s infinite;
        }

        @keyframes pulse-badge {
            0% { transform: scale(1); }
            50% { transform: scale(1.12); }
            100% { transform: scale(1); }
        }

        .notif-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 360px;
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            box-shadow: 0 14px 34px -6px rgba(28, 25, 23, 0.12), 0 4px 10px rgba(28, 25, 23, 0.04);
            z-index: 1000;
            display: none;
            flex-direction: column;
            overflow: hidden;
            animation: notifSlideDown 0.22s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            transform-origin: top right;
        }

        .notif-dropdown.show {
            display: flex;
        }

        @keyframes notifSlideDown {
            from {
                opacity: 0;
                transform: translateY(-8px) scale(0.96);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .notif-header {
            padding: 14px 18px;
            background: #ffffff;
            border-bottom: 1px solid var(--card-border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .notif-header-title {
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .notif-count-pill {
            font-size: 0.72rem;
            font-weight: 700;
            background: var(--orange-soft);
            color: var(--orange-primary);
            padding: 2px 8px;
            border-radius: 9999px;
            border: 1px solid var(--orange-border);
        }

        .notif-list {
            max-height: 380px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .notif-item {
            padding: 12px 18px;
            border-bottom: 1px solid var(--card-border-subtle);
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            transition: background 0.15s ease;
        }

        .notif-item:last-child {
            border-bottom: none;
        }

        .notif-item:hover {
            background: #fafaf9;
        }

        .notif-item-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--orange-soft);
            color: var(--orange-primary);
            border: 1px solid var(--orange-border);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .notif-item-content {
            flex: 1;
            min-width: 0;
        }

        .notif-item-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.3;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notif-item-meta {
            display: flex;
            flex-direction: column;
            gap: 2px;
            font-size: 0.74rem;
            color: var(--text-muted);
        }

        .notif-item-meta-row {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .notif-footer {
            padding: 10px 18px;
            background: #fafaf9;
            border-top: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .notif-footer a {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--orange-primary);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .notif-footer a:hover {
            text-decoration: underline;
        }

        /* ── Hero Section ── */
        .hero {
            position: relative;
            padding: 70px 24px 80px;
            text-align: center;
            max-width: 1040px;
            margin: 0 auto;
            z-index: 1;
        }

        .scripture-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            background: #ffffff;
            border: 1px solid var(--orange-border);
            border-radius: 9999px;
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--orange-primary);
            margin-bottom: 20px;
            box-shadow: var(--shadow-xs);
        }

        .scripture-pill svg {
            width: 14px;
            height: 14px;
            fill: currentColor;
        }

        .hero-headline {
            font-size: clamp(2.2rem, 4.5vw, 3.4rem);
            font-weight: 800;
            letter-spacing: -0.035em;
            line-height: 1.18;
            color: var(--text-main);
            margin-bottom: 18px;
        }

        .hero-headline span.highlight {
            color: var(--orange-primary);
        }

        .hero-subtext {
            font-size: 1.05rem;
            color: var(--text-muted);
            max-width: 660px;
            margin: 0 auto 32px;
            line-height: 1.65;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 40px;
        }

        .hero-verse-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-left: 4px solid var(--orange-primary);
            border-radius: var(--radius-lg);
            padding: 20px 28px;
            max-width: 720px;
            margin: 0 auto;
            text-align: left;
            box-shadow: var(--shadow-xs);
        }

        .hero-verse-card blockquote {
            font-size: 0.96rem;
            font-style: italic;
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 6px;
        }

        .hero-verse-card cite {
            font-size: 0.82rem;
            font-weight: 800;
            color: var(--orange-primary);
            display: block;
            text-align: right;
            font-style: normal;
        }

        /* ── Stats Banner ── */
        .stats-section {
            background: #ffffff;
            border-top: 1px solid var(--card-border);
            border-bottom: 1px solid var(--card-border);
            padding: 32px 24px;
        }

        .stats-container {
            max-width: 1140px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 24px;
            text-align: center;
        }

        .stat-item h3 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--orange-primary);
            letter-spacing: -0.02em;
            margin-bottom: 2px;
        }

        .stat-item p {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ── Sections Common ── */
        .section {
            padding: 70px 24px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .section-header {
            text-align: center;
            max-width: 640px;
            margin: 0 auto 44px;
        }

        .section-tag {
            font-size: 0.74rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--orange-primary);
            margin-bottom: 6px;
            display: block;
        }

        .section-title {
            font-size: 1.95rem;
            font-weight: 800;
            letter-spacing: -0.025em;
            color: var(--text-main);
            margin-bottom: 10px;
        }

        .section-desc {
            color: var(--text-muted);
            font-size: 0.94rem;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        /* ── Item Cards Minimal ── */
        .item-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-shadow: var(--shadow-xs);
            transition: all 0.2s ease;
        }

        .item-card:hover {
            transform: translateY(-3px);
            border-color: var(--orange-border);
            box-shadow: var(--shadow-md);
        }

        .card-top-tag {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 700;
            background: var(--orange-soft);
            color: var(--orange-primary);
            margin-bottom: 12px;
            align-self: flex-start;
        }

        .card-top-tag.blue {
            background: var(--blue-soft);
            color: var(--blue-primary);
        }

        .item-card h3 {
            font-size: 1.12rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.01em;
            margin-bottom: 8px;
        }

        .item-card p {
            font-size: 0.86rem;
            color: var(--text-muted);
            line-height: 1.55;
            margin-bottom: 18px;
        }

        .item-meta-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 18px;
        }

        .item-meta-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .item-meta-row svg {
            color: var(--orange-primary);
            flex-shrink: 0;
        }

        /* ── Prayer Request Wall Cards ── */
        .prayer-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 22px;
            box-shadow: var(--shadow-xs);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.2s ease;
        }

        .prayer-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--blue-border);
        }

        .prayer-user-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .prayer-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: var(--blue-soft);
            color: var(--blue-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.82rem;
            border: 1px solid var(--blue-border);
        }

        .prayer-name {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--text-main);
        }

        .prayer-date {
            font-size: 0.72rem;
            color: var(--text-light);
        }

        .prayer-title {
            font-size: 1rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .prayer-body {
            font-size: 0.85rem;
            color: var(--text-muted);
            line-height: 1.55;
            margin-bottom: 14px;
        }

        .prayer-tag-status {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .prayer-tag-status.answered {
            background: var(--success-light);
            color: #047857;
        }

        .prayer-tag-status.praying {
            background: var(--blue-soft);
            color: var(--blue-primary);
        }

        /* ── Call to Action Banner ── */
        .cta-banner {
            background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
            border-radius: var(--radius-xl);
            padding: 48px 32px;
            color: #ffffff;
            text-align: center;
            max-width: 1140px;
            margin: 0 auto 60px;
            box-shadow: var(--shadow-lg);
        }

        .cta-banner h2 {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 10px;
            letter-spacing: -0.02em;
        }

        .cta-banner p {
            font-size: 0.98rem;
            color: #ffedd5;
            max-width: 580px;
            margin: 0 auto 24px;
            line-height: 1.6;
        }

        .cta-buttons {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-cta-white {
            background: #ffffff;
            color: var(--orange-primary);
            padding: 10px 22px;
            border-radius: var(--radius-md);
            font-weight: 800;
            text-decoration: none;
            font-size: 0.9rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: all 0.2s ease;
        }

        .btn-cta-white:hover {
            transform: translateY(-2px);
            background: #fafaf9;
        }

        .btn-cta-ghost {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 10px 20px;
            border-radius: var(--radius-md);
            font-weight: 700;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        .btn-cta-ghost:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        /* ── Footer ── */
        .footer {
            background: #ffffff;
            border-top: 1px solid var(--card-border);
            padding: 50px 24px 28px;
            margin-top: auto;
        }

        .footer-container {
            max-width: 1140px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1.2fr;
            gap: 32px;
            margin-bottom: 40px;
        }

        .footer-col h4 {
            font-size: 0.82rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-main);
            margin-bottom: 16px;
        }

        .footer-col p {
            font-size: 0.86rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.86rem;
            transition: color 0.18s ease;
        }

        .footer-links a:hover {
            color: var(--orange-primary);
        }

        .footer-bottom {
            max-width: 1140px;
            margin: 0 auto;
            padding-top: 20px;
            border-top: 1px solid var(--card-border-subtle);
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: var(--text-light);
            font-size: 0.78rem;
            flex-wrap: wrap;
            gap: 12px;
        }

        @media (max-width: 900px) {
            .footer-container { grid-template-columns: 1fr 1fr; }
            .nav-links { display: none; }
        }

        @media (max-width: 600px) {
            .footer-container { grid-template-columns: 1fr; }
            .hero { padding: 48px 16px 50px; }
            .cta-banner { padding: 36px 20px; }
        }
    </style>
</head>
<body>

    <!-- ── Navbar ── -->
    <header class="navbar">
        <div class="nav-container">
            <a href="<?= site_url('') ?>" class="brand">
                <div class="brand-logo">
                    <img src="<?= site_url('assets/images/lifegen-logo.png') ?>" alt="Life Generation Logo">
                </div>
                <div class="brand-text">
                    <h1>Life Generation</h1>
                    <span>Christian Fellowship</span>
                </div>
            </a>

            <ul class="nav-links">
                <li><a href="#events">Events</a></li>
                <li><a href="#dgroups">D-Groups</a></li>
                <li><a href="#ministries">Ministries</a></li>
                <li><a href="#prayers">Prayer Wall</a></li>
                <li><a href="#about">About</a></li>
            </ul>

            <div class="nav-actions">
                <?php if (!empty($is_logged_in)): ?>
                    <!-- Event Notification Bell Feature -->
                    <?php 
                        $event_notifications = !empty($notif_events) ? $notif_events : (!empty($upcoming_events) ? $upcoming_events : []);
                        $notif_count = count($event_notifications);
                    ?>
                    <div class="notif-wrapper" id="notifWrapper">
                        <button type="button" class="btn-notif-bell" id="notifBellBtn" aria-label="Event Notifications" title="Upcoming Events Notification" onclick="toggleEventNotifs(event)">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <?php if ($notif_count > 0): ?>
                                <span class="notif-badge"><?= $notif_count ?></span>
                            <?php endif; ?>
                        </button>

                        <!-- Notification Dropdown -->
                        <div class="notif-dropdown" id="notifDropdown" onclick="event.stopPropagation()">
                            <div class="notif-header">
                                <div class="notif-header-title">
                                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--orange-primary);">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Upcoming Events</span>
                                </div>
                                <span class="notif-count-pill"><?= $notif_count ?> New</span>
                            </div>

                            <div class="notif-list">
                                <?php if (!empty($event_notifications)): ?>
                                    <?php foreach ($event_notifications as $nev): ?>
                                        <a href="<?= site_url('dashboard') ?>" class="notif-item">
                                            <div class="notif-item-icon">
                                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <div class="notif-item-content">
                                                <div class="notif-item-title"><?= htmlspecialchars($nev['title'] ?? 'Church Event') ?></div>
                                                <div class="notif-item-meta">
                                                    <div class="notif-item-meta-row">
                                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--orange-primary);">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span><?= !empty($nev['event_date']) ? date('M d, Y', strtotime($nev['event_date'])) : 'Upcoming' ?> &bull; <?= htmlspecialchars($nev['event_time'] ?? 'TBA') ?></span>
                                                    </div>
                                                    <div class="notif-item-meta-row">
                                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--orange-primary);">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        </svg>
                                                        <span><?= htmlspecialchars($nev['location'] ?? 'Main Sanctuary') ?></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div style="padding:24px 18px; text-align:center; color:var(--text-muted); font-size:0.85rem;">
                                        No upcoming events right now. Check back soon!
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="notif-footer">
                                <a href="#events" onclick="closeEventNotifs()">
                                    <span>Browse on page</span>
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                </a>
                                <a href="<?= site_url('dashboard') ?>">
                                    <span>RSVP in Portal &rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <a href="<?= site_url('dashboard') ?>" class="btn btn-primary">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>Member Portal</span>
                    </a>
                    <a href="<?= site_url('logout') ?>" class="btn btn-outline">Sign Out</a>
                <?php else: ?>
                    <a href="<?= site_url('login') ?>" class="btn btn-outline">Sign In</a>
                    <a href="<?= site_url('register') ?>" class="btn btn-primary">Join Community</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- ── Hero Section ── -->
    <section class="hero">
        <div class="scripture-pill">
            <svg viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
            <span>1 Peter 2:9 &bull; A Chosen Generation &bull; Called Into His Light</span>
        </div>

        <h1 class="hero-headline">
            Empowering Generations Through a <span class="highlight">Kindled Passion in Christ</span>
        </h1>

        <p class="hero-subtext">
            Welcome to Life Generation Christian Community. We are a family rooted in Scripture, passionate in worship, and dedicated to raising generations filled with faith, hope, and love.
        </p>

        <div class="hero-actions">
            <?php if (!empty($is_logged_in)): ?>
                <a href="<?= site_url('dashboard') ?>" class="btn btn-primary" style="padding:12px 24px; font-size:0.95rem;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                    <span>View Your Member Card</span>
                </a>
            <?php else: ?>
                <a href="<?= site_url('register') ?>" class="btn btn-primary" style="padding:12px 24px; font-size:0.95rem;">
                    <span>Register for Membership</span>
                </a>
                <a href="<?= site_url('login') ?>" class="btn btn-outline" style="padding:12px 24px; font-size:0.95rem;">
                    <span>Access Member Portal &rarr;</span>
                </a>
            <?php endif; ?>
            <a href="<?= site_url('prayer-wall') ?>" class="btn btn-blue" style="padding:12px 20px; font-size:0.95rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span>Prayer Wall</span>
            </a>
        </div>

        <div class="hero-verse-card">
            <blockquote>
                "I came that they may have life, and have it abundantly. A new commandment I give to you, that you love one another: just as I have loved you, you also are to love one another."
            </blockquote>
            <cite>— John 10:10, John 13:34</cite>
        </div>
    </section>

    <!-- ── Stats Banner ── -->
    <section class="stats-section">
        <div class="stats-container">
            <div class="stat-item">
                <h3><?= isset($total_members) && $total_members > 0 ? htmlspecialchars($total_members) : '150+' ?></h3>
                <p>Registered Members</p>
            </div>
            <div class="stat-item">
                <h3>4+</h3>
                <p>Active Ministries</p>
            </div>
            <div class="stat-item">
                <h3>Weekly</h3>
                <p>D-Group Gatherings</p>
            </div>
            <div class="stat-item">
                <h3>100%</h3>
                <p>Christ-Centered Mission</p>
            </div>
        </div>
    </section>

    <!-- ── SECTION 1: CHURCH EVENTS & CELEBRATIONS ── -->
    <section id="events" class="section">
        <div class="section-header">
            <span class="section-tag">Gather With Us</span>
            <h2 class="section-title">Upcoming Church Events</h2>
            <p class="section-desc">Join our Sunday celebration services, youth fellowships, and special conferences.</p>
        </div>

        <?php if (!empty($upcoming_events)): ?>
            <div class="cards-grid">
                <?php foreach ($upcoming_events as $ev): ?>
                    <div class="item-card">
                        <div>
                            <?php if (!empty($ev['banner_image'])): ?>
                                <div style="margin:-24px -24px 16px -24px; max-height:140px; overflow:hidden; border-top-left-radius:var(--radius-lg); border-top-right-radius:var(--radius-lg);">
                                    <img src="<?= site_url($ev['banner_image']) ?>" alt="Event Flyer" style="width:100%; height:140px; object-fit:cover; display:block;">
                                </div>
                            <?php endif; ?>

                            <span class="card-top-tag">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span><?= date('M j, Y', strtotime($ev['event_date'])) ?></span>
                            </span>

                            <h3><?= htmlspecialchars($ev['title']) ?></h3>

                            <div class="item-meta-list">
                                <div class="item-meta-row">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span><?= htmlspecialchars($ev['event_time']) ?></span>
                                </div>
                                <div class="item-meta-row">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span><?= htmlspecialchars($ev['location']) ?></span>
                                </div>
                            </div>

                            <?php if (!empty($ev['description'])): ?>
                                <p><?= htmlspecialchars(substr($ev['description'], 0, 110)) ?><?= strlen($ev['description']) > 110 ? '...' : '' ?></p>
                            <?php endif; ?>
                        </div>

                        <div style="margin-top:auto; padding-top:12px; border-top:1px solid var(--card-border-subtle);">
                            <a href="<?= site_url('dashboard') ?>" class="btn btn-primary" style="width:100%;">
                                <span>RSVP in Member Portal</span>
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align:center; padding:40px 20px; background:#ffffff; border-radius:var(--radius-lg); border:1px solid var(--card-border); color:var(--text-muted);">
                Check back soon for upcoming fellowship and Sunday worship schedules.
            </div>
        <?php endif; ?>
    </section>

    <!-- ── SECTION 2: DISCIPLESHIP GROUPS (D-GROUPS) ── -->
    <section id="dgroups" class="section" style="padding-top:0;">
        <div class="section-header">
            <span class="section-tag">Spiritual Family</span>
            <h2 class="section-title">Discipleship Groups (D-Groups)</h2>
            <p class="section-desc">Find community, genuine accountability, and biblical growth in a small group setting.</p>
        </div>

        <?php if (!empty($dgroups)): ?>
            <div class="cards-grid">
                <?php foreach ($dgroups as $dg): ?>
                    <div class="item-card">
                        <div>
                            <span class="card-top-tag blue">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <span><?= (int)$dg['member_count'] ?> Active Members</span>
                            </span>

                            <h3><?= htmlspecialchars($dg['name']) ?></h3>

                            <div class="item-meta-list">
                                <div class="item-meta-row">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <span>Leader: <strong><?= htmlspecialchars($dg['leader_name']) ?></strong></span>
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
                                <p><?= htmlspecialchars(substr($dg['description'], 0, 100)) ?><?= strlen($dg['description']) > 100 ? '...' : '' ?></p>
                            <?php endif; ?>
                        </div>

                        <div style="margin-top:auto; padding-top:12px; border-top:1px solid var(--card-border-subtle);">
                            <a href="<?= site_url('dashboard') ?>" class="btn btn-outline" style="width:100%;">
                                <span>Join in Member Portal</span>
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align:center; padding:40px 20px; background:#ffffff; border-radius:var(--radius-lg); border:1px solid var(--card-border); color:var(--text-muted);">
                Discipleship Groups are enrolling now. Sign in to find a group near you.
            </div>
        <?php endif; ?>
    </section>

    <!-- ── SECTION 3: CHURCH MINISTRIES ── -->
    <section id="ministries" class="section" style="padding-top:0;">
        <div class="section-header">
            <span class="section-tag">Serve With Us</span>
            <h2 class="section-title">Church Ministries & Teams</h2>
            <p class="section-desc">Discover active ministry branches designed to uplift families, students, and church worship.</p>
        </div>

        <?php if (!empty($ministries)): ?>
            <div class="cards-grid">
                <?php foreach ($ministries as $min): ?>
                    <div class="item-card">
                        <div>
                            <?php if (!empty($min['image'])): ?>
                                <div style="margin:-24px -24px 16px -24px; max-height:140px; overflow:hidden; border-top-left-radius:var(--radius-lg); border-top-right-radius:var(--radius-lg);">
                                    <img src="<?= site_url($min['image']) ?>" alt="Ministry Team" style="width:100%; height:140px; object-fit:cover; display:block;">
                                </div>
                            <?php endif; ?>

                            <span class="card-top-tag">
                                <span style="font-size:0.9rem; margin-right:2px;"><?= htmlspecialchars($min['icon'] ?? '✦') ?></span>
                                <span>Ministry Team</span>
                            </span>

                            <h3><?= htmlspecialchars($min['name']) ?></h3>

                            <?php if (!empty($min['description'])): ?>
                                <p><?= htmlspecialchars($min['description']) ?></p>
                            <?php endif; ?>
                        </div>

                        <div style="margin-top:auto; padding-top:12px; border-top:1px solid var(--card-border-subtle);">
                            <a href="<?= site_url('dashboard') ?>" class="btn btn-outline" style="width:100%;">
                                <span>Volunteer in Portal</span>
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- ── SECTION 4: PRAYER WALL ── -->
    <section id="prayers" class="section" style="padding-top:0;">
        <div class="section-header">
            <span class="section-tag">Intercession & Faith</span>
            <h2 class="section-title">Community Prayer Wall</h2>
            <p class="section-desc">Stand together in faith and prayer with our brothers and sisters in Christ.</p>
        </div>

        <?php if (!empty($prayer_requests)): ?>
            <div class="cards-grid" style="margin-bottom:28px;">
                <?php foreach ($prayer_requests as $pr): ?>
                    <div class="prayer-card">
                        <div>
                            <div class="prayer-user-header">
                                <div class="prayer-avatar">
                                    <?= strtoupper(substr($pr['name'] ?? 'M', 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="prayer-name"><?= htmlspecialchars($pr['name'] ?? 'Fellow Believer') ?></div>
                                    <div class="prayer-date"><?= date('M j, Y', strtotime($pr['created_at'])) ?></div>
                                </div>
                            </div>

                            <div class="prayer-title"><?= htmlspecialchars($pr['title']) ?></div>
                            <div class="prayer-body"><?= htmlspecialchars(substr($pr['body'] ?? $pr['request'] ?? '', 0, 140)) ?>...</div>
                        </div>

                        <div style="display:flex; align-items:center; justify-content:space-between; padding-top:10px; border-top:1px solid var(--card-border-subtle);">
                            <span class="prayer-tag-status <?= ($pr['status'] === 'answered') ? 'answered' : 'praying' ?>">
                                <?= ($pr['status'] === 'answered') ? 'Answered Praise ✓' : 'Praying Together' ?>
                            </span>
                            <a href="<?= site_url('prayer-wall') ?>" style="font-size:0.78rem; color:var(--orange-primary); font-weight:700; text-decoration:none;">
                                Pray Along &rarr;
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div style="text-align:center;">
            <a href="<?= site_url('prayer-wall') ?>" class="btn btn-blue" style="padding:12px 24px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span>Visit the Full Prayer Wall & Post a Request</span>
            </a>
        </div>
    </section>

    <!-- ── Call to Action Banner ── -->
    <section class="cta-banner" id="about">
        <h2>You Are Always Welcome Here</h2>
        <p>Take your next step in faith. Register for membership to receive your digital member ID card and get connected with our ministries.</p>
        <div class="cta-buttons">
            <?php if (empty($is_logged_in)): ?>
                <a href="<?= site_url('register') ?>" class="btn-cta-white">Join the Community</a>
                <a href="<?= site_url('login') ?>" class="btn-cta-ghost">Member Sign In</a>
            <?php else: ?>
                <a href="<?= site_url('dashboard') ?>" class="btn-cta-white">Access Member Dashboard</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- ── Footer ── -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-col">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
                    <div class="brand-logo" style="width:34px; height:34px;">
                        <img src="<?= site_url('assets/images/lifegen-logo.png') ?>" alt="Life Generation Logo">
                    </div>
                    <h3 style="font-size:1.05rem; font-weight:800; color:var(--text-main);">Life Generation</h3>
                </div>
                <p>
                    A Christ-centered community devoted to worship, discipleship, and generation-spanning transformation through the Gospel.
                </p>
            </div>

            <div class="footer-col">
                <h4>Quick Navigation</h4>
                <ul class="footer-links">
                    <li><a href="#events">Church Events</a></li>
                    <li><a href="#dgroups">Discipleship Groups</a></li>
                    <li><a href="#ministries">Ministries</a></li>
                    <li><a href="#prayers">Prayer Wall</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Member Portal</h4>
                <ul class="footer-links">
                    <li><a href="<?= site_url('login') ?>">Member Sign In</a></li>
                    <li><a href="<?= site_url('register') ?>">Join Fellowship</a></li>
                    <li><a href="<?= site_url('dashboard') ?>">Digital ID Pass</a></li>
                    <li><a href="<?= site_url('prayer-wall') ?>">Prayer Wall</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Fellowship Center</h4>
                <p style="margin-bottom:6px;">Life Generation Sanctuary, Oriental Mindoro</p>
                <p style="color:var(--orange-primary); font-weight:700;">info@lifegeneration.org</p>
            </div>
        </div>

        <div class="footer-bottom">
            <div>&copy; <?= date('Y') ?> Life Generation Community. All rights reserved.</div>
            <div>"For where two or three gather in my name, there am I with them." — Matthew 18:20</div>
        </div>
    </footer>

    <script>
        function toggleEventNotifs(e) {
            if (e) e.stopPropagation();
            const dropdown = document.getElementById('notifDropdown');
            const btn = document.getElementById('notifBellBtn');
            if (dropdown) {
                const isOpen = dropdown.classList.contains('show');
                if (isOpen) {
                    dropdown.classList.remove('show');
                    if (btn) btn.classList.remove('active');
                } else {
                    dropdown.classList.add('show');
                    if (btn) btn.classList.add('active');
                }
            }
        }

        function closeEventNotifs() {
            const dropdown = document.getElementById('notifDropdown');
            const btn = document.getElementById('notifBellBtn');
            if (dropdown) dropdown.classList.remove('show');
            if (btn) btn.classList.remove('active');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const wrapper = document.getElementById('notifWrapper');
            if (wrapper && !wrapper.contains(event.target)) {
                closeEventNotifs();
            }
        });

        // Close dropdown on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEventNotifs();
            }
        });
    </script>
</body>
</html>