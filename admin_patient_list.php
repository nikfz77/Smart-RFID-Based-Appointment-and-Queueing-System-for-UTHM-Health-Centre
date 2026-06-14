<?php
include 'login_check.php';
$host='localhost';$dbname='queue_and_appointment_management';$db_user='root';$db_pass='';
$search=$_GET['search']??'';$faculty=$_GET['faculty']??'';$year=$_GET['year']??'';
try{
    $pdo=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",$db_user,$db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $where=[];$params=[];
    if($search){$where[]="(s.matrix_number LIKE :s OR s.full_name LIKE :s2)";$params[':s']="%$search%";$params[':s2']="%$search%";}
    if($faculty){$where[]="s.faculty=:faculty";$params[':faculty']=$faculty;}
    if($year){$where[]="s.year_of_study=:year";$params[':year']=$year;}
    $sql="SELECT s.*,
        (SELECT COUNT(*) FROM appointments a WHERE a.matrix_number=s.matrix_number) as total_appointments,
        (SELECT COUNT(*) FROM queue q WHERE q.matrix_number=s.matrix_number) as total_visits,
        (SELECT MAX(q2.created_at) FROM queue q2 WHERE q2.matrix_number=s.matrix_number) as last_visit
        FROM students s";
    if($where) $sql.=" WHERE ".implode(" AND ",$where);
    $sql.=" ORDER BY s.created_at DESC";
    $stmt=$pdo->prepare($sql);$stmt->execute($params);$patients=$stmt->fetchAll(PDO::FETCH_ASSOC);
    $total_patients=$pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
    $new_this_month=$pdo->query("SELECT COUNT(*) FROM students WHERE MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW())")->fetchColumn();
}catch(PDOException $e){$patients=[];$total_patients=$new_this_month=0;}
?>
<!DOCTYPE html><html lang="en"><head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient List — Admin — PKU UTHM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        :root{--green-900:#14532d;--green-700:#15803d;--green-600:#16a34a;--green-100:#dcfce7;--green-50:#f0fdf4;--blue-600:#1d5bb5;--blue-50:#eff6ff;--amber-600:#d97706;--amber-100:#fef3c7;--red-600:#dc2626;--purple-600:#7c3aed;--purple-100:#ede9fe;--slate-900:#0f172a;--slate-700:#334155;--slate-500:#64748b;--slate-300:#cbd5e1;--slate-200:#e2e8f0;--slate-100:#f1f5f9;--slate-50:#f8fafc;--white:#fff;--sidebar-w:232px;--topbar-h:58px;--font:'Plus Jakarta Sans',sans-serif}
        html,body{height:100%;font-family:var(--font);background:var(--slate-100);color:var(--slate-900);font-size:14px}
        .sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--sidebar-w);background:var(--green-900);display:flex;flex-direction:column;z-index:50}
        .sidebar-top{height:var(--topbar-h);display:flex;align-items:center;gap:10px;padding:0 18px;border-bottom:1px solid rgba(255,255,255,.07);flex-shrink:0}
        .logo-mark{width:32px;height:32px;background:var(--green-600);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;color:#fff}
        .logo-text strong{display:block;font-size:13px;font-weight:700;color:#fff}.logo-text span{font-size:10.5px;color:rgba(255,255,255,.45)}
        .role-pill{display:inline-block;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);color:#fff;font-size:9px;font-weight:800;letter-spacing:1px;text-transform:uppercase;padding:2px 7px;border-radius:10px;margin-top:3px}
        .sidebar-nav{flex:1;overflow-y:auto;padding:10px 10px 0}.sidebar-nav::-webkit-scrollbar{width:0}
        .nav-section{font-size:9.5px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:rgba(255,255,255,.45);padding:16px 8px 5px}
        .nav-link{display:flex;align-items:center;gap:9px;padding:8px 10px;border-radius:7px;font-size:13px;font-weight:500;color:#fff;text-decoration:none;transition:all .15s;margin-bottom:1px}
        .nav-link:hover{background:rgba(255,255,255,.09)}.nav-link.active{background:var(--green-700);font-weight:600}
        .nav-link .ico{font-size:14px;width:18px;text-align:center;flex-shrink:0}
        .sidebar-footer{padding:12px 10px 16px;border-top:1px solid rgba(255,255,255,.07);flex-shrink:0}
        .user-card{display:flex;align-items:center;gap:9px;padding:9px 10px;border-radius:8px;background:rgba(255,255,255,.06)}
        .user-ava{width:30px;height:30px;border-radius:7px;background:var(--green-600);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0}
        .user-info strong{display:block;font-size:12px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .user-info span{font-size:10.5px;color:rgba(255,255,255,.4)}
        .topbar{position:fixed;top:0;left:var(--sidebar-w);right:0;height:var(--topbar-h);background:var(--white);border-bottom:1px solid var(--slate-200);display:flex;align-items:center;padding:0 24px;gap:12px;z-index:40}
        .topbar-title{font-size:15px;font-weight:700;color:var(--slate-900)}.breadcrumb{font-size:11.5px;color:var(--slate-500);margin-top:1px;display:flex;align-items:center;gap:4px}
        .breadcrumb a{color:var(--slate-500);text-decoration:none}.breadcrumb .sep{color:var(--slate-300)}
        .topbar-right{margin-left:auto;display:flex;align-items:center;gap:8px}
        .topbar-date{font-size:12px;color:var(--slate-500);padding:5px 12px;background:var(--slate-50);border:1px solid var(--slate-200);border-radius:6px}
        .btn{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:8px;font-family:var(--font);font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .15s;text-decoration:none}
        .btn-primary{background:var(--green-600);color:#fff}.btn-primary:hover{background:var(--green-700)}
        .btn-outline{background:var(--slate-50);color:var(--slate-700);border:1px solid var(--slate-200)}.btn-outline:hover{background:var(--green-50);color:var(--green-700)}
        .btn-sm{padding:5px 11px;font-size:12px;border-radius:6px}
        .page{margin-left:var(--sidebar-w);padding-top:var(--topbar-h);min-height:100vh}
        .page-body{padding:22px 26px}
        .page-intro{margin-bottom:18px}
        .page-intro h1{font-size:19px;font-weight:800;color:var(--slate-900);letter-spacing:-.4px}
        .page-intro p{font-size:12.5px;color:var(--slate-500);margin-top:3px}
        .stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:14px}
        .stat-card{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;padding:15px 16px;display:flex;align-items:center;gap:12px}
        .stat-icon{width:40px;height:40px;border-radius:9px;background:var(--green-100);display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0;color:var(--green-600)}
        .stat-icon.amber{background:var(--amber-100);color:var(--amber-600)}
        .stat-val{font-size:24px;font-weight:800;letter-spacing:-1px;line-height:1;color:var(--green-600)}
        .stat-lbl{font-size:11.5px;color:var(--slate-500);margin-top:3px}
        .filter-bar{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;padding:14px 18px;margin-bottom:14px}
        .filter-row{display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end}
        .filter-row .fg{flex:1;min-width:120px;margin-bottom:0}
        .fg label{display:block;font-size:11px;font-weight:700;color:var(--slate-600);margin-bottom:5px;text-transform:uppercase;letter-spacing:.4px}
        .fg input,.fg select{width:100%;padding:9px 12px;border:1.5px solid var(--slate-200);border-radius:8px;font-size:13px;font-family:var(--font);color:var(--slate-900)}
        .fg input:focus,.fg select:focus{border-color:var(--green-600);outline:none;box-shadow:0 0 0 3px rgba(22,163,74,.08)}
        .table-card{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;overflow:hidden}
        .table-head{padding:13px 18px;border-bottom:1px solid var(--slate-100);display:flex;justify-content:space-between;align-items:center}
        .table-head h2{font-size:13px;font-weight:700;color:var(--slate-900)}.table-head span{font-size:11.5px;color:var(--slate-400)}
        .data-table{width:100%;border-collapse:collapse;min-width:800px}
        .data-table thead tr{background:var(--green-50)}
        .data-table th{padding:9px 14px;text-align:left;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--green-700);border-bottom:1px solid var(--green-100)}
        .data-table td{padding:10px 14px;font-size:13px;color:var(--slate-700);border-bottom:1px solid var(--slate-50);vertical-align:middle}
        .data-table tbody tr:last-child td{border-bottom:none}.data-table tbody tr:hover{background:var(--green-50)}
        .fac-tag{padding:2px 8px;border-radius:5px;font-size:11px;font-weight:700;background:var(--blue-50);color:var(--blue-600)}
        .yr-tag{padding:2px 8px;border-radius:5px;font-size:11px;font-weight:700;background:var(--purple-100);color:var(--purple-600)}
        .empty-state{text-align:center;padding:36px;color:var(--slate-400)}
        .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:1000;display:none;justify-content:center;align-items:center}
        .modal-overlay.active{display:flex}
        .modal{background:var(--white);border-radius:12px;padding:24px;max-width:560px;width:90%;max-height:85vh;overflow-y:auto;animation:mIn .2s ease-out}
        @keyframes mIn{from{opacity:0;transform:scale(.96)}to{opacity:1;transform:scale(1)}}
        .modal-hd{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid var(--slate-100)}
        .modal-hd h3{font-size:15px;font-weight:700;color:var(--slate-900)}.modal-close{background:none;border:none;font-size:18px;cursor:pointer;color:var(--slate-400)}
        .det-row{display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--slate-50);font-size:13px}
        .det-row:last-child{border-bottom:none}.det-row span:first-child{font-weight:600;color:var(--slate-900)}.det-row span:last-child{color:var(--slate-500)}
        .hist-table{width:100%;border-collapse:collapse;font-size:12px;margin-top:10px}
        .hist-table th{background:var(--slate-50);color:var(--slate-400);padding:7px 10px;text-align:left;font-weight:600;font-size:11px;text-transform:uppercase}
        .hist-table td{padding:8px 10px;border-bottom:1px solid var(--slate-50)}
    </style>
</head><body>

<aside class="sidebar">
    <div class="sidebar-top">
        <div class="logo-mark"><i class="fa-solid fa-hospital"></i></div>
        <div class="logo-text"><strong>PKU UTHM</strong><span>Queue Management System</span><div class="role-pill">Admin</div></div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Admin Panel</div>
        <a href="admin_dashboard.php"        class="nav-link"><span class="ico"><i class="fa-solid fa-table-columns"></i></span> Dashboard</a>
        <a href="admin_staff_management.php" class="nav-link"><span class="ico"><i class="fa-solid fa-users"></i></span> Staff Management</a>
        <a href="admin_logs.php"             class="nav-link"><span class="ico"><i class="fa-solid fa-clipboard-list"></i></span> System Logs</a>
        <a href="admin_reports.php"          class="nav-link"><span class="ico"><i class="fa-solid fa-chart-bar"></i></span> Reports</a>
        <div class="nav-section">Records</div>
        <a href="admin_patient_list.php"     class="nav-link active"><span class="ico"><i class="fa-solid fa-hospital-user"></i></span> Patients</a>
        <div class="nav-section">Display</div>
        <a href="queue_display.html" target="_blank" class="nav-link"><span class="ico"><i class="fa-solid fa-display"></i></span> Public Display</a>
    </nav>
    <div class="sidebar-footer"><div class="user-card">
        <div class="user-ava"><?php echo strtoupper(substr($_SESSION['full_name']??'A',0,1)); ?></div>
        <div class="user-info">
            <strong><?php echo htmlspecialchars($_SESSION['full_name']??'Admin'); ?></strong>
            <span>Administrator</span>
        </div>
        <a href="logout.php" style="font-size:14px;color:rgba(255,255,255,.3);text-decoration:none;margin-left:4px;" title="Logout"><i class="fa-solid fa-right-from-bracket"></i></a>
    </div></div>
</aside>

<header class="topbar">
    <div>
        <div class="topbar-title">Patient List</div>
        <div class="breadcrumb"><a href="admin_dashboard.php">Dashboard</a><span class="sep">›</span><span>Patients</span></div>
    </div>
    <div class="topbar-right">
        <div class="topbar-date"><?php echo date('D, d M Y'); ?></div>
    </div>
</header>

<div class="page"><div class="page-body">
    <div class="page-intro"><h1>Patient List</h1><p>All registered patients and their visit history</p></div>

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-hospital-user"></i></div>
            <div><div class="stat-val"><?php echo $total_patients; ?></div><div class="stat-lbl">Total Patients</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-user-plus"></i></div>
            <div><div class="stat-val"><?php echo $new_this_month; ?></div><div class="stat-lbl">New This Month</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon amber"><i class="fa-solid fa-magnifying-glass"></i></div>
            <div><div class="stat-val" style="color:var(--amber-600)"><?php echo count($patients); ?></div><div class="stat-lbl">Search Results</div></div>
        </div>
    </div>

    <div class="filter-bar">
        <form method="GET"><div class="filter-row">
            <div class="fg"><label>Search</label><input type="text" name="search" placeholder="Matrix or name…" value="<?php echo htmlspecialchars($search); ?>"></div>
            <div class="fg"><label>Faculty</label><select name="faculty">
                <option value="">All Faculties</option>
                <option value="FKEE"  <?php if($faculty==='FKEE')  echo 'selected'; ?>>FKEE</option>
                <option value="FKAAB" <?php if($faculty==='FKAAB') echo 'selected'; ?>>FKAAB</option>
                <option value="FKMP"  <?php if($faculty==='FKMP')  echo 'selected'; ?>>FKMP</option>
                <option value="FSKTM" <?php if($faculty==='FSKTM') echo 'selected'; ?>>FSKTM</option>
                <option value="FPTP"  <?php if($faculty==='FPTP')  echo 'selected'; ?>>FPTP</option>
                <option value="FPTV"  <?php if($faculty==='FPTV')  echo 'selected'; ?>>FPTV</option>
                <option value="OTHER">Other</option>
            </select></div>
            <div class="fg"><label>Year</label><select name="year">
                <option value="">All Years</option>
                <option value="1" <?php if($year==='1') echo 'selected'; ?>>Year 1</option>
                <option value="2" <?php if($year==='2') echo 'selected'; ?>>Year 2</option>
                <option value="3" <?php if($year==='3') echo 'selected'; ?>>Year 3</option>
                <option value="4" <?php if($year==='4') echo 'selected'; ?>>Year 4</option>
            </select></div>
            <div style="display:flex;gap:8px;align-items:flex-end">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
                <a href="admin_patient_list.php" class="btn btn-outline"><i class="fa-solid fa-xmark"></i> Clear</a>
            </div>
        </div></form>
    </div>

    <div class="table-card">
        <div class="table-head"><h2>Patients</h2><span>Showing <?php echo count($patients); ?> records</span></div>
        <?php if(empty($patients)): ?>
        <div class="empty-state">
            <div style="font-size:32px;margin-bottom:10px;opacity:.4"><i class="fa-solid fa-inbox"></i></div>
            <p>No patients found</p>
        </div>
        <?php else: ?>
        <div style="overflow-x:auto"><table class="data-table">
            <thead><tr><th>#</th><th>Matrix</th><th>Name</th><th>Faculty</th><th>Year</th><th>Email</th><th>Visits</th><th>Last Visit</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach($patients as $i=>$s): ?>
            <tr>
                <td style="color:var(--slate-400);font-size:12px"><?php echo $i+1; ?></td>
                <td><span style="font-family:monospace;font-size:12px;font-weight:600;color:var(--slate-900)"><?php echo htmlspecialchars($s['matrix_number']); ?></span></td>
                <td style="font-weight:600;color:var(--slate-900)"><?php echo htmlspecialchars($s['full_name']); ?></td>
                <td><span class="fac-tag"><?php echo htmlspecialchars($s['faculty']??'—'); ?></span></td>
                <td><span class="yr-tag">Y<?php echo $s['year_of_study']??'—'; ?></span></td>
                <td style="color:var(--slate-500);font-size:12px"><?php echo htmlspecialchars($s['email']??'—'); ?></td>
                <td style="text-align:center;font-weight:600;color:var(--green-600)"><?php echo $s['total_visits']; ?></td>
                <td style="color:var(--slate-400);font-size:12px"><?php echo $s['last_visit']?date('d M Y',strtotime($s['last_visit'])):'Never'; ?></td>
                <td><button class="btn btn-sm btn-outline" onclick='showHistory(<?php echo json_encode($s); ?>)'><i class="fa-solid fa-clock-rotate-left"></i> History</button></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table></div>
        <?php endif; ?>
    </div>
</div></div>

<!-- History Modal -->
<div class="modal-overlay" id="modal" onclick="if(event.target===this)this.classList.remove('active')">
    <div class="modal">
        <div class="modal-hd">
            <h3><i class="fa-solid fa-clock-rotate-left" style="color:var(--green-600);margin-right:6px"></i>Patient History</h3>
            <button class="modal-close" onclick="document.getElementById('modal').classList.remove('active')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div id="modal-content"></div>
    </div>
</div>

<script>
function showHistory(s){
    document.getElementById('modal').classList.add('active');
    document.getElementById('modal-content').innerHTML=`
        <div class="det-row"><span>Matrix No.</span><span>${s.matrix_number}</span></div>
        <div class="det-row"><span>Name</span><span>${s.full_name}</span></div>
        <div class="det-row"><span>Faculty</span><span>${s.faculty||'—'}</span></div>
        <div class="det-row"><span>Program</span><span>${s.program||'—'}</span></div>
        <div class="det-row"><span>Blood Type</span><span>${s.blood_type||'N/A'}</span></div>
        <div class="det-row"><span>Allergies</span><span>${s.allergies||'None'}</span></div>
        <div class="det-row"><span>Total Visits</span><span>${s.total_visits}</span></div>
        <h3 style="font-size:13px;font-weight:700;margin:14px 0 8px;color:var(--slate-900)">Visit History</h3>
        <div id="vh">Loading…</div>`;
    fetch(`get_patient_history.php?matrix_number=${encodeURIComponent(s.matrix_number)}`)
        .then(r=>r.json()).then(d=>{
            if(d.success && d.history.length){
                let h='<table class="hist-table"><thead><tr><th>Queue</th><th>Service</th><th>Status</th><th>Date</th></tr></thead><tbody>';
                d.history.forEach(r=>h+=`<tr><td><strong>${r.queue_number}</strong></td><td>${r.service_type}</td><td>${r.queue_status}</td><td>${new Date(r.created_at).toLocaleDateString('en-MY')}</td></tr>`);
                document.getElementById('vh').innerHTML=h+'</tbody></table>';
            } else {
                document.getElementById('vh').innerHTML='<p style="color:var(--slate-400);text-align:center;padding:20px;font-size:13px">No visit history found.</p>';
            }
        }).catch(()=>{
            document.getElementById('vh').innerHTML='<p style="color:var(--red-600);font-size:13px">Error loading history.</p>';
        });
}
</script>
</body></html>