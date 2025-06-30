<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ auth()->user()->full_name ?? config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        @extends('layouts.profile-layout-home')

        @section('header')
            <div class="header-left" style="display: flex; align-items: center; gap: 20px;">
                <span class="leading-none flex items-center text-white font-semibold text-xl" style="line-height: 1.5;">
                    {{ auth()->user()->username }}
                </span>
            </div>
        @endsection

        @section('header-buttons')
            <div style="display: flex; flex-direction: column; gap: 10px; background: white; padding: 5px 10px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.15); width: 100%;">
                <a href="{{ url('/feedback') }}" class="header-button w-full text-center no-underline text-white inline-block cursor-pointer">Feedback</a>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="header-button w-full">Logout</button>
                </form>
            </div>
        @endsection

        @section('content')
        <style>
        /* Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f9fafb;
            color: #1f2937;
        }

        /* Container */
        .container {
            max-width: 100%;
            padding: 0 20px;
            margin: 0;
        }

        /* Header */
        header {
            justify-content: space-between;
            align-items: center;
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 30px;
        }
        .logo {
            font-weight: bold;
            font-size: 1.5rem;
            color: #4f46e5;
        }
        .header-buttons {
            margin-top: 10px;
            display: flex;
            gap: 10px;
        }
        .header-button {
            background-color: #4f46e5;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        .header-button:hover {
            background-color: #3730a3;
        }
        .username {
            font-size: 1.2rem;
            font-weight: 600;
            color: #374151;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 30px;
        }
        .header-left {
            flex-direction: column;
            align-items: flex-start;
        }
        .icon-button {
            background: none;
            border: none;
            cursor: pointer;
            position: relative;
            color: #4b5563;
            font-size: 1.5rem;
            transition: color 0.3s ease;
        }
        .icon-button:hover {
            color: #6366f1;
        }

        .icon-link:hover {
            background-color: #e0e7ff;
            color: #4f46e5;
        }

        /* Search and buttons */
        .search-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 40px;
            align-items: center;
        }
        .search-container {
            flex-grow: 1;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .search-input {
            flex-grow: 1;
            padding: 12px 20px;
            border-radius: 9999px;
            border: 1px solid #d1d5db;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s ease;
        }
        .search-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.3);
        }
        .filter-button {
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 9999px;
            padding: 10px;
            cursor: pointer;
            transition: border-color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .filter-button:hover {
            border-color: #6366f1;
            color: #6366f1;
        }
        .btn {
            background-color: #6366f1;
            color: white;
            border: none;
            border-radius: 9999px;
            padding: 12px 30px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #4f46e5;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Calendar */
        .calendar {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
    padding: 30px;
    flex: 3 1 1000px; /* Increase the flex value to make the calendar larger horizontally */
    max-width: 1000px; /* Make the calendar grow horizontally but not exceed 1000px */
}

        /* Calendar Title */
        .my-schedule-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: rgb(41, 63, 99);
            border-radius: 10px;
            display: flex;
            justify-content: center;
            letter-spacing: 0.5em;
            padding: 10px 20px;
            border: 4px solid;
            border-image-slice: 1;
            border-width: 4px;
            border-image-source: linear-gradient(135deg, #6a11cb, #2575fc);
            background: linear-gradient(135deg, #e0e7ff, #c7d2fe, #a5b4fc);
            box-shadow: 0 4px 6px rgba(106, 17, 203, 0.7);
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .calendar-month {
            font-size: 1.5rem;
            font-weight: 700;
            color: #374151;
        }

        .calendar-nav button {
            background: none;
            border: none;
            font-size: 1.5rem;
            font-weight: 700;
            color: #6b7280;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .calendar-nav button:hover {
            color: #6366f1;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            text-align: center;
            font-weight: 600;
            color: #4b5563;
            user-select: none;
        }

        .calendar-grid .day-name {
            font-weight: 700;
            color: #6b7280;
        }

        .calendar-grid .day-number {
            padding: 8px 0;
            border-radius: 8px;
        }

        .calendar-grid .day-number.inactive {
            color: #d1d5db;
        }

        .calendar-grid .day-number.active {
            background-color: #6366f1;
            color: white;
            font-weight: 700;
        }

        /* Featured tutors */
       .featured-tutors {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

        .tutor-card {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 20px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
            padding: 10px 20px;
            margin-bottom: 15px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .tutor-image {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .tutor-info {
            flex-grow: 1;
            min-width: 200px;
        }

        .tutor-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 10px;
        }

        .star-rating {
            display: flex;
            gap: 5px;
            color: #fbbf24;
            margin-bottom: 10px;
        }

        .star-rating svg {
            width: 24px;
            height: 24px;
        }

        .tutor-details p {
            font-size: 1rem;
            color: #4b5563;
            margin-bottom: 6px;
        }

        .book-button {
            background-color: #6366f1;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 12px 40px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .book-button:hover {
            background-color: #4f46e5;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .main-content {
        flex-direction: column;
        gap: 20px; /* Reduced gap for smaller screens */
    }

    .calendar {
        max-width: 100%; /* Make the calendar take full width on smaller screens */
        flex: 1 1 100%; /* Ensure the calendar takes the full width on small screens */
    }
}

       @media (max-width: 768px) {
    .calendar {
        padding: 20px; /* Reduced padding for smaller screens */
    }
}
        .tutor-detail-label {
            font-weight: 700;
            font-size: 1.1rem;
            color: #4f46e5;
            margin-bottom: 6px;
            padding: 4px 8px;
            border: 2px solid #4f46e5;
            border-radius: 6px;
            display: inline-block;
            background-color: #eef2ff;
            box-shadow: 0 2px 6px rgba(79, 70, 229, 0.3);
        }
        .tutor-detail-label.availability {
           font-weight: 700;
            font-size: 1.1rem;
            color: #4f46e5;
            margin-bottom: 6px;
            padding: 4px 8px;
            border: 2px solid #4f46e5;
            border-radius: 6px;
            display: inline-block;
            background-color: #eef2ff;
            box-shadow: 0 2px 6px rgba(79, 70, 229, 0.3);
        }
        </style>

        <div class="container" style="min-height: 100vh; display: flex; flex-direction: column; padding-left: 0; padding-right: 0;">
            <div class="main-content" style="flex-grow: 1; width: 100%; max-width: 100%; margin: 0;">
                <!-- My Schedule Title Above Calendar -->
                <div class="my-schedule-title">My Tutoring Schedule</div>

                <!-- Calendar Section -->
                <section aria-label="Calendar" style="background: white; border-radius: 15px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); padding: 30px; flex: 1 1 100px; width: 700px; margin-left: auto; margin-right: auto;">
                    <div id="calendar"></div>
                </section>

                <!-- Tutoring Sessions Section -->
                <div class="my-schedule-title">Your Tutoring Sessions</div>
                <section class="featured-tutors" aria-label="Your Tutoring Sessions">
                    @forelse($sessions as $session)
                        <article class="tutor-card">
                            <div class="tutor-info" style="display: flex; align-items: center; gap: 20px;">
                                {{-- Student Profile Image --}}
                                <div>
                                    @php
                                        $studentImage = $session->student_profile_image ?? null;
                                        $studentName = $session->student_name ?? 'Student';
                                    @endphp
                                    @if($studentImage)
                                        <img src="{{ (Str::startsWith($studentImage, ['http://', 'https://'])) ? $studentImage : asset('storage/' . $studentImage) }}" alt="Student Image" class="w-20 h-20 rounded-full object-cover border border-gray-300" />
                                    @else
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($studentName) }}&background=random&color=fff" alt="Default Student Image" class="w-20 h-20 rounded-full object-cover border border-gray-300" />
                                    @endif
                                </div>
                                <div style="flex:1;">
                                    <h3 class="tutor-name">Subject ({{ $session->subject ?? 'N/A' }})</h3>
                                    <div class="tutor-details">
                                        <p>
                                            <span class="tutor-detail-label">Student:</span>
                                            {{ $studentName }}
                                        </p>
                                        <p>
                                            <span class="tutor-detail-label">Email:</span>
                                            {{ $session->student_email ?? 'N/A' }}
                                        </p>
                                        <p>
                                            <span class="tutor-detail-label">Date:</span>
                                            {{ \Illuminate\Support\Carbon::parse($session->date)->format('F j, Y') ?? 'N/A' }}
                                        </p>
                                        <p>
                                            <span class="tutor-detail-label">Time:</span>
                                            {{ $session->time ?? 'N/A' }}
                                        </p>
                                        <p>
                                            <span class="tutor-detail-label">Total Payment:</span>
                                            RM{{ number_format($session->total_price ?? 0, 2) }}
                                        </p>
                                        <p>
                                            <span class="tutor-detail-label">Payment Method:</span>
                                            @php
                                                $paymentMethod = $session->payment_method ?? '';
                                                $paymentLabel = '';
                                                // If payment method is a JSON array, decode and pick one
                                                if (is_string($paymentMethod) && (str_starts_with($paymentMethod, '[') || str_starts_with($paymentMethod, '{'))) {
                                                    $decoded = json_decode($paymentMethod, true);
                                                    if (is_array($decoded)) {
                                                        // Prioritize Cash if present
                                                        if (in_array('Cash', $decoded) || in_array('cash', array_map('strtolower', $decoded))) {
                                                            $paymentLabel = 'Cash';
                                                        } elseif (count($decoded) > 0) {
                                                            $paymentLabel = ucfirst($decoded[0]);
                                                        } else {
                                                            $paymentLabel = 'N/A';
                                                        }
                                                    }
                                                } else {
                                                    $pm = strtolower($paymentMethod);
                                                    if ($pm === 'cash' || $pm === 'cash payment') {
                                                        $paymentLabel = 'Cash';
                                                    } elseif ($pm === 'online banking' || $pm === 'online_banking') {
                                                        $paymentLabel = 'Online Banking';
                                                    } elseif ($pm) {
                                                        $paymentLabel = ucfirst($paymentMethod);
                                                    } else {
                                                        $paymentLabel = 'N/A';
                                                    }
                                                }
                                            @endphp
                                            {{ $paymentLabel }}
                                        </p>
                                        <p>
                                            <span class="tutor-detail-label">Session Status:</span>
                                            @php
                                                $status = $session->status ?? '';
                                                $statusLabel = '';
                                                $statusColor = '';
                                                if ($status === 'past') {
                                                    $statusLabel = 'Past';
                                                    $statusColor = 'color: #9ca3af;';
                                                } elseif ($status === 'ongoing') {
                                                    $statusLabel = 'Ongoing';
                                                    $statusColor = 'color: #22c55e;';
                                                } else {
                                                    $statusLabel = 'Future';
                                                    $statusColor = 'color: #2563eb;';
                                                }
                                            @endphp
                                            <span style="{{ $statusColor }} font-weight: bold;">{{ $statusLabel }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div style="padding: 20px; color: #888;">No sessions scheduled.</div>
                    @endforelse
                </section>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    events: '/api/calendar-events', // Adjust this URL to your API endpoint
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    navLinks: true,
                    editable: false,
                    dayMaxEvents: true,
                    eventClick: function(info) {
                        info.jsEvent.preventDefault();
                        const event = info.event;

                        // Show modal or prompt for session management
                        const sessionId = event.id;
                        const sessionTitle = event.title;
                        const sessionDate = event.startStr;

                        // For simplicity, confirm dialog for cancel or reschedule
                        // Use a custom modal instead of prompt and alert
                // Cancel session functionality removed as per user request
            }
        });
        calendar.render();
    });
</script>
        @endsection
    </div>
</body>
</html>
