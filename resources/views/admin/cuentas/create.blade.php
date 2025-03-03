@extends('adminlte::page')
@section('title', 'Crear cuentas')

@section('css')

@stop

@section('content_header')
    <h1>Crear cuenta</h1>
@stop

@section('content')
    <form role="form" action = "{{ route('account.save') }}" method = "POST">
        @method('POST')
        @csrf
        <div class="card card-danger">
            <div class="card-header with-border">
                <i class="fa fa-bank"></i>
                <h3 class="card-title">Crear Cuenta</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->

            <div class="card-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="exampleInputEmail1">Nombre de la cuenta</label>
                        <input type="text" name="name" value="{{ old('name') }}" required maxlength="200"
                            class="form-control" placeholder="Nombre de la cuenta">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="exampleInputPassword1">Numero de cuenta</label>
                        <input name="number" type="number" value="{{ old('number') }}" maxlength="200"
                            class="form-control" placeholder="Numero de cuenta">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="add_serie">SERIE INGRESO</label>
                        <input name="add_serie" type="text" maxlength="200" class="form-control"
                            placeholder="Numero de cuenta">
                        @error('add_serie')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group col-md-3">
                        <label for="out_serie">SERIE GASTO</label>
                        <input name="out_serie" type="text" maxlength="200" class="form-control"
                            placeholder="Numero de cuenta">
                        @error('out_serie')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group col-md-12">
                        <label for="type">Tipo de Cuenta</label>

                        <select class="form-control" required name="type">

                            <option value="corriente">
                                Corriente
                            </option>
                            <option value="ahorro">
                                Ahorro
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-info">Guardar</button>
                <a href="{{ route('account.index') }}" class="btn btn-warning">Cancelar</a>
            </div>
        </div>
    </form>
@stop

@section('js')

@stop
