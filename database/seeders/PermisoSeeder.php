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
        // Dashboard/Admin permissions
        Permission::create(['name' => 'dashboard.access']);
        
        // Saldos (Balances)
        Permission::create(['name' => 'saldos.index']);
        Permission::create(['name' => 'saldos.show']);
        Permission::create(['name' => 'saldos.create']);
        Permission::create(['name' => 'saldos.edit']);
        Permission::create(['name' => 'saldos.delete']);

        // Categorías
        Permission::create(['name' => 'categorias.index']);
        Permission::create(['name' => 'categorias.show']);
        Permission::create(['name' => 'categorias.create']);
        Permission::create(['name' => 'categorias.edit']);
        Permission::create(['name' => 'categorias.delete']);

        // Movimientos (Transactions)
        Permission::create(['name' => 'movimientos.index']);
        Permission::create(['name' => 'movimientos.show']);
        Permission::create(['name' => 'movimientos.create']);
        Permission::create(['name' => 'movimientos.edit']);
        Permission::create(['name' => 'movimientos.delete']);
        Permission::create(['name' => 'movimientos.export']);

        // Balance (Financial Reports)
        Permission::create(['name' => 'balance.index']);
        Permission::create(['name' => 'balance.show']);
        Permission::create(['name' => 'balance.export']);

        // Cuentas (Accounts)
        Permission::create(['name' => 'cuentas.index']);
        Permission::create(['name' => 'cuentas.show']);
        Permission::create(['name' => 'cuentas.create']);
        Permission::create(['name' => 'cuentas.edit']);
        Permission::create(['name' => 'cuentas.delete']);

        // Estudiantes (Students)
        Permission::create(['name' => 'estudiantes.index']);
        Permission::create(['name' => 'estudiantes.show']);
        Permission::create(['name' => 'estudiantes.create']);
        Permission::create(['name' => 'estudiantes.edit']);
        Permission::create(['name' => 'estudiantes.delete']);

        // Transferencias
        Permission::create(['name' => 'transferencia.index']);
        Permission::create(['name' => 'transferencia.create']);
        Permission::create(['name' => 'transferencia.show']);

        // Bitácora (Activity Log)
        Permission::create(['name' => 'bitacora.index']);
        Permission::create(['name' => 'bitacora.show']);
        Permission::create(['name' => 'bitacora.export']);

        // Usuarios (Users)
        Permission::create(['name' => 'usuarios.index']);
        Permission::create(['name' => 'usuarios.show']);
        Permission::create(['name' => 'usuarios.create']);
        Permission::create(['name' => 'usuarios.edit']);
        Permission::create(['name' => 'usuarios.delete']);

        // Clientes (Clients)
        Permission::create(['name' => 'clientes.index']);
        Permission::create(['name' => 'clientes.show']);
        Permission::create(['name' => 'clientes.create']);
        Permission::create(['name' => 'clientes.edit']);
        Permission::create(['name' => 'clientes.delete']);

        // Recibos (Receipts)
        Permission::create(['name' => 'recibos.index']);
        Permission::create(['name' => 'recibos.show']);
        Permission::create(['name' => 'recibos.create']);
        Permission::create(['name' => 'recibos.edit']);
        Permission::create(['name' => 'recibos.delete']);
        Permission::create(['name' => 'recibos.print']);
    }
}
