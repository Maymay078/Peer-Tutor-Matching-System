<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
    <title>{{ auth()->user()->full_name ?? config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- FontAwesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <!-- No navigation bar here to remove Laravel dashboard and branding -->

        <!-- Page Heading -->
        @hasSection('header')
        <header class="bg-indigo-600 shadow py-4 px-6">
    <div class="max-w-5xl mx-auto flex justify-between items-center space-x-32">
        
        <!-- Left: Username -->
        <div class="text-white font-bold text-lg">
            @yield('header')
        </div>

        <!-- Center: Logo + Text -->
        <div class="flex flex-col items-center">
            <div class="custom-logo border-4 border-white rounded-full p-2 bg-white" style="width: 100px; height: 100px;">
                <svg class="logo-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%;">
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
            <span class="text-white font-semibold text-sm mt-2">Peer Tutor Matching System</span>
        </div>

        <!-- Right: Profile + Icons -->
        <div class="flex items-center space-x-4">
            @if(auth()->user()->role === 'student')
                <a href="{{ route('home.student') }}" class="icon-link border border-white text-white px-4 py-2 rounded-md hover:bg-indigo-100 hover:text-indigo-700 transition" title="Home">
                    <i class="fas fa-home text-lg"></i>
                </a>
                <a href="{{ route('profile.show', auth()->user()) }}" class="icon-link border border-white text-white px-4 py-2 rounded-md hover:bg-indigo-100 hover:text-indigo-700 transition" title="Profile">
                    <i class="fas fa-user text-lg"></i>
                </a>
                <a href="{{ route('chat.student') }}" class="icon-link border border-white text-white px-4 py-2 rounded-md hover:bg-indigo-100 hover:text-indigo-700 transition" title="Chat">
                    <i class="fas fa-comment-dots text-lg"></i>
                </a>
                <a href="{{ route('notifications') }}" class="icon-link border border-white text-white px-4 py-2 rounded-md hover:bg-indigo-100 hover:text-indigo-700 transition relative" title="Notifications">
                    <i class="fas fa-bell text-lg"></i>
                </a>
            @elseif(auth()->user()->role === 'tutor')
                <a href="{{ url('/home/tutor') }}" class="icon-link border border-white text-white px-4 py-2 rounded-md hover:bg-indigo-100 hover:text-indigo-700 transition" title="Home">
                    <i class="fas fa-home text-lg"></i>
                </a>
                <a href="{{ route('profile.show', auth()->user()) }}" class="icon-link border border-white text-white px-4 py-2 rounded-md hover:bg-indigo-100 hover:text-indigo-700 transition" title="Profile">
                    <i class="fas fa-user text-lg"></i>
                </a>
                <a href="{{ url('/chat/tutor') }}" class="icon-link border border-white text-white px-4 py-2 rounded-md hover:bg-indigo-100 hover:text-indigo-700 transition" title="Chat">
                    <i class="fas fa-comment-dots text-lg"></i>
                </a>
                <a href="{{ url('/notifications') }}" class="icon-link border border-white text-white px-4 py-2 rounded-md hover:bg-indigo-100 hover:text-indigo-700 transition relative" title="Notifications">
                    <i class="fas fa-bell text-lg"></i>
                </a>
            @endif
        </div>

    </div>
</header>

        @endif

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>
