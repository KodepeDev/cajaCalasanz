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

        // ADMINISTRADOR - Has most permissions except some critical system ones
        $adminPermisos = Permission::whereNotIn('name', [
            'admin.full_access',
            'usuarios.delete',
            'configuracion.edit'
        ])->pluck('id')->toArray();

        Role::create([
            'name' => 'ADMINISTRADOR'
        ])->syncPermissions($adminPermisos);

        // CONTADOR - Financial management and comprehensive reporting
        $contadorPermisos = Permission::whereIn('name', [
            // Dashboard y perfil
            'dashboard.access',
            'user.profile', 'user.settings', 'user.receipts',
            
            // Gestión financiera completa
            'saldos.index', 'saldos.show', 'saldos.edit',
            'movimientos.index', 'movimientos.show', 'movimientos.create', 'movimientos.edit',
            'movimientos.cliente', 'movimientos.proveedor',
            'movimientos.futuros.index',
            
            // Balance y cuentas
            'balance.index', 'balance.ingresos', 'balance.egresos', 'balance.global',
            'cuentas.index', 'cuentas.show', 'cuentas.create', 'cuentas.edit',
            
            // Categorías y clasificaciones
            'categorias.index', 'categorias.show', 'categorias.create', 'categorias.edit',
            
            // Socios y docentes (consulta)
            'socios.index', 'socios.show', 'socios.deudores', 'socios.reports',
            'docentes.index',
            
            // Provisiones completas
            'provisiones.index', 'provisiones.create', 'provisiones.edit',
            'provisiones.fijas', 'provisiones.variables', 'provisiones.por_socio',
            
            // Transferencias
            'transferencia.index', 'transferencia.create',
            
            // Cierres
            'cierres.index', 'cierres.create', 'cierres.edit',
            
            // Reportes completos
            'reportes.index', 'reportes.detalles', 'reportes.general',
            'reportes.conceptos', 'reportes.conceptos_deuda',
            'reportes.ingresos_gastos', 'reportes.ingresos_detallados',
            'reportes.buscar_recibo',
            
            // Exportar
            'exportar.index', 'exportar.pdf', 'exportar.excel',
            'exportar.socios', 'exportar.provisiones',
            
            // Recibos
            'recibos.index', 'recibos.show', 'recibos.download',
            'recibos.print', 'recibos.a4', 'recibos.cc5',
            
            // Bitácora (solo lectura)
            'bitacora.index',
            
            // Gestión financiera avanzada
            'financial.management'
        ])->pluck('id')->toArray();

        Role::create([
            'name' => 'CONTADOR'
        ])->syncPermissions($contadorPermisos);

        // CAJERO - Operaciones de caja y recibos
        $cajeroPermisos = Permission::whereIn('name', [
            // Dashboard y perfil
            'dashboard.access',
            'user.profile', 'user.settings', 'user.receipts',
            
            // Movimientos básicos
            'movimientos.index', 'movimientos.show', 'movimientos.create',
            'movimientos.cliente',
            
            // Saldos (consulta)
            'saldos.index', 'saldos.show',
            
            // Balance (consulta)
            'balance.index',
            
            // Cuentas (consulta)
            'cuentas.index', 'cuentas.show',
            
            // Categorías (consulta)
            'categorias.index', 'categorias.show',
            
            // Socios
            'socios.index', 'socios.show', 'socios.deudores',
            
            // Recibos completos
            'recibos.index', 'recibos.show', 'recibos.download',
            'recibos.print', 'recibos.ticket', 'recibos.a4',
            
            // Reportes básicos
            'reportes.index', 'reportes.buscar_recibo',
            
            // Exportar básico
            'exportar.pdf'
        ])->pluck('id')->toArray();

        Role::create([
            'name' => 'CAJERO'
        ])->syncPermissions($cajeroPermisos);

        // OPERADOR - Gestión operativa sin finanzas críticas
        $operadorPermisos = Permission::whereIn('name', [
            // Dashboard y perfil
            'dashboard.access',
            'user.profile', 'user.settings',
            
            // Gestión de socios
            'socios.index', 'socios.show', 'socios.create', 'socios.edit',
            'socios.deudores',
            
            // Gestión de docentes
            'docentes.index', 'docentes.show', 'docentes.create', 'docentes.edit',
            
            // Gestión de stands
            'stands.index', 'stands.show', 'stands.create', 'stands.edit',
            
            // Clientes/Proveedores
            'clientes.index', 'clientes.show', 'clientes.create', 'clientes.edit',
            
            // Movimientos (consulta limitada)
            'movimientos.index', 'movimientos.show',
            
            // Importaciones
            'importar.index', 'importar.socios', 'importar.stands',
            
            // Reportes básicos
            'reportes.index', 'reportes.detalles',
            
            // Exportar
            'exportar.index', 'exportar.socios', 'exportar.stands'
        ])->pluck('id')->toArray();

        Role::create([
            'name' => 'OPERADOR'
        ])->syncPermissions($operadorPermisos);

        // ANALISTA - Reportes y análisis avanzados
        $analistaPermisos = Permission::whereIn('name', [
            // Dashboard y perfil
            'dashboard.access',
            'user.profile', 'user.settings',
            
            // Consultas financieras
            'saldos.index', 'saldos.show',
            'movimientos.index', 'movimientos.show',
            'movimientos.futuros.index',
            'balance.index', 'balance.ingresos', 'balance.egresos',
            
            // Cuentas y categorías (consulta)
            'cuentas.index', 'cuentas.show',
            'categorias.index', 'categorias.show',
            
            // Socios y docentes (consulta)
            'socios.index', 'socios.show', 'socios.deudores',
            'docentes.index',
            
            // Stands (consulta)
            'stands.index',
            
            // Provisiones (consulta)
            'provisiones.index',
            
            // Cierres (consulta)
            'cierres.index',
            
            // Reportes completos
            'reportes.index', 'reportes.detalles', 'reportes.general',
            'reportes.conceptos', 'reportes.conceptos_deuda',
            'reportes.ingresos_gastos', 'reportes.ingresos_detallados',
            'reportes.buscar_recibo', 'reportes.recibos_masivos',
            
            // Exportar completo
            'exportar.index', 'exportar.pdf', 'exportar.excel',
            'exportar.socios', 'exportar.stands', 'exportar.multiples',
            'exportar.provisiones',
            
            // Recibos (consulta)
            'recibos.index', 'recibos.show', 'recibos.download',
            
            // Bitácora
            'bitacora.index',
            
            // Reportes avanzados
            'reports.advanced'
        ])->pluck('id')->toArray();

        Role::create([
            'name' => 'ANALISTA'
        ])->syncPermissions($analistaPermisos);

        // USUARIO - Acceso básico y limitado
        $userPermisos = Permission::whereIn('name', [
            // Dashboard y perfil
            'dashboard.access',
            'user.profile', 'user.settings', 'user.receipts',
            
            // Consultas básicas
            'saldos.index',
            'movimientos.index', 'movimientos.show',
            'balance.index',
            
            // Cuentas (solo consulta)
            'cuentas.show',
            
            // Categorías (solo consulta)
            'categorias.index', 'categorias.show',
            
            // Socios (consulta básica)
            'socios.index', 'socios.show',
            
            // Recibos básicos
            'recibos.index', 'recibos.show'
        ])->pluck('id')->toArray();

        Role::create([
            'name' => 'USUARIO'
        ])->syncPermissions($userPermisos);

        // AUDITOR - Solo lectura y reportes (sin modificaciones)
        $auditorPermisos = Permission::whereIn('name', [
            // Dashboard y perfil
            'dashboard.access',
            'user.profile', 'user.settings',
            
            // Consultas completas (solo lectura)
            'saldos.index', 'saldos.show',
            'movimientos.index', 'movimientos.show',
            'movimientos.futuros.index',
            'balance.index', 'balance.ingresos', 'balance.egresos',
            'cuentas.index', 'cuentas.show',
            'categorias.index', 'categorias.show',
            'socios.index', 'socios.show', 'socios.deudores',
            'docentes.index',
            'stands.index',
            'provisiones.index',
            'cierres.index',
            
            // Transferencias (solo consulta)
            'transferencia.index',
            
            // Reportes completos
            'reportes.index', 'reportes.detalles', 'reportes.general',
            'reportes.conceptos', 'reportes.conceptos_deuda',
            'reportes.ingresos_gastos', 'reportes.ingresos_detallados',
            'reportes.buscar_recibo', 'reportes.recibos_masivos',
            
            // Exportar (solo consulta)
            'exportar.index', 'exportar.pdf', 'exportar.excel',
            'exportar.socios', 'exportar.stands', 'exportar.multiples',
            
            // Recibos (solo consulta)
            'recibos.index', 'recibos.show', 'recibos.download',
            
            // Bitácora completa
            'bitacora.index'
        ])->pluck('id')->toArray();

        Role::create([
            'name' => 'AUDITOR'
        ])->syncPermissions($auditorPermisos);
    }
}