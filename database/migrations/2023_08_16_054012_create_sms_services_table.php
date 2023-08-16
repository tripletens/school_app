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
        Schema::create('sms_services', function (Blueprint $table) {
            $table->id();
            $table->text('name')->nullable();
            $table->text('provider_name');
            $table->text('username')->nullable();
            $table->text('sender_id')->nullable();
            $table->text('token_key')->nullable();
            $table->text('api_key')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('status')->default(true);
            $table->foreignId('created_by')->nullable();
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
        Schema::dropIfExists('sms_services');
    }
};
