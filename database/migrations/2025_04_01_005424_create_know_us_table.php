<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('know_us', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar'); // Title in Arabic
            $table->string('title_en'); // Title in English
            $table->text('description_ar'); // Description in Arabic
            $table->text('description_en'); // Description in English
            $table->string('image_path')->nullable(); // Image path (optional)
            $table->string('author')->default('Admin'); // Author of the content
            $table->integer('comments_count')->default(0); // Number of comments
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('know_us');
    }
};