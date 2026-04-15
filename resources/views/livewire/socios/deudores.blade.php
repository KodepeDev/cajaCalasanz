<div class="pt-3">

    {{-- Loading overlay --}}
    <div wire:loading.flex class="position-fixed w-100 h-100 justify-content-center align-items-center"
        style="top:0;left:0;z-index:9999;background:rgba(0,0,0,.4);">
        <div class="text-white text-center">
            <i class="fas fa-3x fa-circle-notch fa-spin"></i>
            <div class="mt-2 font-weight-bold">Cargando...</div>
        </div>
    </div>

    {{-- Summary boxes --}}
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>S/. {{ number_format($totalSoles, 2, '.', ',') }}</h3>
                    <p>Total deudas en Soles</p>
                </div>
                <div class="icon"><i class="fas fa-wallet"></i></div>
                <span class="small-box-footer">Año {{ $year }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="small-box bg-orange">
                <div class="inner">
                    <h3>$ {{ number_format($totalDolares, 2, '.', ',') }}</h3>
                    <p>Total deudas en Dólares</p>
                </div>
                <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                <span class="small-box-footer">Año {{ $year }}</span>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $socios->total() }}</h3>
                    <p>Estudiantes con deuda</p>
                </div>
                <div class="icon"><i class="fas fa-user-times"></i></div>
                <span class="small-box-footer">Año {{ $year }}</span>
            </div>
        </div>
    </div>

    {{-- Main card --}}
    <div class="card card-danger card-outline">

        <div class="card-header d-flex align-items-center">
            <i class="fas fa-exclamation-circle mr-2 text-danger"></i>
            <h3 class="card-title mb-0">Listado de Deudores — {{ $year }}</h3>
            <div class="card-tools ml-auto">
                <button wire:click="exportDatas" class="btn btn-sm btn-success"
                    wire:loading.attr="disabled" wire:target="exportDatas" title="Exportar todo">
                    <span wire:loading.remove wire:target="exportDatas">
                        <i class="fas fa-file-excel mr-1"></i> Exportar
                    </span>
                    <span wire:loading wire:target="exportDatas">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Exportando...
                    </span>
                </button>
            </div>
        </div>

        <div class="card-header pt-4 pb-2 border-top-0">
            <div class="input-group input-group-sm" style="max-width:360px;">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
                <input type="text" wire:model.debounce.400ms="search"
                    class="form-control" placeholder="Buscar por nombre o documento...">
                @if ($search)
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" wire:click="$set('search','')">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0" style="font-size:.85rem;">
                    <thead style="background:#c82333;color:#fff;">
                        <tr>
                            <th style="width:10%;">Documento</th>
                            <th style="width:28%;">Estudiante</th>
                            <th style="width:28%;">Apoderado</th>
                            <th class="text-right" style="width:12%;">Soles</th>
                            <th class="text-right" style="width:12%;">Dólares</th>
                            <th class="text-center" style="width:10%;">Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($socios as $item)
                            @php
                                $soles   = $item->details->where('currency_id', 1)->sum('amount');
                                $dolares = $item->details->where('currency_id', 2)->sum('amount');
                            @endphp
                            <tr>
                                <td class="align-middle font-weight-bold">{{ $item->document }}</td>
                                <td class="align-middle">{{ $item->full_name }}</td>
                                <td class="align-middle text-muted">
                                    {{ $item->tutor?->full_name ?? '—' }}
                                </td>
                                <td class="text-right align-middle {{ $soles > 0 ? 'text-danger font-weight-bold' : '' }}">
                                    {{ $soles > 0 ? 'S/. ' . number_format($soles, 2, '.', ',') : '—' }}
                                </td>
                                <td class="text-right align-middle {{ $dolares > 0 ? 'text-danger font-weight-bold' : '' }}">
                                    {{ $dolares > 0 ? '$ ' . number_format($dolares, 2, '.', ',') : '—' }}
                                </td>
                                <td class="text-center align-middle">
                                    <button wire:click="showModalDetail({{ $item->id }})"
                                        class="btn btn-xs btn-info" title="Ver detalle"
                                        wire:loading.attr="disabled" wire:target="showModalDetail({{ $item->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5" style="background:#fafafa;">
                                    <i class="fas fa-check-circle fa-2x d-block mb-2 text-success" style="opacity:.5;"></i>
                                    <span class="text-muted">
                                        @if ($search)
                                            No se encontraron deudores con "{{ $search }}".
                                        @else
                                            No hay deudores registrados para {{ $year }}.
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($socios->hasPages())
            <div class="card-footer">
                {{ $socios->links() }}
            </div>
        @endif

    </div>

    {{-- ── Modal Detalle ───────────────────────────────────────────── --}}
    <div wire:ignore.self class="modal fade" id="modalDetails" tabindex="-1"
        aria-labelledby="modalDetailsLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalDetailsLabel">
                        <i class="fas fa-list-ul mr-2"></i>
                        Deudas de {{ $socio_name ?? '—' }}
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar"
                        wire:click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-0">
                    @if (count($detalles) > 0)
                        @php
                            $sumSoles   = collect($detalles)->where('currency_id', 1)->sum('amount');
                            $sumDolares = collect($detalles)->where('currency_id', 2)->sum('amount');
                        @endphp
                        <table class="table table-hover table-sm mb-0" style="font-size:.85rem;">
                            <thead style="background:#6c757d;color:#fff;position:sticky;top:0;">
                                <tr>
                                    <th>Período</th>
                                    <th>Descripción</th>
                                    <th>Categoría</th>
                                    <th class="text-right">Soles</th>
                                    <th class="text-right">Dólares</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detalles as $det)
                                    <tr>
                                        <td class="align-middle text-nowrap">
                                            {{ \Carbon\Carbon::parse($det['date'])->format('m/Y') }}
                                        </td>
                                        <td class="align-middle">{{ $det['description'] }}</td>
                                        <td class="align-middle text-muted small">
                                            {{ $det['category']['name'] ?? '—' }}
                                        </td>
                                        <td class="text-right align-middle {{ ($det['currency_id'] ?? 1) == 1 ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                            {{ ($det['currency_id'] ?? 1) == 1
                                                ? 'S/. ' . number_format($det['amount'], 2, '.', ',')
                                                : '—' }}
                                        </td>
                                        <td class="text-right align-middle {{ ($det['currency_id'] ?? 1) == 2 ? 'text-danger font-weight-bold' : 'text-muted' }}">
                                            {{ ($det['currency_id'] ?? 1) == 2
                                                ? '$ ' . number_format($det['amount'], 2, '.', ',')
                                                : '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot style="background:#f8d7da;font-weight:bold;">
                                <tr>
                                    <td colspan="3" class="text-right">TOTAL</td>
                                    <td class="text-right text-danger">
                                        {{ $sumSoles > 0 ? 'S/. ' . number_format($sumSoles, 2, '.', ',') : '—' }}
                                    </td>
                                    <td class="text-right text-danger">
                                        {{ $sumDolares > 0 ? '$ ' . number_format($sumDolares, 2, '.', ',') : '—' }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-2x d-block mb-2" style="opacity:.35;"></i>
                            Sin deudas registradas.
                        </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        wire:click="closeModal">
                        <i class="fas fa-times mr-1"></i> Cerrar
                    </button>
                    @if ($selected_id)
                        <button type="button" class="btn btn-success" wire:click="exportData"
                            wire:loading.attr="disabled" wire:target="exportData">
                            <span wire:loading.remove wire:target="exportData">
                                <i class="fas fa-file-excel mr-1"></i> Exportar
                            </span>
                            <span wire:loading wire:target="exportData">
                                <i class="fas fa-spinner fa-spin mr-1"></i> Exportando...
                            </span>
                        </button>
                    @endif
                </div>

            </div>
        </div>
    </div>

</div>

@push('js')
<script>
    window.livewire.on('showModalDetails', () => {
        $('#modalDetails').modal('show');
    });
</script>
@endpush
