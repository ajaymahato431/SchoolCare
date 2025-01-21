<?php

namespace App\Filament\Student\Widgets;

use App\Models\Assignment;
use App\Models\Attendance;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class HeadingWidget extends Widget
{
    protected static string $view = 'filament.student.widgets.heading-widget';

    protected int | string | array $columnSpan = 'full';

    public $name;
    public $grade;
    public $total_attendance;
    public $total_assignment;
    public $present;
    public $notPresent;
    public $assignment;
    public $notAssignment;

    public function mount()
    {
        $student = Auth::user();

        $this->name = $student->name;
        $this->grade = $student->classMappings()
            ->latest('id') // Sort by the most recent 'id'
            ->value('grade_id'); // Fetch the 'grade_id' value

        $this->total_attendance = Attendance::whereHas('students', function ($query) {
            $query->where('grade_id', $this->grade); // Assuming grade_id exists in students table
        })->count();

        $this->total_assignment = Assignment::whereHas('students', function ($query) {
            $query->where('grade_id', $this->grade); // Assuming grade_id exists in students table
        })->count();


        $this->present = $student->attendences->filter(function ($attendance) use ($student) {
            return $attendance->students->contains($student);
        })->count();

        $this->notPresent = $this->total_attendance - $this->present;

        // Fetch assignment completion and not-completed counts
        $this->assignment = $student->assignments->filter(function ($assignment) use ($student) {
            return $assignment->students->contains($student);
        })->count();

        $this->notAssignment = $this->total_assignment - $this->assignment;
    }
}
