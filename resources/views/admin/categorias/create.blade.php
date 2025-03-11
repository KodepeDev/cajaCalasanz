@extends('adminlte::page')
@section('title', 'Categorias Crear')

@section('css')

@stop

@section('content_header')
    <h1>Crear Categorias</h1>
@stop

@section('content')
    <form role="form" action="/admin/categories/store" method="POST">
        @csrf
        @method('POST')
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Nueva Categoría (Concepto)</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Nombre de la categoria</label>
                                <input type="text" required maxlength="200" name="name" class="form-control"
                                    placeholder="Nombre de la categoria">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Tipo de Categoria</label>
                                <select name="type" class="form-control">
                                    <option value="add">Categoria de Ingreso</option>
                                    <option value="out">Categoria de Gasto</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="">Descripción</label>
                                <textarea class="form-control" name="description" id="" cols="30" rows="5"
                                    placeholder="descripción de la categoria" maxlength="200"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-warning">Guardar</button>
            </div>
            <!-- /.card-footer -->
        </div>
    </form>

@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop
