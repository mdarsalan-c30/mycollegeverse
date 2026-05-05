<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeUserIdNullableInResumesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE resumes MODIFY user_id BIGINT UNSIGNED NULL');
    }

    public function down()
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE resumes MODIFY user_id BIGINT UNSIGNED NOT NULL');
    }
}
