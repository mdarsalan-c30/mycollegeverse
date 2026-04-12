<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModerationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('reportable_type'); // Note, Post, Comment, User, Review
            $table->unsignedBigInteger('reportable_id');
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            $table->string('reason');
            $table->text('admin_notes')->nullable();
            $table->enum('status', ['pending', 'resolved', 'ignored'])->default('pending');
            $table->timestamps();

            $table->index(['reportable_type', 'reportable_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
