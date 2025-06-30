@extends('layouts.profile-layout')

@section('header')
    <span class="leading-none flex items-center text-white font-semibold text-xl" style="line-height: 1.5;">
        {{ $user->username }}
    </span>
@endsection
 <style>
    .icon-link:hover {
        background-color: #e0e7ff;
        color: #4f46e5;
    }
</style>

@section('content')

    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-md p-8 py-6">
<form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="student-profile-form">
    @csrf
    @method('PATCH')

            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center space-x-6">
                </div>
            </div>

            <div class="space-y-6">
                <div>
@php
                        $profileImage = $user->profile_image;
                    @endphp
                    @if($profileImage)
                        <img src="{{ (Str::startsWith($profileImage, ['http://', 'https://'])) ? $profileImage : asset('storage/' . $profileImage) }}" alt="Profile Image" class="rounded-xl object-contain mx-auto block mb-10" style="width: 192px; height: 192px;" />
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->full_name) }}&background=random&color=fff" alt="Default Profile Image" class="rounded-xl object-contain mx-auto block mb-10" style="width: 192px; height: 192px;" />
                    @endif
                </div>
                <div>
                    <label for="profile_image" class="block text-sm font-medium text-gray-700">Profile Image</label>
                    <input type="file" name="profile_image" id="profile_image" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                </div>
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
<input type="text" name="full_name" id="full_name" value="{{ old('full_name', $user->full_name) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
<input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
<input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
<input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
<input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <div>
                    <label for="major" class="block text-sm font-medium text-gray-700">Major of Study</label>
                    <input type="text" name="major" id="major" value="{{ old('major', $user->student->major ?? ($user->major ?? '')) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <div>
                    <label for="year" class="block text-sm font-medium text-gray-700">Year of Study</label>
                    <input type="number" name="year" id="year" min="1" max="10" value="{{ old('year', $user->student->year ?? ($user->year ?? '')) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                </div>

                <div>
                    <label for="preferred_course" class="block text-sm font-medium text-gray-700">Preferred Course</label>
                    @php
                        $preferredCourse = $user->student->preferred_course ?? [];
                        if (is_string($preferredCourse)) {
                            $preferredCourse = json_decode($preferredCourse, true) ?: [];
                        }
                    @endphp
                    <input type="text" name="preferred_course" id="preferred_course" value="{{ old('preferred_course', implode(', ', $preferredCourse)) }}" class="mt-1 block w-full rounded-xl border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" />
                </div>


                  <div class="flex justify-end mt-8 space-x-4">
            <button type="submit" id="save-changes-btn" class="px-4 py-4 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50" disabled>
        Save Changes
    </button>
        </div>
      </form>
     <!-- Logout and Delete Account buttons (outside the profile form) -->
    <div class="flex justify-end mt-4 space-x-4">
        <form method="POST" action="{{ route('logout') }}" id="logoutForm">
            @csrf
            <button type="submit" class="px-4 py-4 border border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition">
                Logout
            </button>
        </form>
        <form method="POST" action="{{ route('profile.destroy') }}" id="deleteForm">
            @csrf
            @method('DELETE')
            <input type="password" name="password" id="delete-password-input" style="display:none;" />
            <button type="button" id="delete-account-btn" class="px-4 py-4 border border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition">
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
                'Accept': 'application/json' // <-- ADD THIS LINE
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
</div>

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
    // If you add date sections dynamically, call initFlatpickrOnDates() after adding
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
@endsection
