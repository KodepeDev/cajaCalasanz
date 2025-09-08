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
        Permission::create(['name' => 'usuarios.show']);
        Permission::create(['name' => 'user.profile']);
        Permission::create(['name' => 'user.settings']);
        Permission::create(['name' => 'user.receipts']);
        Permission::create(['name' => 'user.change_password']);
        
        // Usuarios (Users Management)
        Permission::create(['name' => 'usuarios.index']);
        Permission::create(['name' => 'usuarios.create']);
        Permission::create(['name' => 'usuarios.edit']);
        Permission::create(['name' => 'usuarios.delete']);
        Permission::create(['name' => 'usuarios.assign_permissions']);
        
        // Clientes/Proveedores (Customer Provider)
        Permission::create(['name' => 'clientes.index']);
        Permission::create(['name' => 'clientes.show']);
        Permission::create(['name' => 'clientes.create']);
        Permission::create(['name' => 'clientes.edit']);
        Permission::create(['name' => 'clientes.delete']);
        
        // Saldos (Balances)
        Permission::create(['name' => 'saldos.index']);
        Permission::create(['name' => 'saldos.show']);
        Permission::create(['name' => 'saldos.edit']);
        Permission::create(['name' => 'saldos.create']);
        
        // Categorías
        Permission::create(['name' => 'categorias.index']);
        Permission::create(['name' => 'categorias.show']);
        Permission::create(['name' => 'categorias.create']);
        Permission::create(['name' => 'categorias.edit']);
        Permission::create(['name' => 'categorias.delete']);
        Permission::create(['name' => 'categorias.attributes']);
        
        // Cuentas (Accounts)
        Permission::create(['name' => 'cuentas.index']);
        Permission::create(['name' => 'cuentas.show']);
        Permission::create(['name' => 'cuentas.create']);
        Permission::create(['name' => 'cuentas.edit']);
        Permission::create(['name' => 'cuentas.delete']);
        
        // Socios/Students
        Permission::create(['name' => 'socios.index']);
        Permission::create(['name' => 'socios.show']);
        Permission::create(['name' => 'socios.create']);
        Permission::create(['name' => 'socios.edit']);
        Permission::create(['name' => 'socios.delete']);
        Permission::create(['name' => 'socios.deudores']);
        Permission::create(['name' => 'socios.reports']);
        
        // Docentes (Teachers)
        Permission::create(['name' => 'docentes.index']);
        Permission::create(['name' => 'docentes.show']);
        Permission::create(['name' => 'docentes.create']);
        Permission::create(['name' => 'docentes.edit']);
        Permission::create(['name' => 'docentes.delete']);
        
        // Movimientos (Transactions)
        Permission::create(['name' => 'movimientos.index']);
        Permission::create(['name' => 'movimientos.show']);
        Permission::create(['name' => 'movimientos.create']);
        Permission::create(['name' => 'movimientos.edit']);
        Permission::create(['name' => 'movimientos.delete']);
        Permission::create(['name' => 'movimientos.cliente']);
        Permission::create(['name' => 'movimientos.proveedor']);
        Permission::create(['name' => 'movimientos.futuros.index']);
        
        // Transferencias
        Permission::create(['name' => 'transferencia.index']);
        Permission::create(['name' => 'transferencia.create']);
        Permission::create(['name' => 'transferencia.edit']);
        Permission::create(['name' => 'transferencia.delete']);
        
        // Balance (Financial Reports)
        Permission::create(['name' => 'balance.index']);
        Permission::create(['name' => 'balance.ingresos']);
        Permission::create(['name' => 'balance.egresos']);
        Permission::create(['name' => 'balance.global']);
        
        // Bitácora (Activity Log)
        Permission::create(['name' => 'bitacora.index']);
        Permission::create(['name' => 'bitacora.show']);
        
        // Stands
        Permission::create(['name' => 'stands.index']);
        Permission::create(['name' => 'stands.show']);
        Permission::create(['name' => 'stands.create']);
        Permission::create(['name' => 'stands.edit']);
        Permission::create(['name' => 'stands.delete']);
        
        // Provisiones (Provisions)
        Permission::create(['name' => 'provisiones.index']);
        Permission::create(['name' => 'provisiones.create']);
        Permission::create(['name' => 'provisiones.edit']);
        Permission::create(['name' => 'provisiones.delete']);
        Permission::create(['name' => 'provisiones.fijas']);
        Permission::create(['name' => 'provisiones.variables']);
        Permission::create(['name' => 'provisiones.por_socio']);
        
        // Cierres (Closures)
        Permission::create(['name' => 'cierres.index']);
        Permission::create(['name' => 'cierres.show']);
        Permission::create(['name' => 'cierres.create']);
        Permission::create(['name' => 'cierres.edit']);
        Permission::create(['name' => 'cierres.delete']);
        
        // Importar (Import)
        Permission::create(['name' => 'importar.index']);
        Permission::create(['name' => 'importar.socios']);
        Permission::create(['name' => 'importar.stands']);
        Permission::create(['name' => 'importar.provisiones']);
        
        // Reportes (Reports)
        Permission::create(['name' => 'reportes.index']);
        Permission::create(['name' => 'reportes.detalles']);
        Permission::create(['name' => 'reportes.general']);
        Permission::create(['name' => 'reportes.conceptos']);
        Permission::create(['name' => 'reportes.conceptos_deuda']);
        Permission::create(['name' => 'reportes.ingresos_gastos']);
        Permission::create(['name' => 'reportes.ingresos_detallados']);
        Permission::create(['name' => 'reportes.buscar_recibo']);
        Permission::create(['name' => 'reportes.recibos_masivos']);
        
        // Exportar (Export)
        Permission::create(['name' => 'exportar.index']);
        Permission::create(['name' => 'exportar.pdf']);
        Permission::create(['name' => 'exportar.excel']);
        Permission::create(['name' => 'exportar.socios']);
        Permission::create(['name' => 'exportar.stands']);
        Permission::create(['name' => 'exportar.multiples']);
        Permission::create(['name' => 'exportar.provisiones']);
        
        // Recibos (Receipts)
        Permission::create(['name' => 'recibos.index']);
        Permission::create(['name' => 'recibos.show']);
        Permission::create(['name' => 'recibos.download']);
        Permission::create(['name' => 'recibos.print']);
        Permission::create(['name' => 'recibos.ticket']);
        Permission::create(['name' => 'recibos.a4']);
        Permission::create(['name' => 'recibos.masivos']);
        Permission::create(['name' => 'recibos.cc5']);
        
        // Configuración del Sistema (System Settings)
        Permission::create(['name' => 'configuracion.index']);
        Permission::create(['name' => 'configuracion.edit']);
        Permission::create(['name' => 'configuracion.company']);
        
        // Permisos adicionales para funcionalidades específicas
        Permission::create(['name' => 'api.access']);
        Permission::create(['name' => 'admin.full_access']);
        Permission::create(['name' => 'reports.advanced']);
        Permission::create(['name' => 'financial.management']);
    }
}