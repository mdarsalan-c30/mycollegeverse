<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefineCollegeReviewsSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('college_reviews', function (Blueprint $table) {
            $table->dropColumn('rating');
            $table->integer('campus_rating')->after('college_id');
            $table->integer('faculty_rating')->after('campus_rating');
            $table->integer('academic_rating')->after('faculty_rating');
            $table->string('verification_id')->after('comment')->nullable();
            $table->string('status')->default('pending')->after('verification_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('college_reviews', function (Blueprint $table) {
            $table->integer('rating')->after('college_id')->default(5);
            $table->dropColumn(['campus_rating', 'faculty_rating', 'academic_rating', 'verification_id', 'status']);
        });
    }
}
