<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class InitialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Municipalities
        $municipalities = [
            'Bharatpur Metropolitan City',
            'Kalika Municipality',
            'Khairahani Municipality',
            'Madi Municipality',
            'Ratnanagar Municipality',
            'Rapti Municipality',
            'Ichchhakamana Rural Municipality',
        ];

        // Grades
        $grades = range(1, 10);

        // Sections
        $sections = ['A', 'B', 'C'];

        // Wards
        $wards = range(1, 29);

        // Subjects
        $subjects = [
            'English',
            'Nepali',
            'Science',
            'Maths',
            'Computer',
            'Social_studies',
            'Moral_education',
            'हाम्रो सेरोफेरो',
            'Account',
            'Optional_math',
            'Economics',
            'EPH',
        ];

        // Insert Municipalities
        foreach ($municipalities as $municipality) {
            DB::table('municipalities')->insert([
                'municipality' => $municipality,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert Grades
        foreach ($grades as $grade) {
            DB::table('grades')->insert([
                'grade' => $grade,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert Sections
        foreach ($sections as $section) {
            DB::table('sections')->insert([
                'section' => $section,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert Wards
        foreach ($wards as $ward) {
            DB::table('wards')->insert([
                'ward' => $ward,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Insert Subjects
        foreach ($subjects as $subject) {
            DB::table('subjects')->insert([
                'subject' => $subject,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
