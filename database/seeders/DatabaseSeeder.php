<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermisoSeeder::class);

        $this->call(RoleSeeder::class);

        $this->call(UsersSeeder::class);

        $this->call(CuentaSeeder::class);

        $this->call(CategorySeeder::class);

        // $this->call(CustomerSeeder::class);

        //$this->call(SummarySeeder::class);

        // $this->call(StageSeeder::class);
        // $this->call(StandSeeder::class);
    }
}
