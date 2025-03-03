@extends('adminlte::page')
@section('title', 'Detalle de la cuenta')

@section('css')

@stop

@section('content_header')
    <h1>Detalle de la cuenta</h1>
@stop

@section('content')

    <div class="container-fluid">
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title"><b>Movimientos de: {{ $nombre->account_name }}</b> <i class="fas fa-chart-line"></i>
                </h3>
                <div class="card-tools">
                    <button onclick="window.history.back()" class="btn btn-info">Regresar</button>
                </div>
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-sm-12 add_top_10">

                        <form action="{{ route('account.show', $id) }}" method = "get">
                            <div class="row">
                                <div class="form-group col-sm-5">
                                    <input type="date" name="start" placeholder="Fecha Inicio" class="form-control">
                                </div>
                                <div class="form-group col-sm-5">
                                    <input type="date" name="finish" placeholder="Fecha Final" class="form-control">
                                </div>
                                <div class="form-group col-sm-2 text-right">
                                    <button type="submit" class="btn btn-block  btn-warning"><i
                                            class="fa fa-filter"></i>Filtrar</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="">

                    <table id="summary" class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Fecha de creación</th>
                                <th>Tipo</th>
                                <th>Monto</th>
                                <th>Impuesto</th>
                                <th>Motivo</th>

                                <th>Categorias</th>
                                <th class="text-center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($summary as $summarys)
                                <tr>
                                    <td>{{ $summarys->id }}</td>
                                    @if ($summarys->date)
                                        <?php $datef = date_create($summarys->date);
                                        $fecha = date_format($datef, 'd-m-Y ');
                                        ?>
                                    @endif
                                    <td>{{ $fecha }}</td>
                                    <!-- <td>{{ $summarys->date }}</td> -->
                                    @if ($summarys->type == 'add')
                                        <td>Abono <small class="badge badge-success bordered float-right"><i
                                                    class="fa fa-sort-up"></i></small></td>
                                    @else
                                        <td>Retiro <small class="badge badge-danger bordered float-right"><i
                                                    class="fa fa-sort-down"></i></small></td>
                                    @endif
                                    <td>{{ number_format($summarys->amount, 2) }}</td>
                                    <td>{{ number_format($summarys->tax, 2) }} </td>
                                    <td>{{ $summarys->concept }}</td>

                                    <td>{{ $summarys->name_categories }}</td>
                                    <td class="text-center">
                                        <form role="form" action = "/summary/eliminar/{{ $summarys->id }}" method="post"
                                            enctype="multipart/form-data">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                            <a class="btn btn-sm btn-info" href="/detalle/detalle/{{ $summarys->id }}"><i
                                                    class="fa fa fa-eye"></i></a>
                                            @if ($summarys->attached)
                                                <a class="btn btn-sm btn-primary" target="_blank"
                                                    href="/download/{{ $summarys->attached->id }}"><i
                                                        class="fa fa-paperclip"></i></a>
                                            @endif


                                            <a class="btn btn-sm btn-primary" href="/summary/edit/{{ $summarys->id }}"><i
                                                    class="fa fa-edit"></i></a>
                                            <button onclick='if(confirmDel() == false){return false;}'
                                                class="btn btn-sm btn-danger" type="submit"><i
                                                    class="fa fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12 ">

            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($totalf, 2, '.', ',') }}</h3>

                    <p>{{ $divisa->value }}</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <label class="small-box-footer">
                    Saldo Total
                </label>
            </div>
        </div>
    </div>
@stop

@section('js')

    {{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#summary').DataTable({
                "order":[[0,"desc"]],
                "dom": "<'row'<'col-sm-10 'f><'col-sm-2  hidden-xs'B>>t<'bottom 'p>",
                //"lengthChange": true,
                "responsive": false,
                buttons: [
                    'pdfHtml5',
                    'csvHtml5',
                ]
        });
    });
</script> --}}

@stop
