<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;
use App\Models\Course;

class EngineeringSubjectSeeder extends Seeder
{
    public function run()
    {
        // Ensure Engineering course exists
        $course = Course::firstOrCreate(['name' => 'Engineering']);

        $subjects = [
            // Year 1: Semester 1
            ['name' => 'Mathematics-I (Calculus & Linear Algebra)', 'semester' => 1],
            ['name' => 'Engineering Physics', 'semester' => 1],
            ['name' => 'Engineering Chemistry', 'semester' => 1],
            ['name' => 'Basic Electrical & Electronics Engineering', 'semester' => 1],
            ['name' => 'Introduction to Problem Solving (C/Python)', 'semester' => 1],
            ['name' => 'Communication Skills', 'semester' => 1],
            ['name' => 'Workshop Technology', 'semester' => 1],

            // Year 1: Semester 2
            ['name' => 'Mathematics-II (Differential Equations & Transforms)', 'semester' => 2],
            ['name' => 'Object-Oriented Programming (C++)', 'semester' => 2],
            ['name' => 'Engineering Graphics / CAD', 'semester' => 2],
            ['name' => 'Basic Mechanical Engineering', 'semester' => 2],
            ['name' => 'Environmental Sciences', 'semester' => 2],

            // Year 2: Semester 3
            ['name' => 'Data Structures & Algorithms (DSA)', 'semester' => 3],
            ['name' => 'Discrete Mathematics', 'semester' => 3],
            ['name' => 'Digital Electronics & Logic Design', 'semester' => 3],
            ['name' => 'Computer Organization & Architecture (COA)', 'semester' => 3],
            ['name' => 'Python Programming (Advanced)', 'semester' => 3],
            ['name' => 'Soft Skills-I', 'semester' => 3],
            ['name' => 'Signals and Systems', 'semester' => 3],

            // Year 2: Semester 4
            ['name' => 'Operating Systems (OS)', 'semester' => 4],
            ['name' => 'Database Management Systems (DBMS)', 'semester' => 4],
            ['name' => 'Theory of Computation (TOC)', 'semester' => 4],
            ['name' => 'Microprocessors & Interfacing', 'semester' => 4],
            ['name' => 'Numerical Methods & Optimization', 'semester' => 4],
            ['name' => 'Soft Skills-II', 'semester' => 4],

            // Year 3: Semester 5
            ['name' => 'Computer Networks (CN)', 'semester' => 5],
            ['name' => 'Design and Analysis of Algorithms (DAA)', 'semester' => 5],
            ['name' => 'Software Engineering', 'semester' => 5],
            ['name' => 'Java Programming (Advanced)', 'semester' => 5],
            ['name' => 'AI / Cyber Security / Cloud (Elective I)', 'semester' => 5],
            ['name' => 'Aptitude & Technical Training (TPP)', 'semester' => 5],

            // Year 3: Semester 6
            ['name' => 'Artificial Intelligence', 'semester' => 6],
            ['name' => 'Compiler Design', 'semester' => 6],
            ['name' => 'Data Warehousing & Data Mining', 'semester' => 6],
            ['name' => 'Machine Learning (Basics)', 'semester' => 6],
            ['name' => 'Full Stack Development (MERN/MEAN)', 'semester' => 6],

            // Year 4: Semester 7
            ['name' => 'Cloud Computing', 'semester' => 7],
            ['name' => 'Information Security / Cryptography', 'semester' => 7],
            ['name' => 'Industrial Training / Internship', 'semester' => 7],

            // Year 4: Semester 8
            ['name' => 'Professional Ethics & Human Values', 'semester' => 8],
        ];

        foreach ($subjects as $s) {
            Subject::updateOrCreate(
                ['name' => $s['name'], 'course_id' => $course->id],
                ['semester' => $s['semester']]
            );
        }
    }
}
