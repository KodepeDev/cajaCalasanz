@extends('adminlte::page')
@section('title', 'Saldos Totales')

@section('content')
<div class="container-fluid pt-4">

    {{-- ── Page header ── --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 font-weight-bold">
                <i class="fas fa-chart-pie mr-2 text-primary"></i>Saldos Totales
            </h4>
            <small class="text-muted">Al {{ \Carbon\Carbon::parse($today)->format('d/m/Y') }}</small>
        </div>
    </div>

    {{-- ── Top KPI strip ── --}}
    <div class="row mb-4">

        {{-- Saldo global --}}
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="info-box shadow-sm"
                style="border-left:4px solid {{ $totalfinal >= 0 ? '#007bff' : '#dc3545' }};">
                <span class="info-box-icon"
                    style="background:{{ $totalfinal >= 0 ? '#007bff' : '#dc3545' }};">
                    <i class="fas fa-wallet"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted" style="font-size:.75rem;">SALDO TOTAL ACTUAL</span>
                    <span class="info-box-number {{ $totalfinal >= 0 ? 'text-primary' : 'text-danger' }}"
                        style="font-size:1.15rem;">
                        S/. {{ number_format($totalfinal, 2, '.', ',') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Ingresos futuros --}}
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="info-box shadow-sm" style="border-left:4px solid #28a745;">
                <span class="info-box-icon" style="background:#28a745;">
                    <i class="fas fa-arrow-circle-up"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted" style="font-size:.75rem;">INGRESOS FUTUROS</span>
                    <span class="info-box-number text-success" style="font-size:1.15rem;">
                        S/. {{ number_format($futureAdd, 2, '.', ',') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Gastos futuros --}}
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="info-box shadow-sm" style="border-left:4px solid #dc3545;">
                <span class="info-box-icon" style="background:#dc3545;">
                    <i class="fas fa-arrow-circle-down"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted" style="font-size:.75rem;">GASTOS FUTUROS</span>
                    <span class="info-box-number text-danger" style="font-size:1.15rem;">
                        S/. {{ number_format($futureOut, 2, '.', ',') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Balance neto futuro --}}
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="info-box shadow-sm"
                style="border-left:4px solid {{ $futureNet >= 0 ? '#17a2b8' : '#dc3545' }};">
                <span class="info-box-icon"
                    style="background:{{ $futureNet >= 0 ? '#17a2b8' : '#dc3545' }};">
                    <i class="fas fa-balance-scale"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text text-muted" style="font-size:.75rem;">NETO FUTURO</span>
                    <span class="info-box-number {{ $futureNet >= 0 ? 'text-info' : 'text-danger' }}"
                        style="font-size:1.15rem;">
                        S/. {{ number_format($futureNet, 2, '.', ',') }}
                    </span>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        {{-- ── Account balances ── --}}
        <div class="col-lg-8 mb-4">
            <div class="card card-primary">
                <div class="card-header d-flex align-items-center">
                    <i class="fas fa-university mr-2"></i>
                    <h3 class="card-title mb-0">Saldo por Cuenta</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Cuenta</th>
                                    <th style="width:120px">N° Cuenta</th>
                                    <th style="width:90px">Tipo</th>
                                    <th style="width:140px" class="text-right">Saldo (S/.)</th>
                                    <th style="width:70px" class="text-center">Ver</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($accounts as $account)
                                    <tr>
                                        <td class="font-weight-bold">
                                            <i class="fas fa-university text-muted mr-2" style="opacity:.5;"></i>
                                            {{ $account->account_name }}
                                        </td>
                                        <td class="text-muted small">
                                            {{ $account->account_number ?? '—' }}
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary text-capitalize">
                                                {{ $account->account_type }}
                                            </span>
                                        </td>
                                        <td class="text-right font-weight-bold">
                                            <span class="{{ $account->total >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($account->total, 2, '.', ',') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @can('cuentas.show')
                                                <a class="btn btn-xs btn-outline-info"
                                                    href="{{ route('account.show', $account->id) }}"
                                                    title="Ver movimientos">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            No hay cuentas registradas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr style="background:#e9ecef;border-top:2px solid #ced4da;">
                                    <td colspan="3" class="text-right font-weight-bold py-2"
                                        style="font-size:.8rem;text-transform:uppercase;letter-spacing:.4px;">
                                        Total consolidado
                                    </td>
                                    <td class="text-right font-weight-bold py-2">
                                        <span class="{{ $totalfinal >= 0 ? 'text-primary' : 'text-danger' }}"
                                            style="font-size:1rem;">
                                            S/. {{ number_format($totalfinal, 2, '.', ',') }}
                                        </span>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Liquidity projections ── --}}
        <div class="col-lg-4 mb-4">
            <div class="card card-secondary">
                <div class="card-header d-flex align-items-center">
                    <i class="fas fa-project-diagram mr-2"></i>
                    <h3 class="card-title mb-0">Proyección de Liquidez</h3>
                </div>
                <div class="card-body p-0">
                    @php
                        $horizons = [
                            ['label' => '30 días',  'value' => $totalm1, 'icon' => 'fa-calendar-day'],
                            ['label' => '3 meses',  'value' => $totalm3, 'icon' => 'fa-calendar-week'],
                            ['label' => '6 meses',  'value' => $totalm6, 'icon' => 'fa-calendar-alt'],
                        ];
                    @endphp
                    @foreach ($horizons as $h)
                        @php $positive = $h['value'] >= 0; @endphp
                        <div class="d-flex align-items-center px-4 py-3
                            {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="d-flex align-items-center justify-content-center rounded-circle mr-3 flex-shrink-0"
                                style="width:42px;height:42px;
                                       background:{{ $positive ? '#e8f5e9' : '#fce4ec' }};">
                                <i class="fas {{ $h['icon'] }}
                                    {{ $positive ? 'text-success' : 'text-danger' }}"></i>
                            </div>
                            <div class="flex-fill">
                                <div class="text-muted" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.4px;">
                                    Liquidez en {{ $h['label'] }}
                                </div>
                                <div class="font-weight-bold {{ $positive ? 'text-success' : 'text-danger' }}"
                                    style="font-size:.95rem;">
                                    S/. {{ number_format($h['value'], 2, '.', ',') }}
                                </div>
                            </div>
                            @if (!$positive)
                                <span class="badge badge-danger ml-2" title="Liquidez negativa proyectada">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                            @else
                                <span class="badge badge-success ml-2">
                                    <i class="fas fa-check"></i>
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="card-footer text-muted" style="font-size:.75rem;">
                    <i class="fas fa-info-circle mr-1"></i>
                    Proyección basada en movimientos PAGADOS registrados hasta cada fecha.
                </div>
            </div>

            {{-- Future movements summary --}}
            <div class="card card-outline card-secondary mt-0">
                <div class="card-header d-flex align-items-center">
                    <i class="fas fa-clock mr-2"></i>
                    <h3 class="card-title mb-0">Movimientos Futuros</h3>
                </div>
                <div class="card-body p-0">
                    <div class="d-flex align-items-center px-4 py-3 border-bottom">
                        <div class="flex-fill">
                            <div class="text-muted" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.4px;">Ingresos pendientes</div>
                            <div class="font-weight-bold text-success" style="font-size:.95rem;">
                                S/. {{ number_format($futureAdd, 2, '.', ',') }}
                            </div>
                        </div>
                        <i class="fas fa-arrow-up text-success fa-lg" style="opacity:.5;"></i>
                    </div>
                    <div class="d-flex align-items-center px-4 py-3 border-bottom">
                        <div class="flex-fill">
                            <div class="text-muted" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.4px;">Gastos pendientes</div>
                            <div class="font-weight-bold text-danger" style="font-size:.95rem;">
                                S/. {{ number_format($futureOut, 2, '.', ',') }}
                            </div>
                        </div>
                        <i class="fas fa-arrow-down text-danger fa-lg" style="opacity:.5;"></i>
                    </div>
                    <div class="d-flex align-items-center px-4 py-3">
                        <div class="flex-fill">
                            <div class="text-muted" style="font-size:.75rem;text-transform:uppercase;letter-spacing:.4px;">Balance neto</div>
                            <div class="font-weight-bold {{ $futureNet >= 0 ? 'text-info' : 'text-danger' }}"
                                style="font-size:.95rem;">
                                S/. {{ number_format($futureNet, 2, '.', ',') }}
                            </div>
                        </div>
                        <i class="fas fa-balance-scale {{ $futureNet >= 0 ? 'text-info' : 'text-danger' }} fa-lg"
                            style="opacity:.5;"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop
