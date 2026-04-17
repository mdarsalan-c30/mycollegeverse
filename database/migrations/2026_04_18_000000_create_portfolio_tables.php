<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Projects (The Proof of Work Vault)
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('stream')->nullable();
            $table->text('description')->nullable();
            $table->string('artifact_url'); // Drive, Notion, Behance link
            $table->string('cover_image_path')->nullable();
            $table->string('type')->default('case_study'); // case_study, research, design, etc.
            $table->integer('verification_count')->default(0);
            $table->boolean('is_official')->default(false);
            $table->timestamps();
        });

        // 2. User Experience (Professional History)
        Schema::create('user_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('company');
            $table->string('type')->default('Internship'); // Full-time, Internship, Freelance
            $table->string('duration')->nullable(); // e.g. "June 2025 - Present"
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 3. User Education (Academic History)
        Schema::create('user_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('institution');
            $table->string('degree');
            $table->string('field_of_study')->nullable();
            $table->string('year')->nullable(); // e.g. "2022 - 2026"
            $table->timestamps();
        });

        // 4. Project Endorsements (Recruiter Feedback)
        Schema::create('project_endorsements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('recruiter_id')->constrained('users')->onDelete('cascade');
            $table->text('comment');
            $table->integer('rating')->default(5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_endorsements');
        Schema::dropIfExists('user_educations');
        Schema::dropIfExists('user_experiences');
        Schema::dropIfExists('projects');
    }
};
