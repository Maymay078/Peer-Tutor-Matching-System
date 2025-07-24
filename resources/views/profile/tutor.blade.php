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
    <style>
   header {
            background-color: #4f46e5;
            padding: 16px 0;
        }
        .header-container {
            max-w-5xl mx-auto flex;
            max-width: 100%;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-title {
            color: white;
            font-weight: bold;
            font-size: 1.125rem;
        }
        .logo-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .logo-circle {
            width: 100px;
            height: 100px;
            padding: 8px;
            background: white;
            border-radius: 9999px;
            border: 4px solid white;
        }
        .logo-svg {
            width: 100%;
            height: 100%;
        }
        .system-name {
            color: white;
            font-size: 0.875rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }
        .header-links {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .header-links a {
            color: white;
            font-weight: 600;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border: 1px solid white;
            border-radius: 6px;
            transition: background 0.3s;
        }
        .header-links a:hover {
            background-color: #e0e7ff;
            color: #4f46e5;
        }
        .icon-link {
            color: white;
            font-size: 1.25rem;
            position: relative;
            border: 1px solid white;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
        }
        .icon-link:hover {
            background-color: #e0e7ff;
            color: #4f46e5;
        }
    .add-date-button {
        background-color: #10b981;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        transition: background-color 0.3s;
    }

    /* Adjust grid layout to ensure two dates per row */
    .availability-section #availability_container {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* Limit to 3 columns max */
        gap: 20px; /* Create space between the date sections */
        max-width: 100%; /* Ensure it takes the full width of the container */
        width: 100%;
    }

    .date-section {
        padding: 1rem; /* Add padding inside the date section for better spacing */
        background-color: #f9fafb; /* Light background for better visibility */
        border: 1px solid #e5e7eb; /* Light border to separate the sections */
        border-radius: 0.375rem; /* Rounded corners for each date section */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        max-width: 100%; /* Ensure no overflow */
        width: 100%; /* Ensure full width */
    }
    .date-section.mb-4.w-full.md\:w-\[calc\(50\%-0\.75rem\)\] {
        width: 100% !important; /* Override fixed width on medium screens */
    }

    /* Ensure proper alignment and some margin for the date section */
    .date-section label {
        font-weight: bold;
        margin-bottom: 0.5rem;
    }

    /* Styling for the Time Slots */
    .time-slots {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* Create 3 columns */
        gap: 10px;
        max-width: 100%; /* Ensure the slots stay within the container */
    }

    /* Move empty time slots to the right */
    .time-slot-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid #e5e7eb;
        padding: 8px;
        border-radius: 0.375rem;
        background-color: #ffffff;
        transition: background-color 0.3s ease;
        max-width: 100%; /* Make sure each label respects max width */
    }

    .time-slot-label input[type="checkbox"]:not(:checked) {
        justify-content: flex-end; /* Align unchecked boxes to the right */
    }

    .time-slot-label input[type="checkbox"]:checked {
        justify-content: flex-start; /* Keep checked boxes on the left */
    }

    /* Hover effect for time slots */
    .time-slot-label:hover {
        background-color: #f3f4f6;
    }

    /* Ensure remove button takes full width */
    .remove-date-button {
        display: block;
        width: 100%;
        margin-top: 10px;
    }

    /* Keep the date section label inside its container */
    .date-section input[type="date"] {
        max-width: 100%; /* Ensure no overflow of the date input */
    }
