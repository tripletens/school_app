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
        Schema::create('user_bio', function (Blueprint $table) {
            $table->id();
            $table->text('surname');
            $table->text('first_name');
            $table->text('other_name')->nullable();
            $table->text('username')->nullable();
            $table->text('phone')->nullable();
            $table->text('photo')->nullable();
            $table->text('dob')->nullable();
            $table->boolean('status')->default(true);
            $table->foreignId('uid');
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
        Schema::dropIfExists('user_bio');
    }
};
