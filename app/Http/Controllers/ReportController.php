<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Student;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;
use App\Models\AssignmentStudent;
use App\Models\AttendanceStudent;
use App\Models\ClassMapping;
use App\Models\StudentParticipation;
use App\Models\Grade;

class ReportController extends Controller
{

    public function studentReportPdf($id)
    {
        // Fetch the student with all related data using Eloquent relationships
        $student = Student::with([
            'studentDetails',
            'markEntries',
            'attendences', // Fixed typo: attendances
            'assignments',
            'scholorships', // Fixed typo: scholarships
            'positiveBehaviours',
            'negativeBehaviours',
            'participations',
            'classMappings',
        ])->findOrFail($id);

        // Generate the PDF with all the data
        $pdf = Pdf::view('pdf.studentReport', [
            'student' => $student,
        ])
            ->format('A4') // A4 size
            ->name('student-report-' . $student->id . '.pdf')
            ->download(); // Forces download

        return $pdf;
    }
}
