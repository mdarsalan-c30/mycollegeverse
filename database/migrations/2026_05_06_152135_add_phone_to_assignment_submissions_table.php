<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneToAssignmentSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assignment_submissions', function (Blueprint $table) {
            $table->string('candidate_phone')->nullable()->after('candidate_email');
        });
    }

    public function down()
    {
        Schema::table('assignment_submissions', function (Blueprint $table) {
            $table->dropColumn('candidate_phone');
        });
    }
}
