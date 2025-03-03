<div class="pt-4">
    <div class="container-fluid">
        <div class="card card-primary">

            <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>

            <div class="card-header">
                <i class="fa fa-bar-chart"></i>
                <h3 class="card-title"><b>Mi Historial de Cobranza</b></h3>
                <div class="card-tools">
                    <div class="btn-group btn-block" role="group" aria-label="">
                        <button type="button" wire:click='EportarRecibos' {{ $totalFinal == 0 ? 'disabled' : '' }}
                            class="btn btn-success"><i class="fa fa-file-excel" aria-hidden="true"></i> Exportar
                            Recibos</button>
                        <button type="button" wire:click='ExportarDetallado' {{ $totalFinal == 0 ? 'disabled' : '' }}
                            class="btn btn-success ml-1"><i class="fa fa-file-excel" aria-hidden="true"></i> Exportar
                            Detallado</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <form action="">
                    <div class="row">
                        <div class="row col-sm-4">
                            <div class="form-group col-sm-6">
                                <input type="date" wire:model.defer='start' name="start" placeholder="Fecha Inicio"
                                    class="form-control form-control-sm">
                            </div>
                            <div class="form-group col-sm-6">
                                <input type="date" wire:model.defer="finish" name="finish" placeholder="Fecha Final"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="row col-sm-8">
                            <div class="col-sm-4">
                                <select class="custom-select custom-select-sm" type="text" wire:model.defer="tipo"
                                    name="tipo">
                                    <option value="">===Tipo de movimiento===</option>
                                    <option value="add">Ingresos</option>
                                    <option value="out">Gastos</option>
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <select class="custom-select custom-select-sm" type="text"
                                    wire:model.defer="account_id" name="cuenta">
                                    <option value="">===Cuenta===</option>
                                    @foreach ($accounts as $name => $id)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-4">
                                <div class="btn-group btn-block" role="group" aria-label="">
                                    <button type="submit" class="btn btn-sm  btn-info" wire:click.prevent='Filter'><i
                                            class="fas fa-filter"></i></button>
                                    <button type="submit" class="btn btn-sm  btn-warning"
                                        wire:click.prevent='clearFilter'><i class="fas fa-broom"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row mt-2">
                    <div class="col-sm-12 table-responsive">
                        <table id="summary" class="table table-bordered table-sm table-hover" style="width:100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th>RECIBO</th>
                                    <th>FECHA</th>
                                    <th>TIPO</th>
                                    <th>MONTO</th>
                                    <th width="15%">Acción</th>
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
                                            <td>{{ number_format($summarys->amount, 2, '.', ',') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('movimientos.a4.recibo', $summarys->id) }}"
                                                    target="_blank" class="btn btn-sm btn-danger"><i
                                                        class="fa fa-print"></i></a>
                                                <a href="{{ route('movimientos.cc5.recibo', $summarys->id) }}"
                                                    target="_blank" class="btn btn-sm btn-primary">RPT</a>
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
                        <p>S/.</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-money-bill"></i>
                    </div>
                    <a href="javascript:void(0)" class="small-box-footer">Balance Actual <i
                            class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
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
