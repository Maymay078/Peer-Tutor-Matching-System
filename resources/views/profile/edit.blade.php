<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight mb-6">
                {{ __('Profile') }}
            </h2>
            <div class="flex items-center space-x-4">
                <a href="{{ route('home.student') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Home</a>
                <button aria-label="Notifications" class="relative p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8a6 6 0 00-12 0c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 01-3.46 0"></path>
                    </svg>
                    <span class="absolute top-0 right-0 block w-2 h-2 bg-red-600 rounded-full"></span>
                </button>
                <button aria-label="Chat" class="relative p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"></path>
                    </svg>
                    <span class="absolute top-0 right-0 block w-2 h-2 bg-green-600 rounded-full"></span>
                </button>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto p-8 bg-gray-50 min-h-screen rounded-xl">
        <div class="flex flex-col md:flex-row md:space-x-12">
            <div class="flex flex-col items-center md:items-start md:w-1/3 mb-8 md:mb-0 bg-white rounded-xl shadow-md p-8">
                @php
                    $profileImage = auth()->user()->profile_image;
                @endphp
                @if($profileImage)
                    <img src="{{ (Str::startsWith($profileImage, ['http://', 'https://'])) ? $profileImage : asset('storage/' . $profileImage) }}" alt="Profile Image" class="w-32 h-32 rounded-full object-cover mb-4" />
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->full_name) }}&background=random&color=fff" alt="Default Profile Image" class="w-32 h-32 rounded-full object-cover mb-4" />
                @endif
                
            </div>

            <div class="md:w-2/3 space-y-8">
                <div class="bg-white rounded-xl shadow-md p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="bg-white rounded-xl shadow-md p-8">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="bg-white rounded-xl shadow-md p-8">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
