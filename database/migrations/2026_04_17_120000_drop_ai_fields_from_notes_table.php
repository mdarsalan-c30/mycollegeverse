<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAiFieldsFromNotesTable extends Migration
{
    /**
     * Drop the AI-generated fields that were added but rolled back.
     */
    public function up()
    {
        Schema::table('notes', function (Blueprint $table) {
            if (Schema::hasColumn('notes', 'ai_content')) {
                $table->dropColumn('ai_content');
            }
            if (Schema::hasColumn('notes', 'note_type')) {
                $table->dropColumn('note_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->longText('ai_content')->nullable();
            $table->string('note_type')->default('pdf');
        });
    }
}
