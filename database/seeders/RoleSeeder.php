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
        // Get all permissions
        $allPermisos = Permission::pluck('id')->toArray();

        // SUPERADMINISTRADOR - Has all permissions
        Role::create([
            'name' => 'SUPERADMINISTRADOR'
        ])->syncPermissions($allPermisos);

        // ADMINISTRADOR - Has most permissions except some critical ones
        $adminPermisos = Permission::whereNotIn('name', [
            'configuracion.index',
            'usuarios.delete',
            'bitacora.index',
            'importar.index'
        ])->pluck('id')->toArray();

        Role::create([
            'name' => 'ADMINISTRADOR'
        ])->syncPermissions($adminPermisos);

        // CONTADOR - Financial and reporting access
        $contadorPermisos = Permission::whereIn('name', [
            'saldos.index', 'saldos.show',
            'movimientos.index', 'movimientos.show', 'movimientos.edit',
            'balance.index',
            'cuentas.index', 'cuentas.show',
            'categorias.index', 'categorias.show',
            'reportes.index',
            'recibos.index', 'recibos.edit',
            'exportar.index',
            'provisiones.index'
        ])->pluck('id')->toArray();

        Role::create([
            'name' => 'CONTADOR'
        ])->syncPermissions($contadorPermisos);

        // USUARIO - Basic access
        $userPermisos = Permission::whereIn('name', [
            'saldos.index',
            'movimientos.index', 'movimientos.show',
            'balance.index',
            'cuentas.show',
            'categorias.index',
            'recibos.index'
        ])->pluck('id')->toArray();

        Role::create([
            'name' => 'USUARIO'
        ])->syncPermissions($userPermisos);
    }
}
