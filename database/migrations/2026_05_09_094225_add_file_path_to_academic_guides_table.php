<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilePathToAcademicGuidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('academic_guides', function (Blueprint $table) {
            $table->string('file_path')->nullable()->after('content');
        });
    }

    public function down()
    {
        Schema::table('academic_guides', function (Blueprint $table) {
            $table->dropColumn('file_path');
        });
    }
}
