<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\College;
use Illuminate\Support\Str;

class CollegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colleges = [
            [
                'name' => 'VIT Vellore',
                'slug' => 'vellore-institute-of-technology',
                'location' => 'Vellore, Tamil Nadu',
                'description' => "Vellore Institute of Technology (VIT) is a premier private research university. It is known for its world-class infrastructure and a massive student community from across the globe.",
                'campusimg' => 'https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=1000',
                'student_count' => 35000,
                'rating' => 4.6,
                'tags' => ['Engineering Excellence', 'Vibrant Campus', 'Research'],
            ],
            [
                'name' => 'IIT Bombay',
                'slug' => 'indian-institute-of-technology-bombay',
                'location' => 'Powai, Mumbai',
                'description' => "The Indian Institute of Technology Bombay is the most sought-after engineering college in India, known for its rigorous academics and the iconic Mood Indigo festival.",
                'campusimg' => 'https://images.unsplash.com/photo-1592280771190-3e2e4d571952?q=80&w=1000',
                'student_count' => 12000,
                'rating' => 4.9,
                'tags' => ['Premier Research', 'Tech Innovation', 'Elite'],
            ],
            [
                'name' => 'IIT Delhi',
                'slug' => 'indian-institute-of-technology-delhi',
                'location' => 'Hauz Khas, New Delhi',
                'description' => "IIT Delhi is a leading public research university and a major hub for scientific and technological education in the heart of India's capital.",
                'campusimg' => 'https://images.unsplash.com/photo-1627555011174-8744b5894298?q=80&w=1000',
                'student_count' => 10000,
                'rating' => 4.8,
                'tags' => ['Research', 'Computing', 'Sustainability'],
            ],
            [
                'name' => 'Stanford University',
                'slug' => 'stanford-university',
                'location' => 'Palo Alto, California',
                'description' => "Stanford University is a private research university in Stanford, California. It is one of the world's leading teaching and research institutions.",
                'campusimg' => 'https://images.unsplash.com/photo-1532649538693-f3a2ec1bf8bc?q=80&w=1000',
                'student_count' => 17000,
                'rating' => 4.9,
                'tags' => ['Innovation', 'Silicon Valley', 'Global Rank'],
            ],
            [
                'name' => 'MIT',
                'slug' => 'massachusetts-institute-of-technology',
                'location' => 'Cambridge, MA',
                'description' => "The Massachusetts Institute of Technology (MIT) is the world's leading tech hub, specializing in engineering, AI, and physical sciences.",
                'campusimg' => 'https://images.unsplash.com/photo-1560523160-c3247963ecf0?q=80&w=1000',
                'student_count' => 11000,
                'rating' => 5.0,
                'tags' => ['Tech Lead', 'Robotics', 'SpaceX Partner'],
            ],
            [
                'name' => 'University of Oxford',
                'slug' => 'university-of-oxford',
                'location' => 'Oxford, UK',
                'description' => "Oxford is the oldest university in the English-speaking world and a unique and historic institution.",
                'campusimg' => 'https://images.unsplash.com/photo-1590494165264-1ebe3602eb80?q=80&w=1000',
                'student_count' => 24000,
                'rating' => 4.8,
                'tags' => ['Heritage', 'Policy', 'Literature'],
            ],
        ];

        foreach ($colleges as $college) {
            College::updateOrCreate(['name' => $college['name']], $college);
        }
    }
}
