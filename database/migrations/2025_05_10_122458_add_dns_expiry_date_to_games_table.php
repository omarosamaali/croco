<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->date('dns_expiry_date')->nullable()->after('dns_servers'); // إضافة عمود تاريخ انتهاء DNS
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('dns_expiry_date'); // حذف العمود في حالة التراجع
        });
    }
};
