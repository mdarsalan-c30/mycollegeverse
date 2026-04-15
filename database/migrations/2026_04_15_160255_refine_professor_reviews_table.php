<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefineProfessorReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('comment');
            $table->json('tags')->nullable()->after('status');
            $table->string('unit_focus')->nullable()->after('tags');
            $table->integer('internal_difficulty')->nullable()->after('unit_focus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['status', 'tags', 'unit_focus', 'internal_difficulty']);
        });
    }
}
