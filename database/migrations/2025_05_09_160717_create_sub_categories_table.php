<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_category_id')->constrained('main_categories')->onDelete('cascade');
            $table->string('name_ar');
            $table->string('name_en');
            $table->decimal('price', 8, 2); // For price (e.g., 9999.99)
            $table->enum('duration', [30, 90, 180, 365]); // Duration in months
            $table->boolean('status')->default(true); // Active (true) or Inactive (false)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_categories');
    }
};
