<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        Account::create([
            'account_name' => 'CAJA GENERAL',
            'add_serie' => 'I001',
            'out_serie' => 'G001',
        ]);
        Account::create([
            'account_name' => 'CAJA CHICA',
            'add_serie' => 'I002',
            'out_serie' => 'G002',
        ]);
        Account::create([
            'account_name' => 'CAJA TRANSFERENCIAS',
            'add_serie' => 'I003',
            'out_serie' => 'G003',
        ]);
    }
}
