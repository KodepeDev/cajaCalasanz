<?php

use App\Models\Cuote;
use App\Models\Student;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cuote_student', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Cuote::class);
            $table->foreignIdFor(Student::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuote_student');
    }
};
