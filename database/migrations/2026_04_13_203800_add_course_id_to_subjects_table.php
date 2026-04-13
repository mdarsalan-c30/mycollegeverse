<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCourseIdToSubjectsTable extends Migration
{
    public function up()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('course_id')->nullable()->after('id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });

        // Migrate existing data
        $uniqueCourses = \DB::table('subjects')->whereNotNull('course')->pluck('course')->unique();
        foreach ($uniqueCourses as $courseName) {
            $courseId = \DB::table('courses')->insertGetId([
                'name' => $courseName,
                'slug' => \Illuminate\Support\Str::slug($courseName),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            \DB::table('subjects')->where('course', $courseName)->update(['course_id' => $courseId]);
        }
    }

    public function down()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropColumn('course_id');
        });
    }
}
