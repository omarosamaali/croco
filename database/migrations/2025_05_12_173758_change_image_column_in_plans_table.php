<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeImageColumnInPlansTable extends Migration
{
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->string('image')->change(); // تغيير الحقل من text إلى string
        });
    }

    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->text('image')->change(); // التراجع عن التغيير
        });
    }
}
