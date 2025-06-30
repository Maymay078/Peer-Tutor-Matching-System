<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Session Scheduling</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <style>
        body {
            margin: 0;
            font-family: "Figtree", sans-serif;
            background-color: #f9fafb;
        }
        header {
            background-color: #4f46e5;
            padding: 16px 0;
        }
        .header-container {
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
        .icon-link {
            color: white;
            font-size: 1.25rem;
            position: relative;
            border: 1px solid white;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            transition: background-color 0.3s;
        }
        .icon-link:hover {
            background-color: #e0e7ff;
            color: #4f46e5;
        }
        .card {
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .calendar {
            width: 100%;
            border-collapse: collapse;
        }

        .calendar th, .calendar td {
            text-align: center;
            padding: 10px;
        }

        .calendar td {
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .calendar td:hover {
            background-color: #f3f4f6;
        }

        .calendar .selected {
            background-color: #6366f1;
            color: white;
        }

        .section-header {
            font-size: 1.5rem;
            font-weight: 600;
            color: #374151;
        }

        .section-content {
            margin-top: 20px;
            font-size: 1.2rem;
        }

        .input-field {
            border-radius: 9999px;
            border: 1px solid #d1d5db;
            padding: 12px 20px;
            width: 100%;
        }

        .input-field:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.3);
        }

        .button {
            background-color: #6366f1;
            color: white;
            border-radius: 9999px;
            padding: 12px 30px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #4f46e5;
        }

        .disabled {
            cursor: not-allowed;
            background-color: #d1d5db;
        }
        .tutor-detail {
            font-size: 1.5rem;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-gray-100 font-sans">

<header>
    <div class="header-container">
        <div class="header-title">Session Scheduling</div>

        <div class="logo-wrapper">
            <div class="logo-circle">
                <svg class="logo-svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <path d="M50 15 L85 25 L50 35 L15 25 Z" fill="#2563eb" stroke="#1d4ed8" stroke-width="1"/>
                    <path d="M50 35 L50 45 L85 35 L85 25 Z" fill="#1d4ed8"/>
                    <circle cx="85" cy="25" r="3" fill="#dc2626"/>
                    <rect x="25" y="45" width="50" height="35" rx="3" fill="#3b82f6" stroke="#2563eb" stroke-width="1"/>
                    <rect x="25" y="45" width="25" height="35" rx="3" fill="#60a5fa"/>
                    <line x1="35" y1="52" x2="65" y2="52" stroke="white" stroke-width="1"/>
                    <line x1="35" y1="58" x2="65" y2="58" stroke="white" stroke-width="1"/>
                    <line x1="35" y1="64" x2="65" y2="64" stroke="white" stroke-width="1"/>
                    <line x1="35" y1="70" x2="60" y2="70" stroke="white" stroke-width="1"/>
                    <circle cx="20" cy="30" r="2" fill="#fbbf24" opacity="0.7"/>
                    <circle cx="80" cy="50" r="1.5" fill="#fbbf24" opacity="0.7"/>
                    <circle cx="15" cy="60" r="1" fill="#fbbf24" opacity="0.7"/>
                </svg>
            </div>
            <span class="system-name">Peer Tutor Matching System</span>
        </div>

        <div class="header-links">
            <a href="/home/student" class="icon-link" title="Home">
                <i class="fas fa-home"></i>
            </a>
            <a href="{{ route('profile.show', auth()->user()->id ?? 1) }}" class="icon-link" title="Profile">
                <i class="fas fa-user"></i>
            </a>
            <a href="/chat" class="icon-link" title="Chat">
                <i class="fas fa-comment-dots"></i>
            </a>
            <a href="/notifications" class="icon-link" title="Notifications">
                <i class="fas fa-bell"></i>
            </a>
        </div>
    </div>
</header>
<!-- Header End -->

<!-- Tutor Details Section -->
<div class="card mt-6">
    <div class="section-header">Tutor Details</div>
    <div class="section-content mt-2 flex items-center gap-4 justify-center">
        <div class="flex-shrink-0">
            <img src="{{ $tutor->user->profile_image ?? '/default-profile.png' }}" alt="Tutor Profile Image" class="w-32 h-32 rounded-full object-cover border border-gray-300" />
        </div>
        <div class="tutor-detail">
            <div><strong>Name:</strong> {{ $tutor->user->full_name ?? 'N/A' }}</div>
            <div><strong>Email:</strong> {{ $tutor->user->email ?? 'N/A' }}</div>
            <div><strong>Overall Rating:</strong> 
                @php
                    $rating = round($tutor->rating ?? 0);
                @endphp
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $rating)
                        <i class="fas fa-star" style="color: #fbbf24;"></i>
                    @else
                        <i class="fas fa-star" style="color: gray;"></i>
                    @endif
                @endfor
            </div>
        </div>
    </div>
</div>

<div class="flex flex-col md:flex-row gap-6 mt-6">
    <!-- Availability Section -->
    <div class="card md:w-1/2">
        <div class="section-header">Available Months
            <span id="available-months" class="ml-2 text-sm text-gray-600"></span>
        </div>
        <div class="section-content mt-2">
            <div class="flex items-center justify-between mb-2">
                <button id="prev-month-btn" class="button bg-gray-300 text-gray-700 hover:bg-gray-400">Previous</button>
                <div id="month-year" class="font-semibold text-lg"></div>
                <button id="next-month-btn" class="button bg-gray-300 text-gray-700 hover:bg-gray-400">Next</button>
            </div>
            <table class="calendar" id="calendar">
                <thead>
                    <tr>
                        <th>Mon</th>
                        <th>Tue</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                        <th>Sat</th>
                        <th>Sun</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

    <!-- Session Details Section -->
    <div class="card md:w-1/2">
        <div class="section-header">Session Details</div>
        <div class="section-content mt-2">
            <label for="subject" class="block text-lg font-medium text-gray-700">Subject</label>
            <select id="subject" class="input-field mt-2" onchange="updatePrice()"></select>

            <label for="payment-method" class="block text-lg font-medium text-gray-700 mt-4">Payment Method</label>
            @if ($tutor->payment_details === 'Cash' || $tutor->payment_details === 'Online Banking')
                <input type="text" id="payment-method" class="input-field mt-2" value="{{ $tutor->payment_details }}" readonly />
            @else
                <select id="payment-method" class="input-field mt-2">
                    <option value="Cash">Cash</option>
                    <option value="Online Banking">Online Banking</option>
                </select>
            @endif

            <label for="date" class="block text-lg font-medium text-gray-700 mt-4">Date</label>
            <input type="text" id="date" class="input-field mt-2" disabled />

            <label for="time" class="block text-lg font-medium text-gray-700 mt-4">Time</label>
            <input type="text" id="time" class="input-field mt-2" disabled />

            <label for="total-price" class="block text-lg font-medium text-gray-700 mt-4">Total Payment</label>
            <input type="text" id="total-price" class="input-field mt-2" disabled />
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="flex justify-between mt-6">
    <button class="button" id="book-session-btn">Book Session</button>
    <button class="button bg-gray-400" id="cancel-btn">Cancel</button>
</div>

<script>
    // Pass PHP tutor data to JavaScript
@php
    // Fix: Only decode if string, else use as array
    $expertiseRaw = $tutor->expertise ?? [];
    if (is_string($expertiseRaw)) {
        $subjectsRaw = collect(json_decode($expertiseRaw, true) ?: []);
    } elseif (is_array($expertiseRaw)) {
        $subjectsRaw = collect($expertiseRaw);
    } else {
        $subjectsRaw = collect([]);
    }
    $uniqueSubjects = $subjectsRaw->map(function($subject) {
        return [
            'name' => $subject['name'] ?? ($subject->name ?? ''),
            'pricePerHour' => $subject['price_per_hour'] ?? ($subject->price_per_hour ?? 0),
        ];
    })->unique(function ($subject) {
        return strtolower(trim($subject['name']));
    })->values()->all();

    // Fix: Only decode if string, else use as array
    $availabilityRaw = $tutor->availability ?? [];
    if (is_string($availabilityRaw)) {
        $availabilityArr = json_decode($availabilityRaw, true) ?: [];
    } elseif (is_array($availabilityRaw)) {
        $availabilityArr = $availabilityRaw;
    } else {
        $availabilityArr = [];
    }
    $tutorData = [
        'name' => $tutor->user->full_name ?? 'N/A',
        'rating' => round($tutor->rating ?? 0),
        'paymentDetails' => $tutor->payment_details ?? 'N/A',
        'paymentMethods' => $paymentMethods ?? [],
        'subjects' => $uniqueSubjects,
        'availability' => collect($availabilityArr)->mapWithKeys(function($slot) {
            return [($slot['date'] ?? ($slot->date ?? '')) => ($slot['time'] ?? ($slot->time ?? []))];
        })->toArray()
    ];
@endphp

    // Pass the tutor data to JavaScript
    const tutor = @json($tutorData);

    // Payment method dropdown is now handled by static HTML options
    const paymentMethodElement = document.getElementById('payment-method');
    
    const calendarElement = document.getElementById('calendar');
    const subjectElement = document.getElementById('subject');
    let dateElement = document.getElementById('date');
    let timeElement = document.getElementById('time');
    const totalPriceElement = document.getElementById('total-price');

    let selectedDate = null;
    let selectedTime = null;

    let currentYear = new Date().getFullYear();
    let currentMonth = new Date().getMonth();

    // Extract unique months with availability
    const availableMonthsSet = new Set();
    Object.keys(tutor.availability).forEach(dateStr => {
        const date = new Date(dateStr);
        const monthYear = date.toLocaleString('default', { month: 'long', year: 'numeric' });
        availableMonthsSet.add(monthYear);
    });
    const availableMonths = Array.from(availableMonthsSet);

    // Display available months as badges or text
    const availableMonthsContainer = document.getElementById('available-months');
    if (availableMonths.length === 0) {
        availableMonthsContainer.innerHTML = '<span class="text-gray-500">N/A</span>';
    } else {
        availableMonthsContainer.innerHTML = availableMonths.map(m => `<span class="inline-block bg-blue-100 text-blue-800 text-lg px-2 py-1 rounded mr-1">${m}</span>`).join('');
    }

    function updateMonthYearHeader() {
        const monthYearStr = new Date(currentYear, currentMonth).toLocaleString('default', { month: 'long', year: 'numeric' });
        document.getElementById('month-year').textContent = monthYearStr;
    }

function generateCalendar() {
        updateMonthYearHeader();

        const firstDayOfMonth = new Date(currentYear, currentMonth, 1);
        const lastDayOfMonth = new Date(currentYear, currentMonth + 1, 0);

        const daysInMonth = lastDayOfMonth.getDate();
        let calendarHtml = '<tbody>';

        let date = 1;
        const today = new Date();
        today.setHours(0,0,0,0); // Normalize to midnight for comparison
        for (let week = 0; week < 6; week++) {
            calendarHtml += '<tr>';
            for (let day = 0; day < 7; day++) {
                if (date > daysInMonth) {
                    calendarHtml += '<td></td>';
                    continue;
                }
                const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                const currentDate = new Date(currentYear, currentMonth, date);
                currentDate.setHours(0,0,0,0);
                const isAvailable = tutor.availability.hasOwnProperty(dateStr);
                const isToday = today.getTime() === currentDate.getTime();
                const isPast = currentDate < today;
                let classes = '';
                let onclickAttr = '';
                if (isPast) {
                    classes = 'text-gray-400 cursor-not-allowed';
                } else if (isAvailable) {
                    classes = 'selected cursor-pointer';
                    onclickAttr = `selectDate('${dateStr}')`;
                } else {
                    classes = 'text-gray-400';
                }
                if (isToday) {
                    classes += ' bg-yellow-200';
                }
                calendarHtml += `<td class="${classes}" ${onclickAttr ? `onclick="${onclickAttr}"` : ''}>${date}</td>`;
                date++;
            }
            calendarHtml += '</tr>';
            if (date > daysInMonth) break;
        }

        calendarHtml += '</tbody>';
        calendarElement.innerHTML = calendarHtml;
    }

    let lastSelectedDate = null;

    function selectDate(dateStr) {
        selectedDate = dateStr;
        dateElement.value = new Date(dateStr).toLocaleDateString(undefined, { year: 'numeric', month: 'long', day: 'numeric' });
        // Only disable Book Session button if tutor has availability
        if (isTutorAvailable()) {
            const bookBtn = document.getElementById('book-session-btn');
            if (bookBtn) {
                bookBtn.disabled = true;
                bookBtn.classList.add('disabled');
            }
        }
        // Only update time slots if the date is different from the last selected date
        if (lastSelectedDate !== dateStr) {
            populateTimeSlots(dateStr);
            lastSelectedDate = dateStr;
        }
        updatePrice();
    }

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('cancel-btn').addEventListener('click', function() {
        window.location.href = '/home/student';
    });

    const bookBtn = document.getElementById('book-session-btn');
    const subjectElement = document.getElementById('subject');
    const dateElement = document.getElementById('date');
    let timeElement = document.getElementById('time');

    function validateForm() {
        const subjectSelected = subjectElement && subjectElement.value.trim() !== '';
        const dateSelected = dateElement && dateElement.value.trim() !== '';
        let timeSelected = false;
        if (timeElement) {
            if (timeElement.tagName === 'INPUT') {
                // For text input, consider non-empty value as valid
                timeSelected = timeElement.value.trim() !== '';
            } else if (timeElement.tagName === 'DIV') {
                // For custom dropdown container, check if any checkbox is checked
                const checkedBoxes = timeElement.querySelectorAll('input[type="checkbox"]:checked');
                timeSelected = checkedBoxes.length > 0;
            } else {
                // For other input types (e.g., select), check value
                timeSelected = timeElement.value && timeElement.value.trim() !== '';
            }
        }
        console.log('Validation:', {subject: subjectElement.value, date: dateElement.value, time: timeElement ? (timeElement.value || 'custom') : 'none', timeTag: timeElement ? timeElement.tagName : 'none', timeSelected});
        bookBtn.disabled = !(subjectSelected && dateSelected && timeSelected);
        if (bookBtn.disabled) {
            bookBtn.classList.add('disabled');
        } else {
            bookBtn.classList.remove('disabled');
        }
    }

    subjectElement.addEventListener('change', function() {
        // On subject change, update selectedDate and selectedTime from inputs
        if (dateElement) {
            // Convert displayed date (e.g. August 1, 2023) back to ISO format YYYY-MM-DD
            const parsedDate = new Date(dateElement.value);
            if (!isNaN(parsedDate)) {
                const year = parsedDate.getFullYear();
                const month = String(parsedDate.getMonth() + 1).padStart(2, '0');
                const day = String(parsedDate.getDate()).padStart(2, '0');
                selectedDate = `${year}-${month}-${day}`;
            } else {
                selectedDate = '';
            }
        }
        if (timeElement) {
            if (timeElement.tagName === 'SELECT' || timeElement.tagName === 'INPUT') {
                selectedTime = timeElement.value;
            } else if (timeElement.tagName === 'DIV') {
                const checkedBoxes = timeElement.querySelectorAll('input[type="checkbox"]:checked');
                selectedTime = Array.from(checkedBoxes).map(cb => cb.value);
            }
        }
        const subjectSelected = subjectElement && subjectElement.value.trim() !== '';
        const dateSelected = dateElement && dateElement.value.trim() !== '';

        // If no time selected, clear price
        if (!selectedTime || (Array.isArray(selectedTime) && selectedTime.length === 0) || (typeof selectedTime === 'string' && selectedTime.trim() === '')) {
            totalPriceElement.value = '';
        } else {
            // Update price if time selected
            updatePrice();
        }

        const timeSelectedValid = (Array.isArray(selectedTime) && selectedTime.length > 0) || (typeof selectedTime === 'string' && selectedTime.trim() !== '');

        const bookBtn = document.getElementById('book-session-btn');
        if (bookBtn) {
            // Only enable if all fields are filled
            bookBtn.disabled = !(subjectSelected && dateSelected && timeSelectedValid);
            if (bookBtn.disabled) {
                bookBtn.classList.add('disabled');
            } else {
                bookBtn.classList.remove('disabled');
            }
        }
        // Also update price when subject changes
        updatePrice();
    });

    dateElement.addEventListener('input', validateForm);

    // Use event delegation for timeElement changes because it may be replaced dynamically
    document.addEventListener('change', function(event) {
        if (event.target && (event.target.id === 'time' || event.target.id === 'time-select' || event.target.type === 'checkbox')) {
            // Update timeElement reference if replaced
            timeElement = document.getElementById('time') || document.getElementById('time-select') || timeElement;
            validateForm();
        }
    });

    validateForm();

    bookBtn.addEventListener('click', function() {
        if (bookBtn.disabled) {
            return;
        }
        const modalHtml = `
<div id="customConfirmModal" role="dialog" aria-modal="true" aria-labelledby="modalTitle" aria-describedby="modalDesc" style="display:flex; position:fixed; z-index:1000; left:0; top:0; width:100vw; height:100vh; background-color: rgba(0,0,0,0.5); align-items:center; justify-content:center;">
    <div class="modal-content" style="background-color:#fff; margin:auto; padding:20px 30px; border-radius:12px; max-width:400px; box-shadow:0 4px 15px rgba(0,0,0,0.3); text-align:center; font-family: Arial, sans-serif;">
        <div id="modalDesc" class="modal-message" style="margin-bottom:20px; font-size:1.1rem; color:#333;">
            Booking confirmation is being sent to selected tutor and will be notified once booking is confirmed.
        </div>
        <div class="modal-buttons" style="display:flex; justify-content:center; gap:20px;">
            <button id="confirmYes" type="button" style="padding:10px 25px; border:none; border-radius:8px; font-size:1rem; cursor:pointer; font-weight:600; background-color:#6366f1; color:white;">OK</button>
            <button id="confirmCancel" type="button" style="padding:10px 25px; border:none; border-radius:8px; font-size:1rem; cursor:pointer; font-weight:600; background-color:#e5e7eb; color:#374151;">Cancel</button>
        </div>
    </div>
</div>
        `;

        // Append modal to body
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = modalHtml;
        document.body.appendChild(tempDiv.firstElementChild);

        const modal = document.getElementById('customConfirmModal');
        const yesBtn = document.getElementById('confirmYes');
        const cancelBtn = document.getElementById('confirmCancel');

        yesBtn.addEventListener('click', function() {
            // Collect booking data
            const tutorId = {{ $tutor->id }};
            const subject = subjectElement.value;
            const date = selectedDate;
            const time = selectedTime;
            const flexibleTimingCheckbox = document.getElementById('flexible-timing-checkbox');
            const flexibleTiming = flexibleTimingCheckbox ? flexibleTimingCheckbox.checked : false;

            if (!subject || !date || !time || (Array.isArray(time) && time.length === 0)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Selection',
                    text: 'Please select subject, date, and at least one time slot.'
                });
                return;
            }

            // Disable buttons to prevent multiple submissions
            yesBtn.disabled = true;
            const cancelBtn = document.getElementById('confirmCancel');
            if (cancelBtn) cancelBtn.disabled = true;

            // Ensure axios is available
            if (typeof axios === 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Axios is not loaded. Please check your JavaScript imports.'
                });
                yesBtn.disabled = false;
                if (cancelBtn) cancelBtn.disabled = false;
                return;
            }

            // Ensure Swal is available
            if (typeof Swal === 'undefined') {
                alert('SweetAlert2 (Swal) is not loaded. Please check your JavaScript imports.');
                yesBtn.disabled = false;
                if (cancelBtn) cancelBtn.disabled = false;
                return;
            }

            // Send booking request via AJAX
            axios.post('/api/book-session', {
                tutor_id: tutorId,
                subject: subject,
                date: date,
                time: Array.isArray(time) ? time : [time],
                flexible_timing: flexibleTiming,
                total_price: totalPriceElement.value ? parseFloat(totalPriceElement.value.replace('RM', '')) : 0,
                payment_method: paymentMethodElement ? paymentMethodElement.value : null
            }).then(response => {
                modal.remove();
                Swal.fire({
                    icon: 'success',
                    title: 'Booking Sent',
                    text: response.data.message || 'Booking confirmation is being sent to selected tutor and will be notified once booking is confirmed.'
                });
            }).catch(error => {
                yesBtn.disabled = false;
                if (cancelBtn) cancelBtn.disabled = false;
                
                // Handle scheduling conflict errors specifically
                if (error.response?.status === 409) {
                    const errorType = error.response.data.error;
                    let icon = 'warning';
                    let title = 'Scheduling Conflict';
                    
                    // Customize icon and title based on conflict type
                    if (errorType === 'Student Scheduling Conflict') {
                        icon = 'info';
                        title = 'You Have Another Session';
                    } else if (errorType === 'Tutor Scheduling Conflict') {
                        icon = 'warning';
                        title = 'Tutor Not Available';
                    }
                    
                    Swal.fire({
                        icon: icon,
                        title: title,
                        text: error.response.data.message,
                        confirmButtonText: 'Choose Another Time',
                        confirmButtonColor: '#6366f1'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Booking Failed',
                        text: error.response?.data?.message || 'Failed to send booking request. Please try again.'
                    });
                }
                modal.remove();
            });
        });
        cancelBtn.addEventListener('click', function() {
            modal.remove();
        });
    });
});



