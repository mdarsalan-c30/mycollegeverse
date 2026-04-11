<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageSupportToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_photo_path')->nullable()->after('username');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->string('type')->default('text')->after('message'); // text, image
            $table->string('image_path')->nullable()->after('type');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('profile_photo_path');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn(['type', 'image_path']);
        });
    }
}
