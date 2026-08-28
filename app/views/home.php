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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Cinzel:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-body: #f8fafc;
            --bg-surface: #ffffff;
            --bg-soft: #f1f5f9;
            --primary: #2563eb;
            --primary-light: #3b82f6;
            --primary-subtle: #eff6ff;
            --amber-gold: #d97706;
            --amber-soft: #fffbeb;
            --amber-border: #fef3c7;
            --gold-gradient: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -2px rgba(0,0,0,0.05);
            --shadow-lg: 0 12px 28px -4px rgba(0,0,0,0.08), 0 6px 12px -4px rgba(0,0,0,0.04);
            --shadow-xl: 0 20px 35px -6px rgba(15, 23, 42, 0.09);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            line-height: 1.6;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Ambient Glow Effects */
        .ambient-sphere-1 {
            position: absolute;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.08) 0%, transparent 70%);
            top: -150px;
            right: -100px;
            pointer-events: none;
            z-index: 0;
        }

        .ambient-sphere-2 {
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.07) 0%, transparent 70%);
            top: 500px;
            left: -150px;
            pointer-events: none;
            z-index: 0;
        }

        /* ── Header / Navigation ── */
        .navbar {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }

        .nav-container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 16px 24px;
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
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.12);
            border: 1px solid var(--border-color);
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-text h1 {
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.1;
            color: #0f172a;
        }

        .brand-text span {
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--amber-gold);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--text-muted);
            font-weight: 600;
            font-size: 0.95rem;
            transition: color 0.2s ease;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 0.92rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.25s ease;
            cursor: pointer;
            border: none;
        }

        .btn-outline {
            background: transparent;
            color: var(--text-main);
            border: 1px solid var(--border-color);
        }

        .btn-outline:hover {
            background: var(--bg-soft);
            border-color: #cbd5e1;
            color: var(--primary);
        }

        .btn-primary {
            background: var(--primary);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .btn-primary:hover {
            background: #1d4ed8;
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.35);
            transform: translateY(-1px);
        }

        .btn-gold {
            background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.25);
        }

        .btn-gold:hover {
            background: linear-gradient(135deg, #b45309 0%, #92400e 100%);
            box-shadow: 0 6px 16px rgba(217, 119, 6, 0.35);
            transform: translateY(-1px);
        }

        /* ── Hero Section ── */
        .hero {
            position: relative;
            padding: 80px 24px 90px;
            text-align: center;
            max-width: 1080px;
            margin: 0 auto;
            z-index: 1;
        }

        .hero-logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 28px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 10px 20px rgba(15, 23, 42, 0.12));
        }

        .scripture-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            background: var(--amber-soft);
            border: 1px solid var(--amber-border);
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--amber-gold);
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
        }

        .scripture-pill svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        .hero-headline {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: clamp(2.4rem, 5vw, 3.8rem);
            font-weight: 800;
            letter-spacing: -0.035em;
            line-height: 1.15;
            color: #0f172a;
            margin-bottom: 20px;
        }

        .hero-headline span.highlight {
            background: linear-gradient(135deg, #1d4ed8 0%, #3b82f6 50%, #d97706 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-subtext {
            font-size: 1.15rem;
            color: var(--text-muted);
            max-width: 680px;
            margin: 0 auto 36px;
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 48px;
        }

        .hero-actions .btn {
            padding: 14px 28px;
            font-size: 1rem;
            border-radius: 12px;
        }

        .hero-verse-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 4px solid var(--amber-gold);
            border-radius: 16px;
            padding: 24px 32px;
            max-width: 760px;
            margin: 0 auto;
            text-align: left;
            box-shadow: var(--shadow-md);
            position: relative;
        }

        .hero-verse-card blockquote {
            font-size: 1.05rem;
            font-style: italic;
            color: #334155;
            line-height: 1.65;
            margin-bottom: 8px;
        }

        .hero-verse-card cite {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--amber-gold);
            display: block;
            text-align: right;
            font-style: normal;
        }

        /* ── Stats Banner ── */
        .stats-section {
            background: #ffffff;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            padding: 40px 24px;
            position: relative;
            z-index: 1;
        }

        .stats-container {
            max-width: 1140px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 32px;
            text-align: center;
        }

        .stat-item h3 {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }

        .stat-item p {
            font-size: 0.92rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* ── Ministries / Pillars Section ── */
        .section {
            padding: 80px 24px;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .section-header {
            text-align: center;
            max-width: 640px;
            margin: 0 auto 52px;
        }

        .section-tag {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--primary);
            margin-bottom: 8px;
            display: block;
        }

        .section-title {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: #0f172a;
            margin-bottom: 14px;
        }

        .section-desc {
            color: var(--text-muted);
            font-size: 1rem;
        }

        .pillars-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 28px;
        }

        .pillar-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 32px 28px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
        }

        .pillar-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-xl);
            border-color: #cbd5e1;
        }

        .pillar-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 22px;
            background: var(--primary-subtle);
            color: var(--primary);
        }

        .pillar-icon.gold {
            background: var(--amber-soft);
            color: var(--amber-gold);
        }

        .pillar-icon svg {
            width: 26px;
            height: 26px;
        }

        .pillar-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 10px;
        }

        .pillar-card p {
            color: var(--text-muted);
            font-size: 0.94rem;
            line-height: 1.6;
        }

        /* ── Weekly Gatherings / Schedule ── */
        .schedule-box {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 40px;
            box-shadow: var(--shadow-lg);
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            gap: 40px;
            align-items: center;
        }

        .schedule-info h3 {
            font-size: 1.8rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 12px;
            letter-spacing: -0.02em;
        }

        .schedule-info p {
            color: var(--text-muted);
            margin-bottom: 24px;
            font-size: 0.98rem;
        }

        .schedule-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .schedule-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--bg-soft);
            padding: 16px 20px;
            border-radius: 14px;
            border: 1px solid var(--border-color);
        }

        .schedule-item-title {
            font-weight: 700;
            font-size: 1rem;
            color: #0f172a;
        }

        .schedule-item-sub {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .schedule-item-time {
            font-weight: 700;
            color: var(--primary);
            font-size: 0.95rem;
            background: #ffffff;
            padding: 6px 14px;
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        /* ── Testimonials / Faith Stories ── */
        .testi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 28px;
        }

        .testi-card {
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 32px;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .testi-quote {
            font-size: 0.98rem;
            color: #334155;
            line-height: 1.7;
            margin-bottom: 24px;
            position: relative;
        }

        .testi-user {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .testi-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #d97706 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
        }

        .testi-details h4 {
            font-size: 0.98rem;
            font-weight: 700;
            color: #0f172a;
        }

        .testi-details span {
            font-size: 0.82rem;
            color: var(--amber-gold);
            font-weight: 600;
        }

        /* ── Call To Action Banner ── */
        .cta-banner {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 60%, #3b82f6 100%);
            color: #ffffff;
            border-radius: 28px;
            padding: 60px 40px;
            text-align: center;
            box-shadow: 0 20px 40px -10px rgba(30, 58, 138, 0.4);
            margin: 40px auto 80px;
            max-width: 1140px;
            position: relative;
            overflow: hidden;
        }

        .cta-banner h2 {
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 16px;
        }

        .cta-banner p {
            font-size: 1.1rem;
            color: #bfdbfe;
            max-width: 600px;
            margin: 0 auto 36px;
        }

        .cta-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn-white {
            background: #ffffff;
            color: #1e3a8a;
            font-weight: 700;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 1rem;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.25s ease;
        }

        .btn-white:hover {
            background: #f8fafc;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .btn-translucent {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
            color: #ffffff;
            font-weight: 600;
            padding: 14px 28px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.25);
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .btn-translucent:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-2px);
        }

        /* ── Footer ── */
        .footer {
            background: #ffffff;
            border-top: 1px solid var(--border-color);
            padding: 60px 24px 30px;
            margin-top: auto;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.2fr;
            gap: 40px;
            margin-bottom: 48px;
        }

        .footer-col h4 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 18px;
        }

        .footer-col p {
            color: var(--text-muted);
            font-size: 0.92rem;
            line-height: 1.6;
        }

        .footer-logo {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 1px solid var(--border-color);
        }

        .footer-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.92rem;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: var(--text-muted);
            font-size: 0.85rem;
            flex-wrap: wrap;
            gap: 12px;
        }

        @media (max-width: 900px) {
            .schedule-box { grid-template-columns: 1fr; }
            .footer-container { grid-template-columns: 1fr 1fr; }
            .nav-links { display: none; }
        }

        @media (max-width: 600px) {
            .footer-container { grid-template-columns: 1fr; }
            .hero { padding: 50px 16px 60px; }
            .cta-banner { padding: 40px 20px; }
        }
    </style>
