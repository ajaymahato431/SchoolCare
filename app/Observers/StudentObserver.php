<?php

namespace App\Observers;

use App\Mail\UserStatusNotification;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class StudentObserver
{
    /**
     * Handle the Student "created" event.
     */
    public function created(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "updated" event.
     */
    public function updated(Student $student): void
    {
        if ($student->isDirty('status') && $student->status == 'approved') {
            $password = rand(11111, 99999);

            $student->withoutEvents(function () use ($student, $password) {
                $student->password = Hash::make($password);
                $student->update();
            });

            $data = [
                "name" => $student->name,
                "subject" => "Student Registration Approved",
                "message1" => "Your registration request to Learners Log has been approved.",
                "message2" => "Your Login Credentials are Email: $student->email and Password: $password",
                "message3" => "Please visit this url to login: " . route('filament.student.auth.login'),
            ];

            Mail::to($student->email)->send(new UserStatusNotification($data));
        }

        if ($student->isDirty('status') && $student->status == 'reject') {

            $data = [
                "name" => $student->name,
                "subject" => "Student Registration Rejected",
                "message1" => "We're sorry, but your registration could not be processed at this time.",
                "message2" => "Please ensure all details are correct and meet our requirements.",
                "message3" => "For assistance, contact support or try again later.",
            ];

            Mail::to(users: $student->email)->send(new UserStatusNotification($data));
        };
    }

    /**
     * Handle the Student "deleted" event.
     */
    public function deleted(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "restored" event.
     */
    public function restored(Student $student): void
    {
        //
    }

    /**
     * Handle the Student "force deleted" event.
     */
    public function forceDeleted(Student $student): void
    {
        //
    }
}
