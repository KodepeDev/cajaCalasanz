<div class="container-fluid pt-4">
    <div class="card card-danger">

        <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
            <i class="fas fa-2x fa-sync-alt fa-spin"></i>
        </div>

        <div class="card-header">
            <h3 class="card-title"><b>Movimientos de: {{ $cuenta->account_name }}</b> <i class="fas fa-chart-line"></i>
            </h3>
            <div class="card-tools">
                <button onclick="window.history.back()" class="btn btn-info">Regresar</button>
            </div>
        </div>

        <div class="card-body">

            <div class="row">
                <div class="col-sm-12 add_top_10">

                    <div class="row">
                        <div class="form-group col-sm-5">
                            <input wire:model.defer="start1" type="date" name="start" placeholder="Fecha Inicio"
                                class="form-control">
                        </div>
                        <div class="form-group col-sm-5">
                            <input wire:model.defer="finish1" type="date" name="finish" placeholder="Fecha Final"
                                class="form-control">
                        </div>
                        <div class="form-group col-sm-2 text-right">
                            <div class="btn-group btn-block" role="group" aria-label="">
                                <button type="button" class="btn btn-primary" wire:click='Filter'
                                    class="btn btn-warning"><i class="fa fa-filter"></i></button>
                                <button type="button" wire:click='clearFilter' class="btn btn-warning"><i
                                        class="fas fa-broom"></i></button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="table-responsive">

                <table id="summary" class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Recibo</th>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Monto</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($summary->count() > 0)
                            @foreach ($summary as $summarys)
                                <tr>
                                    <td>{{ $summarys->recipt_series }} - {{ $summarys->recipt_number }}</td>
                                    <td>{{ $summarys->date->format('d-m-Y') }}</td>
                                    @if ($summarys->type == 'add')
                                        <td>Abono
                                            <small class="badge badge-success bordered float-right mt-1">
                                                @if ($summarys->id_transfer != '')
                                                    <i class="fas fa-exchange-alt"></i>
                                                @else
                                                    <i class="fa fa-sort-up"></i>
                                                @endif
                                            </small>
                                        </td>
                                    @elseif($summarys->type == 'out')
                                        <td>Retiro
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

                                        <a class="btn btn-sm btn-success"
                                            href="{{ route('movimientos.ver', $summarys->id) }}"><i
                                                class="fa fa fa-eye"></i></a>
                                        <a href="{{ route('movimientos.a4.recibo', $summarys->id) }}" target="_blank"
                                            class="btn btn-sm btn-danger"><i class="fa fa-print"></i></a>
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
            </div>
            {{ $summary->links() }}
            <!-- /.box-body -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12 ">

            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($totalf, 2, '.', ',') }}</h3>

                    <p>S/.</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <label class="small-box-footer">
                    Saldo Total
                </label>
            </div>
        </div>
        <div class="col-md-6">

        </div>
        <div class="col-md-3">
            @php
                $url =
                    '?tipo=' .
                    '&cuentas=' .
                    $cuenta->id .
                    '&documento=' .
                    '&categoria=' .
                    '&start=' .
                    $start .
                    '&finish=' .
                    $finish .
                    '';
            @endphp
            {{-- <a target="_blank" href="{{url('admin/movimientos/reportePDF/'.$url)}}" class="btn btn-block btn-danger">
                    <i class="fa fa-file-pdf"></i> Reporte Detallado
                </a>
                <a target="_blank" href="{{url('admin/movimientos/conceptosPDF/'.$url)}}" class="btn btn-block btn-danger">
                    <i class="fa fa-file-pdf"></i> Reporte Conceptos
                </a> --}}
        </div>

    </div>
</div>

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
