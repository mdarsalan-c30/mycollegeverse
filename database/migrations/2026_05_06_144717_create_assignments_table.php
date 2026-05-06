<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recruiter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('job_id')->nullable()->constrained('job_postings')->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->string('role')->nullable(); // e.g. Content Writer, Video Editor
            $table->string('task_type')->default('General'); // Blog, Video, Sales, etc.
            $table->json('submission_types')->nullable(); // ['link', 'file', 'text']
            $table->dateTime('deadline')->nullable();
            $table->boolean('is_public')->default(true);
            $table->string('status')->default('active'); // active, closed
            $table->json('settings')->nullable(); // max_file_size, etc.
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
        Schema::dropIfExists('assignments');
    }
}
