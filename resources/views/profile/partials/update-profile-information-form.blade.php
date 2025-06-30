<section class="max-w-7xl mx-auto">
    <header class="mb-6">
        <h2 class="text-3xl font-bold text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-2 text-gray-700 text-lg">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="profile-update-form" method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="full_name" :value="__('Full Name')" />
            <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full rounded-2xl border border-gray-300 bg-white text-gray-900 shadow-md focus:ring-blue-600 focus:border-blue-600" :value="old('full_name', $user->full_name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('full_name')" />
        </div>

        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full rounded-2xl border border-gray-300 bg-white text-gray-900 shadow-md focus:ring-blue-600 focus:border-blue-600" :value="old('username', $user->username)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label for="date_of_birth" :value="__('Date of Birth')" />
            <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full rounded-2xl border border-gray-300 bg-white text-gray-900 shadow-md focus:ring-blue-600 focus:border-blue-600" :value="old('date_of_birth', $user->date_of_birth ?? '')" autocomplete="bday" />
            <x-input-error class="mt-2" :messages="$errors->get('date_of_birth')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-2xl border border-gray-300 bg-white text-gray-900 shadow-md focus:ring-blue-600 focus:border-blue-600" :value="old('email', $user->email)" required autocomplete="email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="phone_number" :value="__('Phone Number')" />
            <x-text-input id="phone_number" name="phone_number" type="tel" class="mt-1 block w-full rounded-2xl border border-gray-300 bg-white text-gray-900 shadow-md focus:ring-blue-600 focus:border-blue-600" :value="old('phone_number', $user->phone_number ?? '')" autocomplete="tel" />
            <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
        </div>

        <div>
            <x-input-label for="major" :value="__('Major of Study')" />
            <x-text-input id="major" name="major" type="text" class="mt-1 block w-full rounded-2xl border border-gray-300 bg-white text-gray-900 shadow-md focus:ring-blue-600 focus:border-blue-600" :value="old('major', $student->major ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('major')" />
        </div>

        <div>
            <x-input-label for="year" :value="__('Year of Study')" />
            <x-text-input id="year" name="year" type="number" min="1" class="mt-1 block w-full rounded-2xl border border-gray-300 bg-white text-gray-900 shadow-md focus:ring-blue-600 focus:border-blue-600" :value="old('year', $student->year ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('year')" />
        </div>

        <div>
            <x-input-label for="preferred_course" :value="__('Preferred Subject')" />
            <x-text-input id="preferred_course" name="preferred_course" type="text" class="mt-1 block w-full rounded-2xl border border-gray-300 bg-white text-gray-900 shadow-md focus:ring-blue-600 focus:border-blue-600" :value="old('preferred_course', $preferredCourseStr ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('preferred_course')" />
        </div>

        <div>
            <x-input-label for="availability" :value="__('Availability')" />
            @php
                $availability = $student->availability ?? '[]';
                $availability = is_string($availability) ? json_decode($availability, true) : $availability;
                $now = \Carbon\Carbon::now();
                $upcomingAvailability = [];
                if (is_array($availability) && count($availability) > 0) {
                    foreach ($availability as $slot) {
                        $slotDate = \Carbon\Carbon::parse($slot['date']);
                        if ($slotDate->isFuture() || $slotDate->isToday()) {
                            // Filter times that are in the future or today
                            $filteredTimes = [];
                            foreach ($slot['time'] as $timeRange) {
                                // Parse start time from time range string, e.g. "8:00 AM - 9:00 AM"
                                $startTimeStr = trim(explode('-', $timeRange)[0]);
                                $slotDateTime = \Carbon\Carbon::parse($slot['date'] . ' ' . $startTimeStr);
                                if ($slotDateTime->isFuture() || $slotDateTime->isSameDay($now)) {
                                    $filteredTimes[] = $timeRange;
                                }
                            }
                            if (count($filteredTimes) > 0) {
                                $upcomingAvailability[] = [
                                    'date' => $slot['date'],
                                    'time' => $filteredTimes,
                                ];
                            }
                        }
                    }
                }
            @endphp
            @if (count($upcomingAvailability) === 0)
                <p class="text-gray-500 italic">No upcoming availability (all selected dates have passed)</p>
            @else
                <div class="availability-section" id="availability_section">
                    <div id="availability_container">
                        @foreach ($upcomingAvailability as $index => $slot)
                            <div class="date-section" id="date{{ $index + 1 }}">
                                <label for="date{{ $index + 1 }}_input" class="font-semibold text-indigo-600 mb-1 block">Date {{ $index + 1 }}:</label>
                                <input type="date" name="availability[date{{ $index + 1 }}]" id="date{{ $index + 1 }}_input" required onchange="checkDuplicateDates()" min="{{ date('Y-m-d') }}" value="{{ old('availability.date' . ($index + 1), $slot['date']) }}" class="rounded-md border border-gray-300 p-2 focus:ring-indigo-500 focus:border-indigo-500 w-full" />
                                <div class="time-slots grid grid-cols-2 gap-2 mt-2">
                                    @foreach ($slot['time'] as $timeRange)
                                        <label class="time-slot-label flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="availability[time{{ $index + 1 }}][]" value="{{ $timeRange }}" checked />
                                            {{ $timeRange }}
                                        </label>
                                    @endforeach
                                    @php
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
                                        $uncheckedTimes = array_diff($allTimes, $slot['time']);
                                    @endphp
                                    @foreach ($uncheckedTimes as $timeRange)
                                        <label class="time-slot-label flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="availability[time{{ $index + 1 }}][]" value="{{ $timeRange }}" />
                                            {{ $timeRange }}
                                        </label>
                                    @endforeach
                                </div>
                                <button type="button" class="remove-date-button rounded-md bg-red-600 text-white px-3 py-1 mt-2 hover:bg-red-700" id="remove-date{{ $index + 1 }}">Remove Date {{ $index + 1 }}</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="add-date-button rounded-md bg-green-600 text-white px-4 py-2 mt-4 hover:bg-green-700" id="add-date-btn">Add Another Date</button>
                </div>
            @endif
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
                        event.target.value = '';
                    }
                }
                document.getElementById('add-date-btn').addEventListener('click', () => {
                    setTimeout(() => {
                        const dateInputs = document.querySelectorAll('input[type="date"][name^="availability[date"]');
                        dateInputs.forEach(input => {
                            input.removeEventListener('change', checkDuplicateDates);
                            input.addEventListener('change', checkDuplicateDates);
                            const today = new Date().toISOString().split('T')[0];
                            input.setAttribute('min', today);
                        });
                    }, 100);
                });
                function setMinDate() {
                    const today = new Date().toISOString().split('T')[0];
                    const dateInputs = document.querySelectorAll('input[type="date"][name^="availability[date"]');
                    dateInputs.forEach(input => {
                        input.setAttribute('min', today);
                    });
                }
                document.addEventListener('DOMContentLoaded', () => {
                    setMinDate();
                    setInterval(() => {
                        setMinDate();
                    }, 60 * 60 * 1000);
                });
                document.getElementById('profile-update-form').addEventListener('submit', function(event) {
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

        <div class="flex items-center gap-6">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-base text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
