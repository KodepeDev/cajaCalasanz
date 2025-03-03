@extends('adminlte::page')
@section('title', 'Categorias')

@section('css')

@stop

@section('content')
<div class="container-fluid spark-screen pt-4">
    <div class="card card-danger">
        <div class="card-header">
            <div class="card-title">
                Categorias / Conceptos
            </div>
            <div class="card-tools">
                <a class="btn btn-warning" href="categories/create"> <i class="fa fa-plus"></i>Nuevo</a>
            </div>
        </div>
        <div class="card-body">
            <div class="">
                <div class="col-sm-12 table-responsive">
                    <table id="categories" class="table table-bordered" cellspacing="0" width="100%">
                        <thead class="bg-danger">
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Tipo</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $categoriess)
                            <tr>
                            <td>{{ $categoriess->id }}</td>
                            <td>{{ $categoriess->name }}</td>
                            <td>{{ $categoriess->description }}</td>
                            @if($categoriess->type=='add' )
                                <td>Categoria de Ingreso</td>
                            @else
                                <td>Categoria de Gasto</td>
                            @endif


                                <td class="text-center">
                                <form role="form" action = "categories/delete/{{ $categoriess->id }}" method="post"  enctype="multipart/form-data">
                                    @method('DELETE')
                                    @csrf
                                    <a class="btn btn-sm btn-default" href="categories/edit/{{ $categoriess->id }}"><i class="fa fa-edit"></i></a>
                                    @if ($categoriess->details->count() < 1)
                                    <button onclick='if(confirmDel() == false){return false;}' class="btn btn-sm btn-default" type="submit"><i class="fa fa-trash"></i></button>
                                    @endif
                                </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
    <script> console.log('Hi!'); </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('secciones de desarrollo');
            $('#categories').DataTable({
                "order":[[0,"asc"]],
                "dom": "Bfrtip",
                "lengthChange": true,
                "responsive": true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.5/i18n/es-ES.json',
                },
                buttons: [
                    'pdf',
                    'excel',
                    'copy',
                ]
            });
        });
    </script>
@stop
