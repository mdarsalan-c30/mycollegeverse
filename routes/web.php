<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [\App\Http\Controllers\LandingController::class, 'index']);

// Public Knowledge Routes (SEO Friendly)
Route::get('/notes', [App\Http\Controllers\NoteController::class, 'index'])->name('notes.index');
Route::get('/notes/generate', [App\Http\Controllers\NoteController::class, 'generateForm'])->name('notes.generate')->middleware('auth');
Route::post('/notes/generate', [App\Http\Controllers\NoteController::class, 'generate'])->name('notes.generate.store')->middleware('auth');
Route::get('/notes/{slug}', [App\Http\Controllers\NoteController::class, 'show'])->name('notes.show');

// Editorial Discovery 🚀
Route::get('/blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blogs.index');
Route::get('/blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blogs.show');

// Master Browser Nexus (For Hostinger/Terminal-less Sync)

Route::get('/multiverse-academic-sync', function() {
    try {
        // 🧪 Database Health Audit: Auto-Heal broken PoW tables
        if (\Illuminate\Support\Facades\Schema::hasTable('projects')) {
            if (!\Illuminate\Support\Facades\Schema::hasColumn('projects', 'cover_image_url')) {
                \Illuminate\Support\Facades\Schema::dropIfExists('project_endorsements');
                \Illuminate\Support\Facades\Schema::dropIfExists('projects');
                // Force reset migration record so it runs again
                \Illuminate\Support\Facades\DB::table('migrations')->where('migration', 'like', '%create_project_tables%')->delete();
            }
        }

        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return "🛡️ Academic targeting, Signal Hub & Talent Gallery manifested! Database synchronized with the PoW Protocol. Visit <a href='/dashboard'>Dashboard</a>.";
    } catch (\Exception $e) {
        return "Sync Error: " . $e->getMessage();
    }
});

