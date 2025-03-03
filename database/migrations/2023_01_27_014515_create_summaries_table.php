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
        Schema::create('summaries', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->text('concept')->nullable();
            $table->enum('type', ['add', 'out']);
            $table->enum('status', ['PAID', 'PENDING', 'NULLED'])->default('PAID');
            $table->double('amount');
            $table->double('tax')->nullable();
            $table->string('recipt_series')->nullable();
            $table->bigInteger('recipt_number')->nullable();
            $table->enum('future', ['1', '2',])->default('1');

            $table->unsignedBigInteger('account_id')->nullable();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->unsignedBigInteger('id_transfer')->nullable();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('summaries');
    }
};
