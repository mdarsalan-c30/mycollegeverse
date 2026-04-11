<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnhanceCollegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('colleges', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->string('thumbnail_url')->nullable()->after('location');
            $table->integer('student_count')->default(0)->after('thumbnail_url');
            $table->float('rating')->default(5.0)->after('student_count');
            $table->json('tags')->nullable()->after('rating');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('colleges', function (Blueprint $table) {
            $table->dropColumn(['description', 'thumbnail_url', 'student_count', 'rating', 'tags']);
        });
    }
}
