<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdvancedAttributesToCollegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('colleges', function (Blueprint $table) {
            $table->string('type')->nullable()->after('name');
            $table->json('streams')->nullable()->after('type');
            $table->string('state')->nullable()->after('location');
            $table->string('city')->nullable()->after('state');
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
            $table->dropColumn(['type', 'streams', 'state', 'city']);
        });
    }
}