document.getElementById('prev-month-btn').addEventListener('click', () => {
        // Decrement month
        if (currentMonth === 0) {
            currentMonth = 11;
            currentYear--;
        } else {
            currentMonth--;
        }
        if (isTutorAvailable()) {
            generateCalendar();
        } else {
            generateFullCalendar();
        }
    });

    document.getElementById('next-month-btn').addEventListener('click', () => {
        // Increment month
        if (currentMonth === 11) {
            currentMonth = 0;
            currentYear++;
        } else {
            currentMonth++;
        }
        if (isTutorAvailable()) {
            generateCalendar();
        } else {
            generateFullCalendar();
        }
    });

    function populateSubjects() {
         subjectElement.innerHTML = '';
        // Filter unique subjects by normalized name (trimmed, lowercase)
        const uniqueSubjects = [];
        const subjectNames = new Set();
        tutor.subjects.forEach(subject => {
            const normalized = subject.name.trim().toLowerCase();
            if (!subjectNames.has(normalized)) {
                subjectNames.add(normalized);
                uniqueSubjects.push(subject);
            }
        });

        uniqueSubjects.forEach(subject => {
            const option = document.createElement('option');
            option.value = subject.name;
            option.textContent = subject.name;
            subjectElement.appendChild(option);
        });
    }

    let lastSelectedTime = null;

    async function populateTimeSlots(dateStr) {
        let times = tutor.availability[dateStr] || [];
        if (Object.keys(tutor.availability).length === 0) {
            times = generateTimeSlots();
        }

        // Check for existing sessions on this date
        try {
            const response = await axios.get(`/api/check-availability?date=${dateStr}&tutor_id={{ $tutor->id }}`);
            const bookedTimes = response.data.bookedTimes || [];
            
            // Filter out already booked times
            times = times.filter(time => !bookedTimes.includes(time));
        } catch (error) {
            console.error('Error checking availability:', error);
        }
        if (times.length === 0) {
            if (timeElement) {
                timeElement.parentNode.removeChild(timeElement);
                timeElement = null;
            }
            selectedTime = '';
            return;
        }
        // Only recreate the dropdown if it doesn't exist or if the available times have changed
        let currentTimes = [];
        if (timeElement && timeElement.classList && timeElement.classList.contains('relative')) {
            // Get current checkboxes' values
            const checkboxes = timeElement.querySelectorAll('input[type="checkbox"]');
            currentTimes = Array.from(checkboxes).map(cb => cb.value);
        }
        if (JSON.stringify(currentTimes) === JSON.stringify(times)) {
            // Times are the same, do not recreate dropdown
            return;
        }
        // Remove existing time element
        if (timeElement) {
            timeElement.parentNode.removeChild(timeElement);
        }
        if (times.length === 1) {
            // Single time slot - simple dropdown
            let timeSelect = document.createElement('select');
            timeSelect.id = 'time-select';
            timeSelect.className = 'input-field mt-2';
            timeSelect.onchange = function() {
                selectedTime = this.value;
                updatePrice();
            };
            let defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = 'Select time slot';
            timeSelect.appendChild(defaultOption);
            let option = document.createElement('option');
            option.value = times[0];
            option.textContent = times[0];
            timeSelect.appendChild(option);
            // Append the select dropdown
            const timeLabel = document.querySelector('label[for="time"]');
            timeLabel.insertAdjacentElement('afterend', timeSelect);
            timeElement = timeSelect;
            selectedTime = '';
        } else {
            // Multiple time slots - dropdown with checkboxes
            let container = document.createElement('div');
            container.className = 'relative inline-block w-full mt-2';
            let dropdownButton = document.createElement('button');
            dropdownButton.type = 'button';
            dropdownButton.className = 'input-field w-full text-left';
            dropdownButton.textContent = 'Select time slots';
            dropdownButton.onclick = function() {
                dropdownContent.classList.toggle('hidden');
            };
            dropdownButton.addEventListener('keydown', function(event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    dropdownContent.classList.toggle('hidden');
                }
            });
            let dropdownContent = document.createElement('div');
            dropdownContent.className = 'absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded shadow-lg max-h-60 overflow-auto hidden';
            times.forEach(time => {
                let label = document.createElement('label');
                label.className = 'flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer';
                let checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.value = time;
                checkbox.className = 'mr-2';
                checkbox.onchange = function() {
                    updateSelectedTimes();
                    updateConfirmButtonState();
                    updatePrice();
                };
                label.appendChild(checkbox);
                label.appendChild(document.createTextNode(time));
                dropdownContent.appendChild(label);
            });
            container.appendChild(dropdownButton);
            container.appendChild(dropdownContent);
            let confirmButton = document.createElement('button');
            confirmButton.type = 'button';
            confirmButton.textContent = 'Confirm Selection';
            confirmButton.className = 'w-full bg-blue-600 text-white py-2 mt-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500';
            confirmButton.disabled = true; // Initially disabled
            confirmButton.onclick = function() {
                updateSelectedTimes();
                if (selectedTime.length === 0) {
                    // Do not proceed if no time slot selected
                    return;
                }
                updatePrice();
                dropdownContent.classList.add('hidden');
                if (selectedTime.length > 0) {
                    dropdownButton.textContent = selectedTime.join(', ');
                } else {
                    dropdownButton.textContent = 'Select time slots';
                }
                const bookBtn = document.getElementById('book-session-btn');
                if (bookBtn) {
                    bookBtn.disabled = false;
                    bookBtn.classList.remove('disabled');
                }
                if (typeof validateForm === 'function') {
                    validateForm();
                }
            };
            dropdownContent.appendChild(confirmButton);
            const timeLabel = document.querySelector('label[for="time"]');
            timeLabel.insertAdjacentElement('afterend', container);
            timeElement = container;
            selectedTime = [];
            document.addEventListener('click', function(event) {
                if (!container.contains(event.target)) {
                    dropdownContent.classList.add('hidden');
                }
            });
            function updateSelectedTimes() {
                const checkedBoxes = container.querySelectorAll('input[type="checkbox"]:checked');
                selectedTime = Array.from(checkedBoxes).map(cb => cb.value);
            }
            function updateConfirmButtonState() {
                const checkedBoxes = container.querySelectorAll('input[type="checkbox"]:checked');
                confirmButton.disabled = checkedBoxes.length === 0;
            }
        }
    }

    function updatePrice() {
        const selectedSubjectName = subjectElement.value;
        if (!selectedSubjectName || !selectedDate || !selectedTime || (Array.isArray(selectedTime) && selectedTime.length === 0)) {
            totalPriceElement.value = '';
            return;
        }
        const subject = tutor.subjects.find(s => s.name === selectedSubjectName);
        if (!subject) {
            totalPriceElement.value = '';
            return;
        }
        // Assuming each time slot is 1 hour
        const price = subject.pricePerHour;
        if (Array.isArray(selectedTime)) {
            totalPriceElement.value = `RM${price * selectedTime.length}`;
        } else {
            totalPriceElement.value = `RM${price}`;
        }
    }

    // Initial check for tutor availability on page load
    function isTutorAvailable() {
        return Object.keys(tutor.availability).length > 0;
    }

    if (isTutorAvailable()) {
        generateCalendar();
    } else {
        generateFullCalendar();
    }
    populateSubjects();

