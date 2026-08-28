<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) : 'Membership Dashboard - Life Generation' ?></title>
    <meta name="description" content="Your Life Generation membership dashboard. View your digital member ID card, update your profile, and manage your membership.">
    <link rel="icon" type="image/png" href="<?= site_url('assets/images/lifegen-logo.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-body:        #f8fafc;
            --card-bg:        #ffffff;
            --card-border:    #e2e8f0;
            --primary:        #2563eb;
            --primary-hover:  #1d4ed8;
            --primary-glow:   rgba(37, 99, 235, 0.2);
            --amber-gold:     #d97706;
            --text-primary:   #0f172a;
            --text-secondary: #475569;
            --text-muted:     #64748b;
            --success:        #16a34a;
            --error:          #dc2626;
            --shadow-card:    0 12px 30px -4px rgba(15, 23, 42, 0.08);
            --shadow-sm:      0 1px 3px rgba(0,0,0,0.06);
        }

        *, *::before, *::after {
            box-sizing: border-box; margin: 0; padding: 0;
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }

        body {
            background-color: var(--bg-body);
            min-height: 100vh;
            color: var(--text-primary);
            display: flex; flex-direction: column;
            position: relative; overflow-x: hidden;
        }

        .ambient-glow-1 {
            position: fixed; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.07) 0%, transparent 70%);
            top: -150px; left: -150px; z-index: 0;
            pointer-events: none; filter: blur(60px);
        }
        .ambient-glow-2 {
            position: fixed; width: 550px; height: 550px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.07) 0%, transparent 70%);
            bottom: -150px; right: -150px; z-index: 0;
            pointer-events: none; filter: blur(60px);
        }

        /* ── Navbar ── */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--card-border);
            padding: 14px 32px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
            box-shadow: var(--shadow-sm);
        }
        .nav-brand {
            display: flex; align-items: center; gap: 12px;
            text-decoration: none; color: #0f172a;
        }
        .brand-badge {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 6px 14px var(--primary-glow);
            background: #ffffff;
            border: 1px solid var(--card-border);
            overflow: hidden;
        }
        .brand-badge img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .brand-title { font-size: 1.15rem; font-weight: 800; letter-spacing: -0.02em; color: #0f172a; }
        .brand-title span {
            color: var(--amber-gold);
        }

        .nav-right { display: flex; align-items: center; gap: 12px; }

        .btn-home {
            color: var(--text-secondary);
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            padding: 7px 14px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .btn-home:hover {
            color: var(--primary);
            background: #eff6ff;
        }

        .user-pill {
            display: flex; align-items: center; gap: 10px;
            padding: 6px 14px;
            background: #f1f5f9;
            border: 1px solid var(--card-border);
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .avatar {
            width: 28px; height: 28px; border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #d97706);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.75rem; color: #fff;
        }
        .role-badge-admin {
            background: #fef3c7; color: #b45309;
            padding: 2px 8px; border-radius: 6px;
            font-size: 0.7rem; font-weight: 700;
        }
        .role-badge-mod {
            background: #dbeafe; color: #1e40af;
            padding: 2px 8px; border-radius: 6px;
            font-size: 0.7rem; font-weight: 700;
        }

        .btn-logout {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 7px 14px; border-radius: 10px;
            font-size: 0.85rem; font-weight: 600;
            text-decoration: none;
            display: flex; align-items: center; gap: 6px;
            transition: all 0.2s;
        }
        .btn-logout:hover { background: #fca5a5; color: #7f1d1d; }

        /* ── Layout ── */
        .main-container {
            max-width: 1200px; width: 100%;
            margin: 0 auto; padding: 36px 20px 72px;
            position: relative; z-index: 1; flex: 1;
        }

        /* ── Alerts ── */
        .alert {
            padding: 14px 20px; border-radius: 14px;
            margin-bottom: 28px;
            display: flex; align-items: center; gap: 12px;
            font-size: 0.9rem;
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn { from { opacity:0; transform:translateY(-8px); } to { opacity:1; transform:translateY(0); } }
        .alert-success { background: #f0fdf4; border: 1px solid #dcfce7; color: #166534; }
        .alert-error   { background: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; }

        /* ── Dashboard Grid ── */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            gap: 28px;
            margin-bottom: 36px;
        }
        @media (max-width: 920px) { .dashboard-grid { grid-template-columns: 1fr; } }

        /* ── Digital Membership Card ── */
        .membership-card {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 60%, #1d4ed8 100%);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 26px; padding: 34px;
            box-shadow: 0 20px 40px -10px rgba(30, 58, 138, 0.4);
            position: relative; overflow: hidden;
            transition: transform 0.3s ease;
            color: #ffffff;
        }
        .membership-card:hover { transform: translateY(-3px); }

        .card-hologram-strip {
            position: absolute; top: 0; right: 0;
            width: 180px; height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.15) 50%, transparent 100%);
            pointer-events: none;
        }

        .card-org { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; color: #fde047; font-weight: 800; margin-bottom: 4px; }
        .card-type { font-size: 1.35rem; font-weight: 800; color: #fff; letter-spacing: -0.02em; }

        .card-header-row {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 26px;
        }

        .tier-badge {
            padding: 6px 14px; border-radius: 9999px;
            font-size: 0.75rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.7px;
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255,255,255,0.2);
            color: #ffffff;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.3);
        }
        .tier-gold { background: rgba(245,158,11,0.3); color: #fef08a; border-color: rgba(245,158,11,0.5); }
        .tier-vip  { background: rgba(217,70,239,0.3); color: #fae8ff; border-color: rgba(217,70,239,0.5); }

        .card-id-block {
            background: rgba(0,0,0,0.25);
            border: 1px dashed rgba(255,255,255,0.3);
            border-radius: 14px; padding: 14px 18px;
            margin-bottom: 26px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .card-id-label { font-size: 0.72rem; color: #bfdbfe; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; font-weight: 600; }
        .card-id-val {
            font-family: 'SF Mono', 'Courier New', monospace;
            font-size: 1.25rem; font-weight: 800; color: #ffffff; letter-spacing: 1px;
        }
        .btn-copy {
            background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.4);
            color: #ffffff; padding: 6px 14px;
            border-radius: 8px; font-size: 0.78rem; font-weight: 700; cursor: pointer; transition: all 0.2s;
        }
        .btn-copy:hover { background: #ffffff; color: #1e3a8a; }

        .card-details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 26px; }
        .detail-item { display: flex; flex-direction: column; gap: 3px; }
        .detail-label { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.8px; color: #bfdbfe; font-weight: 600; }
        .detail-value { font-size: 0.98rem; font-weight: 700; color: #ffffff; word-break: break-word; }

        .card-footer-bar {
            display: flex; align-items: center; justify-content: space-between;
            padding-top: 18px; border-top: 1px solid rgba(255,255,255,0.15);
            font-size: 0.82rem;
        }
        .status-indicator { display: inline-flex; align-items: center; gap: 6px; font-weight: 700; }
        .status-dot { width: 8px; height: 8px; border-radius: 50%; }
        .status-active .status-dot { background: #4ade80; box-shadow: 0 0 8px #4ade80; }
        .status-active { color: #86efac; }
        .status-inactive .status-dot { background: #f87171; }
        .status-inactive { color: #fca5a5; }

        /* ── Glass Panel (Profile Update) ── */
        .glass-panel {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 24px; padding: 32px;
            box-shadow: var(--shadow-card);
        }
        .panel-header { margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; }
        .panel-title { font-size: 1.2rem; font-weight: 800; color: #0f172a; }

        .form-group { margin-bottom: 18px; display: flex; flex-direction: column; gap: 7px; }
        .form-label { font-size: 0.825rem; font-weight: 700; color: var(--text-secondary); }
        .form-input, .form-textarea {
            width: 100%;
            background: #f8fafc;
            border: 1px solid var(--card-border);
            border-radius: 12px; padding: 12px 14px;
            color: var(--text-primary); font-size: 0.925rem;
            outline: none; transition: all 0.2s ease;
        }
        .form-textarea { min-height: 72px; resize: vertical; line-height: 1.5; }
        .form-input:focus, .form-textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
            background: #ffffff;
        }

        .form-row { display: grid; grid-template-columns: 1fr 100px; gap: 12px; }

        .readonly-field {
            background: #f1f5f9;
            border: 1px dashed var(--card-border);
            border-radius: 10px; padding: 10px 14px;
            font-size: 0.875rem; color: var(--text-secondary);
            display: flex; align-items: center; gap: 8px;
        }

        .btn-save {
            width: 100%;
            background: var(--primary);
            border: none; border-radius: 12px; padding: 13px 20px;
            color: #fff; font-size: 0.95rem; font-weight: 700;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: all 0.2s;
            margin-top: 6px;
        }
        .btn-save:hover { background: var(--primary-hover); transform: translateY(-1px); }

        /* ── Admin Section ── */
        .admin-section { margin-top: 44px; }

        .section-header { margin-bottom: 24px; }
        .section-header h2 { font-size: 1.4rem; font-weight: 800; color: #0f172a; }
        .section-header p { font-size: 0.875rem; color: var(--text-muted); margin-top: 5px; }

        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-bottom: 28px; }
        @media (max-width: 700px) { .stats-grid { grid-template-columns: 1fr; } }

        .stat-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: 18px; padding: 22px;
            box-shadow: var(--shadow-sm);
        }
        .stat-number { font-size: 2.2rem; font-weight: 800; color: #0f172a; margin-top: 8px; line-height: 1; }
        .stat-label { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.8px; color: var(--text-muted); font-weight: 700; }
        .stat-icon { margin-bottom: 6px; font-size: 1.4rem; }

        /* ── Table ── */
        .table-responsive {
            overflow-x: auto; border-radius: 18px;
            border: 1px solid var(--card-border);
            background: #ffffff;
            box-shadow: var(--shadow-card);
        }
        .member-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.875rem; }
        .member-table th {
            background: #f8fafc; padding: 14px 18px;
            font-weight: 700; color: var(--text-secondary);
            text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.8px;
            border-bottom: 1px solid var(--card-border); white-space: nowrap;
        }
        .member-table td {
            padding: 14px 18px;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text-primary); vertical-align: middle;
        }
        .member-table tr:last-child td { border-bottom: none; }
        .member-table tr:hover td { background: #f8fafc; }

        .badge-status { padding: 4px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; }
        .badge-active   { background: #dcfce7; color: #15803d; }
        .badge-inactive { background: #fee2e2; color: #b91c1c; }

        .badge-role { padding: 3px 9px; border-radius: 6px; font-size: 0.72rem; font-weight: 700; }
        .role-admin { background: #fef3c7; color: #b45309; }
        .role-moderator { background: #dbeafe; color: #1e40af; }
        .role-user { background: #f1f5f9; color: #475569; }

        .btn-toggle {
            padding: 5px 12px; border-radius: 8px;
            font-size: 0.775rem; font-weight: 700;
            border: 1px solid var(--card-border);
            cursor: pointer; background: #ffffff;
            color: var(--text-secondary); transition: all 0.2s;
        }
        .btn-toggle:hover { background: #f1f5f9; color: #0f172a; }

        .member-name { font-weight: 700; font-size: 0.92rem; color: #0f172a; }
        .member-username { font-size: 0.78rem; color: var(--text-muted); margin-top: 2px; }
        .member-id-cell {
            font-family: 'SF Mono', monospace;
            font-size: 0.82rem; color: var(--primary); font-weight: 700;
        }

        @media (max-width: 600px) { .navbar { padding: 12px 16px; } .main-container { padding: 24px 14px 48px; } }
    </style>
</head>
<body>

    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <?php
        $user   = $user   ?? [];
        $member = $member ?? [];

        $display_name   = $member['full_name'] ?? $user['username'] ?? 'Member';
        $avatar_initial = strtoupper(substr($display_name, 0, 1));
        $user_role      = $user['role'] ?? 'user';
        $member_tier    = $member['membership_tier'] ?? 'Standard Member';
        $member_active  = !empty($member['is_active']);

        $tier_class = 'tier-standard';
        if (stripos($member_tier, 'gold') !== false) $tier_class = 'tier-gold';
        elseif (stripos($member_tier, 'vip')  !== false) $tier_class = 'tier-vip';
    ?>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="<?= site_url('') ?>" class="nav-brand">
            <div class="brand-badge">
                <img src="<?= site_url('assets/images/lifegen-logo.png') ?>" alt="Life Generation Logo">
            </div>
            <div class="brand-title">Life <span>Generation</span></div>
        </a>

        <div class="nav-right">
            <a href="<?= site_url('') ?>" class="btn-home">Community Home</a>
            <div class="user-pill">
                <div class="avatar"><?= $avatar_initial ?></div>
                <span><?= htmlspecialchars($display_name) ?></span>
                <?php if ($user_role === 'admin'): ?>
                    <span class="role-badge-admin">ADMIN</span>
                <?php elseif ($user_role === 'moderator'): ?>
                    <span class="role-badge-mod">MOD</span>
                <?php endif; ?>
            </div>
            <a href="<?= site_url('logout') ?>" class="btn-logout">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span>Sign Out</span>
            </a>
        </div>
    </nav>

    <main class="main-container">

        <!-- Flash messages -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success" role="alert">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span><?= $success ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error" role="alert">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <!-- ── Two-column grid ── -->
        <div class="dashboard-grid">

            <!-- LEFT: Digital Membership ID Card -->
            <div>
                <div class="membership-card">
                    <div class="card-hologram-strip"></div>

                    <div class="card-header-row">
                        <div>
                            <div class="card-org">Life Generation Fellowship</div>
                            <div class="card-type">Official Membership Card</div>
                        </div>
                        <span class="tier-badge <?= $tier_class ?>">
                            <span>★</span>
                            <span><?= htmlspecialchars($member_tier) ?></span>
                        </span>
                    </div>

                    <!-- Member ID -->
                    <div class="card-id-block">
                        <div>
                            <div class="card-id-label">Member Identification No.</div>
                            <div class="card-id-val" id="memberIdText"><?= htmlspecialchars($member['member_id'] ?? 'LG-PENDING') ?></div>
                        </div>
                        <button type="button" class="btn-copy" onclick="copyMemberId()" id="copyBtn">Copy ID</button>
                    </div>

                    <!-- Member Details Grid -->
                    <div class="card-details-grid">
                        <div class="detail-item">
                            <span class="detail-label">Full Name</span>
                            <span class="detail-value"><?= htmlspecialchars($member['full_name'] ?? '—') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Age</span>
                            <span class="detail-value"><?= !empty($member['age']) ? htmlspecialchars($member['age']) . ' yrs old' : 'N/A' ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email Address</span>
                            <span class="detail-value"><?= htmlspecialchars($member['email'] ?? $user['email'] ?? '—') ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Login Username</span>
                            <span class="detail-value">@<?= htmlspecialchars($user['username'] ?? '—') ?></span>
                        </div>
                        <div class="detail-item" style="grid-column: span 2;">
                            <span class="detail-label">Address</span>
                            <span class="detail-value"><?= !empty($member['address']) ? nl2br(htmlspecialchars($member['address'])) : 'No address on file' ?></span>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="card-footer-bar">
                        <div class="status-indicator <?= $member_active ? 'status-active' : 'status-inactive' ?>">
                            <span class="status-dot"></span>
                            <span><?= $member_active ? 'Active Member' : 'Inactive Member' ?></span>
                        </div>
                        <div style="color: #bfdbfe; font-size: 0.8rem;">
                            Joined: <strong><?= !empty($member['joined_at']) ? date('M d, Y', strtotime($member['joined_at'])) : date('M d, Y') ?></strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Profile Update Panel -->
            <div class="glass-panel">
                <div class="panel-header">
                    <h2 class="panel-title">Update Profile</h2>
                    <span style="font-size:0.8rem;color:var(--text-muted);">Member Details</span>
                </div>

                <!-- Read-only fields -->
                <div style="margin-bottom:18px;">
                    <div class="form-label" style="margin-bottom:7px;">Login Username</div>
                    <div class="readonly-field">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        <span>@<strong><?= htmlspecialchars($user['username'] ?? '') ?></strong></span>
                    </div>
                </div>

                <form action="<?= site_url('dashboard/update-profile') ?>" method="POST" id="profileForm">

                    <div class="form-group">
                        <label class="form-label" for="full_name">Full Name</label>
                        <input type="text" name="full_name" id="full_name" class="form-input"
                               value="<?= htmlspecialchars($member['full_name'] ?? '') ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="email">Email Address</label>
                            <input type="email" name="email" id="email" class="form-input"
                                   value="<?= htmlspecialchars($member['email'] ?? $user['email'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="age">Age</label>
                            <input type="number" name="age" id="age" class="form-input"
                                   min="1" max="125"
                                   value="<?= htmlspecialchars($member['age'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="address">Address</label>
                        <textarea name="address" id="address" class="form-textarea" required><?= htmlspecialchars($member['address'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn-save">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Save Profile Changes</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- ── ADMIN SECTION ── -->
        <?php if ($user_role === 'admin'): ?>
            <section class="admin-section">
                <div class="section-header">
                    <h2>Member Directory</h2>
                    <p>Administrative portal — review and manage all registered members</p>
                </div>

                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">👥</div>
                        <div class="stat-label">Total Members</div>
                        <div class="stat-number"><?= (int)($total_members ?? 0) ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">✨</div>
                        <div class="stat-label">Active Members</div>
                        <div class="stat-number" style="color:var(--success);"><?= (int)($active_members ?? 0) ?></div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">🛡️</div>
                        <div class="stat-label">Admin Access Level</div>
                        <div class="stat-number" style="font-size:1.15rem;color:var(--primary);padding-top:6px;">FULL CONTROL</div>
                    </div>
                </div>

                <!-- Member Table -->
                <div class="table-responsive">
                    <table class="member-table">
                        <thead>
                            <tr>
                                <th>Member ID</th>
                                <th>Name / Username</th>
                                <th>Email</th>
                                <th>Age</th>
                                <th>Address</th>
                                <th>Tier</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($all_members)): ?>
                                <?php foreach ($all_members as $m): ?>
                                    <tr>
                                        <td class="member-id-cell">
                                            <?= htmlspecialchars($m['member_id'] ?? '—') ?>
                                        </td>
                                        <td>
                                            <div class="member-name"><?= htmlspecialchars($m['full_name'] ?? '—') ?></div>
                                            <div class="member-username">@<?= htmlspecialchars($m['username'] ?? '—') ?></div>
                                        </td>
                                        <td><?= htmlspecialchars($m['email'] ?? '—') ?></td>
                                        <td><?= !empty($m['age']) ? htmlspecialchars($m['age']) : '—' ?></td>
                                        <td style="max-width:200px;word-break:break-word;"><?= !empty($m['address']) ? htmlspecialchars($m['address']) : '—' ?></td>
                                        <td>
                                            <span style="font-size:0.8rem;font-weight:700;color:var(--amber-gold);">
                                                <?= htmlspecialchars($m['membership_tier'] ?? 'Standard Member') ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                                $r = $m['role'] ?? 'user';
                                                $rc = $r === 'admin' ? 'role-admin' : ($r === 'moderator' ? 'role-moderator' : 'role-user');
                                            ?>
                                            <span class="badge-role <?= $rc ?>"><?= strtoupper($r) ?></span>
                                        </td>
                                        <td>
                                            <?php if (!empty($m['is_active'])): ?>
                                                <span class="badge-status badge-active">Active</span>
                                            <?php else: ?>
                                                <span class="badge-status badge-inactive">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (($m['user_id'] ?? null) !== $user['id']): ?>
                                                <form action="<?= site_url('admin/toggle-member-status') ?>" method="POST" style="margin:0;">
                                                    <input type="hidden" name="member_row_id" value="<?= (int)$m['member_row_id'] ?>">
                                                    <button type="submit" class="btn-toggle">
                                                        <?= !empty($m['is_active']) ? 'Deactivate' : 'Activate' ?>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <span style="font-size:0.75rem;color:var(--text-muted);">(You)</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" style="text-align:center;padding:32px;color:var(--text-muted);">
                                        No members registered yet.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        <?php endif; ?>

    </main>

    <script>
        function copyMemberId() {
            const idText = document.getElementById('memberIdText').innerText.trim();
            const btn    = document.getElementById('copyBtn');
            navigator.clipboard.writeText(idText).then(() => {
                btn.textContent = '✓ Copied!';
                btn.style.background = '#10b981';
                btn.style.color = '#ffffff';
                setTimeout(() => {
                    btn.textContent = 'Copy ID';
                    btn.style.background = '';
                    btn.style.color = '';
                }, 2000);
            }).catch(() => {
                prompt('Copy your Member ID:', idText);
            });
        }
    </script>
</body>
</html>