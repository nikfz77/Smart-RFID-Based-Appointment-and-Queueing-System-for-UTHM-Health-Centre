<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password — PKU UTHM</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        :root{--ink:#0f1923;--muted:#6b7c8d;--border:#e4e9ee;--surface:#f7f9fb;--white:#fff;--blue:#1a56db;--blue-dk:#1446c0;--green:#059669;--green-lt:#ecfdf5;--red:#dc2626;--red-lt:#fef2f2;--amber:#d97706;--amber-lt:#fffbeb}
        html,body{height:100%;font-family:'DM Sans',sans-serif;background:var(--surface);color:var(--ink);display:flex;align-items:center;justify-content:center;min-height:100vh}
        .wrap{width:100%;max-width:420px;padding:20px}
        .card{background:var(--white);border:1px solid var(--border);border-radius:16px;padding:40px 36px;box-shadow:0 4px 24px rgba(0,0,0,.06)}
        .logo-wrap{text-align:center;margin-bottom:24px}
        .logo-wrap img{width:80px;height:80px;object-fit:contain;border-radius:14px}
        .card-head{text-align:center;margin-bottom:28px}
        .card-head h1{font-family:'DM Serif Display',serif;font-size:22px;color:var(--ink);margin-bottom:5px}
        .card-head p{font-size:13px;color:var(--muted)}
        .fg{margin-bottom:18px}
        .fg label{display:block;font-size:12px;font-weight:600;color:var(--ink);margin-bottom:7px;text-transform:uppercase;letter-spacing:.4px}
        .input-wrap{position:relative}
        .input-wrap input{width:100%;padding:11px 42px 11px 14px;border:1.5px solid var(--border);border-radius:9px;font-size:14px;font-family:'DM Sans',sans-serif;color:var(--ink);background:var(--white);transition:border-color .2s}
        .input-wrap input:focus{border-color:var(--blue);outline:none;box-shadow:0 0 0 3px rgba(26,86,219,.08)}
        .eye{position:absolute;right:13px;top:50%;transform:translateY(-50%);cursor:pointer;font-size:15px;color:var(--muted);user-select:none}
        .eye:hover{color:var(--blue)}
        .strength-bar{height:3px;background:var(--border);border-radius:2px;margin-top:7px;overflow:hidden}
        .strength-fill{height:100%;width:0%;border-radius:2px;transition:width .3s,background .3s}
        .strength-txt{font-size:11px;color:var(--muted);margin-top:4px}
        .btn{width:100%;padding:13px;background:var(--blue);color:white;border:none;border-radius:10px;font-size:14px;font-weight:700;font-family:'DM Sans',sans-serif;cursor:pointer;transition:background .15s,transform .1s;display:flex;align-items:center;justify-content:center;gap:8px}
        .btn:hover:not(:disabled){background:var(--blue-dk);transform:translateY(-1px)}
        .btn:disabled{opacity:.55;cursor:not-allowed;transform:none}
        .msg{padding:11px 14px;border-radius:9px;font-size:13px;font-weight:600;margin-top:14px;display:none}
        .msg.success{background:var(--green-lt);color:#065f46;border:1px solid #6ee7b7}
        .msg.error{background:var(--red-lt);color:#991b1b;border:1px solid #fca5a5}
        .msg.loading{background:var(--amber-lt);color:#92400e}
        .success-screen{text-align:center;display:none}
        .success-screen .sc-icon{font-size:52px;margin-bottom:14px}
        .success-screen h2{font-family:'DM Serif Display',serif;font-size:22px;color:var(--green);margin-bottom:8px}
        .success-screen p{font-size:13px;color:var(--muted);margin-bottom:22px}
        .back-link{display:block;text-align:center;margin-top:18px;font-size:13px;font-weight:600;color:var(--blue);text-decoration:none}
        .back-link:hover{text-decoration:underline}
        .invalid-token{text-align:center;padding:20px 0}
        .invalid-token .icon{font-size:48px;margin-bottom:14px}
        .invalid-token h2{font-size:18px;font-weight:700;color:var(--red);margin-bottom:8px}
        .invalid-token p{font-size:13px;color:var(--muted);margin-bottom:20px}
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

        <!-- Loading state -->
        <div id="loading-state" style="text-align:center;padding:20px 0">
            <div class="spinner" style="border-top-color:var(--blue);border-color:var(--border);width:28px;height:28px;margin:0 auto 14px"></div>
            <p style="font-size:13px;color:var(--muted)">Verifying reset link...</p>
        </div>

        <!-- Invalid token -->
        <div class="invalid-token" id="invalid-state" style="display:none">
            <div class="icon">❌</div>
            <h2>Link Expired</h2>
            <p>This reset link is invalid or has expired. Please request a new one.</p>
            <a href="forgot_password.php" class="btn" style="text-decoration:none;display:inline-flex;width:auto;padding:11px 24px">Request New Link</a>
        </div>

        <!-- Reset form -->
        <div id="form-state" style="display:none">
            <div class="card-head">
                <h1>Set New Password</h1>
                <p id="welcome-text">Choose a strong new password</p>
            </div>

            <div class="fg">
                <label>New Password</label>
                <div class="input-wrap">
                    <input type="password" id="new_password" placeholder="Min 6 characters" oninput="checkStrength(this.value)">
                    <span class="eye" onclick="togglePass('new_password',this)">👁️</span>
                </div>
                <div class="strength-bar"><div class="strength-fill" id="sfill"></div></div>
                <div class="strength-txt" id="stxt">Enter a password</div>
            </div>
            <div class="fg">
                <label>Confirm Password</label>
                <div class="input-wrap">
                    <input type="password" id="confirm_password" placeholder="Re-enter new password">
                    <span class="eye" onclick="togglePass('confirm_password',this)">👁️</span>
                </div>
            </div>

            <button class="btn" id="reset-btn" onclick="resetPassword()">
                <span id="reset-text">Set New Password</span>
            </button>
            <div class="msg" id="msg"></div>
        </div>

        <!-- Success -->
        <div class="success-screen" id="success-state">
            <div class="sc-icon">✅</div>
            <h2>Password Updated!</h2>
            <p>Your password has been reset successfully. You can now log in.</p>
            <a href="staff_login.php" class="btn" style="text-decoration:none">Go to Login</a>
        </div>
    </div>
</div>

<script>
    const token = new URLSearchParams(window.location.search).get('token');

    // Verify token on load
    if (!token) {
        document.getElementById('loading-state').style.display = 'none';
        document.getElementById('invalid-state').style.display = 'block';
    } else {
        fetch('reset_password.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'verify_token', token})
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('loading-state').style.display = 'none';
            if (data.success) {
                document.getElementById('welcome-text').textContent = 'Hi ' + data.full_name + '! Choose a strong password.';
                document.getElementById('form-state').style.display = 'block';
            } else {
                document.getElementById('invalid-state').style.display = 'block';
            }
        })
        .catch(() => {
            document.getElementById('loading-state').style.display = 'none';
            document.getElementById('invalid-state').style.display = 'block';
        });
    }

    function togglePass(id, el) {
        const input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
        el.textContent = input.type === 'password' ? '👁️' : '🙈';
    }

    function checkStrength(val) {
        const fill = document.getElementById('sfill');
        const txt  = document.getElementById('stxt');
        let score = 0;
        if (val.length >= 6)  score++;
        if (val.length >= 10) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;
        const levels = [
            {p:'0%',  c:'#e4e9ee', l:'Enter a password'},
            {p:'25%', c:'#dc2626', l:'Too weak'},
            {p:'50%', c:'#d97706', l:'Weak'},
            {p:'75%', c:'#ca8a04', l:'Fair'},
            {p:'90%', c:'#16a34a', l:'Strong'},
            {p:'100%',c:'#15803d', l:'Very strong'},
        ];
        const lv = levels[score]||levels[0];
        fill.style.width = lv.p;
        fill.style.background = lv.c;
        txt.textContent = lv.l;
    }

    function showMsg(text, type) {
        const el = document.getElementById('msg');
        el.textContent = text;
        el.className = 'msg ' + type;
        el.style.display = 'block';
    }

    function resetPassword() {
        const newPass = document.getElementById('new_password').value;
        const confirm = document.getElementById('confirm_password').value;
        if (newPass.length < 6) { showMsg('Password must be at least 6 characters.', 'error'); return; }
        if (newPass !== confirm) { showMsg('Passwords do not match.', 'error'); return; }

        const btn  = document.getElementById('reset-btn');
        const text = document.getElementById('reset-text');
        btn.disabled = true;
        text.innerHTML = '<span class="spinner"></span>Updating...';
        showMsg('Updating your password...', 'loading');

        fetch('reset_password.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'reset', token, new_password: newPass})
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('form-state').style.display = 'none';
                document.getElementById('msg').style.display = 'none';
                document.getElementById('success-state').style.display = 'block';
            } else {
                showMsg(data.message, 'error');
                btn.disabled = false;
                text.textContent = 'Set New Password';
            }
        })
        .catch(() => {
            showMsg('Network error. Please try again.', 'error');
            btn.disabled = false;
            text.textContent = 'Set New Password';
        });
    }
</script>
</body>
</html>