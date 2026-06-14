<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Account - PKU UTHM Queue Control System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .register-container {
            background: #f5f5f5;
            padding: 50px 40px;
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 580px;
            animation: fadeInUp 0.6s ease-out;
            max-height: 90vh;
            overflow-y: auto;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .register-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 25px;
            position: relative;
        }

        .register-icon svg {
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));
        }

        .register-header h1 {
            color: #333;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .register-header p {
            color: #666;
            font-size: 15px;
            line-height: 1.6;
            max-width: 450px;
            margin: 0 auto;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            display: none;
            animation: slideIn 0.4s ease-out;
            border-left: 4px solid;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-error {
            background: #fee;
            color: #c33;
            border-left-color: #c33;
        }

        .alert-success {
            background: #e8f5e9;
            color: #2e7d32;
            border-left-color: #4caf50;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .input-group label .required {
            color: #c33;
            margin-left: 3px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            color: #667eea;
            font-size: 18px;
            z-index: 1;
        }

        .input-group input,
        .input-group select {
            width: 100%;
            padding: 14px 20px 14px 55px;
            border: 2px solid #ddd;
            border-radius: 12px;
            font-size: 15px;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            outline: none;
            background: white;
        }

        .input-group select {
            padding-left: 55px;
            cursor: pointer;
        }

        .input-group input:focus,
        .input-group select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .toggle-password {
            position: absolute;
            right: 18px;
            cursor: pointer;
            color: #666;
            font-size: 18px;
            user-select: none;
            transition: color 0.3s ease;
            z-index: 1;
        }

        .toggle-password:hover {
            color: #667eea;
        }

        .password-requirements {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }

        .password-requirements h3 {
            color: #333;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .requirement {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            margin-bottom: 6px;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .requirement:last-child {
            margin-bottom: 0;
        }

        .requirement.met {
            color: #4caf50;
            font-weight: 600;
        }

        .requirement .icon {
            font-size: 14px;
            min-width: 14px;
        }

        .register-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            font-family: 'Poppins', sans-serif;
        }

        .register-btn:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .register-btn:active {
            transform: translateY(-1px);
        }

        .register-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }

        .back-to-login a {
            color: #667eea;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }

        .back-to-login a:hover {
            color: #764ba2;
        }

        .footer {
            text-align: center;
            margin-top: 25px;
            color: #666;
            font-size: 14px;
        }

        .spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 40px 25px;
            }

            .register-header h1 {
                font-size: 26px;
            }
        }

        /* Custom scrollbar */
        .register-container::-webkit-scrollbar {
            width: 8px;
        }

        .register-container::-webkit-scrollbar-track {
            background: #e0e0e0;
            border-radius: 10px;
        }

        .register-container::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <div class="register-icon">
                <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                    <!-- User icon circle -->
                    <circle cx="32" cy="32" r="30" fill="url(#userGradient)"/>
                    
                    <!-- Head -->
                    <circle cx="32" cy="24" r="10" fill="white"/>
                    
                    <!-- Body -->
                    <path d="M 12 54 Q 12 40 32 40 Q 52 40 52 54" fill="white"/>
                    
                    <!-- Plus sign -->
                    <circle cx="48" cy="48" r="12" fill="#4CAF50"/>
                    <line x1="48" y1="42" x2="48" y2="54" stroke="white" stroke-width="3" stroke-linecap="round"/>
                    <line x1="42" y1="48" x2="54" y2="48" stroke="white" stroke-width="3" stroke-linecap="round"/>
                    
                    <defs>
                        <linearGradient id="userGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color:#667eea;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#764ba2;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                </svg>
            </div>
            <h1>Register Account</h1>
            <p>Create your staff account to access the PKU UTHM Queue Control System.</p>
        </div>

        <div id="alertBox" class="alert"></div>

        <form id="registerForm">
            <div class="input-group">
                <label for="fullName">Full Name <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">👤</span>
                    <input 
                        type="text" 
                        id="fullName" 
                        name="fullName" 
                        placeholder="Enter your full name"
                        required
                    >
                </div>
            </div>

            <div class="input-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">📧</span>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="your.email@uthm.edu.my"
                        required
                    >
                </div>
            </div>

            <div class="input-group">
                <label for="username">Username <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">🔤</span>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        placeholder="Choose a username"
                        required
                    >
                </div>
            </div>

            <div class="input-group">
                <label for="role">Role <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">👔</span>
                    <select id="role" name="role" required>
                        <option value="">Select your role</option>
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                        <option value="doctor">Doctor</option>
                        <option value="nurse">Nurse</option>
                    </select>
                </div>
            </div>

            <div class="input-group">
                <label for="password">Password <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">🔒</span>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Create a strong password"
                        required
                    >
                    <span class="toggle-password" onclick="togglePassword('password', this)">👁️</span>
                </div>
            </div>

            <div class="input-group">
                <label for="confirmPassword">Confirm Password <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">🔒</span>
                    <input 
                        type="password" 
                        id="confirmPassword" 
                        name="confirmPassword" 
                        placeholder="Re-enter your password"
                        required
                    >
                    <span class="toggle-password" onclick="togglePassword('confirmPassword', this)">👁️</span>
                </div>
            </div>

            <div class="password-requirements">
                <h3>Password Requirements:</h3>
                <div class="requirement" id="req-length">
                    <span class="icon">○</span>
                    <span>At least 8 characters</span>
                </div>
                <div class="requirement" id="req-uppercase">
                    <span class="icon">○</span>
                    <span>One uppercase letter</span>
                </div>
                <div class="requirement" id="req-lowercase">
                    <span class="icon">○</span>
                    <span>One lowercase letter</span>
                </div>
                <div class="requirement" id="req-number">
                    <span class="icon">○</span>
                    <span>One number</span>
                </div>
                <div class="requirement" id="req-match">
                    <span class="icon">○</span>
                    <span>Passwords match</span>
                </div>
            </div>

            <button type="submit" class="register-btn" id="registerBtn" disabled>
                <span>✓</span>
                <span>Create Account</span>
            </button>
        </form>

        <div class="back-to-login">
            <a href="staff_login.php">
                <span>←</span>
                <span>Already have an account? Login</span>
            </a>
        </div>

        <div class="footer">
            <p>© 2024 UTHM Pusat Kesihatan Universiti</p>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const registerBtn = document.getElementById('registerBtn');

        // Toggle password visibility
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = '🙈';
            } else {
                input.type = 'password';
                icon.textContent = '👁️';
            }
        }

        // Show alert message
        function showAlert(message, type = 'error') {
            const alertBox = document.getElementById('alertBox');
            alertBox.textContent = message;
            alertBox.className = `alert alert-${type}`;
            alertBox.style.display = 'block';
            
            setTimeout(() => {
                alertBox.style.display = 'none';
            }, 5000);
        }

        // Validate password requirements
        function validatePassword() {
            const password = passwordInput.value;
            const confirmPassword = confirmPasswordInput.value;
            
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                match: password === confirmPassword && password.length > 0
            };

            // Update requirement indicators
            updateRequirement('req-length', requirements.length);
            updateRequirement('req-uppercase', requirements.uppercase);
            updateRequirement('req-lowercase', requirements.lowercase);
            updateRequirement('req-number', requirements.number);
            updateRequirement('req-match', requirements.match);

            // Check if all requirements are met
            const allMet = Object.values(requirements).every(req => req === true);
            
            // Enable/disable submit button
            registerBtn.disabled = !allMet;

            return allMet;
        }

        function updateRequirement(id, met) {
            const element = document.getElementById(id);
            const icon = element.querySelector('.icon');
            
            if (met) {
                element.classList.add('met');
                icon.textContent = '✓';
            } else {
                element.classList.remove('met');
                icon.textContent = '○';
            }
        }

        // Add event listeners for real-time validation
        passwordInput.addEventListener('input', validatePassword);
        confirmPasswordInput.addEventListener('input', validatePassword);

        // Handle form submission
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!validatePassword()) {
                showAlert('Please meet all password requirements.', 'error');
                return;
            }

            const formData = {
                fullName: document.getElementById('fullName').value.trim(),
                email: document.getElementById('email').value.trim(),
                username: document.getElementById('username').value.trim(),
                role: document.getElementById('role').value,
                password: passwordInput.value
            };

            // Validate all fields
            if (!formData.fullName || !formData.email || !formData.username || !formData.role) {
                showAlert('Please fill in all required fields.', 'error');
                return;
            }

            // Disable button
            registerBtn.disabled = true;
            registerBtn.innerHTML = '<span class="spinner"></span><span>Creating account...</span>';

            try {
                const response = await fetch('register_handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(formData)
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('Account created successfully! Redirecting to login...', 'success');
                    setTimeout(() => {
                        window.location.href = 'staff_login.php?registered=success';
                    }, 2000);
                } else {
                    showAlert(data.message || 'Registration failed. Please try again.', 'error');
                    registerBtn.disabled = false;
                    registerBtn.innerHTML = '<span>✓</span><span>Create Account</span>';
                }
            } catch (error) {
                showAlert('Unable to connect to server. Please try again later.', 'error');
                registerBtn.disabled = false;
                registerBtn.innerHTML = '<span>✓</span><span>Create Account</span>';
            }
        });
    </script>
</body>
</html>