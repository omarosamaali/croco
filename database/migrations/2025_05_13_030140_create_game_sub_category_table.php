<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameSubCategoryTable extends Migration
{
    public function up()
    {
        // إنشاء جدول محوري
        Schema::create('game_sub_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->foreignId('sub_category_id')->constrained('sub_categories')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['game_id', 'sub_category_id']); // منع التكرار
        });

        // إزالة حقل sub_category من جدول games
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('sub_category');
        });
    }

    public function down()
    {
        // إعادة إضافة حقل sub_category عند التراجع
        Schema::table('games', function (Blueprint $table) {
            $table->string('sub_category')->after('image')->nullable();
        });

        // حذف الجدول المحوري
        Schema::dropIfExists('game_sub_category');
    }
}
