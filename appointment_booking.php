<?php include 'login_check.php'; ?>
<!DOCTYPE html><html lang="en"><head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment — PKU UTHM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        :root{--blue-900:#0c2461;--blue-600:#1d5bb5;--blue-500:#2563eb;--blue-50:#eff6ff;--blue-100:#dbeafe;--green-600:#16a34a;--green-100:#dcfce7;--amber-600:#d97706;--amber-100:#fef3c7;--red-600:#dc2626;--red-100:#fee2e2;--slate-900:#0f172a;--slate-600:#475569;--slate-500:#64748b;--slate-300:#cbd5e1;--slate-200:#e2e8f0;--slate-100:#f1f5f9;--slate-50:#f8fafc;--white:#fff;--sidebar-w:232px;--topbar-h:58px;--font:'Plus Jakarta Sans',sans-serif}
        html,body{height:100%;font-family:var(--font);background:var(--slate-100);color:var(--slate-900);font-size:14px}
        .sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--sidebar-w);background:var(--blue-900);display:flex;flex-direction:column;z-index:50}
        .sidebar-top{height:var(--topbar-h);display:flex;align-items:center;gap:10px;padding:0 18px;border-bottom:1px solid rgba(255,255,255,.07);flex-shrink:0}
        .logo-mark{width:32px;height:32px;background:var(--blue-500);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0}
        .logo-text strong{display:block;font-size:13px;font-weight:700;color:#fff}.logo-text span{font-size:10.5px;color:rgba(255,255,255,.45)}
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
        .btn{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:8px;font-family:var(--font);font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .15s;text-decoration:none}
        .btn-primary{background:var(--blue-600);color:#fff}.btn-primary:hover{background:#1e4fa0;transform:translateY(-1px)}
        .btn-outline{background:var(--slate-50);color:var(--slate-700);border:1px solid var(--slate-200)}.btn-outline:hover{background:var(--blue-50);color:var(--blue-600)}
        .btn:disabled{opacity:.5;cursor:not-allowed}
        .page{margin-left:var(--sidebar-w);padding-top:var(--topbar-h);min-height:100vh}
        .page-body{padding:22px 26px}
        .wrap{width:100%}
        .page-intro{margin-bottom:18px}
        .page-intro h1{font-size:19px;font-weight:800;color:var(--slate-900);letter-spacing:-.4px}
        .page-intro p{font-size:12.5px;color:var(--slate-500);margin-top:3px}
        .alert-strip{display:flex;align-items:center;gap:10px;background:var(--blue-50);border:1px solid var(--blue-100);border-left:3px solid var(--blue-500);border-radius:7px;padding:10px 14px;font-size:12.5px;color:var(--blue-700);margin-bottom:16px}
        .card{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;overflow:hidden;margin-bottom:14px}
        .card-head{padding:13px 18px;border-bottom:1px solid var(--slate-100);font-size:13px;font-weight:700;color:var(--slate-900);display:flex;align-items:center;gap:7px}
        .card-body{padding:18px}
        .form-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px}
        .fg{margin-bottom:0}.fg.full{grid-column:1/-1}
        .fg label{display:block;font-size:11px;font-weight:700;color:var(--slate-600);margin-bottom:6px;text-transform:uppercase;letter-spacing:.4px}
        .fg label span{color:var(--red-600)}
        .fg input,.fg select,.fg textarea{width:100%;padding:10px 13px;border:1.5px solid var(--slate-200);border-radius:8px;font-size:13.5px;font-family:var(--font);color:var(--slate-900);background:var(--white);transition:border-color .2s}
        .fg input:focus,.fg select:focus,.fg textarea:focus{border-color:var(--blue-500);outline:none;box-shadow:0 0 0 3px rgba(37,99,235,.08)}
        .fg .hint{font-size:11px;color:var(--slate-400);margin-top:4px}
        .fg textarea{resize:vertical;min-height:80px}
        .svc-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}
        .svc-card{padding:14px 10px;border:1.5px solid var(--slate-200);border-radius:9px;cursor:pointer;text-align:center;transition:all .2s;background:var(--white);position:relative}
        .svc-card:hover{border-color:var(--blue-500)}.svc-card.selected{border-color:var(--blue-500);background:var(--blue-50)}
        .svc-card input[type=radio]{position:absolute;opacity:0;width:0;height:0}
        .svc-icon{font-size:22px;margin-bottom:5px}.svc-name{font-size:11.5px;font-weight:600;color:var(--slate-900)}
        .form-actions{display:flex;gap:10px}
        .status-bar{padding:11px 14px;border-radius:8px;font-size:13px;font-weight:600;margin-top:12px;display:none}
        .status-bar.success{background:var(--green-100);color:#14532d;border:1px solid #86efac}
        .status-bar.error{background:var(--red-100);color:#7f1d1d;border:1px solid #fca5a5}
        .status-bar.loading{background:var(--amber-100);color:#92400e}
        .spinner{display:inline-block;width:12px;height:12px;border:2px solid rgba(255,255,255,.3);border-radius:50%;border-top-color:#fff;animation:spin .7s linear infinite}
        @keyframes spin{to{transform:rotate(360deg)}}
        @media(max-width:600px){.form-grid{grid-template-columns:1fr}.svc-grid{grid-template-columns:1fr 1fr}.form-actions{flex-direction:column}}
    </style>
</head><body>
<aside class="sidebar">
    <div class="sidebar-top"><div class="logo-mark">🏥</div><div class="logo-text"><strong>PKU UTHM</strong><span>Queue Management System</span></div></div>
    <nav class="sidebar-nav">
        <div class="nav-section">Main</div>
        <a href="staff_dashboard.php" class="nav-link"><span class="ico">⊞</span> Dashboard</a>
        <a href="queue_management.php" class="nav-link"><span class="ico">⋮⋮</span> Queue Management</a>
        <a href="appointment_management.php" class="nav-link active"><span class="ico">◫</span> Appointments</a>
        <div class="nav-section">Records</div>
        <a href="student_list.php" class="nav-link"><span class="ico">⊞</span> Students</a>
        <a href="register_user_form.php" class="nav-link"><span class="ico">＋</span> Register Patient</a>
        <a href="walkin_form.php" class="nav-link"><span class="ico">↳</span> Walk-In</a>
        <a href="reports.php" class="nav-link"><span class="ico">▤</span> Reports</a>
        <div class="nav-section">Display</div>
        <a href="queue_display.html" target="_blank" class="nav-link"><span class="ico">▣</span> Public Display</a>
    </nav>
    <div class="sidebar-footer"><div class="user-card">
        <div class="user-ava"><?php echo strtoupper(substr($_SESSION['full_name']??'S',0,1)); ?></div>
        <div class="user-info"><strong><?php echo htmlspecialchars($_SESSION['full_name']??'Staff'); ?></strong><span><?php echo ucfirst($_SESSION['role']??'Staff'); ?></span></div>
        <a href="logout.php" style="font-size:14px;color:rgba(255,255,255,.3);text-decoration:none;margin-left:4px;">⏻</a>
    </div></div>
</aside>
<header class="topbar">
    <div><div class="topbar-title">Book Appointment</div><div class="breadcrumb"><a href="staff_dashboard.php">Dashboard</a><span class="sep">›</span><a href="appointment_management.php">Appointments</a><span class="sep">›</span><span>Book New</span></div></div>
    <div class="topbar-right"><div class="topbar-date"><?php echo date('D, d M Y'); ?></div></div>
</header>
<div class="page"><div class="page-body"><div class="wrap">
    <div class="page-intro"><h1>Book New Appointment</h1><p>Schedule an appointment for a student at PKU UTHM</p></div>
    <div class="alert-strip">🕐 <strong>Operating Hours:</strong> Mon–Fri 8:00 AM – 5:00 PM &nbsp;|&nbsp; Sat 8:00 AM – 12:00 PM</div>

    <form id="booking-form" novalidate>
        <div class="card">
            <div class="card-head">👤 Student Information</div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="fg"><label for="matrix_number">Matrix Number <span>*</span></label><input type="text" id="matrix_number" name="matrix_number" placeholder="e.g. AI210234" required><div class="hint">Format: 2 letters + 6 digits</div></div>
                    <div class="fg"><label for="student_name">Student Name <span>*</span></label><input type="text" id="student_name" name="student_name" placeholder="Enter full name" required minlength="3"></div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-head">📅 Schedule</div>
            <div class="card-body">
                <div class="fg" style="margin-bottom:0"><label for="schedule_time">Date & Time <span>*</span></label><input type="datetime-local" id="schedule_time" name="schedule_time" required></div>
            </div>
        </div>

        <div class="card">
            <div class="card-head">🏥 Service Type <span style="color:var(--red-600);font-size:12px">*</span></div>
            <div class="card-body">
                <div class="svc-grid">
                    <label class="svc-card" onclick="selSvc(this)"><input type="radio" name="service" value="General Consultation" required><div class="svc-icon">🩺</div><div class="svc-name">General Consultation</div></label>
                    <label class="svc-card" onclick="selSvc(this)"><input type="radio" name="service" value="Follow-up Check"><div class="svc-icon">🔄</div><div class="svc-name">Follow-up Check</div></label>
                    <label class="svc-card" onclick="selSvc(this)"><input type="radio" name="service" value="Vaccination"><div class="svc-icon">💉</div><div class="svc-name">Vaccination</div></label>
                    <label class="svc-card" onclick="selSvc(this)"><input type="radio" name="service" value="Prescription Refill"><div class="svc-icon">💊</div><div class="svc-name">Prescription Refill</div></label>
                </div>
                <div id="svc-err" style="font-size:11px;color:var(--red-600);margin-top:8px;display:none">Please select a service type</div>
            </div>
        </div>

        <div class="card">
            <div class="card-head">📝 Additional Notes <span style="font-size:12px;font-weight:400;color:var(--slate-400)">(optional)</span></div>
            <div class="card-body">
                <div class="fg" style="margin-bottom:0"><textarea id="notes" name="notes" placeholder="Any special requirements…" maxlength="500"></textarea><div class="hint" id="char-count">0 / 500 characters</div></div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary" id="submit-btn" style="flex:1;padding:12px;font-size:14px;justify-content:center"><span id="btn-text">📅 Book Appointment</span></button>
            <button type="button" class="btn btn-outline" style="padding:12px 22px" onclick="resetForm()">Reset</button>
        </div>
        <div class="status-bar" id="status-bar"></div>
    </form>
</div></div></div>

<script>
const now=new Date(),pad=n=>String(n).padStart(2,'0');
document.getElementById('schedule_time').min=`${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())}T${pad(now.getHours())}:${pad(now.getMinutes())}`;
document.getElementById('matrix_number').addEventListener('input',function(){this.value=this.value.toUpperCase();});
function selSvc(el){document.querySelectorAll('.svc-card').forEach(c=>c.classList.remove('selected'));el.classList.add('selected');el.querySelector('input').checked=true;document.getElementById('svc-err').style.display='none';}
document.getElementById('notes').addEventListener('input',function(){document.getElementById('char-count').textContent=`${this.value.length} / 500 characters`;});
function showStatus(msg,type){const el=document.getElementById('status-bar');el.textContent=msg;el.className='status-bar '+type;el.style.display='block';if(type==='success')setTimeout(()=>el.style.display='none',5000);}
document.getElementById('booking-form').addEventListener('submit',function(e){
    e.preventDefault();
    if(!document.querySelector('input[name="service"]:checked')){document.getElementById('svc-err').style.display='block';showStatus('Please select a service type.','error');return;}
    const btn=document.getElementById('submit-btn'),text=document.getElementById('btn-text');
    btn.disabled=true;text.innerHTML='<span class="spinner"></span> Booking…';
    showStatus('Processing your booking…','loading');
    fetch('book_appointment.php',{method:'POST',body:new FormData(this)}).then(r=>r.json()).then(data=>{
        if(data.success){showStatus('✓ '+data.message,'success');this.reset();document.querySelectorAll('.svc-card').forEach(c=>c.classList.remove('selected'));document.getElementById('char-count').textContent='0 / 500 characters';}
        else showStatus('⚠ '+data.message,'error');
    }).catch(()=>showStatus('Network error. Please try again.','error')).finally(()=>{btn.disabled=false;text.innerHTML='📅 Book Appointment';});
});
function resetForm(){document.getElementById('booking-form').reset();document.querySelectorAll('.svc-card').forEach(c=>c.classList.remove('selected'));document.getElementById('status-bar').style.display='none';document.getElementById('svc-err').style.display='none';document.getElementById('char-count').textContent='0 / 500 characters';}
</script>
</body></html>