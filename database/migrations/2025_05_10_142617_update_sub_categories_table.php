<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sub_categories', function (Blueprint $table) {
            // تغيير نوع duration من enum إلى integer
            $table->integer('duration')->change();

            // زيادة حجم price إذا كنت تريد (مثال: من decimal(8,2) إلى decimal(10,2))
            $table->decimal('price', 10, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('sub_categories', function (Blueprint $table) {
            // عكس التغييرات في حالة التراجع
            $table->enum('duration', [30, 90, 180, 365])->change();
            $table->decimal('price', 8, 2)->change();
        });
    }
};