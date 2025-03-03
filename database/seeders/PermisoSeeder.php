<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //saldos
        Permission::create([
            'name' => 'saldos.index',
        ]);
        Permission::create([
            'name' => 'saldos.show',
        ]);
        //categorias
        Permission::create([
            'name' => 'categorias.index',
        ]);
        Permission::create([
            'name' => 'categorias.show',
        ]);
        Permission::create([
            'name' => 'categorias.edit',
        ]);
        Permission::create([
            'name' => 'categorias.delete',
        ]);
        //movimientos
        Permission::create([
            'name' => 'movimientos.index',
        ]);
        Permission::create([
            'name' => 'movimientos.show',
        ]);
        Permission::create([
            'name' => 'movimientos.edit',
        ]);
        Permission::create([
            'name' => 'movimientos.delete',
        ]);
        //balance
        Permission::create([
            'name' => 'balance.index',
        ]);
        //cuentas
        Permission::create([
            'name' => 'cuentas.index',
        ]);
        Permission::create([
            'name' => 'cuentas.show',
        ]);
        Permission::create([
            'name' => 'cuentas.edit',
        ]);
        Permission::create([
            'name' => 'cuentas.delete',
        ]);
        //socios
        Permission::create([
            'name' => 'socios.index',
        ]);
        Permission::create([
            'name' => 'socios.show',
        ]);
        Permission::create([
            'name' => 'socios.edit',
        ]);
        Permission::create([
            'name' => 'socios.delete',
        ]);
        //transferencia
        Permission::create([
            'name' => 'transferencia.index',
        ]);
        //bitacora
        Permission::create([
            'name' => 'bitacora.index',
        ]);
        //usuarios
        Permission::create([
            'name' => 'usuarios.index',
        ]);
        Permission::create([
            'name' => 'usuarios.show',
        ]);
        Permission::create([
            'name' => 'usuarios.edit',
        ]);
        Permission::create([
            'name' => 'usuarios.delete',
        ]);
        //clientes
        Permission::create([
            'name' => 'clientes.index',
        ]);
        Permission::create([
            'name' => 'clientes.show',
        ]);
        Permission::create([
            'name' => 'clientes.edit',
        ]);
        Permission::create([
            'name' => 'clientes.delete',
        ]);
        Permission::create([
            'name' => 'recibos.edit',
        ]);
    }
}
