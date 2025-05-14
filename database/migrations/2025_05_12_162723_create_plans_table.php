<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('title_ar'); // العنوان بالعربية
            $table->string('title_en'); // العنوان بالإنجليزية
            $table->string('price'); // السعر (مثل 30 AED)
            $table->text('image'); // رابط الصورة
            $table->json('description_ar'); // الوصف بالعربية (مصفوفة)
            $table->json('description_en'); // الوصف بالإنجليزية (مصفوفة)
            $table->boolean('is_active')->default(true); // حالة الخطة
            $table->integer('order')->default(0); // ترتيب العرض
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
