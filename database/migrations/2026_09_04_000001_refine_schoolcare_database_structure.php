<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Batch Years: add is_active
        Schema::table('batch_years', function (Blueprint $table) {
            $table->boolean('is_active')->default(false)->after('batch');
        });

        // 2. Class Mappings: add batch_year_id and roll_no
        Schema::table('class_mappings', function (Blueprint $table) {
            $table->foreignId('batch_year_id')->nullable()->after('section_id')->constrained('batch_years')->nullOnDelete();
            $table->string('roll_no', 30)->nullable()->after('batch_year_id');
        });

        // 3. Subjects: add code, full_marks, pass_marks
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('code', 30)->nullable()->after('subject');
            $table->float('full_marks')->default(100)->after('code');
            $table->float('pass_marks')->default(40)->after('full_marks');
        });

        // 4. Attendances: add section_id
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('section_id')->nullable()->after('grade_id')->constrained('sections')->nullOnDelete();
        });

        // 5. Attendance Student pivot: add status and remarks
        Schema::table('attendance_student', function (Blueprint $table) {
            $table->string('status', 20)->default('present')->after('student_id'); // present, absent, late, excused
            $table->string('remarks')->nullable()->after('status');
        });

        // 6. Mark Entries: add full_marks, pass_marks
        Schema::table('mark_entries', function (Blueprint $table) {
            $table->float('full_marks')->default(100)->after('marks_obtained');
            $table->float('pass_marks')->default(40)->after('full_marks');
        });

        // 7. Assignments: add description, max_marks
        Schema::table('assignments', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->float('max_marks')->default(100)->after('description');
        });

        // 8. Assignment Student pivot: add status, submission details, marks
        Schema::table('assignment_student', function (Blueprint $table) {
            $table->string('status', 20)->default('assigned')->after('student_id'); // assigned, submitted, late, graded
            $table->dateTime('submitted_at')->nullable()->after('status');
            $table->float('marks_obtained')->nullable()->after('submitted_at');
            $table->text('feedback')->nullable()->after('marks_obtained');
        });

        // 9. Unified Student Behaviors (Merits & Demerits)
        Schema::create('student_behaviors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->foreignId('grade_id')->nullable()->constrained('grades')->nullOnDelete();
            $table->enum('type', ['positive', 'negative'])->default('positive');
            $table->string('category', 60)->nullable(); // Academic, Leadership, Discipline, Punctuality, Conduct, ECA
            $table->string('severity', 30)->default('minor'); // minor, moderate, major, exceptional
            $table->string('title')->nullable();
            $table->text('description');
            $table->string('action_taken')->nullable(); // Commendation, Parent Meeting, Detention, Counseling, Warning
            $table->integer('points')->default(0); // e.g. +5 or -5
            $table->date('event_date');
            $table->timestamps();
        });

        // 10. Activities: add category and description
        Schema::table('activities', function (Blueprint $table) {
            $table->string('category', 50)->nullable()->after('name'); // Sports, Arts, STEM, Debate, Cultural, Other
            $table->text('description')->nullable()->after('address');
        });

        // 11. Student Participation: add role and certificate flag
        Schema::table('student_participations', function (Blueprint $table) {
            $table->string('role_or_position', 60)->nullable()->after('obtained_rank');
            $table->boolean('certificate_issued')->default(false)->after('role_or_position');
        });

        // 12. Scholarships: add status and batch_year_id
        Schema::table('scholorships', function (Blueprint $table) {
            $table->string('status', 20)->default('active')->after('criteria');
            $table->foreignId('batch_year_id')->nullable()->after('status')->constrained('batch_years')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_behaviors');

        Schema::table('scholorships', function (Blueprint $table) {
            $table->dropConstrainedForeignId('batch_year_id');
            $table->dropColumn('status');
        });

        Schema::table('student_participations', function (Blueprint $table) {
            $table->dropColumn(['role_or_position', 'certificate_issued']);
        });

        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['category', 'description']);
        });

        Schema::table('assignment_student', function (Blueprint $table) {
            $table->dropColumn(['status', 'submitted_at', 'marks_obtained', 'feedback']);
        });

        Schema::table('assignments', function (Blueprint $table) {
            $table->dropColumn(['description', 'max_marks']);
        });

        Schema::table('mark_entries', function (Blueprint $table) {
            $table->dropColumn(['full_marks', 'pass_marks']);
        });

        Schema::table('attendance_student', function (Blueprint $table) {
            $table->dropColumn(['status', 'remarks']);
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->dropConstrainedForeignId('section_id');
        });

        Schema::table('subjects', function (Blueprint $table) {
            $table->dropColumn(['code', 'full_marks', 'pass_marks']);
        });

        Schema::table('class_mappings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('batch_year_id');
            $table->dropColumn('roll_no');
        });

        Schema::table('batch_years', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
