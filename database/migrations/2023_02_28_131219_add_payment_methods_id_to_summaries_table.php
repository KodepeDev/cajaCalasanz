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
        Schema::table('summaries', function (Blueprint $table) {
            //
            $table->string('operation_number')->after('amount')->nullable();

            $table->unsignedBigInteger('payment_method_id')->after('student_tutor_id')->default(1)->nullable();
            $table->foreign('payment_method_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('summaries', function (Blueprint $table) {
            //
            $table->dropColumn('operation_number');
            $table->dropColumn('payment_method_id');
        });
    }
};
