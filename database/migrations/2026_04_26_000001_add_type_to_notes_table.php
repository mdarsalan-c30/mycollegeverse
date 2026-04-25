<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            // Only add the columns we actually need for the competitive domain
            if (!Schema::hasColumn('notes', 'note_type')) {
                $table->string('note_type')->default('academic')->after('id');
            }
            if (!Schema::hasColumn('notes', 'exam_name')) {
                $table->string('exam_name')->nullable()->after('note_type');
            }
        });
    }

    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn(['note_type', 'exam_name']);
        });
    }
};
