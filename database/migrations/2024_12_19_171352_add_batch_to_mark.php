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
        Schema::table('mark_entries', function (Blueprint $table) {
            $table->foreignId('batch_year_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mark_entries', function (Blueprint $table) {
            $table->dropForeign(['batch_year_id']);
            $table->dropColumn('batch_year_id');
        });
    }
};
