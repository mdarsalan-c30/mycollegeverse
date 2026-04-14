<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnsureRatingColumnOnCollegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('colleges', 'rating')) {
            Schema::table('colleges', function (Blueprint $table) {
                $table->decimal('rating', 3, 2)->default(0)->after('users_count');
            });
        }
    }

    public function down()
    {
        Schema::table('colleges', function (Blueprint $table) {
            if (Schema::hasColumn('colleges', 'rating')) {
                $table->dropColumn('rating');
            }
        });
    }
}
