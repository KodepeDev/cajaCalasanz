@php
    // Load all needed category names in one query — avoids N+1 Category::find() per row
    $categoryIds   = collect($resumenTotals)->pluck('category_id')->filter()->unique()->values();
    $categoryNames = \App\Models\Category::whereIn('id', $categoryIds)->pluck('name', 'id');

    // Month-totals footer row (always a single aggregate row)
    $suma = $mesTotals[0] ?? [];
    $keys = ['total01','total02','total03','total04','total05','total06',
             'total07','total08','total09','total10','total11','total12'];
    $totalAnio = array_sum(array_map(fn($k) => $suma[$k] ?? 0, $keys));

    // Month labels for header
    $meses = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO',
              'JULIO','AGOSTO','SETIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];

    // Column keys for resumenTotals rows
    $cols = ['jan_total','feb_total','mar_total','apr_total','may_total','jun_total',
             'jul_total','ago_total','sep_total','oct_total','nov_total','dec_total'];
@endphp

<div class="pt-3">

    {{-- Loading overlay --}}
    <div wire:loading.flex class="position-fixed w-100 h-100 justify-content-center align-items-center"
        style="top:0;left:0;z-index:9999;background:rgba(0,0,0,.35);">
        <div class="text-white text-center">
            <i class="fas fa-3x fa-circle-notch fa-spin"></i>
            <div class="mt-2 font-weight-bold">Cargando...</div>
        </div>
    </div>

    <div class="card card-success">

        <div class="card-header d-flex align-items-center">
            <i class="fas fa-arrow-alt-circle-up mr-2 text-white"></i>
            <h3 class="card-title mb-0">Balance de Ingresos por Categorías — {{ $year }}</h3>
            <div class="card-tools ml-auto d-flex align-items-center" style="gap:.5rem;">
                <button class="btn btn-sm btn-light" wire:click="exportExcelData"
                    wire:loading.attr="disabled" title="Exportar Excel">
                    <span wire:loading.remove wire:target="exportExcelData">
                        <i class="fas fa-file-excel mr-1"></i> Excel
                    </span>
                    <span wire:loading wire:target="exportExcelData">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Exportando...
                    </span>
                </button>
                <select class="form-control form-control-sm" wire:model="year" style="width:90px;">
                    @foreach ($ultimosCincoAnios as $anio)
                        <option value="{{ $anio }}">{{ $anio }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm mb-0" style="font-size:.82rem;">
                    <thead style="position:sticky;top:0;z-index:5;background:#28a745;color:#fff;">
                        <tr>
                            <th style="min-width:140px;">
                                <i class="fas fa-tags mr-1"></i> Categoría
                            </th>
                            @foreach ($meses as $mes)
                                <th class="text-right" style="min-width:80px;">{{ $mes }}</th>
                            @endforeach
                            <th class="text-right" style="min-width:90px;background:#1e7e34;">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($resumenTotals as $item)
                            @php
                                $rowTotal = array_sum(array_map(fn($c) => $item[$c] ?? 0, $cols));
                            @endphp
                            <tr>
                                <td class="font-weight-bold align-middle">
                                    {{ $categoryNames[$item['category_id']] ?? '—' }}
                                </td>
                                @foreach ($cols as $col)
                                    <td class="text-right align-middle">
                                        {{ number_format($item[$col] ?? 0, 2) }}
                                    </td>
                                @endforeach
                                <td class="text-right align-middle font-weight-bold text-success"
                                    style="background:#f0fff4;">
                                    {{ number_format($rowTotal, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center py-5" style="background:#fafafa;">
                                    <i class="fas fa-chart-bar fa-2x d-block mb-2 text-muted" style="opacity:.35;"></i>
                                    <span class="text-muted">No hay registros de ingresos para {{ $year }}.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if (count($resumenTotals) > 0)
                        <tfoot>
                            <tr style="background:#d4edda;font-weight:bold;">
                                <td>TOTAL</td>
                                @foreach ($keys as $key)
                                    <td class="text-right">{{ number_format($suma[$key] ?? 0, 2) }}</td>
                                @endforeach
                                <td class="text-right text-success" style="background:#b8dfc4;">
                                    {{ number_format($totalAnio, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
            <span class="text-muted small">Año {{ $year }}</span>
            <span class="font-weight-bold text-success">
                Ingresos totales: S/. {{ number_format($totalAnio, 2) }}
            </span>
        </div>

    </div>
</div>
