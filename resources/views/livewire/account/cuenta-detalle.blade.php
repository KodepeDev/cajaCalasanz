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

        <div class="card card-maroon">

            {{-- Card header --}}
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <i class="fas fa-chart-line mr-2"></i>
                    <div>
                        <h3 class="card-title mb-0">{{ $cuenta->account_name }}</h3>
                        <small class="text-light" style="opacity:.8;">Movimientos registrados</small>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="window.history.back()">
                    <i class="fa fa-arrow-left mr-1"></i> Volver
                </button>
            </div>

            {{-- Balance summary bar --}}
            <div class="px-4 py-3 border-bottom d-flex align-items-center flex-wrap"
                style="background:#f8f9fa;gap:1.5rem;">

                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-2 flex-shrink-0"
                        style="width:36px;height:36px;background:#28a745;">
                        <i class="fas fa-arrow-up text-white fa-sm"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.4px;">
                            Ingresos
                        </div>
                        <div class="font-weight-bold text-success" style="font-size:.95rem;">
                            S/. {{ number_format($ingreso, 2, '.', ',') }}
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mr-2 flex-shrink-0"
                        style="width:36px;height:36px;background:#dc3545;">
                        <i class="fas fa-arrow-down text-white fa-sm"></i>
                    </div>
                    <div>
                        <div class="text-muted" style="font-size:.7rem;text-transform:uppercase;letter-spacing:.4px;">
                            Gastos
                        </div>
                        <div class="font-weight-bold text-danger" style="font-size:.95rem;">
                            S/. {{ number_format($egreso, 2, '.', ',') }}
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
                            Saldo del período
                        </div>
                        <div class="font-weight-bold {{ $totalf >= 0 ? 'text-primary' : 'text-danger' }}"
                            style="font-size:.95rem;">
                            S/. {{ number_format($totalf, 2, '.', ',') }}
                        </div>
                    </div>
                </div>

                <div class="ml-auto text-muted" style="font-size:.75rem;">
                    {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }}
                    — {{ \Carbon\Carbon::parse($finish)->format('d/m/Y') }}
                </div>

            </div>

            <div class="card-body">

                {{-- Filters --}}
                <div class="row align-items-end g-2 mb-3">
                    <div class="col-sm-4">
                        <label class="col-form-label-sm text-muted mb-0">Desde</label>
                        <input type="date" wire:model.defer="start1" class="form-control form-control-sm">
                    </div>
                    <div class="col-sm-4">
                        <label class="col-form-label-sm text-muted mb-0">Hasta</label>
                        <input type="date" wire:model.defer="finish1" class="form-control form-control-sm">
                    </div>
                    <div class="col-sm-4">
                        <label class="col-form-label-sm d-block">&nbsp;</label>
                        <div class="btn-group btn-block">
                            <button type="button" class="btn btn-sm btn-info" wire:click.prevent="filter"
                                title="Filtrar">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" wire:click.prevent="clearFilter"
                                title="Restablecer al mes actual">
                                <i class="fas fa-broom"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm mb-0">
                        <thead class="bg-maroon text-white text-center">
                            <tr>
                                <th style="width:150px">RECIBO</th>
                                <th style="width:100px">FECHA</th>
                                <th style="width:110px">TIPO</th>
                                <th>CLIENTE / PROVEEDOR</th>
                                <th class="text-right" style="width:140px">MONTO</th>
                                <th style="width:100px">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($summary as $mov)
                                @php $isNulled = $mov->status === 'NULLED'; @endphp
                                <tr class="{{ $isNulled ? 'table-danger' : '' }}">

                                    <td class="text-center text-nowrap align-middle">
                                        @if ($mov->recipt_series && $mov->recipt_number)
                                            <span class="badge {{ $isNulled ? 'badge-danger' : 'badge-light border' }}"
                                                style="font-size:.78rem;letter-spacing:.3px;">
                                                @if ($isNulled)<i class="fas fa-ban mr-1"></i>@endif
                                                {{ $mov->recipt_series }}-{{ str_pad($mov->recipt_number, 8, '0', STR_PAD_LEFT) }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    <td class="text-center text-nowrap align-middle">
                                        {{ \Carbon\Carbon::parse($mov->date)->format('d/m/Y') }}
                                    </td>

                                    <td class="text-center align-middle">
                                        @if ($mov->type === 'add')
                                            <span class="badge badge-success">
                                                <i class="fas {{ $mov->id_transfer ? 'fa-exchange-alt' : 'fa-arrow-up' }} mr-1"></i>Ingreso
                                            </span>
                                        @else
                                            <span class="badge badge-danger">
                                                <i class="fas {{ $mov->id_transfer ? 'fa-exchange-alt' : 'fa-arrow-down' }} mr-1"></i>Gasto
                                            </span>
                                        @endif
                                        @if ($isNulled)
                                            <span class="badge badge-secondary ml-1">Anulado</span>
                                        @endif
                                    </td>

                                    <td class="small text-muted align-middle">
                                        {{ $mov->customer->full_name ?? '—' }}
                                    </td>

                                    <td class="text-right text-nowrap align-middle font-weight-bold
                                        {{ $mov->type === 'add' ? 'text-success' : 'text-danger' }}">
                                        S/. {{ number_format($mov->amount, 2, '.', ',') }}
                                    </td>

                                    <td class="text-center text-nowrap align-middle">
                                        <a class="btn btn-xs btn-success" title="Ver"
                                            href="{{ route('movimientos.ver', $mov->id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if (!$isNulled)
                                            <a class="btn btn-xs btn-info" title="Imprimir A4"
                                                href="{{ route('movimientos.a4.recibo', $mov->id) }}"
                                                target="_blank">
                                                <i class="fa fa-print"></i> A4
                                            </a>
                                            <a class="btn btn-xs btn-info" title="Imprimir A5"
                                                href="{{ route('movimientos.a5.recibo', $mov->id) }}"
                                                target="_blank">
                                                <i class="fa fa-print"></i> A5
                                            </a>
                                        @endif
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
                    {{ $summary->links() }}
                </div>

            </div>
        </div>

    </div>
</div>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Livewire.on('error', msg => {
            Swal.fire({ icon: 'error', title: 'Oops!', text: msg });
        });
    });
</script>
@endpush
