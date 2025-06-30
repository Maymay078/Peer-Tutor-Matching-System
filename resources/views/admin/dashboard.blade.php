@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="flex flex-col md:flex-row gap-6">
    <div class="md:w-1/4 bg-gradient-to-br from-indigo-600 to-purple-700 rounded-lg p-8 text-white flex flex-col items-center justify-center shadow-lg">
        <div class="bg-white rounded-full p-4 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-indigo-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 21.75c-2.485 0-4.78-.755-6.825-2.05a12.083 12.083 0 01.665-6.479L12 14z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14v7.5" />
            </svg>
        </div>
        <h2 class="text-3xl font-bold mb-2">Welcome Back!</h2>
        <p class="text-center">Connect with peers, share knowledge, and enhance your learning journey together.</p>
    </div>

    <div class="md:w-3/4 space-y-6">
        <div class="grid grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded shadow text-center">
                <h3 class="text-xl font-bold mb-2 text-indigo-700">Total Users</h3>
                <p class="text-4xl font-extrabold text-gray-900">{{ $totalUsers ?? 'N/A' }}</p>
            </div>
            <div class="bg-white p-6 rounded shadow text-center">
                <h3 class="text-xl font-bold mb-2 text-indigo-700">Total Students</h3>
                <p class="text-4xl font-extrabold text-gray-900">{{ $totalStudents ?? 'N/A' }}</p>
            </div>
            <div class="bg-white p-6 rounded shadow text-center">
                <h3 class="text-xl font-bold mb-2 text-indigo-700">Total Tutors</h3>
                <p class="text-4xl font-extrabold text-gray-900">{{ $totalTutors ?? 'N/A' }}</p>
            </div>
        </div>

        <div>
            <h3 class="text-2xl font-semibold mb-4">Student Profiles</h3>
            <div class="overflow-x-auto rounded-lg shadow">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Full Name</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Email</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Major</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $student->user->full_name ?? 'N/A' }}</td>
                            <td class="py-3 px-4">{{ $student->user->email ?? 'N/A' }}</td>
                            <td class="py-3 px-4">{{ $student->major ?? 'N/A' }}</td>
                            <td class="py-3 px-4">{{ $student->year ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            <h3 class="text-2xl font-semibold mb-4">Tutor Profiles</h3>
            <div class="overflow-x-auto rounded-lg shadow">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Full Name</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Email</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Expertise</th>
                            <th class="text-left py-3 px-4 font-medium text-gray-700">Payment Details (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tutors as $tutor)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $tutor->user->full_name ?? 'N/A' }}</td>
                            <td class="py-3 px-4">{{ $tutor->user->email ?? 'N/A' }}</td>
                            <td class="py-3 px-4">{{ $tutor->expertise ?? 'N/A' }}</td>
                            <td class="py-3 px-4">{{ number_format($tutor->payment_details ?? 0, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