</style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <header>
            <div class="header-container">
                <div class="header-title">Tutor Profile</div>
                <div class="logo-wrapper">
                    <div class="logo-circle">
                        <svg
                            class="logo-svg"
                            viewBox="0 0 100 100"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M50 15 L85 25 L50 35 L15 25 Z"
                                fill="#2563eb"
                                stroke="#1d4ed8"
                                stroke-width="1"
                            />
                            <path d="M50 35 L50 45 L85 35 L85 25 Z" fill="#1d4ed8" />
                            <circle cx="85" cy="25" r="3" fill="#dc2626" />
                            <rect
                                x="25"
                                y="45"
                                width="50"
                                height="35"
                                rx="3"
                                fill="#3b82f6"
                                stroke="#2563eb"
                                stroke-width="1"
                            />
                            <rect x="25" y="45" width="25" height="35" rx="3" fill="#60a5fa" />
                            <line
                                x1="35"
                                y1="52"
                                x2="65"
                                y2="52"
                                stroke="white"
                                stroke-width="1"
                            />
                            <line
                                x1="35"
                                y1="58"
                                x2="65"
                                y2="58"
                                stroke="white"
                                stroke-width="1"
                            />
                            <line
                                x1="35"
                                y1="64"
                                x2="65"
                                y2="64"
                                stroke="white"
                                stroke-width="1"
                            />
                            <line
                                x1="35"
                                y1="70"
                                x2="60"
                                y2="70"
                                stroke="white"
                                stroke-width="1"
                            />
                            <circle cx="20" cy="30" r="2" fill="#fbbf24" opacity="0.7" />
                            <circle cx="80" cy="50" r="1.5" fill="#fbbf24" opacity="0.7" />
                            <circle cx="15" cy="60" r="1" fill="#fbbf24" opacity="0.7" />
                        </svg>
                    </div>
                    <span class="system-name">Peer Tutor Matching System</span>
                </div>
                <div class="header-links">
                    <a href="/home/tutor" class="icon-link" title="Home">
                        <i class="fas fa-home"></i>
                    </a>
                    <a href="{{ route('profile.show', auth()->user()->id) }}" class="icon-link" title="Profile">
                        <i class="fas fa-user"></i>
                    </a>
                    <a href="/chat/tutor" class="icon-link" title="Chat">
                        <i class="fas fa-comment-dots"></i>
                    </a>
                    <a href="/notifications" class="icon-link" title="Notifications">
                        <i class="fas fa-bell"></i>
                    </a>
                </div>
            </div>
        </header>
        <main>
            <div class="w-full max-w-full mx-auto bg-white rounded-xl shadow-md p-8 py-6 sm:p-4 sm:py-4">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
                    @csrf
                    @method('PATCH')
                    <div class="flex justify-between items-center mb-8">
                        <div class="flex items-center space-x-6">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="md:col-span-2 flex flex-col items-center">
                            @php
                                $profileImage = auth()->user()->profile_image;
                            @endphp
                            <div class="flex flex-col items-center">
                                @if($profileImage)
                                    <img src="{{ (Str::startsWith($profileImage, ['http://', 'https://'])) ? $profileImage : asset('storage/' . $profileImage) }}" alt="Profile Image" class="rounded-xl object-contain mx-auto block mb-4" style="width: 192px; height: 192px;" />
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->full_name) }}&background=random&color=fff" alt="Default Profile Image" class="rounded-xl object-contain mx-auto block mb-4" style="width: 192px; height: 192px;" />
                                @endif
                                <label for="profile_image" class="block text-lg font-semibold text-gray-700 text-center mt-2">Profile Image</label>
                                <input type="file" name="profile_image" id="profile_image" class="mt-2 block w-full max-w-xs rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg text-center" />
                            </div>
                        </div>
                        <div>
                            <label for="full_name" class="block text-lg font-semibold text-gray-700">Full Name</label>
                            <input type="text" name="full_name" id="full_name" value="{{ old('full_name', auth()->user()->full_name) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg" />
                        </div>
                        <div>
                            <label for="username" class="block text-lg font-semibold text-gray-700">Username</label>
                            <input type="text" name="username" id="username" value="{{ old('username', auth()->user()->username) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg" />
                        </div>
                         <div>
                            <label for="date_of_birth" class="block text-lg font-semibold text-gray-700">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" max="2007-12-31" value="{{ old('date_of_birth', $user->date_of_birth) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg" />
                        </div>
                        <div>
                            <label for="email" class="block text-lg font-semibold text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg" />
                        </div>
                        <div>
                            <label for="phone_number" class="block text-lg font-semibold text-gray-700">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', auth()->user()->phone_number) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg" />
                        </div>
                        <div>
                            <label for="payment_details" class="block text-lg font-semibold text-gray-700">Payment Details</label>
                            @php
                                $paymentDetails = old('payment_details', auth()->user()->tutor->payment_details ?? '');
                                $paymentOptions = [
                                    'cash' => 'Cash',
                                    'online_banking' => 'Online Banking',
                                ];
                                $selectedPayments = [];
                                if ($paymentDetails) {
                                    if (is_array($paymentDetails)) {
                                        $selectedPayments = $paymentDetails;
                                    } elseif (is_string($paymentDetails)) {
                                        $decoded = json_decode($paymentDetails, true);
                                        if (is_array($decoded)) {
                                            $selectedPayments = $decoded;
                                        } else {
                                            $lower = strtolower($paymentDetails);
                                            if (strpos($lower, 'cash') !== false && strpos($lower, 'online') !== false) {
                                                $selectedPayments = ['cash', 'online_banking'];
                                            } elseif (strpos($lower, 'cash') !== false) {
                                                $selectedPayments = ['cash'];
                                            } elseif (strpos($lower, 'online') !== false) {
                                                $selectedPayments = ['online_banking'];
                                            }
                                        }
                                    }
                                }
                            @endphp
                            <div class="flex gap-4 mt-2">
                                @foreach($paymentOptions as $value => $label)
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="payment_details[]" value="{{ $value }}"
                                            {{ in_array($value, old('payment_details', $selectedPayments)) ? 'checked' : '' }}>
                                        {{ $label }}
                                    </label>
                                @endforeach
                            </div>
                            <small class="text-gray-500">Select one or both payment methods.</small>
                        </div>
                        {{-- Subject Expertise and Payment Details --}}
                        <div>
                            <label class="block text-lg font-semibold text-gray-700">Subject Expertise & Hourly Rate</label>
                            @php
                                $expertise = auth()->user()->tutor->expertise ?? [];
                                if (is_string($expertise)) {
                                    $expertise = json_decode($expertise, true) ?: [];
                                }
                            @endphp
<div id="subjects-container" class="mt-2 space-y-2">
    @if(!empty($expertise))
        @foreach($expertise as $i => $subject)
            <div class="subject-rate-pair flex gap-2 items-center mb-2" id="subject{{ $i + 1 }}">
                <input type="text" name="expertise[{{ $i }}][name]" value="{{ old('expertise.' . $i . '.name', $subject['name'] ?? '') }}" placeholder="Subject Name" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg" required />
                <input type="number" name="expertise[{{ $i }}][price_per_hour]" value="{{ old('expertise.' . $i . '.price_per_hour', $subject['price_per_hour'] ?? '0') }}" placeholder="Hourly Rate" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg" min="0" step="0.01" required />
                <button type="button" class="remove-subject-btn px-2 py-1 rounded-full bg-red-500 text-white flex items-center justify-center" style="width:28px;height:28px;" title="Remove Subject">
                    &times;
                </button>
            </div>
        @endforeach
    @endif
</div>
<div class="mb-8">
    <button type="button" id="add-subject-btn" class="add-subject-button mt-6 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700" >Add Subject (Max 5)</button>
</div>
                        </div>
</div>
                        <div>
                            <label for="availability" class="block text-lg font-semibold text-gray-700">Availability</label>
                            @php
                                $availability = $availability ?? '[]';
                                // Defensive: decode JSON if string, else use as array
                                if (is_string($availability)) {
                                    $availability = json_decode($availability, true) ?: [];
                                }
                                if (!is_array($availability)) {
                                    $availability = [];
                                }
                                $now = \Carbon\Carbon::now();
                                $upcomingAvailability = [];
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
                                            $upcomingAvailability[] = [
                                                'date' => $slotDateVal,
                                                'time' => $filteredTimes,
                                            ];
                                        }
                                    }
                                }
                                $allTimes = [
                                    '8:00 AM - 9:00 AM',
                                    '9:00 AM - 10:00 AM',
                                    '10:00 AM - 11:00 AM',
                                    '11:00 AM - 12:00 PM',
                                    '12:00 PM - 1:00 PM',
                                    '1:00 PM - 2:00 PM',
                                    '2:00 PM - 3:00 PM',
                                    '3:00 PM - 4:00 PM',
                                    '4:00 PM - 5:00 PM',
                                    '5:00 PM - 6:00 PM',
                                ];
                            @endphp
                                <div class="availability-section" id="availability_section">
                                    <div id="availability_container" class="grid grid-cols-1 gap-6">
