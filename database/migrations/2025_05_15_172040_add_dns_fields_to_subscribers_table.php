<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDnsFieldsToSubscribersTable extends Migration
{
    public function up()
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->string('dns_username')->nullable()->after('activation_code');
            $table->string('dns_password')->nullable()->after('dns_username');
            $table->string('dns_link')->nullable()->after('dns_password');
            $table->date('dns_expiry_date')->nullable()->after('dns_link');
        });
    }

    public function down()
    {
        Schema::table('subscribers', function (Blueprint $table) {
            $table->dropColumn(['dns_username', 'dns_password', 'dns_link', 'dns_expiry_date']);
        });
    }
}
