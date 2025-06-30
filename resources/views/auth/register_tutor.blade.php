<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tutor Registration - Peer Tutor Matching System</title>
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .main-wrapper {
            display: flex;
            max-width: 1100px;
            width: 100%;
            min-height: 700px;
            background: white;
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.10);
            overflow: hidden;
        }
        .left-section {
            flex: 1;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            min-width: 320px;
        }
        .left-section h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        .left-section p {
            font-size: 1.1rem;
            opacity: 0.95;
            line-height: 1.6;
            text-align: center;
            margin-bottom: 2rem;
        }
        .right-section {
            flex: 1.3;
            padding: 48px 40px 40px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #4f46e5;
        }
        .header p {
            color: #6b7280;
            margin-top: 0.5rem;
        }
        .form-container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #4f46e5;
        }
        .add-subject-button, .add-date-button {
            background: #10b981;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s;
            width: 100%;
        }
        .add-subject-button:hover, .add-date-button:hover {
            background: #059669;
        }
        .add-subject-button:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }
        .subject-rate-pair {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
            padding: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: white;
            position: relative;
        }
        .subject-input, .rate-input {
            flex: 1;
        }
        .subject-input input, .rate-input input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
        }
        .subject-input label, .rate-input label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
        }
        .remove-subject-btn {
            position: absolute;
            right: 10px;
            top: 10px;
            background: #ef4444;
            color: white;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            padding: 0;
            line-height: 24px;
            text-align: center;
        }
        .remove-subject-btn:hover {
            background: #dc2626;
        }
        .availability-section {
            margin-top: 20px;
        }
        .date-section {
            margin-bottom: 15px;
            border: 1px solid #e5e7eb;
            padding: 15px;
            border-radius: 12px;
            background: white;
        }
        .remove-date-button {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        .remove-date-button:hover {
            background: #dc2626;
        }
        .submit-btn {
            width: auto;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 30px;
            margin-right: 12px;
            transition: transform 0.2s, background 0.2s;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.13);
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #3730a3 0%, #6d28d9 100%);
        }
        .cancel-btn {
            width: auto;
            background: #f3f4f6;
            color: #4f46e5;
            border: 2px solid #4f46e5;
            padding: 14px 32px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 30px;
            transition: background 0.2s, color 0.2s;
        }
        .cancel-btn:hover {
            background: #e0e7ff;
            color: #3730a3;
        }
        .login-link {
            text-align: center;
            margin-top: 24px;
            color: #6b7280;
            font-size: 1rem;
        }
        .login-link a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }
        .login-link a:hover {
            text-decoration: underline;
            color: #3730a3;
        }
        #password-mismatch-error, #password-complexity-error {
            color: red;
            margin-top: 5px;
            display: none;
            font-size: 0.9em;
        }
        @media (max-width: 900px) {
            .main-wrapper {
                flex-direction: column;
                min-height: unset;
            }
            .left-section, .right-section {
                min-width: unset;
                padding: 32px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <div class="left-section">
            <h2>Become a Tutor</h2>
            <p>Share your expertise, help others learn, and earn by joining our peer tutor community.</p>
            <img src="https://img.icons8.com/fluency/144/teacher.png" alt="Tutor Illustration" style="width:120px; margin-bottom:1.5rem; border-radius:16px; box-shadow:0 4px 16px rgba(79,70,229,0.08);" />
        </div>
        <div class="right-section">
            <div class="header">
                <h1>Tutor Registration</h1>
                <p>Fill in your details to start tutoring</p>
            </div>
            <div class="form-container">
                <form method="POST" action="{{ route('register.tutor') }}" id="tutorRegistrationForm">
                    @csrf

                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required />
                        @error('full_name')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <!-- Username before DOB -->
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required />
                        @error('username')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" name="dob" id="dob" value="{{ old('dob') }}" required />
                        @error('dob')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required />
                        @error('email')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" required />
                        <small class="text-gray-600">Password must be a mix of letters, numbers, and special characters.</small>
                        @error('password')<div class="error">{{ $message }}</div>@enderror
                        <div id="password-complexity-error" style="color: red; display: none; margin-top: 5px;">Password must contain letters, numbers, and special characters.</div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Re-enter Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required />
                        @error('password_confirmation')<div class="error">{{ $message }}</div>@enderror
                        <div id="password-mismatch-error" style="color: red; display: none; margin-top: 5px;">Passwords do not match.</div>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" required />
                        @error('phone_number')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="payment_method">Payment Method</label>
                        <select name="payment_method" id="payment_method" required>
                            <option value="" disabled selected hidden>Select payment method</option>
                            <option value="Cash">Cash</option>
                            <option value="Online Banking">Online Banking</option>
                            <option value="Cash or Online Banking">Cash or Online Banking</option>
                        </select>
                        @error('payment_method')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Subjects & Rates</label>
                        <div id="subjects-container">
                            <div class="subject-rate-pair" id="subject1">
                                <div class="subject-input">
                                    <label for="subjects_0">Subject 1:</label>
                                    <input type="text" id="subjects_0" name="subjects[]" placeholder="Enter subject name" class="subject-input-field" required />
                                </div>
                                <div class="rate-input">
                                    <label for="rates_0">Rate per Hour (RM):</label>
                                    <input type="number" id="rates_0" name="rates[]" min="0" step="0.01" placeholder="Enter hourly rate" required />
                                </div>
                                <button type="button" class="remove-subject-btn" id="remove-subject1">×</button>
                            </div>
                        </div>
                        <button type="button" id="add-subject-btn" class="add-subject-button">Add Subject (Max 5)</button>
                        @error('subjects')<div class="error">{{ $message }}</div>@enderror
                        @error('rates')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="availability-section" id="availability_section">
                        <label>Availability (Date and Time slots):</label>
                        <div id="availability_container">
                            <div class="date-section" id="date1">
                                <label for="date1_input">Date 1:</label>
                                <input type="date" name="availability[date1]" id="date1_input" required />
                                <div class="time-slots">
                                    <label class="time-slot-label"><input type="checkbox" name="availability[time1][]" value="8:00 AM - 9:00 AM" /> 8:00 AM - 9:00 AM</label>
                                    <label class="time-slot-label"><input type="checkbox" name="availability[time1][]" value="9:00 AM - 10:00 AM" /> 9:00 AM - 10:00 AM</label>
                                    <label class="time-slot-label"><input type="checkbox" name="availability[time1][]" value="10:00 AM - 11:00 AM" /> 10:00 AM - 11:00 AM</label>
                                    <label class="time-slot-label"><input type="checkbox" name="availability[time1][]" value="11:00 AM - 12:00 PM" /> 11:00 AM - 12:00 PM</label>
                                    <label class="time-slot-label"><input type="checkbox" name="availability[time1][]" value="12:00 PM - 1:00 PM" /> 12:00 PM - 1:00 PM</label>
                                    <label class="time-slot-label"><input type="checkbox" name="availability[time1][]" value="1:00 PM - 2:00 PM" /> 1:00 PM - 2:00 PM</label>
                                    <label class="time-slot-label"><input type="checkbox" name="availability[time1][]" value="2:00 PM - 3:00 PM" /> 2:00 PM - 3:00 PM</label>
                                    <label class="time-slot-label"><input type="checkbox" name="availability[time1][]" value="3:00 PM - 4:00 PM" /> 3:00 PM - 4:00 PM</label>
                                    <label class="time-slot-label"><input type="checkbox" name="availability[time1][]" value="4:00 PM - 5:00 PM" /> 4:00 PM - 5:00 PM</label>
                                    <label class="time-slot-label"><input type="checkbox" name="availability[time1][]" value="5:00 PM - 6:00 PM" /> 5:00 PM - 6:00 PM</label>
                                </div>
                                <button type="button" class="remove-date-button" id="remove-date1">Remove Date 1</button>
                            </div>
                        </div>
                        <button type="button" class="add-date-button" id="add-date-btn">Add Another Date</button>
                    </div>
                    <div style="display:flex;justify-content:flex-end;gap:12px;">
                        <button type="submit" class="submit-btn">Register as Tutor</button>
                        <button type="button" class="cancel-btn" onclick="window.location.href='{{ route('welcome') }}'">Cancel</button>
                    </div>
                </form>
                <div class="login-link">
                    Already have an account? <a href="{{ route('login') }}">Login</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Subject-Rate pairs functionality
        const addSubjectBtn = document.getElementById('add-subject-btn');
        const subjectsContainer = document.getElementById('subjects-container');
        let subjectCount = 1;
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
                const subjectLabel = pair.querySelector('.subject-input label');
                if (subjectLabel) {
                    subjectLabel.textContent = `Subject ${newIndex}:`;
                    subjectLabel.setAttribute('for', `subjects_${index}`);
                }
                const subjectInput = pair.querySelector('.subject-input input');
                if (subjectInput) {
                    subjectInput.id = `subjects_${index}`;
                }
                const rateLabel = pair.querySelector('.rate-input label');
                if (rateLabel) {
                    rateLabel.setAttribute('for', `rates_${index}`);
                }
                const rateInput = pair.querySelector('.rate-input input');
                if (rateInput) {
                    rateInput.id = `rates_${index}`;
                }
                const removeBtn = pair.querySelector('.remove-subject-btn');
                if (removeBtn) {
                    removeBtn.id = `remove-subject${newIndex}`;
                }
            });
            subjectCount = pairs.length;
        }

        addSubjectBtn.addEventListener('click', () => {
            const currentPairs = subjectsContainer.querySelectorAll('.subject-rate-pair').length;
            if (currentPairs >= maxSubjects) return;
            const nextIndex = currentPairs + 1;
            const newPair = document.createElement('div');
            newPair.classList.add('subject-rate-pair');
            newPair.id = `subject${nextIndex}`;
            newPair.innerHTML = `
                <div class="subject-input">
                    <label for="subjects_${currentPairs}">Subject ${nextIndex}:</label>
                    <input type="text" id="subjects_${currentPairs}" name="subjects[]" placeholder="Enter subject name" class="subject-input-field" required />
                </div>
                <div class="rate-input">
                    <label for="rates_${currentPairs}">Rate per Hour (RM):</label>
                    <input type="number" id="rates_${currentPairs}" name="rates[]" min="0" step="0.01" placeholder="Enter hourly rate" required />
                </div>
                <button type="button" class="remove-subject-btn" id="remove-subject${nextIndex}">×</button>
            `;
            subjectsContainer.appendChild(newPair);
            updateAddSubjectButton();
            updateRemoveSubjectButtons();
        });

        updateAddSubjectButton();
        updateRemoveSubjectButtons();

        // Availability date/time add/remove logic
        const addDateButton = document.getElementById('add-date-btn');
        const availabilityContainer = document.getElementById('availability_container');
        let dateCount = 1;

        function updateRemoveDateButtons() {
            const dateSections = availabilityContainer.querySelectorAll('.date-section');
            dateSections.forEach((section, index) => {
                const removeBtn = section.querySelector('.remove-date-button');
                if (removeBtn) {
                    if (dateSections.length === 1) {
                        removeBtn.style.display = 'block';
                        removeBtn.onclick = () => alert('Cannot remove the only date. At least one date is required.');
                    } else {
                        removeBtn.style.display = 'block';
                        removeBtn.onclick = function() {
                            section.remove();
                            updateRemoveDateButtons();
                            reindexDates();
                        };
                    }
                }
            });
        }

        function reindexDates() {
            const dateSections = availabilityContainer.querySelectorAll('.date-section');
            dateSections.forEach((section, index) => {
                const newDateCount = index + 1;
                section.id = `date${newDateCount}`;
                const dateLabel = section.querySelector('label[for^="date"]');
                if (dateLabel) {
                    dateLabel.textContent = `Date ${newDateCount}:`;
                    dateLabel.setAttribute('for', `date${newDateCount}_input`);
                }
                const dateInput = section.querySelector('input[type="date"]');
                if (dateInput) {
                    dateInput.name = `availability[date${newDateCount}]`;
                    dateInput.id = `date${newDateCount}_input`;
                }
                const timeSlots = section.querySelectorAll('label input[type="checkbox"]');
                timeSlots.forEach(timeSlot => {
                    timeSlot.name = `availability[time${newDateCount}][]`;
                });
                const removeButton = section.querySelector('.remove-date-button');
                if (removeButton) {
                    removeButton.id = `remove-date${newDateCount}`;
                    removeButton.textContent = `Remove Date ${newDateCount}`;
                }
            });
            dateCount = dateSections.length;
        }

        addDateButton.addEventListener('click', () => {
            dateCount++;
            const newDateSection = document.createElement('div');
            newDateSection.classList.add('date-section');
            newDateSection.id = `date${dateCount}`;
            newDateSection.innerHTML = `
                <label for="date${dateCount}_input">Date ${dateCount}:</label>
                <input type="date" name="availability[date${dateCount}]" id="date${dateCount}_input" />
                <div class="time-slots">
                    <label class="time-slot-label"><input type="checkbox" name="availability[time${dateCount}][]" value="8:00 AM - 9:00 AM" /> 8:00 AM - 9:00 AM</label>
                    <label class="time-slot-label"><input type="checkbox" name="availability[time${dateCount}][]" value="9:00 AM - 10:00 AM" /> 9:00 AM - 10:00 AM</label>
                    <label class="time-slot-label"><input type="checkbox" name="availability[time${dateCount}][]" value="10:00 AM - 11:00 AM" /> 10:00 AM - 11:00 AM</label>
                    <label class="time-slot-label"><input type="checkbox" name="availability[time${dateCount}][]" value="11:00 AM - 12:00 PM" /> 11:00 AM - 12:00 PM</label>
                    <label class="time-slot-label"><input type="checkbox" name="availability[time${dateCount}][]" value="12:00 PM - 1:00 PM" /> 12:00 PM - 1:00 PM</label>
                    <label class="time-slot-label"><input type="checkbox" name="availability[time${dateCount}][]" value="1:00 PM - 2:00 PM" /> 1:00 PM - 2:00 PM</label>
                    <label class="time-slot-label"><input type="checkbox" name="availability[time${dateCount}][]" value="2:00 PM - 3:00 PM" /> 2:00 PM - 3:00 PM</label>
                    <label class="time-slot-label"><input type="checkbox" name="availability[time${dateCount}][]" value="3:00 PM - 4:00 PM" /> 3:00 PM - 4:00 PM</label>
                    <label class="time-slot-label"><input type="checkbox" name="availability[time${dateCount}][]" value="4:00 PM - 5:00 PM" /> 4:00 PM - 5:00 PM</label>
                    <label class="time-slot-label"><input type="checkbox" name="availability[time${dateCount}][]" value="5:00 PM - 6:00 PM" /> 5:00 PM - 6:00 PM</label>
                </div>
                <button type="button" class="remove-date-button" id="remove-date${dateCount}">Remove Date ${dateCount}</button>
            `;
            availabilityContainer.appendChild(newDateSection);
            updateRemoveDateButtons();
        });

        updateRemoveDateButtons();

        function showSuccessModal(message, redirectUrl) {
            // Remove existing modal if present
            const existingModal = document.getElementById('registration-success-modal');
            if (existingModal) existingModal.remove();

            // Create overlay
            const overlay = document.createElement('div');
            overlay.id = 'registration-success-modal';
            overlay.style.position = 'fixed';
            overlay.style.top = 0;
            overlay.style.left = 0;
            overlay.style.width = '100vw';
            overlay.style.height = '100vh';
            overlay.style.background = 'rgba(31, 41, 55, 0.45)';
            overlay.style.zIndex = 9999;
            overlay.style.display = 'flex';
            overlay.style.alignItems = 'center';
            overlay.style.justifyContent = 'center';

            // Create modal box
            const modal = document.createElement('div');
            modal.style.background = 'white';
            modal.style.borderRadius = '18px';
            modal.style.boxShadow = '0 12px 48px rgba(79,70,229,0.18), 0 2px 8px rgba(0,0,0,0.08)';
            modal.style.padding = '2.5rem 2.2rem 2.2rem 2.2rem';
            modal.style.maxWidth = '400px';
            modal.style.width = '95vw';
            modal.style.display = 'flex';
            modal.style.flexDirection = 'column';
            modal.style.alignItems = 'center';
            modal.style.textAlign = 'center';
            modal.style.position = 'relative';

            modal.innerHTML = `
                <div style="font-size:2.5rem;margin-bottom:0.7rem;">🎉</div>
                <h2 style="font-size:1.5rem;font-weight:800;color:#4f46e5;margin-bottom:0.7rem;">Registration Successful!</h2>
                <p style="font-size:1.08rem;color:#374151;margin-bottom:1.7rem;line-height:1.6;">${message}</p>
                <button id="goToLoginBtn" style="background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%);color:white;font-weight:700;font-size:1.08rem;padding:0.8rem 2.2rem;border:none;border-radius:10px;box-shadow:0 2px 8px rgba(99,102,241,0.08);margin-bottom:0.7rem;cursor:pointer;transition:background 0.18s;">Go to Login</button>
                <button id="closeModalBtn" style="background:none;border:none;color:#6b7280;text-decoration:underline;cursor:pointer;font-size:1.07rem;">Close</button>
            `;

            overlay.appendChild(modal);
            document.body.appendChild(overlay);

            document.getElementById('goToLoginBtn').onclick = function() {
                window.location.href = redirectUrl;
            };
            document.getElementById('closeModalBtn').onclick = function() {
                document.body.removeChild(overlay);
            };
            overlay.onclick = function(ev) {
                if (ev.target === overlay) document.body.removeChild(overlay);
            };
            document.addEventListener('keydown', function escHandler(ev) {
                if (ev.key === 'Escape') {
                    if (document.body.contains(overlay)) document.body.removeChild(overlay);
                    document.removeEventListener('keydown', escHandler);
                }
            });
        }

        document.getElementById('tutorRegistrationForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const form = this;
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');
            const mismatchError = document.getElementById('password-mismatch-error');
            const passwordComplexityError = document.getElementById('password-complexity-error');

            // Check password complexity
            const complexityRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W_]).+$/;
            let hasError = false;
            if (!complexityRegex.test(password.value)) {
                if (passwordComplexityError) passwordComplexityError.style.display = 'block';
                hasError = true;
            } else {
                if (passwordComplexityError) passwordComplexityError.style.display = 'none';
            }

            // Check password confirmation
            if (password.value !== confirmPassword.value) {
                if (mismatchError) mismatchError.style.display = 'block';
                hasError = true;
            } else {
                if (mismatchError) mismatchError.style.display = 'none';
            }

            if (hasError) return;

            // Submit via AJAX to backend
            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => {
                // Try to parse as JSON, fallback to text if not JSON
                return response.json().catch(() => ({}));
            })
            .then(data => {
                if (data.success) {
                    // Show success message then redirect to login page
                    alert('You have successfully registered. Please login now.');
                    window.location.href = "{{ route('login') }}";
                } else if (data.errors) {
                    // Show first error in a nice modal
                    let firstField = Object.keys(data.errors)[0];
                    let firstMsg = data.errors[firstField][0];
                    showSuccessModal(
                        `<span style="color:#dc2626;">Registration failed:</span><br>${firstMsg}`,
                        "#"
                    );
                } else {
                    // If no errors and no success, treat as success (fallback for HTML response)
                    alert('You have successfully registered. Please login now.');
                    window.location.href = "{{ route('login') }}";
                }
            })
            .catch(error => {
                showSuccessModal(
                    `<span style="color:#dc2626;">An error occurred during registration.</span>`,
                    "#"
                );
            });
        });

        // Immediate validation on re-enter password input
        const passwordConfirmationInput = document.getElementById('password_confirmation');
        if (passwordConfirmationInput) {
            passwordConfirmationInput.addEventListener('input', function() {
                const password = document.getElementById('password');
                const confirmPassword = this;
                const mismatchError = document.getElementById('password-mismatch-error');

                if (confirmPassword.value !== password.value) {
                    if (mismatchError) mismatchError.style.display = 'block';
                } else {
                    if (mismatchError) mismatchError.style.display = 'none';
                }
            });
        }

        // Immediate validation on password input for complexity
        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const passwordComplexityError = document.getElementById('password-complexity-error');
                const complexityRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W_]).+$/;

                if (!complexityRegex.test(this.value)) {
                    if (passwordComplexityError) passwordComplexityError.style.display = 'block';
                } else {
                    if (passwordComplexityError) passwordComplexityError.style.display = 'none';
                }
            });
        }

        // Real-time min date for all date pickers (including availability and dob)
        function setMinDatePickers() {
            const today = new Date();
            today.setHours(0,0,0,0);
            const todayStr = today.toISOString().split('T')[0];
            // Set min for DOB (if you want to restrict to users at least 16 years old, adjust accordingly)
            const dobInput = document.getElementById('dob');
            if (dobInput) {
                dobInput.setAttribute('max', new Date(new Date().setFullYear(new Date().getFullYear() - 16)).toISOString().split('T')[0]);
            }
            // Set min for all availability date pickers
            document.querySelectorAll('input[type="date"][name^="availability[date"]').forEach(input => {
                input.setAttribute('min', todayStr);
                // If the value is before today, clear it
                if (input.value && new Date(input.value) < today) {
                    input.value = '';
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            setMinDatePickers();
            // Also set min date on dynamically added date pickers
            document.getElementById('add-date-btn').addEventListener('click', function() {
                setTimeout(setMinDatePickers, 100);
            });
        });
    </script>
</body>
</html>

