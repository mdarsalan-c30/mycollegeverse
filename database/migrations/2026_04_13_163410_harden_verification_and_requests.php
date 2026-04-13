<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HardenVerificationAndRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('id_card_url')->nullable()->after('email');
        });

        Schema::table('professor_requests', function (Blueprint $table) {
            $table->string('profile_photo_url')->nullable()->after('message');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id_card_url');
        });

        Schema::table('professor_requests', function (Blueprint $table) {
            $table->dropColumn('profile_photo_url');
        });
    }
}
