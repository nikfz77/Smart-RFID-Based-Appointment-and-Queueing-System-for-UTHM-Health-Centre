<?php 
session_start();
include 'login_check.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFID Check-In - PKU UTHM</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary-blue: #0d47a1; --secondary-blue: #1976d2; --light-blue: #42a5f5;
            --success: #00c853; --warning: #ffa000; --error: #ff1744;
            --white: #ffffff; --dark: #263238;
        }
        body { font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #0d47a1 0%, #1976d2 50%, #42a5f5 100%); min-height: 100vh; padding: 20px; }
        .header { background: rgba(255,255,255,0.95); padding: 20px 30px; border-radius: 16px; box-shadow: 0 8px 32px rgba(0,0,0,0.2); display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { font-family: 'Orbitron', sans-serif; font-size: 24px; color: var(--primary-blue); display: flex; align-items: center; gap: 12px; }
        .back-btn { background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue)); color: white; padding: 12px 24px; border-radius: 10px; text-decoration: none; font-weight: 600; transition: all 0.3s; }
        .back-btn:hover { transform: translateY(-2px); }
        .container { max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 30px; }
        .scanner-panel, .info-panel { background: rgba(255,255,255,0.95); padding: 40px; border-radius: 20px; box-shadow: 0 8px 32px rgba(0,0,0,0.2); }
        .scanner-status { text-align: center; margin-bottom: 35px; }
        .scanner-icon { width: 180px; height: 180px; margin: 0 auto 25px; background: linear-gradient(135deg, var(--primary-blue), var(--light-blue)); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 72px; animation: pulse 2s infinite; box-shadow: 0 0 50px rgba(13,71,161,0.4); transition: all 0.3s; }
        @keyframes pulse { 0%,100% { transform: scale(1); } 50% { transform: scale(1.05); } }
        .scanner-icon.success { background: linear-gradient(135deg, #00c853, #69f0ae); }
        .scanner-icon.error   { background: linear-gradient(135deg, #ff1744, #ff5252); animation: shake 0.5s ease-out; }
        .scanner-icon.already { background: linear-gradient(135deg, var(--warning), #ffca28); }
        @keyframes shake { 0%,100% { transform: translateX(0); } 25% { transform: translateX(-10px); } 75% { transform: translateX(10px); } }
        .status-text { font-family: 'Orbitron', sans-serif; font-size: 20px; font-weight: 700; margin-bottom: 8px; }
        .status-text.waiting  { color: var(--primary-blue); }
        .status-text.success  { color: var(--success); }
        .status-text.error    { color: var(--error); }
        .status-text.already  { color: var(--warning); }
        .status-subtext { font-size: 13px; color: #666; }
        .manual-input { margin-top: 25px; padding-top: 25px; border-top: 2px dashed #ddd; }
        .manual-input h3 { font-size: 14px; margin-bottom: 12px; color: var(--dark); font-weight: 700; }
        .input-group { display: flex; gap: 10px; }
        .input-group input { flex: 1; padding: 12px; border: 2px solid #e0e7ff; border-radius: 10px; font-size: 14px; font-family: 'Courier New', monospace; text-transform: uppercase; }
        .input-group input:focus { border-color: var(--primary-blue); outline: none; }
        .input-group button { padding: 12px 20px; background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue)); color: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; font-family: 'Inter', sans-serif; }
        .demo-hint { margin-top: 10px; font-size: 12px; color: #666; background: #f5f7fa; padding: 10px; border-radius: 8px; }
        .info-panel h2 { font-family: 'Orbitron', sans-serif; font-size: 18px; color: var(--primary-blue); margin-bottom: 22px; }
        .student-card { background: linear-gradient(135deg, #f5f7fa, #e3f2fd); padding: 20px; border-radius: 14px; margin-bottom: 20px; border-left: 5px solid var(--primary-blue); display: none; }
        .student-card.show { display: block; animation: slideIn 0.4s ease-out; }
        @keyframes slideIn { from { opacity: 0; transform: translateX(-15px); } to { opacity: 1; transform: translateX(0); } }
        .field { margin-bottom: 12px; }
        .label { font-size: 11px; color: #666; font-weight: 700; text-transform: uppercase; margin-bottom: 3px; }
        .value { font-size: 15px; font-weight: 600; color: var(--dark); }
        .value.matrix { font-family: 'Courier New', monospace; font-size: 20px; color: var(--primary-blue); font-weight: 800; }
        .already-queued { background: #fff3e0; border: 2px solid var(--warning); border-radius: 12px; padding: 18px; text-align: center; display: none; margin-bottom: 18px; }
        .already-queued.show { display: block; animation: slideIn 0.4s ease-out; }
        .aq-label { font-size: 12px; color: #666; margin-bottom: 6px; font-weight: 700; }
        .aq-num   { font-family: 'Orbitron', sans-serif; font-size: 36px; font-weight: 900; color: var(--warning); }
        .aq-msg   { font-size: 12px; color: #e65100; margin-top: 6px; }
        .service-selection { display: none; }
        .service-selection.show { display: block; animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .service-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 18px; }
        .service-card { padding: 16px; border: 3px solid #e0e7ff; border-radius: 12px; text-align: center; cursor: pointer; transition: all 0.3s; background: white; }
        .service-card:hover { border-color: var(--light-blue); transform: translateY(-3px); }
        .service-card.selected { border-color: var(--primary-blue); background: linear-gradient(135deg, #e3f2fd, #bbdefb); }
        .service-card .icon { font-size: 32px; margin-bottom: 6px; }
        .service-card .name { font-weight: 700; color: var(--dark); font-size: 12px; }
        .checkin-btn { width: 100%; padding: 15px; background: linear-gradient(135deg, #00c853, #69f0ae); color: white; border: none; border-radius: 12px; font-size: 16px; font-weight: 700; cursor: pointer; transition: all 0.3s; font-family: 'Inter', sans-serif; }
        .checkin-btn:hover:not(:disabled) { transform: translateY(-2px); }
        .checkin-btn:disabled { background: #ccc; cursor: not-allowed; }
        .queue-result { background: linear-gradient(135deg, var(--primary-blue), var(--light-blue)); color: white; padding: 30px; border-radius: 14px; text-align: center; display: none; margin-top: 18px; }
        .queue-result.show { display: block; animation: bounceIn 0.6s ease-out; }
        @keyframes bounceIn { 0% { transform: scale(0.3); opacity: 0; } 50% { transform: scale(1.05); } 100% { transform: scale(1); opacity: 1; } }
        .qr-title  { font-size: 17px; font-weight: 700; }
        .qr-number { font-family: 'Orbitron', sans-serif; font-size: 60px; font-weight: 900; margin: 12px 0; text-shadow: 0 4px 20px rgba(0,0,0,0.3); }
        .qr-sub    { font-size: 13px; opacity: 0.9; }
        .qr-actions { display: flex; gap: 10px; margin-top: 18px; justify-content: center; }
        .btn-white { padding: 11px 20px; background: white; border: none; border-radius: 10px; font-weight: 700; cursor: pointer; font-family: 'Inter', sans-serif; font-size: 13px; transition: all 0.3s; }
        .btn-white.blue  { color: var(--primary-blue); }
        .btn-white.green { color: #2e7d32; }
        .btn-white:hover { transform: translateY(-2px); }
        .recent-checkins { grid-column: 1 / -1; background: rgba(255,255,255,0.95); padding: 28px; border-radius: 20px; box-shadow: 0 8px 32px rgba(0,0,0,0.2); }
        .recent-checkins h2 { font-family: 'Orbitron', sans-serif; font-size: 17px; color: var(--primary-blue); margin-bottom: 18px; }
        .checkin-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px,1fr)); gap: 12px; }
        .checkin-item { padding: 14px; background: linear-gradient(135deg, #f5f7fa, #e3f2fd); border-radius: 10px; border-left: 4px solid var(--primary-blue); animation: slideIn 0.4s ease-out; }
        .ci-queue { font-family: 'Orbitron', sans-serif; font-size: 20px; font-weight: 800; color: var(--primary-blue); }
        .ci-name  { font-weight: 600; margin: 4px 0; font-size: 13px; }
        .ci-time  { font-size: 11px; color: #666; }
        .empty-checkins { text-align: center; padding: 25px; color: #6b7280; grid-column: 1/-1; font-size: 14px; }
        .spinner { display: inline-block; width: 13px; height: 13px; border: 2px solid rgba(255,255,255,0.3); border-radius: 50%; border-top-color: white; animation: sp 0.8s linear infinite; margin-right: 6px; }
        @keyframes sp { to { transform: rotate(360deg); } }
        @media (max-width: 768px) { .container { grid-template-columns: 1fr; } .service-grid { grid-template-columns: 1fr 1fr; } }
    </style>
</head>
<body>

<div class="header">
    <h1>📡 RFID Check-In Station</h1>
    <a href="staff_dashboard.php" class="back-btn">← Dashboard</a>
</div>

<div class="container">
    <div class="scanner-panel">
        <div class="scanner-status">
            <div class="scanner-icon" id="scannerIcon">🎴</div>
            <div class="status-text waiting" id="statusText">Ready to Scan</div>
            <div class="status-subtext" id="statusSubtext">Tap RFID card or enter UID manually</div>
        </div>
        <div class="manual-input">
            <h3>💻 Manual UID Entry</h3>
            <div class="input-group">
                <input type="text" id="manualUid" placeholder="e.g., A1B2C3D4" maxlength="12">
                <button onclick="simulateScan()">Scan</button>
            </div>
            <div class="demo-hint">💡 <strong>Demo UIDs:</strong> A1B2C3D4 · E5F6G7H8 · I9J0K1L2</div>
        </div>
    </div>

    <div class="info-panel">
        <h2>👤 Student Information</h2>
        <div class="student-card" id="studentCard">
            <div class="field"><div class="label">Matrix Number</div><div class="value matrix" id="matrixNumber">-</div></div>
            <div class="field"><div class="label">Full Name</div><div class="value" id="studentName">-</div></div>
            <div class="field"><div class="label">Faculty</div><div class="value" id="faculty">-</div></div>
            <div class="field"><div class="label">Program</div><div class="value" id="program">-</div></div>
        </div>
        <div class="already-queued" id="alreadyQueued">
            <div class="aq-label">Already in queue today</div>
            <div class="aq-num" id="existingQueueNum">Q---</div>
            <div class="aq-msg">⚠️ This student already has an active queue today.</div>
        </div>
        <div class="service-selection" id="serviceSelection">
            <h3 style="margin-bottom:12px;font-size:14px;font-weight:700">Select Service:</h3>
            <div class="service-grid">
                <div class="service-card" onclick="selectService('General Consultation',this)"><div class="icon">🩺</div><div class="name">General Consultation</div></div>
                <div class="service-card" onclick="selectService('Follow-up Check',this)"><div class="icon">🔄</div><div class="name">Follow-up Check</div></div>
                <div class="service-card" onclick="selectService('Vaccination',this)"><div class="icon">💉</div><div class="name">Vaccination</div></div>
                <div class="service-card" onclick="selectService('Prescription Refill',this)"><div class="icon">💊</div><div class="name">Prescription Refill</div></div>
            </div>
            <button class="checkin-btn" id="checkinBtn" onclick="processCheckin()" disabled>✓ Complete Check-In</button>
        </div>
        <div class="queue-result" id="queueResult">
            <div class="qr-title">✓ Check-In Successful!</div>
            <div class="qr-number" id="queueNumber">Q001</div>
            <div class="qr-sub" id="queueSub">Please wait for your number</div>
            <div class="qr-actions">
                <button class="btn-white green" onclick="printTicket()">🖨️ Print</button>
                <button class="btn-white blue"  onclick="resetScanner()">Next →</button>
            </div>
        </div>
    </div>

    <div class="recent-checkins">
        <h2>📋 Recent Check-Ins Today</h2>
        <div class="checkin-list" id="checkinList"><div class="empty-checkins">Loading...</div></div>
    </div>
</div>

<script>
    let currentStudent = null, selectedService = null, lastQueueData = null;

    function simulateScan() {
        const uid = document.getElementById('manualUid').value.trim().toUpperCase();
        if (!uid) { alert('Please enter an RFID UID'); return; }
        setScanner('waiting', '🔄', 'Scanning...', 'Reading card...');
        setTimeout(() => {
            fetch('process_rfid_scan.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ rfid_uid: uid })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    currentStudent = data.student;
                    setScanner('success', '✓', 'Card Recognized!', 'Student found');
                    document.getElementById('matrixNumber').textContent = data.student.matrix_number;
                    document.getElementById('studentName').textContent  = data.student.full_name;
                    document.getElementById('faculty').textContent      = data.student.faculty  || 'N/A';
                    document.getElementById('program').textContent      = data.student.program  || 'N/A';
                    document.getElementById('studentCard').classList.add('show');
                    if (data.already_queued) {
                        document.getElementById('existingQueueNum').textContent = data.existing_queue;
                        document.getElementById('alreadyQueued').classList.add('show');
                        setScanner('already', '⚠️', 'Already Queued', `Active: ${data.existing_queue}`);
                    } else {
                        document.getElementById('serviceSelection').classList.add('show');
                    }
                } else {
                    setScanner('error', '✕', 'Not Found', data.message || 'Card not registered');
                    setTimeout(resetScanner, 3500);
                }
            })
            .catch(() => { setScanner('error', '✕', 'Error', 'Network error'); setTimeout(resetScanner, 3000); });
        }, 1200);
    }

    function selectService(svc, el) {
        selectedService = svc;
        document.querySelectorAll('.service-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('checkinBtn').disabled = false;
    }

    function processCheckin() {
        if (!currentStudent || !selectedService) return;
        const btn = document.getElementById('checkinBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span>Processing...';
        fetch('process_rfid_checkin.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ matrix_number: currentStudent.matrix_number, service_type: selectedService })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                lastQueueData = data;
                document.getElementById('queueNumber').textContent = data.queue_number;
                document.getElementById('queueSub').textContent    = `${data.service} · ${data.student_name}`;
                document.getElementById('serviceSelection').style.display = 'none';
                document.getElementById('queueResult').classList.add('show');
                setScanner('success', '🎉', 'Done!', `Queue: ${data.queue_number}`);
                addCheckin(data.queue_number, data.student_name, data.service);
            } else {
                alert('Error: ' + data.message);
                btn.disabled = false;
                btn.innerHTML = '✓ Complete Check-In';
            }
        })
        .catch(() => { alert('Network error'); btn.disabled = false; btn.innerHTML = '✓ Complete Check-In'; });
    }

    function printTicket() {
        if (!lastQueueData) return;
        window.open(`print_ticket.php?queue_number=${lastQueueData.queue_number}&matrix_number=${encodeURIComponent(lastQueueData.matrix_number)}&service=${encodeURIComponent(lastQueueData.service)}&student_name=${encodeURIComponent(lastQueueData.student_name)}`, '_blank');
    }

    function resetScanner() {
        currentStudent = selectedService = lastQueueData = null;
        setScanner('waiting', '🎴', 'Ready to Scan', 'Tap RFID card or enter UID manually');
        ['studentCard','serviceSelection','alreadyQueued','queueResult'].forEach(id => {
            document.getElementById(id).classList.remove('show');
        });
        document.getElementById('serviceSelection').style.display = '';
        document.getElementById('manualUid').value = '';
        document.getElementById('checkinBtn').disabled = true;
        document.getElementById('checkinBtn').innerHTML = '✓ Complete Check-In';
        document.querySelectorAll('.service-card').forEach(c => c.classList.remove('selected'));
    }

    function setScanner(state, icon, text, sub) {
        document.getElementById('scannerIcon').className   = `scanner-icon ${state}`;
        document.getElementById('scannerIcon').textContent = icon;
        document.getElementById('statusText').textContent  = text;
        document.getElementById('statusText').className    = `status-text ${state}`;
        document.getElementById('statusSubtext').textContent = sub;
    }

    function addCheckin(q, name, svc) {
        const list = document.getElementById('checkinList');
        const empty = list.querySelector('.empty-checkins');
        if (empty) empty.remove();
        const time = new Date().toLocaleTimeString('en-MY', { hour: '2-digit', minute: '2-digit' });
        const el = document.createElement('div');
        el.className = 'checkin-item';
        el.innerHTML = `<div class="ci-queue">${q}</div><div class="ci-name">${name}</div><div class="ci-time">${svc} · ${time}</div>`;
        list.insertBefore(el, list.firstChild);
    }

    function loadRecentCheckins() {
        fetch('get_recent_checkins.php')
            .then(r => r.json())
            .then(data => {
                const list = document.getElementById('checkinList');
                if (data.success && data.checkins && data.checkins.length > 0) {
                    list.innerHTML = '';
                    data.checkins.forEach(c => {
                        const time = new Date(c.created_at).toLocaleTimeString('en-MY', { hour:'2-digit', minute:'2-digit' });
                        const el = document.createElement('div');
                        el.className = 'checkin-item';
                        el.innerHTML = `<div class="ci-queue">${c.queue_number}</div><div class="ci-name">${c.student_name || '-'}</div><div class="ci-time">${time}</div>`;
                        list.appendChild(el);
                    });
                } else {
                    list.innerHTML = '<div class="empty-checkins">No check-ins today yet.</div>';
                }
            })
            .catch(() => { document.getElementById('checkinList').innerHTML = '<div class="empty-checkins">Could not load.</div>'; });
    }

    document.addEventListener('DOMContentLoaded', loadRecentCheckins);
    document.getElementById('manualUid').addEventListener('input', function() { this.value = this.value.toUpperCase(); });
    document.getElementById('manualUid').addEventListener('keypress', e => { if (e.key === 'Enter') simulateScan(); });
</script>
</body>
</html>