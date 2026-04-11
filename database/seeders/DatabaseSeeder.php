<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\College;
use App\Models\Professor;
use App\Models\Subject;
use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // First, ensure colleges exist
        $this->call(CollegeSeeder::class);

        $colleges = College::all();

        // Ensure subjects exist
        $subjectsData = [
            ['name' => 'Advanced Calculus', 'course' => 'Mathematics', 'semester' => 1],
            ['name' => 'Data Structures', 'course' => 'Computer Science', 'semester' => 3],
            ['name' => 'Thermodynamics', 'course' => 'Mechanical Engineering', 'semester' => 2],
            ['name' => 'Criminal Law', 'course' => 'Legal Studies', 'semester' => 4],
        ];

        foreach($subjectsData as $s) {
            Subject::updateOrCreate(['name' => $s['name']], $s);
        }

        $allSubjects = Subject::all();

        // Seed data for each college
        foreach($colleges as $college) {
            // 1. Create a Professor if none exists
            Professor::updateOrCreate(
                ['name' => 'Prof. ' . $college->name . ' Expert', 'college_id' => $college->id],
                ['department' => 'Science & Tech']
            );

            // 2. Create a demo user for this college
            $userEmail = strtolower(str_replace(' ', '', $college->name)) . '@mcv.com';
            $user = User::updateOrCreate(
                ['email' => $userEmail],
                [
                    'name' => 'Top Student (' . $college->name . ')',
                    'password' => Hash::make('password'),
                    'college_id' => $college->id,
                    'role' => 'student',
                    'year' => 'Final Year',
                    'mobile' => '9876543210',
                    'college_email' => 'student.' . $userEmail
                ]
            );

            // 3. Create Note if none exists
            foreach($allSubjects->take(3) as $sub) {
                Note::updateOrCreate(
                    ['title' => $sub->name . ' Material', 'college_id' => $college->id, 'subject_id' => $sub->id],
                    [
                        'file_path' => 'notes/demo.pdf',
                        'user_id' => $user->id,
                    ]
                );
            }
        }
    }
}
