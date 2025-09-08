<?php

use App\Models\Student;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Socios\Deudores;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\BalanceController;
use App\Http\Livewire\Imports\ImportSocios;
use App\Http\Livewire\Imports\ImportStands;
use App\Http\Controllers\CategoryController;
use App\Http\Livewire\Account\CuentaDetalle;
use App\Http\Livewire\Stands\StandComponent;
use App\Http\Livewire\Balances\BalanceGlobal;
use App\Http\Livewire\Socios\SociosComponent;
use App\Http\Controllers\MovimientoController;
use App\Http\Livewire\Balances\BalanceEgresos;
use App\Http\Livewire\Reportes\ReporteSummary;
use App\Http\Livewire\Sistema\CompanySettings;
use App\Http\Controllers\UserProfileController;
use App\Http\Livewire\Balances\BalanceIngresos;
use App\Http\Livewire\Imports\ImportProvisions;
use App\Http\Livewire\Reportes\ReporteDetalles;
use App\Http\Livewire\Movimientos\VerMovimiento;
use App\Http\Livewire\Usuarios\UsuarioComponent;
use App\Http\Livewire\Bitacora\BitacoraComponent;
use App\Http\Livewire\Movimientos\ProvisionFijas;
use App\Http\Livewire\Students\StudentsComponent;
use App\Http\Livewire\Movimientos\CrearMovimiento;
use App\Http\Livewire\Usuarios\AsignarPermisoRole;
use App\Http\Livewire\Movimientos\EditarMovimiento;
use App\Http\Livewire\Socios\Detalles\SocioDetails;
use App\Http\Livewire\Movimientos\Gastos\NuevoGasto;
use App\Http\Livewire\Movimientos\ListadoMovimiento;
use App\Http\Livewire\Movimientos\ProvisionVariables;
use App\Http\Livewire\Socios\Detalles\StudentDetails;
use App\Http\Controllers\MultipleEportsViewController;
use App\Http\Livewire\ReporteCC5\RecibosMasivosExport;
use App\Http\Controllers\ReporteIngresoGastoController;
use App\Http\Livewire\ExportComponents\ExportConceptos;
use App\Http\Livewire\ReporteCC5\BuscarReciboComponent;
use App\Http\Livewire\ReporteCC5\ReporteIngresosGastos;
use App\Http\Livewire\CustomerProvider\CustomerProvider;
use App\Http\Livewire\Movimientos\Saldos\SaldoComponent;
use App\Http\Livewire\Movimientos\Futuro\FuturoComponent;
use App\Http\Livewire\ReporteCC5\ReporteIngresoDetallados;
use App\Http\Livewire\ExportComponents\ExportDeudaConceptos;
use App\Http\Livewire\CierreMoviemientos\SummaryCloseComponent;
use App\Http\Livewire\CierreMoviemientos\NewSummaryCloseComponent;
use App\Http\Controllers\ReciboOriginalCc5\ReciboPrintPdfController;
use App\Http\Livewire\Movimientos\ClaseMovimientos\MovimientoCliente;
use App\Http\Livewire\Movimientos\Transferencias\TransferenciaComponent;
use App\Http\Livewire\Movimientos\Provisiones\PorSocio\PorSocioComponent;
use App\Http\Livewire\Teachers\TeacherComponent;

