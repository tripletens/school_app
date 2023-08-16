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
        Schema::create('school_class', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('slug');
            $table->text('arm');
            $table->foreignId('staff')->nullable();
            $table->foreignId('class_level')->nullable();
            $table->foreignId('class_category')->nullable();
            $table->integer('total_students')->default(0);
            $table->foreignId('report_card_template')->nullable();
            $table->foreignId('mid_term_template')->nullable();
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
        Schema::dropIfExists('school_class');
    }
};
