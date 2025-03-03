<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nulled_details', function (Blueprint $table) {
            $table->id();
            $table->enum('summary_type', ['add', 'out']);
            $table->enum('type', [1,2,3])->default(2);
            $table->string('description')->nullable();
            $table->double('amount');
            $table->date('date')->nullable();
            $table->date('date_paid')->nullable();

            $table->string('category')->nullable();

            $table->string('student')->nullable();

            $table->unsignedBigInteger('summary_id')->nullable();
            $table->foreign('summary_id')->references('id')->on('summaries');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nulled_details');
    }
};
