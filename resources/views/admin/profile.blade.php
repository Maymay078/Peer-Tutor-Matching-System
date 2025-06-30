@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
<h2 class="text-3xl font-semibold mb-6">Profile</h2>

<div class="bg-white p-6 rounded shadow max-w-lg">
    <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-2">Full Name:</label>
        <p class="text-lg">{{ auth()->user()->full_name }}</p>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-2">Username:</label>
        <p class="text-lg">{{ auth()->user()->username }}</p>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-2">Email:</label>
        <p class="text-lg">{{ auth()->user()->email }}</p>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-2">Phone Number:</label>
        <p class="text-lg">{{ auth()->user()->phone_number }}</p>
    </div>
    <div class="mb-4">
        <label class="block text-gray-700 font-bold mb-2">Date of Birth:</label>
        <p class="text-lg">{{ auth()->user()->date_of_birth }}</p>
    </div>
</div>
@endsection
