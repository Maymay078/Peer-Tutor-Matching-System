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
        .calendar-sessions-row {
            display: flex;
            flex-direction: row;
            gap: 20px;
            flex-wrap: nowrap;
            width: 100%;
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

        .featured-tutors h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 25px;
            color: #374151;
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
            width: 130px;
            height: 130px;
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
        /* @media (max-width: 1200px) {
            .main-content {
        flex-direction: column;
        gap: 20px; /* Reduced gap for smaller screens */
    }

    .calendar {
        max-width: 100%; /* Make the calendar take full width on smaller screens */
        flex: 1 1 100%; /* Ensure the calendar takes the full width on small screens */
    }

    .featured-tutors {
        max-width: 100%; /* Featured tutors section will also take full width */
    }
} */

       @media (max-width: 768px) {
    .calendar {
        padding: 20px; /* Reduced padding for smaller screens */
    }

    .featured-tutors {
        flex: 1 1 100%; /* Ensure tutors section takes full width on small screens */
    }
}
    </style>
    <style>
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
    </style>
 
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

    <div class="container" style="min-height: 100vh; display: flex; flex-direction: column; padding-left: 0; padding-right: 0;">
        <div class="search-buttons" style="align-items: center;">
            <div class="search-container" style="position: relative; flex-grow: 1;">
                <input id="searchInput" type="text" placeholder="Search Tutor or Subject" class="search-input" autocomplete="off" />
                <div id="searchDropdown" style="position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #d1d5db; border-radius: 0 0 8px 8px; max-height: 300px; overflow-y: auto; z-index: 1000; display: none;"></div>
            </div>
            <div style="margin-left: 15px; display: flex; align-items: center; gap: 8px;">
                <label for="starRatingFilter" style="font-weight: 600; color: #374151;">Min Star Rating:</label>
                <select id="starRatingFilter" class="search-input" style="width: 120px; padding: 8px 12px; border-radius: 9999px; border: 1px solid #d1d5db; font-size: 1rem; outline: none; cursor: pointer;">
                    <option value="0" selected>All Ratings</option>
                    <option value="1">1 Star & Up</option>
                    <option value="2">2 Stars & Up</option>
                    <option value="3">3 Stars & Up</option>
                    <option value="4">4 Stars & Up</option>
                    <option value="5">5 Stars</option>
                </select>
            </div>
        </div>

        <div class="main-content" style="flex-grow: 1; width: 100%; margin: 0; flex-direction: column; display: flex; gap: 20px;">

            <div class="calendar-sessions-row">

                <!-- Left side: Calendar Section -->
                <div style="flex-basis: 60%; background: white; border-radius: 15px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); padding: 30px; min-width: 0;">
                    <div class="my-schedule-title">My Schedule</div>
                    <section aria-label="Calendar" style="width: 100%;">
                        <div id="calendar"></div>
                    </section>
                </div>

                <!-- Right side: Session Details Section -->
                <div style="flex-basis: 60%; background: white; border-radius: 15px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); padding: 30px; min-width: 0;">
                    <div class="my-schedule-title">Upcoming Sessions</div>
                    @if (!empty($sessions) && count($sessions) > 0)
                        <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach ($sessions as $session)
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
                                <strong>Tutor:</strong> {{ $session->tutor_name }}
                            </div>
                            <div style="margin-top: 10px; display: flex; gap: 10px;">
                                <button class="btn" style="background-color: #ef4444;" onclick="handleCancelSession({{ $session->id }})">Cancel</button>
                                <button class="btn" style="background-color: #3b82f6;" onclick="handleRescheduleSession({{ $session->id }})">Reschedule</button>
                            </div>
                        </li>
                    @endforeach
                        </ul>
                    @else
                        <p style="color: #6b7280;">No upcoming sessions.</p>
                    @endif
                </div>

            </div>

            <!-- Featured Tutors Section -->
            <div style="width: 100%; margin-top: 30px;">
                <div class="my-schedule-title">Featured Tutors</div>
                <section class="featured-tutors" aria-label="Featured Tutors">
                    @foreach ($tutors as $tutor)
                        @php
                            $profileImage = $tutor->user->profile_image;
                            $imageSrc = $profileImage
                                ? (Str::startsWith($profileImage, ['http://', 'https://'])
                                    ? $profileImage
                                    : asset('storage/' . $profileImage))
                                : 'https://api.dicebear.com/7.x/micah/svg?seed=' . urlencode($tutor->user->full_name) . '&backgroundColor=e0f2fe,c7d2fe,fae8ff&radius=50';
                        @endphp

                        <article class="tutor-card" style="display: flex; justify-content: center; align-items: center; gap: 30px;">
                            <img src="{{ $imageSrc }}" alt="Profile Image" class="tutor-image" />
                            <div class="tutor-info" style="flex: 1; margin-right: 20px;">
                                <h3 class="tutor-name">{{ $tutor->user->full_name }} ({{ $tutor->user->email ?? '' }})</h3>
                                <div class="star-rating" aria-label="Star rating">
                                    @php
                                        $rating = round($tutor->rating ?? 0);
                                    @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $rating)
                                            <svg viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="w-6 h-6"><path d="M10 15l-5.878 3.09 1.123-6.545L.49 6.91l6.564-.955L10 0l2.946 5.955 6.564.955-4.755 4.635 1.123 6.545z"/></svg>
                                        @else
                                            <svg viewBox="0 0 20 20" fill="gray" aria-hidden="true" class="w-6 h-6"><path d="M10 15l-5.878 3.09 1.123-6.545L.49 6.91l6.564-.955L10 0l2.946 5.955 6.564.955-4.755 4.635 1.123 6.545z"/></svg>
                                        @endif
                                    @endfor
                                </div>
                                <div class="tutor-details">
                                    <p><span class="tutor-detail-label">Subject Expertise:</span> </p>
                                    @php
                                        $subjects = $tutor->expertise ?? [];
                                        if (is_string($subjects)) {
                                            $subjects = json_decode($subjects, true) ?: [];
                                        }
                                        if (!is_array($subjects)) {
                                            $subjects = [];
                                        }
                                        $now = \Carbon\Carbon::now();
                                        $availability = $tutor->availability ?? [];
                                        // Fix: decode JSON if availability is a string
                                        if (is_string($availability)) {
                                            $availability = json_decode($availability, true) ?: [];
                                        }
                                        if (!is_array($availability)) {
                                            $availability = [];
                                        }
                                        // Filter availability to only future dates and times
                                        $filteredAvailability = [];
                                        if (is_array($availability)) {
                                            foreach ($availability as $slot) {
                                                // Defensive: skip if not array/object
                                                if (!is_array($slot) && !is_object($slot)) continue;
                                                // Defensive: get date and time safely
                                                $slotDateVal = is_array($slot) ? ($slot['date'] ?? null) : (property_exists($slot, 'date') ? $slot->date : null);
                                                $slotTimeVal = is_array($slot) ? ($slot['time'] ?? []) : (property_exists($slot, 'time') ? $slot->time : []);
                                                if (!$slotDateVal) continue;
                                                $slotDate = \Carbon\Carbon::parse($slotDateVal);
                                                if ($slotDate->isFuture() || $slotDate->isToday()) {
                                                    $filteredTimes = [];
                                                    foreach ((array)$slotTimeVal as $timeRange) {
                                                        $startTimeStr = trim(explode('-', $timeRange)[0]);
                                                        $slotDateTime = \Carbon\Carbon::parse($slotDateVal . ' ' . $startTimeStr);
                                                        if ($slotDateTime->isFuture() || $slotDateTime->isSameDay($now)) {
                                                            $filteredTimes[] = $timeRange;
                                                        }
                                                    }
                                                    if (count($filteredTimes) > 0) {
                                                        $filteredAvailability[] = [
                                                            'date' => $slotDateVal,
                                                            'time' => $filteredTimes,
                                                        ];
                                                    }
                                                }
                                            }
                                        }
                                    @endphp
                                    <ul>
                                        @forelse ($subjects as $subject)
                                            <li>{{ $subject['name'] ?? '' }} - RM{{ $subject['price_per_hour'] ?? '' }} per hour</li>
                                        @empty
                                        @endforelse
                                    </ul>
                                    <p class="tutor-detail-label availability">Availability:
                                        @php
                                            $displayAvailability = $filteredAvailability;
                                            if (empty($displayAvailability) && !empty($tutor->original_availability)) {
                                                $displayAvailability = $tutor->original_availability;
                                            }
                                        @endphp
                                        @if (!empty($displayAvailability))
                                            <ul>
                                                @foreach ($displayAvailability as $slot)
                                                    <li>
                                                        Date: {{ \Carbon\Carbon::parse($slot['date'])->format('d M Y') ?? '' }}<br />
                                                        Time: 
                                                        @php
                                                            $times = is_array($slot['time']) ? $slot['time'] : [$slot['time']];
                                                            usort($times, function($a, $b) {
                                                                $startA = \Carbon\Carbon::parse(trim(explode('-', $a)[0]));
                                                                $startB = \Carbon\Carbon::parse(trim(explode('-', $b)[0]));
                                                                return $startA->lt($startB) ? -1 : 1;
                                                            });
                                                        @endphp
                                                        {{ implode(', ', $times) }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                    <p><span class="tutor-detail-label">Payment Method:</span> {{ $tutor->payment_details ?? '' }}</p>
                                
                                </div>
                            </div>
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 8px; flex-shrink: 0;">
                                <button class="book-button" onclick="handleBookSessionClick({{ $tutor->id }}, {{ json_encode($tutor->availability) }}, {{ json_encode($tutor->original_availability) }})">Book Session</button>
                <button class="book-button" style=" background-color: #10b981;" onclick="handleChatClick({{ $tutor->id }})">Chat</button>

                <script>
                    function handleChatClick(tutorId) {
                        window.location.href = '{{ route('chat.student') }}?tutor_id=' + tutorId;
                    }
                </script>
                            </div>
                        </article>
                    @endforeach
                </section>
            </div>
    </div>

    <style>
        /* Modal styles */
        #customConfirmModal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
        }
        #customConfirmModal .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 20px 30px;
            border-radius: 12px;
            max-width: 400px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            text-align: center;
            font-family: Arial, sans-serif;
        }
        #customConfirmModal .modal-message {
            margin-bottom: 20px;
            font-size: 1.1rem;
            color: #333;
        }
        #customConfirmModal .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        #customConfirmModal button {
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }
        #customConfirmModal button#confirmYes {
            background-color: #6366f1;
            color: white;
        }
        #customConfirmModal button#confirmYes:hover {
            background-color: #4f46e5;
        }
        #customConfirmModal button#confirmNo {
            background-color: #e0e0e0;
            color: #333;
        }
        #customConfirmModal button#confirmNo:hover {
            background-color: #c7c7c7;
        }
    </style>

    <div id="customConfirmModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle" aria-describedby="modalDesc">
        <div class="modal-content">
            <div id="modalDesc" class="modal-message">
                The tutors have no availability but they can still book session and wait for confirmation from tutor and do u still want to continue ?
            </div>
            <div class="modal-buttons">
                <button id="confirmYes" type="button">Yes</button>
                <button id="confirmNo" type="button">No</button>
            </div>
        </div>
    </div>

    <script>
        async function handleBookSessionClick(tutorId, availability, originalAvailability) {
            let hasAvailability = false;
            try {
                // Parse availability if it's a string
                if (typeof availability === 'string') {
                    availability = JSON.parse(availability);
                }
                if (typeof originalAvailability === 'string') {
                    originalAvailability = JSON.parse(originalAvailability);
                }
                // Check if there is any future availability slot with time in filtered availability
                if (availability && Array.isArray(availability)) {
                    for (let slot of availability) {
                        if (slot && slot.time && Array.isArray(slot.time) && slot.time.length > 0) {
                            hasAvailability = true;
                            break;
                        }
                    }
                }
                // If no availability found in filtered, check original availability
                if (!hasAvailability && originalAvailability && Array.isArray(originalAvailability)) {
                    for (let slot of originalAvailability) {
                        if (slot && slot.time && Array.isArray(slot.time) && slot.time.length > 0) {
                            hasAvailability = true;
                            break;
                        }
                    }
                }
            } catch (e) {
                hasAvailability = false;
            }

            if (!hasAvailability) {
                // Show custom modal
                const modal = document.getElementById('customConfirmModal');
                modal.style.display = 'flex';

                // Handle Yes button
                const yesBtn = document.getElementById('confirmYes');
                const noBtn = document.getElementById('confirmNo');

                // Remove previous event listeners to avoid duplicates
                yesBtn.replaceWith(yesBtn.cloneNode(true));
                noBtn.replaceWith(noBtn.cloneNode(true));

                const newYesBtn = document.getElementById('confirmYes');
                const newNoBtn = document.getElementById('confirmNo');

                newYesBtn.addEventListener('click', function() {
                    modal.style.display = 'none';
                    window.location.href = '{{ route('session.scheduling') }}?tutor_id=' + tutorId;
                });

                newNoBtn.addEventListener('click', function() {
                    modal.style.display = 'none';
                });
            } else {
                // Redirect to session scheduling with tutor_id
                window.location.href = '{{ route('session.scheduling') }}?tutor_id=' + tutorId;
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

        // Optional: Close modal if user clicks outside modal content
        window.addEventListener('click', function(event) {
            const modal = document.getElementById('customConfirmModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            window.calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
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
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const searchDropdown = document.getElementById('searchDropdown');

            let debounceTimeout = null;

            function clearDropdown() {
                searchDropdown.innerHTML = '';
                searchDropdown.style.display = 'none';
            }

function createDropdownItem(tutor) {
                const item = document.createElement('div');
                item.style.padding = '10px';
                item.style.cursor = 'pointer';
                item.style.borderBottom = '1px solid #e5e7eb';

                const nameEl = document.createElement('div');
                nameEl.style.fontWeight = '600';
                nameEl.style.display = 'flex';
                nameEl.style.alignItems = 'center';
                nameEl.style.gap = '6px';

                // Add tutor name text
                const nameText = document.createTextNode(tutor.full_name);

                // Add star rating display
                const ratingSpan = document.createElement('span');
                ratingSpan.style.color = '#fbbf24';
                ratingSpan.style.fontWeight = '600';
                ratingSpan.style.display = 'flex';
                ratingSpan.style.alignItems = 'center';
                ratingSpan.style.gap = '2px';

                const rating = Math.round(tutor.rating || 0);
                for (let i = 1; i <= 5; i++) {
                    const star = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    star.setAttribute('viewBox', '0 0 20 20');
                    star.setAttribute('fill', i <= rating ? 'currentColor' : 'gray');
                    star.setAttribute('aria-hidden', 'true');
                    star.style.width = '16px';
                    star.style.height = '16px';
                    star.style.verticalAlign = 'middle';

                    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    path.setAttribute('d', 'M10 15l-5.878 3.09 1.123-6.545L.49 6.91l6.564-.955L10 0l2.946 5.955 6.564.955-4.755 4.635 1.123 6.545z');
                    star.appendChild(path);
                    ratingSpan.appendChild(star);
                }

                nameEl.appendChild(nameText);
                nameEl.appendChild(ratingSpan);

                const subjectsEl = document.createElement('div');
                // Format subjects with price
                const subjectsText = tutor.subjects.map(s => `${s.name} - RM${s.price_per_hour} per hour`).join(', ');
                subjectsEl.textContent = 'Subjects: ' + (subjectsText || 'N/A');
                subjectsEl.style.fontSize = '0.9rem';
                subjectsEl.style.color = '#6b7280';

                item.appendChild(nameEl);
                item.appendChild(subjectsEl);

                // Add availability info
                const availabilityEl = document.createElement('div');
                let displayAvailability = tutor.availability;
                if ((!displayAvailability || displayAvailability.length === 0) && tutor.original_availability && tutor.original_availability.length > 0) {
                    displayAvailability = tutor.original_availability;
                }
                if (displayAvailability && displayAvailability.length > 0) {
                    // Sort availability by date ascending
                    displayAvailability.sort((a, b) => {
                        const dateA = new Date(a.date);
                        const dateB = new Date(b.date);
                        return dateA - dateB;
                    });
                    // For each date, sort times ascending
displayAvailability.forEach(slot => {
                        if (Array.isArray(slot.time)) {
                            // Helper function to convert 12-hour time string to minutes since midnight
                            function timeToMinutes(t) {
                                const [time, modifier] = t.trim().split(' ');
                                let [hours, minutes] = time.split(':').map(Number);
                                if (modifier === 'PM' && hours !== 12) {
                                    hours += 12;
                                }
                                if (modifier === 'AM' && hours === 12) {
                                    hours = 0;
                                }
                                return hours * 60 + minutes;
                            }
                            slot.time.sort((t1, t2) => {
                                const startTime1 = t1.split('-')[0].trim();
                                const startTime2 = t2.split('-')[0].trim();
                                return timeToMinutes(startTime1) - timeToMinutes(startTime2);
                            });
                        }
                    });
                    const availabilityText = displayAvailability.map(slot => {
                        const times = Array.isArray(slot.time) ? slot.time.join(', ') : slot.time;
                        return `${slot.date} (${times})`;
                    }).join('; ');
                    availabilityEl.textContent = 'Availability: ' + availabilityText;
                } else {
                    availabilityEl.textContent = 'Availability: N/A';
                }
                availabilityEl.style.fontSize = '0.85rem';
                availabilityEl.style.color = '#4b5563';
                availabilityEl.style.marginTop = '4px';

                item.appendChild(availabilityEl);

                // Add book session button
                const bookBtn = document.createElement('button');
                bookBtn.textContent = 'Book Session';
                bookBtn.style.marginLeft = '10px';
                bookBtn.style.padding = '4px 10px';
                bookBtn.style.border = 'none';
                bookBtn.style.borderRadius = '6px';
                bookBtn.style.backgroundColor = '#6366f1';
                bookBtn.style.color = 'white';
                bookBtn.style.cursor = 'pointer';
                bookBtn.style.fontWeight = '600';
                bookBtn.style.fontSize = '0.9rem';
                bookBtn.style.flexShrink = '0';

                // Add chat button
                const chatBtn = document.createElement('button');
                chatBtn.textContent = 'Chat';
                chatBtn.style.marginLeft = '10px';
                chatBtn.style.padding = '4px 10px';
                chatBtn.style.border = 'none';
                chatBtn.style.borderRadius = '6px';
                chatBtn.style.backgroundColor = '#10b981';
                chatBtn.style.color = 'white';
                chatBtn.style.cursor = 'pointer';
                chatBtn.style.fontWeight = '600';
                chatBtn.style.fontSize = '0.9rem';
                chatBtn.style.flexShrink = '0';

                chatBtn.addEventListener('click', function (event) {
                    event.stopPropagation();
                    window.location.href = '{{ route('chat.student') }}?tutor_id=' + tutor.id;
                });

                bookBtn.addEventListener('click', function (event) {
                    event.stopPropagation();

                    // Check if tutor has availability
                    let hasAvailability = false;
                    if (tutor.availability && tutor.availability.length > 0) {
                        for (let slot of tutor.availability) {
                            if (slot && slot.time && Array.isArray(slot.time) && slot.time.length > 0) {
                                hasAvailability = true;
                                break;
                            }
                        }
                    }
                    if (!hasAvailability && tutor.original_availability && tutor.original_availability.length > 0) {
                        for (let slot of tutor.original_availability) {
                            if (slot && slot.time && Array.isArray(slot.time) && slot.time.length > 0) {
                                hasAvailability = true;
                                break;
                            }
                        }
                    }

                    if (!hasAvailability) {
                        // Show custom modal
                        const modal = document.getElementById('customConfirmModal');
                        modal.style.display = 'flex';

                        const yesBtn = document.getElementById('confirmYes');
                        const noBtn = document.getElementById('confirmNo');

                        // Remove previous event listeners to avoid duplicates
                        yesBtn.replaceWith(yesBtn.cloneNode(true));
                        noBtn.replaceWith(noBtn.cloneNode(true));

                        const newYesBtn = document.getElementById('confirmYes');
                        const newNoBtn = document.getElementById('confirmNo');

                        newYesBtn.addEventListener('click', function () {
                            modal.style.display = 'none';
                            window.location.href = '{{ route('session.scheduling') }}?tutor_id=' + tutor.id;
                        });

                        newNoBtn.addEventListener('click', function () {
                            modal.style.display = 'none';
                        });
                    } else {
                        // Redirect to session scheduling with tutor_id
                        window.location.href = '{{ route('session.scheduling') }}?tutor_id=' + tutor.id;
                    }
                });

                item.appendChild(bookBtn);
                item.appendChild(chatBtn);

                item.addEventListener('click', function (event) {
                    // Prevent navigation on clicking tutor item
                    event.stopPropagation();
                    event.preventDefault();
                    // No navigation as per user request
                });

                return item;
            }

            searchInput.addEventListener('input', function () {
                const query = searchInput.value.trim();
                const minRating = document.getElementById('starRatingFilter').value;

                if (debounceTimeout) {
                    clearTimeout(debounceTimeout);
                }

                if (query.length === 0) {
                    clearDropdown();
                    return;
                }

                debounceTimeout = setTimeout(() => {
                    fetch('/api/search-tutors?q=' + encodeURIComponent(query) + '&min_rating=' + encodeURIComponent(minRating))
                        .then(response => response.json())
                        .then(data => {
                            searchDropdown.innerHTML = '';

                            if (data.length === 0) {
                                const noResult = document.createElement('div');
                                noResult.textContent = 'No results found';
                                noResult.style.padding = '10px';
                                noResult.style.color = '#6b7280';
                                searchDropdown.appendChild(noResult);
                            } else {
                                data.forEach(tutor => {
                                    const item = createDropdownItem(tutor);
                                    searchDropdown.appendChild(item);
                                });
                            }

                            searchDropdown.style.display = 'block';
                        })
                        .catch(() => {
                            clearDropdown();
                        });
                }, 300);
            });

            // Also trigger search when star rating filter changes
            document.getElementById('starRatingFilter').addEventListener('change', function () {
                const event = new Event('input');
                // If search input is empty, trigger search to list tutors by star rating immediately
                if (searchInput.value.trim().length === 0) {
                    // Temporarily set search input to a space to trigger search
                    searchInput.value = ' ';
                    searchInput.dispatchEvent(event);
                    // Reset search input to empty after dispatch
                    searchInput.value = '';
                } else {
                    searchInput.dispatchEvent(event);
                }
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function (event) {
                if (!searchInput.contains(event.target) && !searchDropdown.contains(event.target)) {
                    clearDropdown();
                }
            });
        });
    </script>

    <style>
        /* Custom session action modal styles */
        #sessionActionModal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
        }
        #sessionActionModal .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 30px 40px;
            border-radius: 16px;
            max-width: 420px;
            box-shadow: 0 6px 24px rgba(99,102,241,0.25);
            text-align: center;
            font-family: Arial, sans-serif;
        }
        #sessionActionModal .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #6366f1;
            margin-bottom: 12px;
        }
        #sessionActionModal .modal-message {
            margin-bottom: 24px;
            font-size: 1.1rem;
            color: #333;
        }
        #sessionActionModal .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 24px;
        }
        #sessionActionModal button {
            padding: 12px 32px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            cursor: pointer;
            font-weight: 700;
            transition: background-color 0.3s ease;
        }
        #sessionActionModal button#actionYes {
            background-color: #6366f1;
            color: white;
        }
        #sessionActionModal button#actionYes:hover {
            background-color: #4f46e5;
        }
        #sessionActionModal button#actionNo {
            background-color: #e0e0e0;
            color: #333;
        }
        #sessionActionModal button#actionNo:hover {
            background-color: #c7c7c7;
        }
    </style>

    <div id="sessionActionModal" role="dialog" aria-modal="true" aria-labelledby="actionModalTitle" aria-describedby="actionModalDesc">
        <div class="modal-content">
            <div id="actionModalTitle" class="modal-title"></div>
            <div id="actionModalDesc" class="modal-message"></div>
            <div class="modal-buttons" id="actionModalButtons">
                <button id="actionYes" type="button">Yes</button>
                <button id="actionNo" type="button">No</button>
            </div>
            <div id="actionOkContainer" style="display:none; margin-top:24px;">
                <button id="actionOkBtn" type="button" style="padding:12px 32px; border:none; border-radius:10px; font-size:1.1rem; cursor:pointer; font-weight:700; background-color:#6366f1; color:white;">OK</button>
            </div>
        </div>
    </div>

    <script>
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
            } else if (type === 'reschedule') {
                title.textContent = 'Reschedule Session';
                desc.innerHTML = '<span style="font-size:1.2em;">Do you want to <b style="color:#3b82f6;">reschedule</b> this session?</span><br><span style="color:#6366f1;">You will be redirected to the reschedule page.</span>';
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
                    .then(response => response.json())
                    .then(data => {
                        // Always hide Yes/No and show OK for both error and success
                        document.getElementById('actionModalButtons').style.display = 'none';
                        document.getElementById('actionOkContainer').style.display = 'block';
                        if (data.error) {
                            title.textContent = 'Error';
                            desc.innerHTML = '<span style="color:#ef4444;font-size:1.1em;">' + data.error + '</span>';
                            return;
                        }
                        title.textContent = 'Session Cancelled';
                        desc.innerHTML = '<span style="color:#10b981;font-size:1.2em;">Session ' + sessionActionId + ' has been cancelled.</span>';
                        updateSessionsList(data.sessions);
                        if (data.tutors) {
                            data.tutors.forEach(tutor => {
                                updateTutorAvailabilityInUI(tutor.id, tutor.availability);
                            });
                        }
                        if (window.calendar) {
                            window.calendar.refetchEvents();
                        }
                    })
                    .catch(error => {
                        document.getElementById('actionModalButtons').style.display = 'none';
                        document.getElementById('actionOkContainer').style.display = 'block';
                        title.textContent = 'Error';
                        desc.innerHTML = '<span style="color:#ef4444;font-size:1.1em;">Failed to cancel session. Please try again.</span>';
                        console.error('Cancel session error:', error);
                    });
                } else if (sessionActionType === 'reschedule') {
                    // Show reschedule modal instead of navigating
                    // Close the first modal before showing reschedule modal
                    const sessionActionModal = document.getElementById('sessionActionModal');
                    sessionActionModal.style.display = 'none';
                    showRescheduleModal(sessionActionId);
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

        function updateSessionsList(sessions) {
            const sessionsContainer = document.querySelector('.main-content > .calendar-sessions-row > div:nth-child(2) > ul');
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
                            <strong>Tutor:</strong> ${session.tutor_name}
                        </div>
                        <div style="margin-top: 10px; display: flex; gap: 10px;">
                            <button class="btn" style="background-color: #ef4444;" onclick="handleCancelSession(${session.id})">Cancel</button>
                            <button class="btn" style="background-color: #3b82f6;" onclick="handleRescheduleSession(${session.id})">Reschedule</button>
                        </div>
                    </li>
                `;
            });

            sessionsContainer.innerHTML = html;
        }

        function handleCancelSession(sessionId) {
            showSessionActionModal('cancel', sessionId);
        }

        function handleRescheduleSession(sessionId) {
            showSessionActionModal('reschedule', sessionId);
        }

        // Add reschedule modal HTML after sessionActionModal
const rescheduleModalHtml = `
<div id="rescheduleSessionModal" style="display:none; position:fixed; z-index:10001; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
  <div style="background:#fff; margin:auto; padding:30px 40px; border-radius:16px; max-width:420px; box-shadow:0 6px 24px rgba(99,102,241,0.25); text-align:center; font-family:Arial,sans-serif;">
    <div style="font-size:1.5rem; font-weight:700; color:#3b82f6; margin-bottom:12px;">Reschedule Session</div>
    <div style="margin-bottom:18px; font-size:1.1rem; color:#333;">Select new date and time for your session:</div>
    <input type="date" id="rescheduleDate" style="margin-bottom:12px; padding:8px; font-size:1rem; border-radius:6px; border:1px solid #d1d5db; width: 100%;">
    <br>
    <div id="rescheduleTimeContainer" style="margin-bottom:18px; font-size:1rem; border-radius:6px; border:1px solid #d1d5db; padding: 8px; max-height: 150px; overflow-y: auto;"></div>
    <div id="totalPriceDisplay" style="font-weight: 700; font-size: 1.1rem; margin-top: 8px; color: #374151;">Total Price: RM0</div>
    <br>
    <div style="display:flex; justify-content:center; gap:24px;">
      <button id="rescheduleConfirmBtn" style="padding:12px 32px; border:none; border-radius:10px; font-size:1.1rem; cursor:pointer; font-weight:700; background-color:#3b82f6; color:white;">Confirm</button>
      <button id="rescheduleCancelBtn" style="padding:12px 32px; border:none; border-radius:10px; font-size:1.1rem; cursor:pointer; font-weight:700; background-color:#e0e0e0; color:#333;">Cancel</button>
    </div>
    <div id="rescheduleFeedback" style="margin-top:18px; font-size:1.1rem;"></div>
  </div>
</div>
`;
document.body.insertAdjacentHTML('beforeend', rescheduleModalHtml);

function showRescheduleModal(sessionId) {
    const modal = document.getElementById('rescheduleSessionModal');
    const confirmBtn = document.getElementById('rescheduleConfirmBtn');
    const cancelBtn = document.getElementById('rescheduleCancelBtn');
    const feedback = document.getElementById('rescheduleFeedback');
    const dateSelect = document.getElementById('rescheduleDate');
    const timeSelectContainer = document.getElementById('rescheduleTimeContainer');
    const totalPriceDisplay = document.getElementById('totalPriceDisplay');

    modal.style.display = 'flex';
    feedback.textContent = '';
    dateSelect.innerHTML = '';
    timeSelectContainer.innerHTML = '';

    // Reset dateSelect to original select element if it was changed to input
    if (dateSelect.tagName.toLowerCase() === 'input') {
        const newSelect = document.createElement('select');
        newSelect.id = 'rescheduleDate';
        newSelect.style.marginBottom = '12px';
        newSelect.style.padding = '8px';
        newSelect.style.fontSize = '1rem';
        newSelect.style.borderRadius = '6px';
        newSelect.style.border = '1px solid #d1d5db';
        newSelect.style.width = '100%';
        dateSelect.replaceWith(newSelect);
    }

    // Fetch tutor availability for reschedule
    fetch(`/api/tutor-availability-for-reschedule?session_id=${sessionId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                feedback.style.color = '#ef4444';
                feedback.textContent = data.error;
                return;
            }
            let availability = data.availability || [];
            let sessionData = data;

            // If no availability, generate wide availability from 8 AM to 6 PM for future dates excluding conflicts
            if (availability.length === 0) {
                // Get fresh reference to dateSelect after potential replacement
                const currentDateSelect = document.getElementById('rescheduleDate');
                
                // Only replace with input if it's currently a select
                if (currentDateSelect.tagName.toLowerCase() === 'select') {
                    const newDateInput = document.createElement('input');
                    newDateInput.type = 'date';
                    newDateInput.id = 'rescheduleDate';
                    newDateInput.style.marginBottom = '12px';
                    newDateInput.style.padding = '8px';
                    newDateInput.style.fontSize = '1rem';
                    newDateInput.style.borderRadius = '6px';
                    newDateInput.style.border = '1px solid #d1d5db';
                    newDateInput.style.width = '100%';
                    
                    // Get current Malaysian date and time
                    const now = new Date().toLocaleString("en-US", {timeZone: "Asia/Kuala_Lumpur"});
                    const currentDate = new Date(now);
                    const currentHour = currentDate.getHours();
                    const isoCurrentDate = currentDate.toISOString().split('T')[0];
                    
                    // If current time is after 6 PM (18:00), start from tomorrow
                    let minDate;
                    if (currentHour >= 18) {
                        const tomorrow = new Date(currentDate);
                        tomorrow.setDate(tomorrow.getDate() + 1);
                        minDate = tomorrow.toISOString().split('T')[0];
                        newDateInput.value = minDate;
                    } else {
                        minDate = isoCurrentDate;
                        newDateInput.value = isoCurrentDate;
                    }
                    
                    newDateInput.min = minDate;
                    currentDateSelect.replaceWith(newDateInput);
                }

                // Rest of the no availability logic...
                const dateInput = document.getElementById('rescheduleDate');
                const timeSelectContainer = document.getElementById('rescheduleTimeContainer');
                const totalPriceDisplay = document.getElementById('totalPriceDisplay');
                const feedback = document.getElementById('rescheduleFeedback');

                // Clear previous options
                timeSelectContainer.innerHTML = '';
                feedback.textContent = '';

                // Generate time slots from 8 AM to 6 PM
                const timeSlots = [];
                for (let hour = 8; hour < 18; hour++) {
                    const startHour = hour % 12 === 0 ? 12 : hour % 12;
                    const endHour = (hour + 1) % 12 === 0 ? 12 : (hour + 1) % 12;
                    const start = `${startHour}:00 ${hour < 12 ? 'AM' : 'PM'}`;
                    const end = `${endHour}:00 ${(hour + 1) < 12 ? 'AM' : 'PM'}`;
                    timeSlots.push(`${start} - ${end}`);
                }

                const currentTutorId = data.tutor_id;

                async function fetchBookedTimes(date) {
                    try {
                        const response = await fetch(`/api/check-availability?tutor_id=${currentTutorId}&date=${date}`);
                        const responseData = await response.json();
                        return responseData.bookedTimes || [];
                    } catch (error) {
                        console.error('Error fetching booked times:', error);
                        return [];
                    }
                }

                async function renderTimeSlots(date) {
                    const bookedTimes = await fetchBookedTimes(date);
                    timeSelectContainer.innerHTML = '';
                    
                    // Get current Malaysian time
                    const nowMalaysia = new Date().toLocaleString("en-US", {timeZone: "Asia/Kuala_Lumpur"});
                    const currentDate = new Date(nowMalaysia);
                    const currentHour = currentDate.getHours();
                    const isoCurrentDate = currentDate.toISOString().split('T')[0];
                    
                    let availableSlots = [];

                    timeSlots.forEach(slot => {
                        const slotStartTime = slot.split(' - ')[0];
                        const slotStartHour = parseInt(slotStartTime.split(':')[0]);
                        const slotIsPM = slotStartTime.includes('PM');
                        let slotHour24 = slotStartHour;
                        if (slotIsPM && slotStartHour !== 12) {
                            slotHour24 += 12;
                        } else if (!slotIsPM && slotStartHour === 12) {
                            slotHour24 = 0;
                        }

                        const isBooked = bookedTimes.includes(slot);
                        
                        // For current date, only show slots that are in the future
                        const isPastTime = date === isoCurrentDate && slotHour24 <= currentHour;
                        
                        // Only add slot if it's not booked and not in the past
                        if (!isBooked && !isPastTime) {
                            availableSlots.push(slot);
                        }

                        const label = document.createElement('label');
                        label.style.display = 'block';
                        label.style.marginBottom = '6px';
                        label.style.cursor = 'pointer';
                        
                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.value = slot;
                        checkbox.style.marginRight = '8px';

                        if (isBooked || isPastTime) {
                            checkbox.disabled = true;
                            label.style.color = '#9ca3af';
                            label.style.cursor = 'not-allowed';
                            if (isBooked) {
                                label.title = 'This time slot is already booked';
                            } else if (isPastTime) {
                                label.title = 'This time slot is in the past';
                            }
                        }

                        label.appendChild(checkbox);
                        label.appendChild(document.createTextNode(slot));
                        timeSelectContainer.appendChild(label);
                    });

                    // If no available slots for current date and it's current date, show message
                    if (availableSlots.length === 0 && date === isoCurrentDate) {
                        const noSlotsMsg = document.createElement('p');
                        noSlotsMsg.textContent = 'No available time slots for today. Please select a future date.';
                        noSlotsMsg.style.color = '#ef4444';
                        noSlotsMsg.style.fontStyle = 'italic';
                        timeSelectContainer.appendChild(noSlotsMsg);
                    }

                    const checkboxes = timeSelectContainer.querySelectorAll('input[type="checkbox"]:not(:disabled)');
                    checkboxes.forEach(cb => {
                        cb.addEventListener('change', updateTotalPrice);
                    });
                }

                renderTimeSlots(dateInput.value);

                dateInput.onchange = function() {
                    renderTimeSlots(dateInput.value);
                };

                let pricePerHour = data.subject_rate_per_hour || data.price_per_hour || 0;

                function updateTotalPrice() {
                    const checkedBoxes = document.querySelectorAll('#rescheduleTimeContainer input[type="checkbox"]:checked');
                    let totalHours = checkedBoxes.length;
                    const totalPrice = pricePerHour * totalHours;
                    totalPriceDisplay.textContent = `Total Price: RM${totalPrice.toFixed(2)}`;
                }

                updateTotalPrice();
                return; // Exit early for no availability case
            }

            // Handle tutors WITH availability (original logic)
            const currentDateSelect = document.getElementById('rescheduleDate');
            
            // Sort availability by date ascending
availability.sort((a, b) => {
    const dateA = new Date(a.date);
    const dateB = new Date(b.date);
    return dateA - dateB;
});
            // Populate date dropdown for tutors with availability
            availability.forEach(slot => {
                const option = document.createElement('option');
                option.value = slot.date;
                option.textContent = new Date(slot.date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' });
                currentDateSelect.appendChild(option);
            });

            // Rest of the availability logic...
            let pricePerHour = 0;
            if (availability.length > 0 && availability[0].time.length > 0) {
                pricePerHour = availability[0].time[0].price_per_hour || 0;
            }

            function updateTotalPrice() {
                const checkedBoxes = document.querySelectorAll('#rescheduleTimeContainer input[type="checkbox"]:checked');
                const selectedCount = checkedBoxes.length;
                const totalPrice = pricePerHour * selectedCount;
                totalPriceDisplay.textContent = `Total Price: RM${totalPrice}`;
            }

            function addCheckboxListeners() {
                const checkboxes = document.querySelectorAll('#rescheduleTimeContainer input[type="checkbox"]');
                checkboxes.forEach(cb => {
                    cb.addEventListener('change', updateTotalPrice);
                });
            }

            // Populate time checkboxes based on first date
            const firstDateTimes = availability[0].time || [];
            firstDateTimes.forEach(slot => {
                const label = document.createElement('label');
                label.style.display = 'block';
                label.style.marginBottom = '6px';
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.value = slot.time || slot;
                checkbox.style.marginRight = '8px';
                label.appendChild(checkbox);
                label.appendChild(document.createTextNode(slot.time || slot));
                timeSelectContainer.appendChild(label);
            });

            addCheckboxListeners();

            // Update time checkboxes when date changes
            currentDateSelect.onchange = function() {
                const selectedDate = currentDateSelect.value;
                timeSelectContainer.innerHTML = '';
                const selectedSlot = availability.find(slot => slot.date === selectedDate);
                if (selectedSlot) {
                    selectedSlot.time.forEach(slot => {
                        const label = document.createElement('label');
                        label.style.display = 'block';
                        label.style.marginBottom = '6px';
                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.value = slot.time || slot;
                        checkbox.style.marginRight = '8px';
                        label.appendChild(checkbox);
                        label.appendChild(document.createTextNode(slot.time || slot));
                        timeSelectContainer.appendChild(label);
                    });
                    addCheckboxListeners();
                }
                updateTotalPrice();
            };

            updateTotalPrice();
        })
        .catch(error => {
            console.error('Error fetching availability:', error);
            feedback.style.color = '#ef4444';
            feedback.textContent = 'Error loading availability. Please try again.';
        });

    confirmBtn.onclick = function() {
        const dateSelect = document.getElementById('rescheduleDate');
        const date = dateSelect.value;
        const checkedBoxes = document.querySelectorAll('#rescheduleTimeContainer input[type="checkbox"]:checked');
        const selectedTimes = Array.from(checkedBoxes).map(cb => cb.value);
        
        if (!date || selectedTimes.length === 0) {
            feedback.style.color = '#ef4444';
            feedback.textContent = 'Please select both date and at least one time slot.';
            return;
        }
        
        console.log('Reschedule request payload:', { session_id: sessionId, date: date, time: selectedTimes });
        confirmBtn.disabled = true;
        feedback.style.color = '#6366f1';
        feedback.textContent = 'Rescheduling...';
        
        fetch('/api/reschedule-session', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                session_id: sessionId, 
                date: date, 
                time: selectedTimes.join(', ') 
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Reschedule response:', data);
            confirmBtn.disabled = false;
            if (data.error) {
                feedback.style.color = '#ef4444';
                feedback.textContent = data.error;
                return;
            }
            feedback.style.color = '#10b981';
            feedback.textContent = 'Session rescheduled successfully!';
            updateSessionsList(data.sessions);
            if (window.calendar) window.calendar.refetchEvents();
            setTimeout(() => { modal.style.display = 'none'; }, 1500);
        })
        .catch(() => {
            confirmBtn.disabled = false;
            feedback.style.color = '#ef4444';
            feedback.textContent = 'Failed to reschedule. Please try again.';
        });
    };

    cancelBtn.onclick = function() {
        modal.style.display = 'none';
    };
}    </script>
@endsection













