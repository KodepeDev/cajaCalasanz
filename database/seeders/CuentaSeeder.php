<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CuentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('accounts')->insert([
            [
                'account_name' => 'CAJA GENERAL',
                'add_serie' => 'I001',
                'out_serie' => 'G001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'account_name' => 'CAJA CHICA',
                'add_serie' => 'I002',
                'out_serie' => 'G002',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'account_name' => 'CAJA TRANSFERENCIAS',
                'add_serie' => 'I003',
                'out_serie' => 'G003',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
