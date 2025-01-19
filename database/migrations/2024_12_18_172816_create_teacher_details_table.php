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
        Schema::create('teacher_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained();
            $table->string('phone', 15)->nullable();
            $table->string('address')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->foreignId('municipality_id')->constrained()->nullable();
            $table->foreignId('ward_id')->constrained()->nullable();
            $table->foreignId('subject_id')->constrained()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_details');
    }
};