function generateFullCalendar() {
    updateMonthYearHeader();

    const firstDayOfMonth = new Date(currentYear, currentMonth, 1);
    const lastDayOfMonth = new Date(currentYear, currentMonth + 1, 0);

    const daysInMonth = lastDayOfMonth.getDate();
    let calendarHtml = '<tbody>';

    let date = 1;
    const today = new Date();
    today.setHours(0,0,0,0);
    for (let week = 0; week < 6; week++) {
        calendarHtml += '<tr>';
        for (let day = 0; day < 7; day++) {
            if (date > daysInMonth) {
                calendarHtml += '<td></td>';
                continue;
            }
            const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
            const currentDate = new Date(currentYear, currentMonth, date);
            currentDate.setHours(0,0,0,0);
            const isToday = today.getTime() === currentDate.getTime();
            const isPast = currentDate < today;
            let classes = '';
            let onclickAttr = '';
            if (isPast) {
                classes = 'text-gray-400 cursor-not-allowed';
            } else {
                classes = 'cursor-pointer';
                onclickAttr = `selectDate('${dateStr}')`;
            }
            if (isToday) {
                classes += ' bg-yellow-200';
            }
            calendarHtml += `<td class="${classes}" ${onclickAttr ? `onclick=\"${onclickAttr}\"` : ''}>${date}</td>`;
            date++;
        }
        calendarHtml += '</tr>';
        if (date > daysInMonth) break;
    }

    calendarHtml += '</tbody>';
    calendarElement.innerHTML = calendarHtml;
}

    function generateTimeSlots() {
        // Generate time slots from 8:00 AM to 6:00 PM hourly in 12-hour format with AM/PM
        const slots = [];
        for (let hour = 8; hour < 18; hour++) {
            const startHour = hour % 12 === 0 ? 12 : hour % 12;
            const endHour = (hour + 1) % 12 === 0 ? 12 : (hour + 1) % 12;
            const startPeriod = hour < 12 ? 'AM' : 'PM';
            const endPeriod = (hour + 1) < 12 ? 'AM' : 'PM';
            const start = `${startHour}:00 ${startPeriod}`;
            const end = `${endHour}:00 ${endPeriod}`;
            slots.push(`${start} - ${end}`);
        }
        return slots;
    }

    function populateTimeSlotsFree() {
        const times = generateTimeSlots();

        // Remove existing time element
        if (timeElement) {
            timeElement.parentNode.removeChild(timeElement);
        }

        // Multiple time slots - dropdown with checkboxes
        let container = document.createElement('div');
        container.className = 'relative inline-block w-full mt-2';

        let dropdownButton = document.createElement('button');
        dropdownButton.type = 'button';
        dropdownButton.className = 'input-field w-full text-left';
        dropdownButton.textContent = 'Select time slots';
        dropdownButton.onclick = function() {
            dropdownContent.classList.toggle('hidden');
        };

        // Close dropdown on Enter key press when focused on button
        dropdownButton.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                dropdownContent.classList.toggle('hidden');
            }
        });

        let dropdownContent = document.createElement('div');
        dropdownContent.className = 'absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded shadow-lg max-h-60 overflow-auto hidden';

        times.forEach(time => {
            let label = document.createElement('label');
            label.className = 'flex items-center px-3 py-2 hover:bg-gray-100 cursor-pointer';

            let checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.value = time;
            checkbox.className = 'mr-2';
            checkbox.onchange = function() {
                updateSelectedTimes();
                updatePrice();
            };

            label.appendChild(checkbox);
            label.appendChild(document.createTextNode(time));
            dropdownContent.appendChild(label);
        });

        container.appendChild(dropdownButton);
        container.appendChild(dropdownContent);

        // Add confirm button to dropdownContent
        let confirmButton = document.createElement('button');
            confirmButton.type = 'button';
            confirmButton.textContent = 'Confirm Selection';
            confirmButton.className = 'w-full bg-blue-600 text-white py-2 mt-2 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500';
            confirmButton.disabled = true; // Initially disable confirm button

            // Function to update confirm button disabled state based on checkbox selection
            function updateConfirmButtonState() {
                const checkedBoxes = container.querySelectorAll('input[type="checkbox"]:checked');
                confirmButton.disabled = checkedBoxes.length === 0;
            }

            // Add event listeners to checkboxes to update confirm button state
            times.forEach(time => {
                const checkbox = container.querySelector(`input[type="checkbox"][value="${time}"]`);
                if (checkbox) {
                    checkbox.addEventListener('change', updateConfirmButtonState);
                }
            });

            confirmButton.onclick = function() {
                updateSelectedTimes();
                if (selectedTime.length === 0) {
                    // Do not proceed if no time slot selected
                    return;
                }
                updatePrice();
                dropdownContent.classList.add('hidden');
                // Update dropdown button text to show selected times
                if (selectedTime.length > 0) {
                    dropdownButton.textContent = selectedTime.join(', ');
                } else {
                    dropdownButton.textContent = 'Select time slots';
                }
                // Enable the book session button explicitly after confirming selection for tutor with no availability
                const bookBtn = document.getElementById('book-session-btn');
                if (bookBtn) {
                    bookBtn.disabled = false;
                    bookBtn.classList.remove('disabled');
                }
                // Call validateForm to update button state properly
                validateForm();
            };
            dropdownContent.appendChild(confirmButton);

        // Append the custom dropdown
        const timeLabel = document.querySelector('label[for="time"]');
        timeLabel.insertAdjacentElement('afterend', container);
        timeElement = container;
        selectedTime = [];

        // Close dropdown if clicked outside
        document.addEventListener('click', function(event) {
            if (!container.contains(event.target)) {
                dropdownContent.classList.add('hidden');
            }
        });

        function updateSelectedTimes() {
            const checkedBoxes = container.querySelectorAll('input[type="checkbox"]:checked');
            selectedTime = Array.from(checkedBoxes).map(cb => cb.value);
        }
    }


</script>
</body>
</html>