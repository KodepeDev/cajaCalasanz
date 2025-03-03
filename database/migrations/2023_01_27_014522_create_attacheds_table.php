<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attacheds', function (Blueprint $table) {
            $table->id();
            $table->string('path')->nullable();

            $table->unsignedBigInteger('summary_id');
            $table->foreign('summary_id')->references('id')->on('summaries');

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
        Schema::dropIfExists('attacheds');
    }
};
