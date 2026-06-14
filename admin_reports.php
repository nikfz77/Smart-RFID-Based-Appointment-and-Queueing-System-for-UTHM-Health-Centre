<?php
include 'login_check.php';
date_default_timezone_set('Asia/Kuala_Lumpur');
$host='localhost';$dbname='queue_and_appointment_management';$db_user='root';$db_pass='';
$filter_type=$_GET['filter_type']??'today';
$filter_from=$_GET['date_from']??date('Y-m-d');
$filter_to=$_GET['date_to']??date('Y-m-d');
if($filter_type==='today')        {$filter_from=$filter_to=date('Y-m-d');}
elseif($filter_type==='yesterday'){$filter_from=$filter_to=date('Y-m-d',strtotime('-1 day'));}
elseif($filter_type==='week')     {$filter_from=date('Y-m-d',strtotime('monday this week'));$filter_to=date('Y-m-d');}
elseif($filter_type==='month')    {$filter_from=date('Y-m-01');$filter_to=date('Y-m-d');}
$label=match($filter_type){'today'=>'Today — '.date('d F Y'),'yesterday'=>'Yesterday — '.date('d F Y',strtotime('-1 day')),'week'=>'This Week ('.date('d M',strtotime('monday this week')).' – '.date('d M Y').')','month'=>'This Month — '.date('F Y'),'custom'=>date('d M Y',strtotime($filter_from)).' – '.date('d M Y',strtotime($filter_to)),default=>date('d F Y')};
try{
    $pdo=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",$db_user,$db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $tq=$pdo->prepare("SELECT COUNT(*) FROM queue WHERE DATE(created_at) BETWEEN :f AND :t");$tq->execute([':f'=>$filter_from,':t'=>$filter_to]);$total_queue=$tq->fetchColumn();
    $cq=$pdo->prepare("SELECT COUNT(*) FROM queue WHERE DATE(created_at) BETWEEN :f AND :t AND queue_status='Completed'");$cq->execute([':f'=>$filter_from,':t'=>$filter_to]);$completed=$cq->fetchColumn();
    $xq=$pdo->prepare("SELECT COUNT(*) FROM queue WHERE DATE(created_at) BETWEEN :f AND :t AND queue_status='Cancelled'");$xq->execute([':f'=>$filter_from,':t'=>$filter_to]);$cancelled=$xq->fetchColumn();
    $ta=$pdo->prepare("SELECT COUNT(*) FROM appointments WHERE DATE(schedule_time) BETWEEN :f AND :t");$ta->execute([':f'=>$filter_from,':t'=>$filter_to]);$total_appt=$ta->fetchColumn();
    $ca=$pdo->prepare("SELECT COUNT(*) FROM appointments WHERE DATE(schedule_time) BETWEEN :f AND :t AND status='Confirmed'");$ca->execute([':f'=>$filter_from,':t'=>$filter_to]);$confirmed_appt=$ca->fetchColumn();
    $total_students=$pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
    $daily=$pdo->prepare("SELECT DATE(created_at) as date,COUNT(*) as count FROM queue WHERE DATE(created_at) BETWEEN :f AND :t GROUP BY DATE(created_at) ORDER BY date ASC");$daily->execute([':f'=>$filter_from,':t'=>$filter_to]);$daily_queue=$daily->fetchAll(PDO::FETCH_ASSOC);
    $svc=$pdo->prepare("SELECT service_type,COUNT(*) as count FROM queue WHERE DATE(created_at) BETWEEN :f AND :t GROUP BY service_type ORDER BY count DESC");$svc->execute([':f'=>$filter_from,':t'=>$filter_to]);$service_stats=$svc->fetchAll(PDO::FETCH_ASSOC);
    $sts=$pdo->prepare("SELECT queue_status,COUNT(*) as count FROM queue WHERE DATE(created_at) BETWEEN :f AND :t GROUP BY queue_status");$sts->execute([':f'=>$filter_from,':t'=>$filter_to]);$status_stats=$sts->fetchAll(PDO::FETCH_ASSOC);
    $fac=$pdo->prepare("SELECT s.faculty,COUNT(*) as count FROM queue q JOIN students s ON q.matrix_number=s.matrix_number WHERE DATE(q.created_at) BETWEEN :f AND :t GROUP BY s.faculty ORDER BY count DESC");$fac->execute([':f'=>$filter_from,':t'=>$filter_to]);$faculty_stats=$fac->fetchAll(PDO::FETCH_ASSOC);
    $asvc=$pdo->prepare("SELECT service_type,COUNT(*) as count FROM appointments WHERE DATE(schedule_time) BETWEEN :f AND :t GROUP BY service_type ORDER BY count DESC");$asvc->execute([':f'=>$filter_from,':t'=>$filter_to]);$appt_service=$asvc->fetchAll(PDO::FETCH_ASSOC);
    $rec=$pdo->prepare("SELECT q.queue_number,q.service_type,q.queue_status,q.created_at,q.assigned_room,s.full_name as student_name,s.faculty,u.full_name as doctor_name FROM queue q LEFT JOIN students s ON q.matrix_number=s.matrix_number LEFT JOIN users u ON q.assigned_doctor_id=u.id WHERE DATE(q.created_at) BETWEEN :f AND :t ORDER BY q.created_at DESC LIMIT 20");$rec->execute([':f'=>$filter_from,':t'=>$filter_to]);$recent=$rec->fetchAll(PDO::FETCH_ASSOC);
    $al=$pdo->prepare("SELECT a.matrix_number,a.student_name,a.service_type,a.schedule_time,a.status,a.assigned_room,u.full_name as doctor_name FROM appointments a LEFT JOIN users u ON a.assigned_doctor_id=u.id WHERE DATE(a.schedule_time) BETWEEN :f AND :t ORDER BY a.schedule_time DESC LIMIT 20");$al->execute([':f'=>$filter_from,':t'=>$filter_to]);$appt_list=$al->fetchAll(PDO::FETCH_ASSOC);
    $appt_status=$pdo->prepare("SELECT status,COUNT(*) as count FROM appointments WHERE DATE(schedule_time) BETWEEN :f AND :t GROUP BY status");$appt_status->execute([':f'=>$filter_from,':t'=>$filter_to]);$appt_status=$appt_status->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){$total_queue=$completed=$cancelled=$total_appt=$confirmed_appt=$total_students=0;$daily_queue=$service_stats=$status_stats=$faculty_stats=$appt_service=$recent=$appt_list=$appt_status=[];}
