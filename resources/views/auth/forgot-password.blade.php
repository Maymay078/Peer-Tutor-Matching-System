<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Forgot Password - Peer Tutor Matching System</title>
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
        
        .forgot-password-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            max-width: 500px;
            width: 100%;
            padding: 60px 50px;
            text-align: center;
        }
        
        .logo-container {
            margin-bottom: 40px;
        }
        
        .custom-logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(79, 70, 229, 0.3);
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .logo-svg {
            width: 60px;
            height: 60px;
        }
        
        .header-content h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 15px;
        }
        
        .header-content p {
            color: #6b7280;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 40px;
        }
        
        .form-container {
            text-align: left;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
            font-size: 0.95rem;
        }
        
        .form-group input {
            width: 100%;
            padding: 15px 18px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f9fafb;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #4f46e5;
            background: white;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        
        .reset-btn {
            width: 100%;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
            margin-bottom: 30px;
        }
        
        .reset-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
        }
        
        .reset-btn:active {
            transform: translateY(0);
        }
        
        .back-to-login {
            text-align: center;
            color: #6b7280;
            font-size: 0.95rem;
        }
        
        .back-to-login a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        .back-to-login a:hover {
            color: #3730a3;
            text-decoration: underline;
        }
        
        .success-message {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .error-message {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .icon-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: rgba(79, 70, 229, 0.1);
            border-radius: 50%;
            margin-bottom: 20px;
        }
        
        .lock-icon {
            width: 30px;
            height: 30px;
            color: #4f46e5;
        }
        
        @media (max-width: 768px) {
            .forgot-password-container {
                padding: 40px 30px;
                margin: 10px;
            }
            
            .header-content h1 {
                font-size: 1.75rem;
            }
            
            .custom-logo {
                width: 80px;
                height: 80px;
            }
            
            .logo-svg {
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-password-container">
        <div class="logo-container">
            <div class="custom-logo">
                <!-- Your custom logo SVG -->
                <svg class="logo-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <!-- Book base -->
                    <rect x="25" y="45" width="50" height="35" rx="3" fill="white"/>
                    <!-- Book pages -->
                    <rect x="25" y="45" width="25" height="35" rx="3" fill="#e0e7ff"/>
                    <!-- Graduation cap -->
                    <path d="M50 15L85 25L50 35L15 25Z" fill="white"/>
                    <path d="M50 35L50 45L85 35L85 25Z" fill="#e0e7ff"/>
                    <!-- Lines on book -->
                    <line x1="35" y1="52" x2="65" y2="52" stroke="#4f46e5" stroke-width="1"/>
                    <line x1="35" y1="58" x2="65" y2="58" stroke="#4f46e5" stroke-width="1"/>
                    <line x1="35" y1="64" x2="65" y2="64" stroke="#4f46e5" stroke-width="1"/>
                    <line x1="35" y1="70" x2="60" y2="70" stroke="#4f46e5" stroke-width="1"/>
                </svg>
            </div>
        </div>
        
        <div class="header-content">
            <div class="icon-container">
                <svg class="lock-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h1>Forgot Password?</h1>
            <p>No problem! Just enter your email address and we'll send you a password reset link to get you back into your account.</p>
        </div>
        
        <div class="form-container">
            <!-- Display success message -->
            @if (session('status'))
                <div class="success-message">
                    {{ session('status') }}
                </div>
            @endif
            
            <!-- Display validation errors -->
            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ route('password.email') }}">
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
                        placeholder="Enter your email address"
                    />
                </div>
                
                <button type="submit" class="reset-btn">Send Password Reset Link</button>
            </form>
            
            <div class="back-to-login">
                Remember your password? <a href="{{ route('login') }}">Back to Login</a>
            </div>
        </div>
    </div>
</body>
</html>
