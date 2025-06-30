<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard - @yield('title')</title>
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
        
        .admin-wrapper {
            position: relative;
            display: flex;
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            min-height: 600px;
        }
        
        .admin-left {
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
        
        .admin-left::before {
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
            width: 120px;
            height: 120px;
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
            width: 80px;
            height: 80px;
        }
        
        .welcome-text {
            position: relative;
            z-index: 2;
        }
        
        .welcome-text h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .welcome-text p {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
            max-width: 300px;
        }
        
        .admin-right {
            flex: 1;
            padding: 60px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <div class="admin-left">
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
        <div class="admin-right w-full">
            <nav class="bg-white p-4 flex justify-between items-center shadow mb-6 rounded">
                <div class="space-x-4">
                    <a href="{{ route('home.student') }}" class="text-indigo-700 font-semibold hover:underline">Home</a>
                    <a href="{{ route('admin.profile') }}" class="text-indigo-700 font-semibold hover:underline">Profile</a>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-red-600 font-semibold hover:underline">Log Out</button>
                </form>
            </nav>
            @yield('content')
        </div>
    </div>
</body>
</html>
