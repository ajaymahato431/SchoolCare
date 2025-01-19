<?php

namespace App\Observers;

use App\Mail\UserStatusNotification;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class TeacherObserver
{
    /**
     * Handle the Teacher "created" event.
     */
    public function created(Teacher $teacher): void
    {
        //
    }

    /**
     * Handle the Teacher "updated" event.
     */
    public function updated(Teacher $teacher): void
    {
        if ($teacher->isDirty('status') && $teacher->status == 'approved') {
            $password = rand(11111, 99999);

            $teacher->withoutEvents(function () use ($teacher, $password) {
                $teacher->password = Hash::make($password);
                $teacher->update();
            });

            $data = [
                "name" => $teacher->name,
                "subject" => "Teacher Registration Approved",
                "message1" => "Your registration request to Learners Log has been approved.",
                "message2" => "Your Login Credentials are Email: $teacher->email and Password: $password",
                "message3" => "Please visit this url to login: " . route('filament.teacher.auth.login'),
            ];

            Mail::to($teacher->email)->send(new UserStatusNotification($data));
        }

        if ($teacher->isDirty('status') && $teacher->status == 'reject') {

            $data = [
                "name" => $teacher->name,
                "subject" => "Teacher Registration Rejected",
                "message1" => "We're sorry, but your registration could not be processed at this time.",
                "message2" => "Please ensure all details are correct and meet our requirements.",
                "message3" => "For assistance, contact support or try again later.",
            ];

            Mail::to(users: $teacher->email)->send(new UserStatusNotification($data));
        };
    }

    /**
     * Handle the Teacher "deleted" event.
     */
    public function deleted(Teacher $teacher): void
    {
        //
    }

    /**
     * Handle the Teacher "restored" event.
     */
    public function restored(Teacher $teacher): void
    {
        //
    }

    /**
     * Handle the Teacher "force deleted" event.
     */
    public function forceDeleted(Teacher $teacher): void
    {
        //
    }
}
