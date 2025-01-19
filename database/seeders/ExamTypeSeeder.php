<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Municipalities
        $examtypes = [
            'First Terminal Examination',
            'Second Terminal Examination',
            'Third Terminal Examination',
            'Final Examination',
        ];

        // Insert Municipalities
        foreach ($examtypes as $examtype) {
            DB::table('exam_types')->insert([
                'exam_type' => $examtype,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