@if (count($upcomingAvailability) === 0)
    <div class="date-section mb-4 w-full md:w-[calc(50%-0.75rem)]" id="date1">
        <label for="date1_input" class="font-semibold text-indigo-600 mb-1 block">Date 1:</label>
        <input type="date" name="availability[date1]" id="date1_input" required min="{{ date('Y-m-d') }}" class="rounded-md border border-gray-300 p-2 focus:ring-indigo-500 focus:border-indigo-500 w-full md:w-48" />
        <div class="time-slots grid grid-cols-3 gap-4 mt-4 max-w-md">
            @foreach($allTimes as $timeRange)
                <label class="time-slot-label flex items-center gap-4 cursor-pointer border border-gray-300 rounded-md px-4 py-3 hover:bg-gray-100 transition w-full max-w-xs min-w-[180px]">
                    <input type="checkbox" name="availability[time1][]" value="{{ $timeRange }}" class="form-checkbox h-5 w-5 text-indigo-600" />
                    <span class="select-none whitespace-nowrap">{{ $timeRange }}</span>
                </label>
            @endforeach
        </div>
        <button type="button" class="remove-date-button rounded-md bg-red-600 text-white px-3 py-1 mt-2 hover:bg-red-700" onclick="removeDateSection(this)">Remove Date</button>
    </div>
