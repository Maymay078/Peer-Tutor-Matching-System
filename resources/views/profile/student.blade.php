<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ $user->full_name ?? config('app.name', 'Laravel') }}</title>
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

    </style>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
         <header>
        <div class="header-container">
            <div class="header-title">Student Profile</div>

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
       
                <a href="/home/student" class="icon-link" title="Home">
                    <i class="fas fa-home"></i>
                </a>
            <a href="{{ route('profile.show', auth()->user()->id) }}" class="icon-link" title="Profile">
                <i class="fas fa-user"></i>
            </a>
                <a href="/chat/student" class="icon-link" title="Chat">
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
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="student-profile-form">
                    @csrf
                    @method('PATCH')
                    <div class="flex justify-between items-center mb-8">
                        <div class="flex items-center space-x-6">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div class="md:col-span-2 flex flex-col items-center">
                            @php
                                $profileImage = $user->profile_image;
                            @endphp
                            <div class="flex flex-col items-center">
                                @if($profileImage)
                                    <img src="{{ (Str::startsWith($profileImage, ['http://', 'https://'])) ? $profileImage : asset('storage/' . $profileImage) }}" alt="Profile Image" class="rounded-xl object-contain mx-auto block mb-4" style="width: 192px; height: 192px;" />
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->full_name) }}&background=random&color=fff" alt="Default Profile Image" class="rounded-xl object-contain mx-auto block mb-4" style="width: 192px; height: 192px;" />
                                @endif
                                <label for="profile_image" class="block text-lg font-semibold text-gray-700 text-center mt-2">Profile Image</label>
                                <input type="file" name="profile_image" id="profile_image" class="mt-2 block w-full max-w-xs rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg text-center" />
                            </div>
                        </div>
                         <div>
                            <label for="full_name" class="block text-lg font-semibold text-gray-700">Full Name</label>
                            <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $user->full_name) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg" />
                        </div>
                        <div>
                            <label for="username" class="block text-lg font-semibold text-gray-700">Username</label>
                            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg" />
                        </div>
                         <div>
                            <label for="date_of_birth" class="block text-lg font-semibold text-gray-700">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" max="2007-12-31" value="{{ old('date_of_birth', $user->date_of_birth) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg" />
                        </div>
                        <div>
                            <label for="email" class="block text-lg font-semibold text-gray-700">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg" />
                        </div>
                        <!-- Phone Number (left) and Year of Study (right) -->
                        <div>
                            <label for="phone_number" class="block text-lg font-semibold text-gray-700">Phone Number</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg" />
                        </div>
                        <div>
                            <label for="year" class="block text-lg font-semibold text-gray-700">Year of Study</label>
                            <input type="number" name="year" id="year" min="1" max="10" value="{{ old('year', $user->student->year ?? ($user->year ?? '')) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg" />
                        </div>
                        <!-- Major (left) and Preferred Course (right) -->
                        <div>
                            <label for="major" class="block text-lg font-semibold text-gray-700">Major of Study</label>
                            <input type="text" name="major" id="major" value="{{ old('major', $user->student->major ?? ($user->major ?? '')) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg" />
                        </div>
                        <div>
                            <label for="preferred_course" class="block text-lg font-semibold text-gray-700">Preferred Course</label>
                            @php
                                $preferredCourse = $user->student->preferred_course ?? [];
                                if (is_string($preferredCourse)) {
                                    $preferredCourse = json_decode($preferredCourse, true) ?: [];
                                }
                            @endphp
                            <input type="text" name="preferred_course" id="preferred_course" value="{{ old('preferred_course', implode(', ', $preferredCourse)) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-lg" />
                        </div>
                    </div>
                    <div class="md:col-span-2 flex justify-end mt-8 space-x-4">
                        <button type="submit" id="save-changes-btn" class="px-6 py-4 bg-indigo-600 text-white rounded-lg text-lg font-semibold hover:bg-indigo-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:hover:bg-gray-400 disabled:transform-none disabled:shadow-none disabled:pointer-events-none disabled:opacity-50" disabled>
                            Save Changes
                        </button>
                    </div>
                </form>
                <!-- Logout and Delete Account buttons (outside the profile form) -->
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
                        <input type="password" name="password" id="delete-password-input" style="display:none;" />
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
                        <input type="password" name="modal_password" id="modal-password" class="rounded border border-gray-300 px-3 py-2 w-full mb-4" placeholder="Enter your password" autocomplete="current-password" required />
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
                    // Save Changes button logic
                    document.addEventListener('DOMContentLoaded', function() {
                        const form = document.getElementById('student-profile-form');
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

                            // Update button state and styling based on changes
                            if (isChanged) {
                                saveBtn.disabled = false;
                                saveBtn.textContent = 'Save Changes';
                                saveBtn.style.backgroundColor = '#4f46e5'; // indigo-600
                                saveBtn.style.opacity = '1';
                                saveBtn.style.cursor = 'pointer';
                                saveBtn.style.pointerEvents = 'auto';
                                saveBtn.style.transform = '';
                                saveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                                saveBtn.classList.add('hover:bg-indigo-700', 'hover:shadow-xl', 'hover:-translate-y-0.5');
                            } else {
                                saveBtn.disabled = true;
                                saveBtn.textContent = 'No Changes Made';
                                saveBtn.style.backgroundColor = '#9ca3af'; // gray-400
                                saveBtn.style.opacity = '0.5';
                                saveBtn.style.cursor = 'not-allowed';
                                saveBtn.style.pointerEvents = 'none';
                                saveBtn.style.transform = 'none';
                                saveBtn.classList.add('opacity-50', 'cursor-not-allowed');
                                saveBtn.classList.remove('hover:bg-indigo-700', 'hover:shadow-xl', 'hover:-translate-y-0.5');
                            }
                        }
                        form.addEventListener('input', checkFormChanged);
                        form.addEventListener('change', checkFormChanged);
                        checkFormChanged();

                        // Prevent button clicks when disabled - multiple event listeners for better coverage
                        saveBtn.addEventListener('click', function(event) {
                            if (saveBtn.disabled) {
                                event.preventDefault();
                                event.stopPropagation();
                                event.stopImmediatePropagation();
                                showProfileStatus('No changes have been made to save.', false);
                                return false;
                            }
                        }, true); // Use capture phase

                        // Additional prevention for mousedown and other events
                        ['mousedown', 'mouseup', 'touchstart', 'touchend'].forEach(eventType => {
                            saveBtn.addEventListener(eventType, function(event) {
                                if (saveBtn.disabled) {
                                    event.preventDefault();
                                    event.stopPropagation();
                                    event.stopImmediatePropagation();
                                    return false;
                                }
                            }, true);
                        });

                        // Add image preview functionality
                        const profileImageInput = document.getElementById('profile_image');
                        const profileImg = document.querySelector('img[alt="Profile Image"], img[alt="Default Profile Image"]');

                        if (profileImageInput && profileImg) {
                            profileImageInput.addEventListener('change', function(event) {
                                const file = event.target.files[0];
                                if (file) {
                                    // Validate file type
                                    if (!file.type.startsWith('image/')) {
                                        alert('Please select a valid image file.');
                                        return;
                                    }

                                    // Validate file size (2MB max)
                                    if (file.size > 2 * 1024 * 1024) {
                                        alert('Image size must be less than 2MB.');
                                        return;
                                    }

                                    // Show preview immediately
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        profileImg.src = e.target.result;
                                        profileImg.alt = "Profile Image Preview";
                                    };
                                    reader.readAsDataURL(file);
                                }
                            });
                        }
                        // AJAX submit
                        form.addEventListener('submit', function(event) {
                            if (saveBtn.disabled) {
                                event.preventDefault();
                                showProfileStatus('No changes have been made to save.', false);
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
                                    const responseData = await response.json();

                                    // Update form fields with fresh data from server
                                    if (responseData.user) {
                                        updateFormWithUserData(responseData.user);
                                    }

                                    // Clear file input after successful upload
                                    const fileInput = document.getElementById('profile_image');
                                    if (fileInput) {
                                        fileInput.value = '';
                                    }

                                    // Update initial values to new values
                                    Array.from(form.elements).forEach(input => {
                                        if (input.name) {
                                            initialValues.set(input.name, getInputValue(input));
                                        }
                                    });
                                    saveBtn.disabled = true;
                                    checkFormChanged(); // Update button text and styling
                                    showProfileStatus('Profile updated successfully!', false);

                                    // Auto-hide success message after 3 seconds
                                    setTimeout(() => {
                                        const statusDiv = document.getElementById('profile-status-msg');
                                        if (statusDiv) {
                                            statusDiv.style.display = 'none';
                                        }
                                    }, 3000);
                                } else {
                                    let msg = 'Failed to update profile.';
                                    try {
                                        const data = await response.json();
                                        if (data.errors) {
                                            msg = Object.values(data.errors).flat().join('\n');
                                        } else if (data.message) {
                                            msg = data.message;
                                        }
                                    } catch (e) {
                                        console.error('Error parsing response:', e);
                                    }
                                    showProfileStatus(msg, true);
                                    saveBtn.disabled = false;
                                }
                            })
                            .catch((error) => {
                                console.error('Network error:', error);
                                saveBtn.textContent = 'Save Changes';
                                showProfileStatus('Network error. Please check your connection and try again.', true);
                                saveBtn.disabled = false;
                            });
                        });

                        function updateFormWithUserData(user) {
                            // Update profile image immediately
                            const profileImg = document.querySelector('img[alt="Profile Image"], img[alt="Default Profile Image"]');
                            if (profileImg && user.profile_image) {
                                const imageUrl = user.profile_image.startsWith('http')
                                    ? user.profile_image
                                    : `{{ asset('storage/') }}/${user.profile_image}`;
                                profileImg.src = imageUrl;
                                profileImg.alt = "Profile Image";
                            } else if (profileImg && !user.profile_image) {
                                // Fallback to default avatar if no profile image
                                const defaultUrl = `https://ui-avatars.com/api/?name=${encodeURIComponent(user.full_name || 'User')}&background=random&color=fff`;
                                profileImg.src = defaultUrl;
                                profileImg.alt = "Default Profile Image";
                            }

                            // Update basic user fields
                            const fullNameInput = document.querySelector('input[name="full_name"]');
                            if (fullNameInput && user.full_name) {
                                fullNameInput.value = user.full_name;
                            }

                            const usernameInput = document.querySelector('input[name="username"]');
                            if (usernameInput && user.username) {
                                usernameInput.value = user.username;
                            }

                            const emailInput = document.querySelector('input[name="email"]');
                            if (emailInput && user.email) {
                                emailInput.value = user.email;
                            }

                            const dobInput = document.querySelector('input[name="date_of_birth"]');
                            if (dobInput && user.date_of_birth) {
                                dobInput.value = user.date_of_birth;
                            }

                            const phoneInput = document.querySelector('input[name="phone_number"]');
                            if (phoneInput && user.phone_number) {
                                phoneInput.value = user.phone_number;
                            }

                            // Update student-specific fields
                            if (user.student) {
                                const majorInput = document.querySelector('input[name="major"]');
                                if (majorInput && user.student.major) {
                                    majorInput.value = user.student.major;
                                }

                                const yearInput = document.querySelector('input[name="year"]');
                                if (yearInput && user.student.year) {
                                    yearInput.value = user.student.year;
                                }

                                const preferredCourseInput = document.querySelector('input[name="preferred_course"]');
                                if (preferredCourseInput && user.student.preferred_course) {
                                    // Handle preferred_course as array or string
                                    let preferredCourseValue = user.student.preferred_course;
                                    if (Array.isArray(preferredCourseValue)) {
                                        preferredCourseValue = preferredCourseValue.join(', ');
                                    }
                                    preferredCourseInput.value = preferredCourseValue;
                                }
                            }
                        }

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
                <!-- Include flatpickr JS -->
                <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
                <script>
                    function initFlatpickrOnDates() {
                        if (window.flatpickr) {
                            document.querySelectorAll('.date-picker').forEach(function(input) {
                                if (!input._flatpickr) {
                                    flatpickr(input, { minDate: 'today', dateFormat: 'Y-m-d' });
                                }
                            });
                        }
                    }
                    document.addEventListener('DOMContentLoaded', function() {
                        initFlatpickrOnDates();
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
                </script>
                @if(session('delete_error'))
                    <div class="text-red-600 mt-2 text-sm">{{ session('delete_error') }}</div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
