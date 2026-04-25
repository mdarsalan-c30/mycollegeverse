<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->string('note_type')->default('academic')->after('id'); // academic, competitive, skill
            $table->string('exam_name')->nullable()->after('note_type');
            
            // Making these nullable for non-academic notes
            $table->unsignedBigInteger('course_id')->nullable()->change();
            $table->integer('semester')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn(['note_type', 'exam_name']);
        });
    }
};
