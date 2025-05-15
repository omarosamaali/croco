<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscribersTable extends Migration
{
    public function up()
    {
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('main_category_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->string('country');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->decimal('price', 8, 2);
            $table->string('status')->default('pending');
            $table->string('payment_status')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->string('activation_code')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('main_category_id')
                ->references('id')
                ->on('main_categories')
                ->onDelete('cascade');
            $table->foreign('sub_category_id')
                ->references('id')
                ->on('sub_categories')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscribers');
    }
}
