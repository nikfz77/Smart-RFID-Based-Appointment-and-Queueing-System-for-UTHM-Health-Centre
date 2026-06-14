<?php include 'login_check.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Management — PKU UTHM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        :root{
            --blue-900:#0c2461;--blue-700:#1e4fa0;--blue-600:#1d5bb5;--blue-500:#2563eb;--blue-100:#dbeafe;--blue-50:#eff6ff;
            --green-600:#16a34a;--green-100:#dcfce7;
            --amber-600:#d97706;--amber-100:#fef3c7;
            --red-600:#dc2626;--red-100:#fee2e2;
            --slate-900:#0f172a;--slate-700:#334155;--slate-500:#64748b;--slate-300:#cbd5e1;--slate-200:#e2e8f0;--slate-100:#f1f5f9;--slate-50:#f8fafc;
            --white:#fff;--sidebar-w:232px;--topbar-h:58px;--font:'Plus Jakarta Sans',sans-serif;
        }
        html,body{height:100%;font-family:var(--font);background:var(--slate-100);color:var(--slate-900);font-size:14px}

        /* ── SIDEBAR ── */
        .sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--sidebar-w);background:var(--blue-900);display:flex;flex-direction:column;z-index:50;border-right:1px solid rgba(255,255,255,.06)}
        .sidebar-top{height:var(--topbar-h);display:flex;align-items:center;gap:10px;padding:0 18px;border-bottom:1px solid rgba(255,255,255,.07);flex-shrink:0}
        .logo-mark{width:32px;height:32px;background:var(--blue-500);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;color:white}
        .logo-text strong{display:block;font-size:13px;font-weight:700;color:#fff; font-family:'Sora',sans-serif;letter-spacing:-.2px}
        .logo-text span{font-size:10.5px;color:rgba(255,255,255,.45);font-weight:500}
        .sidebar-nav{flex:1;overflow-y:auto;padding:10px 10px 0}.sidebar-nav::-webkit-scrollbar{width:0}
        .nav-section{font-size:9.5px;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:rgba(255,255,255,.45);padding:16px 8px 5px}
        .nav-link{display:flex;align-items:center;gap:9px;padding:8px 10px;border-radius:7px;font-size:13px;font-weight:500;color:#ffffff;text-decoration:none;transition:all .15s;margin-bottom:1px}
        .nav-link:hover{background:rgba(255,255,255,.09);color:#fff}
        .nav-link.active{background:var(--blue-600);color:#fff;font-weight:600}
        .nav-link .ico{font-size:14px;width:18px;text-align:center;flex-shrink:0}
        .sidebar-footer{padding:12px 10px 16px;border-top:1px solid rgba(255,255,255,.07);flex-shrink:0}
        .user-card{display:flex;align-items:center;gap:9px;padding:9px 10px;border-radius:8px;background:rgba(255,255,255,.06);overflow:hidden}
        .user-ava{width:30px;height:30px;border-radius:7px;background:var(--blue-600);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0}
        .user-info{flex:1;min-width:0;overflow:hidden}
        .user-info strong{display:block;font-size:12px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:130px;max-width:120px}
        .user-info span{font-size:10.5px;color:rgba(255,255,255,.4)}

        /* ── TOPBAR ── */
        .topbar{position:fixed;top:0;left:var(--sidebar-w);right:0;height:var(--topbar-h);background:var(--white);border-bottom:1px solid var(--slate-200);display:flex;align-items:center;padding:0 24px;gap:12px;z-index:40}
        .topbar-title{font-size:15px;font-weight:700;color:var(--slate-900)}
        .breadcrumb{font-size:11.5px;color:var(--slate-500);margin-top:1px;display:flex;align-items:center;gap:4px}
        .breadcrumb a{color:var(--slate-500);text-decoration:none}.breadcrumb a:hover{color:var(--blue-500)}.breadcrumb .sep{color:var(--slate-300)}
        .topbar-right{margin-left:auto;display:flex;align-items:center;gap:8px}
        .topbar-date{font-size:12px;color:var(--slate-500);font-weight:500;padding:5px 12px;background:var(--slate-50);border:1px solid var(--slate-200);border-radius:6px}

        /* ── PAGE ── */
        .page{margin-left:var(--sidebar-w);padding-top:var(--topbar-h);min-height:100vh}
        .page-body{padding:22px 26px}
        .page-intro{margin-bottom:18px}
        .page-intro h1{font-size:19px;font-weight:800;color:var(--slate-900);letter-spacing:-.4px}
        .page-intro p{font-size:12.5px;color:var(--slate-500);margin-top:3px}

        /* ── BUTTONS ── */
        .btn{display:inline-flex;align-items:center;gap:7px;padding:9px 16px;border-radius:8px;font-family:var(--font);font-size:13px;font-weight:600;cursor:pointer;border:none;transition:all .15s;text-decoration:none}
        .btn-green{background:var(--green-600);color:#fff}.btn-green:hover{background:#15803d;transform:translateY(-1px)}
        .btn-green:disabled{opacity:.5;cursor:not-allowed;transform:none}
        .btn-outline{background:var(--slate-50);color:var(--slate-700);border:1px solid var(--slate-200)}.btn-outline:hover{background:var(--blue-50);color:var(--blue-600)}
        .btn-sm{padding:5px 11px;font-size:12px;border-radius:6px}

        /* ── CTRL BAR ── */
        .ctrl-bar{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;padding:14px 18px;margin-bottom:14px;display:flex;align-items:center;gap:10px;flex-wrap:wrap}
        .ctrl-bar h2{font-size:13px;font-weight:700;color:var(--slate-900);margin-right:4px}
        .status-strip{padding:9px 13px;border-radius:7px;font-size:12.5px;font-weight:600;display:none;margin-left:auto}
        .status-strip.success{background:var(--green-100);color:#14532d}
        .status-strip.error{background:var(--red-100);color:#7f1d1d}
        .status-strip.loading{background:var(--amber-100);color:#78350f}

        /* ── QUEUE GRID ── */
        .queue-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:14px}
        .q-col{background:var(--white);border:1px solid var(--slate-200);border-radius:10px;overflow:hidden;display:flex;flex-direction:column}
        .q-col-head{padding:12px 16px;border-bottom:1px solid var(--slate-100);display:flex;align-items:center;justify-content:space-between}
        .q-col-head h3{font-size:12.5px;font-weight:700;display:flex;align-items:center;gap:6px}
        .q-col.serving .q-col-head h3{color:var(--red-600)}
        .q-col.waiting .q-col-head h3{color:var(--blue-600)}
        .q-col.completed .q-col-head h3{color:var(--green-600)}
        .count-badge{font-size:11px;font-weight:700;padding:2px 9px;border-radius:12px}
        .q-col.serving  .count-badge{background:var(--red-100);color:var(--red-600)}
        .q-col.waiting  .count-badge{background:var(--blue-50);color:var(--blue-600)}
        .q-col.completed .count-badge{background:var(--green-100);color:var(--green-600)}
        .q-col-body{padding:10px;flex:1;overflow-y:auto;max-height:560px}
        .q-col-body::-webkit-scrollbar{width:4px}.q-col-body::-webkit-scrollbar-thumb{background:var(--slate-200);border-radius:4px}

        /* ── QUEUE CARDS ── */
        .q-card{background:var(--slate-50);border:1px solid var(--slate-200);border-left:3px solid var(--slate-300);border-radius:8px;padding:12px 14px;margin-bottom:8px;transition:all .2s}
        .q-card:last-child{margin-bottom:0}
        .q-card:hover{transform:translateX(2px);box-shadow:0 2px 8px rgba(0,0,0,.06)}
        .q-col.serving  .q-card{border-left-color:var(--red-600);background:#fff5f5}
        .q-col.waiting  .q-card{border-left-color:var(--blue-500)}
        .q-col.completed .q-card{border-left-color:var(--green-600);opacity:.8}
        .q-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:6px}
        .q-num{font-size:16px;font-weight:800;letter-spacing:-.5px}
        .q-col.serving  .q-num{color:var(--red-600)}
        .q-col.waiting  .q-num{color:var(--blue-600)}
        .q-col.completed .q-num{color:var(--green-600)}
        .q-time{font-size:11px;color:var(--slate-400)}
        .q-sched{font-size:11px;font-weight:700;background:var(--green-100);color:var(--green-600);padding:2px 7px;border-radius:4px}
        .q-name{font-size:13px;font-weight:600;color:var(--slate-900);margin-bottom:2px}
        .q-svc{font-size:11.5px;color:var(--slate-500)}
        .q-faculty{display:inline-block;padding:1px 6px;border-radius:4px;font-size:10px;font-weight:700;background:var(--amber-100);color:#92400e;margin-left:4px}
        .q-actions{display:flex;gap:5px;flex-wrap:wrap;margin-top:9px}
        .dr-assigned{background:var(--green-100);color:#14532d;padding:3px 9px;border-radius:5px;font-size:11px;font-weight:700}

        /* ── EMPTY ── */
        .empty-state{text-align:center;padding:28px 16px;color:var(--slate-400)}
        .empty-state .ico{font-size:28px;margin-bottom:8px;opacity:.4}
        .empty-state p{font-size:13px}

        /* ── MODAL ── */
        .modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:1000;display:none;justify-content:center;align-items:center}
        .modal-overlay.active{display:flex}
        .modal{background:var(--white);border-radius:12px;padding:24px;max-width:440px;width:90%;animation:mIn .2s ease-out;max-height:90vh;overflow-y:auto}
        @keyframes mIn{from{opacity:0;transform:scale(.96)}to{opacity:1;transform:scale(1)}}
        .modal-hd{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;padding-bottom:14px;border-bottom:1px solid var(--slate-100)}
        .modal-hd h3{font-size:15px;font-weight:700;color:var(--slate-900)}.modal-close{background:none;border:none;font-size:18px;cursor:pointer;color:var(--slate-400)}
        .modal-info{background:var(--blue-50);color:var(--blue-700);padding:10px 13px;border-radius:7px;font-size:13px;font-weight:600;margin-bottom:14px}
        .fg{margin-bottom:14px}
        .fg label{display:block;font-size:11px;font-weight:700;color:var(--slate-600);margin-bottom:6px;text-transform:uppercase;letter-spacing:.4px}
        .fg select,.fg input{width:100%;padding:10px 13px;border:1.5px solid var(--slate-200);border-radius:8px;font-size:13.5px;font-family:var(--font);color:var(--slate-900)}
        .fg select:focus,.fg input:focus{border-color:var(--blue-500);outline:none;box-shadow:0 0 0 3px rgba(37,99,235,.08)}
        .fg .hint{font-size:11px;color:var(--slate-400);margin-top:4px}
        .modal-actions{display:flex;gap:8px;margin-top:16px}
        .dr-option{padding:12px 14px;border:1.5px solid var(--slate-200);border-radius:8px;cursor:pointer;margin-bottom:8px;transition:all .15s}
        .dr-option:hover,.dr-option.selected{border-color:var(--green-600);background:var(--green-100)}
        .dr-name-row{font-size:13px;font-weight:600;color:var(--slate-900)}
        .dr-room-row{font-size:12px;color:var(--slate-500);margin-top:2px}
        .spinner{display:inline-block;width:12px;height:12px;border:2px solid rgba(255,255,255,.3);border-radius:50%;border-top-color:#fff;animation:spin .7s linear infinite}
        @keyframes spin{to{transform:rotate(360deg)}}

        @media(max-width:900px){.queue-grid{grid-template-columns:1fr}}
    
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

<!-- ── SIDEBAR ── -->
<aside class="sidebar">
    <div class="sidebar-top">
        <div class="logo-mark"><span class="material-symbols-outlined">local_hospital</span></div>
        <div class="logo-text"><strong>PKU UTHM</strong><span>Queue Management System</span></div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Main</div>
        <a href="staff_dashboard.php"        class="nav-link"><span class="ico"><span class="material-symbols-outlined">dashboard</span></span> Dashboard</a>
        <a href="queue_management.php"       class="nav-link active"><span class="ico"><span class="material-symbols-outlined">format_list_numbered</span></span> Queue Management</a>
        <a href="appointment_management.php" class="nav-link"><span class="ico"><span class="material-symbols-outlined">event_available</span></span> Appointments</a>
        <div class="nav-section">Records</div>
        <a href="patient_list.php"           class="nav-link"><span class="ico"><span class="material-symbols-outlined">personal_injury</span></span> Patients</a>
        <a href="register_user_form.php"     class="nav-link"><span class="ico"><span class="material-symbols-outlined">person_add</span></span> Register Patient</a>
        <a href="walkin_form.php"            class="nav-link"><span class="ico"><span class="material-symbols-outlined">subdirectory_arrow_right</span></span> Walk-In</a>
        <a href="reports.php"               class="nav-link"><span class="ico"><span class="material-symbols-outlined">bar_chart</span></span> Reports</a>
        <div class="nav-section">Display</div>
        <a href="queue_display.html" target="_blank" class="nav-link"><span class="ico"><span class="material-symbols-outlined">monitor</span></span> Public Display</a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-card">
            <div class="user-ava"><?php echo strtoupper(substr($_SESSION['full_name']??'S',0,1)); ?></div>
            <div class="user-info">
                <strong><?php echo htmlspecialchars($_SESSION['full_name']??'Staff'); ?></strong>
                <span><?php echo ucfirst($_SESSION['role']??'Staff'); ?></span>
            </div>
            <a href="logout.php" style="font-size:14px;color:rgba(255,255,255,.3);text-decoration:none;margin-left:4px;"><span class="material-symbols-outlined">logout</span></a>
        </div>
    </div>
</aside>

<!-- ── TOPBAR ── -->
<header class="topbar">
    <div>
        <div class="topbar-title">Queue Management</div>
        <div class="breadcrumb"><a href="staff_dashboard.php">Dashboard</a><span class="sep">›</span><span>Queue Management</span></div>
    </div>
    <div class="topbar-right">
        <div class="topbar-date"><?php echo date('D, d M Y'); ?></div>
        <a href="queue_display.html" target="_blank" class="btn btn-outline" style="padding:6px 13px;font-size:12.5px;"><span class="material-symbols-outlined">monitor</span> Public Display</a>
    </div>
</header>

<!-- ── PAGE ── -->
<div class="page">
    <div class="page-body">

        <div id="availability-panel"><?php include 'doctor_availability_panel.php'; ?></div>

        <div class="page-intro">
            <h1>Queue Management</h1>
            <p>Real-time queue monitoring and control — auto-refreshes every 6 seconds</p>
        </div>

        <div class="ctrl-bar">
            <h2>Control Panel</h2>
            <button class="btn btn-green" id="call-btn" onclick="callNext()"><span id="call-text"><span class="material-symbols-outlined">campaign</span> Call Next Patient</span></button>
            <button class="btn btn-outline" onclick="refreshQueue()"><span class="material-symbols-outlined">refresh</span> Refresh</button>
            <a href="queue_display.html" target="_blank" class="btn btn-outline"><span class="material-symbols-outlined">monitor</span> Public Display</a>
            <div class="status-strip" id="status-strip"></div>
        </div>

        <div class="queue-grid">
            <div class="q-col serving">
                <div class="q-col-head"><h3><span class="material-symbols-outlined">stethoscope</span> Being Served</h3><span class="count-badge" id="cnt-serving">0</span></div>
                <div class="q-col-body" id="list-serving"><div class="empty-state"><div class="ico"><span class="material-symbols-outlined">hourglass_empty</span></div><p>Loading…</p></div></div>
            </div>
            <div class="q-col waiting">
                <div class="q-col-head"><h3><span class="material-symbols-outlined">hourglass_empty</span> Waiting</h3><span class="count-badge" id="cnt-waiting">0</span></div>
                <div class="q-col-body" id="list-waiting"><div class="empty-state"><div class="ico"><span class="material-symbols-outlined">hourglass_empty</span></div><p>Loading…</p></div></div>
            </div>
            <div class="q-col completed">
                <div class="q-col-head"><h3><span class="material-symbols-outlined">check</span> Completed</h3><span class="count-badge" id="cnt-completed">0</span></div>
                <div class="q-col-body" id="list-completed"><div class="empty-state"><div class="ico"><span class="material-symbols-outlined">hourglass_empty</span></div><p>Loading…</p></div></div>
            </div>
        </div>
    </div>
</div>

<!-- Set Time Modal -->
<div class="modal-overlay" id="time-modal">
    <div class="modal">
        <div class="modal-hd"><h3><span class="material-symbols-outlined">schedule</span> Set Appointment Time</h3><button class="modal-close" onclick="closeModal()">&times;</button></div>
        <div class="modal-info" id="modal-info">Queue: —</div>
        <div class="fg"><label>Service Type</label>
            <select id="modal-svc">
                <option value="General Consultation">General Consultation</option>
                <option value="Follow-up Check">Follow-up Check</option>
                <option value="Vaccination">Vaccination</option>
                <option value="Prescription Refill">Prescription Refill</option>
            </select>
        </div>
        <div class="fg"><label>Time</label>
            <input type="time" id="modal-time" min="08:00" max="17:00">
            <div class="hint">Operating hours: 8:00 AM – 5:00 PM</div>
        </div>
        <div class="modal-actions">
            <button class="btn btn-green" id="btn-mc" onclick="confirmTime()" style="flex:1"><span id="mc-text"><span class="material-symbols-outlined">check</span> Confirm & Send Email</span></button>
            <button class="btn btn-outline" onclick="closeModal()">Cancel</button>
        </div>
    </div>
</div>

<!-- Assign Doctor Modal -->
<div class="modal-overlay" id="dr-modal">
    <div class="modal">
        <div class="modal-hd"><h3><span class="material-symbols-outlined">medical_services</span> Assign Doctor</h3><button class="modal-close" onclick="closeDrModal()">&times;</button></div>
        <p style="font-size:12.5px;color:var(--slate-500);margin-bottom:12px">Queue: <strong id="dr-queue-num">—</strong></p>
        <div id="dr-options">Loading doctors…</div>
        <div id="dr-msg" style="padding:9px;border-radius:7px;font-size:13px;font-weight:600;margin-top:10px;display:none"></div>
        <div class="modal-actions">
            <button class="btn btn-green" onclick="confirmAssign()" style="flex:1">Assign</button>
            <button class="btn btn-outline" onclick="closeDrModal()">Cancel</button>
        </div>
    </div>
</div>

<script>
const strip = document.getElementById('status-strip');
let currentQueueId = null, selQueueId = null, selDrId = null, selRoom = null;
const userRole = '<?php echo $_SESSION["role"] ?? "Staff"; ?>';

function showStatus(msg, type) {
    strip.textContent = msg; strip.className = 'status-strip ' + type; strip.style.display = 'block';
    if (type === 'success') setTimeout(() => strip.style.display = 'none', 5000);
}

function callNext() {
    const btn = document.getElementById('call-btn'), text = document.getElementById('call-text');
    btn.disabled = true; text.innerHTML = '<span class="spinner"></span> Calling…';
    showStatus('Calling next patient…', 'loading');
    fetch('call_next_patient.php', { method: 'POST' }).then(r => r.json())
        .then(d => { showStatus(d.message, d.success ? 'success' : 'error'); refreshQueue(); })
        .catch(() => showStatus('Network error', 'error'))
        .finally(() => { btn.disabled = false; text.innerHTML = '<span class="material-symbols-outlined">campaign</span> Call Next Patient'; });
}

function refreshQueue() {
    fetch('get_queue_status.php').then(r => r.json()).then(data => {
        const serving   = data.filter(i => i.queue_status === 'Being-Served');
        const waiting   = data.filter(i => i.queue_status === 'Waiting');
        const completed = data.filter(i => i.queue_status === 'Completed');
        document.getElementById('cnt-serving').textContent   = serving.length;
        document.getElementById('cnt-waiting').textContent   = waiting.length;
        document.getElementById('cnt-completed').textContent = completed.length;
        document.getElementById('list-serving').innerHTML   = renderItems(serving, 'serving');
        document.getElementById('list-waiting').innerHTML   = renderItems(waiting, 'waiting');
        document.getElementById('list-completed').innerHTML = renderItems(completed, 'completed');
    }).catch(() => {});
}

function renderItems(items, status) {
    if (!items.length) {
        const msgs = { serving: 'No patient being served', waiting: 'No patients waiting', completed: 'No completed patients' };
        return `<div class="empty-state"><div class="ico"><span class="material-symbols-outlined">inbox</span></div><p>${msgs[status]}</p></div>`;
    }
    return items.map(item => {
        const time  = item.created_at ? new Date(item.created_at).toLocaleTimeString('en-MY', { hour: '2-digit', minute: '2-digit' }) : '';
        const name  = item.student_name || item.matrix_number || 'Unknown';
        const svc   = item.service || item.service_type || 'General';
        const sched = item.scheduled_time ? `<span class="q-sched"><span class="material-symbols-outlined">schedule</span> ${new Date(item.scheduled_time).toLocaleTimeString('en-MY', { hour: '2-digit', minute: '2-digit' })}</span>` : '';
        const fac   = item.source_faculty ? `<span class="q-faculty">${esc(item.source_faculty)}</span>` : '';
        const url   = `print_ticket.php?queue_number=${item.queue_number}&matrix_number=${encodeURIComponent(item.matrix_number)}&service=${encodeURIComponent(svc)}&student_name=${encodeURIComponent(name)}`;
        let actions = '';
        if (status === 'completed') {
            actions = `<a href="${url}" target="_blank" class="btn btn-outline btn-sm"><span class="material-symbols-outlined">print</span> Print</a>`;
        } else if (status === 'serving') {
            const doneBtn = (userRole === 'Doctor')
                ? `<button class="btn btn-sm" style="background:var(--green-100);color:#14532d;" onclick="updateStatus(${item.id},'Completed')"><span class="material-symbols-outlined" style="font-size:14px">check</span> Done</button>`
                : `<span style="font-size:11px;color:var(--slate-400);font-style:italic;padding:4px 6px">Doctor only</span>`;
            actions = `<div class="q-actions">${doneBtn}<a href="${url}" target="_blank" class="btn btn-outline btn-sm"><span class="material-symbols-outlined">print</span></a></div>`;
        } else if (item.assigned_doctor_id) {
            const drName = item.doctor_name || 'Doctor', rm = item.assigned_room || '';
            actions = `<div class="q-actions"><span class="dr-assigned"><span class="material-symbols-outlined">check</span> ${esc(drName)}${rm ? ' · ' + esc(rm) : ''}</span><button class="btn btn-outline btn-sm" onclick="openModal(${item.id},'${item.queue_number}','${esc2(name)}','${esc2(svc)}')"><span class="material-symbols-outlined">schedule</span> Time</button><button class="btn btn-sm" style="background:var(--red-100);color:var(--red-600);" onclick="updateStatus(${item.id},'Cancelled')">&times;</button><a href="${url}" target="_blank" class="btn btn-outline btn-sm"><span class="material-symbols-outlined">print</span></a></div>`;
        } else {
            actions = `<div class="q-actions"><button class="btn btn-outline btn-sm" onclick="openModal(${item.id},'${item.queue_number}','${esc2(name)}','${esc2(svc)}')"><span class="material-symbols-outlined">schedule</span> Time</button><button class="btn btn-outline btn-sm" style="color:var(--blue-600);" onclick="openAssignDr(${item.id},'${item.queue_number}')"><span class="material-symbols-outlined">medical_services</span> Dr</button><button class="btn btn-sm" style="background:var(--red-100);color:var(--red-600);" onclick="updateStatus(${item.id},'Cancelled')">&times;</button><a href="${url}" target="_blank" class="btn btn-outline btn-sm"><span class="material-symbols-outlined">print</span></a></div>`;
        }
        return `<div class="q-card"><div class="q-top"><div class="q-num">${esc(item.queue_number)}</div><div style="display:flex;gap:5px;align-items:center">${sched}<span class="q-time">${time}</span></div></div><div class="q-name">${esc(name)}</div><div class="q-svc">${esc(svc)}${fac}</div>${actions}</div>`;
    }).join('');
}

function esc(s)  { const d = document.createElement('div'); d.textContent = String(s); return d.innerHTML; }
function esc2(s) { return String(s).replace(/'/g, "\\'"); }

function updateStatus(id, status) {
    if (!confirm(status === 'Completed' ? 'Mark as completed?' : 'Cancel this queue?')) return;
    showStatus('Updating…', 'loading');
    fetch('update_queue_status.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ queue_id: id, status }) })
        .then(r => r.json()).then(d => { showStatus(d.success ? '<span class="material-symbols-outlined">check</span> ' + status : '<span class="material-symbols-outlined">warning</span> ' + d.message, d.success ? 'success' : 'error'); if (d.success) refreshQueue(); })
        .catch(() => showStatus('Network error', 'error'));
}

// Time modal
function openModal(id, qNum, name, svc) {
    currentQueueId = id;
    document.getElementById('modal-info').textContent = `${qNum} — ${name}`;
    document.getElementById('modal-svc').value = svc;
    const next = new Date(Date.now() + 3600000);
    document.getElementById('modal-time').value = `${String(next.getHours()).padStart(2,'0')}:00`;
    document.getElementById('time-modal').classList.add('active');
}
function closeModal() { document.getElementById('time-modal').classList.remove('active'); currentQueueId = null; }
function confirmTime() {
    const time = document.getElementById('modal-time').value, svc = document.getElementById('modal-svc').value;
    if (!time) { alert('Please select a time.'); return; }
    const btn = document.getElementById('btn-mc'), text = document.getElementById('mc-text');
    btn.disabled = true; text.innerHTML = '<span class="spinner"></span> Sending…';
    fetch('set_queue_time.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ queue_id: currentQueueId, scheduled_time: time, service_type: svc }) })
        .then(r => r.json()).then(d => { closeModal(); showStatus(d.success ? '<span class="material-symbols-outlined">check</span> ' + d.message : '<span class="material-symbols-outlined">warning</span> ' + d.message, d.success ? 'success' : 'error'); if (d.success) refreshQueue(); })
        .catch(() => showStatus('Network error', 'error'))
        .finally(() => { btn.disabled = false; text.innerHTML = '<span class="material-symbols-outlined">check</span> Confirm & Send Email'; });
}
document.getElementById('time-modal').addEventListener('click', e => { if (e.target === document.getElementById('time-modal')) closeModal(); });

// Assign Doctor modal
function openAssignDr(qid, qnum) {
    selQueueId = qid; selDrId = null; selRoom = null;
    document.getElementById('dr-queue-num').textContent = qnum;
    document.getElementById('dr-msg').style.display = 'none';
    document.getElementById('dr-modal').classList.add('active');
    const container = document.getElementById('dr-options');
    container.innerHTML = '<p style="color:var(--slate-400);font-size:13px;text-align:center;padding:12px">Loading doctors…</p>';
    fetch('get_doctor_availability.php').then(r => r.json()).then(docs => {
        const available = docs.filter(d => d.is_available == 1 && d.room);
        if (!available.length) {
            container.innerHTML = '<p style="color:var(--red-600);font-size:13px">No doctors available right now.</p>';
            return;
        }
        container.innerHTML = available.map(d => {
            const isRec   = d.is_recommended;
            const avgTime = d.avg_service_min ? `Avg ${d.avg_service_min} min/patient` : 'No history yet';
            const estWait = d.est_wait_min > 0 ? `Est. wait ~${d.est_wait_min} min` : 'Available now';
            const clocked = d.clocked_in_at ? new Date(d.clocked_in_at).toLocaleTimeString('en-MY', { hour: '2-digit', minute: '2-digit' }) : '—';
            const recBadge = isRec
                ? `<span style="display:inline-flex;align-items:center;gap:3px;background:#dcfce7;color:#14532d;font-size:10px;font-weight:800;padding:2px 8px;border-radius:10px;border:1px solid #86efac;margin-left:6px">⭐ RECOMMENDED</span>`
                : '';
            const borderStyle = isRec ? 'border:2px solid var(--green-600)' : 'border:1.5px solid var(--slate-200)';
            const bgStyle     = isRec ? 'background:linear-gradient(135deg,#f0fdf4,#dcfce7)' : '';
            return `<div class="dr-option" style="${borderStyle};${bgStyle};margin-bottom:8px" onclick="selectDr(this,${d.id},'${esc2(d.room)}')">
                <div style="display:flex;align-items:center;flex-wrap:wrap;gap:4px;margin-bottom:6px">
                    <span class="material-symbols-outlined" style="font-size:16px;color:var(--green-600)">medical_services</span>
                    <span style="font-size:13px;font-weight:700;color:var(--slate-900)">${esc(d.full_name)}</span>
                    ${recBadge}
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;font-size:12px;margin-bottom:4px">
                    <span style="font-weight:700;color:var(--blue-600)">${esc(d.room)}</span>
                    <span style="color:var(--slate-400)">Since ${clocked}</span>
                </div>
                <div style="display:flex;gap:12px;flex-wrap:wrap;font-size:11.5px">
                    <span style="color:var(--slate-500)">${d.patient_count} patient${d.patient_count!=1?'s':''} today</span>
                    <span style="color:var(--amber-600);font-weight:600">${estWait}</span>
                    <span style="color:var(--slate-400)">${avgTime}</span>
                </div>
            </div>`;
        }).join('');
        // Auto-select the recommended doctor
        const rec = available.find(d => d.is_recommended);
        if (rec) {
            const el = container.querySelector('.dr-option');
            if (el) selectDr(el, rec.id, rec.room);
        }
    }).catch(() => {
        container.innerHTML = '<p style="color:var(--red-600);font-size:13px">Error loading doctors.</p>';
    });
}
function selectDr(el, did, room) { document.querySelectorAll('.dr-option').forEach(o => o.classList.remove('selected')); el.classList.add('selected'); selDrId = did; selRoom = room; }
function closeDrModal() { document.getElementById('dr-modal').classList.remove('active'); }
function confirmAssign() {
    if (!selDrId) { const m = document.getElementById('dr-msg'); m.textContent = 'Please select a doctor.'; m.style.background = 'var(--red-100)'; m.style.color = '#7f1d1d'; m.style.display = 'block'; return; }
    fetch('assign_doctor.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ queue_id: selQueueId, doctor_id: selDrId, room: selRoom }) })
        .then(r => r.json()).then(d => {
            const m = document.getElementById('dr-msg');
            m.textContent = d.message; m.style.background = d.success ? 'var(--green-100)' : 'var(--red-100)'; m.style.color = d.success ? '#14532d' : '#7f1d1d'; m.style.display = 'block';
            if (d.success) setTimeout(() => { closeDrModal(); refreshQueue(); }, 1500);
        });
}

refreshQueue();
setInterval(refreshQueue, 6000);

function refreshAvailability() {
    fetch('doctor_availability_panel.php')
        .then(r => r.text())
        .then(html => { document.getElementById('availability-panel').innerHTML = html; })
        .catch(() => {});
}
setInterval(refreshAvailability, 6000);
</script>
</body>
</html>