@else
    @foreach ($upcomingAvailability as $index => $slot)
        <div class="date-section mb-4 w-full md:w-[calc(50%-0.75rem)]" id="date{{ $index + 1 }}">
            <label for="date{{ $index + 1 }}_input" class="font-semibold text-indigo-600 mb-1 block">Date {{ $index + 1 }}:</label>
            <input type="date" name="availability[date{{ $index + 1 }}]" id="date{{ $index + 1 }}_input" required min="{{ date('Y-m-d') }}" value="{{ old('availability.date' . ($index + 1), $slot['date']) }}" class="rounded-md border border-gray-300 p-2 focus:ring-indigo-500 focus:border-indigo-500 w-full md:w-48" />
            <!-- Time Slots Grid -->
            <div class="time-slots grid grid-cols-3 gap-4 mt-4 max-w-md">
                @foreach($allTimes as $timeRange)
                    <label class="time-slot-label flex items-center gap-4 cursor-pointer border border-gray-300 rounded-md px-4 py-3 hover:bg-gray-100 transition w-full max-w-xs min-w-[180px]">
                        <input type="checkbox" name="availability[time{{ $index + 1 }}][]" value="{{ $timeRange }}" {{ in_array($timeRange, $slot['time']) ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-indigo-600" />
                        <span class="select-none whitespace-nowrap">{{ $timeRange }}</span>
                    </label>
                @endforeach
            </div>
            <button type="button" class="remove-date-button rounded-md bg-red-600 text-white px-3 py-1 mt-2 hover:bg-red-700" onclick="removeDateSection(this)">Remove Date</button>
        </div>
    @endforeach
@endif
                                </div>
                                <button type="button" class="add-date-button rounded-md bg-green-600 text-white px-4 py-2 mt-4 hover:bg-green-700" id="add-date-btn">Add Another Date</button>
                            </div>
                            <script>
                                function removeDateSection(btn) {
                                    var section = btn.closest('.date-section');
                                    if (section) section.remove();
                                    renumberDateSections();
                                }
                                function addDateSection() {
                                    const container = document.getElementById('availability_container');
                                    const count = container.querySelectorAll('.date-section').length + 1;
                                    let timeSlotsHtml = '';
                                    @foreach($allTimes as $timeRange)
                                        timeSlotsHtml += `<label class="time-slot-label flex items-center gap-4 cursor-pointer border border-gray-300 rounded-md px-4 py-3 hover:bg-gray-100 transition w-full max-w-xs min-w-[180px]">
                                            <input type="checkbox" name="availability[time${count}][]" value="{{ $timeRange }}" class="form-checkbox h-5 w-5 text-indigo-600" />
                                            <span class="select-none whitespace-nowrap">{{ $timeRange }}</span>
                                        </label>`;
                                    @endforeach
                                    const dateSection = document.createElement('div');
                                    dateSection.className = 'date-section mb-4 w-full md:w-[calc(50%-0.75rem)]';
                                    dateSection.id = `date${count}`;
                                    dateSection.innerHTML = `
                                        <label for="date${count}_input" class="font-semibold text-indigo-600 mb-1 block">Date ${count}:</label>
                                        <input type="date" name="availability[date${count}]" id="date${count}_input" required min="{{ date('Y-m-d') }}" class="rounded-md border border-gray-300 p-2 focus:ring-indigo-500 focus:border-indigo-500 w-full md:w-48" />
                                        <div class="time-slots grid grid-cols-3 gap-4 mt-4 max-w-md">
                                            ${timeSlotsHtml}
                                        </div>
                                        <button type="button" class="remove-date-button rounded-md bg-red-600 text-white px-3 py-1 mt-2 hover:bg-red-700" onclick="removeDateSection(this)">Remove Date</button>
                                    `;
                                    container.appendChild(dateSection);
                                    renumberDateSections();
                                    initFlatpickrOnDates();
                                }
                                function initFlatpickrOnDates() {
                                    if (window.flatpickr) {
                                        document.querySelectorAll('.date-picker').forEach(function(input) {
                                            if (!input._flatpickr) {
                                                if (input.hasAttribute('readonly')) {
                                                    flatpickr(input, { 
                                                        minDate: 'today', 
                                                        dateFormat: 'Y-m-d',
                                                        allowInput: false,
                                                        clickOpens: false,
                                                    });
                                                } else {
                                                    flatpickr(input, { 
                                                        minDate: 'today', 
                                                        dateFormat: 'Y-m-d',
                                                    });
                                                }
                                            }
                                        });
                                    }
                                }
                                function renumberDateSections() {
                                    const sections = document.querySelectorAll('#availability_container .date-section');
                                    sections.forEach((section, idx) => {
                                        const num = idx + 1;
                                        section.id = `date${num}`;
                                        // Update label
                                        const label = section.querySelector('label[for^="date"]');
                                        if (label) {
                                            label.textContent = `Date ${num}:`;
                                            label.setAttribute('for', `date${num}_input`);
                                        }
                                        // Update input
                                        const input = section.querySelector('input[type="date"]');
                                        if (input) {
                                            input.name = `availability[date${num}]`;
                                            input.id = `date${num}_input`;
                                        }
                                        // Update time slot checkboxes
                                        const checkboxes = section.querySelectorAll('input[type="checkbox"]');
                                        checkboxes.forEach(cb => {
                                            cb.name = `availability[time${num}][]`;
                                        });
                                        // Update remove button id (optional)
                                        const removeBtn = section.querySelector('.remove-date-button');
                                        if (removeBtn) {
                                            removeBtn.id = `remove-date${num}`;
                                        }
                                    });
                                }
                                document.addEventListener('DOMContentLoaded', function() {
                                    document.getElementById('add-date-btn').addEventListener('click', addDateSection);
                                    // Attach removeDateSection to all existing remove buttons
                                    document.querySelectorAll('.remove-date-button').forEach(function(btn) {
                                        btn.onclick = function() { removeDateSection(this); };
                                    });
                                });
                            </script>
                        </div>
                    

        <!-- Save Changes button (inside the profile update form) -->
          <div class="md:col-span-2 flex justify-end mt-8 space-x-4">
                        <button type="submit" id="save-changes-btn" class="px-6 py-4 bg-indigo-600 text-white rounded-lg text-lg font-semibold hover:bg-indigo-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50" disabled>
                            Save Changes
                        </button>
                    </div>
                </form>
                <div class="flex justify-end mt-4 space-x-4">
                    <form method="POST" action="{{ route('logout') }}" id="logoutForm">
                        @csrf
                        <button type="submit" class="px-4 py-4 border border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition text-lg font-semibold">
                            Logout
                        </button>
                    </form>
                    <form method="POST" action="{{ route('profile.destroy') }}" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="password" id="delete-password-input" />
                        <button type="button" id="delete-account-btn" class="px-4 py-4 border border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition text-lg font-semibold">
                            Delete Account
                        </button>
                        @if(session('delete_error'))
                            <div class="text-red-600 mt-2 text-sm">{{ session('delete_error') }}</div>
                        @endif
                    </form>
    </div>

    <!-- Delete Account Modal -->
<div id="deleteAccountModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden" role="dialog" aria-modal="true" aria-labelledby="deleteAccountModalTitle">
    <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md relative">
        <h2 id="deleteAccountModalTitle" class="text-lg font-semibold mb-4 text-red-600">Confirm Account Deletion</h2>
        <p class="mb-4">Please enter your password to confirm account deletion. This action cannot be undone.</p>
        <input type="password" id="modal-password" class="rounded border border-gray-300 px-3 py-2 w-full mb-4" placeholder="Enter your password" autocomplete="current-password" required />
        <div class="flex justify-end space-x-2">
            <button type="button" id="cancelDeleteBtn" class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300">Cancel</button>
            <button type="button" id="confirmDeleteBtn" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Delete</button>
        </div>
        <button type="button" class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl leading-none" aria-label="Close" id="closeDeleteModal">&times;</button>
    </div>
</div>

    <script>
    function confirmDeleteAccount() {
        return confirm('Are you sure you want to delete your account? This action cannot be undone.');
    }

    // Save Changes button logic (AJAX, same as student)
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('profileForm');
        const saveBtn = document.getElementById('save-changes-btn');
        if (!form || !saveBtn) return;
        const initialValues = new Map();
        function getInputValue(input) {
            if (input.type === 'file') {
                return input.files.length > 0 ? input.files[0].name : '';
            } else if (input.type === 'checkbox' || input.type === 'radio') {
                return input.checked ? 'checked' : 'unchecked';
            } else if (input.multiple) {
                return Array.from(input.selectedOptions).map(opt => opt.value).join(',');
            } else {
                return input.value;
            }
        }
        Array.from(form.elements).forEach(input => {
            if (input.name) {
                initialValues.set(input.name, getInputValue(input));
            }
        });
        function checkFormChanged() {
            let isChanged = false;
            Array.from(form.elements).forEach(input => {
                if (input.name) {
                    const initialVal = initialValues.get(input.name) || '';
                    const currentVal = getInputValue(input);
                    if (initialVal !== currentVal) {
                        isChanged = true;
                    }
                }
            });
            saveBtn.disabled = !isChanged;
        }
        form.addEventListener('input', checkFormChanged);
        form.addEventListener('change', checkFormChanged);
        checkFormChanged();
        // AJAX submit
        form.addEventListener('submit', function(event) {
            if (saveBtn.disabled) {
                event.preventDefault();
                alert('There are no changes made.');
                return;
            }
            event.preventDefault();
            const formData = new FormData(form);
            saveBtn.disabled = true;
            saveBtn.textContent = 'Saving...';
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(async response => {
                saveBtn.textContent = 'Save Changes';
                if (response.ok) {
                    // Update initial values to new values
                    Array.from(form.elements).forEach(input => {
                        if (input.name) {
                            initialValues.set(input.name, getInputValue(input));
                        }
                    });
                    saveBtn.disabled = true;
                    showProfileStatus('Profile updated successfully!', false);
                } else {
                    let msg = 'Failed to update profile.';
                    try {
                        const data = await response.json();
                        if (data.errors) {
                            msg = Object.values(data.errors).flat().join('\n');
                        }
                    } catch {}
                    showProfileStatus(msg, true);
                    saveBtn.disabled = false;
                }
            })
            .catch(() => {
                saveBtn.textContent = 'Save Changes';
                showProfileStatus('Failed to update profile.', true);
                saveBtn.disabled = false;
            });
        });
        function showProfileStatus(message, isError) {
            let statusDiv = document.getElementById('profile-status-msg');
            if (!statusDiv) {
                statusDiv = document.createElement('div');
                statusDiv.id = 'profile-status-msg';
                form.parentNode.insertBefore(statusDiv, form);
            }
            statusDiv.textContent = message;
            statusDiv.style.color = isError ? 'red' : 'green';
            statusDiv.style.marginBottom = '1em';
            statusDiv.style.fontWeight = 'bold';
            setTimeout(() => { statusDiv.textContent = ''; }, 4000);
        }
    });
    </script>


