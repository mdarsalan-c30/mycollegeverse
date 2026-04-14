<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlacementDataToCollegeReviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('college_reviews', function (Blueprint $table) {
            $table->decimal('average_package', 8, 2)->nullable()->after('comment');
            $table->decimal('lowest_package', 8, 2)->nullable()->after('average_package');
            $table->decimal('highest_package', 8, 2)->nullable()->after('lowest_package');
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
            $table->dropColumn(['average_package', 'lowest_package', 'highest_package']);
        });
    }
}
