<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Add new columns
        Schema::table('notes', function (Blueprint $table) {
            if (!Schema::hasColumn('notes', 'note_type')) {
                $table->string('note_type')->default('academic')->after('id');
            }
            if (!Schema::hasColumn('notes', 'exam_name')) {
                $table->string('exam_name')->nullable()->after('note_type');
            }
        });

        // 2. Use Raw SQL to change column nullability (Doctrine DBAL bypass) 🛡️
        // This ensures compatibility with Hostinger/Production environments
        DB::statement('ALTER TABLE notes MODIFY course_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE notes MODIFY semester INT NULL');
    }

    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn(['note_type', 'exam_name']);
        });
        
        // Reverse nullability is optional and risky if data exists, 
        // so we leave it as nullable in down() to prevent crashes.
    }
};