<script>
// Delete Account Modal logic
document.addEventListener('DOMContentLoaded', function() {
    const deleteBtn = document.getElementById('delete-account-btn');
    const modal = document.getElementById('deleteAccountModal');
    const cancelBtn = document.getElementById('cancelDeleteBtn');
    const closeBtn = document.getElementById('closeDeleteModal');
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const passwordInput = document.getElementById('modal-password');
    const form = document.getElementById('deleteForm');
    const hiddenPassword = document.getElementById('delete-password-input');

    function openModal() {
        modal.classList.remove('hidden');
        passwordInput.value = '';
        passwordInput.focus();
    }
    function closeModal() {
        modal.classList.add('hidden');
        passwordInput.value = '';
    }
    deleteBtn.addEventListener('click', function(e) {
        e.preventDefault();
        openModal();
    });
    cancelBtn.addEventListener('click', function() {
        closeModal();
    });
    closeBtn.addEventListener('click', function() {
        closeModal();
    });
    confirmBtn.addEventListener('click', function() {
        if (!passwordInput.value) {
            passwordInput.focus();
            passwordInput.classList.add('border-red-500');
            return;
        }
        hiddenPassword.value = passwordInput.value;
        closeModal();
        form.submit();
    });
    // Accessibility: close modal on Escape
    modal.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
});
function initFlatpickrOnDates() {
    if (window.flatpickr) {
        // Remove flatpickr initialization for availability inputs to avoid conflict with native date inputs
        // Only initialize flatpickr for other date inputs like dob
        const dobInput = document.getElementById('date_of_birth');
        if (dobInput && !dobInput._flatpickr) {
            flatpickr(dobInput, { 
                maxDate: 'today', 
                dateFormat: 'Y-m-d',
            });
        }
    }
}
</script>