$chart_labels=json_encode(array_map(fn($r)=>date('d/m',strtotime($r['date'])),$daily_queue));
$chart_data=json_encode(array_column($daily_queue,'count'));
$svc_labels=json_encode(array_column($service_stats,'service_type'));
$svc_data=json_encode(array_column($service_stats,'count'));
$status_labels=json_encode(array_column($status_stats,'queue_status'));
$status_data=json_encode(array_column($status_stats,'count'));
$appt_svc_labels=json_encode(array_column($appt_service,'service_type'));
$appt_svc_data=json_encode(array_column($appt_service,'count'));
?>
<!DOCTYPE html><html lang="en"><head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reports — PKU UTHM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        :root{--green-900:#14532d;--blue-700:#1e4fa0;--blue-600:#1d5bb5;--blue-500:#2563eb;--blue-50:#eff6ff;--blue-100:#dbeafe;--green-600:#16a34a;--green-100:#dcfce7;--amber-600:#d97706;--amber-100:#fef3c7;--red-600:#dc2626;--red-100:#fee2e2;--purple-600:#7c3aed;--purple-100:#ede9fe;--slate-900:#0f172a;--slate-700:#334155;--slate-500:#64748b;--slate-300:#cbd5e1;--slate-200:#e2e8f0;--slate-100:#f1f5f9;--slate-50:#f8fafc;--white:#fff;--sidebar-w:232px;--topbar-h:58px;--font:'Plus Jakarta Sans',sans-serif}
        html,body{font-family:var(--font);background:var(--slate-100);color:var(--slate-900);font-size:14px}
        .sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--sidebar-w);background:var(--green-900);display:flex;flex-direction:column;z-index:50}
        .sidebar-top{height:var(--topbar-h);display:flex;align-items:center;gap:10px;padding:0 18px;border-bottom:1px solid rgba(255,255,255,.07);flex-shrink:0}
        .logo-mark{width:32px;height:32px;background:var(--green-600);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;color:#fff}
        .logo-text strong{display:block;font-size:13px;font-weight:700;color:#fff}.logo-text span{font-size:10.5px;color:rgba(255,255,255,.45)}.role-pill{display:inline-block;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);color:#fff;font-size:9px;font-weight:800;letter-spacing:1px;text-transform:uppercase;padding:2px 7px;border-radius:10px;margin-top:3px}
        .sidebar-nav{flex:1;overflow-y:auto;padding:10px 10px 0}.sidebar-nav::-webkit-scrollbar{width:0}
        .nav-section{font-size:9.5px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:rgba(255,255,255,.45);padding:16px 8px 5px}
        .nav-link{display:flex;align-items:center;gap:9px;padding:8px 10px;border-radius:7px;font-size:13px;font-weight:500;color:#fff;text-decoration:none;transition:all .15s;margin-bottom:1px}
        .nav-link:hover{background:rgba(255,255,255,.09)}.nav-link.active{background:var(--blue-600);font-weight:600}
        .nav-link .ico{font-size:14px;width:18px;text-align:center;flex-shrink:0}
        .sidebar-footer{padding:12px 10px 16px;border-top:1px solid rgba(255,255,255,.07);flex-shrink:0}
        .user-card{display:flex;align-items:center;gap:9px;padding:9px 10px;border-radius:8px;background:rgba(255,255,255,.06)}
        .user-ava{width:30px;height:30px;border-radius:7px;background:var(--blue-600);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0}
        .user-info strong{display:block;font-size:12px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .user-info span{font-size:10.5px;color:rgba(255,255,255,.4)}
        .topbar{position:fixed;top:0;left:var(--sidebar-w);right:0;height:var(--topbar-h);background:var(--white);border-bottom:1px solid var(--slate-200);display:flex;align-items:center;padding:0 24px;gap:12px;z-index:40}
        .topbar-title{font-size:15px;font-weight:700;color:var(--slate-900)}.breadcrumb{font-size:11.5px;color:var(--slate-500);margin-top:1px;display:flex;align-items:center;gap:4px}
        .breadcrumb a{color:var(--slate-500);text-decoration:none}.breadcrumb .sep{color:var(--slate-300)}
        .topbar-right{margin-left:auto;display:flex;align-items:center;gap:8px}
        .topbar-date{font-size:12px;color:var(--slate-500);padding:5px 12px;background:var(--slate-50);border:1px solid var(--slate-200);border-radius:6px}
        .btn{display:inline-flex;align-items:center;gap:7px;padding:8px 14px;border-radius:8px;font-family:var(--font);font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .15s;text-decoration:none}
        .btn-outline{background:var(--slate-50);color:var(--slate-700);border:1px solid var(--slate-200)}.btn-outline:hover{background:var(--blue-50);color:var(--blue-600)}
        .page{margin-left:var(--sidebar-w);padding-top:var(--topbar-h);min-height:100vh}
        .page-body{padding:22px 26px}
        .page-intro{margin-bottom:16px}
        .page-intro h1{font-size:19px;font-weight:800;color:var(--slate-900);letter-spacing:-.4px}
        .page-intro p{font-size:12.5px;color:var(--slate-500);margin-top:3px}
        .filter-card{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;padding:14px 18px;margin-bottom:16px}
        .filter-card h3{font-size:12.5px;font-weight:700;color:var(--slate-900);margin-bottom:12px;display:flex;align-items:center;gap:7px}
        .filter-chips{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:10px}
        .chip{padding:7px 14px;border:1.5px solid var(--slate-200);border-radius:7px;font-size:12.5px;font-weight:600;cursor:pointer;background:var(--white);color:var(--slate-600);font-family:var(--font);transition:all .15s}
        .chip:hover,.chip.active{background:var(--blue-600);color:#fff;border-color:var(--blue-600)}
        .custom-dates{display:none;gap:10px;flex-wrap:wrap;align-items:flex-end}
        .custom-dates.show{display:flex}
        .fg-inline label{font-size:11px;font-weight:700;color:var(--slate-500);display:block;margin-bottom:4px;text-transform:uppercase;letter-spacing:.4px}
        .fg-inline input{padding:8px 11px;border:1.5px solid var(--slate-200);border-radius:7px;font-size:13px;font-family:var(--font)}
        .fg-inline input:focus{border-color:var(--blue-500);outline:none}
        .apply-btn{padding:8px 18px;background:var(--blue-600);color:white;border:none;border-radius:7px;font-size:12.5px;font-weight:700;cursor:pointer;font-family:var(--font)}
        .stats-row{display:grid;grid-template-columns:repeat(6,1fr);gap:10px;margin-bottom:16px}
        .stat-card{background:var(--white);border:1px solid var(--slate-200);border-radius:9px;padding:13px 14px;display:flex;align-items:center;gap:10px}
        .stat-icon{width:36px;height:36px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0}
        .stat-icon.blue{background:var(--blue-50);color:var(--blue-600)}.stat-icon.green{background:var(--green-100);color:var(--green-600)}.stat-icon.amber{background:var(--amber-100);color:var(--amber-600)}.stat-icon.red{background:var(--red-100);color:var(--red-600)}.stat-icon.purple{background:var(--purple-100);color:var(--purple-600)}
        .stat-val{font-size:22px;font-weight:800;letter-spacing:-1px;line-height:1}
        .stat-val.blue{color:var(--blue-600)}.stat-val.green{color:var(--green-600)}.stat-val.amber{color:var(--amber-600)}.stat-val.red{color:var(--red-600)}.stat-val.purple{color:var(--purple-600)}
        .stat-lbl{font-size:11px;color:var(--slate-500);margin-top:2px}
        .section-title{font-size:14px;font-weight:800;color:var(--slate-900);margin:18px 0 12px;display:flex;align-items:center;gap:8px;border-left:3px solid var(--blue-500);padding-left:10px}
        .charts-grid{display:grid;grid-template-columns:2fr 1fr;gap:14px;margin-bottom:14px}
        .two-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px}
        .chart-card{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;padding:18px}
        .chart-card h3{font-size:12.5px;font-weight:700;color:var(--slate-900);margin-bottom:12px}
        .chart-box{position:relative;height:210px}
        .table-card{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;overflow:hidden;margin-bottom:14px}
        .table-head{padding:12px 18px;border-bottom:1px solid var(--slate-100);display:flex;justify-content:space-between;align-items:center;background:var(--blue-50)}
        .table-head h3{font-size:13px;font-weight:700;color:var(--blue-700);display:flex;align-items:center;gap:7px}.table-head span{font-size:11.5px;color:var(--blue-600)}
        .data-table{width:100%;border-collapse:collapse}
        .data-table thead tr{background:var(--slate-50)}
        .data-table th{padding:8px 13px;text-align:left;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--slate-400);border-bottom:1px solid var(--slate-100)}
        .data-table td{padding:9px 13px;font-size:12.5px;color:var(--slate-700);border-bottom:1px solid var(--slate-50)}
        .data-table tbody tr:last-child td{border-bottom:none}.data-table tbody tr:hover{background:var(--slate-50)}
        .badge{display:inline-flex;align-items:center;gap:4px;padding:2px 7px;border-radius:4px;font-size:10.5px;font-weight:700}
        .badge::before{content:'';width:5px;height:5px;border-radius:50%}
        .badge-Waiting,.badge-Pending{background:var(--amber-100);color:#92400e}.badge-Waiting::before,.badge-Pending::before{background:var(--amber-600)}
        .badge-Being-Served,.badge-Confirmed{background:var(--blue-50);color:var(--blue-700)}.badge-Being-Served::before,.badge-Confirmed::before{background:var(--blue-500)}
        .badge-Completed{background:var(--green-100);color:#14532d}.badge-Completed::before{background:var(--green-600)}
        .badge-Cancelled{background:var(--red-100);color:#7f1d1d}.badge-Cancelled::before{background:var(--red-600)}
        .no-data{text-align:center;padding:20px;color:var(--slate-400);font-size:13px}
        @media print{.sidebar,.topbar,.filter-card{display:none!important}body{background:white!important}.page{margin-left:0;padding-top:0}}
        @media(max-width:1200px){.stats-row{grid-template-columns:repeat(3,1fr)}.charts-grid{grid-template-columns:1fr}.two-grid{grid-template-columns:1fr}}
    </style>
</head><body>
<aside class="sidebar">
    <div class="sidebar-top">
        <div class="logo-mark"><i class="fa-solid fa-house-medical"></i></div>
        <div class="logo-text"><strong>PKU UTHM</strong><span>Queue Management System</span><div class="role-pill">Admin</div></div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Main</div>
        <a href="admin_dashboard.php" class="nav-link"><span class="ico"><i class="fa-solid fa-gauge"></i></span> Dashboard</a>
        <a href="admin_staff_management.php" class="nav-link"><span class="ico"><i class="fa-solid fa-user-gear"></i></span> Staff Management</a>
        <a href="admin_logs.php" class="nav-link"><span class="ico"><i class="fa-solid fa-file-lines"></i></span> System Logs</a>
        <a href="admin_reports.php" class="nav-link active"><span class="ico"><i class="fa-solid fa-chart-bar"></i></span> Reports</a>
        <div class="nav-section">View</div>
        <a href="queue_display.html" target="_blank" class="nav-link"><span class="ico"><i class="fa-solid fa-display"></i></span> Public Display</a>
        <a href="admin_student_list.php" class="nav-link"><span class="ico"><i class="fa-solid fa-user-graduate"></i></span> Students</a>
    </nav>
    <div class="sidebar-footer"><div class="user-card">
        <div class="user-ava"><?php echo strtoupper(substr($_SESSION['full_name']??'S',0,1)); ?></div>
        <div class="user-info">
            <strong><?php echo htmlspecialchars($_SESSION['full_name']??'Staff'); ?></strong>
            <span><?php echo ucfirst($_SESSION['role']??'Staff'); ?></span>
        </div>
        <a href="logout.php" style="font-size:14px;color:rgba(255,255,255,.3);text-decoration:none;margin-left:4px;" title="Logout"><i class="fa-solid fa-right-from-bracket"></i></a>
    </div></div>
</aside>
<header class="topbar">
    <div><div class="topbar-title">Admin Reports</div><div class="breadcrumb"><a href="admin_dashboard.php">Dashboard</a><span class="sep">›</span><span>Reports</span></div></div>
    <div class="topbar-right">
        <div class="topbar-date"><?php echo date('D, d M Y'); ?></div>
        <button class="btn btn-outline" onclick="window.print()"><i class="fa-solid fa-print"></i> Print</button>
    </div>
</header>
<div class="page"><div class="page-body">
    <div class="page-intro"><h1>Reports &amp; Analytics</h1><p>Data for: <strong><?php echo $label; ?></strong></p></div>

    <div class="filter-card">
        <h3><i class="fa-solid fa-calendar-days" style="color:var(--blue-500)"></i> Filter Period</h3>
        <form method="GET" id="filter-form">
            <div class="filter-chips">
                <button type="submit" name="filter_type" value="today" class="chip <?php echo $filter_type==='today'?'active':''; ?>">Today</button>
                <button type="submit" name="filter_type" value="yesterday" class="chip <?php echo $filter_type==='yesterday'?'active':''; ?>">Yesterday</button>
                <button type="submit" name="filter_type" value="week" class="chip <?php echo $filter_type==='week'?'active':''; ?>">This Week</button>
                <button type="submit" name="filter_type" value="month" class="chip <?php echo $filter_type==='month'?'active':''; ?>">This Month</button>
                <button type="button" class="chip <?php echo $filter_type==='custom'?'active':''; ?>" onclick="toggleCustom()">Custom Range</button>
            </div>
            <div class="custom-dates <?php echo $filter_type==='custom'?'show':''; ?>" id="custom-dates">
                <div class="fg-inline"><label>From</label><input type="date" name="date_from" value="<?php echo $filter_from; ?>"></div>
                <div class="fg-inline"><label>To</label><input type="date" name="date_to" value="<?php echo $filter_to; ?>"></div>
                <button type="submit" name="filter_type" value="custom" class="apply-btn">Apply</button>
            </div>
        </form>
    </div>

    <div class="stats-row">
        <div class="stat-card"><div class="stat-icon blue"><i class="fa-solid fa-hourglass-half"></i></div><div><div class="stat-val blue"><?php echo $total_queue; ?></div><div class="stat-lbl">Total Queue</div></div></div>
        <div class="stat-card"><div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div><div><div class="stat-val green"><?php echo $completed; ?></div><div class="stat-lbl">Completed</div></div></div>
        <div class="stat-card"><div class="stat-icon red"><i class="fa-solid fa-circle-xmark"></i></div><div><div class="stat-val red"><?php echo $cancelled; ?></div><div class="stat-lbl">Cancelled</div></div></div>
        <div class="stat-card"><div class="stat-icon amber"><i class="fa-solid fa-calendar-days"></i></div><div><div class="stat-val amber"><?php echo $total_appt; ?></div><div class="stat-lbl">Appointments</div></div></div>
        <div class="stat-card"><div class="stat-icon blue"><i class="fa-solid fa-calendar-check"></i></div><div><div class="stat-val blue"><?php echo $confirmed_appt; ?></div><div class="stat-lbl">Confirmed Appt</div></div></div>
        <div class="stat-card"><div class="stat-icon purple"><i class="fa-solid fa-user-graduate"></i></div><div><div class="stat-val purple"><?php echo $total_students; ?></div><div class="stat-lbl">Total Students</div></div></div>
    </div>

    <div class="section-title"><i class="fa-solid fa-hourglass-half" style="color:var(--blue-500)"></i> Queue Statistics</div>
    <div class="charts-grid">
        <div class="chart-card"><h3>Daily Queue Trend</h3><div class="chart-box"><canvas id="dailyChart"></canvas></div></div>
        <div class="chart-card"><h3>Service Types</h3><div class="chart-box"><canvas id="serviceChart"></canvas></div></div>
    </div>
    <div class="two-grid">
        <div class="chart-card"><h3>Queue Status Breakdown</h3><div class="chart-box"><canvas id="statusChart"></canvas></div></div>
        <div class="chart-card"><h3>Faculty Breakdown</h3>
            <?php if(empty($faculty_stats)): ?><div class="no-data">No data for this period.</div>
            <?php else: ?><table class="data-table"><thead><tr><th>Faculty</th><th style="text-align:right">Visits</th></tr></thead><tbody>
            <?php foreach($faculty_stats as $f): ?><tr><td style="font-weight:600"><?php echo htmlspecialchars($f['faculty']); ?></td><td style="text-align:right;font-weight:800;color:var(--blue-600);font-family:monospace"><?php echo $f['count']; ?></td></tr><?php endforeach; ?>
            </tbody></table><?php endif; ?>
        </div>
    </div>

    <div class="table-card">
        <div class="table-head"><h3><i class="fa-solid fa-list-ol"></i> Queue Records</h3><span><?php echo count($recent); ?> records</span></div>
        <?php if(empty($recent)): ?><div class="no-data">No queue records for this period.</div>
        <?php else: ?><div style="overflow-x:auto"><table class="data-table"><thead><tr><th>Queue</th><th>Student</th><th>Faculty</th><th>Service</th><th>Doctor</th><th>Room</th><th>Status</th><th>Time</th></tr></thead><tbody>
        <?php foreach($recent as $r): ?><tr>
            <td style="font-family:monospace;font-weight:700"><?php echo htmlspecialchars($r['queue_number']); ?></td>
            <td style="font-weight:600"><?php echo htmlspecialchars($r['student_name']??'Unknown'); ?></td>
            <td style="color:var(--slate-400)"><?php echo htmlspecialchars($r['faculty']??'—'); ?></td>
            <td><?php echo htmlspecialchars($r['service_type']); ?></td>
            <td style="color:var(--green-600);font-size:12px"><?php echo htmlspecialchars($r['doctor_name']??'—'); ?></td>
            <td style="color:var(--blue-600);font-weight:600;font-size:12px"><?php echo htmlspecialchars($r['assigned_room']??'—'); ?></td>
            <td><span class="badge badge-<?php echo str_replace(' ','-',$r['queue_status']); ?>"><?php echo $r['queue_status']; ?></span></td>
            <td style="color:var(--slate-400);font-size:12px"><?php echo date('d/m h:i A',strtotime($r['created_at'])); ?></td>
        </tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
    </div>

    <div class="section-title"><i class="fa-solid fa-calendar-days" style="color:var(--blue-500)"></i> Appointment Statistics</div>
    <div class="two-grid">
        <div class="chart-card"><h3>Appointments by Service</h3><div class="chart-box"><canvas id="apptServiceChart"></canvas></div></div>
        <div class="chart-card"><h3>Appointment Status</h3>
            <?php if(empty($appt_status)): ?><div class="no-data">No appointment data.</div>
            <?php else: ?><table class="data-table" style="margin-top:6px"><thead><tr><th>Status</th><th style="text-align:right">Count</th></tr></thead><tbody>
            <?php foreach($appt_status as $s): ?><tr><td><span class="badge badge-<?php echo $s['status']; ?>"><?php echo $s['status']; ?></span></td><td style="text-align:right;font-weight:800;font-family:monospace"><?php echo $s['count']; ?></td></tr><?php endforeach; ?>
            </tbody></table><?php endif; ?>
        </div>
    </div>

    <div class="table-card">
        <div class="table-head"><h3><i class="fa-solid fa-calendar-check"></i> Appointment Records</h3><span><?php echo count($appt_list); ?> records</span></div>
        <?php if(empty($appt_list)): ?><div class="no-data">No appointments for this period.</div>
        <?php else: ?><div style="overflow-x:auto"><table class="data-table"><thead><tr><th>Matrix</th><th>Student</th><th>Service</th><th>Schedule</th><th>Doctor</th><th>Room</th><th>Status</th></tr></thead><tbody>
        <?php foreach($appt_list as $a): ?><tr>
            <td style="font-family:monospace;font-size:12px"><?php echo htmlspecialchars($a['matrix_number']); ?></td>
            <td style="font-weight:600"><?php echo htmlspecialchars($a['student_name']); ?></td>
            <td><?php echo htmlspecialchars($a['service_type']); ?></td>
            <td style="font-size:12px;color:var(--slate-400)"><?php echo date('d/m/Y h:i A',strtotime($a['schedule_time'])); ?></td>
            <td style="color:var(--green-600);font-size:12px"><?php echo htmlspecialchars($a['doctor_name']??'—'); ?></td>
            <td style="color:var(--blue-600);font-weight:600;font-size:12px"><?php echo htmlspecialchars($a['assigned_room']??'—'); ?></td>
            <td><span class="badge badge-<?php echo $a['status']; ?>"><?php echo $a['status']; ?></span></td>
        </tr><?php endforeach; ?></tbody></table></div><?php endif; ?>
    </div>
</div></div>

<script>
function toggleCustom(){document.getElementById('custom-dates').classList.toggle('show');}
const opts={responsive:true,maintainAspectRatio:false};
const dl=<?php echo $chart_labels; ?>,dd=<?php echo $chart_data; ?>;
if(dl.length){new Chart(document.getElementById('dailyChart'),{type:'bar',data:{labels:dl,datasets:[{label:'Queue',data:dd,backgroundColor:'rgba(37,99,235,.15)',borderColor:'#2563eb',borderWidth:2,borderRadius:5}]},options:{...opts,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{stepSize:1},grid:{color:'rgba(0,0,0,.05)'}},x:{grid:{display:false}}}}});}
else document.getElementById('dailyChart').parentElement.innerHTML='<div class="no-data">No data for this period.</div>';
const sl=<?php echo $svc_labels; ?>,sd=<?php echo $svc_data; ?>;
if(sl.length){new Chart(document.getElementById('serviceChart'),{type:'doughnut',data:{labels:sl,datasets:[{data:sd,backgroundColor:['#2563eb','#16a34a','#d97706','#dc2626'],borderWidth:0}]},options:{...opts,plugins:{legend:{position:'bottom',labels:{font:{size:11},padding:8}}}}})}
else document.getElementById('serviceChart').parentElement.innerHTML='<div class="no-data">No data.</div>';
const stl=<?php echo $status_labels; ?>,std=<?php echo $status_data; ?>;
if(stl.length){new Chart(document.getElementById('statusChart'),{type:'pie',data:{labels:stl,datasets:[{data:std,backgroundColor:['#d97706','#2563eb','#16a34a','#dc2626'],borderWidth:0}]},options:{...opts,plugins:{legend:{position:'bottom',labels:{font:{size:11},padding:8}}}}})}
else document.getElementById('statusChart').parentElement.innerHTML='<div class="no-data">No data.</div>';
const asl=<?php echo $appt_svc_labels; ?>,asd=<?php echo $appt_svc_data; ?>;
if(asl.length){new Chart(document.getElementById('apptServiceChart'),{type:'doughnut',data:{labels:asl,datasets:[{data:asd,backgroundColor:['#2563eb','#16a34a','#d97706','#dc2626'],borderWidth:0}]},options:{...opts,plugins:{legend:{position:'bottom',labels:{font:{size:11},padding:8}}}}})}
else document.getElementById('apptServiceChart').parentElement.innerHTML='<div class="no-data">No data.</div>';
</script>
</body></html>