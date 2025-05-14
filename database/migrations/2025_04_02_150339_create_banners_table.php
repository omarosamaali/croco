<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('image_path'); // Path to the banner image
            $table->dateTime('start_date'); // Added start date and time
            $table->dateTime('expiration_date'); // Changed to dateTime for time inclusion
            $table->string('location'); // Added location (website/app)
            $table->string('category')->nullable(); // Added category, nullable if location is not 'app'
            $table->boolean('is_active')->default(true); // Whether the banner is active
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
