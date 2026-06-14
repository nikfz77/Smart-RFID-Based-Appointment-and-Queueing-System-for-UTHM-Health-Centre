<?php
include 'login_check.php';
$host='localhost';$dbname='queue_and_appointment_management';$db_user='root';$db_pass='';
$filter_status=$_GET['status']??'';$filter_date=$_GET['date']??'';$filter_service=$_GET['service']??'';$search=$_GET['search']??'';
try{
    $pdo=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",$db_user,$db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $where=[];$params=[];
    if($filter_status){$where[]="a.status=:status";$params[':status']=$filter_status;}
    if($filter_date){$where[]="DATE(a.schedule_time)=:date";$params[':date']=$filter_date;}
    if($filter_service){$where[]="a.service_type=:service";$params[':service']=$filter_service;}
    if($search){$where[]="(a.matrix_number LIKE :s OR a.student_name LIKE :s2)";$params[':s']="%$search%";$params[':s2']="%$search%";}
    $sql="SELECT a.*,u.full_name as staff_name FROM appointments a LEFT JOIN users u ON a.created_by=u.id";
    if($where) $sql.=" WHERE ".implode(" AND ",$where);
    $sql.=" ORDER BY a.schedule_time DESC";
    $stmt=$pdo->prepare($sql);$stmt->execute($params);$appointments=$stmt->fetchAll(PDO::FETCH_ASSOC);
    try{$pdo->exec("ALTER TABLE appointments ADD COLUMN IF NOT EXISTS assigned_doctor_id INT NULL DEFAULT NULL");$pdo->exec("ALTER TABLE appointments ADD COLUMN IF NOT EXISTS assigned_room VARCHAR(20) NULL DEFAULT NULL");}catch(Exception $e){}
    $total=$pdo->query("SELECT COUNT(*) FROM appointments")->fetchColumn();
    $pending=$pdo->query("SELECT COUNT(*) FROM appointments WHERE status='Pending'")->fetchColumn();
    $confirmed=$pdo->query("SELECT COUNT(*) FROM appointments WHERE status='Confirmed'")->fetchColumn();
    $today=$pdo->query("SELECT COUNT(*) FROM appointments WHERE DATE(schedule_time)=CURDATE()")->fetchColumn();
}catch(PDOException $e){$appointments=[];$total=$pending=$confirmed=$today=0;}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments — PKU UTHM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        :root{--blue-900:#0c2461;--blue-700:#1e4fa0;--blue-600:#1d5bb5;--blue-500:#2563eb;--blue-100:#dbeafe;--blue-50:#eff6ff;--green-600:#16a34a;--green-100:#dcfce7;--amber-600:#d97706;--amber-100:#fef3c7;--red-600:#dc2626;--red-100:#fee2e2;--purple-600:#7c3aed;--purple-100:#ede9fe;--slate-900:#0f172a;--slate-700:#334155;--slate-500:#64748b;--slate-300:#cbd5e1;--slate-200:#e2e8f0;--slate-100:#f1f5f9;--slate-50:#f8fafc;--white:#ffffff;--sidebar-w:232px;--topbar-h:58px;--font:'Plus Jakarta Sans',sans-serif}
        html,body{height:100%;font-family:var(--font);background:var(--slate-100);color:var(--slate-900);font-size:14px}
        .sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--sidebar-w);background:var(--blue-900);display:flex;flex-direction:column;z-index:50}
        .sidebar-top{height:var(--topbar-h);display:flex;align-items:center;gap:10px;padding:0 18px;border-bottom:1px solid rgba(255,255,255,0.07);flex-shrink:0}
        .logo-mark{width:32px;height:32px;background:var(--blue-500);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;color:white}
        .logo-text strong{display:block;font-size:13px;font-weight:700;color:#fff}.logo-text span{font-size:10.5px;color:rgba(255,255,255,0.45)}
        .sidebar-nav{flex:1;overflow-y:auto;padding:10px 10px 0}.sidebar-nav::-webkit-scrollbar{width:0}
        .nav-section{font-size:9.5px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:rgba(255,255,255,0.45);padding:16px 8px 5px}
        .nav-link{display:flex;align-items:center;gap:9px;padding:8px 10px;border-radius:7px;font-size:13px;font-weight:500;color:#fff;text-decoration:none;transition:all .15s;margin-bottom:1px}
        .nav-link:hover{background:rgba(255,255,255,0.09)}.nav-link.active{background:var(--blue-600);font-weight:600}
        .nav-link .ico{font-size:14px;width:18px;text-align:center;flex-shrink:0}
        .sidebar-footer{padding:12px 10px 16px;border-top:1px solid rgba(255,255,255,0.07);flex-shrink:0}
        .user-card{display:flex;align-items:center;gap:9px;padding:9px 10px;border-radius:8px;background:rgba(255,255,255,0.06)}
        .user-ava{width:30px;height:30px;border-radius:7px;background:var(--blue-600);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0}
        .user-info strong{display:block;font-size:12px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:130px;max-width:120px}
        .user-info span{font-size:10.5px;color:rgba(255,255,255,0.4)}
        .topbar{position:fixed;top:0;left:var(--sidebar-w);right:0;height:var(--topbar-h);background:var(--white);border-bottom:1px solid var(--slate-200);display:flex;align-items:center;padding:0 24px;gap:12px;z-index:40}
        .topbar-title{font-size:15px;font-weight:700;color:var(--slate-900)}
        .breadcrumb{font-size:11.5px;color:var(--slate-500);margin-top:1px;display:flex;align-items:center;gap:4px}
        .breadcrumb a{color:var(--slate-500);text-decoration:none}.breadcrumb a:hover{color:var(--blue-500)}.breadcrumb .sep{color:var(--slate-300)}
        .topbar-right{margin-left:auto;display:flex;align-items:center;gap:8px}
        .topbar-date{font-size:12px;color:var(--slate-500);font-weight:500;padding:5px 12px;background:var(--slate-50);border:1px solid var(--slate-200);border-radius:6px}
        .btn{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:8px;font-family:var(--font);font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .15s;text-decoration:none}
        .btn-primary{background:var(--blue-600);color:#fff}.btn-primary:hover{background:var(--blue-700)}
        .btn-outline{background:var(--slate-50);color:var(--slate-700);border:1px solid var(--slate-200)}.btn-outline:hover{background:var(--blue-50);color:var(--blue-600)}
        .btn-sm{padding:5px 10px;font-size:12px;border-radius:6px}
        .btn:disabled{opacity:.5;cursor:not-allowed}
        .page{margin-left:var(--sidebar-w);padding-top:var(--topbar-h);min-height:100vh}
        .page-body{padding:22px 26px}
        .page-intro h1{font-size:19px;font-weight:800;color:var(--slate-900);letter-spacing:-0.4px}
        .page-intro p{font-size:12.5px;color:var(--slate-500);margin-top:3px}
        .stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:16px}
        .stat-card{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;padding:15px 16px;display:flex;align-items:center;gap:12px}
        .stat-icon{width:40px;height:40px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:17px;flex-shrink:0}
        .stat-icon.blue{background:var(--blue-50);color:var(--blue-600)}.stat-icon.green{background:var(--green-100);color:var(--green-600)}.stat-icon.amber{background:var(--amber-100);color:var(--amber-600)}
        .stat-val{font-size:24px;font-weight:800;letter-spacing:-1px;line-height:1}
        .stat-val.blue{color:var(--blue-600)}.stat-val.green{color:var(--green-600)}.stat-val.amber{color:var(--amber-600)}
        .stat-lbl{font-size:11.5px;color:var(--slate-500);margin-top:3px}
        .filter-bar{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;padding:14px 18px;margin-bottom:14px}
        .filter-row{display:flex;gap:10px;flex-wrap:wrap;align-items:flex-end}
        .filter-row .fg{flex:1;min-width:120px;margin-bottom:0}
        .fg label{display:block;font-size:11px;font-weight:700;color:var(--slate-600);margin-bottom:5px;text-transform:uppercase;letter-spacing:.4px}
        .fg input,.fg select{width:100%;padding:9px 12px;border:1.5px solid var(--slate-200);border-radius:8px;font-size:13px;font-family:var(--font);color:var(--slate-900)}
        .fg input:focus,.fg select:focus{border-color:var(--blue-500);outline:none;box-shadow:0 0 0 3px rgba(37,99,235,.08)}
        .table-card{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;overflow:hidden}
        .table-head{padding:13px 18px;border-bottom:1px solid var(--slate-100);display:flex;justify-content:space-between;align-items:center}
        .table-head h2{font-size:13px;font-weight:700;color:var(--slate-900)}
        .data-table{width:100%;border-collapse:collapse}
        .data-table thead tr{background:var(--slate-50)}
        .data-table th{padding:9px 14px;text-align:left;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--slate-400);border-bottom:1px solid var(--slate-100);white-space:nowrap}
        .data-table td{padding:10px 14px;font-size:13px;color:var(--slate-700);border-bottom:1px solid var(--slate-50);vertical-align:middle}
        .data-table tbody tr:last-child td{border-bottom:none}
        .data-table tbody tr:hover{background:var(--slate-50)}
        .badge{display:inline-flex;align-items:center;gap:5px;padding:3px 8px;border-radius:5px;font-size:11px;font-weight:700}
        .badge::before{content:'';width:5px;height:5px;border-radius:50%}
        .badge-Pending{background:var(--amber-100);color:#92400e}.badge-Pending::before{background:var(--amber-600)}
        .badge-Confirmed{background:var(--blue-50);color:var(--blue-700)}.badge-Confirmed::before{background:var(--blue-500)}
        .badge-Completed{background:var(--green-100);color:#14532d}.badge-Completed::before{background:var(--green-600)}
        .badge-Cancelled{background:var(--red-100);color:#7f1d1d}.badge-Cancelled::before{background:var(--red-600)}
        .svc-tag{padding:2px 8px;border-radius:5px;font-size:11px;font-weight:600;background:var(--purple-100);color:var(--purple-600)}
        .act-btns{display:flex;gap:5px;flex-wrap:wrap}
        .empty-state{text-align:center;padding:36px;color:var(--slate-400)}
        .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:1000;display:none;justify-content:center;align-items:center}
        .modal-overlay.active{display:flex}
        .modal{background:var(--white);border-radius:12px;padding:24px;max-width:420px;width:90%;animation:mIn .2s ease-out;max-height:90vh;overflow-y:auto}
        @keyframes mIn{from{opacity:0;transform:scale(.96)}to{opacity:1;transform:scale(1)}}
        .modal-hd{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid var(--slate-100)}
        .modal-hd h3{font-size:15px;font-weight:700;color:var(--slate-900)}.modal-close{background:none;border:none;font-size:18px;cursor:pointer;color:var(--slate-400)}
        .modal-actions{display:flex;gap:8px;margin-top:14px}
        .dr-option{padding:12px 14px;border:1.5px solid var(--slate-200);border-radius:8px;cursor:pointer;margin-bottom:8px;transition:all .15s}
        .dr-option:hover,.dr-option.selected{border-color:var(--green-600);background:var(--green-100)}
        #toast{position:fixed;bottom:20px;right:20px;padding:11px 16px;border-radius:9px;font-size:13px;font-weight:600;display:none;z-index:9999;box-shadow:0 4px 20px rgba(0,0,0,.1)}
        #toast.success{background:var(--green-100);color:#14532d;border:1px solid #86efac}
        #toast.error{background:var(--red-100);color:#7f1d1d;border:1px solid #fca5a5}
        @media(max-width:768px){.stats-row{grid-template-columns:1fr 1fr}}
    
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
    <div class="sidebar-top"><div class="logo-mark"><span class="material-symbols-outlined">local_hospital</span></div><div class="logo-text"><strong>PKU UTHM</strong><span>Queue Management System</span></div></div>
    <nav class="sidebar-nav">
        <div class="nav-section">Main</div>
        <a href="staff_dashboard.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">dashboard</span></span> Dashboard</a>
        <a href="queue_management.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">format_list_numbered</span></span> Queue Management</a>
        <a href="appointment_management.php" class="nav-link active"><span class="ico"><span class="material-symbols-outlined">event_available</span></span> Appointments</a>
        <div class="nav-section">Records</div>
        <a href="patient_list.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">personal_injury</span></span> Patients</a>
        <a href="register_user_form.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">person_add</span></span> Register Patient</a>
        <a href="walkin_form.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">subdirectory_arrow_right</span></span> Walk-In</a>
        <a href="reports.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">bar_chart</span></span> Reports</a>
        <div class="nav-section">Display</div>
        <a href="queue_display.html" target="_blank" class="nav-link"><span class="ico"><span class="material-symbols-outlined">monitor</span></span> Public Display</a>
    </nav>
    <div class="sidebar-footer"><div class="user-card">
        <div class="user-ava"><?php echo strtoupper(substr($_SESSION['full_name']??'S',0,1)); ?></div>
        <div class="user-info"><strong><?php echo htmlspecialchars($_SESSION['full_name']??'Staff'); ?></strong><span><?php echo ucfirst($_SESSION['role']??'Staff'); ?></span></div>
        <a href="logout.php" style="font-size:14px;color:rgba(255,255,255,0.3);text-decoration:none;margin-left:4px;"><span class="material-symbols-outlined">logout</span></a>
    </div></div>
</aside>

<header class="topbar">
    <div>
        <div class="topbar-title">Appointments</div>
        <div class="breadcrumb"><a href="staff_dashboard.php">Dashboard</a><span class="sep">›</span><span>Appointment Management</span></div>
    </div>
    <div class="topbar-right">
        <div class="topbar-date"><?php echo date('D, d M Y'); ?></div>
        <a href="appointment_booking.php" class="btn btn-primary" style="padding:7px 14px;font-size:12.5px;">+ New Appointment</a>
    </div>
</header>

<div class="page"><div class="page-body">
    <div class="page-intro"><h1>Appointment Management</h1><p>View, confirm and manage all patient appointments</p></div>

    <div class="stats-row">
        <div class="stat-card"><div class="stat-icon blue"><span class="material-symbols-outlined">calendar_month</span></div><div><div class="stat-val blue"><?php echo $total; ?></div><div class="stat-lbl">Total</div></div></div>
        <div class="stat-card"><div class="stat-icon amber"><span class="material-symbols-outlined">hourglass_empty</span></div><div><div class="stat-val amber"><?php echo $pending; ?></div><div class="stat-lbl">Pending</div></div></div>
        <div class="stat-card"><div class="stat-icon green"><span class="material-symbols-outlined">check</span> </div><div><div class="stat-val green"><?php echo $confirmed; ?></div><div class="stat-lbl">Confirmed</div></div></div>
        <div class="stat-card"><div class="stat-icon blue"><span class="material-symbols-outlined">calendar_today</span></div><div><div class="stat-val blue"><?php echo $today; ?></div><div class="stat-lbl">Today</div></div></div>
    </div>

    <div class="filter-bar">
        <form method="GET"><div class="filter-row">
            <div class="fg"><label>Search</label><input type="text" name="search" placeholder="Matrix or name…" value="<?php echo htmlspecialchars($search); ?>"></div>
            <div class="fg"><label>Status</label><select name="status"><option value="">All Status</option><option value="Pending" <?php if($filter_status==='Pending') echo 'selected'; ?>>Pending</option><option value="Confirmed" <?php if($filter_status==='Confirmed') echo 'selected'; ?>>Confirmed</option><option value="Completed" <?php if($filter_status==='Completed') echo 'selected'; ?>>Completed</option><option value="Cancelled" <?php if($filter_status==='Cancelled') echo 'selected'; ?>>Cancelled</option></select></div>
            <div class="fg"><label>Service</label><select name="service"><option value="">All Services</option><option value="General Consultation">General Consultation</option><option value="Follow-up Check">Follow-up Check</option><option value="Vaccination">Vaccination</option><option value="Prescription Refill">Prescription Refill</option></select></div>
            <div class="fg"><label>Date</label><input type="date" name="date" value="<?php echo htmlspecialchars($filter_date); ?>"></div>
            <div style="display:flex;gap:8px;align-items:flex-end">
                <button type="submit" class="btn btn-primary" style="padding:9px 16px;">Filter</button>
                <a href="appointment_management.php" class="btn btn-outline" style="padding:9px 14px;">Clear</a>
            </div>
        </div></form>
    </div>

    <div class="table-card">
        <div class="table-head"><h2>Appointments</h2><span>Showing <?php echo count($appointments); ?> records</span></div>
        <?php if(empty($appointments)): ?>
        <div class="empty-state"><div style="font-size:32px;margin-bottom:10px;opacity:.4"><span class="material-symbols-outlined">inbox</span></div><p>No appointments found — try adjusting your filters</p></div>
        <?php else: ?>
        <div style="overflow-x:auto">
        <table class="data-table">
            <thead><tr><th>#</th><th>Matrix</th><th>Student</th><th>Service</th><th>Schedule</th><th>Status</th><th>Booked By</th><th>Notes</th><th>Doctor / Room</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach($appointments as $i=>$a): ?>
            <tr id="row-<?php echo $a['id']; ?>">
                <td style="color:var(--slate-400);font-size:12px"><?php echo $i+1; ?></td>
                <td><span style="font-family:monospace;font-size:12px;font-weight:600"><?php echo htmlspecialchars($a['matrix_number']); ?></span></td>
                <td style="font-weight:600"><?php echo htmlspecialchars($a['student_name']); ?></td>
                <td><span class="svc-tag"><?php echo htmlspecialchars($a['service_type']); ?></span></td>
                <td style="font-size:12px;white-space:nowrap"><?php $t=strtotime($a['schedule_time']); echo date('d M Y',$t); ?><br><span style="color:var(--slate-400)"><?php echo date('h:i A',$t); ?></span></td>
                <td><span class="badge badge-<?php echo $a['status']; ?>"><?php echo $a['status']; ?></span></td>
                <td style="color:var(--slate-400);font-size:12px"><?php echo htmlspecialchars($a['staff_name']??'N/A'); ?></td>
                <td style="color:var(--slate-500);font-size:12px;max-width:140px"><?php echo htmlspecialchars($a['notes']??'—'); ?></td>
                <td>
                    <?php $dr=null; if(!empty($a['assigned_doctor_id'])){$drs=$pdo->prepare("SELECT full_name,room FROM users WHERE id=:id");$drs->execute([':id'=>$a['assigned_doctor_id']]);$dr=$drs->fetch(PDO::FETCH_ASSOC);} if($dr): ?>
                    <div style="font-size:12px;font-weight:600;color:var(--green-600)"><?php echo htmlspecialchars($dr['full_name']); ?></div>
                    <div style="font-size:11px;color:var(--blue-600);font-weight:600"><?php echo htmlspecialchars($dr['room']??''); ?></div>
                    <?php else: ?><span style="color:var(--slate-400);font-size:12px">Not assigned</span><?php endif; ?>
                </td>
                <td>
                    <div class="act-btns">
                    <?php if($a['status']==='Pending'): ?>
                    <button class="btn btn-sm" style="background:var(--blue-50);color:var(--blue-600);" onclick="updateStatus(<?php echo $a['id']; ?>,'Confirmed')">Confirm</button>
                    <button class="btn btn-sm" style="background:var(--slate-100);color:var(--slate-600);" onclick="openAssignDr(<?php echo $a['id']; ?>)">Assign Dr</button>
                    <button class="btn btn-sm" style="background:var(--red-100);color:var(--red-600);" onclick="updateStatus(<?php echo $a['id']; ?>,'Cancelled')">Cancel</button>
                    <?php elseif($a['status']==='Confirmed'): ?>
                    <button class="btn btn-sm" style="background:var(--green-100);color:#14532d;" onclick="updateStatus(<?php echo $a['id']; ?>,'Completed')">Complete</button>
                    <button class="btn btn-sm" style="background:var(--slate-100);color:var(--slate-600);" onclick="openAssignDr(<?php echo $a['id']; ?>)">Assign Dr</button>
                    <button class="btn btn-sm" style="background:var(--red-100);color:var(--red-600);" onclick="updateStatus(<?php echo $a['id']; ?>,'Cancelled')">Cancel</button>
                    <?php else: ?><span style="color:var(--slate-400);font-size:12px">—</span><?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table></div>
        <?php endif; ?>
    </div>
</div></div>

<div id="toast"></div>

<!-- Assign Doctor Modal -->
<div class="modal-overlay" id="dr-modal">
    <div class="modal">
        <div class="modal-hd"><h3><span class="material-symbols-outlined">medical_services</span> Assign Doctor</h3><button class="modal-close" onclick="document.getElementById('dr-modal').classList.remove('active')">&times;</button></div>
        <p style="font-size:12.5px;color:var(--slate-500);margin-bottom:12px">Select an available doctor:</p>
        <div id="dr-options">Loading…</div>
        <div id="dr-msg" style="padding:9px;border-radius:7px;font-size:13px;font-weight:600;margin-top:10px;display:none"></div>
        <div class="modal-actions">
            <button class="btn btn-primary" onclick="confirmAssignDr()" style="flex:1">Assign</button>
            <button class="btn btn-outline" onclick="document.getElementById('dr-modal').classList.remove('active')">Cancel</button>
        </div>
    </div>
</div>

<script>
let assignId=null,selDrId=null,selRoom=null;
function showToast(msg,type){const t=document.getElementById('toast');t.textContent=msg;t.className=type;t.style.display='block';setTimeout(()=>t.style.display='none',4000);}
function updateStatus(id,status){
    if(!confirm('Are you sure?')) return;
    document.getElementById('row-'+id).querySelectorAll('.btn').forEach(b=>b.disabled=true);
    fetch('update_appointment.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({appointment_id:id,status})})
    .then(r=>r.json()).then(d=>{if(d.success){showToast('<span class="material-symbols-outlined">check</span> Updated to '+status,'success');setTimeout(()=>location.reload(),1500);}else{showToast('<span class="material-symbols-outlined">warning</span> '+d.message,'error');document.getElementById('row-'+id).querySelectorAll('.btn').forEach(b=>b.disabled=false);}})
    .catch(()=>showToast('Network error','error'));
}
function openAssignDr(id){
    assignId=id;selDrId=null;selRoom=null;
    document.getElementById('dr-msg').style.display='none';
    document.getElementById('dr-modal').classList.add('active');
    fetch('get_doctor_availability.php').then(r=>r.json()).then(docs=>{
        const available=docs.filter(d=>d.is_available==1&&d.room);
        const c=document.getElementById('dr-options');
        if(!available.length){c.innerHTML='<p style="color:var(--red-600);font-size:13px">No doctors available.</p>';return;}
        c.innerHTML=available.map(d=>`<div class="dr-option" onclick="selDr(this,${d.id},'${d.room}')"><div style="font-size:13px;font-weight:600;color:var(--slate-900)"><span class="material-symbols-outlined">medical_services</span> ${d.full_name}</div><div style="font-size:12px;color:var(--slate-500)">${d.room} · ${d.clocked_in_at?new Date(d.clocked_in_at).toLocaleTimeString('en-MY',{hour:'2-digit',minute:'2-digit'}):'—'}</div></div>`).join('');
    });
}
function selDr(el,did,room){document.querySelectorAll('.dr-option').forEach(o=>o.classList.remove('selected'));el.classList.add('selected');selDrId=did;selRoom=room;}
function confirmAssignDr(){
    if(!selDrId){const m=document.getElementById('dr-msg');m.textContent='Please select a doctor.';m.style.background='var(--red-100)';m.style.color='#7f1d1d';m.style.display='block';return;}
    fetch('assign_doctor_appointment.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({appointment_id:assignId,doctor_id:selDrId,room:selRoom})})
    .then(r=>r.json()).then(d=>{const m=document.getElementById('dr-msg');m.textContent=d.message;m.style.background=d.success?'var(--green-100)':'var(--red-100)';m.style.color=d.success?'#14532d':'#7f1d1d';m.style.display='block';if(d.success)setTimeout(()=>{document.getElementById('dr-modal').classList.remove('active');location.reload();},1500);});
}
</script>
</body></html>