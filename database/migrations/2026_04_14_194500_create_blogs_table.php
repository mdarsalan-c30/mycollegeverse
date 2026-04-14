<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Author (Admin)
            $table->unsignedBigInteger('category_id')->nullable(); // Editorial Segment
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->string('excerpt', 500)->nullable();
            $table->string('featured_image')->nullable();
            
            // SEO Nucleus 🛡️
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->integer('seo_score')->default(0);
            $table->integer('ai_score')->default(0);
            
            // Institutional Integration 🧬
            $table->boolean('auto_recommend_colleges')->default(true);
            $table->json('college_ids')->nullable(); // Explicit list of colleges
            
            // Status & Discovery 🛰️
            $table->boolean('is_published')->default(false);
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamp('published_at')->nullable();
            
            $table->timestamps();

            // High-Performance Indexing
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('is_published');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
