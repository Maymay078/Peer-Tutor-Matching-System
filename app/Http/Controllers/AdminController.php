<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Tutor;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalStudents = Student::count();
        $totalTutors = Tutor::count();

        $students = Student::with('user')->get();
        $tutors = Tutor::with('user')->get();

        return view('admin.dashboard', compact('totalUsers', 'totalStudents', 'totalTutors', 'students', 'tutors'));
    }
}
