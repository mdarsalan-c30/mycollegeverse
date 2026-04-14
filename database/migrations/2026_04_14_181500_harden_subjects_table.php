<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HardenSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('subjects', 'course')) {
            Schema::table('subjects', function (Blueprint $table) {
                // Dropping the legacy column that is causing 500 error due to 'no default value' constraint
                $table->dropColumn('course');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->string('course')->nullable()->after('name');
        });
    }
}
