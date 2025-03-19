<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'Kodepe',
            'last_name' => 'Admin',
            'status' => true,
            'document' => '20202020',
            'email' => 'adminsistemas@kodepe.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('12345678')
        ])->syncRoles(1);

        User::create([
            'first_name' => 'ADMIN',
            'last_name' => 'ADMIN',
            'status' => true,
            'document' => '10101010',
            'email' => 'admin@admin.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('10101010')
        ])->syncRoles(3);

        Setting::create([
            'name' => 'Soles',
            'value' => 'S/.',
        ]);
    }
}
