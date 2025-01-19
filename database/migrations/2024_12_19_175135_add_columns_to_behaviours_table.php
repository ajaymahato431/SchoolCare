<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('positive_behaviours', function (Blueprint $table) {
            $table->date('event_date')->nullable()->after('id'); // Adjust 'after' as needed
        });

        Schema::table('negative_behaviours', function (Blueprint $table) {
            $table->date('event_date')->nullable()->after('id'); // Adjust 'after' as needed
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('positive_behaviours', function (Blueprint $table) {
            $table->dropColumn('event_date');
        });

        Schema::table('negative_behaviours', function (Blueprint $table) {
            $table->dropColumn('event_date');
        });
    }
};
