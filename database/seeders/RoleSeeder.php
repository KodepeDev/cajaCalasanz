<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $permisos = Permission::pluck('id')->toArray();

        Role::create([
            'name' => 'SUPERADMINISTRADOR'
        ])->syncPermissions($permisos);

        Role::create([
            'name' => 'ADMINISTRADOR'
        ])->syncPermissions($permisos);
        Role::create([
            'name' => 'USUARIO'
        ]);
    }
}
