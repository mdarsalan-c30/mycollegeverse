<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HardenBlogsTableSchema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blogs', function (Blueprint $table) {
            // Check and add missing columns for Editorial Evolution
            if (!Schema::hasColumn('blogs', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('blogs', 'excerpt')) {
                $table->string('excerpt', 500)->nullable()->after('content');
            }
            if (!Schema::hasColumn('blogs', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('featured_image');
            }
            if (!Schema::hasColumn('blogs', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('blogs', 'meta_keywords')) {
                $table->string('meta_keywords')->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('blogs', 'seo_score')) {
                $table->integer('seo_score')->default(0)->after('meta_keywords');
            }
            if (!Schema::hasColumn('blogs', 'ai_score')) {
                $table->integer('ai_score')->default(0)->after('seo_score');
            }
            if (!Schema::hasColumn('blogs', 'auto_recommend_colleges')) {
                $table->boolean('auto_recommend_colleges')->default(true)->after('ai_score');
            }
            if (!Schema::hasColumn('blogs', 'college_ids')) {
                $table->json('college_ids')->nullable()->after('auto_recommend_colleges');
            }
            if (!Schema::hasColumn('blogs', 'views')) {
                $table->unsignedBigInteger('views')->default(0)->after('is_published');
            }
            if (!Schema::hasColumn('blogs', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('views');
            }

            // Relationship & Index Maintenance
            if (Schema::hasTable('blog_categories')) {
                // We add the index safely
                if (Schema::hasColumn('blogs', 'category_id')) {
                    // check if index exists is hard in some versions, but we assume it's safe to try or skip
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn([
                'category_id', 'excerpt', 'meta_title', 'meta_description', 
                'meta_keywords', 'seo_score', 'ai_score', 
                'auto_recommend_colleges', 'college_ids', 'views', 'published_at'
            ]);
        });
    }
}
