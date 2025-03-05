<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            // 📌 Categorías de INGRESOS (add)
            ['name' => 'Matrícula', 'description' => 'Pago por derecho de matrícula', 'type' => 'add', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pensión mensual', 'description' => 'Cuota mensual de enseñanza', 'type' => 'add', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cuota de APAFA', 'description' => 'Aporte de la Asociación de Padres', 'type' => 'add', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Seguro estudiantil', 'description' => 'Pago por seguro de accidentes', 'type' => 'add', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Venta de uniformes', 'description' => 'Ingreso por venta de uniformes escolares', 'type' => 'add', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Talleres extracurriculares', 'description' => 'Ingreso por actividades como deportes, arte, música', 'type' => 'add', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Excursiones y viajes', 'description' => 'Pago por viajes de estudio', 'type' => 'add', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Venta de material educativo', 'description' => 'Ingreso por libros y útiles escolares', 'type' => 'add', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Donaciones', 'description' => 'Aportes voluntarios de padres o empresas', 'type' => 'add', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Servicios de comedor', 'description' => 'Ingreso por alimentación escolar', 'type' => 'add', 'created_at' => now(), 'updated_at' => now()],

            // 📌 Categorías de EGRESOS (out)
            ['name' => 'Salarios de docentes', 'description' => 'Pago de sueldos a profesores', 'type' => 'out', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sueldos administrativos', 'description' => 'Pago a personal administrativo', 'type' => 'out', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Servicios básicos', 'description' => 'Gastos en agua, luz, internet y teléfono', 'type' => 'out', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Mantenimiento de infraestructura', 'description' => 'Reparaciones y mejoras en el colegio', 'type' => 'out', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Compra de material educativo', 'description' => 'Gasto en libros, pizarras y otros recursos', 'type' => 'out', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Publicidad y marketing', 'description' => 'Promoción y difusión del colegio', 'type' => 'out', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Capacitación docente', 'description' => 'Cursos y actualizaciones para profesores', 'type' => 'out', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Compra de mobiliario', 'description' => 'Gasto en escritorios, sillas y otros', 'type' => 'out', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Seguridad y vigilancia', 'description' => 'Pago a personal de seguridad', 'type' => 'out', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Transporte escolar', 'description' => 'Pago por movilidad para alumnos y docentes', 'type' => 'out', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
