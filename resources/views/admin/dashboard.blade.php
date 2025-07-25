<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
            min-height: 100vh;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 2rem 0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .main-container {
            max-width: 100%;
            margin: 0;
            padding: 0 2rem;
        }

        .content-wrapper {
            padding: 2rem 0;
        }

        /* Increased text sizes */
        .stat-number {
            font-size: 2.5rem !important;
            font-weight: 800 !important;
        }

        .stat-label {
            font-size: 1rem !important;
            font-weight: 600 !important;
        }

        .table-text {
            font-size: 1rem !important;
        }

        .table-header {
            font-size: 0.9rem !important;
            font-weight: 700 !important;
        }

        .section-title {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
        }

        .welcome-title {
            font-size: 2.5rem !important;
            font-weight: 800 !important;
        }

        .welcome-subtitle {
            font-size: 1.25rem !important;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Expandable sections */
        .expandable-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .expandable-content.expanded {
            max-height: 500px;
            transition: max-height 0.3s ease-in;
        }

        .expand-icon {
            transition: transform 0.3s ease;
        }

        .expand-icon.rotated {
            transform: rotate(180deg);
        }

        /* Button-like header styling */
        .expandable-header {
            transition: all 0.2s ease;
        }

        .expandable-header:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .expandable-header:active {
            transform: translateY(0);
        }

        /* Navigation buttons */
        .nav-btn {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            color: #475569;
            border: 2px solid #cbd5e1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .nav-btn:hover {
            background: linear-gradient(135deg, #cbd5e1 0%, #94a3b8 100%);
            color: #334155;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .nav-btn.active {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border: 2px solid #4f46e5;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .nav-btn.active:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.4);
        }

        /* Content sections */
        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        /* User navigation buttons */
        .user-nav-btn {
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            color: #475569;
            border: 2px solid #cbd5e1;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .user-nav-btn:hover {
            background: linear-gradient(135deg, #cbd5e1 0%, #94a3b8 100%);
            color: #334155;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .user-nav-btn.active.students-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: 2px solid #10b981;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .user-nav-btn.active.students-active:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
        }

        .user-nav-btn.active.tutors-active {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            border: 2px solid #8b5cf6;
            box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        }

        .user-nav-btn.active.tutors-active:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(139, 92, 246, 0.4);
        }

        /* User content sections */
        .user-content-section {
            display: none;
        }

        .user-content-section.active {
            display: block;
        }

        /* Pagination styles */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.75rem;
            margin-top: 2rem;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
        }

        .pagination-btn {
            padding: 0.75rem 1.25rem;
            border: 2px solid #d1d5db;
            background: white;
            color: #374151;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 1rem;
            font-weight: 600;
            min-width: 3rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .pagination-btn:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .pagination-btn.active {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border-color: #4f46e5;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .pagination-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .pagination-info {
            color: #475569;
            font-size: 1rem;
            font-weight: 600;
            margin: 0 1.5rem;
            padding: 0.5rem 1rem;
            background: white;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
        }

        /* Management controls */
        .management-controls {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        .search-input {
            padding: 0.75rem 1rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s;
            width: 100%;
        }

        .search-input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .filter-select {
            padding: 0.75rem 1rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 1rem;
            background: white;
            transition: all 0.2s;
        }

        .filter-select:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }

        .delete-btn {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .delete-btn:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
        }

        .bulk-actions {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            display: none;
        }

        .bulk-actions.show {
            display: block;
        }

        .checkbox-custom {
            width: 1.25rem;
            height: 1.25rem;
            cursor: pointer;
        }

        /* Beautiful confirmation modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: scale(0.9) translateY(-20px);
            transition: all 0.3s ease;
        }

        .modal-overlay.show .modal-content {
            transform: scale(1) translateY(0);
        }

        .modal-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
        }

        .modal-icon.danger {
            background: #fee2e2;
            color: #dc2626;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
        }

        .modal-message {
            color: #6b7280;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .modal-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .modal-btn-cancel {
            background: #f3f4f6;
            color: #374151;
        }

        .modal-btn-cancel:hover {
            background: #e5e7eb;
        }

        .modal-btn-confirm {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .modal-btn-confirm:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
        }

        /* Star rating styles */
        .star-rating {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .star {
            color: #fbbf24;
            font-size: 1rem;
        }

        .star.empty {
            color: #d1d5db;
        }

        .rating-text {
            margin-left: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="dashboard-header">
        <div class="main-container">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold">Admin Dashboard</h1>
                        <p class="text-indigo-100 text-xl">Peer Tutor Management System</p>
                    </div>
                </div>
                <nav class="flex items-center space-x-8">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg font-semibold text-lg transition-colors">
                            Log Out
                        </button>
                    </form>
                </nav>
            </div>
        </div>
    </header>

    <!-- Navigation Buttons -->
    <div class="bg-white shadow-sm border-b">
        <div class="main-container">
            <div class="flex space-x-1 py-4">
                <button onclick="showSection('dashboard')" id="dashboard-btn" class="nav-btn active px-6 py-3 rounded-lg font-semibold text-lg transition-all duration-200">
                    Dashboard
                </button>
                <button onclick="showSection('manage-user')" id="manage-user-btn" class="nav-btn px-6 py-3 rounded-lg font-semibold text-lg transition-all duration-200">
                    Manage User
                </button>
                <button onclick="showSection('report')" id="report-btn" class="nav-btn px-6 py-3 rounded-lg font-semibold text-lg transition-all duration-200">
                    Report
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="content-wrapper">
        <div class="main-container">
            <!-- Dashboard Section -->
            <div id="dashboard-section" class="content-section active">
                <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 rounded-2xl p-10 text-white shadow-xl mb-10 fade-in">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="welcome-title mb-4">Welcome back!</h2>
                        <p class="text-blue-100 welcome-subtitle">Here's what's happening with your platform today.</p>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-white/20 rounded-full p-8">
                            <svg class="w-20 h-20 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                <div class="bg-white rounded-xl shadow-lg p-8 border-l-4 border-blue-500 card-hover fade-in">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="stat-label text-gray-600 uppercase tracking-wider">Total Users</p>
                            <p class="stat-number text-gray-900 mt-3">{{ number_format($totalUsers ?? 0) }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-4">
                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8 border-l-4 border-green-500 card-hover fade-in">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="stat-label text-gray-600 uppercase tracking-wider">Total Students</p>
                            <p class="stat-number text-gray-900 mt-3">{{ number_format($totalStudents ?? 0) }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-4">
                            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-8 border-l-4 border-purple-500 card-hover fade-in">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="stat-label text-gray-600 uppercase tracking-wider">Total Tutors</p>
                            <p class="stat-number text-gray-900 mt-3">{{ number_format($totalTutors ?? 0) }}</p>
                        </div>
                        <div class="bg-purple-100 rounded-full p-4">
                            <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Recent Activities Section -->
            <div class="mt-10">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover fade-in">
                    <div class="bg-gradient-to-r from-indigo-500 to-blue-600 px-8 py-6">
                        <h3 class="section-title text-white flex items-center">
                            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Recent Activities
                        </h3>
                    </div>
                    <div class="p-8">
                        @if($recentActivities && $recentActivities->count() > 0)
                            <div class="space-y-6">
                                @foreach($recentActivities as $activity)
                                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                                    <div class="flex-shrink-0">
                                        @if($activity['icon'] === 'user-plus')
                                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                                </svg>
                                            </div>
                                        @elseif($activity['icon'] === 'academic-cap')
                                            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                                                </svg>
                                            </div>
                                        @elseif($activity['icon'] === 'calendar')
                                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @elseif($activity['icon'] === 'star')
                                            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h4 class="text-lg font-semibold text-gray-900">{{ $activity['title'] }}</h4>
                                            <span class="text-base text-gray-500">{{ $activity['created_at']->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-base text-gray-600 mt-2">{{ $activity['description'] }}</p>

                                        @if($activity['type'] === 'session_booking')
                                            <div class="flex items-center space-x-4 mt-3">
                                                <span class="inline-flex px-3 py-2 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $activity['subject'] }}
                                                </span>
                                                <span class="text-base text-gray-500">{{ \Carbon\Carbon::parse($activity['session_date'])->format('M d, Y') }}</span>
                                            </div>
                                        @endif

                                        @if($activity['type'] === 'feedback_submission')
                                            <div class="flex items-center mt-3">
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-5 h-5 {{ $i <= $activity['rating'] ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @endfor
                                                    <span class="ml-3 text-base text-gray-600">({{ $activity['rating'] }}/5)</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-20 h-20 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-lg text-gray-500">No recent activities found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            </div>

            <!-- Manage User Section -->
            <div id="manage-user-section" class="content-section">
                <!-- User Management Navigation -->
                <div class="flex space-x-1 mb-8">
                    <button onclick="showUserSection('students')" id="students-btn" class="user-nav-btn px-6 py-3 rounded-lg font-semibold text-lg transition-all duration-200">
                        <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span id="students-btn-text">View Students (<span id="students-btn-count">{{ $totalStudents }}</span>)</span>
                    </button>
                    <button onclick="showUserSection('tutors')" id="tutors-btn" class="user-nav-btn px-6 py-3 rounded-lg font-semibold text-lg transition-all duration-200">
                        <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <span id="tutors-btn-text">View Tutors (<span id="tutors-btn-count">{{ $totalTutors }}</span>)</span>
                    </button>
                </div>

                <!-- User Content Sections -->
                <div class="user-content-sections">
                    <!-- Student Profiles Section -->
                    <div id="students-content" class="user-content-section">
                        <!-- Student Management Controls -->
                        <div class="management-controls">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Search Students</label>
                                    <input type="text" id="students-search" class="search-input" placeholder="Search by name or email...">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Filter by Major</label>
                                    <select id="students-major-filter" class="filter-select">
                                        <option value="">All Majors</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Filter by Year</label>
                                    <select id="students-year-filter" class="filter-select">
                                        <option value="">All Years</option>
                                        <option value="1">Year 1</option>
                                        <option value="2">Year 2</option>
                                        <option value="3">Year 3</option>
                                        <option value="4">Year 4</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Actions</label>
                                    <button onclick="clearStudentFilters()" class="action-btn bg-gray-500 text-white hover:bg-gray-600 w-full">
                                        Clear Filters
                                    </button>
                                </div>
                            </div>

                            <!-- Bulk Actions -->
                            <div id="students-bulk-actions" class="bulk-actions">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-semibold text-gray-700">
                                        <span id="students-selected-count">0</span> students selected
                                    </span>
                                    <button onclick="bulkDeleteStudents()" class="action-btn delete-btn">
                                        Delete Selected
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover fade-in">
                            <div class="bg-gradient-to-r from-green-500 to-green-600 px-8 py-6">
                                <h3 class="section-title text-white flex items-center">
                                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    Student Profiles (<span id="students-total">{{ $totalStudents }}</span>)
                                </h3>
                            </div>
                            <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-6 text-left">
                                            <input type="checkbox" id="students-select-all" class="checkbox-custom" onchange="toggleAllStudents(this)">
                                        </th>
                                        <th class="px-8 py-6 text-left text-base font-bold text-gray-700 uppercase tracking-wider">Name</th>
                                        <th class="px-8 py-6 text-left text-base font-bold text-gray-700 uppercase tracking-wider">Email</th>
                                        <th class="px-8 py-6 text-left text-base font-bold text-gray-700 uppercase tracking-wider">Major</th>
                                        <th class="px-8 py-6 text-left text-base font-bold text-gray-700 uppercase tracking-wider">Year</th>
                                        <th class="px-8 py-6 text-left text-base font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="students-table-body" class="bg-white divide-y divide-gray-200">
                                    <!-- Students will be populated by JavaScript -->
                                </tbody>
                            </table>
                            </div>

                            <!-- Students Pagination -->
                            <div id="students-pagination" class="pagination">
                                <!-- Pagination will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>

                    <!-- Tutor Profiles Section -->
                    <div id="tutors-content" class="user-content-section">
                        <!-- Tutor Management Controls -->
                        <div class="management-controls">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Search Tutors</label>
                                    <input type="text" id="tutors-search" class="search-input" placeholder="Search by name or email...">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Filter by Subject</label>
                                    <select id="tutors-subject-filter" class="filter-select">
                                        <option value="">All Subjects</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Filter by Rating</label>
                                    <select id="tutors-rating-filter" class="filter-select">
                                        <option value="">All Ratings</option>
                                        <option value="5">5 Stars</option>
                                        <option value="4">4+ Stars</option>
                                        <option value="3">3+ Stars</option>
                                        <option value="2">2+ Stars</option>
                                        <option value="1">1+ Stars</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Actions</label>
                                    <button onclick="clearTutorFilters()" class="action-btn bg-gray-500 text-white hover:bg-gray-600 w-full">
                                        Clear Filters
                                    </button>
                                </div>
                            </div>

                            <!-- Bulk Actions -->
                            <div id="tutors-bulk-actions" class="bulk-actions">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-semibold text-gray-700">
                                        <span id="tutors-selected-count">0</span> tutors selected
                                    </span>
                                    <button onclick="bulkDeleteTutors()" class="action-btn delete-btn">
                                        Delete Selected
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover fade-in">
                            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-8 py-6">
                                <h3 class="section-title text-white flex items-center">
                                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Tutor Profiles (<span id="tutors-total">{{ $totalTutors }}</span>)
                                </h3>
                            </div>
                            <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-6 text-left">
                                            <input type="checkbox" id="tutors-select-all" class="checkbox-custom" onchange="toggleAllTutors(this)">
                                        </th>
                                        <th class="px-8 py-6 text-left text-base font-bold text-gray-700 uppercase tracking-wider">Name</th>
                                        <th class="px-8 py-6 text-left text-base font-bold text-gray-700 uppercase tracking-wider">Email</th>
                                        <th class="px-8 py-6 text-left text-base font-bold text-gray-700 uppercase tracking-wider">Subject Expertise (Hourly Rate)</th>
                                        <th class="px-8 py-6 text-left text-base font-bold text-gray-700 uppercase tracking-wider">Rating</th>
                                        <th class="px-8 py-6 text-left text-base font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="tutors-table-body" class="bg-white divide-y divide-gray-200">
                                    <!-- Tutors will be populated by JavaScript -->
                                </tbody>
                            </table>
                            </div>

                            <!-- Tutors Pagination -->
                            <div id="tutors-pagination" class="pagination">
                                <!-- Pagination will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Report Section -->
            <div id="report-section" class="content-section">
                <!-- Report Filters -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Reports & Analytics</h2>

                    <!-- Date Range Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">From Date</label>
                            <input type="date" id="report-from-date" class="search-input">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">To Date</label>
                            <input type="date" id="report-to-date" class="search-input">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Actions</label>
                            <button onclick="applyReportFilters()" class="action-btn bg-blue-500 text-white hover:bg-blue-600 w-full">
                                Apply Filters
                            </button>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Export</label>
                            <div class="flex gap-2">
                                <button onclick="exportReport('csv')" class="action-btn bg-green-500 text-white hover:bg-green-600 flex-1">
                                    CSV
                                </button>
                                <button onclick="exportReport('pdf')" class="action-btn bg-red-500 text-white hover:bg-red-600 flex-1">
                                    PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Session Analytics -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Session Analytics
                    </h3>

                    <!-- Session Overview Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm font-semibold uppercase">Completed Sessions</p>
                                    <p class="text-3xl font-bold" id="completed-sessions">0</p>
                                </div>
                                <div class="bg-green-400 rounded-full p-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-red-100 text-sm font-semibold uppercase">Cancelled Sessions</p>
                                    <p class="text-3xl font-bold" id="cancelled-sessions">0</p>
                                </div>
                                <div class="bg-red-400 rounded-full p-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-yellow-100 text-sm font-semibold uppercase">Pending Sessions</p>
                                    <p class="text-3xl font-bold" id="pending-sessions">0</p>
                                </div>
                                <div class="bg-yellow-400 rounded-full p-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm font-semibold uppercase">Success Rate</p>
                                    <p class="text-3xl font-bold" id="success-rate">0%</p>
                                </div>
                                <div class="bg-blue-400 rounded-full p-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Popular Subjects and Peak Hours -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-xl font-bold text-gray-900 mb-4">Popular Subjects</h4>
                            <div id="popular-subjects" class="space-y-3">
                                <!-- Popular subjects will be populated by JavaScript -->
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="text-xl font-bold text-gray-900 mb-4">Peak Hours</h4>
                            <div id="peak-hours" class="space-y-3">
                                <!-- Peak hours will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                        </svg>
                        Performance Metrics
                    </h3>

                    <!-- Top Rated Tutors -->
                    <div class="mb-8">
                        <h4 class="text-xl font-bold text-gray-900 mb-4">Top Rated Tutors</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tutor</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sessions</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Earnings</th>
                                    </tr>
                                </thead>
                                <tbody id="top-tutors-table" class="bg-white divide-y divide-gray-200">
                                    <!-- Top tutors will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function toggleSection(sectionName) {
            const content = document.getElementById(sectionName + '-content');
            const icon = document.getElementById(sectionName + '-icon');

            if (content.classList.contains('expanded')) {
                content.classList.remove('expanded');
                icon.classList.remove('rotated');
            } else {
                content.classList.add('expanded');
                icon.classList.add('rotated');
            }
        }

        // Initialize sections as collapsed by default
        document.addEventListener('DOMContentLoaded', function() {
            // Sections start collapsed, so no need to add rotated class initially
            // Initialize students section as active by default in manage user
            showUserSection('students');
        });

        // Section switching functionality
        function showSection(sectionName) {
            // Hide all sections
            const sections = document.querySelectorAll('.content-section');
            sections.forEach(section => {
                section.classList.remove('active');
            });

            // Remove active class from all buttons
            const buttons = document.querySelectorAll('.nav-btn');
            buttons.forEach(button => {
                button.classList.remove('active');
            });

            // Show selected section
            const targetSection = document.getElementById(sectionName + '-section');
            if (targetSection) {
                targetSection.classList.add('active');
            }

            // Add active class to clicked button
            const targetButton = document.getElementById(sectionName + '-btn');
            if (targetButton) {
                targetButton.classList.add('active');
            }
        }

        // User section switching functionality
        function showUserSection(sectionName) {
            // Hide all user sections
            const userSections = document.querySelectorAll('.user-content-section');
            userSections.forEach(section => {
                section.classList.remove('active');
            });

            // Remove active class from all user nav buttons
            const userButtons = document.querySelectorAll('.user-nav-btn');
            userButtons.forEach(button => {
                button.classList.remove('active', 'students-active', 'tutors-active');
            });

            // Show selected user section
            const targetSection = document.getElementById(sectionName + '-content');
            if (targetSection) {
                targetSection.classList.add('active');
            }

            // Add active class to clicked button with appropriate color
            const targetButton = document.getElementById(sectionName + '-btn');
            if (targetButton) {
                targetButton.classList.add('active', sectionName + '-active');
            }

            // Load data for the selected section
            if (sectionName === 'students') {
                loadStudentsData();
            } else if (sectionName === 'tutors') {
                loadTutorsData();
            }
        }

        // Students data and pagination
        let studentsData = @json($students->toArray());
        let studentsCurrentPage = 1;
        const studentsPerPage = 15;

        // Tutors data and pagination
        let tutorsData = @json($tutors->toArray());
        let tutorsCurrentPage = 1;
        const tutorsPerPage = 15;

        // Initialize original data for filtering
        let originalStudents = [...studentsData];
        let originalTutors = [...tutorsData];

        // Initialize filter options when page loads
        document.addEventListener('DOMContentLoaded', function() {
            populateFilterOptions();
        });
        function loadStudentsData() {
            const totalPages = Math.ceil(studentsData.length / studentsPerPage);
            const startIndex = (studentsCurrentPage - 1) * studentsPerPage;
            const endIndex = startIndex + studentsPerPage;
            const currentStudents = studentsData.slice(startIndex, endIndex);

            // Populate table
            const tableBody = document.getElementById('students-table-body');
            tableBody.innerHTML = '';

            if (currentStudents.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" class="px-8 py-8 text-center text-lg text-gray-500">No students found</td></tr>';
            } else {
                currentStudents.forEach(student => {
                    const row = createStudentRow(student);
                    tableBody.appendChild(row);
                });
            }

            // Update pagination
            createPagination('students', studentsCurrentPage, totalPages);
        }

        function createStudentRow(student) {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50 transition-colors duration-200';

            const profileImage = student.user?.profile_image
                ? `<img class="h-14 w-14 rounded-full object-cover border-2 border-green-200" src="${student.user.profile_image}" alt="${student.user?.full_name || 'Student'}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                   <div class="h-14 w-14 rounded-full bg-green-100 flex items-center justify-center" style="display: none;">
                       <span class="text-xl font-bold text-green-800">${(student.user?.full_name || 'N').charAt(0)}</span>
                   </div>`
                : `<div class="h-14 w-14 rounded-full bg-green-100 flex items-center justify-center">
                       <span class="text-xl font-bold text-green-800">${(student.user?.full_name || 'N').charAt(0)}</span>
                   </div>`;

            row.innerHTML = `
                <td class="px-4 py-6 whitespace-nowrap">
                    <input type="checkbox" class="checkbox-custom student-checkbox" value="${student.user?.id || student.user_id}" onchange="updateStudentSelection()">
                </td>
                <td class="px-8 py-6 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-14 w-14">
                            ${profileImage}
                        </div>
                        <div class="ml-4">
                            <div class="text-lg font-bold text-gray-900">${student.user?.full_name || 'N/A'}</div>
                        </div>
                    </div>
                </td>
                <td class="px-8 py-6 whitespace-nowrap text-base text-gray-600">${student.user?.email || 'N/A'}</td>
                <td class="px-8 py-6 whitespace-nowrap">
                    <span class="inline-flex px-4 py-2 text-base font-bold rounded-full bg-blue-100 text-blue-800">
                        ${student.major || 'N/A'}
                    </span>
                </td>
                <td class="px-8 py-6 whitespace-nowrap text-base text-gray-600">${student.year || 'N/A'}</td>
                <td class="px-8 py-6 whitespace-nowrap">
                    <button onclick="showDeleteConfirmation('student', ${student.user?.id || student.user_id}, '${student.user?.full_name || 'this student'}')" class="action-btn delete-btn">
                        Delete
                    </button>
                </td>
            `;
            return row;
        }

        function loadTutorsData() {
            const totalPages = Math.ceil(tutorsData.length / tutorsPerPage);
            const startIndex = (tutorsCurrentPage - 1) * tutorsPerPage;
            const endIndex = startIndex + tutorsPerPage;
            const currentTutors = tutorsData.slice(startIndex, endIndex);

            // Populate table
            const tableBody = document.getElementById('tutors-table-body');
            tableBody.innerHTML = '';

            if (currentTutors.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" class="px-8 py-8 text-center text-lg text-gray-500">No tutors found</td></tr>';
            } else {
                currentTutors.forEach(tutor => {
                    const row = createTutorRow(tutor);
                    tableBody.appendChild(row);
                });
            }

            // Update pagination
            createPagination('tutors', tutorsCurrentPage, totalPages);
        }

        function createTutorRow(tutor) {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50 transition-colors duration-200';

            const profileImage = tutor.user?.profile_image
                ? `<img class="h-14 w-14 rounded-full object-cover border-2 border-purple-200" src="${tutor.user.profile_image}" alt="${tutor.user?.full_name || 'Tutor'}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                   <div class="h-14 w-14 rounded-full bg-purple-100 flex items-center justify-center" style="display: none;">
                       <span class="text-xl font-bold text-purple-800">${(tutor.user?.full_name || 'T').charAt(0)}</span>
                   </div>`
                : `<div class="h-14 w-14 rounded-full bg-purple-100 flex items-center justify-center">
                       <span class="text-xl font-bold text-purple-800">${(tutor.user?.full_name || 'T').charAt(0)}</span>
                   </div>`;

            // Process expertise
            let expertise = tutor.expertise || [];
            if (typeof expertise === 'string') {
                try {
                    expertise = JSON.parse(expertise);
                } catch (e) {
                    expertise = [];
                }
            }
            if (!Array.isArray(expertise)) {
                expertise = [];
            }

            const subjectDetails = expertise.map(subject => {
                const name = subject.name || 'N/A';
                const price = subject.price_per_hour || 0;
                return `${name} (RM ${Math.round(price)})`;
            });
            const displayText = subjectDetails.length > 0 ? subjectDetails.join(', ') : 'N/A';

            // Create star rating display using backend-calculated rating
            const rating = tutor.average_rating || tutor.rating;
            let ratingDisplay = '';

            if (rating && rating > 0) {
                const fullStars = Math.floor(rating);
                const hasHalfStar = rating % 1 >= 0.5;
                const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

                ratingDisplay = '<div class="star-rating">';

                // Full stars
                for (let i = 0; i < fullStars; i++) {
                    ratingDisplay += '<span class="star">★</span>';
                }

                // Half star
                if (hasHalfStar) {
                    ratingDisplay += '<span class="star">☆</span>';
                }

                // Empty stars
                for (let i = 0; i < emptyStars; i++) {
                    ratingDisplay += '<span class="star empty">☆</span>';
                }

                ratingDisplay += `<span class="rating-text">${rating.toFixed(1)}</span></div>`;
            } else {
                ratingDisplay = '<span class="text-base text-gray-500">N/A</span>';
            }

            row.innerHTML = `
                <td class="px-4 py-6 whitespace-nowrap">
                    <input type="checkbox" class="checkbox-custom tutor-checkbox" value="${tutor.user?.id || tutor.user_id}" onchange="updateTutorSelection()">
                </td>
                <td class="px-8 py-6 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-14 w-14">
                            ${profileImage}
                        </div>
                        <div class="ml-4">
                            <div class="text-lg font-bold text-gray-900">${tutor.user?.full_name || 'N/A'}</div>
                        </div>
                    </div>
                </td>
                <td class="px-8 py-6 whitespace-nowrap text-base text-gray-600">${tutor.user?.email || 'N/A'}</td>
                <td class="px-8 py-6 whitespace-nowrap">
                    <span class="text-base font-semibold text-gray-900">${displayText}</span>
                </td>
                <td class="px-8 py-6 whitespace-nowrap">
                    ${ratingDisplay}
                </td>
                <td class="px-8 py-6 whitespace-nowrap">
                    <button onclick="showDeleteConfirmation('tutor', ${tutor.user?.id || tutor.user_id}, '${tutor.user?.full_name || 'this tutor'}')" class="action-btn delete-btn">
                        Delete
                    </button>
                </td>
            `;
            return row;
        }

        function createPagination(type, currentPage, totalPages) {
            const paginationContainer = document.getElementById(type + '-pagination');
            paginationContainer.innerHTML = '';

            if (totalPages <= 1) return;

            // Previous button
            const prevBtn = document.createElement('button');
            prevBtn.className = 'pagination-btn';
            prevBtn.innerHTML = '← Previous';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => changePage(type, currentPage - 1);
            paginationContainer.appendChild(prevBtn);

            // Page numbers
            const startPage = Math.max(1, currentPage - 2);
            const endPage = Math.min(totalPages, currentPage + 2);

            if (startPage > 1) {
                const firstBtn = document.createElement('button');
                firstBtn.className = 'pagination-btn';
                firstBtn.innerHTML = '1';
                firstBtn.onclick = () => changePage(type, 1);
                paginationContainer.appendChild(firstBtn);

                if (startPage > 2) {
                    const dots = document.createElement('span');
                    dots.className = 'pagination-info';
                    dots.innerHTML = '...';
                    paginationContainer.appendChild(dots);
                }
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = 'pagination-btn' + (i === currentPage ? ' active' : '');
                pageBtn.innerHTML = i;
                pageBtn.onclick = () => changePage(type, i);
                paginationContainer.appendChild(pageBtn);
            }

            if (endPage < totalPages) {
                if (endPage < totalPages - 1) {
                    const dots = document.createElement('span');
                    dots.className = 'pagination-info';
                    dots.innerHTML = '...';
                    paginationContainer.appendChild(dots);
                }

                const lastBtn = document.createElement('button');
                lastBtn.className = 'pagination-btn';
                lastBtn.innerHTML = totalPages;
                lastBtn.onclick = () => changePage(type, totalPages);
                paginationContainer.appendChild(lastBtn);
            }

            // Next button
            const nextBtn = document.createElement('button');
            nextBtn.className = 'pagination-btn';
            nextBtn.innerHTML = 'Next →';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => changePage(type, currentPage + 1);
            paginationContainer.appendChild(nextBtn);

            // Page info
            const pageInfo = document.createElement('span');
            pageInfo.className = 'pagination-info';
            const start = (currentPage - 1) * (type === 'students' ? studentsPerPage : tutorsPerPage) + 1;
            const end = Math.min(currentPage * (type === 'students' ? studentsPerPage : tutorsPerPage),
                                type === 'students' ? studentsData.length : tutorsData.length);
            const total = type === 'students' ? studentsData.length : tutorsData.length;
            pageInfo.innerHTML = `Showing ${start}-${end} of ${total}`;
            paginationContainer.appendChild(pageInfo);
        }

        function changePage(type, page) {
            if (type === 'students') {
                studentsCurrentPage = page;
                loadStudentsData();
            } else if (type === 'tutors') {
                tutorsCurrentPage = page;
                loadTutorsData();
            }
        }

        // Search and filter functionality

        // Populate filter dropdown options
        function populateFilterOptions() {
            // Populate student major filter
            const studentMajors = [...new Set(originalStudents.map(s => s.major).filter(m => m))];
            const majorFilter = document.getElementById('students-major-filter');
            majorFilter.innerHTML = '<option value="">All Majors</option>';
            studentMajors.forEach(major => {
                majorFilter.innerHTML += `<option value="${major}">${major}</option>`;
            });

            // Populate tutor subject filter
            const tutorSubjects = new Set();
            originalTutors.forEach(tutor => {
                // Parse expertise from the tutor data
                let expertise = [];
                if (tutor.parsed_expertise && Array.isArray(tutor.parsed_expertise)) {
                    expertise = tutor.parsed_expertise;
                } else if (tutor.expertise) {
                    try {
                        expertise = typeof tutor.expertise === 'string' ? JSON.parse(tutor.expertise) : tutor.expertise;
                        if (!Array.isArray(expertise)) {
                            expertise = [];
                        }
                    } catch (e) {
                        expertise = [];
                    }
                }

                expertise.forEach(subject => {
                    if (subject.name) {
                        tutorSubjects.add(subject.name);
                    }
                });
            });

            const subjectFilter = document.getElementById('tutors-subject-filter');
            subjectFilter.innerHTML = '<option value="">All Subjects</option>';
            [...tutorSubjects].sort().forEach(subject => {
                subjectFilter.innerHTML += `<option value="${subject}">${subject}</option>`;
            });
        }

        // Student search and filter
        function filterStudents() {
            const searchTerm = document.getElementById('students-search').value.toLowerCase();
            const majorFilter = document.getElementById('students-major-filter').value;
            const yearFilter = document.getElementById('students-year-filter').value;

            const filtered = originalStudents.filter(student => {
                const matchesSearch = !searchTerm ||
                    (student.user?.full_name?.toLowerCase().includes(searchTerm)) ||
                    (student.user?.email?.toLowerCase().includes(searchTerm));

                const matchesMajor = !majorFilter || student.major === majorFilter;
                const matchesYear = !yearFilter || student.year == yearFilter;

                return matchesSearch && matchesMajor && matchesYear;
            });

            studentsData = filtered;
            studentsCurrentPage = 1;
            loadStudentsData();
        }

        // Tutor search and filter
        function filterTutors() {
            const searchTerm = document.getElementById('tutors-search').value.toLowerCase();
            const subjectFilter = document.getElementById('tutors-subject-filter').value;
            const ratingFilter = document.getElementById('tutors-rating-filter').value;

            const filtered = originalTutors.filter(tutor => {
                const matchesSearch = !searchTerm ||
                    (tutor.user?.full_name?.toLowerCase().includes(searchTerm)) ||
                    (tutor.user?.email?.toLowerCase().includes(searchTerm));

                // Check subject match using parsed expertise
                let matchesSubject = !subjectFilter;
                if (subjectFilter) {
                    let expertise = [];
                    if (tutor.parsed_expertise && Array.isArray(tutor.parsed_expertise)) {
                        expertise = tutor.parsed_expertise;
                    } else if (tutor.expertise) {
                        try {
                            expertise = typeof tutor.expertise === 'string' ? JSON.parse(tutor.expertise) : tutor.expertise;
                            if (!Array.isArray(expertise)) {
                                expertise = [];
                            }
                        } catch (e) {
                            expertise = [];
                        }
                    }
                    matchesSubject = expertise.some(s => s.name === subjectFilter);
                }

                const matchesRating = !ratingFilter ||
                    (tutor.average_rating && tutor.average_rating >= parseFloat(ratingFilter));

                return matchesSearch && matchesSubject && matchesRating;
            });

            tutorsData = filtered;
            tutorsCurrentPage = 1;
            loadTutorsData();
        }

        // Clear filters
        function clearStudentFilters() {
            document.getElementById('students-search').value = '';
            document.getElementById('students-major-filter').value = '';
            document.getElementById('students-year-filter').value = '';
            studentsData = [...originalStudents];
            studentsCurrentPage = 1;
            loadStudentsData();
            updateStudentSelection();
        }

        function clearTutorFilters() {
            document.getElementById('tutors-search').value = '';
            document.getElementById('tutors-subject-filter').value = '';
            document.getElementById('tutors-rating-filter').value = '';
            tutorsData = [...originalTutors];
            tutorsCurrentPage = 1;
            loadTutorsData();
            updateTutorSelection();
        }

        // Checkbox management
        function toggleAllStudents(checkbox) {
            const studentCheckboxes = document.querySelectorAll('.student-checkbox');
            studentCheckboxes.forEach(cb => cb.checked = checkbox.checked);
            updateStudentSelection();
        }

        function toggleAllTutors(checkbox) {
            const tutorCheckboxes = document.querySelectorAll('.tutor-checkbox');
            tutorCheckboxes.forEach(cb => cb.checked = checkbox.checked);
            updateTutorSelection();
        }

        function updateStudentSelection() {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            const checkedBoxes = document.querySelectorAll('.student-checkbox:checked');
            const selectAllBox = document.getElementById('students-select-all');
            const bulkActions = document.getElementById('students-bulk-actions');
            const selectedCount = document.getElementById('students-selected-count');

            // Update select all checkbox
            selectAllBox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < checkboxes.length;
            selectAllBox.checked = checkboxes.length > 0 && checkedBoxes.length === checkboxes.length;

            // Show/hide bulk actions
            if (checkedBoxes.length > 0) {
                bulkActions.classList.add('show');
                selectedCount.textContent = checkedBoxes.length;
            } else {
                bulkActions.classList.remove('show');
            }
        }

        function updateTutorSelection() {
            const checkboxes = document.querySelectorAll('.tutor-checkbox');
            const checkedBoxes = document.querySelectorAll('.tutor-checkbox:checked');
            const selectAllBox = document.getElementById('tutors-select-all');
            const bulkActions = document.getElementById('tutors-bulk-actions');
            const selectedCount = document.getElementById('tutors-selected-count');

            // Update select all checkbox
            selectAllBox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < checkboxes.length;
            selectAllBox.checked = checkboxes.length > 0 && checkedBoxes.length === checkboxes.length;

            // Show/hide bulk actions
            if (checkedBoxes.length > 0) {
                bulkActions.classList.add('show');
                selectedCount.textContent = checkedBoxes.length;
            } else {
                bulkActions.classList.remove('show');
            }
        }

        // Modal functions
        function showDeleteConfirmation(type, id, name) {
            const modal = document.getElementById('confirmationModal');
            const title = document.getElementById('modalTitle');
            const message = document.getElementById('modalMessage');
            const confirmBtn = document.getElementById('confirmDeleteBtn');

            title.textContent = `Delete ${type.charAt(0).toUpperCase() + type.slice(1)}`;
            message.textContent = `Are you sure you want to delete "${name}"? This action cannot be undone and will permanently remove all associated data including sessions, reviews, and account information.`;

            confirmBtn.onclick = () => {
                if (type === 'student') {
                    deleteStudent(id);
                } else if (type === 'tutor') {
                    deleteTutor(id);
                }
                closeConfirmationModal();
            };

            modal.classList.add('show');
        }

        function showBulkDeleteConfirmation(type, ids, count) {
            const modal = document.getElementById('confirmationModal');
            const title = document.getElementById('modalTitle');
            const message = document.getElementById('modalMessage');
            const confirmBtn = document.getElementById('confirmDeleteBtn');

            title.textContent = `Delete ${count} ${type}${count > 1 ? 's' : ''}`;
            message.textContent = `Are you sure you want to delete ${count} ${type}${count > 1 ? 's' : ''}? This action cannot be undone and will permanently remove all associated data including sessions, reviews, and account information.`;

            confirmBtn.onclick = () => {
                if (type === 'student') {
                    executeBulkDeleteStudents();
                } else if (type === 'tutor') {
                    executeBulkDeleteTutors();
                }
                closeConfirmationModal();
            };

            modal.classList.add('show');
        }

        function closeConfirmationModal() {
            const modal = document.getElementById('confirmationModal');
            modal.classList.remove('show');
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('confirmationModal');
            if (e.target === modal) {
                closeConfirmationModal();
            }
        });

        // Success and error message functions
        function showSuccessMessage(message) {
            // Create a temporary success notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300';
            notification.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    ${message}
                </div>
            `;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        function showErrorMessage(message) {
            // Create a temporary error notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300';
            notification.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    ${message}
                </div>
            `;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Delete functions
        function deleteStudent(studentId) {
            fetch(`/admin/users/${studentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove from both original and current arrays
                    originalStudents = originalStudents.filter(s => (s.user?.id || s.user_id) != studentId);
                    studentsData = studentsData.filter(s => (s.user?.id || s.user_id) != studentId);
                    loadStudentsData();
                    updateStudentSelection();

                    // Update total count
                    document.getElementById('students-total').textContent = originalStudents.length;
                    document.getElementById('students-btn-count').textContent = originalStudents.length;

                    showSuccessMessage('Student deleted successfully');
                } else {
                    showErrorMessage('Error deleting student: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Error deleting student');
            });
        }

        function deleteTutor(tutorId) {
            fetch(`/admin/users/${tutorId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove from both original and current arrays
                    originalTutors = originalTutors.filter(t => (t.user?.id || t.user_id) != tutorId);
                    tutorsData = tutorsData.filter(t => (t.user?.id || t.user_id) != tutorId);
                    loadTutorsData();
                    updateTutorSelection();

                    // Update total count
                    document.getElementById('tutors-total').textContent = originalTutors.length;
                    document.getElementById('tutors-btn-count').textContent = originalTutors.length;

                    showSuccessMessage('Tutor deleted successfully');
                } else {
                    showErrorMessage('Error deleting tutor: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Error deleting tutor');
            });
        }

        function bulkDeleteStudents() {
            const checkedBoxes = document.querySelectorAll('.student-checkbox:checked');
            const studentIds = Array.from(checkedBoxes).map(cb => cb.value);

            if (studentIds.length === 0) {
                showErrorMessage('Please select students to delete');
                return;
            }

            showBulkDeleteConfirmation('student', studentIds, studentIds.length);
        }

        function executeBulkDeleteStudents() {
            const checkedBoxes = document.querySelectorAll('.student-checkbox:checked');
            const studentIds = Array.from(checkedBoxes).map(cb => cb.value);

            fetch('/admin/users/bulk-delete', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ user_ids: studentIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove from both arrays
                    originalStudents = originalStudents.filter(s => !studentIds.includes((s.user?.id || s.user_id).toString()));
                    studentsData = studentsData.filter(s => !studentIds.includes((s.user?.id || s.user_id).toString()));
                    loadStudentsData();
                    updateStudentSelection();

                    // Update total count
                    document.getElementById('students-total').textContent = originalStudents.length;
                    document.getElementById('students-btn-count').textContent = originalStudents.length;

                    showSuccessMessage(`${studentIds.length} student(s) deleted successfully`);
                } else {
                    showErrorMessage('Error deleting students: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Error deleting students');
            });
        }

        function bulkDeleteTutors() {
            const checkedBoxes = document.querySelectorAll('.tutor-checkbox:checked');
            const tutorIds = Array.from(checkedBoxes).map(cb => cb.value);

            if (tutorIds.length === 0) {
                showErrorMessage('Please select tutors to delete');
                return;
            }

            showBulkDeleteConfirmation('tutor', tutorIds, tutorIds.length);
        }

        function executeBulkDeleteTutors() {
            const checkedBoxes = document.querySelectorAll('.tutor-checkbox:checked');
            const tutorIds = Array.from(checkedBoxes).map(cb => cb.value);

            fetch('/admin/users/bulk-delete', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ user_ids: tutorIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove from both arrays
                    originalTutors = originalTutors.filter(t => !tutorIds.includes((t.user?.id || t.user_id).toString()));
                    tutorsData = tutorsData.filter(t => !tutorIds.includes((t.user?.id || t.user_id).toString()));
                    loadTutorsData();
                    updateTutorSelection();

                    // Update total count
                    document.getElementById('tutors-total').textContent = originalTutors.length;
                    document.getElementById('tutors-btn-count').textContent = originalTutors.length;

                    showSuccessMessage(`${tutorIds.length} tutor(s) deleted successfully`);
                } else {
                    showErrorMessage('Error deleting tutors: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Error deleting tutors');
            });
        }

        // Add event listeners for search and filter
        document.addEventListener('DOMContentLoaded', function() {
            // Student filters
            document.getElementById('students-search').addEventListener('input', filterStudents);
            document.getElementById('students-major-filter').addEventListener('change', filterStudents);
            document.getElementById('students-year-filter').addEventListener('change', filterStudents);

            // Tutor filters
            document.getElementById('tutors-search').addEventListener('input', filterTutors);
            document.getElementById('tutors-subject-filter').addEventListener('change', filterTutors);
            document.getElementById('tutors-rating-filter').addEventListener('change', filterTutors);

            // Initialize reports
            initializeReports();
        });

        // Report functionality
        let reportData = {};

        function initializeReports() {
            // Set default date range (last 6 months to capture more data)
            const today = new Date();
            const sixMonthsAgo = new Date(today.getTime() - (180 * 24 * 60 * 60 * 1000));

            document.getElementById('report-from-date').value = sixMonthsAgo.toISOString().split('T')[0];
            document.getElementById('report-to-date').value = today.toISOString().split('T')[0];

            // Load initial report data
            loadReportData();
        }

        function applyReportFilters() {
            loadReportData();
        }

        function loadReportData() {
            const fromDate = document.getElementById('report-from-date').value;
            const toDate = document.getElementById('report-to-date').value;

            if (!fromDate || !toDate) {
                showErrorMessage('Please select both from and to dates');
                return;
            }

            // Show loading state
            showLoadingState();

            fetch('/admin/reports/data', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    from_date: fromDate,
                    to_date: toDate
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Report data received:', data);
                if (data.success) {
                    reportData = data.data;
                    updateReportDisplay();
                } else {
                    showErrorMessage('Error loading report data: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error details:', error);
                showErrorMessage('Error loading report data: ' + error.message);
            });
        }

        function showLoadingState() {
            document.getElementById('completed-sessions').textContent = '...';
            document.getElementById('cancelled-sessions').textContent = '...';
            document.getElementById('pending-sessions').textContent = '...';
            document.getElementById('success-rate').textContent = '...';
        }

        function updateReportDisplay() {
            // Ensure reportData exists and has the expected structure
            if (!reportData || typeof reportData !== 'object') {
                console.error('Invalid report data:', reportData);
                showErrorMessage('Invalid report data received');
                return;
            }

            // Update session analytics cards with fallbacks
            const sessions = reportData.sessions || {};
            document.getElementById('completed-sessions').textContent = sessions.completed || 0;
            document.getElementById('cancelled-sessions').textContent = sessions.cancelled || 0;
            document.getElementById('pending-sessions').textContent = sessions.pending || 0;

            // Calculate and display success rate
            const completed = sessions.completed || 0;
            const cancelled = sessions.cancelled || 0;
            const total = completed + cancelled;
            const successRate = total > 0 ? Math.round((completed / total) * 100) : 0;
            document.getElementById('success-rate').textContent = successRate + '%';

            // Update popular subjects
            updatePopularSubjects(reportData.popular_subjects || []);

            // Update peak hours
            updatePeakHours(reportData.peak_hours || []);

            // Update top tutors
            updateTopTutors(reportData.top_tutors || []);
        }

        function updatePopularSubjects(subjects) {
            const container = document.getElementById('popular-subjects');
            container.innerHTML = '';

            if (subjects.length === 0) {
                container.innerHTML = '<p class="text-gray-500 italic">No data available</p>';
                return;
            }

            subjects.forEach((subject, index) => {
                const percentage = subject.total > 0 ? Math.round((subject.count / subject.total) * 100) : 0;
                const item = document.createElement('div');
                item.className = 'flex items-center justify-between p-3 bg-white rounded-lg border';
                item.innerHTML = `
                    <div class="flex items-center">
                        <span class="w-8 h-8 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center text-sm font-bold mr-3">
                            ${index + 1}
                        </span>
                        <span class="font-semibold text-gray-900">${subject.name}</span>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-gray-900">${subject.count}</div>
                        <div class="text-sm text-gray-500">${percentage}%</div>
                    </div>
                `;
                container.appendChild(item);
            });
        }

        function updatePeakHours(hours) {
            const container = document.getElementById('peak-hours');
            container.innerHTML = '';

            if (hours.length === 0) {
                container.innerHTML = '<p class="text-gray-500 italic">No data available</p>';
                return;
            }

            hours.forEach((hour, index) => {
                const item = document.createElement('div');
                item.className = 'flex items-center justify-between p-3 bg-white rounded-lg border';
                item.innerHTML = `
                    <div class="flex items-center">
                        <span class="w-8 h-8 bg-purple-100 text-purple-800 rounded-full flex items-center justify-center text-sm font-bold mr-3">
                            ${index + 1}
                        </span>
                        <span class="font-semibold text-gray-900">${hour.hour}:00</span>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-gray-900">${hour.count}</div>
                        <div class="text-sm text-gray-500">sessions</div>
                    </div>
                `;
                container.appendChild(item);
            });
        }

        function updateTopTutors(tutors) {
            const tableBody = document.getElementById('top-tutors-table');
            tableBody.innerHTML = '';

            if (tutors.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">No data available</td></tr>';
                return;
            }

            tutors.forEach((tutor, index) => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';

                // Create star rating display
                const rating = tutor.rating || 0;
                let starsHtml = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= rating) {
                        starsHtml += '<span class="text-yellow-400">★</span>';
                    } else {
                        starsHtml += '<span class="text-gray-300">☆</span>';
                    }
                }

                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-purple-100 text-purple-800 rounded-full text-sm font-bold">
                            ${index + 1}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                    <span class="text-sm font-bold text-purple-800">${(tutor.name || 'T').charAt(0)}</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">${tutor.name || 'N/A'}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            ${starsHtml}
                            <span class="ml-2 text-sm text-gray-600">(${rating.toFixed(1)})</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${tutor.sessions_count || 0}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">RM ${(tutor.earnings || 0).toFixed(2)}</td>
                `;
                tableBody.appendChild(row);
            });
        }

        function exportReport(format) {
            const fromDate = document.getElementById('report-from-date').value;
            const toDate = document.getElementById('report-to-date').value;

            if (!fromDate || !toDate) {
                showErrorMessage('Please select date range before exporting');
                return;
            }

            // Create download link
            const url = `/admin/reports/export/${format}?from_date=${fromDate}&to_date=${toDate}`;
            const link = document.createElement('a');
            link.href = url;
            link.download = `report_${fromDate}_to_${toDate}.${format}`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            showSuccessMessage(`Report exported as ${format.toUpperCase()}`);
        }
    </script>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-icon danger">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <h3 class="modal-title" id="modalTitle">Confirm Deletion</h3>
            </div>
            <p class="modal-message" id="modalMessage">
                Are you sure you want to delete this user? This action cannot be undone and will permanently remove all associated data.
            </p>
            <div class="modal-actions">
                <button type="button" class="modal-btn modal-btn-cancel" onclick="closeConfirmationModal()">
                    Cancel
                </button>
                <button type="button" class="modal-btn modal-btn-confirm" id="confirmDeleteBtn">
                    Delete
                </button>
            </div>
        </div>
    </div>
</body>
</html>
