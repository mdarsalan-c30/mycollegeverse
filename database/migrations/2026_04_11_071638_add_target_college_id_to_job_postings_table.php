<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTargetCollegeIdToJobPostingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->foreignId('target_college_id')->nullable()->after('recruiter_id')->constrained('colleges')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropForeign(['target_college_id']);
            $table->dropColumn('target_college_id');
        });
    }
}
