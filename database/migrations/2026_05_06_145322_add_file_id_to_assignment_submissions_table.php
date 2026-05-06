<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileIdToAssignmentSubmissionsTable extends Migration
{
    public function up()
    {
        Schema::table("assignment_submissions", function (Blueprint $table) {
            $table->string("file_id")->nullable()->after("file_path");
        });
    }

    public function down()
    {
        Schema::table("assignment_submissions", function (Blueprint $table) {
            $table->dropColumn("file_id");
        });
    }
}
