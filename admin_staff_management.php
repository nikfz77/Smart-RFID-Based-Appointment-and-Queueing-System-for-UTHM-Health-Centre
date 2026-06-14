<?php
include 'login_check.php';
$host='localhost';$dbname='queue_and_appointment_management';$db_user='root';$db_pass='';
try{
    $pdo=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",$db_user,$db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $staff_list=$pdo->query("SELECT id,username,full_name,role,status,created_at FROM users ORDER BY role ASC,created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){$staff_list=[];}
$me=intval($_SESSION['user_id']);
?>
<!DOCTYPE html><html lang="en"><head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management — PKU UTHM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        :root{--green-900:#14532d;--green-700:#15803d;--green-600:#16a34a;--green-100:#dcfce7;--green-50:#f0fdf4;--amber-600:#d97706;--amber-100:#fef3c7;--red-600:#dc2626;--red-100:#fee2e2;--blue-600:#1d5bb5;--blue-50:#eff6ff;--purple-600:#7c3aed;--purple-100:#ede9fe;--slate-900:#0f172a;--slate-700:#334155;--slate-600:#475569;--slate-500:#64748b;--slate-300:#cbd5e1;--slate-200:#e2e8f0;--slate-100:#f1f5f9;--slate-50:#f8fafc;--white:#fff;--sidebar-w:232px;--topbar-h:58px;--font:'Plus Jakarta Sans',sans-serif}
        html,body{height:100%;font-family:var(--font);background:var(--slate-100);color:var(--slate-900);font-size:14px}
        .sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--sidebar-w);background:var(--green-900);display:flex;flex-direction:column;z-index:50}
        .sidebar-top{height:var(--topbar-h);display:flex;align-items:center;gap:10px;padding:0 18px;border-bottom:1px solid rgba(255,255,255,.08);flex-shrink:0}
        .logo-mark{width:32px;height:32px;background:var(--green-600);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .logo-text strong{display:block;font-size:13px;font-weight:700;color:#fff}.logo-text span{font-size:10.5px;color:rgba(255,255,255,.45)}
        .role-pill{display:inline-block;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);color:#fff;font-size:9px;font-weight:800;letter-spacing:1px;text-transform:uppercase;padding:2px 7px;border-radius:10px;margin-top:3px}
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
        .alert-strip{display:flex;align-items:center;gap:10px;background:var(--amber-100);border:1px solid #fcd34d;border-left:3px solid var(--amber-600);border-radius:7px;padding:10px 14px;font-size:12.5px;color:#92400e;margin-bottom:16px}
        .card{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;overflow:hidden;margin-bottom:16px}
        .card-head{padding:13px 18px;border-bottom:1px solid var(--slate-100);display:flex;align-items:center;justify-content:space-between}
        .card-head h2{font-size:13px;font-weight:700;color:var(--slate-900)}.card-head-meta{font-size:11.5px;color:var(--slate-400)}
        .card-body{padding:18px}
        .form-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;align-items:flex-end}
        .fg{margin-bottom:0}
        .fg label{display:block;font-size:11px;font-weight:700;color:var(--slate-600);margin-bottom:6px;text-transform:uppercase;letter-spacing:.4px}
        .fg input,.fg select{width:100%;padding:10px 13px;border:1.5px solid var(--slate-200);border-radius:8px;font-size:13.5px;font-family:var(--font);color:var(--slate-900);background:var(--white);transition:border-color .2s}
        .fg input:focus,.fg select:focus{border-color:var(--green-600);outline:none;box-shadow:0 0 0 3px rgba(22,163,74,.08)}
        .btn{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:8px;font-family:var(--font);font-size:13px;font-weight:700;cursor:pointer;border:none;transition:all .15s}
        .btn-primary{background:var(--green-600);color:#fff}.btn-primary:hover{background:var(--green-700);transform:translateY(-1px)}
        .btn-primary:disabled{opacity:.5;cursor:not-allowed;transform:none}
        .status-bar{padding:10px 14px;border-radius:7px;font-size:13px;font-weight:600;margin-top:14px;display:none}
        .status-bar.success{background:var(--green-100);color:#14532d;border:1px solid #86efac}
        .status-bar.error{background:var(--red-100);color:#7f1d1d;border:1px solid #fca5a5}
        .data-table{width:100%;border-collapse:collapse}
        .data-table thead tr{background:var(--green-50)}
        .data-table th{padding:9px 14px;text-align:left;font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:var(--green-700);border-bottom:1px solid var(--green-100)}
        .data-table td{padding:11px 14px;font-size:13px;color:var(--slate-700);border-bottom:1px solid var(--slate-50);vertical-align:middle}
        .data-table tbody tr:last-child td{border-bottom:none}.data-table tbody tr:hover{background:var(--green-50)}
        .role-badge{padding:3px 8px;border-radius:5px;font-size:11px;font-weight:700;display:inline-block}
        .role-Admin{background:var(--green-100);color:#14532d}.role-Staff{background:var(--blue-50);color:var(--blue-600)}.role-Doctor{background:var(--purple-100);color:var(--purple-600)}
        .status-badge{padding:3px 8px;border-radius:5px;font-size:11px;font-weight:700}
        .status-Active{background:var(--green-100);color:#14532d}.status-Inactive{background:var(--red-100);color:#7f1d1d}
        .me-tag{font-size:10px;font-weight:800;background:var(--green-600);color:#fff;padding:2px 7px;border-radius:4px;margin-left:6px}
        .act-btns{display:flex;gap:5px;flex-wrap:wrap}
        .ab{padding:5px 11px;font-size:12px;font-weight:700;border:none;border-radius:6px;cursor:pointer;font-family:var(--font);transition:all .15s}
        .ab:hover{filter:brightness(.92)}.ab:disabled{opacity:.4;cursor:not-allowed}
        .ab-activate{background:var(--green-100);color:#14532d}.ab-deactivate{background:var(--amber-100);color:#92400e}.ab-delete{background:var(--red-100);color:#7f1d1d}
        .spinner{display:inline-block;width:12px;height:12px;border:2px solid rgba(255,255,255,.3);border-radius:50%;border-top-color:#fff;animation:spin .7s linear infinite;margin-right:5px}
        @keyframes spin{to{transform:rotate(360deg)}}
    </style>
</head><body>
<aside class="sidebar">
    <div class="sidebar-top">
        <div class="logo-mark"><i class="fa-solid fa-hospital" style="color:#fff;font-size:15px"></i></div>
        <div class="logo-text"><strong>PKU UTHM</strong><span>Queue Management System</span><div class="role-pill">Admin</div></div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Admin Panel</div>
        <a href="admin_dashboard.php" class="nav-link"><span class="ico"><i class="fa-solid fa-table-columns"></i></span> Dashboard</a>
        <a href="admin_staff_management.php" class="nav-link active"><span class="ico"><i class="fa-solid fa-users"></i></span> Staff Management</a>
        <a href="admin_logs.php" class="nav-link"><span class="ico"><i class="fa-solid fa-clipboard-list"></i></span> System Logs</a>
        <a href="admin_reports.php" class="nav-link"><span class="ico"><i class="fa-solid fa-chart-bar"></i></span> Reports</a>
        <div class="nav-section">View</div>
        <a href="queue_display.html" target="_blank" class="nav-link"><span class="ico"><i class="fa-solid fa-display"></i></span> Public Display</a>
        <a href="admin_student_list.php" class="nav-link"><span class="ico"><i class="fa-solid fa-graduation-cap"></i></span> Students</a>
    </nav>
    <div class="sidebar-footer"><div class="user-card">
        <div class="user-ava"><?php echo strtoupper(substr($_SESSION['full_name']??'A',0,1)); ?></div>
        <div class="user-info"><strong><?php echo htmlspecialchars($_SESSION['full_name']??'Admin'); ?></strong><span>Administrator</span></div>
        <a href="logout.php" style="font-size:14px;color:rgba(255,255,255,.3);text-decoration:none;margin-left:auto;"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
    </div></div>
</aside>
<header class="topbar">
    <div><div class="topbar-title">Staff Management</div><div class="breadcrumb"><a href="admin_dashboard.php">Dashboard</a><span class="sep">›</span><span>Staff Management</span></div></div>
    <div class="topbar-right"><div class="topbar-date"><?php echo date('D, d M Y'); ?></div></div>
</header>
<div class="page"><div class="page-body">
    <div class="page-intro"><h1>Staff Management</h1><p>Add, activate or remove staff accounts</p></div>
    <div class="alert-strip"><i class="fa-solid fa-triangle-exclamation"></i> You cannot delete or deactivate your own account. To transfer admin access, add a new admin account.</div>

    <div class="card">
        <div class="card-head"><h2>Add New Staff Account</h2></div>
        <div class="card-body">
            <div class="form-grid">
                <div class="fg"><label>Full Name</label><input type="text" id="full_name" placeholder="e.g. Dr. Ahmad"></div>
                <div class="fg"><label>Username</label><input type="text" id="new_username" placeholder="e.g. doctor2"></div>
                <div class="fg"><label>Password</label><input type="password" id="new_password" placeholder="Min 6 characters"></div>
                <div class="fg"><label>Email</label><input type="email" id="new_email" placeholder="e.g. staff@uthm.edu.my"></div>
                <div class="fg"><label>Role</label><select id="new_role"><option value="Staff">Staff</option><option value="Doctor">Doctor</option><option value="Admin">Admin</option></select></div>
                <div class="fg" style="display:flex;align-items:flex-end">
                    <button class="btn btn-primary" id="add-btn" onclick="addStaff()" style="width:100%;justify-content:center"><span id="add-text"><i class="fa-solid fa-plus"></i> Add Staff</span></button>
                </div>
            </div>
            <div class="status-bar" id="status-bar"></div>
        </div>
    </div>

    <div class="card">
        <div class="card-head"><h2>All Staff Accounts</h2><span class="card-head-meta"><?php echo count($staff_list); ?> accounts</span></div>
        <div style="overflow-x:auto"><table class="data-table">
            <thead><tr><th>#</th><th>Full Name</th><th>Username</th><th>Role</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach($staff_list as $i=>$s): $isSelf=intval($s['id'])===$me; ?>
            <tr id="row-<?php echo $s['id']; ?>">
                <td style="color:var(--slate-400);font-size:12px"><?php echo $i+1; ?></td>
                <td>
                    <span style="font-weight:600;color:var(--slate-900)"><?php echo htmlspecialchars($s['full_name']); ?></span>
                    <?php if($isSelf): ?><span class="me-tag">YOU</span><?php endif; ?>
                </td>
                <td style="font-family:monospace;font-size:12.5px"><?php echo htmlspecialchars($s['username']); ?></td>
                <td><span class="role-badge role-<?php echo $s['role']; ?>"><?php echo $s['role']; ?></span></td>
                <td><span class="status-badge status-<?php echo $s['status']; ?>"><?php echo $s['status']; ?></span></td>
                <td style="color:var(--slate-400);font-size:12px"><?php echo date('d M Y',strtotime($s['created_at'])); ?></td>
                <td>
                    <?php if($isSelf): ?>
                    <span style="font-size:12px;color:var(--slate-400);font-style:italic">Your account</span>
                    <?php else: ?>
                    <div class="act-btns">
                        <?php if($s['status']==='Active'): ?>
                        <button class="ab ab-deactivate" onclick="toggleStatus(<?php echo $s['id']; ?>,'Inactive')">Deactivate</button>
                        <?php else: ?>
                        <button class="ab ab-activate" onclick="toggleStatus(<?php echo $s['id']; ?>,'Active')">Activate</button>
                        <?php endif; ?>
                        <button class="ab ab-delete" onclick="deleteStaff(<?php echo $s['id']; ?>,'<?php echo htmlspecialchars($s['username'],ENT_QUOTES); ?>')">Delete</button>
                    </div>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table></div>
    </div>
</div></div>

<script>
function showStatus(msg,type){const el=document.getElementById('status-bar');el.textContent=msg;el.className='status-bar '+type;el.style.display='block';if(type==='success')setTimeout(()=>el.style.display='none',4000);}
function addStaff(){
    const full_name=document.getElementById('full_name').value.trim(),username=document.getElementById('new_username').value.trim(),password=document.getElementById('new_password').value,email=document.getElementById('new_email').value.trim(),role=document.getElementById('new_role').value;
    if(!full_name||!username||!password||!email){showStatus('Please fill in all fields.','error');return;}
    if(password.length<6){showStatus('Password must be at least 6 characters.','error');return;}
    const btn=document.getElementById('add-btn'),text=document.getElementById('add-text');
    btn.disabled=true;text.innerHTML='<span class="spinner"></span>Adding…';
    fetch('manage_staff.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'add',full_name,username,password,email,role})})
    .then(r=>r.json()).then(d=>{if(d.success){showStatus(d.message,'success');['full_name','new_username','new_password','new_email'].forEach(id=>document.getElementById(id).value='');setTimeout(()=>location.reload(),1500);}else showStatus(d.message,'error');})
    .catch(()=>showStatus('Network error.','error')).finally(()=>{btn.disabled=false;text.innerHTML='<i class="fa-solid fa-plus"></i> Add Staff';});
}
function toggleStatus(id,status){
    if(!confirm(`${status==='Active'?'Activate':'Deactivate'} this account?`)) return;
    fetch('manage_staff.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'toggle_status',id,status})})
    .then(r=>r.json()).then(d=>{if(d.success)location.reload();else alert(d.message);}).catch(()=>alert('Network error.'));
}
function deleteStaff(id,username){
    if(!confirm(`Delete "${username}"? This cannot be undone!`)) return;
    fetch('manage_staff.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'delete',id})})
    .then(r=>r.json()).then(d=>{if(d.success){document.getElementById('row-'+id).remove();showStatus('Staff deleted.','success');}else alert(d.message);}).catch(()=>alert('Network error.'));
}
</script>
</body></html>