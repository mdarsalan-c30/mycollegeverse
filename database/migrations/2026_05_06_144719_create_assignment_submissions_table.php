<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('candidate_name')->nullable();
            $table->string('candidate_email')->nullable();
            $table->string('submission_link')->nullable();
            $table->string('file_path')->nullable();
            $table->longText('submission_text')->nullable();
            $table->text('recruiter_notes')->nullable();
            $table->integer('score')->nullable();
            $table->string('status')->default('pending'); // pending, reviewed, shortlisted, rejected
            $table->dateTime('expires_at')->nullable();
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
        Schema::dropIfExists('assignment_submissions');
    }
}
