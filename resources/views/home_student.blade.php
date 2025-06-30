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
        @media (max-width: 1200px) {
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
}

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

        <div class="main-content" style="flex-grow: 1; width: 100%; max-width: 100%; margin: 0;">
            <!-- My Schedule Title Above Calendar -->
            <div class="my-schedule-title">My Schedule</div>

            <!-- Calendar Section -->
            <section aria-label="Calendar" style="background: white; border-radius: 15px; box-shadow: 0 2px 12px rgba(0,0,0,0.1); padding: 30px; flex: 1 1 100px; width: 700px; margin-left: auto; margin-right: auto;">
                <div id="calendar"></div>
            </section>

            <!-- Featured Tutors Section -->
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

                    <article class="tutor-card">
                        <img src="{{ $imageSrc }}" alt="Profile Image" class="tutor-image" />
                        <div class="tutor-info">
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
                                                    Time: {{ is_array($slot['time']) ? implode(', ', $slot['time']) : ($slot['time'] ?? '') }}
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
                        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px;">
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
            var calendar = new FullCalendar.Calendar(calendarEl, {
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

            });
            calendar.render();
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
@endsection
