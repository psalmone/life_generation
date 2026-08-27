<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Dashboard' ?></title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #090d16;
            --card-bg: rgba(18, 24, 38, 0.85);
            --card-border: rgba(255, 255, 255, 0.08);
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.35);
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --success: #10b981;
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
            color: var(--text-primary);
            position: relative;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        .ambient-glow-1 {
            position: fixed;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, rgba(99, 102, 241, 0) 70%);
            top: -150px;
            left: -150px;
            z-index: 0;
            pointer-events: none;
            filter: blur(50px);
        }

        .ambient-glow-2 {
            position: fixed;
            width: 550px;
            height: 550px;
            background: radial-gradient(circle, rgba(168, 85, 247, 0.18) 0%, rgba(168, 85, 247, 0) 70%);
            bottom: -150px;
            right: -150px;
            z-index: 0;
            pointer-events: none;
            filter: blur(50px);
        }

        /* Navbar */
        .navbar {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--card-border);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 10;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            font-size: 1.2rem;
            color: #fff;
            text-decoration: none;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px -2px var(--primary-glow);
        }

        .brand-icon svg {
            width: 20px;
            height: 20px;
            color: #fff;
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--card-border);
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 0.9rem;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background: var(--success);
            border-radius: 50%;
            box-shadow: 0 0 8px var(--success);
        }

        .btn-logout {
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 8px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-logout:hover {
            background: rgba(239, 68, 68, 0.25);
            color: #fff;
        }

        /* Main Container */
        .main-content {
            flex: 1;
            padding: 40px 24px;
            max-width: 1000px;
            width: 100%;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .welcome-banner {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(168, 85, 247, 0.1));
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .welcome-text h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 6px;
            color: #fff;
        }

        .welcome-text p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .badge-auth {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(16, 185, 129, 0.2);
            color: #34d399;
            border: 1px solid rgba(52, 211, 153, 0.3);
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
        }

        .dashboard-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.4);
        }

        .card-header-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(99, 102, 241, 0.15);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .dashboard-card h3 {
            font-size: 1.15rem;
            color: #fff;
            margin-bottom: 8px;
        }

        .dashboard-card p {
            font-size: 0.9rem;
            color: var(--text-secondary);
            line-height: 1.5;
            margin-bottom: 16px;
        }

        .info-list {
            list-style: none;
            border-top: 1px solid var(--card-border);
            padding-top: 14px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            padding: 6px 0;
        }

        .info-item .label {
            color: var(--text-muted);
        }

        .info-item .val {
            color: var(--text-primary);
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <nav class="navbar">
        <a href="<?= site_url('dashboard') ?>" class="nav-brand">
            <div class="brand-icon">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <span>Life Generation</span>
        </a>

        <div class="nav-user">
            <div class="user-pill">
                <span class="status-dot"></span>
                <span><?= htmlspecialchars($user['username'] ?? 'User') ?></span>
            </div>
            <a href="<?= site_url('logout') ?>" class="btn-logout">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Logout</span>
            </a>
        </div>
    </nav>

    <main class="main-content">
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1>Hello, <?= htmlspecialchars($user['username'] ?? 'User') ?>! 👋</h1>
                <p>You have successfully authenticated via secure password verification.</p>
            </div>
            <div class="badge-auth">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Authenticated Session Active</span>
            </div>
        </div>

        <div class="grid">
            <!-- User Profile Details -->
            <div class="dashboard-card">
                <div class="card-header-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h3>Account Information</h3>
                <p>Your current user profile information retrieved from database storage.</p>
                <ul class="info-list">
                    <li class="info-item">
                        <span class="label">Username:</span>
                        <span class="val"><?= htmlspecialchars($user['username'] ?? 'N/A') ?></span>
                    </li>
                    <li class="info-item">
                        <span class="label">Email:</span>
                        <span class="val"><?= htmlspecialchars($user['email'] ?? 'N/A') ?></span>
                    </li>
                    <li class="info-item">
                        <span class="label">Role:</span>
                        <span class="val" style="text-transform: capitalize;"><?= htmlspecialchars($user['role'] ?? 'user') ?></span>
                    </li>
                </ul>
            </div>

            <!-- Security & Password details -->
            <div class="dashboard-card">
                <div class="card-header-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3>Security & Password</h3>
                <p>Your credentials are protected using industry-standard password cryptography.</p>
                <ul class="info-list">
                    <li class="info-item">
                        <span class="label">Hash Algorithm:</span>
                        <span class="val" style="color: #818cf8;">BCrypt (Cost: 10/Default)</span>
                    </li>
                    <li class="info-item">
                        <span class="label">Session Fixation:</span>
                        <span class="val" style="color: #34d399;">Protected (Regenerated)</span>
                    </li>
                    <li class="info-item">
                        <span class="label">Visibility Toggle:</span>
                        <span class="val">Enabled (Masked on Type)</span>
                    </li>
                </ul>
            </div>
        </div>
    </main>

</body>
</html>
