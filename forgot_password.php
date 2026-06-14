<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — PKU UTHM</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        :root{--ink:#0f1923;--muted:#6b7c8d;--border:#e4e9ee;--surface:#f7f9fb;--white:#fff;--blue:#1a56db;--blue-dk:#1446c0;--blue-lt:#eef2ff;--green:#059669;--green-lt:#ecfdf5;--red:#dc2626;--red-lt:#fef2f2;--amber:#d97706;--amber-lt:#fffbeb}
        html,body{height:100%;font-family:'DM Sans',sans-serif;background:var(--surface);color:var(--ink);display:flex;align-items:center;justify-content:center;min-height:100vh}
        .wrap{width:100%;max-width:420px;padding:20px}
        .card{background:var(--white);border:1px solid var(--border);border-radius:16px;padding:40px 36px;box-shadow:0 4px 24px rgba(0,0,0,.06)}
        .logo-wrap{text-align:center;margin-bottom:28px}
        .logo-wrap img{width:80px;height:80px;object-fit:contain;border-radius:14px}
        .card-head{text-align:center;margin-bottom:28px}
        .card-head h1{font-family:'DM Serif Display',serif;font-size:22px;color:var(--ink);margin-bottom:5px}
        .card-head p{font-size:13px;color:var(--muted)}
        .fg{margin-bottom:18px}
        .fg label{display:block;font-size:12px;font-weight:600;color:var(--ink);margin-bottom:7px;text-transform:uppercase;letter-spacing:.4px}
        .input-wrap{position:relative}
        .input-wrap input{width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:9px;font-size:14px;font-family:'DM Sans',sans-serif;color:var(--ink);background:var(--white);transition:border-color .2s}
        .input-wrap input:focus{border-color:var(--blue);outline:none;box-shadow:0 0 0 3px rgba(26,86,219,.08)}
        .btn{width:100%;padding:13px;background:var(--blue);color:white;border:none;border-radius:10px;font-size:14px;font-weight:700;font-family:'DM Sans',sans-serif;cursor:pointer;transition:background .15s,transform .1s;display:flex;align-items:center;justify-content:center;gap:8px}
        .btn:hover:not(:disabled){background:var(--blue-dk);transform:translateY(-1px)}
        .btn:disabled{opacity:.55;cursor:not-allowed;transform:none}
        .msg{padding:11px 14px;border-radius:9px;font-size:13px;font-weight:600;margin-top:14px;display:none;line-height:1.5}
        .msg.success{background:var(--green-lt);color:#065f46;border:1px solid #6ee7b7}
        .msg.error{background:var(--red-lt);color:#991b1b;border:1px solid #fca5a5}
        .msg.loading{background:var(--amber-lt);color:#92400e}
        .back-link{display:block;text-align:center;margin-top:22px;font-size:13px;font-weight:600;color:var(--blue);text-decoration:none}
        .back-link:hover{text-decoration:underline}
        .spinner{display:inline-block;width:13px;height:13px;border:2px solid rgba(255,255,255,.3);border-radius:50%;border-top-color:white;animation:spin .7s linear infinite}
        @keyframes spin{to{transform:rotate(360deg)}}
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <div class="logo-wrap">
            <img src="logo_pku.png" alt="PKU UTHM">
        </div>
        <div class="card-head">
            <h1>Forgot Password</h1>
            <p>Enter your email address and we'll send you a reset link</p>
        </div>

        <div class="fg">
            <label for="email">Email Address</label>
            <div class="input-wrap">
                <input type="email" id="email" placeholder="e.g. staff@uthm.edu.my">
            </div>
        </div>

        <button class="btn" id="send-btn" onclick="sendReset()">
            <span id="btn-text">Send Reset Link</span>
        </button>

        <div class="msg" id="msg"></div>
        <a href="staff_login.php" class="back-link">← Back to Login</a>
    </div>
</div>

<script>
    function sendReset() {
        const email = document.getElementById('email').value.trim();
        if (!email) { showMsg('Please enter your email address.', 'error'); return; }

        const btn  = document.getElementById('send-btn');
        const text = document.getElementById('btn-text');
        btn.disabled = true;
        text.innerHTML = '<span class="spinner"></span>Sending...';
        showMsg('Sending reset link...', 'loading');

        fetch('reset_password.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'send_reset', email})
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showMsg('✓ Reset link sent! Check your email inbox.', 'success');
                btn.disabled = true;
                text.innerHTML = '✓ Email Sent';
            } else {
                showMsg(data.message, 'error');
                btn.disabled = false;
                text.innerHTML = 'Send Reset Link';
            }
        })
        .catch(() => {
            showMsg('Network error. Please try again.', 'error');
            btn.disabled = false;
            text.innerHTML = 'Send Reset Link';
        });
    }

    function showMsg(text, type) {
        const el = document.getElementById('msg');
        el.textContent = text;
        el.className = 'msg ' + type;
        el.style.display = 'block';
    }

    document.getElementById('email').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendReset();
    });
</script>
</body>
</html>