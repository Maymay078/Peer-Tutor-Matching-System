
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register - Peer Tutor Matching System</title>
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .form-container {
            padding: 40px;
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
            box-sizing: border-box;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #4f46e5;
        }
        .role-selection {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        .role-option {
            flex: 1;
            padding: 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .role-option:hover {
            border-color: #4f46e5;
            background-color: #f8fafc;
        }
        .role-option.selected {
            border-color: #4f46e5;
            background-color: #eef2ff;
        }
        .role-option input[type="radio"] {
            display: none;
        }
        .role-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .role-specific {
            display: none;
            background-color: #f9fafb;
            padding: 25px;
            border-radius: 12px;
            margin-top: 20px;
        }
        .role-specific.active {
            display: block;
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
        .add-date-button {
            background: #10b981;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        .add-date-button:hover {
            background: #059669;
        }
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 30px;
            transition: transform 0.2s;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #6b7280;
        }
        .login-link a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .student-subject-pair {
            position: relative;
        }
        .remove-student-subject-btn {
        position: absolute;
        right: 10px;
        top: 70%;
        transform: translateY(-50%);
        background: #ef4444;
        color: white;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 16px;
        cursor: pointer;
        display: flex;
        align-items: center;   /* Vertically center */
        justify-content: center;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.6);
        transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        .remove-student-subject-btn:hover {
           background: #dc2626;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Join Peer Tutor Matching System</h1>
            <p>Create your account and start your learning journey</p>
        </div>
        <div class="form-container">
            <form method="POST" action="{{ route('register') }}" id="registrationForm">
                @csrf
                <!-- Role Selection -->
                <div class="form-group">
                    <label>Select Your Role</label>
                    <div class="role-selection">
                        <div class="role-option" onclick="selectRole('student')">
                            <div class="role-icon">🎓</div>
                            <h3>Student</h3>
                            <p>Find tutors and enhance your learning</p>
                            <input type="radio" name="role" value="student" id="student_role">
                        </div>
                        <div class="role-option" onclick="selectRole('tutor')">
                            <div class="role-icon">👨‍🏫</div>
                            <h3>Tutor</h3>
                            <p>Share knowledge and help others</p>
                            <input type="radio" name="role" value="tutor" id="tutor_role">
                        </div>
                    </div>
                    @error('role')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div id="success-message" style="display:none; color: green; font-weight: bold; margin-bottom: 20px;"></div>

                <script>
                    document.getElementById('registrationForm').addEventListener('submit', function(event) {
                        event.preventDefault();
                        const form = this;
                        fetch(form.action, {
                            method: 'POST',
                            body: new FormData(form),
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const successMessage = document.getElementById('success-message');
                                successMessage.textContent = 'Registration successful! Do you want to go to the login page?';
                                successMessage.style.display = 'block';
                                if (confirm('Registration successful! Do you want to go to the login page?')) {
                                    window.location.href = "{{ route('login') }}";
                                }
                            } else if (data.errors) {
                                // Display first validation error in alert
                                const firstErrorField = Object.keys(data.errors)[0];
                                const firstErrorMsg = data.errors[firstErrorField][0];
                                alert('Registration failed: ' + firstErrorMsg);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred during registration.');
                        });
                    });
                </script>

                <!-- Common Fields -->
                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required autocomplete="name" />
                    @error('full_name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input type="date" name="dob" id="dob" value="{{ old('dob') }}" required max="2007-12-31" autocomplete="bday" />
                    @error('dob')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}" required autocomplete="username" />
                    @error('username')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email" />
                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required autocomplete="new-password" />
                    <small class="text-gray-600">Password must be a mix of letters, numbers, and special characters.</small>
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Re-enter Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" />
                    @error('password_confirmation')
                        <div class="error">{{ $message }}</div>
                    @enderror
                    <div id="password-mismatch-error" style="color: red; display: none; margin-top: 5px;">Passwords do not match.</div>
                </div>
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" required autocomplete="tel" />
                    @error('phone_number')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Student Specific Fields -->
                <div class="role-specific student">
                    <div class="form-group">
                        <label for="major">Major of Study</label>
                        <input type="text" name="major" id="major" value="{{ old('major') }}" />
                        @error('major')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="year">Year of Study</label>
                        <input type="number" name="year" id="year" min="1" max="10" value="{{ old('year') }}" />
                        @error('year')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Faculty</label>
                        <select id="student-faculty-select" class="faculty-select" name="student_faculty">
                            <option value="">Select a faculty</option>
                            @foreach (App\Models\SubjectNormalizer::getFaculties() as $faculty)
                                <option value="{{ $faculty }}" {{ old('student_faculty') == $faculty ? 'selected' : '' }}>{{ $faculty }}</option>
                            @endforeach
                        </select>
                    
                        <label>Preferred Subjects</label>
                        <div id="student-subjects-container">
                            <div class="student-subject-pair" id="student-subject1">
                                <div class="student-subject-input">
                        <label for="student_subjects_0">Subject 1:</label>
                        <div class="subject-input-container">
                            <input type="text" 
                                   id="student_subjects_0"
                                   name="student_subjects[]" 
                                   placeholder="Enter subject name" 
                                   list="student-subject-suggestions"
                                   class="subject-input-field" />
                            <datalist id="student-subject-suggestions"></datalist>
                        </div>
                    </div>
                    <button type="button" class="remove-student-subject-btn" id="remove-student-subject1">×</button>
                            </div>
                        </div>
                        <button type="button" id="add-student-subject-btn" class="add-subject-button">Add Subject (Max 5)</button>
                        @error('student_subjects')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Tutor Specific Fields -->
                <div class="role-specific tutor">
                    <div class="form-group">
                         <label for="payment_method">Payment Method</label>
        <select name="payment_method" id="payment_method">
            <option value="" disabled selected hidden>Select payment method</option>
            <option value="Cash">Cash</option>
            <option value="Online Banking">Online Banking</option>
            <option value="Cash or Online Banking">Cash or Online Banking</option>
        </select>
        @error('payment_method')
            <div class="error">{{ $message }}</div>
        @enderror
</div>
        <div class="form-group">
                        <label>Faculty</label>
                        <select id="faculty-select" class="faculty-select">
                             <option value="" disabled selected hidden>Select a Faculty</option>
                            @foreach (App\Models\SubjectNormalizer::getFaculties() as $faculty)
                                <option value="{{ $faculty }}">{{ $faculty }}</option>
                            @endforeach
                        </select>

                        <label>Subjects & Rates</label>
                        <div id="subjects-container">
                            <div class="subject-rate-pair" id="subject1">
                                <div class="subject-input">
                                    <label for="subjects_0">Subject 1:</label>
                                    <div class="subject-input-container">
                                        <input type="text" 
                                               id="subjects_0"
                                               name="subjects[]" 
                                               placeholder="Enter subject name" 
                                               list="subject-suggestions"
                                               class="subject-input-field" />
                                        <datalist id="subject-suggestions"></datalist>
                                    </div>
                                </div>
                                <div class="rate-input">
                                    <label for="rates_0">Rate per Hour (RM):</label>
                                    <input type="number" id="rates_0" name="rates[]" min="0" step="0.01" placeholder="Enter hourly rate" />
                                </div>
                                <button type="button" class="remove-subject-btn" id="remove-subject1">×</button>
                            </div>
                        </div>
                        <button type="button" id="add-subject-btn" class="add-subject-button">Add Subject (Max 5)</button>
                        @error('subjects')
                            <div class="error">{{ $message }}</div>
                        @enderror
                        @error('rates')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                     <!-- Availability Section -->
                <div class="availability-section" id="availability_section">
                    <label>Availability (Date and Time slots):</label>
                    <div id="availability_container">
                <div class="date-section" id="date1">
                    <label for="date1_input">Date 1:</label>
        <input type="date" name="availability[date1]" id="date1_input" onchange="checkDuplicateDates()" />
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

                    <style>
                        .faculty-select {
                            width: 100%;
                            padding: 12px 16px;
                            border: 2px solid #e5e7eb;
                            border-radius: 8px;
                            font-size: 16px;
                            margin-bottom: 20px;
                        }
                        .subject-input-container {
                            position: relative;
                            width: 100%;
                        }
                        .subject-input-field {
                            width: 100%;
                            padding: 8px 12px;
                            padding-right: 40px; /* Added padding to make space for remove button */
                            border: 1px solid #e5e7eb;
                            border-radius: 6px;
                            font-size: 14px;
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
                        .add-subject-button {
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
                        .add-subject-button:hover {
                            background: #059669;
                        }
                        .add-subject-button:disabled {
                            background: #9ca3af;
                            cursor: not-allowed;
                        }
                        /* Time slots styling */
                        .time-slots {
                            display: grid;
                            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                            gap: 10px;
                            margin-top: 10px;
                        }
                        .time-slot-label {
                            display: flex;
                            align-items: center;
                            padding: 8px;
                            border: 1px solid #e5e7eb;
                            border-radius: 6px;
                            background: #f9fafb;
                        }
                        .time-slot-label:hover {
                            background: #f3f4f6;
                        }
                        .time-slot-label input[type="checkbox"] {
                            margin-right: 8px;
                        }
                    </style>
                </div>

        <script>
            function checkDuplicateDates() {
                const dateInputs = document.querySelectorAll('input[type="date"][name^="availability[date"]');
                const dates = [];
                let duplicateFound = false;
                dateInputs.forEach(input => {
                    if (input.value) {
                        if (dates.includes(input.value)) {
                            duplicateFound = true;
                        } else {
                            dates.push(input.value);
                        }
                    }
                });
                if (duplicateFound) {
                    alert('You cannot select the same date for multiple availability entries.');
                    // Clear the last changed input value
                    event.target.value = '';
                }
            }

            // Toggle required attributes based on role selection
            function toggleRequiredFields(role) {
                const tutorFields = document.querySelectorAll('.role-specific.tutor input, .role-specific.tutor select');
                const studentFields = document.querySelectorAll('.role-specific.student input, .role-specific.student select');

                if (role === 'tutor') {
                    tutorFields.forEach(field => field.setAttribute('required', 'required'));
                    studentFields.forEach(field => field.removeAttribute('required'));
                } else if (role === 'student') {
                    studentFields.forEach(field => field.setAttribute('required', 'required'));
                    tutorFields.forEach(field => field.removeAttribute('required'));
                }
            }

            // Update required fields on role selection
            function selectRole(role) {
                // Set the radio button checked state
                document.getElementById(role + '_role').checked = true;

                // Show/hide role-specific sections
                const studentFields = document.querySelectorAll('.role-specific.student');
                const tutorFields = document.querySelectorAll('.role-specific.tutor');

                if (role === 'student') {
                    studentFields.forEach(el => el.classList.add('active'));
                    tutorFields.forEach(el => el.classList.remove('active'));
                } else if (role === 'tutor') {
                    tutorFields.forEach(el => el.classList.add('active'));
                    studentFields.forEach(el => el.classList.remove('active'));
                }

                // Add selected class to role-option divs for visual feedback
                document.querySelectorAll('.role-option').forEach(div => {
                    div.classList.remove('selected');
                });
                document.getElementById(role + '_role').parentElement.classList.add('selected');

                // Toggle required attributes
                toggleRequiredFields(role);
            }

            // Initialize required fields on page load based on selected role
            document.addEventListener('DOMContentLoaded', () => {
                const selectedRole = document.querySelector('input[name="role"]:checked');
                if (selectedRole) {
                    toggleRequiredFields(selectedRole.value);
                }
            });

            // Attach event listener to dynamically added date inputs
            document.getElementById('add-date-btn').addEventListener('click', () => {
                setTimeout(() => {
                    const dateInputs = document.querySelectorAll('input[type="date"][name^="availability[date"]');
                    dateInputs.forEach(input => {
                        input.removeEventListener('change', checkDuplicateDates);
                        input.addEventListener('change', checkDuplicateDates);
                        // Also set min attribute on new inputs
                        const today = new Date().toISOString().split('T')[0];
                        input.setAttribute('min', today);
                    });
                }, 100);
            });

            // Set min attribute dynamically to today's date for all date inputs
            function setMinDate() {
                const today = new Date().toISOString().split('T')[0];
                console.log('Setting min date to:', today);
                const dateInputs = document.querySelectorAll('input[type="date"][name^="availability[date"]');
                dateInputs.forEach(input => {
                    input.setAttribute('min', today);
                    console.log(`Set min for input id=${input.id} to ${input.min}`);
                });
            }

            document.addEventListener('DOMContentLoaded', () => {
                setMinDate();

                // Periodically update min attribute every hour to prevent selecting past dates if page stays open
                setInterval(() => {
                    setMinDate();
                }, 60 * 60 * 1000); // every 1 hour
            });

            // Additional validation on form submit to prevent past dates
            document.getElementById('registrationForm').addEventListener('submit', function(event) {
                const today = new Date();
                today.setHours(0,0,0,0);
                const dateInputs = document.querySelectorAll('input[type="date"][name^="availability[date"]');
                for (const input of dateInputs) {
                    if (input.value) {
                        const inputDate = new Date(input.value);
                        if (inputDate < today) {
                            alert('You cannot select a past date: ' + input.value);
                            event.preventDefault();
                            input.focus();
                            return false;
                        }
                    }
                }
            });
        </script>
                </div>

                <button type="submit" class="submit-btn">Sign Up</button>
            </form>
            <p class="login-link">Already have an account? <a href="{{ url('/login') }}">Login</a></p>
        </div>
    </div>

    <script>
        function selectRole(role) {
            // Set the radio button checked state
            document.getElementById(role + '_role').checked = true;

            // Show/hide role-specific sections
            const studentFields = document.querySelectorAll('.role-specific.student');
            const tutorFields = document.querySelectorAll('.role-specific.tutor');

            if (role === 'student') {
                studentFields.forEach(el => el.classList.add('active'));
                tutorFields.forEach(el => el.classList.remove('active'));
            } else if (role === 'tutor') {
                tutorFields.forEach(el => el.classList.add('active'));
                studentFields.forEach(el => el.classList.remove('active'));
            }

            // Add selected class to role-option divs for visual feedback
            document.querySelectorAll('.role-option').forEach(div => {
                div.classList.remove('selected');
            });
            document.getElementById(role + '_role').parentElement.classList.add('selected');
        }

        // Availability date/time add/remove logic
        document.addEventListener('DOMContentLoaded', () => {
            const addDateButton = document.getElementById('add-date-btn');
            const availabilityContainer = document.getElementById('availability_container');
            let dateCount = 1;
            let availableIndices = [];

            // Set min attribute for all date inputs to today
            function setMinDateForAll() {
                const today = new Date().toISOString().split('T')[0];
                const dateInputs = availabilityContainer.querySelectorAll('input[type="date"]');
                dateInputs.forEach(input => {
                    input.setAttribute('min', today);
                });
            }

            setMinDateForAll();

            function updateRemoveButtons() {
                const dateSections = availabilityContainer.querySelectorAll('.date-section');
                dateSections.forEach((section, index) => {
                    const removeBtn = section.querySelector('.remove-date-button');
                    if (!removeBtn) return;
                    removeBtn.disabled = false;
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
                        dateLabel.setAttribute('for', `date${newDateCount}`);
                    }
                    const dateInput = section.querySelector('input[name^="availability[date"]');
                    if (dateInput) {
                        dateInput.name = `availability[date${newDateCount}]`;
                        dateInput.id = `date${newDateCount}_input`;
                    }
                    const timeSlots = section.querySelectorAll('label input[name^="availability[time"]');
                    timeSlots.forEach(timeSlot => {
                        timeSlot.name = `availability[time${newDateCount}][]`;
                    });
                    const removeButton = section.querySelector('.remove-date-button');
                    if (removeButton) {
                        removeButton.id = `remove-date${newDateCount}`;
                        removeButton.textContent = `Remove Date ${newDateCount}`;
                    }
                });
                // Reset dateCount to match the number of sections
                dateCount = dateSections.length;
                // Clear availableIndices since we're using sequential numbering
                availableIndices = [];
            }
            
            // Centralized click handler for remove buttons
            availabilityContainer.addEventListener('click', function(event) {
                if (event.target && event.target.classList.contains('remove-date-button')) {
                    const dateSection = event.target.closest('.date-section');
                    const dateSections = document.querySelectorAll('.date-section');
                    
                    // Only show "Cannot remove the only date" for Date 1 when it's the only date
                    if (dateSections.length === 1 && dateSection.id === 'date1') {
                        alert('Cannot remove the only date.');
                        return false;
                    }

                    // Check if after removing this date, there will be at least one filled date remaining
                    const allDateInputs = document.querySelectorAll('.date-section input[type="date"]');
                    const currentDateInput = dateSection.querySelector('input[type="date"]');
                    
                    // Count filled dates excluding the current one being removed
                    let filledDatesCount = 0;
                    allDateInputs.forEach(input => {
                        if (input !== currentDateInput && input.value && input.value.trim() !== '') {
                            filledDatesCount++;
                        }
                    });
                    
                    // If no other dates are filled, prevent removal
                    if (filledDatesCount === 0) {
                        alert('Please ensure at least one date is filled before removing.');
                        return false;
                    }

                    dateSection.remove();
                    reindexDates();
                    updateRemoveButtons();
                }
            });

            addDateButton.addEventListener('click', () => {
                dateCount++;
                const newDateSection = document.createElement('div');
                newDateSection.classList.add('date-section');
                newDateSection.id = `date${dateCount}`;
                newDateSection.innerHTML = `
                    <label for="date${dateCount}_input">Date ${dateCount}:</label>
                    <input type="date" name="availability[date${dateCount}]" id="date${dateCount}_input" required min="{{ date('Y-m-d') }}" />
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
                updateRemoveButtons();
            });

            // Initialize remove buttons
            updateRemoveButtons();

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
                                    const subjectInput = pair.querySelector('input[name="subjects[]"]');
                                    if (subjectInput && subjectInput.value.trim() !== '') {
                                        const otherFilledSubjects = Array.from(subjectsContainer.querySelectorAll('.subject-rate-pair'))
                                            .filter(p => p !== pair)
                                            .some(p => p.querySelector('input[name="subjects[]"]').value.trim() !== '');
                                        
                                        if (!otherFilledSubjects) {
                                            alert('Please ensure at least one subject is filled before removing.');
                                            return;
                                        }
                                    }
                                    pair.remove();
                                    updateAddSubjectButton();
                                    updateRemoveSubjectButtons();
                                    reindexSubjects();

            // Call onSubjectRemoved to repopulate suggestions and focus input
            onSubjectRemoved();

            // Also repopulate suggestions for all remaining inputs
            populateSubjectSuggestions();

            // Re-assign datalist attribute to all subject inputs after removal and reindexing
            const allSubjectInputs = subjectsContainer.querySelectorAll('input[name="subjects[]"]');
            allSubjectInputs.forEach(input => {
                input.setAttribute('list', 'subject-suggestions');
            });

            // Debug log
            console.log('Tutor subject removed, suggestions repopulated and datalist reassigned');
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
                        subjectLabel.setAttribute('for', `subjects[${index}]`);
                    }
                    const removeBtn = pair.querySelector('.remove-subject-btn');
                    if (removeBtn) {
                        removeBtn.id = `remove-subject${newIndex}`;
                    }
                });
                // Update subjectCount to match the current number of pairs
                subjectCount = pairs.length;

                // Re-assign datalist attribute to all subject inputs after reindexing
                const allSubjectInputs = subjectsContainer.querySelectorAll('input[name="subjects[]"]');
                allSubjectInputs.forEach(input => {
                    input.setAttribute('list', 'subject-suggestions');
                });

                // Repopulate suggestions for the first subject input after reindexing
                if (allSubjectInputs.length > 0) {
                    const firstInput = allSubjectInputs[0];
                    firstInput.dispatchEvent(new Event('focus'));
                }

                // Explicitly repopulate the datalist options after reindexing
                populateSubjectSuggestions();
            }

            addSubjectBtn.addEventListener('click', () => {
                const currentPairs = subjectsContainer.querySelectorAll('.subject-rate-pair').length;
                if (currentPairs >= maxSubjects) return;

                // Prevent adding duplicate subjects
                const existingSubjects = Array.from(subjectsContainer.querySelectorAll('input[name="subjects[]"]'))
                    .map(input => input.value.trim().toLowerCase())
                    .filter(val => val !== '');

                // Check for duplicates
                const hasDuplicates = existingSubjects.some((item, idx) => existingSubjects.indexOf(item) !== idx);

                if (hasDuplicates) {
                    alert('Please remove duplicate subjects before adding new ones.');
                    return;
                }

                const nextIndex = currentPairs + 1;
                const newPair = document.createElement('div');
                newPair.classList.add('subject-rate-pair');
                newPair.id = `subject${nextIndex}`;
                newPair.innerHTML = `
                    <div class="subject-input">
                        <label for="subjects[${currentPairs}]">Subject ${nextIndex}:</label>
                        <div class="subject-input-container">
                            <input type="text" 
                                   name="subjects[]" 
                                   required 
                                   placeholder="Enter subject name" 
                                   list="subject-suggestions"
                                   class="subject-input-field" />
                        </div>
                    </div>
                    <div class="rate-input">
                        <label for="rates[${currentPairs}]">Rate per Hour (RM):</label>
                        <input type="number" name="rates[]" min="0" step="0.01" required placeholder="Enter hourly rate" />
                    </div>
                    <button type="button" class="remove-subject-btn" id="remove-subject${nextIndex}">×</button>
                `;
                subjectsContainer.appendChild(newPair);
                updateAddSubjectButton();
                updateRemoveSubjectButtons();

                // Add event listeners for input and focus to new subject input
                const newInput = newPair.querySelector('input[name="subjects[]"]');
                if (newInput) {
                    newInput.addEventListener('input', () => {
                        populateSubjectSuggestions();
                    });
                    newInput.addEventListener('focus', () => {
                        populateSubjectSuggestions();
                    });
                }
            });

            // Initialize subject-rate buttons
            updateAddSubjectButton();
            updateRemoveSubjectButtons();
        });

        // Faculty and subject suggestions logic for tutor
        document.addEventListener('DOMContentLoaded', () => {
            const facultySelect = document.getElementById('faculty-select');
            const subjectSuggestions = document.getElementById('subject-suggestions');

            // Pass faculties data from PHP to JS
            const facultiesData = @json(App\Models\SubjectNormalizer::$faculties);

            facultySelect.addEventListener('change', () => {
                const selectedFaculty = facultySelect.value;
                subjectSuggestions.innerHTML = '';

                if (!selectedFaculty || !facultiesData[selectedFaculty]) return;

                const facultySubjects = facultiesData[selectedFaculty];

                facultySubjects.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject;
                    subjectSuggestions.appendChild(option);
                });
            });

            // Add event listener to repopulate datalist on focus of subject input fields
            const subjectsContainer = document.getElementById('subjects-container');
            subjectsContainer.addEventListener('focusin', (event) => {
                if (event.target && event.target.matches('input[name="subjects[]"]')) {
                    const selectedFaculty = facultySelect.value;
                    subjectSuggestions.innerHTML = '';

                    if (!selectedFaculty || !facultiesData[selectedFaculty]) return;

                    const facultySubjects = facultiesData[selectedFaculty];

                    facultySubjects.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject;
                        subjectSuggestions.appendChild(option);
                    });
                }
            });

            let selectionMade = false;

            function populateSubjectSuggestions() {
                const selectedFaculty = facultySelect.value;
                subjectSuggestions.innerHTML = '';

                if (!selectedFaculty || !facultiesData[selectedFaculty]) return;

                const facultySubjects = facultiesData[selectedFaculty];

                // Get all currently selected subjects to exclude from suggestions
                const selectedSubjects = Array.from(subjectsContainer.querySelectorAll('input[name="subjects[]"]'))
                    .map(input => input.value.trim().toLowerCase())
                    .filter(val => val !== '');

                // If no subjects selected, show full list
                const filteredSubjects = selectedSubjects.length === 0
                    ? facultySubjects
                    : facultySubjects.filter(subject => !selectedSubjects.includes(subject.toLowerCase()));

                filteredSubjects.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject;
                    subjectSuggestions.appendChild(option);
                });
            }

            // Use event delegation to handle dynamic subject inputs
            subjectsContainer.addEventListener('change', (event) => {
                if (event.target && event.target.matches('input[name="subjects[]"]')) {
                    selectionMade = true;
                    setTimeout(() => {
                        selectionMade = false;
                    }, 500);
                }
            });

            subjectsContainer.addEventListener('input', (event) => {
                if (event.target && event.target.matches('input[name="subjects[]"]')) {
                    if (selectionMade) return;
                    populateSubjectSuggestions();
                }
            });

            subjectsContainer.addEventListener('focusin', (event) => {
                if (event.target && event.target.matches('input[name="subjects[]"]')) {
                    populateSubjectSuggestions();
                }
            });

            // Call populateSubjectSuggestions on faculty change
            facultySelect.addEventListener('change', () => {
                populateSubjectSuggestions();
            });

            // Call populateSubjectSuggestions after removing a subject
            function onSubjectRemoved() {
                console.log('onSubjectRemoved called');
                populateSubjectSuggestions();
                let nextInput = subjectsContainer.querySelector('input[name="subjects[]"]');
                if (!nextInput) {
                    // If no subject inputs exist, create and add a new one directly
                    const newIndex = 1;
                    const newPair = document.createElement('div');
                    newPair.classList.add('subject-rate-pair');
                    newPair.id = `subject${newIndex}`;
                    newPair.innerHTML = `\
                        <div class="subject-input">
                            <label for="subjects[0]">Subject 1:</label>
                            <div class="subject-input-container">
                                <input type="text" 
                                       name="subjects[]" 
                                       required 
                                       placeholder="Enter subject name" 
                                       list="subject-suggestions"
                                       class="subject-input-field" />
                            </div>
                        </div>
                        <div class="rate-input">
                            <label for="rates[0]">Rate per Hour (RM):</label>
                            <input type="number" name="rates[]" min="0" step="0.01" required placeholder="Enter hourly rate" />
                        </div>
                        <button type="button" class="remove-subject-btn" id="remove-subject1">×</button>
                    `;
                    subjectsContainer.appendChild(newPair);
                    updateAddSubjectButton();
                    updateRemoveSubjectButtons();
                    nextInput = newPair.querySelector('input[name="subjects[]"]');
                    if (nextInput) {
                        nextInput.setAttribute('list', 'subject-suggestions');
                        console.log('Set datalist attribute on new input');
                    }
                }
                if (nextInput) {
                    console.log('Focusing input:', nextInput);
                    nextInput.focus();
                    nextInput.value = ''; // Clear to trigger suggestions
                } else {
                    console.log('No subject input found to focus');
                }
            }

                // Student faculty and subject suggestions logic
                const studentFacultySelect = document.getElementById('student-faculty-select');
                const studentSubjectSuggestions = document.getElementById('student-subject-suggestions');

                function populateStudentSubjectSuggestions() {
                    // Get all currently selected student subjects to exclude from suggestions
                    const selectedStudentSubjects = Array.from(studentSubjectsContainer.querySelectorAll('input[name="student_subjects[]"]'))
                        .map(input => input.value.trim().toLowerCase())
                        .filter(val => val !== '');

                    // Filter faculty subjects to exclude selected ones
                    const selectedFaculty = studentFacultySelect.value;
                    if (!selectedFaculty || !facultiesData[selectedFaculty]) return;

                    const facultySubjects = facultiesData[selectedFaculty];

                    const filteredSubjects = selectedStudentSubjects.length === 0
                        ? facultySubjects
                        : facultySubjects.filter(subject => !selectedStudentSubjects.includes(subject.toLowerCase()));

                    studentSubjectSuggestions.innerHTML = '';
                    filteredSubjects.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject;
                        studentSubjectSuggestions.appendChild(option);
                    });
                }

                studentFacultySelect.addEventListener('change', () => {
                    const selectedFaculty = studentFacultySelect.value;
                    studentSubjectSuggestions.innerHTML = '';

                    if (!selectedFaculty || !facultiesData[selectedFaculty]) return;

                    populateStudentSubjectSuggestions();

                    // Add event listeners to update suggestions on input focus and change
                    studentSubjectsContainer.addEventListener('input', (event) => {
                        if (event.target && event.target.matches('input[name="student_subjects[]"]')) {
                            populateStudentSubjectSuggestions();
                        }
                    });

                    studentSubjectsContainer.addEventListener('focusin', (event) => {
                        if (event.target && event.target.matches('input[name="student_subjects[]"]')) {
                            populateStudentSubjectSuggestions();
                        }
                    });
                });

        // Trigger change event on student faculty select to populate initial suggestions if any
        document.addEventListener('DOMContentLoaded', () => {
            const studentFacultySelect = document.getElementById('student-faculty-select');
            if (studentFacultySelect) {
                const event = new Event('change');
                studentFacultySelect.dispatchEvent(event);
            }
        });

            // Student preferred subjects add/remove functionality
            const addStudentSubjectBtn = document.getElementById('add-student-subject-btn');
            const studentSubjectsContainer = document.getElementById('student-subjects-container');
            const maxStudentSubjects = 5;

            function updateAddStudentSubjectButton() {
                const currentPairs = studentSubjectsContainer.querySelectorAll('.student-subject-pair').length;
                if (currentPairs >= maxStudentSubjects) {
                    addStudentSubjectBtn.disabled = true;
                    addStudentSubjectBtn.textContent = `Maximum ${maxStudentSubjects} subjects reached`;
                } else {
                    addStudentSubjectBtn.disabled = false;
                    addStudentSubjectBtn.textContent = `Add Subject (Max ${maxStudentSubjects})`;
                }
            }

            function updateRemoveStudentSubjectButtons() {
                const pairs = studentSubjectsContainer.querySelectorAll('.student-subject-pair');
                pairs.forEach((pair, index) => {
                    const removeBtn = pair.querySelector('.remove-student-subject-btn');
                    if (removeBtn) {
                        if (pairs.length === 1) {
                            removeBtn.style.display = 'block';
                            removeBtn.onclick = () => alert('Cannot remove the only subject. At least one subject is required.');
                        } else {
                            removeBtn.style.display = 'block';
                            removeBtn.onclick = function() {
                                const subjectInput = pair.querySelector('input[name="student_subjects[]"]');
                                if (subjectInput && subjectInput.value.trim() !== '') {
                                    const otherFilledSubjects = Array.from(studentSubjectsContainer.querySelectorAll('.student-subject-pair'))
                                        .filter(p => p !== pair)
                                        .some(p => p.querySelector('input[name="student_subjects[]"]').value.trim() !== '');
                                    
                                    if (!otherFilledSubjects) {
                                        alert('Please ensure at least one subject is filled before removing.');
                                        return;
                                    }
                                }
                                pair.remove();
                                updateAddStudentSubjectButton();
                                updateRemoveStudentSubjectButtons();
                                reindexStudentSubjects();

                                // Repopulate suggestions for student subjects after removal
                                if (typeof populateStudentSubjectSuggestions === 'function') {
                                    populateStudentSubjectSuggestions();
                                }

                                // Re-assign datalist attribute to all student subject inputs after removal and reindexing
                                const allStudentSubjectInputs = studentSubjectsContainer.querySelectorAll('input[name="student_subjects[]"]');
                                allStudentSubjectInputs.forEach(input => {
                                    input.setAttribute('list', 'student-subject-suggestions');
                                });

                                // Debug log
                                console.log('Student subject removed, suggestions repopulated and datalist reassigned');
                            };
                        }
                    }
                });
            }

            function reindexStudentSubjects() {
                const pairs = studentSubjectsContainer.querySelectorAll('.student-subject-pair');
                pairs.forEach((pair, index) => {
                    const newIndex = index + 1;
                    pair.id = `student-subject${newIndex}`;
                    const subjectLabel = pair.querySelector('.student-subject-input label');
                    if (subjectLabel) {
                        subjectLabel.textContent = `Subject ${newIndex}:`;
                        subjectLabel.setAttribute('for', `student_subjects[${index}]`);
                    }
                    const removeBtn = pair.querySelector('.remove-student-subject-btn');
                    if (removeBtn) {
                        removeBtn.id = `remove-student-subject${newIndex}`;
                    }
                });

                // Re-assign datalist attribute to all student subject inputs after reindexing
                const allStudentSubjectInputs = studentSubjectsContainer.querySelectorAll('input[name="student_subjects[]"]');
                allStudentSubjectInputs.forEach(input => {
                    input.setAttribute('list', 'student-subject-suggestions');
                });

                // Repopulate suggestions for the first student subject input after reindexing
                if (allStudentSubjectInputs.length > 0) {
                    const firstInput = allStudentSubjectInputs[0];
                    firstInput.dispatchEvent(new Event('focus'));
                }

                // Explicitly repopulate the datalist options after reindexing
                populateStudentSubjectSuggestions();
            }

            addStudentSubjectBtn.addEventListener('click', () => {
                const currentPairs = studentSubjectsContainer.querySelectorAll('.student-subject-pair').length;
                if (currentPairs >= maxStudentSubjects) return;

                // Prevent adding duplicate subjects
                const existingStudentSubjects = Array.from(studentSubjectsContainer.querySelectorAll('input[name="student_subjects[]"]'))
                    .map(input => input.value.trim().toLowerCase())
                    .filter(val => val !== '');

                // Check for duplicates
                const hasDuplicates = existingStudentSubjects.some((item, idx) => existingStudentSubjects.indexOf(item) !== idx);

                if (hasDuplicates) {
                    alert('Please remove duplicate subjects before adding new ones.');
                    return;
                }

                const nextIndex = currentPairs + 1;
                const newPair = document.createElement('div');
                newPair.classList.add('student-subject-pair');
                newPair.id = `student-subject${nextIndex}`;
                newPair.innerHTML = `
                    <div class="student-subject-input">
                        <label for="student_subjects[${currentPairs}]">Subject ${nextIndex}:</label>
                        <div class="subject-input-container">
                            <input type="text" 
                                   name="student_subjects[]" 
                                   required 
                                   placeholder="Enter subject name" 
                                   list="student-subject-suggestions"
                                   class="subject-input-field" />
                            <datalist id="student-subject-suggestions"></datalist>
                        </div>
                    </div>
                    <button type="button" class="remove-student-subject-btn" id="remove-student-subject${nextIndex}" aria-label="Remove subject">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5" style="width: 20px; height: 20px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                `;
                studentSubjectsContainer.appendChild(newPair);
                updateAddStudentSubjectButton();
                updateRemoveStudentSubjectButtons();

                // Add event listeners for input and focus to new student subject input
                const newInput = newPair.querySelector('input[name="student_subjects[]"]');
                if (newInput) {
                    newInput.addEventListener('input', () => {
                        populateStudentSubjectSuggestions();
                    });
                    newInput.addEventListener('focus', () => {
                        populateStudentSubjectSuggestions();
                    });
                }
            });

            // Initialize student preferred subjects buttons
            updateAddStudentSubjectButton();
            updateRemoveStudentSubjectButtons();
            
        });

        // Client-side password confirmation validation on form submit
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');
            const mismatchError = document.getElementById('password-mismatch-error');
            const passwordComplexityError = document.getElementById('password-complexity-error');

            // Check password complexity
            const complexityRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W_]).+$/;
            if (!complexityRegex.test(password.value)) {
                event.preventDefault();
                if (passwordComplexityError) passwordComplexityError.style.display = 'block';
            } else {
                if (passwordComplexityError) passwordComplexityError.style.display = 'none';
            }

            // Check password confirmation
            if (password.value !== confirmPassword.value) {
                event.preventDefault();
                if (mismatchError) mismatchError.style.display = 'block';
            } else {
                if (mismatchError) mismatchError.style.display = 'none';
            }
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
    </script>

    <style>
        #password-mismatch-error, #password-complexity-error {
            color: red;
            margin-top: 5px;
            display: none;
            font-size: 0.9em;
        }
    </style>
</body>
</html>


