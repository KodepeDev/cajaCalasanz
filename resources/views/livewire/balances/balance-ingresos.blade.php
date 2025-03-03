<div class="pt-4">
    <div class="card">

        <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>

        <div class="card-header">
            <h3 class="card-title">Balance de Ingresos por Categorías {{ $year }}</h3>
            <div class="card-tools">
                <div class="form-group form-inline">
                    <button class="btn btn-info" id="btn_print_balance" wire:click='exportExcelData'><i
                            class="fas fa-print"></i></button>
                    <label for="date"></label>
                    <select class="form-control" wire:model='year' name="date" id="date">
                        @foreach ($ultimosCincoAnios as $anio)
                            <option value="{{ $anio }}">{{ $anio }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col"><i class="fas fa-arrow-alt-circle-up text-success"></i> Categorías</th>
                            <th scope="col">ENERO</th>
                            <th scope="col">FEBRERO</th>
                            <th scope="col">MARZO</th>
                            <th scope="col">ABRIL</th>
                            <th scope="col">MAYO</th>
                            <th scope="col">JUNIO</th>
                            <th scope="col">JULIO</th>
                            <th scope="col">AGOSTO</th>
                            <th scope="col">SETIEMBRE</th>
                            <th scope="col">OCTUBRE</th>
                            <th scope="col">NOVIEMBRE</th>
                            <th scope="col">DICIEMBRE</th>
                            <th scope="col">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($resumenTotals) > 0)
                            @foreach ($resumenTotals as $item)
                                <tr>
                                    <th scope="row">
                                        {{ \App\Models\Category::find($item['category_id'])->name }}
                                    </th>
                                    <td>{{ number_format($item['jan_total'], 2) }}</td>
                                    <td>{{ number_format($item['feb_total'], 2) }}</td>
                                    <td>{{ number_format($item['mar_total'], 2) }}</td>
                                    <td>{{ number_format($item['apr_total'], 2) }}</td>
                                    <td>{{ number_format($item['may_total'], 2) }}</td>
                                    <td>{{ number_format($item['jun_total'], 2) }}</td>
                                    <td>{{ number_format($item['jul_total'], 2) }}</td>
                                    <td>{{ number_format($item['ago_total'], 2) }}</td>
                                    <td>{{ number_format($item['sep_total'], 2) }}</td>
                                    <td>{{ number_format($item['oct_total'], 2) }}</td>
                                    <td>{{ number_format($item['nov_total'], 2) }}</td>
                                    <td>{{ number_format($item['dec_total'], 2) }}</td>
                                    <td class="bg-warning">
                                        {{-- {{ number_format($item->where('status', true)->where('category_id', $item->category->id)->whereYear('date_paid', $year)->where('summary_type', 'add')->sum('amount'),2) }} --}}
                                        {{ number_format($item['jan_total'] + $item['feb_total'] + $item['mar_total'] + $item['apr_total'] + $item['may_total'] + $item['jun_total'] + $item['jul_total'] + $item['ago_total'] + $item['sep_total'] + $item['oct_total'] + $item['nov_total'] + $item['dec_total'], 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <td colspan="14" class="text-center">No existen registros para el año {{ $year }}
                            </td>
                        @endif

                    </tbody>

                    <tfoot>
                        <tr class="bg-warning">
                            <th scope="row">TOTAL</th>
                            @foreach ($mesTotals as $suma)
                                <td>{{ number_format($suma['total01'], 2) }}</td>
                                <td>{{ number_format($suma['total02'], 2) }}</td>
                                <td>{{ number_format($suma['total03'], 2) }}</td>
                                <td>{{ number_format($suma['total04'], 2) }}</td>
                                <td>{{ number_format($suma['total05'], 2) }}</td>
                                <td>{{ number_format($suma['total06'], 2) }}</td>
                                <td>{{ number_format($suma['total07'], 2) }}</td>
                                <td>{{ number_format($suma['total08'], 2) }}</td>
                                <td>{{ number_format($suma['total09'], 2) }}</td>
                                <td>{{ number_format($suma['total10'], 2) }}</td>
                                <td>{{ number_format($suma['total11'], 2) }}</td>
                                <td>{{ number_format($suma['total12'], 2) }}</td>
                                @php
                                    $total_anio =
                                        $suma['total01'] +
                                        $suma['total02'] +
                                        $suma['total03'] +
                                        $suma['total04'] +
                                        $suma['total05'] +
                                        $suma['total06'] +
                                        $suma['total07'] +
                                        $suma['total08'] +
                                        $suma['total09'] +
                                        $suma['total10'] +
                                        $suma['total11'] +
                                        $suma['total12'];
                                @endphp
                            @endforeach
                            <td class="bg-warning">{{ number_format($total_anio, 2) }}</td>
                        </tr>
                    </tfoot>

                </table>
            </div>

        </div>
        <div class="card-footer">
            Ingresos totales: {{ number_format($total_anio, 2) }} Soles
        </div>
    </div>
</div>
