<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignment_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('assignment_submissions')->onDelete('cascade');
            $table->string('criteria'); // Quality, Creativity, Accuracy, etc.
            $table->integer('score'); // out of 10 or 100
            $table->text('feedback')->nullable();
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
        Schema::dropIfExists('assignment_evaluations');
    }
}
