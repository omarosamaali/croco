<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_xx_xx_create_news_table.php
public function up()
{
    Schema::create('news', function (Blueprint $table) {
        $table->id();
        $table->string('title_ar');
        $table->string('title_en');
        $table->text('description_ar')->nullable();
        $table->text('description_en')->nullable();
        $table->string('image_path')->nullable();
        $table->string('author')->default('Admin');
        $table->integer('comments_count')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
