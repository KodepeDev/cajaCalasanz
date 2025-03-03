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
        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(false);
            $table->enum('summary_type', ['add', 'out']);
            $table->enum('type', [1,2,3])->default(2);
            $table->string('description')->nullable();
            $table->double('amount');
            $table->date('date')->nullable();
            $table->date('date_paid')->nullable();

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');

            $table->unsignedBigInteger('student_id')->nullable();
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('student_tutor_id')->nullable();
            $table->foreign('student_tutor_id')->references('id')->on('student_tutors');

            $table->unsignedBigInteger('summary_id')->nullable();
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
        Schema::dropIfExists('details');
    }
};
