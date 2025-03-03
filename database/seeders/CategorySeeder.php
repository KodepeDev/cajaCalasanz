<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
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
        //
        Category::create([
            'name' => 'COBRO POR MANTENIMIENTO',
            'type' => 'add'
        ]);
        Category::create([
            'name' => 'COBRO POR ALQUILERES',
            'type' => 'add'
        ]);
        Category::create([
            'name' => 'COBRO POR ENEGÍA ELÉCTRICA',
            'type' => 'add'
        ]);
        Category::create([
            'name' => 'COBRO DE MULTAS',
            'type' => 'add'
        ]);
        Category::create([
            'name' => 'PAGO DE INTERNET',
            'type' => 'out'
        ]);
        Category::create([
            'name' => 'PAGO DE ENERGÍA ELÉCTRICA',
            'type' => 'out'
        ]);
        Category::create([
            'name' => 'PAGO DE AGUA',
            'type' => 'out'
        ]);
        Category::create([
            'name' => 'CONSUMOS',
            'type' => 'out'
        ]);
    }
}
