<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TeacherAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::post('/teacher/store', [AuthController::class, 'teacherStore'])->name('teacher.store');
Route::post('/student/store', [AuthController::class, 'studentStore'])->name('student.store');

Route::get('/student-report/pdf/{id}', [ReportController::class, 'studentReportPdf'])
    ->name('studentReport.pdf');
