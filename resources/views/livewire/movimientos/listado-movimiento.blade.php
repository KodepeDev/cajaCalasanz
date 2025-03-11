<div class="pt-4">
    <div class="container-fluid">
        <div class="card card-maroon">

            <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>

            <div class="card-header">
                <i class="fa fa-bar-chart"></i>
                <h3 class="card-title"><b>RECIBOS GENERADOS</b></h3>
                <div class="card-tools">
                    <a class="btn btn-warning" href="{{ route('movimientos.crear') }}"> <i class="fa fa-plus"></i>
                        NUEVO</a>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="row col-sm-3">
                        <div class="form-group col-sm-6">
                            <input type="date" wire:model.defer='start1' name="start" placeholder="Fecha Inicio"
                                class="form-control form-control-sm">
                        </div>
                        <div class="form-group col-sm-6">
                            <input type="date" wire:model.defer="finish1" name="finish" placeholder="Fecha Final"
                                class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="row col-sm-9">
                        <div class="col-sm-2">
                            <select class="custom-select custom-select-sm" type="text" wire:model.defer="tipo1"
                                name="tipo">
                                <option value="">===Tipo de movimiento===</option>
                                <option value="add">Ingresos</option>
                                <option value="out">Gastos</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select class="custom-select custom-select-sm" type="text" wire:model.defer="cuenta_id1"
                                name="cuentas">
                                <option value="">===Cuentas===</option>
                                @foreach ($accounts as $datas)
                                    <option value="{{ $datas->id }}">{{ $datas->account_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" wire:model.defer="documento1" class="form-control form-control-sm"
                                placeholder="documento">
                        </div>
                        {{-- <div class="col-sm-2">
                                <select class="custom-select custom-select-sm" name="categoria" wire:model="categoria_id1" id="categorie_select">
                                    <option value="">===Categorias===</option>
                                    @foreach ($categories as $datas2)
                                        @if ($datas2->id != 1)
                                            <option class="attr-{{$datas2->type}}" value="{{ $datas2->id }}">
                                                {{ $datas2->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>

                                <div id="modalSubcategorias" class="modal">
                                    <div class="modal-dialog">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" id="closemodal" class="close"
                                                        data-dismiss="modal">&times;
                                                </button>
                                                <h4 class="modal-title">Subcategorias</h4>
                                            </div>
                                            <div class="modal-body" id="res_ajax">
                                            </div>
                                            <div class="modal-footer">
                                                <button style=" margin: 15px;" type="button"
                                                        id="closemodal3" class="btn btn-default"
                                                        data-dismiss="modal">Ok
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}

                        <div class="col-sm-3">
                            <div class="btn-group btn-block" role="group" aria-label="">
                                <button type="submit" class="btn btn-sm  btn-info" wire:click.prevent='Filter'><i
                                        class="fas fa-filter"></i></button>
                                <button type="submit" class="btn btn-sm  btn-warning"
                                    wire:click.prevent='clearFilter'><i class="fas fa-broom"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-sm-12 table-responsive">
                        <table id="summary" class="table table-bordered table-hover" style="width:100%">
                            <thead class="bg-primary text-center">
                                <tr>
                                    <th>RECIBO</th>
                                    <th>FECHA E.</th>
                                    <th>TIPO</th>
                                    <th>CUENTA</th>
                                    <th>MONTO</th>
                                    <th width="15%">ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($summaries->count() > 0)
                                    @foreach ($summaries as $summarys)
                                        <tr class="{{ $summarys->status == 'NULLED' ? 'table-danger' : '' }}">
                                            <td>{{ $summarys->recipt_series }} - {{ $summarys->recipt_number }}</td>
                                            <td>{{ $summarys->date->format('d-m-Y') }}</td>
                                            @if ($summarys->type == 'add')
                                                <td>Ingreso
                                                    <small class="badge badge-success bordered float-right mt-1">
                                                        @if ($summarys->id_transfer != '')
                                                            <i class="fas fa-exchange-alt"></i>
                                                        @else
                                                            <i class="fa fa-sort-up"></i>
                                                        @endif
                                                    </small>
                                                </td>
                                            @elseif($summarys->type == 'out')
                                                <td>Gasto
                                                    <small class="badge badge-danger bordered float-right mt-1">
                                                        @if ($summarys->id_transfer != '')
                                                            <i class="fas fa-exchange-alt"></i>
                                                        @else
                                                            <i class="fa fa-sort-down"></i>
                                                        @endif
                                                    </small>
                                                </td>
                                            @endif
                                            <td>{{ $summarys->account->account_name }}</td>
                                            <td>S/. {{ number_format($summarys->amount, 2, '.', ',') }}</td>
                                            <td class="text-center">

                                                <a class="btn btn-sm btn-success"
                                                    href="{{ route('movimientos.ver', $summarys->id) }}"><i
                                                        class="fa fa fa-eye"></i></a>

                                                @if ($summarys->type == 'out' and $summarys->status == 'PAID')
                                                    <a class="btn btn-sm btn-warning"
                                                        href="{{ route('movimientos.editar', $summarys->id) }}"><i
                                                            class="fa fa-edit"></i></a>
                                                @endif

                                                <a href="{{ route('movimientos.a4.recibo', $summarys->id) }}"
                                                    target="_blank" class="btn btn-sm btn-danger"><i
                                                        class="fa fa-print"></i></a>

                                                {{-- @if ($summarys->type == 'out' and $summarys->status == 'PAID' and $summarys->date >= Carbon\Carbon::now()->subDays(3))
                                                    <button wire:click="anular({{ $summarys->id }})"
                                                        class="btn btn-sm btn-danger">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                @endif --}}

                                                {{-- @if ($summarys->type == 'add' and $summarys->status == 'PAID')
                                                    <a title="Recibo de Caja"
                                                        href="{{ route('movimientos.cc5.recibo', $summarys->id) }}"
                                                        target="_blank" class="btn btn-sm btn-primary">
                                                        <i class="fa fa-file" aria-hidden="true"></i>
                                                    </a>
                                                @endif --}}
                                                {{-- <button type="button" onclick="printJS({printable:'{{route('movimientos.ticket.recibo', $summarys->id)}}', type:'pdf', showModal:true})">
                                                            Print PDF with Message
                                                        </button> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="9" class="text-center">No se encontraron registros</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        {{ $summaries->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 col-sm-4 col-xs-12 ">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($totalFinal, 2, '.', ',') }}</h3>
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

            {{-- <div class="col-md-4 col-sm-6 col-xs-12  float-right ">
                <div class="info-box ">
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
                            <span style="color: red;"> {{ number_format($totalIngresosTx, 2, '.', ',') }}</span>
                        </span>
                    </div>
                </div>
                @php
                    $url =
                        '?tipo=' .
                        $tipo .
                        '&cuentas=' .
                        $cuenta_id .
                        '&documento=' .
                        $documento .
                        '&categoria=' .
                        $categoria_id .
                        '&start=' .
                        $start .
                        '&finish=' .
                        $finish .
                        '';
                @endphp
                <a target="_blank" href="{{ url('admin/movimientos/reportePDF/' . $url) }}"
                    class="btn btn-block btn-danger">
                    <i class="fa fa-file-pdf"></i> Reporte Genérico
                </a>
                <a target="_blank" href="{{ url('admin/movimientos/conceptosPDF/' . $url) }}"
                    class="btn btn-block btn-danger">
                    <i class="fa fa-file-pdf"></i> Reporte Detallado
                </a>
            </div> --}}
        </div>
    </div>

    @livewire('movimientos.anular-movimiento')

</div>
<script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
<link rel="stylesheet" href="https://printjs-4de6.kxcdn.com/print.min.css">
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
        window.livewire.on('movimiento_anulado', msg => {
            Swal.fire({
                icon: 'success',
                title: 'Correcto!',
                text: msg,
            });
            $('#modalAnularRegistro').modal('hide');
        });

        window.livewire.on('show-modal-anular', msg => {
            $('#modalAnularRegistro').modal('show');
        });

    });
</script>
