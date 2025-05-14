<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanSubCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('plan_sub_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_category_id')->constrained('sub_categories')->onDelete('cascade');
            $table->timestamps();

            // منع التكرار في العلاقة
            $table->unique(['plan_id', 'sub_category_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('plan_sub_category');
    }
}
