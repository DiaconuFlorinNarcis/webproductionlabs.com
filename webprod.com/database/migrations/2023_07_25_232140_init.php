<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        try {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });

            Schema::create('tags', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });

            Schema::create('articles', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('description');
                $table->string('content');
                $table->boolean('active');
                $table->foreignId('category_id')->nullable()->constrained();
                $table->timestamps();
            });

            Schema::create('article_pictures', function (Blueprint $table) {
                $table->id();
                $table->foreignId('article_id')->nullable()->constrained();
                $table->string('name');
                $table->string('path');
                $table->timestamps();
            });

            Schema::create('article_tags', function (Blueprint $table) {
                $table->id();
                $table->foreignId('article_id')->constrained();
                $table->foreignId('tag_id')->constrained();
            });

            Schema::create('article_categories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('article_id')->constrained();
                $table->foreignId('category_id')->constrained();
            });
        } catch (Exception $e) {
            dump((string)$e);
            $this->down();
        }
    }

    public function down(): void
    {
        Schema::drop('categories');
        Schema::drop('tags');
        Schema::drop('articles');
        Schema::drop('article_pictures');
        Schema::drop('article_tags');
        Schema::drop('article_categories');
    }
};
