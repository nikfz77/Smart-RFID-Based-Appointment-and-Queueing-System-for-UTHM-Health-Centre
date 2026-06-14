<?php include 'login_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard — PKU UTHM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --blue-900: #0c2461; --blue-800: #1a3a6b; --blue-700: #1e4fa0; --blue-600: #1d5bb5; --blue-500: #2563eb; --blue-400: #3b82f6; --blue-100: #dbeafe; --blue-50: #eff6ff;
            --green-600: #16a34a; --green-100: #dcfce7; --amber-600: #d97706; --amber-100: #fef3c7; --red-600: #dc2626; --red-100: #fee2e2;
            --slate-900: #0f172a; --slate-700: #334155; --slate-500: #64748b; --slate-300: #cbd5e1; --slate-200: #e2e8f0; --slate-100: #f1f5f9; --slate-50: #f8fafc;
            --white: #ffffff; --sidebar-w: 232px; --topbar-h: 58px; --font: 'Plus Jakarta Sans', sans-serif;
        }
        html, body { height: 100%; font-family: var(--font); background: var(--slate-100); color: var(--slate-900); font-size: 14px; }
        .sidebar { position: fixed; top: 0; left: 0; bottom: 0; width: var(--sidebar-w); background: var(--blue-900); display: flex; flex-direction: column; z-index: 50; border-right: 1px solid rgba(255,255,255,0.06); }
        .sidebar-top { height: var(--topbar-h); display: flex; align-items: center; gap: 10px; padding: 0 18px; border-bottom: 1px solid rgba(255,255,255,0.07); flex-shrink: 0; }
        .logo-mark { width: 32px; height: 32px; background: var(--blue-500); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
        .logo-text strong { display: block; font-size: 13px; font-weight: 700; color: #fff; font-family:'Sora',sans-serif; letter-spacing: -0.2px; }
        .logo-text span { font-size: 10.5px; color: rgba(255,255,255,0.45); font-weight: 500; }
        .sidebar-nav { flex: 1; overflow-y: auto; padding: 10px 10px 0; }
        .sidebar-nav::-webkit-scrollbar { width: 0; }
        .nav-section { font-size: 9.5px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: rgba(255,255,255,0.45); padding: 16px 8px 5px; }
        .nav-link { display: flex; align-items: center; gap: 9px; padding: 8px 10px; border-radius: 7px; font-size: 13px; font-weight: 500; color: #ffffff; text-decoration: none; transition: all 0.15s; margin-bottom: 1px; }
        .nav-link:hover { background: rgba(255,255,255,0.09); color: #ffffff; }
        .nav-link.active { background: var(--blue-600); color: #ffffff; font-weight: 600; }
        .nav-link .ico { font-size: 14px; width: 18px; text-align: center; flex-shrink: 0; }
        .nav-badge { margin-left: auto; background: var(--blue-500); color: #fff; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 10px; min-width: 18px; text-align: center; }
        .sidebar-footer { padding: 12px 10px 16px; border-top: 1px solid rgba(255,255,255,0.07); flex-shrink: 0; }
        .user-card { display: flex; align-items: center; gap: 9px; padding: 9px 10px; border-radius: 8px; background: rgba(255,255,255,0.06); overflow: hidden; }
        .user-ava { width: 30px; height: 30px; border-radius: 7px; background: var(--blue-600); display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #fff; flex-shrink: 0; }
        .user-info { flex: 1; min-width: 0; overflow: hidden; }
        .user-info strong { display: block; font-size: 12px; font-weight: 700; color: #fff; white-space: nowrap; overflow: hidden; text-overflow:ellipsis;max-width:130px; max-width: 120px; }
        .user-info span { font-size: 10.5px; color: rgba(255,255,255,0.4); }
        .topbar { position: fixed; top: 0; left: var(--sidebar-w); right: 0; height: var(--topbar-h); background: var(--white); border-bottom: 1px solid var(--slate-200); display: flex; align-items: center; padding: 0 24px; gap: 12px; z-index: 40; }
        .topbar-title { font-size: 15px; font-weight: 700; color: var(--slate-900); display: flex; align-items: center; gap: 6px; }
        .breadcrumb { font-size: 11.5px; color: var(--slate-500); margin-top: 1px; display: flex; align-items: center; gap: 4px; }
        .breadcrumb a { color: var(--slate-500); text-decoration: none; }
        .breadcrumb a:hover { color: var(--blue-500); }
        .breadcrumb .sep { color: var(--slate-300); }
        .topbar-right { margin-left: auto; display: flex; align-items: center; gap: 8px; }
        .topbar-date { font-size: 12px; color: var(--slate-500); font-weight: 500; padding: 5px 12px; background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: 6px; }
        .icon-btn { width: 34px; height: 34px; border-radius: 7px; border: 1px solid var(--slate-200); background: var(--white); display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 15px; position: relative; transition: background 0.15s; }
        .icon-btn:hover { background: var(--slate-50); border-color: var(--slate-300); }
        .notif-wrap { position: relative; }
        .notif-dropdown { position: absolute; top: 42px; right: 0; width: 296px; background: var(--white); border: 1px solid var(--slate-200); border-radius: 10px; box-shadow: 0 8px 30px rgba(0,0,0,0.1); z-index: 200; display: none; overflow: hidden; }
        .notif-dropdown.show { display: block; animation: popIn 0.18s ease-out; }
        @keyframes popIn { from { opacity:0; transform:translateY(-4px) scale(0.98); } to { opacity:1; transform:translateY(0) scale(1); } }
        .notif-header { padding: 12px 14px; border-bottom: 1px solid var(--slate-100); display: flex; justify-content: space-between; align-items: center; }
        .notif-header strong { font-size: 13px; font-weight: 700; color: var(--slate-900); }
        .notif-header span { font-size: 11.5px; color: var(--slate-500); }
        .notif-item { padding: 10px 14px; border-bottom: 1px solid var(--slate-50); }
        .notif-item:last-child { border-bottom: none; }
        .notif-item-title { font-size: 12.5px; font-weight: 600; color: var(--slate-900); }
        .notif-item-sub { font-size: 11.5px; color: var(--slate-500); margin-top: 2px; }
        .notif-empty { padding: 24px; text-align: center; font-size: 12.5px; color: var(--slate-400); }
        .notif-badge-count { position: absolute; top: -4px; right: -4px; background: var(--red-600); color: #fff; font-size: 9px; font-weight: 800; line-height: 1; padding: 2px 5px; border-radius: 8px; border: 1.5px solid #fff; display: none; }
        .notif-badge-count.show { display: block; }
        .page { margin-left: var(--sidebar-w); padding-top: var(--topbar-h); min-height: 100vh; }
        .page-body { padding: 22px 26px; }
        .page-intro { margin-bottom: 20px; }
        .page-intro h1 { font-size: 20px; font-weight: 800; color: var(--slate-900); letter-spacing: -0.4px; line-height: 1.2; }
        .page-intro p { font-size: 12.5px; color: var(--slate-500); margin-top: 3px; }
        .alert-strip { display: flex; align-items: center; gap: 10px; background: var(--blue-50); border: 1px solid var(--blue-100); border-left: 3px solid var(--blue-500); border-radius: 7px; padding: 10px 14px; font-size: 12.5px; color: var(--blue-700); margin-bottom: 20px; }
        .stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; margin-bottom: 20px; }
        .stat-card { background: var(--white); border: 1px solid var(--slate-200); border-radius: 10px; padding: 16px 18px; display: flex; align-items: center; gap: 14px; transition: box-shadow 0.2s, transform 0.2s; }
        .stat-card:hover { box-shadow: 0 4px 16px rgba(37,99,235,0.08); transform: translateY(-1px); }
        .stat-icon { width: 42px; height: 42px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
        .stat-icon.blue { background: var(--blue-50); color: var(--blue-600); }
        .stat-icon.green { background: var(--green-100); color: var(--green-600); }
        .stat-icon.amber { background: var(--amber-100); color: var(--amber-600); }
        .stat-icon.red { background: var(--red-100); color: var(--red-600); }
        .stat-body { flex: 1; min-width: 0; }
        .stat-val { font-size: 26px; font-weight: 800; letter-spacing: -1px; line-height: 1; }
        .stat-val.blue { color: var(--blue-600); } .stat-val.green { color: var(--green-600); } .stat-val.amber { color: var(--amber-600); } .stat-val.red { color: var(--red-600); }
        .stat-lbl { font-size: 11.5px; color: var(--slate-500); margin-top: 3px; font-weight: 500; }
        .main-grid { display: grid; grid-template-columns: 1fr 280px; gap: 14px; align-items: start; }
        .left-col { display: flex; flex-direction: column; gap: 14px; }
        .card { background: var(--white); border: 1px solid var(--slate-200); border-radius: 10px; overflow: hidden; }
        .card-head { padding: 13px 18px; border-bottom: 1px solid var(--slate-100); display: flex; align-items: center; justify-content: space-between; }
        .card-head h2 { font-size: 13px; font-weight: 700; color: var(--slate-900); display: flex; align-items: center; gap: 7px; }
        .card-head-meta { font-size: 11.5px; color: var(--slate-400); font-weight: 500; }
        .card-body { padding: 16px; }
        .call-btn { width: 100%; padding: 12px 16px; background: var(--green-600); color: #fff; border: none; border-radius: 8px; font-family: var(--font); font-size: 13.5px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: background 0.15s, transform 0.1s, box-shadow 0.15s; margin-bottom: 8px; }
        .call-btn:hover:not(:disabled) { background: #15803d; box-shadow: 0 4px 12px rgba(22,163,74,0.25); transform: translateY(-1px); }
        .call-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        .action-btn { width: 100%; padding: 9px 13px; background: var(--slate-50); color: var(--slate-700); border: 1px solid var(--slate-200); border-radius: 7px; font-family: var(--font); font-size: 12.5px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px; text-decoration: none; transition: all 0.15s; margin-bottom: 5px; }
        .action-btn:hover { background: var(--blue-50); color: var(--blue-600); }
        .status-strip { padding: 9px 13px; border-radius: 7px; font-size: 12.5px; font-weight: 600; margin-top: 8px; display: none; }
        .status-strip.success { background: var(--green-100); color: #14532d; }
        .status-strip.error { background: var(--red-100); color: #7f1d1d; }
        .status-strip.loading { background: var(--amber-100); color: #78350f; }
        .q-table { width: 100%; border-collapse: collapse; }
        .q-table thead tr { background: var(--slate-50); }
        .q-table th { padding: 9px 14px; text-align: left; font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px; color: var(--slate-400); border-bottom: 1px solid var(--slate-100); }
        .q-table td { padding: 10px 14px; font-size: 13px; color: var(--slate-700); border-bottom: 1px solid var(--slate-50); }
        .q-table tbody tr:last-child td { border-bottom: none; }
        .q-table tbody tr:hover { background: var(--slate-50); }
        .q-num { font-weight: 800; font-size: 14px; color: var(--blue-600); }
        .q-name-cell strong { display: block; font-size: 13px; font-weight: 600; color: var(--slate-900); }
        .q-name-cell span { font-size: 11px; color: var(--slate-400); margin-top: 1px; display: block; }
        .print-btn { padding: 4px 10px; font-size: 11px; font-weight: 600; background: var(--slate-100); border: 1px solid var(--slate-200); border-radius: 5px; color: var(--slate-600); cursor: pointer; text-decoration: none; transition: all 0.15s; display: inline-flex; align-items: center; gap: 4px; font-family: var(--font); }
        .print-btn:hover { background: var(--blue-50); color: var(--blue-600); }
        .q-empty { text-align: center; padding: 32px 16px; color: var(--slate-400); }
        .q-empty-icon { font-size: 28px; opacity: 0.4; margin-bottom: 8px; }
        .q-empty p { font-size: 13px; }
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 3px 8px; border-radius: 5px; font-size: 11px; font-weight: 700; }
        .badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; }
        .badge-waiting { background: var(--amber-100); color: #92400e; }
        .badge-waiting::before { background: var(--amber-600); }
        .q-links { display: flex; flex-direction: column; }
        .q-link { display: flex; align-items: center; gap: 9px; padding: 9px 12px; border-radius: 7px; font-size: 12.5px; font-weight: 500; color: var(--slate-700); text-decoration: none; transition: all 0.15s; border-bottom: 1px solid var(--slate-50); }
        .q-link:last-child { border-bottom: none; }
        .q-link:hover { background: var(--blue-50); color: var(--blue-600); }
        .q-link.danger { color: var(--red-600); }
        .q-link.danger:hover { background: var(--red-100); }
        .q-link .ico { font-size: 14px; width: 16px; text-align: center; }
        .toast-wrap { position: fixed; bottom: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 8px; }
        .toast { background: var(--white); border: 1px solid var(--slate-200); border-left: 3px solid var(--green-600); border-radius: 9px; padding: 11px 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); font-size: 12.5px; max-width: 260px; animation: toastSlide 0.25s ease-out; }
        @keyframes toastSlide { from { opacity:0; transform:translateX(20px); } to { opacity:1; transform:translateX(0); } }
        .toast strong { display: block; font-size: 13px; font-weight: 700; color: var(--slate-900); margin-bottom: 2px; }
        .toast span { color: var(--slate-500); }
        .spinner { display: inline-block; width: 12px; height: 12px; border: 2px solid rgba(255,255,255,0.3); border-radius: 50%; border-top-color: #fff; animation: spin 0.7s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .stat-card { opacity: 0; animation: fadeUp 0.35s ease-out forwards; }
        .stat-card:nth-child(1) { animation-delay: 0.05s; } .stat-card:nth-child(2) { animation-delay: 0.10s; } .stat-card:nth-child(3) { animation-delay: 0.15s; } .stat-card:nth-child(4) { animation-delay: 0.20s; }
        .card { opacity: 0; animation: fadeUp 0.35s ease-out 0.2s forwards; }
        @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    
        .material-symbols-outlined {
            font-size: 18px;
            line-height: 1;
            vertical-align: middle;
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .nav-link .material-symbols-outlined { font-size: 16px; }
        .stat-icon .material-symbols-outlined { font-size: 20px; }
        .logo-mark .material-symbols-outlined { font-size: 18px; color: #fff; }
</style>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-top">
        <div class="logo-mark"><span class="material-symbols-outlined">local_hospital</span></div>
        <div class="logo-text"><strong>PKU UTHM</strong><span>Queue Management System</span></div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Main</div>
        <a href="staff_dashboard.php" class="nav-link active"><span class="ico"><span class="material-symbols-outlined">dashboard</span></span> Dashboard</a>
        <a href="queue_management.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">format_list_numbered</span></span> Queue Management <span class="nav-badge" id="sidebar-badge">0</span></a>
        <a href="appointment_management.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">event_available</span></span> Appointments</a>
        <div class="nav-section">Records</div>
        <a href="patient_list.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">personal_injury</span></span> Patients</a>
        <a href="register_user_form.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">person_add</span></span> Register Patient</a>
        <a href="walkin_form.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">subdirectory_arrow_right</span></span> Walk-In</a>
        <a href="reports.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">bar_chart</span></span> Reports</a>
        <div class="nav-section">Display</div>
        <a href="queue_display.html" target="_blank" class="nav-link"><span class="ico"><span class="material-symbols-outlined">monitor</span></span> Public Display</a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-ava"><?php echo strtoupper(substr($_SESSION['full_name']??'S',0,1)); ?></div>
            <div class="user-info">
                <strong><?php echo htmlspecialchars($_SESSION['full_name']??'Staff'); ?></strong>
                <span><?php echo ucfirst($_SESSION['role']??'Staff'); ?></span>
            </div>
            <a href="logout.php" style="font-size:14px;color:rgba(255,255,255,0.3);text-decoration:none;margin-left:4px;" title="Logout"><span class="material-symbols-outlined">logout</span></a>
        </div>
    </div>
</aside>
<header class="topbar">
    <div>
        <div class="topbar-title">Dashboard</div>
        <div class="breadcrumb"><a href="#">Home</a><span class="sep">›</span><span>Staff Dashboard</span></div>
    </div>
    <div class="topbar-right">
        <div class="topbar-date"><?php echo date('D, d M Y'); ?></div>
        <div class="notif-wrap">
            <button class="icon-btn" onclick="toggleNotif()" style="position:relative">
                <span class="material-symbols-outlined">notifications</span>
                <span class="notif-badge-count" id="notif-badge">0</span>
            </button>
            <div class="notif-dropdown" id="notif-dropdown">
                <div class="notif-header"><strong>Notifications</strong><span id="notif-count-label">No new alerts</span></div>
                <div id="notif-list"><div class="notif-empty">No new walk-ins</div></div>
            </div>
        </div>
        <button class="icon-btn" onclick="fetchQueue()" title="Refresh"><span class="material-symbols-outlined">refresh</span></button>
    </div>
</header>
<div class="page"><div class="page-body">
    <div class="page-intro">
        <h1>Good <?php $h=date('H'); echo $h<12?'morning':($h<17?'afternoon':'evening'); ?>, <?php echo htmlspecialchars(explode(' ',$_SESSION['full_name']??'Staff')[0]); ?> <span class="material-symbols-outlined" style="font-size:18px;color:var(--amber-600)">waving_hand</span></h1>
        <p><?php echo date('l, d F Y'); ?> — Here's a summary of today's clinic activity.</p>
    </div>
    <div class="alert-strip"><span class="material-symbols-outlined">info</span>&nbsp; <span>Walk-in queue and appointment data updates every 15 seconds automatically.</span></div>
    <div class="stats-row">
        <div class="stat-card"><div class="stat-icon blue"><span class="material-symbols-outlined">hourglass_empty</span></div><div class="stat-body"><div class="stat-val blue" id="total-waiting">—</div><div class="stat-lbl">Patients Waiting</div></div></div>
        <div class="stat-card"><div class="stat-icon green"><span class="material-symbols-outlined">check</span></div><div class="stat-body"><div class="stat-val green" id="total-served">—</div><div class="stat-lbl">Served Today</div></div></div>
        <div class="stat-card"><div class="stat-icon amber"><span class="material-symbols-outlined">calendar_month</span></div><div class="stat-body"><div class="stat-val amber" id="total-appointments">—</div><div class="stat-lbl">Total Today</div></div></div>
        <div class="stat-card"><div class="stat-icon red"><span class="material-symbols-outlined">stethoscope</span></div><div class="stat-body"><div class="stat-val red" id="being-served">—</div><div class="stat-lbl">Being Served</div></div></div>
    </div>
    <div id="availability-panel"><?php include 'doctor_availability_panel.php'; ?></div>
    <div class="main-grid">
        <div class="left-col">
            <div class="card">
                <div class="card-head"><h2><span class="material-symbols-outlined">bolt</span> Queue Control</h2></div>
                <div class="card-body">
                    <button class="call-btn" id="call-next-btn" onclick="callNextPatient()"><span id="btn-text"><span class="material-symbols-outlined">campaign</span> Call Next Patient</span></button>
                    <a href="queue_management.php" class="action-btn"><span class="material-symbols-outlined">format_list_numbered</span> &nbsp;Full Queue View</a>
                    <a href="queue_display.html" target="_blank" class="action-btn"><span class="material-symbols-outlined">monitor</span> &nbsp;Open Public Display</a>
                    <div class="status-strip" id="status-message"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-head"><h2><span class="material-symbols-outlined">hourglass_empty</span> Waiting Queue</h2><span class="card-head-meta" id="queue-count">Loading…</span></div>
                <div style="overflow-x:auto">
                    <table class="q-table">
                        <thead><tr><th>Queue No.</th><th>Patient</th><th>Service</th><th>Status</th><th></th></tr></thead>
                        <tbody id="queue-tbody"><tr><td colspan="5"><div class="q-empty"><div class="q-empty-icon"><span class="material-symbols-outlined">hourglass_empty</span></div><p>Loading queue data…</p></div></td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card" style="align-self:start">
            <div class="card-head"><h2><span class="material-symbols-outlined">link</span> Quick Actions</h2></div>
            <div class="q-links">
                <a href="appointment_management.php" class="q-link"><span class="ico"><span class="material-symbols-outlined">calendar_month</span></span> Manage Appointments</a>
                <a href="walkin_form.php" class="q-link"><span class="ico"><span class="material-symbols-outlined">subdirectory_arrow_right</span></span> Register Walk-In</a>
                <a href="queue_management.php" class="q-link"><span class="ico"><span class="material-symbols-outlined">format_list_numbered</span></span> Queue Management</a>
                <a href="reports.php" class="q-link"><span class="ico"><span class="material-symbols-outlined">bar_chart</span></span> Reports &amp; Analytics</a>
                <a href="patient_list.php" class="q-link"><span class="ico"><span class="material-symbols-outlined">personal_injury</span></span> Patient List</a>
                <a href="register_user_form.php" class="q-link"><span class="ico"><span class="material-symbols-outlined">person_add</span></span> Register Patient</a>
                <a href="logout.php" class="q-link danger"><span class="ico"><span class="material-symbols-outlined">logout</span></span> Logout</a>
            </div>
        </div>
    </div>
</div></div>
<div class="toast-wrap" id="toast-wrap"></div>
<script>
const statusMsg = document.getElementById('status-message');
let lastChecked = new Date().toISOString().slice(0,19).replace('T',' ');
let pendingNotifs = [], notifOpen = false;
function callNextPatient() {
    const btn = document.getElementById('call-next-btn'), text = document.getElementById('btn-text');
    btn.disabled = true; text.innerHTML = '<span class="spinner"></span> Calling…';
    showStatus('Calling next patient…', 'loading');
    fetch('call_next_patient.php', { method: 'POST' }).then(r => r.json())
        .then(data => { showStatus(data.message, data.success ? 'success' : 'error'); fetchQueue(); })
        .catch(() => showStatus('Network error. Please try again.', 'error'))
        .finally(() => { btn.disabled = false; text.innerHTML = '<span class="material-symbols-outlined">campaign</span> Call Next Patient'; });
}
function fetchQueue() {
    fetch('get_queue_status.php').then(r => r.json()).then(data => {
        const waiting = data.filter(i => i.queue_status === 'Waiting');
        const served  = data.filter(i => i.queue_status === 'Completed');
        const serving = data.filter(i => i.queue_status === 'Being-Served');
        document.getElementById('total-waiting').textContent = waiting.length;
        document.getElementById('total-served').textContent = served.length;
        document.getElementById('being-served').textContent = serving.length;
        document.getElementById('total-appointments').textContent = data.length;
        document.getElementById('queue-count').textContent = waiting.length + ' waiting';
        document.getElementById('sidebar-badge').textContent = waiting.length;
        const tbody = document.getElementById('queue-tbody');
        if (!waiting.length) { tbody.innerHTML = `<tr><td colspan="5"><div class="q-empty"><div class="q-empty-icon"><span class="material-symbols-outlined">check_circle</span></div><p>No patients waiting right now.</p></div></td></tr>`; return; }
        tbody.innerHTML = waiting.slice(0, 8).map(p => {
            const name = p.student_name || p.matrix_number || 'Unknown';
            const svc = p.service || p.service_type || 'General';
            const url = `print_ticket.php?queue_number=${p.queue_number}&matrix_number=${encodeURIComponent(p.matrix_number)}&service=${encodeURIComponent(svc)}&student_name=${encodeURIComponent(name)}`;
            return `<tr><td><span class="q-num">${p.queue_number}</span></td><td class="q-name-cell"><strong>${escHtml(name)}</strong><span>${escHtml(p.matrix_number||'')}</span></td><td>${escHtml(svc)}</td><td><span class="badge badge-waiting">Waiting</span></td><td><a href="${url}" target="_blank" class="print-btn"><span class="material-symbols-outlined">print</span> Ticket</a></td></tr>`;
        }).join('');
    }).catch(() => {});
}
function showStatus(msg, type) { statusMsg.textContent = msg; statusMsg.className = 'status-strip ' + type; statusMsg.style.display = 'block'; if (type === 'success') setTimeout(() => statusMsg.style.display = 'none', 4000); }
function fetchNotifs() {
    fetch(`get_notifications.php?last_checked=${encodeURIComponent(lastChecked)}`).then(r => r.json()).then(data => {
        if (!data.success) return; lastChecked = data.server_time;
        if (data.count > 0) { pendingNotifs = [...data.notifications, ...pendingNotifs].slice(0, 20); updateBadge(pendingNotifs.length); renderDropdown(); data.notifications.forEach(n => showToast(n.queue_number, n.name, n.service)); }
    }).catch(() => {});
}
function updateBadge(n) { const b = document.getElementById('notif-badge'); b.textContent = n > 9 ? '9+' : n; n > 0 ? b.classList.add('show') : b.classList.remove('show'); }
function renderDropdown() {
    document.getElementById('notif-count-label').textContent = pendingNotifs.length ? `${pendingNotifs.length} new` : 'No new alerts';
    document.getElementById('notif-list').innerHTML = pendingNotifs.length
        ? pendingNotifs.map(n => `<div class="notif-item"><div class="notif-item-title"><span class="material-symbols-outlined">directions_walk</span> ${n.queue_number} — ${escHtml(n.name)}</div><div class="notif-item-sub">${escHtml(n.service)} · ${n.time}</div></div>`).join('')
        : '<div class="notif-empty">No new walk-ins</div>';
}
function toggleNotif() { notifOpen = !notifOpen; document.getElementById('notif-dropdown').classList.toggle('show', notifOpen); if (notifOpen) { pendingNotifs = []; updateBadge(0); renderDropdown(); } }
document.addEventListener('click', e => { if (!e.target.closest('.notif-wrap')) { document.getElementById('notif-dropdown').classList.remove('show'); notifOpen = false; } });
function showToast(q, name, svc) { const wrap = document.getElementById('toast-wrap'); const t = document.createElement('div'); t.className = 'toast'; t.innerHTML = `<strong><span class="material-symbols-outlined">directions_walk</span> New walk-in — ${q}</strong><span>${escHtml(name)} · ${escHtml(svc)}</span>`; wrap.appendChild(t); setTimeout(() => t.remove(), 6000); }
function escHtml(s) { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
fetchQueue(); fetchNotifs(); setInterval(fetchQueue, 15000); setInterval(fetchNotifs, 15000);
function refreshAvailability() { fetch('doctor_availability_panel.php').then(r => r.text()).then(html => { document.getElementById('availability-panel').innerHTML = html; }).catch(() => {}); }
setInterval(refreshAvailability, 15000);
</script>
</body></html>