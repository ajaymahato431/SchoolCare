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
use App\Models\StudentBehavior;
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
                // 1. Core Reference / Lookup Tables
                $grades = collect([8, 9, 10, 11, 12])->mapWithKeys(fn(int $value) => [
                    $value => Grade::query()->firstOrCreate(['grade' => $value]),
                ]);

                $sections = collect(['A', 'B', 'C'])->mapWithKeys(fn(string $value) => [
                    $value => Section::query()->firstOrCreate(['section' => $value]),
                ]);

                $municipalities = collect([
                    'Kathmandu Metropolitan',
                    'Lalitpur Metropolitan',
                    'Bhaktapur Municipality',
                    'Pokhara Metropolitan',
                ])->mapWithKeys(fn(string $value) => [
                    $value => Municipality::query()->firstOrCreate(['municipality' => $value]),
                ]);

                $wards = collect(range(1, 10))->mapWithKeys(fn(int $value) => [
                    $value => Ward::query()->firstOrCreate(['ward' => $value]),
                ]);

                $subjects = collect([
                    ['subject' => 'Mathematics', 'code' => 'MTH-101', 'full_marks' => 100, 'pass_marks' => 40],
                    ['subject' => 'Science', 'code' => 'SCI-101', 'full_marks' => 100, 'pass_marks' => 40],
                    ['subject' => 'English', 'code' => 'ENG-101', 'full_marks' => 100, 'pass_marks' => 40],
                    ['subject' => 'Nepali', 'code' => 'NEP-101', 'full_marks' => 100, 'pass_marks' => 40],
                    ['subject' => 'Social Studies', 'code' => 'SOC-101', 'full_marks' => 100, 'pass_marks' => 40],
                    ['subject' => 'Computer Science', 'code' => 'CS-101', 'full_marks' => 100, 'pass_marks' => 40],
                    ['subject' => 'Accountancy', 'code' => 'ACC-101', 'full_marks' => 100, 'pass_marks' => 40],
                ])->mapWithKeys(fn(array $data) => [
                    $data['subject'] => Subject::query()->updateOrCreate(
                        ['subject' => $data['subject']],
                        $data
                    ),
                ]);

                $examTypes = collect(['First Term', 'Mid Term', 'Final Term'])->mapWithKeys(fn(string $value) => [
                    $value => ExamType::query()->firstOrCreate(['exam_type' => $value]),
                ]);

                $batchYears = collect([
                    '2023/24' => false,
                    '2024/25' => true,
                    '2025/26' => false,
                ])->mapWithKeys(function (bool $isActive, string $value) {
                    return [
                        $value => BatchYear::query()->updateOrCreate(
                            ['batch' => $value],
                            ['is_active' => $isActive]
                        ),
                    ];
                });

                // Key Lookups for convenience
                $grade8 = $grades->get(8);
                $grade9 = $grades->get(9);
                $grade10 = $grades->get(10);
                $grade11 = $grades->get(11);
                $grade12 = $grades->get(12);

                $secA = $sections->get('A');
                $secB = $sections->get('B');
                $secC = $sections->get('C');

                $ktm = $municipalities->get('Kathmandu Metropolitan');
                $lalitpur = $municipalities->get('Lalitpur Metropolitan');
                $bhaktapur = $municipalities->get('Bhaktapur Municipality');
                $pokhara = $municipalities->get('Pokhara Metropolitan');

                $math = $subjects->get('Mathematics');
                $science = $subjects->get('Science');
                $english = $subjects->get('English');
                $nepali = $subjects->get('Nepali');
                $social = $subjects->get('Social Studies');
                $cs = $subjects->get('Computer Science');
                $account = $subjects->get('Accountancy');

                $firstTerm = $examTypes->get('First Term');
                $midTerm = $examTypes->get('Mid Term');
                $finalTerm = $examTypes->get('Final Term');

                $batch2324 = $batchYears->get('2023/24');
                $batch2425 = $batchYears->get('2024/25');
                $batch2526 = $batchYears->get('2025/26');

                // 2. Default User & System Administrator
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
                        'address' => 'Administrative Complex, Kathmandu',
                    ]
                );

                // 3. Teachers (Approved Faculty & Pending Applicants)
                $teacherDefinitions = [
                    [
                        'name' => 'Dr. Rajesh Sharma',
                        'email' => 'teacher@gmail.com',
                        'status' => 'approved',
                        'phone' => '9800000002',
                        'address' => 'Kupondole, Lalitpur',
                        'gender' => 'Male',
                        'municipality_id' => $lalitpur?->id,
                        'ward_id' => 2,
                        'subject_id' => $science?->id,
                    ],
                    [
                        'name' => 'John Doe',
                        'email' => 'john.doe@gmail.com',
                        'status' => 'approved',
                        'phone' => '9800000004',
                        'address' => 'Lazimpat, Kathmandu',
                        'gender' => 'Male',
                        'municipality_id' => $ktm?->id,
                        'ward_id' => 1,
                        'subject_id' => $english?->id,
                    ],
                    [
                        'name' => 'Anita Rai',
                        'email' => 'anita.rai@gmail.com',
                        'status' => 'approved',
                        'phone' => '9800000005',
                        'address' => 'Jhamsikhel, Lalitpur',
                        'gender' => 'Female',
                        'municipality_id' => $lalitpur?->id,
                        'ward_id' => 3,
                        'subject_id' => $cs?->id,
                    ],
                    [
                        'name' => 'Bikash Thapa',
                        'email' => 'bikash.thapa@gmail.com',
                        'status' => 'approved',
                        'phone' => '9800000006',
                        'address' => 'Suryabinayak, Bhaktapur',
                        'gender' => 'Male',
                        'municipality_id' => $bhaktapur?->id,
                        'ward_id' => 4,
                        'subject_id' => $social?->id,
                    ],
                    [
                        'name' => 'Sarah Smith',
                        'email' => 'sarah.smith@gmail.com',
                        'status' => 'pending',
                        'phone' => '9800000012',
                        'address' => 'Baluwatar, Kathmandu',
                        'gender' => 'Female',
                        'municipality_id' => $ktm?->id,
                        'ward_id' => 1,
                        'subject_id' => $math?->id,
                    ],
                    [
                        'name' => 'Priya Shrestha',
                        'email' => 'priya.shrestha@gmail.com',
                        'status' => 'pending',
                        'phone' => '9800000013',
                        'address' => 'Lakeside, Pokhara',
                        'gender' => 'Female',
                        'municipality_id' => $pokhara?->id,
                        'ward_id' => 5,
                        'subject_id' => $nepali?->id,
                    ],
                ];

                $teachers = collect();
                foreach ($teacherDefinitions as $def) {
                    $t = Teacher::query()->firstOrNew(['email' => $def['email']]);
                    $t->fill([
                        'name' => $def['name'],
                        'password' => 'password',
                        'status' => $def['status'],
                        'email_verified_at' => $def['status'] === 'approved' ? Carbon::now() : null,
                    ]);
                    $t->save();

                    TeacherDetail::query()->updateOrCreate(
                        ['teacher_id' => $t->id],
                        [
                            'phone' => $def['phone'],
                            'address' => $def['address'],
                            'gender' => $def['gender'],
                            'municipality_id' => $def['municipality_id'],
                            'ward_id' => $wards->get($def['ward_id'])?->id,
                            'subject_id' => $def['subject_id'],
                        ]
                    );

                    $teachers->put($def['email'], $t);
                }

                $leadTeacher = $teachers->get('teacher@gmail.com');
                $englishTeacher = $teachers->get('john.doe@gmail.com');
                $csTeacher = $teachers->get('anita.rai@gmail.com');
                $socialTeacher = $teachers->get('bikash.thapa@gmail.com');

                // 4. Students (Approved Cohorts & Pending Applicants)
                $studentDefinitions = [
                    [
                        'name' => 'Aarav Sharma',
                        'email' => 'student@gmail.com',
                        'status' => 'approved',
                        'phone' => '9800000003',
                        'address' => 'Baneshwor, Kathmandu',
                        'gender' => 'Male',
                        'blood_group' => 'O+',
                        'municipality_id' => $ktm?->id,
                        'ward_id' => 1,
                        'grade_id' => $grade10?->id,
                        'section_id' => $secA?->id,
                        'batch_year_id' => $batch2425?->id,
                        'roll_no' => '01',
                    ],
                    [
                        'name' => 'Rohan Adhikari',
                        'email' => 'rohan.adhikari@gmail.com',
                        'status' => 'approved',
                        'phone' => '9800000021',
                        'address' => 'Thamel, Kathmandu',
                        'gender' => 'Male',
                        'blood_group' => 'B+',
                        'municipality_id' => $ktm?->id,
                        'ward_id' => 2,
                        'grade_id' => $grade10?->id,
                        'section_id' => $secA?->id,
                        'batch_year_id' => $batch2425?->id,
                        'roll_no' => '02',
                    ],
                    [
                        'name' => 'Sneha Pandey',
                        'email' => 'sneha.pandey@gmail.com',
                        'status' => 'approved',
                        'phone' => '9800000022',
                        'address' => 'Patan, Lalitpur',
                        'gender' => 'Female',
                        'blood_group' => 'A+',
                        'municipality_id' => $lalitpur?->id,
                        'ward_id' => 2,
                        'grade_id' => $grade10?->id,
                        'section_id' => $secB?->id,
                        'batch_year_id' => $batch2425?->id,
                        'roll_no' => '01',
                    ],
                    [
                        'name' => 'Kiran Shrestha',
                        'email' => 'kiran.shrestha@gmail.com',
                        'status' => 'approved',
                        'phone' => '9800000023',
                        'address' => 'Sanepa, Lalitpur',
                        'gender' => 'Male',
                        'blood_group' => 'AB+',
                        'municipality_id' => $lalitpur?->id,
                        'ward_id' => 3,
                        'grade_id' => $grade10?->id,
                        'section_id' => $secB?->id,
                        'batch_year_id' => $batch2425?->id,
                        'roll_no' => '02',
                    ],
                    [
                        'name' => 'Ayush K.C.',
                        'email' => 'ayush.kc@gmail.com',
                        'status' => 'approved',
                        'phone' => '9800000024',
                        'address' => 'Radhe Radhe, Bhaktapur',
                        'gender' => 'Male',
                        'blood_group' => 'O+',
                        'municipality_id' => $bhaktapur?->id,
                        'ward_id' => 4,
                        'grade_id' => $grade9?->id,
                        'section_id' => $secA?->id,
                        'batch_year_id' => $batch2425?->id,
                        'roll_no' => '01',
                    ],
                    [
                        'name' => 'Puja Tamang',
                        'email' => 'puja.tamang@gmail.com',
                        'status' => 'approved',
                        'phone' => '9800000026',
                        'address' => 'Kapan, Kathmandu',
                        'gender' => 'Female',
                        'blood_group' => 'B-',
                        'municipality_id' => $ktm?->id,
                        'ward_id' => 3,
                        'grade_id' => $grade9?->id,
                        'section_id' => $secB?->id,
                        'batch_year_id' => $batch2425?->id,
                        'roll_no' => '01',
                    ],
                    [
                        'name' => 'Bibek Giri',
                        'email' => 'bibek.giri@gmail.com',
                        'status' => 'approved',
                        'phone' => '9800000027',
                        'address' => 'Maharajgunj, Kathmandu',
                        'gender' => 'Male',
                        'blood_group' => 'A-',
                        'municipality_id' => $ktm?->id,
                        'ward_id' => 5,
                        'grade_id' => $grade11?->id,
                        'section_id' => $secA?->id,
                        'batch_year_id' => $batch2425?->id,
                        'roll_no' => '01',
                    ],
                    [
                        'name' => 'Shristi Karki',
                        'email' => 'shristi.karki@gmail.com',
                        'status' => 'approved',
                        'phone' => '9800000028',
                        'address' => 'Jawalakhel, Lalitpur',
                        'gender' => 'Female',
                        'blood_group' => 'O-',
                        'municipality_id' => $lalitpur?->id,
                        'ward_id' => 1,
                        'grade_id' => $grade11?->id,
                        'section_id' => $secB?->id,
                        'batch_year_id' => $batch2425?->id,
                        'roll_no' => '01',
                    ],
                    [
                        'name' => 'Alex Doe',
                        'email' => 'alex.doe@gmail.com',
                        'status' => 'pending',
                        'phone' => '9800000025',
                        'address' => 'Pulchowk, Lalitpur',
                        'gender' => 'Male',
                        'blood_group' => 'A+',
                        'municipality_id' => $lalitpur?->id,
                        'ward_id' => 2,
                        'grade_id' => $grade9?->id,
                        'section_id' => $secA?->id,
                        'batch_year_id' => $batch2425?->id,
                        'roll_no' => '02',
                    ],
                    [
                        'name' => 'Riya Basnet',
                        'email' => 'riya.basnet@gmail.com',
                        'status' => 'pending',
                        'phone' => '9800000029',
                        'address' => 'Chabahil, Kathmandu',
                        'gender' => 'Female',
                        'blood_group' => 'B+',
                        'municipality_id' => $ktm?->id,
                        'ward_id' => 2,
                        'grade_id' => $grade10?->id,
                        'section_id' => $secA?->id,
                        'batch_year_id' => $batch2425?->id,
                        'roll_no' => '03',
                    ],
                ];

                $students = collect();
                foreach ($studentDefinitions as $def) {
                    $s = Student::query()->firstOrNew(['email' => $def['email']]);
                    $s->fill([
                        'name' => $def['name'],
                        'password' => 'password',
                        'status' => $def['status'],
                        'email_verified_at' => $def['status'] === 'approved' ? Carbon::now() : null,
                    ]);
                    $s->save();

                    StudentDetail::query()->updateOrCreate(
                        ['student_id' => $s->id],
                        [
                            'phone' => $def['phone'],
                            'address' => $def['address'],
                            'gender' => $def['gender'],
                            'municipality_id' => $def['municipality_id'],
                            'ward_id' => $wards->get($def['ward_id'])?->id,
                            'blood_group' => $def['blood_group'],
                        ]
                    );

                    if ($def['grade_id'] && $def['section_id']) {
                        ClassMapping::query()->updateOrCreate(
                            [
                                'student_id' => $s->id,
                                'grade_id' => $def['grade_id'],
                                'section_id' => $def['section_id'],
                            ],
                            [
                                'batch_year_id' => $def['batch_year_id'],
                                'roll_no' => $def['roll_no'],
                                'start_date' => Carbon::now()->subMonths(3)->toDateString(),
                                'end_date' => null,
                            ]
                        );
                    }

                    $students->put($def['email'], $s);
                }

                $modelStudent = $students->get('student@gmail.com');
                $rohan = $students->get('rohan.adhikari@gmail.com');
                $sneha = $students->get('sneha.pandey@gmail.com');
                $kiran = $students->get('kiran.shrestha@gmail.com');
                $ayush = $students->get('ayush.kc@gmail.com');
                $bibek = $students->get('bibek.giri@gmail.com');
                $shristi = $students->get('shristi.karki@gmail.com');

                // Previous Year Class Mapping for Model Student (Grade 9 in 2023/24)
                ClassMapping::query()->updateOrCreate(
                    [
                        'student_id' => $modelStudent->id,
                        'grade_id' => $grade9?->id,
                        'section_id' => $secA?->id,
                    ],
                    [
                        'batch_year_id' => $batch2324?->id,
                        'roll_no' => '05',
                        'start_date' => Carbon::now()->subMonths(14)->toDateString(),
                        'end_date' => Carbon::now()->subMonths(3)->toDateString(),
                    ]
                );

                // 5. Daily Attendances & Student Attendance Records
                $attendanceDays = [
                    [
                        'name' => 'Morning Roll Call',
                        'date' => Carbon::today()->toDateString(),
                        'teacher' => $leadTeacher,
                        'grade' => $grade10,
                        'section' => $secA,
                        'records' => [
                            ['student' => $modelStudent, 'status' => 'present', 'remarks' => 'Prompt & attentive'],
                            ['student' => $rohan, 'status' => 'present', 'remarks' => 'On time'],
                        ],
                    ],
                    [
                        'name' => 'Morning Roll Call',
                        'date' => Carbon::yesterday()->toDateString(),
                        'teacher' => $leadTeacher,
                        'grade' => $grade10,
                        'section' => $secA,
                        'records' => [
                            ['student' => $modelStudent, 'status' => 'present', 'remarks' => 'On time'],
                            ['student' => $rohan, 'status' => 'late', 'remarks' => 'Bus delay 15 mins'],
                        ],
                    ],
                    [
                        'name' => 'Daily Attendance',
                        'date' => Carbon::today()->toDateString(),
                        'teacher' => $englishTeacher,
                        'grade' => $grade10,
                        'section' => $secB,
                        'records' => [
                            ['student' => $sneha, 'status' => 'present', 'remarks' => 'On time'],
                            ['student' => $kiran, 'status' => 'excused', 'remarks' => 'Medical leave approved'],
                        ],
                    ],
                    [
                        'name' => 'Morning Roll Call',
                        'date' => Carbon::today()->toDateString(),
                        'teacher' => $csTeacher,
                        'grade' => $grade11,
                        'section' => $secA,
                        'records' => [
                            ['student' => $bibek, 'status' => 'present', 'remarks' => 'On time'],
                        ],
                    ],
                ];

                foreach ($attendanceDays as $attData) {
                    $attendance = Attendance::query()->updateOrCreate(
                        [
                            'name' => $attData['name'],
                            'attendance_date' => $attData['date'],
                            'teacher_id' => $attData['teacher']->id,
                            'grade_id' => $attData['grade']->id,
                        ],
                        [
                            'section_id' => $attData['section']->id,
                        ]
                    );

                    foreach ($attData['records'] as $rec) {
                        DB::table('attendance_student')->updateOrInsert(
                            [
                                'attendance_id' => $attendance->id,
                                'student_id' => $rec['student']->id,
                            ],
                            [
                                'status' => $rec['status'],
                                'remarks' => $rec['remarks'],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]
                        );
                    }
                }

                // 6. Assignments & Submissions
                $assignmentsData = [
                    [
                        'name' => 'Renewable Energy & Solar Cells Research',
                        'description' => 'Write a comprehensive research report comparing modern photovoltaic technologies and solar grid scalability.',
                        'max_marks' => 100,
                        'teacher_id' => $leadTeacher->id,
                        'grade_id' => $grade10?->id,
                        'subject_id' => $science?->id,
                        'assignment_date' => Carbon::now()->subDays(10)->toDateString(),
                        'submission_date' => Carbon::now()->addDays(4)->toDateString(),
                        'submissions' => [
                            [
                                'student' => $modelStudent,
                                'status' => 'graded',
                                'submitted_at' => Carbon::now()->subDays(3),
                                'marks_obtained' => 95.0,
                                'feedback' => 'Exceptional research clarity, detailed diagrams, and well-structured bibliography.',
                            ],
                            [
                                'student' => $rohan,
                                'status' => 'graded',
                                'submitted_at' => Carbon::now()->subDays(2),
                                'marks_obtained' => 86.0,
                                'feedback' => 'Thorough analysis of grid systems. Minor errors in photovoltaic efficiency formulas.',
                            ],
                        ],
                    ],
                    [
                        'name' => 'Trigonometric Identities & Applications',
                        'description' => 'Solve problems 1-25 on angle sum formulas, heights and distances, and coordinate geometry.',
                        'max_marks' => 50,
                        'teacher_id' => $leadTeacher->id,
                        'grade_id' => $grade10?->id,
                        'subject_id' => $math?->id,
                        'assignment_date' => Carbon::now()->subDays(7)->toDateString(),
                        'submission_date' => Carbon::now()->addDays(2)->toDateString(),
                        'submissions' => [
                            [
                                'student' => $modelStudent,
                                'status' => 'graded',
                                'submitted_at' => Carbon::now()->subDays(1),
                                'marks_obtained' => 48.0,
                                'feedback' => 'Accurate algebraic simplifications and correct proofs.',
                            ],
                            [
                                'student' => $sneha,
                                'status' => 'submitted',
                                'submitted_at' => Carbon::now()->subDays(1),
                                'marks_obtained' => null,
                                'feedback' => null,
                            ],
                        ],
                    ],
                    [
                        'name' => 'Persuasive Essay: Ethics of Generative AI',
                        'description' => 'Compose an analytical 1,200-word essay addressing the societal impacts of AI in modern education.',
                        'max_marks' => 50,
                        'teacher_id' => $englishTeacher->id,
                        'grade_id' => $grade10?->id,
                        'subject_id' => $english?->id,
                        'assignment_date' => Carbon::now()->subDays(12)->toDateString(),
                        'submission_date' => Carbon::now()->subDays(2)->toDateString(),
                        'submissions' => [
                            [
                                'student' => $modelStudent,
                                'status' => 'graded',
                                'submitted_at' => Carbon::now()->subDays(4),
                                'marks_obtained' => 47.5,
                                'feedback' => 'Compelling thesis statement, articulate rhetoric, and persuasive case studies.',
                            ],
                            [
                                'student' => $rohan,
                                'status' => 'graded',
                                'submitted_at' => Carbon::now()->subDays(2),
                                'marks_obtained' => 41.0,
                                'feedback' => 'Good overview; focus on stronger transitional phrasing between arguments.',
                            ],
                        ],
                    ],
                    [
                        'name' => 'Data Structures in Python: Hash Maps & Trees',
                        'description' => 'Implement Binary Search Trees and Hash Maps with custom collision resolution and test suites.',
                        'max_marks' => 100,
                        'teacher_id' => $csTeacher->id,
                        'grade_id' => $grade11?->id,
                        'subject_id' => $cs?->id,
                        'assignment_date' => Carbon::now()->subDays(8)->toDateString(),
                        'submission_date' => Carbon::now()->addDays(5)->toDateString(),
                        'submissions' => [
                            [
                                'student' => $bibek,
                                'status' => 'graded',
                                'submitted_at' => Carbon::now()->subDays(2),
                                'marks_obtained' => 98.0,
                                'feedback' => 'Clean OOP architecture, comprehensive unit tests, and optimal time complexity.',
                            ],
                            [
                                'student' => $shristi,
                                'status' => 'graded',
                                'submitted_at' => Carbon::now()->subDays(1),
                                'marks_obtained' => 92.5,
                                'feedback' => 'Elegant recursive methods and clear documentation.',
                            ],
                        ],
                    ],
                ];

                foreach ($assignmentsData as $assignItem) {
                    $assignment = Assignment::query()->updateOrCreate(
                        [
                            'name' => $assignItem['name'],
                            'teacher_id' => $assignItem['teacher_id'],
                            'grade_id' => $assignItem['grade_id'],
                            'subject_id' => $assignItem['subject_id'],
                        ],
                        [
                            'description' => $assignItem['description'],
                            'max_marks' => $assignItem['max_marks'],
                            'assignment_date' => $assignItem['assignment_date'],
                            'submission_date' => $assignItem['submission_date'],
                        ]
                    );

                    foreach ($assignItem['submissions'] as $sub) {
                        DB::table('assignment_student')->updateOrInsert(
                            [
                                'assignment_id' => $assignment->id,
                                'student_id' => $sub['student']->id,
                            ],
                            [
                                'status' => $sub['status'],
                                'submitted_at' => $sub['submitted_at'],
                                'marks_obtained' => $sub['marks_obtained'],
                                'feedback' => $sub['feedback'],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]
                        );
                    }
                }

                // 7. Student Behaviors (Merits & Demerits)
                $behaviorData = [
                    [
                        'student_id' => $modelStudent->id,
                        'teacher_id' => $leadTeacher->id,
                        'grade_id' => $grade10?->id,
                        'title' => 'Exemplary Science Lab Mentorship',
                        'type' => 'positive',
                        'category' => 'Leadership',
                        'severity' => 'exceptional',
                        'description' => 'Mentored junior peers through safety protocols and led the chemistry demonstration team.',
                        'action_taken' => 'Commendation Certificate & Honor Roll',
                        'points' => 10,
                        'event_date' => Carbon::now()->subWeeks(1)->toDateString(),
                    ],
                    [
                        'student_id' => $modelStudent->id,
                        'teacher_id' => $englishTeacher->id,
                        'grade_id' => $grade10?->id,
                        'title' => 'Campus Cleanliness & Green Initiative',
                        'type' => 'positive',
                        'category' => 'ECA',
                        'severity' => 'moderate',
                        'description' => 'Mobilized peers for a weekend campus tree-planting and waste segregation campaign.',
                        'action_taken' => 'House Merit Points',
                        'points' => 5,
                        'event_date' => Carbon::now()->subWeeks(3)->toDateString(),
                    ],
                    [
                        'student_id' => $modelStudent->id,
                        'teacher_id' => $leadTeacher->id,
                        'grade_id' => $grade10?->id,
                        'title' => 'Late Homework Submission',
                        'type' => 'negative',
                        'category' => 'Punctuality',
                        'severity' => 'minor',
                        'description' => 'Turned in math worksheet one day late without prior notice.',
                        'action_taken' => 'Verbal Reminder & Grace Acceptance',
                        'points' => -2,
                        'event_date' => Carbon::now()->subWeeks(2)->toDateString(),
                    ],
                    [
                        'student_id' => $rohan->id,
                        'teacher_id' => $socialTeacher->id,
                        'grade_id' => $grade10?->id,
                        'title' => 'Inter-House Chess Championship Gold',
                        'type' => 'positive',
                        'category' => 'ECA',
                        'severity' => 'major',
                        'description' => 'Won all 7 rounds undefeated representing Sagarmatha House.',
                        'action_taken' => 'Trophy & School Assembly Recognition',
                        'points' => 8,
                        'event_date' => Carbon::now()->subWeeks(2)->toDateString(),
                    ],
                    [
                        'student_id' => $rohan->id,
                        'teacher_id' => $leadTeacher->id,
                        'grade_id' => $grade10?->id,
                        'title' => 'Class Disturbance in Science Practical',
                        'type' => 'negative',
                        'category' => 'Discipline',
                        'severity' => 'moderate',
                        'description' => 'Misused laboratory glassware and distracted team members during titrations.',
                        'action_taken' => 'Detention & Safety Refresher Briefing',
                        'points' => -5,
                        'event_date' => Carbon::now()->subDays(5)->toDateString(),
                    ],
                    [
                        'student_id' => $sneha->id,
                        'teacher_id' => $englishTeacher->id,
                        'grade_id' => $grade10?->id,
                        'title' => 'Literary Society Best Orator',
                        'type' => 'positive',
                        'category' => 'Leadership',
                        'severity' => 'major',
                        'description' => 'Delivered an outstanding extempore speech on sustainable development.',
                        'action_taken' => 'Certificate of Distinction',
                        'points' => 8,
                        'event_date' => Carbon::now()->subWeeks(3)->toDateString(),
                    ],
                    [
                        'student_id' => $bibek->id,
                        'teacher_id' => $csTeacher->id,
                        'grade_id' => $grade11?->id,
                        'title' => 'National Informatics Olympiad Finalist',
                        'type' => 'positive',
                        'category' => 'Academic',
                        'severity' => 'exceptional',
                        'description' => 'Qualified in top 10 nationwide in algorithmic coding contest.',
                        'action_taken' => 'School Honors Wall & Full ECA Grant',
                        'points' => 15,
                        'event_date' => Carbon::now()->subMonths(1)->toDateString(),
                    ],
                ];

                foreach ($behaviorData as $b) {
                    StudentBehavior::query()->updateOrCreate(
                        [
                            'student_id' => $b['student_id'],
                            'title' => $b['title'],
                        ],
                        $b
                    );
                }

                // Backward compatibility records for legacy behavior tables
                PositiveBehaviour::query()->updateOrCreate(
                    [
                        'student_id' => $modelStudent->id,
                        'grade_id' => $grade10?->id,
                        'report' => 'Assisted classmates during science practicals',
                    ],
                    [
                        'event_date' => Carbon::now()->subWeeks(1)->toDateString(),
                    ]
                );

                NegativeBehaviour::query()->updateOrCreate(
                    [
                        'student_id' => $modelStudent->id,
                        'grade_id' => $grade10?->id,
                        'report' => 'Late submission of math worksheet',
                    ],
                    [
                        'event_date' => Carbon::now()->subWeeks(2)->toDateString(),
                    ]
                );

                // 8. Extracurricular Activities & Student Participation
                $activitiesData = [
                    [
                        'name' => 'Annual Science Fair & Innovation Expo',
                        'category' => 'STEM',
                        'organizer' => 'School Science Society',
                        'address' => 'School Auditorium & Grounds',
                        'description' => 'Comprehensive exhibition featuring student robotics, automated hydroponics, and physics demonstrations.',
                        'start_date' => Carbon::now()->subMonths(1)->toDateString(),
                        'end_date' => Carbon::now()->subMonths(1)->addDays(2)->toDateString(),
                        'participants' => [
                            [
                                'student' => $modelStudent,
                                'rank' => 'First',
                                'role' => 'Team Lead & Presenter',
                                'cert' => true,
                            ],
                            [
                                'student' => $rohan,
                                'rank' => 'First',
                                'role' => 'Hardware Specialist',
                                'cert' => true,
                            ],
                            [
                                'student' => $sneha,
                                'rank' => 'Third',
                                'role' => 'Biotechnology Researcher',
                                'cert' => true,
                            ],
                        ],
                    ],
                    [
                        'name' => 'Inter-School Debate Championship',
                        'category' => 'Debate',
                        'organizer' => 'Literary Council',
                        'address' => 'Conference Hall A',
                        'description' => 'Parliamentary style debate competing against 12 leading regional high schools.',
                        'start_date' => Carbon::now()->subWeeks(3)->toDateString(),
                        'end_date' => Carbon::now()->subWeeks(3)->addDay()->toDateString(),
                        'participants' => [
                            [
                                'student' => $modelStudent,
                                'rank' => 'First',
                                'role' => 'First Proposition Speaker',
                                'cert' => true,
                            ],
                            [
                                'student' => $shristi,
                                'rank' => 'Runner Up',
                                'role' => 'Opposition Whip',
                                'cert' => true,
                            ],
                        ],
                    ],
                    [
                        'name' => 'Annual Track & Field Sports Meet',
                        'category' => 'Sports',
                        'organizer' => 'Department of Physical Education',
                        'address' => 'Central Sports Stadium',
                        'description' => 'Inter-house athletic meet including sprints, hurdles, high jump, and relay competitions.',
                        'start_date' => Carbon::now()->subMonths(2)->toDateString(),
                        'end_date' => Carbon::now()->subMonths(2)->addDays(3)->toDateString(),
                        'participants' => [
                            [
                                'student' => $ayush,
                                'rank' => 'First',
                                'role' => '100m Sprint Gold Medalist',
                                'cert' => true,
                            ],
                            [
                                'student' => $rohan,
                                'rank' => 'Second',
                                'role' => '4x100m Relay Anchor',
                                'cert' => true,
                            ],
                        ],
                    ],
                    [
                        'name' => 'Hackathon 2025: Code for Community',
                        'category' => 'STEM',
                        'organizer' => 'Computer Science Guild',
                        'address' => 'IT Innovation Hub',
                        'description' => '24-hour hackathon building accessible community welfare web and mobile applications.',
                        'start_date' => Carbon::now()->subWeeks(2)->toDateString(),
                        'end_date' => Carbon::now()->subWeeks(2)->addDay()->toDateString(),
                        'participants' => [
                            [
                                'student' => $bibek,
                                'rank' => 'Winner',
                                'role' => 'Lead Fullstack Developer',
                                'cert' => true,
                            ],
                            [
                                'student' => $shristi,
                                'rank' => 'Winner',
                                'role' => 'UI/UX & Product Designer',
                                'cert' => true,
                            ],
                        ],
                    ],
                ];

                foreach ($activitiesData as $actItem) {
                    $activity = Activities::query()->updateOrCreate(
                        [
                            'name' => $actItem['name'],
                            'start_date' => $actItem['start_date'],
                        ],
                        [
                            'category' => $actItem['category'],
                            'organizer' => $actItem['organizer'],
                            'address' => $actItem['address'],
                            'description' => $actItem['description'],
                            'end_date' => $actItem['end_date'],
                        ]
                    );

                    foreach ($actItem['participants'] as $p) {
                        StudentParticipation::query()->updateOrCreate(
                            [
                                'activity_id' => $activity->id,
                                'student_id' => $p['student']->id,
                            ],
                            [
                                'obtained_rank' => $p['rank'],
                                'role_or_position' => $p['role'],
                                'certificate_issued' => $p['cert'],
                            ]
                        );
                    }
                }

                // 9. Scholarships & Recipients
                $scholarshipsData = [
                    [
                        'name' => 'Merit Academic Excellence Scholarship',
                        'amount' => 2500,
                        'criteria' => 'Awarded to top-ranking students with GPA > 3.8 and exemplary disciplinary record.',
                        'year' => '2024',
                        'status' => 'active',
                        'batch_year_id' => $batch2425?->id,
                        'students' => [$modelStudent, $bibek],
                    ],
                    [
                        'name' => 'Community Welfare & Need-Based Grant',
                        'amount' => 1500,
                        'criteria' => 'Financial aid supporting promising students demonstrating academic diligence.',
                        'year' => '2024',
                        'status' => 'active',
                        'batch_year_id' => $batch2425?->id,
                        'students' => [$sneha, $ayush],
                    ],
                    [
                        'name' => 'Athletics & ECA Distinction Award',
                        'amount' => 1800,
                        'criteria' => 'Recognizing outstanding representation in regional/national sports and debates.',
                        'year' => '2024',
                        'status' => 'active',
                        'batch_year_id' => $batch2425?->id,
                        'students' => [$ayush, $shristi],
                    ],
                ];

                foreach ($scholarshipsData as $sch) {
                    $scholarship = Scholorship::query()->updateOrCreate(
                        ['name' => $sch['name']],
                        [
                            'amount' => $sch['amount'],
                            'criteria' => $sch['criteria'],
                            'year' => $sch['year'],
                            'status' => $sch['status'],
                            'batch_year_id' => $sch['batch_year_id'],
                        ]
                    );

                    foreach ($sch['students'] as $st) {
                        DB::table('scholorship_student')->updateOrInsert(
                            [
                                'scholorship_id' => $scholarship->id,
                                'student_id' => $st->id,
                            ],
                            [
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]
                        );
                    }
                }

                // 10. Mark Entries (Exams & Academic Term Results)
                $markEntries = [
                    // Aarav Sharma - Current Session (Grade 10, Batch 2024/25, Mid Term)
                    [
                        'student' => $modelStudent,
                        'grade' => $grade10,
                        'exam_type' => $midTerm,
                        'subject' => $math,
                        'batch' => $batch2425,
                        'teacher' => $leadTeacher,
                        'obtained' => 94.0,
                        'remarks' => 'Exceptional mastery of quadratic functions and trigonometry',
                    ],
                    [
                        'student' => $modelStudent,
                        'grade' => $grade10,
                        'exam_type' => $midTerm,
                        'subject' => $science,
                        'batch' => $batch2425,
                        'teacher' => $leadTeacher,
                        'obtained' => 96.5,
                        'remarks' => 'Highest practical score and pristine theory paper',
                    ],
                    [
                        'student' => $modelStudent,
                        'grade' => $grade10,
                        'exam_type' => $midTerm,
                        'subject' => $english,
                        'batch' => $batch2425,
                        'teacher' => $englishTeacher,
                        'obtained' => 90.0,
                        'remarks' => 'Excellent vocabulary, eloquent creative writing section',
                    ],
                    [
                        'student' => $modelStudent,
                        'grade' => $grade10,
                        'exam_type' => $midTerm,
                        'subject' => $social,
                        'batch' => $batch2425,
                        'teacher' => $socialTeacher,
                        'obtained' => 91.0,
                        'remarks' => 'Thorough analysis of constitution and governance systems',
                    ],
                    [
                        'student' => $modelStudent,
                        'grade' => $grade10,
                        'exam_type' => $midTerm,
                        'subject' => $cs,
                        'batch' => $batch2425,
                        'teacher' => $csTeacher,
                        'obtained' => 98.0,
                        'remarks' => 'Flawless database design and programming logic',
                    ],

                    // Aarav Sharma - Previous Session (Grade 9, Batch 2023/24, Final Term)
                    [
                        'student' => $modelStudent,
                        'grade' => $grade9,
                        'exam_type' => $finalTerm,
                        'subject' => $math,
                        'batch' => $batch2324,
                        'teacher' => $leadTeacher,
                        'obtained' => 88.5,
                        'remarks' => 'Solid foundation in algebraic proofs',
                    ],
                    [
                        'student' => $modelStudent,
                        'grade' => $grade9,
                        'exam_type' => $finalTerm,
                        'subject' => $science,
                        'batch' => $batch2324,
                        'teacher' => $leadTeacher,
                        'obtained' => 92.0,
                        'remarks' => 'Outstanding experimental comprehension',
                    ],

                    // Rohan Adhikari (Grade 10, Batch 2024/25, Mid Term)
                    [
                        'student' => $rohan,
                        'grade' => $grade10,
                        'exam_type' => $midTerm,
                        'subject' => $math,
                        'batch' => $batch2425,
                        'teacher' => $leadTeacher,
                        'obtained' => 85.0,
                        'remarks' => 'Good analytical problem-solving skills',
                    ],
                    [
                        'student' => $rohan,
                        'grade' => $grade10,
                        'exam_type' => $midTerm,
                        'subject' => $science,
                        'batch' => $batch2425,
                        'teacher' => $leadTeacher,
                        'obtained' => 87.5,
                        'remarks' => 'Very active in lab experiments',
                    ],
                    [
                        'student' => $rohan,
                        'grade' => $grade10,
                        'exam_type' => $midTerm,
                        'subject' => $english,
                        'batch' => $batch2425,
                        'teacher' => $englishTeacher,
                        'obtained' => 80.0,
                        'remarks' => 'Good grammar, recommend broader literature reading',
                    ],

                    // Sneha Pandey (Grade 10, Batch 2024/25, Mid Term)
                    [
                        'student' => $sneha,
                        'grade' => $grade10,
                        'exam_type' => $midTerm,
                        'subject' => $math,
                        'batch' => $batch2425,
                        'teacher' => $leadTeacher,
                        'obtained' => 91.0,
                        'remarks' => 'Consistent precision in theorem proofs',
                    ],
                    [
                        'student' => $sneha,
                        'grade' => $grade10,
                        'exam_type' => $midTerm,
                        'subject' => $english,
                        'batch' => $batch2425,
                        'teacher' => $englishTeacher,
                        'obtained' => 95.0,
                        'remarks' => 'Distinction quality critical literary interpretation',
                    ],

                    // Bibek Giri (Grade 11, Batch 2024/25, Mid Term)
                    [
                        'student' => $bibek,
                        'grade' => $grade11,
                        'exam_type' => $midTerm,
                        'subject' => $cs,
                        'batch' => $batch2425,
                        'teacher' => $csTeacher,
                        'obtained' => 99.0,
                        'remarks' => 'Exceptional code optimization and system architecture',
                    ],
                    [
                        'student' => $bibek,
                        'grade' => $grade11,
                        'exam_type' => $midTerm,
                        'subject' => $english,
                        'batch' => $batch2425,
                        'teacher' => $englishTeacher,
                        'obtained' => 87.0,
                        'remarks' => 'Articulate technical essay presentation',
                    ],

                    // Shristi Karki (Grade 11, Batch 2024/25, Mid Term)
                    [
                        'student' => $shristi,
                        'grade' => $grade11,
                        'exam_type' => $midTerm,
                        'subject' => $cs,
                        'batch' => $batch2425,
                        'teacher' => $csTeacher,
                        'obtained' => 93.0,
                        'remarks' => 'High quality UI programming and algorithmic design',
                    ],
                ];

                foreach ($markEntries as $m) {
                    MarkEntry::query()->updateOrCreate(
                        [
                            'student_id' => $m['student']->id,
                            'grade_id' => $m['grade']?->id,
                            'exam_type_id' => $m['exam_type']?->id,
                            'subject_id' => $m['subject']?->id,
                            'batch_year_id' => $m['batch']?->id,
                        ],
                        [
                            'teacher_id' => $m['teacher']->id,
                            'marks_obtained' => $m['obtained'],
                            'full_marks' => $m['subject']->full_marks ?? 100,
                            'pass_marks' => $m['subject']->pass_marks ?? 40,
                            'remarks' => $m['remarks'],
                        ]
                    );
                }
            });
        });
    }
}
