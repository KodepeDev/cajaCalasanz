<?php

use Illuminate\Support\Facades\DB;
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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('document_type');
            $table->string('document')->unique();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        // insertar docentes por default
        DB::table('teachers')->insert([
            [
                'full_name' => 'Carlos Pérez',
                'email' => 'carlos.perez@example.com',
                'document_type' => 1,
                'document' => '12345678',
                'phone' => '987654321',
                'address' => 'Av. Lima 123',
                'photo' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ejemplo: "1° Primaria", "2° Secundaria"
            $table->string('level'); // Nivel educativo: Inicial, Primaria, Secundaria
            $table->timestamps();
        });

        // Insertar los grados según el sistema educativo peruano
        DB::table('grades')->insert([
            // Nivel Inicial
            ['name' => 'Inicial 3 años', 'level' => 'Inicial'],
            ['name' => 'Inicial 4 años', 'level' => 'Inicial'],
            ['name' => 'Inicial 5 años', 'level' => 'Inicial'],

            // Nivel Primaria
            ['name' => '1° Primaria', 'level' => 'Primaria'],
            ['name' => '2° Primaria', 'level' => 'Primaria'],
            ['name' => '3° Primaria', 'level' => 'Primaria'],
            ['name' => '4° Primaria', 'level' => 'Primaria'],
            ['name' => '5° Primaria', 'level' => 'Primaria'],
            ['name' => '6° Primaria', 'level' => 'Primaria'],

            // Nivel Secundaria
            ['name' => '1° Secundaria', 'level' => 'Secundaria'],
            ['name' => '2° Secundaria', 'level' => 'Secundaria'],
            ['name' => '3° Secundaria', 'level' => 'Secundaria'],
            ['name' => '4° Secundaria', 'level' => 'Secundaria'],
            ['name' => '5° Secundaria', 'level' => 'Secundaria'],
        ]);

        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ejemplo: "A", "B", "C"
            $table->timestamps();
        });

        Schema::create('school_years', function (Blueprint $table) {
            $table->id();
            $table->year('year'); // Ejemplo: 2025, 2026
            $table->boolean('is_active')->default(false); // Indica si es el año en curso
            $table->timestamps();
        });
        // Insertar el año escolar 2025 como inactivo por defecto
        DB::table('school_years')->insert([
            ['year' => 2025, 'is_active' => false],
        ]);
        DB::table('school_years')->where('year', 2025)->update(['is_active' => true]);

        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('grade_id')->constrained('grades')->onDelete('cascade');
            $table->foreignId('section_id')->nullable()->constrained('sections')->onDelete('cascade');
            $table->foreignId('school_year_id')->constrained('school_years')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('teacher_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->foreignId('grade_id')->constrained('grades')->onDelete('cascade');
            $table->foreignId('section_id')->nullable()->constrained('sections')->onDelete('cascade');
            $table->foreignId('school_year_id')->constrained('school_years')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
        Schema::dropIfExists('grades');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('school_years');
        Schema::dropIfExists('enrrollments');
        Schema::dropIfExists('teacher_assignments');
    }
};
