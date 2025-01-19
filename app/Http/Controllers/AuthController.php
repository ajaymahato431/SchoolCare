<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentDetail;
use App\Models\Teacher;
use App\Models\TeacherDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function teacherStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'required|string|max:15',
        ]);

        $password = rand(11111, 99999);

        // Store teacher data
        $teacher = Teacher::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        // Store phone in teacher_detail
        TeacherDetail::create([
            'teacher_id' => $teacher->id,
            'phone' => $request->phone,
        ]);

        return redirect()->back()->with('success', 'Teacher registered successfully!');
    }

    public function studentStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'phone' => 'required|string|max:15',
        ]);

        $password = rand(11111, 99999);

        // Store teacher data
        $student = Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        // Store phone in teacher_detail
        StudentDetail::create([
            'student_id' => $student->id,
            'phone' => $request->phone,
        ]);

        return redirect()->back()->with('success', 'Student registered successfully!');
    }
}