// Signal Protocol: Core Notification Endpoints
Route::middleware('auth')->group(function() {
    Route::get('/api/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/api/notifications/{id}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/api/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
});

Route::get('/multiverse-note-slug-sync', function() {
    try {
        $notes = \App\Models\Note::whereNull('slug')->get();
        $count = 0;
        foreach($notes as $n) {
            $n->update([
                'slug' => \Illuminate\Support\Str::slug($n->title) . '-' . \Illuminate\Support\Str::random(6)
            ]);
            $count++;
        }
        return "🌌 Note identity mapped! Generated $count note slugs. Visit <a href='/notes'>Notes</a>.";
    } catch (\Exception $e) {
        return "Sync Error: " . $e->getMessage();
    }
});

Route::get('/community', [App\Http\Controllers\CommunityController::class, 'index'])->name('community.index');
Route::get('/community/{user:username}/{post:slug}', [App\Http\Controllers\CommunityController::class, 'show'])->name('community.show');

Route::get('/multiverse-post-slug-sync', function() {
    try {
        $posts = \App\Models\Post::whereNull('slug')->get();
        $count = 0;
        foreach($posts as $p) {
            $p->update([
                'slug' => \Illuminate\Support\Str::slug($p->title) . '-' . \Illuminate\Support\Str::random(6)
            ]);
            $count++;
        }
        return "🌌 Community identity mapped! Generated $count post slugs.";
    } catch (\Exception $e) {
        return "Sync Error: " . $e->getMessage();
    }
});

Route::get('/jobs', [App\Http\Controllers\JobBoardController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [App\Http\Controllers\JobBoardController::class, 'show'])->name('jobs.show');

Route::get('/colleges', [App\Http\Controllers\CollegeController::class, 'index'])->name('colleges.index');
Route::get('/colleges/{college:slug}', [App\Http\Controllers\CollegeController::class, 'show'])->name('colleges.show');

Route::get('/leaderboard', [App\Http\Controllers\LeaderboardController::class, 'index'])->name('leaderboard.index');

// Multiverse SEO Pages (Privacy, Terms, About, etc.)
Route::get('/p/{slug}', [App\Http\Controllers\PageController::class, 'show'])->name('pages.show');

// Startup Identity Nodes 🚀
Route::get('/careers', [App\Http\Controllers\PageController::class, 'show'])->defaults('slug', 'careers')->name('pages.careers');
Route::get('/partner', [App\Http\Controllers\PageController::class, 'show'])->defaults('slug', 'partner')->name('pages.partner');
Route::get('/faq', [App\Http\Controllers\PageController::class, 'show'])->defaults('slug', 'faq')->name('pages.faq');

// Interaction Routes (Auth Required)
Route::middleware(['auth'])->group(function () {
    // Shared Routes (Dynamic Layouts)
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', function(\Illuminate\Http\Request $request) {
        if ($request->career_role) {
            \Illuminate\Support\Facades\Auth::user()->update(['career_role' => $request->career_role]);
        }
        return redirect()->route('dashboard')->with('success', 'Career goal updated!');
    });

    // 🥁 Academic Pulse: Personalized Deadline Stream 🛡️
    Route::prefix('academic-pulse')->name('academic-pulse.')->group(function () {
        Route::post('/manifest', [App\Http\Controllers\AcademicEventController::class, 'store'])->name('store');
        Route::post('/scan-notice', [App\Http\Controllers\AcademicEventController::class, 'scan'])->name('scan');
        Route::post('/verify/{event}', [App\Http\Controllers\AcademicEventController::class, 'verify'])->name('verify');
    });
    Route::get('/chat/{user?}', [App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/fetch/{user}', [App\Http\Controllers\ChatController::class, 'fetch'])->name('chat.fetch');
    Route::post('/chat/send', [App\Http\Controllers\ChatController::class, 'send'])->name('chat.send');
    Route::delete('/chat/message/{id}', [App\Http\Controllers\ChatController::class, 'deleteMessage'])->name('chat.delete');

    Route::get('/profile/{user?}', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/update-photo', [App\Http\Controllers\ProfileController::class, 'updatePhoto'])->name('profile.update-photo');

    // Student Only Routes
    Route::middleware(['role:student'])->group(function () {
        Route::post('/notes', [App\Http\Controllers\NoteController::class, 'store'])->name('notes.store');
        Route::post('/notes/{note}/review', [App\Http\Controllers\NoteController::class, 'addReview'])->name('notes.review');
        Route::get('/notes/{slug}/download', [App\Http\Controllers\NoteController::class, 'download'])->name('notes.download');
        Route::get('/notes/{slug}/print', [App\Http\Controllers\NoteController::class, 'print'])->name('notes.print');
        Route::post('/notes/{note}/save', [App\Http\Controllers\NoteController::class, 'toggleSave'])->name('notes.save');
        Route::post('/community/store', [App\Http\Controllers\CommunityController::class, 'store'])->name('community.store');
        Route::post('/community/comment', [App\Http\Controllers\CommunityController::class, 'comment'])->name('community.comment');
        Route::post('/community/vote/{postId}', [App\Http\Controllers\CommunityController::class, 'vote'])->name('community.vote');
        Route::get('/professors', [App\Http\Controllers\ProfessorController::class, 'index'])->name('professors.index');
        Route::get('/professors/{professor:slug}', [App\Http\Controllers\ProfessorController::class, 'show'])->name('professors.show');
        Route::post('/professors/request', [App\Http\Controllers\ProfessorController::class, 'requestProfessor'])->name('professors.request');
        Route::post('/professors/{professor:slug}/rate', [App\Http\Controllers\ProfessorController::class, 'rate'])->name('professors.rate');
        Route::post('/colleges/{college:slug}/rate', [App\Http\Controllers\CollegeController::class, 'rate'])->name('colleges.rate');
        Route::post('/colleges/request', [App\Http\Controllers\CollegeController::class, 'requestCollege'])->name('colleges.request');

        // Recruitment Pipeline routes
        Route::get('/pipeline', [App\Http\Controllers\PipelineController::class, 'index'])->name('pipeline.index');
        Route::post('/jobs/{job}/apply', [App\Http\Controllers\JobApplicationController::class, 'store'])->name('jobs.apply');

        // 🗂️ Proof of Work (PoW) Showcase: Evidence of Talent 🛡️
        Route::prefix('talent')->name('projects.')->group(function () {
            Route::get('/', [App\Http\Controllers\ProjectController::class, 'index'])->name('index');
            Route::get('/manifest', [App\Http\Controllers\ProjectController::class, 'create'])->name('create');
            Route::post('/store', [App\Http\Controllers\ProjectController::class, 'store'])->name('store');
            Route::post('/endorse/{project}', [App\Http\Controllers\ProjectController::class, 'endorse'])->name('endorse');
        });

        // Perks & Rewards Hub 🎁
        Route::get('/perks', [App\Http\Controllers\RewardController::class, 'index'])->name('rewards.index');
        Route::post('/perks/{reward}/claim', [App\Http\Controllers\RewardController::class, 'claim'])->name('rewards.claim');

        // Batch mates Discovery 🧬
        Route::get('/college/{college:slug}/batch/{year}', [App\Http\Controllers\BatchFinderController::class, 'index'])->name('colleges.batchmates');
        Route::post('/profile/batch-visibility', [App\Http\Controllers\BatchFinderController::class, 'toggleVisibility'])->name('profile.batch.toggle');
    });

    // Comparison Engine ⚖️ (Public)
    Route::prefix('compare')->group(function() {
        Route::get('/', [App\Http\Controllers\ComparisonController::class, 'index'])->name('compare.index');
        Route::get('/{slugs}', [App\Http\Controllers\ComparisonController::class, 'compare'])->name('compare.show');
        Route::post('/redirect', [App\Http\Controllers\ComparisonController::class, 'redirect'])->name('compare.redirect');
    });

    // Mentorship Hub 🤝
    Route::post('/mentorship/toggle', [App\Http\Controllers\MentorshipController::class, 'toggleMode'])->name('mentorship.toggle');
    Route::post('/mentorship/request/{mentor}', [App\Http\Controllers\MentorshipController::class, 'requestStore'])->name('mentorship.request');
    Route::post('/mentorship/respond/{mRequest}', [App\Http\Controllers\MentorshipController::class, 'respond'])->name('mentorship.respond');
    Route::post('/mentorship/complete/{mRequest}', [App\Http\Controllers\MentorshipController::class, 'complete'])->name('mentorship.complete');

    // Recruiter Only Routes
    Route::middleware(['role:recruiter'])->group(function () {
        Route::get('/recruiter', [App\Http\Controllers\RecruiterController::class, 'index'])->name('recruiter.dashboard');
        Route::post('/recruiter/jobs', [App\Http\Controllers\RecruiterController::class, 'storeJob'])->name('recruiter.jobs.store');
        Route::get('/recruiter/jobs/{job}/applicants', [App\Http\Controllers\RecruiterController::class, 'viewApplicants'])->name('recruiter.jobs.applicants');
        Route::post('/recruiter/applications/{application}/status', [App\Http\Controllers\RecruiterController::class, 'updateApplicationStatus'])->name('recruiter.applications.status');
        Route::post('/recruiter/integration/initialize', [App\Http\Controllers\RecruiterController::class, 'initializeIntegration'])->name('recruiter.integration.initialize');
    });
});

// Separate Recruiter Auth (Isolated Node) 🛰️
Route::middleware('guest')->group(function () {
    Route::get('/recruiter/register', [App\Http\Controllers\Auth\RecruiterRegisterController::class, 'create'])->name('recruiter.register');
    Route::post('/recruiter/register', [App\Http\Controllers\Auth\RecruiterRegisterController::class, 'store'])->name('recruiter.register.store');
    Route::get('/recruiter/login', [App\Http\Controllers\Auth\RecruiterLoginController::class, 'create'])->name('recruiter.login');
    Route::post('/recruiter/login', [App\Http\Controllers\Auth\RecruiterLoginController::class, 'store'])->name('recruiter.login.store');
});
Route::post('/recruiter/logout', [App\Http\Controllers\Auth\RecruiterLoginController::class, 'destroy'])->name('recruiter.logout');

require __DIR__.'/auth.php';
Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index']);

// Emergency Production Fix for Hostinger
/*
|--------------------------------------------------------------------------
| Admin Multiverse Security Guard 🛡️
|--------------------------------------------------------------------------
*/
// Master Authority Auth Terminal (Isolated Node) 🛡️
Route::middleware('guest')->group(function () {
    Route::get('/mcv-admin/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'create'])->name('admin.login');
    Route::post('/mcv-admin/login', [App\Http\Controllers\Auth\AdminLoginController::class, 'store'])->name('admin.login.store');
    
    // Master Authority Recovery Nodes (Temporary) 🔐
    Route::get('/mcv-admin/register', [App\Http\Controllers\Auth\AdminRegisterController::class, 'create'])->name('admin.register');
    Route::post('/mcv-admin/register', [App\Http\Controllers\Auth\AdminRegisterController::class, 'store'])->name('admin.register.store');
});
Route::post('/mcv-admin/logout', [App\Http\Controllers\Auth\AdminLoginController::class, 'destroy'])->name('admin.logout');

// Admin Base Redirect
Route::get('/mcv-admin', function() {
    return redirect()->route('admin.dashboard');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // The Command Center Hub
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Citizen Registry (User Management)
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users');
    Route::post('/users/{user}/status', [App\Http\Controllers\Admin\UserController::class, 'updateStatus'])->name('users.status');
    Route::post('/users/{user}/promote', [App\Http\Controllers\Admin\UserController::class, 'promote'])->name('users.promote');
    
    // Knowledge Moderation (Note Approval)
    Route::get('/notes', [App\Http\Controllers\Admin\NoteController::class, 'index'])->name('notes');
    Route::get('/notes/bulk-generate', [App\Http\Controllers\Admin\NoteController::class, 'bulkGenerateForm'])->name('notes.bulk');
    Route::post('/notes/bulk-generate', [App\Http\Controllers\Admin\NoteController::class, 'bulkGenerate'])->name('notes.bulk.store');
    Route::post('/notes/generate-single', [App\Http\Controllers\Admin\NoteController::class, 'generateSingle'])->name('notes.generate.single');
    Route::post('/notes/{note}/verify', [App\Http\Controllers\Admin\NoteController::class, 'verify'])->name('notes.verify');
    Route::delete('/notes/{note}', [App\Http\Controllers\Admin\NoteController::class, 'destroy'])->name('notes.destroy');

    // Resolution Center (Reports & Flags)
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports');
    Route::post('/reports/{report}/resolve', [App\Http\Controllers\Admin\ReportController::class, 'resolve'])->name('reports.resolve');

    // Community Moderation (Verse Feed)
    Route::get('/community', [App\Http\Controllers\Admin\CommunityController::class, 'index'])->name('community');
    Route::post('/community/{post}/pin', [App\Http\Controllers\Admin\CommunityController::class, 'togglePin'])->name('community.pin');
    Route::delete('/community/{post}', [App\Http\Controllers\Admin\CommunityController::class, 'destroy'])->name('community.destroy');

    // Chat Monitoring (Social Safety) 💬
    Route::get('/chat', [App\Http\Controllers\Admin\ChatController::class, 'index'])->name('chat');
    
    // Feedback Governance (Reviews) ⭐
    Route::get('/reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews');
    Route::post('/reviews/{type}/{id}/approve', [App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{type}/{id}', [App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Institutional Registries (Colleges, Professors, Courses, & Subjects)
    Route::get('/colleges', [App\Http\Controllers\Admin\CollegeController::class, 'index'])->name('colleges');
    Route::post('/colleges', [App\Http\Controllers\Admin\CollegeController::class, 'store'])->name('colleges.store');
    Route::post('/colleges/import', [App\Http\Controllers\Admin\CollegeController::class, 'import'])->name('colleges.import');
    Route::patch('/colleges/{college}', [App\Http\Controllers\Admin\CollegeController::class, 'update'])->name('colleges.update');
    Route::delete('/colleges/{college}', [App\Http\Controllers\Admin\CollegeController::class, 'destroy'])->name('colleges.destroy');

    Route::resource('courses', App\Http\Controllers\Admin\CourseController::class)->except(['create', 'edit', 'show', 'update']);
    Route::resource('subjects', App\Http\Controllers\Admin\SubjectController::class)->except(['create', 'edit', 'show', 'update']);

    Route::get('/professors', [App\Http\Controllers\Admin\ProfessorController::class, 'index'])->name('professors');
    Route::get('/professors/requests', [App\Http\Controllers\Admin\ProfessorController::class, 'requests'])->name('professors.requests');
    Route::post('/professors', [App\Http\Controllers\Admin\ProfessorController::class, 'store'])->name('professors.store');
    Route::post('/professors/requests/{profRequest}/approve', [App\Http\Controllers\Admin\ProfessorController::class, 'approveRequest'])->name('professors.approve');
    Route::post('/professors/requests/{profRequest}/reject', [App\Http\Controllers\Admin\ProfessorController::class, 'rejectRequest'])->name('professors.reject');
    Route::delete('/professors/{professor}', [App\Http\Controllers\Admin\ProfessorController::class, 'destroy'])->name('professors.destroy');

    // Command Center (Settings & Admins) ⚙️👑
    Route::get('/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    
    Route::get('/admins', [App\Http\Controllers\Admin\AdminManagementController::class, 'index'])->name('admins');
    Route::post('/admins', [App\Http\Controllers\Admin\AdminManagementController::class, 'store'])->name('admins.store');
    Route::delete('/admins/{admin}', [App\Http\Controllers\Admin\AdminManagementController::class, 'destroy'])->name('admins.destroy');

    // Deep Analytics Cluster
    Route::get('/analytics', [App\Http\Controllers\Admin\AnalyticsController::class, 'index'])->name('analytics');

    // SEO Nucleus (Page & Blog Management) 🛰️
    Route::resource('pages', App\Http\Controllers\Admin\PageController::class);
    Route::resource('blogs', App\Http\Controllers\Admin\BlogController::class);

    // Command: Startup Hub (Social & Corporate Nodes) 🚀
    Route::get('/startup', [App\Http\Controllers\Admin\StartupHubController::class, 'index'])->name('startup.index');
    Route::post('/startup/social', [App\Http\Controllers\Admin\StartupHubController::class, 'updateSocial'])->name('startup.social.update');

    // Perks & Rewards Governance 🎁
    Route::resource('rewards', App\Http\Controllers\Admin\RewardController::class);
});

Route::get('/multiverse-slug-sync', function() {
    try {
        $profs = \App\Models\Professor::whereNull('slug')->get();
        $count = 0;
        foreach($profs as $p) {
            $p->update([
                'slug' => \Illuminate\Support\Str::slug($p->name . '-' . $p->department . '-' . $p->id)
            ]);
            $count++;
        }
        return "🌌 Identity Sync Complete! Generated $count slugs for existing faculty nodes.";
    } catch (\Exception $e) {
        return "Sync Error: " . $e->getMessage();
    }
});


Route::get('/multiverse-startup-init', function () {
    try {
        $nodes = [
            ['title' => 'Careers | MyCollegeVerse', 'slug' => 'careers', 'content' => '<h1>Join the Multiverse</h1><p>We are building the future of education.</p>'],
            ['title' => 'Partner With Us', 'slug' => 'partner', 'content' => '<h1>Institutional Partnerships</h1><p>Digitize your campus.</p>'],
            ['title' => 'Help Center & FAQ', 'slug' => 'faq', 'content' => '<h1>Command Center</h1><p>Frequently asked questions.</p>'],
        ];

        foreach ($nodes as $node) {
            \App\Models\Page::firstOrCreate(
                ['slug' => $node['slug']],
                ['title' => $node['title'], 'content' => $node['content'], 'is_active' => true]
            );
        }

        return "Startup Identity Nodes Initialized. You can now edit them in the Startup Hub.";
    } catch (\Exception $e) {
        return "Initialization Error: " . $e->getMessage();
    }
});

Route::get('/multiverse-sync', function() {
    try {
        Artisan::call('optimize:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
        return "🌌 Multiverse Synchronized! All production caches have been cleared. You can now visit <a href='/admin/login'>/admin/login</a>.";
    } catch (\Exception $e) {
        return "Sync Error: " . $e->getMessage();
    }
});

Route::get('/multiverse-migrate', function() {
    try {
        // 1. Establish Nexus Points (Clear Caches)
        Artisan::call('optimize:clear');
        
        // 2. Run Migrations
        Artisan::call('migrate', ['--force' => true]);
        return "🌌 Multiverse Manifested! Database schema updated. You can now visit <a href='/multiverse-post-slug-sync'>/multiverse-post-slug-sync</a> to finalize the identity mapping.";
    } catch (\Exception $e) {
        return "Migration Error: " . $e->getMessage();
    }
});

Route::get('/multiverse-seed', function() {
    try {
        Artisan::call('multiverse:seed-community');
        return "🌱 Community SEO Goldmine Initialized! Content manifested in the Verse. Visit <a href='/community'>Community</a>.";
    } catch (\Exception $e) {
        return "Seeding Error: " . $e->getMessage();
    }
});

Route::get('/multiverse-editorial-seed', function() {
    try {
        $categories = [
            ['name' => 'Career Roadmap', 'description' => 'Deep strategic insights for your future profession.'],
            ['name' => 'College Comparison', 'description' => 'Side-by-side analysis of institutional nodes.'],
            ['name' => 'Exam Intelligence', 'description' => 'Mastering the academic multiverse entrance nodes.'],
            ['name' => 'Campus Life', 'description' => 'Authentic reports from the citizen ground level.'],
        ];

        foreach($categories as $cat) {
            \App\Models\BlogCategory::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($cat['name'])],
                ['name' => $cat['name'], 'description' => $cat['description']]
            );
        }
        return "🌱 Editorial Taxonomy Initialized! Seeded " . count($categories) . " categories. Visit <a href='/blog'>Blog</a>.";
    } catch (\Exception $e) {
        return "Seeding Error: " . $e->getMessage();
    }
});

Route::get('/multiverse-init', function() {
    try {
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        return "🌌 Multiverse Synchronized! All caches cleared. You can now visit the <a href='/'>Home Page</a>.";
    } catch (\Exception $e) {
        return "Initialization Error: " . $e->getMessage();
    }
});
