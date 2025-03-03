@extends('adminlte::page')
@section('title', 'Cuentas')

@section('css')

@stop

@section('content_header')
    <h1>Cuentas</h1>
@stop

@section('content')
    <div class="card card-danger">
        <div class="card-header">
            <h3 class="card-title"><b>Cuentas</b></h3>
            <div class="card-tools">
                <a class="btn btn-primary " href="{{ route('account.create') }}"><i class="fa fa-plus"></i> Nuevo </a>
            </div>
        </div>

        <div class="card-body table-responsive">

            <table id="accounts" class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Numero de cuenta</th>
                        <th>Serie Ingreso</th>
                        <th>Serie Gasto</th>
                        <th>Tipo</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($account as $accounts)
                        <tr>
                            <td>{{ $accounts->id }}</td>
                            <td>{{ $accounts->account_name }}</td>
                            <td>{{ $accounts->account_number }}</td>
                            <td>{{ $accounts->add_serie }}</td>
                            <td>{{ $accounts->out_serie }}</td>
                            <td>{{ $accounts->account_type }}</td>
                            <td class="text-center">

                                <form role="form" action = "{{ route('account.destroy', $accounts->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    {{ method_field('DELETE') }}
                                    {{ csrf_field() }}

                                    <a class="btn btn-sm btn-info" href="{{ route('account.show', $accounts->id) }}"><i
                                            class="fa fa fa-eye"></i></a>
                                    <a class="btn btn-sm btn-primary" href="{{ route('account.edit', $accounts->id) }}"><i
                                            class="fa fa-edit"></i></a>
                                    @if ($accounts->summaries->count() < 1)
                                        <button onclick='if(confirmDel() == false){return false;}'
                                            class="btn btn-sm btn-danger" type="submit"><i
                                                class="fa fa-trash"></i></button></a>
                                    @endif
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#accounts').DataTable({
                "order": [
                    [0, "desc"]
                ],
                "dom": "Bft",
                "lengthChange": true,
                "responsive": false,
                buttons: [
                    'pdf',
                    'excel',
                ]
            });
        });
    </script>
@stop
