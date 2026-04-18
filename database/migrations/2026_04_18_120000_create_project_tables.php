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
        // 🗂️ Proof of Work Showcase Table
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('stream'); // Commerce, Arts, Law, Design, etc.
            $table->string('file_url'); // Primary document (PDF/PPT)
            $table->string('cover_image_url'); // Portfolio thumbnail (Mandatory)
            $table->integer('visibility_score')->default(0); // Boosted by endorsements
            $table->boolean('is_featured')->default(false);
            $table->json('metadata')->nullable(); // For stream-specific data
            $table->timestamps();
            
            // Optimization Indexes
            $table->index('stream');
            $table->index('visibility_score');
        });

        // 🤝 Community Endorsement System
        Schema::create('project_endorsements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('strength')->default('standard'); // standard, high_impact
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'project_id']);
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
        Schema::dropIfExists('projects');
    }
};
