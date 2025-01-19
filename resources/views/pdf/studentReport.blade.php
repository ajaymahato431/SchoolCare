<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Report of {{ $student->name }}</title>
    <style>
        /* General styles for A4 layout */
        @page {
            size: A4;
            margin: 20mm;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .cv-container {
            width: 210mm;
            height: 297mm;
            margin: auto;
            background: white;
            padding: 20mm;
            box-sizing: border-box;
            border: 1px solid #ddd;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        .section {
            margin-bottom: 20px;
        }

        .section h2 {
            font-size: 18px;
            color: #444;
            border-bottom: 2px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .section-content {
            font-size: 14px;
            line-height: 1.6;
            color: #555;
        }

        .skills ul {
            list-style-type: none;
            padding: 0;
        }

        .skills ul li {
            background: #f0f0f0;
            margin: 5px 0;
            padding: 5px 10px;
            display: inline-block;
            border-radius: 3px;
        }

        .contact {
            text-align: center;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="cv-container">
        <div class="header">
            <h1>Student Report</h1>
            <p>Name: {{ $student->name ?? 'N/A' }}</p>
            <p>Grade: {{ $student->classMappings->sortByDesc('created_at')->first()->grades->grade ?? 'N/A' }}</p>
        </div>

        <div class="section">
            <h2>Mark Obtained in Exams</h2>
            <div class="section-content">
                @php
                    // Group mark entries by grade_id
                    $groupedMarks = $student->markEntries->groupBy('grades.grade');
                @endphp

                @forelse ($groupedMarks as $gradeId => $entries)
                    <h3>Grade: {{ $gradeId }}</h3>
                    <ul>
                        @foreach ($entries as $entry)
                            <li>Subject: {{ $entry->subjects->subject ?? 'N/A' }} - Marks: {{ $entry->marks_obtained }}
                            </li>
                        @endforeach
                    </ul>
                @empty
                    <p>No mark entries available.</p>
                @endforelse
            </div>
        </div>


        <div class="section">
            <h2>Attendance</h2>
            <div class="section-content">
                @php
                    // Group attendances by grade_id
                    $groupedAttendances = $student->attendences->groupBy('grade_id');
                @endphp

                @forelse ($groupedAttendances as $gradeId => $attendances)
                    <h3>Grade: {{ $gradeId }}</h3>
                    <p>Total Attendance: {{ $attendances->count() }}</p>

                @empty
                    <p>No attendance records available.</p>
                @endforelse
            </div>
        </div>


        <div class="section">
            <h2>Assignments Done</h2>
            <div class="section-content">
                @php
                    // Group assignments by grade_id
                    $groupedAssignments = $student->assignments->groupBy('grade_id');
                @endphp

                @forelse ($groupedAssignments as $gradeId => $assignments)
                    <h3>Grade: {{ $gradeId }}</h3>
                    <p>Total Assignments: {{ $assignments->count() }}</p>
                @empty
                    <p>No assignments available.</p>
                @endforelse
            </div>
        </div>


        <div class="section">
            <h2>Scholarships</h2>
            <div class="section-content">
                <ul>
                    @forelse ($student->scholorships as $scholarship)
                        <li>{{ $scholarship->name }} - Awarded On: {{ $scholarship->year }}</li>
                    @empty
                        <li>No scholarships awarded.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="section">
            <h2>Positive Behaviours</h2>
            <div class="section-content">
                <ul>
                    @forelse ($student->positiveBehaviours as $behaviour)
                        <li>{{ $behaviour->report }} - Date: {{ $behaviour->event_date }}</li>
                    @empty
                        <li>No positive behaviours recorded.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="section">
            <h2>Negative Behaviours</h2>
            <div class="section-content">
                <ul>
                    @forelse ($student->negativeBehaviours as $behaviour)
                        <li>{{ $behaviour->report }} - Date: {{ $behaviour->event_date }}</li>
                    @empty
                        <li>No negative behaviours recorded.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="section">
            <h2>Participation</h2>
            <div class="section-content">
                <ul>
                    @forelse ($student->participations as $participation)
                        <li>{{ $participation->activities->name }} - Date: {{ $participation->date }}</li>
                    @empty
                        <li>No participations recorded.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="footer">
            <p>Report generated by Learners Log</p>
        </div>
    </div>
</body>


</html>
