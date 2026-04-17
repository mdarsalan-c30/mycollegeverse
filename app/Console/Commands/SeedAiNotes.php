<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subject;
use App\Models\Note;
use App\Models\User;
use App\Models\College;
use App\Services\AiNoteService;
use Illuminate\Support\Str;

class SeedAiNotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notes:ai-seed {--subject_id= : Seed a specific subject} {--force : Re-generate existing AI notes}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Generate and seed AI-powered high-yield exam notes using Gemini API';

    protected $aiService;

    public function __construct(AiNoteService $aiService)
    {
        parent::__construct();
        $this->aiService = $aiService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting MCV AI Scholar Seeding Engine...');

        // 1. Ensure the MCV AI Scholar identity exists
        $aiUser = User::updateOrCreate(
            ['email' => 'ai.scholar@mycollegeverse.com'],
            [
                'name' => 'MCV AI Scholar',
                'password' => bcrypt(Str::random(16)),
                'role' => 'admin',
                'username' => 'mcv_ai_scholar',
                'college_id' => 1, // Assume Global Hub or MCV HQ
            ]
        );

        $subjects = Subject::query();
        if ($this->option('subject_id')) {
            $subjects->where('id', $this->option('subject_id'));
        }

        $allSubjects = $subjects->get();
        $bar = $this->output->createProgressBar($allSubjects->count());
        $bar->start();

        $nightmareSubjects = [
            'Theory of Computation',
            'TOC',
            'Compiler Design',
            'Signals and Systems',
            'Electromagnetic Field Theory',
            'Data Structures & Algorithms',
            'DSA'
        ];

        foreach ($allSubjects as $subject) {
            $exists = Note::where('subject_id', $subject->id)
                         ->where('note_type', 'ai')
                         ->exists();

            if ($exists && !$this->option('force')) {
                $bar->advance();
                continue;
            }

            $isNightmare = false;
            foreach ($nightmareSubjects as $ns) {
                if (stripos($subject->name, $ns) !== false) {
                    $isNightmare = true;
                    break;
                }
            }

            $this->comment("\nGenerating for: {$subject->name}...");
            
            $content = $this->aiService->generateNotes($subject->name, $subject->semester, $isNightmare);

            if ($content) {
                Note::updateOrCreate(
                    ['subject_id' => $subject->id, 'note_type' => 'ai'],
                    [
                        'title' => "Exam Ready: {$subject->name}",
                        'ai_content' => $content,
                        'user_id' => $aiUser->id,
                        'college_id' => 1,
                        'is_verified' => true,
                        'file_path' => 'ai_generated', // Placeholder since it's not a real file
                    ]
                );
            }

            $bar->advance();
            // Sleep briefly to avoid API rate limits if necessary
            usleep(500000); 
        }

        $bar->finish();
        $this->info("\n✅ Mission Accomplished! The Academic Verse is now smarter.");
    }
}