<script>
    // Subject-Rate pairs functionality
const addSubjectBtn = document.getElementById('add-subject-btn');
const subjectsContainer = document.getElementById('subjects-container');
let subjectCount = subjectsContainer ? subjectsContainer.querySelectorAll('.subject-rate-pair').length : 0;
const maxSubjects = 5;

function updateAddSubjectButton() {
    const currentPairs = subjectsContainer.querySelectorAll('.subject-rate-pair').length;
    if (currentPairs >= maxSubjects) {
        addSubjectBtn.disabled = true;
        addSubjectBtn.textContent = `Maximum ${maxSubjects} subjects reached`;
    } else {
        addSubjectBtn.disabled = false;
        addSubjectBtn.textContent = `Add Subject (Max ${maxSubjects})`;
    }
}

function updateRemoveSubjectButtons() {
    const pairs = subjectsContainer.querySelectorAll('.subject-rate-pair');
    pairs.forEach((pair, index) => {
        const removeBtn = pair.querySelector('.remove-subject-btn');
        if (removeBtn) {
            if (pairs.length === 1) {
                removeBtn.style.display = 'block';
                removeBtn.onclick = () => alert('Cannot remove the only subject. At least one subject is required.');
            } else {
                removeBtn.style.display = 'block';
                removeBtn.onclick = function() {
                    pair.remove();
                    updateAddSubjectButton();
                    updateRemoveSubjectButtons();
                    reindexSubjects();
                };
            }
        }
    });
}

