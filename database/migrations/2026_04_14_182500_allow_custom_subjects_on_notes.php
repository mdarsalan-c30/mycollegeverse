<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AllowCustomSubjectsOnNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Native SQL fallback to bypass Doctrine DBAL requirement on Hostinger
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE notes MODIFY subject_id BIGINT UNSIGNED NULL');

        Schema::table('notes', function (Blueprint $table) {
            $table->string('custom_subject')->nullable()->after('subject_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Native SQL fallback to bypass Doctrine DBAL requirement on Hostinger
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE notes MODIFY subject_id BIGINT UNSIGNED NOT NULL');

        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn('custom_subject');
        });
    }
}
