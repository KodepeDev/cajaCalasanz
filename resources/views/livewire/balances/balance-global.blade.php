@php
    $categoryIds   = collect($resumenTotals)->pluck('category_id')->filter()->unique()->values();
    $categoryNames = \App\Models\Category::whereIn('id', $categoryIds)->pluck('name', 'id');

    // Global uses total01…total12 for both resumen and mes rows
    $suma    = $mesTotals[0] ?? [];
    $mesKeys = ['total_mes01','total_mes02','total_mes03','total_mes04','total_mes05','total_mes06',
                'total_mes07','total_mes08','total_mes09','total_mes10','total_mes11','total_mes12'];
    $cols    = ['total01','total02','total03','total04','total05','total06',
                'total07','total08','total09','total10','total11','total12'];

    $totalAnio = array_sum(array_map(fn($k) => $suma[$k] ?? 0, $mesKeys));

    $meses = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO',
              'JULIO','AGOSTO','SETIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
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

    <div class="card card-primary">

        <div class="card-header d-flex align-items-center">
            <i class="fas fa-balance-scale mr-2 text-white"></i>
            <h3 class="card-title mb-0">Balance Global por Categorías — {{ $year }}</h3>
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
                    <thead style="position:sticky;top:0;z-index:5;background:#007bff;color:#fff;">
                        <tr>
                            <th style="min-width:140px;">
                                <i class="fas fa-tags mr-1"></i> Categoría
                            </th>
                            @foreach ($meses as $mes)
                                <th class="text-right" style="min-width:80px;">{{ $mes }}</th>
                            @endforeach
                            <th class="text-right" style="min-width:90px;background:#0056b3;">BALANCE</th>
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
                                    @php $val = $item[$col] ?? 0; @endphp
                                    <td class="text-right align-middle {{ $val < 0 ? 'text-danger' : ($val > 0 ? 'text-success' : 'text-muted') }}">
                                        {{ number_format($val, 2) }}
                                    </td>
                                @endforeach
                                <td class="text-right align-middle font-weight-bold {{ $rowTotal < 0 ? 'text-danger' : 'text-success' }}"
                                    style="background:{{ $rowTotal < 0 ? '#fff5f5' : '#f0fff4' }};">
                                    {{ number_format($rowTotal, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center py-5" style="background:#fafafa;">
                                    <i class="fas fa-balance-scale fa-2x d-block mb-2 text-muted" style="opacity:.35;"></i>
                                    <span class="text-muted">No hay registros para {{ $year }}.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if (count($resumenTotals) > 0)
                        <tfoot>
                            <tr style="background:#cce5ff;font-weight:bold;">
                                <td>SUMA TOTALES</td>
                                @foreach ($mesKeys as $key)
                                    @php $val = $suma[$key] ?? 0; @endphp
                                    <td class="text-right {{ $val < 0 ? 'text-danger' : ($val > 0 ? 'text-success' : '') }}">
                                        {{ number_format($val, 2) }}
                                    </td>
                                @endforeach
                                <td class="text-right font-weight-bold {{ $totalAnio < 0 ? 'text-danger' : 'text-success' }}"
                                    style="background:#b8daff;">
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
            <span class="font-weight-bold {{ $totalAnio < 0 ? 'text-danger' : 'text-success' }}">
                Balance total: S/. {{ number_format($totalAnio, 2) }}
                <i class="fas fa-{{ $totalAnio >= 0 ? 'arrow-up' : 'arrow-down' }} ml-1"></i>
            </span>
        </div>

    </div>
</div>