Route::middleware(['user_status'])->group(function () {

    // Dashboard Route
    Route::group(['middleware' => ['can:dashboard.access']], function () {
        Route::get('/', [App\Http\Controllers\Admin\HomeController::class, 'dashboard'])->name('home');
    });

    // User Profile Routes
    Route::group(['middleware' => ['can:usuarios.show']], function () {
        Route::get('user_profile', [UserProfileController::class, 'index'])->name('user.profile');
        Route::get('user_profile/settings', [UserProfileController::class, 'index'])->name('user.settings');
        Route::get('user_profile/mi-cobranza', [UserProfileController::class, 'index'])->name('user.recipts');
        Route::get('user_profile/change_password', [UserProfileController::class, 'index'])->name('user.change_password');
        Route::post('user_profile/update_info/{id}', [UserProfileController::class, 'update'])->name('user.update_info');
        Route::post('user_profile/update_password', [UserProfileController::class, 'updatePassword'])->name('user.update_password');
    });

    // Users Management Routes
    Route::group(['middleware' => ['can:usuarios.index']], function () {
        Route::get('usuarios', UsuarioComponent::class)->name('usuarios');
        
        Route::group(['middleware' => ['can:usuarios.assign_permissions']], function () {
            Route::get('asignar-permiso-rol', AsignarPermisoRole::class)->name('asignar.permiso');
        });
    });

    // Customer Provider Routes
    Route::group(['middleware' => ['can:clientes.index']], function () {
        Route::get('cliente-proveedor', CustomerProvider::class)->name('cliente-proveedor');
        Route::get('datatable-cliente-proveedor', [CustomerProvider::class, 'datatable'])->name('data.cliente-proveedor');
    });

    // Balance Routes
    Route::group(['middleware' => ['can:saldos.index']], function () {
        Route::get('saldos', [SaldoController::class, 'index'])->name('saldos');
        Route::get('montos', SaldoComponent::class)->name('montos.index');
    });

    // Categories Routes
    Route::group(['middleware' => ['can:categorias.index']], function () {
        Route::get('categories', [CategoryController::class, 'index'])->name('categorias');
        
        Route::group(['middleware' => ['can:categorias.create']], function () {
            Route::get('categories/create', [CategoryController::class, 'create']);
            Route::post('categories/store',[CategoryController::class, 'store']);
        });
        
        Route::group(['middleware' => ['can:categorias.edit']], function () {
            Route::get('categories/edit/{id}', [CategoryController::class, 'edit']);
            Route::put('categories/edit/{id}', [CategoryController::class, 'update']);
        });
        
        Route::group(['middleware' => ['can:categorias.attributes']], function () {
            Route::get('categories/view_attr/{id}',[CategoryController::class, 'view_attr']);
            Route::post('categories/save_attr/{id}',[CategoryController::class, 'save_attr']);
            Route::get('categories/get_attr/{id}',[CategoryController::class, 'get_all']);
            Route::get('categories/eliminarattr/{id}',[CategoryController::class, 'destroyattr']);
        });

        Route::group(['middleware' => ['can:categorias.delete']], function () {
            Route::delete('categories/delete/{id}', [CategoryController::class, 'destroy']);
        });
    });

    // Account Routes
    Route::group(['middleware' => ['can:cuentas.index']], function () {
        Route::get('cuentas',[AccountController::class, 'index'])->name('account.index');
        
        Route::group(['middleware' => ['can:cuentas.create']], function () {
            Route::get('account/create', [AccountController::class, 'create'])->name('account.create');
            Route::post('account/save',[AccountController::class, 'store'])->name('account.save');
        });
        
        Route::group(['middleware' => ['can:cuentas.edit']], function () {
            Route::get('account/edit/{id}',[AccountController::class, 'edit'])->name('account.edit');
            Route::put('account/editar/{id}',[AccountController::class, 'update'])->name('account.update');
        });

        Route::group(['middleware' => ['can:cuentas.delete']], function () {
            Route::delete('account/eliminar/{id}',[AccountController::class, 'destroy'])->name('account.destroy');
        });

        Route::group(['middleware' => ['can:cuentas.show']], function () {
            Route::get('account/detalle/{id}',CuentaDetalle::class)->name('account.show');
        });
    });

    // Students Routes
    Route::group(['middleware' => ['can:socios.index']], function () {
        Route::get('students', StudentsComponent::class)->name('students.index');
        
        Route::group(['middleware' => ['can:socios.deudores']], function () {
            Route::get('students/deudores', Deudores::class)->name('students.deudores');
        });
    });

    Route::group(['middleware' => ['can:socios.show']], function () {
        Route::get('students/detalle/{id}', StudentDetails::class)->name('students.detalle');
    });
    
    Route::group(['middleware' => ['can:socios.reports']], function () {
        Route::get('students/reportePDF', [ExportController::class, 'reportePdfSocio'])->name('socio.reportePDF');
    });

    // Teachers Routes
    Route::group(['middleware' => ['can:docentes.index']], function () {
        Route::get('docentes', TeacherComponent::class)->name('teachers');
    });

    // Movements Routes
    Route::group(['middleware' => ['can:movimientos.index']], function () {
        Route::get('movimientos', ListadoMovimiento::class)->name('movimientos.listado');
        
        Route::group(['middleware' => ['can:movimientos.create']], function () {
            Route::get('movimientos/crear', CrearMovimiento::class)->name('movimientos.crear');
        });
        
        Route::group(['middleware' => ['can:movimientos.cliente']], function () {
            Route::get('movimientos/crear_por_cliente', MovimientoCliente::class)->name('movimientos.cliente');
        });
        
        Route::group(['middleware' => ['can:movimientos.proveedor']], function () {
            Route::get('movimientos/crear_gasto_proveedor', NuevoGasto::class)->name('movimientos.proveedor');
        });
        
        Route::group(['middleware' => ['can:movimientos.edit']], function () {
            Route::get('movimientos/editar/{id}', EditarMovimiento::class)->name('movimientos.editar');
        });

        Route::group(['middleware' => ['can:movimientos.show']], function () {
            Route::get('movimientos/ver/{id}', VerMovimiento::class)->name('movimientos.ver');
        });
    });

    // Transfer Routes
    Route::group(['middleware' => ['can:transferencia.index']], function () {
        Route::get('transferencias', TransferenciaComponent::class)->name('transferencias.create');
    });

    // Future Movements Routes
    Route::group(['middleware' => ['can:movimientos.futuros.index']], function () {
        Route::get('movimientos/futuros', FuturoComponent::class)->name('movimientosfuturos.index');
    });

    // Balance Category Routes
    Route::group(['middleware' => ['can:balance.index']], function () {
        Route::get('movimientos/balance', BalanceGlobal::class)->name('balance.index');
        
        Route::group(['middleware' => ['can:balance.ingresos']], function () {
            Route::get('balance/ingresos', BalanceIngresos::class)->name('balance.ingresos');
        });
        
        Route::group(['middleware' => ['can:balance.egresos']], function () {
            Route::get('balance/gastos', BalanceEgresos::class)->name('balance.gastos');
        });
    });

    // Bitacora Routes
    Route::group(['middleware' => ['can:bitacora.index']], function () {
        Route::get('bitacora/historial', BitacoraComponent::class)->name('bitacora.index');
    });

    // Stands Routes
    Route::group(['middleware' => ['can:stands.index']], function () {
        Route::get('stands/listado', StandComponent::class)->name('stands.index');
    });

    // Provisions Routes
    Route::group(['middleware' => ['can:provisiones.index']], function () {
        Route::group(['middleware' => ['can:provisiones.fijas']], function () {
            Route::get('movimientos/provision-fija', ProvisionFijas::class)->name('provision.fija');
        });
        
        Route::group(['middleware' => ['can:provisiones.variables']], function () {
            Route::get('movimientos/provision-variable', ProvisionVariables::class)->name('provision.variable');
        });
        
        Route::group(['middleware' => ['can:provisiones.por_socio']], function () {
            Route::get('movimientos/provision-socio', PorSocioComponent::class)->name('provision.socio');
        });
    });

    // Closures Routes
    Route::group(['middleware' => ['can:cierres.index']], function () {
        Route::get('cierres/listado', SummaryCloseComponent::class)->name('closes.index');
        
        Route::group(['middleware' => ['can:cierres.create']], function () {
            Route::get('cierres/nuevo', NewSummaryCloseComponent::class)->name('closes.create');
        });
    });

    // Import Routes
    Route::group(['middleware' => ['can:importar.index']], function () {
        Route::group(['middleware' => ['can:importar.socios']], function () {
            Route::get('import/socios', ImportSocios::class)->name('import.socios');
        });
        
        Route::group(['middleware' => ['can:importar.stands']], function () {
            Route::get('import/stands', ImportStands::class)->name('import.stands');
        });
        
        Route::group(['middleware' => ['can:importar.provisiones']], function () {
            Route::get('import/details', ImportProvisions::class)->name('import.details');
        });
    });

    // Reports Routes
    Route::group(['middleware' => ['can:reportes.index']], function () {
        Route::group(['middleware' => ['can:reportes.detalles']], function () {
            Route::get('reportes/detalles', ReporteDetalles::class)->name('reportes.detalles');
        });
        
        Route::group(['middleware' => ['can:reportes.general']], function () {
            Route::get('reportes/general', ReporteSummary::class)->name('reportes.general');
        });
        
        Route::group(['middleware' => ['can:reportes.conceptos']], function () {
            Route::get('reportes/conceptos', ExportConceptos::class)->name('reportes.conceptos.view');
        });
        
        Route::group(['middleware' => ['can:reportes.conceptos_deuda']], function () {
            Route::get('reportes/conceptos/deudas', ExportDeudaConceptos::class)->name('reportes.conceptos_deuda.view');
        });
        
        // CC5 Reports
        Route::group(['middleware' => ['can:reportes.ingresos_gastos']], function () {
            Route::get('reporte/rpt/ingreso-gasto', ReporteIngresosGastos::class)->name('reporte.ingreso.gasto');
            Route::get('reporte/rpt/pdf/', [ReporteIngresoGastoController::class, 'reporteIngresosPdf'])->name('reporte.ingreso.pdf');
        });
        
        Route::group(['middleware' => ['can:reportes.ingresos_detallados']], function () {
            Route::get('reporte/rpt/pdf-detallado/', ReporteIngresoDetallados::class)->name('reporte.ingreso.pdf-detallado');
            Route::get('reporte/rpt/pdf-get/', [ReporteIngresoGastoController::class, 'reporteIngresosDetallePdf'])->name('reporte.ingreso.pdf-get');
        });
        
        Route::group(['middleware' => ['can:reportes.buscar_recibo']], function () {
            Route::get('reporte/rpt/buscar-recibo/', BuscarReciboComponent::class)->name('reporte.ingreso.buscar-recibo');
        });
        
        Route::group(['middleware' => ['can:reportes.recibos_masivos']], function () {
            Route::get('reporte/rpt/recibo-multiple/', RecibosMasivosExport::class)->name('reporte.ingreso.recibos-masivos');
        });
    });

    // Export Routes
    Route::group(['middleware' => ['can:exportar.index']], function () {
        Route::group(['middleware' => ['can:exportar.pdf']], function () {
            Route::get('export/pdf', [ReportController::class, 'reportePdfDetalles'])->name('export.pdf');
        });
        
        Route::group(['middleware' => ['can:exportar.excel']], function () {
            Route::get('export/excel', [ReportController::class, 'reporteExcelDetalles'])->name('export.excel');
        });
        
        Route::group(['middleware' => ['can:exportar.provisiones']], function () {
            Route::get('export/excel/provisiones', [ReportController::class, 'reporteExcelDetallesVariables'])->name('export.excel.provisiones');
        });
        
        Route::group(['middleware' => ['can:exportar.socios']], function () {
            Route::get('export/socios', [ReportController::class, 'reporteSocios'])->name('reportes.socios');
        });
        
        Route::group(['middleware' => ['can:exportar.stands']], function () {
            Route::get('export/stands', [ReportController::class, 'reporteStands'])->name('reportes.stands');
            Route::get('export/stands/excel', [MultipleEportsViewController::class, 'standExcel'])->name('reportes.stands.excel');
        });
        
        Route::group(['middleware' => ['can:exportar.multiples']], function () {
            Route::get('export/multiples', [MultipleEportsViewController::class, 'index'])->name('reportes.multiples');
        });
    });

    // Receipt Routes
    Route::group(['middleware' => ['can:recibos.index']], function () {
        Route::group(['middleware' => ['can:recibos.download']], function () {
            Route::get('movimientos/descargar/{id}', [ExportController::class, 'downloadReceipt'])->name('movimientos.descargar.recibo');
        });
        
        Route::group(['middleware' => ['can:recibos.ticket']], function () {
            Route::get('movimientos/ticket/{id}', [ExportController::class, 'printReceiptTicket'])->name('movimientos.ticket.recibo');
        });
        
        Route::group(['middleware' => ['can:recibos.a4']], function () {
            Route::get('movimientos/a4/{id}', [ExportController::class, 'printReceiptA4'])->name('movimientos.a4.recibo');
        });
        
        Route::group(['middleware' => ['can:recibos.masivos']], function () {
            Route::get('movimientos/rpts/masivosPdf', [ReciboPrintPdfController::class, 'recibosMasivos'])->name('movimientos.cc5.recibosMas');
        });
        
        Route::group(['middleware' => ['can:recibos.cc5']], function () {
            Route::get('movimientos/rpt/{id}', [ReciboPrintPdfController::class, 'recibo'])->name('movimientos.cc5.recibo');
        });
        
        Route::group(['middleware' => ['can:recibos.print']], function () {
            Route::get('movimientos/reportePDF', [ExportController::class, 'reportePdfMovimiento'])->name('movimientos.reportePDF');
            Route::get('movimientos/conceptosPDF', [ExportController::class, 'reportePdfConceptos'])->name('movimientos.conceptosPDF');
        });
    });

    // System Settings Routes
    Route::group(['middleware' => ['can:configuracion.index']], function () {
        Route::get('company/settings', CompanySettings::class)->name('company.settings');
    });

});