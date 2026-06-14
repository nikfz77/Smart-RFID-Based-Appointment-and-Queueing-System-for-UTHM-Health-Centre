<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PKU UTHM — Staff Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        *,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
        :root{--blue:#1d5bb5;--blue-dark:#1446c0;--blue-900:#0c2461;--text:#111827;--muted:#6b7280;--border:#e5e7eb;--white:#ffffff;--surface:#f8fafc}
        html,body{height:100%;font-family:'Plus Jakarta Sans',sans-serif;background:var(--white);color:var(--text)}
        .page{display:grid;grid-template-columns:1.4fr 420px;min-height:100vh}

        /* ── LEFT PANEL ── */
        .left{display:flex;flex-direction:column;justify-content:space-between;padding:60px 72px;position:relative;overflow:hidden}
        .slide{position:absolute;inset:0;background-size:cover;background-position:center;transition:opacity 1.2s ease-in-out;opacity:0;z-index:0}
        .slide.active{opacity:1}
        .slide-1{background-image:url('bangunan PTTA.jpg')}
        .slide-2{background-image:url('pusat kesihatan.jpg')}
        .left-overlay{position:absolute;inset:0;background:linear-gradient(145deg,rgba(8,22,80,0.86) 0%,rgba(8,20,70,0.78) 100%);z-index:1}
        .slide-dots{position:absolute;bottom:30px;left:72px;display:flex;gap:8px;z-index:3}
        .slide-dot{width:8px;height:8px;border-radius:50%;background:rgba(255,255,255,0.35);transition:all .4s ease;cursor:pointer}
        .slide-dot.active{background:white;width:24px;border-radius:4px}
        .slide-caption{position:absolute;bottom:70px;left:72px;font-size:12px;color:rgba(255,255,255,0.5);z-index:3;font-weight:500;transition:opacity .5s ease}
        .left-inner{position:relative;z-index:2}
        .brand{display:flex;align-items:center;gap:14px;margin-bottom:72px}
        .brand-icon{width:46px;height:46px;background:rgba(255,255,255,0.12);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;border:1px solid rgba(255,255,255,0.18);color:white}
        .brand-name{font-size:17px;font-weight:800;color:white;letter-spacing:-.3px}
        .brand-sub{font-size:12px;color:rgba(255,255,255,0.5);margin-top:1px}
        .left-heading{font-size:52px;line-height:1.08;color:white;margin-bottom:22px;letter-spacing:-2px;font-weight:800}
        .left-heading em{font-style:italic;color:rgba(255,255,255,0.55);font-weight:400}
        .left-desc{font-size:15px;line-height:1.75;color:rgba(255,255,255,0.58);max-width:360px;margin-bottom:52px}
        .features{display:flex;flex-direction:column;gap:16px}
        .feature{display:flex;align-items:center;gap:14px}
        .feature-dot{width:38px;height:38px;border-radius:10px;background:rgba(255,255,255,0.09);border:1px solid rgba(255,255,255,0.14);display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;color:rgba(255,255,255,0.85)}
        .feature-text{font-size:14px;color:rgba(255,255,255,0.72);font-weight:500}
        .left-footer{position:relative;z-index:2;font-size:12px;color:rgba(255,255,255,0.25)}

        /* ── RIGHT PANEL ── */
        .right{background:var(--white);display:flex;flex-direction:column;justify-content:center;padding:52px 44px;border-left:1px solid var(--border)}
        .logo-wrap{text-align:center;margin-bottom:28px}
        .logo-wrap img{width:240px;height:auto;object-fit:contain;border-radius:16px}
        .right-header{margin-bottom:32px;text-align:center}
        .right-header h2{font-size:22px;font-weight:800;letter-spacing:-.5px;margin-bottom:5px;color:var(--text)}
        .right-header p{font-size:14px;color:var(--muted)}
        .form-group{margin-bottom:16px}
        .form-group label{display:block;font-size:13px;font-weight:700;color:var(--text);margin-bottom:7px}
        .input-wrap{position:relative}
        .input-wrap input{width:100%;padding:13px 44px 13px 16px;border:1.5px solid var(--border);border-radius:10px;font-size:15px;font-family:'Plus Jakarta Sans',sans-serif;color:var(--text);background:var(--white);transition:border-color .2s,box-shadow .2s;outline:none}
        .input-wrap input:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(29,91,181,0.1)}
        .input-wrap input::placeholder{color:#b0b7c3}
        .input-icon{position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#c0c7d4;font-size:15px;cursor:pointer;user-select:none;transition:color .2s}
        .input-icon:hover{color:var(--blue)}
        .forgot-row{display:flex;justify-content:flex-end;margin-top:-6px;margin-bottom:22px}
        .forgot-link{font-size:13px;font-weight:600;color:var(--blue);text-decoration:none}
        .forgot-link:hover{text-decoration:underline}
        .btn-login{width:100%;padding:14px;background:var(--blue);color:white;border:none;border-radius:10px;font-size:15px;font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;cursor:pointer;transition:background .2s,transform .15s,box-shadow .2s;letter-spacing:.1px}
        .btn-login:hover:not(:disabled){background:var(--blue-dark);box-shadow:0 4px 18px rgba(29,91,181,0.32);transform:translateY(-1px)}
        .btn-login:active{transform:translateY(0)}
        .btn-login:disabled{opacity:.6;cursor:not-allowed}
        #login-message{margin-top:14px;padding:11px 15px;border-radius:10px;font-size:13px;font-weight:600;display:none;animation:msgIn .3s ease-out}
        @keyframes msgIn{from{opacity:0;transform:translateY(-5px)}to{opacity:1;transform:translateY(0)}}
        #login-message.success{background:#ecfdf5;color:#065f46;border:1px solid #6ee7b7}
        #login-message.error{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5}
        .spinner{display:inline-block;width:13px;height:13px;border:2px solid rgba(255,255,255,.3);border-radius:50%;border-top-color:white;animation:spin .7s linear infinite;margin-right:7px;vertical-align:middle}
        @keyframes spin{to{transform:rotate(360deg)}}
        .right-footer{margin-top:28px;font-size:12px;color:#b0b7c3;text-align:center}

        @media(max-width:820px){
            .page{grid-template-columns:1fr}
            .left{padding:40px 28px;min-height:240px}
            .left-heading{font-size:34px}
            .features,.left-desc{display:none}
            .right{padding:36px 24px;border-left:none}
        }
    </style>
</head>
<body>
<div class="page">

    <!-- LEFT — Background slideshow with branding -->
    <div class="left">
        <div class="slide slide-1 active"></div>
        <div class="slide slide-2"></div>
        <div class="left-overlay"></div>
        <div class="slide-dots">
            <div class="slide-dot active" onclick="goToSlide(0)"></div>
            <div class="slide-dot" onclick="goToSlide(1)"></div>
        </div>
        <div class="slide-caption" id="slide-caption">UTHM Main Campus</div>

        <div class="left-inner">
            <div class="brand">
                <div class="brand-icon"><i class="fa-solid fa-house-medical"></i></div>
                <div>
                    <div class="brand-name">PKU UTHM</div>
                    <div class="brand-sub">Pusat Kesihatan Universiti</div>
                </div>
            </div>
            <h1 class="left-heading">Smart Queue<br><em>Management</em><br>System.</h1>
            <p class="left-desc">Manage patient queues, appointments, and RFID check-ins — all in one place. Built for PKU UTHM staff.</p>
            <div class="features">
                <div class="feature"><div class="feature-dot"><i class="fa-solid fa-wifi"></i></div><div class="feature-text">RFID card-based automatic check-in</div></div>
                <div class="feature"><div class="feature-dot"><i class="fa-solid fa-calendar-days"></i></div><div class="feature-text">Appointment booking & management</div></div>
                <div class="feature"><div class="feature-dot"><i class="fa-solid fa-chart-bar"></i></div><div class="feature-text">Real-time queue analytics & reports</div></div>
                <div class="feature"><div class="feature-dot"><i class="fa-solid fa-bell"></i></div><div class="feature-text">Live notifications for new walk-ins</div></div>
            </div>
        </div>
        <div class="left-footer">© <?php echo date('Y'); ?> Universiti Tun Hussein Onn Malaysia</div>
    </div>

    <!-- RIGHT — Login form -->
    <div class="right">
        <div class="logo-wrap">
            <img src="logo_pku.png" alt="PKU UTHM Logo">
        </div>

        <div class="right-header">
            <h2>Log in to your account</h2>
            <p>Enter your staff credentials to continue</p>
        </div>

        <form id="loginForm">
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrap">
                    <input type="text" id="username" placeholder="Enter your username" required autocomplete="username">
                    <span class="input-icon"><i class="fa-solid fa-user"></i></span>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <input type="password" id="password" placeholder="Enter your password" required autocomplete="current-password">
                    <span class="input-icon" id="toggleIcon" onclick="togglePassword()" title="Show/hide"><i class="fa-solid fa-eye"></i></span>
                </div>
            </div>
            <div class="forgot-row">
                <a href="forgot_password.php" class="forgot-link">Forgot password?</a>
            </div>
            <button type="submit" class="btn-login" id="loginBtn">
                <span id="btnText">Log in</span>
            </button>
            <div id="login-message"></div>
        </form>

        <div class="right-footer">PKU UTHM Queue System · Staff Portal</div>
    </div>
</div>

<script>
function togglePassword(){
    const input=document.getElementById('password'),icon=document.getElementById('toggleIcon');
    input.type=input.type==='password'?'text':'password';
    icon.innerHTML=input.type==='password'?'<i class="fa-solid fa-eye"></i>':'<i class="fa-solid fa-eye-slash"></i>';
}

document.getElementById('loginForm').addEventListener('submit',function(e){
    e.preventDefault();
    const msgEl=document.getElementById('login-message'),btn=document.getElementById('loginBtn'),btnText=document.getElementById('btnText');
    msgEl.style.display='none';btn.disabled=true;
    btnText.innerHTML='<span class="spinner"></span>Logging in...';
    fetch('check_staff_login.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({username:document.getElementById('username').value.trim(),password:document.getElementById('password').value})})
    .then(r=>r.json()).then(result=>{
        msgEl.textContent=result.message;msgEl.style.display='block';
        if(result.success){msgEl.className='success';btnText.innerHTML='<i class="fa-solid fa-check"></i> Redirecting...';setTimeout(()=>window.location.href=result.redirect,900);}
        else{msgEl.className='error';btn.disabled=false;btnText.textContent='Log in';}
    }).catch(()=>{
        msgEl.innerHTML='<i class="fa-solid fa-triangle-exclamation"></i> Network error. Please try again.';
        msgEl.className='error';msgEl.style.display='block';btn.disabled=false;btnText.textContent='Log in';
    });
});

// Slideshow
const slides=document.querySelectorAll('.slide'),dots=document.querySelectorAll('.slide-dot');
const captions=['UTHM Main Campus','PKU UTHM Health Centre'];
const captionEl=document.getElementById('slide-caption');
let current=0,timer;
function goToSlide(index){
    slides[current].classList.remove('active');dots[current].classList.remove('active');
    current=index;slides[current].classList.add('active');dots[current].classList.add('active');
    captionEl.style.opacity='0';
    setTimeout(()=>{captionEl.textContent=captions[current];captionEl.style.opacity='1';},400);
    resetTimer();
}
function nextSlide(){goToSlide((current+1)%slides.length);}
function resetTimer(){clearInterval(timer);timer=setInterval(nextSlide,5000);}
timer=setInterval(nextSlide,5000);
</script>
</body>
</html>