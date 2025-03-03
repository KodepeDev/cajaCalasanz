<div class="pt-4">
    <div class="card ">

        <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>

        <div class="card-header">
            <h3 class="card-title">Balance Global por Categorías {{ $year }}</h3>
            <div class="card-tools">
                <div class="form-group form-inline">
                    {{-- <button class="btn btn-info" id="btn_print_balance" onclick="imprimir()"><i
                            class="fas fa-print"></i></button> --}}
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
                            <th scope="col"><i class="fas fa-arrow-alt-circle-right text-info"></i> Categorías</th>
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
                                    <th scope="row">{{ \App\Models\Category::find($item['category_id'])->name }}
                                    </th>
                                    <td>{{ number_format($item['total01'], 2) }}</td>
                                    <td>{{ number_format($item['total02'], 2) }}</td>
                                    <td>{{ number_format($item['total03'], 2) }}</td>
                                    <td>{{ number_format($item['total04'], 2) }}</td>
                                    <td>{{ number_format($item['total05'], 2) }}</td>
                                    <td>{{ number_format($item['total06'], 2) }}</td>
                                    <td>{{ number_format($item['total07'], 2) }}</td>
                                    <td>{{ number_format($item['total08'], 2) }}</td>
                                    <td>{{ number_format($item['total09'], 2) }}</td>
                                    <td>{{ number_format($item['total10'], 2) }}</td>
                                    <td>{{ number_format($item['total11'], 2) }}</td>
                                    <td>{{ number_format($item['total12'], 2) }}</td>
                                    <td class="bg-warning">
                                        {{-- {{ number_format($item->where('status', 1)->where('category_id', $item->category->id)->whereYear('date_paid', $year)->where('summary_type', 'add')->sum('amount') -$item->where('status', 1)->where('category_id', $item->category->id)->whereYear('date_paid', $year)->where('summary_type', 'out')->sum('amount'),2) }} --}}
                                        {{ number_format($item['total01'] + $item['total02'] + $item['total03'] + $item['total04'] + $item['total05'] + $item['total06'] + $item['total07'] + $item['total08'] + $item['total09'] + $item['total10'] + $item['total11'] + $item['total12'], 2) }}
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
                            <th scope="row">SUMA TOTALES</th>
                            @foreach ($mesTotals as $total)
                                <td>{{ number_format($total['total_mes01'], 2) }}</td>
                                <td>{{ number_format($total['total_mes02'], 2) }}</td>
                                <td>{{ number_format($total['total_mes03'], 2) }}</td>
                                <td>{{ number_format($total['total_mes04'], 2) }}</td>
                                <td>{{ number_format($total['total_mes05'], 2) }}</td>
                                <td>{{ number_format($total['total_mes06'], 2) }}</td>
                                <td>{{ number_format($total['total_mes07'], 2) }}</td>
                                <td>{{ number_format($total['total_mes08'], 2) }}</td>
                                <td>{{ number_format($total['total_mes09'], 2) }}</td>
                                <td>{{ number_format($total['total_mes10'], 2) }}</td>
                                <td>{{ number_format($total['total_mes11'], 2) }}</td>
                                <td>{{ number_format($total['total_mes12'], 2) }}</td>
                                @php
                                    $total_anio =
                                        $total['total_mes01'] +
                                        $total['total_mes02'] +
                                        $total['total_mes03'] +
                                        $total['total_mes04'] +
                                        $total['total_mes05'] +
                                        $total['total_mes06'] +
                                        $total['total_mes07'] +
                                        $total['total_mes08'] +
                                        $total['total_mes09'] +
                                        $total['total_mes10'] +
                                        $total['total_mes11'] +
                                        $total['total_mes12'];
                                @endphp
                            @endforeach
                            <td class="bg-warning">{{ number_format($total_anio, 2) }}</td>
                        </tr>
                    </tfoot>

                </table>
            </div>

        </div>
        <div class="card-footer">
            Balance total: {{ number_format($total_anio, 2) }} Soles
        </div>
    </div>
</div>
