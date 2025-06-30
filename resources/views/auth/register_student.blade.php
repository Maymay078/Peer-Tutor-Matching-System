<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Student Registration - Peer Tutor Matching System</title>
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
        .student-subject-pair {
            position: relative;
            margin-bottom: 10px;
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
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.6);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        .remove-student-subject-btn:hover {
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
            <h2>Become a Student</h2>
            <p>Find the best tutors, connect with peers, and boost your learning journey.</p>
            <img src="https://img.icons8.com/fluency/144/student-male--v1.png" alt="Student Illustration" style="width:120px; margin-bottom:1.5rem; border-radius:16px; box-shadow:0 4px 16px rgba(79,70,229,0.08);" />
        </div>
        <div class="right-section">
            <div class="header">
                <h1>Student Registration</h1>
                <p>Fill in your details to start learning</p>
            </div>
            <div class="form-container">
                <form method="POST" action="{{ route('register.student') }}" id="studentRegistrationForm">
                    @csrf

                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" required />
                        @error('full_name')<div class="error">{{ $message }}</div>@enderror
                    </div>
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
                        <label for="major">Major of Study</label>
                        <input type="text" name="major" id="major" value="{{ old('major') }}" />
                        @error('major')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label for="year">Year of Study</label>
                        <input type="number" name="year" id="year" min="1" max="10" value="{{ old('year') }}" />
                        @error('year')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
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
                                            class="subject-input-field" />
                                    </div>
                                </div>
                                <button type="button" class="remove-student-subject-btn" id="remove-student-subject1">×</button>
                            </div>
                        </div>
                        <button type="button" id="add-student-subject-btn" class="add-subject-button">Add Subject (Max 5)</button>
                        @error('student_subjects')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div style="display:flex;justify-content:flex-end;gap:12px;">
                        <button type="submit" class="submit-btn">Register as Student</button>
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
        // Password confirmation validation and complexity check
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

            let goToLoginButtonHTML = '';
            if (redirectUrl && redirectUrl !== '#') {
                goToLoginButtonHTML = `<button id="goToLoginBtn" style="background:linear-gradient(135deg,#4f46e5 0%,#7c3aed 100%);color:white;font-weight:700;font-size:1.08rem;padding:0.8rem 2.2rem;border:none;border-radius:10px;box-shadow:0 2px 8px rgba(99,102,241,0.08);margin-bottom:0.7rem;cursor:pointer;transition:background 0.18s;">Go to Login</button>`;
            }

            modal.innerHTML = `
                <div style="font-size:2.5rem;margin-bottom:0.7rem;">🎉</div>
                <h2 style="font-size:1.5rem;font-weight:800;color:#4f46e5;margin-bottom:0.7rem;">Registration Successful!</h2>
                <p style="font-size:1.08rem;color:#374151;margin-bottom:1.7rem;line-height:1.6;">${message}</p>
                ${goToLoginButtonHTML}
                <button id="closeModalBtn" style="background:none;border:none;color:#6b7280;text-decoration:underline;cursor:pointer;font-size:1.07rem;">Close</button>
            `;

            overlay.appendChild(modal);
            document.body.appendChild(overlay);

            if (redirectUrl && redirectUrl !== '#') {
                document.getElementById('goToLoginBtn').onclick = function() {
                    window.location.href = redirectUrl;
                };
            }
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

        document.getElementById('studentRegistrationForm').addEventListener('submit', function(event) {
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

            // Remove any existing student_subjects fields to avoid duplicates
            for (let key of Array.from(formData.keys())) {
                if (key.startsWith('student_subjects')) {
                    formData.delete(key);
                }
            }

            // Append preferred subjects as student_subjects[] (not student_subjects[index])
            const subjectPairs = document.querySelectorAll('.student-subject-pair');
            subjectPairs.forEach(section => {
                const input = section.querySelector('input[type="text"]');
                if (input && input.value) {
                    formData.append('student_subjects[]', input.value);
                }
            });

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
                    alert('You have successfully registered. Please login now.');
                    window.location.href = "{{ route('login') }}";
                } else if (data.errors) {
                    let firstField = Object.keys(data.errors)[0];
                    let firstMsg = data.errors[firstField][0];
                    alert(`Registration failed: ${firstMsg}`);
                } else {
                    alert('You have successfully registered. Please login now.');
                    window.location.href = "{{ route('login') }}";
                }
            })
            .catch(error => {
                alert('An error occurred during registration.');
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

        // Add/Remove Preferred Subjects logic
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
                            pair.remove();
                            updateAddStudentSubjectButton();
                            updateRemoveStudentSubjectButtons();
                            reindexStudentSubjects();
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
                    subjectLabel.setAttribute('for', `student_subjects_${index}`);
                }
                const subjectInput = pair.querySelector('.student-subject-input input');
                if (subjectInput) {
                    subjectInput.id = `student_subjects_${index}`;
                }
                const removeBtn = pair.querySelector('.remove-student-subject-btn');
                if (removeBtn) {
                    removeBtn.id = `remove-student-subject${newIndex}`;
                }
            });
        }

        addStudentSubjectBtn.addEventListener('click', () => {
            const currentPairs = studentSubjectsContainer.querySelectorAll('.student-subject-pair').length;
            if (currentPairs >= maxStudentSubjects) return;
            const nextIndex = currentPairs + 1;
            const newPair = document.createElement('div');
            newPair.classList.add('student-subject-pair');
            newPair.id = `student-subject${nextIndex}`;
            newPair.innerHTML = `
                <div class="student-subject-input">
                    <label for="student_subjects_${currentPairs}">Subject ${nextIndex}:</label>
                    <div class="subject-input-container">
                        <input type="text"
                            id="student_subjects_${currentPairs}"
                            name="student_subjects[]"
                            placeholder="Enter subject name"
                            class="subject-input-field" />
                    </div>
                </div>
                <button type="button" class="remove-student-subject-btn" id="remove-student-subject${nextIndex}">×</button>
            `;
            studentSubjectsContainer.appendChild(newPair);
            updateAddStudentSubjectButton();
            updateRemoveStudentSubjectButtons();
        });

        updateAddStudentSubjectButton();
        updateRemoveStudentSubjectButtons();

        // Real-time min date for DOB
        document.addEventListener('DOMContentLoaded', function() {
            const dobInput = document.getElementById('dob');
            if (dobInput) {
                dobInput.setAttribute('max', new Date(new Date().setFullYear(new Date().getFullYear() - 16)).toISOString().split('T')[0]);
            }
        });
    </script>
</body>
</html>