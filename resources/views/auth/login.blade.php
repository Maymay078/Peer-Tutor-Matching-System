<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login - Peer Tutor Matching System</title>
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .close-button {
            position: fixed;
            top: 35px;
            right: 70px;
            width: 45px;
            height: 45px;
            background: rgba(19, 17, 17, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: black;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            z-index: 3000;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .close-button:hover {
            background: rgba(14, 44, 214, 0.25);
            transform: scale(1.1);
            border-color: rgba(8, 8, 8, 0.3);
        }

        .close-button svg {
            stroke: black;
            stroke-width: 2.5;
        }
        
        .login-wrapper {
            position: relative;
            display: flex;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            max-width: 100%;
            width: 96%;
            min-height: 900px;
        }
        
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>') repeat;
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
        }
        
        .logo-container {
            position: relative;
            z-index: 2;
            margin-bottom: 30px;
        }
        
        .custom-logo {
            width: 200px;
            height: 200px;
            margin: 0 auto 20px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .logo-svg {
            width: 150px;
            height: 150px;
        }
        
        .welcome-text {
            position: relative;
            z-index: 2;
        }
        
        .welcome-text h1 {
            font-size: 4.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .welcome-text p {
            font-size: 2rem;
            opacity: 0.9;
            line-height: 1.6;
            max-width: 100%;
        }
        
        .login-right {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .login-form h2 {
            font-size: 3rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 10px;
        }
        
        .login-form .subtitle {
            color: #6b7280;
            margin-bottom: 40px;
            font-size: 2rem;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
            font-size: 1.5rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 15px 18px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1.5rem;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #4f46e5;
            background: white;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        .forgot-password {
            text-align: right;
            margin-bottom: 30px;
        }
        
        .forgot-password a {
            color: #4f46e5;
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .forgot-password a:hover {
            color: #3730a3;
            text-decoration: underline;
        }
        
        .login-btn {
            width: 100%;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 12px;
            font-size: 1.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
        }
        
        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
        }
        
        .login-btn:active {
            transform: translateY(0);
        }
        
        .signup-link {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 1.2rem;
        }
        
        .signup-link a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .signup-link a:hover {
            color: #3730a3;
            text-decoration: underline;
        }
        
        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 1.2rem;
        }
        
        @media (max-width: 768px) {
            .login-wrapper {
                flex-direction: column;
                margin: 10px;
                border-radius: 15px;
            }
            
            .login-left {
                padding: 40px 30px;
                min-height: 300px;
            }
            
            .login-right {
                padding: 40px 30px;
            }
            
            .welcome-text h1 {
                font-size: 2rem;
            }
            
            .custom-logo {
                width: 100px;
                height: 100px;
            }
            
            .logo-svg {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <a href="{{ route('welcome') }}" class="close-button">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
    </a>
    <div class="login-wrapper">
        <!-- Left Side - Welcome Section -->
        <div class="login-left">
            <div class="logo-container">
                <div class="custom-logo">
                    <!-- Custom SVG Logo -->
                    <svg class="logo-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                        <!-- Graduation Cap -->
                        <path d="M50 15 L85 25 L50 35 L15 25 Z" fill="#2563eb" stroke="#1d4ed8" stroke-width="1"/>
                        <path d="M50 35 L50 45 L85 35 L85 25 Z" fill="#1d4ed8"/>
                        <circle cx="85" cy="25" r="3" fill="#dc2626"/>
                        
                        <!-- Book -->
                        <rect x="25" y="45" width="50" height="35" rx="3" fill="#3b82f6" stroke="#2563eb" stroke-width="1"/>
                        <rect x="25" y="45" width="25" height="35" rx="3" fill="#60a5fa"/>
                        <line x1="35" y1="52" x2="65" y2="52" stroke="white" stroke-width="1"/>
                        <line x1="35" y1="58" x2="65" y2="58" stroke="white" stroke-width="1"/>
                        <line x1="35" y1="64" x2="65" y2="64" stroke="white" stroke-width="1"/>
                        <line x1="35" y1="70" x2="60" y2="70" stroke="white" stroke-width="1"/>
                        
                        <!-- Decorative elements -->
                        <circle cx="20" cy="30" r="2" fill="#fbbf24" opacity="0.7"/>
                        <circle cx="80" cy="50" r="1.5" fill="#fbbf24" opacity="0.7"/>
                        <circle cx="15" cy="60" r="1" fill="#fbbf24" opacity="0.7"/>
                    </svg>
                </div>
                <div class="welcome-text">
                    <h1>Welcome Back!</h1>
                    <p>Connect with peers, share knowledge, and enhance your learning journey together.</p>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="login-right">
            <div class="login-form">
                <h2>Sign In</h2>
                <p class="subtitle">Access your Peer Tutor account</p>
                
                <!-- Display validation errors -->
                @if ($errors->any())
                    <div class="error-message">
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif

                <!-- Display success message -->
                @if (session('success'))
                    <div class="success-message" style="background: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem;">
                        {{ session('success') }} <a href="{{ route('login') }}" style="color: #065f46; font-weight: 600; text-decoration: underline;">Go to Login</a>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required 
                            autofocus 
                            placeholder="Enter your email"
                        />
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            placeholder="Enter your password"
                        />
                    </div>
                    
                    <div class="forgot-password">
                        <a href="{{ route('password.request') }}">Forgot your password?</a>
                    </div>
                    
                    <button type="submit" class="login-btn">Sign In</button>
                </form>
                
                <div class="signup-link">
                    Don't have an account? <a href="#" id="registerDialogLink">Create one here</a>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const registerLink = document.getElementById('registerDialogLink');
        if (!registerLink) return;
        registerLink.addEventListener('click', function(e) {
            e.preventDefault();

            // Overlay for background dim
            const overlay = document.createElement('div');
            overlay.style.position = 'fixed';
            overlay.style.top = 0;
            overlay.style.left = 0;
            overlay.style.width = '100vw';
            overlay.style.height = '100vh';
            overlay.style.background = 'rgba(31, 41, 55, 0.45)';
            overlay.style.zIndex = 9998;
            overlay.style.display = 'flex';
            overlay.style.alignItems = 'center';
            overlay.style.justifyContent = 'center';

            // Dialog
            const dialog = document.createElement('div');
            dialog.style.background = 'white';
            dialog.style.borderRadius = '22px';
            dialog.style.boxShadow = '0 12px 48px rgba(79,70,229,0.18), 0 2px 8px rgba(0,0,0,0.08)';
            dialog.style.padding = '3rem 2.8rem 2.2rem 2.8rem';
            dialog.style.maxWidth = '440px';
            dialog.style.width = '98vw';
            dialog.style.display = 'flex';
            dialog.style.flexDirection = 'column';
            dialog.style.alignItems = 'center';
            dialog.style.position = 'relative';
            dialog.style.zIndex = 9999;
            dialog.style.border = '1.5px solid #e0e7ff';
            dialog.style.transition = 'box-shadow 0.2s';

            dialog.innerHTML = `
                <div style="display:flex;align-items:center;gap:0.7rem;margin-bottom:0.5rem;">
                    <span style="font-size:2.1rem;">✨</span>
                    <h2 style="font-size:1.7rem;font-weight:800;color:#4f46e5;letter-spacing:-1px;">Welcome!</h2>
                </div>
                <p style="font-size:1.13rem;color:#374151;margin-bottom:1.7rem;text-align:justify;line-height:1.6;">
                    <span style="font-size:1.05rem;color:#6366f1;font-weight:600;">Peer Tutor Matching System</span><br>
                    Please select your registration type to get started. <b>Student</b> to find tutors, or <b>Tutor</b> to offer your expertise.
                </p>
                <div style="display:flex;gap:2.2rem;margin-bottom:1.7rem;">
                    <button id="studentBtn" type="button" style="display:flex;flex-direction:column;align-items:center;gap:0.6rem;padding:1.3rem 1.7rem;background:#eef2ff;border:2.5px solid #6366f1;color:#4f46e5;border-radius:14px;font-weight:700;cursor:pointer;transition:background 0.18s, color 0.18s, box-shadow 0.18s;box-shadow:0 2px 8px rgba(99,102,241,0.08);font-size:1.09rem;">
                        <span style="font-size:2.5rem;">🎓</span>
                        Student
                    </button>
                    <button id="tutorBtn" type="button" style="display:flex;flex-direction:column;align-items:center;gap:0.6rem;padding:1.3rem 1.7rem;background:#ecfdf5;border:2.5px solid #10b981;color:#059669;border-radius:14px;font-weight:700;cursor:pointer;transition:background 0.18s, color 0.18s, box-shadow 0.18s;box-shadow:0 2px 8px rgba(16,185,129,0.08);font-size:1.09rem;">
                        <span style="font-size:2.5rem;">👨‍🏫</span>
                        Tutor
                    </button>
                </div>
                <button id="cancelBtn" type="button" style="margin-top:0.5rem;background:none;border:none;color:#6b7280;text-decoration:underline;cursor:pointer;font-size:1.07rem;">Cancel</button>
            `;

            // Center dialog in overlay
            overlay.appendChild(dialog);
            document.body.appendChild(overlay);

            // Animate dialog in
            dialog.animate([{ opacity: 0, transform: 'scale(0.95)' }, { opacity: 1, transform: 'scale(1)' }], { duration: 180, fill: 'forwards' });

            // Button handlers
            dialog.querySelector('#studentBtn').onclick = function() {
                window.location.href = "{{ route('register.student.form') }}";
                document.body.removeChild(overlay);
            };
            dialog.querySelector('#tutorBtn').onclick = function() {
                window.location.href = "{{ route('register.tutor.form') }}";
                document.body.removeChild(overlay);
            };
            dialog.querySelector('#cancelBtn').onclick = function() {
                document.body.removeChild(overlay);
            };
            // Close on overlay click (but not dialog click)
            overlay.onclick = function(ev) {
                if (ev.target === overlay) document.body.removeChild(overlay);
            };
            // ESC key closes
            document.addEventListener('keydown', function escHandler(ev) {
                if (ev.key === 'Escape') {
                    if (document.body.contains(overlay)) document.body.removeChild(overlay);
                    document.removeEventListener('keydown', escHandler);
                }
            });
        });
    });
    </script>
</body>
</html>