function reindexSubjects() {
    const pairs = subjectsContainer.querySelectorAll('.subject-rate-pair');
    pairs.forEach((pair, index) => {
        const newIndex = index + 1;
        pair.id = `subject${newIndex}`;
        const subjectLabel = pair.querySelector('label[for^="subjects_"]');
        if (subjectLabel) {
            subjectLabel.textContent = `Subject ${newIndex}:`;
            subjectLabel.setAttribute('for', `subjects_${index}`);
        }
        const subjectInput = pair.querySelector('input[id^="subjects_"]');
        if (subjectInput) {
            subjectInput.id = `subjects_${index}`;
            subjectInput.name = `expertise[${index}][name]`;
        }
        const rateLabel = pair.querySelector('label[for^="rates_"]');
        if (rateLabel) {
            rateLabel.setAttribute('for', `rates_${index}`);
        }
        const rateInput = pair.querySelector('input[id^="rates_"]');
        if (rateInput) {
            rateInput.id = `rates_${index}`;
            rateInput.name = `expertise[${index}][price_per_hour]`;
        }
        const removeBtn = pair.querySelector('.remove-subject-btn');
        if (removeBtn) {
            removeBtn.id = `remove-subject${newIndex}`;
        }
    });
    subjectCount = pairs.length;
}

