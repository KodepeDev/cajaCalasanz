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

    Route::get('/', [App\Http\Controllers\Admin\HomeController::class, 'dashboard'])->name('home');
    // Route::resource('categories', CategoryController::class);
    Route::group(['middleware' => ['can:usuarios.show']], function () {
        Route::get('user_profile', [UserProfileController::class, 'index'])->name('user.profile');
        Route::get('user_profile/settings', [UserProfileController::class, 'index'])->name('user.settings');
        Route::get('user_profile/mi-cobranza', [UserProfileController::class, 'index'])->name('user.recipts');
        Route::get('user_profile/change_password', [UserProfileController::class, 'index'])->name('user.change_password');
        Route::post('user_profile/update_info/{id}', [UserProfileController::class, 'update'])->name('user.update_info');
        Route::post('user_profile/update_password', [UserProfileController::class, 'updatePassword'])->name('user.update_password');
    });

    Route::group(['middleware' => ['can:usuarios.index']], function () {
        //
        Route::get('usuarios', UsuarioComponent::class)->name('usuarios');
        Route::get('asignar-permiso-rol', AsignarPermisoRole::class)->name('asignar.permiso');
    });
    Route::group(['middleware' => ['can:clientes.index']], function () {
        Route::get('cliente-proveedor', CustomerProvider::class)->name('cliente-proveedor');
        Route::get('datatable-cliente-proveedor', [CustomerProvider::class, 'datatable'])->name('data.cliente-proveedor');
    });
    Route::group(['middleware' => ['can:saldos.index']], function () {
        Route::get('saldos', [SaldoController::class, 'index'])->name('saldos');
    });
    //listar categories                                                 /
    Route::group(['middleware' => ['can:categorias.index']], function () {
    Route::get('categories', [CategoryController::class, 'index'])->name('categorias');
    });
    Route::group(['middleware' => ['can:categorias.index','can:categorias.edit']], function () {
    //agregar categories
    Route::get('categories/create', [CategoryController::class, 'create']);
    Route::post('categories/store',[CategoryController::class, 'store']);
    });
    //editar categories
    Route::group(['middleware' => ['can:categorias.index','can:categorias.edit']], function () {
        Route::get('categories/edit/{id}', [CategoryController::class, 'edit']);
        Route::put('categories/edit/{id}', [CategoryController::class, 'update']);
    });
    //eliminar categories
    Route::group(['middleware' => ['can:categorias.index','can:categorias.delete']], function () {
    Route::delete('categories/delete/{id}', [CategoryController::class, 'destroy']);
    });
    Route::group(['middleware' => ['can:categorias.index','can:categorias.edit']], function () {
    Route::get('categories/view_attr/{id}',[CategoryController::class, 'view_attr']);
    Route::post('categories/save_attr/{id}',[CategoryController::class, 'save_attr']);
    Route::get('categories/get_attr/{id}',[CategoryController::class, 'get_all']);
    Route::get('categories/eliminarattr/{id}',[CategoryController::class, 'destroyattr']);
    });


    ///////////////////////////////
    ///////// account ////////////
    ////////////////////////////////////////////////////////////////////////
    Route::group(['middleware' => ['can:cuentas.index']], function () {
    //listar account
    Route::get('cuentas',[AccountController::class, 'index'])->name('account.index');
    });
    Route::group(['middleware' => ['can:cuentas.edit']], function () {
    //agregar account
    Route::get('account/create', [AccountController::class, 'create'])->name('account.create');
    Route::post('account/save',[AccountController::class, 'store'])->name('account.save');
    //editar account
    Route::get('account/edit/{id}',[AccountController::class, 'edit'])->name('account.edit');

    Route::put('account/editar/{id}',[AccountController::class, 'update'])->name('account.update');
    });
    Route::group(['middleware' => ['can:cuentas.delete']], function () {
        //eliminar account
        Route::delete('account/eliminar/{id}',[AccountController::class, 'destroy'])->name('account.destroy');
    });
    //detalle
    Route::group(['middleware' => ['can:cuentas.index','can:cuentas.show']], function () {
        Route::get('account/detalle/{id}',CuentaDetalle::class)->name('account.show');
    });
    ///////////////////////////////
    ///////// estudiantes ////////////
    ////////////////////////////////////////////////////////////////////////
    Route::group(['middleware' => ['can:socios.index']], function () {
        Route::get('students', StudentsComponent::class)->name('students.index');
        Route::get('students/deudores', Deudores::class)->name('students.deudores');
    });
    Route::group(['middleware' => ['can:socios.show']], function () {
        Route::get('students/detalle/{id}', StudentDetails::class)->name('students.detalle');
        Route::get('students/reportePDF', [ExportController::class, 'reportePdfSocio'])->name('socio.reportePDF');
    });
    ///////////////////////////////
    ///////// Docentes ////////////
    Route::get('docentes', TeacherComponent::class)->name('teachers');
    ///////////////////////////////
    ///////// Saldos - montos totales ////////////
    ////////////////////////////////////////////////////////////////////////
    Route::group(['middleware' => ['can:saldos.index']], function () {
        Route::get('montos', SaldoComponent::class)->name('montos.index');
    });
    ///////////////////////////////
    ///////// summary ////////////
    ////////////////////////////////////////////////////////////////////////

    //listar attached
    // Route::get('movimientos',[MovimientoController::class, 'index']);
    //agregar attached
    Route::group(['middleware' => ['can:movimientos.edit']], function () {
        Route::get('summary/create',[MovimientoController::class, 'create']);
        Route::post('summary/save',[MovimientoController::class, 'store']);
        Route::get('summary/edit/{id}',[MovimientoController::class, 'edit']);
        Route::put('summary/editar/{id}',[MovimientoController::class, 'update']);
    });
    Route::group(['middleware' => ['can:movimientos.index']], function () {
        Route::get('movimientos', ListadoMovimiento::class)->name('movimientos.listado');
    });
    Route::group(['middleware' => ['can:movimientos.edit']], function () {
        Route::get('movimientos/crear', CrearMovimiento::class)->name('movimientos.crear');
        Route::get('movimientos/crear_por_cliente', MovimientoCliente::class)->name('movimientos.cliente');
        Route::get('movimientos/crear_gasto_proveedor', NuevoGasto::class)->name('movimientos.proveedor');
        Route::get('movimientos/editar/{id}', EditarMovimiento::class)->name('movimientos.editar');
    });
    Route::group(['middleware' => ['can:movimientos.show']], function () {
        Route::get('movimientos/ver/{id}', VerMovimiento::class)->name('movimientos.ver');
    });

    Route::get('movimientos/descargar/{id}', [ExportController::class, 'downloadReceipt'])->name('movimientos.descargar.recibo');
    Route::get('movimientos/ticket/{id}', [ExportController::class, 'printReceiptTicket'])->name('movimientos.ticket.recibo');
    Route::get('movimientos/a4/{id}', [ExportController::class, 'printReceiptA4'])->name('movimientos.a4.recibo');
    Route::get('movimientos/rpts/masivosPdf', [ReciboPrintPdfController::class, 'recibosMasivos'])->name('movimientos.cc5.recibosMas');
    Route::get('movimientos/rpt/{id}', [ReciboPrintPdfController::class, 'recibo'])->name('movimientos.cc5.recibo');
    Route::get('movimientos/reportePDF', [ExportController::class, 'reportePdfMovimiento'])->name('movimientos.reportePDF');
    Route::get('movimientos/conceptosPDF', [ExportController::class, 'reportePdfConceptos'])->name('movimientos.conceptosPDF');

    ///////////////////////////////
    ///////// transferencias ////////////
    ////////////////////////////////////////////////////////////////////////
    Route::group(['middleware' => ['can:transferencia.index']], function () {
        Route::get('transferencias', TransferenciaComponent::class)->name('transferencias.create');
    });
    ///////////////////////////////
    ///////// Movimientos Futuros ////////////
    ////////////////////////////////////////////////////////////////////////

    Route::get('movimientos/futuros', FuturoComponent::class)->name('movimientosfuturos.index');


    ///////////////////////////////
    ///////// Balance de categorias////////////
    ////////////////////////////////////////////////////////////////////////
    Route::group(['middleware' => ['can:balance.index']], function () {
    // Route::get('movimientos/balance', [BalanceController::class, 'index'])->name('balance.index');
    Route::get('movimientos/balance', BalanceGlobal::class)->name('balance.index');

    Route::get('balance/ingresos', BalanceIngresos::class)->name('balance.ingresos');
    Route::get('balance/gastos', BalanceEgresos::class)->name('balance.gastos');
    });

    Route::group(['middleware' => ['can:bitacora.index']], function () {
    Route::get('bitacora/historial', BitacoraComponent::class)->name('bitacora.index');
    });

    //apis
    // Route::get('datatable/personas', [RouteController::class, 'personas'])->name('datatable.personas');


    Route::get('stands/listado', StandComponent::class)->name('stands.index');
    Route::get('movimientos/provision-fija', ProvisionFijas::class)->name('provision.fija');
    Route::get('movimientos/provision-variable', ProvisionVariables::class)->name('provision.variable');
    Route::get('movimientos/provision-socio', PorSocioComponent::class)->name('provision.socio');

    ///////////////////////////////
    ///////// Balance de categorias////////////
    ////////////////////////////////////////////////////////////////////////

    Route::get('cierres/listado', SummaryCloseComponent::class)->name('closes.index');
    Route::get('cierres/nuevo', NewSummaryCloseComponent::class)->name('closes.create');

    ///Modulo de Importaciones
    Route::get('import/socios', ImportSocios::class)->name('import.socios');
    Route::get('import/stands', ImportStands::class)->name('import.stands');
    Route::get('import/details', ImportProvisions::class)->name('import.details');

    ///Modulo de Reportes

    Route::get('reportes/detalles', ReporteDetalles::class)->name('reportes.detalles');
    Route::get('reportes/general', ReporteSummary::class)->name('reportes.general');
    Route::get('reportes/conceptos', ExportConceptos::class)->name('reportes.conceptos.view');
    Route::get('reportes/conceptos/deudas', ExportDeudaConceptos::class)->name('reportes.conceptos_deuda.view');

    //reportes cc5

    Route::get('reporte/rpt/ingreso-gasto', ReporteIngresosGastos::class)->name('reporte.ingreso.gasto');
    Route::get('reporte/rpt/pdf/', [ReporteIngresoGastoController::class, 'reporteIngresosPdf'])->name('reporte.ingreso.pdf');
    Route::get('reporte/rpt/pdf-detallado/', ReporteIngresoDetallados::class)->name('reporte.ingreso.pdf-detallado');
    Route::get('reporte/rpt/pdf-get/', [ReporteIngresoGastoController::class, 'reporteIngresosDetallePdf'])->name('reporte.ingreso.pdf-get');
    Route::get('reporte/rpt/buscar-recibo/', BuscarReciboComponent::class)->name('reporte.ingreso.buscar-recibo');
    Route::get('reporte/rpt/recibo-multiple/', RecibosMasivosExport::class)->name('reporte.ingreso.recibos-masivos');

    Route::get('export/pdf', [ReportController::class, 'reportePdfDetalles'])->name('export.pdf');
    Route::get('export/excel', [ReportController::class, 'reporteExcelDetalles'])->name('export.excel');
    Route::get('export/excel/provisiones', [ReportController::class, 'reporteExcelDetallesVariables'])->name('export.excel.provisiones');

    Route::get('export/socios', [ReportController::class, 'reporteSocios'])->name('reportes.socios');
    Route::get('export/stands', [ReportController::class, 'reporteStands'])->name('reportes.stands');
    Route::get('export/stands/excel', [MultipleEportsViewController::class, 'standExcel'])->name('reportes.stands.excel');
    Route::get('export/multiples', [MultipleEportsViewController::class, 'index'])->name('reportes.multiples');

    Route::get('movimientos/deudas/{stand}', [MultipleEportsViewController::class, 'reporteDeudaPdf'])->name('reportes.deudas');
    Route::get('movimientos/conceptos/', [MultipleEportsViewController::class, 'reporteDeudaPdf'])->name('reportes.conceptos');


    // CONFIGURACION DEL SISTEMA

    Route::get('company/settings', CompanySettings::class)->name('company.settings');

});
