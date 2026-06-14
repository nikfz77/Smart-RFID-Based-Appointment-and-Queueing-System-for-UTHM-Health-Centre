<?php include 'login_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walk-In — PKU UTHM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        :root{--blue-900:#0c2461;--blue-600:#1d5bb5;--blue-500:#2563eb;--blue-50:#eff6ff;--blue-100:#dbeafe;--green-600:#16a34a;--green-100:#dcfce7;--amber-600:#d97706;--amber-100:#fef3c7;--red-600:#dc2626;--red-100:#fee2e2;--slate-900:#0f172a;--slate-700:#334155;--slate-500:#64748b;--slate-300:#cbd5e1;--slate-200:#e2e8f0;--slate-100:#f1f5f9;--slate-50:#f8fafc;--white:#fff;--sidebar-w:232px;--topbar-h:58px;--font:'Plus Jakarta Sans',sans-serif}
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
        .breadcrumb a{color:var(--slate-500);text-decoration:none}.breadcrumb .sep{color:var(--slate-300)}
        .topbar-right{margin-left:auto;display:flex;align-items:center;gap:8px}
        .topbar-date{font-size:12px;color:var(--slate-500);padding:5px 12px;background:var(--slate-50);border:1px solid var(--slate-200);border-radius:6px}
        .btn{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:8px;font-family:var(--font);font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .15s;text-decoration:none}
        .btn-primary{background:var(--blue-600);color:#fff}.btn-primary:hover{background:#1e4fa0}
        .btn-green{background:var(--green-600);color:#fff}.btn-green:hover{background:#15803d;transform:translateY(-1px)}
        .btn-outline{background:var(--slate-50);color:var(--slate-700);border:1px solid var(--slate-200)}.btn-outline:hover{background:var(--blue-50);color:var(--blue-600)}
        .btn:disabled{opacity:.5;cursor:not-allowed}
        .page{margin-left:var(--sidebar-w);padding-top:var(--topbar-h);min-height:100vh}
        .page-body{padding:22px 26px}
        .wrap{max-width:100%}
        .page-intro{margin-bottom:18px}
        .page-intro h1{font-size:19px;font-weight:800;color:var(--slate-900);letter-spacing:-0.4px}
        .page-intro p{font-size:12.5px;color:var(--slate-500);margin-top:3px}
        .alert-strip{display:flex;align-items:center;gap:10px;background:var(--amber-100);border:1px solid #fcd34d;border-left:3px solid var(--amber-600);border-radius:7px;padding:10px 14px;font-size:12.5px;color:#92400e;margin-bottom:16px}
        .alert-strip a{color:#92400e;font-weight:700}
        .card{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;overflow:hidden;margin-bottom:14px}
        .card-head{padding:13px 18px;border-bottom:1px solid var(--slate-100);font-size:13px;font-weight:700;color:var(--slate-900);display:flex;align-items:center;gap:7px}
        .card-body{padding:18px}
        .fg{margin-bottom:14px}
        .fg label{display:block;font-size:11px;font-weight:700;color:var(--slate-600);margin-bottom:6px;text-transform:uppercase;letter-spacing:.4px}
        .fg label .req{color:var(--red-600)}
        .fg input,.fg select,.fg textarea{width:100%;padding:10px 13px;border:1.5px solid var(--slate-200);border-radius:8px;font-size:13.5px;font-family:var(--font);color:var(--slate-900)}
        .fg input:focus,.fg select:focus{border-color:var(--blue-500);outline:none;box-shadow:0 0 0 3px rgba(37,99,235,.08)}
        .fg .hint{font-size:11px;color:var(--slate-400);margin-top:4px}
        .patient-found{background:var(--green-100);border:1px solid #86efac;border-radius:8px;padding:12px 14px;margin-top:10px;display:none}
        .pf-title{font-size:12px;font-weight:700;color:#14532d;margin-bottom:8px}
        .pf-row{display:flex;justify-content:space-between;font-size:13px;padding:4px 0;border-bottom:1px solid rgba(0,0,0,.06)}
        .pf-row:last-child{border-bottom:none}
        .pf-row span:first-child{font-weight:600;color:var(--slate-900)}.pf-row span:last-child{color:var(--slate-500)}
        .not-found{background:var(--amber-100);border:1px solid #fcd34d;border-radius:8px;padding:11px 14px;margin-top:10px;display:none;font-size:13px;color:#92400e}
        .not-found a{color:#92400e;font-weight:700}
        .svc-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
        .svc-card{padding:14px 12px;border:1.5px solid var(--slate-200);border-radius:9px;cursor:pointer;text-align:center;transition:all .2s;background:var(--white)}
        .svc-card:hover{border-color:var(--blue-500)}.svc-card.selected{border-color:var(--blue-500);background:var(--blue-50)}
        .svc-card input[type=radio]{display:none}
        .svc-icon{font-size:24px;margin-bottom:5px}.svc-name{font-size:12px;font-weight:600;color:var(--slate-900)}
        .priority-box{background:var(--amber-100);border:1.5px solid #fcd34d;border-radius:9px;padding:12px 14px;display:flex;align-items:center;gap:12px;cursor:pointer}
        .priority-box input[type=checkbox]{width:16px;height:16px;accent-color:var(--amber-600)}
        .priority-box label{font-size:13px;font-weight:600;color:#92400e;cursor:pointer;flex:1}
        .priority-box small{display:block;font-weight:400;color:#b45309}
        .form-actions{display:flex;gap:10px;margin-top:4px}
        .status-bar{padding:11px 14px;border-radius:8px;font-size:13px;font-weight:600;margin-top:12px;display:none}
        .status-bar.success{background:var(--green-100);color:#14532d;border:1px solid #86efac}
        .status-bar.error{background:var(--red-100);color:#7f1d1d;border:1px solid #fca5a5}
        .status-bar.loading{background:var(--amber-100);color:#92400e}
        .ticket-card{background:var(--blue-600);color:white;border-radius:12px;padding:26px;text-align:center;margin-top:14px;display:none;animation:popIn .4s ease-out}
        @keyframes popIn{from{opacity:0;transform:scale(.93)}to{opacity:1;transform:scale(1)}}
        .ticket-q{font-size:60px;font-weight:800;letter-spacing:-2px;margin:10px 0;line-height:1}
        .ticket-info{font-size:13.5px;opacity:.9;line-height:2}
        .ticket-btns{display:flex;gap:10px;justify-content:center;margin-top:18px}
        .ticket-btn{padding:9px 20px;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.4);color:white;border-radius:7px;font-weight:600;cursor:pointer;font-size:13px;font-family:var(--font)}
        .ticket-btn:hover{background:rgba(255,255,255,.25)}
        .spinner{display:inline-block;width:12px;height:12px;border:2px solid rgba(255,255,255,.3);border-radius:50%;border-top-color:#fff;animation:spin .7s linear infinite}
        @keyframes spin{to{transform:rotate(360deg)}}
    
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
        <a href="appointment_management.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">event_available</span></span> Appointments</a>
        <div class="nav-section">Records</div>
        <a href="patient_list.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">personal_injury</span></span> Patients</a>
        <a href="register_user_form.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">person_add</span></span> Register Patient</a>
        <a href="walkin_form.php" class="nav-link active"><span class="ico"><span class="material-symbols-outlined">subdirectory_arrow_right</span></span> Walk-In</a>
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
        <div class="topbar-title">Walk-In Check-In</div>
        <div class="breadcrumb"><a href="staff_dashboard.php">Dashboard</a><span class="sep">›</span><span>Walk-In</span></div>
    </div>
    <div class="topbar-right">
        <div class="topbar-date"><?php echo date('D, d M Y'); ?></div>
        <a href="register_user_form.php" class="btn btn-primary" style="padding:7px 14px;font-size:12.5px;">+ Register New Patient</a>
    </div>
</header>

<div class="page"><div class="page-body">
    <div class="wrap">
        <div class="page-intro"><h1>Walk-In Check-In</h1><p>Generate a queue number for a walk-in patient</p></div>
        <div class="alert-strip"><span class="material-symbols-outlined">warning</span> New patient? <a href="register_user_form.php" style="margin-left:4px">Register them first &rarr;</a></div>

        <div style="display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start">
        <form id="walkin-form">
            <div class="card">
                <div class="card-head"><span class="material-symbols-outlined">search</span> Patient Lookup</div>
                <div class="card-body">
                    <div class="fg">
                        <label for="matrix_number">Matrix / Staff Number <span class="req">*</span></label>
                        <input type="text" id="matrix_number" name="matrix_number" placeholder="e.g. AI210234" required autocomplete="off">
                        <div class="hint">Type matrix number to look up registered patient</div>
                    </div>
                    <div class="patient-found" id="patient-found">
                        <div class="pf-title"><span class="material-symbols-outlined">check</span> Patient found</div>
                        <div class="pf-row"><span>Name</span><span id="pf-name">—</span></div>
                        <div class="pf-row"><span>Faculty</span><span id="pf-faculty">—</span></div>
                        <div class="pf-row"><span>Program</span><span id="pf-program">—</span></div>
                    </div>
                    <div class="not-found" id="not-found"><span class="material-symbols-outlined">warning</span> Patient not registered. <a href="register_user_form.php">Register them first &rarr;</a></div>
                </div>
            </div>

            <div class="card">
                <div class="card-head"><span class="material-symbols-outlined">local_hospital</span> Service Type <span style="color:var(--red-600);font-size:12px">*</span></div>
                <div class="card-body">
                    <div class="svc-grid">
                        <label class="svc-card" onclick="selSvc(this)"><input type="radio" name="service" value="General Consultation" required><div class="svc-icon"><span class="material-symbols-outlined">stethoscope</span></div><div class="svc-name">General Consultation</div></label>
                        <label class="svc-card" onclick="selSvc(this)"><input type="radio" name="service" value="Follow-up Check"><div class="svc-icon"><span class="material-symbols-outlined">refresh</span></div><div class="svc-name">Follow-up Check</div></label>
                        <label class="svc-card" onclick="selSvc(this)"><input type="radio" name="service" value="Vaccination"><div class="svc-icon"><span class="material-symbols-outlined">vaccines</span></div><div class="svc-name">Vaccination</div></label>
                        <label class="svc-card" onclick="selSvc(this)"><input type="radio" name="service" value="Prescription Refill"><div class="svc-icon"><span class="material-symbols-outlined">pill</span></div><div class="svc-name">Prescription Refill</div></label>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body" style="padding:14px">
                    <div class="priority-box">
                        <input type="checkbox" id="is_priority" name="is_priority" value="1">
                        <label for="is_priority"><strong><span class="material-symbols-outlined">warning</span> Priority / Urgent Case</strong><small>Check for emergency or urgent medical attention</small></label>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-head"><span class="material-symbols-outlined">schedule</span> Scheduled Time <span style="font-size:12px;font-weight:400;color:var(--slate-400)">(optional)</span></div>
                <div class="card-body">
                    <div class="fg" style="margin-bottom:0">
                        <label for="scheduled_time">Appointment Time</label>
                        <input type="time" id="scheduled_time" name="scheduled_time" style="max-width:200px">
                        <div class="hint">If set, patient will receive an email with queue details</div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-green" id="submit-btn" style="flex:1;padding:12px;font-size:14px;justify-content:center"><span id="btn-text"><span class="material-symbols-outlined">check</span> Check-In &amp; Generate Queue</span></button>
                <button type="button" class="btn btn-outline" style="padding:12px 22px" onclick="resetForm()">Reset</button>
            </div>
            <div class="status-bar" id="status-bar"></div>
        </form>

        <!-- RIGHT PANEL -->
        <div>
            <div class="ticket-card" id="ticket-card">
                <div style="font-size:14px;font-weight:600;opacity:.85">Queue Number Generated</div>
                <div class="ticket-q" id="ticket-q">Q—</div>
                <div class="ticket-info">
                    <div id="ticket-name">—</div>
                    <div id="ticket-svc">—</div>
                    <div id="ticket-time">—</div>
                </div>
                <div class="ticket-btns">
                    <button class="ticket-btn" onclick="window.print()"><span class="material-symbols-outlined">print</span> Print</button>
                    <button class="ticket-btn" onclick="nextPatient()"><span class="material-symbols-outlined">add</span> Next Patient</button>
                </div>
            </div>
            <div style="background:var(--white);border:1px solid var(--slate-200);border-radius:10px;padding:18px;margin-top:0" id="tips-panel">
                <div style="font-size:13px;font-weight:700;color:var(--slate-900);margin-bottom:14px;display:flex;align-items:center;gap:7px"><span class="material-symbols-outlined">lightbulb</span> Quick Guide</div>
                <div style="display:flex;flex-direction:column;gap:12px">
                    <div style="display:flex;gap:10px;align-items:flex-start">
                        <div style="width:28px;height:28px;border-radius:7px;background:var(--blue-100);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;font-weight:800;color:var(--blue-600)">1</div>
                        <div><div style="font-size:12.5px;font-weight:600;color:var(--slate-900)">Look up patient</div><div style="font-size:11.5px;color:var(--slate-500);margin-top:2px">Enter their matrix or staff number to verify registration</div></div>
                    </div>
                    <div style="display:flex;gap:10px;align-items:flex-start">
                        <div style="width:28px;height:28px;border-radius:7px;background:var(--blue-100);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;font-weight:800;color:var(--blue-600)">2</div>
                        <div><div style="font-size:12.5px;font-weight:600;color:var(--slate-900)">Select service</div><div style="font-size:11.5px;color:var(--slate-500);margin-top:2px">Choose the type of consultation needed today</div></div>
                    </div>
                    <div style="display:flex;gap:10px;align-items:flex-start">
                        <div style="width:28px;height:28px;border-radius:7px;background:var(--amber-100);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;font-weight:800;color:var(--amber-600)"><span class="material-symbols-outlined">warning</span></div>
                        <div><div style="font-size:12.5px;font-weight:600;color:var(--slate-900)">Priority cases</div><div style="font-size:11.5px;color:var(--slate-500);margin-top:2px">Tick priority for emergency or urgent cases — they'll be served first</div></div>
                    </div>
                    <div style="display:flex;gap:10px;align-items:flex-start">
                        <div style="width:28px;height:28px;border-radius:7px;background:var(--green-100);display:flex;align-items:center;justify-content:center;font-size:13px;flex-shrink:0;color:var(--green-600)"><span class="material-symbols-outlined">check</span></div>
                        <div><div style="font-size:12.5px;font-weight:600;color:var(--slate-900)">Queue ticket</div><div style="font-size:11.5px;color:var(--slate-500);margin-top:2px">A queue number is generated — print or show to patient</div></div>
                    </div>
                </div>
                <div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--slate-100)">
                    <a href="register_user_form.php" style="display:flex;align-items:center;gap:8px;padding:10px 12px;background:var(--blue-50);border:1px solid var(--blue-100);border-radius:8px;text-decoration:none;color:var(--blue-600);font-size:12.5px;font-weight:600;transition:background .15s">
                        <span class="material-symbols-outlined">add</span> New patient? Register first
                    </a>
                </div>
            </div>
        </div>
        </div>
    </div>
</div></div>

<script>
document.getElementById('matrix_number').addEventListener('input',function(){
    this.value=this.value.toUpperCase();
    document.getElementById('patient-found').style.display='none';
    document.getElementById('not-found').style.display='none';
});
document.getElementById('matrix_number').addEventListener('blur',function(){
    const mx=this.value.trim();
    if(mx.length>=6){
        fetch(`check_patient.php?matrix_number=${mx}`).then(r=>r.json()).then(d=>{
            if(d.success&&d.student){
                document.getElementById('pf-name').textContent=d.student.full_name;
                document.getElementById('pf-faculty').textContent=d.student.faculty||'—';
                document.getElementById('pf-program').textContent=d.student.program||'—';
                document.getElementById('patient-found').style.display='block';
                document.getElementById('not-found').style.display='none';
            }else{document.getElementById('patient-found').style.display='none';document.getElementById('not-found').style.display='block';}
        }).catch(()=>{});
    }
});
function selSvc(el){document.querySelectorAll('.svc-card').forEach(c=>c.classList.remove('selected'));el.classList.add('selected');el.querySelector('input').checked=true;}
function showStatus(msg,type){const el=document.getElementById('status-bar');el.textContent=msg;el.className='status-bar '+type;el.style.display='block';}
document.getElementById('walkin-form').addEventListener('submit',function(e){
    e.preventDefault();
    if(!document.querySelector('input[name="service"]:checked')){showStatus('Please select a service type.','error');return;}
    const btn=document.getElementById('submit-btn'),text=document.getElementById('btn-text');
    btn.disabled=true;text.innerHTML='<span class="spinner"></span> Processing…';
    showStatus('Generating queue number…','loading');
    fetch('process_walkin.php',{method:'POST',body:new FormData(this)}).then(r=>r.json()).then(d=>{
        if(d.success){
            document.getElementById('ticket-q').textContent=d.queue_number;
            document.getElementById('ticket-name').textContent=d.student_name||d.matrix_number;
            document.getElementById('ticket-svc').textContent='Service: '+d.service;
            document.getElementById('ticket-time').textContent=d.scheduled_time?'Scheduled: '+d.scheduled_time:'Time will be set by staff';
            document.getElementById('ticket-card').style.display='block';
            document.getElementById('tips-panel').style.display='none';
            document.getElementById('status-bar').style.display='none';
        }else showStatus('<span class="material-symbols-outlined">warning</span> '+d.message,'error');
    }).catch(()=>showStatus('Network error.','error')).finally(()=>{btn.disabled=false;text.innerHTML='<span class="material-symbols-outlined">check</span> Check-In & Generate Queue';});
});
function resetForm(){document.getElementById('walkin-form').reset();document.querySelectorAll('.svc-card').forEach(c=>c.classList.remove('selected'));document.getElementById('patient-found').style.display='none';document.getElementById('not-found').style.display='none';document.getElementById('status-bar').style.display='none';}
function nextPatient(){document.getElementById('ticket-card').style.display='none';document.getElementById('tips-panel').style.display='block';resetForm();window.scrollTo({top:0,behavior:'smooth'});}
</script>
</body></html>