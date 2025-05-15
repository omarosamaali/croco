<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToSubscribersTable extends Migration
{
    public function up()
    {
        Schema::table('subscribers', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('subscribers', 'main_category_id')) {
                $table->unsignedBigInteger('main_category_id')->nullable();
                $table->foreign('main_category_id')
                    ->references('id')
                    ->on('main_categories')
                    ->onDelete('cascade');
            }

            if (!Schema::hasColumn('subscribers', 'sub_category_id')) {
                $table->unsignedBigInteger('sub_category_id')->nullable();
                $table->foreign('sub_category_id')
                    ->references('id')
                    ->on('sub_categories')
                    ->onDelete('cascade');
            }

            if (!Schema::hasColumn('subscribers', 'price')) {
                $table->decimal('price', 8, 2)->nullable();
            }

            if (!Schema::hasColumn('subscribers', 'status')) {
                $table->string('status')->default('pending');
            }

            if (!Schema::hasColumn('subscribers', 'payment_status')) {
                $table->string('payment_status')->nullable();
            }

            if (!Schema::hasColumn('subscribers', 'payment_date')) {
                $table->timestamp('payment_date')->nullable();
            }

            if (!Schema::hasColumn('subscribers', 'activation_code')) {
                $table->string('activation_code')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->dropForeign(['main_category_id']);
            $table->dropForeign(['sub_category_id']);
            $table->dropColumn(['main_category_id', 'sub_category_id', 'price', 'status', 'payment_status', 'payment_date', 'activation_code']);
        });
    }
}
