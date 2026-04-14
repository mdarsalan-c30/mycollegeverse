<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameThumbnailToCampusimgInCollegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('colleges', function (Blueprint $table) {
            $table->renameColumn('thumbnail_url', 'campusimg');
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
            $table->renameColumn('campusimg', 'thumbnail_url');
        });
    }
}
