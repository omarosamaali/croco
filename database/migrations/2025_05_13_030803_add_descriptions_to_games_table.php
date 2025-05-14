<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionsToGamesTable extends Migration
{
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->json('description_ar')->nullable()->after('image'); // وصف عربي كمصفوفة
            $table->json('description_en')->nullable()->after('description_ar'); // وصف إنجليزي كمصفوفة
        });
    }

    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['description_ar', 'description_en']);
        });
    }
}
