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
    <style>
        :root {
            --bg-body:        #f8fafc;
            --card-bg:        #ffffff;
            --card-border:    #e2e8f0;
            --primary:        #2563eb;
            --primary-hover:  #1d4ed8;
            --primary-light:  #eff6ff;
            --text-primary:   #0f172a;
            --text-secondary: #475569;
            --text-muted:     #64748b;
            --success:        #16a34a;
            --success-light:  #f0fdf4;
            --success-border: #bbf7d0;
            --danger:         #dc2626;
            --danger-light:   #fef2f2;
            --danger-border:  #fecaca;
            --warning:        #d97706;
            --warning-light:  #fffbeb;
            --warning-border: #fde68a;
            --shadow-sm:      0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md:      0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --shadow-lg:      0 10px 15px -3px rgba(0, 0, 0, 0.06), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
            --radius-sm:      8px;
            --radius-md:      12px;
            --radius-lg:      16px;
            --sidebar-width:  250px;
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
            padding: 20px 14px;
            z-index: 100;
            flex-shrink: 0;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--text-primary);
            padding: 6px 10px 18px;
            border-bottom: 1px solid var(--card-border);
            margin-bottom: 14px;
        }
        .brand-badge {
            width: 32px; height: 32px;
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            background: #ffffff; border: 1px solid var(--card-border);
            overflow: hidden; flex-shrink: 0;
        }
        .brand-badge img { width: 100%; height: 100%; object-fit: contain; }
        .brand-title { font-size: 0.95rem; font-weight: 700; letter-spacing: -0.01em; }
        .brand-title span { color: var(--primary); }

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
            color: var(--text-muted);
            font-weight: 700;
            padding: 12px 10px 4px;
        }
        .nav-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 9px 12px;
            border-radius: var(--radius-sm);
            font-size: 0.84rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            border: none;
            background: transparent;
            cursor: pointer;
            text-align: left;
            transition: all 0.15s ease;
        }
        .nav-btn:hover {
            color: var(--primary);
            background: var(--primary-light);
        }
        .nav-btn.active {
            color: var(--primary);
            background: var(--primary-light);
            font-weight: 700;
        }
        .nav-btn svg {
            width: 17px; height: 17px; flex-shrink: 0;
        }

        .sidebar-footer {
            border-top: 1px solid var(--card-border);
            padding-top: 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .sidebar-user-info {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 6px 8px;
        }
        .avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.75rem; color: #fff;
            flex-shrink: 0;
            overflow: hidden;
            object-fit: cover;
        }
        .avatar img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .user-name {
            font-size: 0.8rem; font-weight: 700; color: var(--text-primary);
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 130px;
        }
        .user-role-badge {
            font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600;
        }

        .btn-sidebar-logout {
            display: flex; align-items: center; gap: 8px;
            padding: 8px 10px; border-radius: var(--radius-sm);
            background: var(--danger-light); border: 1px solid var(--danger-border);
            color: var(--danger); font-size: 0.8rem; font-weight: 600;
            text-decoration: none; transition: all 0.15s ease;
        }
        .btn-sidebar-logout:hover { background: #fee2e2; }

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
            background: #ffffff;
            border-bottom: 1px solid var(--card-border);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
        }
        .topbar-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text-primary);
        }
        .topbar-right {
            display: flex; align-items: center; gap: 12px;
        }

        .main-container {
            max-width: 1100px;
            width: 100%;
            margin: 0 auto;
            padding: 28px 24px 64px;
        }

        /* ── Alerts ── */
        .alert {
            padding: 12px 16px; border-radius: var(--radius-md);
            margin-bottom: 24px;
            display: flex; align-items: center; gap: 10px;
            font-size: 0.875rem; font-weight: 500;
        }
        .alert-success { background: var(--success-light); border: 1px solid var(--success-border); color: #15803d; }
        .alert-error   { background: var(--danger-light); border: 1px solid var(--danger-border); color: #b91c1c; }

        /* ── Tab Views ── */
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
            animation: fadeIn 0.2s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Member Overview Top Grid ── */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1.15fr 1fr;
            gap: 22px;
            margin-bottom: 28px;
        }
        @media (max-width: 900px) { .dashboard-grid { grid-template-columns: 1fr; } }

        /* ── Minimal Digital Membership Card with QR Code ── */
        .membership-card {
            background: #0f172a;
            border-radius: var(--radius-lg);
            padding: 24px 26px;
            color: #ffffff;
            display: flex; flex-direction: column;
            justify-content: space-between;
            min-height: 270px;
            box-shadow: var(--shadow-lg);
        }
        .card-top {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 16px;
        }
        .card-brand-title {
            font-size: 0.7rem; text-transform: uppercase;
            letter-spacing: 0.1em; color: #94a3b8; font-weight: 700;
        }
        .card-brand-sub {
            font-size: 1.1rem; font-weight: 700; color: #ffffff; margin-top: 2px;
        }
        .status-pill {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 9px; border-radius: 9999px;
            font-size: 0.7rem; font-weight: 600;
        }
        .status-pill.active { background: rgba(34, 197, 94, 0.15); color: #4ade80; }
        .status-pill.inactive { background: rgba(239, 68, 68, 0.15); color: #f87171; }
        .status-dot-sm { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

        /* Card Header with Photo & QR Box */
        .card-profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .card-profile-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .card-avatar {
            width: 52px; height: 52px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.25);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; font-weight: 700; color: #ffffff;
            overflow: hidden; flex-shrink: 0;
        }
        .card-avatar img {
            width: 100%; height: 100%; object-fit: cover;
        }

        /* Card QR Code Frame */
        .card-qr-box {
            background: #ffffff;
            padding: 4px;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            transition: transform 0.15s ease;
        }
        .card-qr-box:hover {
            transform: scale(1.05);
        }
        .card-qr-box canvas {
            display: block;
            border-radius: 4px;
        }
        .card-qr-hint {
            font-size: 0.58rem;
            color: #0f172a;
            font-weight: 800;
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .card-id-row {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: var(--radius-md);
            padding: 9px 13px;
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 16px;
        }
        .card-id-label { font-size: 0.66rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; }
        .card-id-value { font-family: monospace; font-size: 0.95rem; font-weight: 700; color: #38bdf8; margin-top: 1px; }
        .btn-copy-id {
            background: rgba(255, 255, 255, 0.1);
            border: none; color: #f1f5f9;
            padding: 4px 9px; border-radius: var(--radius-sm);
            font-size: 0.72rem; font-weight: 600; cursor: pointer;
            display: inline-flex; align-items: center; gap: 4px;
            transition: all 0.15s ease;
        }
        .btn-copy-id:hover { background: rgba(255, 255, 255, 0.2); }

        .card-info-grid {
            display: grid; grid-template-columns: repeat(2, 1fr);
            gap: 10px; margin-bottom: 14px;
        }
        .card-info-label { font-size: 0.66rem; color: #94a3b8; text-transform: uppercase; }
        .card-info-val { font-size: 0.82rem; font-weight: 600; color: #f8fafc; margin-top: 2px; }

        .card-badges-row {
            display: flex; flex-wrap: wrap; gap: 7px;
            padding-top: 12px; border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .card-tag {
            background: rgba(255, 255, 255, 0.08);
            color: #cbd5e1;
            padding: 3px 8px; border-radius: 6px;
            font-size: 0.7rem; font-weight: 600;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .card-tag.ministry { background: rgba(56, 189, 248, 0.15); color: #7dd3fc; }
        .card-tag.dgroup { background: rgba(168, 85, 247, 0.15); color: #d8b4fe; }

        /* ── Profile Update Form & Avatar Upload ── */
        .glass-panel {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 22px 24px;
            box-shadow: var(--shadow-sm);
        }
        .panel-title { font-size: 1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 14px; }

        .avatar-upload-box {
            border: 2px dashed var(--card-border);
            border-radius: var(--radius-md);
            padding: 16px;
            text-align: center;
            background: #f8fafc;
            margin-bottom: 12px;
            cursor: pointer;
            transition: all 0.15s ease;
            position: relative;
        }
        .avatar-upload-box:hover, .avatar-upload-box.drag-over {
            border-color: var(--primary);
            background: var(--primary-light);
        }
        .avatar-upload-preview {
            width: 68px; height: 68px;
            border-radius: 50%;
            margin: 0 auto 8px;
            background: #e2e8f0;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            border: 3px solid #ffffff;
            box-shadow: var(--shadow-md);
        }
        .avatar-upload-preview img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .avatar-upload-text {
            font-size: 0.78rem; font-weight: 600; color: var(--text-primary); margin-bottom: 2px;
        }
        .avatar-upload-hint {
            font-size: 0.68rem; color: var(--text-muted);
        }
        .avatar-upload-box input[type="file"] {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            opacity: 0; cursor: pointer; z-index: 10;
        }

        .avatar-actions-row {
            display: flex; gap: 8px; margin-bottom: 16px; justify-content: center;
        }
        .btn-avatar-action {
            padding: 5px 12px; border-radius: var(--radius-sm);
            font-size: 0.75rem; font-weight: 600; cursor: pointer;
            display: inline-flex; align-items: center; gap: 5px;
            border: 1px solid transparent; text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-avatar-change { background: var(--primary-light); color: var(--primary); border-color: #bfdbfe; }
        .btn-avatar-change:hover { background: #dbeafe; }
        .btn-avatar-remove { background: var(--danger-light); color: var(--danger); border-color: var(--danger-border); }
        .btn-avatar-remove:hover { background: #fee2e2; }

        .form-group { margin-bottom: 12px; }
        .form-label {
            display: block; font-size: 0.76rem; font-weight: 600;
            color: var(--text-secondary); margin-bottom: 4px;
        }
        .form-input, .form-textarea, .form-select {
            width: 100%; padding: 8px 12px;
            border: 1px solid var(--card-border);
            border-radius: var(--radius-sm);
            font-size: 0.84rem; color: var(--text-primary);
            background: #ffffff;
            transition: border-color 0.15s ease;
        }
        .form-input:focus, .form-textarea:focus, .form-select:focus {
            outline: none; border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }
        .form-textarea { min-height: 55px; resize: vertical; }
        .form-row { display: grid; grid-template-columns: 1.5fr 1fr; gap: 10px; }

        .btn-save {
            width: 100%; background: var(--primary);
            color: #ffffff; border: none;
            padding: 8px 14px; border-radius: var(--radius-sm);
            font-size: 0.84rem; font-weight: 600; cursor: pointer;
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            transition: background 0.15s ease;
        }
        .btn-save:hover { background: var(--primary-hover); }

        /* ── Section Cards ── */
        .section-box {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 22px 24px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 24px;
        }
        .section-header-row {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 18px;
        }
        .section-title { font-size: 1.05rem; font-weight: 700; color: var(--text-primary); }
        .section-desc { font-size: 0.8rem; color: var(--text-muted); margin-top: 2px; }

        /* ── Admin Stats ── */
        .stats-row {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 14px; margin-bottom: 18px;
        }
        @media (max-width: 650px) { .stats-row { grid-template-columns: 1fr; } }
        .stat-box {
            background: #f8fafc; border: 1px solid var(--card-border);
            border-radius: var(--radius-md); padding: 14px 16px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .stat-title { font-size: 0.74rem; font-weight: 600; color: var(--text-secondary); }
        .stat-val { font-size: 1.2rem; font-weight: 700; color: var(--text-primary); margin-top: 2px; }
        .stat-icon-wrapper {
            width: 34px; height: 34px; border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            background: #ffffff; border: 1px solid var(--card-border);
            color: var(--primary);
        }

        /* ── Tables ── */
        .table-responsive { overflow-x: auto; }
        .clean-table {
            width: 100%; border-collapse: collapse; text-align: left;
            font-size: 0.82rem;
        }
        .clean-table th {
            padding: 10px 12px; background: #f8fafc;
            border-bottom: 1px solid var(--card-border);
            color: var(--text-secondary); font-weight: 600;
        }
        .clean-table td {
            padding: 10px 12px; border-bottom: 1px solid #f1f5f9;
            color: var(--text-primary); vertical-align: middle;
        }
        .clean-table tr:last-child td { border-bottom: none; }
        .clean-table tr:hover td { background: #fafafa; }

        .table-avatar {
            width: 28px; height: 28px; border-radius: 50%;
            background: #e2e8f0; color: var(--text-secondary);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.72rem; font-weight: 700;
            overflow: hidden; flex-shrink: 0;
        }
        .table-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .btn-action-sm {
            padding: 4px 8px; border-radius: var(--radius-sm);
            font-size: 0.74rem; font-weight: 600; cursor: pointer;
            border: 1px solid transparent; text-decoration: none;
            display: inline-flex; align-items: center; gap: 4px;
        }
        .btn-action-approve { background: var(--success-light); color: #15803d; border-color: var(--success-border); }
        .btn-action-reject  { background: var(--danger-light); color: #b91c1c; border-color: var(--danger-border); }
        .btn-action-toggle  { background: #f1f5f9; color: var(--text-secondary); border-color: var(--card-border); }

        /* ── Cards Grid ── */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }
        .item-card {
            background: #ffffff;
            border: 1px solid var(--card-border);
            border-radius: var(--radius-md);
            padding: 18px;
            display: flex; flex-direction: column; justify-content: space-between;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .item-card:hover {
            border-color: #cbd5e1; box-shadow: var(--shadow-sm);
        }
        .item-card.enrolled {
            border-color: #bfdbfe; background: #fbfdff;
        }
        .item-header {
            display: flex; justify-content: space-between; align-items: flex-start;
            margin-bottom: 8px;
        }
        .item-title { font-size: 0.95rem; font-weight: 700; color: var(--text-primary); }
        .item-meta { display: flex; flex-direction: column; gap: 5px; margin: 6px 0 10px; }
        .item-meta-row {
            display: flex; align-items: center; gap: 6px;
            font-size: 0.76rem; color: var(--text-secondary);
        }
        .item-desc { font-size: 0.76rem; color: var(--text-muted); line-height: 1.45; margin-bottom: 12px; }

        .btn-card-primary {
            width: 100%; background: var(--primary);
            color: #ffffff; border: none;
            padding: 7px 12px; border-radius: var(--radius-sm);
            font-size: 0.78rem; font-weight: 600; cursor: pointer;
            display: inline-flex; align-items: center; justify-content: center; gap: 4px;
            transition: background 0.15s ease;
        }
        .btn-card-primary:hover { background: var(--primary-hover); }

        .btn-card-danger {
            width: 100%; background: var(--danger-light);
            color: var(--danger); border: 1px solid var(--danger-border);
            padding: 7px 12px; border-radius: var(--radius-sm);
            font-size: 0.78rem; font-weight: 600; cursor: pointer;
            transition: all 0.15s ease;
        }
        .btn-card-danger:hover { background: #fee2e2; }

        .tag-status {
            padding: 2px 7px; border-radius: 4px;
            font-size: 0.68rem; font-weight: 600;
        }
        .tag-status.pending  { background: var(--warning-light); color: var(--warning); border: 1px solid var(--warning-border); }
        .tag-status.approved { background: var(--success-light); color: var(--success); border: 1px solid var(--success-border); }
        .tag-status.rejected { background: var(--danger-light); color: var(--danger); border: 1px solid var(--danger-border); }

        .user-app-badge {
            background: #f8fafc; border: 1px solid var(--card-border);
            border-radius: var(--radius-sm); padding: 10px 14px;
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 16px;
        }

        .role-tag {
            background: #e2e8f0; color: var(--text-secondary);
            padding: 2px 6px; border-radius: 4px;
            font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
        }
        .role-tag.admin { background: #dbeafe; color: #1d4ed8; }

        /* QR Scanner Container */
        .scanner-container {
            border: 2px dashed var(--card-border);
            border-radius: var(--radius-md);
            overflow: hidden;
            background: #000000;
            margin-bottom: 16px;
            max-width: 480px;
            margin: 0 auto 16px;
        }

        @media (max-width: 768px) {
            body { flex-direction: column; }
            .sidebar {
                width: 100%; height: auto; position: relative; border-right: none;
                border-bottom: 1px solid var(--card-border);
            }
            .main-wrapper { height: auto; }
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
                    <button type="button" class="nav-btn active" onclick="showTab('overview', this)">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
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
            <div class="sidebar-user-info">
                <div class="avatar">
                    <?php if ($avatar_url): ?>
                        <img src="<?= $avatar_url ?>" alt="Avatar">
                    <?php else: ?>
                        <?= $avatar_initial ?>
                    <?php endif; ?>
                </div>
                <div>
                    <div class="user-name"><?= htmlspecialchars($display_name) ?></div>
                    <div class="user-role-badge"><?= htmlspecialchars($user_role) ?></div>
                </div>
            </div>
            <a href="<?= site_url('logout') ?>" class="btn-sidebar-logout">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span>Sign Out</span>
            </a>
        </div>
    </aside>

    <!-- ── MAIN CONTENT ── -->
    <div class="main-wrapper">

        <!-- Top Header -->
        <header class="topbar">
            <h1 class="topbar-title" id="pageTitle">Dashboard</h1>
            <div class="topbar-right">
                <a href="<?= site_url('') ?>" class="nav-btn" style="width:auto; padding:6px 12px; background:#f8fafc; border:1px solid var(--card-border);">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Website Home</span>
                </a>
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
                                        <span><?= $member_active ? 'Active' : 'Inactive' ?></span>
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
                                            <div style="font-size:1.05rem; font-weight:700; color:#ffffff;"><?= htmlspecialchars($member['full_name'] ?? '—') ?></div>
                                            <div style="font-size:0.75rem; color:#94a3b8;">@<?= htmlspecialchars($user['username'] ?? '') ?></div>
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
                                        <div class="card-id-label">Member ID</div>
                                        <div class="card-id-value" id="memberIdText"><?= htmlspecialchars($member_id_code) ?></div>
                                    </div>
                                    <button type="button" class="btn-copy-id" onclick="copyMemberId()" id="copyBtn">
                                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                        <span>Copy</span>
                                    </button>
                                </div>

                                <!-- Info Grid -->
                                <div class="card-info-grid">
                                    <div>
                                        <div class="card-info-label">Email</div>
                                        <div class="card-info-val"><?= htmlspecialchars($member['email'] ?? $user['email'] ?? '—') ?></div>
                                    </div>
                                    <div>
                                        <div class="card-info-label">Age</div>
                                        <div class="card-info-val"><?= !empty($member['age']) ? htmlspecialchars($member['age']) . ' yrs' : '—' ?></div>
                                    </div>
                                    <div>
                                        <div class="card-info-label">Ministry</div>
                                        <div class="card-info-val" style="color: <?= !empty($current_ministry) ? '#38bdf8' : '#94a3b8' ?>;">
                                            <?= !empty($current_ministry) ? htmlspecialchars($current_ministry['name']) : 'None' ?>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="card-info-label">D-Group</div>
                                        <div class="card-info-val" style="color: <?= !empty($current_dgroup) ? '#d8b4fe' : '#94a3b8' ?>;">
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
                                        <span>Not enrolled in any team or group yet</span>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Form with Drag & Drop Avatar -->
                    <div class="glass-panel">
                        <div class="panel-title">Update Profile & Avatar</div>

                        <!-- Drag and Drop Avatar Form -->
                        <form action="<?= site_url('dashboard/upload-avatar') ?>" method="POST" enctype="multipart/form-data" id="avatarForm">
                            <div class="avatar-upload-box" id="avatarDropZone">
                                <div class="avatar-upload-preview" id="avatarPreview">
                                    <?php if ($avatar_url): ?>
                                        <img src="<?= $avatar_url ?>" alt="Avatar" id="previewImg">
                                    <?php else: ?>
                                        <span id="previewInitial" style="font-weight:700; color:var(--text-secondary);"><?= $avatar_initial ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="avatar-upload-text">
                                    <strong>Click or drag photo here</strong> to <?= $avatar_url ? 'change' : 'upload' ?>
                                </div>
                                <div class="avatar-upload-hint">Supports PNG, JPG, JPEG, WEBP up to 5MB</div>
                                <input type="file" name="avatar" id="avatarInput" accept="image/png, image/jpeg, image/webp, image/gif" onchange="submitAvatarForm()">
                            </div>
                        </form>

                        <!-- Avatar Actions (Edit / Remove) -->
                        <?php if ($avatar_url): ?>
                            <div class="avatar-actions-row">
                                <label for="avatarInput" class="btn-avatar-action btn-avatar-change" style="cursor:pointer;">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    <span>Choose New Photo</span>
                                </label>
                                <form action="<?= site_url('dashboard/remove-avatar') ?>" method="POST" onsubmit="return confirm('Remove your profile photo and reset to default?')" style="margin:0;">
                                    <button type="submit" class="btn-avatar-action btn-avatar-remove">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Remove Photo</span>
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>

                        <!-- Profile Info Form -->
                        <form action="<?= site_url('dashboard/update-profile') ?>" method="POST">
                            <div class="form-group">
                                <label class="form-label" for="full_name">Full Name</label>
                                <input type="text" name="full_name" id="full_name" class="form-input"
                                       value="<?= htmlspecialchars($member['full_name'] ?? '') ?>" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="email">Email</label>
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
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <span>Save Profile Details</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Quick shortcuts row -->
                <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:14px;">
                    <div class="item-card" onclick="document.querySelector('[onclick*=\'events\']').click()" style="cursor:pointer;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div class="stat-icon-wrapper" style="color:var(--primary); background:#eff6ff; border-color:#dbeafe;">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="item-title">Church Events</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);"><?= count($upcoming_events ?? []) ?> upcoming services & conferences →</div>
                            </div>
                        </div>
                    </div>

                    <div class="item-card" onclick="document.querySelector('[onclick*=\'dgroup\']').click()" style="cursor:pointer;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div class="stat-icon-wrapper" style="color:#7e22ce; background:#faf5ff; border-color:#f3e8ff;">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div>
                                <div class="item-title">Discipleship Groups</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);"><?= !empty($current_dgroup) ? 'Enrolled in ' . htmlspecialchars($current_dgroup['name']) : 'Browse available groups →' ?></div>
                            </div>
                        </div>
                    </div>

                    <div class="item-card" onclick="document.querySelector('[onclick*=\'ministry\']').click()" style="cursor:pointer;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div class="stat-icon-wrapper" style="color:var(--success); background:#f0fdf4; border-color:#bbf7d0;">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <div>
                                <div class="item-title">Ministry Volunteers</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);"><?= !empty($current_ministry) ? 'Serving in ' . htmlspecialchars($current_ministry['name']) : 'Find a ministry team →' ?></div>
                            </div>
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
                                                <span class="tag-status approved">Checked In ✓</span>
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
                                        <div class="item-header">
                                            <div class="item-title"><?= htmlspecialchars($min['name']) ?></div>
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
                            <button type="button" onclick="closeEditEventModal()" style="background:transparent; border:none; cursor:pointer; font-size:1.2rem; color:var(--text-muted);">✕</button>
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
                        <div class="section-header-row">
                            <div>
                                <h2 class="section-title">Review Volunteer Applications</h2>
                                <p class="section-desc">Approve or reject ministry team requests</p>
                            </div>
                        </div>

                        <?php if (!empty($all_ministry_apps)): ?>
                            <div class="table-responsive">
                                <table class="clean-table">
                                    <thead>
                                        <tr>
                                            <th>Ministry</th>
                                            <th>Applicant</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($all_ministry_apps as $app): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($app['ministry_name']) ?></strong></td>
                                                <td><?= htmlspecialchars($app['member_name']) ?></td>
                                                <td><span class="tag-status <?= $app['status'] ?>"><?= strtoupper($app['status']) ?></span></td>
                                                <td style="font-size:0.75rem; color:var(--text-muted);"><?= date('M j, Y', strtotime($app['created_at'])) ?></td>
                                                <td>
                                                    <form action="<?= site_url('admin/ministry/update-application') ?>" method="POST" style="display:flex; gap:6px;">
                                                        <input type="hidden" name="app_id" value="<?= (int)$app['id'] ?>">
                                                        <?php if ($app['status'] !== 'approved'): ?>
                                                            <button type="submit" name="status" value="approved" class="btn-action-sm btn-action-approve">Approve</button>
                                                        <?php endif; ?>
                                                        <?php if ($app['status'] !== 'rejected'): ?>
                                                            <button type="submit" name="status" value="rejected" class="btn-action-sm btn-action-reject">Reject</button>
                                                        <?php endif; ?>
                                                    </form>
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
                'events': 'Church Events & RSVP',
                'dgroup': 'Discipleship Groups',
                'ministry': 'Ministries & Volunteering',
                'admin-attendance': 'QR Event Check-in Station',
                'admin-events': 'Event Management',
                'admin-members': 'Members Directory',
                'admin-ministry-apps': 'Volunteer Requests'
            };
            const titleElement = document.getElementById('pageTitle');
            if (titleElement && titles[tabName]) {
                titleElement.textContent = titles[tabName];
            }

            sessionStorage.setItem('activeDashboardTab', tabName);
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

            // 5. Restore active tab
            const savedTab = sessionStorage.getItem('activeDashboardTab');
            if (savedTab) {
                const btn = document.querySelector(`[onclick*="'${savedTab}'"]`) || document.querySelector(`[onclick*="${savedTab}"]`);
                if (btn) {
                    showTab(savedTab, btn);
                }
            }
        });
    </script>
</body>
</html>