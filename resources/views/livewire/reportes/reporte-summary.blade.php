<div class="pt-4">
    <div class="container-fluid">
        <div class="card card-danger">

            <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>

            <div class="card-header">
                <i class="fa fa-bar-chart"></i>
                <h3 class="card-title"><b>Movimientos Detalles</b></h3>
                {{-- <div class="card-tools">
                    <a class="btn btn-warning" href="{{ route('movimientos.crear') }}"> <i
                            class="fa fa-plus"></i> Nuevo</a>
                </div> --}}
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="row col-md-6">
                        <div class="form-group col-sm-6">
                            <input type="date" wire:model.defer='start1' name="start" placeholder="Fecha Inicio"
                                class="form-control form-control-sm">
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="date" wire:model.defer="finish1" name="finish" placeholder="Fecha Final"
                                class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="row col-md-6">
                        <div class="col-sm-6">
                            <select class="custom-select custom-select-sm" type="text" wire:model.defer="tipo1"
                                name="tipo">
                                <option value="">===Tipo de movimiento===</option>
                                <option value="add">INGRESOS</option>
                                <option value="out">GASTOS</option>
                            </select>
                        </div>
                        {{-- <div class="col-md-4">
                            <select class="custom-select custom-select-sm" type="text" wire:model.defer="categoria_id1"
                                name="tipo">
                                <option value="">===Categoria===</option>
                                @foreach ($categories as $name => $id)
                                <option value="{{$id}}">{{$name}}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="col-md-6">
                            <div class="btn-group btn-block" role="group" aria-label="">
                                <button type="submit" class="btn btn-sm  btn-info" wire:click.prevent='Filter'><i
                                        class="fas fa-filter"></i></button>
                                <button type="submit" class="btn btn-sm  btn-warning"
                                    wire:click.prevent='clearFilter'><i class="fas fa-broom"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-2" id="printJS-view">
                <div class="col-sm-12 table-responsive">
                    <table cellpadding="0" celspacing="0" id="summary"
                        class="table table-bordered table-sm table-hover" style="width:100%">
                        <thead class="thead-dark">
                            <tr>
                                <th>Fecha</th>
                                <th>Descripción</th>
                                <th>Tipo</th>
                                <th>Categoría</th>
                                <th>Stand</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($summaries->count() > 0)
                                @foreach ($summaries as $summarys)
                                    @if ($summarys->details->count() > 0)
                                        <tr class="bg-secondary">
                                            <th colspan="2">
                                                RECIBO: {{ $summarys->recipt_series }} - {{ $summarys->recipt_number }}
                                            </th>
                                            <th colspan="2">
                                                CLIENTE: {{ $summarys->customer->full_name }}
                                            </th>
                                            <th colspan="2">
                                                MEDIO DE PAGO: {{ $summarys->paymentMethod->name }}
                                            </th>
                                        </tr>
                                    @endif
                                    @foreach ($summarys->details as $item)
                                        <tr>
                                            <td>{{ $item->date_paid->format('d-m-Y') }}</td>
                                            <td>{{ $item->description }}</td>
                                            @if ($item->summary_type == 'add')
                                                <td>Ingreso
                                                    <small class="badge badge-success bordered float-right mt-1">
                                                        @if ($item->id_transfer != '')
                                                            <i class="fas fa-exchange-alt"></i>
                                                        @else
                                                            <i class="fa fa-sort-up"></i>
                                                        @endif
                                                    </small>
                                                </td>
                                            @elseif($item->summary_type == 'out')
                                                <td>Gasto
                                                    <small class="badge badge-danger bordered float-right mt-1">
                                                        @if ($item->id_transfer != '')
                                                            <i class="fas fa-exchange-alt"></i>
                                                        @else
                                                            <i class="fa fa-sort-down"></i>
                                                        @endif
                                                    </small>
                                                </td>
                                            @endif
                                            <td>{{ $item->category->name }}</td>
                                            @if ($item->stand)
                                                <td>{{ $item->stand->name }}</td>
                                            @else
                                                <td>S/N</td>
                                            @endif
                                            <td class="text-center">{{ number_format($item->amount, 2, '.', ',') }}</td>
                                        </tr>
                                    @endforeach

                                    @if ($summarys->details->count() > 0)
                                        <tr class="bg-info">
                                            <th colspan="2">

                                            </th>
                                            <th colspan="2" class="text-right">
                                                TOTAL RECIBO:
                                            </th>
                                            <th colspan="2" class="text-center">
                                                S/. {{ number_format($summarys->amount, 2, '.', ',') }}
                                            </th>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9" class="text-center">No se encontraron registros</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 col-sm-4 col-xs-12 ">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($totalFinal, 2, '.', ',') }}
                    </h3>
                    <p>{{ $divisa }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-money-bill"></i>
                </div>
                <a href="javascript:void(0)" class="small-box-footer">Balance Actual <i
                        class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-md-5 col-sm-2">

        </div>

        <div class="col-md-4 col-sm-6 col-xs-12  float-right ">
            {{-- <div class="info-box ">
                <span class="info-box-icon"><i class="fa fa-credit-card"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Balance de Impuestos</span>
                    <span class="info-box-number" style="color: darkgreen;">+
                        {{ number_format($totalEgresosTx, 2, '.', ',') }}</span>

                    <div class="progress">
                        <div class="progress-bar" style="width: 0%">

                        </div>
                    </div>
                    <span class="progress-description">No deducibles:
                        <span style="color: red;">
                            {{ number_format($totalIngresosTx, 2, '.', ',') }}</span>
                    </span>
                </div>
            </div> --}}
            @php
                $url = '?tipo=' . $tipo . '&categoria=' . $categoria_id . '&start=' . $start . '&finish=' . $finish . '';
            @endphp
            {{-- <a target="_blank" href="{{ url('admin/export/pdf/'.$url) }}"
                class="btn btn-block btn-danger" {{$summaries->count() > 0 ? '' : 'disabled'}}>
                <i class="fa fa-file-pdf" ></i> Reporte PDF
            </a> --}}
            <button type="button" class="btn btn-block btn-danger" wire:loading.attr='disabled'
                onclick="printJS({
                printable: 'printJS-view', type: 'html', header: 'Reporte General - CC5 del {{ $start }} - {{ $finish }}',
                headerStyle: 'color: black;  border: 2px solid black; background-color: orange; font-size: 20px; font-weight: bold; text-align: center',
                style: 'table {border-collapse: collapse;} table tr td {border: 1px solid black; padding: 2px;} thead th {background-color: orange; padding: 2px; border: 1px solid black;} tbody tr th {border: 1px solid black; padding: 2px; background-color: whitesmoke;}'
                })">
                <i class="fa fa-file-pdf"></i> Reporte PDF
            </button>
            {{-- <button class="btn btn-block btn-danger" wire:click='reportePdfGeneral'>
                <i class="fa fa-file-pdf" ></i> Reporte General PDF
            </button> --}}
            {{-- <a target="" href="{{ url('admin/export/excel/'.$url) }}"
                class="btn btn-block btn-success" {{$summaries->count() > 0 ? '' : 'disabled'}}>
                <span class="spinner-grow spinner-grow-sm" wire:loading role="status" aria-hidden="true"></span>
                <i class="fa fa-file-excel" ></i> Reporte Excel
            </a> --}}
        </div>
    </div>
</div>
</div>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
<script></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('error', msg => {
            Swal.fire({
                icon: 'error',
                title: 'Opss...!',
                text: msg,
            });
        });

        window.livewire.on('error_fecha', msg => {
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: msg,
            });
        });

    });
</script>
