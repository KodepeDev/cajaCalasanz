@extends('adminlte::page')
@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="m-0 text-dark font-weight-bold">
                <i class="fas fa-tachometer-alt mr-2 text-primary"></i>Panel de Inicio
            </h1>
            <small class="text-muted">Resumen general del sistema</small>
        </div>
        <span class="badge badge-secondary py-2 px-3" style="font-size:.8rem;font-weight:500;">
            <i class="far fa-calendar-alt mr-1"></i>
            {{ \Carbon\Carbon::now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
        </span>
    </div>
@stop

@section('content')

{{-- ── Acciones Rápidas ────────────────────────────────────────── --}}
<div class="row mb-3">
    <div class="col-12">
        @can('movimientos.crear')
        <a href="{{ route('movimientos.crear') }}" class="btn btn-success btn-sm mr-1">
            <i class="fas fa-plus mr-1"></i> Nuevo Ingreso
        </a>
        @endcan
        @can('movimientos.proveedor')
        <a href="{{ route('movimientos.proveedor') }}" class="btn btn-danger btn-sm mr-1">
            <i class="fas fa-minus mr-1"></i> Nuevo Gasto
        </a>
        @endcan
        <a href="{{ route('movimientos.listado') }}" class="btn btn-primary btn-sm mr-1">
            <i class="fas fa-list mr-1"></i> Movimientos
        </a>
        <a href="{{ route('students.index') }}" class="btn btn-warning btn-sm">
            <i class="fas fa-users mr-1"></i> Estudiantes
        </a>
    </div>
</div>

{{-- ── KPI — Small Boxes ───────────────────────────────────────── --}}
<div class="row">

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>S/. {{ number_format($saldo_actual, 2) }}</h3>
                <p>Saldo Acumulado</p>
            </div>
            <div class="icon"><i class="fas fa-wallet"></i></div>
            <a href="{{ route('balance.index') }}" class="small-box-footer">
                Ver balance <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>S/. {{ number_format($ingresos, 2) }}</h3>
                <p>
                    Ingresos {{ date('Y') }}
                    @php
                        $trend_ing = $ingresos_mes_anterior > 0
                            ? round((($ingresos_mes - $ingresos_mes_anterior) / $ingresos_mes_anterior) * 100, 1)
                            : null;
                    @endphp
                    @if($trend_ing !== null)
                        <small class="ml-1">
                            <i class="fas fa-arrow-{{ $trend_ing >= 0 ? 'up' : 'down' }}"></i>
                            {{ abs($trend_ing) }}% vs mes ant.
                        </small>
                    @endif
                </p>
            </div>
            <div class="icon"><i class="fas fa-arrow-up"></i></div>
            <a href="{{ route('movimientos.listado') }}" class="small-box-footer">
                Ver movimientos <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>S/. {{ number_format($gastos, 2) }}</h3>
                <p>
                    Gastos {{ date('Y') }}
                    @php
                        $trend_gasto = $gastos_mes_anterior > 0
                            ? round((($gastos_mes - $gastos_mes_anterior) / $gastos_mes_anterior) * 100, 1)
                            : null;
                    @endphp
                    @if($trend_gasto !== null)
                        <small class="ml-1">
                            <i class="fas fa-arrow-{{ $trend_gasto >= 0 ? 'up' : 'down' }}"></i>
                            {{ abs($trend_gasto) }}% vs mes ant.
                        </small>
                    @endif
                </p>
            </div>
            <div class="icon"><i class="fas fa-arrow-down"></i></div>
            <a href="{{ route('movimientos.listado') }}" class="small-box-footer">
                Ver movimientos <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($socios) }}</h3>
                <p>
                    Estudiantes Activos
                    @if($pendientes > 0)
                        <small class="ml-1">
                            <i class="fas fa-clock"></i> {{ $pendientes }} pendiente(s)
                        </small>
                    @endif
                </p>
            </div>
            <div class="icon"><i class="fas fa-user-graduate"></i></div>
            <a href="{{ route('students.index') }}" class="small-box-footer">
                Ver estudiantes <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

</div>

