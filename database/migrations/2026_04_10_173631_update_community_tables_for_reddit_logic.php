<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateCommunityTablesForRedditLogic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->smallInteger('value')->default(1)->after('post_id');
        });

        Schema::table('comments', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable()->index()->after('user_id');
            $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');
        });

        // Initialize existing likes with value 1 (Upvotes)
        DB::table('likes')->update(['value' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });

        Schema::table('likes', function (Blueprint $table) {
            $table->dropColumn('value');
        });
    }
}
