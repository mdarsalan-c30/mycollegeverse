<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcademicEventsTable extends Migration
{
    public function up()
    {
        Schema::create('academic_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['exam', 'project', 'assignment', 'quiz', 'lab', 'mst', 'other'])->default('other');
            $table->dateTime('due_date');
            $table->text('description')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            
            // Targeting
            $table->foreignId('subject_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('college_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('semester')->nullable();
            
            // Ownership & Verification
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // If null, it's global/official
            $table->boolean('is_official')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->integer('verification_count')->default(0);
            
            $table->timestamps();
            
            // Indexes for fast lookup on dashboard
            $table->index(['college_id', 'course_id', 'semester']);
            $table->index('due_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('academic_events');
    }
}
