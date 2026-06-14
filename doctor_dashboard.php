<?php
include 'login_check.php';
date_default_timezone_set('Asia/Kuala_Lumpur');
$host='localhost';$dbname='queue_and_appointment_management';$db_user='root';$db_pass='';
try{
    $pdo=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",$db_user,$db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET time_zone='+08:00'");
    $doc_id=$_SESSION['user_id'];
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS profile_pic VARCHAR(255) NULL DEFAULT NULL");
    try{ $pdo->exec("ALTER TABLE queue ADD COLUMN IF NOT EXISTS called_at DATETIME NULL DEFAULT NULL"); }catch(Exception $e){}
    $doc=$pdo->prepare("SELECT * FROM users WHERE id=:id");
    $doc->execute([':id'=>$doc_id]);
    $doctor=$doc->fetch(PDO::FETCH_ASSOC);
    $pic_url=!empty($doctor['profile_pic'])?'uploads/profiles/'.$doctor['profile_pic']:null;
    $patients=$pdo->prepare("
        SELECT q.*,s.full_name as student_name,s.matrix_number as mx,q.source_faculty,q.node_id
        FROM queue q LEFT JOIN students s ON q.matrix_number=s.matrix_number
        WHERE q.assigned_doctor_id=:did AND DATE(q.created_at)=CURDATE() AND q.queue_status NOT IN ('Cancelled')
        ORDER BY q.scheduled_time ASC,q.created_at ASC
    ");
    $patients->execute([':did'=>$doc_id]);
    $patient_list=$patients->fetchAll(PDO::FETCH_ASSOC);
    $appts=$pdo->prepare("
        SELECT a.id,a.matrix_number,a.student_name,a.service_type,a.schedule_time,a.status,a.notes
        FROM appointments a
        WHERE a.assigned_doctor_id=:did AND DATE(a.schedule_time)=CURDATE() AND a.status NOT IN ('Cancelled')
        ORDER BY a.schedule_time ASC
    ");
    $appts->execute([':did'=>$doc_id]);
    $appt_list=$appts->fetchAll(PDO::FETCH_ASSOC);
    $wq=$pdo->prepare("SELECT COUNT(*) FROM queue WHERE assigned_doctor_id=:did AND queue_status='Waiting' AND DATE(created_at)=CURDATE()");
    $wq->execute([':did'=>$doc_id]);$waiting_count=$wq->fetchColumn();
    $sq=$pdo->prepare("SELECT COUNT(*) FROM queue WHERE assigned_doctor_id=:did AND queue_status='Being-Served' AND DATE(created_at)=CURDATE()");
    $sq->execute([':did'=>$doc_id]);$serving_count=$sq->fetchColumn();
    $dq=$pdo->prepare("SELECT COUNT(*) FROM queue WHERE assigned_doctor_id=:did AND queue_status='Completed' AND DATE(created_at)=CURDATE()");
    $dq->execute([':did'=>$doc_id]);$done_count=$dq->fetchColumn();
}catch(PDOException $e){
    $patient_list=[];$appt_list=[];
    $waiting_count=$serving_count=$done_count=0;
    $doctor=['full_name'=>'Doctor','room'=>null,'is_available'=>0,'profile_pic'=>null];
    $pic_url=null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard — PKU UTHM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        :root{
            --amber-950:#451a03;--amber-900:#78350f;--amber-700:#b45309;
            --amber-600:#d97706;--amber-500:#f59e0b;--amber-100:#fef3c7;--amber-50:#fffbeb;
            --green-600:#16a34a;--green-100:#dcfce7;
            --red-600:#dc2626;--red-100:#fee2e2;
            --blue-600:#1d5bb5;--blue-50:#eff6ff;
            --slate-900:#0f172a;--slate-700:#334155;--slate-500:#64748b;
            --slate-300:#cbd5e1;--slate-200:#e2e8f0;--slate-100:#f1f5f9;--slate-50:#f8fafc;
            --white:#fff;--sidebar-w:232px;--topbar-h:58px;--font:'Plus Jakarta Sans',sans-serif;
        }
        html,body{height:100%;font-family:var(--font);background:var(--slate-100);color:var(--slate-900);font-size:14px}
        .sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--sidebar-w);background:var(--amber-900);display:flex;flex-direction:column;z-index:50}
        .sidebar-top{height:var(--topbar-h);display:flex;align-items:center;gap:10px;padding:0 18px;border-bottom:1px solid rgba(255,255,255,0.08);flex-shrink:0}
        .logo-mark{width:32px;height:32px;background:var(--amber-600);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .logo-text strong{display:block;font-size:13px;font-weight:700;color:#fff;font-family:'Sora',sans-serif;letter-spacing:-.2px}
        .logo-text span{font-size:10.5px;color:rgba(255,255,255,0.45)}
        .role-pill{display:inline-block;background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.2);color:#fff;font-size:9px;font-weight:800;letter-spacing:1px;text-transform:uppercase;padding:2px 7px;border-radius:10px;margin-top:3px}
        .sidebar-nav{flex:1;overflow-y:auto;padding:10px 10px 0}.sidebar-nav::-webkit-scrollbar{width:0}
        .nav-section{font-size:9.5px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:rgba(255,255,255,0.4);padding:16px 8px 5px}
        .nav-link{display:flex;align-items:center;gap:9px;padding:8px 10px;border-radius:7px;font-size:13px;font-weight:500;color:#fff;text-decoration:none;transition:all .15s;margin-bottom:1px;border:none;background:none;width:100%;cursor:pointer;font-family:var(--font)}
        .nav-link:hover{background:rgba(255,255,255,0.1)}
        .nav-link.active{background:var(--amber-700);font-weight:700}
        .nav-link .ico{font-size:14px;width:18px;text-align:center;flex-shrink:0;display:flex;align-items:center;justify-content:center}
        .sidebar-footer{padding:12px 10px 16px;border-top:1px solid rgba(255,255,255,0.08);flex-shrink:0}
        .user-card{display:flex;align-items:center;gap:9px;padding:9px 10px;border-radius:8px;background:rgba(255,255,255,0.07)}
        .user-ava{width:30px;height:30px;border-radius:7px;background:var(--amber-600);display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;overflow:hidden}
        .user-ava img{width:100%;height:100%;object-fit:cover}
        .user-info{flex:1;min-width:0;overflow:hidden}
        .user-info strong{display:block;font-size:12px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:130px;max-width:120px}
        .user-info span{font-size:10.5px;color:rgba(255,255,255,0.4);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block}
        .topbar{position:fixed;top:0;left:var(--sidebar-w);right:0;height:var(--topbar-h);background:var(--white);border-bottom:1px solid var(--slate-200);display:flex;align-items:center;padding:0 24px;gap:12px;z-index:40}
        .topbar-title{font-size:15px;font-weight:700;color:var(--slate-900)}
        .breadcrumb{font-size:11.5px;color:var(--slate-500);margin-top:1px;display:flex;align-items:center;gap:4px}
        .breadcrumb .sep{color:var(--slate-300)}
        .topbar-right{margin-left:auto;display:flex;align-items:center;gap:8px}
        .topbar-date{font-size:12px;color:var(--slate-500);font-weight:500;padding:5px 12px;background:var(--slate-50);border:1px solid var(--slate-200);border-radius:6px}
        .avail-badge{display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:7px;font-size:12.5px;font-weight:700}
        .avail-badge.online{background:var(--green-100);color:#14532d;border:1px solid #86efac}
        .avail-badge.offline{background:var(--red-100);color:#7f1d1d;border:1px solid #fca5a5}
        .avail-dot{width:7px;height:7px;border-radius:50%}
        .avail-dot.online{background:var(--green-600)}.avail-dot.offline{background:var(--red-600)}
        .btn-avail{padding:7px 14px;background:var(--amber-600);color:#fff;border:none;border-radius:7px;font-size:12.5px;font-weight:700;cursor:pointer;font-family:var(--font);transition:background .15s;display:inline-flex;align-items:center;gap:6px}
        .btn-avail:hover{background:var(--amber-700)}
        .page{margin-left:var(--sidebar-w);padding-top:var(--topbar-h);min-height:100vh}
        .page-body{padding:22px 26px}
        .page-intro{margin-bottom:18px}
        .page-intro h1{font-size:19px;font-weight:800;color:var(--slate-900);letter-spacing:-0.4px}
        .page-intro p{font-size:12.5px;color:var(--slate-500);margin-top:3px}
        .stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:18px}
        .stat-card{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;padding:16px;display:flex;align-items:center;gap:13px;transition:box-shadow .2s}
        .stat-card:hover{box-shadow:0 4px 16px rgba(217,119,6,0.1)}
        .stat-icon{width:42px;height:42px;border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .stat-icon.amber{background:var(--amber-100)}.stat-icon.green{background:var(--green-100)}.stat-icon.slate{background:var(--slate-100)}
        .stat-icon.amber .material-symbols-outlined{color:var(--amber-600)}
        .stat-icon.green .material-symbols-outlined{color:var(--green-600)}
        .stat-icon.slate .material-symbols-outlined{color:var(--slate-500)}
        .stat-val{font-size:26px;font-weight:800;letter-spacing:-1px;line-height:1}
        .stat-val.amber{color:var(--amber-600)}.stat-val.green{color:var(--green-600)}.stat-val.slate{color:var(--slate-500)}
        .stat-lbl{font-size:11.5px;color:var(--slate-500);margin-top:3px;font-weight:500}
        .card{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;overflow:hidden;margin-bottom:16px}
        .card-head{padding:13px 18px;border-bottom:1px solid var(--slate-100);display:flex;align-items:center;justify-content:space-between}
        .card-head h2{font-size:13px;font-weight:700;color:var(--slate-900);display:flex;align-items:center;gap:8px}
        .card-head-meta{font-size:11.5px;color:var(--slate-400);font-weight:500}
        .data-table{width:100%;border-collapse:collapse}
        .data-table thead tr{background:var(--amber-50)}
        .data-table th{padding:9px 14px;text-align:left;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--amber-700);border-bottom:1px solid var(--amber-100);white-space:nowrap}
        .data-table td{padding:11px 14px;font-size:13px;color:var(--slate-700);border-bottom:1px solid var(--slate-50);vertical-align:middle}
        .data-table tbody tr:last-child td{border-bottom:none}
        .data-table tbody tr:hover{background:var(--amber-50)}
        .badge{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:5px;font-size:11px;font-weight:700}
        .badge::before{content:'';width:5px;height:5px;border-radius:50%}
        .badge-waiting{background:var(--amber-100);color:#92400e}.badge-waiting::before{background:var(--amber-600)}
        .badge-called{background:#e0f2fe;color:#0369a1}.badge-called::before{background:#0ea5e9}
        .badge-serving{background:var(--blue-50);color:var(--blue-600)}.badge-serving::before{background:var(--blue-600)}
        .badge-done{background:var(--green-100);color:#14532d}.badge-done::before{background:var(--green-600)}
        .badge-Pending{background:var(--amber-100);color:#92400e}.badge-Pending::before{background:var(--amber-600)}
        .badge-Confirmed{background:var(--blue-50);color:var(--blue-600)}.badge-Confirmed::before{background:var(--blue-600)}
        .badge-Completed{background:var(--green-100);color:#14532d}.badge-Completed::before{background:var(--green-600)}
        .src-chip{padding:2px 8px;border-radius:4px;font-size:10.5px;font-weight:700}
        .src-walkin{background:var(--amber-100);color:var(--amber-700)}
        .src-device{background:var(--blue-50);color:var(--blue-600)}
        .btn{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;border-radius:6px;font-family:var(--font);font-size:12px;font-weight:700;cursor:pointer;border:none;transition:all .15s}
        .btn-call{background:var(--amber-600);color:#fff}.btn-call:hover{background:var(--amber-700)}
        .btn-arrive{background:var(--green-100);color:#14532d}.btn-arrive:hover{background:#bbf7d0}
        .btn-done{background:var(--slate-100);color:var(--slate-700)}.btn-done:hover{background:var(--slate-200)}
        .btn-complete-appt{background:var(--green-100);color:#14532d}.btn-complete-appt:hover{background:#bbf7d0}
        .countdown{display:inline-flex;align-items:center;padding:3px 9px;border-radius:5px;font-size:11.5px;font-weight:700;font-family:monospace;margin-left:6px}
        .countdown.normal{background:var(--amber-100);color:var(--amber-700)}
        .countdown.urgent{background:var(--red-100);color:var(--red-600);animation:blink .8s infinite}
        @keyframes blink{0%,100%{opacity:1}50%{opacity:.4}}
        .empty-state{text-align:center;padding:32px 16px;color:var(--slate-400)}
        .modal-bg{position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:200;display:none;align-items:center;justify-content:center}
        .modal-bg.open{display:flex}
        .modal{background:var(--white);border-radius:14px;padding:28px;width:100%;max-width:420px;animation:mIn .2s ease-out;max-height:90vh;overflow-y:auto}
        @keyframes mIn{from{opacity:0;transform:scale(.96)}to{opacity:1;transform:scale(1)}}
        .modal-hd{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;padding-bottom:14px;border-bottom:1px solid var(--slate-100)}
        .modal-hd h3{font-size:16px;font-weight:800;color:var(--slate-900)}
        .modal-close{background:none;border:none;font-size:18px;cursor:pointer;color:var(--slate-400)}
        .modal-info{background:var(--amber-50);border:1px solid var(--amber-100);border-radius:8px;padding:10px 13px;font-size:13px;color:var(--amber-700);font-weight:600;margin-bottom:16px}
        .fg{margin-bottom:14px}
        .fg label{display:block;font-size:11px;font-weight:700;color:var(--slate-600);margin-bottom:6px;text-transform:uppercase;letter-spacing:.4px}
        .fg select,.fg input{width:100%;padding:10px 13px;border:1.5px solid var(--slate-200);border-radius:8px;font-size:13.5px;font-family:var(--font);color:var(--slate-900)}
        .fg select:focus,.fg input:focus{border-color:var(--amber-500);outline:none;box-shadow:0 0 0 3px rgba(245,158,11,.1)}
        .modal-actions{display:flex;gap:8px;margin-top:16px}
        .btn-modal-primary{flex:1;padding:11px;background:var(--amber-600);color:#fff;border:none;border-radius:8px;font-size:13.5px;font-weight:700;cursor:pointer;font-family:var(--font)}
        .btn-modal-primary:hover{background:var(--amber-700)}
        .btn-modal-cancel{padding:11px 18px;background:var(--slate-50);color:var(--slate-700);border:1px solid var(--slate-200);border-radius:8px;font-size:13.5px;font-weight:600;cursor:pointer;font-family:var(--font)}
        .status-msg{padding:9px 13px;border-radius:7px;font-size:13px;font-weight:600;margin-top:12px;display:none}
        .status-msg.success{background:var(--green-100);color:#14532d}
        .status-msg.error{background:var(--red-100);color:#7f1d1d}
        .status-msg.loading{background:var(--amber-100);color:var(--amber-700)}
        .divider{border:none;border-top:1px solid var(--slate-100);margin:18px 0}
        .material-symbols-outlined{font-size:18px;line-height:1;vertical-align:middle;font-variation-settings:'FILL' 0,'wght' 400,'GRAD' 0,'opsz' 24}
        .nav-link .material-symbols-outlined{font-size:16px}
        .stat-icon .material-symbols-outlined{font-size:20px}
        .logo-mark .material-symbols-outlined{font-size:18px;color:#fff}
        @media(max-width:900px){.stats-row{grid-template-columns:1fr 1fr}}
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-top">
        <div class="logo-mark"><span class="material-symbols-outlined">local_hospital</span></div>
        <div class="logo-text">
            <strong>PKU UTHM</strong>
            <span>Queue Management System</span>
            <div class="role-pill">Doctor</div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Doctor Panel</div>
        <a href="doctor_dashboard.php" class="nav-link active"><span class="ico"><span class="material-symbols-outlined">dashboard</span></span> Dashboard</a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-ava">
                <?php if($pic_url): ?>
                <img src="<?php echo htmlspecialchars($pic_url); ?>" alt="Profile">
                <?php else: ?><span class="material-symbols-outlined" style="color:#fff;font-size:16px">medical_services</span><?php endif; ?>
            </div>
            <div class="user-info">
                <strong><?php echo htmlspecialchars($doctor['full_name']??'Doctor'); ?></strong>
                <span><?php echo $doctor['room']?htmlspecialchars($doctor['room']):'No room set'; ?></span>
            </div>
            <a href="logout.php" style="font-size:20px;color:rgba(255,255,255,0.3);text-decoration:none;margin-left:auto;" title="Logout"><span class="material-symbols-outlined">logout</span></a>
        </div>
    </div>
</aside>

<header class="topbar">
    <div>
        <div class="topbar-title">Doctor Dashboard</div>
        <div class="breadcrumb"><span>PKU UTHM</span><span class="sep">›</span><span>Doctor Panel</span></div>
    </div>
    <div class="topbar-right">
        <div class="topbar-date" id="live-time"><?php echo date('D, d M Y · h:i A'); ?></div>
        <?php if($doctor['is_available'] && $doctor['room']): ?>
        <div class="avail-badge online"><div class="avail-dot online"></div>Available — <?php echo htmlspecialchars($doctor['room']); ?></div>
        <?php else: ?>
        <div class="avail-badge offline"><div class="avail-dot offline"></div>Unavailable</div>
        <?php endif; ?>
        <button class="btn-avail" onclick="document.getElementById('avail-modal').classList.add('open')"><span class="material-symbols-outlined">settings</span> Set Availability</button>
    </div>
</header>

<div class="page">
    <div class="page-body">
        <div class="page-intro">
            <h1>Good <?php echo (date('H')<12)?'Morning':(date('H')<17?'Afternoon':'Evening'); ?>, <?php echo htmlspecialchars($doctor['full_name']??'Doctor'); ?> <span class="material-symbols-outlined" style="color:var(--amber-500);font-size:22px;vertical-align:middle">waving_hand</span></h1>
            <p><?php echo date('l, d F Y'); ?> — Here's your patient schedule for today</p>
        </div>

        <div class="stats-row">
            <div class="stat-card"><div class="stat-icon amber"><span class="material-symbols-outlined">hourglass_empty</span></div><div><div class="stat-val amber"><?php echo $waiting_count; ?></div><div class="stat-lbl">Waiting</div></div></div>
            <div class="stat-card"><div class="stat-icon green"><span class="material-symbols-outlined">stethoscope</span></div><div><div class="stat-val green"><?php echo $serving_count; ?></div><div class="stat-lbl">Being Served</div></div></div>
            <div class="stat-card"><div class="stat-icon slate"><span class="material-symbols-outlined">check_circle</span></div><div><div class="stat-val slate"><?php echo $done_count; ?></div><div class="stat-lbl">Completed Today</div></div></div>
        </div>

        <div class="card">
            <div class="card-head">
                <h2><span class="material-symbols-outlined" style="color:var(--amber-600)">medical_services</span> My Patients Today</h2>
                <span class="card-head-meta"><?php echo $doctor['room']?htmlspecialchars($doctor['room']):'No room assigned'; ?> · <?php echo count($patient_list); ?> patient<?php echo count($patient_list)!=1?'s':''; ?></span>
            </div>
            <?php if(empty($patient_list)): ?>
            <div class="empty-state"><div style="font-size:32px;margin-bottom:8px;opacity:.4"><span class="material-symbols-outlined" style="font-size:32px">inbox</span></div><p>No patients assigned to you today</p></div>
            <?php else: ?>
            <div style="overflow-x:auto">
            <table class="data-table">
                <thead><tr><th>Queue</th><th>Patient</th><th>Matrix</th><th>Service</th><th>Source</th><th>Sched. Time</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                <?php foreach($patient_list as $p):
                    $isCalled = $p['queue_status']==='Waiting' && !empty($p['called_at']);
                    $bc='badge-waiting';
                    if($isCalled) $bc='badge-called';
                    elseif($p['queue_status']==='Being-Served') $bc='badge-serving';
                    elseif($p['queue_status']==='Completed') $bc='badge-done';
                ?>
                <tr>
                    <td><strong style="font-family:monospace;font-size:13px"><?php echo htmlspecialchars($p['queue_number']); ?></strong></td>
                    <td style="font-weight:600"><?php echo htmlspecialchars($p['student_name']??$p['matrix_number']); ?></td>
                    <td style="color:var(--slate-400);font-size:12px"><?php echo htmlspecialchars($p['matrix_number']); ?></td>
                    <td><?php echo htmlspecialchars($p['service_type']); ?></td>
                    <td>
                        <?php if(!empty($p['source_faculty'])): ?>
                        <span class="src-chip src-device"><?php echo htmlspecialchars($p['source_faculty']); ?></span>
                        <?php else: ?>
                        <span class="src-chip src-walkin">Walk-In</span>
                        <?php endif; ?>
                    </td>
                    <td style="color:var(--slate-500);font-size:12px"><?php echo $p['scheduled_time']?date('h:i A',strtotime($p['scheduled_time'])):'—'; ?></td>
                    <td><span class="badge <?php echo $bc; ?>"><?php echo $isCalled?'Called':$p['queue_status']; ?></span></td>
                    <td>
                        <?php if($p['queue_status']==='Waiting' && empty($p['called_at'])): ?>
                            <button class="btn btn-call" onclick="callPatient(<?php echo $p['id']; ?>,'<?php echo $p['queue_number']; ?>')"><span class="material-symbols-outlined" style="font-size:14px">campaign</span> Call</button>
                        <?php elseif($isCalled): ?>
                            <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap">
                                <button class="btn btn-arrive" onclick="markArrived(<?php echo $p['id']; ?>,'<?php echo $p['queue_number']; ?>')"><span class="material-symbols-outlined" style="font-size:14px">check</span> Arrived</button>
                                <span class="countdown normal" id="cd-<?php echo $p['id']; ?>" data-called="<?php echo $p['called_at']; ?>">5:00</span>
                            </div>
                        <?php elseif($p['queue_status']==='Being-Served'): ?>
                            <button class="btn btn-done" onclick="completePatient(<?php echo $p['id']; ?>,'<?php echo $p['queue_number']; ?>')"><span class="material-symbols-outlined" style="font-size:14px">check</span> Done</button>
                        <?php else: ?>—<?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php endif; ?>
        </div>

        <div class="card">
            <div class="card-head">
                <h2><span class="material-symbols-outlined" style="color:var(--amber-600)">calendar_month</span> My Appointments Today</h2>
                <span class="card-head-meta"><?php echo date('d F Y'); ?> · <?php echo count($appt_list); ?> appointment<?php echo count($appt_list)!=1?'s':''; ?></span>
            </div>
            <?php if(empty($appt_list)): ?>
            <div class="empty-state"><div style="font-size:32px;margin-bottom:8px;opacity:.4"><span class="material-symbols-outlined" style="font-size:32px">inbox</span></div><p>No appointments assigned to you today</p></div>
            <?php else: ?>
            <div style="overflow-x:auto">
            <table class="data-table">
                <thead><tr><th>Student</th><th>Matrix</th><th>Service</th><th>Time</th><th>Status</th><th>Notes</th><th>Action</th></tr></thead>
                <tbody>
                <?php foreach($appt_list as $a):
                    $bc='badge-Pending';
                    if($a['status']==='Confirmed') $bc='badge-Confirmed';
                    elseif($a['status']==='Completed') $bc='badge-Completed';
                ?>
                <tr>
                    <td style="font-weight:600"><?php echo htmlspecialchars($a['student_name']); ?></td>
                    <td style="color:var(--slate-400);font-size:12px;font-family:monospace"><?php echo htmlspecialchars($a['matrix_number']); ?></td>
                    <td><?php echo htmlspecialchars($a['service_type']); ?></td>
                    <td style="font-size:12px;color:var(--slate-500)"><?php echo date('h:i A',strtotime($a['schedule_time'])); ?></td>
                    <td><span class="badge <?php echo $bc; ?>"><?php echo $a['status']; ?></span></td>
                    <td style="color:var(--slate-500);font-size:12px;max-width:180px"><?php echo htmlspecialchars($a['notes']??'—'); ?></td>
                    <td>
                        <?php if($a['status']==='Pending'||$a['status']==='Confirmed'): ?>
                        <button class="btn btn-complete-appt" onclick="completeAppt(<?php echo $a['id']; ?>)"><span class="material-symbols-outlined" style="font-size:14px">check</span> Mark Done</button>
                        <?php else: ?>—<?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- AVAILABILITY MODAL -->
<div class="modal-bg" id="avail-modal">
    <div class="modal">
        <div class="modal-hd">
            <h3><span class="material-symbols-outlined" style="color:var(--amber-600);vertical-align:middle">settings</span> Set Availability</h3>
            <button class="modal-close" onclick="document.getElementById('avail-modal').classList.remove('open')"><span class="material-symbols-outlined">close</span></button>
        </div>
        <div class="modal-info">Your assigned room: <strong><?php echo htmlspecialchars($doctor['room']??'Not assigned'); ?></strong></div>
        <div class="fg">
            <label>Status</label>
            <select id="avail-status">
                <option value="1" <?php echo $doctor['is_available']?'selected':''; ?>>Available — Clock In</option>
                <option value="0" <?php echo !$doctor['is_available']?'selected':''; ?>>Unavailable — Clock Out</option>
            </select>
        </div>
        <div class="modal-actions">
            <button class="btn-modal-primary" onclick="setAvailability()">Save Status</button>
            <button class="btn-modal-cancel" onclick="document.getElementById('avail-modal').classList.remove('open')">Cancel</button>
        </div>
        <div class="status-msg" id="avail-msg"></div>
        <hr class="divider">
        <div class="fg" style="margin-bottom:0">
            <label>Profile Photo</label>
            <div style="display:flex;align-items:center;gap:12px;margin-top:6px">
                <div class="user-ava" style="width:48px;height:48px;border-radius:10px;background:var(--amber-100);font-size:22px;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0">
                    <?php if($pic_url): ?><img src="<?php echo htmlspecialchars($pic_url); ?>" style="width:100%;height:100%;object-fit:cover" alt=""><?php else: ?><span class="material-symbols-outlined" style="color:var(--amber-600);font-size:24px">medical_services</span><?php endif; ?>
                </div>
                <div>
                    <input type="file" id="photo-upload" accept="image/*" style="display:none" onchange="uploadPhoto()">
                    <button class="btn-avail" onclick="document.getElementById('photo-upload').click()" style="font-size:12px;padding:6px 14px"><span class="material-symbols-outlined" style="font-size:16px">photo_camera</span> Upload Photo</button>
                    <div style="font-size:11px;color:var(--slate-400);margin-top:4px">JPG or PNG, max 2MB</div>
                </div>
            </div>
        </div>
        <div class="status-msg" id="photo-msg" style="margin-top:8px"></div>
    </div>
</div>

<script>
function updateClock(){
    const now=new Date();
    const opts={weekday:'short',day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit',hour12:true};
    document.getElementById('live-time').textContent=now.toLocaleString('en-MY',opts);
}
setInterval(updateClock,60000);

function setAvailability(){
    const room='<?php echo addslashes($doctor["room"]??""); ?>';
    const status=document.getElementById('avail-status').value;
    const msg=document.getElementById('avail-msg');
    if(!room){msg.textContent='No room assigned. Contact admin.';msg.className='status-msg error';msg.style.display='block';return;}
    msg.textContent='Saving…';msg.className='status-msg loading';msg.style.display='block';
    fetch('set_doctor_availability.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({room,is_available:status})})
    .then(r=>r.json()).then(d=>{
        msg.textContent=d.message;msg.className='status-msg '+(d.success?'success':'error');
        if(d.success) setTimeout(()=>location.reload(),900);
    }).catch(()=>{msg.textContent='Error. Try again.';msg.className='status-msg error';});
}

function uploadPhoto(){
    const file=document.getElementById('photo-upload').files[0];
    if(!file) return;
    const form=new FormData();form.append('photo',file);
    const msg=document.getElementById('photo-msg');
    msg.textContent='Uploading…';msg.className='status-msg loading';msg.style.display='block';
    fetch('update_profile_pic.php',{method:'POST',body:form})
    .then(r=>r.json()).then(d=>{
        msg.textContent=d.message;msg.className='status-msg '+(d.success?'success':'error');
        if(d.success) setTimeout(()=>location.reload(),1000);
    }).catch(()=>{msg.textContent='Error uploading';msg.className='status-msg error';});
}

function callPatient(qid,qnum){
    if(!confirm('Call patient '+qnum+' to your room? A 5-minute countdown will start.')) return;
    fetch('update_queue_status.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({queue_id:qid,status:'Called'})})
    .then(r=>r.json()).then(d=>{if(d.success) location.reload();else alert(d.message);});
}
function markArrived(qid,qnum){
    if(!confirm('Mark patient '+qnum+' as arrived? Consultation will begin.')) return;
    fetch('update_queue_status.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({queue_id:qid,status:'Being-Served'})})
    .then(r=>r.json()).then(d=>{if(d.success) location.reload();else alert(d.message);});
}
function completePatient(qid,qnum){
    if(!confirm('Mark patient '+qnum+' as completed?')) return;
    fetch('update_queue_status.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({queue_id:qid,status:'Completed'})})
    .then(r=>r.json()).then(d=>{if(d.success) location.reload();else alert(d.message);});
}
function completeAppt(aid){
    if(!confirm('Mark appointment as completed?')) return;
    fetch('update_appointment.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({appointment_id:aid,status:'Completed'})})
    .then(r=>r.json()).then(d=>{if(d.success) location.reload();else alert(d.message);});
}

const autoCancelledIds = new Set();
function updateCountdowns(){
    document.querySelectorAll('.countdown[data-called]').forEach(el=>{
        const called=new Date(el.dataset.called.replace(' ','T'));
        const deadline=new Date(called.getTime()+5*60*1000);
        const remaining=Math.max(0,deadline-new Date());
        const mins=Math.floor(remaining/60000);
        const secs=Math.floor((remaining%60000)/1000);
        if(remaining>0){
            el.textContent=`${mins}:${secs.toString().padStart(2,'0')}`;
            el.className='countdown '+(remaining<60000?'urgent':'normal');
        }else{
            el.textContent='TIME UP';el.className='countdown urgent';
            const qid=parseInt(el.id.replace('cd-',''));
            if(qid&&!autoCancelledIds.has(qid)){
                autoCancelledIds.add(qid);
                fetch('update_queue_status.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({queue_id:qid,status:'Cancelled'})})
                .then(()=>setTimeout(()=>location.reload(),1500)).catch(()=>setTimeout(()=>location.reload(),1500));
            }
        }
    });
}
setInterval(updateCountdowns,1000);
updateCountdowns();

const INACTIVE_WARN_MS=25*60*1000;
const INACTIVE_OUT_MS=30*60*1000;
const IS_AVAILABLE=<?php echo $doctor['is_available']?'true':'false'; ?>;
const DOCTOR_ROOM='<?php echo addslashes($doctor["room"]??""); ?>';
let lastActivity=Date.now(),warnShown=false,clockedOut=false;
['mousemove','mousedown','keydown','touchstart','scroll','click'].forEach(ev=>
    document.addEventListener(ev,()=>{lastActivity=Date.now();hideInactiveWarning();},{passive:true}));
function sendHeartbeat(){fetch('doctor_heartbeat.php',{method:'POST'}).catch(()=>{});}
sendHeartbeat();setInterval(sendHeartbeat,60000);
function showInactiveWarning(minsLeft){
    let el=document.getElementById('inactive-warn');
    if(!el){el=document.createElement('div');el.id='inactive-warn';
    el.style.cssText='position:fixed;top:0;left:var(--sidebar-w);right:0;z-index:9999;background:#7c2d12;color:#fff;padding:12px 24px;font-size:13.5px;font-weight:600;display:flex;align-items:center;justify-content:space-between;gap:12px;box-shadow:0 4px 20px rgba(0,0,0,.3)';
    document.body.appendChild(el);}
    el.innerHTML=`<div style="display:flex;align-items:center;gap:10px"><span class="material-symbols-outlined" style="font-size:18px">warning</span><span>No activity — auto clock-out in <strong id="warn-countdown">${minsLeft}</strong></span></div><button onclick="resetInactivity()" style="padding:7px 16px;background:rgba(255,255,255,.2);border:1.5px solid rgba(255,255,255,.4);color:#fff;border-radius:7px;font-size:13px;font-weight:700;cursor:pointer;">I'm still here</button>`;
    el.style.display='flex';warnShown=true;
}
function hideInactiveWarning(){const el=document.getElementById('inactive-warn');if(el)el.style.display='none';warnShown=false;}
function resetInactivity(){lastActivity=Date.now();hideInactiveWarning();sendHeartbeat();}
function autoClockOut(){
    if(clockedOut||!IS_AVAILABLE||!DOCTOR_ROOM)return;clockedOut=true;
    let el=document.getElementById('inactive-warn');
    if(!el){el=document.createElement('div');el.id='inactive-warn';document.body.appendChild(el);}
    el.style.cssText='position:fixed;top:0;left:var(--sidebar-w);right:0;z-index:9999;background:#991b1b;color:#fff;padding:14px 24px;font-size:13.5px;font-weight:600;display:flex;align-items:center;gap:12px;box-shadow:0 4px 20px rgba(0,0,0,.3)';
    el.innerHTML='<span class="material-symbols-outlined" style="font-size:18px">circle</span><span>No activity for 30 minutes — clocking out…</span>';
    fetch('set_doctor_availability.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({room:DOCTOR_ROOM,is_available:'0'})})
    .then(()=>setTimeout(()=>location.reload(),2000)).catch(()=>setTimeout(()=>location.reload(),2000));
}
setInterval(()=>{
    if(!IS_AVAILABLE||clockedOut)return;
    const idle=Date.now()-lastActivity;
    if(idle>=INACTIVE_OUT_MS){autoClockOut();}
    else if(idle>=INACTIVE_WARN_MS){
        const secsLeft=Math.ceil((INACTIVE_OUT_MS-idle)/1000);
        const m=Math.floor(secsLeft/60),s=secsLeft%60;
        showInactiveWarning(m>0?`${m}m ${s}s`:`${s}s`);
        const el=document.getElementById('warn-countdown');
        if(el)el.textContent=m>0?`${m}m ${s}s`:`${s}s`;
    }
},1000);
setInterval(()=>{if(!warnShown&&!clockedOut)location.reload();},10000);
</script>
</body>
</html>