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
            <div class="card-header d-flex align-items-center">
                <i class="fas fa-fast-forward mr-2"></i>
                <h3 class="card-title mb-0">Movimientos Futuros</h3>
            </div>

            <div class="card-body">

                {{-- Filters --}}
                <div class="row align-items-end g-2 mb-3">
                    <div class="col-sm-2">
                        <label class="col-form-label-sm text-muted mb-0">Desde</label>
                        <input type="date" wire:model.defer="start1" class="form-control form-control-sm
                            @error('start1') is-invalid @enderror">
                        @error('start1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-2">
                        <label class="col-form-label-sm text-muted mb-0">Hasta</label>
                        <input type="date" wire:model.defer="finish1" class="form-control form-control-sm
                            @error('finish1') is-invalid @enderror">
                        @error('finish1')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-2">
                        <label class="col-form-label-sm text-muted mb-0">Tipo</label>
                        <select class="custom-select custom-select-sm" wire:model.defer="tipo1">
                            <option value="">Todos</option>
                            <option value="add">Ingresos</option>
                            <option value="out">Gastos</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="col-form-label-sm text-muted mb-0">Cuenta</label>
                        <select class="custom-select custom-select-sm" wire:model.defer="cuenta_id1">
                            <option value="">Todas</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">{{ $account->account_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label class="col-form-label-sm d-block">&nbsp;</label>
                        <div class="btn-group btn-block">
                            <button type="button" class="btn btn-sm btn-info" wire:click.prevent="filter"
                                title="Filtrar">
                                <i class="fas fa-filter"></i> Filtrar
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" wire:click.prevent="clearFilter"
                                title="Limpiar filtros">
                                <i class="fas fa-broom"></i>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Active filters --}}
                @if ($tipo || $cuenta_id)
                    <div class="mb-2">
                        @if ($tipo)
                            <span class="badge badge-info mr-1">
                                Tipo: {{ $tipo === 'add' ? 'Ingresos' : 'Gastos' }}
                            </span>
                        @endif
                        @if ($cuenta_id)
                            <span class="badge badge-info mr-1">Cuenta aplicada</span>
                        @endif
                    </div>
                @endif

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm mb-0">
                        <thead class="bg-maroon text-white text-center">
                            <tr>
                                <th style="width:150px">RECIBO</th>
                                <th style="width:100px">FECHA</th>
                                <th style="width:110px">TIPO</th>
                                <th>MOTIVO</th>
                                <th style="width:130px">CUENTA</th>
                                <th style="width:120px">CATEGORÍA</th>
                                <th class="text-right" style="width:130px">MONTO</th>
                                <th style="width:70px">ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($summaries as $mov)
                                <tr>
                                    <td class="text-center text-nowrap align-middle">
                                        @if ($mov->recipt_series && $mov->recipt_number)
                                            <span class="badge badge-light border"
                                                style="font-size:.78rem;letter-spacing:.3px;">
                                                {{ $mov->recipt_series }}-{{ str_pad($mov->recipt_number, 8, '0', STR_PAD_LEFT) }}
                                            </span>
                                        @else
                                            <span class="text-muted small">—</span>
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
                                    </td>
                                    <td class="small align-middle text-truncate" style="max-width:220px;">
                                        {{ $mov->concept ?? '—' }}
                                    </td>
                                    <td class="small align-middle">
                                        {{ $mov->account->account_name ?? '—' }}
                                    </td>
                                    <td class="small align-middle text-muted">
                                        {{ $mov->category->name ?? '—' }}
                                    </td>
                                    <td class="text-right text-nowrap align-middle font-weight-bold
                                        {{ $mov->type === 'add' ? 'text-success' : 'text-danger' }}">
                                        S/. {{ number_format($mov->amount, 2, '.', ',') }}
                                    </td>
                                    <td class="text-center align-middle">
                                        <a class="btn btn-xs btn-success"
                                            href="{{ route('movimientos.ver', $mov->id) }}"
                                            title="Ver">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5" style="background:#fafafa;">
                                        <i class="fas fa-inbox fa-2x d-block mb-2 text-muted" style="opacity:.4;"></i>
                                        <span class="text-muted">No se encontraron movimientos futuros.</span>
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
            <div class="col-md-3 col-sm-6 col-12 mb-3">
                <div class="small-box {{ $totalFinal >= 0 ? 'bg-success' : 'bg-danger' }}">
                    <div class="inner">
                        <h3>S/. {{ number_format(abs($totalFinal), 2, '.', ',') }}</h3>
                        <p>Balance futuro</p>
                    </div>
                    <div class="icon"><i class="fas fa-balance-scale"></i></div>
                    <span class="small-box-footer">
                        {{ $totalFinal >= 0 ? 'Favorable' : 'Desfavorable' }}
                    </span>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12 mb-3">
                <div class="small-box bg-success" style="opacity:.85">
                    <div class="inner">
                        <h3>S/. {{ number_format($totalAdd, 2, '.', ',') }}</h3>
                        <p>Total Ingresos</p>
                    </div>
                    <div class="icon"><i class="fas fa-arrow-up"></i></div>
                    <span class="small-box-footer">Movimientos futuros</span>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12 mb-3">
                <div class="small-box bg-danger" style="opacity:.85">
                    <div class="inner">
                        <h3>S/. {{ number_format($totalOut, 2, '.', ',') }}</h3>
                        <p>Total Gastos</p>
                    </div>
                    <div class="icon"><i class="fas fa-arrow-down"></i></div>
                    <span class="small-box-footer">Movimientos futuros</span>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 col-12 mb-3">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fa fa-percent"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">IGV Compras</span>
                        <span class="info-box-number text-success">
                            S/. {{ number_format($totalOutTax, 2, '.', ',') }}
                        </span>
                        <span class="progress-description">
                            No deducibles:
                            <span class="text-danger">S/. {{ number_format($totalAddTax, 2, '.', ',') }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Livewire.on('error', msg => {
            Swal.fire({ icon: 'error', title: 'Error', text: msg });
        });
    });
</script>
@endpush
