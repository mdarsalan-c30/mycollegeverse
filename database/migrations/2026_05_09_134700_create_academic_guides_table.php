<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_guides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Creator (Student/Admin)
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content'); // Quill HTML
            $table->string('category')->default('General'); // Syllabus, College, Notice, Admission, Career
            
            // SEO Layer 🚀
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            
            // Filtering Metadata
            $table->string('target_university')->nullable();
            $table->string('target_course')->nullable();
            $table->string('featured_image')->nullable();
            
            $table->boolean('is_published')->default(true);
            $table->unsignedBigInteger('views')->default(0);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_guides');
    }
};
