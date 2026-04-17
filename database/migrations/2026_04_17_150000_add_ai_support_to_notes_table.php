<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAiSupportToNotesTable extends Migration
{
    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            if (!Schema::hasColumn('notes', 'note_type')) {
                $table->string('note_type')->default('pdf')->after('title');
            }
            if (!Schema::hasColumn('notes', 'ai_content')) {
                $table->longText('ai_content')->nullable()->after('note_type');
            }
        });

        // Make file_path nullable for AI notes (Raw SQL used to avoid Doctrine DBAL dependency)
        if (Schema::hasColumn('notes', 'file_path')) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE notes MODIFY file_path VARCHAR(255) NULL');
        }
    }

    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            if (Schema::hasColumn('notes', 'note_type')) {
                $table->dropColumn('note_type');
            }
            if (Schema::hasColumn('notes', 'ai_content')) {
                $table->dropColumn('ai_content');
            }
        });
    }
}
