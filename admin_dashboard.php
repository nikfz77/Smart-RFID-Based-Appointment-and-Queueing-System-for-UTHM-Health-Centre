<?php
include 'login_check.php';
$host='localhost';$dbname='queue_and_appointment_management';$username='root';$password='';
try{
    $pdo=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",$username,$password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $total_staff=$pdo->query("SELECT COUNT(*) FROM users WHERE role='Staff'")->fetchColumn();
    $total_doctors=$pdo->query("SELECT COUNT(*) FROM users WHERE role='Doctor'")->fetchColumn();
    $doctors_available=$pdo->query("SELECT COUNT(*) FROM users WHERE role='Doctor' AND is_available=1")->fetchColumn();
    $total_patients=$pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
    $total_queue_today=$pdo->query("SELECT COUNT(*) FROM queue WHERE DATE(created_at)=CURDATE()")->fetchColumn();
    $total_appt_today=$pdo->query("SELECT COUNT(*) FROM appointments WHERE DATE(created_at)=CURDATE()")->fetchColumn();
    $logs=$pdo->query("SELECT * FROM system_logs ORDER BY created_at DESC LIMIT 8")->fetchAll(PDO::FETCH_ASSOC);
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS profile_pic VARCHAR(255) NULL DEFAULT NULL");
    $admin_pic_row=$pdo->prepare("SELECT profile_pic FROM users WHERE id=:id");
    $admin_pic_row->execute([':id'=>$_SESSION['user_id']]);
    $admin_pic=$admin_pic_row->fetchColumn();
    $admin_pic_url=$admin_pic?'uploads/profiles/'.$admin_pic:null;
}catch(PDOException $e){
    $total_staff=$total_patients=$total_queue_today=$total_appt_today=0;
    $total_doctors=$doctors_available=0;
    $logs=[];$admin_pic_url=null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — PKU UTHM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        :root{
            --green-950:#052e16;--green-900:#14532d;--green-700:#15803d;
            --green-600:#16a34a;--green-500:#22c55e;--green-100:#dcfce7;--green-50:#f0fdf4;
            --amber-600:#d97706;--amber-100:#fef3c7;
            --red-600:#dc2626;--red-100:#fee2e2;
            --blue-600:#1d5bb5;--blue-50:#eff6ff;--blue-100:#dbeafe;
            --purple-600:#7c3aed;--purple-100:#ede9fe;
            --slate-900:#0f172a;--slate-700:#334155;--slate-500:#64748b;
            --slate-300:#cbd5e1;--slate-200:#e2e8f0;--slate-100:#f1f5f9;--slate-50:#f8fafc;
            --white:#fff;--sidebar-w:232px;--topbar-h:58px;--font:'Plus Jakarta Sans',sans-serif;
        }
        html,body{height:100%;font-family:var(--font);background:var(--slate-100);color:var(--slate-900);font-size:14px}
        .sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--sidebar-w);background:var(--green-900);display:flex;flex-direction:column;z-index:50}
        .sidebar-top{height:var(--topbar-h);display:flex;align-items:center;gap:10px;padding:0 18px;border-bottom:1px solid rgba(255,255,255,.08);flex-shrink:0}
        .logo-mark{width:32px;height:32px;background:var(--green-600);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .logo-text strong{display:block;font-size:13px;font-weight:700;color:#fff}
        .logo-text span{font-size:10.5px;color:rgba(255,255,255,.45)}
        .role-pill{display:inline-block;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);color:#fff;font-size:9px;font-weight:800;letter-spacing:1px;text-transform:uppercase;padding:2px 7px;border-radius:10px;margin-top:3px}
        .sidebar-nav{flex:1;overflow-y:auto;padding:10px 10px 0}.sidebar-nav::-webkit-scrollbar{width:0}
        .nav-section{font-size:9.5px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:rgba(255,255,255,.4);padding:16px 8px 5px}
        .nav-link{display:flex;align-items:center;gap:9px;padding:8px 10px;border-radius:7px;font-size:13px;font-weight:500;color:#fff;text-decoration:none;transition:all .15s;margin-bottom:1px}
        .nav-link:hover{background:rgba(255,255,255,.1)}
        .nav-link.active{background:var(--green-700);font-weight:700}
        .nav-link .ico{font-size:14px;width:18px;text-align:center;flex-shrink:0}
        .sidebar-footer{padding:12px 10px 16px;border-top:1px solid rgba(255,255,255,.08);flex-shrink:0}
        .user-card{display:flex;align-items:center;gap:9px;padding:9px 10px;border-radius:8px;background:rgba(255,255,255,.07)}
        .user-ava{width:30px;height:30px;border-radius:7px;background:var(--green-600);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0;overflow:hidden}
        .user-ava img{width:100%;height:100%;object-fit:cover}
        .user-info strong{display:block;font-size:12px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .user-info span{font-size:10.5px;color:rgba(255,255,255,.4)}
        .topbar{position:fixed;top:0;left:var(--sidebar-w);right:0;height:var(--topbar-h);background:var(--white);border-bottom:1px solid var(--slate-200);display:flex;align-items:center;padding:0 24px;gap:12px;z-index:40}
        .topbar-title{font-size:15px;font-weight:700;color:var(--slate-900)}
        .breadcrumb{font-size:11.5px;color:var(--slate-500);margin-top:1px;display:flex;align-items:center;gap:4px}
        .breadcrumb .sep{color:var(--slate-300)}
        .topbar-right{margin-left:auto;display:flex;align-items:center;gap:8px}
        .topbar-date{font-size:12px;color:var(--slate-500);font-weight:500;padding:5px 12px;background:var(--slate-50);border:1px solid var(--slate-200);border-radius:6px}
        .page{margin-left:var(--sidebar-w);padding-top:var(--topbar-h);min-height:100vh}
        .page-body{padding:22px 26px}
        .page-intro{margin-bottom:18px}
        .page-intro h1{font-size:19px;font-weight:800;color:var(--slate-900);letter-spacing:-.4px}
        .page-intro p{font-size:12.5px;color:var(--slate-500);margin-top:3px}
        .stats-row{display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:18px}
        .stat-card{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;padding:15px 16px;display:flex;align-items:center;gap:12px;transition:box-shadow .2s}
        .stat-card:hover{box-shadow:0 4px 16px rgba(22,163,74,.1)}
        .stat-icon{width:40px;height:40px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .stat-icon.green{background:var(--green-100)}.stat-icon.amber{background:var(--amber-100)}.stat-icon.blue{background:var(--blue-100)}.stat-icon.red{background:var(--red-100)}.stat-icon.purple{background:var(--purple-100)}
        .stat-icon i{font-size:17px}
        .stat-icon.green i{color:var(--green-600)}.stat-icon.amber i{color:var(--amber-600)}.stat-icon.blue i{color:var(--blue-600)}.stat-icon.red i{color:var(--red-600)}.stat-icon.purple i{color:var(--purple-600)}
        .stat-val{font-size:24px;font-weight:800;letter-spacing:-1px;line-height:1}
        .stat-val.green{color:var(--green-600)}.stat-val.amber{color:var(--amber-600)}.stat-val.blue{color:var(--blue-600)}.stat-val.red{color:var(--red-600)}.stat-val.purple{color:var(--purple-600)}
        .stat-lbl{font-size:11.5px;color:var(--slate-500);margin-top:3px;font-weight:500}
        .stat-sub{font-size:11px;color:var(--green-600);font-weight:700;margin-top:1px}
        .content-grid{display:grid;grid-template-columns:240px 1fr;gap:16px}
        .card{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;overflow:hidden}
        .card-head{padding:13px 18px;border-bottom:1px solid var(--slate-100);display:flex;align-items:center;justify-content:space-between}
        .card-head h2{font-size:13px;font-weight:700;color:var(--slate-900)}
        .card-head-meta{font-size:11.5px;color:var(--slate-400)}
        .card-body{padding:14px}
        .q-link{display:flex;align-items:center;gap:9px;padding:9px 11px;border-radius:7px;text-decoration:none;font-size:13px;font-weight:500;color:var(--slate-700);transition:all .15s;border-left:3px solid transparent;margin-bottom:1px}
        .q-link:hover{background:var(--green-50);border-left-color:var(--green-600);color:var(--green-700)}
        .q-link.danger{color:var(--red-600)}.q-link.danger:hover{background:var(--red-100);border-left-color:var(--red-600)}
        .q-link i{font-size:14px;width:16px;text-align:center;flex-shrink:0}
        .logs-table{width:100%;border-collapse:collapse;font-size:13px}
        .logs-table thead tr{background:var(--green-50)}
        .logs-table th{padding:9px 14px;text-align:left;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--green-700);border-bottom:1px solid var(--green-100)}
        .logs-table td{padding:10px 14px;border-bottom:1px solid var(--slate-50);vertical-align:middle}
        .logs-table tbody tr:last-child td{border-bottom:none}
        .logs-table tbody tr:hover{background:var(--green-50)}
        .badge{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:5px;font-size:11px;font-weight:700}
        .badge::before{content:'';width:5px;height:5px;border-radius:50%}
        .badge-login{background:var(--green-100);color:#14532d}.badge-login::before{background:var(--green-600)}
        .badge-logout{background:var(--red-100);color:#7f1d1d}.badge-logout::before{background:var(--red-600)}
        .badge-failed{background:var(--amber-100);color:#92400e}.badge-failed::before{background:var(--amber-600)}
        .badge-default{background:var(--green-100);color:#14532d}.badge-default::before{background:var(--green-600)}
        .view-all{display:block;text-align:right;font-size:12.5px;font-weight:600;color:var(--green-600);text-decoration:none;padding:10px 14px 12px}
        .view-all:hover{text-decoration:underline}
        .empty-state{text-align:center;padding:28px;color:var(--slate-400);font-size:13px}
        @media(max-width:1100px){.stats-row{grid-template-columns:repeat(3,1fr)}.content-grid{grid-template-columns:1fr}}
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-top">
        <div class="logo-mark"><i class="fa-solid fa-hospital" style="color:#fff;font-size:15px"></i></div>
        <div class="logo-text"><strong>PKU UTHM</strong><span>Queue Management System</span><div class="role-pill">Admin</div></div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Admin Panel</div>
        <a href="admin_dashboard.php"        class="nav-link active"><span class="ico"><i class="fa-solid fa-table-columns"></i></span> Dashboard</a>
        <a href="admin_staff_management.php" class="nav-link"><span class="ico"><i class="fa-solid fa-users"></i></span> Staff Management</a>
        <a href="admin_logs.php"             class="nav-link"><span class="ico"><i class="fa-solid fa-clipboard-list"></i></span> System Logs</a>
        <a href="admin_reports.php"          class="nav-link"><span class="ico"><i class="fa-solid fa-chart-bar"></i></span> Reports</a>
        <div class="nav-section">Records</div>
        <a href="admin_patient_list.php"     class="nav-link"><span class="ico"><i class="fa-solid fa-hospital-user"></i></span> Patients</a>
        <div class="nav-section">Display</div>
        <a href="queue_display.html" target="_blank" class="nav-link"><span class="ico"><i class="fa-solid fa-display"></i></span> Public Display</a>
    </nav>
    <div class="sidebar-footer"><div class="user-card">
        <div class="user-ava">
            <?php if($admin_pic_url): ?>
            <img src="<?php echo htmlspecialchars($admin_pic_url); ?>" alt="">
            <?php else: ?>
            <?php echo strtoupper(substr($_SESSION['full_name']??'A',0,1)); ?>
            <?php endif; ?>
        </div>
        <div class="user-info">
            <strong><?php echo htmlspecialchars($_SESSION['full_name']??'Admin'); ?></strong>
            <span>Administrator</span>
        </div>
        <a href="logout.php" style="font-size:14px;color:rgba(255,255,255,.3);text-decoration:none;margin-left:auto;" title="Logout"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
    </div></div>
</aside>

<header class="topbar">
    <div>
        <div class="topbar-title">Admin Dashboard</div>
        <div class="breadcrumb"><span>PKU UTHM</span><span class="sep">›</span><span>Admin Panel</span></div>
    </div>
    <div class="topbar-right"><div class="topbar-date"><?php echo date('D, d M Y'); ?></div></div>
</header>

<div class="page"><div class="page-body">

    <div class="page-intro">
        <h1>System Overview</h1>
        <p><?php echo date('l, d F Y'); ?> — PKU UTHM Queue Management System</p>
    </div>

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon green"><i class="fa-solid fa-users"></i></div>
            <div><div class="stat-val green"><?php echo $total_staff; ?></div><div class="stat-lbl">Total Staff</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber"><i class="fa-solid fa-user-doctor"></i></div>
            <div>
                <div class="stat-val amber"><?php echo $total_doctors; ?></div>
                <div class="stat-lbl">Doctors</div>
                <div class="stat-sub"><?php echo $doctors_available; ?> available now</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><i class="fa-solid fa-hospital-user"></i></div>
            <div><div class="stat-val green"><?php echo $total_patients; ?></div><div class="stat-lbl">Total Patients</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon blue"><i class="fa-solid fa-hourglass-half"></i></div>
            <div><div class="stat-val blue"><?php echo $total_queue_today; ?></div><div class="stat-lbl">Queue Today</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><i class="fa-solid fa-calendar-days"></i></div>
            <div><div class="stat-val purple"><?php echo $total_appt_today; ?></div><div class="stat-lbl">Appointments Today</div></div>
        </div>
    </div>

    <div class="content-grid">
        <div class="card" style="align-self:start">
            <div class="card-head"><h2>Admin Controls</h2></div>
            <div class="card-body">
                <a href="admin_staff_management.php" class="q-link"><i class="fa-solid fa-users"></i> Manage Staff &amp; Doctors</a>
                <a href="admin_logs.php"             class="q-link"><i class="fa-solid fa-clipboard-list"></i> System Logs</a>
                <a href="admin_reports.php"          class="q-link"><i class="fa-solid fa-chart-bar"></i> Reports &amp; Analytics</a>
                <a href="admin_patient_list.php"     class="q-link"><i class="fa-solid fa-hospital-user"></i> Patient List</a>
                <a href="queue_display.html" target="_blank" class="q-link"><i class="fa-solid fa-display"></i> Public Display</a>
                <a href="logout.php"                 class="q-link danger"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</a>
            </div>
        </div>

        <div class="card">
            <div class="card-head">
                <h2>Recent Activity</h2>
                <span class="card-head-meta">Last 8 actions</span>
            </div>
            <?php if(empty($logs)): ?>
            <div class="empty-state">No activity logs yet.</div>
            <?php else: ?>
            <table class="logs-table">
                <thead><tr><th>User</th><th>Action</th><th>Time</th></tr></thead>
                <tbody>
                <?php foreach($logs as $log):
                    $action=$log['action'];
                    $bc='badge-default';
                    if($action==='Login') $bc='badge-login';
                    elseif($action==='Logout') $bc='badge-logout';
                    elseif(stripos($action,'fail')!==false) $bc='badge-failed';
                ?>
                <tr>
                    <td>
                        <div style="font-weight:600;color:var(--slate-900)"><?php echo htmlspecialchars($log['username']); ?></div>
                        <div style="font-size:11px;color:var(--slate-400)"><?php echo htmlspecialchars($log['role']); ?></div>
                    </td>
                    <td><span class="badge <?php echo $bc; ?>"><?php echo htmlspecialchars($action); ?></span></td>
                    <td style="color:var(--slate-400);font-size:12px;white-space:nowrap"><?php echo date('d M, H:i',strtotime($log['created_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <a href="admin_logs.php" class="view-all">View all logs →</a>
            <?php endif; ?>
        </div>
    </div>

</div></div>
</body></html>