if (addSubjectBtn && subjectsContainer) {
    addSubjectBtn.addEventListener('click', () => {
        const currentPairs = subjectsContainer.querySelectorAll('.subject-rate-pair').length;
        if (currentPairs >= maxSubjects) return;
        const nextIndex = currentPairs + 1;
        const newPair = document.createElement('div');
        newPair.classList.add('subject-rate-pair', 'flex', 'gap-2', 'items-center', 'mb-2');
        newPair.id = `subject${nextIndex}`;
        newPair.innerHTML = `
                <input type="text" id="subjects_${currentPairs}" name="expertise[${currentPairs}][name]" placeholder="Enter subject name" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg" required />
                <input type="number" id="rates_${currentPairs}" name="expertise[${currentPairs}][price_per_hour]" min="0" step="0.01" placeholder="Enter hourly rate" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg" required />
                <button type="button" class="remove-subject-btn px-2 py-1 rounded-full bg-red-500 text-white flex items-center justify-center" style="width:28px;height:28px;" title="Remove Subject">&times;</button>
        `;
        subjectsContainer.appendChild(newPair);
        updateAddSubjectButton();
        updateRemoveSubjectButtons();
    });

    updateAddSubjectButton();
    updateRemoveSubjectButtons();
}
</script>
  
  </body>
  </html>

