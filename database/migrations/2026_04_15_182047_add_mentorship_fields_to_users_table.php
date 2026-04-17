<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMentorshipFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_mentor')->default(false)->after('career_role');
            $table->text('mentor_bio')->nullable()->after('is_mentor');
            $table->json('mentor_topics')->nullable()->after('mentor_bio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_mentor', 'mentor_bio', 'mentor_topics']);
        });
    }
}