{{-- ── Info Boxes — Resumen mensual ────────────────────────────── --}}
<div class="row">

    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-success elevation-1">
                <i class="fas fa-sign-in-alt"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Ingresos del mes</span>
                <span class="info-box-number">S/. {{ number_format($ingresos_mes, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-danger elevation-1">
                <i class="fas fa-sign-out-alt"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Gastos del mes</span>
                <span class="info-box-number">S/. {{ number_format($gastos_mes, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        @php $balance_anual = $ingresos - $gastos; @endphp
        <div class="info-box">
            <span class="info-box-icon {{ $balance_anual >= 0 ? 'bg-primary' : 'bg-orange' }} elevation-1">
                <i class="fas fa-balance-scale"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Balance neto {{ date('Y') }}</span>
                <span class="info-box-number {{ $balance_anual >= 0 ? 'text-success' : 'text-danger' }}">
                    S/. {{ number_format($balance_anual, 2) }}
                </span>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-12">
        <div class="info-box">
            <span class="info-box-icon bg-warning elevation-1">
                <i class="fas fa-hourglass-half"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Cobros pendientes</span>
                <span class="info-box-number">{{ $pendientes }}</span>
            </div>
        </div>
    </div>

</div>

{{-- ── Gráfica + Resumen anual ─────────────────────────────────── --}}
<div class="row">

    <div class="col-lg-8">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-bar mr-1"></i> Ingresos y Gastos Mensuales
                </h3>
                <div class="card-tools">
                    <span class="badge badge-secondary">{{ date('Y') }}</span>
                </div>
            </div>
            <div class="card-body">
                @livewire('movimientos.components.grafica-movimiento')
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i> Resumen {{ date('Y') }}
                </h3>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-circle text-success mr-2"></i>Total Ingresos</span>
                        <span class="badge badge-success badge-pill px-3 py-2">
                            S/. {{ number_format($ingresos, 2) }}
                        </span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-circle text-danger mr-2"></i>Total Gastos</span>
                        <span class="badge badge-danger badge-pill px-3 py-2">
                            S/. {{ number_format($gastos, 2) }}
                        </span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-circle text-primary mr-2"></i>Balance Neto</span>
                        <span class="badge {{ $balance_anual >= 0 ? 'badge-primary' : 'badge-warning' }} badge-pill px-3 py-2">
                            S/. {{ number_format($balance_anual, 2) }}
                        </span>
                    </li>

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-circle text-info mr-2"></i>Saldo Acumulado</span>
                        <span class="badge badge-info badge-pill px-3 py-2">
                            S/. {{ number_format($saldo_actual, 2) }}
                        </span>
                    </li>

                    @php
                        $total_mov = $ingresos + $gastos;
                        $pct_ing   = $total_mov > 0 ? round(($ingresos / $total_mov) * 100) : 0;
                        $pct_gasto = 100 - $pct_ing;
                    @endphp
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="text-success font-weight-bold">Ingresos {{ $pct_ing }}%</small>
                            <small class="text-danger font-weight-bold">Gastos {{ $pct_gasto }}%</small>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-success" style="width:{{ $pct_ing }}%"></div>
                            <div class="progress-bar bg-danger" style="width:{{ $pct_gasto }}%"></div>
                        </div>
                    </li>

                </ul>
            </div>
            <div class="card-footer text-center">
                <a href="{{ route('balance.index') }}" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-external-link-alt mr-1"></i> Ver Balance Completo
                </a>
            </div>
        </div>
    </div>

</div>

{{-- ── Movimientos Recientes ───────────────────────────────────── --}}
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history mr-1"></i> Movimientos Recientes
                </h3>
                <div class="card-tools">
                    <a href="{{ route('movimientos.listado') }}" class="btn btn-sm btn-outline-primary">
                        Ver todos <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                @if($recientes->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                        No hay movimientos registrados.
                    </div>
                @else
                <table class="table table-hover table-sm mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Concepto</th>
                            <th>Tipo</th>
                            <th>Estudiante / Proveedor</th>
                            <th>Registrado por</th>
                            <th class="text-right">Monto</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recientes as $mov)
                        <tr>
                            <td class="align-middle">
                                <span class="font-weight-bold">{{ \Carbon\Carbon::parse($mov->date)->format('d/m/Y') }}</span><br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($mov->date)->diffForHumans() }}</small>
                            </td>
                            <td class="align-middle">
                                <span title="{{ $mov->concept }}" style="display:block;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    {{ $mov->concept ?? '—' }}
                                </span>
                                @if($mov->recipt_series && $mov->recipt_number)
                                    <small class="text-muted">{{ $mov->recipt_series }}-{{ str_pad($mov->recipt_number, 4, '0', STR_PAD_LEFT) }}</small>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if($mov->type === 'add')
                                    <span class="badge badge-success">
                                        <i class="fas fa-arrow-up mr-1"></i>Ingreso
                                    </span>
                                @else
                                    <span class="badge badge-danger">
                                        <i class="fas fa-arrow-down mr-1"></i>Gasto
                                    </span>
                                @endif
                            </td>
                            <td class="align-middle">
                                @if($mov->student)
                                    {{ $mov->student->first_name ?? '' }} {{ $mov->student->last_name ?? '' }}
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="align-middle">
                                <small>{{ $mov->user->name ?? '—' }}</small>
                            </td>
                            <td class="align-middle text-right">
                                <span class="font-weight-bold {{ $mov->type === 'add' ? 'text-success' : 'text-danger' }}">
                                    {{ $mov->type === 'add' ? '+' : '-' }}S/. {{ number_format($mov->amount, 2) }}
                                </span>
                            </td>
                            <td class="align-middle text-center">
                                <a href="{{ route('movimientos.ver', $mov->id) }}"
                                   class="btn btn-sm btn-default"
                                   title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
@stop
