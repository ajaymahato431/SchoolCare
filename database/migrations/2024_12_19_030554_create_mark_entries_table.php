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
        Schema::create('mark_entries', function (Blueprint $table) {
            $table->id();
            // Foreign keys
            $table->foreignId('student_id')->constrained();
            $table->foreignId('grade_id')->constrained();
            $table->foreignId('exam_type_id')->constrained();

            // Mark Details
            $table->float('marks_obtained')->nullable();
            $table->string('remarks')->nullable(); // Optional remarks field

            // Audit Trail
            $table->foreignId('teacher_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mark_entries');
    }
};
