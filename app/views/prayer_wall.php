<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <meta name="description" content="Submit and view prayer requests with the Life Generation community.">
    <link rel="icon" type="image/png" href="<?= site_url('assets/images/lifegen-logo.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            /* ── Minimal Pastel Palette (Orange, Soft Blue, Crisp White) ── */
            --bg-body:            #fafaf9;
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

        *, *::before, *::after {
            box-sizing: border-box; margin: 0; padding: 0;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
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

        /* ── Sidebar ── */
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

        /* ── Page Container ── */
        .main-container {
            max-width: 900px; width: 100%;
            margin: 0 auto; padding: 28px 24px 72px;
            flex: 1;
        }

        .page-header { margin-bottom: 24px; }
        .page-title { font-size: 1.4rem; font-weight: 800; color: var(--text-primary); }
        .page-desc { font-size: 0.875rem; color: var(--text-muted); margin-top: 4px; }

        /* ── Alerts ── */
        .alert {
            padding: 12px 16px; border-radius: var(--radius-md);
            margin-bottom: 20px; font-size: 0.86rem; font-weight: 600;
            display: flex; align-items: center; gap: 10px;
            box-shadow: var(--shadow-xs);
        }
        .alert-success { background: var(--success-light); border: 1px solid var(--success-border); color: #047857; }
        .alert-error   { background: var(--danger-light); border: 1px solid var(--danger-border); color: #be123c; }

        /* ── Submit Card ── */
        .card-box {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-xl);
            padding: 24px 26px;
            margin-bottom: 28px;
            box-shadow: var(--shadow-xs);
        }
        .card-title { font-size: 1.05rem; font-weight: 800; color: var(--text-primary); margin-bottom: 16px; letter-spacing: -0.01em; }

        .form-group { margin-bottom: 14px; }
        .form-label {
            display: block; font-size: 0.78rem; font-weight: 700;
            color: var(--text-secondary); margin-bottom: 5px;
            text-transform: uppercase; letter-spacing: 0.04em;
        }
        .form-input, .form-textarea {
            width: 100%; padding: 10px 14px;
            border: 1px solid var(--card-border);
            border-radius: var(--radius-md);
            font-size: 0.9rem; color: var(--text-primary);
            background: #fafaf9;
            transition: all 0.2s ease;
        }
        .form-input:focus, .form-textarea:focus {
            outline: none; border-color: var(--orange-primary);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15);
        }
        .form-textarea { min-height: 85px; resize: vertical; }

        .visibility-options { display: flex; align-items: center; gap: 18px; margin-top: 6px; }
        .vis-label { display: inline-flex; align-items: center; gap: 6px; font-size: 0.82rem; font-weight: 600; color: var(--text-secondary); cursor: pointer; }
        .vis-label input { accent-color: var(--orange-primary); }

        .btn-submit {
            background: var(--primary-gradient); color: #ffffff;
            border: none; padding: 10px 20px; border-radius: var(--radius-md);
            font-size: 0.875rem; font-weight: 700; cursor: pointer;
            display: inline-flex; align-items: center; gap: 6px;
            margin-top: 8px;
            box-shadow: 0 4px 12px rgba(249, 115, 22, 0.25);
            transition: all 0.2s ease;
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(249, 115, 22, 0.35);
        }

        /* ── Requests List ── */
        .list-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 16px;
        }
        .list-title { font-size: 1.05rem; font-weight: 800; color: var(--text-primary); }
        .badge-count {
            background: var(--orange-soft); color: var(--orange-primary);
            padding: 3px 10px; border-radius: 9999px;
            font-size: 0.75rem; font-weight: 700;
            border: 1px solid var(--orange-border);
        }

        .requests-stack { display: flex; flex-direction: column; gap: 14px; }
        .request-item {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 20px 22px;
            box-shadow: var(--shadow-xs);
            transition: all 0.2s ease;
        }
        .request-item:hover {
            border-color: #e7e5e4;
            box-shadow: var(--shadow-sm);
        }
        .request-item.answered {
            border-left: 4px solid var(--success);
            background: #fafdfc;
        }
        .req-top {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 10px;
        }
        .req-author-info { display: flex; align-items: center; gap: 8px; }
        .req-avatar {
            width: 28px; height: 28px; border-radius: 50%;
            background: var(--orange-soft); color: var(--orange-primary);
            border: 1px solid var(--orange-border);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700;
        }
        .req-author { font-size: 0.84rem; font-weight: 700; color: var(--text-primary); }
        .req-date { font-size: 0.72rem; color: var(--text-muted); }

        .tag-pill {
            padding: 3px 8px; border-radius: 6px;
            font-size: 0.7rem; font-weight: 700;
        }
        .tag-public   { background: #f5f5f4; color: var(--text-secondary); }
        .tag-private  { background: var(--warning-light); color: var(--warning); border: 1px solid var(--warning-border); }
        .tag-answered { background: var(--success-light); color: var(--success); border: 1px solid var(--success-border); }

        .req-title { font-size: 0.95rem; font-weight: 800; color: var(--text-primary); margin-bottom: 6px; }
        .req-body  { font-size: 0.86rem; color: var(--text-secondary); line-height: 1.55; }

        .req-actions {
            display: flex; gap: 8px; margin-top: 12px; padding-top: 10px;
            border-top: 1px solid var(--card-border-subtle);
        }
        .btn-action-sm {
            padding: 4px 10px; border-radius: var(--radius-sm);
            font-size: 0.75rem; font-weight: 700; cursor: pointer;
            border: 1px solid transparent; text-decoration: none;
            display: inline-flex; align-items: center; gap: 4px;
            transition: all 0.15s ease;
        }
        .btn-action-approve { background: var(--success-light); color: #047857; border-color: var(--success-border); }
        .btn-action-approve:hover { background: #dcfce7; }
        .btn-action-reject  { background: var(--danger-light); color: #be123c; border-color: var(--danger-border); }
        .btn-action-reject:hover { background: #ffe4e6; }

        @media (max-width: 900px) {
            body { flex-direction: column; }
            .sidebar {
                width: 100%; height: auto; position: static;
                padding: 16px 20px;
                border-right: none;
                border-bottom: 1px solid var(--card-border);
            }
            .main-wrapper { height: auto; }
            .topbar { padding: 12px 18px; }
            .main-container { padding: 18px 14px 44px; }
        }
    </style>
</head>
<body>

    <?php
        $user           = $user   ?? [];
        $member         = $member ?? [];
        $display_name   = $member['full_name'] ?? $user['username'] ?? 'Fellowship Member';
        $avatar_initial = strtoupper(substr($display_name, 0, 1));
        $user_role      = $user['role'] ?? 'user';
        $avatar_url     = !empty($member['avatar']) ? site_url($member['avatar']) : null;
    ?>

    <!-- ── LEFT SIDEBAR NAVIGATION ── -->
    <aside class="sidebar">
        <div>
            <!-- Brand -->
            <a href="<?= site_url('') ?>" class="sidebar-brand">
                <div class="brand-badge">
                    <img src="<?= site_url('assets/images/lifegen-logo.png') ?>" alt="Logo">
                </div>
                <div class="brand-title">Life <span>Generation</span></div>
            </a>

            <!-- Navigation Links -->
            <ul class="nav-menu">
                <li class="nav-section-label">Menu</li>
                <li>
                    <a href="<?= site_url('dashboard') ?>" class="nav-btn">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('dashboard') ?>#profile" class="nav-btn">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <span>My Profile</span>
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('dashboard') ?>#events" class="nav-btn">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Church Events</span>
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('dashboard') ?>#dgroup" class="nav-btn">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        <span>D-Groups</span>
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('dashboard') ?>#ministry" class="nav-btn">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span>Ministries</span>
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('prayer-wall') ?>" class="nav-btn active">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span>Prayer Wall</span>
                    </a>
                </li>

                <?php if ($user_role === 'admin'): ?>
                    <li class="nav-section-label" style="margin-top:8px;">Admin Operations</li>
                    <li>
                        <a href="<?= site_url('dashboard') ?>#admin-attendance" class="nav-btn">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                            <span>QR Attendance & Check-in</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('dashboard') ?>#admin-events" class="nav-btn">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            <span>Manage Events</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('dashboard') ?>#admin-dgroups" class="nav-btn">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <span>Manage D-Groups</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('dashboard') ?>#admin-ministries" class="nav-btn">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <span>Manage Ministries</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('dashboard') ?>#admin-members" class="nav-btn">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>Members Directory</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= site_url('dashboard') ?>#admin-ministry-apps" class="nav-btn">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            <span>Volunteer Requests</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <a href="<?= site_url('dashboard') ?>#profile" class="sidebar-user-card" title="View & edit profile">
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
            </a>
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
            <h1 class="topbar-title">Prayer Wall</h1>
            <div class="topbar-right">
                <a href="<?= site_url('') ?>" class="topbar-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Website Home</span>
                </a>
                <a href="<?= site_url('dashboard') ?>" class="topbar-btn">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>
            </div>
        </header>

        <div class="main-container">



        <div class="page-header">
            <h1 class="page-title">Prayer Wall</h1>
            <p class="page-desc">Share requests and stand together in faith</p>
        </div>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                <span><?= $success ?></span>
            </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <!-- Submit Box -->
        <div class="card-box">
            <h2 class="card-title">Submit a Prayer Request</h2>
            <form action="<?= site_url('prayer-wall/submit') ?>" method="POST">
                <div class="form-group">
                    <label class="form-label" for="pr_title">Title</label>
                    <input type="text" id="pr_title" name="title" class="form-input" placeholder="e.g., Healing, Guidance in studies" required maxlength="200">
                </div>
                <div class="form-group">
                    <label class="form-label" for="pr_body">Request Details</label>
                    <textarea id="pr_body" name="body" class="form-textarea" placeholder="Share your prayer request..." required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Visibility</label>
                    <div class="visibility-options">
                        <label class="vis-label">
                            <input type="radio" name="visibility" value="public" checked>
                            <span>Public (All signed-in members)</span>
                        </label>
                        <label class="vis-label">
                            <input type="radio" name="visibility" value="private">
                            <span>Private (Leaders & Admins only)</span>
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn-submit">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    <span>Submit Request</span>
                </button>
            </form>
        </div>

        <!-- Feed -->
        <div class="list-header">
            <h2 class="list-title">Community Requests</h2>
            <span class="badge-count"><?= count($requests ?? []) ?> requests</span>
        </div>

        <?php if (!empty($requests)): ?>
            <div class="requests-stack">
                <?php foreach ($requests as $req): ?>
                    <?php
                        $initial  = strtoupper(substr($req['member_name'] ?? 'A', 0, 1));
                        $isOwner  = (int)$req['user_id'] === (int)($user['id'] ?? 0);
                        $answered = $req['status'] === 'answered';
                    ?>
                    <div class="request-item <?= $answered ? 'answered' : '' ?>">
                        <div class="req-top">
                            <div class="req-author-info">
                                <div class="req-avatar"><?= $initial ?></div>
                                <div>
                                    <div class="req-author"><?= htmlspecialchars($req['member_name']) ?></div>
                                    <div class="req-date"><?= date('M j, Y · g:i A', strtotime($req['created_at'])) ?></div>
                                </div>
                            </div>
                            <div style="display:flex; align-items:center; gap:6px;">
                                <?php if ($answered): ?>
                                    <span class="tag-pill tag-answered">Answered</span>
                                <?php endif; ?>
                                <span class="tag-pill <?= $req['visibility'] === 'private' ? 'tag-private' : 'tag-public' ?>">
                                    <?= $req['visibility'] === 'private' ? 'Private' : 'Public' ?>
                                </span>
                            </div>
                        </div>

                        <div class="req-title"><?= htmlspecialchars($req['title']) ?></div>
                        <div class="req-body"><?= nl2br(htmlspecialchars($req['body'])) ?></div>

                        <?php if ($isOwner || $is_admin): ?>
                            <div class="req-actions">
                                <?php if (!$answered): ?>
                                    <form action="<?= site_url('prayer-wall/mark-answered') ?>" method="POST" style="margin:0;">
                                        <input type="hidden" name="request_id" value="<?= (int)$req['id'] ?>">
                                        <button type="submit" class="btn-action-sm btn-action-approve">Mark as Answered</button>
                                    </form>
                                <?php endif; ?>
                                <form action="<?= site_url('prayer-wall/delete') ?>" method="POST" style="margin:0;" onsubmit="return confirm('Remove this request?')">
                                    <input type="hidden" name="request_id" value="<?= (int)$req['id'] ?>">
                                    <button type="submit" class="btn-action-sm btn-action-reject">Remove</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align:center; padding:48px 20px; color:var(--text-muted); background:#ffffff; border:1px solid var(--card-border); border-radius:var(--radius-lg);">
                No prayer requests yet. Be the first to share one.
            </div>
        <?php endif; ?>

        </div> <!-- /.main-container -->
    </div> <!-- /.main-wrapper -->

    <!-- ── Logout Confirmation Modal ── -->
    <div id="logoutConfirmModal" style="position:fixed; inset:0; z-index:9999; background:rgba(15,23,42,0.65); backdrop-filter:blur(6px); display:none; align-items:center; justify-content:center; padding:20px;" onclick="if(event.target===this) closeLogoutConfirm()">
        <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:16px; padding:32px 28px; width:100%; max-width:380px; text-align:center; box-shadow:0 24px 64px rgba(0,0,0,0.3); animation:slideUpModal 0.22s cubic-bezier(0.34,1.56,0.64,1);">
            <div style="margin:0 auto 16px; width:60px; height:60px; border-radius:50%; background:#fff1f2; border:2px solid #fecdd3; display:flex; align-items:center; justify-content:center;">
                <svg width="26" height="26" fill="none" stroke="#e11d48" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </div>
            <div style="font-size:1.15rem; font-weight:800; color:#0f172a; margin-bottom:8px;">Sign Out?</div>
            <p style="font-size:0.875rem; color:#64748b; line-height:1.6; margin-bottom:24px;">
                Are you sure you want to sign out of your<br>Life Generation member account?
            </p>
            <div style="display:flex; gap:10px;">
                <button type="button" onclick="closeLogoutConfirm()" style="flex:1; padding:10px 16px; border-radius:10px; border:1px solid #e2e8f0; background:transparent; color:#475569; font-size:0.88rem; font-weight:600; cursor:pointer;">
                    Cancel
                </button>
                <a href="<?= site_url('logout') ?>" style="flex:1; padding:10px 16px; border-radius:10px; background:linear-gradient(135deg,#e11d48,#be123c); color:#ffffff; font-size:0.88rem; font-weight:700; text-decoration:none; display:flex; align-items:center; justify-content:center; gap:7px; box-shadow:0 2px 8px rgba(225,29,72,0.3);">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Yes, Sign Out
                </a>
            </div>
        </div>
    </div>
    <script>
        function openLogoutConfirm() {
            const m = document.getElementById('logoutConfirmModal');
            if (m) m.style.display = 'flex';
        }
        function closeLogoutConfirm() {
            const m = document.getElementById('logoutConfirmModal');
            if (m) m.style.display = 'none';
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLogoutConfirm();
        });
    </script>
</body>
</html>
