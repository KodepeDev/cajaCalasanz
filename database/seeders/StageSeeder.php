<?php

namespace Database\Seeders;

use App\Models\CuoteType;
use App\Models\Stage;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        CuoteType::create([
            'name' => 'Matrícula 2025',
        ]);
        CuoteType::create([
            'name' => 'Mensualidad 2025',
        ]);
    }
}
