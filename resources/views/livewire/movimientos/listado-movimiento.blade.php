<div class="pt-3">
    {{-- Loading overlay --}}
    <div wire:loading.flex class="position-fixed w-100 h-100 justify-content-center align-items-center"
        style="top:0;left:0;z-index:9999;background:rgba(0,0,0,.35);">
        <div class="text-white text-center">
            <i class="fas fa-3x fa-circle-notch fa-spin"></i>
            <div class="mt-2 font-weight-bold">Cargando...</div>
        </div>
    </div>

    <div class="container-fluid">

        {{-- Main card --}}
        <div class="card card-maroon">
            <div class="card-header d-flex align-items-center">
                <i class="fa fa-receipt mr-2"></i>
                <h3 class="card-title mb-0"><b>RECIBOS GENERADOS</b></h3>
                <div class="card-tools ml-auto">
                    <a class="btn btn-warning btn-sm" href="{{ route('movimientos.crear') }}">
                        <i class="fa fa-plus mr-1"></i> NUEVO
                    </a>
                </div>
            </div>

            <div class="card-body">

                {{-- Filters --}}
                <div class="row align-items-end g-2 mb-3">
                    {{-- Date range --}}
                    <div class="col-sm-2">
                        <label class="col-form-label-sm text-muted mb-0">Desde</label>
                        <input type="date" wire:model.defer="start1"
                            class="form-control form-control-sm">
                    </div>
                    <div class="col-sm-2">
                        <label class="col-form-label-sm text-muted mb-0">Hasta</label>
                        <input type="date" wire:model.defer="finish1"
                            class="form-control form-control-sm">
                    </div>

                    {{-- Type --}}
                    <div class="col-sm-2">
                        <label class="col-form-label-sm text-muted mb-0">Tipo</label>
                        <select class="custom-select custom-select-sm" wire:model.defer="tipo1">
                            <option value="">Todos</option>
                            <option value="add">Ingresos</option>
                            <option value="out">Gastos</option>
                        </select>
                    </div>

                    {{-- Account --}}
                    <div class="col-sm-2">
                        <label class="col-form-label-sm text-muted mb-0">Cuenta</label>
                        <select class="custom-select custom-select-sm" wire:model.defer="cuenta_id1">
                            <option value="">Todas</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Document --}}
                    <div class="col-sm-2">
                        <label class="col-form-label-sm text-muted mb-0">Documento</label>
                        <input type="text" wire:model.defer="documento1"
                            class="form-control form-control-sm" placeholder="Nro. documento"
                            id="filtro-documento">
                    </div>

                    {{-- Action buttons --}}
                    <div class="col-sm-2">
                        <label class="col-form-label-sm d-block">&nbsp;</label>
                        <div class="btn-group btn-block">
                            <button class="btn btn-sm btn-info" wire:click.prevent="filter"
                                title="Filtrar">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                            <button class="btn btn-sm btn-secondary" wire:click.prevent="clearFilter"
                                title="Limpiar filtros">
                                <i class="fas fa-broom"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Active filters badge --}}
                @if($tipo || $cuenta_id || $documento)
                    <div class="mb-2">
                        @if($tipo)
                            <span class="badge badge-info mr-1">
                                Tipo: {{ $tipo === 'add' ? 'Ingresos' : 'Gastos' }}
                            </span>
                        @endif
                        @if($cuenta_id)
                            <span class="badge badge-info mr-1">Cuenta aplicada</span>
                        @endif
                        @if($documento)
                            <span class="badge badge-info mr-1">Doc: {{ $documento }}</span>
                        @endif
                    </div>
                @endif

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm mb-0">
                        <thead class="bg-maroon text-white text-center">
                            <tr>
                                <th style="width:140px">RECIBO</th>
                                <th style="width:90px">FECHA</th>
                                <th>CLIENTE / ALUMNO</th>
                                <th style="width:180px">CUENTA / TIPO</th>
                                <th class="text-right" style="width:120px">MONTO</th>
                                <th style="width:150px">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($summaries as $summary)
                                @php
                                    $isNulled = $summary->status === 'NULLED';
                                    $receipt  = $summary->recipt_series . '-'
                                        . str_pad($summary->recipt_number, 8, '0', STR_PAD_LEFT);
                                @endphp
                                <tr class="{{ $isNulled ? 'table-danger' : '' }}">

                                    {{-- Recibo --}}
                                    <td class="text-center text-nowrap align-middle">
                                        <span class="badge {{ $isNulled ? 'badge-danger' : 'badge-light border' }}"
                                            style="font-size:.78rem;letter-spacing:.3px;">
                                            @if($isNulled)<i class="fas fa-ban mr-1"></i>@endif
                                            {{ $receipt }}
                                        </span>
                                    </td>

                                    {{-- Fecha --}}
                                    <td class="text-center text-nowrap align-middle">
                                        {{ $summary->date->format('d/m/Y') }}
                                    </td>

                                    {{-- Cliente / Alumno --}}
                                    <td class="align-middle">
                                        <div class="font-weight-bold" style="font-size:.87rem;">
                                            {{ $summary->customer->full_name ?? '—' }}
                                        </div>
                                        @if ($summary->student)
                                            <div class="text-muted" style="font-size:.75rem;">
                                                <i class="fas fa-user-graduate mr-1"></i>
                                                {{ $summary->student->full_name }}
                                            </div>
                                        @endif
                                        @if ($summary->paid_by && $summary->paid_by !== ($summary->customer->full_name ?? ''))
                                            <div class="text-muted" style="font-size:.75rem;">
                                                <i class="fas fa-hand-holding-usd mr-1"></i>
                                                {{ $summary->paid_by }}
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Cuenta / Tipo --}}
                                    <td class="align-middle">
                                        <div style="font-size:.85rem;">
                                            {{ $summary->account->account_name ?? '—' }}
                                        </div>
                                        <div class="mt-1">
                                            @if ($summary->type === 'add')
                                                <span class="badge badge-success" style="font-size:.7rem;">
                                                    <i class="{{ $summary->id_transfer ? 'fas fa-exchange-alt' : 'fas fa-arrow-up' }} mr-1"></i>Ingreso
                                                </span>
                                            @else
                                                <span class="badge badge-danger" style="font-size:.7rem;">
                                                    <i class="{{ $summary->id_transfer ? 'fas fa-exchange-alt' : 'fas fa-arrow-down' }} mr-1"></i>Gasto
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Monto --}}
                                    <td class="text-right text-nowrap align-middle font-weight-bold
                                        {{ $summary->type === 'add' ? 'text-success' : 'text-danger' }}">
                                        S/. {{ number_format($summary->amount, 2, '.', ',') }}
                                    </td>

                                    {{-- Acciones --}}
                                    <td class="text-center text-nowrap align-middle">
                                        <a class="btn btn-xs btn-success" title="Ver"
                                            href="{{ route('movimientos.ver', $summary->id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        @if ($summary->type === 'out' && $summary->status === 'PAID')
                                            <a class="btn btn-xs btn-warning" title="Editar"
                                                href="{{ route('movimientos.editar', $summary->id) }}">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif

                                        <a class="btn btn-xs btn-info" title="Imprimir"
                                            href="{{ route('movimientos.a4.recibo', $summary->id) }}"
                                            target="_blank">
                                            <i class="fa fa-print"></i> A4
                                        </a>
                                        <a class="btn btn-xs btn-info" title="Imprimir"
                                            href="{{ route('movimientos.a5.recibo', $summary->id) }}"
                                            target="_blank">
                                            <i class="fa fa-print"></i> A5
                                        </a>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5" style="background:#fafafa;">
                                        <i class="fas fa-inbox fa-2x d-block mb-2 text-muted" style="opacity:.4;"></i>
                                        <span class="text-muted">No se encontraron registros para el período seleccionado.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-2">
                    {{ $summaries->links() }}
                </div>
            </div>
        </div>

        {{-- Summary cards --}}
        <div class="row">
            {{-- Balance --}}
            <div class="col-md-3 col-sm-6 col-12 mb-3">
                <div class="small-box {{ $totalFinal >= 0 ? 'bg-success' : 'bg-danger' }}">
                    <div class="inner">
                        <h3>S/. {{ number_format(abs($totalFinal), 2, '.', ',') }}</h3>
                        <p>Balance del período</p>
                    </div>
                    <div class="icon"><i class="fa fa-balance-scale"></i></div>
                    <span class="small-box-footer">
                        {{ $totalFinal >= 0 ? 'Favorable' : 'Desfavorable' }}
                    </span>
                </div>
            </div>

            {{-- Ingresos --}}
            <div class="col-md-3 col-sm-6 col-12 mb-3">
                <div class="small-box bg-success" style="opacity:.85">
                    <div class="inner">
                        <h3>S/. {{ number_format($totalIngresos, 2, '.', ',') }}</h3>
                        <p>Total Ingresos</p>
                    </div>
                    <div class="icon"><i class="fas fa-arrow-up"></i></div>
                    <span class="small-box-footer">Recibos pagados</span>
                </div>
            </div>

            {{-- Egresos --}}
            <div class="col-md-3 col-sm-6 col-12 mb-3">
                <div class="small-box bg-danger" style="opacity:.85">
                    <div class="inner">
                        <h3>S/. {{ number_format($totalEgresos, 2, '.', ',') }}</h3>
                        <p>Total Gastos</p>
                    </div>
                    <div class="icon"><i class="fas fa-arrow-down"></i></div>
                    <span class="small-box-footer">Recibos pagados</span>
                </div>
            </div>

            {{-- Tax + Reports --}}
            <div class="col-md-3 col-sm-6 col-12 mb-3">
                <div class="info-box mb-2">
                    <span class="info-box-icon bg-warning"><i class="fa fa-percent"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">IGV Compras</span>
                        <span class="info-box-number text-success">
                            S/. {{ number_format($totalEgresosTx, 2, '.', ',') }}
                        </span>
                        <span class="progress-description">
                            No deducibles:
                            <span class="text-danger">S/. {{ number_format($totalIngresosTx, 2, '.', ',') }}</span>
                        </span>
                    </div>
                </div>

                @php
                    $reportParams = http_build_query([
                        'tipo'      => $tipo,
                        'cuentas'   => $cuenta_id,
                        'documento' => $documento,
                        'categoria' => $categoria_id,
                        'start'     => $start,
                        'finish'    => $finish,
                    ]);
                @endphp
                <a target="_blank"
                    href="{{ url('admin/movimientos/reportePDF/?' . $reportParams) }}"
                    class="btn btn-block btn-sm btn-outline-danger mb-1">
                    <i class="fa fa-file-pdf mr-1"></i> Reporte Genérico
                </a>
                <a target="_blank"
                    href="{{ url('admin/movimientos/conceptosPDF/?' . $reportParams) }}"
                    class="btn btn-block btn-sm btn-outline-danger">
                    <i class="fa fa-file-pdf mr-1"></i> Reporte Detallado
                </a>
            </div>
        </div>

    </div>

    @livewire('movimientos.anular-movimiento')
</div>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alertError = msg => Swal.fire({ icon: 'error', title: 'Error', text: msg });

        window.livewire.on('error',       alertError);
        window.livewire.on('error_fecha', alertError);

        window.livewire.on('movimiento_anulado', msg => {
            Swal.fire({ icon: 'success', title: '¡Correcto!', text: msg });
            $('#modalAnularRegistro').modal('hide');
        });

        window.livewire.on('show-modal-anular', () => {
            $('#modalAnularRegistro').modal('show');
        });

        // Filtrar con Enter desde el campo de documento
        document.getElementById('filtro-documento')?.addEventListener('keydown', e => {
            if (e.key === 'Enter') livewire.emit('filter');
        });
    });
</script>
@endpush
