<?php include 'login_check.php'; ?>
<!DOCTYPE html><html lang="en"><head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Patient — PKU UTHM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        :root{--blue-900:#0c2461;--blue-600:#1d5bb5;--blue-500:#2563eb;--blue-50:#eff6ff;--blue-100:#dbeafe;--green-600:#16a34a;--green-100:#dcfce7;--amber-600:#d97706;--amber-100:#fef3c7;--red-600:#dc2626;--red-100:#fee2e2;--slate-900:#0f172a;--slate-600:#475569;--slate-500:#64748b;--slate-300:#cbd5e1;--slate-200:#e2e8f0;--slate-100:#f1f5f9;--slate-50:#f8fafc;--white:#fff;--sidebar-w:232px;--topbar-h:58px;--font:'Plus Jakarta Sans',sans-serif}
        html,body{height:100%;font-family:var(--font);background:var(--slate-100);color:var(--slate-900);font-size:14px}
        .sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--sidebar-w);background:var(--blue-900);display:flex;flex-direction:column;z-index:50}
        .sidebar-top{height:var(--topbar-h);display:flex;align-items:center;gap:10px;padding:0 18px;border-bottom:1px solid rgba(255,255,255,.07);flex-shrink:0}
        .logo-mark{width:32px;height:32px;background:var(--blue-500);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0}
        .logo-text strong{display:block;font-size:13px;font-weight:700;color:#fff; font-family:'Sora',sans-serif}.logo-text span{font-size:10.5px;color:rgba(255,255,255,.45)}
        .sidebar-nav{flex:1;overflow-y:auto;padding:10px 10px 0}.sidebar-nav::-webkit-scrollbar{width:0}
        .nav-section{font-size:9.5px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:rgba(255,255,255,.45);padding:16px 8px 5px}
        .nav-link{display:flex;align-items:center;gap:9px;padding:8px 10px;border-radius:7px;font-size:13px;font-weight:500;color:#fff;text-decoration:none;transition:all .15s;margin-bottom:1px}
        .nav-link:hover{background:rgba(255,255,255,.09)}.nav-link.active{background:var(--blue-600);font-weight:600}
        .nav-link .ico{font-size:14px;width:18px;text-align:center;flex-shrink:0}
        .sidebar-footer{padding:12px 10px 16px;border-top:1px solid rgba(255,255,255,.07);flex-shrink:0}
        .user-card{display:flex;align-items:center;gap:9px;padding:9px 10px;border-radius:8px;background:rgba(255,255,255,.06);overflow:hidden}
        .user-ava{width:30px;height:30px;border-radius:7px;background:var(--blue-600);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0}
        .user-info strong{display:block;font-size:12px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:130px;max-width:120px}
        .user-info span{font-size:10.5px;color:rgba(255,255,255,.4)}
        .topbar{position:fixed;top:0;left:var(--sidebar-w);right:0;height:var(--topbar-h);background:var(--white);border-bottom:1px solid var(--slate-200);display:flex;align-items:center;padding:0 24px;gap:12px;z-index:40}
        .topbar-title{font-size:15px;font-weight:700;color:var(--slate-900)}.breadcrumb{font-size:11.5px;color:var(--slate-500);margin-top:1px;display:flex;align-items:center;gap:4px}
        .breadcrumb a{color:var(--slate-500);text-decoration:none}.breadcrumb .sep{color:var(--slate-300)}
        .topbar-right{margin-left:auto;display:flex;align-items:center;gap:8px}
        .topbar-date{font-size:12px;color:var(--slate-500);padding:5px 12px;background:var(--slate-50);border:1px solid var(--slate-200);border-radius:6px}
        .btn{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:8px;font-family:var(--font);font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .15s;text-decoration:none}
        .btn-green{background:var(--green-600);color:#fff}.btn-green:hover{background:#15803d;transform:translateY(-1px)}
        .btn-outline{background:var(--slate-50);color:var(--slate-700);border:1px solid var(--slate-200)}.btn-outline:hover{background:var(--blue-50);color:var(--blue-600)}
        .btn:disabled{opacity:.5;cursor:not-allowed}
        .page{margin-left:var(--sidebar-w);padding-top:var(--topbar-h);min-height:100vh}
        .page-body{padding:22px 26px}
        .wrap{max-width:100%}
        .page-intro{margin-bottom:18px}
        .page-intro h1{font-size:19px;font-weight:800;color:var(--slate-900);letter-spacing:-.4px}
        .page-intro p{font-size:12.5px;color:var(--slate-500);margin-top:3px}
        .alert-strip{display:flex;align-items:flex-start;gap:10px;background:var(--blue-50);border:1px solid var(--blue-100);border-left:3px solid var(--blue-500);border-radius:7px;padding:10px 14px;font-size:12.5px;color:var(--blue-700);margin-bottom:16px;line-height:1.5}
        .card{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;overflow:hidden;margin-bottom:14px}
        .card-head{padding:13px 18px;border-bottom:1px solid var(--slate-100);font-size:13px;font-weight:700;color:var(--slate-900);display:flex;align-items:center;gap:7px}
        .card-body{padding:18px}
        .type-tabs{display:grid;grid-template-columns:1fr 1fr;gap:8px}
        .type-tab{padding:11px;border:1.5px solid var(--slate-200);border-radius:9px;background:var(--white);font-size:13.5px;font-weight:600;color:var(--slate-500);cursor:pointer;text-align:center;transition:all .2s;font-family:var(--font)}
        .type-tab.active.student{border-color:var(--green-600);background:var(--green-100);color:#14532d}
        .type-tab.active.staff{border-color:var(--blue-500);background:var(--blue-50);color:var(--blue-600)}
        .form-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px}
        .fg{margin-bottom:0}
        .fg.full{grid-column:1/-1}
        .fg label{display:block;font-size:11px;font-weight:700;color:var(--slate-600);margin-bottom:6px;text-transform:uppercase;letter-spacing:.4px}
        .fg label .req{color:var(--red-600)}.fg label .opt{font-size:11px;color:var(--slate-400);font-weight:400;text-transform:none;letter-spacing:0;margin-left:4px}
        .fg input,.fg select{width:100%;padding:10px 13px;border:1.5px solid var(--slate-200);border-radius:8px;font-size:13.5px;font-family:var(--font);color:var(--slate-900);background:var(--white);transition:border-color .2s}
        .fg input:focus,.fg select:focus{border-color:var(--blue-500);outline:none;box-shadow:0 0 0 3px rgba(37,99,235,.08)}
        .fg input.valid{border-color:var(--green-600)}.fg input.invalid{border-color:var(--red-600)}
        .fg .hint{font-size:11px;color:var(--slate-400);margin-top:4px}
        .dup-warn{background:var(--amber-100);border:1px solid #fcd34d;border-radius:7px;padding:8px 12px;margin-top:7px;font-size:12px;color:#92400e;font-weight:600;display:none}
        .rfid-preview{background:var(--blue-600);border-radius:8px;padding:12px 16px;color:white;margin-top:10px;display:none}
        .rp-label{font-size:10px;opacity:.7;margin-bottom:3px;text-transform:uppercase;letter-spacing:.8px}
        .rp-uid{font-family:monospace;font-size:20px;font-weight:700;letter-spacing:2px}
        .form-actions{display:flex;gap:10px;margin-top:4px}
        .status-bar{padding:11px 14px;border-radius:8px;font-size:13px;font-weight:600;margin-top:12px;display:none;line-height:1.5}
        .status-bar.success{background:var(--green-100);color:#14532d;border:1px solid #86efac}
        .status-bar.error{background:var(--red-100);color:#7f1d1d;border:1px solid #fca5a5}
        .status-bar.loading{background:var(--amber-100);color:#92400e}
        .success-card{background:var(--green-600);color:white;border-radius:12px;padding:26px;text-align:center;margin-top:14px;display:none;animation:popIn .4s ease-out}
        @keyframes popIn{from{opacity:0;transform:scale(.93)}to{opacity:1;transform:scale(1)}}
        .sc-info{background:rgba(255,255,255,.15);border-radius:9px;padding:14px;text-align:left;margin:14px 0}
        .sc-row{display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid rgba(255,255,255,.15);font-size:13px}
        .sc-row:last-child{border-bottom:none;padding-bottom:0}.sc-row span:first-child{opacity:.7;font-size:12px}.sc-row span:last-child{font-weight:600}
        .sc-btns{display:flex;gap:10px;justify-content:center}
        .sc-btn{padding:9px 20px;background:rgba(255,255,255,.15);border:1.5px solid rgba(255,255,255,.4);color:white;border-radius:7px;font-weight:600;cursor:pointer;font-size:13px;font-family:var(--font)}
        .sc-btn:hover{background:rgba(255,255,255,.25)}
        .spinner{display:inline-block;width:12px;height:12px;border:2px solid rgba(255,255,255,.3);border-radius:50%;border-top-color:#fff;animation:spin .7s linear infinite}
        @keyframes spin{to{transform:rotate(360deg)}}
    
        .material-symbols-outlined {
            font-size: 18px; line-height: 1; vertical-align: middle;
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .nav-link .material-symbols-outlined { font-size: 16px; }
        .stat-icon .material-symbols-outlined { font-size: 20px; }
        .logo-mark .material-symbols-outlined { font-size: 18px; color: #fff; }
</style>
</head><body>
<aside class="sidebar">
    <div class="sidebar-top"><div class="logo-mark"><span class="material-symbols-outlined">local_hospital</span></div><div class="logo-text"><strong>PKU UTHM</strong><span>Queue Management System</span></div></div>
    <nav class="sidebar-nav">
        <div class="nav-section">Main</div>
        <a href="staff_dashboard.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">dashboard</span></span> Dashboard</a>
        <a href="queue_management.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">format_list_numbered</span></span> Queue Management</a>
        <a href="appointment_management.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">event_available</span></span> Appointments</a>
        <div class="nav-section">Records</div>
        <a href="patient_list.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">dashboard</span></span> Patients</a>
        <a href="register_user_form.php" class="nav-link active"><span class="ico"><span class="material-symbols-outlined">person_add</span></span> Register Patient</a>
        <a href="walkin_form.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">subdirectory_arrow_right</span></span> Walk-In</a>
        <a href="reports.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">bar_chart</span></span> Reports</a>
        <div class="nav-section">Display</div>
        <a href="queue_display.html" target="_blank" class="nav-link"><span class="ico"><span class="material-symbols-outlined">monitor</span></span> Public Display</a>
    </nav>
    <div class="sidebar-footer"><div class="user-card">
        <div class="user-ava"><?php echo strtoupper(substr($_SESSION['full_name']??'S',0,1)); ?></div>
        <div class="user-info"><strong><?php echo htmlspecialchars($_SESSION['full_name']??'Staff'); ?></strong><span><?php echo ucfirst($_SESSION['role']??'Staff'); ?></span></div>
        <a href="logout.php" style="font-size:14px;color:rgba(255,255,255,.3);text-decoration:none;margin-left:4px;"><span class="material-symbols-outlined">logout</span></a>
    </div></div>
</aside>
<header class="topbar">
    <div><div class="topbar-title">Register Patient</div><div class="breadcrumb"><a href="staff_dashboard.php">Dashboard</a><span class="sep">›</span><span>Register Patient</span></div></div>
    <div class="topbar-right"><div class="topbar-date"><?php echo date('D, d M Y'); ?></div></div>
</header>
<div class="page"><div class="page-body"><div class="wrap">
    <div class="page-intro"><h1>Register Patient</h1><p>One-time registration for PKU walk-in queue system</p></div>
    <div class="alert-strip"><span class="material-symbols-outlined">info</span> Register the patient once. After this, staff can look them up by matrix or staff number at the walk-in counter. RFID card is optional — link it now or later.</div>
    <div style="display:grid;grid-template-columns:1fr 300px;gap:18px;align-items:start">
    <div>
    <form id="reg-form" novalidate>
        <div class="card">
            <div class="card-head"><span class="material-symbols-outlined">badge</span> Patient Type</div>
            <div class="card-body">
                <div class="type-tabs">
                    <button type="button" class="type-tab student active" onclick="setType('student',this)"><span class="material-symbols-outlined">school</span> Student</button>
                    <button type="button" class="type-tab staff" onclick="setType('staff',this)"><span class="material-symbols-outlined">work</span> Staff</button>
                </div>
                <input type="hidden" name="patient_type" id="patient_type" value="student">
            </div>
        </div>
        <div class="card">
            <div class="card-head" id="id-card-head"><span class="material-symbols-outlined">school</span> Student Information</div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="fg">
                        <label for="matrix_number" id="id-label">Matrix Number <span class="req">*</span></label>
                        <input type="text" id="matrix_number" name="matrix_number" placeholder="e.g. AI210234" required autocomplete="off">
                        <div class="hint" id="id-hint">Format: 2 letters + 6 digits</div>
                        <div class="dup-warn" id="dup-warn"><span class="material-symbols-outlined">warning</span> Already registered.</div>
                    </div>
                    <div class="fg">
                        <label for="full_name">Full Name <span class="req">*</span></label>
                        <input type="text" id="full_name" name="full_name" placeholder="Full name as per IC" required>
                    </div>
                    <div class="fg full">
                        <label for="email">Email <span class="req">*</span></label>
                        <input type="email" id="email" name="email" placeholder="e.g. ai210234@student.uthm.edu.my" required>
                        <div class="hint" id="email-hint">Student: @student.uthm.edu.my · Staff: @uthm.edu.my</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-head" id="dept-card-head"><span class="material-symbols-outlined">school</span> Academic Information</div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="fg">
                        <label for="faculty" id="faculty-label">Faculty <span class="req">*</span></label>
                        <select id="faculty" name="faculty" required>
                            <option value="">Select Faculty</option>
                            <option value="FKEE">FKEE — Electrical Engineering</option>
                            <option value="FKAAB">FKAAB — Civil Engineering</option>
                            <option value="FKMP">FKMP — Mechanical Engineering</option>
                            <option value="FSKTM">FSKTM — Computer Science</option>
                            <option value="FPTP">FPTP — Technology Management</option>
                            <option value="FPTV">FPTV — Technical & Vocational</option>
                            <option value="OTHER">Other</option>
                        </select>
                    </div>
                    <div class="fg">
                        <label for="program" id="program-label">Program <span class="req">*</span></label>
                        <input type="text" id="program" name="program" placeholder="e.g. Computer Engineering" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-head"><span class="material-symbols-outlined">credit_card</span> RFID Card <span style="font-size:12px;font-weight:400;color:var(--slate-400);margin-left:6px">(optional)</span></div>
            <div class="card-body">
                <div class="fg" style="margin-bottom:0">
                    <label for="rfid_uid">Card UID <span class="opt">(optional)</span></label>
                    <input type="text" id="rfid_uid" name="rfid_uid" placeholder="e.g. A1B2C3D4">
                    <div class="hint">8–12 hex characters. Leave blank if no card yet.</div>
                </div>
                <div class="rfid-preview" id="rfid-preview">
                    <div class="rp-label">Card Preview</div>
                    <div class="rp-uid" id="rfid-display">—</div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-green" id="submit-btn" style="flex:1;padding:12px;font-size:14px;justify-content:center"><span id="btn-text"><span class="material-symbols-outlined">check</span> Register Patient</span></button>
            <button type="button" class="btn btn-outline" style="padding:12px 22px" onclick="resetForm()">Reset</button>
        </div>
        <div class="status-bar" id="status-bar"></div>
    </form>
    <div class="success-card" id="success-card">
        <div style="font-size:36px"><span class="material-symbols-outlined">check_circle</span></div>
        <div style="font-size:18px;font-weight:700;margin:8px 0 4px">Patient Registered!</div>
        <div style="font-size:13px;opacity:.85">They can now be looked up at the walk-in counter.</div>
        <div class="sc-info">
            <div class="sc-row"><span>Name</span><span id="sc-name">—</span></div>
            <div class="sc-row"><span>Matrix / Staff No.</span><span id="sc-matrix">—</span></div>
            <div class="sc-row"><span>Email</span><span id="sc-email">—</span></div>
            <div class="sc-row"><span>Faculty / Dept.</span><span id="sc-faculty">—</span></div>
            <div class="sc-row"><span>RFID Card</span><span id="sc-rfid">—</span></div>
        </div>
        <div class="sc-btns">
            <button class="sc-btn" onclick="window.location.href='walkin_form.php'"><span class="material-symbols-outlined">add</span> Walk-In Now</button>
            <button class="sc-btn" onclick="anotherPatient()"><span class="material-symbols-outlined">refresh</span> Register Another</button>
        </div>
    </div>
    </div>
    <div style="display:flex;flex-direction:column;gap:14px">
        <div style="background:var(--white);border:1px solid var(--slate-200);border-radius:10px;padding:18px">
            <div style="font-size:13px;font-weight:700;color:var(--slate-900);margin-bottom:14px;display:flex;align-items:center;gap:7px"><span class="material-symbols-outlined">checklist</span> Registration Steps</div>
            <div style="display:flex;flex-direction:column;gap:12px">
                <div style="display:flex;gap:10px;align-items:flex-start"><div style="width:26px;height:26px;border-radius:6px;background:var(--green-100);display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0;font-weight:800;color:var(--green-600)">1</div><div><div style="font-size:12.5px;font-weight:600;color:var(--slate-900)">Choose patient type</div><div style="font-size:11.5px;color:var(--slate-500);margin-top:2px">Student or UTHM staff</div></div></div>
                <div style="display:flex;gap:10px;align-items:flex-start"><div style="width:26px;height:26px;border-radius:6px;background:var(--green-100);display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0;font-weight:800;color:var(--green-600)">2</div><div><div style="font-size:12.5px;font-weight:600;color:var(--slate-900)">Fill in details</div><div style="font-size:11.5px;color:var(--slate-500);margin-top:2px">Matrix number, full name, email, faculty</div></div></div>
                <div style="display:flex;gap:10px;align-items:flex-start"><div style="width:26px;height:26px;border-radius:6px;background:var(--amber-100);display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0;font-weight:800;color:var(--amber-600)">3</div><div><div style="font-size:12.5px;font-weight:600;color:var(--slate-900)">RFID card (optional)</div><div style="font-size:11.5px;color:var(--slate-500);margin-top:2px">Link now or assign later at any time</div></div></div>
                <div style="display:flex;gap:10px;align-items:flex-start"><div style="width:26px;height:26px;border-radius:6px;background:var(--blue-100);display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0;font-weight:800;color:var(--blue-600)">4</div><div><div style="font-size:12.5px;font-weight:600;color:var(--slate-900)">Done — walk them in</div><div style="font-size:11.5px;color:var(--slate-500);margin-top:2px">Use Walk-In form to assign a queue number</div></div></div>
            </div>
        </div>
        <div style="background:var(--white);border:1px solid var(--slate-200);border-radius:10px;padding:16px">
            <div style="font-size:12.5px;font-weight:700;color:var(--slate-700);margin-bottom:10px">Quick actions</div>
            <a href="walkin_form.php" style="display:flex;align-items:center;gap:8px;padding:9px 12px;background:var(--blue-50);border:1px solid var(--blue-100);border-radius:8px;text-decoration:none;color:var(--blue-600);font-size:12.5px;font-weight:600;margin-bottom:8px"><span class="material-symbols-outlined">subdirectory_arrow_right</span> Walk-In Form</a>
            <a href="patient_list.php" style="display:flex;align-items:center;gap:8px;padding:9px 12px;background:var(--slate-50);border:1px solid var(--slate-200);border-radius:8px;text-decoration:none;color:var(--slate-600);font-size:12.5px;font-weight:600"><span class="material-symbols-outlined">dashboard</span> View All Patients</a>
        </div>
    </div>
    </div>
</div></div></div>
<script>
let currentType='student',dupTimer=null;
function rebuildFaculty(opts,placeholder){const sel=document.getElementById('faculty');sel.innerHTML=`<option value="">${placeholder}</option>`;opts.forEach(([v,l])=>{const o=document.createElement('option');o.value=v;o.textContent=l;sel.appendChild(o);});}
function setType(type,btn){
    currentType=type;document.getElementById('patient_type').value=type;
    document.querySelectorAll('.type-tab').forEach(t=>t.classList.remove('active'));btn.classList.add('active');
    const isStaff=type==='staff';
    document.getElementById('id-label').innerHTML=isStaff?'Staff Number <span class="req">*</span>':'Matrix Number <span class="req">*</span>';
    document.getElementById('matrix_number').placeholder=isStaff?'e.g. P12345':'e.g. AI210234';
    document.getElementById('id-hint').textContent=isStaff?'e.g. P12345, KP001':'Format: 2 letters + 6 digits';
    document.getElementById('id-card-head').textContent=isStaff?'<span class="material-symbols-outlined">work</span> Staff Information':'<span class="material-symbols-outlined">school</span> Student Information';
    document.getElementById('dept-card-head').textContent=isStaff?'<span class="material-symbols-outlined">business</span> Department Information':'<span class="material-symbols-outlined">school</span> Academic Information';
    document.getElementById('faculty-label').innerHTML=isStaff?'Department <span class="req">*</span>':'Faculty <span class="req">*</span>';
    document.getElementById('program-label').innerHTML=isStaff?'Position / Role <span class="req">*</span>':'Program <span class="req">*</span>';
    document.getElementById('program').placeholder=isStaff?'e.g. Lecturer, Admin Officer':'e.g. Computer Engineering';
    document.getElementById('email-hint').textContent=isStaff?'Staff: @uthm.edu.my':'Student: @student.uthm.edu.my';
    if(isStaff){rebuildFaculty([['FKEE','FKEE'],['FKAAB','FKAAB'],['FKMP','FKMP'],['FSKTM','FSKTM'],['FPTP','FPTP'],['PKU','PKU — Health Centre'],['REGISTRAR','Registrar'],['BURSAR','Bursar'],['LIBRARY','Library'],['ICT','ICT Centre'],['OTHER','Other']],'Select Department');}
    else{rebuildFaculty([['FKEE','FKEE — Electrical Engineering'],['FKAAB','FKAAB — Civil Engineering'],['FKMP','FKMP — Mechanical Engineering'],['FSKTM','FSKTM — Computer Science'],['FPTP','FPTP — Technology Management'],['FPTV','FPTV — Technical & Vocational'],['OTHER','Other']],'Select Faculty');}
    document.getElementById('matrix_number').value='';document.getElementById('matrix_number').className='';clearDup();
}
document.getElementById('matrix_number').addEventListener('input',function(){this.value=this.value.toUpperCase();clearDup();clearTimeout(dupTimer);if(this.value.length>=4)dupTimer=setTimeout(()=>checkDup(this.value),700);});
function checkDup(val){fetch(`check_patient.php?matrix_number=${encodeURIComponent(val)}&type=${currentType}`).then(r=>r.json()).then(d=>{const warn=document.getElementById('dup-warn'),inp=document.getElementById('matrix_number');if(d.exists){warn.style.display='block';inp.classList.add('invalid');}else{clearDup();inp.classList.add('valid');}}).catch(()=>{});}
function clearDup(){document.getElementById('dup-warn').style.display='none';document.getElementById('matrix_number').classList.remove('valid','invalid');}
document.getElementById('rfid_uid').addEventListener('input',function(){this.value=this.value.toUpperCase();const prev=document.getElementById('rfid-preview');if(this.value.length>=8){prev.style.display='block';document.getElementById('rfid-display').textContent=this.value;}else prev.style.display='none';});
function showStatus(msg,type){const el=document.getElementById('status-bar');el.innerHTML=msg;el.className='status-bar '+type;el.style.display='block';}
document.getElementById('reg-form').addEventListener('submit',function(e){
    e.preventDefault();
    const mx=document.getElementById('matrix_number').value.trim(),name=document.getElementById('full_name').value.trim(),email=document.getElementById('email').value.trim(),fac=document.getElementById('faculty').value,prog=document.getElementById('program').value.trim();
    if(!mx||!name||!email||!fac||!prog){showStatus('<span class="material-symbols-outlined">warning</span> Please fill in all required fields.','error');return;}
    if(document.getElementById('dup-warn').style.display==='block'){showStatus('<span class="material-symbols-outlined">warning</span> This number is already registered.','error');return;}
    const btn=document.getElementById('submit-btn'),text=document.getElementById('btn-text');
    btn.disabled=true;text.innerHTML='<span class="spinner"></span> Registering…';
    showStatus('Saving patient record…','loading');
    fetch('register_patient.php',{method:'POST',body:new FormData(this)}).then(r=>r.json()).then(d=>{
        if(d.success){
            document.getElementById('sc-name').textContent=name;document.getElementById('sc-matrix').textContent=mx;document.getElementById('sc-email').textContent=email;
            document.getElementById('sc-faculty').textContent=document.getElementById('faculty').options[document.getElementById('faculty').selectedIndex].text;
            document.getElementById('sc-rfid').textContent=document.getElementById('rfid_uid').value.trim()||'Not linked';
            document.getElementById('status-bar').style.display='none';document.getElementById('reg-form').style.display='none';
            document.getElementById('success-card').style.display='block';
        }else showStatus('<span class="material-symbols-outlined">warning</span> '+(d.message||'Registration failed.'),'error');
    }).catch(()=>showStatus('<span class="material-symbols-outlined">close</span> Network error.','error')).finally(()=>{btn.disabled=false;text.innerHTML='<span class="material-symbols-outlined">check</span> Register Patient';});
});
function resetForm(){document.getElementById('reg-form').reset();document.getElementById('matrix_number').className='';document.getElementById('rfid-preview').style.display='none';clearDup();document.getElementById('status-bar').style.display='none';}
function anotherPatient(){document.getElementById('success-card').style.display='none';document.getElementById('reg-form').style.display='block';resetForm();window.scrollTo({top:0,behavior:'smooth'});}
</script>
</body></html>