</head>
<body>

    <div class="ambient-sphere-1"></div>
    <div class="ambient-sphere-2"></div>

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
                <li><a href="#about">About</a></li>
                <li><a href="#ministries">Ministries</a></li>
                <li><a href="#gatherings">Gatherings</a></li>
                <li><a href="#testimonies">Testimonies</a></li>
            </ul>

            <div class="nav-actions">
                <?php if (!empty($is_logged_in)): ?>
                    <a href="<?= site_url('dashboard') ?>" class="btn btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        Portal Dashboard
                    </a>
                    <a href="<?= site_url('logout') ?>" class="btn btn-outline">Logout</a>
                <?php else: ?>
                    <a href="<?= site_url('login') ?>" class="btn btn-outline">Sign In</a>
                    <a href="<?= site_url('register') ?>" class="btn btn-gold">Join Fellowship</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- ── Hero Section ── -->
    <section class="hero">
        <div class="hero-logo">
            <img src="<?= site_url('assets/images/lifegen-logo.png') ?>" alt="Life Generation Logo">
        </div>

        <div class="scripture-pill">
            <svg viewBox="0 0 24 24"><path d="M12 2L1 21h22L12 2zm0 4.5l7.5 13H4.5L12 6.5z"/></svg>
            1 Peter 2:9 &bull; A Chosen Generation &bull; Called Into His Light
        </div>

        <h1 class="hero-headline">
            Empowering Generations Through a <span class="highlight">Kindled Passion in Christ</span>
        </h1>

        <p class="hero-subtext">
            Welcome to the Life Generation Christian Community. We are a family rooted in scripture, passionate in worship, and dedicated to raising generations filled with faith, hope, and love.
        </p>

        <div class="hero-actions">
            <?php if (!empty($is_logged_in)): ?>
                <a href="<?= site_url('dashboard') ?>" class="btn btn-primary">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    Go to Your Member Card
                </a>
            <?php else: ?>
                <a href="<?= site_url('register') ?>" class="btn btn-gold">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <line x1="20" y1="8" x2="20" y2="14"></line>
                        <line x1="23" y1="11" x2="17" y2="11"></line>
                    </svg>
                    Register for Membership
                </a>
                <a href="<?= site_url('login') ?>" class="btn btn-outline">
                    Access Member Portal &rarr;
                </a>
            <?php endif; ?>
        </div>

        <!-- Scripture Highlight Box -->
        <div class="hero-verse-card">
            <blockquote>
                "I came that they may have life, and have it abundantly. A new commandment I give to you, that you love one another: just as I have loved you, you also are to love one another."
            </blockquote>
            <cite>— John 10:10, John 13:34</cite>
        </div>
    </section>

    <!-- ── Community Stats ── -->
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
                <p>Cell & Worship Gatherings</p>
            </div>
            <div class="stat-item">
                <h3>100%</h3>
                <p>Christ-Centered Mission</p>
            </div>
        </div>
    </section>

    <!-- ── Pillars / Ministries Section ── -->
    <section id="ministries" class="section">
        <div class="section-header">
            <span class="section-tag">Our Ministries</span>
            <h2 class="section-title">Nurturing Spiritual Growth</h2>
            <p class="section-desc">Discover our key ministry branches designed to uplift families, students, and professionals in every walk of life.</p>
        </div>

        <div class="pillars-grid">
            <div class="pillar-card">
                <div class="pillar-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18V5l12-2v13"></path>
                        <circle cx="6" cy="18" r="3"></circle>
                        <circle cx="18" cy="16" r="3"></circle>
                    </svg>
                </div>
                <h3>Worship & Creative Arts</h3>
                <p>Lifting hearts in genuine praise and adoration through contemporary worship, musical excellence, and creative ministry.</p>
            </div>

            <div class="pillar-card">
                <div class="pillar-icon gold">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <h3>LifeGen Youth & Young Adults</h3>
                <p>Empowering the next generation with godly conviction, leadership opportunities, authentic friendships, and campus outreach.</p>
            </div>

            <div class="pillar-card">
                <div class="pillar-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                    </svg>
                </div>
                <h3>Discipleship & Word</h3>
                <p>Deepening scriptural roots through structured biblical teaching, cell group mentorship, and spiritual foundation courses.</p>
            </div>

            <div class="pillar-card">
                <div class="pillar-icon gold">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                    </svg>
                </div>
                <h3>Community Care & Outreach</h3>
                <p>Serving our local communities through compassionate outreach, relief programs, and sharing the tangible love of Christ.</p>
            </div>
        </div>
    </section>

    <!-- ── Gatherings & Service Times ── -->
    <section id="gatherings" class="section" style="padding-top: 0;">
        <div class="schedule-box">
            <div class="schedule-info">
                <span class="section-tag">Join Us In Person & Online</span>
                <h3>Weekly Worship & Fellowship Gatherings</h3>
                <p>Whether you are seeking God for the first time or looking for a church family to call home, you are always welcome here.</p>
                <a href="<?= site_url('register') ?>" class="btn btn-primary">Become a Member Today &rarr;</a>
            </div>

            <div class="schedule-list">
                <div class="schedule-item">
                    <div>
                        <div class="schedule-item-title">Sunday Celebration Service</div>
                        <div class="schedule-item-sub">Main Sanctuary & Live Broadcast</div>
                    </div>
                    <div class="schedule-item-time">9:30 AM &bull; Sun</div>
                </div>

                <div class="schedule-item">
                    <div>
                        <div class="schedule-item-title">LifeGen Youth Fellowship</div>
                        <div class="schedule-item-sub">Youth Hall & Interactive Workshops</div>
                    </div>
                    <div class="schedule-item-time">3:00 PM &bull; Sat</div>
                </div>

                <div class="schedule-item">
                    <div>
                        <div class="schedule-item-title">Midweek Prayer & Bible Study</div>
                        <div class="schedule-item-sub">Online Community & Local Cell Groups</div>
                    </div>
                    <div class="schedule-item-time">7:00 PM &bull; Wed</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Testimonies Section ── -->
    <section id="testimonies" class="section">
        <div class="section-header">
            <span class="section-tag">Testimonies of Faith</span>
            <h2 class="section-title">Stories of God's Faithfulness</h2>
            <p class="section-desc">Hear how belonging to the Life Generation community has transformed lives and strengthened faith.</p>
        </div>

        <div class="testi-grid">
            <div class="testi-card">
                <p class="testi-quote">
                    "Finding Life Generation gave me a true family in Christ. The cell group mentors walked with me through difficult times, and my spiritual life has never been stronger."
                </p>
                <div class="testi-user">
                    <div class="testi-avatar">DV</div>
                    <div class="testi-details">
                        <h4>Danielle Vitto</h4>
                        <span>Gold Member &bull; Youth Leader</span>
                    </div>
                </div>
            </div>

            <div class="testi-card">
                <p class="testi-quote">
                    "The biblical teaching and community outreach programs here allow our whole family to serve with purpose. It is a genuine blessing to be part of this congregation."
                </p>
                <div class="testi-user">
                    <div class="testi-avatar">MR</div>
                    <div class="testi-details">
                        <h4>Marcus Rivera</h4>
                        <span>VIP Member &bull; Worship Ministry</span>
                    </div>
                </div>
            </div>

            <div class="testi-card">
                <p class="testi-quote">
                    "From the moment I registered for membership, the community embraced me. Having access to discipleship classes and supportive friends made all the difference."
                </p>
                <div class="testi-user">
                    <div class="testi-avatar">SC</div>
                    <div class="testi-details">
                        <h4>Sarah Cruz</h4>
                        <span>Standard Member &bull; Outreach Team</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── Call to Action Banner ── -->
    <section class="cta-banner">
        <h2>You Are Welcome Here</h2>
        <p>Take your next step in faith. Register for membership to receive your digital member ID and get connected with our ministries.</p>
        <div class="cta-buttons">
            <a href="<?= site_url('register') ?>" class="btn-white">Join the Community</a>
            <a href="<?= site_url('login') ?>" class="btn-translucent">Member Sign In</a>
        </div>
    </section>

    <!-- ── Footer ── -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-col">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px;">
                    <div class="footer-logo">
                        <img src="<?= site_url('assets/images/lifegen-logo.png') ?>" alt="Life Generation Logo">
                    </div>
                    <h3 style="font-size:1.15rem; font-weight:800; color:#0f172a;">Life Generation</h3>
                </div>
                <p>
                    A Christ-centered community devoted to worship, discipleship, and generation-spanning transformation through the Gospel.
                </p>
            </div>

            <div class="footer-col">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="<?= site_url('') ?>">Home</a></li>
                    <li><a href="#ministries">Ministries</a></li>
                    <li><a href="#gatherings">Service Times</a></li>
                    <li><a href="#testimonies">Testimonies</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Membership</h4>
                <ul class="footer-links">
                    <li><a href="<?= site_url('login') ?>">Member Portal</a></li>
                    <li><a href="<?= site_url('register') ?>">Join Fellowship</a></li>
                    <li><a href="<?= site_url('dashboard') ?>">Digital ID Card</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Fellowship Center</h4>
                <p style="margin-bottom:8px;">100 Life Generation Sanctuary, Innovation Blvd, Metro Manila</p>
                <p style="color:var(--primary); font-weight:600;">info@lifegeneration.org</p>
            </div>
        </div>

        <div class="footer-bottom">
            <div>&copy; <?= date('Y') ?> Life Generation Community. All rights reserved.</div>
            <div>"For where two or three gather in my name, there am I with them." — Matthew 18:20</div>
        </div>
    </footer>

</body>
</html>