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
        .fc-event-title {
            color: black !important;
            font-weight: bold !important;
            font-size: 1rem !important;
        }
        .fc-event-time {
            color: black !important;
            font-weight: bold !important;
            font-size: 1rem !important;
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
        <div class="main-content" style="flex-grow: 1; width: 100%; margin: 0; flex-direction: row; display: flex; gap: 20px;">

           <div style="flex-basis: 60%; background: white; border-radius: 15px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); padding: 30px; min-width: 0;">
           <div class="my-schedule-title">My Schedule</div>     
           <section aria-label="Calendar" style="width: 100%;">
                    <div id="calendar"></div>
                </section>
            </div>

            <div style="flex-basis: 60%; background: white; border-radius: 15px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); padding: 30px; min-width: 0;">
                <div class="my-schedule-title">Upcoming Sessions</div>
                @if (!empty($upcomingSessions) && count($upcomingSessions) > 0)
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        @foreach ($upcomingSessions as $session)
                            <li style="border-bottom: 1px solid #e5e7eb; padding: 15px 12px; margin-bottom: 10px; border-radius: 10px; background-color: #f3f4f6;">
                                <div style="color: #1f2937; font-size: 1.2rem; margin-bottom: 6px;">
                                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($session->date)->format('d M Y') }}
                                </div>
                                <div style="font-size: 1rem; color: #374151; margin-bottom: 4px;">
                                    <strong>Subject:</strong> {{ $session->subject }}
                                </div>
                                <div style="color: #374151; font-size: 1rem; margin-bottom: 4px;">
                                    <strong>Time:</strong> {{ $session->time }}
                                </div>
                                <div style="color: #374151; font-size: 1rem;">
                                    <strong>Student:</strong> {{ $session->student_name }}
                                </div>
                                <div style="color: #374151; font-size: 1rem;">
                                    <strong>Email:</strong> {{ $session->student_email }}
                                </div>
                                <div style="margin-top: 10px; display: flex; gap: 10px;">
                                    <button class="btn" style="background-color: #ef4444;" onclick="handleCancelSession({{ $session->id }})">Cancel</button>
                                    <!-- Removed Reschedule button as per user request -->
                                    <!-- <button class="btn" style="background-color: #3b82f6;" onclick="handleRescheduleSession({{ $session->id }})">Reschedule</button> -->
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p style="color: #6b7280;">No upcoming sessions.</p>
                @endif
            </div>
              </div>
            
                <!-- Tutoring Sessions Section -->
                 <div style="width: 100%; margin-top: 30px;">
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
            window.calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                timeZone: 'Asia/Kuala_Lumpur',
                events: '/api/calendar-events', // Make sure this endpoint returns the student's session bookings
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                navLinks: true,
                editable: false,
                dayMaxEvents: true,
                eventDidMount: function(info) {
                    // Use Malaysian time for comparison
                    var now = new Date().toLocaleString("en-US", {timeZone: "Asia/Kuala_Lumpur"});
                    var eventEnd = new Date(info.event.end).toLocaleString("en-US", {timeZone: "Asia/Kuala_Lumpur"});
                    var nowDate = new Date(now);
                    var eventEndDate = new Date(eventEnd);
                    if (eventEndDate < nowDate) {
                        // Past event - change background color to gray
                        info.el.style.backgroundColor = '#9ca3af'; // gray-400
                        info.el.style.borderColor = '#9ca3af';
                        info.el.style.color = 'black'; // Change text color to black for past events
                        // Remove dot if exists
                        var dot = info.el.querySelector('.fc-event-dot');
                        if (dot) {
                            dot.remove();
                        }
                    } else {
                        // Future event - ensure background is blue and no dot
                        info.el.style.backgroundColor = '#82a4eeff'; // blue-600
                        info.el.style.borderColor = '#82a4eeff';
                        info.el.style.color = 'black'; // Change text color to black for future events
                        var dot = info.el.querySelector('.fc-event-dot');
                        if (dot) {
                            dot.remove();
                        }
                    }
                },

            });
            window.calendar.render();
        });
        </script>

        <script>
            function updateSessionsList(sessions) {
                // Updated selector to target the correct Upcoming Sessions <ul>
                const sessionsContainer = document.querySelector('.my-schedule-title + ul');
                if (!sessionsContainer) return;

                if (sessions.length === 0) {
                    sessionsContainer.innerHTML = '<p style="color: #6b7280;">No upcoming sessions.</p>';
                    return;
                }

                let html = '';
                sessions.forEach(session => {
                    html += `
                        <li style="border-bottom: 1px solid #e5e7eb; padding: 15px 12px; margin-bottom: 10px; border-radius: 10px; background-color: #f3f4f6;">
                            <div style="color: #1f2937; font-size: 1.2rem; margin-bottom: 6px;">
                                <strong>Date:</strong> ${new Date(session.date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })}
                            </div>
                            <div style="font-size: 1rem; color: #374151; margin-bottom: 4px;">
                                <strong>Subject:</strong> ${session.subject}
                            </div>
                            <div style="color: #374151; font-size: 1rem; margin-bottom: 4px;">
                                <strong>Time:</strong> ${session.time}
                            </div>
                            <div style="color: #374151; font-size: 1rem;">
                                <strong>Student:</strong> ${session.student_name}
                            </div>
                            <div style="color: #374151; font-size: 1rem;">
                                <strong>Email:</strong> ${session.student_email}
                            </div>
                            <div style="margin-top: 10px; display: flex; gap: 10px;">
                                <button class="btn" style="background-color: #ef4444;" onclick="handleCancelSession(${session.id})">Cancel</button>
                            </div>
                        </li>
                    `;
                });

                sessionsContainer.innerHTML = html;
            }

            function updateTutorSessionsList(sessions) {
                const tutorSessionsContainer = document.querySelector('section.featured-tutors');
                if (!tutorSessionsContainer) return;

                if (sessions.length === 0) {
                    tutorSessionsContainer.innerHTML = '<div style="padding: 20px; color: #888;">No sessions scheduled.</div>';
                    return;
                }

                let html = '';
                sessions.forEach(session => {
                    const studentImageUrl = session.student_profile_image
                        ? (session.student_profile_image.startsWith('http://') || session.student_profile_image.startsWith('https://')
                            ? session.student_profile_image
                            : `/storage/${session.student_profile_image}`)
                        : `https://ui-avatars.com/api/?name=${encodeURIComponent(session.student_name || 'Student')}&background=random&color=fff`;

                    let statusColor = '';
                    if (session.status === 'past') {
                        statusColor = 'color: #9ca3af;';
                    } else if (session.status === 'ongoing') {
                        statusColor = 'color: #22c55e;';
                    } else {
                        statusColor = 'color: #2563eb;';
                    }

                    html += `
                        <article class="tutor-card">
                            <div class="tutor-info" style="display: flex; align-items: center; gap: 20px;">
                                <div>
                                    <img src="${studentImageUrl}" alt="Student Image" class="w-20 h-20 rounded-full object-cover border border-gray-300" />
                                </div>
                                <div style="flex:1;">
                                    <h3 class="tutor-name">Subject (${session.subject ?? 'N/A'})</h3>
                                    <div class="tutor-details">
                                        <p><span class="tutor-detail-label">Student:</span> ${session.student_name}</p>
                                        <p><span class="tutor-detail-label">Email:</span> ${session.student_email ?? 'N/A'}</p>
                                        <p><span class="tutor-detail-label">Date:</span> ${new Date(session.date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
                                        <p><span class="tutor-detail-label">Time:</span> ${session.time ?? 'N/A'}</p>
                                        <p><span class="tutor-detail-label">Session Status:</span> <span style="${statusColor} font-weight: bold;">${session.status.charAt(0).toUpperCase() + session.status.slice(1)}</span></p>
                                    </div>
                                </div>
                            </div>
                        </article>
                    `;
                });

                tutorSessionsContainer.innerHTML = html;
            }

            let sessionActionType = null;
            let sessionActionId = null;
            function showSessionActionModal(type, sessionId) {
                sessionActionType = type;
                sessionActionId = sessionId;
                const modal = document.getElementById('sessionActionModal');
                const title = document.getElementById('actionModalTitle');
                const desc = document.getElementById('actionModalDesc');
                if (type === 'cancel') {
                    title.textContent = 'Cancel Session';
                    desc.innerHTML = '<span style="font-size:1.2em;">Are you sure you want to <b style="color:#ef4444;">cancel</b> this session?</span><br><span style="color:#6366f1;">This action cannot be undone.</span>';
                }
                modal.style.display = 'flex';

                // Remove previous event listeners
                const yesBtn = document.getElementById('actionYes');
                const noBtn = document.getElementById('actionNo');
                yesBtn.replaceWith(yesBtn.cloneNode(true));
                noBtn.replaceWith(noBtn.cloneNode(true));
                const newYesBtn = document.getElementById('actionYes');
                const newNoBtn = document.getElementById('actionNo');

                newYesBtn.addEventListener('click', function() {
                    if (sessionActionType === 'cancel') {
                        fetch('{{ route('api.cancel.session') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ session_id: sessionActionId })
                        })
                        .then(async response => {
                            // Try to parse JSON, otherwise return error
                            let data;
                            try {
                                data = await response.json();
                            } catch (e) {
                                data = { error: 'Session not found or server error.' };
                            }
                            return data;
                        })
                .then(data => {
                    document.getElementById('actionModalButtons').style.display = 'none';
                    document.getElementById('actionOkContainer').style.display = 'block';
            if (data.error) {
                title.textContent = 'Error';
                desc.innerHTML = '<span style="color:#ef4444;font-size:1.1em;">' + data.error + '</span>';
            } else {
                title.textContent = 'Session Cancelled';
                desc.innerHTML = '<span style="color:#10b981;font-size:1.2em;">Session ' + sessionActionId + ' has been cancelled.</span>';
                // Filter sessions for upcoming (exclude past)
                const upcomingSessions = data.sessions.filter(session => session.status !== 'past');
                updateSessionsList(upcomingSessions);
                updateTutorSessionsList(data.sessions);
                if (data.tutors) {
                    data.tutors.forEach(tutor => {
                        updateTutorAvailabilityInUI(tutor.id, tutor.availability);
                    });
                }
                if (window.calendar) {
                    window.calendar.refetchEvents();
                }
            }

            function updateTutorAvailabilityInUI(tutorId, updatedAvailability) {
                // Update the featured tutors list availability display
                const tutorCards = document.querySelectorAll('.tutor-card');
                tutorCards.forEach(card => {
                    const nameElem = card.querySelector('.tutor-name');
                    if (nameElem && nameElem.textContent.includes(tutorId)) {
                        const availabilityElem = card.querySelector('.tutor-detail-label.availability');
                        if (availabilityElem) {
                            if (updatedAvailability && updatedAvailability.length > 0) {
                                let html = '<ul>';
                                updatedAvailability.forEach(slot => {
                                    const times = Array.isArray(slot.time) ? slot.time.join(', ') : slot.time;
                                    html += `<li>Date: ${slot.date}<br>Time: ${times}</li>`;
                                });
                                html += '</ul>';
                                availabilityElem.innerHTML = html;
                            } else {
                                availabilityElem.textContent = 'N/A';
                            }
                        }
                    }
                });

                // Update the search dropdown availability if visible
                const searchDropdown = document.getElementById('searchDropdown');
                if (searchDropdown && searchDropdown.style.display === 'block') {
                    const items = searchDropdown.children;
                    for (let item of items) {
                        if (item.textContent.includes(tutorId)) {
                            const availabilityEl = item.querySelector('div:nth-child(3)');
                            if (availabilityEl) {
                                if (updatedAvailability && updatedAvailability.length > 0) {
                                    const availabilityText = updatedAvailability.map(slot => {
                                        const times = Array.isArray(slot.time) ? slot.time.join(', ') : slot.time;
                                        return `${slot.date} (${times})`;
                                    }).join('; ');
                                    availabilityEl.textContent = 'Availability: ' + availabilityText;
                                } else {
                                    availabilityEl.textContent = 'Availability: N/A';
                                }
                            }
                        }
                    }
                }
            }
                        })
                        .catch(error => {
                            document.getElementById('actionModalButtons').style.display = 'none';
                            document.getElementById('actionOkContainer').style.display = 'block';
                            title.textContent = 'Error';
                            desc.innerHTML = '<span style="color:#ef4444;font-size:1.1em;">Failed to cancel session. Please try again.</span>';
                            console.error('Cancel session error:', error);
                        });
                    }
                });
                // OK button closes modal and resets buttons
                const okBtn = document.getElementById('actionOkBtn');
                if (okBtn) {
                    okBtn.onclick = function() {
                        modal.style.display = 'none';
                        document.getElementById('actionOkContainer').style.display = 'none';
                        document.getElementById('actionModalButtons').style.display = 'flex';
                    };
                }
                newNoBtn.addEventListener('click', function() {
                    modal.style.display = 'none';
                });
            }

            function handleCancelSession(sessionId) {
                showSessionActionModal('cancel', sessionId);
            }

            // Removed handleRescheduleSession function as reschedule button is removed
        </script>

        <!-- Session Action Modal -->
        <div id="sessionActionModal" role="dialog" aria-modal="true" aria-labelledby="actionModalTitle" aria-describedby="actionModalDesc" style="display:none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100vw; height: 100vh; background-color: rgba(0,0,0,0.5); align-items: center; justify-content: center;">
            <div class="modal-content" style="background-color: #fff; margin: auto; padding: 30px 40px; border-radius: 16px; max-width: 420px; box-shadow: 0 6px 24px rgba(99,102,241,0.25); text-align: center; font-family: Arial, sans-serif;">
                <div id="actionModalTitle" class="modal-title" style="font-size: 1.5rem; font-weight: 700; color: #6366f1; margin-bottom: 12px;"></div>
                <div id="actionModalDesc" class="modal-message" style="margin-bottom: 24px; font-size: 1.1rem; color: #333;"></div>
                <div class="modal-buttons" id="actionModalButtons" style="display: flex; justify-content: center; gap: 24px;">
                    <button id="actionYes" type="button" style="padding: 12px 32px; border: none; border-radius: 10px; font-size: 1.1rem; cursor: pointer; font-weight: 700; background-color: #6366f1; color: white;">Yes</button>
                    <button id="actionNo" type="button" style="padding: 12px 32px; border: none; border-radius: 10px; font-size: 1.1rem; cursor: pointer; font-weight: 700; background-color: #e0e0e0; color: #333;">No</button>
                </div>
                <div id="actionOkContainer" style="display: none; margin-top: 24px;">
                    <button id="actionOkBtn" type="button" style="padding: 12px 32px; border: none; border-radius: 10px; font-size: 1.1rem; cursor: pointer; font-weight: 700; background-color: #6366f1; color: white;">OK</button>
                </div>
            </div>
        </div>

        @endsection
    </div>
</body>
</html>
