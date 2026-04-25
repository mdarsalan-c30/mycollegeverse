<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddPyqFieldsToNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Using Raw SQL for Hostinger stability
        DB::statement('ALTER TABLE notes ADD COLUMN is_pyq TINYINT(1) DEFAULT 0 AFTER exam_name');
        DB::statement('ALTER TABLE notes ADD COLUMN pyq_year VARCHAR(10) NULL AFTER is_pyq');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn(['is_pyq', 'pyq_year']);
        });
    }
}
