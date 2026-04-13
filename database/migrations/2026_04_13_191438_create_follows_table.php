<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('follows', function (Blueprint $blade) {
            $blade->id();
            $blade->foreignId('follower_id')->constrained('users')->onDelete('cascade');
            $blade->foreignId('following_id')->constrained('users')->onDelete('cascade');
            $blade->timestamps();

            // Prevent duplicate follows
            $blade->unique(['follower_id', 'following_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('follows');
    }
}
