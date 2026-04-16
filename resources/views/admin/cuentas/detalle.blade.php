@extends('adminlte::page')
@section('title', 'Detalle de cuenta')

@section('content')
<div class="container-fluid pt-4">

    <div class="card card-maroon">

        {{-- Card header --}}
        <div class="card-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="fas fa-chart-line mr-2"></i>
                <div>
                    <h3 class="card-title mb-0">{{ $nombre->account_name }}</h3>
                    <small class="text-light opacity-75">Movimientos registrados</small>
                </div>
            </div>
            <button type="button" class="btn btn-secondary btn-sm" onclick="window.history.back()">
                <i class="fa fa-arrow-left mr-1"></i> Volver
            </button>
        </div>

        {{-- ── Balance summary bar ── --}}
        <div class="px-4 py-3 border-bottom d-flex align-items-center flex-wrap" style="background:#f8f9fa;gap:1.5rem;">
            @php
                $ingresos = $summary->where('type', 'add')->sum('amount');
                $gastos   = $summary->where('type', 'out')->sum('amount');
            @endphp

            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle mr-2 flex-shrink-0"
                    style="width:36px;height:36px;background:#28a745;">
                    <i class="fas fa-arrow-up text-white fa-sm"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.4px;">Ingresos</div>
                    <div class="font-weight-bold text-success" style="font-size:.95rem;">
                        S/. {{ number_format($ingresos, 2) }}
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle mr-2 flex-shrink-0"
                    style="width:36px;height:36px;background:#dc3545;">
                    <i class="fas fa-arrow-down text-white fa-sm"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.4px;">Gastos</div>
                    <div class="font-weight-bold text-danger" style="font-size:.95rem;">
                        S/. {{ number_format($gastos, 2) }}
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center">
                <div class="d-flex align-items-center justify-content-center rounded-circle mr-2 flex-shrink-0"
                    style="width:36px;height:36px;background:{{ $totalf >= 0 ? '#007bff' : '#dc3545' }};">
                    <i class="fas fa-wallet text-white fa-sm"></i>
                </div>
                <div>
                    <div class="text-muted" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.4px;">
                        Saldo {{ $divisa->value ?? 'Soles' }}
                    </div>
                    <div class="font-weight-bold {{ $totalf >= 0 ? 'text-primary' : 'text-danger' }}" style="font-size:.95rem;">
                        S/. {{ number_format($totalf, 2, '.', ',') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">

            {{-- ── Date filter ── --}}
            <form action="{{ route('account.show', $id) }}" method="GET" class="mb-4">
                <div class="row align-items-end">
                    <div class="form-group col-md-4 mb-md-0">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                            Fecha inicio
                        </label>
                        <input type="date" name="start" class="form-control"
                            value="{{ request('start') }}">
                    </div>
                    <div class="form-group col-md-4 mb-md-0">
                        <label class="col-form-label-sm font-weight-bold text-uppercase text-muted">
                            Fecha fin
                        </label>
                        <input type="date" name="finish" class="form-control"
                            value="{{ request('finish') }}">
                    </div>
                    <div class="col-md-4 d-flex" style="gap:.5rem;">
                        <button type="submit" class="btn btn-warning flex-fill">
                            <i class="fa fa-filter mr-1"></i> Filtrar
                        </button>
                        @if (request('start') || request('finish'))
                            <a href="{{ route('account.show', $id) }}" class="btn btn-secondary">
                                <i class="fa fa-times"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            {{-- ── Movements table ── --}}
            <div class="table-responsive">
                <table id="summary-table" class="table table-bordered table-hover table-sm">
                    <thead class="bg-maroon text-white">
                        <tr>
                            <th style="width:50px">#</th>
                            <th style="width:110px">Fecha</th>
                            <th style="width:100px">Tipo</th>
                            <th>Recibo</th>
                            <th>Categoría</th>
                            <th class="text-right" style="width:120px">Monto</th>
                            <th style="width:90px" class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($summary as $mov)
                            <tr class="{{ $mov->status === 'NULLED' ? 'table-light text-muted' : '' }}">
                                <td>{{ $mov->id }}</td>
                                <td class="small">
                                    {{ $mov->date instanceof \Carbon\Carbon
                                        ? $mov->date->format('d/m/Y')
                                        : \Carbon\Carbon::parse($mov->date)->format('d/m/Y') }}
                                </td>
                                <td>
                                    @if ($mov->type === 'add')
                                        <span class="badge badge-success">
                                            <i class="fas fa-arrow-up mr-1"></i>Ingreso
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-arrow-down mr-1"></i>Gasto
                                        </span>
                                    @endif
                                    @if ($mov->status === 'NULLED')
                                        <span class="badge badge-secondary ml-1">Anulado</span>
                                    @endif
                                </td>
                                <td class="small">
                                    @if ($mov->recipt_series && $mov->recipt_number)
                                        <span class="badge badge-light border">
                                            {{ $mov->recipt_series }}-{{ str_pad($mov->recipt_number, 8, '0', STR_PAD_LEFT) }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="small text-muted">{{ $mov->name_categories ?? '—' }}</td>
                                <td class="text-right font-weight-bold">
                                    <span class="{{ $mov->type === 'add' ? 'text-success' : 'text-danger' }}">
                                        S/. {{ number_format($mov->amount, 2) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a class="btn btn-outline-info"
                                            href="{{ route('movimientos.ver', $mov->id) }}"
                                            title="Ver recibo">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if ($mov->status === 'PAID')
                                            <a class="btn btn-outline-primary"
                                                href="{{ route('movimientos.editar', $mov->id) }}"
                                                title="Editar">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                        @if ($mov->attached)
                                            <a class="btn btn-outline-secondary" target="_blank"
                                                href="/download/{{ $mov->attached->id }}"
                                                title="Ver adjunto">
                                                <i class="fa fa-paperclip"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-search fa-2x d-block mb-2" style="opacity:.25;"></i>
                                    No hay movimientos en el período seleccionado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#summary-table').DataTable({
            order: [[0, 'desc']],
            dom: 'Bfrtip',
            responsive: true,
            language: { url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json' },
            buttons: ['pdf', 'excel', 'copy'],
            columnDefs: [
                { orderable: false, targets: [6] }
            ],
        });
    });
</script>
@stop
