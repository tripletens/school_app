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
        Schema::table('role', function (Blueprint $table) {});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role', function (Blueprint $table) {
            //
            $table->id();
            $table->text('name');
            $table->text('slug');
            $table->text('type')->nullable();
            $table->text('category')->nullable();
            $table->text('icon')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->timestamps();
        });
    }
};
