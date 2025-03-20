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
        
        // User Profile permissions
        Permission::create(['name' => 'user.profile']);
        Permission::create(['name' => 'user.settings']);
        Permission::create(['name' => 'user.receipts']);
        Permission::create(['name' => 'user.change_password']);

        // Saldos (Balances)
        Permission::create(['name' => 'saldos.index']);
        Permission::create(['name' => 'saldos.show']);

        // Categorías
        Permission::create(['name' => 'categorias.index']);
        Permission::create(['name' => 'categorias.show']);
        Permission::create(['name' => 'categorias.edit']);
        Permission::create(['name' => 'categorias.delete']);

        // Movimientos (Transactions)
        Permission::create(['name' => 'movimientos.index']);
        Permission::create(['name' => 'movimientos.show']);
        Permission::create(['name' => 'movimientos.edit']);
        Permission::create(['name' => 'movimientos.futuros.index']);

        // Balance (Financial Reports)
        Permission::create(['name' => 'balance.index']);
        Permission::create(['name' => 'balance.ingresos']);
        Permission::create(['name' => 'balance.gastos']);

        // Cuentas (Accounts)
        Permission::create(['name' => 'cuentas.index']);
        Permission::create(['name' => 'cuentas.show']);
        Permission::create(['name' => 'cuentas.edit']);
        Permission::create(['name' => 'cuentas.delete']);

        // Socios/Students
        Permission::create(['name' => 'socios.index']);
        Permission::create(['name' => 'socios.show']);

        // Docentes (Teachers)
        Permission::create(['name' => 'docentes.index']);

        // Transferencias
        Permission::create(['name' => 'transferencia.index']);

        // Bitácora (Activity Log)
        Permission::create(['name' => 'bitacora.index']);

        // Usuarios (Users)
        Permission::create(['name' => 'usuarios.index']);
        Permission::create(['name' => 'usuarios.show']);
        Permission::create(['name' => 'usuarios.edit']);
        Permission::create(['name' => 'usuarios.delete']);

        // Clientes (Clients)
        Permission::create(['name' => 'clientes.index']);

        // Stands
        Permission::create(['name' => 'stands.index']);

        // Provisiones (Provisions)
        Permission::create(['name' => 'provisiones.index']);

        // Cierres (Closures)
        Permission::create(['name' => 'cierres.index']);

        // Importar (Import)
        Permission::create(['name' => 'importar.index']);

        // Reportes (Reports)
        Permission::create(['name' => 'reportes.index']);

        // Exportar (Export)
        Permission::create(['name' => 'exportar.index']);

        // Recibos (Receipts)
        Permission::create(['name' => 'recibos.index']);
        Permission::create(['name' => 'recibos.edit']);

        // System Settings
        Permission::create(['name' => 'configuracion.index']);
    }
}
