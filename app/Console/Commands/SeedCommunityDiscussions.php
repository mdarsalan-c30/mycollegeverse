<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\User;
use App\Models\College;
use Illuminate\Support\Str;

class SeedCommunityDiscussions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multiverse:seed-community';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the community with high-intent SEO discussions based on 2025-2026 trends';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🌌 Initializing Multiverse Community Seed...');

        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $this->error('Admin user not found. Please create an admin account first.');
            return 1;
        }

        $colleges = College::pluck('id')->toArray();
        if (empty($colleges)) {
            $this->error('No colleges found in the registry. Seeding aborted.');
            return 1;
        }

        $discussions = [
            // CSE & AI CLUSTERS
            [
                'title' => 'Is B.Tech CSE still worth it in 2026? AI replacing coding jobs?',
                'content' => "With tools like Cursor, Devin, and GPT-5 coming up, many students are scared. Is a traditional CSE degree still the best path, or should we focus exclusively on AI and prompt engineering? Let's discuss placements and future scope.",
                'category' => 'Career',
            ],
            [
                'title' => 'CSE vs AIML vs Data Science: Which has the better salary in 2026?',
                'content' => "I am confused between these three branches. Older seniors say stick to core CSE, but new specializations are trending. Which one is getting recruited more currently in Tier-1 and Tier-2 colleges?",
                'category' => 'Career',
            ],
            [
                'title' => 'DSA vs Development: What should I focus on in 1st and 2nd year?',
                'content' => "Everyone says 'do DSA for FANG', but startups want development projects. Is it possible to balance both? What is your daily schedule for 2025?",
                'category' => 'Doubt',
            ],
            
            // CIVIL & CORE CLUSTERS
            [
                'title' => 'Is Civil Engineering dead in India? Future with Smart Cities & AI.',
                'content' => "Core branches like Civil and Mechanical have seen low placements recently. But with the Smart Cities mission and AI in construction safety, is there a revival coming? Should I learn Revit or Python side-by-side?",
                'category' => 'General',
            ],
            
            // MBBS & MEDICAL
            [
                'title' => 'MBBS ROI Reality Check: How many years to actually start earning?',
                'content' => "Everyone sees the 'Doctor' title, but nobody talks about the 10+ years of study + PG + residency. Is the financial ROI still there compared to an MBA or Tech career in 2026?",
                'category' => 'Career',
            ],
            [
                'title' => 'Can MBBS doctors switch to AI / Health-Tech?',
                'content' => "I am a 3rd-year med student interested in technology. Are there genuine career paths where I can use my medical knowledge in the AI/Software space? Looking for advice on biomedical engineering or startups.",
                'category' => 'Career',
            ],

            // COMMERCE & BCOM
            [
                'title' => 'B.Com vs BBA vs CA: Best path for high-paying finance jobs.',
                'content' => "Commerce students often get stuck in basic accounting roles. What are the modern skills (like FinTech or Data Analytics) that B.Com students must learn to compete with MBA grads in 2026?",
                'category' => 'General',
            ],

            // CROSS-STREAM / VIRAL
            [
                'title' => 'College Degree vs Skills: What matters more in the next 5 years?',
                'content' => "Elon Musk says degrees don't matter, but Indian HRs still ask for them. Is college just a waste of time for people who can learn everything online, or is the networking worth it?",
                'category' => 'General',
            ],
            [
                'title' => 'How to earn money in college? Practical side hustles for students.',
                'content' => "I want to be financially independent. Freelancing, content creation, or tutoring? What is working for you right now in 2025?",
                'category' => 'Career',
            ],
            [
                'title' => 'I wasted 3 years in college and have zero skills. What now?',
                'content' => "Honestly, I just gamed and hung out. Now I'm in my final year. Is it too late to restart? Give me a 6-month roadmap to get an entry-level job.",
                'category' => 'Doubt',
            ],
        ];

        $count = 0;
        foreach ($discussions as $disc) {
            // Check if post already exists to avoid duplicates if run multiple times
            if (!Post::where('title', $disc['title'])->exists()) {
                Post::create([
                    'user_id' => $admin->id,
                    'college_id' => $colleges[array_rand($colleges)],
                    'title' => $disc['title'],
                    'content' => $disc['content'],
                    'category' => $disc['category'],
                    'is_pinned' => rand(0, 10) > 8 ? true : false, // Randomly pin some
                ]);
                $count++;
            }
        }

        $this->info("🚀 Manifestation Complete! {$count} Goldmine Discussions launched into the Verse.");
        return 0;
    }
}
