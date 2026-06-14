<?php
include 'login_check.php';
$host='localhost';$dbname='queue_and_appointment_management';$db_user='root';$db_pass='';
$filter_action=$_GET['action']??'';$filter_role=$_GET['role']??'';$filter_date_from=$_GET['date_from']??'';$filter_date_to=$_GET['date_to']??'';$search=$_GET['search']??'';
try{
    $pdo=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",$db_user,$db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $where=[];$params=[];
    if($filter_action){$where[]="action LIKE :action";$params[':action']="%$filter_action%";}
    if($filter_role){$where[]="role=:role";$params[':role']=$filter_role;}
    if($filter_date_from){$where[]="DATE(created_at)>=:df";$params[':df']=$filter_date_from;}
    if($filter_date_to){$where[]="DATE(created_at)<=:dt";$params[':dt']=$filter_date_to;}
    if($search){$where[]="(username LIKE :s OR details LIKE :s2)";$params[':s']="%$search%";$params[':s2']="%$search%";}
    $sql="SELECT * FROM system_logs";if($where) $sql.=" WHERE ".implode(" AND ",$where);$sql.=" ORDER BY created_at DESC LIMIT 200";
    $stmt=$pdo->prepare($sql);$stmt->execute($params);$logs=$stmt->fetchAll(PDO::FETCH_ASSOC);
    $total_logs=$pdo->query("SELECT COUNT(*) FROM system_logs")->fetchColumn();
    $today_logs=$pdo->query("SELECT COUNT(*) FROM system_logs WHERE DATE(created_at)=CURDATE()")->fetchColumn();
    $failed_logins=$pdo->query("SELECT COUNT(*) FROM system_logs WHERE action='Failed Login' AND DATE(created_at)=CURDATE()")->fetchColumn();
}catch(PDOException $e){$logs=[];$total_logs=$today_logs=$failed_logins=0;}

