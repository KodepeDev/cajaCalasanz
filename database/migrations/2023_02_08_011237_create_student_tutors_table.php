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
        Schema::create('student_tutors', function (Blueprint $table) {

            $table->id();
            $table->string('full_name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('document_type')->nullable();
            $table->string('document')->nullable();;
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->enum('type', ['PADRE', 'MADRE', 'TUTOR', 'APODERADO', 'OTROS'])->nullable();
            $table->boolean('is_ative')->default(true);
            $table->boolean('is_client')->default(true);
            $table->text('description')->nullable();

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
        Schema::dropIfExists('student_tutors');
    }
};
