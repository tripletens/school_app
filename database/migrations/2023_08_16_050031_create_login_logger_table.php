<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_logger', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uid');
            $table->longText('browser');
            $table->text('ip_address');
            $table->longText('location_data');
            $table->text('login_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('login_logger');
    }
};
