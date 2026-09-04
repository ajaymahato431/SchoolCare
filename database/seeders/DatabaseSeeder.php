<?php

namespace Database\Seeders;

use App\Models\Activities;
use App\Models\Admin;
use App\Models\AdminDetail;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\BatchYear;
use App\Models\ClassMapping;
use App\Models\ExamType;
use App\Models\Grade;
use App\Models\MarkEntry;
use App\Models\Municipality;
use App\Models\NegativeBehaviour;
use App\Models\PositiveBehaviour;
use App\Models\Scholorship;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentDetail;
use App\Models\StudentParticipation;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherDetail;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Model::unguarded(function () {
            DB::transaction(function () {
                $grades = collect([9, 10, 11])->mapWithKeys(fn(int $value) => [
                    $value => Grade::query()->firstOrCreate(['grade' => $value]),
                ]);

                $sections = collect(['A', 'B'])->mapWithKeys(fn(string $value) => [
                    $value => Section::query()->firstOrCreate(['section' => $value]),
                ]);

                $municipalities = collect([
                    'Kathmandu Metropolitan',
                    'Lalitpur Metropolitan',
                ])->mapWithKeys(fn(string $value) => [
                    $value => Municipality::query()->firstOrCreate(['municipality' => $value]),
                ]);

                $wards = collect([1, 2, 3])->mapWithKeys(fn(int $value) => [
                    $value => Ward::query()->firstOrCreate(['ward' => $value]),
                ]);

                $subjects = collect(['Mathematics', 'Science', 'English'])->mapWithKeys(fn(string $value) => [
                    $value => Subject::query()->firstOrCreate(['subject' => $value]),
                ]);

                $examTypes = collect(['Mid Term', 'Final Term'])->mapWithKeys(fn(string $value) => [
                    $value => ExamType::query()->firstOrCreate(['exam_type' => $value]),
                ]);

                $batchYears = collect([
                    '2023/24' => false,
                    '2024/25' => true,
                ])->map(function (bool $isActive, string $value) {
                    return BatchYear::query()->updateOrCreate(
                        ['batch' => $value],
                        ['is_active' => $isActive]
                    );
                });

                $subjects = collect([
                    ['subject' => 'Mathematics', 'code' => 'MTH-101', 'full_marks' => 100, 'pass_marks' => 40],
                    ['subject' => 'Science', 'code' => 'SCI-101', 'full_marks' => 100, 'pass_marks' => 40],
                    ['subject' => 'English', 'code' => 'ENG-101', 'full_marks' => 100, 'pass_marks' => 40],
                ])->mapWithKeys(fn(array $data) => [
                    $data['subject'] => Subject::query()->updateOrCreate(
                        ['subject' => $data['subject']],
                        $data
                    ),
                ]);

                $examTypes = collect(['Mid Term', 'Final Term'])->mapWithKeys(fn(string $value) => [
                    $value => ExamType::query()->firstOrCreate(['exam_type' => $value]),
                ]);

                $gradeNine = $grades->get(9);
                $gradeTen = $grades->get(10);
                $sectionA = $sections->get('A');
                $sectionB = $sections->get('B');
                $kathmandu = $municipalities->get('Kathmandu Metropolitan');
                $lalitpur = $municipalities->get('Lalitpur Metropolitan');
                $wardOne = $wards->get(1);
                $wardTwo = $wards->get(2);
                $math = $subjects->get('Mathematics');
                $science = $subjects->get('Science');
                $midTerm = $examTypes->get('Mid Term');
                $finalTerm = $examTypes->get('Final Term');
                $batch2324 = $batchYears->get('2023/24');
                $batch2425 = $batchYears->get('2024/25');

                $defaultUser = User::query()->firstOrNew(['email' => 'user@example.com']);
                $defaultUser->fill([
                    'name' => 'Default User',
                    'password' => 'password',
                    'email_verified_at' => Carbon::now(),
                ]);
                $defaultUser->save();

                $admin = Admin::query()->firstOrNew(['email' => 'admin@gmail.com']);
                $admin->fill([
                    'name' => 'System Administrator',
                    'password' => 'password',
                    'email_verified_at' => Carbon::now(),
                ]);
                $admin->save();

                AdminDetail::query()->updateOrCreate(
                    ['admin_id' => $admin->id],
                    [
                        'phone' => '9800000001',
                        'address' => 'Main Office, Kathmandu',
                    ]
                );

                $teacher = Teacher::query()->firstOrNew(['email' => 'teacher@gmail.com']);
                $teacher->fill([
                    'name' => 'Lead Teacher',
                    'password' => 'password',
                    'status' => 'approved',
                    'email_verified_at' => Carbon::now(),
                ]);
                $teacher->save();

                TeacherDetail::query()->updateOrCreate(
                    ['teacher_id' => $teacher->id],
                    [
                        'phone' => '9800000002',
                        'address' => 'Lalitpur',
                        'gender' => 'Female',
                        'municipality_id' => $lalitpur?->id,
                        'ward_id' => $wardTwo?->id,
                        'subject_id' => $science?->id,
                    ]
                );

                // Additional pending teacher for UX demonstration
                $pendingTeacher = Teacher::query()->firstOrNew(['email' => 'sarah.smith@gmail.com']);
                $pendingTeacher->fill([
                    'name' => 'Sarah Smith',
                    'password' => 'password',
                    'status' => 'pending',
                ]);
                $pendingTeacher->save();

                TeacherDetail::query()->updateOrCreate(
                    ['teacher_id' => $pendingTeacher->id],
                    [
                        'phone' => '9800000012',
                        'address' => 'Kathmandu',
                        'gender' => 'Female',
                        'municipality_id' => $kathmandu?->id,
                        'ward_id' => $wardOne?->id,
                        'subject_id' => $math?->id,
                    ]
                );

                $student = Student::query()->firstOrNew(['email' => 'student@gmail.com']);
                $student->fill([
                    'name' => 'Model Student',
                    'password' => 'password',
                    'status' => 'approved',
                    'email_verified_at' => Carbon::now(),
                ]);
                $student->save();

                StudentDetail::query()->updateOrCreate(
                    ['student_id' => $student->id],
                    [
                        'phone' => '9800000003',
                        'address' => 'Kathmandu',
                        'gender' => 'Male',
                        'municipality_id' => $kathmandu?->id,
                        'ward_id' => $wardOne?->id,
                        'blood_group' => 'O+',
                    ]
                );

                ClassMapping::query()->updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'grade_id' => $gradeNine?->id,
                        'section_id' => $sectionA?->id,
                    ],
                    [
                        'batch_year_id' => $batch2425?->id,
                        'roll_no' => '01',
                        'start_date' => Carbon::now()->subMonths(2)->toDateString(),
                        'end_date' => null,
                    ]
                );

                // Additional pending student for UX testing
                $pendingStudent = Student::query()->firstOrNew(['email' => 'alex.doe@gmail.com']);
                $pendingStudent->fill([
                    'name' => 'Alex Doe',
                    'password' => 'password',
                    'status' => 'pending',
                ]);
                $pendingStudent->save();

                StudentDetail::query()->updateOrCreate(
                    ['student_id' => $pendingStudent->id],
                    [
                        'phone' => '9800000025',
                        'address' => 'Lalitpur',
                        'gender' => 'Male',
                        'municipality_id' => $lalitpur?->id,
                        'ward_id' => $wardTwo?->id,
                        'blood_group' => 'A+',
                    ]
                );

                ClassMapping::query()->updateOrCreate(
                    [
                        'student_id' => $pendingStudent->id,
                        'grade_id' => $gradeNine?->id,
                        'section_id' => $sectionA?->id,
                    ],
                    [
                        'batch_year_id' => $batch2425?->id,
                        'roll_no' => '02',
                        'start_date' => Carbon::now()->toDateString(),
                    ]
                );

                $activity = Activities::query()->updateOrCreate(
                    [
                        'name' => 'Annual Science Fair',
                        'start_date' => Carbon::now()->subMonths(1)->toDateString(),
                    ],
                    [
                        'category' => 'STEM',
                        'end_date' => Carbon::now()->subMonths(1)->addDays(2)->toDateString(),
                        'organizer' => 'Science Club',
                        'address' => 'School Auditorium',
                        'description' => 'Inter-school STEM project exhibition and innovation competition.',
                    ]
                );

                StudentParticipation::query()->updateOrCreate(
                    [
                        'activity_id' => $activity->id,
                        'student_id' => $student->id,
                    ],
                    [
                        'obtained_rank' => 'First',
                        'role_or_position' => 'Team Lead & Presenter',
                        'certificate_issued' => true,
                    ]
                );

                $scholarship = Scholorship::query()->updateOrCreate(
                    ['name' => 'Merit Scholarship'],
                    [
                        'amount' => 1500,
                        'criteria' => 'Awarded to top-performing students in academics and discipline',
                        'year' => '2024',
                        'status' => 'active',
                        'batch_year_id' => $batch2425?->id,
                    ]
                );

                DB::table('scholorship_student')->updateOrInsert(
                    [
                        'scholorship_id' => $scholarship->id,
                        'student_id' => $student->id,
                    ],
                    [
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]
                );

                $attendance = Attendance::query()->updateOrCreate(
                    [
                        'name' => 'Morning Roll Call',
                        'attendance_date' => Carbon::today()->toDateString(),
                        'teacher_id' => $teacher->id,
                        'grade_id' => $gradeNine?->id,
                    ],
                    [
                        'section_id' => $sectionA?->id,
                    ]
                );

                DB::table('attendance_student')->updateOrInsert(
                    [
                        'attendance_id' => $attendance->id,
                        'student_id' => $student->id,
                    ],
                    [
                        'status' => 'present',
                        'remarks' => 'On time',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]
                );

                $assignment = Assignment::query()->updateOrCreate(
                    [
                        'name' => 'Science Project Report',
                        'teacher_id' => $teacher->id,
                        'grade_id' => $gradeNine?->id,
                        'subject_id' => $science?->id,
                    ],
                    [
                        'description' => 'Write a comprehensive 5-page report on renewable energy solutions.',
                        'max_marks' => 100,
                        'assignment_date' => Carbon::now()->subDays(7)->toDateString(),
                        'submission_date' => Carbon::now()->addDays(7)->toDateString(),
                    ]
                );

                DB::table('assignment_student')->updateOrInsert(
                    [
                        'assignment_id' => $assignment->id,
                        'student_id' => $student->id,
                    ],
                    [
                        'status' => 'submitted',
                        'submitted_at' => Carbon::now()->subDays(2),
                        'marks_obtained' => 95.0,
                        'feedback' => 'Well-researched and clearly articulated.',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]
                );

                // Legacy behavior entries for backward compatibility
                PositiveBehaviour::query()->updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'grade_id' => $gradeNine?->id,
                        'report' => 'Assisted classmates during lab activities',
                    ],
                    [
                        'event_date' => Carbon::now()->subWeeks(1)->toDateString(),
                    ]
                );

                NegativeBehaviour::query()->updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'grade_id' => $gradeNine?->id,
                        'report' => 'Late submission of math homework',
                    ],
                    [
                        'event_date' => Carbon::now()->subWeeks(2)->toDateString(),
                    ]
                );

                // Unified Student Behaviors (Merit & Demerit)
                \App\Models\StudentBehavior::query()->updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'title' => 'Exemplary Lab Mentorship',
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'grade_id' => $gradeNine?->id,
                        'type' => 'positive',
                        'category' => 'Leadership',
                        'severity' => 'exceptional',
                        'description' => 'Guided junior peers through safety protocols and demonstrated exceptional teamwork in chemistry experiments.',
                        'action_taken' => 'Commendation Certificate',
                        'points' => 10,
                        'event_date' => Carbon::now()->subWeeks(1)->toDateString(),
                    ]
                );

                \App\Models\StudentBehavior::query()->updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'title' => 'Late Homework Submission',
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'grade_id' => $gradeNine?->id,
                        'type' => 'negative',
                        'category' => 'Punctuality',
                        'severity' => 'minor',
                        'description' => 'Submitted assignment 2 days after deadline without prior notification.',
                        'action_taken' => 'Verbal Reminder & Grace Extension',
                        'points' => -2,
                        'event_date' => Carbon::now()->subWeeks(2)->toDateString(),
                    ]
                );

                MarkEntry::query()->updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'grade_id' => $gradeNine?->id,
                        'exam_type_id' => $midTerm?->id,
                        'subject_id' => $math?->id,
                        'batch_year_id' => $batch2324?->id,
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'marks_obtained' => 88.5,
                        'full_marks' => 100,
                        'pass_marks' => 40,
                        'remarks' => 'Great grasp of core concepts',
                    ]
                );

                MarkEntry::query()->updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'grade_id' => $gradeNine?->id,
                        'exam_type_id' => $finalTerm?->id,
                        'subject_id' => $science?->id,
                        'batch_year_id' => $batch2425?->id,
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'marks_obtained' => 92.0,
                        'full_marks' => 100,
                        'pass_marks' => 40,
                        'remarks' => 'Outstanding experimental skills',
                    ]
                );
            });
        });
    }
}
