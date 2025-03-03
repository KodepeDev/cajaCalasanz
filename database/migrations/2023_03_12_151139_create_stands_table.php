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
        Schema::create('cuotes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->decimal('amount')->default(0.00);

            $table->unsignedBigInteger('cuote_type_id')->nullable();
            $table->foreign('cuote_type_id')->references('id')->on('cuote_types');

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
        Schema::dropIfExists('stands');
    }
};