$INITIAL_SHOW = 25;
?>
<!DOCTYPE html><html lang="en"><head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Logs — PKU UTHM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        :root{--green-900:#14532d;--green-700:#15803d;--green-600:#16a34a;--green-100:#dcfce7;--green-50:#f0fdf4;--amber-600:#d97706;--amber-100:#fef3c7;--red-600:#dc2626;--red-100:#fee2e2;--blue-600:#1d5bb5;--blue-50:#eff6ff;--purple-600:#7c3aed;--purple-100:#ede9fe;--slate-900:#0f172a;--slate-700:#334155;--slate-500:#64748b;--slate-300:#cbd5e1;--slate-200:#e2e8f0;--slate-100:#f1f5f9;--slate-50:#f8fafc;--white:#fff;--sidebar-w:232px;--topbar-h:58px;--font:'Plus Jakarta Sans',sans-serif}
        html,body{height:100%;font-family:var(--font);background:var(--slate-100);color:var(--slate-900);font-size:14px}
        .sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--sidebar-w);background:var(--green-900);display:flex;flex-direction:column;z-index:50}
        .sidebar-top{height:var(--topbar-h);display:flex;align-items:center;gap:10px;padding:0 18px;border-bottom:1px solid rgba(255,255,255,.08);flex-shrink:0}
        .logo-mark{width:32px;height:32px;background:var(--green-600);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0}
        .logo-text strong{display:block;font-size:13px;font-weight:700;color:#fff}.logo-text span{font-size:10.5px;color:rgba(255,255,255,.45)}
        .role-pill-nav{display:inline-block;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);color:#fff;font-size:9px;font-weight:800;letter-spacing:1px;text-transform:uppercase;padding:2px 7px;border-radius:10px;margin-top:3px}
        .sidebar-nav{flex:1;overflow-y:auto;padding:10px 10px 0}.sidebar-nav::-webkit-scrollbar{width:0}
        .nav-section{font-size:9.5px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:rgba(255,255,255,.4);padding:16px 8px 5px}
        .nav-link{display:flex;align-items:center;gap:9px;padding:8px 10px;border-radius:7px;font-size:13px;font-weight:500;color:#fff;text-decoration:none;transition:all .15s;margin-bottom:1px}
        .nav-link:hover{background:rgba(255,255,255,.1)}.nav-link.active{background:var(--green-700);font-weight:700}
        .nav-link .ico{font-size:14px;width:18px;text-align:center;flex-shrink:0}
        .sidebar-footer{padding:12px 10px 16px;border-top:1px solid rgba(255,255,255,.08);flex-shrink:0}
        .user-card{display:flex;align-items:center;gap:9px;padding:9px 10px;border-radius:8px;background:rgba(255,255,255,.07)}
        .user-ava{width:30px;height:30px;border-radius:7px;background:var(--green-600);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0}
        .user-info strong{display:block;font-size:12px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .user-info span{font-size:10.5px;color:rgba(255,255,255,.4)}
        .topbar{position:fixed;top:0;left:var(--sidebar-w);right:0;height:var(--topbar-h);background:var(--white);border-bottom:1px solid var(--slate-200);display:flex;align-items:center;padding:0 24px;gap:12px;z-index:40}
        .topbar-title{font-size:15px;font-weight:700;color:var(--slate-900)}.breadcrumb{font-size:11.5px;color:var(--slate-500);margin-top:1px;display:flex;align-items:center;gap:4px}
        .breadcrumb a{color:var(--slate-500);text-decoration:none}.breadcrumb .sep{color:var(--slate-300)}
        .topbar-right{margin-left:auto;display:flex;align-items:center;gap:8px}
        .topbar-date{font-size:12px;color:var(--slate-500);padding:5px 12px;background:var(--slate-50);border:1px solid var(--slate-200);border-radius:6px}
        .page{margin-left:var(--sidebar-w);padding-top:var(--topbar-h);min-height:100vh}
        .page-body{padding:22px 26px}
        .page-intro{margin-bottom:18px}
        .page-intro h1{font-size:19px;font-weight:800;color:var(--slate-900);letter-spacing:-.4px}
        .page-intro p{font-size:12.5px;color:var(--slate-500);margin-top:3px}
        .stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px}
        .stat-card{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;padding:15px 16px;display:flex;align-items:center;gap:12px}
        .stat-icon{width:40px;height:40px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0}
        .stat-icon.green{background:var(--green-100)}.stat-icon.amber{background:var(--amber-100)}.stat-icon.red{background:var(--red-100)}
        .stat-val{font-size:24px;font-weight:800;letter-spacing:-1px;line-height:1}
        .stat-val.green{color:var(--green-600)}.stat-val.amber{color:var(--amber-600)}.stat-val.red{color:var(--red-600)}
        .stat-lbl{font-size:11.5px;color:var(--slate-500);margin-top:3px}
        .filter-bar{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;padding:14px 18px;margin-bottom:14px}
        .filter-row{display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end}
        .filter-row .fg{flex:1;min-width:120px;margin-bottom:0}
        .fg{margin-bottom:14px}
        .fg label{display:block;font-size:11px;font-weight:700;color:var(--slate-600);margin-bottom:5px;text-transform:uppercase;letter-spacing:.4px}
        .fg input,.fg select{width:100%;padding:9px 12px;border:1.5px solid var(--slate-200);border-radius:8px;font-size:13px;font-family:var(--font);color:var(--slate-900)}
        .fg input:focus,.fg select:focus{border-color:var(--green-600);outline:none;box-shadow:0 0 0 3px rgba(34,197,94,.08)}
        .btn{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:8px;font-family:var(--font);font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .15s;text-decoration:none}
        .btn-primary{background:var(--green-600);color:#fff}.btn-primary:hover{background:var(--green-700)}
        .btn-outline{background:var(--slate-50);color:var(--slate-700);border:1px solid var(--slate-200)}.btn-outline:hover{background:var(--green-50);color:var(--green-600)}
        .table-card{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;overflow:hidden}
        .table-head{padding:13px 18px;border-bottom:1px solid var(--slate-100);display:flex;justify-content:space-between;align-items:center}
        .table-head h2{font-size:13px;font-weight:700;color:var(--slate-900)}
        .table-head-meta{display:flex;align-items:center;gap:10px}
        .table-head-meta span{font-size:11.5px;color:var(--slate-400)}
        .data-table{width:100%;border-collapse:collapse}
        .data-table thead tr{background:var(--green-50)}
        .data-table th{padding:9px 14px;text-align:left;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--green-700);border-bottom:1px solid var(--green-100);white-space:nowrap}
        .data-table td{padding:10px 14px;font-size:13px;color:var(--slate-700);border-bottom:1px solid var(--slate-50);vertical-align:middle}
        .data-table tbody tr:last-child td{border-bottom:none}
        .data-table tbody tr:hover{background:var(--green-50)}

        /* ── Hidden rows ── */
        .log-row.hidden{display:none}

        /* ── Read More bar ── */
        .read-more-bar{
            padding:14px 18px;
            border-top:1px solid var(--slate-100);
            display:flex;align-items:center;justify-content:space-between;
            background:var(--slate-50);
        }
        .read-more-bar.gone{display:none}
        .read-more-info{font-size:12.5px;color:var(--slate-500)}
        .read-more-info strong{color:var(--slate-700)}
        .btn-read-more{
            background:var(--white);color:var(--green-600);
            border:1.5px solid var(--green-600);
            padding:8px 20px;border-radius:8px;
            font-family:var(--font);font-size:13px;font-weight:700;
            cursor:pointer;transition:all .15s;display:flex;align-items:center;gap:7px;
        }
        .btn-read-more:hover{background:var(--green-50)}

        .badge{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:5px;font-size:11px;font-weight:700}
        .badge::before{content:'';width:5px;height:5px;border-radius:50%}
        .badge-login{background:var(--green-100);color:#14532d}.badge-login::before{background:var(--green-600)}
        .badge-logout{background:var(--red-100);color:#7f1d1d}.badge-logout::before{background:var(--red-600)}
        .badge-failed{background:var(--amber-100);color:#92400e}.badge-failed::before{background:var(--amber-600)}
        .badge-add{background:var(--blue-50);color:var(--blue-600)}.badge-add::before{background:var(--blue-600)}
        .badge-delete{background:var(--red-100);color:#7f1d1d}.badge-delete::before{background:var(--red-600)}
        .badge-default{background:var(--green-100);color:#14532d}.badge-default::before{background:var(--green-600)}
        .role-pill{padding:2px 8px;border-radius:4px;font-size:11px;font-weight:700}
        .role-Admin{background:var(--green-100);color:#14532d}.role-Staff{background:var(--blue-50);color:var(--blue-600)}
        .role-Doctor{background:var(--purple-100);color:var(--purple-600)}.role-Student{background:var(--amber-100);color:#92400e}.role-Unknown{background:var(--slate-100);color:var(--slate-500)}
        .empty-state{text-align:center;padding:40px;color:var(--slate-400)}
    </style>
</head><body>

<aside class="sidebar">
    <div class="sidebar-top">
        <div class="logo-mark">🏥</div>
        <div class="logo-text"><strong>PKU UTHM</strong><span>Queue Management System</span><div class="role-pill-nav">Admin</div></div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Admin Panel</div>
        <a href="admin_dashboard.php" class="nav-link"><span class="ico">⊞</span> Dashboard</a>
        <a href="admin_staff_management.php" class="nav-link"><span class="ico">👥</span> Staff Management</a>
        <a href="admin_logs.php" class="nav-link active"><span class="ico">📋</span> System Logs</a>
        <a href="admin_reports.php" class="nav-link"><span class="ico">📊</span> Reports</a>
        <div class="nav-section">View</div>
        <a href="queue_display.html" target="_blank" class="nav-link"><span class="ico">▣</span> Public Display</a>
        <a href="admin_patient_list.php" class="nav-link"><span class="ico">🏥</span> Patients</a>
    </nav>
    <div class="sidebar-footer"><div class="user-card">
        <div class="user-ava"><?php echo strtoupper(substr($_SESSION['full_name']??'A',0,1)); ?></div>
        <div class="user-info"><strong><?php echo htmlspecialchars($_SESSION['full_name']??'Admin'); ?></strong><span>Administrator</span></div>
        <a href="logout.php" style="font-size:14px;color:rgba(255,255,255,.3);text-decoration:none;margin-left:auto;">⏻</a>
    </div></div>
</aside>

<header class="topbar">
    <div>
        <div class="topbar-title">System Logs</div>
        <div class="breadcrumb"><a href="admin_dashboard.php">Dashboard</a><span class="sep">›</span><span>System Logs</span></div>
    </div>
    <div class="topbar-right"><div class="topbar-date"><?php echo date('D, d M Y'); ?></div></div>
</header>

<div class="page"><div class="page-body">

    <div class="page-intro">
        <h1>System Logs</h1>
        <p>All user activity and system events</p>
    </div>

    <div class="stats-row">
        <div class="stat-card"><div class="stat-icon green">📋</div><div><div class="stat-val green"><?php echo $total_logs; ?></div><div class="stat-lbl">Total Logs</div></div></div>
        <div class="stat-card"><div class="stat-icon amber">📅</div><div><div class="stat-val amber"><?php echo $today_logs; ?></div><div class="stat-lbl">Today's Logs</div></div></div>
        <div class="stat-card"><div class="stat-icon red">⚠</div><div><div class="stat-val red"><?php echo $failed_logins; ?></div><div class="stat-lbl">Failed Logins Today</div></div></div>
    </div>

    <div class="filter-bar">
        <form method="GET"><div class="filter-row">
            <div class="fg"><label>Search</label><input type="text" name="search" placeholder="Username or details…" value="<?php echo htmlspecialchars($search); ?>"></div>
            <div class="fg"><label>Action</label>
                <select name="action">
                    <option value="">All Actions</option>
                    <option value="Login"              <?php if($filter_action==='Login') echo 'selected'; ?>>Login</option>
                    <option value="Failed Login"       <?php if($filter_action==='Failed Login') echo 'selected'; ?>>Failed Login</option>
                    <option value="Logout"             <?php if($filter_action==='Logout') echo 'selected'; ?>>Logout</option>
                    <option value="Add Staff"          <?php if($filter_action==='Add Staff') echo 'selected'; ?>>Add Staff</option>
                    <option value="Delete Staff"       <?php if($filter_action==='Delete Staff') echo 'selected'; ?>>Delete Staff</option>
                    <option value="RFID Check-in"      <?php if($filter_action==='RFID Check-in') echo 'selected'; ?>>RFID Check-in</option>
                    <option value="Update Appointment" <?php if($filter_action==='Update Appointment') echo 'selected'; ?>>Update Appointment</option>
                </select>
            </div>
            <div class="fg"><label>Role</label>
                <select name="role">
                    <option value="">All Roles</option>
                    <option value="Admin"   <?php if($filter_role==='Admin') echo 'selected'; ?>>Admin</option>
                    <option value="Staff"   <?php if($filter_role==='Staff') echo 'selected'; ?>>Staff</option>
                    <option value="Doctor"  <?php if($filter_role==='Doctor') echo 'selected'; ?>>Doctor</option>
                    <option value="Student" <?php if($filter_role==='Student') echo 'selected'; ?>>Student (RFID)</option>
                </select>
            </div>
            <div class="fg"><label>From</label><input type="date" name="date_from" value="<?php echo htmlspecialchars($filter_date_from); ?>"></div>
            <div class="fg"><label>To</label><input type="date" name="date_to" value="<?php echo htmlspecialchars($filter_date_to); ?>"></div>
            <div style="display:flex;gap:8px;align-items:flex-end">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="admin_logs.php" class="btn btn-outline">Clear</a>
            </div>
        </div></form>
    </div>

    <div class="table-card">
        <div class="table-head">
            <h2>Activity Logs</h2>
            <div class="table-head-meta">
                <span id="showing-label">
                    Showing <strong><?php echo min($INITIAL_SHOW, count($logs)); ?></strong> of <strong><?php echo count($logs); ?></strong> records
                </span>
            </div>
        </div>

        <?php if(empty($logs)): ?>
        <div class="empty-state"><div style="font-size:32px;margin-bottom:10px;opacity:.4">📭</div><p>No logs found — try adjusting your filters</p></div>
        <?php else: ?>

        <div style="overflow-x:auto">
            <table class="data-table">
                <thead>
                    <tr><th>#</th><th>User</th><th>Role</th><th>Action</th><th>Details</th><th>IP Address</th><th>Date & Time</th></tr>
                </thead>
                <tbody id="log-tbody">
                <?php foreach($logs as $i=>$log):
                    $action=$log['action'];$bc='badge-default';
                    if($action==='Login') $bc='badge-login';
                    elseif($action==='Logout') $bc='badge-logout';
                    elseif($action==='Failed Login') $bc='badge-failed';
                    elseif(strpos($action,'Add')!==false) $bc='badge-add';
                    elseif(strpos($action,'Delete')!==false) $bc='badge-delete';
                    $hidden = $i >= $INITIAL_SHOW ? 'hidden' : '';
                ?>
                <tr class="log-row <?php echo $hidden; ?>">
                    <td style="color:var(--slate-400);font-size:12px"><?php echo $i+1; ?></td>
                    <td style="font-weight:600;color:var(--slate-900)"><?php echo htmlspecialchars($log['username']); ?></td>
                    <td><span class="role-pill role-<?php echo $log['role']??'Unknown'; ?>"><?php echo $log['role']??'Unknown'; ?></span></td>
                    <td><span class="badge <?php echo $bc; ?>"><?php echo htmlspecialchars($action); ?></span></td>
                    <td style="color:var(--slate-500);font-size:12px;max-width:260px"><?php echo htmlspecialchars($log['details']??'—'); ?></td>
                    <td style="font-family:monospace;font-size:12px;color:var(--slate-400)"><?php echo htmlspecialchars($log['ip_address']??'—'); ?></td>
                    <td style="font-size:12px;color:var(--slate-400);white-space:nowrap"><?php echo date('d M Y, H:i:s',strtotime($log['created_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if(count($logs) > $INITIAL_SHOW): ?>
        <div class="read-more-bar" id="read-more-bar">
            <div class="read-more-info">
                Showing <strong><?php echo $INITIAL_SHOW; ?></strong> of <strong><?php echo count($logs); ?></strong> records —
                <strong><?php echo count($logs) - $INITIAL_SHOW; ?></strong> more hidden
            </div>
            <button class="btn-read-more" onclick="showAll()">
                ↓ Read More
            </button>
        </div>
        <?php endif; ?>

        <?php endif; ?>
    </div>

</div></div>

<script>
function showAll() {
    // Show all hidden rows
    document.querySelectorAll('.log-row.hidden').forEach(row => {
        row.classList.remove('hidden');
    });

    // Hide the read-more bar
    const bar = document.getElementById('read-more-bar');
    if (bar) bar.classList.add('gone');

    // Update the showing label
    const total = document.querySelectorAll('.log-row').length;
    const label = document.getElementById('showing-label');
    if (label) label.innerHTML = `Showing <strong>${total}</strong> of <strong>${total}</strong> records`;
}
</script>

</body></html>