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
            --radius-sm:      8px;
            --radius-md:      12px;
            --radius-lg:      16px;
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
            -webkit-font-smoothing: antialiased;
        }

        /* ── Navbar ── */
        .navbar {
            background: #ffffff;
            border-bottom: 1px solid var(--card-border);
            padding: 12px 28px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .nav-brand {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none; color: var(--text-primary);
        }
        .brand-badge {
            width: 34px; height: 34px; border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            background: #ffffff; border: 1px solid var(--card-border);
            overflow: hidden;
        }
        .brand-badge img { width: 100%; height: 100%; object-fit: contain; }
        .brand-title { font-size: 1.05rem; font-weight: 700; letter-spacing: -0.01em; color: var(--text-primary); }
        .brand-title span { color: var(--primary); }

        .nav-right { display: flex; align-items: center; gap: 10px; }
        .nav-link {
            color: var(--text-secondary); font-size: 0.85rem; font-weight: 600;
            text-decoration: none; padding: 7px 12px; border-radius: var(--radius-sm);
            display: inline-flex; align-items: center; gap: 6px;
            transition: all 0.15s ease;
        }
        .nav-link:hover { color: var(--primary); background: var(--primary-light); }
        .nav-link.active { color: var(--primary); background: var(--primary-light); }

        .btn-logout {
            background: var(--danger-light); border: 1px solid var(--danger-border);
            color: var(--danger); padding: 6px 12px; border-radius: var(--radius-sm);
            font-size: 0.82rem; font-weight: 600; text-decoration: none;
            display: inline-flex; align-items: center; gap: 5px;
            transition: all 0.15s ease;
        }
        .btn-logout:hover { background: #fee2e2; }

        /* ── Container ── */
        .page-container {
            max-width: 860px; width: 100%;
            margin: 0 auto; padding: 32px 20px 64px;
            flex: 1;
        }

        .page-header { margin-bottom: 24px; }
        .page-title { font-size: 1.4rem; font-weight: 800; color: var(--text-primary); }
        .page-desc { font-size: 0.875rem; color: var(--text-muted); margin-top: 4px; }

        /* ── Alerts ── */
        .alert {
            padding: 12px 16px; border-radius: var(--radius-md);
            margin-bottom: 20px; font-size: 0.875rem; font-weight: 500;
            display: flex; align-items: center; gap: 10px;
        }
        .alert-success { background: var(--success-light); border: 1px solid var(--success-border); color: #15803d; }
        .alert-error   { background: var(--danger-light); border: 1px solid var(--danger-border); color: #b91c1c; }

        /* ── Submit Card ── */
        .card-box {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            padding: 22px 24px;
            margin-bottom: 28px;
            box-shadow: var(--shadow-sm);
        }
        .card-title { font-size: 1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 16px; }

        .form-group { margin-bottom: 14px; }
        .form-label {
            display: block; font-size: 0.78rem; font-weight: 600;
            color: var(--text-secondary); margin-bottom: 5px;
        }
        .form-input, .form-textarea {
            width: 100%; padding: 9px 12px;
            border: 1px solid var(--card-border);
            border-radius: var(--radius-sm);
            font-size: 0.875rem; color: var(--text-primary);
            background: #ffffff;
            transition: border-color 0.15s ease;
        }
        .form-input:focus, .form-textarea:focus {
            outline: none; border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }
        .form-textarea { min-height: 80px; resize: vertical; }

        .visibility-options { display: flex; align-items: center; gap: 18px; margin-top: 6px; }
        .vis-label { display: inline-flex; align-items: center; gap: 6px; font-size: 0.82rem; font-weight: 600; color: var(--text-secondary); cursor: pointer; }
        .vis-label input { accent-color: var(--primary); }

        .btn-submit {
            background: var(--primary); color: #ffffff;
            border: none; padding: 9px 18px; border-radius: var(--radius-sm);
            font-size: 0.85rem; font-weight: 600; cursor: pointer;
            display: inline-flex; align-items: center; gap: 6px;
            margin-top: 8px;
            transition: background 0.15s ease;
        }
        .btn-submit:hover { background: var(--primary-hover); }

        /* ── Requests List ── */
        .list-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 16px;
        }
        .list-title { font-size: 1.05rem; font-weight: 700; color: var(--text-primary); }
        .badge-count {
            background: #f1f5f9; color: var(--text-secondary);
            padding: 3px 8px; border-radius: 9999px;
            font-size: 0.75rem; font-weight: 600;
        }

        .requests-stack { display: flex; flex-direction: column; gap: 14px; }
        .request-item {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-md);
            padding: 18px 20px;
            box-shadow: var(--shadow-sm);
        }
        .request-item.answered {
            border-left: 3px solid var(--success);
            background: #fafdfc;
        }
        .req-top {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 10px;
        }
        .req-author-info { display: flex; align-items: center; gap: 8px; }
        .req-avatar {
            width: 26px; height: 26px; border-radius: 50%;
            background: #e2e8f0; color: var(--text-secondary);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.72rem; font-weight: 700;
        }
        .req-author { font-size: 0.82rem; font-weight: 600; color: var(--text-primary); }
        .req-date { font-size: 0.72rem; color: var(--text-muted); }

        .tag-pill {
            padding: 2px 7px; border-radius: 4px;
            font-size: 0.7rem; font-weight: 600;
        }
        .tag-public   { background: #f1f5f9; color: var(--text-secondary); }
        .tag-private  { background: var(--warning-light); color: var(--warning); border: 1px solid var(--warning-border); }
        .tag-answered { background: var(--success-light); color: var(--success); border: 1px solid var(--success-border); }

        .req-title { font-size: 0.95rem; font-weight: 700; color: var(--text-primary); margin-bottom: 6px; }
        .req-body  { font-size: 0.85rem; color: var(--text-secondary); line-height: 1.55; }

        .req-actions {
            display: flex; gap: 8px; margin-top: 12px; padding-top: 10px;
            border-top: 1px solid #f1f5f9;
        }
        .btn-action-sm {
            padding: 4px 8px; border-radius: var(--radius-sm);
            font-size: 0.75rem; font-weight: 600; cursor: pointer;
            border: 1px solid transparent; text-decoration: none;
            display: inline-flex; align-items: center; gap: 4px;
        }
        .btn-action-approve { background: var(--success-light); color: #15803d; border-color: var(--success-border); }
        .btn-action-reject  { background: var(--danger-light); color: #b91c1c; border-color: var(--danger-border); }

        @media (max-width: 600px) {
            .navbar { padding: 10px 16px; }
            .page-container { padding: 20px 14px 48px; }
        }
    </style>
</head>
<body>

    <!-- Minimal Navbar -->
    <nav class="navbar">
        <a href="<?= site_url('') ?>" class="nav-brand">
            <div class="brand-badge">
                <img src="<?= site_url('assets/images/lifegen-logo.png') ?>" alt="Logo">
            </div>
            <div class="brand-title">Life <span>Generation</span></div>
        </a>

        <div class="nav-right">
            <a href="<?= site_url('dashboard') ?>" class="nav-link">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Dashboard</span>
            </a>
            <a href="<?= site_url('prayer-wall') ?>" class="nav-link active">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span>Prayer Wall</span>
            </a>
            <a href="<?= site_url('logout') ?>" class="btn-logout">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                <span>Sign Out</span>
            </a>
        </div>
    </nav>

    <div class="page-container">

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
                No prayer requests yet. Be the first to share one,vince.
            </div>
        <?php endif; ?>

    </div>

</body>
</html>
